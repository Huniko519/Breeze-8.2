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
	
	
		if($_POST['ff'] == 'confirm') {
 
			$TEXT['temp-id'] = $user['idu'];
			$TEXT['temp-image'] = $user['image'];
			$TEXT['temp-title'] = sprintf($TEXT['_uni-T_S_del_ttl'],fixName(25,$user['username'],$user['first_name'],$user['last_name']),$TEXT['web_name']);
	
			echo display(templateSrc('/user/delete'));
		 
		} 

	}		

}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>