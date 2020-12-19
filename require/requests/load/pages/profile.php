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
	
	// Pass properties to fetch logged user if exists
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// Pass administration settings
	$profile->settings = $page_settings;
	
	// If user doesn't exists
    if (empty($user['idu'])) {
        echo showError($TEXT['lang_error_connection2']);
    } elseif(Blocker::IsTypeBlocked(Blocker::GetBlock($user['idu'], $_POST['p']), 'profile')) {
		echo showError($TEXT['_uni-Profile_Blocked']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);

		// Validate inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] > 0) {
	
			// Get user top and intro
			list($profile_top,$intro) = $profile->getProfileTop($_POST['p'],$user,1);
			
			$get_user =  $profile->getUserByID($_POST['p']);
			
			// Reset
			$edit = $TEXT['content'] = '' ;
		
			if($get_user['pin']) $TEXT['content'] .= $profile->getPost($get_user['pin'],$user,null,null,1);
		    
			// Add update cover and photo buttons if user id matches
			if($_POST['p'] == $user['idu']) {
				
				// Add image
				$TEXT['temp-image'] = $user['image'];
		
				// Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
		
				$TEXT['content'] .= display(templateSrc('/main/post_form'));
				$edit = '<script>
							$("#uPc-f-2").on(\'change\', function(event){	
								$(document).on(\'change\', \':file\', function () {
									if($("#uPc-f-2").val()) {
										// Start buttonloader
										smartLoader(1,\'#btn-cover-chn\');
	
										// Submit photo form	
   		 								document.getElementById("uPc-2").submit();				
									}
								});
							}); 
							$("#uPp-f-1").on(\'change\', function(event){	
								$(document).on(\'change\', \':file\', function () {
									if($("#uPp-f-1").val()) {
				
										smartLoader(1,\'#btn-photo-chn\');
	
										// Submit photo form	
   	 									document.getElementById("uPp-1").submit();
									}
								});
							});
						</script>';
			}
			

			// fetch feeds 
		    $TEXT['posts']   = $profile->getFeeds(0,$_POST['p'],NULL,1);
  
			// Display full profile
			$main_body = display(templateSrc('/main/left_large'));
  		
		    $bible_verse = (substr($page_settings['bible_view'],2,1)) ? getBible() : '';
			
			// Get some stuff
			$TEXT['content'] = $intro.$bible_verse.$profile->getBoxedUsers($user['idu']);
		
			echo $profile_top.$edit.$main_body.display(templateSrc('/main/right_small'));
			
		} else {
			// Invalid inputs
			echo showError($TEXT['lang_error_script1']);
		}
	}

// No credentials	
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>