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
	
// Clear login from recents
if($_POST['t'] == 1) {
	if(!empty($_POST['v1'])) {
	
	    // Gather recent logins and remove the requested login
		$recent_logins = (!empty($_COOKIE['loggedout'])) ? explode(',', preg_replace('/,{2,}/', ',', trim(str_replace('ID_'.$_POST['v1'].'_ID','',$_COOKIE['loggedout']), ','))) : NULL ;

		$login_2 = ($recent_logins[0]) ? $recent_logins[0] : '' ;
		$login_3 = ($recent_logins[1]) ? ','.$recent_logins[1] : '' ;
		
        // Save remaining loins
		setcookie("loggedout", $login_2.$login_3, time() + 30 * 24 * 60 * 60,'/');
	
		echo '';
	
	}	
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>