<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/public.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

$profile = new main();

$public = new access();

$profile->db = $public->db = $db;
$profile->settings = $public->settings = $page_settings;

// Load login info
if($_POST['t'] == '1') {
	
	// Get user
	$user = $profile->getUserByID($_POST['v1']);
	
	$TEXT['temp-id'] = $user['idu'];
	$TEXT['temp-image'] = $public->getImage($user['idu'],$user['p_image'],$user['image']);
	$TEXT['temp-title'] = sprintf($TEXT['_uni-T_s_login_cnn_heading'],fixName(25,$user['username'],$user['first_name'],$user['last_name']),$TEXT['web_name']);
	
	echo display(templateSrc('/public/connect_modal'));

}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>