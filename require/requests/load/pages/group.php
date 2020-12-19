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
	$pre_load = '<script>$("#brz-add-GNav-'.$_POST['ff'].'").find("img").hide();</script>';
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Load group main page
		if($_POST['ff'] == '1') {
		    
			$group = $profile->getGroup($_POST['p']);
			
			// If group exists
			if($group) {
			
				// Get group content
				list($group,$group_user,$group_top,$group_feeds,$about,$d_nav_items) = $profile->getGroupTop($_POST['p'],$user,1);
 
            	// Add group feeds
            	$TEXT['posts'] = $group_feeds;

                // Add group id to html template
			    $TEXT['TEMP_GROUP_ID'] = $group['group_id'];
				
				// Set data for post form
				$TEXT['temp-image'] = $user['image'];
				$TEXT['temp-group-image'] = getCoverSrc(str_replace('rep_','',$group['group_cover']));
				$TEXT['temp-group-name'] = $group['group_name'];
				$TEXT['temp-group-type'] = $profile->getGroupHeading($group['group_privacy']);
		
		        // Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
		
			    $TEXT['content'] = ($group_user['group_status'] == 1 && $group_user['p_post'] == 0 && ($group_user['group_role'] == 2 || $group['group_posting'] == 1)) ? display(templateSrc('/main/post_form_group')):'';
			
			    // Parse Navigation for desktops
				$TEXT['TEMP-NAV-ITEMS'] = $d_nav_items;
				$TEXT['TEMP-NAV-SHORTCUTS'] = $profile->getShortcuts($user['idu'],$group_user['group_id'],5);
				$d_nav = '<style>@media screen and (min-width:992px){#P_NAV_LEFT {display:none!important;}}.G-PAGE-NAV{display:block!important;}</style>';
				$d_nav .= display(templateSrc('/navigations/main_group'));
				$d_nav .= '<script>groupNav();</script>';

				// Display full group
				$main_body = display(templateSrc('/main/left_large')).$d_nav;
		
				// Right side body
				$TEXT['content'] = $about.$profile->getPersonalities($user,$page_settings['trendind_per_limit']);
			
            	echo $group_top.$main_body.display(templateSrc('/main/right_small'));				
		
				
			} else {		
				echo '<script>loadHome();</script>';
			}
	
        // Load add member wizard		
		} elseif($_POST['ff'] == '2') {		    
			
			$TEXT['temp-p_val'] = $_POST['p'];
			echo display(templateSrc('/group/group_main/add_members'));
			
		// Group search for add members
		} elseif($_POST['ff'] == '3') {	
			
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    echo $profile->searchMembers($user,$_POST['v1'],$group['group_id'],2,$_POST['f']);			
			} else {
			    echo $TEXT['_uni-No_allow_add_members'];
			}
			
		// Load remove member wizard		
		} elseif($_POST['ff'] == '4') {		    
			
			$TEXT['temp-p_val'] = $_POST['p'];
			echo display(templateSrc('/group/group_main/remove_members'));
			
		// Group search for remove members
		} elseif($_POST['ff'] == '5') {	
			
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    echo $profile->searchMembers($user,$_POST['v1'],$group['group_id'],3);			
			} else {
			    echo $TEXT['_uni-No_allow_add_members'];
			}
			
		// Load member requests
		} elseif($_POST['ff'] == '6') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;
				
				// Get member requests
				$TEXT['content'] = ($from > 0) ? '' : '<div class="block-container-2 clear"><div class="block-title"><div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Requests'].'</div></div></div>' ;
				
			    $TEXT['posts'] = $group_functions->getRequests($user,$group['group_id'],$_POST['f'],$page_settings['group_requests_per_page']);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display(templateSrc('/main/left_large')).'<script>$("#all_posts").addClass("block-container-2");</script>';
				
			} else {
			    $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_add_members']); 
			    echo $pre_load.display(templateSrc('/main/left_large')); 
			}
	
		// Load group log
		} elseif($_POST['ff'] == '10') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_status'] == 1 && ($group_user['p_activity'] || $group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'])) {
			    
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;
				
				// Get member requests
				$TEXT['content'] = ($from > 0) ? '' : '<div class="block-container-2 clear"><div class="block-title"><div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Activity_log'].'</div></div></div>' ;
				
			    $TEXT['posts'] = $profile->getGroupLog($user,$group['group_id'],$_POST['f'],$page_settings['group_log_per_page']);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display(templateSrc('/main/left_large')).'<script>$("#all_posts").addClass("block-container-2");</script>';
				
			} else {
			    $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_activity_log']); 
				echo $pre_load.display(templateSrc('/main/left_large'));
			}
			
		// Load group feeds
	    } elseif($_POST['ff'] == '15') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		    
			if($_POST['p'] && is_numeric($_POST['p'])) {
				// Fetch group user
				$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
				// Get group
				$group = $profile->getGroup($_POST['p']);
		
		    	// Allow user to add members on the basis of group privacy
		    	if($group_user['group_status'] == 1 || $group['group_privacy'] == 1) {
                
				    // Add group id to html template
			        $TEXT['TEMP_GROUP_ID'] = $group['group_id'];
					$TEXT['temp-image'] = $user['image'];
					$TEXT['temp-group-image'] = getCoverSrc(str_replace('rep_','',$group['group_cover']));
					$TEXT['temp-group-name'] = $group['group_name'];
		            
					// Parse background images
				    parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
				
                    $TEXT['content'] = ($from == 0 && $group_user['group_status'] == 1 && $group_user['p_post'] == 0 && ($group_user['group_role'] == 2 || $group['group_posting'] == 1)) ? display(templateSrc('/main/post_form_group')):'';
			
					$TEXT['posts'] = $profile->getGroupFeeds($user,$from,$_POST['p'],NULL,1);
				
					echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display(templateSrc('/main/left_large'));
				
				} else {
			    	$TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf3']); 
					echo $pre_load.display(templateSrc('/main/left_large'));
				}

			} else {
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;

				$groups_joined = $user['group_feeds'];
				
				$TEXT['posts'] = ($groups_joined) ? $profile->getGroupFeeds($user,$from,$groups_joined) : bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-No_more_feeds'],$TEXT['_uni-No_more_feeds_ttl']);	
				
				$return = ($from > 0) ? $TEXT['posts'] : display(templateSrc('/main/left_large'));
		       
                // Add more stuff if user lands directly on groups page
                if($_POST['bo'] == 1) {
					
					// Get groups
					$groups_all = $group_functions->getAllGroups($user['group_feeds'],7);
					
                    // Get suggestions and trending users
                    $groups = ($page_settings['groups_on_home']) ? $profile->getGroups($user['idu'],15) : '';
                    $boxed_users = ($page_settings['feature_pe_tren_on_home']) ? $profile->getBoxedUsers($user['idu']) : '';
                    $personalities = ($page_settings['feature_trending_on_home']) ? $profile->getPersonalities($user,$page_settings['trendind_per_limit']) : '';
                    $TEXT['content'] = $groups_all.$boxed_users.$groups.$personalities;
			   
                    $return .= display(templateSrc('/main/right_small'));         
                }
                
                echo $pre_load.$return ;

			}
	
	    // Load user permissions
		} elseif($_POST['ff'] == '12') {	
			
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
			
			// fetch target user
			$group_target = $profile->getGroupUser($_POST['v1'],$_POST['p'],$_POST['v1']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_target['group_status'] == '1' && $group['group_owner'] !== $group_target['user_id'] && ($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_approval_type'] == 1 && $group_user['group_role'] == 1))) {
			    $TEXT['temp-v1'] = $_POST['v1'];
			    $TEXT['temp-group_id'] = $group['group_id'];
			    $TEXT['temp-p_post'] = $group_target['p_post'];
			    $TEXT['temp-p_cover'] = $group_target['p_cover'];
			    $TEXT['temp-p_activity'] = $group_target['p_activity'];
			    $TEXT['temp-group_role'] = $group_target['group_role'];
				$TEXT['temp-inner_content'] = display(templateSrc('/group/group_main/edit_member'));
			} elseif($group['group_owner'] == $group_target['user_id']) {
			    $TEXT['temp-inner_content'] = showBox($TEXT['_uni-Group_fnder_edit_no']); 
			} else {
				$TEXT['temp-inner_content'] = showBox($TEXT['_uni-User_exists_group']);			
			}
			
			echo display(templateSrc('/group/group_main/edit_member_combine'));

	    // Load create new group
		} elseif($_POST['ff'] == '14') {	
			
			echo display(templateSrc('/group/group_main/create_group')); 	
		
		// Create new group
		} elseif($_POST['ff'] == '16') {	
		    
			if(isXSSED($_POST['v2']) || strlen($_POST['v2']) < 6 || strlen($_POST['v2']) > 32) {
			    
				$return = showError($TEXT['_uni-group_val-1']);
		
			// Veriy check box data
		    } elseif(!in_array($_POST['v3'],array("1","2")) || !in_array($_POST['v4'],array("1","2","3"))) {

				$return = showError($TEXT['_uni-error_script']);
	
			} else {
				
				// Get group functions
				$group_functions = new groups();
				$group_functions->db = $db;
				
				$new_group = $group_functions->createGroup($_POST['v2'],$_POST['v3'],$_POST['v4'],$user['idu']);
				
				$return = (!$new_group) ? showError($TEXT['_uni-error_script']) : '<script>loadModal(0);loadGroup('.$new_group.',1,1);</script>';
			
			}
			
			echo $return.'<script>contentLoader(0,3);</script>';
			
		// Load members
		} elseif($_POST['ff'] == '11') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		    	
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || $group['group_privacy'] !== 3) {
			  
			    $TEXT['posts'] = $profile->getGroupMembers($group,$_POST['f'],$group_user,$user['idu']);
				
				if($from > 0) {
					echo $pre_load.$TEXT['posts'];
				} else {
		    
					// Get Content box
					$TEXT['temp_standard_content'] = $TEXT['posts'];
					$TEXT['temp_standard_title'] = $TEXT['_uni-Members'];
					$TEXT['temp_standard_title_img'] = 'people';
					$TEXT['temp_standard_id'] = 'people-box-main';
			
					$TEXT['posts'] = display(templateSrc('/main/standard_box'));
			
					// Full page
					$main_body = display(templateSrc('/main/left_large'));
			   	
					// Display body
					echo $pre_load.$main_body;		
				}	
			
			} else {
				$TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_activity_log']); 
			    echo $pre_load.display(templateSrc('/main/left_large'));
			}
	
		// Process member requests
		} elseif($_POST['ff'] == '7') {

		    // Allow || Remove member request
		    $type = ($_POST['v2']) ? 1 : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;

				// Check user status
			    $target_user = $profile->getGroupUser($_POST['v1'],$_POST['p']);
			
			    // Update request
				echo $group_functions->processRequest($group['group_id'],$user['idu'],$profile->getUserByID($_POST['v1']),$type,$target_user);
				
		    } else {
			    echo showError($TEXT['_uni-No_allow_add_members']); 
			}
		
        // Save group settings		
	    } elseif($_POST['ff'] == '9') {	
			
			    // Fetch group user
			    $group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			    // Get group
			    $group = $profile->getGroup($_POST['p']);
		
		        // Allow user to save settings
		        if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu']) {
				
				    if(isXSSED($_POST['v1']) || isXSSED($_POST['v2']) || isXSSED($_POST['v3']) || isXSSED($_POST['v4']) || isXSSED($_POST['v5']) || isXSSED($_POST['v6']) || isXSSED($_POST['v7']) || isXSSED($_POST['v8']) || isXSSED($_POST['v9'])) {
			
					    // If values doesn't meets security requirements
					    $return = showError($TEXT['_uni-error_xss']);
			
			        // Group usernamename check
				    } elseif(!empty($_POST['v1']) && $group['group_username'] !== $_POST['v1'] && ($profile->isUsernameExists($_POST['v1'],1))) {
			    
			            // Verify whether user name exists
			            $return = showError($TEXT['_uni-Username_exists']);
			
			        // Group usernamename check
		            } elseif(!empty($_POST['v1']) && (!ctype_alnum(trim($_POST['v1'])) || is_numeric(trim($_POST['v1'])))) {
			
			            // Allow only valid chars for username
			            $return = showError($TEXT['_uni-signup-9']);
			    
				    // Group usernamename check
				    } elseif(!empty($_POST['v1']) && (strlen($_POST['v1']) < $page_settings['username_min_len'] || strlen($_POST['v1']) > $page_settings['username_max_len'])) {
			
			            $return = showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
			
			        // Group name check
				    } elseif(strlen($_POST['v2']) < 6 || strlen($_POST['v2']) > 32) {
			
			            $return = showError($TEXT['_uni-group_val-1']);
			
			        // Group email check
				    } elseif((!filter_var($db->real_escape_string($_POST['v3']), FILTER_VALIDATE_EMAIL) || strlen($_POST['v3']) < 3 || strlen($_POST['v3']) > 62)  && !empty($_POST['v3'])) {
			
			            $return = showError($TEXT['_uni-signup-4']);
			
			        // Group location check
				    } elseif(strlen($_POST['v4'])> 32) { 

				        $return = showError($TEXT['_uni-error_location_len']);	
					
		            // Group website check			
		            } elseif((strlen($_POST['v5']) > 64 || !filter_var($_POST['v5'], FILTER_VALIDATE_URL)) && !empty($_POST['v5'])){ 
				
				        $return = showError($TEXT['_uni-error_web_in']);	
					
				    // Group description check			
				    } elseif(strlen($_POST['v6']) > 2000){ 
				
				        $return = showError($TEXT['_uni-error_bio_in2']);
					
				    // Veriy check box data
		            } elseif(!in_array($_POST['v7'],array("1","2")) || !in_array($_POST['v8'],array("1","2")) || !in_array($_POST['v9'],array("1","2","3"))) {

					    $return = showError($TEXT['_uni-error_script']);
					
				    } else {
					
					    $group_functions = new groups();
				        $group_functions->db = $db;
				    
					    $return = ($group_functions->updateGroup($_POST['p'],$_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7'],$_POST['v8'],$_POST['v9'],$user['idu'])) ? showSuccess($TEXT['_uni-Settings_updated'],NULL,'loadGroup('.$group['group_id'].',1,1);')  : showNotification($TEXT['_uni-No_changes_detected']);
				    }
				
				    echo $return.'<script>contentLoader(0,1);</script>';
			    } else {
			        echo showError($TEXT['_uni-No_allow_add_members']); 
			    }
			
	    // Save member permissions
	    } elseif($_POST['ff'] == '13') {	
			
			    // Fetch group user
			    $group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
		        // fetch target user
			    $group_target = $profile->getGroupUser($_POST['v1'],$_POST['p'],$_POST['v1']);
			
			    // Get group
			    $group = $profile->getGroup($_POST['p']);
		
		        // Allow user to save settings
		        if(($group_target['group_status'] == 1 && $group_target['user_id'] !== $group['group_owner']) && ($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'])) {
				
				    if(isXSSED($_POST['v2']) || isXSSED($_POST['v3']) || isXSSED($_POST['v4']) || isXSSED($_POST['v5'])) {
			
					    // If values doesn't meets security requirements
					    $return = showError($TEXT['_uni-error_xss']);
			
				    // Veriy check box data
		            } elseif(!in_array($_POST['v2'],array("1","0")) || !in_array($_POST['v3'],array("1","0"))  || !in_array($_POST['v4'],array("1","0")) || !in_array($_POST['v5'],array("1","2"))) {

					    $return = showError($TEXT['_uni-error_script']);
					
				    } else {
					
					    // Get group functions
					    $group_functions = new groups();
				        $group_functions->db = $db;
				    
					    $return = ($group_functions->updateGroupMember($_POST['p'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$group_target['gid'])) ? showSuccess($TEXT['_uni-Settings_updated']) : showNotification($TEXT['_uni-No_changes']);
				    }
				
				
			    } elseif($group_target['user_id'] == $group['group_owner']) {
			        $return = showError($TEXT['_uni-Group_fnder_edit_no']);
			    } else {
			        $return = ($group_user['group_role'] == 1 || $group_user['group_status'] == 2) ? showError($TEXT['_uni-edit_user_rights_group_fail']) : showError($TEXT['_uni-User_exists_group']); 
			    }

                echo $return.'<script>contentLoader(0,2);</script>';			
			
        // Edit group settings		
	    } elseif($_POST['ff'] == '8') {	
			
			    // Fetch group user
			    $group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			    // Get group
			    $group = $profile->getGroup($_POST['p']);
	
		        if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu']) {
					
					$TEXT['temp-group_id'] = $group['group_id'];
					$TEXT['temp-group_name'] = $group['group_name'];
					$TEXT['temp-group_email'] = $group['group_email'];
					$TEXT['temp-group_location'] = $group['group_location'];
					$TEXT['temp-group_username'] = $group['group_username'];
					$TEXT['temp-group_description'] = $group['group_description'];
					$TEXT['temp-group_approval_type'] = $group['group_approval_type'];
					$TEXT['temp-group_posting'] = $group['group_posting'];
					$TEXT['temp-group_privacy'] = $group['group_privacy'];
			    
				    $TEXT['posts'] = display(templateSrc('/group/group_main/group_settings')) ;
					
				    echo $pre_load.display(templateSrc('/main/left_large'));		    
			    } else {
			        $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-settings_rights_group_fail']);
			        echo $pre_load.display(templateSrc('/main/left_large'));				
			    }
			
	    // Groups list		
        } elseif($_POST['ff'] == '17') {	
		
		    // Get page class
		    $groups = new groups();
		    $groups->db = $db;
		    $groups->followings = $profile->followings;
	
		    // Add starting point
		    $from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0;
		
		    // Search if group exists		
		    echo $groups->getAllGroups($user['group_feeds'],5,$from);	

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