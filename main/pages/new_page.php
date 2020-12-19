<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
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

		// Get categories
		list($institutes, $brands, $artists, $entertainment, $communities) = $profile->getCategories();
	
		// End body
		$TEXT['page_mainbodsy'] .= '</div>';
		$TEXT['temp-terms_title'] = sprintf($TEXT['_uni-Create_a_page_start_ttl'],$TEXT['web_name']);
		$TEXT['temp-selects2'] = getPageSelects($institutes);
		$TEXT['temp-selects3'] = getPageSelects($brands);
		$TEXT['temp-selects4'] = getPageSelects($artists);
		$TEXT['temp-selects5'] = getPageSelects($entertainment);
		$TEXT['temp-selects6'] = getPageSelects($communities);

		// Display
	    echo display(templateSrc('/pages/create_page'));

		// Add notifications type
		require_once('./require/requests/content/add_notifications_type.php');
	
    // Display homepage(WRONG COOKIES SET)
	} else {		
		$need_home = 1;
	}
	
} else {
	$need_home = 1;	
}

if(isset($need_home) && $need_home) {
	
	// Get recent logins
	$TEXT['content_main_page'] = (isset($_COOKIE['loggedout'])) ? $profile->getRecentLogins($_COOKIE['loggedout']):$TEXT['content_main_page'];
    
	// Display homepage
    echo display('themes/'.$TEXT['theme'].'/html/home/home'.$TEXT['templates_extension']);

}

// Refresh all JS PLUGINS
echo '<script>refreshElements();</script>' ;
?>