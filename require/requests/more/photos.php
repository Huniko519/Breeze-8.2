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
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
    // Try to fetch logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);;
	} else {
		
		// Pass more properties
		$profile->followings = $profile->listFollowings($user['idu']);
	
		// Validate JS inputs and get gallery
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] >= 0 && isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] >= 0) {
			echo $profile->getGallery($_POST['f'],$_POST['p']);
		} else {
			echo showError($TEXT['lang_error_script1']);
		}
	}
} else {
	// No credentials
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>