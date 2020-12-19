<?php
session_start();

require_once('../../../main/config.php');          // Import configuration
require_once('../../main/database.php');           // Import database connection
require_once('../../main/classes.php');            // Import all classes
require_once('../../main/settings_tabs.php');      // Import settings tabs
require_once('../../main/settings.php');           // Import settings
require_once('../../../language.php');             // Import language

// User class
$profile = new main();
$profile->db = $db;	

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and user credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// Fake credentials
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {	
		// Open requested tab
        echo getTab($user,$_POST['v2'],$_POST['v1']);
	}
} else {
	// User has been logged out
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>