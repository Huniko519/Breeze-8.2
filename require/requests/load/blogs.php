<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/blogs.php');            // Import blogs classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

// Pass properties to class
$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
// Get user which is currently logged
$user = $profile->getUser();
	
// No login
if(empty($user['idu'])) {
	
	$add_title = '<script>document.title = \''.$db->real_escape_string($TEXT['web_name']).' | '.$TEXT['_uni-Blogs'].'\';</script>';

} else {

	// Pass user properties
	$profile->followings = $profile->listFollowings($user['idu']);

	// Add title
	$add_title = '<script>document.title = \''.$db->real_escape_string(fixName(12,$user['username'],$user['first_name'],$user['last_name'])).' | '.$TEXT['_uni-Blogs'].'\';</script>';

}

// Get recent articles
echo $profile->getRecentBlogs();

// Get trending article form last 30 days
echo $profile->getTrendingBlogs();

$TEXT['posts'] = display(templateSrc('/blogs/home/articles_heading')).'<script>blogAricles(0,0,25)</script>';;

// Add both feeds and post form to left part of body
$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

// Get popular posts
$TEXT['content'] = $profile->getPopularPosts();

$TEXT['content'] .= (substr($page_settings['bible_view'],6,1)) ? getBible() : '';

$TEXT['content'] .= $profile->getParTags($profile->getPopularTags(),1);

// Get all categories
$TEXT['content'] .= $profile->getAllCategories();

$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	

// Display full page
echo $main_body.$right_body.$add_title;
	
if(isset($db) && $db) {
	mysqli_close($db);
}
?>