<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New user class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(!empty($user['idu'])){
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);	
        
		// Validate JS inputs
		if(isset($_POST['v1']) && !empty($_POST['v1'])) {	
			
			echo $profile->sharePost($user,$_POST['v1']);

		} else {
			// Invalid JS input
			echo showError($TEXT['lang_error_script1']);
		}
	} else {		
		// Wrong credentials
		echo showError($TEXT['lang_error_connection2']);	
	}
} else {
	// No credentials set
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>