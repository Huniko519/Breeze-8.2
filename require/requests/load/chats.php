<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once("../../main/settings.php");         // Import settings
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/presets/presets.php');  // Import presets
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

// Check user credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials set
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass user and administration settings
		$profile->followings = $profile->listFollowings($user['idu']);

		// load side chat form
		if($_POST['ff'] == "side-chat") {

			echo $profile->chatWindow($user,$_POST['v1'],'/chats/side_chat');

		} else {

			// Add starting point
			$from = (isset($_POST['f'])) ? $_POST['f'] : 0 ;

			// Add filter to reports
			$filter = (isset($_POST['v1']) && in_array($_POST['v1'],array("1","2","3","4"))) ? $_POST['v1'] : '0' ;

			// Add title
			$title = (isset($title_set1[$filter])) ? $TEXT[$title_set1[$filter]] :  $TEXT[$title_set1[0]] ;

			// Apply type
			if($from == 0) {
				
				// Add page title
				$add_title = '<script>document.title = \''.$title.' \';</script>';
						
				// Get chats
				$TEXT['posts']   = $profile->getChats($user,$from,$filter);
				
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
				
				// Get Content box
				$TEXT['temp_standard_content'] = $TEXT['posts'];
				$TEXT['temp_standard_title'] = $title;
				$TEXT['temp_standard_title_img'] = 'chats-big';
				$TEXT['temp_standard_id'] = 'chats-box-main';
				
				$TEXT['posts'] = display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box'.$TEXT['templates_extension']);
				
				$main_body = (isset($_POST['v2']) && $_POST['v2'] == '1') ?  display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']):$TEXT['posts'];
				
				// Get chat filters & active user
				if(isset($_POST['v2']) && $_POST['v2'] == '1') {
					
					$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_chats'.$TEXT['templates_extension']);
				
					$TEXT['content'] .=  $profile->activeUsers($user['idu']) ;
					
					// Bind filters to right body
					$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);		

				} else {
					$right_body ='';
				}

				// Display full body
				echo $main_body.$right_body.$add_title;
				
			} else {
				
				// Else display chats only
				echo $profile->getChats($user,$from,$filter);
				
			}
		}
		
	}
// No credentials set
} else {
	echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>