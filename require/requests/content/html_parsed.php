<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global Array			
global $TEXT;

// User class
$profile = new main();
$profile->db = $db;	

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// get logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		if($_POST['t'] == 'rec-articles') {
			
			require_once('../../main/processors/discover.php');
			
			echo recArticles($db,$page_settings,$user,15);
			
		} elseif($_POST['t'] == 'rec-pages') {
			
			require_once('../../main/processors/discover.php');
			
			echo manPages($db,$page_settings,$user);
			
		}
		
	}	
// Credentials not set
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>