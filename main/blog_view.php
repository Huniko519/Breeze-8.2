<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6 ie"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7 ie"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 ie"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9 ie"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class=""> <!--<![endif]-->
 
<head>

<!-- Set META DATA -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

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

if(isset($_GET['view']) && is_numeric($_GET['view']) && $_GET['view'] > 0) {
	
	list($article,$article_page) = $profile->getArticle($user,$_GET['view']);

	$cat = $profile->getCategoryById($article['blog_type']);

	?> 

	
	<title><?php echo $db->real_escape_string($article['blog_heading']); ?></title>
	<meta name="description" content='<?php echo $db->real_escape_string($article['blog_description']); ?>'>
	<meta name="keyword" content='<?php echo $db->real_escape_string($article['blog_tags']); ?>'>

	<meta name="og:title" property="og:title" content='<?php echo $db->real_escape_string($article['blog_heading']); ?>'/>
	<meta name="og:type" property="og:type" content="article"/>
	<meta name="og:image" property="og:image" content='<?php echo $TEXT['installation'].'/thumb.php?fol=blog&w=800&h=300&src='.$db->real_escape_string($article['blog_image']); ?>'/>
	<meta name="og:site_name" property="og:site_name" content='<?php echo $db->real_escape_string($TEXT['web_name']); ?>'/>
	<meta name="og:description" property="og:description" content='<?php echo $db->real_escape_string($article['blog_description']); ?>'/>

	<?php 
	
	$echo = '<script>loadBlog('.$_GET['view'].');</script>';
	
} else {
	$echo = '<script>loadBlogHome(1);</script>';
}

// Display body						
echo display('themes/'.$TEXT['theme'].'/html/main/blog_view'.$TEXT['templates_extension']);	

// Trigger function
echo $echo;

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