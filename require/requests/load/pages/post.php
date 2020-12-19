<?php
session_start();

require_once("../../../../main/config.php");        // Import configuration
require_once('../../../main/database.php');         // Import database connection
require_once('../../../main/classes.php');          // Import all classes
require_once('../../../main/settings.php');         // Import settings
require_once('../../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;	

// Check credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass user credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Try to fetch logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->settings = $page_settings;

		// Fetch Full post on different page
		if($_POST['ff'] == 1 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
			
			$post_temp = $profile->getPostByID($_POST['v1']);
			
			// Get post
			$TEXT['content'] = ($post_temp['post_type'] == 6) ? $profile->getPost($post_temp['post_content'],$user,$post_temp['post_id'],$profile->getUserByID($post_temp['post_by_id'])) : $profile->getPost($_POST['v1'],$user);
			
			// Bind body
			$main = display(templateSrc('/main/left_large'));
		
			// Get recent likers
			$TEXT['temp-content'] = $profile->getLovers(0,$_POST['v1'],$user,10);
		
			$TEXT['temp-data'] = $TEXT['_uni-REC_LIKES'];
			
			$TEXT['temp-data-id'] = 'RIGHT_RECENT_LIKES';	
			
		    $likes = (!empty($TEXT['temp-content'])) ? display(templateSrc('/modals/boxed_users')):'';
		    
			if($user['n_mention']) {
				$TEXT['temp-title'] = $TEXT['_uni-Mentions_not_info2'] ;
				$TEXT['temp-info'] = $TEXT['_uni-Mentions_not_info_ttl2'] ;
				$TEXT['temp-widget_color'] = 'blue-grey';
				$TEXT['temp-widget_class'] = 'notifications';
			} else {
				$TEXT['temp-title'] = $TEXT['_uni-Mentions_not_info1'] ;
				$TEXT['temp-info'] = $TEXT['_uni-Mentions_not_info_ttl1'] ;
				$TEXT['temp-widget_color'] = 'orange';
				$TEXT['temp-widget_class'] = 'mentions';
			}
			
			
			$TEXT['temp-js'] = 'getSettings(4);';
			$TEXT['temp-href'] = '#';
			$TEXT['temp-btn'] = $TEXT['_uni-Edit'];
			
			// Generate widget from template 
		    $widget = display(templateSrc('/modals/widget'));

			// Parse ads
			$TEXT['content'] = $widget.$profile->parseAdd($page_settings['fi_add_post'],1).$likes; // Fixed
			
			// Bind ads
			$ads = display(templateSrc('/main/right_small'));
		
			// Display page
			echo $main.$ads;
		
		// Get post text for edit modal
		} elseif($_POST['ff'] == 2 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
			
			// get post text
			echo $profile->getPostText($_POST['v1'],$user);	
			
		// Load single post
		} elseif($_POST['ff'] == 3 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
	
			// Get single post
			echo $profile->getPost($_POST['v1'],$user);

		}  else {
			// Invalid inputs
			echo showError($TEXT['lang_error_script1']); 
		}
	}		
// Neither user nor administration logged
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>