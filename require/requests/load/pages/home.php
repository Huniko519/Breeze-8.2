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
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties to class
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get user which is currently logged
	$user = $profile->getUser();
	
	// No wrong credentials
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Add title
		$add_title = '<script>document.title = \''.$db->real_escape_string(fixName(12,$user['username'],$user['first_name'],$user['last_name'])).' | '.$TEXT['_uni-Home'].'\';</script>';
		
		// Add image
		$TEXT['temp-image'] = $user['image'];
		
		// Parse background images
		parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
	
		// Add post form
		$TEXT['content'] = display(templateSrc('/main/post_form'));
		
		// Add feeds
		$TEXT['posts'] = $profile->getFeeds(0,$user['idu']);
		
		// Update Last time on feeds
		$profile->updateHomeActivity($user['idu']);
		
		// Add both feeds and post form to left part of body
		$main_body = display(templateSrc('/main/left_large'));	
		
		// Parse suggestions other data
		$groups = ($page_settings['groups_on_home']) ? $profile->getGroups($user['idu'],15) : '';
		$boxed_users = ($page_settings['feature_pe_tren_on_home']) ? $profile->getBoxedUsers($user['idu']) : '';
		$personalities = ($page_settings['feature_trending_on_home']) ? $profile->getPersonalities($user,$page_settings['trendind_per_limit']) : '';
		$trending_tags = ($page_settings['feature_tags_on_home']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
		$bible_verse = (substr($page_settings['bible_view'],0,1)) ? getBible() : '';
		$boxes = $groups.$bible_verse.$trending_tags.$boxed_users.$personalities;	

	    // Get active users
		$active_users = $profile->activeUsers($user['idu']);

		// Add ads 
		$ad_type = ($_POST['f']) ? 'fi_add_home1' : 'fi_add_feed';
		$ads = $profile->parseAdd($page_settings[$ad_type],1);                          // Fixed
		$ads .= ($_POST['f']) ? $profile->parseAdd($page_settings['po_add_home']) : ''; // Pop-up
	
		// Parse ads and create right body
		$TEXT['content'] = $ads.$boxes.$active_users; // Fixed
	
		$right_body = display(templateSrc('/main/right_small'));	
		
		// Display full page
		echo $main_body.$right_body.$add_title;
	}
} else {	
	// No credentials found
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>