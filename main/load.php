<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Add user class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;

// Default functions
$function = '<script>window.location.href = \''.$TEXT['installation'].'\';</script>';
// Check whether visitor is looking for a profile
if(isset($_GET['profile']) && !isXSSED($_GET['profile'])) {
	
	// Check whether profile exists
	if(is_numeric($_GET['profile'])) {
		$get_user = $profile->getUserByID($_GET['profile']);		
	} else {
		$get_user = $profile->getUserByUsername($_GET['profile']);
	}
	
	// Add connect to redirect if user exists
	if(!empty($get_user['idu'])) {
		
		// Check profile picture
		$profile_picture = ($get_user['p_image']) ? 'private.png' : $get_user['image'];

		// Add function
		$function = '<script>loadProfile('.$get_user['idu'].');</script>';
		
		// Parse ads
		$ads = $profile->parseAdd($page_settings['po_add_conn_user']); // Pop-up
	
		$TEXT['temp-name'] = fixName(50,$get_user['username'],$get_user['first_name'],$get_user['last_name']);
		$TEXT['temp-connect_title'] = sprintf($TEXT['_uni-Connet_title'],$TEXT['temp-name']);
		$TEXT['temp-image'] = $profile_picture;
		$TEXT['content_redirect'] = display(templateSrc('/home/connect_user')).$ads;
		
	}
	
// Else if post is requested
} elseif(isset($_GET['post'])) {	
	
	// Get post
	$get_post = $profile->getPostByID($_GET['post']);
  
    // Get post owner if post exists  
	if(!empty($get_post['post_by_id'])) {
		$get_user = $profile->getUserByID($get_post['post_by_id']);
	} else {
		$get_user = '';
	}
	
	// Add connect to redirect if post and poster exists
    if(!empty($get_user['idu'])) {
		
		// Check for privacy in profile picture
		if($get_user['p_image'] || $get_user['image'] == 'default.png') {
			$profile_picture = 'private.png';
		} else {
			$profile_picture = $get_user['image'];
		}
		
		// Add function
		$function = '<script>loadPost('.$_GET['post'].');</script>';
		
		// Parse ads
		$ads = $profile->parseAdd($page_settings['po_add_conn_post']); // Pop-up

		$TEXT['temp-name'] = fixName(50,$get_user['username'],$get_user['first_name'],$get_user['last_name']);
		$TEXT['temp-image'] = $profile_picture;
		$TEXT['temp-connect_title'] = sprintf($TEXT['_uni-Connet_title'],$TEXT['temp-name']);	
		$TEXT['content_redirect'] = display(templateSrc('/home/connect_user')).$ads;
		
	}
	
}

// Check user credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

    // Pass credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])) {
		
		// Generate navigation
		$TEXT['page_navigation'] = $profile->genNavigation($user);
		
		// Generate CSS loader
		$TEXT['page_mainbody'] = '<div id="content-body" name="content-body" class="main-body clear" style="margin-left:250px;margin-top:45px;">
		                            <div align="center" id="temp_pre_loader_load_more_feed">
										<img class="margin-20 loader-big" src="'.$TEXT['installation'].'/themes/'.$page_settings['theme'].'/img/icons/loader.svg" ></img>
									</div>
								</div>
								<script>refreshElements();</script>';
			
		// Display page	
		echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);	
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Reset contents
		$TEXT['content'] = $TEXT['posts'] = '';

		// Create a empty body
		$full_body = display('themes/'.$TEXT['theme'].'/html/main/body'.$TEXT['templates_extension']);

        // Display blank page with navigation		 
		echo display('themes/'.$TEXT['theme'].'/html/main/body'.$TEXT['templates_extension']);

		// Display requested using JS function
		echo $function;

		// Add notifications type
		require_once('./require/requests/content/add_notifications_type.php');
		echo $function = notifications($user['n_type'],'/require/requests/content/active_notifications.php','/require/requests/content/active_inbox.php') ;
	
    // Display homepage
	} else {
		echo display('themes/'.$TEXT['theme'].'/html/home/home'.$TEXT['templates_extension']);
	}	
	
// Show public contents of profile
} elseif(!isset($_GET['ref']) && isset($_GET['profile'])) {

	if($get_user['idu']) {
	
		require_once('./require/main/public.php');    // Import public classes
	
		// New public class
		$public = new access();
		$public->db = $db;	
    	$public->settings = $page_settings;

		// Generate header
		$TEXT['page_navigation'] = $public->genNavigation($get_user);
	
		// Profile contents
		$TEXT['page_mainbody'] = $public->profileTop($get_user);
		
		// Profile about
		$TEXT['left_content'] = $public->getAbout($get_user);
		
		// Favourites
		$TEXT['left_content'] .= $public->getFavourites($get_user);
		
		// Recent photos
		$TEXT['right_content'] = $public->getPhotos($get_user);;
		
		// Similar users
		$TEXT['right_content'] .= $public->getSimilarUsers($get_user);;
	
	    // Display profile and it's contents
    	echo display('themes/'.$TEXT['theme'].'/html/public/wrapper'.$TEXT['templates_extension']);
	
	} else {
		
		// Else user doesn't exists
		header('Location: '.$TEXT['installation']);
		
	}
	
} else {
	
	// If REFered show direct login/signup
	$TEXT['temp-REF_AVAIL'] .= ($_GET['ref'] == 'signup') ? 'toggleForms("REGISTERATION_FORM",1);' : '';
	
	echo display('themes/'.$TEXT['theme'].'/html/home/home'.$TEXT['templates_extension']);	
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>