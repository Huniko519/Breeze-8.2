<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/admin.php');            // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New administration class
$profile = new manage();
$profile->db = $db;	
$updated = 0;

// Verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged display sponsor settings
	if(!empty($admin['id'])) {	
	
		$image_name = $_FILES['add_background_file']['name'];     // File name
		$image_size = $_FILES['add_background_file']['size'];     // File size
		$image_temp = $_FILES['add_background_file']['tmp_name']; // File temp

        // Check whether file is selected			
        if(isset($_FILES['add_background_file']) && is_uploaded_file($_FILES['add_background_file']['tmp_name'])){
			$image_size_info = getimagesize($image_temp);            // Return true if file is a valid image       
		} else {
			$image_size_info = 0;
		}
		
		// Check whether file is processed
		if($image_size_info && $image_size <= $page_settings['max_img_size']*1000000){
			
			$image_width = $image_size_info[0];      // Image width
			$image_height = $image_size_info[1];     // Image height
			$image_type = $image_size_info['mime'];  // Image type 
			
			// Create image from available format
			switch($image_type){		
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

				// Count backrounds
				$use_count = glob('../../../uploads/posts/backgrounds/' . "*.[jJ][pP]{[eE],}[gG]", GLOB_BRACE);
	
				// Generating a unique name for photo using USER_ID + MD5()->TIME()
				$new_file_name = 'n-'.(count($use_count)+1).'.jpg'; 
				
				// Image full save path
				$image_save_folder = '../../../uploads/posts/backgrounds/' . $new_file_name;	
	
	            // Save image after series of size and resolution fixes
				if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_chat_icons'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
					
					// Update chat icon
					$return = '';
					$updated = 1;
				
				} else {
					$return = $TEXT['_uni-ERROR_WHILE_UPLOADING'];
				}
				
				// Free memory
				imagedestroy($image_res);
				
			} else {
				// Invalid format
				$return = showError($TEXT['_uni-Photo-invalid-oe_of2']);
			}		
		} else {
			// Image is not selected or out of size
			$return = ($image_size > $page_settings['max_img_size']*2000000) ? showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_img_size'])) : showError($TEXT['_uni-Photo-not_selected']);	
		}
	} else {
		// User logged out
		$return = showError($TEXT['lang_error_connection2']);
	}
} else {
	// No credentials set
	$return = showError($TEXT['lang_error_connection2']);
}
?>
<?php if(isset($return)) { ?>
<script language="javascript" type="text/javascript">
	window.top.window.addedBackground('<?php echo $db->real_escape_string($return); ?>','<?php echo $updated ?>');
</script>
<?php }
mysqli_close($db);
?>