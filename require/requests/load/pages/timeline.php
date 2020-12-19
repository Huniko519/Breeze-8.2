<?php
session_start();

require_once("../../../../main/config.php");        // Import configuration
require_once('../../../main/database.php');         // Import database connection
require_once('../../../main/classes.php');          // Import all classes
require_once('../../../main/processors/video.php'); // Import all classes
require_once('../../../main/settings.php');         // Import settings
require_once('../../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more properties
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->settings = $page_settings;

		// Validate JS inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] >= 0) {
			
			// Add post form if same user is logged
			if($_POST['p'] == $user['idu']) {
				$TEXT['temp-image'] = $user['image'];
				
				// Parse active backgrounds for post form
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
				
				$TEXT['content'] = display(templateSrc('/main/post_form'));
		    }
			
			// Get posts
			if($_POST['ff'] == 2) {
				$TEXT['posts'] = $profile->getFeeds(0,$_POST['p'],NULL,1,NULL,'AND `post_type` = \'2\'','load_more_profile_feeds(%s,%s,2);');
			} else {
			    $TEXT['posts'] = $profile->getFeeds(0,$_POST['p'],NULL,1);
			} 
			
			// Display feeds
			echo display(templateSrc('/main/left_large'));
		
		} else {
			// Invalid JS inputs
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