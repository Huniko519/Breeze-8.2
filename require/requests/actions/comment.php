<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global ARRAY
global $TEXT;

// User class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;	

// Check user credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// if user exists
	if(!empty($user['idu'])){
		
		// pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Validate inputs
        if(isset($_POST['v1']) && !empty($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 && isset($_POST['v3'])) {
			
			// Custom protection
			if(isXSSED($_POST['v3']) || empty($_POST['v3'])) {
				
				echo showError($TEXT['_uni-P-xss']);
				
			} elseif(strlen($_POST['v3']) > $page_settings['max_comment_length']) {
				
				echo showError(sprintf($TEXT['_uni-error-comment-length'],$page_settings['max_comment_length'])); 
			
			} else {
				
				// post comment	
				echo $profile->addComment($_POST['v3'],$_POST['v1'],$user);
			}	
		} else {
			echo showError($TEXT['lang_error_script1']);   // JS input error
		}
	} else {
		echo showError($TEXT['lang_error_connection2']);   // user doesn't exists
	}
} else {
	echo showError($TEXT['lang_error_connection2']);       // user is not logged
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>