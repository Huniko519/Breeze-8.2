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
        
		// Post pinning
		if($_POST['ff'] == 'pin') {
	        
			$type = ($_POST['t']) ? $_POST['p'] :0;
			
			$db->query(sprintf("UPDATE `users` SET `pin` ='%s' WHERE `idu` = '%s'",$db->real_escape_string($type),$db->real_escape_string($user['idu'])));
		
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