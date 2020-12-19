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
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Validation
	if(empty($user['idu'])){		
		echo showError($TEXT['lang_error_connection2']);		
	} else {
	    if(isset($_POST['t'])){ 
		    
			// Genralize VARs
			$v1 = $_POST['v1'];
			$v2 = $_POST['v2'];
			$v3 = $_POST['v3'];
			$v4 = $_POST['v4'];
			$v5 = $_POST['v5'];
			$v6 = $_POST['v6'];
			$v7 = $_POST['v7'];
			$v8 = $_POST['v8'];
			$v9 = $_POST['v9'];
				
			// General settings | NAME
		    if($_POST['t'] == 1){ 
			    
				// Count lengths
			    $fnm = strlen(trim($v1));
				$lnm = strlen(trim($v2));
		
				if(isXSSED($v1) || isXSSED($v2)) {
			
					// If values doesn't meets security requirements
					$return = showError($TEXT['_uni-error_xss']);
			
				} elseif($fnm > 15) {
			
					// If first name is out no length
					$return = showError($TEXT['_uni-error_firstname_len']);
			
				} elseif($lnm > 15) {
			
					// If last name is out of length
					$return = showError($TEXT['_uni-error_lastname_len']);
			
				} else {
				    $values = array($v1,$v2,$user['idu']);
				} 
			
            // General settings || Username			
		    } elseif($_POST['t'] == 2){ 
			    
				// Count lengths
			    $u_len = strlen(trim($v1));
		
				if(isXSSED($v1)) {
			
					// If values doesn't meets security requirements
					$return = showError($TEXT['_uni-error_xss']);
			
				} elseif($u_len < $page_settings['username_min_len'] || $u_len > $page_settings['username_max_len']) {
			
			        // Verify whether user name is within allowed length
			        $return = showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
			
				} elseif(!ctype_alnum(trim($v1)) || is_numeric(trim($v1))) {
			
			        // Allow only valid chars for username
			        $return = showError($TEXT['_uni-signup-9']);
			
				} elseif($profile->isUsernameExists($v1) || $user['username'] == trim($v1)) {
			
			        // Verify whether user name exists
			        $return = showError($TEXT['_uni-Username_exists']);
			
		        } else {  
					$values = array(trim($v1),$user['idu']);	
				} 
				
		    // General settings || Email			
		    } elseif($_POST['t'] == 3){ 
			    
				// Count lengths
			    $e_len = strlen(trim($v1));
		
				if(isXSSED($v1)) {
			
					// If values doesn't meets security requirements
					$return = showError($TEXT['_uni-error_xss']);
			
				} elseif(!filter_var($db->real_escape_string($v1), FILTER_VALIDATE_EMAIL) || $e_len < 3 || $e_len > 62) {
			
			        // Invalid email
			        $return = showError($TEXT['_uni-signup-4']);
			
				} elseif($profile->isEmailExists($v1) || $user['email'] == trim($v1)) {
			
			        // Verify whether user name exists
			        $return = showError($TEXT['_uni-signup-6']);
			
		        } else {
					$values = array(trim($v1),'2',$user['idu']);
				} 
				
		    // General settings || Password			
		    } elseif($_POST['t'] == 4){ 
			    
				// Count lengths
			    $p_len = strlen($v2);
		        
				// Encrypt passwords
		        $new = md5($v2);
		        $old = md5($v1);
				
				if(isXSSED($v2)) {
			
					// If values doesn't meets security requirements
					$return = showError($TEXT['_uni-error_xss']);
			
				} elseif($v2 !== $v3) {
			
			        // Passwords not matched
			        $return = showError($TEXT['_uni-signup-3']);
			
		        } elseif($p_len > $page_settings['password_max_len'] || $p_len < $page_settings['password_min_len']) {
			
			        // Out of length
			        $return = showError(sprintf($TEXT['_uni-signup-2'],$page_settings['password_min_len'],$page_settings['password_max_len']));
			
				} elseif($profile->passwordMatches($user['idu'],$old) !== 1) {
			
			        // Verify whether user name exists
			        $return = showError($TEXT['_uni-error_old_not']);
			
		        }
				
				// Cleared bug in v 1.2
		        $v1 = $new;
				
			// Profile information settings || Profession			
		    } elseif($_POST['t'] == 5 && (isXSSED($v1) || strlen($v1)> 32)){ 

				$return = showError($TEXT['_uni-error_profession_len']);
				
		    // Profile information settings || Education			
		    } elseif($_POST['t'] == 6 && (isXSSED($v1) || strlen($v1)> 32)){ 

				$return = showError($TEXT['_uni-error_education_len']);
				
		    // Profile information settings || Hometown			
		    } elseif($_POST['t'] == 7 && (isXSSED($v1) || strlen($v1)> 32)){ 

				$return = showError($TEXT['_uni-error_hometown_len']);
				
		    // Profile information settings || Living in			
		    } elseif($_POST['t'] == 8 && (isXSSED($v1) || strlen($v1)> 32)){ 

				$return = showError($TEXT['_uni-error_living_len']);
				
		    // Profile information settings || Interested in			
		    } elseif($_POST['t'] == 9 && !in_array($v1,array("0","1","2"))){ 

				$return = showError($TEXT['_uni-error_interested_in']);
				
		    // Profile information settings || Relationship			
		    } elseif($_POST['t'] == 10 && !in_array($v1,array("0","1","2"))){ 

				$return = showError($TEXT['_uni-error_relation_in']);
				
		    // Profile information settings || Gender			
		    } elseif($_POST['t'] == 11 && !in_array($v1,array("0","1","2"))){ 

				$return = showError($TEXT['_uni-error_gender_in']);
				
		    // Profile information settings || website			
		    } elseif($_POST['t'] == 12 && (strlen($v1) > 64 || !filter_var($v1, FILTER_VALIDATE_URL) || empty($v1))){ 

				$return = showError($TEXT['_uni-error_web_in']);			
				
		    // Profile information settings || Birthdate			
		    } elseif($_POST['t'] == 13){ 

			    // Validate values
				if((!empty($v1) && !empty($v2) && !empty($v3)) && (($v1 < 0 || $v1 > 31) || ( $v2 < 0 || $v2 > 12) || ($v3 < 1800 || $v3 > 2012) || !checkdate($v2,$v1,$v3))) {
					$return = showError($TEXT['_uni-error_date_in']);
				} else {
				    
					// Verify format
					$data = (!empty($v1) && !empty($v2) && !empty($v3)) ? $v1.'-'.$v2.'-'.$v3 : 0 ;
					$values = array($data,$user['idu']);
				}
				
		    // Profile information settings || Bio			
		    } elseif($_POST['t'] == 14 && (strlen($v1) > 160 || isXSSED($v1))){ 

				$return = showError($TEXT['_uni-error_bio_in']);

		    // Privacy settings || Posts			
		    } elseif($_POST['t'] == 15){ 
			   
			    // Validate values
				if(!in_array($v1,array("1","0")) || !in_array($v2,array("1","0"))) {
					$return = showError($TEXT['_uni-error_script']);
				} else {
				   	$values = array($v1,$v2,$user['idu']);
				}
				
		    // Privacy settings || Profile			
		    } elseif($_POST['t'] == 16){ 

			    // Validate values
				if(!in_array($v1,array("1","0")) || !in_array($v2,array("1","0")) || !in_array($v3,array("1","0")) || !in_array($v4,array("1","0"))) {
					$return = showError($TEXT['_uni-error_script']);
				} else {
				   	$values = array($v1,$v2,$v3,$v4,$user['idu']);
				}
				
		    // Privacy settings || Contact			
		    } elseif($_POST['t'] == 17){ 

		        // Validate values
				if(!in_array($v1,array("1","0")) || !in_array($v2,array("1","0"))) {
					$return = showError($TEXT['_uni-error_script']);		
				} else {
				   	$values = array($v1,$v2,$user['idu']);	
				}
				
		    // Privacy settings || Info			
		    } elseif($_POST['t'] == 18){ 

			    // Validate values
				if(!in_array($v1,array("1","0")) || !in_array($v2,array("1","0")) || !in_array($v3,array("1","0")) || !in_array($v4,array("1","0")) || !in_array($v5,array("1","0")) || !in_array($v6,array("1","0")) || !in_array($v7,array("1","0")) || !in_array($v8,array("1","0"))) {
					$return = showError($TEXT['_uni-error_script']);
				} else {
					$values = array($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$user['idu']);	
				}
				
		    // Privacy settings || Security			
		    } elseif($_POST['t'] == 19 && !in_array($v1,array("1","0"))){ 
			    
				$return = showError($TEXT['_uni-error_script']);

		    // Notifications settings || On Fasebook			
		    } elseif($_POST['t'] == 20){ 
			   
			    // Validate values
				if(!in_array($v1,array("1","0")) || !in_array($v2,array("15","10","5")) || !in_array($v3,array("1","0")) || !in_array($v4,array("1","0")) || !in_array($v5,array("1","0")) || !in_array($v6,array("1","0")) || !in_array($v7,array("1","0"))) {
					$return = showError($TEXT['_uni-error_script']);
				} else {
				   	$values = array($v1,$v2,$v3,$v4,$v5,$v6,$v7,$user['idu']);			
				}
				
		    // Notifications settings || On Email			
		    } elseif($_POST['t'] == 21){ 
			   
			    // Validate values
				if(!in_array($v1,array("1","0")) || !in_array($v2,array("1","0")) || !in_array($v3,array("1","0")) || !in_array($v4,array("1","0")) || !in_array($v5,array("1","0"))) {
					$return = showError($TEXT['_uni-error_script']);
				} else {
				   	$values = array($v1,$v2,$v3,$v4,$v5,$user['idu']);
				}
		    }

			// Available data types
			$data_type = array(	
				1 => 'ssi',	2 => 'si',	3 => 'ssi',	4 => 'si',	5 => 'si',	6 => 'si',	7 => 'si',	8 => 'si',	9 => 'si',	10 => 'si',	11 => 'si',12 => 'si',
				13 => 'si',	14 => 'si',	15 => 'ssi',	16 => 'ssssi',	17 => 'ssi',	18 => 'ssssssssi',	19 => 'si',	20 => 'sssssssi',	21 => 'sssssi',	
			);
			
			// For Single values
		    $values = (in_array($_POST['t'],array("4","5","6","7","8","9","10","11","12","14","19"))) ? array($v1,$user['idu']) : $values ;
		
            // Update settings
			$update = (isset($values)) ? $profile->updateSettings($_POST['t'],$values,$data_type[$_POST['t']]) : 0;

			// Update SESSIONs if username or email changed
		    if(($_POST['t'] == 3 || $_POST['t'] == 2) && $update == 1) {					
			    $_SESSION['username'] = trim($v1);
				setcookie("username", trim($v1), time() + 30 * 24 * 60 * 60,'/');
			}
			
            // Update SESSIONs if password changed
		    if($_POST['t'] == 4 && $update == '1') {
		
				$_SESSION['password'] = $new;	
				setcookie("password", $new, time() + 30 * 24 * 60 * 60,'/');

				// Save password changed time
				$update = $profile->updateSettings(sprintf("UPDATE `users` SET `users`.`p_chn` = '%s' WHERE `users`.`idu` = ?",$db->real_escape_string(time())),$user['idu'],'i');
			
			}	
			
	        // Add notification
			$return = (!isset($return)) ? ($update !== 0) ? '<script>closeTab('.$_POST['t'].','.$update.')</script>' :  showError($TEXT['_uni-Please_err']) : $return;
			
		    // Return data and remove pre loaders
		    echo '<script>contentLoader(0,'.$_POST['t'].');</script>'.$return;
			
		}
	}
// No credentials	
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>