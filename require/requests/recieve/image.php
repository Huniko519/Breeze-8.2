<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Import user management class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;

// Check logged user
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
    
	// Pass user properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// If user is logged and exists
	if(!empty($user['idu'])) {

	    if ($_GET['type'] == 'blog-image') {
            
			if(isset($_FILES['file']['name'])) {

				$image_name = $_FILES['file']['name'];     // File name
				$image_size = $_FILES['file']['size'];     // File size
				$image_temp = $_FILES['file']['tmp_name']; // File temp
				$image_type = $_FILES['file']['type'];     // File type
        
				// Check whether file is selected			
        		if(isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
					$image_size_info = getimagesize($image_temp);            // Return true if file is a valid image       
				} else {
					$image_size_info = 0;
				}

				if($image_size_info && $image_size <= $page_settings['max_img_size']*1000000){
			
					$image_width = $image_size_info[0];      // Image width
					$image_height = $image_size_info[1];     // Image height
					$image_type = $image_size_info['mime'];  // Image type 
			
					// Create image from available format
					switch($image_type){
						case 'image/png':
			        		$image_res =  imagecreatefrompng($image_temp); 
							break;
						case 'image/gif':
				    		$image_res =  imagecreatefromgif($image_temp); break;			
						case 'image/jpeg': case 'image/pjpeg':
				    		$image_res = imagecreatefromjpeg($image_temp); break;
						default:
				    		$image_res = false;
					}

					// Fix image orientation if php_exif extensions available
					if(function_exists('exif_read_data')) {
				
						// Get image EXIF data 
						$exif = exif_read_data($image_temp);
		        
						// Check orientation property
						$orientation = (isset($exif['Orientation'])) ? $exif['Orientation'] : '';
				
						// If image has Incorrect orientation then ix it by rotating image
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
			
					// If image is created successfully
					if($image_res){
				
						// Image path
						$image_info = pathinfo($image_name);
				
						// Image extension
						$image_extension = strtolower($image_info["extension"]);
				
						// image uploaded name
						$image_name_only = strtolower($image_info["filename"]);
				
						// Generating a unique name for photo using USER_ID + MD5()->TIME()
						$new_file_name = rand(0, 9999).$user['idu'].md5(time()).'.'.$image_extension; 
						
						// Image full save path
						$image_save_folder 	= '../../../uploads/blogs/inner/'.$new_file_name;	
	
	            		// Save image after series of size and resolution fixes
						if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_main_pics'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
					
							$data = array('location' => $TEXT['installation'].'/uploads/blogs/inner/'.$new_file_name);
					
						} else {
							$return = $TEXT['_uni-ERROR_WHILE_UPLOADING'];
						}
				
						// Free memory
						imagedestroy($image_res);
				
					} else {
						// Invalid format
						$return = showError($TEXT['_uni-Photo-invalid']);
					}		
				} else {
					// Image is not selected or out of size
					$return = ($image_size > $page_settings['max_img_size']*1000000) ? showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_img_size'])) : showError($TEXT['_uni-Photo-not_selected']);	
				}

            } else {
				$return = 'No Images set';
			}
			
			if(!isset($data)) {
				$data = array(
					'Error' => $db->real_escape_string($return),
				);
			}

			header("Content-type: application/json");
			
			echo json_encode($data);

			if(isset($db) && $db) {
				mysqli_close($db);
			}
			
            exit();
			
        }

	}
}
?>