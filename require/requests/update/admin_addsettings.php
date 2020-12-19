<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/admin.php');            // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language
require_once('../../main/presets/presets.php');  // Import preset arrays

// New administration class
$profile = new manage();
$profile->db = $db;	

// If SESSIONS set verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If fake cookies set
	if(empty($admin['id'])){
		
		echo showError($TEXT['lang_error_connection2']);
		
	} else {
	    
		// Update settings using input protection
		echo $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7'],$_POST['v8'],$_POST['v9'],$_POST['v10'],$_POST['v11'],$_POST['v12']),$key_set1,$protection_set1);
		
	}
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>