<?php 
//--------------------------------------------------------------------------------------//
//                          Breeze Social networking platform                           //
//                                     PHP ADMIN CLASSES                                //
//--------------------------------------------------------------------------------------//


class AdminLogin {		// Administration Login | Administration Logout
	
	// Properties
	public $db;                         // DATABASE
	public $username;	                // USERNAME
	public $password;	                // PASSWORD 

	function start() {                                        // Start administration login methods           
		global $TEXT;
		
		// Unset everything
		$this->logOut();
		
		// Verify profile
		$profile = $this->checkProfile();
		
		// Administration verified
		if($profile == 1) {
			
			// Set both sessions and cookies
			$_SESSION['a_username'] = $this->username;
			$_SESSION['a_password'] = md5($this->password);
			
			setcookie("a_username", $this->username, time() + 30 * 24 * 60 * 60,'/'); 
			setcookie("a_password", md5($this->password), time() + 30 * 24 * 60 * 60,'/'); 	
				
			// Log out general user SESSIONs if logged
			unset($_SESSION['username']);
			unset($_SESSION['password']);
		
			// Unset general user Cookies if logged 
			setcookie("username", "", time() + 1 * 1,'/'); 
			setcookie("password", "", time() + 1 * 1,'/');			
			
			// Return success
			return 1;
			
		} else {
			
			// Wrong credentials
			return $TEXT['_uni-Username_password_incorrect'];	
		}	
	}
	
	function log() {                                          // Direct login (To be used frequently)
		
		// Select administration row
		$profile = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string($this->username), $this->db->real_escape_string($this->password)));
		
		// Return administration row if exists else unset everything
		return ($profile->num_rows) ? $profile->fetch_assoc() : $this->logOut();	
	}
	
	function checkProfile() {                                 // Check profile for Full login
	
		$result = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string($this->username), $this->db->real_escape_string(md5($this->password))));
		
		return ($result->num_rows) ? 1 : 0;
		
	}
	
	function logOut() {                                       // Carry out SESSIONS and COOKIES RETURN 0
		// Unset administration cookies and sessions
		unset($_SESSION['a_username']);
		unset($_SESSION['a_password']);

		// unset cookies
		setcookie("a_username", '', time() + 1*1,'/'); 
        setcookie("a_password", '', time() + 1*1,'/'); 
		
		return 0;
	}
	
}

class manage {           // Administration functions
	
	function logOut() {                                      // Log out administration
		
		// Unset Administation SESSION
		unset($_SESSION['a_username']);
		unset($_SESSION['a_password']);
		
		return 0;
	}

	function getAdmin() {                                    // Fetch logged administration
		
		// Unset Administation SESSION
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		
		// Set logged out session
		$_SESSION['loggedout'] = 'USER_LOGGED_OUT';
		
		// Unset Cookies
		setcookie("username", "", time() + 1 * 1,'/');
		setcookie("password", "", time() + 1 * 1,'/');
		
		// Else select user using user name as user name
		$user = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `admins`.`username` = '%s' AND `admins`.`password` = '%s' ", $this->db->real_escape_string($this->username), $this->db->real_escape_string($this->password)));
	
	    // Return administration details if exists
		return ($user->num_rows) ?  $user->fetch_assoc() : $this->logOut();

	}
	
	function verifiedBatch($x,$type = 0) {                         // Return verified batch if profile is verified
		global $TEXT;
		
		// If small icon is requested
		$size = ($type) ? 'width="14px"': 'width="18px"';
		
		// Set responsiveness
		$responsive = ($type) ? '' : 'responsive-medium';
		
		// Return verified image if profile is verified
		return ($x) ? '<img class="'.$responsive.'" title="'.$TEXT['_uni-Profile_verified'].'" alt="Image" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/others/verified.svg" '.$size.'></img>' : '';	
	
	}
	
	function genNavigation($admin) {                         // Fetch administration navigation
		global $TEXT;

		// Generate navigation from template 
		return display('themes/'.$TEXT['theme'].'/html/navigations/main_admin'.$TEXT['templates_extension']);
		
	}
	
	function applyTheme($theme) {	                         // Apply website theme
		
		// Update theme if exists
        return (file_exists('themes/'.$theme.'/theme.php')) ? $this->db->query(sprintf( "UPDATE `settings` SET `value` = '%s' WHERE `key` = 'theme'",$this->db->real_escape_string($theme))) : TRUE;		
	
	}
	
	function getThemes() {                                   // Fetch available themes
		global $TEXT;
		
		// Open directory
		$directory = opendir('../../../themes/');
		
		// If directory exists
		if($directory) {
			
			// Reset
			$listThemes = array();$theme_error = '';
			
			// Start with title
			$box = display(templateSrc('/admin/themes/title'));

			$theme_tpl = display(templateSrc('/admin/themes/theme'),0,1);
			
			while(FALSE !== ($theme = readdir($directory)))  {
				
				// Check whether theme.php file exists
				if($theme != '.' && $theme != '..'  && $theme != '../..' && file_exists('../../../themes/'.$theme.'/theme.php')) {
					
					// Add theme to list
					$listThemes[] = $theme;
					
					// Import theme information
					include('../../../themes/'.$theme.'/theme.php');
					
					// Import platform info
					include(__DIR__ .'/platform.php');
					
					// Check whether theme supports this versions
					if(!isset($theme_support[$PLATFORM['VERSION_SHT']]) || $theme_support[$PLATFORM['VERSION_SHT']] !== 1) {
						
						$TEXT['temp-message'] = sprintf($TEXT['_uni-Theme_support_error'],$theme_update_url);
						
						$TEXT['temp-error'] = display(templateSrc('/admin/elements/message'));
					
					} else {
						$TEXT['temp-error'] = '';
					}
					
					// Check whether this already active else add activating URL
					if($TEXT['theme'] == $theme) {
						$TEXT['temp-btn'] = '<span class="btn btn-medium btn-light" href="'.$TEXT['installation'].'/?admin=apply&theme='.$theme.'">'.$TEXT['_uni-Active'].'</span>';
					} else {
						$TEXT['temp-btn'] = '<a class="btn btn-medium btn-dark" href="'.$TEXT['installation'].'/?admin=apply&theme='.$theme.'">'.$TEXT['_uni-Apply'].'</a>';
					}
					
					$TEXT['temp-img_src'] = $TEXT['installation'].'/themes/'.$theme.'/theme.png';
					$TEXT['temp-href'] = $theme_extra_url;
					$TEXT['temp-name'] = protectXSS($theme_name);
					$TEXT['temp-author'] = sprintf($TEXT['_uni-theme-author'],protectXSS($theme_developer));
					$TEXT['temp-version'] = protectXSS($theme_version);
					$TEXT['temp-release'] = protectXSS($theme_released);
					$TEXT['temp-description'] = protectXSS($theme_description);

					// Generate theme
					$box .= display('',$theme_tpl);
				}
			}
		
			// Close themes directory
			closedir($directory);
			
			// Return themes
			return $box;
		}
	}
	
