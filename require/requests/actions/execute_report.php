<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/admin.php');            // Import all admin classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Check administration credentials
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {

	// ADMIN class
    $profile = new manage();
    $profile->db = $db;
	
	// Pass credentials
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged ADMIN
	$admin = $profile->getAdmin();
	
	// If ADMIN is logged and exists
	if(!empty($admin['id']) && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 ) {
		
		// Check whether action is set to mark report safe or delete content
		$safe = (isset($_POST['v2']) && $_POST['v2'] == 1) ? 1 : NULL ;

		// Execute report
		echo $profile->executeReport($_POST['v1'],$safe);
		
	}
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>