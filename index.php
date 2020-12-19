<?php
// Start PHP SESSION
session_start();

if(isset($_GET['browse'])) {
	
	// Pass request to browse.php
	require_once('main/browse.php');

// Else check whether thumb is requested	
} elseif(isset($_GET['thumb'])) {
	
	// Pass request to thumb.php
	require_once('thumb.php');
	
// Else check whether extention process is requested 
} elseif(isset($_GET['extend'])) {
	
	// Pass request to thumb.php
	require_once('main/extend.php');
	
// Else check whether data pages are requested 
} elseif(isset($_GET['new']) ) {

   // Create page
   if($_GET['new'] == 'page') require_once('main/pages/new_page.php');
   
   // Create Blog(Article)
   elseif($_GET['new'] == 'blog') require_once('main/pages/new_blog.php');
   
      //  Upload a video
   elseif($_GET['new'] == 'video') require_once('main/pages/new_video.php');
   
   
   // Else home
   else require_once('main/home.php');
   
} elseif(isset($_GET['blogs'])) {

   // Blogs
   require_once('main/blogs.php');
   
} elseif(isset($_GET['blog'])) {

   // Blog view (SEO optimised)
   require_once('main/blog_view.php');
   
} elseif(isset($_GET['admin'])) {
	
	// Pass request to admin.php
	require_once('main/admin.php');
	
// Pass outer requests like account activation or password reset
} elseif(isset($_GET['respond'])) {

    // Pass request to responder.php
	require_once('main/respond.php');
	
// Delete account
} elseif(isset($_GET['leave'])) {

    // Pass request to leave.php
	require_once('main/leave.php');
	
} else {	

    // Else check whether post is requested
	if(isset($_GET['post']) && is_numeric($_GET['post'])) {
		
		// Pass request to load.php
		require_once('main/load.php');
	
    // Check whether profile is requested	
	} elseif(isset($_GET['profile'])) {
		
		// Pass request to load.php
		require_once('main/load.php');
		
	} else {
		
		// If nothing is set show homepage
	    require_once('main/home.php');
	
	}
}
?>