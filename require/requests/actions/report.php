<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global ARRAY
global $TEXT;

// New user class
$profile = new main();
$profile->db = $db;	
	
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// User doesn't exists
	if(!empty($user['idu'])){
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);	
		
		// Add report
        if(isset($_POST['v1']) && !empty($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0 ) {
			
			// Validate values
			$val1 = ($_POST['v2']) ? 1 : 0;   // Report comment 1
			$val2 = ($_POST['v3']) ? 1 : 0;   // Report comment 2
			$val3 = ($_POST['v4']) ? 1 : 0;   // Report comment 3
			$val4 = ($_POST['v5']) ? 1 : 0;	  // Report comment 4
			
			// Report type (user,post or comment)
			$type = (isset($_POST['t']) && in_array($_POST['t'],array("1","2","3"))) ? $_POST['t'] : 0 ; 		
			
			// Add Report
			echo ($type !== 0) ? $profile->report($_POST['v1'],$val1,$val2,$val3,$val4,$user,$type) : $TEXT['lang_error_script1'];
			
		} else {
			// Invalid JS input
			echo $TEXT['lang_error_script1'];
		}
	} else {
		// User doesn't logged
		echo $TEXT['lang_error_connection2'];
	}
// no credentials set
} else {
	echo $TEXT['lang_error_connection2'];
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>