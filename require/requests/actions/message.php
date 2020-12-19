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
	
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// try to fetch logged user
	$user = $profile->getUser();
	
	// if user exists
	if(!empty($user['idu'])){
		
		// pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		$chat_loaders = 'chatLoaders(0, '.$_POST['v1'].');';

		// Message validation
		if(empty($_POST['v2'])) {
			echo '<script>alert("'.$TEXT['_uni-M-empty'].'");'.$chat_loaders.';</script>';
		} elseif(isXSSED($_POST['v2'])) {
			echo '<script>alert("'.$TEXT['_uni-P-xss'].'");'.$chat_loaders.';</script>';
		} elseif(strlen($_POST['v2']) > $page_settings['max_message_length']) {
			echo '<script>alert("'.sprintf($TEXT['_uni-Make_sure_message_len'],$page_settings['max_message_length']).'");</script>';
		} else {
			
			// Fetch chat form if exists
			$form = $profile->getChatFormByID($_POST['v1'],$user['idu']);
		
		    // Add message if form is available
			if($form['form_id']) {
				$profile->messageForm($user['idu'],$form['form_id'],1,$_POST['v2']);
				echo '<script>socketNewMessage('.$form['form_id'].', '.$user['idu'].', "MESSAGE", "");</script>';
			} else {
				echo '<script>alert("'.$TEXT['_uni-Form_n_available'].'");'.$chat_loaders.';</script>';
			}
			
		}
	} else {
		echo '<script>alert("'.$TEXT['lang_error_connection2'].'");'.$chat_loaders.';</script>';
	}
} else {
	echo '<script>alert("'.$TEXT['lang_error_connection2'].'");'.$chat_loaders.';</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>