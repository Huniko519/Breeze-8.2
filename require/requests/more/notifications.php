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
	
    // Try to fetch logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more properties
		$profile->followings = $profile->listFollowings($user['idu']);
	    $profile->n_per_page = $user['n_per_page'];	
		
		// Add filter
		$filter = (isset($_POST['ff']) && in_array($_POST['ff'],array('1','2','3','4','ALL','UNR','FOL','REQ'))) ? $_POST['ff'] : 1;
		
		// Add start up
		$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] >= 0) ? $_POST['f'] : 0;
		
		// Get notifications
		echo $profile->getNotiicationsAll($from,$user['idu'],$filter);
	}
} else {
	// No credentials
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>