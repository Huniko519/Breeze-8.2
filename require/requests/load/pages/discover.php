<?php
session_start();

require_once("../../../../main/config.php");           // Import configuration
require_once('../../../main/database.php');            // Import database connection
require_once('../../../main/classes.php');             // Import all classes
require_once('../../../main/processors/discover.php'); // Import all classes
require_once('../../../main/settings.php');            // Import settings
require_once('../../../main/presets/presets.php');     // Import all arrays
require_once('../../../../language.php');              // Import language

// User class
$profile = new main();
$profile->db = $db;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// get logged user
	if(empty($user['idu'])){
        echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass settings and user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->settings = $page_settings;
		
		$TEXT['temp-add_type'] = ($_POST['f'] == "START") ? display(templateSrc('/discover/link_discover')) : '';
		
		if($_POST['ff'] == 1) {
			
			if ($_POST['f'] == "START") echo display(templateSrc('/discover/header_posts'));

			echo trendingFeeds($db,$page_settings,$user,$profile->followings,$_POST['f'],$page_settings['t_posts_per_load']);
			
		} else {
			// Add page title
			echo '<script>document.title = "'.$TEXT['_uni-Popular'].' | '.$page_settings['title'].'";</script>';

			// Fetch trending groups
			$TEXT['temp-groups'] = getJoinableGroups($db,$user,$page_settings['joinable_groups']);
		
			// Fetch trending artiles
			$TEXT['temp-articles'] = getReadableBlogs($db,$user,$page_settings['readable_blogs']);
		
			$r_categories = getReadableCategoires($db,$page_settings['readable_categories']);
			$boxed_users = $profile->getBoxedUsers($user['idu']);
			$t_pages = trendingPages($db,$user,$page_settings['trendind_pages_limit']);
			$personalities = $profile->getPersonalities($user,$page_settings['trendind_per_limit']);
			
			$p_tags = getParTags(getPopularTags($db),'tag','tags_box_popular');
		
			echo display(templateSrc('/discover/wrapper'));

			// Add image
			$TEXT['temp-image'] = $user['image'];
		
			// Parse background images
			parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
	
	    	$TEXT['_uni-whats_mind'] = $TEXT['_uni-Create_a_new_post'];
		
			// Add post form
			$TEXT['content'] = display(templateSrc('/discover/header_posts')).display(templateSrc('/main/post_form'));
		
			// Add feeds
			$TEXT['posts'] = trendingFeeds($db,$page_settings,$user,$profile->followings,'Start',$page_settings['t_posts_per_load']);
		
			echo display(templateSrc('/main/left_large'));
		
			$TEXT['content'] = $r_categories.$p_tags.$t_pages.$boxed_users.$personalities;
	
			echo display(templateSrc('/main/right_small'));
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