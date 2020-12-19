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

// Verify user
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials set
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass user properties and administration settings
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->settings = $page_settings;
		
		// Add result types 
		$latest = (isset($_POST['v2']) && $_POST['v2'] == 1) ? 1 : 0;
	
		if(isset($_POST['v2']) && $_POST['v2'] == 2) {
			$latest = 2;
		}
		
		// Add staring point
		$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
		// Validate and get comments
		if(isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0) {
			
			// Get comments
			echo $profile->getComments($from,$_POST['v1'],$user,$latest);
			
		} else {
			
			// Invalid inputs
			echo showError($TEXT['lang_error_script1']);
		}
	
	}
// no credentials set
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>