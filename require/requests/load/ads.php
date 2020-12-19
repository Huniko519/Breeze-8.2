<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/ads.php');              // Import ads functons
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;	

// Verify user credentials if set
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials
	if(empty($user['idu'])){
        echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass user properties and administration settings
		$profile->followings = $profile->listFollowings($user['idu']);
	    $profile->settings = $page_settings;
		
		// Load ads home
		if($_POST['t'] == 'home') {
			
			$TEXT['content'] = display(templateSrc('/sponsors/ads_head')) ;
			
			// Get about section
			$TEXT['posts'] = display(templateSrc('/sponsors/ads_title')).listAds($user,0);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			$TEXT['content'] = '';
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
			
		// List
		} elseif($_POST['t'] == 'list_ads') {
			echo listAds($user,$_POST['f']);
			
		// Posts
		} elseif($_POST['t'] == 'post_ads') {
			$TEXT['posts'] = getPosts($user,$_POST['f']);
			echo ($_POST['f'] > 0) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		
		// Pages
		} elseif($_POST['t'] == 'page_ads') {
			$TEXT['posts'] = getPages($user,$_POST['f']);
			echo ($_POST['f'] > 0) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		
		// Groups
		} elseif($_POST['t'] == 'group_ads') {
			$TEXT['posts'] = getGroups($user,$_POST['f']);
			echo ($_POST['f'] > 0) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		} elseif($_POST['t'] == 'create_post_ad') {
			$TEXT['posts'] = getAdWizard($user,'post',$_POST['f']);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		    
		} elseif($_POST['t'] == 'create_page_ad') {
			$TEXT['posts'] = getAdWizard($user,'page',$_POST['f']);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		    
		} elseif($_POST['t'] == 'create_group_ad') {
			$TEXT['posts'] = getAdWizard($user,'group',$_POST['f']);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		    
		} elseif($_POST['t'] == 'create_invoice') {
			
			$c_id = $_POST['v1'];
			$c_type = $_POST['v2'];
			$c_views = $_POST['v3'];
			
			$TEXT['posts'] = createInvoice($user,$c_type,$c_id,$c_views);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		    
		}
	}
} else {
	// No credentials set
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>