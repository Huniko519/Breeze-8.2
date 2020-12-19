<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Verify methods
if(isset($_GET['respond']) && !empty($_GET['respond']) && isset($_GET['type']) && in_array($_GET['type'],array("activation","recover","startrecovery"))) {

	if($_GET['type'] == "activation") {

		// Build up class
		$profile = new Login;
        $profile->db = $db;	

		// Pass property username
		$profile->username = (isset($_GET['for'])) ? $_GET['for'] : NULL;

		// Fetch results
		$perform = $profile->activateProfile($_GET['respond']);

		// Generate message 
	    $TEXT['page_navigation'] = $TEXT['page_mainbody'] = '';

	    echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);
		
		// Add heading to page
        $TEXT['_temp-Head1'] = $TEXT['_uni-E-Mail_verification3'];

		// If activated
        if($perform == "ACTIVATED") {

			// Display activated message
			$TEXT['_temp-content'] = $TEXT['_uni-E-Mail_verification2'];
			echo display('themes/'.$TEXT['theme'].'/html/modals/respond'.$TEXT['templates_extension']);
			
		} else {

			// Else return error
			$TEXT['_temp-content'] = $perform ;
			echo display('themes/'.$TEXT['theme'].'/html/modals/respond'.$TEXT['templates_extension']);
			
		}
	
	// else if password recovery is requested
	} elseif($_GET['type'] == "startrecovery") {
		
		// Generate message 
	    $TEXT['page_navigation'] = $TEXT['page_mainbody'] = '';
	    echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);	

        // Add header	
		$TEXT['_temp-Head1'] = $TEXT['_uni-Reset_password_ttl'];

		// Build up class
		$profile = new main;
        $profile->db = $db;	

		// Send instructions		
		$TEXT['_temp-content'] = $profile->sendRecovery($_GET['for'],$page_settings); ;

		// Display message
		echo display('themes/'.$TEXT['theme'].'/html/modals/respond'.$TEXT['templates_extension']);
	
	// else if password recovery is in progress complete it	
	} elseif($_GET['type'] == "recover") {

		// Generate message 
	    $TEXT['page_navigation'] = $TEXT['page_mainbody'] = '';
	    echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);	

        // Add header	
		$TEXT['_temp-Head1'] = $TEXT['_uni-Reset_success_ttl'];

		// Build up class
		$profile = new main;
        $profile->db = $db;	

		// Reset password if salt matches
		if($profile->saltMatches($_GET['for'],$_GET['respond']) == 1) {

			// Reset
			$TEXT['_temp-content'] = $profile->resetPassword($_GET['for'],$page_settings); ;

			// Display results
			echo display('themes/'.$TEXT['theme'].'/html/modals/respond'.$TEXT['templates_extension']);
	
		} else {	
			// Redirect the visitor to home
		    header("Location: ".$TEXT['installation']);		
		}			
	} else {
		// Redirect the visitor to home
		header("Location: ".$TEXT['installation']);
	}		
} else {
	// Redirect the visitor to home
	header("Location: ".$TEXT['installation']);	
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>