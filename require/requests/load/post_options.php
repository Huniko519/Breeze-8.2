<?php
session_start();

require_once("../../../main/config.php");          // Import configuration
require_once('../../main/database.php');           // Import database connection
require_once('../../main/classes.php');            // Import all classes
require_once('../../main/processors/pinning.php'); // Import pinning functions
require_once('../../main/settings.php');           // Import settings
require_once('../../../language.php');             // Import language

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
		if(isset($_POST['t']) && is_numeric($_POST['t']) && $_POST['t'] >= 0) {
			
			$post = getPost($db,$user,$_POST['t']);
			
			if($post && $post['posted_as'] == 0) {
				$TEXT['temp-id'] = $post['post_id'];
				if($post['post_by_id'] == $user['idu']) {
					echo display(templateSrc( ($post['post_id'] == $user['pin']) ? '/post/elements/unpin_post' : '/post/elements/pin_post'));
				}
			} 
			
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