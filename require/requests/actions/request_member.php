<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global ARRAY
global $TEXT;

// User class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;	

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// try to fetch logged user
	$user = $profile->getUser();
	
	// if user exists
	if(!empty($user['idu'])){
		
		// Get chat form
		$form = $profile->getChatFormByID($_POST['v2'],$user['idu']);
		
		// Get request type(add or remove request)
		$type = ($_POST['t']) ? 1 : 0 ;
		
		// No one can add or remove administration
		if($form['form_id'] && $_POST['v3'] !== $form['form_by']) {
			
			echo $profile->requestMember($_POST['v1'],$form['form_id'],$_POST['v3'],$user['idu'],$type);
			
		} else {
			echo '<div class="brz-margin brz-card brz-small brz-round brz-padding ">'.$TEXT['_uni-You_permission'].'</div>';		
		}
	} else {
		echo showError($TEXT['lang_error_connection2']); 
	}
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>