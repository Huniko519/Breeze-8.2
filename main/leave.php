<?php
require_once('./main/config.php');                        // Import configuration
require_once('./require/main/database.php');              // Import database connection
require_once('./require/main/classes.php');               // Import all classes
require_once('./require/main/admin.php');                 // Import all admin
require_once('./require/main/processors/delete.php');     // Import all classes
require_once('./require/main/settings.php');              // Import settings
require_once('./language.php');                           // Import language


if(!isset($_GET['id'])) {

// Import user management class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;

// Check logged user
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
    
	// Pass user properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// If user is logged and exists
	if(!empty($user['idu'])) {
		
		$id = $user['idu'];
		
		// Delete user
		deleteUser($db,$id);
		
        // Redirect  to home
		header("Location: ".$TEXT['installation']);
		
	}	
} else {
	die("Unable to process your request, please login and try again");
}

} else {

// Add administration class
$profile = new manage();
$profile->db = $db;	

// Reset
$TEXT['content_redirect'] = $TEXT['posts'] = '';

// Check administration credentials
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged and exists
	if(!empty($admin['id'])) {
		$id = $_GET['id'];
		
		// Delete user
		deleteUser($db,$id);
		
        // Redirect  to manage users
		header("Location: ".$TEXT['installation']."/manage/users");
	} else {
		die("Admin is out, please relogin");
	}
} else {
	die("Admin is out, please relogin");
}

}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>