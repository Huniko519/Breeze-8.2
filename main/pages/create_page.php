<?php
session_start();

require_once("../../main/config.php");             // Import configuration
require_once('../../require/main/database.php');   // Import database connection
require_once('../../require/main/classes.php');    // Import all classes
require_once('../../require/main/settings.php');   // Import settings
require_once('../../language.php');                // Import language

// User class
$profile = new main();
$profile->db = $db;

// Check credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties to fetch logged user if exists
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// Pass administration settings
	$profile->settings = $page_settings;
	
	// If user doesn't exists
	if(empty($user['idu'])){
		$return = showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Create new page
		if($_POST['t']) {
		    
			// Get categories
			$all_cats = $profile->getCategories(1);
			
			// Arrange data
			$p_type = $_POST['t'];
			$p_name = $_POST['v1'];
			$p_cat = $_POST['v2'];
			$p_address = ($_POST['v3']) ? $_POST['v3'] : '';
			
			// Main page type check
			if(!in_array($p_type,array(1,2,3,4,5,6))) {
				
				$return = showError($TEXT['_uni-Create_a_page_err-1']);
				
			// Check page sub category
			} elseif($p_type > 1 && !in_array($p_cat,$all_cats)) {
				
				$return = showError($TEXT['_uni-Create_a_page_err-2']);
				
			// Check page sub category for local level
			} elseif($p_type == 1 && (empty($p_cat) || strlen($p_cat) > 50 || strlen($p_cat) < 6)) {
				
				$return = (strlen($p_cat) > 50 || strlen($p_cat) < 6) ? showError($TEXT['_uni-Create_a_page_err-3']) : showError($TEXT['_uni-Create_a_page_err-2']);
				
			// Check page name
			} elseif(empty($p_name) || strlen($p_name) > 100 || strlen($p_name) < 6) {
			
			    $return = (strlen($p_name) > 100 || strlen($p_name) < 6) ? showError($TEXT['_uni-Create_a_page_err-4']) : showError($TEXT['_uni-Create_a_page_err-5']);
		    
			// Check page location for local level
			} elseif($p_type == 1 && (empty($p_address) || strlen($p_address) > 200 || strlen($p_address) < 10)) {
			
			    $return = (strlen($p_address) > 100 || strlen($p_address) < 6) ? showError($TEXT['_uni-Create_a_page_err-6']) : showError($TEXT['_uni-Create_a_page_err-7']);
		    
			// Confirmed | Create a new page
			} else {
				
				// Page processor
				$pages = new pages();

				$pages->db = $db;
				
				// Create page
				$new_page = $pages->createPage($user['idu'],$p_name,$p_type,$p_cat,$p_address);
				
				$return = ($new_page) ? '<script>locate(\''.$TEXT['installation'].'/page/'.$new_page.'\');</script>' : showError('Page creation failed !');
	
			}
	
        // Load add member wizard		
		} else {
			
			$return = showError($TEXT['_uni-Create_a_page_err-1']);
			
		}
		
	}

// No credentials	
} else {
	$return = showError($TEXT['lang_error_connection2']);
}

// Display data
echo '<script>contentLoader(0,'.$_POST['t'].');</script>'.$return;
?>