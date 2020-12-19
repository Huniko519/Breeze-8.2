<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/blogs.php');      // Import all blog classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Import user management class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;

// Add CSS loader to body
$TEXT['page_mainbody'] = '<div id="content-body" name="content-body" class="main-body blogs clear" style="margin-left:0px;margin-top:45px;">
		                    <div align="center" id="temp_pre_loader_load_more_feed">
								<img class="margin-20 loader-big" src="'.$TEXT['installation'].'/themes/'.$page_settings['theme'].'/img/icons/loader.svg" ></img>
							</div>
						</div>
						<script>refreshElements();</script>';
						
// Pass user properties
$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

// Try fetching logged user
$user = $profile->getUser();

// If user is logged and exists
if(!empty($user['idu'])) {

	// Generate Main body with navigation
	$TEXT['page_navigation'] = $profile->genNavigation($user);

	// Add more properties
	$profile->followings = $profile->listFollowings($user['idu']);

} else {

	$TEXT['page_navigation'] = $profile->genPubNavigation($user);

}

// Display body						
echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);	

if(isset($_GET['view']) && in_array($_GET['view'],array("category","search"))) {
	
	if($_GET['view'] == "category" && isset($_GET['v'])) {
		echo '<script>loadBlogData('.$_GET['v'].',1,1);</script>';
	} elseif($_GET['view'] == "search" && isset($_GET['v'])) {
		echo '<script>$(\'.search-articles-input\').val(\''.$db->real_escape_string($_GET['v']).'\');loadBlogHome(1);scrollToTop();</script>';
	} else {
        echo '<script>loadBlogHome(1);</script>';
	}	

} elseif(isset($_GET['view']) && is_numeric($_GET['view']) && $_GET['view'] > 0) {
	
	echo '<script>loadBlog('.$_GET['view'].');</script>';
	
} else {
	echo '<script>loadBlogHome(1);</script>';
}

// Add notifications type
if(!empty($user['idu'])) {
	require_once('./require/requests/content/add_notifications_type.php');
	echo $function = notifications($user['n_type'],'/require/requests/content/active_notifications.php','/require/requests/content/active_inbox.php') ;
}

// Refresh all JS PLUGINS
echo '<script>refreshElements();</script>' ;

if(isset($db) && $db) {
	mysqli_close($db);
}
?>