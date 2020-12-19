<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
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
	
	// Try to fetch logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])) {
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);

        // Validate JS inputs		
        if(isset($_POST['v1']) && !empty($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 ) {
			
			// Validate type
			if(isset($_POST['t']) && in_array($_POST['t'],array("1","2","3","4","5","6","11"))) {
				
				// TYPE 1 : Delete post ||  TYPE 2 : Delete comment
				if($_POST['t'] == 1) {
					echo $profile->deletePost($_POST['v1'],$user) ;
				} elseif($_POST['t'] == 2) {
					echo $profile->deleteComment($_POST['v1'],$user) ;
				} elseif($_POST['t'] == 4) {
					echo $profile->deleteNotification($_POST['v1'],$user['idu']) ;
				} elseif($_POST['t'] == 5) {
					echo $profile->exitChat($_POST['v1'],$user['idu']);
				} elseif($_POST['t'] == 6) {
					
					echo $profile->deleteChat($_POST['v1'],$user['idu']);
					
					// Trigger sockets about the change
					echo '<script>socketNewMessage('.$form['form_id'].', '.$user['idu'].', "DELETE", "NULL");</script>';

				} elseif($_POST['t'] == 11) {
					
					$group_functions = new groups();
					$group_functions->db = $db;
					
					// Get group
					$group = $profile->getGroup($_POST['v1']);
			
					// Fetch group user
			        $group_user = $profile->getGroupUser($user['idu'],$_POST['v1']);
					
					echo ($group_user['user_id'] == $group['group_owner']) ? $group_functions->deleteGroup($group['group_id'],$user) : $TEXT['_uni-Delete_group_failed'];
					
				} else {
					echo $profile->deleteMessage($_POST['v1'],$user['idu']) ;
				}
				
			} else {
				// Invalid JS input
				echo $TEXT['lang_error_script1'];
			}		
		} else {
			// Invalid JS input
			echo $TEXT['lang_error_script1'];
		}
	} else {
		// Fake credentials are set
		echo $TEXT['lang_error_connection2'];
	}
} else {
	// No credentials
	echo $TEXT['lang_error_connection2'];
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>