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
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->followers = $profile->listFollowers($user['idu']);
		
		// Pass user profile settings
		$profile->posts_results_limit = $user['r_posts_per_page'];
		$profile->followers_results_limit = $user['r_followers_per_page'];
		$profile->followings_results_limit = $user['r_followings_per_page'];

		// Validate JS inputs
		if(isset($_POST['v1']) && !empty($_POST['v1'])) {
            
			// Add page title
			$add_title = '<script>document.title = \''.$TEXT['_uni-Search_results'].' | '.$TEXT['_uni-Profile'].'\';</script>';

			// Search profile
			$TEXT['posts'] = $profile->searchProfile($user,$_POST['v1']);

			// Display results
			$main = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).$add_title;
			
			// Get trending hashtags
			$TEXT['content'] = $profile->getHashtags('', 15);
			
			$TEXT['TEMP_TAB_ID'] = 5;
			
		    echo display('../../../themes/'.$TEXT['theme'].'/html/tab/search_tab'.$TEXT['templates_extension']).$main.display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
			
		} else {
			// Empty Query
			echo showNotification($TEXT['_uni-Please_add_search']);
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