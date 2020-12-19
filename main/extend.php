<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/admin.php');     // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Add administration class
$profile = new manage();
$profile->db = $db;	

// Check administration credentials
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged and exists
	if(empty($admin['id'])){
		echo addLog($TEXT['lang_error_connection2'],1);
	} else {

		// Reset
	    $error = ''; $count = 0;
	
		echo addLog($TEXT['_uni-ext_loading']);
		echo addLog($TEXT['_uni-ext-locating']);

		// Verify files
		if(file_exists('./extensions/'.$_GET['name'].'/install.php')) {

			echo addLog($TEXT['_uni-ext-Verifying']);
			
			// Update database
			$check = $db->query(sprintf("SELECT * FROM `extensions` WHERE `ext_name` = '%s' ",$db->real_escape_string($_GET['name'])));
			
	        $extension = ($check->num_rows) ? $check->fetch_assoc() : 0;
			
			// If extension is not in database
			if(!isset($extension['ext_name'])) {
				$error = 1;
				echo addLog($TEXT['_uni-ext-corrupted'],1);				
			} 
			
			if(!file_exists('./extensions/'.$_GET['name'].'/uninstall.php')) {
				echo addLog($TEXT['_uni-ext-r1'],1);
				$error = 1;
			} elseif(!file_exists('./extensions/'.$_GET['name'].'/prepare.php')) {
				echo addLog($TEXT['_uni-ext-r2'],1);
				$error = 1;
			} elseif(!file_exists('./extensions/'.$_GET['name'].'/unprepare.php')) {
				echo addLog($TEXT['_uni-ext-r3'],1);
				$error = 1;
			}
			
	        // Confirm installation
			if(!$error && $_GET['extend'] == "install") {
				
				if($extension['ext_status']) {
					$error = 1;
					echo addLog($TEXT['_uni-ext-a1'],1);
				} else {
					echo addLog($TEXT['_uni-ext-start']);
					
					// Check version
					require_once('./extensions/'.$_GET['name'].'/info.php');
					require_once('./require/main/platform.php');
					
					// Check support
					if(!isset($EXT_SUPPORT[$PLATFORM['VERSION_EXT']]) || $EXT_SUPPORT[$PLATFORM['VERSION_EXT']] !== 1) {
						$error = 1;
						echo addLog($TEXT['_uni-ext-unsp'],1);
					} else {
	
						// Prepare installation
						require_once('./extensions/'.$_GET['name'].'/prepare.php');
					
						// Import installation instructions
		        		require_once('./extensions/'.$_GET['name'].'/install.php');					
					
						$update_type = '1';
					
						$data = $TEXT['_uni-ext-ie1'];
					
						// Strings to find
						$extend = $extend_strings;
					
						// Strings which will replace matches
						$to = $extended_strings;
						
					}
				
				}

			} elseif(!$error) {
				
				if(!$extension['ext_status']) {
					$error = 1;
					echo addLog($TEXT['_uni-ext-a2'],1);
				} else {
					echo addLog($TEXT['_uni-ext-start2']);
					
					// Prepare uninstallation
					require_once('./extensions/'.$_GET['name'].'/unprepare.php');
					
					// Import Uninstallation instructions
		        	require_once('./extensions/'.$_GET['name'].'/uninstall.php');					
					
					$update_type = '0';
					
					$data = $TEXT['_uni-ext-ie2'];
					
					// Strings to find
					$extend = $un_extend_strings;
					
					// Strings which will replace matches
					$to = $un_extended_strings;
					
				}
			}
			
			// Confirm error free check
			if(!$error) {
				
				echo addLog($TEXT['_uni-ext-Verifyinge']);
				
				// Verify extension
				foreach($extend_files as $key=>$id) {
				
					if($error) break;
					
					// File to update
					$file = $extend_files[$key];
				
				    // Check files availability
				    if(!file_exists($file)) {
						echo addLog($TEXT['_uni-ext-file-miss'].$file,1);
						$error = 1;
						break;
					}
					
					// Get file
					$file_contents = file_get_contents($file);
				
				    // Check strings availability
					if(is_array($extend[$key])) {
					
						foreach($extend[$key] as $key_in) {
							
							if($error) break;
							
							// Check string
							if (stripos($file_contents, $key_in) !== false) {
								$count++;
							} else {
                    			echo addLog($TEXT['_uni-ext-e1'].'<div class="shadow"><div class="padding-5 white-color margin-5-0-0-0 active-side">'.htmlspecialchars($key_in).'</div></div>',1);$error = 1;
                			}
						}
					} else {
					
						// Check string
						if (stripos($file_contents, $extend[$key]) !== false) {
							$count++;
						} else {
							echo addLog($TEXT['_uni-ext-e1'].'<div class="shadow"><div class="border-min white-color margin-5-0-0-0 rounded padding-5">'.htmlspecialchars($extend[$key]).'</div></div>',1);
							$error = 1;
               	 		}	
					}	
				}
			
				// Install | Uninstall
				if(!$error) {
				
	                echo addLog($TEXT['_uni-ext-ie']);
	
					foreach($extend_files as $key=>$id) {	
				
						if($error) break;
				
						// File to update
						$file = $extend_files[$key];
				
						// Get file
						$file_contents = file_get_contents($file);
				
						// Update file
						$file_contents = str_replace($extend[$key],$to[$key],$file_contents);
				
						// Open original file
						$extending = fopen($file, "w");
				
						// Save extended code
						fwrite($extending, $file_contents);
						fclose($extending);

						// Look for log note
						if(isset($extention_key_notes[$key])) {
							echo addLog($extention_key_notes[$key]);
						}	
			
					}
			
					// Activate extension in database
					$db->query(sprintf("UPDATE `extensions` SET `ext_status` = '$update_type' WHERE `ext_name` = '%s' ",$db->real_escape_string($_GET['name'])));

			        echo addLog(sprintf($TEXT['_uni-ext-ies'],$count));
			        echo addLog($data);	
				}		
			}
		} else {
			echo addLog($TEXT['_uni-ext-r4'],1);	
		}	
	}	
	
// Welcome page for administration
} else {
	
	// Redirect to home
    echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>