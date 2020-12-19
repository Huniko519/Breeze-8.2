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
	
	// User doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Add title
		$title_r = ($_POST['t']) ? $TEXT['_uni-Followings'] : $TEXT['_uni-Followers']; 
		
		// Validate inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] >= 0) {
			
			// Add starting point
			$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] >= 0) ? $_POST['f'] : 0 ;
			
			// Add results type 
			$type = ($_POST['t']) ? 1 : 0 ;
			
			// Generate list
			$TEXT['posts'] = $profile->getAttachments($user,$from,$_POST['p'],$type);
			
		} else {
			// Invalid inputs
		    $TEXT['posts'] = showError($TEXT['lang_error_script1']);
		}
		
		// Get Content box
		$TEXT['temp_standard_content'] = $TEXT['posts'];
		$TEXT['temp_standard_title'] = $title_r;
		$TEXT['temp_standard_title_img'] = 'people';
		$TEXT['temp_standard_id'] = 'people-box-main';
		$TEXT['posts'] = display(templateSrc('/main/standard_box'));
	
		// Dislay body
		echo display(templateSrc('/main/left_large'));
	
	}
} else {
	// No credentials
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>