<?php
session_start();

require_once("../../../main/config.php");          // Import configuration
require_once('../../main/database.php');           // Import database connection
require_once('../../main/classes.php');            // Import all classes
require_once('../../main//presets/presets.php');   // Import all arrays
require_once('../../main/settings_tabs.php');      // Import settings tabs
require_once('../../main/settings.php');           // Import settings
require_once('../../../language.php');             // Import language

// User class
$profile = new main();
$profile->db = $db;	

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and user credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// Fake credentials
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Settings type
		$type = (isset($_POST['t']) && in_array($_POST['t'],array("1","2","3","4","5"))) ? $_POST['t'] : 1;
		
		// Add title
		$TEXT['TEMP-settings_title'] = $TEXT[$title_set4[$type]];
		
		// Genral account settings
		if($type == 1) {
		    
		    $tabs = array(1, 2, 3, 4);
			
		// Profile information 
		} elseif($type == 2) {
		 
		    $tabs = array(5, 6, 7, 8, 9, 10, 11, 12, 13, 14);	
			
		// Privacy settings 
		} elseif($type == 3) {
		 
		    $tabs = array(15, 16, 17, 18, 19);			
		
		// Notifications settings 
		} elseif($type == 4) {
		 
			$tabs = array(20, 21);	
	
		}
	
		// Reset
		$TEXT['TEMP-content'] = '';
		
		// Load tabs
		foreach($tabs as $tab) {
    		$TEXT['TEMP-content'] .= getTab($user,$tab);
		}
		
		// if nothing set display general settings
		$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/main/settings'.$TEXT['templates_extension']);
	
		// Main body
		$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		echo $main_body;
	
	}
} else {
	// User has been logged out
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>