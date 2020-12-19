<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
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

	if(!empty($user['idu'])) {
 	    
		// Needs to fetch post after post has been posted
		$profile->followings = $profile->listFollowings($user['idu']);
		
		$grouped = $group = $youtube_share = $status_update = 0;
		$error = NULL;
		
		// Form validation	
		$message = trim($_POST['update-status-form-text']);           // Message

		// Custom security check
        $error = (isXSSED($message)) ? showError($TEXT['_uni-P-xss']) : 0;

		// image count
		$images_count = count($_FILES['update-status-form-file']['name']);

		// If posting on group
		if($_POST['GROUP_ID']) {

			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['GROUP_ID']);
		
			// Get group
			$group = $profile->getGroup($_POST['GROUP_ID']);
	
			// Confirm group privacy
			if($group_user['group_status'] == 1 && $group_user['p_post'] == 0 && ($group_user['group_role'] == 2 || $group['group_posting'] == 1)) {
				$grouped = 1;
			} else {
				$error = showError($TEXT['_uni-post_rights_group_fail']);
			}
		}
		
		// If posting on a page
		if($_POST['PAGE_ID']) {

			// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['PAGE_ID']);
		
			// Get page
			$group = $profile->getPage($_POST['PAGE_ID']);
	
			// Confirm group privacy
			if($page_role['page_role'] > 3) {
				$paged = 1;
			} else {
				$error = showError($TEXT['_uni-post_rights_page_fail']);
			}
		}
		

		// First check whether photos added
		if(empty($_FILES['update-status-form-file']['name'][0])) {
			
			// If message empty show error empty post
			if(empty($message)) {
				$error = showError($TEXT['_uni-P-empty']);	
			} else {
				
				// Check for Facebook Video link
				//preg_match('~(?:https?://)?(?:www.)?(?:facebook.com)/(?:video\.php\?v=\d+|.*?/videos/\d+)~', $message, $facebook_link);
				preg_match('~(?:https?://)?(?:www.)?(?:facebook.com)/(?:video\.php\?v=\d+|.*?/videos/\d+)~', $message, $facebook_link);
				
				
				
				if(!empty($facebook_link)) {
					
					$facebook_linked = 1;
					
				} else {
				
					// Check whether youtube link added
					preg_match('~(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?([^\s]+)~', $message, $youtube_link);
            	
					if(!empty($youtube_link)) {
						$youtube_share = 1;
					} else {
					
						// Check for soundcloud link
						preg_match('~(?:https?://)?(?:www.)?(?:soundcloud.com)/([\-a-z0-9_]+/[\-a-z0-9_]+)~', $message, $track_link);

						if(!empty($track_link)) {
							$track_share = 1;
						} else {
						
							// Check for Dailymotion link
							preg_match('~(?:https?://)?(?:www.)?(?:dailymotion.com)/video/([\-a-z0-9_]+)~', $message, $dailymotion_link);

							if(!empty($dailymotion_link)) {
								$dailymotion_share = 1;
							} else {
							
								// Check for Vimeo link
								preg_match('~(?:https?://)?(?:www.)?(?:vimeo.com)/channels/([\-a-z0-9_]+/[\-a-z0-9_]+)~', $message, $vimeo_link);
								
								if(!empty($vimeo_link)) {
									$vimeo_share = 1;
								} else {
									$status_update = 1;
								}
							}
							
						} 
					}
				}
			}
		}

		// Insert feeling and location if not group
		if(!$paged && !$grouped) {
			
			// Add location
			$add_checkin = (!empty($_POST['post-status-checkin-input-value'])) ? protectInput($_POST['post-status-checkin-input-value']) : '0';
	    
			// Add feeling
			if(!empty($_POST['post-status-feeling-input-value']) && !empty($_POST['post-status-feeling-input-type'])) {
			
				// Import feelings presets
				require_once('../../main/presets/post_extras.php');

				$add_feeling = (isset($feeling_available[$_POST['post-status-feeling-input-type']])) ? protectInput($feeling_available[$_POST['post-status-feeling-input-type']].','.fixText(20,$_POST['post-status-feeling-input-value'].',')) : '0,0,';
	
			} else {
				
				$add_feeling = '0,0,';
			}
			
			// Combine feeling and checkin
		    $link_post_extras = $add_feeling.$add_checkin;
		} else {
			$link_post_extras = '0,0,0';
		}
		
		if(!$error && $images_count < $page_settings['MAX_IMAGES'] + 1) {
		
			// Validate and save each image
        	foreach($_FILES["update-status-form-file"]["tmp_name"] as $key=>$tmp_name){
			 
				// if error occured stop saving images
			    if($error || $status_update || $youtube_share || $track_share || $dailymotion_share || $vimeo_share || $facebook_linked) {
					break;
				}
				
				// Get image size
        		if(isset($_FILES['update-status-form-file']) && is_uploaded_file($_FILES['update-status-form-file']['tmp_name'][$key])){
					$image_size_info = getimagesize($_FILES['update-status-form-file']['tmp_name'][$key]);            // Return true if file is a valid image       
				} else {
					$image_size_info = 0;
				}
			
				// Create a image valid type
				if($image_size_info && $error == 0 && $_FILES['update-status-form-file']['size'][$key] <= $page_settings['max_img_size']*1000000) {
					
					$image_width 		= $image_size_info[0];      // Image width
					$image_height 		= $image_size_info[1];      // Image height
					$image_type 		= $image_size_info['mime']; // Image type 
					
					switch($image_type){
						case 'image/png':
			        		$image_res =  imagecreatefrompng($_FILES['update-status-form-file']['tmp_name'][$key]); break;
						case 'image/gif':
				    		$image_res =  imagecreatefromgif($_FILES['update-status-form-file']['tmp_name'][$key]); break;			
						case 'image/jpeg': case 'image/pjpeg':
				    		$image_res = imagecreatefromjpeg($_FILES['update-status-form-file']['tmp_name'][$key]); break;
						default:
				    		$image_res = false;
					}
					
					// Fix image orientation if php_exif extension is available
					if(function_exists('exif_read_data')) {
				
						$exif = exif_read_data($_FILES['update-status-form-file']['tmp_name'][$key]);
		        
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
						$image_extension = pathinfo($_FILES['update-status-form-file']['name'][$key], PATHINFO_EXTENSION);

						// Generate a unique name for photo using USER_ID + MD5()->TIME()
						$new_file_name = rand(0, 99999).$user['idu'].md5(time()).'.'.$image_extension; 
				
						// Image to be saved full path
						$image_save_folder 	= $page_settings['folder_post_photos'] . $new_file_name;	

						// Save and add image name in array
				    	if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_image_size'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
					    	$images_uploaded[] = $new_file_name;
				    	} else {
					   		$error = $TEXT['_uni-ERROR_WHILE_UPLOADING'];
				    	}				

						imagedestroy($image_res);
				
					} else {
						$error = showError($TEXT['_uni-Photo-invalid-single']);
					}
					
				
				// Failed to save image
				} else {
                   
				    $count_error = ($images_count > 1) ? showError($TEXT['_uni-Photo-invalid-oe_of']) : showError($TEXT['_uni-Photo-invalid-single']);
					
					// Set error whether image size is larger than allowed or image is not selected
					$error = ($_FILES['update-status-form-file']['size'][$key] > $page_settings['max_img_size']*1000000) ? showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_img_size'])) : $count_error;

				}
			
			// Image processing finished
			}
			
			
			// Confirm post
			if(!$error) {
				
				// Set post type and content
				if(isset($images_uploaded) && !empty($images_uploaded)){
				
				    $post_type = array("1",NULL,"1","1");
					$p_content = implode(',',$images_uploaded);

				} elseif($facebook_linked) {
					
					$post_type = array("13",NULL,NULL,NULL);
					
					if (!preg_match("~^(?:f|ht)tps?://~i", $facebook_link[0])) $facebook_link[0] = "https://" . $facebook_link[0];
	
					$p_content = $facebook_link[0];			
	
				} elseif($youtube_share) {
					
					$link_parts = explode('&',$youtube_link[1]);
					
					$post_type = array("4",NULL,NULL,NULL);
					$p_content = 'https://www.youtube.com/embed/'.$link_parts[0];		    
	
				} elseif($track_share) {
					
					$post_type = array("7",NULL,NULL,NULL);
					$p_content = 'https://soundcloud.com/'.$track_link[1];		    
	
				} elseif($dailymotion_share) {
					
					$post_type = array("8",NULL,NULL,NULL);
					$p_content = 'https://www.dailymotion.com/video/'.$dailymotion_link[1];		    
	
				} elseif($vimeo_share) {
					
					$post_type = array("9",NULL,NULL,NULL);
					$p_content = 'https://vimeo.com/channels/'.$vimeo_link[1];		    
	
				} else {
					
					$post_type = array("0",NULL,NULL,NULL);
					$p_content = (($_POST['update-status-emoji-target'] == '#update-status-form-btext-viewable') && in_array($_POST['update-status-emoji-target-back'],explode(',',$TEXT['ACTIVE_BACKGROUNDS']))) ? $_POST['update-status-emoji-target-back'] : '';

				}
				
				// Create post
				$post = $profile->postStatus($user,$p_content,$message,$link_post_extras,$group,$post_type);
				
				$performed = 1;
				
			// Show error if occured in uploading images
			} else {
				
				// if error occured saving images
			    if($error) {
					// Delete uploaded images if error occured
				    if(isset($images_uploaded) && !empty($images_uploaded)){						
					    foreach($images_uploaded as $delete_image){
							unlink($page_settings['folder_post_photos'].$delete_image);
					    }
				    }
				}
				
				$post = $error;			
			}
	
		// Post text includes restricted characters
		} else {
			$post = ($images_count < $page_settings['MAX_IMAGES'] + 1) ? $error : showError(sprintf($TEXT['_uni-Images_selection_exceed'],$page_settings['MAX_IMAGES']));
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
<?php if($performed == 1) { ?>
<script language="javascript" type="text/javascript">
	window.top.window.resetElement('#update-status-form-submit','<?php echo $TEXT['_uni-Make_post']; ?>');
	window.top.window.statusUpdated('<?php echo $db->real_escape_string($post); ?>');
	window.top.window.refreshElements();
	window.top.window.resetForm('update-status-form-data');
	window.top.window.resetPostform();
    window.top.window.resetElement('#post-file-1','<img class="nav-item-inverse brz-img-add-photo" alt="" src="<?php echo $TEXT['DATA-IMG-12']; ?>"></img>&nbsp;<?php echo $TEXT['_uni-Add_photo']; ?>');		
</script>
<?php } else { ?>
<script language="javascript" type="text/javascript">
	window.top.window.resetElement('#update-status-form-submit','<?php echo $TEXT['_uni-Make_post']; ?>');
	window.top.window.statusUpdated('<?php echo $db->real_escape_string($post); ?>');
	window.top.window.resetElement('#post-file-1','<img class="nav-item-inverse brz-img-add-photo" alt="" src="<?php echo $TEXT['DATA-IMG-12']; ?>"></img>&nbsp;<?php echo $TEXT['_uni-Add_photo']; ?>');		
</script>
<?php } 
mysqli_close($db);
?>