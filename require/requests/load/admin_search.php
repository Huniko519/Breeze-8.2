<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/admin.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New administration class
$profile = new manage();
$profile->db = $db;	

// Verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration doesn't exists go to homepage
	if(empty($admin['id'])) {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
	} else {
		
		// Pass settings
	    $profile->settings = $page_settings;
		
		// Validate JS inputs
		if(isset($_POST['v1']) && !empty($_POST['v1'])) {
		    
			// Add starting point
		    $from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
		    // Add filter to search
		    $filter = (isset($_POST['v2']) && in_array($_POST['v2'],array("1","2","3","4"))) ? $_POST['v2'] : '0' ;

			// Apply type
			if($from == 0) {
	
				// Get search
		    	$TEXT['posts'] = $profile->search($from,$_POST['v1'],$filter);
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		    
				// Get search filters 
				$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_search_admin'.$TEXT['templates_extension']);
				$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
			
				// Bind and display full body
				echo $main_body.$right_body;
			
			// Else get search only
			} else {
				echo $profile->search($from,$_POST['v1'],$filter);
			}	
        // Empty search			
		} else {
			echo showNotification($TEXT['_uni-sorry_no_users']);	
		}
	}	
// Go to homepage administration is not logged
} else {
	echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>