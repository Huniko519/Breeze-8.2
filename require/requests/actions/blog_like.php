<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/blogs.php');            // Import blog functions
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
        
		// Validate JS inputs
		if(isset($_POST['p']) && !empty($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] > 0 && isset($_POST['t']) && in_array($_POST['t'], array('0','1'))) {

			echo $profile->blogAct($_POST['p'],$user,($_POST['t'] == 1) ? 1 : 0);
	
		} else {
			echo 0;
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