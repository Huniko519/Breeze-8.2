<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials set
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} elseif(isset($_POST['v1'])) {
		
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
	
		// Get chatting window
	    $TEXT['content'] = $profile->chatWindow($user,$_POST['v1']);

		// Display full body		
		$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).display('../../../themes/'.$TEXT['theme'].'/html/chats/chat_extras'.$TEXT['templates_extension']);
	  
	    $TEXT['content'] = $profile->getChatForm($user,$_POST['v1'],$db);

		// Display right body		
		$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small_no_lang'.$TEXT['templates_extension']);
		
		// Temprary hide express bar
		echo $main_body.$right_body;
	}
// Welcome page for administration
} else {
	echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>