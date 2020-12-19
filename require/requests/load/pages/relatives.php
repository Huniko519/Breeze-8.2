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

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->followers = $profile->listFollowers($user['idu']);    
		
		// Add filter
		$filter = ($_POST['t']) ? 1 : 0;
		
		// Add title
		$title_r = ($_POST['t']) ? $TEXT['_uni-Followings'] : $TEXT['_uni-Followers']; 
		
		// Add start up
		$from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;

		// Add results type , Followers || Followings
		if(isset($_POST['v1']) && $_POST['v1'] == 0) {
			$vale = $app = 0;
		} else {
			$vale = $_POST['v1'];
			$app = 1;
		}
			
		// Get users list
		$TEXT['posts']  = $profile->relatives($from,$user['idu'],$filter,$vale);
		
		// Little start up check and display result
		if((isset($_POST['f']) && $_POST['f'] > 0)) {
			echo $TEXT['posts'];
		} elseif($app == 0) {
		    
			// Get Content box
			$TEXT['temp_standard_content'] = $TEXT['posts'];
			$TEXT['temp_standard_title'] = $title_r;
			$TEXT['temp_standard_title_img'] = 'people';
			$TEXT['temp_standard_id'] = 'people-box-main';
			
			$TEXT['posts'] = display(templateSrc('/main/standard_box'));
			
			// Full page
			$main_body = display(templateSrc('/main/left_large'));
			
			// Add title
			$add_title = '<script>document.title = \''.$db->real_escape_string(fixName(12,$user['username'],$user['first_name'],$user['last_name'])).' | '.$TEXT['_uni-Users'].'\';</script>';
		
			// Parse adds
			$TEXT['content'] = $profile->parseAdd($page_settings['fi_add_relatives'],1); // Fixed
			
			// Get some stuff
			$TEXT['content'] .= $profile->getBoxedUsers($user['idu']);
			
			// Get active users
		    $TEXT['content'] .= $profile->activeUsers($user['idu']);
			
			// Bind ads
			$ads = display(templateSrc('/main/right_small'));
		   	
			// Display body
			echo $main_body.$ads.$add_title;		
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