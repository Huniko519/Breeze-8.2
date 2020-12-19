<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// user class
$profile = new main();
$profile->db = $db;	

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
    // Try to fetch logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more properties
		$profile->followings = $profile->listFollowings($user['idu']);
	    $profile->settings = $page_settings;
		
		// Validate JS inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] >= 0 && isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] >= 0) {
			
			// Validate requested type
			if(isset($_POST['t']) && is_numeric($_POST['t']) && in_array($_POST['t'], array('1','0'))) {
				
				// Echo users
				$results =  $profile->getAttachments($user,$_POST['f'],$_POST['p'],$_POST['t']);
			
			} else {
				// Invalid TYPE
				$results = showError('lang_error_script1');
			}
		} else {
			// Invalid JS inputs
			$results = showError('lang_error_script1');
		}
		// Display data
		echo $results;
	}
} else {
	// No credentials
	echo showError($TEXT['lang_error_connection2']);
}


if(isset($db) && $db) {
	mysqli_close($db);
}
?>