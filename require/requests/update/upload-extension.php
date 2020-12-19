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

// Verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged display sponsor settings
	if(!empty($admin['id'])) {	
	
		// Get zip info
		$zip_name = $_FILES['EXTENSION']['name'];     // File name
		$zip_size = $_FILES['EXTENSION']['size'];     // File size
		$zip_temp = $_FILES['EXTENSION']['tmp_name']; // File temp
		$zip_type = $_FILES['EXTENSION']['type'];     // File type
    
		$name = explode(".", $zip_name);
	
	    // Allowed formats
    	$allowed = array('application/zip', 'application/octet-stream', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    	
		// Check format
		foreach($allowed as $mime) {
        	if($mime == $zip_type) {
            	$format = true;
            	$error = false;
            	break;
        	} else {
				$error = showError($TEXT['_uni-ext-formate']);
			}
    	}
		
		// Check whether file is processed
		if(strtolower($name[1]) == 'zip') {  
	 
	        // Current path
	        $path = dirname(__FILE__).'/';

            // Uploaded location(ZIP)			
			$target_zip = $path.$zip_name;
			
			if(move_uploaded_file($zip_temp, $target_zip)) {
				
        		$zip = new ZipArchive();
				
				// Open ZIP
        		$opened_zip = $zip->open($target_zip);  
				
        		if ($opened_zip === true) {

					// Check database
					$check = $db->query(sprintf("SELECT * FROM `extensions` WHERE `ext_name` = '%s' ",$db->real_escape_string($name[0])));
			
	        		$checked = ($check->num_rows) ? $check->fetch_assoc() : 0;
		
		            // If not uploaded
					if(!$checked) {
						
						$zip->extractTo('../../../extensions/'); 
            		
						$zip->close();
						
						// Get extension info
						require_once('../../../extensions/'.$name[0].'/info.php');
						
						// Add etension to database
						$db->query(sprintf("INSERT INTO `extensions` (`eid`, `ext_name`, `ext_extras`, `ext_update`, `ext_status`, `ext_author`, `ext_description`) VALUES (NULL, '%s', '%s', CURRENT_TIMESTAMP, '0', '%s', '%s');",$db->real_escape_string($name[0]),'',$db->real_escape_string($ext_author),$db->real_escape_string($ext_description)));

						$return = "1";

					} else {
						
						// Check if previous version is installed
						if($checked['ext_status']) {
							$return = showError($TEXT['_uni-ext-extract']);
							$zip->close();
						} else {

							$zip->extractTo('../../../extensions/'); 
            		
							$zip->close();

							// Get extension info
							require_once('../../../extensions/'.$name[0].'/info.php');
						
							$db->query(sprintf("UPDATE `extensions` SET `extensions`.`ext_author` = '%s', `extensions`.`ext_descrption` = '%s', `extensions`.`ext_update` = CURRENT_TIMESTAMP WHERE `ext_name` = '%s'",$db->real_escape_string($ext_author),$db->real_escape_string($ext_description),$db->real_escape_string($ext_name)));
			
							$return = "1";
							
						}
					}
        		} else {
					$return = showError($TEXT['_uni-ext-formateu']);
				}
				
				// Remove ZIP
				unlink($target_zip);	
				
    		} else {
				$return = showError($TEXT['_uni-ext-formateu']);
			}
			
		} else {	
			$return = $error;
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
	window.top.window.uploadExtension('<?php echo $db->real_escape_string($return); ?>',1);
</script>
<?php }
mysqli_close($db);
?>