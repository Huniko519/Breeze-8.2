<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/blogs.php');      // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

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

	    $categoires = $profile->getCategories();
		
		//print_R($categoires);
		
	    $TEXT['temp-add_select'] = getPageSelects($categoires);
		
		// Display
	    echo display(templateSrc('/pages/create_blog'));


    // Display homepage(WRONG COOKIES SET)
	} else {		
		echo '<script>window.location.href = \''.$db->real_escape_string($TEXT['installation']).'\';</script>';
	}
	
} else {
	echo '<script>window.location.href = \''.$db->real_escape_string($TEXT['installation']).'\';</script>';
}


// Refresh all JS PLUGINS
echo '<script>refreshElements();</script>' ;
?>