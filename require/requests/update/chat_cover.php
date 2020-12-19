<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Import user class
$profile = new main();
$profile->db = $db;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get currently logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])) {	
	
		$image_name = $_FILES['chat-cover-file']['name'];     // File name
		$image_size = $_FILES['chat-cover-file']['size'];     // File size
		$image_temp = $_FILES['chat-cover-file']['tmp_name']; // File temp
	    	
        // Check whether file is selected			
        if(isset($_FILES['chat-cover-file']) && is_uploaded_file($_FILES['chat-cover-file']['tmp_name'])){
			$image_size_info = getimagesize($image_temp);            // Return true if file is a valid image       
		} else {
			$image_size_info = 0;
		}
		
		// Check whether file is processed
		if($image_size_info && $image_size <= $page_settings['max_img_size']*1000000){
			
			$image_width 		= $image_size_info[0];      // Image width
			$image_height 		= $image_size_info[1];      // Image height
			$image_type 		= $image_size_info['mime']; // Image type 
			
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
				$image_save_folder 	= $page_settings['folder_chat_covers'] . $new_file_name;	
	
	            // Save image after series of size and resolution fixes
				if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_chat_covers'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
					
					// Update chat icon
					list($return,$updated_image) = $profile->chatCover($user['idu'],$_POST['chat-cover-form-id'],$new_file_name);
				
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
			/// Image is not selected or out of size
			$return = ($image_size > $page_settings['max_img_size']*1000000) ? showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_img_size'])) : showError($TEXT['_uni-Photo-not_selected']);
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
	window.top.window.chatCoverReturn('<?php echo $db->real_escape_string($return); ?>','<?php echo (isset($updated_image)) ? '1' : '0' ;?>');
</script>
<?php }
if(isset($updated_image) && $updated_image) { ?>
<script language="javascript" type="text/javascript">
	window.top.window.chatCoverUpdated('<?php echo $db->real_escape_string($updated_image); ?>');
	window.top.window.socketNewMessage('<?php echo $db->real_escape_string($_POST['chat-cover-form-id']); ?>', '<?php echo $db->real_escape_string($user['idu']); ?>', "MESSAGE", "NULL");
</script>
<?php } 
mysqli_close($db);
?>