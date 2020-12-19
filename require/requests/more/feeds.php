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
	
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more properties
		$profile->followings = $profile->listFollowings($user['idu']);
	
		// Add start up
		$from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0;
		
		// Validate JS inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) > 0) {
			
			// Get user if logged user is request profile feeds
			$getUser = $profile->getUserByID($_POST['p']);
			
			// If user exists load feed
			if(!empty($getUser['idu'])) {
				
				// Check whether single user feeds are requested
				$val = (isset($_POST['v10']) && $_POST['v10'] == 1) ? 1 : NULL ;
				
				// Generate feeds
				if($_POST['ff'] == 2) {
					$feeds = $profile->getFeeds($from,$_POST['p'],NULL,$val,NULL,'AND `post_type` = \'2\'','load_more_profile_feeds(%s,%s,2);');
				} else {
			    	$feeds = $profile->getFeeds($from,$_POST['p'],NULL,$val);
				} 
				
			} else {
				// Else generate new feeds
				$feeds = $profile->getFeeds($from,$user);
			}
		} else {
			// Else generate new feeds
			$feeds = $profile->getFeeds($from,$user);
		}
		// Display feeds
		echo $feeds;
	}
} else {
	// Wrong credentials
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>