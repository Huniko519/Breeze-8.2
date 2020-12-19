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

// TYPE 1 : SAVE POST EDIT 
// TYPE 2 : SAVE CHAT FORM EDIT
	
// Check user credentials
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
        
		// Get edit type
		if($_POST['t'] == 1) {
			
			// Validate post length
			if(strlen($_POST['v2']) > $page_settings['max_post_length']) {
			
			    echo sprintf($TEXT['_uni-P-length'],$page_settings['max_post_length']);
		
		    // Verified post status
		    } elseif(isset($_POST['v1']) && !empty($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 ) {
			
				// Save edit
				echo (isset($_POST['v2'])) ? $profile->savePostEdit($_POST['v1'],$_POST['v2'],$user) : $TEXT['lang_error_script1']; 
				
			} else {		
				// Invalid  input
				echo $TEXT['lang_error_script1']; 	
			}
			
		} elseif($_POST['t'] == 2) {
			// Validate  inputs
			if(isset($_POST['v1']) && !empty($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 ) {
			    
				// Validate chat name length
				if(empty($_POST['v2']) || strlen($_POST['v2']) < $page_settings['min_chat_len1'] || strlen($_POST['v2']) > $page_settings['max_chat_len1']) {
					echo showBox(sprintf($TEXT['_uni-error_chat_name_len'],$page_settings['min_chat_len1'],$page_settings['max_chat_len1']));
				} elseif(strlen($_POST['v3']) > $page_settings['max_chat_len2'] ) {
					echo showBox(sprintf($TEXT['_uni-error_chat_desc_len'],$page_settings['max_chat_len2']));
				} else {

				    // Save edits if inputs are verified
					echo $profile->saveFormEdit($_POST['v1'],$_POST['v2'],$_POST['v3'],$user);

					// Trigger sockets about the change
					echo '<script>socketNewMessage('.$_POST['v1'].', '.$user['idu'].', "MESSAGE", "NULL");</script>';

				}
				
			} else {		
				// Invalid  input
				echo showBox($TEXT['lang_error_script1']); 	
			}			
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