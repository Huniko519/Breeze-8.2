<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/processors/video.php'); // Import video processing functions
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Fetch logged user
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	$user = $profile->getUser();
    $performed = 0;
	
	if(!empty($user['idu'])) {
 	    
		// Needs to fetch post after post has been posted
		$profile->followings = $profile->listFollowings($user['idu']);
		
		$error = NULL;
		
		// Form validation	
		$heading = trim($_POST['video-form-heading']);
		$description = trim($_POST['video-form-description']);
		$tags = trim($_POST['video-form-tags']);
		
		if(empty($heading) || empty($description)) $error = showError($TEXT['_uni-signup-7']);

		// CHeck major data
		if(strlen($description) > 250 || strlen($description) < 20) $error = showError(sprintf($TEXT['_uni-Video-error-6'],20,250));

		// Check files
		if(empty($_FILES['video-form-file']['name'][0])) $error = showError($TEXT['_uni-Video-not_selected']);	
		if(empty($_FILES['video-photo-form-file']['name'][0])) $error = showError($TEXT['_uni-Video_cover-not_selected']);	

		// Parse tags
		if(strlen($tags) > 250) {
			$error = showError($TEXT['_uni-Blog-error-4']);
		} else {
			$split = explode(',',$tags);
			if(count($split) > 8) {
				$error = showError($TEXT['_uni-Blog-error-5']);
			} else {
				$tags = array();
				foreach($split as $tag) {
					if(!empty(trim($tag))) {
						$tags[] = fixText(15,$tag);
					}
				}
				$tags = implode(',',$tags);
			}
		}

		if(!$error) {
		
			// Get image size
        	if(isset($_FILES['video-photo-form-file']) && is_uploaded_file($_FILES['video-photo-form-file']['tmp_name'])){
				$image_size_info = getimagesize($_FILES['video-photo-form-file']['tmp_name']);            // Return true if file is a valid image       
			} else {
				$image_size_info = 0;
			}
			
			
			
				// Create a image valid type
				if($image_size_info && !$error && $_FILES['video-photo-form-file']['size'] <= $page_settings['max_vid_size']*1000000) {
					
					$image_width 		= $image_size_info[0];      // Image width
					$image_height 		= $image_size_info[1];      // Image height
					$image_type 		= $image_size_info['mime']; // Image type 
					
					switch($image_type){
						case 'image/png':
			        		$image_res =  imagecreatefrompng($_FILES['video-photo-form-file']['tmp_name']); break;
						case 'image/gif':
				    		$image_res =  imagecreatefromgif($_FILES['video-photo-form-file']['tmp_name']); break;			
						case 'image/jpeg': case 'image/pjpeg':
				    		$image_res = imagecreatefromjpeg($_FILES['video-photo-form-file']['tmp_name']); break;
						default:
				    		$image_res = false;
					}
					
					// Fix image orientation if php_exif extension is available
					if(function_exists('exif_read_data')) {
				
						$exif = exif_read_data($_FILES['video-photo-form-file']['tmp_name']);
		        
						$orientation = (isset($exif['Orientation'])) ? $exif['Orientation'] : '';
				
						// if image is not correct then rotate
						if(!empty($orientation) && in_array($orientation, array(3, 6, 8))) {
							if($orientation == 3) {
								$image_res = imagerotate($image_res, 180, 0);
							} elseif($orientation == 6) {
								$image_res = imagerotate($image_res, -90, 0);
							} elseif($orientation == 8) {
								$image_res = imagerotate($image_res, 90, 0);
							}						
						}
	
					}
					
					if($image_res) { 
				
						// Get image extension
						$image_extension = pathinfo($_FILES['video-photo-form-file']['name'], PATHINFO_EXTENSION);

						// Generate a unique name for photo using USER_ID + MD5()->TIME()
						$new_file_name = rand(0, 99999).$user['idu'].md5(time()); 
				
						// Image to be saved full path
						$image_save_folder 	= '../../../uploads/posts/videos/photos/' . $new_file_name.'.'.$image_extension;	

						// Save and add image name in array
				    	if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_image_size'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
				
							// video cover uploaded now process video
							
							$video_extension = pathinfo($_FILES['video-form-file']['name'], PATHINFO_EXTENSION);
							
							// Check Mime type
							if(function_exists('mime_content_type')) {
								$mime = mime_content_type($_FILES['video-form-file']['tmp_name']);
								
								$valid = ($mime == 'video/x-ms-wmv' || $mime == 'application/x-mpegURL' || $mime == 'video/quicktime' || $mime == 'video/x-msvideo' || $mime == 'video/3gpp' || $mime == 'video/x-flv' || $mime == 'video/mp4') ? 1 : 0;
		
							} else {
								$valid = 1;
							}
							
							// Check video size
							if($_FILES['video-form-file']['size'] >= $page_settings['max_vid_size']*1000000) {
								$error = showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_vid_size']));
							}
							
							// Further check extension
							if($valid && !$error && in_array($video_extension,explode(',',$page_settings['video_extensions']))) {
								
								// Set destination
								$video_save_folder 	= '../../../uploads/posts/videos/' . $new_file_name.'.'.$video_extension;
								
								// Directly upload the video file
								move_uploaded_file($_FILES['video-form-file']['tmp_name'], $video_save_folder);
				
				                $post_params = array(
								    "name" => $new_file_name.'.'.$video_extension,
								    "img" => $new_file_name.'.'.$image_extension,
								    "title" => $heading,
								    "description" => $description,
								    "tags" => $tags,
								);
								
				                // Insert post
								list($performed,$error) = addVideo($user,$post_params,$db);
				
							} else {
								$error = ($error) ? $error : $error.showError(sprintf($TEXT['_uni-Video-error-10'],$page_settings['video_extensions']));
							}
						
							
				    	} else {
					   		$error = $TEXT['_uni-ERROR_WHILE_UPLOADING'];
				    	}				

						imagedestroy($image_res);
				
					} else {
						$error = showError($TEXT['_uni-Photo-invalid-single']);
					}
					
				// Failed to save image
				} else {
                   
					// Set error whether image size is larger than allowed or image is not selected
					$error = showError(sprintf($TEXT['_uni-Photo-out_of_size'],'IMG:'.$page_settings['max_img_size']));

				}
		
		} 
		
	// wrong credentials
	} else {
		$post = showError($TEXT['lang_error_connection2']);
	}
// user not logged	
} else {
	$post = showError($TEXT['lang_error_connection2']);
}
?>
<?php if($performed) { ?>
<script language="javascript" type="text/javascript">
	window.top.window.locate('<?php echo $db->real_escape_string($performed); ?>');
</script>
<?php } else { ?>
<script language="javascript" type="text/javascript">
	window.top.window.resetElement('#video-form-submit','<?php echo $TEXT['_uni-Publish_it']; ?>');
	window.top.window.uploadVideo(1,'<?php echo $db->real_escape_string($error); ?>');
	window.top.window.resetElement('#blog-file','<?php echo $TEXT['_uni-Add_video']; ?>');		
</script>
<?php } 
mysqli_close($db);
?>