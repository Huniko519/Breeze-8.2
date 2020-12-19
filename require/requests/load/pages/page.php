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
	
	// Preload switcer
	$pre_load = '<script>$("#brz-GNAV_ALL").find("img").hide();</script>';
	$update_tab = '$("#page_bar_{$TEXT->TEMP_TAB_ID}").removeClass(\'brz-hvr-active\').addClass(\'brz-hvr-active-1\');';
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Load complete page
		if($_POST['ff'] == '1') {
		    
			$page = $profile->getPage($_POST['p']);
			
			// If page_access exists
			if($page) {
			
				// Get page content
				list($page,$page_user,$page_role,$page_top,$page_feeds,$about,$d_nav_items) = $profile->getPageTop($_POST['p'],$user,1,$page);
 
                // Reset
                $TEXT['TEMP-NAV-UPDATE'] = '';

            	// Add page feeds
            	$TEXT['posts'] = $page_feeds;

                // Add group id to html template
			    $TEXT['TEMP_PAGE_ID'] = $page['page_id'];
				
				// Set data for post form
				$TEXT['temp-image'] = $page['page_icon'];
				$TEXT['temp-page-image'] = $page['page_icon'];
				$TEXT['temp-page-name'] = $page['page_name'].' '.$profile->verifiedBatch($page['page_verified']);
				$TEXT['temp-page-username'] = $TEXT['temp-page-type'] = ($page['page_username']) ? '<span title="'.$TEXT['_uni-Username'].'">'.$TEXT['sign-at'].$page['page_username'].'</span>' : '' ;
		
		        // Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
		
			    $TEXT['content'] = ($page_role['page_role'] > 3) ? display(templateSrc('/main/post_form_page')):'';
			
			    // Allow update page icon
			    if($page_role['page_role'] > 3) {
				    $TEXT['TEMP-NAV-UPDATE'] = display(templateSrc('/page/page_main/picture_update_desk'));	
				}
				
			    // Parse Navigation for desktops
				$TEXT['TEMP-NAV-ITEMS'] = $d_nav_items;
				$d_nav = '<style>@media screen and (min-width:992px){#P_NAV_LEFT {display:none!important;}}.G-PAGE-NAV{display:block!important;}</style>';
				$d_nav .= display(templateSrc('/navigations/main_page'));
				$d_nav .= '<script>groupNav();</script>';

				// Display full group
				$main_body = display(templateSrc('/main/left_large')).$d_nav;
		
				// Right side body
				$TEXT['content'] = $about.$profile->getPersonalities($user,$page_settings['trendind_per_limit']);
				
				$add_tab = ($page_role['page_role'] > 4) ? display(templateSrc('/tab/page_tab')) : '';
			
            	echo $add_tab.$page_top.$main_body.display(templateSrc('/main/right_small'));				
		
				
			} else {		
				echo '<script>loadHome();</script>';
			}
			
		// Search page invites
		} elseif($_POST['ff'] == '3') {	
			
			// Get page
			$page = $profile->getPage($_POST['p']);
		
		    // Search i page exists		
		    echo ($page['page_id']) ? $profile->searchInvites($user,$_POST['v1'],$page) : '' ;			
		
        // Pages list		
		} elseif($_POST['ff'] == '2') {	
			
			// Get page class
			$pages = new pages();
			$pages->db = $db;
			$pages->followings = $profile->followings;
	
			// Add starting point
		    $from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0;
		
		    // Search i page exists		
		    echo $pages->getAllPages($user['page_feeds'],5,$from);			
		
        // Page activity log		
		} elseif($_POST['ff'] == '10') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($page_role['page_role']) {
			    
				// Get page class
				$pages = new pages();
				$pages->db = $db;
				$pages->followings = $profile->followings;
				
				// Get member requests
				$TEXT['content'] = ($from > 0) ? '' : '<div class="block-container-2 clear"><div class="block-title"><div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Activity_log'].'</div></div></div>' ;
				
			    $TEXT['posts'] = $profile->getPageLog($page['page_id'],$_POST['f'],$page_settings['group_log_per_page']);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display(templateSrc('/main/left_large')).'<script>$("#all_posts").addClass("block-container-2");</script>';
				
			} else {
			    $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_activity_log_page']); 
				echo $pre_load.display(templateSrc('/main/left_large'));
			}
			
	    // Load page feeds
	    } elseif($_POST['ff'] == '15') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
			
			// Add filter
		    $filter = (isset($_POST['t']) && in_array($_POST['t'],array(1,2))) ? $_POST['t'] : 0 ;

			if($_POST['p'] && is_numeric($_POST['p'])) {
				
				// Get page
				$page = $profile->getPage($_POST['p']);
		
				// Add group id to html template
			    $TEXT['TEMP_PAGE_ID'] = $page['page_id'];
				$TEXT['temp-image'] = $page['page_icon'];
				$TEXT['temp-page-image'] = $page['page_icon'];
				$TEXT['temp-page-username'] = $TEXT['temp-page-name'] = $page['page_name'];
		      
				// Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
				
				$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
				
                $TEXT['content'] = ($from == 0 && $page_role && $page_role['page_role'] > 3) ? display(templateSrc('/main/post_form_page')):'';
			
				$TEXT['posts'] = $profile->getPageFeeds($user,$from,$_POST['p'],NULL,1,null,$filter);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display(templateSrc('/main/left_large'));
				
			} else {
		
				// Get page class
				$pages = new pages();
				$pages->db = $db;
				$pages->followings = $profile->followings;

				$pages_feeded = $user['page_feeds'];
		
				$TEXT['posts'] = ($pages_feeded) ? $profile->getPageFeeds($user,$from,$pages_feeded,null,$filter) : bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-lang_load_no_feeds'],$TEXT['_uni-No_more_feeds_ttl']);
	
				$return = ($from > 0) ? $TEXT['posts'] : display(templateSrc('/main/left_large'));
	
            	// Add more stuff if user lands directly on groups page
            	if($_POST['bo'] == 1) {
               	
					// Get pages
					$pages_all = $pages->getAllPages($user['page_feeds'],7);
		
					// Get suggestions and trending users
					$groups = ($page_settings['groups_on_home']) ? $profile->getGroups($user['idu'],15) : '';
                	$boxed_users = ($page_settings['feature_pe_tren_on_home']) ? $profile->getBoxedUsers($user['idu']) : '';
                	$personalities = ($page_settings['feature_trending_on_home']) ? $profile->getPersonalities($user,$page_settings['trendind_per_limit']) : '';
                	$TEXT['content'] = $pages_all.$boxed_users.$groups.$personalities;
			   
                	$return .= display(templateSrc('/main/right_small'));         
           	 	}
                
            	echo $pre_load.$return ;

	    	}
			
		// Load page settings
		} elseif($_POST['ff'] == '9') {
			
		   	// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
			
			// If admin
			if($page_role['page_role'] > 4) {

				// General settings
                if($_POST['v1'] == 1) { 
				    
					// Verify Name length
			    	if(strlen($_POST['v2']) < 6 || strlen($_POST['v2']) > 32) {
			
			        	$return = showError($TEXT['_uni-Create_a_page_err-4']);
			
			    	// Page email check
					} elseif((!filter_var($db->real_escape_string($_POST['v3']), FILTER_VALIDATE_EMAIL) || strlen($_POST['v3']) < 3 || strlen($_POST['v3']) > 62)  && !empty($_POST['v3'])) {
			
			        	$return = showError($TEXT['_uni-signup-4']);
			
			    	// Page location check
					} elseif(strlen($_POST['v4'])> 50) { 

				    	$return = showError($TEXT['_uni-error_location_len23']);	
					
		        	// Page website check			
		        	} elseif((strlen($_POST['v5']) > 64 || !filter_var($_POST['v5'], FILTER_VALIDATE_URL)) && !empty($_POST['v5'])){ 
				
				    	$return = showError($TEXT['_uni-error_web_in']);	
					
					// Page description check			
					} elseif(strlen($_POST['v6']) > 2000){ 
				
				   		$return = showError($TEXT['_uni-error_bio_in3']);
					
		        	} else {
					
						$pages = new pages();
				    	$pages->db = $db;
				    
						$return = ($pages->updatePage($_POST['p'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$user['idu'])) ? showSuccess($TEXT['_uni-Settings_updated'],NULL,'loadPage('.$page['page_id'].',1,1)')  : showNotification($TEXT['_uni-No_changes']);
					}			
				
				// Update page username
				} elseif($_POST['v1'] == 3) {
					
					if(!empty($_POST['v2']) && $page['page_username'] !== $_POST['v2'] && ($profile->isUsernameExists($_POST['v2'],2))) {
			
			            // Verify whether user name exists
			            $return = showError($TEXT['_uni-Username_exists']);
			
			        // page username check
		            } elseif(!empty($_POST['v2']) && (!ctype_alnum(trim($_POST['v2'])) || is_numeric(trim($_POST['v2'])))) {
			
			            // Allow only valid chars for username
			            $return = showError($TEXT['_uni-signup-9']);
			    
				    // page username check
				    } elseif(!empty($_POST['v2']) && (strlen($_POST['v2']) < $page_settings['username_min_len'] || strlen($_POST['v2']) > $page_settings['username_max_len'])) {
			
			            $return = showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
	
				    } else {
					
					    $pages = new pages();
				        $pages->db = $db;
				    
					    $return = ($pages->updateUsername($_POST['p'],$_POST['v2'],$user['idu'])) ? showSuccess($TEXT['_uni-Settings_updated'],NULL,'loadPage('.$page['page_id'].',1,1)')  : showNotification($TEXT['_uni-No_changes']);
				
				    }
					
				// Add a new role
				} elseif($_POST['v1'] == 4) {
					
					$pages = new pages();
					$pages->db = $db;
					
					// Get target user
					$get_user = $profile->getUserByUsername($_POST['v2']);
					
					if(empty($_POST['v2']) || !$get_user['idu']) {
	
			            // Verify whether user name doesn't exists
			            $return = showError($TEXT['_uni-No_username']);
			
		            } elseif($pages->isRoleExists($_POST['p'],$get_user['idu'])) {
						
						// If role already exists
			            $return = showError($TEXT['_uni-role_exists']);
						
					} elseif(!in_array($_POST['v3'],array(2,3,4,5))) {
						
						// If role already exists
			            $return = showError($TEXT['_uni-role_type_no']);
						
					} else {
						
						$return = $pages->addRole($_POST['p'],$_POST['v3'],$get_user['idu'],$user['idu']);
						
					}
	
	            // Remove role
				} elseif($_POST['v1'] == 5) {
					
					$pages = new pages();
					$pages->db = $db;
					
					// Get target user
					$page_role_target = $profile->getPageRole($_POST['v2'],$_POST['p'],$_POST['v2']);
					
					if($page_role_target['user_id'] == $page['page_owner']) {
						$bug = 'TARGET IS FOUNDER';
					} else {
						$return = $pages->deleteRole($_POST['v2'],$_POST['p'],$page_role_target['user_id'],$user['idu']);
					}
					
					
				}
				
			} else {
				$return = showError($TEXT['_uni-settings_rights_page_fail']);
			}		
			
			echo $return.'<script>contentLoader(0,1);</script>';
		
		// Load page settings
		} elseif($_POST['ff'] == '8') {	

		   	// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
			
			// If editor or admin
			if($page_role['page_role'] > 3 ) {
				
				// Add page id
				$TEXT['TEMP_PAGE_ID'] = $page['page_id'];

				echo '<style>@media screen and (min-width:992px){#P_NAV_LEFT {display:none!important;}}.G-PAGE-NAV{display:block!important;}</style>';

				$TEXT['temp-page_id'] = $page['page_id'];

			    // General settings
				if($_POST['t'] == 0 || $_POST['t'] == 1) {
					
					$TEXT['temp-page_name'] = $page['page_name'];
					$TEXT['temp-page_email'] = $page['page_email'];
					$TEXT['temp-page_location'] = $page['page_location'];
					$TEXT['temp-page_web'] = $page['page_web'];
					$TEXT['temp-page_description'] = $page['page_description'];

					$TEXT['posts'] = display(templateSrc('/page/page_settings/general_settings'));
					
				// Username settings
				} elseif($_POST['t'] == 3) {
					
					$TEXT['temp-page_username'] = $page['page_username'];
					$TEXT['posts'] = display(templateSrc('/page/page_settings/username_settings'));
					
				} elseif($_POST['t'] == 4) {
				    
                    $TEXT['temp-page_username'] = $page['page_username'];
					$TEXT['posts'] = display(templateSrc('/page/page_settings/add_role'));					

				}
		
				$main_body = display(templateSrc('/main/left_large'));
				
				$TEXT['content'] = ($_POST['t'] == 0) ? display(templateSrc('/navigations/page_settings')) : '';
	
	            $right_body = ($_POST['t'] == 0) ? display(templateSrc('/main/right_small')) : '';
				
				echo $pre_load.$main_body.$right_body;

			
			} else {
			    echo showError($TEXT['_uni-settings_rights_page_fail']); 
			}

	    } elseif($_POST['ff'] == '11') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
			
		   	// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
			
		    // Allow user to add members on the basis of group privacy
		    if($page_role['page_role'] > 2) {
			  
			    $pages = new pages();
				$pages->db = $db;
				$pages->followings = $profile->followings;
				$pages->settings = $page_settings;
				
				$TEXT['content'] = ($from > 0) ? '' : '<div class="block-container-2 clear"><div class="block-title"><div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Page_roles'].'</div></div></div>' ;
				
			    // Get taggesd posts
			    $TEXT['posts'] = $pages->pageRoles($page,$page_role['page_role'],$from,$user);
			
			    // Add posts to main body
			    echo (!$from) ? display(templateSrc('/main/left_large')) : $TEXT['posts'];
				
			
			} else {
				$TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_roles']); 
			    echo $pre_load.display(templateSrc('/main/left_large'));
			}

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