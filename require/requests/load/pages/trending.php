<?php
session_start();

require_once("../../../../main/config.php");        // Import configuration
require_once('../../../main/database.php');         // Import database connection
require_once('../../../main/classes.php');          // Import all classes
require_once('../../../main/settings.php');         // Import settings
require_once('../../../main/presets/presets.php');  // Import all arrays
require_once('../../../../language.php');           // Import language

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
		
		// Add starting point
		$from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0 ;

		// Add filter
		$filter = (isset($_POST['v1']) && in_array($_POST['v1'],array("1","2","3","4","5"))) ? $_POST['v1'] : 1 ;
	
		// If page is requested or the first time add title and ads
		if($from == 0) {
			
			// Add page title
			$add_title = ($filter == "1") ? '<script>document.title = \''.$TEXT[$title_set3[$filter]].' | '.$page_settings['title'].'\';</script>':'';
				
			// Get Content box
			$TEXT['temp_standard_content'] = $profile->getTrending($from ,$filter);
			$TEXT['temp_standard_title'] = $TEXT[$title_set3[$filter]];
			$TEXT['temp_standard_title_img'] = 'photos-big';
			$TEXT['temp_standard_id'] = 'trending-box-main';
			
			// Get trends
		    $TEXT['posts'] = display(templateSrc('/main/standard_box'));
			
			// Reset
			$TEXT['content'] = '';
			
			// Set to results
			$trends = display(templateSrc('/main/left_large'));
		
			// Parse ads
			$ads = ($filter == "1") ? $profile->parseAdd($page_settings['po_add_trending']) : '';    // Pop-up
			$ads .= ($filter == "1") ? $profile->parseAdd($page_settings['fi_add_trending'],1):''; // Fixed
			$ads .= (substr($page_settings['bible_view'],10,1)) ? getBible() : '';
			
			// Add filter modal and title
			$add_filters = ($filter == "1") ? display(templateSrc('/modals/filter_trending')) : '';
			
			$TEXT['content'] = $add_filters.$ads.$add_title;
		
			// Bind all ads and filters
			$filters = ($filter == "1") ? display(templateSrc('/main/right_small')) : $TEXT['content'] ;
		
		} else {
            // fetch more trends		
			$filters = '';
			$trends = $profile->getTrending($from ,$filter);
		}
		
		// Display results
		echo $trends.$filters ;	
	}
} else {
	// no credentials set
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>