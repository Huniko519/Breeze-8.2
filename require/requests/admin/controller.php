<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/admin.php');            // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New administration class
$profile = new manage();
$profile->db = $db;

// If SESSIONS set verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged and exists
	if(!empty($admin['id'])) {
		
		if(in_array($_POST['i'],array("maj_set","con_set","fea_set","reg_set","plu_set","spo_set"))) {
			
			// If saving, load new presets
			if($_POST['t'] == "save") {
            	require_once('../../main/presets/admin_save.php');	
			} elseif($_POST['t'] == "load") {
				require_once('../../main/presets/admin_load.php');
			}
		}
	
	    // Admin access
		if($_POST['i'] == "adm_set") {
		   
		    if($_POST['ff'] == "access") {
				$TEXT['content'] = ($_POST['t'] == "save") ? $profile->updateAdmin($admin,$_POST['v1'],$_POST['v2'],$_POST['v3']) : $profile->getEditAdmin();
		    } 
		
		    echo ($_POST['t'] == "save") ? $TEXT['content'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

		// Extensions
		} elseif($_POST['i'] == "cor_set") {
		    
			if($_POST['ff'] == "updates") {
				$TEXT['content'] = $profile->websiteUpdates();
		    } elseif($_POST['ff'] == "patches") {
				$TEXT['content'] = $profile->websitePatches();
		    }
		
		    echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

		// Boost features
		} elseif($_POST['i'] == "spo_set") {
		    
			if($_POST['ff'] == "paypal") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($boost_settings_data['paypal'],$boost_settings_inputs['paypal']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5']),$boost_settings_keys['paypal'],$protection_paypal);
			} elseif($_POST['ff'] == "boads") {
				
				// Validate values
				if($_POST['t'] == "save") {
					if($_POST['v3'] < 0 || !is_numeric($_POST['v3'])) {
						$TEXT['posts'] = showError($TEXT['_uni-Boost_er1']);
					} else {
						$TEXT['posts'] = $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4']),$boost_settings_keys['boads'],$protection_boads);
					}
				}
				
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($boost_settings_data['boads'],$boost_settings_inputs['boads']) : $TEXT['posts'];
			} 
		
		    echo ($_POST['t'] == "load") ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'] ;

		// Plus features
		} elseif($_POST['i'] == "plu_set") {
		    
			if($_POST['ff'] == "bibleview") {
				
				if($_POST['t'] == "save") {
					
					$values = array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6']);
					
					$parsed_values = array();
					
					foreach($values as $value) {
						$parsed_values[] = ($value) ? '1' : '0';
					}
					
					$parsed_values = implode(',',$parsed_values);
					
				} else {
					$parsed_values = $page_settings['bible_view'];
				}
				
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($plus_settings_data['bibleview'],$plus_settings_inputs['bibleview']) : $profile->updateSettings(array($parsed_values),$plus_settings_keys['bibleview'],$protection_bible) ;	
			
			} elseif($_POST['ff'] == "fonts") {
				
				if($_POST['t'] == "save") {
					
					$values = $_POST['v1'];
					
					if(is_numeric($values)) {
						
						$split = explode('.',(floor($values) != $values) ? $values : $values.'.0');
						
						if(intval($split[0]) >= 6 && intval($split[0]) <= 18) {
							$parsed_values = intval($split[0]).'.'.intval(substr($split[1],0,1));
						} else {
							$parsed_values = '10';
						}
	
					} else {
						$parsed_values = '12';
					}

				} else {
					$parsed_values = $page_settings['font_size'];
				}
				
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($plus_settings_data['fonts'],$plus_settings_inputs['fonts']) : $profile->updateSettings(array($parsed_values,'?'.mt_rand(0,99999999)),$plus_settings_keys['fonts'],$protection_fonts) ;	
			
			} 
		
		    echo ($_POST['t'] == "load") ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'] ;
		
		// Extensions
		} elseif($_POST['i'] == "ext_set") {
		
		    if($_POST['ff'] == "extensions") {
				$TEXT['content'] = $profile->loadExtensions();
		    }
		
		    echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			
		// Manage
		} elseif($_POST['i'] == "mna_set") {
			
			// Post backgrounds
			if($_POST['ff'] == "backgrounds") {
				$TEXT['content'] = $profile->getPostBackgrounds($admin);
			
			//  Activate post background
			} elseif($_POST['ff'] == "activateback") {
				$TEXT['content'] = $profile->activateBackground($_POST['v2']);

			// Reorder post background
			} elseif($_POST['ff'] == "reorderback") {
				$TEXT['content'] = $profile->reorderBackground($_POST['v1']);
	 
			// Page categories
			} elseif($_POST['ff'] == "pagecats") {
				$TEXT['content'] = $profile->loadCategoris();
	
			// Blog categories
			} elseif($_POST['ff'] == "blogcats") {
				$TEXT['content'] = $profile->loadBlogCategoris();
	
			// Delete category
			} elseif($_POST['ff'] == "delcats") {
				$TEXT['content'] =$profile->deleteCategory($_POST['v3']);
	        
			// Delete blog category
			} elseif($_POST['ff'] == "delblogcats") {
				$TEXT['content'] =$profile->deleteCategory($_POST['v3'],1);
	     
			// Manage ads
			} elseif($_POST['ff'] == "sponsors") {
				$TEXT['content'] = $profile->getManageadds($admin,$page_settings);
               
			} elseif($_POST['ff'] == "infopages") {
				$TEXT['content'] = $profile->getInfoPages();
               
			} elseif($_POST['ff'] == "editinfopage") {
				
				$id = ($_POST['v1']) ? $_POST['v1'] : $_POST['p'];
				
				$TEXT['content'] = $profile->editInfoPage($id);
               
			} elseif($_POST['ff'] == "createinfopage") {
				$TEXT['content'] = $profile->createInfoPage();
               
			} elseif($_POST['ff'] == "saveinfopage") {
				$TEXT['content'] = $profile->saveInfoPage($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6']);
               
			} elseif($_POST['ff'] == "dellinfopage") {
				$TEXT['content'] = $profile->dellInfoPage($_POST['v7']);
               
			}
			
			echo ($_POST['t'] == "save") ? $TEXT['content'] :  display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			
        // Explore		
		} elseif($_POST['i'] == "man_set") {
	
	        if($_POST['ff'] == "users") {

				// Add starting point
				$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
				// Add filter to users
				$filter = (isset($_POST['p']) && is_numeric($_POST['p'])) ? $_POST['p'] : '0' ;

				// Apply type
				if($from == 0) {
			
					// Get users
		    		$TEXT['posts'] = $profile->manageUsers($from,$filter);
					$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		    
			        // Get filters 
			        if($_POST['bo'] !== "5") {
						$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_manage_users'.$TEXT['templates_extension']);
						$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
					} else {
						$right_body = '';
					}
					
					// Display full body
					echo $main_body.$right_body;
			
				} else {
			
					// Else get users only
					echo $profile->manageUsers($from,$filter);
			
				}
				
			} elseif($_POST['ff'] == "reports") {
				
				// Add starting point
				$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
				// Add filter to reports
				$filter = (isset($_POST['p']) && is_numeric($_POST['p'])) ? $_POST['p'] : '1' ;

				// Apply type
				if($from == 0) {
				
					// Get reports
		    		$TEXT['posts'] = $profile->getReports($admin,$from,$filter);
					$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		    
					// Get filters 
			        if($_POST['bo'] !== "5") {
						$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_reports'.$TEXT['templates_extension']);
						$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
					} else {
						$right_body = '';
					}
					
					// Bind and display full body
					echo $main_body.$right_body;
			
				} else {
			
					// Else get reports only
					echo $profile->getReports($admin,$from,$filter);
			
				}	
				
            // Manage groups
			} elseif($_POST['ff'] == "groups") {
				
				// Add starting point
				$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
				// Add filter to groups
				$filter = (isset($_POST['p']) && is_numeric($_POST['p'])) ? $_POST['p'] : '0' ;

				// Apply type
				if($from == 0) {
			
					// Get groups
		    		$TEXT['posts'] = $profile->manageGroups($from,$filter);
					$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		    
					// Get filters 
			        if($_POST['bo'] !== "5") {
						$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_manage_groups'.$TEXT['templates_extension']);
						$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
					} else {
						$right_body = '';
					}
					
					// Display full body
					echo $main_body.$right_body;
			
				} else {
			
					// Else get groups only
					echo $profile->manageGroups($from,$filter);
			
				}
				
			} elseif($_POST['ff'] == "edituser") {
				
				// Edit user profile
				$TEXT['posts'] = $profile->editUser($_POST['v1']);
				echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
				
			} elseif($_POST['ff'] == "editgroup") {

				// Edit group
				$TEXT['posts'] = $profile->editGroup($_POST['v1']);
				echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
				
			} elseif($_POST['ff'] == "savegroup") {
				
				echo $profile->updateGroup($_POST['id'],$_POST['username'],$_POST['name'],$_POST['description'],$_POST['email'],$_POST['location'],$_POST['website']);
				
			} elseif($_POST['ff'] == "saveuser") {
				
				if(isset($_POST['v7']) && $_POST['v7'] == 1) {
			
					// Block user from reporting
					$b_users  = $b_posts  = $b_comments = '1';
			
					// Remove profile verification too
					$verify = '0';
			
					// Block user login access 
					$state = '3';
			
				} else {
			
					// Get blocking properties
            		$b_users  = ($_POST['v4']) ? '1' : '0' ;
	        		$b_posts  = ($_POST['v5']) ? '1' : '0' ;
	        		$b_comments = ($_POST['v6']) ? '1' : '0' ;

            		// Check whether user is set to verified			
					$verify = ($_POST['v2']) ? '1' : '0' ;
	        
					// Check whether user email is set to verify
					$state  = (isset($_POST['v3']) && $_POST['v3'] == 1) ? '1' : '2' ;        
		
				}
				
				$new_pass = trim($_POST['v17']);
				
				$v17 = (!empty($new_pass)) ? sprintf(", `password` = '%s'",$db->real_escape_string(md5($new_pass))) : '' ;
		
				// Security check
				if(isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0) {	
			
					// Update user profile
					echo $profile->updateUserprofile($_POST['v1'],$state,$verify,$b_users,$b_posts,$b_comments,$_POST['v8'],$_POST['v9'],$_POST['v10'],$_POST['v11'],$_POST['v12'],$_POST['v13'],$_POST['v14'],$_POST['v15'],$_POST['v16'],$v17);
		
				}
				
		
			}
		
		// Major settings	
		} elseif($_POST['i'] == "maj_set") {
			
			// SMTP Settings
			if($_POST['ff'] == "smtp") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['smtp'],$maj_settings_inputs['smtp']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6']),$maj_settings_keys['smtp'],$protection_smtp) ;	
			
			// SEO Settings
			} elseif($_POST['ff'] == "seo") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['seo'],$maj_settings_inputs['seo']) : $profile->saveSEO($_POST['v1'],$_POST['v2']);
			
			// Image settings
			} elseif($_POST['ff'] == "images") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['images'],$maj_settings_inputs['images']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7']),$maj_settings_keys['images'],$protection_images);
			
			// VIdeo settings
			} elseif($_POST['ff'] == "videos") {
				
				if($_POST['t'] == "save") {
					
					if(!filter_var($_POST['v1'], FILTER_VALIDATE_INT) || $_POST['v1'] <= 0) {
						$parsed = showError($TEXT['_uni-Settings-Err-Positive_number']);
					} else {
						$parsed = $profile->updateSettings(array($_POST['v1'],$_POST['v2']),$maj_settings_keys['videos'],$protection_videos);
					}
					
				}
				
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['videos'],$maj_settings_inputs['videos']) : $parsed;
			
			// Website settings
			} elseif($_POST['ff'] == "website") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['website'],$maj_settings_inputs['website']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3']),$maj_settings_keys['website'],$protection_website);
			
			// Website themes
			} elseif($_POST['ff'] == "themes") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->getThemes($admin) : $profile->updateSettings(array($_POST['v1'],$_POST['v2']),$maj_settings_keys['website'],$protection_website);
			
			} elseif($_POST['ff'] == "polling") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['polling'],$maj_settings_inputs['polling']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3']),$maj_settings_keys['polling'],$protection_polling);
			
			// Chat socket server
			}  elseif($_POST['ff'] == "sockets") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($maj_settings_data['sockets'],$maj_settings_inputs['sockets']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2']),$maj_settings_keys['sockets'],$protection_sockets);
			
			// Website languages
			} elseif($_POST['ff'] == "languages") {
				
				if($_POST['t'] == "load") {
					$TEXT['posts'] = $profile->getLanguages($admin);
				} else {
					$profile->updateSettings(array($_POST['v1']),array('default_lang','_uni-Settings_updated'),$protection_website);
					$TEXT['posts'] = "<script>_admin(41,0,0,'load','languages',1,1);</script>";
				}
		
			}
			
			echo ($_POST['t'] == "load") ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'] ;
		
		// Content settings
		} elseif($_POST['i'] == "con_set") {
		
		    // Users
			if($_POST['ff'] == "users") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['users'],$con_settings_inputs['users']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7']),$con_settings_keys['users'],$protection_users) ;	
			
			// Express
			} elseif($_POST['ff'] == "express") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['express'],$con_settings_inputs['express']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3']),$con_settings_keys['express'],$protection_express);
			
			// Posts
			} elseif($_POST['ff'] == "posts") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['posts'],$con_settings_inputs['posts']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4']),$con_settings_keys['posts'],$protection_posts);
			
			// Chats
			} elseif($_POST['ff'] == "chats") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['chats'],$con_settings_inputs['chats']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5']),$con_settings_keys['chats'],$protection_chats);
			
			// Popular
			} elseif($_POST['ff'] == "popular") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['popular'],$con_settings_inputs['popular']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5']),$con_settings_keys['popular'],$protection_popular);
			
			// Groups
			} elseif($_POST['ff'] == "groups") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['groups'],$con_settings_inputs['groups']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4']),$con_settings_keys['groups'],$protection_groups);
			
			// Comments
			} elseif($_POST['ff'] == "comments") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($con_settings_data['comments'],$con_settings_inputs['comments']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2']),$con_settings_keys['comments'],$protection_comments);
			}
			
		    echo ($_POST['t'] == "load") ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'] ;
			
		// Features settings
		} elseif($_POST['i'] == "fea_set") {
		
		    // Express
			if($_POST['ff'] == "expcontent") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($fea_settings_data['expcontent'],$fea_settings_inputs['expcontent']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4']),$fea_settings_keys['expcontent'],$protection_expcontent) ;	
			
			// Trending hashtags
			} elseif($_POST['ff'] == "trendingtags") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($fea_settings_data['trendingtags'],$fea_settings_inputs['trendingtags']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7']),$fea_settings_keys['trendingtags'],$protection_trendingtags);
			
			// Extra features
			} elseif($_POST['ff'] == "extra") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($fea_settings_data['extra'],$fea_settings_inputs['extra']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2']),$fea_settings_keys['extra'],$protection_extra);
			
			// Groups joined
			} elseif($_POST['ff'] == "groupfeatures") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($fea_settings_data['groupfeatures'],$fea_settings_inputs['groupfeatures']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4']),$fea_settings_keys['groupfeatures'],$protection_groupfeatures);
			} 
			
		    echo ($_POST['t'] == "load") ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'] ;
			
		// For registration settings	
		} elseif($_POST['i'] == "reg_set") {
			
			// Notification Settings
			if($_POST['ff'] == "notification") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($reg_settings_data['notification'],$reg_settings_inputs['notification']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6']),$reg_settings_keys['notification'],$protection_notification) ;	
			
			// Blocking Settings
			} elseif($_POST['ff'] == "blocking") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($reg_settings_data['blocking'],$reg_settings_inputs['blocking']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3']),$reg_settings_keys['blocking'],$protection_blocking);
			
			// Privacy Settings
			} elseif($_POST['ff'] == "privacy") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($reg_settings_data['privacy'],$reg_settings_inputs['privacy']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7']),$reg_settings_keys['privacy'],$protection_privacy);
			
			// Search Settings
			} elseif($_POST['ff'] == "search") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($reg_settings_data['search'],$reg_settings_inputs['search']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3']),$reg_settings_keys['search'],$protection_search);
			
			// Security Settings
			} elseif($_POST['ff'] == "security") {
				$TEXT['posts'] = ($_POST['t'] == "load") ? $profile->loadSettings($reg_settings_data['security'],$reg_settings_inputs['security']) : $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7']),$reg_settings_keys['security'],$protection_security);
			}
			
			echo ($_POST['t'] == "load") ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'] ;
		
		} else {
			echo "Admin controller E:PARMS not found";
		}
		
		

	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
	}		
// No credentials
} else {
    echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>