<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more properties
		$profile->followings = $profile->listFollowings($user['idu']);

		// Add starting point
		$from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0;
		
		// Is recent
		$recent = (isset($_POST['f']) && is_numeric($_POST['f'])) ? 1 : 0;
		
		// Validate JS inputs and display results
		if(isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
			echo $profile->getLovers($from,$_POST['v1'],$user,$recent);			 
		} else {
			echo showError($TEXT['lang_error_script1']); 
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