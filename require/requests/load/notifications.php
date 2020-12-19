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
		
		// Pass user and administration settings and properties
		$profile->followings = $profile->listFollowings($user['idu']);
	    $profile->n_per_page = $user['n_per_page'];	
		
		// Add filter
		$filter = (isset($_POST['ff']) && in_array($_POST['ff'], array('0','1','2','3'))) ? $_POST['ff'] : 1;
		
		// Get type
		if(isset($_POST['f']) && !empty($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) {
			
			// Fetch notifications directly if already on notifications page
			echo $profile->getNotiicationsAll($_POST['f'],$user['idu'],$filter);
			
		} else {
	
			// Notifications
		    $TEXT['posts'] = $profile->getNotiicationsAll($_POST['f'],$user['idu'],$filter);
			
		    $main_body = display('../../../themes/'.$TEXT['theme'].'/html/notifications/page'.$TEXT['templates_extension']);
			
			// Check for navigation
			if(isset($_POST['t']) && !empty($_POST['t']) && is_numeric($_POST['t']) && $_POST['t'] == 1) {
				
				// Add title
			    $add_title = '<script>document.title = \''.$TEXT['_uni-Your_notifications'].'\';</script>';
			
				// Get notifications page navigation
				$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/notifications/filter_notifications'.$TEXT['templates_extension']);
				
				$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
				
				// Echo both navigation and notifications
				echo $main_body.$right_body.$add_title;
				
			} else {	
				// Display only notifications
				echo $main_body;				
			}
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