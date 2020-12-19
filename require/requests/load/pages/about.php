<?php
session_start();

require_once("../../../../main/config.php");        // Import configuration
require_once('../../../main/database.php');         // Import database connection
require_once('../../../main/classes.php');          // Import all classes
require_once('../../../main/settings.php');         // Import settings
require_once('../../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;	

// Verify user credentials if set
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials
	if(empty($user['idu'])){
        echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass user properties and administration settings
		$profile->followings = $profile->listFollowings($user['idu']);
	    $profile->settings = $page_settings;
		
		// Validate inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] >= 0) {
			
			// Get about section
			$TEXT['posts'] = $profile->getFullAbout($_POST['p'],$user);
			echo display(templateSrc('/main/left_large'));
			
		} else {
			// Add invalid input error to results
			echo showError($TEXT['lang_error_script1']);
		}	
	}
} else {
	// No credentials set
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>