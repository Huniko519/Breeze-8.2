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
		if(isset($_POST['v1']) && !empty($_POST['v1']) && isset($_POST['v2'])) {
			
			// Validate and send message
			if(empty($_POST['v2']) && $_POST['v2'] !== '0') {
				echo showError($TEXT['_uni-M-empty']).'<script>quickMessage(0,2);</script>';
			} elseif(strlen($_POST['v2']) > $page_settings['max_message_length']) {
				echo showError(sprintf($TEXT['_uni-Make_sure_message_len'],$page_settings['max_message_length'])).'<script>quickMessage(0,2);</script>';
			} else {
				echo $profile->quickMessage($_POST['v1'],$_POST['v2'],$user);
			}

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