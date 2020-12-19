<?php
session_start();

require_once('../../../main/config.php');           // Import configuration
require_once('../../main/database.php');            // Import database connection
require_once('../../main/classes.php');             // Import all classes
require_once('../../main/processors/comments.php'); // Import all classes
require_once('../../main/settings.php');            // Import settings
require_once('../../../language.php');              // Import language

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
		
		if($_POST['ff'] == "comment") {
			

			echo commentAct($db,$user,$_POST['v1'],$_POST['t']);
			
		} else {
			// validate inputs
        	if(isset($_POST['v1']) && !empty($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 && isset($_POST['t']) && in_array($_POST['t'], array('0','1'))) {		
				// TYPE 1 : LOVE 
				// TYPE 2 : REMOVE LOVE
				
				// Add live like | dislike
				$echo = (isset($_POST['v2']) && $_POST['v2'] == 1) ? '<script>ajaxProtocol(load_lovers_file,0,0,0,'.$_POST['v1'].',0,1,0,0,0,0,0,0,"#RIGHT_RECENT_LIKES",0,0,1,66);</script>' : '';

				if($_POST['p']) $profile->postAct($_POST['v1'],$_POST['ff'],$user,0);
				
				echo ($_POST['t']) ? $profile->postAct($_POST['v1'],$_POST['ff'],$user,1).$echo : $profile->postAct($_POST['v1'],$_POST['ff'],$user,0).$echo; 
			
			} else {
				// error JS inputs
			}
		}
	} else {
		// user is not logged
		echo 0;
	}
} else {
	// Not available for visitors need login | registration
	echo 0;
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>