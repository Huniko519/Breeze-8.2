<?php
session_start();

require_once("../../../main/config.php");          // Import configuration
require_once('../../main/database.php');           // Import database connection
require_once('../../main/classes.php');            // Import all classes
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
		
		// Settings type
		$type = (isset($_POST['t']) && in_array($_POST['t'],array("1","2","3","4","5"))) ? $_POST['t'] : 1;
		
		// if nothing set display general settings
		$TEXT['content'] = '<script>if($(window).width() > 600) {loadSettings('.$type.');}</script>';

		// Main body
		$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		
		// Get navigation
		$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/navigations/settings'.$TEXT['templates_extension']);
		
		// Right body
		$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small_no_lang'.$TEXT['templates_extension']);
		
		echo $main_body.$right_body;
	
	}
} else {
	// User has been logged out
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>