	function editInfoPage($id) {                                     // Edit info pages
	    global $TEXT,$page_settings;
		
		$page = $this->db->query(sprintf("SELECT * FROM `info_pages` WHERE `id` = '%s' ",$this->db->real_escape_string($id)));
		
		if(!empty($page)) {
			
			// Fetch page
			$row = $page->fetch_assoc();
			
			$input_tpl = display(templateSrc('/admin/elements/input'),0,1);
			$description_tpl = display(templateSrc('/admin/elements/description'),0,1);
		    $select_tpl = display(templateSrc('/admin/elements/select'),0,1);
			
			$TEXT['temp-heading'] = $TEXT['_uni-Info_page'];
			$TEXT['temp-headingbtn'] = $TEXT['_uni-Save_changes'];
			$TEXT['temp-addinputs'] = $this->enrollInput($TEXT['_uni-Title'],'am-val-1',$TEXT['_uni-Title_info_page'],$row['title'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Nav_title'],'am-val-2',$TEXT['_uni-Title_info_page2'],$row['title_nav'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Heading'],'am-val-3',$TEXT['_uni-Title_info_page3'],$row['title_big'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-Description'],'am-val-4',$TEXT['_uni-Title_info_page4'],$row['text'],'bottom-divider',$description_tpl);
		    $TEXT['temp-addinputs'] .= $this->getSelected($TEXT['_uni-Public'],'am-val-5',getSelect($row['public'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider',$TEXT['_uni-Title_info_page5'],$select_tpl);								
			
			$TEXT['temp-id'] = $row['id'];
			return display(templateSrc('/admin/infopages/edit'));
			
		} else {
			return showError($TEXT['_uni-Info_pages_nt']);
			//return showError($TEXT['_uni-Info_pages_nt'])."<script>_admin(63,0,0,'load','infopages',1,1);</script>";
		}
		
	}
	
    function saveInfoPage($title,$nav,$heading,$text,$state,$id) {  // Save info page
	    global $TEXT,$page_settings;
		
		$public = ($state) ? '1' : '0';
		
		if(!empty($title) && !empty($nav) && !empty($heading) && !empty($text)) {
			
			$this->db->query(sprintf("UPDATE `info_pages` SET `title` = '%s', `title_nav` = '%s', `title_big` = '%s',`text` = '%s', `public` = '%s', `published` = '1' WHERE `id` = '%s'",$this->db->real_escape_string($title),$this->db->real_escape_string($nav),$this->db->real_escape_string($heading),$this->db->real_escape_string($text),$this->db->real_escape_string($public),$this->db->real_escape_string($id)));
		   
		    return ($this->db->affected_rows) ? showError($TEXT['_uni-Info_page_save']) :  showError($TEXT['_uni-No_changes']);
		} else {
			return showError($TEXT['_uni-No_changes']);
		}
	    
	}
	
    function dellInfoPage($id) {                                    // Delete info page
	    global $TEXT;
		
		$this->db->query(sprintf("DELETE FROM `info_pages` WHERE `id` = '%s' ",$id));
		
		return "<script>_admin(63,0,0,'load','infopages',1,1);</script>";
		
	}
	
    function createInfoPage() {                                     // Create info page
	    global $TEXT;
		
		// Insert a draft page
		$draft = "INSERT INTO `info_pages` (`id`, `title`, `title_nav`, `title_big`, `text`, `public`, `published`) VALUES (NULL, '', '', '', '', '0', '0');";
		
		if($this->db->query($draft) === TRUE) {
			
			// Last inserted post id
			$draft_id = $this->db->insert_id;
	
			$return = "<script>_admin(64,".$draft_id.",0,'load','editinfopage',1,1);</script>";
			
		} else {
			
			$return = showError($TEXT['_uni-Error_mysql']);

		}
		
		$ids = '1,'.$this->db->real_escape_string($draft_id).'';
		
		// Delete drafts
		$this->db->query(sprintf("DELETE FROM `info_pages` WHERE `id` NOT IN(%s) AND `published` = '0'",$ids));
		
		return $return;
	}
	
	function getInfoPages() {                                        // Fetch info pages
	    global $TEXT;
		
		$pages = $this->db->query("SELECT * FROM `info_pages` WHERE `published` = '1' ORDER BY `id` DESC ");
		
		$rows = array();$TEXT['temp-pages'] = '';
		
		if(!empty($pages)) {
			
			// Fetch pages
			while($row = $pages->fetch_assoc()) {
				$rows[] = $row;
		    }
			
		}
		
		$pages_tpl = display(templateSrc('/admin/infopages/page'),0,1);
		
		foreach($rows as $row) {
			$TEXT['temp-id'] = $row['id'];
			$TEXT['temp-name'] = $row['title'];
			$TEXT['temp-type'] = ($row['public']) ? $TEXT['_uni-Public'] : $TEXT['_uni-Private'];
			$TEXT['temp-pages'] .= display('',$pages_tpl);
		}
		
		return display(templateSrc('/admin/infopages/box'));
		
	}
	
	
	function search($from,$val,$filter = 0) {                // Return search results
		global $TEXT ;
		
		// Limit + 1 to check more results availability
		$limit = $this->settings['search_results_per_page'] + 1;
		$verifieds = $results = array(); 
		
		// Add filter
		if($filter == 1) {             // Verified emails
			$add_filter = 'AND `users`.`state` = \'1\' ';
		} elseif($filter == 2) {       // none verified emails
			$add_filter = 'AND `users`.`state` = \'2\' ';
		} elseif($filter == 3) {       // Spam emails
			$add_filter = 'AND `users`.`state` = \'4\' ';
		} elseif($filter == 4) {       // Suspended by administrations
			$add_filter = 'AND `users`.`state` = \'3\' ';
		} else {
			$add_filter = '';
		}	
		
		// Set start up and header
		if(is_numeric($from) && $from > 0 ) { 
			$from = 'AND idu < \''.$this->db->real_escape_string($from).'\'';
			$box = '';
		} else {
			$box = display(templateSrc('/admin/search/title'));
			$from = '';
		}
		
        $rows = array();
		
		// Select from general users
		$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') %s $add_filter ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$from,$limit));
		
		if(!empty($users)) {
			
			// Fetch users
			while($row = $users->fetch_assoc()) {
				$rows[] = $row;
		    }
			
		}	
		
		// Check for more results
		$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;

		// If results exists
		if(!empty($rows)) {

			$people = '<div class="brz-white brz-new-container">'.$header;
            
			// Import main class functions
			if(!class_exists('main')) {
				require_once(__DIR__ . '/classes.php');
			}
			
			$main = new main;
			$main->db = $this->db;
		    
			$user_tpl = display(templateSrc('/admin/search/user'),0,1);
			
			// Generate user from each row
		    foreach($rows as $row) {
				
				$TEXT['temp-img'] = $TEXT['installation'].'/thumb.php?src='.$row['image'].'&fol=a&w=80&h=80';
				$TEXT['temp-idu'] = $row['idu'];
				$TEXT['temp-verfied_batch'] = $this->verifiedBatch($row['verified'],1);
				$TEXT['temp-name'] = fixName(80,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-timet'] = fuzzyStamp($row['active']);
				$TEXT['temp-time'] = (time() - $row['active'] < 30 || fuzzyStamp($row['active']) == $TEXT['_uni-Online'] ) ? '<i class="fa fa-circle h5 b2 green-font"></i> '.$TEXT['_uni-Online'].'' : $TEXT['_uni-Last_seen'].' '.fuzzyStamp($row['active']);
				$box .= display('',$user_tpl);

				$last = $row['idu'];
					
			}
			
            // Add load more function if more results exists
			$box .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-ttl_more-results'],'searchAdmin('.$last.','.$filter.',6);') : closeBody($TEXT['_uni-No_more_users']);

			// Return results
			return $box;	
			
		} else {
			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchp3_i'],$val));
		}
	}
	
	function getLanguages($admin) {                                  // Fetch available languages
		global $TEXT,$page_settings;
		
		// Search languages in directory
		$languages = glob('../../../languages/' . '*.php', GLOB_BRACE);
	
		// Start with title
		$box = display(templateSrc('/admin/languages/title'));

		$lang_tpl = display(templateSrc('/admin/languages/language'),0,1);
		
		// Import platform info
		include(__DIR__ .'/platform.php');	
		
		$active_lang  = $TEXT['LANG_SETTINGS_FILE_NAME'];
		
		// Save important strings
		$active = $TEXT['_uni-Default'];
		$default = $TEXT['_uni-Make_default'];
		$support_error = $TEXT['_uni-Lang_support_error'];
		$installation = $TEXT['installation'];
		
		foreach($languages as $language) {
		
			// Include language file to et information
			include($language);

			// Check language language supports this versions
		    if(!in_array($PLATFORM['OLD_LANG_VERSION_CODES'],$TEXT['LANG_SETTINGS_VERION_CODE'])) {
				$TEXT['temp-message'] = sprintf($support_error,$TEXT['LANG_SETTINGS_URL']);
				$TEXT['temp-error'] = display(templateSrc('/admin/elements/message'));
			} else {
				$TEXT['temp-error'] = '';
			}

			// Check whether language is default
			if($TEXT['LANG_SETTINGS_FILE_NAME'] == $page_settings['default_lang']) {
				$TEXT['temp-btn'] = '<span class="btn btn-medium btn-light">'.$active.'</span>';
			} else {
				$TEXT['temp-btn'] = '<a class="btn btn-medium btn-dark">'.$default.'</a>';
			}
			
			$TEXT['temp-href'] = '';
			$TEXT['temp-fname'] = $this->db->real_escape_string($TEXT['LANG_SETTINGS_FILE_NAME']);
			$TEXT['temp-name'] = protectXSS($TEXT['LANG_SETTINGS_NAME']);
			$TEXT['temp-author'] = sprintf($TEXT['_uni-lang-author'],protectXSS($TEXT['LANG_SETTINGS_AUTHOR']));
       		
			$box .= display('',$lang_tpl);
			
		}
		
		// Return themes
		return $box;
	}
	
	function manageUsers($from,$filter = 0) {                // Manage users
		global $TEXT,$page_settings ;
		
		// Limit
		$limit = 16;

		// Set starting point
		if(is_numeric($from) && $from > 0 ) {
			$start = 'WHERE idu < \''.$this->db->real_escape_string($from).'\'';
            $and = 'AND';
			$box = '';		
		} else {
		    $box = display(templateSrc('/admin/users/title'));
			$start = '';
			$and = 'WHERE';
		}
		
		// Add filter
		if($filter == 1) {
			$add_filter = '`users`.`verified` = \'1\'';        // Verified users
		} elseif($filter == 2) { 
		    $add_filter = '`users`.`state` = \'1\'';           // Verified emails
		} elseif($filter == 3) { 
		    $add_filter = '`users`.`state` = \'3\'';           // Suspended
		} elseif($filter == 4) { 
		    $add_filter = '`users`.`state` = \'4\'';           // Using Spam emails
		} elseif($filter == 5) { 
		    $add_filter = '`users`.`state` = \'2\'';           // Non verified emails
		} elseif($filter == 6) { 
		    $add_filter = '`users`.`ip` = \'\'';               // Without IP info
		} elseif($filter == 7) { 
		    $add_filter = '`users`.`safe` = \'1\'';            // Marked safe
		} elseif($filter == 8) { 
		    $add_filter = '`users`.`verified` = \'0\'';        // Not verified
		} elseif($filter == 10) { 
		    $add_filter = '`users`.`state` NOT IN(4,3)';       // Not suspended 
		} else {
			$add_filter = '';
			$and = '';
		}
		
		// Check whether filter is set to show oldest
		if($filter == 9) {
			
		    // Add starting point	
			$start = (is_numeric($from) && $from > 0 ) ? 'WHERE idu > \''.$this->db->real_escape_string($from).'\'' : '' ;		
			
			// reverse order
			$add_filter = 'ORDER BY `users`.`idu` ASC LIMIT 16';
			
		} else {
			$add_filter = $add_filter.' ORDER BY `users`.`idu` DESC LIMIT 16';
		}
	
	    // Select users
		$result = $this->db->query("SELECT * FROM `users` $start $and $add_filter ");

	    // Reset
		$rows = array(); 
    
	    $user_tpl = display(templateSrc('/admin/users/user'),0,1);
		
		// If users exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Check for more results
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;
			
			if(!class_exists('main')) {
				require_once(__DIR__ . '/classes.php');
			}
			
			// Create main class
			$main = new main;
			$main->db = $this->db;
			
			// Generate user from each row
		    foreach($rows as $row) {
	
				$TEXT['temp-img'] = $TEXT['installation'].'/thumb.php?src='.$row['image'].'&fol=a&w=100&h=100';
				$TEXT['temp-idu'] = $row['idu'];
				$TEXT['temp-verfied_batch'] = $this->verifiedBatch($row['verified'],1);
				$TEXT['temp-name'] = fixName(80,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-timet'] = fuzzyStamp($row['active']);
				$TEXT['temp-time'] = (time() - $row['active'] < 30 || fuzzyStamp($row['active']) == $TEXT['_uni-Online'] ) ? '<i class="fa fa-circle h5 b2 green-font"></i> '.$TEXT['_uni-Online'].'' : $TEXT['_uni-Last_seen'].' '.fuzzyStamp($row['active']);
				$box .= display('',$user_tpl);
				$last = $row['idu'];

			}
			
            // Add load more function if more results exists
			$box .= ($loadmore) ? addLoadmore($page_settings['inf_scroll'],'','_admin(43,'.$filter.','.$last.',\'load\',\'users\',1,6);') : closeBody($TEXT['_uni-No_more_users']);
			
			// Return accordions
			return $box;	
			
		} else {			
			
			// Else no users
			return bannerIt('users'.mt_rand(1,4),$TEXT['_uni-No_searchp'],$TEXT['_uni-No_searchp3_cci']);				   
		}	
	}
	
	function manageGroups($from,$filter = 0) {                // Manage groups
		global $TEXT ;
		
		// Limit
		$limit = 31;

		// Set starting point
		if(is_numeric($from) && $from > 0 ) {
			$start = 'WHERE group_id < \''.$this->db->real_escape_string($from).'\'';
            $and = 'AND';
			$box = '';		
		} else {
		    $box = display(templateSrc('/admin/groups/title'));
			$start = '';
			$and = 'WHERE';
		}
		
		// Add filter
		if($filter == 1) {
			$add_filter = '`groups`.`group_privacy` = \'1\'';        // Public groups
		} elseif($filter == 2) { 
		    $add_filter = '`groups`.`group_privacy` = \'2\'';        // Private groups
		} elseif($filter == 3) { 
		    $add_filter = '`groups`.`group_privacy` = \'3\'';        // Secret groups
		} elseif($filter == 4) { 
		    $add_filter = '`groups`.`group_users` >= \'100\'';       // New groups
		} elseif($filter == 5) { 
		    $add_filter = '`groups`.`group_users` >= \'1000\'';      // Starter groups
		} elseif($filter == 6) { 
		    $add_filter = '`groups`.`group_users` >= \'10000\'';     // Super groups
		} elseif($filter == 7) { 
		    $add_filter = '`groups`.`group_users` >= \'100000\'';    // Ultimate groups
		} elseif($filter == 8) { 
		    $add_filter = '`groups`.`group_users` >= \'1000000\'';   // Legendary groups 
		} else {
			$add_filter = '';
			$and = '';
		}
		
		// Check whether filter is set to show oldest
		if($filter == 9) {
			
		    // Add starting point	
			$start = (is_numeric($from) && $from > 0 ) ? 'WHERE group_id > \''.$this->db->real_escape_string($from).'\'' : '' ;		
			
			// reverse order
			$add_filter = 'ORDER BY `groups`.`group_id` ASC LIMIT 31';
			
		} else {
			$add_filter = $add_filter.' ORDER BY `groups`.`group_id` DESC LIMIT 31';
		}
	
	    // Select groups
		$result = $this->db->query("SELECT * FROM `groups` $start $and $add_filter ");

	    // Reset
		$rows = array(); 
    
		// If groups exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch groups
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Check for more results
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;

			$group_tpl = display(templateSrc('/admin/groups/group'),0,1);

			// Generate group from each row
		    foreach($rows as $row) {
				
				$TEXT['temp-img'] = $TEXT['installation'].'/thumb.php?src='.getCoverSrc(str_replace('rep_','',$row['group_cover'])).'&fol=f&w=100&h=100';
				$TEXT['temp-id'] = $row['group_id'];
				$TEXT['temp-name'] = $row['group_name'];
				$TEXT['temp-members'] = sprintf($TEXT['_uni-ttl_group_members_ttl'] ,readable($row['group_users']));
				$box .= display('',$group_tpl);
				
				// Set last processed id	
				$last = $row['group_id'];

			}
			
            // Add load more function if more results exists
			$box .= ($loadmore) ? addLoadmore(1,'','_admin(47,'.$filter.','.$last.',\'load\',\'groups\',1,6);') : closeBody($TEXT['_uni-No_more_groups']);
			
			// Return accordions
			return $box;	
			
		} else {			
			
			// Else no groups
			return bannerIt('users'.mt_rand(1,4),$TEXT['_uni-No_searchp'],$TEXT['_uni-No_searchp3_ccgri']);				   
		}	
	}
	
	function getReports($admin,$from = 0,$filter = 1) {      // Get reports
        global $TEXT,$page_settings;

		// Set starting points and header
		if(is_numeric($from) && $from > 0) {
			$start ='AND `reports`.`id` < \''.$this->db->real_escape_string($from).'\' ';
			$box = '';
		} else {
			$start = '';
			$box = display(templateSrc('/admin/reports/title'));
		}
		
		// Add filter
		if($filter == 2) {
			$fil = 'AND `reports`.`type` = \'1\''; // Users
		}elseif($filter == 3) { 
		    $fil = 'AND `reports`.`type` = \'2\''; // Posts
		}elseif($filter == 4) { 
		    $fil = 'AND `reports`.`type` = \'3\''; // Comments
		} else {
			$fil ='';
		}
		
		// select reports
		$reports = $this->db->query("SELECT * FROM `reports`, `users` WHERE `reports`.`from` = `users`.`idu` $fil $start ORDER BY `reports`.`id` DESC LIMIT 10");
		
		if($reports->num_rows) {
            
			// Fetch report rows
			while($row = $reports->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Delete load more identifier
			$loadmore = (array_key_exists(4, $rows)) ? array_pop($rows) : NULL;

			if(!class_exists('main')) {
			    require_once(__DIR__ . '/classes.php');
		    }
			
			// Intilize main functioning class
			$functions = new main();
			$functions->db = $this->db;
			
			// Pass credentials
			$functions->username = $this->username;
			$functions->password = $this->password;

			// Load templates
			$item_tpl = display(templateSrc('/admin/reports/item'),0,1);
			$report_tpl = display(templateSrc('/admin/reports/report'),0,1);
			
			// Generate report from each row
			foreach($rows as $row) {
				
				// Reported content owner
				$ownere = $this->db->real_escape_string($row['content_owner']);
				
				// Reported content ID
				$content_id_esc = $this->db->real_escape_string($row['content_id']);
			    
				// Select content owner
				$content_owner = $this->db->query("SELECT * FROM `users` WHERE `users`.`idu` = '$ownere' LIMIT 1");

                // Reset					
				$comments = $report_content = $image_download = $post_tags = $post_text = '';
				
				// Fetch content owner
				if($content_owner->num_rows) {
					$owner = $content_owner->fetch_assoc();
				}
		
		        // Add headings || viewable functions and more stuff to report
				if($row['type'] == 1) {
					
					$TEXT['temp-heading'] = $TEXT['_uni-Reported_user'];
					$TEXT['temp-heading2'] = $TEXT['_uni-Profile'];
					
					$user = $functions->getUserByID($row['content_id']);
					
					$bios_text = $prof_text = $home_text = $livi_text = $educ_text = $webs_text = '';
					
					if(!empty($user['profession'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Profession'];$TEXT['temp-d-data'] = $user['profession'];
					    $prof_text = display('',$item_tpl);				
				    }
					
					if(!empty($user['from'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Hometown'];$TEXT['temp-d-data'] = $user['from'];
					    $home_text = display('',$item_tpl);				
				    }	
				
					if(!empty($user['living'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Location'];$TEXT['temp-d-data'] = $user['living'];
					    $livi_text = display('',$item_tpl);				
				    }
					
					if(!empty($user['study'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Education'];$TEXT['temp-d-data'] = $user['study'];
					    $educ_text = display('',$item_tpl);					
				    }

					if(!empty($user['website'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Website'];$TEXT['temp-d-data'] = $functions->parseText($user['website']);
					    $educ_text = display('',$item_tpl);					
				    }	

					if(!empty($user['bio'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Bio'];$TEXT['temp-d-data'] = $user['bio'];
					    $educ_text = display('',$item_tpl);					
				    }

					$TEXT['temp-d-title'] = $TEXT['_uni-Avatar'];$TEXT['temp-d-data'] = '<a href="'.$TEXT['installation'].'/uploads/profile/main/'.$user['image'].'" download="" class="h6 b2 theme-font" href="javascript:void(0);">'.$TEXT['_uni-Download_attch'].'</a>';
					$TEXT['temp-report_content'] = display('',$item_tpl);					
					
					$TEXT['temp-d-title'] = $TEXT['_uni-Cover'];$TEXT['temp-d-data'] = '<a href="'.$TEXT['installation'].'/uploads/profile/covers/'.$user['cover'].'" download="" class="h6 b2 theme-font" href="javascript:void(0);">'.$TEXT['_uni-Download_attch'].'</a>';
					$TEXT['temp-report_content'] .= display('',$item_tpl);
					
					$TEXT['temp-report_content'] .= $prof_text.$home_text.$livi_text.$educ_text.$webs_text.$bios_textost;
					
				} elseif($row['type'] == 2) {
					
					$TEXT['temp-heading'] = $TEXT['_uni-Reported_post'];
					$TEXT['temp-heading2'] = $TEXT['_uni-Post'];
					$viewable = '<span class="brz-btn brz-round brz-margin-top-small brz-blue-hd brz-hover-green" onclick="$(\'#report_'.$row['id'].'\').remove();$(\'#report_'.$row['id'].'\').next().remove();$(\'#'.$row['id'].'_c23asd78\').remove();loadPost('.$row['content_id'].')" ><i class="fa fa-external-link" ></i> <span class="brz-hide-small">'.$TEXT['_uni-View'].'</span></span>';
				    
					// Add title
			        $title_set = array("0" => "_uni-status_update","1" => "_uni-photo",	"2" => "_uni-Grouped_chats",	"3" => "_uni-profile_photo","4" => "_uni-shared_video","5" => "_uni-profile_cover","7" => "_uni-shared_track","8" => "_uni-shared_video","9" => "_uni-shared_video",);
		
					$post = $functions->getPostByID($row['content_id']);
				
					if($post['post_type'] == 1) {
						
						$get_images = explode(',', $post['post_content']);
						
						$pics = '';
						
						foreach($get_images as $imge) {
							$pics .= '<span class="padding-5"><img src="'.$TEXT['installation'].'/uploads/posts/photos/'.$imge.'" class="rounded img-40"></span>';
						}
						
						$TEXT['temp-d-title'] = $TEXT['_uni-Photos'];$TEXT['temp-d-data'] = $pics;
					    $image_s = display('',$item_tpl);
						
					} 

					$parsed = $functions->parseText($post['post_text'],1);
					
					if(!empty($parsed)) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Post_text'];$TEXT['temp-d-data'] = $functions->parseText($post['post_text'],1);
					    $post_text = display('',$item_tpl);
					}
					
					if(!empty($post['post_tags'])) {
						$TEXT['temp-d-title'] = $TEXT['_uni-Post_tags'];$TEXT['temp-d-data'] = $post['post_tags'];
					    $post_tags = display('',$item_tpl);	
					}
					
					$TEXT['temp-d-title'] = $TEXT['_uni-Post_type'];$TEXT['temp-d-data'] = $TEXT[$title_set[$post['post_type']]];
					$post_type = display('',$item_tpl);
					
					$TEXT['temp-d-title'] = $TEXT['_uni-Online_for'];$TEXT['temp-d-data'] = '<span class="timeago h6 b1 tin-light-font-only" title="'.$post['post_time'].'">'.$post['post_time'].'</span>';
					$post_time = display('',$item_tpl);
					
				    $TEXT['temp-report_content'] = $post_type.$post_time.$post_tags.$post_text.$image_s;
				
				} elseif($row['type'] == 3) {
					
					$TEXT['temp-heading'] = $TEXT['_uni-Reported_comment'];
					$TEXT['temp-heading2'] = $TEXT['_uni-Comment'];
					
					$comment = $functions->getCommentByID($row['content_id']);
					
					$TEXT['temp-d-title'] = $TEXT['_uni-Online_for'];$TEXT['temp-d-data'] = '<span class="timeago h6 b1 tin-light-font-only" title="'.$comment['time'].'">'.$comment['time'].'</span>';
					$TEXT['temp-report_content'] = display('',$item_tpl);
					
					$TEXT['temp-d-title'] = $TEXT['_uni-Comment'];$TEXT['temp-d-data'] = $functions->parseText($comment['comment_text'],1);
					$TEXT['temp-report_content'] .= display('',$item_tpl);				
				
				}
				
				// Reset
				$TEXT['temp-val1'] = $TEXT['temp-val2'] = $TEXT['temp-val3'] = $TEXT['temp-val4'] = '';
				
				// Add report marks checked by user
				if($row['val1']) { $TEXT['temp-val1'] = '<div><i class="fa fa-certificate h6 b2 theme-x-font"></i> '.$TEXT['_uni-Report-1'].'</div>'; }
				
				if($row['val2']) { $TEXT['temp-val2'] = '<div><i class="fa fa-certificate h6 b2 theme-x-font"></i> '.$TEXT['_uni-Report-2'].'</div>'; }
				
				if($row['val3']) { $TEXT['temp-val3'] = '<div><i class="fa fa-certificate h6 b2 theme-x-font"></i> '.$TEXT['_uni-Report-3'].'</div>'; }
				
				if($row['val4']) { $TEXT['temp-val4'] = '<div><i class="fa fa-certificate h6 b2 theme-x-font"></i> '.$TEXT['_uni-Report-4'].'</div>'; }
				
				$TEXT['temp-rid'] = $row['id'];
				$TEXT['temp-time'] = $row['time'];
				$TEXT['temp-idu1'] = $row['idu'];
				$TEXT['temp-img1'] = $TEXT['installation'].'/thumb.php?src='.$row['image'].'&fol=a&w=100&h=100';
				$TEXT['temp-idu2'] = $owner['idu'];
				$TEXT['temp-img2'] = $owner['image'];
				$TEXT['temp-name1'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-name2'] = fixName(14,$owner['username'],$owner['first_name'],$owner['last_name']);
				$TEXT['temp-verfied_batch1'] = $this->verifiedBatch($row['verified'],1);
				$TEXT['temp-verfied_batch2'] = $this->verifiedBatch($owner['verified'],1);
				
				$box .= display('',$report_tpl);
				
				// Last processed id
				$last = $row['id'];
			}

			// Add load more function if more results exists
			$box .= ($loadmore) ? addLoadmore(1,'','_admin(51,'.$filter.','.$last.',\'load\',\'reports\',1,6);') : closeBody($TEXT['_uni-No_more-reports']);

		} else {
			// Return no users found
			return bannerIt('report'.mt_rand(1,4),$TEXT['_uni-No_searchp'],$TEXT['_uni-No_searchp3_csdci']);
		}
		return $box;
	}
	
	function loadExtensions() {                                  
		global $TEXT,$page_settings;
		
        // Select all extensions
		$exts = $this->db->query("SELECT * FROM `extensions` ORDER BY `ext_update`DESC ");
	    
		// Reset
		$installed = $available = $rows = array();
		$TEXT['temp-installed'] = $TEXT['temp-available'] = '';

		// If extensions exists
		if(!empty($exts) && $exts->num_rows) {
			
			// Fetch extensions
			while($row = $exts->fetch_assoc()) {
				
				// Categorize
				if($row['ext_status']) {
					$installed[] = $row;
				} else {
					$available[] = $row;
				}    
				
				// For foreach loop
				$rows[] = $row;
			}
			
			$ext_tpl = display(templateSrc('/admin/extensions/extension'),0,1);
			
			foreach($rows as $ext) {

				// Check whether extension is installed
				if($ext['ext_status']) {
					$TEXT['temp-btn'] = '<span onclick="updateExtension(\''.$ext['ext_name'].'\',0);" class="btn btn-small btn-light">'.$TEXT['_uni-UnInstall'].'</span>';
				} else {
					$TEXT['temp-btn'] = '<span onclick="updateExtension(\''.$ext['ext_name'].'\',1);" class="btn btn-small btn-dark">'.$TEXT['_uni-Install'].'</span>';
				}
			
				$TEXT['temp-img_src'] = $TEXT['installation'].'/extensions/'.$ext['ext_name'].'/icon.png';
				$TEXT['temp-name'] = protectXSS($ext['ext_name']);
				$TEXT['temp-author'] = sprintf($TEXT['_uni-theme-author'],protectXSS($ext['ext_author']));
				$TEXT['temp-version'] = '';
				$TEXT['temp-release'] = addStamp($ext['ext_update']);
				$TEXT['temp-description'] = protectXSS($ext['ext_description']);

				// Categorize
				if($ext['ext_status']) {
					$TEXT['temp-installed'] .= display('',$ext_tpl);
				} else {
					$TEXT['temp-available'] .= display('',$ext_tpl);
				}

			}
			
		}

		$installed_exts = (!empty($installed)) ? display(templateSrc('/admin/extensions/installed')) : '';
		
		// Return extensions
		return $installed_exts.display(templateSrc('/admin/extensions/available')) ;
		
	}
	
	function websiteUpdates() {      
        global $TEXT,$page_settings;
		
        // Select all updates
		$get_updates = $this->db->query("SELECT * FROM `updates` ORDER BY `u_date` DESC");
	    
		// Reset
		$installed = $installed_exts = array();
		$TEXT['temp-installed_ups'] = '';
		
		// Check for active extensions before updating website
		$check = $this->db->query("SELECT * FROM `extensions` WHERE `ext_status` = '1' ");
		
		$error = ($check->num_rows) ? addLog($TEXT['_uni-ext-disabl-first'],1) : '' ;

		// If updates exists
		if(!empty($get_updates) && $get_updates->num_rows) {
			
			// Fetch updates
			while($row = $get_updates->fetch_assoc()) {
				$installed[] = $row;		
			}
			
			$update_tpl = display(templateSrc('/admin/updates/update'),0,1);
			
			foreach($installed as $update) {

				$TEXT['temp-version'] = $update['u_version'];
				$TEXT['temp-name'] = $TEXT['_uni-Update'].' '.protectXSS($update['u_version']);
				$TEXT['temp-author'] = addStamp($update['u_date']);
				$TEXT['temp-logs'] = $update['u_description'];
       		
				// Generate updates in list
				$TEXT['temp-installed_ups'] .= display('',$update_tpl);

			}
			
		}
		
		// Return updates
		return display(templateSrc('/admin/updates/installed'));
		
	}
	
	function getEditAdmin() {                           // Edit administration password
		global $TEXT;

		$input_tpl = display(templateSrc('/admin/elements/input'),0,1);

		$TEXT['temp-heading'] = $TEXT['_uni-Edit_password'];
		$TEXT['temp-headingbtn'] = $TEXT['_uni-Save_changes'];
		$TEXT['temp-jsfunction'] = '_admin(62,0,0,\'save\',\'access\',0,7);';
		
		$TEXT['temp-addinputs'] = $this->enrollInput($TEXT['_uni-Current'],'am-val-1','','','bottom-divider',"password",$input_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-New'],'am-val-2','','','bottom-divider',"password",$input_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Repeat'],'am-val-3','','','bottom-divider',"password",$input_tpl);

		return display(templateSrc('/admin/settings'));
	}
	
	function websitePatches() {      
        global $TEXT,$page_settings;
		
        // Select all patches
		$get_updates = $this->db->query("SELECT * FROM `patches` ORDER BY `p_date` DESC");
	    
		// Reset
		$installed = $installed_exts = array();
		$TEXT['temp-applieds'] = '';
		
		// If patches exists
		if(!empty($get_updates) && $get_updates->num_rows) {
			
			// Fetch patches
			while($row = $get_updates->fetch_assoc()) {
				$installed[] = $row;		
			}
			
			// Generate patches in list
			$patch_tpl = display(templateSrc('/admin/patches/patch'),0,1);
			
			foreach($installed as $update) {
				
				$TEXT['temp-name'] = $TEXT['_uni-Patch'].' '.protectXSS($update['p_name_main']);
				$TEXT['temp-author'] = addStamp($update['p_date']);
				$TEXT['temp-logs'] = $update['p_description'];
       		
				// Generate updates in list
				$TEXT['temp-applieds'] .= display('',$patch_tpl);

			}
		}
	
		// Return patches
		return display(templateSrc('/admin/patches/applied'));
		
	}
	
	function deleteUser($id) {                               // Delete user
		
		// Escape ID
		$user_id_esc = $this->db->real_escape_string($id);
		
		// Delete the user from the database
		$this->db->query("DELETE FROM `users` WHERE `users`.`idu` = '$user_id_esc' ");

		// If the user deleted
		if($this->db->affected_rows) {
			
			// Select posts
			$user_posts = $this->db->query("SELECT * FROM `user_posts` WHERE `user_posts`.`post_by_id` = '$user_id_esc' ");
			
			// Fetch posts if exists
			if(!empty($user_posts)) {
				
				$rows = array();
				
				// Create ARRAYs 
			    while($row = $user_posts->fetch_assoc()) {
			        
					$rows[] = $row['post_id'];
    
	
					// Delete linked content
					if($post['post_type'] == 1) { 

                    	$images = explode(',', $row['post_content']);
					
						// Delete Post photo
						foreach($images as $image) {
							unlink("../../../uploads/posts/photos/".$image);
						}
					
			    	} elseif($row['post_type'] == 2) {
						unlink("../../../uploads/posts/videos/".$row['post_content']);               // Delete Post video
					} elseif($row['post_type'] == 3) {
						unlink("../../../uploads/profile/main/".$row['post_content']);               // DEL-Profile photo if it's not in use
					} elseif($row['post_type'] == 5) {
						unlink("../../../uploads/profile/covers/".$row['post_content']);             // DEL-Cover photo if it's not in use
					}
				
		        }
				
				$user_posts->close();
				
				// Implode results make usable in MySQL IN Clause
				$user_posts_ids = implode(',', $user_posts_is);
		
			    // Delete user's post loves and post's comments
			    $this->db->query("DELETE FROM `post_loves` WHERE `post_id` IN ({$user_posts_ids})");
			    $this->db->query("DELETE FROM `post_comments` WHERE `post_id` IN ({$user_posts_ids})");
				
			}
	
			// Update loves of posts that user has loved
			$this->db->query("UPDATE `user_posts` SET `user_posts`.`post_loves` = `user_posts`.`post_loves`-1 WHERE `post_id` IN (SELECT `post_id` FROM `post_loves` WHERE `post_loves`.`by_id` = '$user_id_esc' )");
			
			// Update comments 
			$this->db->query("UPDATE `user_posts` SET `user_posts`.`post_comments` = `user_posts`.`post_loves`-1 WHERE `post_id` IN (SELECT `post_id` FROM `post_comments` WHERE `post_comments`.`by_id` = '$user_id_esc' )");
			
			// Delete all the comments
			$this->db->query("DELETE FROM `post_comments` WHERE `post_comments`.`by_id` = '$user_id_esc' ");
			
			// Delete the loves
			$this->db->query("DELETE FROM `post_loves` WHERE `post_loves`.`by_id` = '$user_id_esc' ");
			
			// Delete the reports
			$this->db->query("DELETE FROM `reports` WHERE `by` = '$user_id_esc' ");
			
			// Delete all the friendships
			$this->db->query("DELETE FROM `friendships` WHERE `user1` = '$user_id_esc' OR `user2` = '$user_id_esc' ");
			
			// Delete chat messages
			$this->db->query("DELETE FROM `chat_messages` WHERE `by` = '$user_id_esc' ");
			
			// Left chat forms
			$this->db->query("DELETE FROM `chat_users` WHERE `uid` = '$user_id_esc' ");
			
			// Delete all the notifications
			$this->db->query("DELETE FROM `notifications` WHERE `not_from` = '$user_id_esc' OR `not_to` = '$user_id_esc' ");
		}
	}
	
	function executeReport($report_id,$safe = NULL) {	     // Mark safe or execute reported content
		
		// Select report
		$report_se = $this->db->query("SELECT * FROM `reports` WHERE `reports`.`id` = '{$this->db->real_escape_string($report_id)}' ");

		// If report exists
		if(!empty($report_se)) {
			$report = $report_se->fetch_assoc();
			$db_esc_content_id = $this->db->real_escape_string($report['content_id']);
		}
		
		// Close report as we are going to delete this report
		$report_se->close();
		
		// Delete report as well
		$this->db->query("DELETE FROM `reports` WHERE `reports`.`id` = '{$this->db->real_escape_string($report_id)}'");

		
		if(!empty($report) && $report['type'] == 1) {		
			
			if(!$safe) {
				// Delete user
			    $perforom = $this->deleteUser($report['content_id']);
			} else {
				$this->db->query("UPDATE `users` SET `safe` = '1' WHERE `users`.`idu` = '$db_esc_content_id' ");
			}
	
		} elseif(!empty($report) && $report['type'] == 2) {
			
			if(!$safe) {
			
				if(!class_exists('main')) {
					require_once(__DIR__ . '/classes.php');
			    }
			
				// Main class
				$import = new main;
				$import->db = $this->db;
			
				// Create owner rights
				$reported = array();
				$reported['idu'] =  $report['content_owner'];
				$reported['image'] = 'FORCE[]DELETE';
				$reported['cover'] = 'FORCE[]DELETE';
			
				// Delete post
				$perforom = $import->deletePost($report['content_id'],$reported);
				
			} else {
				$this->db->query("UPDATE `user_posts` SET `safe` = '1' WHERE `user_posts`.`post_id` = '$db_esc_content_id' ");
			}
			
		} elseif(!empty($report) && $report['type'] == 3) {
			
			if(!$safe) {
                
				if(!class_exists('main')) {
			        require_once(__DIR__ . '/classes.php');
		        }
				
				// Main class
				$import = new main;
				$import->db = $this->db;
			
				// Create owner rights 
				$reported = array();
				$reported['idu'] = $report['content_owner'];
			
				// Delete comment
				$perforom = $import->deleteComment($report['content_id'],$reported);
				
			} else {
				$this->db->query("UPDATE `post_comments` SET `safe` = '1' WHERE `post_comments`.`post_id` = '$db_esc_content_id' ");
			}	
		}
		
		return '';
	}

	function loadBlogCategoris() {                               // Fetch available blog categories
        global $TEXT;

		// Select all categories
		$cats = $this->db->query("SELECT * FROM `blog_categories` ORDER BY `cat_name`");
	
		// Reset
		$all_cats = array();
		$TEXT['temp-add_cats'] = '';

		$item_tpl = display(templateSrc('/admin/blogcats/cat'),0,1);

		// If not empty
	    if(!empty($cats) && $cats->num_rows) {
	    
		    // Fetch cats
			while($row = $cats->fetch_assoc()) {
				$rows[] = $row;
			} 
			
			foreach($rows as $cat) {

			    if(!$cat['cat_protected']) {
			        $TEXT['temp-state'] = '<a onclick="$(\'#am-val-3\').val(\''.$cat['cid'].'\');_admin(71,0,0,\'load\',\'delblogcats\',1,1);" class="btn btn-small btn-light">'.$TEXT['_uni-Delete'].'</a>';
				} else {
					$TEXT['temp-state'] = '<span title="'.$TEXT['_uni-category_fixed'].'" class="theme-x-color padding-5 h5 b2 white-font-only">!</span>';
				}
		
				$TEXT['temp-cat_name'] = protectXSS($TEXT[$cat['cat_name']]);
		
				$TEXT['temp-add_cats'] .= display('',$item_tpl);

			}
		}

		return display(templateSrc('/admin/blogcats/box'));

	}
	
	function loadCategoris() {                                   // Fetch available categories
	    global $TEXT;

		// Select all categories
		$cats = $this->db->query("SELECT * FROM `categories` ORDER BY `cat_name`");
	
		// Reset
		$institutes = $brands = $artists = $entertainment = $communities  = array();
		$TEXT['temp-institutes'] = $TEXT['temp-brands'] = $TEXT['temp-artists'] = $TEXT['temp-entertainment'] = $TEXT['temp-communities'] = '';

		$item_tpl = display(templateSrc('/admin/pagecats/cat'),0,1);

		// If not empty
	    if(!empty($cats) && $cats->num_rows) {
	    
		    // Fetch cats
			while($row = $cats->fetch_assoc()) {
				
				switch ($row['cat_sub']) {	
						
					case '2':
					    $institutes[] = $row;
						break;
					case '3':
					    $brands[] = $row;
						break;
					case '4':
					    $artists[] = $row;
						break;
					case '5':
					    $entertainment[] = $row;
						break;
					case '6':
					    $communities[] = $row;
						break;						
		
				}
				
				// For foreach loop
				$rows[] = $row;
				
			} 
			
			foreach($rows as $cat) {

			    if($cat['cat_type']) {
			        $TEXT['temp-state'] = '<a onclick="$(\'#am-val-3\').val(\''.$cat['cid'].'\');_admin(56,0,0,\'load\',\'delcats\',1,1);" class="btn btn-small btn-light">'.$TEXT['_uni-Delete'].'</a>';
				} else {
					$TEXT['temp-state'] = '<span title="'.$TEXT['_uni-category_fixed'].'" class="theme-x-color padding-5 h5 b2 white-font-only">!</span>';
				}
		
				$TEXT['temp-cat_name'] = protectXSS($TEXT[$cat['cat_name']]);
		
		        // Categorize			
				switch ($cat['cat_sub']) {	
						
					case '2':
					    $TEXT['temp-institutes'] .= display('',$item_tpl);
						break;
					case '3':
					    $TEXT['temp-brands'] .= display('',$item_tpl);
						break;
					case '4':
					    $TEXT['temp-artists'] .= display('',$item_tpl);
						break;
					case '5':
					    $TEXT['temp-entertainment'] .= display('',$item_tpl);
						break;
					case '6':
					    $TEXT['temp-communities'] .= display('',$item_tpl);
						break;						
				}				
			}
		}

		$institutes_cats = display(templateSrc('/admin/pagecats/institutes'));
		$brands_cats = display(templateSrc('/admin/pagecats/brands'));
		$artists_cats = display(templateSrc('/admin/pagecats/artists'));
		$entertainment_cats = display(templateSrc('/admin/pagecats/entertainment'));
		$communities_cats = display(templateSrc('/admin/pagecats/communities'));

		return $institutes_cats.$brands_cats.$artists_cats.$entertainment_cats.$communities_cats;

	}
	
	function addCategory($name,$id) { 
        global $TEXT,$page_settings; 
		
		// No index
		if(empty($name)) {
			
			return showBox($TEXT['_uni-Add_index_err']);
		
		// Index not set
		} elseif(!isset($TEXT[$this->db->real_escape_string($name)])) {
			
			return showBox($TEXT['_uni-Add_index_err2']).'<script>scrollToTop();</script>';
		
		// Parent category not found
		} elseif(!in_array($id,array(2,3,4,5,6,7))) {
			
			return showBox($TEXT['_uni-Add_index_err3']).'<script>scrollToTop();</script>';
		
		// Confirmed
		} else {
			
			// Add a new blog category
			if($id == 7) {
		
           		$this->db->query(sprintf("INSERT INTO `blog_categories` (`cid`, `cat_name`, `cat_counts`, `cat_protected`) VALUES (NULL, '%s', '0', '0');",$this->db->real_escape_string(substr(protectXSS($name),0,32))));

            // Add a new page category			
			} else {
				
				$this->db->query(sprintf("INSERT INTO `categories` (`cid`, `cat_name`, `cat_sub`, `cat_type`) VALUES (NULL, '%s', '%s', '1');",$this->db->real_escape_string(substr(protectXSS($name),0,32)),$this->db->real_escape_string($id)));

			}
			
			return showBox($TEXT['_uni-Added_cat_success']).'<script>scrollToTop();</script>';
			
		}
	
	}
	
	function deleteCategory($cid,$type=0) {
		global $TEXT;
		
		if($type) {
			$this->db->query(sprintf("DELETE FROM `blog_categories` WHERE `cid` = '%s' AND `cat_protected` = '0'",$this->db->real_escape_string($cid)));
		} else {
			$this->db->query(sprintf("DELETE FROM `categories` WHERE `cid` = '%s' AND `cat_type` = '1'",$this->db->real_escape_string($cid)));
		}
		
		return ($this->db->affected_rows) ? showBox($TEXT['_uni-Delete_index_done']).'<script>scrollToTop();</script>' : showBox($TEXT['_uni-Delete_index_err']).'<script>scrollToTop();</script>' ;
	
	}
	
    function reorderBackground($image) {                     // Re-order post background
	    global $TEXT;

		// Get active backgrounds
		$backgrounds = explode(',',$TEXT['ACTIVE_BACKGROUNDS']);
		
		// Remove from list
		if (false !== $key = array_search($image, $backgrounds)) {
            unset($backgrounds[$key]);
        }
		
		// Add background
		array_unshift($backgrounds, $image);

		// Maximum 10 backgrounds
		if(count($backgrounds) > 10) {
			array_pop($backgrounds);
		}

		// Update backgrounds
		$this->db->query(sprintf("UPDATE `settings` SET `value` = '%s' WHERE `key` = 'post_backgrounds'",$this->db->real_escape_string(implode(',',$backgrounds))));

		// Load backgrounds wizard
		return '<script>_admin(52,0,0,\'load\',\'backgrounds\',1,1);</script>';
	
	}
	
	function getPostBackgrounds($admin) {                    // Fetch post backgrounds
	    global $TEXT;
		
	    $TEXT['temp-content'] = '';
		
        // Search for .jpg files
        $backgrounds = glob('../../../uploads/posts/backgrounds/' . '*.jpg', GLOB_BRACE);
	    
		foreach(explode(',',$TEXT['ACTIVE_BACKGROUNDS']) as $image) {
			
			// Build responsive gallery from trending posts
			$TEXT['temp-content'] .= '<div class="col padding-5" style="width:20%;">
                            <img class="round" onclick="$(\'#am-val-1\').val(\''.str_replace('.jpg','',$image).'\');_admin(54,0,0,\'load\',\'reorderback\',1,1);" src="'.$TEXT['installation'].'/thumb.php?src='.$image.'.jpg&fol=bb&w=252&h=192" style="width:100%;cursor:pointer">
                        </div>';
						
		}
		
		$active = display(templateSrc('/admin/backgrounds/active'));

	    $TEXT['temp-content'] = '';
		
		foreach(array_reverse($backgrounds) as $image) {
			
			$image = str_replace('../../../uploads/posts/backgrounds/','',$image);
			
			if(!in_array(str_replace('.jpg','',$image),explode(',',$TEXT['ACTIVE_BACKGROUNDS']))) {
			
			    if(!is_numeric(str_replace('.jpg','',$image))) {
					$c1 = 'relative';
					$c2 = '<span class="display-topright rounded  border-min shadow padding-5 h3 b2 white-font-only theme-x-color">'.$TEXT['_uni-New'].'</span>';
				} else {
					$c1 = $c2 = '';
				}
			
				// Build responsive gallery from trending posts
				$TEXT['temp-content'] .= '<div class="col padding-5 '.$c1.'" style="width:20%;">
                            	<img class="rounded" src="'.$TEXT['installation'].'/thumb.php?src='.$image.'&fol=bb&w=252&h=192" onclick="$(\'#am-val-2\').val(\''.str_replace('.jpg','',$image).'\');_admin(53,0,0,\'load\',\'activateback\',1,1);" style="width:100%;cursor:pointer">
                        	    '.$c2.'
							</div>';
						
			}
		}
		
		$available = display(templateSrc('/admin/backgrounds/available'));
		
		return $active.$available;
	
	}
	
	function activateBackground($image) {                    // Activate post background
	    global $TEXT;
	    
	    // Confirm availability
		if(file_exists('../../../uploads/posts/backgrounds/'.$image.'.jpg')) {
			
			
			if(!is_numeric($image)) {
				rename('../../../uploads/posts/backgrounds/'.$image.'.jpg','../../../uploads/posts/backgrounds/'.str_replace('n-','',$image).'.jpg');
			    $image  = str_replace('n-','',$image);
			}
	
			// Get active backgrounds
			$backgrounds = explode(',',$TEXT['ACTIVE_BACKGROUNDS']);
			
			// Add background
			array_unshift($backgrounds, $image);
			
			// Maximum 10 backgrounds
			if(count($backgrounds) > 10) {
				array_pop($backgrounds);
			}
			
			// Update backgrounds
			$this->db->query(sprintf("UPDATE `settings` SET `value` = '%s' WHERE `key` = 'post_backgrounds'",$this->db->real_escape_string(implode(',',$backgrounds))));
			
		}

		// Load backgrounds wizard
		return '<script>_admin(52,0,0,\'load\',\'backgrounds\',1,1);</script>';
	
	}
	
	function editUser($id) {                                 // Return edit user profile page
		global $TEXT;

		// Select user
		$username = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $this->db->real_escape_string($id)));	
		
		if($username->num_rows) {
			// Fetch user if exists
			$user = $username->fetch_assoc();
		} else {
			return '';
		}
		
		// If user exists
		if(!empty($user['idu'])) {
			
			// Check user state
			$suspended = ($user['state'] == 4 || $user['state'] == 3) ? '1' : '0';
			
			// Check IP address
			$ip = ($user['ip'] !== '') ? protectXSS($user['ip']) : '<i class="fa fa-exclamation-circle brz-text-red"></i>';

			// Load templates
			$input_tpl = display(templateSrc('/admin/elements/input'),0,1);
			$select_tpl = display(templateSrc('/admin/elements/select'),0,1);
			$title_tpl = display(templateSrc('/admin/elements/title'),0,1);
			
			$TEXT['temp-idu'] = $user['idu'];
			$TEXT['temp-addinputs'] = $this->getSelected($TEXT['_uni-Suspend'],'uUSAA-v7',getSelect($suspended,$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider','',$select_tpl);								
			$TEXT['temp-addinputs'] .= $this->getSelected($TEXT['_uni-Verified'],'uUSAA-v2',getSelect($user['verified'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider','',$select_tpl);								
			$TEXT['temp-addinputs'] .= $this->getSelected($TEXT['_uni-Email_verified'],'uUSAA-v3',getSelect($user['state'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider','',$select_tpl);								
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Username'],'uUSAA-vv1','',$user['username'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-First_name'],'uUSAA-vv2','',$user['first_name'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Last_name'],'uUSAA-vv3','',$user['last_name'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Email'],'uUSAA-vv4','',$user['email'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Hometown'],'uUSAA-vv5','',$user['from'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Living_in'],'uUSAA-vv6','',$user['living'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Education'],'uUSAA-ss1','',$user['study'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Profession'],'uUSAA-ss3','',$user['profession'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Website'],'uUSAA-ss2','',$user['website'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-title'] = $TEXT['_uni-Block_reporting']; $TEXT['temp-addinputs'] .= display('',$title_tpl);
			$TEXT['temp-addinputs'] .= $this->getSelected($TEXT['_uni-Users'],'uUSAA-v4',getSelect($user['b_users'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider','',$select_tpl);								
			$TEXT['temp-addinputs'] .= $this->getSelected($TEXT['_uni-Posts'],'uUSAA-v5',getSelect($user['b_posts'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider','',$select_tpl);								
			$TEXT['temp-addinputs'] .= $this->getSelected($TEXT['_uni-Comments'],'uUSAA-v6',getSelect($user['b_comments'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'bottom-divider','',$select_tpl);								
            $TEXT['temp-title'] = $TEXT['_uni-Advance_settings']; $TEXT['temp-addinputs'] .= display('',$title_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Password'],'uUSAA-as1',$TEXT['_uni-leve_emtyp_no_chng'],'','bottom-divider',"text",$input_tpl);
			
			// Generate content
			return display(templateSrc('/admin/users/edit'));
		}
	} 
	function getManageadds($admin,$settings) {               // Adds manager (sponsors)
		global $TEXT;


		$description_tpl = display(templateSrc('/admin/elements/description'),0,1);
		$title_tpl = display(templateSrc('/admin/elements/title'),0,1);
		
		$TEXT['temp-title'] = $TEXT['_uni-Fixed_ads']; $TEXT['temp-addinputs'] = display('',$title_tpl);
		
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl1'],'sponsor1',$TEXT['_uni-fixed_add1'],$settings['fi_add_search'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl2'],'sponsor2',$TEXT['_uni-fixed_add2'],$settings['fi_add_trending'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl2'],'sponsor3',$TEXT['_uni-fixed_add6'],$settings['fi_add_home1'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl3'],'sponsor4',$TEXT['_uni-fixed_add3'],$settings['fi_add_feed'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl4'],'sponsor5',$TEXT['_uni-fixed_add4'],$settings['fi_add_post'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl4'],'sponsor6',$TEXT['_uni-fixed_add5'],$settings['fi_add_relatives'],'bottom-divider',$description_tpl);
		
		$TEXT['temp-title'] = $TEXT['_uni-PopUp_Ads']; $TEXT['temp-addinputs'] .= display('',$title_tpl);
			
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl1'],'sponsor7',$TEXT['_uni-fixed_add7'],$settings['po_add_visit'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl1'],'sponsor8',$TEXT['_uni-fixed_add2'],$settings['po_add_trending'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl2'],'sponsor9',$TEXT['_uni-fixed_add6'],$settings['po_add_home'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl2'],'sponsor10',$TEXT['_uni-fixed_add8'],$settings['po_add_out'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl3'],'sponsor11',$TEXT['_uni-fixed_add9'],$settings['po_add_conn_user'],'bottom-divider',$description_tpl);
		$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-fixed_add_ttl3'],'sponsor12',$TEXT['_uni-fixed_add10'],$settings['po_add_conn_post'],'bottom-divider',$description_tpl);
		
		return display(templateSrc('/admin/sponsors'));		
	}
	
	function passwordMatches($id,$pass) {                    // Accept MD5(PASS) Return 1 if matches inSESSION admin
		
		// Try Selecting profile using password and username
		$profile = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `admins`.`id` = '%s' AND `admins`.`password` = '%s'", $this->db->real_escape_string($id),$this->db->real_escape_string($pass)));		
	    
		// Return test results
		return ($profile->num_rows) ? 1 : 0;
		
	}
	
	function updateAdmin($admin,$v1,$v2,$v3) {               // Update administration password
	    // | $v1 = Old Password | $v2 = New password | $v3 = Retyped new password |
	    
		global $TEXT;
		
		// Count length
		$len = strlen($v2);
		
		// Encrypt passwords (Make cookies,SESSIONS and Database unreadable)
		$new = md5($v2);$old = md5($v1);
		
		if($len > 32 || $len < 6) {
			
			// Password out of length
			return showError($TEXT['_uni-error_password_len']);
			
		} elseif($v2 !== $v3) {
			
			// New password and retyped new password doesn't matches
			return showError($TEXT['_uni-error_password_match']);
			
		} elseif($this->passwordMatches($admin['id'],$old) !== 1) {
			
			// Old password is incorrect
			return showError($TEXT['_uni-error_old_not2']);
			
		} else {
			
			// Update
			$this->db->query(sprintf("UPDATE `admins` SET `admins`.`password` = '%s' WHERE `admins`.`id` = '%s'", $this->db->real_escape_string($new), $this->db->real_escape_string($admin['id'])));
		
			if($this->db->affected_rows) {
				
				// Update SESSION
				$_SESSION['a_password'] = $new;
                return showSuccess($TEXT['_uni-Settings_updated']);
				
		    } else {
				// Else return no changes notifier
				return showNotification($TEXT['_uni-No_changes']);
		    }
		}
	}
	
    function editGroup($id) {                                // Return edit group page
		global $TEXT;

		// Select group
		$groups = $this->db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_id` = '%s' ", $this->db->real_escape_string($id)));	
		
		$group = ($groups->num_rows) ? $groups->fetch_assoc() : '';
	
		// If group exists
		if(!empty($group['group_id'])) {
	
	        // Load templates
			$input_tpl = display(templateSrc('/admin/elements/input'),0,1);
			$description_tpl = display(templateSrc('/admin/elements/description'),0,1);

			$TEXT['temp-addinputs'] = $this->enrollInput($TEXT['_uni-Username'],'uUSAA-vv1','',$group['group_username'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Name'],'uUSAA-vv2','',$group['group_name'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Email'],'uUSAA-vv4','',$group['group_email'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($TEXT['_uni-Description'],'uUSAA-vv3','',$group['group_description'],'bottom-divider',$description_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Location'],'uUSAA-vv5','',$group['group_location'],'bottom-divider',"text",$input_tpl);
			$TEXT['temp-addinputs'] .= $this->enrollInput($TEXT['_uni-Website'],'uUSAA-vv6','',$group['group_web'],'',"text",$input_tpl);

			$TEXT['temp-id'] = $group['group_id'];
			
			return display(templateSrc('/admin/groups/edit'));
		}
	} 
	
	function updateGroup($id,$username,$name,$description,$email,$location,$website) {                            // Save group edit		
		global $TEXT,$page_settings;
		
		// Select data
		$groups = $this->db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_id` = '%s' ", $this->db->real_escape_string($id)));	
		$username_get = $this->db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_username` = '%s' ", $this->db->real_escape_string($username)));	
		
		if($groups->num_rows !== 0) {
			
			// Fetch user if exists
			$group = $groups->fetch_assoc();
		
		} else {
			
			// Else return 0
			return '';
		}
		
		// Username availabilty
		$username_ava = ($username_get->num_rows && $username !== $group['group_username']) ?  false : true;
		
		if(!empty($email) && !filter_var($this->db->real_escape_string($email), FILTER_VALIDATE_EMAIL)) {
			
			// Invalid email
			return showError($TEXT['_uni-signup-4']);
			
		} elseif(strlen(trim($name)) > 32 || strlen(trim($name)) < 6) {
			
			// If first name is out no length
			return showError($TEXT['_uni-group_val-1']);
			
		} elseif(!empty($username) && !$username_ava) {
			
			// Username already in use
			return showError($TEXT['_uni-Username_exists']);
			
		} elseif(!empty($website) && (strlen($website) > 64 || (!filter_var($website, FILTER_VALIDATE_URL) && !empty($website)))) {
			
			return showError($TEXT['_uni-error_web_in']);
			
		} elseif(!empty($username) && (strlen(trim($username)) < $page_settings['username_min_len'] || strlen(trim($username)) > $page_settings['username_max_len'])) {
			
			// Verify whether user name is within allowed length
			return showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
				
		} elseif(!empty($email) && (!ctype_alnum(trim($username)) || is_numeric(trim($username)))) {
			
			// Allow only valid chars for username
			return showError($TEXT['_uni-signup-9']);
			
		} elseif(!empty($group['group_id'])) {
			
			// Update profile
			$this->db->query(sprintf("UPDATE `groups` SET `group_username` = '%s', `group_name` = '%s', `group_description` = '%s', `group_email` = '%s', `group_location` = '%s', `group_web` = '%s' WHERE `groups`.`group_id` = '%s' ", 
			$this->db->real_escape_string($username),$this->db->real_escape_string($name),$this->db->real_escape_string($description),$this->db->real_escape_string($email),
			$this->db->real_escape_string($location),$this->db->real_escape_string($website),$this->db->real_escape_string($id)));	
		
	        // Return if affected 
		    return ($this->db->affected_rows) ? showSuccess($TEXT['_uni-Settings_updated']) : showNotification($TEXT['_uni-No_changes']);
		}		
	
	}
	
	function updateUserprofile($id,$state,$verify,$b_users,$b_posts,$b_comments,$v8,$v9,$v10,$v11,$v12,$v13,$v14,$v15,$v16,$v17) {// Save user edit		
		global $TEXT,$page_settings;
		
		if(!class_exists('main')) {
			require_once(__DIR__ . '/classes.php');
		}
	
		$profile = new main();
		$profile->db = $this->db;
		
		// Select user
		$username = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $this->db->real_escape_string($id)));	
		
		if($username->num_rows !== 0) {
			
			// Fetch user if exists
			$user = $username->fetch_assoc();
		
		} else {
			
			// Else return 0
			return '';
		}
		
        // Validation		
		if(!filter_var($this->db->real_escape_string($v11), FILTER_VALIDATE_EMAIL)) {
			
			// Invalid email
			return showError($TEXT['_uni-signup-4']);
			
		} elseif(strlen(trim($v9)) > 15) {
			
			// If first name is out no length
			return showError($TEXT['_uni-error_firstname_len']);
			
		} elseif(strlen(trim($v10)) > 15) {
			
			// If last name is out of length
			return showError($TEXT['_uni-error_lastname_len']);
			
		} elseif(!filter_var($this->db->real_escape_string($v11), FILTER_VALIDATE_EMAIL) || strlen(trim($v11)) < 3 || strlen(trim($v11) > 62)) {
			
			// Invalid email
			return showError($TEXT['_uni-signup-4']);
			
		} elseif($profile->isEmailExists($v11) && $user['email'] !== $v11) {
			
			// Verify whether user name exists
			return showError($TEXT['_uni-signup-6']);
			
		} elseif(isXSSED($v16) || strlen($v16)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_profession_len']);
			
		} elseif(isXSSED($v13) || strlen($v13)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_living_len']);
			
		} elseif(isXSSED($v12) || strlen($v12)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_hometown_len']);
			
		} elseif(isXSSED($v15) || strlen($v14)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_education_len']);
			
		} elseif(!empty($username) && $profile->isUsernameExists($v8) && $user['username'] !== $v8) {
			
			// Username already in use
			return showError($TEXT['_uni-Username_exists']);
			
		} elseif(strlen($v15) > 64 || (!filter_var($v15, FILTER_VALIDATE_URL) && !empty($v15))) {
			
			return showError($TEXT['_uni-error_web_in']);
			
		} elseif(strlen(trim($v8)) < $page_settings['username_min_len'] || strlen(trim($v8)) > $page_settings['username_max_len']) {
			
			// Verify whether user name is within allowed length
			return showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
				
		} elseif(!ctype_alnum(trim($v8)) || is_numeric(trim($v8))) {
			
			// Allow only valid chars for username
			return showError($TEXT['_uni-signup-9']);
			
		} elseif(!empty($user['idu'])) {
			
			// Update profile
			$this->db->query(sprintf("UPDATE `users` SET `study` = '%s' $v17 , `website` = '%s', `profession` = '%s', `state` = '%s', `verified` = '%s', `b_users` = '%s', `b_posts` = '%s', `b_comments` = '%s' , `username` = '%s' , `first_name` = '%s' , `last_name` = '%s' , `email` = '%s' , `from` = '%s' , `living` = '%s' WHERE `users`.`idu` = '%s' ", 
			$this->db->real_escape_string($v14),$this->db->real_escape_string($v15),$this->db->real_escape_string($v16),$this->db->real_escape_string($state), $this->db->real_escape_string($verify), $this->db->real_escape_string($b_users), 
		    $this->db->real_escape_string($b_posts), $this->db->real_escape_string($b_comments), $this->db->real_escape_string(protectInput($v8)),
			$this->db->real_escape_string(protectInput($v9)) , $this->db->real_escape_string(protectInput($v10)), $this->db->real_escape_string(protectInput($v11)),
			$this->db->real_escape_string(protectInput($v12)),$this->db->real_escape_string(protectInput($v13)),$this->db->real_escape_string($user['idu'])));	
		
	        // Return if affected 
		    return ($this->db->affected_rows) ? showSuccess($TEXT['_uni-Settings_updated']) : showNotification($TEXT['_uni-No_changes']);
		}		
	
	}
	
    function loadSettings($data,$inputs) {                   // Load amin settings
        global $TEXT;
		
		$TEXT['temp-addinputs'] = '';
		
		// Load templates
		$input_tpl = display(templateSrc('/admin/elements/input'),0,1);
		$select_tpl = display(templateSrc('/admin/elements/select'),0,1);
		$title_tpl = display(templateSrc('/admin/elements/title'),0,1);
		$description_tpl = display(templateSrc('/admin/elements/description'),0,1);
		
		// Add inputs
		foreach($inputs as $input) {
	
			// Add input field
			if($input[0] == "input") {
				
				$divider = ($input[5] == "SEP") ? 'bottom-divider' : '';
				
				$TEXT['temp-addinputs'] .= $this->enrollInput($input[1],$input[2],$input[3],$input[4],$divider,"text",$input_tpl);
			
			// Else add select field
			} elseif($input[0] == "select") {

                $divider = ($input[6] == "SEP") ? 'bottom-divider' : '';
				
				$TEXT['temp-addinputs'] .= $this->getSelected($input[1],$input[2],getSelect($input[3],$input[4],$input[5]),$divider,$input[7],$select_tpl);							
			
			// Else add select field with three inputs
			} elseif($input[0] == "selectthree") {

                $divider = ($input[7] == "SEP") ? 'bottom-divider' : '';
				
				$TEXT['temp-addinputs'] .= $this->getSelected($input[1],$input[2],getSelVal($input[3],$input[4],$input[5],$input[6]),$divider,$input[8],$select_tpl);								
			
			// Add title
			} elseif($input[0] == "title") {

			    $TEXT['temp-title'] = $input[1];
				
			    $TEXT['temp-addinputs'] .= display('',$title_tpl);							
			
			// Add description input
			} else {
				
				$divider = ($input[4] == "SEP") ? 'bottom-divider' : '';
				
				$TEXT['temp-addinputs'] .= $this->enrollDescriptioner($input[1],$input[2],$input[3],$input[4],$divider,$description_tpl);
				
			}
			
		}
		
		// Set data for contianer
		$TEXT['temp-heading'] = $data['heading'];
		$TEXT['temp-headingbtn'] = $data['heading_btn'];
		$TEXT['temp-jsfunction'] = $data['jsfunction'];
		
		// Return page
		return display(templateSrc('/admin/settings'));
	
	}
	
    function updateSettings($settings_set,$keys_set,$protection_set) { // Update Settings universal function
		global $TEXT;

		if (is_array($settings_set)) {
			
			//	Reset
			$i = $changes = 0 ;

			foreach ($settings_set as $setting) {
			
				// Enable disable protection on fields and bind values and update settings
				$this->db->query(sprintf("UPDATE `settings` SET `settings`.`value` = '%s' WHERE `settings`.`key` = '%s'",  $this->db->real_escape_string(((in_array($i, $protection_set)) ? protectInput($setting) : $setting)), $this->db->real_escape_string($keys_set[$i])));
			
				// Verify changes made
				if ($this->db->affected_rows) {
					$changes = TRUE;
				}

				// Next
				$i++ ;

			}

			// Check for affected rows and return message
		    return ($changes) ? showError($TEXT[$keys_set[$i]]) : showError($TEXT['_uni-No_changes']); 
			
		} else {
			
			// Error not a array
		    return showError($TEXT['_uni-No_changes']); 

		}
	}
	
	function saveSEO($keywords,$description) {               // Save SEO Settings
	    global $TEXT;
		
		$file_src = templateSrc('/main/main');
		
		// Verify data
		if(substr_count($keywords, ",") < 4) {
			return showError($TEXT['_uni-seo_keyword_nt']);
		} elseif(strlen($description) < 15) {
			return showError($TEXT['_uni-seo_descript_nt']);
		} else {
		
			// Get saved tags
			$meta_tags = get_meta_tags($file_src);
		
	    	$main_file = str_replace(array($meta_tags['keyword'],$meta_tags['description']),array(trim(str_replace('"','',$keywords)),trim(str_replace('"','',$description))),file_get_contents($file_src));

			$save = fopen($file_src, "w");
		
			// Save meta tags
        	fwrite($save, $main_file);
        	fclose($save);
			
			$main_file = str_replace(array($meta_tags['keyword'],$meta_tags['description']),array(trim(str_replace('"','',$keywords)),trim(str_replace('"','',$description))),file_get_contents(templateSrc('/home/home')));

			$save = fopen(templateSrc('/home/home'), "w");
		
			// Save meta tags
        	fwrite($save, $main_file);
        	fclose($save);
		
			return showError($TEXT['_uni-Settings_updated']);
		}

	}
	
	function getSelected($title,$id,$tex,$end,$desc="",$template) {             // Get select  
		global $TEXT;
		$TEXT['temp-id'] = $id; 	
		$TEXT['temp-title'] = $title; 
		$TEXT['temp-options'] = $tex;  
		$TEXT['temp-description'] = $desc; 
		$TEXT['temp-seprator'] = $end; 
		return display('',$template);
	}
	
	function enrollInput($title,$id,$tex,$val,$end,$type="text",$template) {// Get input                   
		global $TEXT;
		$TEXT['temp-id'] = $id; 	
		$TEXT['temp-title'] = $title; 
		$TEXT['temp-type'] = $type; 
		$TEXT['temp-val'] = $val; 
		$TEXT['temp-description'] = $tex; 
		$TEXT['temp-seprator'] = $end; 
		return display('',$template);
	}

	function enrollDescriptioner($title,$id,$tex,$val,$end,$template) {// Get input for description                 
		global $TEXT;
		$TEXT['temp-id'] = $id; 	
		$TEXT['temp-title'] = $title; 
		$TEXT['temp-description'] = $tex; 
		$TEXT['temp-val'] = $val; 
		$TEXT['temp-seprator'] = $end;
        return display('',$template);		
	}	

	function getRegChart($admin,$type) {                     // Administration home | Throw Listed STATS
		global $TEXT;
		
        // Select Query
		$query = "SELECT(SELECT COUNT(idu) FROM `users` ) AS total_regs,
					(SELECT COUNT(page_id) FROM pages ) AS total_pages,
					(SELECT COUNT(group_id) FROM groups ) AS total_groups,
					(SELECT COUNT(post_id) FROM user_posts ) AS total_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 1 ) AS total_photos,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 2 ) AS total_videos,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 ) AS total_youtubeshares,
					(SELECT COUNT(blog_id) FROM blogs) AS total_blogs,
					(SELECT COUNT(id) FROM reports ) AS total_reports,				
					(SELECT COUNT(idu) FROM `users` WHERE CURDATE() = `date` AND `state` != 4) AS today_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND CURDATE() = date(`post_time`)) AS today_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND CURDATE() = date(`post_time`)) AS today_youtubeshares,
					(SELECT COUNT(id) FROM reports  WHERE CURDATE() = date(`time`)) AS today_reports,					
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = `date` AND `state` != 4) AS yesterday_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(`post_time`)) AS yesterday_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(`post_time`)) AS yesterday_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(`time`)) AS yesterday_reports,		
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 2 DAY) = `date` AND `state` != 4) AS d2_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 2 DAY) = date(`post_time`)) AS d2_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 2 DAY) = date(`post_time`)) AS d2_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 2 DAY) = date(`time`)) AS d2_reports,					
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 3 DAY) = `date` AND `state` != 4) AS d3_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 3 DAY) = date(`post_time`)) AS d3_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 3 DAY) = date(`post_time`)) AS d3_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 3 DAY) = date(`time`)) AS d3_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 4 DAY) = `date` AND `state` != 4) AS d4_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 4 DAY) = date(`post_time`)) AS d4_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 4 DAY) = date(`post_time`)) AS d4_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 4 DAY) = date(`time`)) AS d4_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 5 DAY) = `date` AND `state` != 4) AS d5_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 5 DAY) = date(`post_time`)) AS d5_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 5 DAY) = date(`post_time`)) AS d5_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 5 DAY) = date(`time`)) AS d5_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 6 DAY) = `date` AND `state` != 4) AS d6_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 6 DAY) = date(`post_time`)) AS d6_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 6 DAY) = date(`post_time`)) AS d6_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 6 DAY) = date(`time`)) AS d6_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) = `date` AND `state` != 4) AS d7_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(`post_time`)) AS d7_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(`post_time`)) AS d7_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(`time`)) AS d7_reports,
					(SELECT COUNT(idu) FROM `users` WHERE `state` != 4 AND date(`date`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_regs,
					(SELECT COUNT(page_id) FROM `pages` WHERE date(`time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_pages,					
					(SELECT COUNT(group_id) FROM `groups` WHERE date(`time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_groups,
					(SELECT COUNT(post_id) FROM user_posts WHERE date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 1 AND date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_photos,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 2 AND date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_videos,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_youtubeshares,
					(SELECT COUNT(blog_id) FROM blogs WHERE date(`blog_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_blogs,
					(SELECT COUNT(id) FROM reports WHERE date(`time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_reports					
					";
	
		// Query and fetch results
		$result = $this->db->query($query);
	
		// Get statics list and assign 
		
		list($stats['total_regs'],$stats['total_pages'],$stats['total_groups'],$stats['total_posts'],$stats['total_photos'],$stats['total_videos'],$stats['total_youtubeshares'],
		
		$stats['total_blogs'],$stats['total_reports'],	
	
		$stats['today_regs'],$stats['today_posts'],$stats['today_youtubeshares'],$stats['today_reports'],
	
		$stats['yesterday_regs'],$stats['yesterday_posts'],$stats['yesterday_youtubeshares'],$stats['yesterday_reports'],
		
		$stats['d2_regs'],$stats['d2_posts'],$stats['d2_youtubeshares'],$stats['d2_reports'],
		
		$stats['d3_regs'],$stats['d3_posts'],$stats['d3_youtubeshares'],$stats['d3_reports'],
		
		$stats['d4_regs'],$stats['d4_posts'],$stats['d4_youtubeshares'],$stats['d4_reports'],
		
		$stats['d5_regs'],$stats['d5_posts'],$stats['d5_youtubeshares'],$stats['d5_reports'],
		
		$stats['d6_regs'],$stats['d6_posts'],$stats['d6_youtubeshares'],$stats['d6_reports'],
		
		$stats['d7_regs'],$stats['d7_posts'],$stats['d7_youtubeshares'],$stats['d7_reports'],
		
		$stats['lt_regs'],$stats['lt_pages'],$stats['lt_groups'],$stats['lt_posts'],$stats['lt_photos'], $stats['lt_videos'], $stats['lt_youtubeshares'],$stats['lt_blogs'],$stats['lt_reports']

		) = $result->fetch_row() ;		
		
		// Percentage calculations
		$per_users = (!$stats['lt_regs']) ? '0' : substr($stats['lt_regs']/$stats['total_regs']*100,0,5);
		$per_pages = (!$stats['lt_pages']) ? '0' : substr($stats['lt_pages']/$stats['total_pages']*100,0,5);
		$per_groups = (!$stats['lt_groups']) ? '0' : substr($stats['lt_groups']/$stats['total_groups']*100,0,5);
		$per_posts = (!$stats['lt_posts']) ? '0' : substr($stats['lt_posts']/$stats['total_posts']*100,0,5);
		$per_photos = (!$stats['lt_photos']) ? '0' : substr($stats['lt_photos']/$stats['total_photos']*100,0,5);
		$per_videos = (!$stats['lt_videos']) ? '0' : substr($stats['lt_videos']/$stats['total_videos']*100,0,5);
		$per_yts = (!$stats['lt_youtubeshares']) ? '0' : substr($stats['lt_youtubeshares']/$stats['total_youtubeshares']*100,0,5);
		$per_rep = (!$stats['lt_reports']) ? '0' : substr($stats['lt_reports']/$stats['total_reports']*100,0,5);
		$per_blogs = (!$stats['lt_blogs']) ? '0' : substr($stats['lt_blogs']/$stats['total_blogs']*100,0,5);

		// Storage calculation
		$avatars = round(getFolderSize('../../../uploads/profile/main')/1024/1024);	
		$covers = round(getFolderSize('../../../uploads/profile/covers')/1024/1024 + getFolderSize('../../../uploads/groups')/1024/1024);		
		$uploads = round(getFolderSize('../../../uploads/posts/photos')/1024/1024);	
		$chats = round(getFolderSize('../../../uploads/chats')/1024/1024);	
		$pages1 = round(getFolderSize('../../../uploads/pages/covers')/1024/1024);	
		$pages2 = round(getFolderSize('../../../uploads/pages/main')/1024/1024);	
		$videos = round(getFolderSize('../../../uploads/posts/videos')/1024/1024);	
		$blogs = round(getFolderSize('../../../uploads/blogs/photos')/1024/1024);	
		$pages = round($pages1+$pages2);	
		$cached = getFolderSize('../../../cache');				
		$temp_files = getFolderSize('../../../main/logs');				
		$total = round($avatars + $videos + $covers + $pages + $uploads + $blogs + $chats);
		$per_cah = substr($cached/1024/1024/$total*100,0,5);
		
		// Time to last seven days
		$dateString = date('Y-m-d',time()); // for example
		$back = date('Y-m-d', strtotime("$dateString -7 days"));
		$days = '';
		$period = new DatePeriod(new DateTime($back), new DateInterval('P1D'), 7 );
     
	    // Today date
		$date = date('Y-m-d'); 

		// Create days
		foreach ($period as $day) { $days .= '"'.substr($day->format('l'),0,3).'",';}
	
	    // Plot graphs onscreen
		return  '<div class="block">
					<div class="padding-20 h7 b2 dark-font-only bottom-divider">'.$TEXT['_uni-PERFORMANCE'].'</div>
				</div>		
				<div class="padding-10"><canvas id="content_gt_graph" height="200"></canvas></div></div>
				<script>
					var ctx = document.getElementById("content_gt_graph");
					var myChart = new Chart(ctx, {
   						type: \'line\',
   						data: {
   						labels: ['.$days.'],
   						datasets: [{
   							label: \''.$TEXT['web_name'].'\',
   							data: ['.$stats['d7_posts'].', '.$stats['d6_posts'].', '.$stats['d5_posts'].', '.$stats['d4_posts'].', '.$stats['d3_posts'].' , '.$stats['d2_posts'].','.$stats['yesterday_posts'].','.$stats['today_posts'].'],
							borderColor: \'rgb(0, 204, 99)\',borderWidth: 1,fill:false,backgroundColor: "#fff",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgba(75,192,192,1)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgb(0, 255, 153)",pointHoverBorderColor: "rgba(220,220,220,1)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		},{
   							label: \''.$TEXT['_uni-Outer_sources'].'\',
   							data: ['.$stats['d7_youtubeshares'].', '.$stats['d6_youtubeshares'].', '.$stats['d5_youtubeshares'].', '.$stats['d4_youtubeshares'].', '.$stats['d3_youtubeshares'].' , '.$stats['d2_youtubeshares'].','.$stats['yesterday_youtubeshares'].','.$stats['today_youtubeshares'].'],
   							borderColor: \'rgb(230, 230, 0)\',borderWidth: 1,fill:false,backgroundColor: "#fff",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgb(230, 230, 0)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgb(255, 255, 26)",pointHoverBorderColor: "rgb(230, 230, 0)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		},{
   							label: \''.$TEXT['_uni-Pending_issues'].'\',
   							data: ['.$stats['d7_reports'].', '.$stats['d6_reports'].', '.$stats['d5_reports'].', '.$stats['d4_reports'].', '.$stats['d3_reports'].' , '.$stats['d2_reports'].','.$stats['yesterday_reports'].','.$stats['today_reports'].'],
   							borderColor: \'rgb(255, 51, 0)\',borderWidth: 1,fill:false,backgroundColor: "#fff",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgb(204, 41, 0)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgb(255, 71, 26)",pointHoverBorderColor: "rgb(179, 36, 0)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		}]
   		    		}});
   				</script>
                <div class="block">
					<div class="padding-20 h7 b2 dark-font-only bottom-divider">'.$TEXT['_uni-Storage'].'<span class="b1 h5 tin-light-font-only">'.sprintf($TEXT['_uni-sprint_storage'],$total).'</span></div>			
				</div>			
	            <div class="padding-10"><canvas id="storage_gt_pie" width="400" height="200"></canvas></div>
   				<script>
   					var ctx = document.getElementById("storage_gt_pie");
   					var data = {
                        	labels: ["'.$TEXT['_uni-Avatars'].'","'.$TEXT['_uni-Covers'].'","'.$TEXT['_uni-Posts'].'","'.$TEXT['_uni-Videos'].'","'.$TEXT['_uni-Chats'].'","'.$TEXT['_uni-Pages'].'"],
                        	datasets: [{data: ['.$avatars.', '.$covers.', '.$uploads.', '.$videos.', '.$chats.', '.$pages.'],backgroundColor: ["#FF6384","#36A2EB","#FFCE56", "#FFAF4F", "#47d147","#eaa1b9"],hoverBackgroundColor: ["#ff1a4b","#2e9fea","#ffb700","#FF8B00","#33cc33","#d1c2c9"]}]
						};
		
					var myPieChart = new Chart(ctx,{type: \'pie\',data: data
					});
				</script>
                <div class="block">
					<div class="padding-20 h7 b2 dark-font-only bottom-divider">'.$TEXT['_uni-Registrations'].'</div>			
				</div>
				<div class="padding-10"><canvas id="reg_gt_graph" height="200"></canvas></div></div>
				<script>
					var ctx = document.getElementById("reg_gt_graph");
					var myChart = new Chart(ctx, {
   						type: \'line\',
   						data: {
   						labels: ['.$days.'],
   						datasets: [{
   							label: \''.$TEXT['_uni-New_Registrations'].'\',
   							data: ['.$stats['d7_regs'].', '.$stats['d6_regs'].', '.$stats['d5_regs'].', '.$stats['d4_regs'].', '.$stats['d3_regs'].' , '.$stats['d2_regs'].','.$stats['yesterday_regs'].','.$stats['today_regs'].'],
							fill:true,borderColor: \'rgb(0, 102, 204)\',borderWidth: 1,backgroundColor: "rgba(75,192,192,0.4)",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgba(75,192,192,1)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgba(75,192,192,1)",pointHoverBorderColor: "rgba(220,220,220,1)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		}]
   		    		}});
   				</script>
                <div class="block">
					<div class="padding-20 h7 b2 dark-font-only bottom-divider">'.$TEXT['_uni-Content'].'</div>			
				</div>
				<div class="padding-10"><canvas id="content_gt_chart" width="400" height="200"></canvas></div>	
				<div class="brz-hide">
				    <div id="QUICK_DETACH" class="block-container brz-dettachable brz-detach-to-threequarter">
						<div class="block-title">
                            <div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-All_time_stocked'].'</div>
                        </div>
						<div class="padding-0-15">
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_regs']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Users'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_users.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_pages']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Pages'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_pages.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                        <hr style="margin:10px;">						
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_groups']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Groups'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_groups.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                        <hr style="margin:10px;">						
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_blogs']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Blogs'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_blogs.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                        <hr style="margin:10px;">
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_posts']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Posts'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_posts.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                        <hr style="margin:10px;">
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_photos']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Photos'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_photos.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>	
                        <hr style="margin:10px;">
					    <div class="padding-0-10-10-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_videos']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Videos'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_videos.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
						<hr style="margin:10px;">
					    <div class="padding-0-10-10-10">
                            <div class="h20 b1 dark-font-only">'.number_format($stats['total_youtubeshares']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Youtube_shares'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="green-font italic">'.$per_yts.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                		</div>
				   </div>
				   
				   <div id="QUICK_DETACH_2" class="block-container crackable">
						<div class="block-title">
                            <div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Reports'].'</div>
                        </div>
						<div class="padding-0-15">
					    <div class="padding-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.readable($stats['total_reports']).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Reports'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="theme-x-font italic">'.$per_rep.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>				
                		</div>				
				   </div>
				   
				   <div id="QUICK_DETACH_3" class="block-container crackable">
						<div class="block-title">
                            <div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Website_cache'].'</div>
                        </div>
						<div class="padding-0-15">
					    <div class="padding-10">
                            <div class="h20 b1 dark-font-only">'.readableBytes($cached).' <span class="h6 b1 dark-font-only">'.$TEXT['_uni-Storage_cached'].'</span></div>
                            <div class="h5 b1 tin-light-font-only"><span class="theme-font italic">'.$per_cah.'%</span> '.$TEXT['_uni-cache_sp'].'</div>			
                		</div>				
                		</div>				
				   </div>
				   
				   <div id="QUICK_DETACH_4" class="block-container crackable"">
						<div class="block-title">
                            <div class="h7 b3 dark-font-only padding-10">'.$TEXT['_uni-Temp_files'].'</div>
                        </div>
						<div class="padding-0-15">
					    <div id="TEMP_FILE_CONTAINER" class="padding-10 bottom-divider">
                            <div class="h20 b1 dark-font-only">'.readableBytes($temp_files).' <span onclick="clearTemp();" id="settings-content-save-%s" class="btn btn-small btn-light">'.$TEXT['_uni-Clear'].'</span></div>
                        </div>				
                        </div>				
				   </div>
				   
				</div>
				
				<script>
   					var ctx = document.getElementById("content_gt_chart");
   					var myChart = new Chart(ctx, {
   					    type: \'bar\',
   					    data: {
   					    labels: ["'.$TEXT['_uni-Users'].'", "'.$TEXT['_uni-Pages'].'", "'.$TEXT['_uni-Groups'].'", "'.$TEXT['_uni-Posts'].'", "'.$TEXT['_uni-Photos'].'", "'.$TEXT['_uni-Youtube'].'", "'.$TEXT['_uni-Reports'].'"],
   					    datasets: [{
   					        label: \''.$TEXT['_uni-Total'].'\',
   					        data: ['.$stats['total_regs'].', '.$stats['total_pages'].', '.$stats['total_groups'].', '.$stats['total_posts'].', '.$stats['total_photos'].', '.$stats['total_youtubeshares'].', '.$stats['total_reports'].'],
   					        backgroundColor: [\'rgba(54, 162, 235, 0.2)\',\'#ffc7da\',\'rgba(54, 198, 98, 0.2)\',\'rgba(255, 206, 86, 0.2)\',\'rgba(75, 192, 192, 0.2)\',\'rgba(153, 102, 255, 0.2)\',\'rgba(255, 99, 132, 0.2)\'],borderColor: [\'rgba(54, 162, 235, 1)\',\'#eaa1b9\',\'rgba(54, 198, 122, 1)\',\'rgba(255, 206, 86, 1)\',\'rgba(75, 192, 192, 1)\',\'rgba(153, 102, 255, 1)\',\'rgba(255,99,132,1)\'],borderWidth: 1}]
   					    },
   					    options: {scales: {yAxes: [{ticks: {beginAtZero:true}}]}}
					});	
					$("#RIGHT_LANGUAGE").before($("#QUICK_DETACH").detach()).before($("#QUICK_DETACH_2").detach()).before($("#QUICK_DETACH_3").detach()).before($("#QUICK_DETACH_4").detach());
					addCrack();
				</script>';
			
	}

	
}

function getMetaTags($type) {                                // Return Meta tags
	$meta_tags = get_meta_tags(templateSrc('/main/main'));
	return ($type) ? $meta_tags['keyword'] : $meta_tags['description'];
}

// Add global uncions if don' exists
if(!function_exists('emailVerification')) {
	require_once(__DIR__ . '/functions.php');
}
?>