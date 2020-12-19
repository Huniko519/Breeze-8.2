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

// Create express bar
$express_open = '<div id="right_express" class="express noflow clear hide-small">';
$express_close = '</div>';

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])){
		
		// Pass settings and user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Fetch friends suggestions
		$express_suggests = ($page_settings['feature_expresssuggestions']) ? $profile->expressSuggestions($user['idu'],$page_settings['suggestions_limit']) : '' ;
		
		// Fetch friends activity
		$express_activity_content = ($page_settings['feature_expressactivity']) ? $profile->expressActivity($user['idu']) : '';
		$express_activity = (empty($express_activity_content)) ? '<div id="EXPRESS_ACTIVITY" style="max-height:220px!important;" class="clear express-in nicescrollit bottom-divider"></div>': '<div id="EXPRESS_ACTIVITY" style="max-height:180px!important;" class="clear express-in nicescrollit bottom-divider">'.$express_activity_content.'</div>';
		
		// Fetch chats
		$express_friends_content = ($page_settings['feature_expressfriends']) ? $profile->expressChats($user,$page_settings['active_limit']) : '';
		$express_friends = (empty($express_friends_content)) ? '<div id="EXPRESS_FRIENDS" style="max-height:220px!important;" class="bclear express-in nicescrollit bottom-divider"></div>': '<div id="EXPRESS_FRIENDS" style="max-height:240px!important;" class="clear express-in nicescrollit bottom-divider">'.$express_friends_content.'</div>';

	    // Add express show
		echo ($page_settings['feature_expresssuggestions'] || $page_settings['feature_expressactivity'] || $page_settings['feature_expressfriends']) ? $express_open.$express_suggests.$express_activity.$express_friends.$express_close.'<script>$(".nicescrollit").niceScroll();</script>' : '';

	} else {
		$add_widgets = true;
	}
	
} else {
	$add_widgets = true;
}

// Prevent widget for admin
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    echo '';
} elseif(isset($add_widgets) && $add_widgets) {
	
	$TEXT['temp-title'] = $TEXT['_uni-Blog_signup_1'] ;
	$TEXT['temp-info'] = $TEXT['_uni-Blog_signup_11'] ;
	$TEXT['temp-widget_color'] = 'theme';
	$TEXT['temp-widget_class'] = 'mentions';
	$TEXT['temp-js'] = 'locate(\''.$TEXT['installation'].'\');';
	$TEXT['temp-href'] = '#';
    $TEXT['temp-btn'] = $TEXT['_uni-Get_started'];

	// Generate widget from template 
	echo $express_open.display(templateSrc('/modals/widget')).$express_close;
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>