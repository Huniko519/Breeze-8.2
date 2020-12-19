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

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])){
		
		// Pass settings and user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		echo ($page_settings['feature_expressfriends']) ? $profile->expressChats($user,$page_settings['active_limit']) : '';

	}
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>