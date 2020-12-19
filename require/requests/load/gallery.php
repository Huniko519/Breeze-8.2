<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// If user exists
	if(empty($user['idu'])){
        echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass settings and user properties
		$profile->followings = $profile->listFollowings($user['idu']);

		// Validate inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] >= 0) {
			
			// Add start up
			$from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0 ;
			
			// Get Content box
			$TEXT['temp_standard_content'] = $profile->getGallery($from ,$_POST['p']);
			$TEXT['temp_standard_title'] = $TEXT['_uni-Photos'];
			$TEXT['temp_standard_title_img'] = 'photos-big';
			$TEXT['temp_standard_id'] = 'gallery-box-main';
			
			// Get gallery
			$TEXT['posts'] = display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box'.$TEXT['templates_extension']);
			
			// Set to results
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		
		} else {
			// Invalid inputs
			echo showError($TEXT['lang_error_script1']);
		}
	}
} else {
	// no credentials set
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>