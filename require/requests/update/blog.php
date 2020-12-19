<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/blogs.php');            // Import all classes
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
		
		$all_cats = $profile->getCategories(1);
		
		// Form validation	
		$description = trim($_POST['blog-form-description']);
		$content = trim($_POST['blog-content']);
		$tags = trim($_POST['blog-form-tags']);
		$heading = trim($_POST['blog-form-heading']);
		$type = trim($_POST['blog-form-category']);
		$share_it = trim($_POST['blog-form-share']);

		if(!in_array($type,$all_cats)) {
			$error = showError($TEXT['_uni-Blog-error-6']);
		}
			
		// First check whether photo is added
		if(empty($_FILES['blog-form-file']['name'][0])) {
			$error = showError($TEXT['_uni-Photo-not_selected']);	
		}
		
		// Check for empty fields
		if(empty($description) || empty($content) || empty($tags) || empty($heading)) {
			$error = showError($TEXT['_uni-signup-7']);
		}
		
		if(strlen($description) > 250 || strlen($description) < 50) {
			$error = showError($TEXT['_uni-Blog-error-1']);
		}
		
		if(strlen($heading) > 150 || strlen($heading) < 10) {
			$error = showError($TEXT['_uni-Blog-error-2']);
		}
		
		if(strlen($content) > 50000 || strlen($content) < 10) {
			$error = showError($TEXT['_uni-Blog-error-3']);
		}
		
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
        	if(isset($_FILES['blog-form-file']) && is_uploaded_file($_FILES['blog-form-file']['tmp_name'])){
				$image_size_info = getimagesize($_FILES['blog-form-file']['tmp_name']);            // Return true if file is a valid image       
			} else {
				$image_size_info = 0;
			}
			
				// Create a image valid type
				if($image_size_info && $error == 0 && $_FILES['blog-form-file']['size'] <= $page_settings['max_img_size']*1000000) {
					
					$image_width 		= $image_size_info[0];      // Image width
					$image_height 		= $image_size_info[1];      // Image height
					$image_type 		= $image_size_info['mime']; // Image type 
					
					switch($image_type){
						case 'image/png':
			        		$image_res =  imagecreatefrompng($_FILES['blog-form-file']['tmp_name']); break;
						case 'image/gif':
				    		$image_res =  imagecreatefromgif($_FILES['blog-form-file']['tmp_name']); break;			
						case 'image/jpeg': case 'image/pjpeg':
				    		$image_res = imagecreatefromjpeg($_FILES['blog-form-file']['tmp_name']); break;
						default:
				    		$image_res = false;
					}
					
					// Fix image orientation if php_exif extension is available
					if(function_exists('exif_read_data')) {
				
						$exif = exif_read_data($_FILES['blog-form-file']['tmp_name']);
		        
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
						$image_extension = pathinfo($_FILES['blog-form-file']['name'], PATHINFO_EXTENSION);

						// Generate a unique name for photo using USER_ID + MD5()->TIME()
						$new_file_name = rand(0, 99999).$user['idu'].md5(time()).'.'.$image_extension; 
				
						// Image to be saved full path
						$image_save_folder 	= $page_settings['folder_blog_photos'] . $new_file_name;	

						// Save and add image name in array
				    	if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_image_size'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
				
							list($performed,$error) = $profile->createBlog($user,$heading,$new_file_name,$description,$content,$type,str_replace("'","",$tags),$share_it);
							
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
					$error = showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_img_size']));

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
	window.top.window.resetElement('#blog-form-submit','<?php echo $TEXT['_uni-Publish_it']; ?>');
	window.top.window.createBlog(1,'<?php echo $db->real_escape_string($error); ?>');
	window.top.window.resetElement('#blog-file','<?php echo $TEXT['_uni-Add_photo']; ?>');		
</script>
<?php } 
mysqli_close($db);
?>