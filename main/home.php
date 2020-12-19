<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Import user management class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;

// Check logged user
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
    
	// Pass user properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// If user is logged and exists
	if(!empty($user['idu'])) {
		
		// Generate Main body with navigation
		$TEXT['page_navigation'] = $profile->genNavigation($user);
		
		// Add SVG loader to body
		$TEXT['page_mainbody'] = display(templateSrc('/main/home_loader'));

        // Display body						
		echo display(templateSrc('/main/main'));	
		
		// Add more properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Check URL
		if(isset($_GET['p']) && in_array($_GET['p'],array("chats","notifications","blogs","popular","ads","feeds","page","groups","pages","edit","trends","followers","group","followings","search","profilesearch"))) {

			$val = (isset($_GET['v']) && !empty($_GET['v']) && !isXSSED($_GET['v'])) ? $db->real_escape_string($_GET['v']) : FALSE ;
			
			// Use JS functions to load requested content
			if($_GET['p'] == "followings") {
				echo '<script>loadRelatives(1);</script>';			
			} elseif($_GET['p'] == "edit") {
				echo '<script>getSettings(1);</script>';			
			} elseif($_GET['p'] == "popular") {
				echo '<script>loadDiscover();</script>';			
			} elseif($_GET['p'] == "blogs") {
				echo '<script>loadBlogData(0,7,1);</script>';			
			} elseif($_GET['p'] == "groups") {
				echo '<script>groupFeeds(0,0,1);</script>';			
			} elseif($_GET['p'] == "ads") {
				echo '<script>loadAdsManager();</script>';			
			} elseif($_GET['p'] == "pages") {
				echo '<script>pageFeeds(0,0,1);</script>';			
			} elseif($_GET['p'] == "trends") {
				echo '<script>loadTrending(0,0,1);</script>';			
			} elseif($_GET['p'] == "notifications") {
				echo '<script>loadNotifications();</script>';			
			} elseif($_GET['p'] == "followers") {
				echo '<script>loadRelatives(0);</script>';			
			} elseif($_GET['p'] == "followings") {
				echo '<script>loadRelatives(1);</script>';		
			} elseif($_GET['p'] == "chats") {
				echo '<script>loadChats(0,0,1);</script>';		
			} elseif($_GET['p'] == "group" && isset($_GET['type'])) {

				// Check whether group exists
				$group_access_type = (is_numeric($_GET['type'])) ? NULL : 1;
			
				$group = $profile->getGroup($_GET['type'],$group_access_type);		
				
				echo ($group) ? '<script>loadGroup('.$group['group_id'].',1,1);</script>' : '<script>groupFeeds(0,0,1);</script>';	

			} elseif($_GET['p'] == "page" && isset($_GET['type'])) {

				// Check whether group exists
				$page_access_type = (is_numeric($_GET['type'])) ? NULL : 1;
			
				$page = $profile->getPage($_GET['type'],$page_access_type);		
				
				echo ($page) ? '<script>loadPage('.$page['page_id'].',1,1);</script>' : '<script>pageFeeds(0,0,1);</script>';	

			} elseif($val && $_GET['p'] == "search") {				
				
				if(isset($_GET['type'])) {
                	
					// Select proper search type
					switch ($_GET['type']) {	
						
						case 'page':
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,7);</script>';
							break;
						
						case 'people':
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,1);</script>';
							break;
						
						case 'group':
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,2);</script>';
							break;
							
						case 'tag':
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,3);</script>';
							break;
						
						case 'at':
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,4);</script>';
							break;
					
						case 'me':
							echo '<script>$(\'#w2rsdf\').val(\''.$val.'\');searchProfile(0,1,5);</script>';
							break;
						
						case 'video':
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,6);</script>';
							break;
							
						default:
							echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,0);</script>';
							break;
					}

                // Else general search					
				} else {
				    echo '<script>$(\'.swsef89u3hj89sd\').val(\''.$val.'\');search(0,1,0);</script>';
				}
				
			// If nothing set load home
			} else {
				echo '<script>loadHome();</script>';			
			}
			
		// Load home in current request
		} else {
            echo '<script>loadHome(1);</script>';
        }
		
		// Add notifications type
		require_once('./require/requests/content/add_notifications_type.php');
		echo $function = notifications($user['n_type'],'/require/requests/content/active_notifications.php','/require/requests/content/active_inbox.php') ;
		
    // Display homepage(WRONG COOKIES SET)
	} else {		
		$need_home = 1;
	}	
} else {
	// Parse ads for new visitor
	$parsedAdd = (isset($_SESSION['loggedout'])) ? $profile->parseAdd($page_settings['po_add_out']) : $profile->parseAdd($page_settings['po_add_visit']);
	$need_home = 1;	
}

if(isset($need_home) && $need_home) {
	
	// Get recent logins
	$TEXT['content_main_page'] = (isset($_COOKIE['loggedout'])) ? $profile->getRecentLogins($_COOKIE['loggedout']):$TEXT['content_main_page'];
    
	// Display homepage
    echo display('themes/'.$TEXT['theme'].'/html/home/home'.$TEXT['templates_extension']).$parsedAdd;

}

// Refresh all JS PLUGINS
echo '<script>refreshElements();</script>' ;

if(isset($db) && $db) {
	mysqli_close($db);
}
?>