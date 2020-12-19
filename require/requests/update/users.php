<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// user class
$profile = new main();
$profile->db = $db;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])){
        
		// Check whether user is joining | leaving - groups
		if($_POST['ff'] == 1) {
	
			$group_functions = new groups();
			$group_functions->db = $db;
		
			echo $group_functions->groupAct($profile->getGroup($_POST['p']),$user,$profile->getGroupUser($user['idu'],$_POST['p']),$_POST['t']);
			
		// Page type
		} elseif($_POST['ff'] == 2) {
			
			$pages = new pages();
			$pages->db = $db;
			
			$page_user = $profile->getPageUser($user['idu'],$_POST['p']);

			if($_POST['t'] == '1' && $profile->isLiked($_POST['p'],$user['idu'])) {
				echo '';
			} elseif($_POST['t'] == '0' && !$profile->isLiked($_POST['p'],$user['idu'])) {
				echo '';
			} elseif($_POST['t'] == '6' && isset($page_user['page_id'])) {
				echo '';
			} elseif($_POST['t'] == '7' && !isset($page_user['page_id'])) {
				echo '';
			} else {
				echo $pages->pageAct($profile->getPage($_POST['p']),$user,$page_user,$_POST['t']);
			}
	
		} else {
			// Validate JS inputs
			if(isset($_POST['p']) && !empty($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] > 0 && isset($_POST['t']) && in_array($_POST['t'], array('0','1','11','10'))) {

				// Get type
				if($_POST['t'] == 10) {
				
					// Delete follow request
			    	echo $profile->deleteRequest($_POST['p'],$user);
				
				} elseif($_POST['t'] == 1) {
				
					// Follow or Request
			    	echo $profile->followUser($_POST['p'],$user);
				
				} elseif($_POST['t'] == 11) {
				
					// Allow follow request
			    	echo $profile->allowUser($_POST['p'],$user);
				
				} else {
				
					// Un_follow or Undo request
			    	echo $profile->unFollowUser($_POST['p'],$user);
				
				}	
			} else {
				echo 0;
			}			
			
		}	
	} else {
		echo 0;
	}
} else {
	echo 0;
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>