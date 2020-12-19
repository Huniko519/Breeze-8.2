<?php
//--------------------------------------------------------------------------------------//
//                          Breeze Social networking platform                           //
//                                     PHP CLASSES                                      //
//--------------------------------------------------------------------------------------//

class main {            // Users management
	
	// Properties
	public $db;                   // Database connection
	public $username;             // Username property
	public $password;             // Password property
	public $followings;           // Logged user followers (ARRAY)
	public $followers;            // Logged user followings (ARRAY)
	public $settings;             // Administration settings
	public $admin;                // Administration detection
	
	function logOut() {                                             // Log out user

		// Save login as recent
		if(!empty($_SESSION['username'])) {
			
			$user = (filter_var($_SESSION['username'], FILTER_VALIDATE_EMAIL)) ? $this->getUserByEmail($_SESSION['username']) : $this->getUserByUsername($_SESSION['username']);
			
			if(!empty($user['idu'])) {
				
				// Get login cookie
				$recent_logins = (!empty($_COOKIE['loggedout'])) ? explode(',', preg_replace('/,{2,}/', ',', trim(str_replace('ID_'.$user['idu'].'_ID','',$_COOKIE['loggedout']), ','))) : NULL ;
				
				// Create list
				$login_1 = 'ID_'.$user['idu'].'_ID';
				$login_2 = ($recent_logins[0]) ? ','.$recent_logins[0] : '' ;
				$login_3 = ($recent_logins[1]) ? ','.$recent_logins[1] : '' ;
			
				// Update cookie
				setcookie("loggedout", $login_1.$login_2.$login_3, time() + 30 * 24 * 60 * 60,'/');
			}
		}
		
		// Unset SESSIONs
		unset($_SESSION['username']);
		unset($_SESSION['password']);
	
		// Unset Cookies
		setcookie("username", "", time() + 1 * 1,'/');
		setcookie("password", "", time() + 1 * 1,'/');
		
		return 0;
	}	

	function passwordMatches($id,$pass) {                           
		
		// Return true if password matches
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery("PASSWORDMATCHES"), $this->db->real_escape_string($id),$this->db->real_escape_string($pass) ));
	
	}
	
	function saltMatches($id,$salt) {                               
		
		// Return true if salt matches
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery("SALTMATCHES"), $this->db->real_escape_string($id),$this->db->real_escape_string($salt) ));
	
	}	
	
	function isEmailExists($email) {                                // Return true if email exists
		
		// Return true if email exists
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery("EMAIL_EXISTS"), $this->db->real_escape_string(strtolower($email)) ));

	}
	
	function isRequested($user_id,$to_id) {                         // Return true if request exists
		
		// Return true if $user_id as requested to $to_id
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery("ISREQUESTED"), $this->db->real_escape_string($user_id),$this->db->real_escape_string($to_id) ));

	}
	
    function getLoved($post,$by_user,$type=0) {	                    // Return love
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(($type) ? "COMMENT_LIKE" : "POST_LIKE"), $this->db->real_escape_string($post), $this->db->real_escape_string($by_user) ));

	}
	
	function isLoved($post,$by_user,$type=0) {	                    // Return true if content is loved
		
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery(($type) ? "COMMENT_LIKE" : "POST_LIKE"), $this->db->real_escape_string($post), $this->db->real_escape_string($by_user) ));

	}	
	
	function isLiked($page,$by_user) {	                            // Return page if page is liked
		
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery("PAGE_LIKE"), $this->db->real_escape_string($page), $this->db->real_escape_string($by_user) ));
	
	}

	function isMember($user_id,$content_id,$group) {                // Return true if user is a member of chat/group
		// GROUP 1 : Group member
		// GROUP 0 : Chat member
	    
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery(($group) ? "ISGROUP_MEMBER" : "ISCHAT_MEMBER"), $this->db->real_escape_string($user_id), $this->db->real_escape_string($content_id) ));
	
	}
	
	function getPageRole($user_id,$page_id,$pid = NULL) {           // Return page role(Page member)
	    // PID TRUE : Use `pid` to search(Much faster)
	    // PID NULL : Use `user_id` to search
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(($pid) ? "PAGE_ROLE_PID" : "PAGE_ROLE_IDU"), $this->db->real_escape_string(($pid) ? $pid:$user_id), $this->db->real_escape_string($page_id) ));

	}
	
	function getPageUser($user_id,$page_id,$pid = NULL) {           // Return page user(Page follower)	    
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(($pid) ? "PAGE_USER_PID" : "PAGE_USER_IDU"), $this->db->real_escape_string(($pid) ? $pid:$user_id), $this->db->real_escape_string($page_id) ));

	}
	
	function getGroupUser($user_id,$group_id,$gid = NULL) {         // Return group user
	    // GID TRUE : Use `gid` to search(Much faster)
	    // GID NULL : Use `group_id` to search
	    
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(($gid) ? "GROUP_USER_GID" : "GROUP_USER_IDU"), $this->db->real_escape_string(($gid) ? $gid:$user_id), $this->db->real_escape_string($group_id) ));
		
	}
	
	function getPage($page_access,$type=null) {                     // Return page
	    // TYPE 1 : Use `page_username` to search
	    // TYPE 0 : Use `page_id` to search (faster)

		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(($type) ? "PAGE_BY_NAME" : "PAGE_BY_ID"), $this->db->real_escape_string($page_access) ));

	}
	
	function getGroup($group_access,$type=null) {                   // Return group
	    // TYPE 1 : Use `group_username` to search
	    // TYPE 0 : Use `group_id` to search (faster)

		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(($type) ? "GROUP_BY_NAME" : "GROUP_BY_ID"), $this->db->real_escape_string($group_access) ));

	}

	function getArticle($id) {                                       // Fetch blog post
		global $TEXT;

		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("ARTICLE_BY_ID"), $this->db->real_escape_string($id) ));
	
	}
	
	function getUserByID($id) {                                     // Fetch user using IDU
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("USR_BY_IDU"), $this->db->real_escape_string($id) ));

	}
	
	function getUsernameById($id) {                                 // Fetch user's username using IDU
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("USR_NAME_BY_IDU"), $this->db->real_escape_string($id) ));
		
	}
	
	function getUserByUsername($u) {                                // Fetch user using username
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("USR_BY_UNAME"), $this->db->real_escape_string(strtolower($u)) ));
	
	}
	
	function getUserByEmail($e) {                                   // Fetch user using email
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("USR_BY_EMAIL"), $this->db->real_escape_string(strtolower($e)) ));

	}
	
	function getPostByID($post_id) {                                // Fetch post using post id
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("POST_BY_ID"), $this->db->real_escape_string($post_id) ));

	}

	function getCommentByID($comment_id) {                          // Fetch comment using comment id
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("COMMENT_BY_ID"), $this->db->real_escape_string($comment_id) ));
		
	}
	
	function getCommentLike($id,$idu) {                             // Fetch comment like
		
		return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("COMMENT_LIKE"), $this->db->real_escape_string($id), $this->db->real_escape_string($idu) ));

	}
	
	function getChatFormByID($id,$uid) {                            // Fetch chat form
		
		// If user is in form
		if(database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery("ISCHAT_MEMBER"), $this->db->real_escape_string($uid), $this->db->real_escape_string($id) ))) {
			
			// Select form and it's owner
			return database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery("FORM_N_USR_BY_FID"), $this->db->real_escape_string($id) ));

		} else {
			return 0;
		}	
	}
	
	function numberComments($post_id) {                             // Number comments on post id
	
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_POST_COMMENTS"), $this->db->real_escape_string($post_id) ));	
	
	}
	
	function numberPosts($user_id) {                                // Number posts by user id
        
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_USER_POSTS"), $this->db->real_escape_string($user_id) ));	
		
	}
	
	function numberNewPosts($user_id,$time) {                       // Number new posts

		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("NEW_USER_POSTS"), $this->db->real_escape_string($user_id), $this->db->real_escape_string($time) ));	

	}
	
	function numberPhotos($user_id) {                              // Number photos of user id
		
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_USER_PHOTOS"), $this->db->real_escape_string($user_id) ));	
		
	}
	
	function numberFollowers($user_id) {                           // Number followers of user id
		
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_FOLLOWERS"), $this->db->real_escape_string($user_id) ));	

	}
	
	function numberFollowings($user_id) {                          // Number followings of user id
		
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_FOLLOWINGS"), $this->db->real_escape_string($user_id) ));

	}
	
	function listFollowings($user_id) {                            // Return user followings(array)
		
		return database('SINGLE', 'QUERY', 'LIST', sprintf(contextQuery("USER_FOLLOWINGS"), $this->db->real_escape_string($user_id) ), 'user2');
	
	}

    function listLikers($page_id) {                                // Return user followers(array)
		
		return database('SINGLE', 'QUERY', 'LIST', sprintf(contextQuery("PAGE_LIKERS"), $this->db->real_escape_string($page_id) ), 'by_id');

	}
	
	function listFollowers($user_id) {                             // Return user followers(array)
		
		return database('SINGLE', 'QUERY', 'LIST', sprintf(contextQuery("USER_FOLLOWERS"), $this->db->real_escape_string($user_id) ), 'user1');
		
	}	

 	function listMembers($content_id,$type=0) {                    // Return members(array) of chat or group
		
		return database('SINGLE', 'QUERY', 'LIST', sprintf(contextQuery(($type > 1) ? "GROUP_MEMBERS":"CHAT_MEMBERS"), $this->db->real_escape_string($content_id) ), ($type > 1) ? 'user_id' : 'uid');
	
	}
	
	function checkMessages($user_id,$time) {                       // Return unread messenger messages
	    
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_NEW_MSGS"), $this->db->real_escape_string($user_id), $this->db->real_escape_string($user_id) ));

	}
	
	function checkNotiications($user_id) {                         // Return unread notifications
		
		return database('SINGLE', 'QUERY', 'COUNT', sprintf(contextQuery("CN_NEW_NOTIS"), $this->db->real_escape_string($user_id) ));

	}   

	function getUser() {                                            // Fetch logged user
		
		// Select user
		$fetched = database('SINGLE', 'QUERY', 'FETCH', sprintf(contextQuery(filter_var($this->db->real_escape_string($this->username), FILTER_VALIDATE_EMAIL) ? "USR_BY_MAIL_PASS":"USR_BY_NAME_PASS"), $this->db->real_escape_string(strtolower($this->username)), $this->db->real_escape_string($this->password) ));
		
		// If user exists
		if(is_array($fetched) && isset($fetched['idu']) && $fetched['idu']) {
			
			// Update user last activity
			database('SINGLE', 'QUERY', NULL, sprintf(contextQuery("UPDATE_USR_ACT"), time(), $this->db->real_escape_string($fetched['idu']) ));
			
		    return $fetched;
			
		} else {
			
			// Else unset credentials
			return $this->logOut();
		}	
	}
	
	function getRecentLogins($ids) {                                // Get user recent logins
	    global $TEXT;
		
		// Create array from recent login cookie
		$logins = explode(',',$ids);
		
		// Reset
		$TEXT['temp-recent_logins'] = '';
		
		// Load templates
		$rl_template = display(templateSrc('/home/recent_logins/recent_login'),0,1);
		$unread_template = display(templateSrc('/home/recent_logins/unread_notifications'),0,1);
		
		// Add recent logins
		foreach($logins as $login) {
			
			// Get login user info
			$user = $this->getUserByID(preg_replace("/[^0-9]/", "",$login));
			
			// If user exists
			if($user['idu']) {
				
				// Check unread notifiations
				$TEXT['temp-unread'] = $this->checkNotiications($user['idu']);

				// Add notifications if detected
				$TEXT['temp-add_read'] = ($TEXT['temp-unread']) ? display('',$unread_template,0) : '';				
				
				// Set data for template
				$TEXT['temp-user_id'] = $user['idu'];				
				$TEXT['temp-user_username'] = $user['username'];				
				$TEXT['temp-user_image'] = $user['image'];				
				$TEXT['temp-user_name_35'] = fixName(35,$user['username'],$user['first_name'],$user['last_name']);
		
				// Add login to list
				$TEXT['temp-recent_logins'] .= display('',$rl_template,0);
			}			
		}
		
		// Return logins
		return display(templateSrc('/home/recent_logins/recent_logins_container'));
		
	}
	
	function isValue($amount,$tag) {                                // Return iff it's not zero
		return ($amount) ? readAble($amount).' '.$tag : '';
	}

	function isUsernameExists($name,$type=null) {                   // Return true if username exists
		// TYPE 1 : Group username
		// TYPE 2 : Page username
		// TYPE * : Profile username

		// Return true if UserName exists
		return database('SINGLE', 'QUERY', 'NUMROWS_CHK', sprintf(contextQuery(($type == 1 || $type == 2) ? (($type == 1) ? "GROUP_UNAME" : "PAGE_UNAME" ) : ("U_IDU_UNAME")), $this->db->real_escape_string(strtolower($name)) ));
		
	}
	
	function isPostAvailable($post,$poster,$user_id,$group=null) {  // Return true if post available
        
		// If posted in a page
		if($post['posted_as'] == 1) {
			
			return 1;
			
		// If posted in a group	
		} elseif($post['posted_as'] == 2) {
			
			// Get group
			$group = (isset($grp['group_name'])) ? $grp : $this->getGroup($post['posted_at']);
			
			// Return true if group is public
			if($group['group_privacy'] == 1) return 1;
			
			// Else get group user
			$group_user = $this->getGroupUser($user_id,$group['group_id']);
			
			// Return true if user has joined the group
			return ($group_user['group_status'] == 1) ? 1 :0;
			
		} else {
		
			// Check user followings
			$available = (in_array($post['post_by_id'] , (is_array($this->followings) ? $this->followings : array($this->followings))) || $poster['idu'] == $user_id ) ? 1 : 0 ;
		
			// ALLOW : If user exists in followings or owner
			if($available && !empty($post['post_by_id'])) return 1;
		
			// BLOCK : If target had added privacy set to the posts
			return ($poster['p_posts']) ? 0 : 1;
        }
	}
	
	function getCategories($type=null) {                            // Return page categories
	    // TYPE 1 : Only `cid` Col
	    // TYPE NULL : Entire row
		
		$typed = ($type) ? '`cid`' : '*';
		
		// Select categories
		$cats = $this->db->query(sprintf("SELECT %s FROM `categories` ORDER BY `cat_name` ",$typed));

		// Reset
		$cids = $institutes = $brands = $artists = $entertainment = $communities = array();

		// Fetch categories
		while($row = $cats->fetch_assoc()) {

		    if($type) {
				$cids[] = $row['cid'];
			} else {
				
				// Calasify
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
			}
		}
		
		// Return categories
		return ($type) ? $cids : array($institutes, $brands, $artists, $entertainment, $communities);
	
	}
	
	function parseAdd($add,$fixed = FALSE) {                       // Parse adds(sponsor)
		global $TEXT; 
		
		// Set add
		$TEXT['temp-ad1'] = $add;
		
		// Parse fixed ads
		if($fixed && !empty($add)) {
			
			return display(templateSrc('/ads/fixed_ad'));
		
		// Parse Pop up ads
	    } elseif(!empty($add)) {
			
			$TEXT['temp-id'] = mt_rand(100, 9999);
			
			return display(templateSrc('/ads/popup_ad'));
			
		}
		
	}	
	
	function getPermissions($c_id,$u_id,$data,$grouped=NULL,$g_req=NULL) { // Return privacy protection
		
		// Check user privacy
		$private = ($data && is_null($grouped) || (!is_null($grouped) && in_array($data,array("2","3")))) ? 1 : 0 ;

		// If target user is logged user
		if($u_id == $c_id) {
			
			return array($available = 1,$following = 3,$private);
		
		// Else check permissions
		} else {

		    // Check following status
		    if(is_null($grouped)) {
			    $following = (in_array($u_id,$this->followings)) ? 1 : 0 ;
			} else {
			    $following = ($grouped) ? ($grouped == 2) ? 3 : 1 : 0;
			}
			
			// If following
            if($following) return array($available = 1,$following,$private);
			
		    // Else check is requested
		    if(is_null($grouped)) {
			    $requested = (!$following && $this->isRequested($c_id,$u_id)) ? 2 : NULL;
			} else {
			    $requested = ($g_req == 2) ? 2 : NULL;
			}
			
			// Cheeck further group request in public mode
			$available = (!is_null($requested) && $private) ? 0 : 2;
			
			// If requested
            if(!is_null($requested)) return array($available,$requested,$private);
			
			// Else allow if target user is not private
			return ($private) ? array($available = 0,$following = 0,$private) : array($available = 1,$following = 0,$private);

		}
	}	

    function genNavigation($user,$type=null) {                     // Generate user navigation
		global $TEXT;
		
		// Set content for theme
        $TEXT['temp-user_id'] = protectXSS($user['idu']);
        $TEXT['temp-image'] = protectXSS($user['image']);
        $TEXT['temp-cover'] = protectXSS($user['cover']);
        $TEXT['temp-last_name'] = protectXSS($user['last_name']);	
	    $TEXT['temp-username'] = protectXSS($user['username']);
        $TEXT['temp-first_name'] = protectXSS($user['first_name']);
        $TEXT['temp-Search_placeholder'] = sprintf($TEXT['_uni-Search_people_on'],$TEXT['web_name']);			
        $TEXT['temp-Name_navigation_14'] = protectXSS(fixName(14,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        $TEXT['temp-Name_navigation_30'] = protectXSS(fixName(30,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        $TEXT['temp-Name_navigation_title'] = protectXSS(sprintf($TEXT['_uni-Profile_load_text'],$TEXT['temp-Name_navigation_14']));			
		
		// Generate navigation from template 
		return ($type) ? display('themes/'.$TEXT['theme'].'/html/navigations/main_user_header'.$TEXT['templates_extension']) : display('themes/'.$TEXT['theme'].'/html/navigations/main_user'.$TEXT['templates_extension']);

	}	
	
	function genAccordData($a,$row,$small = NULL) {                // Generate accordion data of user (PRIVACY PROTECTED)
		global $TEXT;
		
		// Reset 
		$prof = $home = $liv = '';
		
		// Profession details check
		if((!empty($row['profession']) && $row['p_profession'] == 0) || (!empty($row['profession']) && $a == 1)) {
			$prof = '<span title="'.$TEXT['_uni-Profession'].'">'.protectXSS($row['profession']).'</span>';
		}
		
		// Hometown details check
		if((!empty($row['from']) && $row['p_hometown'] == 0) || (!empty($row['from']) && $a == 1)) {
			$home = '<span title="'.$TEXT['_uni-Hometown'].'">'.protectXSS($row['from']).'</span>';
		}
		
		
		// Current location details check
		if((!empty($row['living']) && $row['p_location'] == 0) || (!empty($row['living']) && $a == 1)) {
			$liv = '<span title="'.$TEXT['_uni-Living_in'].'">'.protectXSS($row['living']).'</span>';
		}	
			
		// Return data
        return array($prof,$liv,$home);

	}
	
    function generatePageAbout($user,$page_row,$following,$role) { // Return About section of pages
		global $TEXT;
		
		// Get list of all categories
		$all_cats = $this->getCategories(1);
		
		// Parse page category
		if($page_row['page_cat'] == 1) {
			$TEXT['temp-add_cat_in'] = $page_row['page_sub_cat'];
		} else {	
			if(in_array($page_row['page_sub_cat'],$all_cats)) {			
				$cat_index = getCategoryTitle($page_row['page_sub_cat'],$this->db);
				
				// Add Name
		        $TEXT['temp-add_cat_in'] = $TEXT[$cat_index['cat_name']];	
			}
		}

	    // Load template src
		$t_src = templateSrc('SRC',1);
		
		// Set data for page likes and follows
		$TEXT['temp-page_id'] = $page_row['page_id'];
		$TEXT['temp-page_likes'] = number_format($page_row['page_likes']);
		$TEXT['temp-page_followers'] = number_format($page_row['page_follows']);
		
		// Build page likes and follows
		$likes_follows = display($t_src.'/page/about/page_likes_follows'.$TEXT['templates_extension']);

		$TEXT['temp-add_desc'] = $TEXT['temp-add_web'] = $TEXT['temp-add_mail'] = $TEXT['temp-add_loc'] = '';
		
		// Set data for page about
		if(!empty($page_row['page_web'])) {
			$TEXT['temp-page_web'] = preg_replace_callback('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', "linkHref", $page_row['page_web']);
		    $TEXT['temp-add_web'] = display($t_src.'/page/about/page_web'.$TEXT['templates_extension']);
		}
		if(!empty($page_row['page_email'])) {
			$TEXT['temp-page_email'] = $page_row['page_email'];
		    $TEXT['temp-add_mail'] = display($t_src.'/page/about/page_email'.$TEXT['templates_extension']);
		}
		if(!empty($page_row['page_location'])) {
			$TEXT['temp-page_location'] = $page_row['page_location'];
		    $TEXT['temp-add_loc'] = display($t_src.'/page/about/page_location'.$TEXT['templates_extension']);
		}		
		if(!empty($page_row['page_description'])) {
			$TEXT['temp-page_description'] = protectXSS($page_row['page_description']);
			$TEXT['temp-add_desc'] = display($t_src.'/page/about/page_description'.$TEXT['templates_extension']);
		}
		
		// Build page about
		$about = display($t_src.'/page/about/page_combine_web_email_des'.$TEXT['templates_extension']);
	
	    // Set data for invite system
		$TEXT['temp-add_suggestions'] = $this->searchInvites($user,'',$page_row);
		$TEXT['temp-add_suggestions'] = ($TEXT['temp-add_suggestions']) ? display($t_src.'/page/about/page_invite_suggestions'.$TEXT['templates_extension']) : '';

		// Build invite system
		$invite_system = display($t_src.'/page/about/page_invite_system'.$TEXT['templates_extension']);
		
		return $invite_system.$likes_follows.$about;
		
	}
	
	function generateGroupAbout($user,$group_row,$following,$add_mem) {    // Return About section of group
		global $TEXT;
		
	    // Load template src
		$t_src = templateSrc('SRC',1);
		
		// Set main content for templates
		$TEXT['temp-group_id'] = $group_row['group_id'];
		$TEXT['temp-group_name'] = $group_row['group_name'];
		
		// Reset
		$TEXT['temp-add_group_location'] = $TEXT['temp-add_group_email'] = $TEXT['temp-add_members'] = $TEXT['temp-add_group_website'] = $TEXT['temp-group_website'] = '';
	
	    // Add members wizard if available
		if($add_mem) {
		
		    // Set suggestions
			$TEXT['temp-suggestions'] = $this->searchMembers($user,'',$group_row['group_id'],2,'SUGGESTIONS');
	
            // Build group suggestions
			$TEXT['temp-add_suggestions_system'] = ($TEXT['temp-suggestions']) ? display($t_src.'/group/about/group_suggestions'.$TEXT['templates_extension']) : '';
		
			// Combine Member and suggestion system
			$TEXT['temp-add_members'] = display($t_src.'/group/about/group_members'.$TEXT['templates_extension']);
		
		}
		
		// Set group description and heading
		$TEXT['temp-group_description'] = (empty($group_row['group_description'])) ? $TEXT['_uni-No_description'] : $group_row['group_description'] ;
		$TEXT['temp-group_heading'] = $this->getGroupHeading($group_row['group_privacy']);
			
        // Add group location
		if(($following == 1 || $following == 3 || $group_row['group_privacy'] == 1) && !empty($group_row['group_location'])) {
		    $TEXT['temp-group_location'] = $group_row['group_location'];
			$TEXT['temp-add_group_location'] = display($t_src.'/group/about/group_location'.$TEXT['templates_extension']);    
		}
		
        // Add group email
		if(($following == 1 || $following == 3 || $group_row['group_privacy'] == 1) && !empty($group_row['group_email'])) {
		    $TEXT['temp-group_email'] = $group_row['group_email'];
			$TEXT['temp-add_group_email'] = display($t_src.'/group/about/group_email'.$TEXT['templates_extension']);
		}
		
        // Add group website
		if(($following == 1 || $following == 3 || $group_row['group_privacy'] == 1) && !empty($group_row['group_web'])) {
			$TEXT['temp-group_website'] .= preg_replace_callback('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', "linkHref", $group_row['group_web']);  
		    $TEXT['temp-add_group_website'] = display($t_src.'/group/about/group_website'.$TEXT['templates_extension']);
		}
		
        // Build and display complete about
		return display($t_src.'/group/about/group_combine_about'.$TEXT['templates_extension']);
	}
	
	function generateAbout($profile_row,$following) {              // Return About section of profile page (PRIVACY PROTECTED)
		global $TEXT;
		
        // Load template src
		$t_src = templateSrc('SRC',1);
		
		// Reset
		$TEXT['temp-add_user_profession'] = $TEXT['temp-add_user_education'] = $TEXT['temp-add_user_relation'] = '';
		$TEXT['temp-add_user_interests'] = $TEXT['temp-add_user_gender'] = $TEXT['temp-add_user_birthday'] = '';
		$TEXT['temp-add_user_hometown'] = $TEXT['temp-add_user_city'] = $TEXT['temp-add_user_website'] = '';
		
		$TEXT['temp-user_id'] = $profile_row['idu'];
		$TEXT['temp-user_bio'] = ($profile_row['bio']) ? $profile_row['bio'] : $TEXT['_uni-No_description'];
		
		// Add profession
		if(($following == 1 || $profile_row['p_profession'] == 0) && !empty($profile_row['profession'])) {
		    $TEXT['temp-user_profession'] = protectXSS($profile_row['profession']);
		    $TEXT['temp-add_user_profession'] = display($t_src.'/user/intro/user_profession'.$TEXT['templates_extension']);
		}
		
		// Add study
		if(($following == 1 || $profile_row['p_study'] == 0) && !empty($profile_row['study'])) {
			$TEXT['temp-user_education'] = protectXSS($profile_row['study']);
		    $TEXT['temp-add_user_education'] = display($t_src.'/user/intro/user_education'.$TEXT['templates_extension']);
		}
		
		// Add relationship
		if(($following == 1 || $profile_row['p_relationship'] == 0) && !empty($profile_row['relationship'])) {
		    $TEXT['temp-user_relation'] = ($profile_row['relationship'] == '2') ? $TEXT['_uni-In_a_rel']: $TEXT['_uni-Single'];
            $TEXT['temp-add_user_relation'] = display($t_src.'/user/intro/user_relation'.$TEXT['templates_extension']);
		}
		
		// Add interests
		if(($following == 1 || $profile_row['p_interest'] == 0) && !empty($profile_row['interest'])) {
		    $TEXT['temp-user_interests'] = ($profile_row['interest'] == '2') ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
			$TEXT['temp-add_user_interests'] = display($t_src.'/user/intro/user_interests'.$TEXT['templates_extension']);
		}
		
		// Add gender
		if(($following == 1 || $profile_row['p_gender'] == 0) && !empty($profile_row['gender'])) {
		    $TEXT['temp-user_gender'] = ($profile_row['gender'] > 1) ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
			$TEXT['temp-add_user_gender'] = display($t_src.'/user/intro/user_gender'.$TEXT['templates_extension']);
		}
		
		// Add birthday
		if(($following == 1 || $profile_row['p_bday'] == 0) && !empty($profile_row['b_day'])) {
		    $TEXT['temp-user_birthday'] = getBirthday($profile_row['b_day']);
			$TEXT['temp-add_user_birthday'] = display($t_src.'/user/intro/user_birthday'.$TEXT['templates_extension']);
		}
		
		// Add hometown 
		if(($following == 1 || $profile_row['p_hometown'] == 0) && !empty($profile_row['from'])) {
		    $TEXT['temp-user_hometown'] = protectXSS($profile_row['from']);
			$TEXT['temp-add_user_hometown'] = display($t_src.'/user/intro/user_hometown'.$TEXT['templates_extension']);
		}
		
		// Add living city 
		if(($following == 1 || $profile_row['p_location'] == 0) && !empty($profile_row['living'])) {
		    $TEXT['temp-user_city'] = protectXSS($profile_row['living']);
			$TEXT['temp-add_user_city'] = display($t_src.'/user/intro/user_city'.$TEXT['templates_extension']);
		}
		
		// Add website
		if(($following == 1 || $profile_row['p_web'] == 0) && !empty($profile_row['website'])) {
		    $TEXT['temp-user_website'] = preg_replace_callback('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', "linkHref", $profile_row['website']);
			$TEXT['temp-add_user_website'] = display($t_src.'/user/intro/user_website'.$TEXT['templates_extension']);    
		}
		
		// Build complete intro
		return display($t_src.'/user/intro/user_intro_combine'.$TEXT['templates_extension']);
	}	
	
	function getFullAbout($user_id,$current_user) {                // Return user about section
		global $TEXT ;
		
        // Load template src
		$t_src = templateSrc('SRC',1);
		
		// fetch target user
		$view = $this->getUserByID($user_id);
		
		// Return if target user doesn't exists
		if(empty($view['idu'])) {
			return showError($TEXT['lang_error_script1']);
		} else {
		
		    $TEXT['temp-more_content'] = $TEXT['temp-add_profession'] = $TEXT['temp-add_education'] = '';			
			$TEXT['temp-add_relation'] = $TEXT['temp-add_interests'] = $TEXT['temp-add_living'] = '';
			$TEXT['temp-add_hometown'] = $TEXT['temp-add_gender'] = $TEXT['temp-add_website'] = '';
			
			// Check following status
			$following = (in_array($view['idu'],$this->followings) || $current_user['idu'] == $view['idu']) ? 1: 0;

			// Add profession
			if(($following == 1 || $view['p_profession'] == 0) && !empty($view['profession'])) {
				$TEXT['temp-profession'] = protectXSS($view['profession']);
				$TEXT['temp-add_profession'] = display($t_src.'/user/about/profession'.$TEXT['templates_extension']);
			}
		
			// Add study
			if(($following == 1 || $view['p_study'] == 0) && !empty($view['study'])) {
				$TEXT['temp-education'] = protectXSS($view['study']);
				$TEXT['temp-add_education'] = display($t_src.'/user/about/education'.$TEXT['templates_extension']);
			}
		
			// Add relationship
			if(($following == 1 || $view['p_relationship'] == 0) && !empty($view['relationship'])) {
				$TEXT['temp-relation'] = ($view['relationship'] == '2') ? $TEXT['_uni-In_a_rel']: $TEXT['_uni-Single'];
				$TEXT['temp-add_relation'] = display($t_src.'/user/about/relationship'.$TEXT['templates_extension']);
			}
		
			// Add interests
			if(($following == 1 || $view['p_interest'] == 0) && !empty($view['interest'])) {
                $TEXT['temp-interests'] = ($view['interest'] == '1') ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
				$TEXT['temp-add_interests'] = display($t_src.'/user/about/interests'.$TEXT['templates_extension']);
			}
		
			// Add gender
			if(($following == 1 || $view['p_gender'] == 0) && !empty($view['gender'])) {
                $TEXT['temp-gender'] = ($view['gender'] > 1) ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
				$TEXT['temp-add_gender'] = display($t_src.'/user/about/gender'.$TEXT['templates_extension']);
			}
		
			// Add hometown 
			if(($following == 1 || $view['p_hometown'] == 0) && !empty($view['from'])) {
                $TEXT['temp-hometown'] = protectXSS($view['from']);
				$TEXT['temp-add_hometown'] = display($t_src.'/user/about/hometown'.$TEXT['templates_extension']);							   										   
			}
		
			// Add living city 
			if(($following == 1 || $view['p_location'] == 0) && !empty($view['living'])) {
                $TEXT['temp-living'] = protectXSS($view['living']);
				$TEXT['temp-add_living'] = display($t_src.'/user/about/living'.$TEXT['templates_extension']);							   											   
			}
		
			// Add Website
			if(($following == 1 || $view['p_web'] == 0) && !empty($view['website'])) {
                $TEXT['temp-website'] = preg_replace_callback('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', "linkHref", $view['website']);
				$TEXT['temp-add_website'] = display($t_src.'/user/about/website'.$TEXT['templates_extension']);		   
			}
			
			// Add bio
			$TEXT['temp-add_bio'] = (empty($view['bio'])) ? $TEXT['_uni-No_description'] : $view['bio'];

			// Set content for theme
            $TEXT['temp-user_id'] = protectXSS($view['idu']);
        	$TEXT['temp-name'] = fixName(25,$view['username'],$view['first_name'],$view['last_name']);	
	   		$TEXT['temp-username'] = protectXSS('@'.$view['username']);
        	
			/* Will be re-added soon
			$TEXT['temp-posts'] = protectXSS($view['posts']);
        	$TEXT['temp-photos'] = protectXSS($view['photos']);
        	$TEXT['temp-followers'] = protectXSS($view['followers']);
        	$TEXT['temp-followings'] = $this->numberFollowings($view['idu']);*/

		    // Generate about page from template
		    return display($t_src.'/user/about/combined_page'.$TEXT['templates_extension']);
		}
	}
	
	function verifiedBatch($x,$type = 0) {                         // Return verified batch if profile is verified
		global $TEXT;
		
		// Return verified image if profile is verified
		return ($x) ? '<img class="'.(($type) ? '' : 'responsive-medium').'" title="'.$TEXT['_uni-Profile_verified'].'" alt="Image" src="/themes/'.$TEXT['theme'].'/img/icons/others/verified.svg" '.(($type) ? 'width="14px"': 'width="18px"').'></img>' : '';	
	
	}

	function getRelationButton($type,$id,$p = NULL) {              // Generate user relation button
	    global $TEXT;
		
		// Unique HTML element id 
		$co = md5(mt_rand(100,99999).time());
		
		if($type == 0 && $p == 0) {    // Follow button
			return '<button id="fo32'.$co .'" class="btn btn-medium btn-theme" onclick="follow('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Follow'].'\',\''.$TEXT['_uni-Following'].'\');">'.$TEXT['_uni-Follow'].'</button>';
		} elseif($type == 0 && $p == 1) {   // Request button 
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="request('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Request'].'\',\''.$TEXT['_uni-Requested'].'\');">'.$TEXT['_uni-Request'].'</button>';
		} elseif($type == 1) {         // Following button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-theme btn-active" onclick="unfollow('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Following'].'\',\''.$TEXT['_uni-Follow'].'\');">'.$TEXT['_uni-Following'].'</button>';
		} elseif($type == 2) {         // Undo request button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="unrequest('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Requested'].'\',\''.$TEXT['_uni-Request'].'\');">'.$TEXT['_uni-Requested'].'</button>';
		} else {                       // Edit profile button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="getSettings(0);">'.$TEXT['uni_Edit_profile'].'</button>';
		}	
    }	

	function getPageButtons($co,$following,$liked,$role,$id) {     // Generate page buttons
	    global $TEXT;
		
		// Like/Unlike button
		if($liked) {
			$btns = '<button id="fo32'.$co .'" class="btn btn-medium btn-small-inline btn-theme btn-active" onclick="likePage('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Liked'].'\',\''.$TEXT['_uni-Like'].'\',0,1);">'.$TEXT['_uni-Liked'].'</button>';
		} else {
			$btns = '<button id="fo32'.$co .'" class="btn btn-medium btn-small-inline btn-theme" onclick="likePage('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Like'].'\',\''.$TEXT['_uni-Liked'].'\',1,0);">'.$TEXT['_uni-Like'].'</button>';
		}
		
		// Follow/Unfollow button
		if($following) {
			$btns .= '<button id="fo32'.$co .'" title="'.$TEXT['_uni-Hide_from_feeds_ttl'].'" class="btn btn-small-inline btn-medium btn-theme" onclick="bodyLoader(\'content-body\');ajaxProtocol(users_file,'.$id.',0,7,0,0,0,0,0,0,0,0,0,0,2,0,0,98);">'.$TEXT['_uni-Following'].'</button>';
		} else {
			$btns .= '<button id="fo32'.$co .'" title="'.$TEXT['_uni-Show_in_feeds_ttl'].'" class="btn btn-small-inline btn-medium btn-light" onclick="bodyLoader(\'content-body\');ajaxProtocol(users_file,'.$id.',0,6,0,0,0,0,0,0,0,0,0,0,2,0,0,98);">'.$TEXT['_uni-Follow'].'</button>';
		}
		
		return $btns;
		
    }

	function getGroupButton($type,$id,$p = NULL,$float=NULL) {     // Generate group relation button
	    global $TEXT;
		
		// Unique HTML element id 
		$co = md5(mt_rand(100,99999).time());
	
		if($type == 0 && $p == 0) {         // Join button
			return '<button id="fo32'.$co .'" class="btn btn-medium btn-theme" onclick="joinGroup('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Join'].'\',\''.$TEXT['_uni-Joined'].'\');">'.$TEXT['_uni-Join'].'</button>';
		} elseif($type == 0 && $p == 1) {   // Request button 
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="requestGroup('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Request'].'\',\''.$TEXT['_uni-Requested'].'\');">'.$TEXT['_uni-Request'].'</button>';
		} elseif($type == 1 || $type == 3) { // Un Join button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-theme btn-active" onclick="unjoinGroup('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Joined'].'\',\''.$TEXT['_uni-Join'].'\');">'.$TEXT['_uni-Joined'].'</button>';
		} else {                            // Undo request button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="unrequestGroup('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Requested'].'\',\''.$TEXT['_uni-Request'].'\');">'.$TEXT['_uni-Requested'].'</button>';
		}

    }
	
	function getGroupHeading($group_type) {                        // Generate Group heading (e.g Secret Group)
	    global $TEXT;	
		return '<span title="'.$TEXT['_uni-Group_type_'.$group_type.'_ttl'].'">'.$TEXT['_uni-Group_type_'.$group_type].'</span>';
	}
		
	function getPostHeading($post_type,$posted_as,$posted_at,$post_extraas,$post_g_nsme,$p_con=NULL,$gender,$id=null) {                          // Generate Post heading (e.g Updated his status)
	    global $TEXT,$feeling_title,$feeling_available;
		
		// Disable feelings and extras fo groups
		if($posted_as == 2) {
			$heading_title = '';
			$group_title = ' <i class="fa fa-play margin-0-5 h3 b1 tin-light-font-only"></i> <a class="h6 b3 theme-x-font" href="/group/'.$posted_at.'" onclick="loadGroup('.$posted_at.',1,1);return false;" >'.$post_g_nsme.'</a>';
		} else {
			
			$group_title = $heading_title = '';
	
	        // Create array from post extras
			$post_extras = explode(',', $post_extraas);	
		    
			// If feeling set
			if($post_extras[0] || !empty($post_extras[2])) {
				
				// Add poist extras pesets
				require_once(__DIR__ .'/presets/post_extras.php');
			
			    // if extra activity available, add it
				if(isset($feeling_available[$post_extras[0]]) || !empty($post_extras[2])) {
				
					$add_at = $added_feeling = '';
			
					if(!empty($post_extras[2])) {
						$add_at = (isset($feeling_available[$post_extras[0]]) && $feeling_available[$post_extras[0]] == 'traveling') ? '<span> '.$TEXT['_uni-from'].' </span><span class="dark-font-only b2">'.$post_extras[2].'</span>' : '<span> '.$TEXT['_uni-at'].' </span><span class="b2 dark-font-only">'.$post_extras[2].'</span>';
					}
			
					if($post_extras[0]) {
						$added_feeling = '<span>'.$TEXT['_uni-is'].' '.$TEXT['_uni-'.$feeling_title[$post_extras[0]]].' </span><img class="nav-item-inverse margin-0" src="/thumb.php?theme='.$TEXT['theme'].'&fol=i&src='.$feeling_available[$post_extras[0]].'.png&w=16&h=16"><span class="b2 dark-font-only"> '.$post_extras[1].'</span>';
					}
			
					$heading_title = $added_feeling.$add_at;
				}
			} else {
				
				$href = ' <a class="h6 b2 theme-x-font" href="/view/'.$id.'" onclick="loadPost('.$id.');return false;">';
				
				if($post_type == 1) {	
				  
				    $images = explode(',', $p_con);
			
			        $heading_title =(count($images) == 1) ? $TEXT['_uni-added_a_new'].$href.$TEXT['_uni-photo'].'</a>'  : sprintf($TEXT['_uni-added_sprint'],count($images)).$href.$TEXT['_uni-photos'].'</a>';
							
				} elseif($post_type == 2) {   // Added a new video
					$heading_title = $TEXT['_uni-added_a_new'].$href.$TEXT['_uni-video'].'</a>'; 
				} elseif($post_type == 3) {   // Updated is profile picture
					$heading_title = ($gender == '2') ? $TEXT['_uni-updated_her'].$href.$TEXT['_uni-profile_photo'].'</a>' : $TEXT['_uni-updated_his'].$href.$TEXT['_uni-profile_photo'].'</a>'; 
				} elseif($post_type == 4) {   // Shared a YouTube video
					$heading_title = $TEXT['_uni-shared_a_new'].$href.$TEXT['_uni-video'].'</a>';
				} elseif($post_type == 7) {   // Shared a soudcloud track
					$heading_title = $TEXT['_uni-shared_a_new'].$href.$TEXT['_uni-track'].'</a>';
				} elseif($post_type == 8) {   // Shared a Dailymotion video
					$heading_title = $TEXT['_uni-shared_a_new'].$href.$TEXT['_uni-video'].'</a>';
				} elseif($post_type == 9) {   // Shared a Vimeo video
					$heading_title = $TEXT['_uni-shared_a_new'].$href.$TEXT['_uni-video'].'</a>';
				} elseif($post_type == 10) {  // Shared a created article(Blog post)
					$heading_title = $TEXT['_uni-wrote_a'].$href.$TEXT['_uni-blog_post'].'</a>';
                } elseif($post_type == 12) {  // Added a track
					$heading_title = $TEXT['_uni-added_a_new'].$href.$TEXT['_uni-track'].'</a>';
				} elseif($post_type == 13) {   // Shared a Facebook video
					$heading_title = $TEXT['_uni-shared_a_new'].$href.$TEXT['_uni-video'].'</a>';
				} elseif($post_type == 14) {   // Shared a ViLoud Video
					$heading_title = $TEXT['_uni-shared_a_new'].$href.$TEXT['_uni-live_video'].'</a>';
				} elseif($post_type == 5) {   // Updated his cover photo
					$heading_title = ($gender == '2') ?  $TEXT['_uni-updated_her'].$href.$TEXT['_uni-profile_cover'].'</a>' : $TEXT['_uni-updated_his'].$href.$TEXT['_uni-profile_cover'].'</a>';   
				} else {                      // Updated status
					$heading_title = ($gender == '2') ? $TEXT['_uni-updated_her'].$href.$TEXT['_uni-status'].'</a>' :  $TEXT['_uni-updated_his'].$href.$TEXT['_uni-status'].'</a>';   
				}
				
			}		
		}
		return array($heading_title,$group_title);
	}	
	
	function getPostContent($post,$id = NULL,$pic = 1,$si_it = NULL,$ret_text) {  // Return Post content
		global $TEXT;
		
		$type = $post['post_type'];
		
		// Enable on click event for feeds page
		$onclick = (!is_null($id)) ? 'onclick="loadPost('.$id.')"' : '';
		
		// Enable pointers
		$pointer = (!is_null($id)) ? 'pointer' : '';
		
		// Enable titles
		$title = (!is_null($id)) ? $TEXT['_uni-ttl_load_post'] : '';

		$rand_id = mt_rand(0,9999);
		
		// Check image privacy
		if(is_null($pic)) {
			$post['post_content'] = 'private.png';
		}
		
		// Add XSS protection 
		$protected = protectXSS($post['post_content']);

		// Get right content
		if($type == 1) {            // Added a new photo

		    $images = explode(',', $protected);
			
			if(count($images) == 1) {
				
	            $return = '<div id="POST_IMAGE_'.$id.'" class="clear center post-image">
		    				<img id="post_view_main_image_1_'.$id.'" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[0].'&fol=c&w=650&h=600" style="margin-top:5px;max-width:100%;max-height:600px;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';
				
				/* Add preloader one if number of posts requested  (Optional)
		    	if(is_null($si_it)) {
		        	$return = '<div id="POST_IMAGE_'.$id.'" class="brz-img-pre-loader_cc brz-padding brz-center brz-clear">
		    				<img id="post_view_main_image_1_'.$id.'" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' style="margin-top:5px;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
	        				<script>loadImage(\'/thumb.php?src='.$images[0].'&fol=c&w=650&h=300\',\'#post_view_main_image_1_'.$id.'\')</script>
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';				
				}*/
				
			} elseif(count($images) == 2) {
				$return = '<div id="POST_IMAGE_'.$id.'" class="clear center post-image">
		    				<img id="post_view_main_image_1_'.$id.'" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[0].'&fol=c&w=300&h=300" style="margin-top:5px;width: 48.73999%;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_2_'.$id.'" onclick="$(\'#post_gallery_image_2_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[1].'&fol=c&w=300&h=300" style="margin-top:5px;width: 48.73999%;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';
						
			} elseif(count($images) == 3) {
				$return = '<div id="POST_IMAGE_'.$id.'" class="clear center post-image">
		    				<img id="post_view_main_image_1_'.$id.'" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[0].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;width:32.2%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_2_'.$id.'" onclick="$(\'#post_gallery_image_2_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[1].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;max-height:450px;width:32.2%;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_3_'.$id.'" onclick="$(\'#post_gallery_image_3_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;max-height:450px;width:32.2%;" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';
						
			} elseif(count($images) == 4) {
				$return = '<div id="POST_IMAGE_'.$id.'" class="clear center post-image">
		    				<img id="post_view_main_image_1_'.$id.'" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_2_'.$id.'" onclick="$(\'#post_gallery_image_2_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_3_'.$id.'" onclick="$(\'#post_gallery_image_3_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[2].'&fol=c&w=300&h=200" style="margin-top:2px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_4_'.$id.'" onclick="$(\'#post_gallery_image_4_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[3].'&fol=c&w=300&h=200" style="margin-top:2px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';
						
			} elseif(count($images) == 5) {
				$return = '<div id="POST_IMAGE_'.$id.'" class="clear center post-image">
		    				<img id="post_view_main_image_1_'.$id.'" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_2_'.$id.'" onclick="$(\'#post_gallery_image_2_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_3_'.$id.'" onclick="$(\'#post_gallery_image_3_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_4_'.$id.'" onclick="$(\'#post_gallery_image_4_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[3].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_5_'.$id.'" onclick="$(\'#post_gallery_image_5_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[4].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';
						
			} elseif(count($images) > 5) {
				$return = '<div id="POST_IMAGE_'.$id.'" class="clear center post-image">
		    				<img id="post_view_main_image_1_'.$id.'"  onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_2_'.$id.'"  onclick="$(\'#post_gallery_image_2_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_3_'.$id.'"  onclick="$(\'#post_gallery_image_3_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
		    				<img id="post_view_main_image_4_'.$id.'" onclick="$(\'#post_gallery_image_4_'.$id.'\').click();" '.$title.' src="/thumb.php?src='.$images[3].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity '.$pointer.' brz-align-center">
							<div onclick="$(\'#post_gallery_image_5_'.$id.'\').click();" class="relative clear" style="display:inline-block!important;width: 32.23999%;margin-top:2px;max-width:100%;max-height:450px;">
  								<img src="/thumb.php?src='.$images[4].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;" class="pointer greyscale">
  								<div style="text-shadow: 1px 1px 1px rgba(0, 0, 0, .4);" class="text-shadow h15 b3 white-font-only display-middle">+'.(count($images) - 4).'</div>
							</div>
						</div>
						<div id="GALLERY_LOAD_'.$id.'">';		
			}

			$i = 1;
			$a = 0;

			foreach($images as $image) {
				$return .= '<input id="post_gallery_image_'.$i.'_'.$id.'" class="hide" onclick="loadGallery('.$id.',\'/uploads/posts/photos/'.$image.'\','.$i.');">';
				$i++;
				$a++;
			}
			$return = $return.'</div>';
			
		} elseif($type == 2) {      // Added a new video
			$return = '<input id="video-sembed-'.$rand_id.'" data-id="'.$id.'" data-icon="play" data-src="UPLOADS" data-error="'.$TEXT['_uni-Error_video'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embesd"></input>
						<div id="video-embeded-'.$rand_id.'" class="block-container '.$id.' clear2 noflow video-embeded full rounded">
							<div id="video-img-'.$rand_id.'" class="relative '.$id.' noflow vid-img left">
								<a onclick="playVideo(\'/uploads/posts/videos/'.$protected.'\',\'/thumb.php?src='.$post['link_img'].'&fol=vid&w=650&h=650\','.$id.',1);return false;" href="" class="display-middle shadow img-40 border-bold circle filter-opacity-little theme-x-color">
									<img class="img-18 display-middle play-btn" src="/themes/'.$TEXT['theme'].'/img/icons/buttons/play.svg">
								</a>
								<img src="/thumb.php?src='.$post['link_img'].'&fol=vid&w=350&h=350" class="vid-img">
							</div>
							<div class="padding-10 noflow">
								<div onclick="playVideo(\'/uploads/posts/videos/'.$protected.'\',\'/thumb.php?src='.$post['link_img'].'&fol=vid&w=650&h=650\','.$id.',1);return false;"id="video-title-8757" class="h11 '.$id.' pointer b0 dark-font">
									<span class="">'.$post['link_title'].'</span>
								</div>
								<div class="h4 text-left b1">
									<span class="tin-light-font '.$id.'">'.sprintf($TEXT['_uni-Views_count'],readable($post['post_views'])).'</span>
									<div class="tin-light-font padding-6-0">'.$post['link_description'].'</div>
								</div>
								<div></div>
							</div>
						</div>';
		
		
		} elseif($type == 3) {      // Updated profile picture	
			$return = '<div class="center post-image" id="POST_IMAGE_'.$id.'" >
			                <img src="/thumb.php?src='.$protected.'&fol=a&w=650&h=600" alt="...." onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" title="'.$title.'" style="max-width:100%;max-height:600px;" class="'.$pointer.'">
			            </div>
					    <div id="GALLERY_LOAD_'.$id.'">
					        <input id="post_gallery_image_1_'.$id.'" class="hide" onclick="loadGallery('.$id.',\'/uploads/profile/main/'.$protected.'\',1);">
					    </div>';	
						
		} elseif($type == 4) {      // Shared a youtube video
			$return = '<input id="video-embed-'.$rand_id.'" data-id="'.$id.'" data-icon="play" data-src="YOUTUBE.COM" data-error="'.$TEXT['_uni-Error_video'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embed"></input>';
		} elseif($type == 7) {      // Shared a Soundcloud track
			$return = '<input id="video-embed-'.$rand_id.'" data-id="'.$id.'" data-icon="listen" data-src="SOUNDCLOUD.COM" data-error="'.$TEXT['_uni-Error_track'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embed"></input>';
		} elseif($type == 8) {      // Shared a Dailymotion video
			$return = '<input id="video-embed-'.$rand_id.'" data-id="'.$id.'" data-icon="play" data-src="DAILYMOTION.COM" data-error="'.$TEXT['_uni-Error_track'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embed"></input>';
		} elseif($type == 9) {      // Shared a Vimeo video
			$return = '<input id="video-embed-'.$rand_id.'" data-id="'.$id.'" data-icon="play" data-src="VIMEO.COM" data-error="'.$TEXT['_uni-Error_track'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embed"></input>';
		} elseif($type == 12) {      // Added a new track
			$return = '<div class="block-container background-x noflow">
							<div class="left right-divider vid-img relative noflow hide-small">
							    <a data-track-name="'.$post['ms_title'].'" data-track-artist="'.$post['ms_artist'].'" data-track-cover="/thumb.php?src='.$post['ms_img'].'&fol=ms&w=200&h=200" data-track-id="'.$post['post_id'].'" id="track-play-'.$post['post_id'].'" data-track-url="/uploads/music/'.$post['post_content'].'" data-track-format="'.strtolower(pathinfo($post['post_content'], PATHINFO_EXTENSION)).'" class="play-track stopped display-middle shadow img-40 border-bold circle filter-opacity-little theme-x-color">
								    <img class="img-18 display-middle track-play-btn play-btn" src="/themes/'.$TEXT['theme'].'/img/icons/buttons/play.svg" >
								</a>
								<img alt="" src="/thumb.php?src='.$post['ms_img'].'&fol=ms&w=200&h=200" class="right-divider vid-img">
							</div>
							<div style="max-height:130px;" class="noflow nicescrollit padding-10 h9 b0 white-font-only">
								<a href="#" onclick="loadTrack('.$post['post_id'].');return false;" class="dark-font">'.$post['ms_title'].'</a>
								<div class="tin-light-font-only h5 b1"><span class="dark-font-only">'.$post['ms_artist'].'</span> - '.$post['post_text'].'</div>
							</div>
				        </div>';
		
		} elseif($type == 13) {     // Shared a facebook video
			$return = '<input id="video-embed-'.$rand_id.'" data-id="'.$id.'" data-icon="play" data-src="FACEBOOK.COM" data-error="'.$TEXT['_uni-Error_video'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embed"></input>';
		}  elseif($type == 14) {     // Shared a ViLOUD Live video
			$return = '<input id="video-embed-'.$rand_id.'" data-id="'.$id.'" data-icon="play" data-src="VILOUD.TV" data-error="'.$TEXT['_uni-Error_video'].'" data-rand="'.$rand_id.'" data-link="'.$protected.'" class="hide '.$id.' video-embed"></input>';
		} elseif($type == 10) {     // Shared a created article(Blog post)
			
			$article = $this->getArticle($protected);
			
			if($article) {
				$return = '<div onclick="locate(\''.$this->db->real_escape_string('/blogs/'.$article['blog_id']).'\');" class="block-container background-x noflow">
				            	<div class="left right-divider vid-img vid-img-full noflow hide-small"><img alt="" src="/thumb.php?fol=blog&w=800&h=500&src='.$article['blog_image'].'" class="right-divider vid-img vid-img-full"></div>
								<div style="max-height:130px;" class="noflow nicescrollit padding-10 h9 b0 white-font-only">
								<a href="/blogs/'.$article['blog_id'].'" class="dark-font">'.$article['blog_heading'].'</a>
									<div class="tin-light-font-only h5 b1">'.$article['blog_description'].'</div>
								</div>
				        	</div>';
			} else {
				$TEXT['temp-message'] = $TEXT['_uni-doesnt-exists-blog'];
				$return = display(templateSrc('/admin/elements/message'));
			}
			
			$ret_text = '';
		} elseif($type == 5) {      // Updated his cover photo		
			$url = ($si_it) ? '/uploads/profile/covers/'.$protected : '/thumb.php?src='.$protected.'&fol=b&w=650&h=300';
			$return = '<div class="center post-image clear" id="POST_IMAGE_'.$id.'" >
						    <img src="/thumb.php?src='.$protected.'&fol=b&w=650&h=350" onclick="$(\'#post_gallery_image_1_'.$id.'\').click();" alt="...." title="'.$title.'" style="max-width:100%;max-height:350px;" class="'.$pointer.'">
					    </div>
					    <div id="GALLERY_LOAD_'.$id.'">
					        <input id="post_gallery_image_1_'.$id.'" class="hide" onclick="loadGallery('.$id.',\''.'/uploads/profile/covers/'.$protected.'\',1);">
					    </div>';
		} else {
			$return = '';
		}
		
		// For background posts
		if(!$type && !empty($post['post_content'])) {
			
			$return = ' <div class="full post-image relative">
							<img class="full" src="/thumb.php?src='.$post['post_content'].'.jpg&fol=bb&w=600&h=350" >
							<div class="display-mob-left padding-10 big-imgs white-font-only full h8 b3 center">'.$ret_text.'</div>
						</div> ';
					
			$ret_text = '';
		} elseif($post['link']) {
			
			if($post['link_title']) {
				
				$image = ($post['link_img']) ? '<div class="left right-divider vid-img vid-img-full noflow hide-small"><img alt="" src="'.$post['link_img'].'" class="right-divider vid-img vid-img-full"></div>' : '';

			    $return = '<div class="block-container background-x noflow">
				                '.$image.'
				            	<div style="max-height:130px;" class="noflow nicescrollit padding-10 h9 b0 white-font-only">
									<a href="'.$post['link'].'" class="dark-font">'.$post['link_title'].'</a>
									<div class="tin-light-font-only h5 b1">'.$post['link_description'].'</div>
								</div>
				            </div>';
			}

		}
		
		// Return content
		return array($return,$ret_text);
		
	}
	
	function getImage($current_id,$id,$privacy,$photo) {	                     // Generate profile avatars (PRIVACY PROTECTED)
		
		// Privacy check
		if($this->admin || $id == $current_id || (!is_array($this->followings) && $id == $this->followings) || (is_array($this->followings) && in_array($id,$this->followings)) || substr($photo, 0, 7) == "default") return $photo ;
		
		// Else confirm privacy check and return image
		return ($privacy == 1) ? 'private.png' : $photo;
		
	}
	
	function listFunctions($data,$user_logged,$single=NULL,$full=1) {		     // Generate post details       
		global $TEXT,$page_settings;	

		$viewable = ($single) ? '' : '<a onclick="loadPost('.$data['post_id'].');return false;" class="padding-10 tin-light-font drop-item" href="/view/'.$data['post_id'].'">'.$TEXT['_uni-View_post'].'</a>';
		
		// If post had photo or video linked add download button
		if($full) {
		    
			$drop_options = '';

		    // If target user is logged user add Edit and delete button		
			if($user_logged['idu'] == $data['post_by_id']) {
		    	$drop_options .= '<a title="'.$TEXT['_uni-ttl_edit_post'].'" onclick="editPost('.$data['post_id'].');" class="padding-10 tin-light-font drop-item" href="javascript:void(0);">'.$TEXT['_uni-Edit_post'].'</a>
                          <a title="'.$TEXT['_uni-ttl_del_post'].'" onclick="deleteContent('.$data['post_id'].',1)" class="padding-10 tin-light-font drop-item" href="javascript:void(0);">'.$TEXT['_uni-Delete_post'].'</a>';
			} else {	
				// Else report button
		    	$drop_options .= '<a title="'.$TEXT['_uni-ttl_rep_post'].'" onclick="report('.$data['post_id'].',2)"class="padding-10 tin-light-font drop-item" href="javascript:void(0);">'.$TEXT['_uni-Report'].'</a>';
				
			}
			
			// Add dropdown
			$drop_options = '<a href="/view/'.$data['post_id'].'" target="_blank" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-opn_ne_tb'].'</a>
					            '.$viewable.'
						        '.$drop_options.'
					        <a onclick="copyToClipboard(\'/view/'.$data['post_id'].''.'\',\''.$TEXT['_uni-Post_URL_copied'].'\');" class="padding-10 tin-light-font drop-item" href="javascript:returnFalse();">'.$TEXT['_uni-Copy_URL_post'].'</a>									';
			
		} else {
		    $drop_options = '<a href="/view/'.$data['post_id'].'" target="_blank" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-opn_ne_tb'].'</a>';
		}
	
		// Get like
		$liked = $this->getLoved($data['post_id'],$user_logged['idu']);
		$liked_n = ($liked['id']) ? 1 : 0;
		
		// Add self liked title
		$only_self_liked = ($data['post_loves'] == 1 && $liked_n) ? 1 : 0;
		
		// Make liked choice
		if(!$only_self_liked && $liked_n) {
		    $like_txt = ($data['post_loves'] > 1) ? $TEXT['_uni-You_and'] : '';
		} elseif($only_self_liked) {
		    $like_txt = $TEXT['_uni-You_liked_this'];
		} elseif(!$data['post_loves']) {
		    $like_txt = $TEXT['_uni-Be_fir_like'];
		} else {
		    $like_txt = '';
		}
		
		$TEXT['temp-post_likers'] = '';

		$result = $this->db->query(sprintf("SELECT * FROM `post_loves`, `users` WHERE `post_loves`.`post_id` = '%s' AND `post_loves`.`by_id` = `users`.`idu` ORDER BY post_loves.id DESC LIMIT 4",$this->db->real_escape_string($data['post_id'])));
		
		// If post and user exists
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch data
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			foreach($rows as $row) {
				$TEXT['temp-post_likers'] .= '<span class="pointer" onclick="loadProfile('.$row['idu'].');" ><img src="thumb.php?src='.$this->getImage($user_logged['idu'],$row['idu'],$row['p_image'],$row['image']).'&fol=a&w=50&h=50" class="post-liker"></span>';
			}
			
		}
		
		// Add on like and dislike event 
		$event = ($single) ? 1 : 0;
		
		$reacts = getReactions($page_settings['reactions']);
		$minus = (!$only_self_liked && $data['post_loves'] == 1) ? 0 : 1;
		
		// Set buttons	
		$like_txt = ($data['post_loves'] > 1 || ($data['post_loves'] && !$liked_n)) ? '<span class="like-count">'.$like_txt.'</span> <span class="like-others-count">'.sprintf($TEXT['_uni-others_and_sprint'],$data['post_loves'] - $minus).'</span>' : '<span class="like-count">'.$like_txt.'</span>';
		$like_txt .= '<span class="hide" value="'.$data['post_loves'].'"></span>';
		$like_txt .= ($data['post_comments'] == 0 ) ? '' : ', <span id="comment-count" onclick="loadComments('.$data['post_id'].');" class="dark-font-only pointer b2">'.readAble($data['post_comments']).'</span> '.$TEXT['_uni-comments'];

		if($liked_n) {
			$icon =  $reacts[$liked['type']][2];
			$svg_ad = 'active-img';
			$onclick = array('onclick="noLove(\'#post_view_like_'.$data['post_id'].'\','.$data['post_id'].','.$event.','.$liked['type'].');"','btn-liked');
		} else {
			$icon =  str_replace('.svg','-nc.svg',$reacts[0][2]);
			$svg_ad = 'active-img ssvg';
			$onclick = array('onclick="doLove(\'#post_view_like_'.$data['post_id'].'\','.$data['post_id'].','.$event.',0);"','btn-like');
		}
		
		if(count($reacts) > 1) {

			$like_btn = '<div class="dropup noselect">
			                <div id="post_view_like_'.$data['post_id'].'" '.$onclick[0].' class="dark-font dropbtn dropbtn-up like-btn pointer b2 '.$onclick[1].'"><img class="img-22 '.$svg_ad.' active-img" src="/themes/'.$TEXT['theme'].'/'.$icon.'"> </div>
                            <div class="dropup-content padding-5 clear rounded white-color">
                                '.generateReacts($reacts,'\'#post_view_like_'.$data['post_id'].'\'',$data['post_id'],$event).' 
                             </div>
							
                        </div> ';

		} else {
			$like_btn = '<div id="post_view_like_'.$data['post_id'].'" '.$onclick[0].' class="dark-font like-btn pointer b2 '.$onclick[1].'"><img class="img-22 '.$svg_ad.'" src="/themes/'.$TEXT['theme'].'/'.$icon.'"></div>';		
		}

		
		$comment_btn = '<div id="post_view_comment_'.$data['post_id'].'" onclick="loadComments('.$data['post_id'].');" class="dark-font like-btn pointer b2"><img class="img-22 svg" src="/themes/'.$TEXT['theme'].'/img/icons/buttons/comment.svg"></div>';		

		$comment_as = array($user_logged['image'].'&fol=a','loadProfile('.$user_logged['idu'].');');
		
		// If posted on page
		if($data['posted_as'] == 1) {
			
			// Get page
			$get_page = $this->getPage($data['posted_at']);
			
			// If owner allow hime comment as page
			if($get_page['page_owner'] == $user_logged['idu']) {
			   
			   $comment_as = array($get_page['page_icon'].'&fol=pa','loadPage('.$get_page['page_id'].',1,1);');
			
			// Else check further
			} else {
				
				// Get user role on page
				$get_page_role = $this->getPageRole($user_logged['idu'],$data['posted_at']);
				
				// If is above analyst
				if(isset($get_page_role['page_role']) && $get_page_role['page_role'] > 2) {
					$comment_as = array($get_page['page_icon'].'&fol=pa','loadPage('.$get_page['page_id'].',1,1);');
				}
				
			}
	
		}
		
		// Return post content			
		return array($like_btn,$comment_btn,$like_txt,$drop_options,$comment_as[1],$comment_as[0]);		
	}
	
	function getCommentFunctions($comment,$user,$posted_as=0) {                               // Generate comment functions	
		global $TEXT;
		
		// Fetch post
		$post = $this->getPostByID($comment['post_id']);
		
		$buttons = '';
		
		$get_like = $this->getCommentLike($comment['id'],$user['idu']);
		
		if($get_like && $get_like['id']) {
			$buttons = '<div id="comment-view-'.$comment['id'].'" class="margin-0-10-0-0 noselect tin-light-font pointer" onclick="likeComment('.$comment['id'].',0);aOverlow();">'.$TEXT['_uni-Liked'].'</div>';
		} else {
			$buttons = '<div id="comment-view-'.$comment['id'].'" class="margin-0-10-0-0 noselect tin-light-font pointer" onclick="likeComment('.$comment['id'].',1);aOverlow();">'.$TEXT['_uni-Like'].'</div>';
		}
		
		// Add delete button as poster is user
		if($post['post_by_id'] == $user['idu'] && $comment['by_id'] !== $user['idu']) {
			$buttons .= '<div class="margin-0-10-0-0 noselect tin-light-font pointer" onclick="$(\'#comments-container-'.$comment['post_id'].'\').fadeOut();deleteContent('.$comment['id'].',2);aOverlow();">'.$TEXT['_uni-Delete'].'</div>';
		}
		
		// Add delete button as user is the owner of comment
		if($comment['by_id'] == $user['idu']) {
			$buttons .= '<div class="margin-0-10-0-0 noselect tin-light-font pointer" onclick="$(\'#comments-container-'.$comment['post_id'].'\').fadeOut();deleteContent('.$comment['id'].',2);aOverlow();">'.$TEXT['_uni-Delete'].'</div>';
	   
	    // Else add report button
		} else {
			$buttons .= '<div class="margin-0-10-0-0 noselect tin-light-font pointer" onclick="$(\'#comments-container-'.$comment['post_id'].'\').fadeOut();report('.$comment['id'].',3);aOverlow()">'.$TEXT['_uni-Report'].'</div>';
		}
		
		// Add reply button
		if ($comment['by_id'] !== $user['idu'] && !$posted_as) {
			$buttons .= '<div class="margin-0-10-0-0 noselect tin-light-font pointer" onclick="reply(\''.$this->db->real_escape_string($comment['username']).'!, \',\'#form-add-comment-text-'.$comment['post_id'].'\');">'.$TEXT['_uni-Reply'].'</div>';
		}
		
		return $buttons;
	}
	
	function expressSuggestions($userId,$limit) {                                // Generate suggestions express bar
	    global $TEXT,$page_settings;
	
		// Get list of users followed by current user
		if(!empty($this->followings) && is_array($this->followings)) {		    
			$allowed = 'NOT IN('.$this->db->real_escape_string(implode(',', $this->followings).','.$this->db->real_escape_string($userId)).')';	
		} else {
		    $allowed = '!= '.$this->db->real_escape_string($userId);
		}
		
		// Set limit
		$limit = $this->db->real_escape_string($limit);
		
		// Add autoplay if enabled
		$autoplay = ($page_settings['feature_expressautoplay']) ? 'true' : 'false';
		
		// Get related users
		$select = $this->db->query("SELECT `idu`, `image`, `username`, `first_name`, `last_name`, `p_image` FROM `users` WHERE `users`.`idu` $allowed AND `users`.`p_image` != '1' AND `users`.`image` != 'default.png' ORDER BY RAND() LIMIT $limit");
		
		// fetch_assoc
		if($select->num_rows) {	
			while($row = $select->fetch_assoc()) {	
				$rows[] = $row;  
			}
				
			if(!empty($rows)) {
					
				$suggestions = '<div style="height:100px;width:100%"  class="bottom-divider padding-10">
	                                <div class="tin-light-font-only h4 b2">
                                        '.$TEXT['_uni-Suggestions'].' 
		                            </div>
									<section class="sugesstions-slick slider">';
					
				// Insert data
				foreach($rows as $row) {		    
					$suggestions .= '<div >
			                            <img class="pointer img-35 circle" onclick="loadProfile('.$row['idu'].');" title="'.sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name'])).'" src="/thumb.php?src='.$row['image'].'&fol=a&w=70&h=70">
			                        </div>';
				}	
				    
				$suggestions .= '</section></div>
                                <script>
                                    $(".sugesstions-slick").slick({
        		                        infinite: true,
        		                        slidesToShow: 4,  
				                        autoplay: '.$autoplay.',
        		                        slidesToScroll: 3
      		                        })
                                </script>';
				
			    // Generate suggestions
		        return $suggestions;					
			}
		}	
	}	
	
	function expressFriends($userId,$limit) {                                    // Generate express bar content
	    global $TEXT;

		$limit = $this->db->real_escape_string($limit);

		// Get list
		$select = $this->db->query(sprintf("SELECT * FROM users WHERE users.idu IN(%s) ORDER BY users.active DESC LIMIT $limit",$this->db->real_escape_string((is_array($this->followings)) ? implode(',',$this->followings) : $this->followings)));
	
		// fetch_assoc
		if(!empty($select) && $select->num_rows) {	
			while($row = $select->fetch_assoc()) {	
				$rows[] = $row;  
			}
				
			if(!empty($rows)) {
			
				$users = '';
			
				// Insert data
				foreach($rows as $row) {
			
					// get user activity
				    $active = (time() - $row['active'] < 30 || fuzzyStamp($row['active']) == $TEXT['_uni-Online'] ) ? '<span><i class="fa fa-circle green-font"></i></span>' : '<span>'.fuzzyStamp($row['active'],NULL,'_b').'</span>';
		
					$users .= '<div class="bottom-divider noflow clear full h4 tin-light-font-only b2 padding-10">
		                                <div class="left">
			                                <img class="pointer circle img-35" src="/thumb.php?src='.$row['image'].'&fol=a&w=70&h=70">
			                            </div>
			                            <div class="">
			                                <div class="noflow">
				                                <div class="margin-0-5 padding-6-0 tin-light-font-only">
					                                <span class="right">'.$active.'</span>
					                                <a onclick="loadProfile('.$row['idu'].');return false;" href="/'.$row['idu'].'" class="b2 h5 dark-font">'.fixName(20,$row['username'],$row['first_name'],$row['last_name']).'</a>
				                                </div>
				                            </div>
			                            </div>
		                            </div>';
					
				}	
				    
				
			    // Generate suggestions
		        return $users;					
			}
		}	
	}

	function expressChats($user, $limit) {                                    // Generate express bar content
	    global $TEXT;

		$limit = $this->db->real_escape_string($limit);
		$add_filter = '';

		// Select chats
		$select = $this->db->query(sprintf("SELECT * FROM chat_forms, chat_users WHERE chat_users.uid = %s AND chat_users.form_id = chat_forms.form_id $add_filter ORDER BY chat_forms.form_active DESC LIMIT %s", $this->db->real_escape_string($user['idu']), $limit));

		// fetch_assoc
		if(!empty($select) && $select->num_rows) {	
			while($row = $select->fetch_assoc()) {	
				$rows[] = $row;  
			}
				
			if(!empty($rows)) {
			
				$chats = '';
			
				// Insert data
				foreach($rows as $row) {
			
					// get user activity
					$active = (intval(time()) - intval(strtotime($row['form_active'])) < -19770 ) ? '<span><i class="fa fa-circle green-font"></i></span>' :'';
					
					// RESET
					$actions = $TEXT['temp-new_messages'] = '';
					
					if($row['form_type'] == 1 ) {
						
						// Fetch chat form owner
						$chat_owner = $this->getUserByID(($row['form_by'] == $user['idu']) ? $row['form_to']  : $row['form_by']);
						
						// Add available photo
						$TEXT['temp-image'] = ($row['form_icon'] == 'default.png') ? '/thumb.php?src='.$this->getImage($user['idu'],$chat_owner['idu'],$chat_owner['p_image'],$chat_owner['image']).'&fol=a&w=50&h=50' : '/thumb.php?src='.$row['form_icon'].'&fol=d&w=50&h=50';
						
					} else {
						
						// Set chat owner as current user
						$chat_owner = $user ;
						
						// SRC group photo
						$TEXT['temp-image'] = '/thumb.php?src='.$row['form_icon'].'&fol=d&w=50&h=50';
						
					}

					// Chat name 
					$TEXT['temp-name'] = ($row['form_name'] == $TEXT['_uni-Name_this_chat'] && $row['form_type'] == 1) ? fixName(30,$chat_owner['username'],$chat_owner['first_name'],$chat_owner['last_name']) : fixText(30,$row['form_name']);

					$chats .= '<div onclick="loadSideChat('.$row['form_id'].');return false;" class="bottom-divider noflow clear full h4 tin-light-font-only b2 padding-10">
		                                <div class="left">
			                                <img class="pointer circle img-35" src="'.$TEXT['temp-image'].'">
			                            </div>
			                            <div class="">
			                                <div class="noflow">
				                                <div class="margin-0-5 padding-6-0 tin-light-font-only">
					                                <span class="right">'.$active.'</span>
					                                <a onclick="loadSideChat('.$row['form_id'].');return false;" class="b2 h5 dark-font">'.$TEXT['temp-name'].'</a>
				                                </div>
				                            </div>
			                            </div>
		                            </div>';
					
				}	
				    
				
			    // Generate suggestions
		        return $chats;					
			}
		}	
	}
	
	function expressActivity($userId) {                                          // Generate suggestions and trending users
        global $TEXT,$page_settings;
		
		// If there are no friends, return false
		if(empty($this->followings)) {
			return false;
		}
		
		// Get Scope
		$followings = implode(',',$this->followings);
		
		// Reset
		$expressActivity = $row = ''; $likes = $comments = array();$i = $b = $c =0;
		
		// Select all latest likes
		$likes_all = $this->db->query(sprintf("SELECT * FROM  `user_posts`, `post_loves`,`users` WHERE `post_loves`.`by_id` = `users`.`idu` AND `post_loves`.`post_id` = `user_posts`.`post_id` AND `post_loves`.`by_id` IN (%s) AND `users`.`state` != 4 ORDER BY `post_loves`.`id` DESC LIMIT %s", $followings, 12));
		
		// Fetch likes
		if(!empty($likes_all)) {
		    while($row = $likes_all->fetch_assoc()) {
			    $likes[] = $row;
		    }
		}
	
	    // Select all latest comments
		$comments_all = $this->db->query(sprintf("SELECT * FROM  `user_posts`, `post_comments`,`users` WHERE `post_comments`.`by_id` = `users`.`idu` AND `post_comments`.`post_id` = `user_posts`.`post_id` AND `post_comments`.`by_id` IN (%s) AND `users`.`state` != 4 ORDER BY `post_comments`.`post_id` DESC LIMIT %s", $followings, 12));

		// fetch comments
		if(!empty($comments_all)) {
		    while($row = $comments_all->fetch_assoc()) {
			    $comments[] = $row;
		    }
		}
	
		// Return if empty
		if(empty($likes) && empty($comments)) {
			return false;
		}
		
		// Add data type before combining likes and comments
		foreach($likes as $like) {
			$likes[$b]['type'] = '2';
			$b++;
		}

		// Add data type before combining likes and comments
		foreach($comments as $comment) {
			$comments[$c]['type'] = '3';
			$c++;
		}
		
		// Combine comments and likes
		$array = array_merge($likes, $comments);

		// Sort the array
		usort($array, 'reorderArray');		
	
		// Add title
		$title_set = array("0" => "_uni-status_update","1" => "_uni-photo",	"2" => "_uni-Grouped_chats",	"3" => "_uni-profile_photo","4" => "_uni-shared_video","5" => "_uni-profile_cover","7" => "_uni-shared_track","8" => "_uni-shared_video","9" => "_uni-shared_video",);
		
		foreach($array as $row) {
			
			if($i == $page_settings['express_activity_per_limit']) break;
			
			//$value['event'] == 'like';
			
			// Add info
			if($row['type'] == '2') {
			
			    // Add type : photo status etc
			    $t_type = (in_array($row['post_type'],array("1","3","5","4","0"))) ? $TEXT[$title_set[$row['post_type']]] :$TEXT['_uni-post'] ;
				
				// Get parent user
				if($row['idu'] == $row['post_by_id']) {
				    $line = $TEXT['_uni-his'].'</a> \'s';
					$idu = $row['post_by_id'];
				} else {
				    $child_user = $this->getUsernameById($row['post_by_id']);
				    $idu = $child_user['idu'];
				    $line = fixName(20,$child_user['username'],$child_user['first_name'],$child_user['last_name']).'</a> \'s';
				}
				$content = $TEXT['_uni-liked'].' <a class="h5 dark-font pointer" href="/'.$idu.'" onclick="loadProfile('.protectXSS($idu).');return false;">'.$line.' <a class="pointer b2 h5 theme-font" href="/view/'.$row['post_id'].'" onclick="loadPost('.protectXSS($row['post_id']).');return false;">'.$t_type.'</a>';
			} elseif($row['type'] == '3') {
			    
		
			    // Add type : photo status etc
			    $t_type = (in_array($row['post_type'],array("1","3","5","4","0"))) ? $TEXT[$title_set[$row['post_type']]] :$TEXT['_uni-post'] ;
				
				// Get parent user
				if($row['idu'] == $row['post_by_id']) {
				    $line = $TEXT['_uni-his'].'</a> \'s';
					$idu = $row['post_by_id'];
				} else {
				    $child_user = $this->getUsernameById($row['post_by_id']);
				    $idu = $child_user['idu'];
				    $line = fixName(20,$child_user['username'],$child_user['first_name'],$child_user['last_name']).'</a> \'s';
				}
				
				$content = $TEXT['_uni-commented_on'].' <a class="pointer b2 h5 dark-font" href="/'.$idu.'" onclick="loadProfile('.protectXSS($idu).');return false;">'.$line.' <a class="pointer b2 h5 theme-font" href="/view/'.$row['post_id'].'" onclick="loadPost('.protectXSS($row['post_id']).');return false;">'.$t_type.'</a>';
			
			}
			
			// Add activity
			$expressActivity .= '<div class="bottom-divider noflow clear full h4 tin-light-font-only b2 padding-10">
		                            <div class="left">
			                            <img class="circle img-35" src="/thumb.php?src='.$row['image'].'&fol=a&w=70&h=70">
			                        </div>
			                        <div class="">
			                            <div class="noflow">
										    <div class="margin-0-5">
				                            <a href="/'.$row['idu'].'" onclick="loadProfile('.protectXSS($row['idu']).');return false;" class="pointer b2 h5 dark-font">'.fixName(20,$row['username'],$row['first_name'],$row['last_name']).'</a>
					                            '.$content.'
				                            </div>
			                            </div>
		                            </div>
								</div>';
			
			
		}
		
		// Return activities
		return $expressActivity;
	}
	
	function getBoxedUsers($userId) {                                            // Generate suggestions and trending users
	    global $TEXT;
	
		// Reset
	    $TEXT['temp-content'] = $trending = $suggestions = '';
		
	    // Get template src
	    $template = templateSrc('/modals/boxed_users');
		
		// Suggests users if user following is not empty
		if(!empty($this->followings)) {
			
			// Get list of users followed by current user
			$list = $this->db->real_escape_string(implode(',', $this->followings));
		    
			$user_id_esc = $this->db->real_escape_string($userId);
		
		    // Get related users
		    $select = $this->db->query("SELECT `idu`, `image`, `username`, `first_name`, `last_name`, `verified` , `followers`, `p_image` FROM `users` WHERE `idu` IN(SELECT `user2` FROM `friendships` WHERE `user1` IN($list)) AND `p_image` != '1' ORDER BY `idu` DESC LIMIT 5");
		
		    // fetch_assoc
			if($select->num_rows) {	
				
				while($row = $select->fetch_assoc()) {
					if($userId !== $row['idu'] && !in_array($row['idu'],$this->followings)) {
						$rows[] = $row;
					}    
				}
				
				if(!empty($rows)) {
					
					// load template
	                $know_user_template = display(templateSrc('/user/may_know_user'),0,1);
					
					// Insert data
					foreach($rows as $row) {
					
						$TEXT['temp-user_id'] = $row['idu'];
						$TEXT['temp-user_profile_title'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));	
						$TEXT['temp-user_profile_img'] = $this->getImage($userId,$row['idu'],$row['p_image'],$row['image']);						
						$TEXT['temp-user_profile_name'] = fixName(32,$row['username'],$row['first_name'],$row['last_name']);						
						$TEXT['temp-user_verified_batch'] = $this->verifiedBatch($row['verified'],1);						
						$TEXT['temp-user_followed_by'] = ($row['followers']) ? sprintf($TEXT['_uni-followed_by'],readable($row['followers'])) : '@'.protectXSS($row['username']);

						$TEXT['temp-content'] .= display('',$know_user_template);
					
					}	
				
					$TEXT['temp-data'] = $TEXT['_uni-People_you_may_know'];
					
					$TEXT['temp-data-id'] = 'RIGHT_PEOPLE_KNOW';
				
					// Generate box from template 
		        	$suggestions = display($template);					
				}
			} 
		}
		
		// Select top 3 users from last month
	    $top_posts = $this->db->query("SELECT DISTINCT `post_by_id` FROM `user_posts` WHERE date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() ORDER BY `user_posts`.`post_loves` DESC LIMIT 0 , 3");
		
		// Add users
		if(!empty($top_posts) && $top_posts->num_rows !== 0) {
			while($row = $top_posts->fetch_assoc()) {	
				$arra[] = $row['post_by_id'];
			}	
			$top_posts = implode(',', $arra);
		} else { 
			$top_posts = '';
		}
		
		// Select users
		$top_users = $this->db->query("SELECT `idu`, `image`, `first_name`, `last_name`, `username` , `followers` , `verified` , `p_image` , `posts` FROM `users` WHERE `idu` IN($top_posts) AND `image` != ' default.png' AND `p_image` != '1'");

		// If to users exists
		if(!empty($top_users) && $top_users->num_rows) {
			
			// Fetch posts ARRAys
			while($row2 = $top_users->fetch_assoc()) {
			    $rows2[] = $row2;
			}
		
			// Reset box
			$TEXT['temp-content'] = '';
			
			// load template
	        $trend_user_template = display(templateSrc('/user/trending_user'),0,1);
			
			if(!empty($rows2)) {

				// Create list
				foreach($rows2 as $row2) {

					// Get raw people reach
					$people_reach = ($row2['followers']*$row2['posts']) ? protectXSS(number_format($row2['followers']*$row2['posts'])).' '.$TEXT['_uni-people_reach'] : $row2['posts'].' '.$TEXT['_uni-posts'];

					$TEXT['temp-user_id'] = $row2['idu'];					
					$TEXT['temp-user_profile_title'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row2['username'],$row2['first_name'],$row2['last_name']));					
					$TEXT['temp-user_profile_img'] = $this->getImage($userId,$row2['idu'],$row2['p_image'],$row2['image']);					
					$TEXT['temp-user_profile_name'] = fixName(32,$row2['username'],$row2['first_name'],$row2['last_name']);					
					$TEXT['temp-user_verified_batch'] = $this->verifiedBatch($row2['verified'],1);
					$TEXT['temp-user_people_reach'] = (!$people_reach) ? $row2['posts'].' '.$TEXT['_uni-posts'] : $people_reach;

					$TEXT['temp-content'] .= display('',$trend_user_template);
					
				}
	
	            $TEXT['temp-data'] = $TEXT['_uni-Trending-month'];
				
				$TEXT['temp-data-id'] = 'RIGHT_TRENDING';
				
				// Generate box from template 
		        $trending = display($template);
			}				
		}
		
		// Return suggestions and trending users
        return $suggestions.$trending;	
	}	
	
	function getPersonalities($user, $limit) {                                   // Get trending personalities 
	    global $TEXT;
		
		// Reset 
		$hashtags = '';$i = 0;
		
		// Set scope
		$scope = (!empty($this->followings)) ? implode(',',$this->followings).','.$this->db->real_escape_string($user['idu']) : $this->db->real_escape_string($user['idu']);
		
		// Set title
		$TEXT['temp-data'] = $TEXT['_uni-Trending'];
		
		$TEXT['temp-data-id'] = 'RIGHT_PERSONALITIES';
		
		// Select users
		$top_users = $this->db->query(sprintf("SELECT `idu`, `cover`, `first_name`, `last_name`, `username` , `followers` , `profession` FROM `users` WHERE `idu` NOT IN(%s) AND `p_private` != '1' AND `p_cover` != '1' AND `p_profession` != '1' AND `verified` = '1' ORDER BY RAND() LIMIT %s",$scope,$this->db->real_escape_string($limit)));

		// If to users exists
		if($top_users->num_rows) {
		
		    // Fetch posts ARRAys
			while($row = $top_users->fetch_assoc()) {
			    $rows[] = $row;
			}
	
	        // Load template
	        $user_template = display(templateSrc('/user/trending_personality'),0,1);
			
			// Open box
		    $TEXT['temp-content'] = '<div class="block-container"><div id="PERSONALITIES_SHOW_BOX">';

			// Create user box
			foreach($rows as $row) {
			
			    // Show only one at time
			    $TEXT['temp-container_style_tags'] = ($i) ? 'STYLE="display:none;"': '' ;			    
				$TEXT['temp-user_id'] = $row['idu'];				
				$TEXT['temp-user_cover'] = getCoverSrc(str_replace('rep_','',$row['cover']));				
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);				
				$TEXT['temp-user_name_50'] = fixName(50,$row['username'],$row['first_name'],$row['last_name']);			
				$TEXT['temp-co_id'] = mt_rand(0,999999).mt_rand();		
				$TEXT['temp-user_profession'] = ($row['profession']) ? fixText(30,$row['profession']).' - ': '';			
				$TEXT['temp-user_verified_batch'] = $this->verifiedBatch(1,1);	
				$TEXT['temp-user_followers'] = sprintf($TEXT['_uni-peopl_follow_this_sprint'],readable($row['followers']));
				
				// Add user
			    $TEXT['temp-content'] .= display('',$user_template);
				
		        $i = 1;
			}
			
			// Cose container
			$TEXT['temp-content'] .= '</div></div>';
			
			// Add js functions from template
			$TEXT['temp-content'] .= display(templateSrc('/user/trending_personality_js'));
			
		    return display(templateSrc('/modals/boxed_users_full'));
		} else {
	        // Generate box from template 
		    return '';	
		}
	}
	
	function getShortcuts($user_id,$gid,$limit) {                                // Get groups Shortcuts
	    global $TEXT;
		
		// Reset
		$content = '';

		// Select groups
		$groups = $this->db->query(sprintf("SELECT `group_name`, `group_id`, `group_cover` FROM `groups` WHERE `group_id` IN(SELECT `group_id` FROM `group_users` WHERE `user_id` = '%s' AND `group_status` = '1' AND `group_id` != '%s')  ORDER BY RAND() LIMIT %s",$this->db->real_escape_string($user_id),$this->db->real_escape_string($gid),$this->db->real_escape_string($limit)));

		// Selected
		$counts = $groups->num_rows;
	
		// If to groups exists
		if($counts) {
		
		    // Fetch groups ARRAys
			while($row = $groups->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Reset
			$shortcuts = '';
			
			// Load template
	        $gsc_template = display(templateSrc('/shortcuts/group'),0,1);
			
			// Create groups box
			foreach($rows as $row) {
				
				// Set data for shortcut
				$TEXT['temp-group_id'] = $row['group_id'];
				$TEXT['temp-group_cover'] = getCoverSrc(str_replace('rep_','',$row['group_cover']));
				$TEXT['temp-group_name'] = fixText(35,$row['group_name']);
				
				// Add shortcut from template
				$shortcuts .= display('',$gsc_template);
		
			}
			
		    return $shortcuts;
		} else {
			return '';
		}
	}
		
	function getGroups($user_id,$limit) {                                        // Get groups list
	    global $TEXT,$page_settings;
	
		// Reset
		$content = $TEXT['temp-content']  = '';
		
		$TEXT['temp-data'] = $TEXT['_uni-Groups_joined'];
		
		$TEXT['temp-data-id'] = 'GROUPS_YOU_I';	

		// Select groups
		$groups = $this->db->query(sprintf("SELECT `group_name`, `group_id`, `group_users`, `group_cover` FROM `groups` WHERE `group_id` IN(SELECT `group_id` FROM `group_users` WHERE `user_id` = '%s' AND `group_status` = '1')  ORDER BY RAND() LIMIT %s",$this->db->real_escape_string($user_id),$this->db->real_escape_string($page_settings['groups_joined_limit'])));
       
	    $count = $groups->num_rows;
		
		// If to groups exists
		if($count) {
		
		    // Fetch groups ARRAys
			while($row = $groups->fetch_assoc()) {
			    $rows[] = $row;
			}

			// load template
	        $group_template = display(templateSrc('/group/joined'),0,1);
	
			// Create groups box
			foreach($rows as $row) {
			
				$TEXT['temp-group_id'] = $row['group_id'];
				$TEXT['temp-group_ttl'] = sprintf($TEXT['_uni-ttl_group_members_count'],readAble($row['group_users']));
				$TEXT['temp-group_img'] = getCoverSrc(str_replace('rep_','',$row['group_cover']));	
				$TEXT['temp-loop'] = ($count > 3) ? 'true' : 'false';	
				$TEXT['temp-content'] .= display('',$group_template);
			
			}

		    return display(templateSrc('/group/box'));
			
		} else {
			
			$TEXT['temp-widget_class'] = 'groups';
			$TEXT['temp-widget_color'] = 'pink';

			$TEXT['temp-js'] = 'loadModal(1);loadGroup(0,14,32);';
			$TEXT['temp-href'] = '#';
			$TEXT['temp-btn'] = $TEXT['_uni-Create'];
			$TEXT['temp-title'] = $TEXT['_uni-Groups_create_info'] ;
			$TEXT['temp-info'] = $TEXT['_uni-Groups_create_ping'] ;

			// Generate widget from template 
		    return display(templateSrc('/modals/widget'));	
		}
	}
	
	function getHashtags($searched, $limit) {                                    // Get today's active hastags 
		global $TEXT;
		
		// Get template
		$template = (file_exists('themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension'])) ? 'themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension'] : '../../../themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension'];
	    
		// Reset 
		$hashtags = '';$i = 0;
		
		// Set title
		$TEXT['temp-data'] = $TEXT['_uni-Trending_hashtags'];
		
		$TEXT['temp-data-id'] = 'RIGHT_TRENDING_TAGS';
		
		// Select posts wih tags
		$posts = $this->db->query("SELECT * FROM `user_posts`, `users` WHERE `user_posts`.`post_by_id` = `users`.`idu` AND `user_posts`.`posted_as` = '0' AND `users`.`p_posts` != '1' AND `user_posts`.`post_time` > CURRENT_DATE AND `user_posts`.`post_time` < CURRENT_DATE + INTERVAL 7 DAY AND `user_posts`.`post_tags` != ''");
		
		// Store the hashtags into a string
		while($row = $posts->fetch_assoc()) {
			$hashtags .= $row['post_tags'];
		}

		// If there are trends available
		if(!empty($hashtags)) {
			
			// Explode all tags
			$hashtags = explode(',', $hashtags);
			
			// Remove case senstive filter
			$count = array_count_values(array_map('strtolower', array_filter($hashtags)));
			
			// Sort
			arsort($count);
			
			// Open container
			$TEXT['temp-content'] = '';
			
			// Add tags to list
			foreach($count as $row => $value) {
				
				// Break when the trends hits the limit
				if($i == $limit) break; 
				
				// Add tag to list
				$TEXT['temp-content'] .= ($row == ltrim($searched, '#')) ? '<a title="'.$TEXT['_uni-Tags_counts'].'" class="h7 b2 theme-x-font inline padding-0-6" href="/search/tag/'.$row.'" >&nbsp;#'.$row.' <span class="background-x h4 b2 tin-light-font-only">'.readable($value).'</span></a>': '<a title="'.$TEXT['_uni-Tags_counts'].'" class="h6 b2 dark-font padding-0-6" href="/search/tag/'.$row.'" >&nbsp;#'.$row.' <span class="background-x h4 b2 tin-light-font-only">'.readable($value).'</span></a>';
				
				// Add count
				$i++;
			}
				
			// Generate box from template 
		    return display($template);
			
		} else {
			return false;
		}
	}
	
	function activeUsers($user_id,$onfeeds = NULL) {                             // Get active users for mobile and chats
		global $TEXT;
		
		// Reset
	    $TEXT['temp-content'] = $users =  '';
		
		// Hide box on desktop
	    $TEXT['BOX_TEMP_CLASSES'] = 'brz-detach-to-threequarter brz-dettachable hide-large';
		
		$rows = array();
		
		// Fetch more data if not available
		if(is_null($onfeeds)) { 
		    $c_user = $this->getUserByID($user_id);
			$c_user_feeds = $c_user['onfeeds'];
		} else {
			$c_user_feeds = $onfeeds;
		}
		
		// Get template
		$template = (file_exists('themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension'])) ? 'themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension'] : '../../../themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension'];
	
	    // Get list
		$users = $this->db->query(sprintf("SELECT * FROM users WHERE users.idu IN(%s) AND users.active > %s",$this->db->real_escape_string(implode(',',$this->followings)),$this->db->real_escape_string(time()-30)));
		
	    // fetch_assoc
		if($users->num_rows) {	
			
			while($row = $users->fetch_assoc()) {
				$rows[] = $row;
			}
			
			// Load template
			$act_tpl = display(templateSrc('/user/active_user_mobile'),0,1);
			
			// Insert user
			foreach($rows as $row) {
				
				// Set data for template
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-name'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-name_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],$name);
				
				$TEXT['temp-content'] .= display('',$act_tpl);
	
			}
			
			// Add heading to list
			$TEXT['temp-data'] = $TEXT['_uni-Active_users'];
			
			$TEXT['temp-data-id'] = 'RIGHT_ONLINE';

			// Generate box from template 
		    return display($template);
		}
	}

	function followUser($follow,$by) {                                           // Follow user
	    global $TEXT,$page_settings;

		if(Blocker::IsTypeBlocked(Blocker::GetBlock($by['idu'], $follow), 'follow'))
			return false;

		// Fetch target user
		$target = $this->getUserByID($follow);
		
		// If target exists
		if(!empty($target['idu']) && $target['idu'] !== $by['idu']) {

		    // Number target user followers
		    $followers = $this->db->real_escape_string($this->numberFollowers($target['idu']));	
			
			// Whether target user is private
			if($target['p_private'] == 0) {	
				
				// Insert follow and increase target users followers count
				$status = 1;
				$followers = $followers + 1;
				
			} else {	
				
				// Insert request to target user
				$status = 2;
			}
			
			// Escaping variable or MySQL query
			$followed_esc = $this->db->real_escape_string($target['idu']);     // User going to target
			$by_user_esc = $this->db->real_escape_string($by['idu']);            // User going to follow

            // Delete previous relations
			$query = "DELETE FROM `friendships` WHERE `friendships`.`user2` = '$followed_esc' AND `friendships`.`user1` = '$by_user_esc' ;" ;	
		    
			// Insert new relationship
			$query.= "INSERT INTO `friendships`(`id`, `user2`, `user1`, `status`, `time`) VALUES (NULL, '$followed_esc', '$by_user_esc', '$status', CURRENT_TIMESTAMP) ;" ;
		    
			// If target followed and target user has enabled notifications on new followers
			if($status == 1 && $target['n_follower'] == 1) {
				
				// Insert new follower notification to target user
				$query.= "INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$by_user_esc', '$followed_esc', '0','0','1','0', CURRENT_TIMESTAMP) ;" ;
			
			} elseif($status == 2){
				
				// If target user is request Insert a new request notification to target user
				$query.= "INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$by_user_esc', '$followed_esc', '0','0','2','0', CURRENT_TIMESTAMP) ;" ;
			
			}
			
			// Always Updating target user followers count helps repairing previous jerks
		    $query.= "UPDATE `users` SET `followers` = '$followers' WHERE `users`.`idu` = '$followed_esc' ;" ;
		   
		    // Send new follower email if user has enable email notifications
			if($target['e_follower']) {
				mailSender($page_settings, $target['email'], $TEXT['_uni-New_follower_a_ttl_sml'], sprintf($TEXT['_uni-New_follower_a_ttl'], fixName(35,$target['username'],$target['first_name'],$target['last_name']), '/'.$by['username'], fixName(35,$by['username'],$by['first_name'],$by['last_name'])), $TEXT['web_mail']);
			}
		   
            // Perform MySQL multi_query
		    return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}
	}
	
	function unFollowUser($followed,$by) {                                       // UN-follow user
		
		// Fetch target users
		$following = $this->getUserByID($followed);
		
		// If target exists
		if(!empty($following['idu'])) {
			
			// Count target followers
			$followers = $this->db->real_escape_string($this->numberFollowers($following['idu']));	
			
			// If performer has requested the target 
			if($this->isRequested($by['idu'],$following['idu']) == 0) {
				$followers = $followers - 1;
			}
			
			// Escapin variables for MySQL Query
			$followed_esc = $this->db->real_escape_string($following['idu']);
			$by_user_esc = $this->db->real_escape_string($by['idu']);
			
			// Delete relationship
			$query = "DELETE FROM `friendships` WHERE `friendships`.`user2` = '$followed_esc' AND `friendships`.`user1` = '$by_user_esc' ;" ;
		    
			// Delete notifications related to this relationship
			$query.= "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$by_user_esc' AND `notifications`.`not_to` = '$followed_esc' AND `notifications`.`not_type` IN(1,2) ;" ;
			$query.= "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$followed_esc' AND `notifications`.`not_to` = '$by_user_esc' AND `notifications`.`not_type` = '3' ;" ;
			
			// Update target followers
			$query .= "UPDATE `users` SET `followers` = '$followers' WHERE `users`.`idu` = '$followed_esc' ;" ;
			
			// Perform MySQL multi_query
			return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target doesn't exists
			return 0;
		}
	}
	
	function deleteRequest($delete,$by) {                                        // Delete follow request
		
		// Fetch target user
		$deleted = $this->getUserByID($delete);
		
		// If target exists
		if(!empty($deleted['idu'])) {
			
			// Escape variables for MySQL Query
			$deleted_esc = $this->db->real_escape_string($deleted['idu']);
			$by_user_esc = $this->db->real_escape_string($by['idu']);
			
			// Delete request
			$query = "DELETE FROM `friendships` WHERE `friendships`.`user1` = '$deleted_esc' AND `friendships`.`user2` = '$by_user_esc' AND `friendships`.`status` = 2 ;" ;
		    
			// Delete notifications related to this request
			$query.= "DELETE FROM `notifications` WHERE `notifications`.`not_to` = $by_user_esc' AND `notifications`.`not_from` = '$deleted_esc' AND `notifications`.`not_type` = '2'  ;" ;
	
			// Perform MySQL multi_query
			return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}
	}
	
	function allowUser($allowed_id,$by) {                                        // Allow follow request	
		global $TEXT,$page_settings;
		
		// Fetch target user
		$allow = $this->getUserByID($allowed_id);
		
		// If target user exists
		if(!empty($allow['idu'])) {	
		    
			// Count current user followers
			$followers = $this->db->real_escape_string($this->numberFollowers($by['idu'])) + 1;	
			
			//  Escape variables for MySQL Query
			$allowed_esc = $this->db->real_escape_string($allow['idu']);
			$by_user_esc = $this->db->real_escape_string($by['idu']);
			
			// Delete all notifications related 
			$query = "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$allowed_esc' AND `notifications`.`not_to` = '$by_user_esc' AND `not_type` =  '2';" ;
		    
			// Update relationship as accepted
			$query .= "UPDATE `friendships` SET `friendships`.`status` = '1' , `friendships`.`time` = CURRENT_TIMESTAMP WHERE `friendships`.`user1` = '$allowed_esc' AND `friendships`.`user2` = '$by_user_esc' ;" ;	
			
			// Update current user followers count (+1)
			$query .= "UPDATE `users` SET `followers` = '$followers' WHERE `users`.`idu` = '$by_user_esc' ;" ;
			
			// If target user has enabled notifications on request accepts insert it
			if($allow['n_accept'] == 1) { 
				$query.= "INSERT INTO `notifications`(`id`, `not_to`, `not_from`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$allowed_esc', '$by_user_esc', '0','0','3','0', CURRENT_TIMESTAMP) ;" ;
			}
			
			// Send request accepted email if user has enable email notifications
			if($allow['e_accept']) {
				mailSender($page_settings, $allow['email'], $TEXT['_uni-New_request_ttl'], sprintf($TEXT['_uni-request_accep_f'], fixName(35,$allow['username'],$allow['first_name'],$allow['last_name']), '/'.$by['username'], fixName(35,$by['username'],$by['first_name'],$by['last_name'])), $TEXT['web_mail']);
			}
			
			// If current user has enabled notifications on new followers insert it
			if($by['n_follower'] == 1) {
				$query.= "INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$allowed_esc', '$by_user_esc', '0','0','1','0', CURRENT_TIMESTAMP) ;" ;
			}
			
			// Send new follower email if user has enable email notifications
			if($by['e_follower']) {
				mailSender($page_settings, $by['email'], $TEXT['_uni-New_follower_a_ttl_sml'], sprintf($TEXT['_uni-New_follower_a_ttl'], fixName(35,$by['username'],$by['first_name'],$by['last_name']), '/'.$user['username'], fixName(35,$user['username'],$user['first_name'],$user['last_name'])), $TEXT['web_mail']);
			}
			
			// Perform MySQL multi_query
			return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}
	}
	
    function postStatus($user,$content,$message,$list_post_extras,$group=null,$post_type) {  // Post status  
		
		// Save time to make MySQL quires fast
		$time = date('Y/m/d H:i:s');
		
		$user_id = $user['idu'];
		
		// Random pending section id for notifications
		$id = mt_rand(100, 9999);

		// Escape variable for MySQl Query 
		$user_esc = $this->db->real_escape_string($user_id);
        $content_esc = $this->db->real_escape_string($content);
		$link_title = $link_content = $link_image = $link_url = '';
		
		// Get group/page id if available
		if($group) {
			$group_id_esc = (isset($group['group_id'])) ? $this->db->real_escape_string($group['group_id']) : $this->db->real_escape_string($group['page_id']);
			$post_grp_typ = (isset($group['group_id'])) ? '2' : '1';
		} else {
			$this->db->real_escape_string($group['group_id']);
		}

		// Disable mention notifications for groups/pages
		$mention_nots = ($group) ? 1 : NULL;
		
		// Parse message
		$message_esc = $this->db->real_escape_string($this->addMentions(protectInput($message),$user,$id,$mention_nots));
		
		$post_extras_esc = $this->db->real_escape_string($list_post_extras);
		
		// Match hashtags
		preg_match_all('/(#\w+)/u', str_replace(array('\r', '\n'), ' ', $message_esc), $matchedHashtags);

		// For each hashtag, strip the # and add a comma after it
		if(!empty($matchedHashtags[0])) {
			foreach($matchedHashtags[0] as $tag) {
				$tags .= str_replace('#', '', $tag).',';
			}
		} else {
			$tags = '';
		}
		
		// Escape tags
		$tags_esc = $this->db->real_escape_string($tags);
		
		// Check for shared link if a text update
		if($post_type[0] == 0 && empty($p_content)) {
            
		    // Search for links included
            preg_match('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', $message, $matches);

			if($matches[0]) {
				
				require_once(__DIR__.'/SimpleHTMLDom/simple_html_dom.php');
		
				foreach($matches as $url) {
					
					if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                        $url = "http://" . $url;
                    }
					
					$get_content = file_get_html($url);
					$link_url = $url;
					if($get_content && strlen($url) < 240) {
						break;
					} else {
						$link_url = '';
					}
				}
			}
				
			if($get_content) {
				
                foreach ($get_content->find('title') as $element) {
                    $link_title = fixText(240,$element->plaintext);
                }

                $link_content = $get_content->find("meta[name='description']", 0)->content;
                
				$link_content = fixText(240, $link_content);
                
				if (empty($link_title)) {
                    $link_title = '';
                }
				
				if ($link_content === false) {
                    $link_content = '';
                }
				
                if (empty($link_content)) {
                    $link_content = $get_content->find("meta[property='og:description']", 0)->content;
                    $link_content = fixText(240, $link_content);
                    if ($link_content === false) {
                        $link_content = '';
                    }
                }
                
				$link_image = $get_content->find("meta[property='og:image']", 0)->content;
               
			    if (empty($link_image) || !preg_match('/[\w\-]+\.(jpg|png|gif|jpeg)/', $link_image) || strlen($link_image) > 240) {
					$link_image = $get_content->find('img')->src;
                }
				
				if (empty($link_image) || strlen($link_image) > 240) {
					$link_image = '';
				}
				
			}
		
		}
		
		// Insert post
		if($group) {
            $query = sprintf("INSERT INTO `user_posts`(`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_tags`, `post_time`, `post_extras`, `link_title`, `link`, `link_description`, `link_img`, `posted_as`, `posted_at`, `post_deleted`) VALUES (NULL, '$user_esc', '$content_esc', '$message_esc', '$post_type[0]', '$tags_esc', CURRENT_TIMESTAMP, '0,0,0', '%s', '%s', '%s', '%s', '$post_grp_typ', '$group_id_esc', '0');", $this->db->real_escape_string($link_title), $this->db->real_escape_string($link_url), $this->db->real_escape_string($link_content), $this->db->real_escape_string($link_image));
		} else {
            $query = sprintf("INSERT INTO `user_posts`(`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_tags`, `post_time`, `post_extras`, `link_title`, `link`, `link_description`, `link_img`, `post_deleted`) VALUES (NULL, '$user_esc', '$content_esc', '$message_esc', '$post_type[0]', '$tags_esc', CURRENT_TIMESTAMP, '$post_extras_esc', '%s', '%s', '%s', '%s', '0');", $this->db->real_escape_string($link_title), $this->db->real_escape_string($link_url), $this->db->real_escape_string($link_content), $this->db->real_escape_string($link_image));
		}
		
		// If post inserted successfully 
		if($this->db->query($query) === TRUE) {
			
			// Last inserted post id
			$post_id = $this->db->insert_id;
			
			// Update user photos count if added photos
			$update_photos = ($post_type[0] == "1") ? ", `photos` = `photos` + '1'" : "";
			
			// Update user posts and photos count
			$query = $this->db->query("UPDATE `users` SET `posts` = `posts` + '1' $update_photos WHERE `idu` = '$user_esc' ; ");
			
			// Return latest added post
			if(!$group) {
				
				// Set mention type
				$mention_types = array("1" =>"8","4"=>"9","7"=>"15","8"=>"9","9"=>"9");
				$men_type = (isset($mention_types[$post_type[0]])) ? $mention_types[$post_type[0]] : 6;
				
				// Update pending notifications section
				$query = $this->db->query(sprintf("UPDATE `notifications` SET `not_type` = '$men_type' , `not_content_id` = '%s' WHERE `not_type` = '$id' AND `not_time` >= '$time' ;",$post_id));		

				// Return latest added post
			    return $this->getFeeds(0,$user_id,1,$post_type[1],$post_type[2]);
			
			} else {
				
				// Else return group post
				if(isset($group['group_id'])) {
					
					return $this->getGroupFeeds($user,0,$group['group_id'],1,1,$post_type[3]) ;
					
				} else {
					
					// Insert into page log
	                $this->db->query(sprintf("INSERT INTO `page_logs` (`id`, `page_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($group['page_id']),$this->db->real_escape_string($user['idu']),$post_type[0],3));		

					return $this->getPageFeeds($user,0,$group['page_id'],1,1,$post_type[3]) ;
					
				}
			}
		}			
	}
	
    function sharePost($user,$post_id) {                                                     // Share post  
	    global $TEXT;
		
		$query = sprintf("INSERT INTO `user_posts`(`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_tags`, `post_time`, `post_extras`, `posted_as`, `posted_at`, `link_title`, `link`, `link_description`, `link_img`, `post_deleted`) VALUES (NULL, '%s', '%s', '', '6', '', CURRENT_TIMESTAMP, '0,0,0', '0', '0', '', '', '', '', '0');",$this->db->real_escape_string($user['idu']),$this->db->real_escape_string($post_id));
		
		return ($this->db->query($query) === TRUE) ? showSuccess($TEXT['_uni-Share_success']).'<script>share(0,0);</script>' : showError($TEXT['_uni-Share_fails']).'<script>share(0,0);</script>';
		
	}
	
  	function postAct($post_id,$p_type=0,$user,$type = 0) {                                 // Post love | Remove love
        // USER_ID : Logged user	
		// TYPE 0  : Remove love (UNLIKE)		
        // TYPE 1  : Do love (LIKE)
		global $TEXT,$page_settings;
		
		if(!$user['idu']) {
		    $user = $this->getUserById($user);
		}
		
		$user_id = $user['idu'];
		
		// Is post exists
        $post = $this->db->query(sprintf("SELECT * FROM `user_posts` WHERE `post_id` = '%s'", $this->db->real_escape_string($post_id)));

	    // If post exists
		if(!empty($post)) {
			$post = $post->fetch_assoc();
			$poster = $this->getUserByID($post['post_by_id']);               // Fetch the user who posted this post
			$verified = $this->isLoved($post['post_id'],$user_id);           // Check whether posts is already loved
			$available = $this->isPostAvailable($post,$poster,$user_id);     // Confirm post privacy
			
			// Escape variables for SQL Query
			$post_id_esc = $this->db->real_escape_string($post['post_id']);
			$user_id_esc = $this->db->real_escape_string($user_id);
			$poster_id_esc = $this->db->real_escape_string($poster['idu']);
			$p_type_esc = $this->db->real_escape_string($p_type);
			
			// Do love(Like)
			if($type == 1 && $available == 1) {
				
				// If post is not loved
				if(!$verified) {
					
					// Insert new Love(Like)
					$query = $this->db->query("INSERT INTO `post_loves` (`id`, `post_id`, `by_id`, `type`, `time`) VALUES (NULL, '$post_id_esc', '$user_id_esc', '$p_type_esc', CURRENT_TIMESTAMP) ");

					// Update posts loves +1
					$query = $this->db->query("UPDATE user_posts SET user_posts.post_loves = user_posts.post_loves + 1 WHERE user_posts.post_id = $post_id_esc ");

					// Insert notification if user has enabled notifications on new post likes
					if($poster['n_like'] == 1 && $poster_id_esc !== $user_id_esc) {
						$query = $this->db->query("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_data`, `not_read`, `not_time`) VALUES (NULL, '$user_id_esc', '$poster_id_esc', '$post_id_esc','0','4','$p_type_esc', '0', CURRENT_TIMESTAMP) ") ;
					}	

					// Send new like email if user has enable email notifications
					if($poster['e_like'] && $poster_id_esc !== $user_id_esc) {
						mailSender($page_settings, $poster['email'], $TEXT['_uni-new_like_ee_ttl_sml'], sprintf($TEXT['_uni-new_like_ee_ttl'], fixName(35,$poster['username'],$poster['first_name'],$poster['last_name']), '/'.$user['username'], fixName(35,$user['username'],$user['first_name'],$user['last_name']),'/view/'.$post_id_esc), $TEXT['web_mail']);
					}
				}
			}
			
			// Remove love
			if($type == 0 && $available == 1) {
				
				// If post is loved
				if($verified) {

					// Delete post love
					$query = $this->db->query("DELETE FROM `post_loves` WHERE `post_id` = '$post_id_esc' AND `by_id` = '$user_id_esc' ");
					
					// Update post loves -1
					$query = $this->db->query("UPDATE user_posts SET user_posts.post_loves = user_posts.post_loves - 1 WHERE user_posts.post_id = '$post_id_esc' ");

					// Delete notifications related 
					$query = $this->db->query("DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$user_id_esc' AND `notifications`.`not_to` = '$poster_id_esc' AND `notifications`.`not_content_id` = '$post_id_esc' AND `notifications`.`not_type` = '4' ");

				}
			}				
		}
	}
	
	function addComment($comment,$post_id,$user) {                               // Post Comment
	    global $TEXT,$page_settings;
		
		// Is post exists
        $post = $this->getPostByID($post_id);
		
	    // If post exists
		if($post) {
			
			$poster = $this->getUserByID($post['post_by_id']);                   // Fetch the user who posted this post
			
			// If post is from group/user
			if(in_array($post['posted_as'],array(0,2))) {
				
				$commented_as = '0';                   
			    $available = $this->isPostAvailable($post,$poster,$user['idu']);     // Confirm post privacy
				$user_id2_esc = $this->db->real_escape_string($user['idu']);
				
			} else {
			
				// Get page
				$get_page = $this->getPage($post['posted_at']);
			
				// If owner allow hime comment as page
				if($get_page['page_owner'] == $user['idu']) {
			        
					//$poster = $get_page['page_id'];
					$user_id2_esc = $this->db->real_escape_string($get_page['page_id']);
					$commented_as = '1';
					
				// Else check further
				} else {
				
					// Get user role on page
					$get_page_role = $this->getPageRole($user['idu'],$post['posted_at']);
					
					// If is above analyst
					if(isset($get_page_role['page_role']) && $get_page_role['page_role'] > 2) {
						//$poster = $get_page['page_id'];
						$user_id2_esc = $this->db->real_escape_string($get_page['page_id']);
						$commented_as = '1';
					} else {
						$commented_as = '0';                   
			    		$user_id2_esc = $this->db->real_escape_string($user['idu']);
					}

				}
				
				$available = 1;
			}
		
			// Escape variables for SQL Query
			$post_id_esc = $this->db->real_escape_string($post['post_id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$poster_id_esc = $this->db->real_escape_string($poster['idu']);
			
            // Save time to make MySQL queries fast
			$time = date('Y/m/d H:i:s');
			
			// Random ID for mention notifications
			$id = mt_rand(100, 9999);
		
			// Parse comment
			$comment_esc = $this->db->real_escape_string($this->addMentions(protectInput($comment),$user,$id));
		
			// Post comment if privacy confirmed
			if($available) {
				
				// Insert new Comment
				$query = $this->db->query("INSERT INTO `post_comments` (`id`, `post_id`, `by_id`, `commented_as`, `comment_text`, `time`) VALUES (NULL, '$post_id_esc', '$user_id2_esc', '$commented_as', '$comment_esc', CURRENT_TIMESTAMP) ");
				
				// Update posts Comment +1
				$query =  $this->db->query("UPDATE user_posts SET user_posts.post_comments = user_posts.post_comments + 1 WHERE user_posts.post_id = $post_id_esc ");

				// Update pending notifications section
				$query = $this->db->query("UPDATE `notifications` SET `not_type` = '7' , `not_content_id` = '$post_id_esc' WHERE `not_type` = '$id' AND `not_time` >= '$time' ;");		
		
				// Insert notification if post by user has enabled notifications on new post Comments
				if($poster['n_comment'] == 1 && $poster_id_esc !== $user_id_esc) {
					$query =  $this->db->query("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$user_id_esc', '$poster_id_esc', '$post_id_esc','0','5','0', CURRENT_TIMESTAMP) ") ;
				}
				
				// Send new comment email if user has enable email notifications
			    if($poster['e_comment'] && $poster_id_esc !== $user_id_esc) {
				    mailSender($page_settings, $poster['email'], $TEXT['_uni-new_comment_ee_ttl_sml'], sprintf($TEXT['_uni-new_comment_ee_ttl'], fixName(35,$poster['username'],$poster['first_name'],$poster['last_name']), '/'.$user['username'], fixName(35,$user['username'],$user['first_name'],$user['last_name']),'/view/'.$post_id_esc,protectXSS($comment_esc)), $TEXT['web_mail']);
			    }

				return '<script>
							resetForm("form-add-comment-'.protectXSS($post_id).'");
							loadComments('.protectXSS($post_id).');
						</script>';
			} else {
				return showError($TEXT['_uni-doesnt-exists-post']);
			}
		} else {
			return showError($TEXT['_uni-doesnt-exists-post']);
		}
	}	
	
    function editMember($content_id,$add_user,$user_id,$type=1,$group=0) {       // Add or remove user from chat 
		// TYPE 1 : ADD Member
        // TYPE 0 : REMOVE Member
		// GROUP 0 : In Chat
		// GROUP 1 : In Group
		
		global $TEXT;
		
		// Get user if exists
		$target = $this->getUserByID($add_user);
		
		// Check whether user is the member of chat
		$isMember = $this->isMember($target['idu'],$content_id,$group);
		
		// Validation
		if(!$target['idu']) {
			return showError($TEXT['_uni-User_exists_0']);    // User doesn't exists
		} elseif($add_user == $user_id) {
			return showError($TEXT['_uni-You_yourself']);     // User is trying to add or remove himself
		} elseif($isMember && $type == 1) { 
			return showError($TEXT['_uni-User_already']);     // User can't be added two times
		}  elseif(!$isMember && $type == 0) {
			return showError($TEXT['_uni-This_user_is_not']); // User can't be removed if he not a memeber
		} else {
			
			// Set query type and success message
			if($group) {
			
			    // Add or remove user
			    if($type == 1) {
		            $query = sprintf("INSERT INTO `group_users` (`gid`, `user_id`, `group_id`, `group_role`, `group_partner_id`, `f_feeds`, `group_status`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', '0', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($add_user),$this->db->real_escape_string($content_id),1,$this->db->real_escape_string($user_id),1);
		            $message = $TEXT['_uni-User_added_group'];	
				} else {
				    $query = sprintf("DELETE FROM `group_users` WHERE `user_id` = '%s' AND `group_id` = '%s'",$this->db->real_escape_string($add_user),$this->db->real_escape_string($content_id));
		            $message = $TEXT['_uni-User_removed'];
				}
				
		    } elseif($type == 1) {
		        $query = sprintf("INSERT INTO `chat_users` (`cid`, `uid`, `type`, `form_id`, `on_form`) VALUES (NULL, '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($add_user),1,$this->db->real_escape_string($content_id));
		        $message = $TEXT['_uni-User_added'];
		    } else {
			    $query = sprintf("DELETE FROM `chat_users` WHERE `uid` = '%s' AND `form_id` = '%s'",$this->db->real_escape_string($add_user),$this->db->real_escape_string($content_id));
		        $message = $TEXT['_uni-User_removed'];
		    }	
		
		    // Final validation and execute query
		    if($target['idu'] && $this->db->query($query) === TRUE) {
			
   			    // Add activity in group log
				if($group) {	    	
			    	
					$log_type = ($type == 1) ? 3 : 4;		
					
					$this->db->query(sprintf("INSERT INTO `group_logs` (`id`, `group_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($content_id),$this->db->real_escape_string($user_id),$this->db->real_escape_string($add_user),$log_type));		
					
					// Update group members count
					$this->db->query(sprintf("UPDATE `groups` SET `group_users` = '%s' WHERE `group_id` = '%s' ",$this->db->real_escape_string(numberGroupMembers($content_id,$this->db)),$this->db->real_escape_string($content_id)));		
	          
		            if($type == 1) {

						// Add notification to user (Added to following group)
		   	 	        $this->db->query(sprintf("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '%s', '%s', '%s','0','13','0', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($target['idu']),$this->db->real_escape_string($content_id))) ;
				
					}
	
				} elseif($type == 1) {
				
				    // Add notification to group (User added)
				    $this->db->query(sprintf("INSERT INTO `chat_messages` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '4', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string(fixName(30,$target['username'],$target['first_name'],$target['last_name'])),$this->db->real_escape_string($user_id),$this->db->real_escape_string($content_id)));
		   
		    	    // Add notification to user (Added to following chat)
		   	 	    $this->db->query(sprintf("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '%s', '%s', '%s','0','10','0', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($target['idu']),$this->db->real_escape_string($content_id))) ;
				
			    } elseif(!$group) {
				
				    // Add notification to group (User removed)
				    $this->db->query(sprintf("INSERT INTO `chat_messages` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '6', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string(fixName(30,$target['username'],$target['first_name'],$target['last_name'])),$this->db->real_escape_string($user_id),$this->db->real_escape_string($content_id)));
		   	
			        // Delete previous added notification if exists (Add to following chat)
			        $this->db->query(sprintf("DELETE FROM `notifications` WHERE `not_from` = '%s' AND `not_to` = '%s' AND `not_content_id` = '%s' AND `not_type` = '10' ",$this->db->real_escape_string($user_id),$this->db->real_escape_string($target['idu']),$this->db->real_escape_string($content_id)));
			
			    }
 
                if(!$group) {
				    
					// Update group status
			        $this->updateForm($content_id,$type);
				
				    // Update last activity on form
	                $this->updateFormActivity($content_id,$user_id);
				
				}
	  
			    return showSuccess($message);
		    }			
		}
	}
	
	function requestMember($to,$form_id,$requested,$by,$type) {                  // Add request to chat founder
		
		global $TEXT;
		
		// Set request type (Add or remove)
		$n_type = (!$type) ? 12 : 11 ;

		// Add notification to chat founder
		$this->db->query(sprintf("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '%s', '%s', '%s','%s','%s','0', CURRENT_TIMESTAMP);",$this->db->real_escape_string($by),$this->db->real_escape_string($to),$this->db->real_escape_string($form_id),$this->db->real_escape_string($requested),$this->db->real_escape_string($n_type)));

		// Return message
		return showSuccess($TEXT['_uni-Request_sent']);
		
	}
	
	function report($c_id,$v1,$v2,$v3,$v4,$user,$type) {                         // Report content
	    // TYPE 1 : Report USER
		// TYPE 2 : Report POST
		// TYPE 3 : Report COMMENT
		
		global $TEXT;
		
		// Escape types
		$v1_esc = $this->db->real_escape_string($v1);    // Report form option 1
		$v2_esc = $this->db->real_escape_string($v2);    // Report form option 2
		$v3_esc = $this->db->real_escape_string($v3);    // Report form option 3
		$v4_esc = $this->db->real_escape_string($v4);    // Report form option 4

		if($type == 1) {

			// Fetch user row if exists
			$fetched = $this->getUserByID($c_id);
			
			// If user exists
			if($fetched && !$user['b_users']) {
			
			    // Escape variables for SQL Query
				$user_id_esc = $this->db->real_escape_string($user['idu']);    // Current user
				$c_id_esc = $this->db->real_escape_string($fetched['idu']);    // User going to report
				$content_owner_esc = $c_id_esc;                                // USER is owner of USER

				// If user is already reported and marked safe
				if($fetched['safe']) {
					return $TEXT['_uni-doesnt-reportable-user'];
				} else {
					
					// Verify if user is reported and in queue
					$check = $this->db->query("SELECT * FROM reports WHERE reports.content_id = $c_id_esc AND reports.type = 1 ;");
					
					// If user not reported yet
					if($check->num_rows) {
						
						// User is already reported and report is in queue
						return $TEXT['_uni-doesnt-reportable2-user'];			
						
					} else {
						
                        if($user['idu'] == $fetched['idu']) {
							
							// User can't report himself
						    return $TEXT['_uni-doesnt-exists-user'];
							
						} else {
							
							// Add report
							$query = "INSERT INTO `reports`(`id`, `from`, `content_id`,`content_owner`, `type`,`val1`,`val2`,`val3`, `val4`, `time`) VALUES (NULL, '$user_id_esc', '$c_id_esc', '$content_owner_esc', '1','$v1_esc','$v2_esc','$v3_esc','$v4_esc',CURRENT_TIMESTAMP) ;" ;
							
							if($this->db->query($query) === TRUE) {
							
								// Success
						    	return sprintf($TEXT['_uni-reported-user'],$TEXT['web_name']);
								
					   	 	} else {
							
								// MySQL query failure
						    	return $TEXT['lang_error_connection'];
								
					    	}
						}						
					}
				}
			} else {
				// Not available
				return ($user['b_users']) ? $TEXT['_uni-doesnt-blocked-users'] : $TEXT['_uni-doesnt-exists-user'];	
			}	
			
		} elseif($type == 2) {
			
			// Fetch post row if exists
			$fetched = $this->getPostByID($c_id);
			
			// If posts exists
			if($fetched && !$user['b_posts']) {	
			
			    // Escaping variables for query
				$user_id_esc = $this->db->real_escape_string($user['idu']);                  // Current user id 
				$c_id_esc = $this->db->real_escape_string($fetched['post_id']);              // Post id
				$content_owner_esc = $this->db->real_escape_string($fetched['post_by_id']);  // Post owner id
				
				// If post is already reported and marked safe and is not edited after marking safe
				if($fetched['safe'] == 1 && $fetched['edited'] == 0) {
					return $TEXT['_uni-doesnt-reportable-post'];	
				} else {
					
					// Ignore if already reported
					$check = $this->db->query("SELECT * FROM reports WHERE reports.content_id = $c_id_esc AND reports.type = 2 ;");
					
					if($check->num_rows) {
						return $TEXT['_uni-doesnt-reportable2-post'];
						
					} else {
	                    
						// Insert report
						$query = "INSERT INTO `reports`(`id`, `from`, `content_id`,`content_owner`, `type`,`val1`,`val2`,`val3`, `val4`, `time`) VALUES (NULL, '$user_id_esc', '$c_id_esc', '$content_owner_esc', '2','$v1_esc','$v2_esc','$v3_esc','$v4_esc',CURRENT_TIMESTAMP) ;" ;
					    
						if($this->db->query($query) === TRUE) {
							
							// Mark unsafe if it's safe
							if($fetched['safe']) $this->db->query("UPDATE user_posts SET user_post.safe = '0' WHERE user_posts.post_id = $c_id_esc ;") ;
						    
							return sprintf($TEXT['_uni-reported-post'],$TEXT['web_name']);
							
					    } else {
						    return $TEXT['lang_error_connection'];
					    }					
					}
				}
				
			} else {
				// Not available
				return ($user['b_posts']) ? $TEXT['_uni-doesnt-blocked-posts'] : $TEXT['_uni-doesnt-exists-post'];
			}
			
		} elseif($type == 3) {
			
			// Fetch comment
			$fetched = $this->getCommentByID($c_id);
			
			// If exists
			if($fetched && !$user['b_comments']) {

                // Escape variable for MySQl Query			
				$user_id_esc = $this->db->real_escape_string($user['idu']);
				$c_id_esc = $this->db->real_escape_string($fetched['id']);
				$content_owner_esc = $this->db->real_escape_string($fetched['by_id']);
		
				// If comment is safe
				if($fetched['safe'] == 1) {
					return $TEXT['_uni-doesnt-reportable-comment'];
				} else {
					
					// Check whether comment is already reported
					$check = $this->db->query("SELECT * FROM reports WHERE reports.content_id = $c_id_esc AND reports.type = 3 ;");
					
					// If comment is not reported yet
					if($check->num_rows == 0) {
						
						// Insert new report
						$query = "INSERT INTO `reports`(`id`, `from`, `content_id`,`content_owner`, `type`,`val1`,`val2`,`val3`, `val4`, `time`) VALUES (NULL, '$user_id_esc', '$c_id_esc', '$content_owner_esc', '3','$v1_esc','$v2_esc','$v3_esc','$v4_esc',CURRENT_TIMESTAMP) ;" ;
					    
						// Perform MySQL multi_query
						if($this->db->multi_query($query) === TRUE) {
						    return sprintf($TEXT['_uni-reported-comment'],$TEXT['web_name']);
					    } else {
						    return $TEXT['lang_error_connection'];
					    }
						
					} else {
						
						// Comment is already reported and in queue
						return $TEXT['_uni-doesnt-reportable2-comment'];
					}
				}
				
			} else {
				// Not available
				return ($user['b_comments']) ? $TEXT['_uni-doesnt-blocked-comments']: $TEXT['_uni-doesnt-exists-comment'];

			}			
		} else {
			// Wrong Parameters
			return $TEXT['lang_error_script1'];
		}
	}	
	
	function savePostEdit($post_id,$new_text,$user) {                            // Save edited post
		global $TEXT;
		
		// Fetch post
		$post = $this->getPostByID($post_id);
		
		// If post exists
		if($post) {
			
			// Escape variables for MySQL Query
			$post_id_esc = $this->db->real_escape_string($post['post_id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$new_text_esc = $this->db->real_escape_string(protectInput($new_text));

			// Security and privacy check
            if($post['post_by_id'] == $user['idu'] && !isXSSED($new_text)) {
				
				// Remove background from status update in text increased
				$post_type_up = (!$post['post_type'] && strlen($new_text_esc) > strlen($post['post_text'])) ? ",`post_content` = ''" : "";
				
				// Update post text and make it reportable
				$query = "UPDATE `user_posts` SET `user_posts`.`post_text` = '$new_text_esc' , `user_posts`.`edited` = '1' , `user_posts`.`safe` = '0' $post_type_up WHERE `user_posts`.`post_id` = '$post_id_esc' AND `user_posts`.`post_by_id` = '$user_id_esc' ";
			    
				// Perform MySQL Query
				return ($this->db->query($query) === TRUE) ? $TEXT['_uni-success-edited-post'] : $TEXT['lang_error_connection'];
			
			} else {
				// Return error
				return (isXSSED($new_text)) ? $TEXT['_uni-P-xss'] : $TEXT['_uni-doesnt-exists-post'];	
			}			
		} else {
			// Post doesn't exists
			return $TEXT['_uni-doesnt-exists-post'];
		}	
	}	
	
    function saveFormEdit($form_id,$name,$description,$user)	{                // Save edited chat
		global $TEXT;
		
		// Fetch post
		$form = $this->getChatFormByID($form_id,$user['idu']);
		
		// If post exists
		if($form['form_id']) {
			
			// Escape variables for MySQL Query
			$form_id_esc = $this->db->real_escape_string($form['form_id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$new_name_esc = $this->db->real_escape_string(protectInput($name));
			$new_desc_esc = $this->db->real_escape_string(protectInput($description));

			// Update post text and make it reportable
			$query = "UPDATE `chat_forms` SET `chat_forms`.`form_name` = '$new_name_esc' , `chat_forms`.`form_description` = '$new_desc_esc'  WHERE `chat_forms`.`form_id` = '$form_id_esc'  ";
 
			// Perform MySQL Query
			if($this->db->query($query) === TRUE) {
				
				if($this->db->affected_rows) {
					
					// Add notification to group (User removed)
				    $this->db->query(sprintf("INSERT INTO `chat_messages` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '7', '', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user['idu']),$this->db->real_escape_string($form_id)));
		   		    
					// Return message
					return showBox($TEXT['_uni-Settings_updated']);
					
				} else {
					return showBox($TEXT['_uni-No_changes']);
				}
			} else {	
				return showBox($TEXT['lang_error_connection']);	
			}		
		} else {
			// Post doesn't exists
			return showBox($TEXT['_uni-Form_n_available']);
		}	
	}
	
	function deletePost($post_id,$user,$post = NULL) {                           // Delete user post
	    
		global $TEXT;
	   
	    // Fetch post if not fetched already
		if(is_null($post)) {
			$post = $this->getPostByID($post_id);
		}

	    // If post exists
		if($post) {

			// Escape variables for SQL Query
			$post_id_esc = $this->db->real_escape_string($post['post_id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			
			// Count user posts
			$totalposts = $this->db->real_escape_string($this->numberPosts($user['idu']) - 1);

			// Count user photos
			$totalphotos = ($post['post_type'] == 1) ? $this->db->real_escape_string($this->numberPhotos($user['idu']) - 1) : $this->db->real_escape_string($this->numberPhotos($user['idu']));

			// Perform set of SQL Queries after this little check
			if(($post['post_by_id'] == $user['idu'])) {
				
            	// Delete post
				$this->db->query("DELETE FROM `user_posts` WHERE `user_posts`.`post_id` = '$post_id_esc' AND `user_posts`.`post_by_id` = '$user_id_esc' ") ;
			
				// Delete loves/likes
				$this->db->query("DELETE FROM `posts_loves` WHERE `posts_loves`.`post_id` = '$post_id_esc' ") ;
			
				// Delete comments
				$this->db->query("DELETE FROM `post_comments` WHERE `post_comments`.`post_id` = '$post_id_esc' ") ;
			
				// Delete notifications
				$this->db->query("DELETE FROM `notifications` WHERE `notifications`.`not_content_id` = '$post_id_esc' AND `notifications`.`not_type` IN(4,5,6,8,9) ") ;
				
				// Delete reports
				$this->db->query("DELETE FROM `reports` WHERE `reports`.`content_id` = '$post_id_esc' AND `reports`.`type` = '2' AND `reports`.`content_owner` = '$user_id_esc' ") ;
			
				// Update user posts and photos count
				$this->db->query("UPDATE `users` SET `users`.`posts` = '$totalposts' , `users`.`photos` = '$totalphotos' WHERE `users`.`idu` = '$user_id_esc' ") ;

				// Delete linked content
				if($post['post_type'] == 1) { 

                    $images = explode(',', $post['post_content']);
					
					// Delete Post photos
					foreach($images as $image) {
						unlink("../../../uploads/posts/photos/".$image);
					}
					
			    } elseif($post['post_type'] == 2) {
					unlink("../../../uploads/posts/videos/".$post['post_content']);               // Delete Post video
				} elseif($post['post_type'] == 3 && $post['post_content'] !== $user['image']) {
					unlink("../../../uploads/profile/main/".$post['post_content']);               // DEL-Profile photo if it's not in use
				}

				// Success
				return $TEXT['_uni-deleted-post'];
				
		    } else {
				//  Post doesn't belongs to this user
				return $TEXT['_uni-doesnt-exists-post'];	
			} 					
		} else {
			// Post doesn't exists
			return $TEXT['_uni-doesnt-exists-post'];	
		}
	}	
	
	function deleteComment($comment_id,$user) {                                  // Delete user comment 
	    global $TEXT;
	   
	    // Fetch Comment if exists
        $comment = $this->getCommentByID($comment_id);
		
	    // If Comment exists
		if($comment) {
		    
			// Fetch parent post
			$post = $this->getPostByID($comment['post_id']);
			
			// Escape variables for SQL Query
			$comment_id_esc = $this->db->real_escape_string($comment['id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$post_id_esc = $this->db->real_escape_string($comment['post_id']);
            $left = $this->db->real_escape_string($this->numberComments($comment['post_id']) - 1);
			
			// Perform SQL query after this little check
			if(($comment['by_id'] == $user['idu']) || $post['post_by_id'] == $user['idu']) { 
			
                if($comment['by_id'] == $user['idu']) {
                
				    //  Delete Own Comment
			        $this->db->query("DELETE FROM `post_comments` WHERE `post_comments`.`id` = '$comment_id_esc' AND `post_comments`.`by_id` = '$user_id_esc' ");	
			
			    } else {
                
				    //  Delete others Comment on own post
			        $this->db->query("DELETE FROM `post_comments` WHERE `post_comments`.`id` = '$comment_id_esc' ");	
				
			    }
				
			    // Update user posts and photos count
			    $this->db->query("UPDATE `user_posts` SET `user_posts`.`post_comments`= '$left' WHERE `user_posts`.`post_id`= '$post_id_esc' ");
				
				// Success
				return $TEXT['_uni-deleted-comment'].'<script>if($("#comments-'.$post_id_esc.' > div").length) {$("#comments-count-'.$post_id_esc.'").html($("#comments-'.$post_id_esc.' > div").length - 1 + " '.sprintf($TEXT['_uni-addOff'],$left).'")}</script>';
				
			} else {
				//  Comment doesn't belongs to this user
				return $TEXT['_uni-doesnt-exists-comment'];	
			} 					
		} else {
			// Comment doesn't exists
			return $TEXT['_uni-doesnt-exists-comment'];	
		}
	}
	
	function deleteMessage($mid,$user_id) {                                      // Delete chat message
		global $TEXT;
		
		// Delete message
		$query = sprintf("DELETE FROM `chat_messages` WHERE `mid` = '%s' AND `by` = '%s'",$this->db->real_escape_string($mid),$this->db->real_escape_string($user_id));

		return ($this->db->query($query) == TRUE) ? $TEXT['_uni-deleted-message'] : $TEXT['_uni-doesnt-exists-message'];

	}
	
	function deleteNotification($id,$user_id) {                                  // Delete member request
		
		// Delete member request only
		$query = sprintf("DELETE FROM `notifications` WHERE `notifications`.`id` = '%s' AND `notifications`.`not_to` = '%s' AND `notifications`.`not_type` IN(11,12)",$this->db->real_escape_string($id),$this->db->real_escape_string($user_id));
		
		return ($this->db->query($query) === TRUE) ? 1 : 0;
	}

	function chatCover($user_id,$form_id,$image) {                               // Update chat cover                   
		global $TEXT;
		
		$form = $this->getChatFormByID($form_id,$user_id);
		
		// Escape variable for MySQl Query 
		$form_id_esc = $this->db->real_escape_string($form['form_id']);
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		if($form['form_id']) {
		
			// Update user cover photo
			$query = "UPDATE `chat_forms` SET `form_cover` = '$image_esc' WHERE `chat_forms`.`form_id` = '$form_id_esc' ;";

			// Perform MySQl multi_query
			if($this->db->query($query) === TRUE) {
				
				// Add notification to group (cover updated)
			    $this->db->query(sprintf("INSERT INTO `chat_messages` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '3', '', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($form['form_id'])));
		  
		        // Delete previous cover
				if(file_exists("../../../uploads/chats/covers/".$form['form_cover']) && $form['form_cover'] !== 'default.png') {
					unlink("../../../uploads/chats/covers/".$form['form_cover']);
				}

				// Update last activity on form
	            $this->updateFormActivity($form['form_id'],$user_id);
				
				return array(showSuccess($TEXT['info-s-chat_cover']),'thumb=1&src='.$image.'&fol=e&w=282&h=122');		
			}	
		} else {
			return array($TEXT['_uni-Form_n_available'],'0');
		}
	}
	
	function updatePageCover($user_id,$page_id,$image) {                         // Update page cover  
        global $TEXT;
		
		$page = $this->getPage($page_id);
		
		// Escape variable for MySQl Query 
		$page_id_esc = $this->db->real_escape_string($page['page_id']);
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		if($page['page_id']) {
		
			// Update user cover photo
			$query = "UPDATE `pages` SET `page_cover` = '$image_esc' WHERE `pages`.`page_id` = '$page_id_esc' ;";

			// Perform MySQl multi_query
			if($this->db->query($query) === TRUE) {
				
		        // Delete previous cover (Disabled temp)
				if(file_exists("../../../uploads/pages/covers/".getCoverSrc(str_replace('rep_','',$page['page_cover']))) && $page['page_cover'] !== 'default.png') {
					//unlink("../../../uploads/pages/covers/".getCoverSrc(str_replace('rep_','',$page['page_cover'])));
					//unlink("../../../uploads/pages/covers/".getCoverSrc($page['page_cover']));
				}
				
				// Add in group log
				$this->db->query(sprintf("INSERT INTO `page_logs` (`id`, `page_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($page['page_id']),$this->db->real_escape_string($user_id),'',5));		

				return showSuccess($TEXT['info-s-page_cover']);		
			}	
		} else {
			return array($TEXT['_uni-Form_n_available'],'0');
		}
	}
	
	function updatePagePic($user_id,$page_id,$image) {                           // Update page cover  
        global $TEXT;
		
		$page = $this->getPage($page_id);
		
		// Escape variable for MySQl Query 
		$page_id_esc = $this->db->real_escape_string($page['page_id']);
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		if($page['page_id']) {
		
			// Update user cover photo
			$query = "UPDATE `pages` SET `page_icon` = '$image_esc' WHERE `pages`.`page_id` = '$page_id_esc' ;";

			// Perform MySQl multi_query
			if($this->db->query($query) === TRUE) {
				
		        // Delete previous cover
				if(file_exists("../../../uploads/pages/main/".$page['page_icon']) && $page['page_icon'] !== 'default.png') {
					unlink("../../../uploads/pages/main/".$page['page_icon']);
				}
				
				// Add in group log
				$this->db->query(sprintf("INSERT INTO `page_logs` (`id`, `page_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($page['page_id']),$this->db->real_escape_string($user_id),'',6));		

				return showSuccess($TEXT['info-s-page_pic']);		
			}	
		} else {
			return array($TEXT['_uni-Form_n_available'],'0');
		}
	}
	
	function updateGroupCover($user_id,$group_id,$image) {                       // Update group cover                   
		global $TEXT;
		
		$group = $this->getGroup($group_id);
		
		// Escape variable for MySQl Query 
		$group_id_esc = $this->db->real_escape_string($group['group_id']);
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		if($group['group_id']) {
		
			// Update user cover photo
			$query = "UPDATE `groups` SET `group_cover` = '$image_esc' WHERE `groups`.`group_id` = '$group_id_esc' ;";

			// Perform MySQl multi_query
			if($this->db->query($query) === TRUE) {
				
				// Add notification to group (cover updated)
			    $this->db->query(sprintf("INSERT INTO `group_log` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '3', '', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($group['group_id'])));
		  
		        // Delete previous cover
				if(file_exists("../../../uploads/groups/".getCoverSrc(str_replace('rep_','',$group['group_cover']))) && $group['group_cover'] !== 'default.png') {
					
					// Unlink main file
					unlink("../../../uploads/groups/".getCoverSrc(str_replace('rep_','',$group['group_cover'])));
					
					// Unlink repositioned file too
					unlink("../../../uploads/groups/".getCoverSrc($group['group_cover']));
				}
				
				// Add in group log
				$this->db->query(sprintf("INSERT INTO `group_logs` (`id`, `group_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($group['group_id']),$this->db->real_escape_string($user_id),'',5));		

				return showSuccess($TEXT['info-s-group_cover']);		
			}	
		} else {
			return array($TEXT['_uni-Form_n_available'],'0');
		}
	}
	
	function chatIcon($user_id,$form_id,$image) {                                // Update chat icon                   
		global $TEXT;
		
		$form = $this->getChatFormByID($form_id,$user_id);
		
		// Escape variable for MySQl Query 
		$form_id_esc = $this->db->real_escape_string($form['form_id']);
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		if($form['form_id']) {
		
			// Update user cover photo
			$query = "UPDATE `chat_forms` SET `form_icon` = '$image_esc' WHERE `chat_forms`.`form_id` = '$form_id_esc' ;";
			
			// Perform MySQl multi_query
			if($this->db->query($query) === TRUE) {
				
				// Add notification to group (icon updated)
			    $this->db->query(sprintf("INSERT INTO `chat_messages` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '2', '', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($form['form_id'])));
		  
		        // Delete previous icon
				if(file_exists("../../../uploads/chats/icons/".$form['form_icon']) && $form['form_icon'] !== 'default.png') {
					unlink("../../../uploads/chats/icons/".$form['form_icon']);
				}
				
				// Update last activity on form
	            $this->updateFormActivity($form['form_id'],$user_id);
				
				return array(showSuccess($TEXT['info-s-chat_icon']),'thumb=1&src='.$image.'&fol=d&w=50&h=50&q=100');		
			}	
		} else {
			return array($TEXT['_uni-Form_n_available'],'0');
		}
	}
	
	function updateCover($user_id,$image) {                                      // Update user cover photo                    
		global $TEXT;
		
		// Escape variable for MySQl Query 
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		// Update user cover photo
		$query = "UPDATE `users` SET `cover` = '$image_esc' WHERE `users`.`idu` = '$user_esc' ;";
		
		// Insert post notification
		$query .= "INSERT INTO `user_posts`(`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_time`, `post_deleted`, `post_tags` ,`link_title`, `link`, `link_description`, `link_img`) VALUES (NULL, '$user_esc', '$image_esc', '', '5', CURRENT_TIMESTAMP, '0', '', '', '', '') ;";
		
		// Perform MySQl multi_query
		return ($this->db->multi_query($query) === TRUE) ? showSuccess($TEXT['info-s-profile_cover']) : '';

	}

	function updateAvatar($user_id,$image) {                                     // Update user profile image                         
		global $TEXT;
		
		// Escape variable for MySQl Query 
		$user_esc = $this->db->real_escape_string($user_id);
		$image_esc = $this->db->real_escape_string($image);
		
		// Update user cover photo
		$query = "UPDATE `users` SET `image` = '$image_esc' WHERE `users`.`idu` = '$user_esc' ;";
		
		// Insert a post
		$query .= "INSERT INTO `user_posts`(`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_time`, `post_deleted`, `post_tags` ,`link_title`, `link`, `link_description`, `link_img`) VALUES (NULL, '$user_esc', '$image_esc', '', '3', CURRENT_TIMESTAMP, '0', '', '', '', '', '') ;";
		
		// Perform MySQl multi_query
		return ($this->db->multi_query($query) === TRUE) ? showSuccess($TEXT['info-s-profile_pic']) : ''; 
		
	}	
	
	function updateSettings($update,$settings,$str) {                            // Update Settings universal function or users
		global $TEXT;
		
		// Preset queries
        require_once(__DIR__ . '/presets/preset_queries.php');

		//	Reset
		$i = $changes = 0 ;
		
		// Load available queries
		$get_query_for = getQueries(1);
		
		// If multiple settings requested
		if (is_array($settings)) {
			
			// Prepare to update settings
			$stmt = $this->db->prepare($get_query_for[$update]);
            
            // Reference is required for PHP 5.3+
			if (strnatcmp(phpversion(),'5.3') >= 0)	{ 
                $tmp = array();
				foreach($settings as $key => $value) $tmp[$key] = &$settings[$key];        
            } 
			
			// Prepends passed elements to the front of the array
			array_unshift($settings,$str);
            
			// Bind new values
			call_user_func_array(array($stmt,'bind_param'),$settings);
			
			// Execute statement
			$stmt->execute();

			// Verify changes made
			if ($stmt->affected_rows) {
				$changes = 1;
			}

			// Close statement
			$stmt->close();
			
			// Check for affected rows and return message
		    return ($changes) ? 1 : 2; 
			
		// Else update single setting
		} else {
			
			// Prepare to update settings
			$stmt = $this->db->prepare($update);

			$stmt->bind_param("s", $this->db->real_escape_string($settings));	
			
			// Execute statement
			$stmt->execute();

			// Verify changes made
			if ($stmt->affected_rows) {
				$changes = 1;
			}

			// Close statement
			$stmt->close();
			
			// Check for affected rows and return message
		    return ($changes) ? 1 : 2;
		}
	}
	
	function sendRecovery($username,$settings) {                                 // Send password recovery details
        global $TEXT;

	    // Get user
	    $result = $this->getUserByUsername($username);
		
	    // Generate random salt
		$salted = md5(secureRand(12,TRUE));
		
		if(!empty($result['idu'])) {
			
			// Set activation response
			$this->db->query(sprintf("UPDATE `users` SET `salt` = '%s' WHERE `users`.`idu` = '%s' ", $salted, $this->db->real_escape_string($result['idu'])));

			// Send activation mail
			mailSender($settings, $result['email'], $TEXT['_uni-Reset_password_ttl'], sprintf($TEXT['_uni-Recover_pass'], $TEXT['title'], '/index.php?respond='.$salted.'&type=recover&for='.$result['idu']), $TEXT['web_mail']);
		
			return $TEXT['_uni-Recovery_sent'];	
			
		} else {
			return $TEXT['_uni-doesnt-exists-user'];
		}
	
	}

 	function resetPassword($id,$settings) {                                      // Reset user password and send new password
        global $TEXT;
		
	    // Get user
	    $result = $this->getUserByID($id);
		
	    // Generate random password (12 characters)
	    $password = secureRand(12,TRUE);
	    
		// Set activation response
		$this->db->query(sprintf("UPDATE `users` SET `password` = '%s' WHERE `users`.`idu` = '%s' ", md5($password), $this->db->real_escape_string($id)));

		// Send activation mail
		mailSender($settings, $result['email'], $TEXT['_uni-New_password'], sprintf($TEXT['_uni-New_password_mail'], $TEXT['title'], $password), $TEXT['web_mail']);
		
		return $TEXT['_uni-Reset_success'];	
	
	}
	
	function getPost($post_id,$c_user = NULL,$type=null,$shared_by=null,$pin=0) {        // Return Single post
        global $TEXT;
		
		// Select post
		$post = $this->db->query(sprintf("SELECT * FROM `user_posts`,`users` WHERE `user_posts`.`post_id` = '%s' AND `users`.`idu` =  `user_posts`.`post_by_id` ", $this->db->real_escape_string($post_id)));

		// If post exists
		if($post->num_rows) {
			$post_row = $post->fetch_assoc();
			$poster = $this->getUserByID($post_row['post_by_id']);               // Fetch the user who posted this post
			
			if($pin) {
				$TEXT['temp-pinned'] = display(templateSrc('/post/elements/pinned'));
				$TEXT['temp-pinned_ch'] = 'pinned';
			}

			if($type) {
				$TEXT['temp-shared_by_id'] = $shared_by['idu'];
				$TEXT['temp-view_del2'] = ($shared_by['idu'] == $c_user['idu']) ? '' : 'style="display:none!important;"';
				$TEXT['temp-shared_by_img'] = $this->getImage($c_user['idu'],$shared_by['idu'],$shared_by['p_image'],$shared_by['image']);	
				
				$TEXT['temp-sid'] = $type;
				
				$TEXT['temp-shared_by'] = fixName(25,$shared_by['username'],$shared_by['first_name'],$shared_by['last_name']);
			}
			
			// Get profile picture
			$TEXT['temp-user_img'] = $this->getImage($c_user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']);			
		
			$pictyure = 1;
          
			if($post_row['posted_as'] == 2) {
				$group = $this->getGroup($post_row['posted_at']);	
                $available = $this->isPostAvailable($post_row,$poster,$c_user['idu'],$group);     // Confirm post privacy						
			} else {
                $group['group_name'] = '';					
			    $available = $this->isPostAvailable($post_row,$poster,$c_user['idu']);
			}

			// List post buttons and details
			list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $this->listFunctions($post_row,$c_user,1,1);
		
			// Parse post text remove all smiles
			$TEXT['temp-pt_ns'] = protectXSS($this->parseText($post_row['post_text'],1));

			// Get post type and content
			list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $this->getPostContent($post_row,$post_row['post_id'],$pictyure,null,$this->parseText(protectXSS($post_row['post_text'])));	

			// Page post
			if($post_row['posted_as'] == 1) {
				
				$get_page = $this->getPage($post_row['posted_at']);
				
				$TEXT['temp-page_link'] = ($get_page['page_username']) ? $get_page['page_username'] : $get_page['page_id'];
				
				// Post heading
			    $TEXT['temp-heading'] = '<a class="h6 b2 dark-font" href="/page/'.$TEXT['temp-page_link'].'" onclick="loadPage('.$get_page['page_id'].',1,1);return false;">'.$get_page['page_name'].'</a>';
				
				// Post id
				$TEXT['temp-id'] = $post_row['post_id'];
				$TEXT['temp-page_id'] = $get_page['page_id'];
				$TEXT['temp-page_icon'] = $get_page['page_icon'];
				
				// Posted time
				$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
				
				// Add post
				$content = ($type) ? display(templateSrc('/post/page_post_shared')) : display(templateSrc('/post/page_post'));
				
			} else {
			
				// Post heading
				list($TEXT['temp-heading'],$TEXT['temp-group_heading']) = $this->getPostHeading($post_row['post_type'],$post_row['posted_as'],$post_row['posted_at'],$post_row['post_extras'],$group['group_name'],$post_row['post_content'],$post_row['gender'],$post_row['post_id']);

				// Post id
				$TEXT['temp-id'] = $post_row['post_id'];

				// Posted time
				$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
				$TEXT['temp-username_c'] = $c_user['username'];
				$TEXT['temp-user_img_c'] = $c_user['image'];
				$TEXT['temp-user_id_c'] = $c_user['idu'];
				$TEXT['temp-user_id'] = $post_row['idu'];
				$TEXT['temp-username'] = $post_row['username'];
				$TEXT['temp-user_name'] = fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']);
				$TEXT['temp-userttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']));
				
				// Add post in list
				$content = ($type) ? display(templateSrc('/post/user_post_shared')) : display(templateSrc('/post/user_post'));
			
			}
			
			$TEXT['temp-pinned'] = $TEXT['temp-pinned_ch'] = '';

			// Privacy check
			if($available == 1) {
				return $content;
			} else {
				return ($type) ? '' : bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf3']);     
			}
		}    		
	}	
	
	function getLovers($from,$post_id,$user,$recent = NULL) {                    // Return People who loved post_id
        global $TEXT,$page_settings;

		// Get post
		$post = $this->getPostByID($post_id);

		// If post exists
		if($post) {
			
			// Reset
			$rows = array();$content = '';
			
			// Set limit
			$limit = $this->db->real_escape_string($this->settings['lovers_per_page'] + 1);
			
			// Set start up
			$from = ($from > 0) ? 'AND post_loves.id < \''.$this->db->real_escape_string($from).'\'' : '';	

			// Confirm post privacy
			$post_available = $this->isPostAvailable($post,$this->getUserByID($post['post_by_id']),$user['idu']);

			// Select data
			$result = $this->db->query(sprintf("SELECT * FROM `post_loves`, `users` WHERE `post_loves`.`post_id` = '%s' AND `post_loves`.`by_id` = `users`.`idu` $from ORDER BY post_loves.id DESC LIMIT $limit ",$this->db->real_escape_string($post_id)));

			// If post and user exists
			if(!empty($result) && $result->num_rows !== 0) {
	   			
				$t_src = templateSrc('SRC',1);
				
				// Likes template
				$lk_tpl = display($t_src.'/elements/lovers/lover'.$TEXT['templates_extension'],0,1);
				
				// Fetch data
				while($row = $result->fetch_assoc()) {
			        $rows[] = $row;
				}
				
				// Check for more results
				$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;	
                $reactions = getReactions($page_settings['reactions']);
				
				foreach($rows as $row) {

					// Generate permissions
					list($available,$following,$pri) = $this->getPermissions($user['idu'],$row['idu'],$row['p_private']);
					
					// Set data
					$TEXT['temp-user_id'] = $row['idu'];
					$TEXT['temp-user_profile_title'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));
					$TEXT['temp-user_image'] = $this->getImage($user['idu'],$row['idu'],$row['p_image'],$row['image']);
					$TEXT['temp-user_name'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
					
					if(isset($reactions[$row['type']][2]))  {
						$TEXT['temp-ico2'] = '<img class="img-20 s" src="/themes/'.$TEXT['theme'].'/'.$reactions[$row['type']][2].'">';
					} else {
						$TEXT['temp-ico2'] = '';
					}
					
					$TEXT['temp-time'] = addStamp($row['time'],2);

				
					// Add user to list
					$content .= display('',$lk_tpl);
					
					// Set last processed id
				    $TEXT['temp-last'] = $row['id'];
					
				}
				
				// Add load more function if set					
				if($loadmore) {
					$TEXT['temp-post_id'] = $post['post_id'];
				    $content .= display($t_src.'/elements/lovers/load_more'.$TEXT['templates_extension']);
				} elseif(!$recent) {
				    $content .= showBox($TEXT['_uni-No_more-lovers']);	
				}	
			
			// None loved this post	
			} else {
				return (!$recent) ? showBox($TEXT['_uni-None-loves']):'';
			}
			
			// Last privacy check and RETURN content
			if($post_available == 1) {
				return $content;
			} else {
				return bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf2']);      
			}
		}    		
	}	
	
	function getComments($from,$post_id,$user,$latest,$fetched = NULL) {         // Return Comments
        global $TEXT;

		// Get post
		$post = $this->getPostByID($post_id);

		// If post exists
		if($post) {
			
			// If posted on page
			if(in_array($post['posted_as'],array(0,2))) {
				
				$image_it = array($user['image'].'&fol=a','loadProfile('.$user['idu'].');');
				
			} else {
			
			    // Get page
				$get_page = $this->getPage($post['posted_at']);
			
				// If owner allow hime comment as page
				if($get_page['page_owner'] == $user['idu']) {
			        
					$image_it = array($get_page['page_icon'].'&fol=pa','loadPage('.$get_page['page_id'].',1,1);');
					
				// Else check further
				} else {
				
					// Get user role on page
					$get_page_role = $this->getPageRole($user['idu'],$post['posted_at']);
					
					// If is above analyst
					if(isset($get_page_role['page_role']) && $get_page_role['page_role'] > 2) {
						$image_it = array($get_page['page_icon'].'&fol=pa','loadPage('.$get_page['page_id'].',1,1);');
					}
				}
			}
			
			// Reset
			$content = '';
			
			// Set limit
			$limit = $this->db->real_escape_string($this->settings['comments_per_widget'] + 1);
			
			// Add start up
			$from = ($from > 0) ? 'AND post_comments.id < \''.$this->db->real_escape_string($from).'\'':'';	
		
			// Escape (variables | strings) for SQL Query
			$post_id_esc = $this->db->real_escape_string($post_id);
			
			// Confirm post privacy
			$available = $this->isPostAvailable($post,$this->getUserByID($post['post_by_id']),$user['idu']);
			
			// Select data
			$result = $this->db->query("SELECT * FROM post_comments, users WHERE post_comments.post_id = $post_id_esc AND post_comments.by_id = users.idu $from ORDER BY post_comments.id DESC LIMIT $limit");

           	$rows = array();
            
			// If comment exists
			if(!empty($result) && $result->num_rows !== 0) {
	   			
				while($row = $result->fetch_assoc()) {
			        $rows[] = $row;
				}
				
				// Check for more results
				$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;
				
				$comment_template = display(templateSrc('/elements/comment/comment'),0,1);
				
				foreach($rows as $row) {
				
				    // If comment by a page
				    if($row['commented_as'] == 1) {
						
						$get_page = $this->getPage($row['by_id']); 
						
						$TEXT['temp-user_name'] = fixText(115,$get_page['page_name']);	
						
						$TEXT['temp-user_img'] = $get_page['page_icon'].'&fol=pa';
						
						$TEXT['temp-onclick'] = 'loadPage('.$get_page['page_id'].',1,1);';
						
						$TEXT['temp-href'] = '/page/'.$get_page['page_id'];
						
						$block_reply = 1;
					
					} else {
						
						$get_user = $this->getuserByID($row['by_id']);
						
						$TEXT['temp-user_name'] = fixName(115,$get_user['username'],$get_user['first_name'],$get_user['last_name']);
						
						$TEXT['temp-user_img'] = $this->getImage($user['idu'],$get_user['idu'],$get_user['p_image'],$get_user['image']).'&fol=a';
						
						$TEXT['temp-onclick'] = 'loadProfile('.$get_user['idu'].');';
						
						$TEXT['temp-href'] = '/'.$get_user['idu'];
						
						$block_reply = 0;
						
					}
					
					$TEXT['temp-comment_id'] = $row['id'];
					$TEXT['temp-comment'] = $this->parseText(protectXSS($row['comment_text']));
					$TEXT['temp-comment_btns'] = $this->getCommentFunctions($row,$user,$block_reply);
					$TEXT['temp-time'] = addStamp($row['time'],2);
					$content = display('',$comment_template).$content;
				    
                    // Last Processed id
					$last = $row['id'];
					
				}
				
				// Add load more if set
				if(isset($loadmore)) {
					$content = '<div id="p1234za4w-'.$row['post_id'].'" class="center full noflow clear padding-20" onclick="$(\'#p1234za4w-'.$row['post_id'].'\').remove();loadPreviousComments('.protectXSS($post_id).','.$last.')">
									<span id="comments-count-'.$row['post_id'].'" class="right h4 b2 tin-light-font-only">30 of 33</span>
									<span class="left pointer h4 b1 tin-light-font">'.$TEXT['_uni-Load_pre_cmm'].'</span>
								</div>
								<script>if($("#comments-'.$row['post_id'].' > div").length) {$("#comments-count-'.$row['post_id'].'").html($("#comments-'.$row['post_id'].' > div").length - 1 + " '.sprintf($TEXT['_uni-addOff'],$post['post_comments']).'")}</script>'.$content;
				}		
			}
				
			// Last privacy check and RETURN content
			if($available == 1) {
				return $content;
			} else {
				return bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf2']);      
			}
		}    		
	}	
	
	function getPostText($post_id,$user) { 	                                     // Return post edit form
	    global $TEXT;
	    
		// fetch post
		$post = $this->getPostByID($post_id);
		
		// if post exists
		if($post) {
			
			// Escape variables for MySQL Query
			$post_id_esc = $this->db->real_escape_string($post['post_id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			
			// Privacy check
            if($post['post_by_id'] == $user['idu']) {				
				$TEXT['temp-post_id'] = $post_id_esc;
				$TEXT['temp-post_text'] = protectXSS($post['post_text']);		
				return display(templateSrc('/elements/edit_post/edit_wizard'));
			} else {
				// Post not available
				return $TEXT['_uni-doesnt-exists-post'];
			}			
		} else {
			// Post doesn't exists
			return $TEXT['_uni-doesnt-exists-post'];
		}			
	}
	
	function getFeeds($from,$user_id,$latest_feeds = NULL,$single_user = NULL,$one=NULL,$filter='',$js='') {     // Return list of posts
        global $TEXT;
 
		// Fetch user
		$user = $this->getUserByID($user_id);

		// Fetch current logged user
		$c_user = $this->getUser();

		// If requested user doesn't exists
		if(empty($user['idu'])) return showError($TEXT['lang_error_script1']);

		// Set limit
		$limit = ($latest_feeds) ? 2 : $this->settings['posts_per_page'] + 1;
		
		// Set type
		$si_it = ($one) ? 1 : NULL;
		
		// Set results start up
		if($from == 0) {
			
			// Feeds type for single user or home page
			$from = ($latest_feeds || $single_user == 1) ? 'AND user_posts.post_by_id = \''.$this->db->real_escape_string($user['idu']).'\'' : '';

		} elseif(is_numeric($from) && $from > 0 && $single_user == 1) {
			
			// Feeds type for single user
			$from = 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\''.'AND user_posts.post_by_id = \''.$this->db->real_escape_string($user['idu']).'\'';
		
		} elseif(is_numeric($from) && $from > 0 ) {
			
			// Set start up point for news feeds
			$from = 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\'';
		
		}
		
		// Add list 
		if(empty($this->followings)) {
			
			// Add current user posts to feeds
			$people = $user['idu'];

			// Return with no feeds message
			if($single_user !== 1 && $user['posts'] == 0) {
				return '<script>trendingFeeds("START");</script>'; 
			}

		} else {
			
			// List of people followed by user
			$people = implode(',', $this->followings).','.$user['idu'];
			
		}

		// Select available posts
		$result = $this->db->query(sprintf("SELECT * FROM `user_posts`, `users` WHERE `user_posts`.`post_by_id` IN (%s) $filter AND `user_posts`.`posted_as` = '0' $from  AND `user_posts`.`post_by_id` = `users`.`idu` ORDER BY `user_posts`.`post_id` DESC LIMIT %s", $people, $limit));

		// Reset
		$rows = array();$messages = '';$rows_cnt = $result->num_rows;

		// set conditions
		if(!empty($result) && $rows_cnt !== 0) {
			
			$sponsored_ads = ($latest_feeds || $single_user == 1) ? '' : getAds($this->db,$user,'1,2,3','feeds');
			
			$inject_id = mt_rand(0,$rows_cnt-1);$inj = 0;

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}

			// Add load more button if available
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;

			$post_template = display(templateSrc('/post/user_post'),0,1);
			
			// From each row generate post
			foreach($rows as $post_row) {
				
				if($inj == $inject_id) {
					$messages .= $sponsored_ads;
				}
	
			    // If a shared post
			    if($post_row['post_type'] == 6) {
					
					$messages .= $this->getPost($post_row['post_content'],$user,$post_row['post_id'],$this->getUserByID($post_row['post_by_id']));
					
					// Last processed id
					$from = $post_row['post_id'];
					
				} else {
					
					// Get profile picture
					$TEXT['temp-user_img'] = $this->getImage($c_user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']);			
				
					// Add profile photos and cover photos privacy
					if($single_user == 1) {
					
						if($post_row['post_type'] == 3) {
						
							// Check whether image is available
							$pictyure = ($TEXT['temp-user_img'] !== 'private.png') ? 1 : NULL;						
						
						} elseif($post_row['post_type'] == 5) {
						
	                    	// Check whether cover is available
					    	$pictyure = ($this->getImage($c_user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']) == 'private.png') ? NULL : 1;					
					
						} else {					
							$pictyure = 1;			
						}			
				
					} else {
						$pictyure = 1;		
					}

					// List post buttons and details
					list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $this->listFunctions($post_row,$c_user);
				
					// Parse post text remove all smiles
					$TEXT['temp-pt_ns'] = protectXSS($this->parseText($post_row['post_text'],1));
		
					// Get post type and content
			    	list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $this->getPostContent($post_row,$post_row['post_id'],$pictyure,$si_it,$this->parseText(protectXSS($post_row['post_text'])));		
				
					// Post heading
					list($TEXT['temp-heading'],$TEXT['temp-group_heading']) = $this->getPostHeading($post_row['post_type'],$post_row['posted_as'],$post_row['posted_at'],$post_row['post_extras'],'',$post_row['post_content'],$post_row['gender'],$post_row['post_id']);
				
					// Post id
					$TEXT['temp-id'] = $post_row['post_id'];

					// Posted time
					$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
					$TEXT['temp-username_c'] = $c_user['username'];
					$TEXT['temp-user_img_c'] = $c_user['image'];
					$TEXT['temp-user_id_c'] = $c_user['idu'];
					$TEXT['temp-user_id'] = $post_row['idu'];
					$TEXT['temp-username'] = $post_row['username'];
					$TEXT['temp-user_name'] = fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']);
					$TEXT['temp-userttl'] = sprintf($TEXT['_uni-Profile_load_text2'],$TEXT['temp-user_name']);
				
					// Add post in list
					$messages .= display('',$post_template);
				
					// Last processed id
					$from = $post_row['post_id'];
				
				}
				
				$inj++;
				
			}
			
			// If more results available add function
			if($loadmore && (!isset($latest_feeds))) {
				
				if($js) {
					$add_func = sprintf($js,$from,$user['idu']);
				} else {
					$function = ($single_user == 1) ? 'load_more_profile_feeds' : 'load_more_feeds';
					$add_func = $function .'('.$from.','.$user['idu'].');';
				}
				
				$messages .= addLoadmore($this->settings['inf_scroll'],'',$add_func);
				
			// Else add no more posts message
			} elseif(!isset($latest_feeds)) {
				$messages .= closeBody($TEXT['_uni-No_more_feeds']);		
			}
				
			// PRIVACY ADDED
			if($user['p_posts'] == 1 && !in_array($user['idu'],$this->followings)) {			
				if($c_user['idu'] !== $user['idu']) {
					return bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf2']); 		
				} else {
					return $messages;
				}
			} else {
				return $messages;
			}					
		} else {
			return bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-No_more_feeds'],$TEXT['_uni-No_more_feeds_ttl']);			
		}	    		
	}
	
	function getPageFeeds($user,$from,$page_ids,$latest_feeds=NULL,$single_group=NULL,$one=NULL,$filter=0) { // Return list of posts for groups
        global $TEXT;

		// Set limit
		$limit = ($latest_feeds) ? 2 : $this->settings['posts_per_page'] + 1;
			
		// Set results start up
		if($from == 0) {
			
			// Feeds type for single user or home page
			$from = ($latest_feeds) ? 'AND user_posts.post_by_id = \''.$this->db->real_escape_string($user['idu']).'\'' : '';

		} elseif (is_numeric($from) && $from > 0 ) {
			
			// Set start up point for news feeds
			$from = 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\'';
		
		}
		
		// Only Photos
		if($filter == 1) {
			$add_filter = 'AND `user_posts`.`post_type` = \'1\' ';
		
		// Only Videos
		} elseif($filter == 2) {
			$add_filter = 'AND `user_posts`.`post_type` = \'4\' ';
		
		// All
		} else {
			$add_filter = '';
		}
		
	    // Select available posts
		$result = $this->db->query(sprintf("SELECT * FROM `user_posts`, `pages`, `users` WHERE `user_posts`.`posted_at` IN (%s) $from AND `user_posts`.`posted_as` = '1' AND `user_posts`.`posted_at` = `pages`.`page_id` $add_filter AND `user_posts`.`post_by_id` = `users`.`idu` ORDER BY `user_posts`.`post_id` DESC LIMIT %s", $page_ids, $limit));

		// Reset
		$rows = array();$messages = '';
		
		// Set type
		$si_it = ($one) ? 1 : NULL;
		
		// set conditions
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}

			// Add load more button if available
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;

			$post_template = display(templateSrc('/post/page_post'),0,1);
			
			// From each row generate post
			foreach($rows as $post_row) {

			    $TEXT['temp-page_link'] = ($post_row['page_username']) ? $post_row['page_username'] : $post_row['page_id'];
				
				// List post buttons and details
				list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $this->listFunctions($post_row,$user);
				
				// Parse post text remove all smiles
				$TEXT['temp-pt_ns'] = protectXSS($this->parseText($post_row['post_text'],1));
				
				// Get post type and content
			    list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $this->getPostContent($post_row,$post_row['post_id'],1,$si_it,$this->parseText(protectXSS($post_row['post_text'])));		
				
				// Post heading
				$TEXT['temp-heading'] = '<a class="h6 b2 dark-font" href="/page/'.$TEXT['temp-page_link'].'" onclick="loadPage('.$post_row['page_id'].',1,1);return false;">'.$post_row['page_name'].' '.$this->verifiedBatch($post_row['page_verified']).'</a>';
				
				// Post id
				$TEXT['temp-id'] = $post_row['post_id'];
				$TEXT['temp-page_id'] = $post_row['page_id'];
				$TEXT['temp-page_icon'] = $post_row['page_icon'];

				// Posted time
				$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
				
				// Add post in list
				$messages .= display('',$post_template);
				
				// Last processed id
				$last = $row['post_id'];
			}

			// If more results available add function
			if($loadmore && (!isset($latest_feeds))) {
				
				$ids = ($single_group) ? $page_ids : 0;
				
				$messages .= addLoadmore($this->settings['inf_scroll'],'','pageFeeds('.$ids.','.$last.',6);');
				
			// Else add no more posts message
			} elseif(!isset($latest_feeds)) {
				$messages .= closeBody($TEXT['_uni-No_more_feeds']);		
			}
		
			return $messages;
		} else {
			return bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-No_more_feeds'],$TEXT['_uni-No_more_feeds_ttl']);			
		}	    		
	}
	
    function getGroupFeeds($user,$from,$group_ids,$latest_feeds=NULL,$single_group=NULL,$one=NULL) { // Return list of posts for groups
        global $TEXT;

		// Set limit
		$limit = ($latest_feeds) ? 2 : $this->settings['posts_per_page'] + 1;
			
		// Set results start up
		if($from == 0) {
			
			// Feeds type for single user or home page
			$from = ($latest_feeds) ? 'AND user_posts.post_by_id = \''.$this->db->real_escape_string($user['idu']).'\'' : '';

		} elseif (is_numeric($from) && $from > 0 ) {
			
			// Set start up point for news feeds
			$from = 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\'';
		
		}
		
	    // Select available posts
		$result = $this->db->query(sprintf("SELECT * FROM `user_posts`, `groups`, `users` WHERE `user_posts`.`posted_at` IN (%s) $from AND `user_posts`.`posted_as` = '2' AND `user_posts`.`posted_at` = `groups`.`group_id` AND `user_posts`.`post_by_id` = `users`.`idu` ORDER BY `user_posts`.`post_id` DESC LIMIT %s", $group_ids, $limit));

		// Reset
		$rows = array();$messages = '';
		
		// Set type
		$si_it = ($one) ? 1 : NULL;
		
		// set conditions
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}

			// Add load more button if available
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;

			$post_template = display(templateSrc('/post/user_post'),0,1);

			// From each row generate post
			foreach($rows as $post_row) {

			    $TEXT['temp-id'] = $post_row['post_id'];
				
				// Get profile picture
				$TEXT['temp-user_img'] = $this->getImage($user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']);			
				
				// Add profile photos and cover photos privacy
				if($single_group == 1) {
					
					if($post_row['post_type'] == 3) {
						
						// Check whether image is available
						$pictyure = ($TEXT['temp-user_img'] !== 'private.png') ? 1 : NULL;						
						
					} elseif($post_row['post_type'] == 5) {
						
	                    // Check whether cover is available
					    $pictyure = ($this->getImage($user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']) == 'private.png') ? NULL : 1;					
					
					} else {					
						$pictyure = 1;			
					}			
				
				} else {
					$pictyure = 1;		
				}

				// List post buttons and details
				list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $this->listFunctions($post_row,$user);
				
				// Parse post text remove all smiles
				$TEXT['temp-pt_ns'] = protectXSS($this->parseText($post_row['post_text'],1));
				
				// Get post type and content
			    list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $this->getPostContent($post_row,$post_row['post_id'],$pictyure,$si_it,$this->parseText(protectXSS($post_row['post_text'])));		
				
				// Post heading
				$TEXT['temp-heading'] = (!$single_group) ? ' <i class="fa fa-play margin-0-5 h3 b1 tin-light-font-only"></i> <a class="h6 b3 theme-font" href="/group/'.$post_row['group_id'].'" onclick="loadGroup('.$post_row['group_id'].',1,1);return false;" >'.$post_row['group_name'].'</a>' : '';
				$TEXT['temp-group_heading'] = '';
		
				$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
				$TEXT['temp-username_c'] = $user['username'];
				$TEXT['temp-user_img_c'] = $user['image'];
				$TEXT['temp-user_id_c'] = $user['idu'];
				$TEXT['temp-user_id'] = $post_row['idu'];
				$TEXT['temp-username'] = $post_row['username'];
				$TEXT['temp-user_name'] = fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']);
				$TEXT['temp-userttl'] = sprintf($TEXT['_uni-Profile_load_text2'],$TEXT['temp-user_name']);

				// Add post in list
				$messages .= display('',$post_template);
				
				// Last processed id
				$last = $row['post_id'];
			}

			// If more results available add function
			if($loadmore && (!isset($latest_feeds))) {
				
				$ids = ($single_group) ? $group_ids : 0;
				
				$messages .= addLoadmore($this->settings['inf_scroll'],'','groupFeeds('.$ids.','.$last.',6);');
				
			// Else add no more posts message
			} elseif(!isset($latest_feeds)) {
				$messages .= closeBody($TEXT['_uni-No_more_feeds']);		
			}
		
			return $messages;
		} else {
			return bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-No_more_feeds'],$TEXT['_uni-No_more_feeds_ttl']);			
		}	    		
	}
	
	function getProfileTop($user_id,$current_user = NULL,$array = 0) {             // Return Profile page
		global $TEXT;
		
		// Fetch target user
		$user = $this->getUserByID($user_id);
		
		// Get template SRC
		$t_src = templateSrc('SRC',1);
		
		// If user exists
		if(!empty($user['idu'])) {
			
			// Reset
			$row = array();
			$TEXT['temp-updateable'] = $TEXT['temp-reporter'] = $TEXT['temp-onclick1'] = $TEXT['temp-onclick2'] = '';

			// XSS protection
			$TEXT['temp-username'] = $row['username'] = trim(protectXSS($user['username']));
			$row['first_name'] = trim(protectXSS($user['first_name']));
			$row['last_name'] = trim(protectXSS($user['last_name']));
			$row['verified'] = trim(protectXSS($user['verified']));
			$row['followers'] = trim(protectXSS($user['followers']));
			$row['image'] = trim(protectXSS($user['image']));
			$row['cover'] = trim(protectXSS($user['cover']));
			$TEXT['temp-user_id'] = $row['idu'] = trim(protectXSS($user['idu']));
			$row['p_private'] = $user['p_private'];

			// Get owner profile picture
			$TEXT['temp-profile_picture'] = $this->getImage($current_user['idu'],$row['idu'],$user['p_image'],$row['image']);
			
			// Get owner cover photo
			$TEXT['temp-cover_photo'] = $this->getImage($current_user['idu'],$row['idu'],$user['p_cover'],$row['cover']);
			
			// If reposition applied
			if (strpos($TEXT['temp-cover_photo'], 'rep_') !== false || strpos($TEXT['temp-cover_photo'], 'fault') !== false) {
    			// Leave
			// If  not
			} else {

			    $src = explode('themes',$t_src);
				
				// Check cover reposition file
				$TEXT['temp-cover_photo'] = (file_exists($src[0].'/uploads/profile/covers/rep_'.$TEXT['temp-cover_photo'])) ? $TEXT['temp-cover_photo'] : $TEXT['temp-cover_photo'].'?fault';
				
			}
			
			// Add navigations if same user is logged
			$TEXT['temp-update_nav'] = ($current_user['idu'] == $row['idu']) ? 'updateNavigation(\'p\');' : 'updateNavigation(6473);';		
			
			$TEXT['temp-esc_user_name'] = $this->db->real_escape_string(fixName(25,$row['username'],$row['first_name'],$row['last_name']));

			// Import privacy			
			list($available,$following,$private) = $this->getPermissions($current_user['idu'],$user_id,$row['p_private']);

			// Relation button
            $TEXT['temp-follow_btn'] = (Blocker::IsTypeBlocked(Blocker::GetBlock($current_user['idu'], $row['idu']), 'follow')) ? "" : $this->getRelationButton($following,$user_id,$private);
		
			// Update browser title and history
			$TEXT['temp-browser'] = display($t_src.'/user/user_main/title_js'.$TEXT['templates_extension']);

			// Add update photos buttons if target user is logged user
			if($row['idu'] == $current_user['idu']) {
				$TEXT['temp-updateable'] = display($t_src.'/user/user_main/picture_update'.$TEXT['templates_extension']);
				$TEXT['temp-blockable'] = "";
			// Else allow reporting and quick messaging 
			} else {
				$TEXT['temp-blockable'] = '<a onclick="profileLoadBlock('.$row['idu'].', 0);javascript:void(0);" class="padding-10 tin-light-font drop-item" href="javascript:void(0);">'.$TEXT['_uni-Block_User'].'</a>';
				$TEXT['temp-reporter'] = 1;
			}

			// Add report functionality if available
			$TEXT['temp-reporterable'] = (!empty($TEXT['temp-reporter']))? '<a onclick="report('.$row['idu'].',1);javascript:void(0);" class="padding-10 top-divider tin-light-font drop-item" href="javascript:void(0);">'.$TEXT['_uni-Report'].'</a>' :'';

			$TEXT['temp-messageable'] = ($current_user['idu'] !== $row['idu']) ? '<a onclick="quickMessage('.$row['idu'].',3);javascript:void(0);" class="padding-10 bottom-divider tin-light-font drop-item" href="javascript:void(0);">'.$TEXT['_uni-Message'].'</a>' :'';
	
			// Fetch Intro
			$fetched_intro = $this->generateAbout($user,$following);
			$TEXT['temp-about_section'] = ($array) ? '' : $fetched_intro;
			$TEXT['temp-verified'] = $this->verifiedBatch($row['verified']);
			$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
			
			// Build profile page
			$content = display($t_src.'/user/user_main/user_top'.$TEXT['templates_extension']);

			return ($array) ? array($content,$fetched_intro) : $content;
		
		} else {
			
			// If target user doesn't exists
			return showError($TEXT['lang_error_script1']);
		
		}			
	}
	
	function getGroupLog($user,$group_id,$from,$limet) {                                 // Return Group logs
		global $TEXT,$page_settings;
		
		// Reset
		$rows = $update = array(); $logs = $prev = '';
	
		// Set starting point
		$from = (is_numeric($from) && $from > 0 ) ? 'AND group_logs.id < \''.$this->db->real_escape_string($from).'\'' : ''; 
		
		// Set limit
		$limit = $limet + 1;

		// Escape variables for MySQl Query
		$group_id_esc = $this->db->real_escape_string($group_id);

		// Select logs
		$results = $this->db->query("SELECT * FROM group_logs , users WHERE group_logs.user_id = users.idu AND group_logs.group_id = $group_id_esc $from ORDER BY group_logs.id DESC LIMIT $limit ;") ;
	
		// If logs exists
		if(!empty($results) && $results->num_rows) {
			
			// fetch logs
			while($row = $results->fetch_assoc()) {
			    $rows[] = $row;
			}

			// Check whether more logs exists 
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;

	        $lg_tpl = display(templateSrc('/logs/group/log'),0,1);
			
			foreach($rows as $row) {
		   		
				$target = ($row['target_id']) ? $this->getUserByID($row['target_id']) : 0;

				$target_name = ($target) ? '<span class="h6 b2 theme-font"><a title="'.sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$target['username'],$target['first_name'],$target['last_name'])).'" href="/'.$target['idu'].'" onclick="aOverlow();loadProfile('.$target['idu'].');return false;">'.fixName(35,$target['username'],$target['first_name'],$target['last_name']).'</a></span>':'';
	
				if($row['type'] == 1) {		
					$content = sprintf($TEXT['_uni-grp_log_1'],$target_name);
				} elseif($row['type'] == 2) {
					$content = sprintf($TEXT['_uni-grp_log_2'],$target_name);
				} elseif($row['type'] == 3) {
					$content = sprintf($TEXT['_uni-grp_log_3'],$target_name);
				} elseif($row['type'] == 4) {
					$content = sprintf($TEXT['_uni-grp_log_4'],$target_name);
				} elseif($row['type'] == 5) {
					$content = $TEXT['_uni-grp_log_5'];
				}
				
				// Add sepration system to dates
				$newDateTime = date('Y-m-d', strtotime($row['time']));
				
				if ($newDateTime != $prev) {
                    $add_seprate = addStamp($row['time'],0,'_uni-U-');				
					$logs .= '<div class="block-box padding-10 h2 b4 tin-light-font-only bottom-divider">'.$add_seprate.'</div>';
					$prev = $newDateTime;
				}
				
				$TEXT['temp-user_id'] = $row['user_id'];
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_35'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-content'] = $content;
				$TEXT['temp-user_image'] = $this->getImage($user['idu'],$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-time'] = date('h:i A', strtotime($row['time']));
				
				// Add log to list
				$logs .= display('',$lg_tpl);
				
				// Last processed id
                $from = $row['id'];			
			}
			
			// Add load more function if exists
			$logs .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'groupLog('.$group_id.','.$from.',6);') : closeBody($TEXT['_uni-No_more-logs'],1);
			
			// Return logs
			return $logs;
			
		} else {
			// Else no logs yet
			return bannerIt('no-results',$TEXT['_uni-No_logs-all'],$TEXT['_uni-No_logs-all-inf']);	
		}
	}
	
	function getPageLog($page_id,$from,$limet) {                                   // Return page logs
		global $TEXT,$page_settings;
		
		$rows = $update = array();
		
		// Set starting point
		$from = (is_numeric($from) && $from > 0 ) ? 'AND page_logs.id < \''.$this->db->real_escape_string($from).'\'' : ''; 
		
		// Set limit
		$limit = $limet + 1;
		
		// Escape variables for MySQl Query
		$page_id_esc = $this->db->real_escape_string($page_id);

		// Select logs
		$results = $this->db->query("SELECT * FROM page_logs , users WHERE page_logs.user_id = users.idu AND page_logs.page_id = $page_id_esc $from ORDER BY page_logs.id DESC LIMIT $limit ;") ;
	
		// If logs exists
		if(!empty($results) && $results->num_rows) {
			
			$lg_tpl = display(templateSrc('/logs/page/log'),0,1);
			
			// fetch logs
			while($row = $results->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Reset
			$logs = $prev = '';
		
			// Check whether more logs exists 
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
	
			foreach($rows as $row) {
		   		
				$target = (in_array($row['type'],array(1,2)) && $row['target_id']) ? $this->getUserByID($row['target_id']) : 0;

				$target_name = ($target) ? '<span class="h6 b2 theme-font"><a title="'.sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$target['username'],$target['first_name'],$target['last_name'])).'" href="/'.$target['idu'].'" onclick="aOverlow();loadProfile('.$target['idu'].');return false;">'.fixName(35,$target['username'],$target['first_name'],$target['last_name']).'</a></span>':'';
	
				if($row['type'] == 1) {		
					$content = sprintf($TEXT['_uni-page_log_1'],$target_name);
				} elseif($row['type'] == 2) {
					$content = sprintf($TEXT['_uni-page_log_2'],$target_name);
				} elseif($row['type'] == 3) {
					
					if($row['target_id'] == 1) {
						$add_content = $TEXT['_uni-Photo'];
					} elseif($row['target_id'] == 4) {
						$add_content = $TEXT['_uni-Video'];
					} else {
						$add_content = $TEXT['_uni-Post'];
					}
					
					$content = sprintf($TEXT['_uni-page_log_3'],$add_content);
					
				} else {
					$index = '_uni-page_log_'.$row['type'];
					
					$content = $TEXT[$index];
				}
				
				// Add sepration system to dates
				$newDateTime = date('Y-m-d', strtotime($row['time']));
				
				if ($newDateTime != $prev) {
                    $add_seprate = addStamp($row['time'],0,'_uni-U-');
					$logs .= '<div class="block-box padding-10 h2 b4 tin-light-font-only bottom-divider">'.$add_seprate.'</div>';
					$prev = $newDateTime;
				}
				
				$TEXT['temp-user_id'] = $row['user_id'];
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_35'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-content'] = $content;
				$TEXT['temp-user_image'] = $this->getImage($user['idu'],$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-time'] = date('h:i A', strtotime($row['time']));
				
				// Add log to list
				$logs .= display('',$lg_tpl);
		
				// Last processed id
                $from = $row['id'];			
			}
			
			// Add load more function if exists
			$logs .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'pageLog('.$page_id.','.$from.',6);') : closeBody($TEXT['_uni-No_more-logs'],1);
			
			// Return logs
			return $logs;
			
		} else {
			// Else no logs yet
			return bannerIt('no-results',$TEXT['_uni-No_logs-all'],$TEXT['_uni-No_logs-all-inf']);	
		}
	}
	
	function getPageTop($page_id,$current_user = NULL,$array = 0,$pge=NULL) {      // Return page
		global $TEXT;
		
		// Fetch page user(ROLE)
		$page_role = $this->getPageRole($current_user['idu'],$page_id);
		
		// Fetch page user
		$page_user = $this->getPageUser($current_user['idu'],$page_id);
		
		// Get page
		$page = ($pge) ? $pge : $this->getPage($page_id);

		// Set page Role
		$role = ($page_role['page_role']) ? $page_role['page_role'] : 0;	
		
		// Check whether liked
		$liked = $this->isLiked($page['page_id'],$current_user['idu']);
		
		// Check whether following
		$following = (in_array($page['page_id'],explode(',',$current_user['page_feeds']))) ? 1 : 0;

		$TEXT['temp-unique_id'] = md5(mt_rand(100,99999).time());
		
		$t_src = templateSrc('SRC',1);
		
		// Get buttons
		$TEXT['temp-action_btns'] = $this->getPageButtons($TEXT['temp-unique_id'],$following,$liked,$role,$page_id);

		// Get access type
		$page_access = ($page['page_username']) ? $page['page_username'] : $page['page_id']; 
		
		// Update browser title and history
		$TEXT['temp-browser_js'] = '<script>document.title = \''.$this->db->real_escape_string(fixText(25,$page['page_name'])).'\';					
						if(!isIE()) {store(\'/page/'.$this->db->real_escape_string(protectXSS($page_access)).'\');}
						</script>';

		$TEXT['temp-onclick'] = $TEXT['temp-updateable'] = $TEXT['temp-settings'] = $TEXT['temp-activity_log'] = $TEXT['temp-update_cover'] = $TEXT['temp-options'] = $d_nav_items = $feeds_button = '';
		
		$TEXT['temp-page_id'] = $page['page_id'];
		$TEXT['temp-page_cover'] = $page['page_cover'];
		
		// If reposition applied
		if (strpos($TEXT['temp-page_cover'], 'rep_') !== false || strpos($TEXT['temp-page_cover'], 'fault') !== false) {
	   	    // Leave
		} else {
			$src = explode('themes',$t_src);
	   	    $TEXT['temp-page_cover'] = (file_exists($src[0].'/uploads/pages/covers/rep_'.$TEXT['temp-page_cover'])) ? $TEXT['temp-page_cover'] : $TEXT['temp-page_cover'].'?fault';
		}	
		
		$TEXT['temp-page_icon'] = $page['page_icon'];
		$TEXT['temp-page_name'] = $page['page_name'];	
		$TEXT['temp-page_category'] = nameCategory($page['page_cat'],$page['page_sub_cat'],$this->getCategories(1),$this->db);

		// If user has a role
		if($role) {
			
			// Able to view activity logs
			$TEXT['temp-activity_log'] = '<a href="javascript:void(0);" onclick="pageLog('.$page_id.',0,5);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Activity_log'].'</a>';
			$TEXT['temp-timeline_ops'] .= '<a id="profile_view_tab_4" href="javascript:void(0);" onclick="pageLog('.$page_id.',0,5);" class="activable-tab-white light-x-font padding-10">'.$TEXT['_uni-Logs'].'</a>';		
			$d_nav_items .= '<div><a id="brz-add-GNav-10" class="btn-nav padding-5 h7 b1 dark-font-only" onclick="pageLog('.$page_id.',0,5);">&nbsp; '.$TEXT['_uni-Activity_log'].'<img class="right padding-5 img-25 preloader" style="display:none;" src="/themes/'.$TEXT['theme'].'/img/icons/loader-small.svg"></img></a></div>';
		    
			// If Editor or Admin
		    if($role > 3) {
				
				// Allow Cover update
				$TEXT['temp-update_cover'] = '<a href="javascript:void(0);" onclick="$(\'#uGp-f-2\').click();" class="padding-10 top-divider tin-light-font drop-item">'.$TEXT['_uni-Update_photo'].'</a>
				<a href="javascript:void(0);" onclick="$(\'#uGc-f-2\').click();" class="padding-10 bottom-divider tin-light-font drop-item">'.$TEXT['_uni-Update_cover'].'</a>';
				
				$TEXT['temp-updateable'] = display($t_src.'/page/page_main/picture_update'.$TEXT['templates_extension']);							
			
			    $TEXT['temp-onclick'] = 'onclick="$(\'#uGc-f-2\').click();"';
			    
				if($role > 4) {
				    $d_nav_items .= '<div><a id="brz-add-GNav-8" class="btn-nav padding-5 h7 b1 dark-font-only" onclick="loadPage('.$page['page_id'].',8,1);">&nbsp; '.$TEXT['_uni-Settings'].'<img class="right padding-5 img-25 preloader" style="display:none;" src="/themes/'.$TEXT['theme'].'/img/icons/loader-small.svg"></img></a></div>';
					$TEXT['temp-timeline_ops'] = '<a href="javascript:void(0);" onclick="loadPage('.$page['page_id'].',8,1);" class="activable-tab-white light-x-font padding-10">'.$TEXT['_uni-Edit'].'</a>'.$TEXT['temp-timeline_ops'];		
					$TEXT['temp-settings'] = '<a href="javascript:void(0);" onclick="loadPage('.$page['page_id'].',8,1);" class="padding-10 top-divider tin-light-font drop-item">'.$TEXT['_uni-Edit'].'</a>';
				}
			}
		}
		
		$TEXT['temp-options'] = '<a href="javascript:void(0);" onclick="pageFeeds('.$page['page_id'].',0,5,22);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Recent'].'</a>';
		$TEXT['temp-page_likes'] = readAble($page['page_likes']).' '.$TEXT['_uni-likes'];
		$TEXT['temp-page_follows'] = readAble($page['page_follows']).' '.$TEXT['_uni-following_this'];

		// Build page
		$content = display($t_src.'/page/page_main/page_top'.$TEXT['templates_extension']);

		$feeds = $this->getPageFeeds($current_user,0,$page_id,NULL,1);

		return ($array) ? array($page,$page_user,$page_role,$content,$feeds,$this->generatePageAbout($current_user,$page,$following,$role),$d_nav_items) : $content;
		
	}

	function getGroupTop($group_id,$current_user = NULL,$array = 0,$grp=NULL) {    // Return Group page
		global $TEXT;
		
		// Fetch group user
		$group_user = $this->getGroupUser($current_user['idu'],$group_id);
		
		$TEXT['temp-timeline_ops'] = '';
		
		// Get group
		$group = ($grp) ? $grp : $this->getGroup($group_id);

		// Is joined
		if($group_user['group_status'] == 1) {
			$joined = ($group_user['group_role'] == "2") ? 2 : 1;	
		} else {
			$joined = 0 ;
		}
		
		// Import privacy			
		list($available,$following,$private) = $this->getPermissions($current_user['idu'],$group['group_owner'],$group['group_privacy'],$joined,$group_user['group_status']);

		$t_src = templateSrc('SRC',1);
		
		// Get join button
		$TEXT['temp-join_button'] = $this->getGroupButton($following,$group_id,$private);
		
		// Get access type
		$group_access = ($group['group_username']) ? $group['group_username'] : $group['group_id']; 
		
		// Update browser title and history
		$TEXT['temp-browser_js'] = '<script>document.title = \''.$this->db->real_escape_string(fixText(25,$group['group_name'])).'\';					
						if(!isIE()) {store(\'/group/'.$this->db->real_escape_string(protectXSS($group_access)).'\');}
						</script>';
		
		$TEXT['temp-onclick'] = $TEXT['temp-updateable'] = $TEXT['temp-settings'] = $TEXT['temp-activity_log'] = $TEXT['temp-update_cover'] = $TEXT['temp-add_members'] = $TEXT['temp-options'] = '';
		
		$TEXT['temp-activity_log'] = ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group_user['p_activity'])) ? '<a href="javascript:void(0);" onclick="groupLog('.$group_id.',0,5);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Activity_log'].'</a>':'';
		
		$TEXT['temp-timeline_ops'] .= ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group_user['p_activity'])) ? '<a id="profile_view_tab_4" href="javascript:void(0);" onclick="groupLog('.$group_id.',0,5);" class="activable-tab-white light-x-font padding-10">'.$TEXT['_uni-Logs'].'</a>':'';
		
		$d_nav_items = ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group_user['p_activity'])) ? '<div><a id="brz-add-GNav-10" class="btn-nav padding-5 h7 b1 dark-font-only" onclick="groupLog('.$group_id.',0,5);">&nbsp; '.$TEXT['_uni-Activity_log'].'<img class="right padding-5 img-25 preloader" style="display:none;" src="/themes/'.$TEXT['theme'].'/img/icons/loader-small.svg"></img></a></div>':'';
		
		$TEXT['temp-update_cover'] = ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group_user['p_cover'])) ? '<a href="javascript:void(0);" onclick="$(\'#uGc-f-2\').click();" class="padding-10 tin-light-font drop-item bottom-divider">'.$TEXT['_uni-Update_cover'].'</a>':'';
        
		$TEXT['temp-group_id'] = $group['group_id'];
		
		$TEXT['temp-group_cover'] = $group['group_cover'];
		
		// If reposition applied
		if (strpos($TEXT['temp-group_cover'], 'rep_') !== false || strpos($TEXT['temp-group_cover'], 'fault') !== false) {
	   	    // Leave
		// If  not
		} else {

		    $src = explode('themes',$t_src);
			
	   	    // Check cover reposition file
	   	    $TEXT['temp-group_cover'] = (file_exists($src[0].'uploads/groups/rep_'.$TEXT['temp-group_cover'])) ? $TEXT['temp-group_cover'] : $TEXT['temp-group_cover'].'?fault';
		
		}
		
		$TEXT['temp-group_users'] = readable($group['group_users']);
		$TEXT['temp-group_name'] = $group['group_name'];
		$TEXT['temp-group_head'] = $this->getGroupHeading($group['group_privacy']);
		
		// Add update potos buttons
		if(!empty($TEXT['temp-update_cover'])) {
			$TEXT['temp-updateable'] = display($t_src.'/group/group_main/picture_update'.$TEXT['templates_extension']);
			$TEXT['temp-onclick'] = 'onclick="$(\'#uGc-f-2\').click();"';	
		}

        if($group_user['group_id']) {
		
		    if($group_user['f_feeds'] == 1) {  // Unfollow
			    $TEXT['temp-feeds_button'] = '<button id="fo3df" class="btn btn-medium btn-theme" onclick="bodyLoader(\'content-body\');ajaxProtocol(users_file,'.$group['group_id'].',0,5,0,0,0,0,0,0,0,0,0,0,1,0,0,98);">'.$TEXT['_uni-Following'].'';
			} else {                           // Follow
			    $TEXT['temp-feeds_button'] = '<button id="fo3df" class="btn btn-medium btn-light" onclick="bodyLoader(\'content-body\');ajaxProtocol(users_file,'.$group['group_id'].',0,4,0,0,0,0,0,0,0,0,0,0,1,0,0,98);" >'.$TEXT['_uni-Follow'].'';
		    }
			
		} else {
			$TEXT['temp-feeds_button'] = '';
		}
		
		if($following == 3) {
			$d_nav_items .= '<div><a id="brz-add-GNav-8" class="btn-nav padding-5 h7 b1 dark-font-only" onclick="loadGroup('.$group['group_id'].',8,5);">&nbsp; '.$TEXT['_uni-Manage'].'<img class="right padding-5 img-25 preloader" style="display:none;" src="/themes/'.$TEXT['theme'].'/img/icons/loader-small.svg"></img></a></div>';		
			$TEXT['temp-settings'] = '<a href="javascript:void(0);" onclick="loadGroup('.$group['group_id'].',8,5);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Manage'].'</a>
			             <a href="javascript:void(0);" onclick="deleteContent('.$group['group_id'].',11)" class="padding-10 tin-light-font drop-item bottom-divider">'.$TEXT['_uni-Delete_group'].'</a>';
		    $TEXT['temp-timeline_ops'] = '<a id="profile_view_tab_3" href="javascript:void(0);" onclick="loadGroup('.$group['group_id'].',8,5);" class="activable-tab-white light-x-font padding-10">'.$TEXT['_uni-Manage'].'</a>'.$TEXT['temp-timeline_ops'];
		}
	    
		if(($following == 1 && $group['group_approval_type'] == '1') || $following == 3) {
		    $add_mem2 = 1;
			$d_nav_items .= '<div><a id="brz-add-GNav-6" class="btn-nav padding-5 h7 b1 dark-font-only" onclick="groupRequests('.$group_id.',0,1,5,0,0);">&nbsp; '.$TEXT['_uni-Requests'].'<img class="right padding-5 img-25 preloader" style="display:none;" src="/themes/'.$TEXT['theme'].'/img/icons/loader-small.svg"></img></a></div>';
			$TEXT['temp-add_members'] = '<a href="javascript:void(0);" onclick="loadModal(1);loadGroup('.$group['group_id'].',2,32);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Add_members'].'</a>
		                    <a href="javascript:void(0);" onclick="loadModal(1);loadGroup('.$group['group_id'].',4,32);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Remove_members'].'</a>
							<a href="javascript:void(0);" onclick="groupRequests('.$group_id.',0,1,5,0,0);" class="padding-10 tin-light-font drop-item bottom-divider">'.$TEXT['_uni-Requests'].'</a>';
			$TEXT['temp-timeline_ops'] = '<a id="profile_view_tab_5" href="javascript:void(0);" onclick="groupRequests('.$group_id.',0,1,5,0,0);" class="activable-tab-white hide-small light-x-font padding-10">'.$TEXT['_uni-Requests'].'</a>'.$TEXT['temp-timeline_ops'];
		
		}	
		
		if($group['group_privacy'] !== '3') {
		    $TEXT['temp-options'] = '<a href="javascript:void(0);" onclick="groupFeeds('.$group['group_id'].',0,5);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Discussions'].'</a>
		                <a href="javascript:void(0);" onclick="groupMembers('.$group['group_id'].',0,5);" class="padding-10 tin-light-font drop-item">'.$TEXT['_uni-Members'].'</a>';
		}
		
		// Build group page
		$content = display($t_src.'/group/group_main/group_top'.$TEXT['templates_extension']);

		$feeds = ($available) ? $this->getGroupFeeds($current_user,0,$group_id,NULL,1): bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf3']);

		return ($array) ? array($group,$group_user,$content,$feeds,$this->generateGroupAbout($current_user,$group,$following,$add_mem2),$d_nav_items) : $content;
		
	}

	function getGroupMembers($group,$from,$group_user,$current_user) {             // Return group members
	    global $TEXT ;

		// Limit
		$limit = $this->settings['results_per_page'] + 1;

		// Set starting point
		if(is_numeric($from) && $from > 0 ) {
			$from = 'AND group_users.gid < \''.$this->db->real_escape_string($from).'\'';
			$header = $style = $style2 = '';			
		} else {
			$from = '';
		}

	    // Select users
		$result = $this->db->query(sprintf("SELECT * FROM `group_users`, `users` WHERE `group_users`.`user_id` = `users`.`idu` AND `group_users`.`group_status` != '2' AND `group_users`.`group_id` = '%s' $from ORDER BY `group_users`.`gid` DESC LIMIT %s", $group['group_id'], $limit));

	    // Reset
		$rows = array(); 
	    
		// If users exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Check for more results
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;
			
			// Add Header if available
			$people = '';
			
			$rights = ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group['group_owner'] == $current_user)) ? 1 : 0;

			$template = display(templateSrc('/user/search/in_box'),0,1);

			// Generate user from each row
		    foreach($rows as $row) {
				
				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user,$row['idu'],$row['p_private']);

				$role_type = ($row['user_id'] == $row['group_partner_id']) ?  $TEXT['_uni-Joined'] : $TEXT['_uni-Added'];
				
				$role_it = ($row['group_role'] == 2) ? $TEXT['_uni-Administration'] : $role_type;
				
				$admin = ($group['group_owner'] == $row['idu']) ? $TEXT['_uni-Founder'] : $role_it;
				
				$edit_btn = ($rights) ? '<span class="h6 dark-svg tin-light-font pointer" onclick="editGroupMemberPermissions('.$group['group_id'].','.$row['gid'].',0);"><img class="svg img-20" src="/themes/'.$TEXT['theme'].'/img/icons/buttons/cogs.svg"> </span>' : '';
				
				$people .= listMainUser($row['idu'],fixName(35,$row['username'],$row['first_name'],$row['last_name']),$row['username'],$this->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']),sprintf($TEXT['_uni-Profile_load_text2'],fixName(35,$row['username'],$row['first_name'],$row['last_name'])),$this->verifiedBatch($row['verified']),$admin,addStamp($row['time'],4),'',$edit_btn,'',$template);
			
				// Set last processed id	
				$last = $row['gid'];
		
			}
	
            // Add load more function if more results exists
			$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','groupMembers('.$group['group_id'].','.$last.',23);') : closeBox($TEXT['_uni-No_more_users']);
			
			return $people;	
			
		} else {			
			// Else no members
			return bannerIt('foll2'.mt_rand(1,4),$TEXT['_uni-No members'],$TEXT['_uni-No_more-mem-2']).'<script>$("#people-box-main").find(".block-title").remove();</script>';				   
		}	
	}
	
	function relatives($from,$view_id,$type = 1) {                                 // Return current user followers | followings page
		global $TEXT ;
		
		// Get current user
		$current_user = $this->getUserByID($view_id);
		
		// Limit
		$limit = $this->settings['results_per_page'] + 1;
		
		// Set starting point
		if(is_numeric($from) && $from > 0 ) {
			$from = 'AND idu < \''.$this->db->real_escape_string($from).'\'';
			$header = $style = $style2 = '';			
		} else {
			$from = '';
		}
		
		// Get list of users
		if($type == 1){
			$people = implode(',', $this->listFollowings($view_id));
			$empty_message = $TEXT['_uni-No-fol2'];
			$empty_message2 = $TEXT['_uni-No-fol2-inf'];
			$img = 'foll2';
		} else {
			$people = implode(',', $this->listFollowers($view_id));
			$empty_message = $TEXT['_uni-No-fol1'];
			$empty_message2 = $TEXT['_uni-No-fol1-inf'];
			$img = 'foll1';
		}
	
	    // Select users
		$result = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` IN (%s) $from ORDER BY `users`.`idu` DESC LIMIT %s", $people, $limit));

	    // Reset
		$rows = array(); 
	    
		// If users exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Check for more results
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;
			
			// Add Header if available
			$people = '';
			
			$template = display(templateSrc('/user/search/in_box'),0,1);

			// Generate user from each row
		    foreach($rows as $row) {
				
				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);

				// Generate accordion data according to permissions
				list($d1,$d2,$d3) = $this->genAccordData($following,$row);			
				
				// Count New posts
				$n_posts = ($following == 1) ? $this->numberNewPosts($row['idu'],$current_user['onfeeds']) : NULL ;

				// Add user to list
				$people .= listMainUser($row['idu'],fixName(15,$row['username'],$row['first_name'],$row['last_name']),$row['username'],$this->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']),sprintf($TEXT['_uni-Profile_load_text2'],fixName(35,$row['username'],$row['first_name'],$row['last_name'])),$this->verifiedBatch($row['verified']),$d1,$d2,$d3,$this->getRelationButton($following,$row['idu'],$pri),'',$template );
			
				// Set last processed id	
				$last = $row['idu'];
		
			}	
	
            // Add load more function if more results exists
			$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','load_more_relatives('.$last.','.$type.');') : closeBox($TEXT['_uni-No_more_users']);
			
			// Return accordions
			return $people;	
			
		} else {			
			// Else no users
			return bannerIt($img.mt_rand(1,4),$empty_message,$empty_message2).'<script>$("#people-box-main").find(".block-title").remove();</script>';				   
		}	
	}
	
	function listNotifData($row,$el = NULL){                                              // Generate Notification data
		global $TEXT;

		if($row['not_type'] == 1) {                                // New follower
			
			return array($TEXT['_uni-started_following_you'],'');
		
		} elseif($row['not_type'] == 2) {                          // New follow request

		    // Genrate actions
		    $actions =  '<div title="'.$TEXT['_uni-Allow_this_user_to_follow_you'].'" class="btn btn-small-inline btn-x-theme right" onclick="proccessRequest(\''.protectXSS($row['not_from']).'\',\''.$el.'\',11)">'.$TEXT['_uni-Confirm'].'</div>
			            <div title="'.$TEXT['_uni-Remove_request'].'" class="btn btn-small-inline btn-light" onclick="proccessRequest(\''.protectXSS($row['not_from']).'\',\''.$el.'\',10)">'.$TEXT['_uni-Not_now'].'</div>';
	
			return array($TEXT['_uni-wants_to_follow_you'],$actions);
			
		} elseif($row['not_type'] == 6 || $row['not_type'] == 7 || $row['not_type'] == 8 || $row['not_type'] == 9 || $row['not_type'] == 15) { // New mention!

		    // Select where user is mentioned
			if($row['not_type'] == 7) {
				$text = $TEXT['_uni-comment']; // Mentioned in a comment
			} elseif($row['not_type'] == 8) {  
				$text = $TEXT['_uni-photo'];   // Mentioned in a photo
			} elseif($row['not_type'] == 9) {  
				$text = $TEXT['_uni-video'];   // Mentioned in a video
			} elseif($row['not_type'] == 15) {  
				$text = $TEXT['_uni-track'];   // Mentioned in a track
			} else {
				$text = $TEXT['_uni-post'];    // Mentioned in a post
			}
			
			return array($TEXT['_uni-mentioned_you'].' <span class="h6 b3 theme-font"><a href="/view/'.$row['not_content_id'].'" onclick="aOverlow();loadPost('.protectXSS($row['not_content_id']).');return false;">'.$text.'</a></span>','');
			
		} elseif($row['not_type'] == 3) {                             // Request accepted
			
			return array($TEXT['_uni-accepted_your_follow_request'],'');
		
		} elseif($row['not_type'] == 11 || $row['not_type'] == 12) {  // Member request(add or remove) for chat
				
				$function = ($row['not_type'] == 11) ? 1 : 0;
     
	            $text = ($row['not_type'] == 11) ? $TEXT['_uni-wants_to_add'] : $TEXT['_uni-wants_to_remove'];
				
				$child_user = $this->getUserByID($row['not_content']);
				
				$child_form = $this->getChatFormByID($row['not_content_id'],$row['not_to']);
				
				// Genrate actions
		        $actions =  '<div class="btn btn-small-inline btn-x-theme right" onclick="notsEditMember(1,\''.$el.'\',\''.$row['id'].'\',\''.$row['not_content_id'].'\',\''.$row['not_content'].'\','.$function.');">'.$TEXT['_uni-Confirm'].'</div>
			                 <div class="btn btn-small-inline btn-light" onclick="notsEditMember(0,\''.$el.'\',\''.$row['id'].'\',\''.$row['not_content_id'].'\',\''.$row['not_content'].'\','.$function.');">'.$TEXT['_uni-Not_now'].'</div>';

	            $full_text = $text.'<span title="'.fixName(115,$child_user['username'],$child_user['first_name'],$child_user['last_name']).'" class="h6 b3 dark-font" ><a href="'.$row['not_content'].'" onclick="aOverlow();loadProfile('.$row['not_content'].');return false;"> '.fixName(35,$child_user['username'],$child_user['first_name'],$child_user['last_name']).' </a></span> 
									<span> '.$TEXT['_uni-in'].' </span> 
									<span title="'.fixText(100,$child_form['form_name']).'" class="h6 b3 theme-font" ><a href="#" onclick="aOverlow();loadChat('.$row['not_content_id'].');return false;">'.fixName(35,$child_form['form_name'],'','').'</a></span>';
	
                return array($full_text,$actions);
				
		} elseif($row['not_type'] == 10) {                            // Added to a chat
				
				$child_form = $this->getChatFormByID($row['not_content_id'],$row['not_to']);
				
				$full_text =  $TEXT['_uni-added_you_in'].' <span title="'.fixText(100,$child_form['form_name']).'" class="h6 b3 dark-font" ><a href="#" onclick="aOverlow();loadChat('.$row['not_content_id'].');return false;">'.fixText(35,$child_form['form_name']).'</a></span>';
				
				return array($full_text,'');
		
		} elseif($row['not_type'] == 5 || $row['not_type'] == 4) {    // New like or comment

			// Fetch post
			$post = $this->getPostByID($row['not_content_id']);
			
			// Add title
			$title_set = array("0" => "_uni-status_update","1" => "_uni-photo",	"2" => "_uni-video",	"3" => "_uni-profile_photo","4" => "_uni-shared_video","5" => "_uni-profile_cover","7" => "_uni-shared_track","8" => "_uni-shared_video", "9" => "_uni-shared_video", "10" => "_uni-shared_blog_post","13" => "_uni-shared_video","14" => "_uni-shared_video",);
			
			// Add type : photo status etc
			$t_type = (in_array($post['post_type'],array("1","2","3","5","4","8","7","9","10","13","0")) && $post['posted_as'] == 0) ? $TEXT[$title_set[$post['post_type']]] :$TEXT['_uni-post'] ;
			
			$extra = '';
			
			// Add post photos to notifications
			if(!empty($post['post_type'])) {

				if($post['post_type'] == 1) {         // New photo
					
					$images = explode(',',$post['post_content']);
					
					if(count($images) > 1) {
						$t_type = sprintf($TEXT['_uni-post_including'],count($images));
						$extra = $TEXT['_uni-photos'];
					}
					
					$path = '/thumb.php?src='.$images[0].'&fol=c&w=70&h=70';
				
				} elseif($post['post_type'] == 3) {   // New profile image
					
					$path = '/thumb.php?src='.$post['post_content'].'&fol=a&w=70&h=70';
				
				} elseif($post['post_type'] == 5) {   // New cover photo
					
					$path = '/thumb.php?src='.$post['post_content'].'&fol=b&w=70&h=70';
				
				}
				
				// Set image
				$image = ($post['post_type']  == 4) ? '' : '<img onclick="aOverlow();loadPost('.$post['post_id'].')" title="'.$TEXT['_uni-View_post'].'" src="'.$path.'" alt="..." class="circle" width="35" height="35">';			
				
			} else {
			    $image = '';
			}
			
			if($post['posted_as'] == 2) {
				
				$child_group = $this->getGroup($post['posted_at']);
				
				$posted_in = $TEXT['_uni-in'].' <span title="'.readable(sprintf($TEXT['_uni-ttl_group_members_count'],$child_group['group_users'])).'" class="h6 b3 dark-font"><a href="/group/'.$post['posted_at'].'" onclick="aOverlow();loadGroup('.protectXSS($post['posted_at']).',1,1);return false;">'.$child_group['group_name'].'</a></span>';
				
			} elseif($post['posted_as'] == 1) {
				
				$child_page = $this->getPage($post['posted_at']);
				
				$posted_in = $TEXT['_uni-at'].' <span class="h6 b3 dark-font"><a href="/page/'.$post['posted_at'].'" onclick="aOverlow();loadPage('.protectXSS($post['posted_at']).',1,1);return false;">'.$child_page['page_name'].'</a></span>';
	
			} else {
				$posted_in = '';
			}
	
			// Finalize type and return results
			$text_start = ($row['not_type'] == 4) ? $TEXT['_uni-liked_your'] : $TEXT['_uni-commented_on_your'];

            $full_text = $text_start.' <span title="'.fixText(40,$post['post_text']).'" class="h6 b3 theme-font"><a href="/view/'.$row['not_content_id'].'" onclick="aOverlow();loadPost('.protectXSS($row['not_content_id']).');return false;">'.$t_type.' '.$extra.'</a></span> '.$posted_in ;		
			
			// Return data
			return array($full_text,$image);
			
		} elseif($row['not_type'] == 13) { // Added in group

		    // Select group in which user is added
			$child_group = $this->getGroup($row['not_content_id']);
			
			return array($TEXT['_uni-added_you_in_the_group'].' <span class="h6 b3 theme-font"><a href="/group/'.$row['not_content_id'].'" onclick="aOverlow();loadGroup('.protectXSS($row['not_content_id']).',1,1);return false;">'.fixText(35,$child_group['group_name']).'</a></span>','');
			
		} elseif($row['not_type'] == 14) { // Invited to like page

			$child_page = $this->getPage($row['not_content_id']);
			
			return array($TEXT['_uni-invited_to_like'].' <span class="h6 b3 theme-font"><a href="/page/'.$row['not_content_id'].'" onclick="aOverlow();loadPage('.protectXSS($row['not_content_id']).',1,1);return false;">'.fixText(35,$child_page['page_name']).'</a></span>','');
			
		} elseif($row['not_type'] == 16) { // Like comment

			return array($TEXT['_uni-liked_your_comment_on_this'].' <span class="h6 b3 theme-font"><a href="/view/'.$row['not_content_id'].'" onclick="aOverlow();loadPost('.protectXSS($row['not_content_id']).',1,1);return false;">'.$TEXT['_uni-post'].'</a></span><div class="tin-light-font-only margin-5-0-0-0 active-side h4 b1 border background-x padding-10">'.fixText(20,$row['not_data']).'</div>','');
			
		}
	}	
	
    function getNotiications($from,$user_id) {                                            // Return notifications for WIDGET 
		global $TEXT,$page_settings;
		
		// Roll some arrays in
		require_once(__DIR__.'/presets/presets.php');
		
		// Reset
		$start = $style = $notifications = '';
		
		// Limit +1 for more results check
		$limit = $this->n_per_page + 1;
		
		// Add startup
		if(is_numeric($from) && $from > 0 ) {
			$start = 'AND `notifications`.`id` < \''.$this->db->real_escape_string($from).'\'';
			$style = 'brz-animate-bottom';
		}
		
		// Select notifications
		$result = $this->db->query(sprintf("SELECT * FROM `notifications` , `users` WHERE `notifications`.`not_from` = `users`.`idu` $start AND `notifications`.`not_type` IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16) AND `notifications`.`not_to` = '%s' ORDER BY `notifications`.`id` DESC LIMIT %s",$this->db->real_escape_string($user_id),$limit));

		$rows = $update = array();
		
		if(!empty($result) && $result->num_rows) {
			
			// Fetch notifications
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
				$update[] = $row['id'];
			}
			
			// remove last result if exists
			if(array_key_exists($limit-1, $rows)) {
				$loadmore = 1;
				array_pop($rows);
				array_pop($update);
			}
			
			// Load template
		    $nf_tpl = display(templateSrc('/notifications/notification'),0,1);
			$reactions = getReactions($page_settings['reactions']);
			
			foreach($rows as $row) {
		        
				// separate unread by adding different background
				$TEXT['temp-color'] = ($row['not_read']) ? 'theme-color-active-hvr' : 'theme-color-active';
	            
				// Random html id
				$TEXT['temp-id'] = mt_rand(100, 99999);
				
				$TEXT['temp-animate'] = (is_numeric($from) && $from > 0 ) ? 'animate-bottom' : '';
				
				// List notification data
				list($TEXT['temp-text'],$TEXT['temp-data']) = $this->listNotifData($row,$TEXT['temp-id']);
				
				// Set data for template
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-not_from'] = $row['not_from'];
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
				
				if($row['not_data'] != "Empty") {
					$TEXT['temp-img_icon'] = (isset($reactions[$row['not_data']][2])) ? '/themes/'.$TEXT['theme'].'/'.$reactions[$row['not_data']][2] : $image_set1[$row['not_type']];
				} else {
					$TEXT['temp-img_icon'] = $image_set1[$row['not_type']];
				}
				
				$TEXT['temp-not_time'] = addStamp($row['not_time'],2);
				
				$notifications .= display('',$nf_tpl);
		
				// Last processed id
                $last = $row['id'];		
			}
			
			// Update as unread as read
			$query = $this->db->query(sprintf("UPDATE `notifications` SET `notifications`.`not_read` = '1' WHERE `notifications`.`id` IN(%s)",$this->db->real_escape_string(implode(',',$update))));
			
			// Add load more function if more results exists
			if(isset($loadmore)) {
				$notifications .= '<input id="7h4sd4" value="'.$last.'" class="hidden"></input>';
			}
			
			// Return notifications
			return $notifications;	
			
		} else {	
			// No notifications
			return showBox($TEXT['_uni-No_more_notifications']);			   
		}
	}
	
	function getNotiicationsAll($from,$user_id,$filter = 1) {                             // Return notifications for Page
		global $TEXT,$page_settings;
		// FILTER 1 ALL (DEFAULT)
		// FILTER 2 REQUESTS
		// FILTER 3 NEW FOLLOWERS
		// FILTER 0 UN READED NOTIFICATIONS
		
		// Roll some arrays in
		require_once(__DIR__.'/presets/presets.php');
		
		// Reset
		$notifications = $prev = '';
		$rows = $update = array();
		
		// Set starting point
		$from = (is_numeric($from) && $from > 0 ) ? 'AND notifications.id < \''.$this->db->real_escape_string($from).'\'' : ''; 
		
		// Set limit
		$limit = $this->db->real_escape_string($this->n_per_page + 1);
		
		// Escape variables for MySQl Query
		$user_id_esc = $this->db->real_escape_string($user_id);
			
		// Select notifications
		if($filter == 1) {
			$notiications = $this->db->query("SELECT * FROM notifications , users WHERE notifications.not_from = users.idu AND notifications.not_type IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16) AND notifications.not_to = $user_id_esc $from ORDER BY notifications.id DESC LIMIT $limit ;") ;
		    $empty = $TEXT['_uni-No_nots-all'];
		} elseif($filter == 2) {
			$notiications = $this->db->query("SELECT * FROM notifications , users WHERE notifications.not_from = users.idu AND notifications.not_type IN(2,12,11) AND notifications.not_to = $user_id_esc $from ORDER BY notifications.id DESC LIMIT $limit ;") ;
		    $empty = $TEXT['_uni-No_nots-req'];
		} elseif($filter == 3) {
			$notiications = $this->db->query("SELECT * FROM notifications , users WHERE notifications.not_from = users.idu AND notifications.not_type = 1 AND notifications.not_to = $user_id_esc $from ORDER BY notifications.id DESC LIMIT $limit ;") ;
		    $empty = $TEXT['_uni-No_nots-fol'];
		} elseif($filter == 0) {
			$notiications = $this->db->query("SELECT * FROM notifications , users WHERE notifications.not_from = users.idu AND notifications.not_type IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14) AND notifications.not_read = 0 AND notifications.not_to = $user_id_esc $from ORDER BY notifications.id DESC LIMIT $limit ;") ;
		    $empty = $TEXT['_uni-No_nots-red'];
		}	
		
		// If notifications exists
		if($notiications->num_rows) {
			
			// fetch notifications
			while($row = $notiications->fetch_assoc()) {
			    $rows[] = $row;
				$update[] = $row['id'];
			}
			
			// Check whether more notifications exists 
			if(array_key_exists($limit-1, $rows)) {
				$loadmore = 1;
				array_pop($rows); 
				array_pop($update);
			}
			
			// Load template
		    $nf_tpl = display(templateSrc('/notifications/notification_all'),0,1);
			
			foreach($rows as $row) {
		        
				// separate unread by adding different background
				$TEXT['temp-color'] = ($row['not_read']) ? 'theme-color-active-hvr' : 'theme-color-active';
	            
				// Random html id
				$TEXT['temp-id'] = mt_rand(100, 99999);
				
				// List notification data
				list($TEXT['temp-text'],$TEXT['temp-data']) = $this->listNotifData($row,$TEXT['temp-id']);
				
				// Parse date for seprators
				$newDateTime = date('Y-m-d', strtotime($row['not_time']));
				
				if ($newDateTime != $prev) {
                    
					$add_seprate = addStamp($row['not_time'],0,'_uni-U-');				
			
					$notifications .= '<div class="block-box padding-10 h2 b4 tin-light-font-only bottom-divider">'.$add_seprate.'</div>';
					
					$prev = $newDateTime;

				}
				
				// Set data for template
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-not_from'] = $row['not_from'];
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_name_32'] = fixName(32,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-img_icon'] = $image_set1[$row['not_type']];
				$TEXT['temp-not_time'] = addStamp($row['not_time'],2);
			
			    // Add notification to list
				$notifications .= display('',$nf_tpl);
				
				// Last processed id
                $from = $row['id'];
				
			}
			
			// Update unread
			$query = $this->db->query(sprintf("UPDATE `notifications` SET `notifications`.`not_read` = '1' WHERE `notifications`.`id` IN(%s)",$this->db->real_escape_string(implode(',',$update))));

			// Add load more function if exists
			$notifications .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'ggj4wdf('.$from.','.$filter.');') : closeBody($TEXT['_uni-No_more_notifications'],1);
			
			// Return notifications
			return $notifications;
			
		} else {
			// Else no notifications yet
			return bannerIt('nots'.mt_rand(1,4),$empty,$TEXT['_uni-No_notifications_here']).'<script>$("#threequarter").find("#notification_header").remove();</script>';	
		}
	}

    function getChats($user,$from,$filter) {                                              // Return chat forms
		global $TEXT ;
		
		// limit +1 to check for more chats
		$limit = $this->settings['chats_per_page'] + 1;
		
		// Default chat order
		$order = 'ORDER BY chat_forms.form_active DESC';
		
		// Add filter
		if($filter == 1) {             // Grouped chats	
			$add_filter = 'AND chat_forms.form_type = 2 ';
			$empty = $TEXT['_uni-No_chats-grp'];$empty2 = $TEXT['_uni-No_chats-grp-inf'];
		} elseif($filter == 2) {       // Single chats
			$add_filter = 'AND chat_forms.form_type = 1 ';
			$empty = $TEXT['_uni-No_chats-sing'];$empty2 = $TEXT['_uni-No_chats-sing-inf'];
		} elseif($filter == 3) {       // Started by you
			$add_filter = 'AND chat_forms.form_by = '.$this->db->real_escape_string($user['idu']).' ';
			$empty = $TEXT['_uni-No_chats-stb'];$empty2 = $TEXT['_uni-No_chats-stb-inf'];
		} elseif($filter == 4) {       // inActive stocked
			$add_filter = '';
			$empty = $TEXT['_uni-No_chats-all'];$empty2 = $TEXT['_uni-No_chats-all-inf'];
			$order = 'ORDER BY chat_forms.form_id DESC';	
		} else {                      // All chats
			$add_filter = '';
            $empty = $TEXT['_uni-No_chats-rec'];$empty2 = $TEXT['_uni-No_chats-rec-inf'];		
		}
		
		// Add header and staring point
		if($from > 0 ) {
			
			if($filter == 4) {       // all time stocked
				$from = 'AND chat_forms.form_id < '.$this->db->real_escape_string($from);
		    } else {
				$limit = 50;
				$from = '';
			}	
		} else {
			$from = '';
		}		

		// Select chats
		$result = $this->db->query(sprintf("SELECT * FROM chat_forms, chat_users WHERE chat_users.uid = %s AND chat_users.form_id = chat_forms.form_id $from $add_filter $order LIMIT %s", $this->db->real_escape_string($user['idu']), $limit));

	    $rows = array();
	
		// If posts with photos exists
		if($result->num_rows !== 0) {
			
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}

			// Check for more results
			$loadmore = (array_key_exists($limit-1, $rows) && $limit !== 50) ? array_pop($rows) : NULL;

			// Create boxed page 
			$chats ='';
 
            $chat_tpl = display(templateSrc('SRC',1).'/chats/in_list'.$TEXT['templates_extension'],0,1);

            // Create list of chats
            foreach($rows as $row) {
				
				// RESET
				$actions = $TEXT['temp-new_messages'] = '';
				
				if($row['form_type'] == 1 ) {
					
					// Fetch chat form owner
					$chat_owner = $this->getUserByID(($row['form_by'] == $user['idu']) ? $row['form_to']  : $row['form_by']);
					
					// Add available photo
					$TEXT['temp-image'] = ($row['form_icon'] == 'default.png') ? '/thumb.php?src='.$this->getImage($user['idu'],$chat_owner['idu'],$chat_owner['p_image'],$chat_owner['image']).'&fol=a&w=50&h=50' : '/thumb.php?src='.$row['form_icon'].'&fol=d&w=50&h=50';
					
				} else {
					
					// Set chat owner as current user
					$chat_owner = $user ;
					
					// SRC group photo
					$TEXT['temp-image'] = '/thumb.php?src='.$row['form_icon'].'&fol=d&w=50&h=50';
					
				}

			    // Chat name 
		        $TEXT['temp-name'] = ($row['form_name'] == $TEXT['_uni-Name_this_chat'] && $row['form_type'] == 1) ? fixName(30,$chat_owner['username'],$chat_owner['first_name'],$chat_owner['last_name']) : fixText(30,$row['form_name']);

				// Count unread messages
				$unread = $this->db->query(sprintf("SELECT COUNT(*) FROM `chat_messages` WHERE `form_id` = '%s' AND `posted_on` > '%s' AND `by` != '%s' ",$this->db->real_escape_string($row['form_id']),$this->db->real_escape_string($row['on_form']),$this->db->real_escape_string($user['idu'])));
				
				list($count) = $unread->fetch_row();
				
				if($count > 0) {
					$TEXT['temp-new_messages'] = '<span class="number-counter">'.$count.'</span>';
				}
			
				// Generate chat form accordion
				//$chats .= listChatCaps(,'',$src,$chat_name,'<span class="timeago" title="'.$row['form_active'].'">'.$row['form_active'].'</span>',$new_messages);
				
				$TEXT['temp-id'] = $row['form_id'];
				$TEXT['temp-info'] = addStamp($row['form_active'],2);
                
				$chats .= display('',$chat_tpl);
				
				// Last processed id
				$last = $row['form_id'];	
			}
	    	
			// Update user activity on messenger
			$this->updateChatsActivity($user['idu']);
		
			// Add load more function if more results exists
			$chats .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','loadChats('.$last.','.$filter.',24);') : closeBox($TEXT['_uni-No_more-chats']);
			
			// return listed chat forms
			return $chats;
			
		} else {
			
			// Return no users yet
			return bannerIt('chats'.mt_rand(1,4),$empty,$empty2).'<script>$("#chats-box-main").find(".block-title").remove();</script>';		
		}	
	}	

	function getFormUsers($user_id,$form_id,$founder) {                                   // Get all chat users
	    global $TEXT;
		
		// Escape form id
		$escaped_form_id = $this->db->real_escape_string($form_id);
		
		// Select form users
		$form = $this->db->query("SELECT * FROM `chat_users`, `users` WHERE `chat_users`.`form_id` = '{$this->db->real_escape_string($form_id)}' AND `users`.`idu` = `chat_users`.`uid`");
		
		// If posts with photos exists
		if($form->num_rows) {
			
			while($row = $form->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Reset
			$users = '';

			$user_tpl = display(templateSrc('/chats/member'),0,1);

			// Create list
			foreach($rows as $row) {
				
				if($row['type'] == 2 && $founder !== $row['idu']) {
					$TEXT['temp-info'] = $TEXT['_uni-Administration'];			
				} elseif($founder == $row['idu']) {
				    $TEXT['temp-info'] = $TEXT['_uni-Founder'];			
				} else {
					$TEXT['temp-info'] = '';
				}
				
				$TEXT['temp-id'] = $row['idu'];
				$TEXT['temp-name'] = fixName(32,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				
				$users .= display('',$user_tpl);
	
			}
			
			return $users;
	
		} else {
			return showBox($TEXT['_uni-sorry_no_users']);
		}
	}
	
	function getChatForm($user,$form_id,$db) {                                            // Get chat info page
		global $TEXT;
		
		// Fetch chat form if exists
		$form = $this->getChatFormByID($form_id,$user['idu']);
		
		if(!empty($form['form_id'])) {
		
			// Set chat image
			if($form['form_type'] == 1) {
	
				// Fetch chat form owner
				$chat_owner = $this->getUserByID(($form['form_by'] == $user['idu']) ? $form['form_to']  : $form['form_by']);
		
				// Add available photo
				$TEXT['temp-form_icon'] = ($form['form_icon'] == 'default.png') ? '/thumb.php?src='.$this->getImage($user['idu'],$chat_owner['idu'],$chat_owner['p_image'],$chat_owner['image']).'&fol=a&w=200&h=200' : '/thumb.php?src='.$form['form_icon'].'&fol=d&w=200&h=200' ;
	
			} else {
	
				// Fix chat owner as current user
				$chat_owner = $user ;

				// SRC group photo
				$TEXT['temp-form_icon'] =  '/thumb.php?src='.$form['form_icon'].'&fol=d&w=200&h=200';

			}

			// Random id
	        $el = mt_rand(100, 9999);
			
			// Add form actions
			if($form['form_by'] == $user['idu']) {	
				$TEXT['temp-actions'] = '<div id="'.$el.'" onclick="deleteChat(\''.$el.'\','.$form['form_id'].',6);" class="pointer padding-10 h5 b1 tin-light-font">
        									<img class="img-20" src="/themes/'.$TEXT['theme'].'/img/icons/navigation/trash.svg">
	    									<span class="padding-0-6">
	        									'.$TEXT['_uni-Delete_chat'].'
	    									</span>
    									</div>';
		    } else {
				$TEXT['temp-actions'] = '<div id="'.$el.'" onclick="deleteChat(\''.$el.'\','.$form['form_id'].',5);" class="pointer padding-10 h5 b1 tin-light-font">
        									<img class="img-20" src="/themes/'.$TEXT['theme'].'/img/icons/navigation/exit.svg">
	    									<span class="padding-0-6">
	        									'.$TEXT['_uni-Exit_chat'].'
	    									</span>
    									</div>';
			}

			$TEXT['temp-form_id'] = $form['form_id'];
			$TEXT['temp-form_name'] = $form['form_name'];
			$TEXT['temp-form_cover'] = $form['form_cover'];
			$TEXT['temp-form_text'] = ($form['form_description']) ? $form['form_description'] : $TEXT['_uni-No_description'];
			$TEXT['temp-form_people'] = $this->getFormUsers($user['idu'],$form['form_id'],$form['form_by']);
			
	
	        return display(templateSrc('/chats/header'));
			
		} else {
			return showError($TEXT['_uni-Form_n_available']);
		}	
	}
	
	function chatWindow($user,$form_id,$ctemplate = '/chats/window') {                                                 // Open chat
		global $TEXT;

		// Fetch chat form if exists
		$form = $this->getChatFormByID($form_id,$user['idu']);
		
		if(!empty($form['form_id'])) {
			
			if($form['form_type'] == 1) {
				
				// Fetch chat form owner
				$chat_owner = $this->getUserByID(($form['form_by'] == $user['idu']) ? $form['form_to']  : $form['form_by']);

				// get user activity
				$active = (time() - $chat_owner['active'] < 30 || fuzzyStamp($chat_owner['active']) == $TEXT['_uni-Online'] ) ? '<i class="fa fa-circle h5 b2 green-font"></i> <span class="tin-light-font-only h5 b1">'.$TEXT['_uni-Online'].'</span>' : '<span class="tin-light-font-only h5 b1">'. $TEXT['_uni-Last_seen'].' '.fuzzyStamp($chat_owner['active']) .'</span>';
				
				// Chat type 
			    $TEXT['temp-time'] = '<span class="tin-light-font-only h5 b1" >'.$active.'</span>' ;
	
			} else {
				
				// Fix chat owner as current user
				$chat_owner = $user ;
				
				// Chat type 
			    $TEXT['temp-time'] = '<span class="tin-light-font-only h5 b1">'.$TEXT['_uni-Last_activity'].' <span class="timeago" title="'.$form['form_active'].'">'.$form['form_active'].'</span></span>';
	
			}
			
			// Chat name 
		    $TEXT['temp-chat_name'] = ($form['form_name'] == $TEXT['_uni-Name_this_chat'] && $form['form_type'] == 1) ? fixName(30,$chat_owner['username'],$chat_owner['first_name'],$chat_owner['last_name']) : fixText(30,$form['form_name']);

			// Set chat image
			if($form['form_type'] == 1 && $form['form_icon'] == 'default.png') {
				
				// Add available photo
				$TEXT['temp-icon'] = '/thumb.php?src='.$this->getImage($user['idu'],$chat_owner['idu'],$chat_owner['p_image'],$chat_owner['image']).'&fol=a&w=200&h=200' ;
				
			} else {
	
				// SRC group photo
				$TEXT['temp-icon'] =  '/thumb.php?src='.$form['form_icon'].'&fol=d&w=200&h=200';
				
			}
			
			$TEXT['temp-form_id'] = $form['form_id'];
			$TEXT['temp-user_id'] = $user['idu'];
			$TEXT['temp-form_name'] = $form['form_name'];
			$TEXT['temp-form_cover'] = $form['form_cover'];
			$TEXT['temp-form_text'] = $form['form_description'];
			$TEXT['temp-messages'] = $this->getMessages(0,$form_id,$user,1);
	
	        return display(templateSrc($ctemplate));
			
		} else {
			return showError($TEXT['_uni-Form_n_available']);
		}		
	}
	
	function updateForm($form_id,$type = 0) {                                             // Update last activity on form
		
		$more = ($type) ? ',`form_type` = \'2\'' : '';
		
		$this->db->query(sprintf("UPDATE `chat_forms` SET `form_active` = CURRENT_TIMESTAMP $more WHERE `form_id` = '%s' ",$this->db->real_escape_string($form_id)));
	
	}
	
	function updateFormActivity($form_id,$user_id) {                                      // Update user last activity on form
		
		// Update user last activity on form
		$this->db->query(sprintf("UPDATE `chat_users` SET `on_form` = CURRENT_TIMESTAMP WHERE `uid` = '%s' AND `form_id` = '%s' ",$this->db->real_escape_string($user_id),$this->db->real_escape_string($form_id)));

	}
	
	function updateHomeActivity($user_id) {                                               // Update user last activity on feeds
		
		// Update user last activity on home
		$this->db->query(sprintf("UPDATE `users` SET `onfeeds` = CURRENT_TIMESTAMP WHERE `idu` = '%s' ",$this->db->real_escape_string($user_id)));

	}
	
	function updateChatsActivity($user_id) {                                              // Update user last activity on messenger
		
		// Update user last activity on home
		$this->db->query(sprintf("UPDATE `users` SET `onmessenger` = CURRENT_TIMESTAMP WHERE `idu` = '%s' ",$this->db->real_escape_string($user_id)));

	}
	
	function exitChat($form_id,$user_id) {                                                // Left chat
		global $TEXT;
		
		// Select form
		$form = $this->getChatFormByID($form_id,$user_id);
		
		// If available
		if($form['form_id']) {
			
			// Delete user
			$query = sprintf("DELETE FROM `chat_users` WHERE `form_id` = '%s' AND `uid` = '%s' ",$this->db->real_escape_string($form_id),$this->db->real_escape_string($user_id));
			
			// If success
			if($this->db->query($query) === TRUE) {
				
				// Add notification to group
				$this->db->query(sprintf("INSERT INTO `chat_messages` (`mid`, `m_type`, `m_text`, `by`, `form_id`, `posted_on`) VALUES (NULL, '5', '', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($form_id)));
		   
				// Update group status
				$this->updateForm($form_id);
	
                // Redirect user to chats
				return '<script>window.location.href = \'/user/chats\';</script>';
			}
		}
		
		// Redirect if not available
		return '<script>window.location.href = \'/user/chats\';</script>';
		
	}

	function deleteChat($form_id,$user_id) {                                              // Delete chat
		global $TEXT;
		
		// Select form
		$form = $this->getChatFormByID($form_id,$user_id);
		
		// Verify ownership
		if($form['form_id'] && $form['form_by'] == $user_id) {
			
			// Delete chat form
			$this->db->query(sprintf("DELETE FROM `chat_forms` WHERE `form_id` = '%s' ",$this->db->real_escape_string($form_id)));
			
			// If chat form is deleted
			if($this->db->affected_rows) {
				
				// Delete users
				$this->db->query(sprintf("DELETE FROM `chat_users` WHERE `form_id` = '%s' ",$this->db->real_escape_string($form_id)));
				
				// Delete messages
				$this->db->query(sprintf("DELETE FROM `chat_messages` WHERE `form_id` = '%s' ",$this->db->real_escape_string($form_id)));

				// Delete chat notifications
				$this->db->query(sprintf("DELETE FROM `notifications` WHERE `not_from` = '%s' AND `not_content_id` = '%s' AND `not_type` = '10' ",$this->db->real_escape_string($user_id),$this->db->real_escape_string($form['form_id'])));

				// Delete pending requests
		        $this->db->query(sprintf("DELETE FROM `notifications` WHERE `not_to` =  '%s' AND `not_content_id` = '%s'  AND `not_type` IN(11,12);",$this->db->real_escape_string($user_id),$this->db->real_escape_string($form_id)));

				// Redirect users to chats 
				return '<script>window.location.href = \'/user/chats\';</script>';
			}
		} else {
			return '<script>window.location.href = \'/user/chats\';</script>';
		}
	}
	
	function getMessages($from,$form_id,$user,$verified = NULL,$latest = 0,$prev=0) {             // Get chat messages
		global $TEXT;
		
		// Limit
		$limit = ($latest) ? '' : 'LIMIT 31';
		
		// Type Identifier
		$identifier = ($latest) ? '>' : '<' ;
		
		// Start up
		$start = ($from && is_numeric($from)) ? 'AND `chat_messages`.`mid` '.$identifier.' \''.$this->db->real_escape_string($from).'\'' : '';
	
	    // Privacy check
		$form = $this->getChatFormByID($form_id,$user['idu']);
        
		// Check form
		$form_id = (!$form['form_id']) ? FALSE : $form['form_id'];	
		
		if($form_id) {
			
			// Reset
			$rows = $parsed = array();
			$messages = '';
			
			// Get messages
			$results = $this->db->query(sprintf("SELECT * FROM `chat_messages`,`users` WHERE `chat_messages`.`form_id` = '%s' AND `users`.`idu` = `chat_messages`.`by` $start ORDER BY `chat_messages`.`mid` DESC %s ",$this->db->real_escape_string($form_id),$this->db->real_escape_string($limit)));

			// If messages exits
			if($results->num_rows) {
				
				// Update form activiy
			    $this->updateFormActivity($form_id,$user['idu']);

				while($row = $results->fetch_assoc()) {
					$rows[] = $row;
				}
				
				// Check whether more results exists
				$loadmore = (!$latest && array_key_exists(30,$rows)) ? array_pop($rows) : NULL;
				
				foreach($rows as $row) {
					
					// Reset
					$name = $delete = '';
					$round = "200px;";
					
					// Standard message
					if($row['m_type'] == 1 && !in_array($row['mid'],$parsed)) {
		
					    // Check message owner and add styles
						if($row['idu'] == $user['idu']) {	
							
							// Align to right side as owner is current user
							$classes = 'right body-color tin-light-font-only';
							$img = '';
							
							// Add delete button
							$actions = '<div id="show-message-functions-'.$row['mid'].'" class="right full h2 hide  padding-5">
 							                <span class="timeago b1 tin-light-font-only" title="'.addStamp($row['posted_on'],2).'">'.addStamp($row['posted_on'],2).'</span> | 
											<span class="b2 h4 tin-light-font pointer" onclick="deleteContent('.$row['mid'].',3)"> '.$TEXT['_uni-Delete'].'</span>
										</div>';
						
						} else {
							
							// Align to left side
							$classes = 'left theme-color white-font-only';
							
							// Add posted time only
							$actions = '<div id="show-message-functions-'.$row['mid'].'" class="right full hide padding-5">
							                <span class="timeago h2 b1 white-font-only" title="'.addStamp($row['posted_on'],2).'">'.addStamp($row['posted_on'],2).'</span>
										</div>';
							

							$img = '<div title="'.fixName(100,$row['username'],$row['first_name'],$row['last_name']).'" class="left padding-2-6">
							            <a href="/'.$row['idu'].'" onclick="loadProfile('.$row['idu'].');return false;">
			                                <img class="circle img-25 border-min" src="/thumb.php?src='.$this->getImage($user['idu'],$row['idu'],$row['p_image'],$row['image']).'&fol=a&w=70&h=70">
			                            </a>
									</div>';

							
						}
						
						// Add message to list
						$messages = '<div id="message-'.$row['mid'].'" onclick="$(\'#show-message-functions-'.$row['mid'].'\').toggleClass(\'hide\');" class="full message-bos trans noflow inline-in padding-6-0">
						                '.$img.'
										<div class="'.$classes.' border message padding-4-8 h5 b1">
		    								'.$this->parseText(protectXSS($row['m_text'])).$actions.'
										</div>
									</div>'.$messages;									  
						
					
					// Chat notifications
					} elseif($row['m_type'] == 2 && !in_array($row['mid'],$parsed)) {
						
						// Chat notification
						$messages = chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name']).' '.$TEXT['_uni-updated_c_icon'], $row['mid']).'<script>updateSRC("#active-form-img","/thumb.php?src='.$form['form_icon'].'&fol=d&w=200&h=200&q=100'.'");</script>'.$messages;
					
						if($latest) {$messages.='<script>$(\'.chat-icon-'.$form_id.'\').attr(\'src\',\'/thumb.php?src='.$form['form_icon'].'&fol=d&w=200&h=200\');</script>';}

					
					} elseif($row['m_type'] == 3 && !in_array($row['mid'],$parsed)) {
						
						// Chat notification
						$messages = chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name']).' '.$TEXT['_uni-updated_c_cover'], $row['mid']).$messages;

					    if($latest && $row['by'] !== $user['idu']) {$messages.='<script>$(\'.chat-cover-'.$form_id.'\').attr(\'src\',\'url(\'/thumb.php?src='.$form['form_cover'].'&fol=e&w=750&h=300)\');</script>';}
					
					} elseif($row['m_type'] == 4) {
						
						// Chat notification
						$messages = chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name'], $row['mid']).' '.$TEXT['_uni-added'].' '.$row['m_text'], $row['mid']).$messages;
					
					} elseif($row['m_type'] == 5) {
						
						// Chat notification
						$messages = chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name'], $row['mid']).' '.$TEXT['_uni-left'], $row['mid']).$messages;
					
					} elseif($row['m_type'] == 6) {
						
						// Chat notification
						$messages = chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name']).' '.$TEXT['_uni-removed'].' '.$row['m_text'], $row['mid']).$messages;

					} elseif($row['m_type'] == 7) {
						
						// Chat notification
						$messages = chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name']).' '.$TEXT['_uni-updated_c_info'], $row['mid']).$messages;

						if($latest) {$messages.='<script>$(".chat-name-'.$form_id.'").html("'.$form['form_name'].'");$("#chat-name").html("'.$form['form_name'].'");$("#chat-desc").html("'.protectXSS($form['form_description']).'");</script>';}
						
					}
					
					// Remove pre loaders as message is inserted
					if($latest && $row['idu'] == $user['idu']) {
						$messages .= '<script>chatLoaders(0, '.$form['form_id'].');</script>';
					}
					
					// First processed message id
					$first = (!isset($first)) ? $row['mid'] : $first ;
					
					// Last processed message id
					$last = $row['mid'];
					
					// Prevent duplicates when target user has delete last message
                    $parsed[] = $row['mid'];
                    
                    $had_messages = true;
				
				}
				
				// Add load more messages is exists
				if($loadmore) { 
				    $messages = addLoadmore(0,'','moreMessages('.$last.','.$form_id.');').$messages;
				}

				// Active chat features
	            if($latest) {
					$messages = '<script>$(".latest-message-'.$form_id.'").val(\''.$first.'\');</script>'.$messages;			
				} elseif(!$latest && $from) {
				    //$messages = 'Nothing to apply'.$messages;
				} else {
					$messages = '<script>$(".latest-message-'.$form_id.'").val(\''.$first.'\');</script>'.$messages;
				}

				if(isset($had_messages)) {
					$messages .= '<script>scrollFullClass(".messages-container-'.$form_id.'");</script>';
				}
		
				// Return messages
				return $messages;
				
			} else {
				
				// No messages yet
				if(!$latest) {
					return chatNotification(fixName(45,$row['username'],$row['first_name'],$row['last_name']).' '.$TEXT['_uni-No_messages'], $row['mid']);
				}
				
			}

        // If fail to get form			
		} else {
			return (!$latest) ? $TEXT['_uni-Form_n_available'] : '';
		}
	}
	
	function messageForm($user_id,$form_id,$type,$text) {                                 // Send message and update form
		
		// Add message
		$query = sprintf("INSERT INTO `chat_messages`(`m_type`,`m_text`,`by`,`form_id`,`posted_on`) VALUES ('%s','%s','%s','%s',CURRENT_TIMESTAMP);",$this->db->real_escape_string($type),$this->db->real_escape_string(protectInput(trim($text))),$this->db->real_escape_string($user_id),$this->db->real_escape_string($form_id));
		
		// Update last activity
		if($this->db->query($query)) {
			$this->updateForm($form_id);
		}
		
	}

	function quickMessage($to_id,$message,$user) {                                        // Send chat message from profile page
		global $TEXT;
		
		// Get target user
		$target = $this->getUserByID($to_id);
		
		// Check privacy
		if(!$target['idu']) {
			return showError($TEXT['_uni-User_exists_0']).'<script>quickMessage(0,2);</script>';		
		} elseif(!in_array($target['idu'],$this->followings)) {
			return showError($TEXT['_uni-Follow_bef_message']).'<script>quickMessage(0,2);</script>';
		} elseif($target['idu'] == $user['idu']) {
			return showError($TEXT['_uni-No_messages_to_your']).'<script>quickMessage(0,2);</script>';
		} elseif(Blocker::IsTypeBlocked(Blocker::GetBlock($user['idu'], $target['idu']), 'chat')) {
			return showError($TEXT['_uni-Chat_Blocked']).'<script>quickMessage(0,2);</script>';
		} else {
			
			// Check whether form already exists
			$form = $this->db->query(sprintf("SELECT `form_id` FROM `chat_forms` WHERE `form_type` = '1' AND `form_by` = '%s' AND `form_to` = '%s' ",$this->db->real_escape_string($user['idu']),$this->db->real_escape_string($target['idu'])));
	    
		    if($form->num_rows) {
				
				// Fetch chat form
				$form = $form->fetch_assoc();
				$form_id = $form['form_id'];

    		} else {
			    
				// Create a new chat form
				$form = $this->db->query(sprintf("INSERT INTO `chat_forms`(`form_id`,`form_type`,`form_name`,`form_date`,`form_to`,`form_by`,`form_active`,`form_description`) VALUES (NULL,'1','%s','%s','%s','%s',CURRENT_TIMESTAMP,'')",$TEXT['_uni-Name_this_chat'],date('Y-m-d H:i:s'),$this->db->real_escape_string($target['idu']),$this->db->real_escape_string($user['idu'])));
			    $form_id = $this->db->insert_id;
				
				// Add both users to newly created form
			    $this->db->query(sprintf("INSERT INTO `chat_users` (`cid`, `uid`, `type`, `form_id`, `on_form`) VALUES (NULL, '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($target['idu']),1,$this->db->real_escape_string($form_id)));
			    $this->db->query(sprintf("INSERT INTO `chat_users` (`cid`, `uid`, `type`, `form_id`, `on_form`) VALUES (NULL, '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($user['idu']),1,$this->db->real_escape_string($form_id)));
		
			}
			
		    // Insert message
		    $message = $this->db->query(sprintf("INSERT INTO `chat_messages`(`m_type`,`m_text`,`by`,`form_id`,`posted_on`) VALUES ('1','%s','%s','%s',CURRENT_TIMESTAMP)",$this->db->real_escape_string(protectInput(trim($message))),$this->db->real_escape_string($user['idu']),$this->db->real_escape_string($form_id)));
		
		    // Update form
			$this->updateForm($form_id,0);
			
		    // Return if message inserted
		    return '<script>quickMessage(0,0);loadChat('.$form_id.');</script>';

		}
		
	}

	function getAttachments($current_user,$from,$view_id,$type = 1) {                     // Return User followers or followings (Profile page)
		global $TEXT ;
		
		// Fetch target user
		$view = $this->getUserByID($view_id);
		
		// If target user doesn't exists return 
		if(empty($view['idu'])) return showError($TEXT['lang_error_script1']);

		// Set  limit
		$limit = $this->settings['results_per_page'] + 1;
		
		// Set heading
		$header_content =($type == 1) ? $TEXT['_uni-Following'] : $TEXT['_uni-Followers'];

		// Set staring point 
		$from = (is_numeric($from) && $from > 0 ) ? 'AND idu < \''.$this->db->real_escape_string($from).'\'':'';
		
		// List users
		if($type == 1){
			$people = implode(',', $this->listFollowings($view['idu']));
			$empty_message = $TEXT['_uni-none-follows'];
		} else {
			$people = implode(',', $this->listFollowers($view['idu']));
			$empty_message = $TEXT['_uni-none-followers'];
		}
		
        // Select users		
		$result = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` IN (%s) $from ORDER BY `users`.`idu` DESC LIMIT %s", $people, $limit));

	    $rows = array();
	
		if(!empty($result)) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Check whether more users exists
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
			
			$people = '';
			
			$template = display(templateSrc('/user/search/in_box'),0,1);
			
			foreach($rows as $row) {
				
				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);

				// Generate accordion data according to permissions
				list($d1,$d2,$d3) = $this->genAccordData($following,$row);			
				
				// Count New posts
				$n_posts = ($following == 1) ? $this->numberNewPosts($row['idu'],$current_user['onfeeds']) : NULL ;

				// Add user to list
				$people .= listMainUser($row['idu'],fixName(15,$row['username'],$row['first_name'],$row['last_name']),$row['username'],$this->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']),sprintf($TEXT['_uni-Profile_load_text2'],fixName(35,$row['username'],$row['first_name'],$row['last_name'])),$this->verifiedBatch($row['verified']),$d1,$d2,$d3,$this->getRelationButton($following,$row['idu'],$pri),'',$template);
			
				// Set last processed id	
				$last = $row['idu'];			

			}
			
            // Add load more function if more results exists
			$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'load_more_results('.$view['idu'].','.$last.','.$type.');') : closeBox($TEXT['_uni-No_more_users']);

			// Last privacy check
		    if($view['p_followers'] == 1 && $type == 0 && $view['idu'] !== $current_user['idu'] && !in_array($view['idu'],$this->followings)) {
				
				return bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf2']); 

			} elseif($view['p_followings'] == 1 && $view['idu'] !== $current_user['idu'] && $type == 1 && !in_array($view['idu'],$this->followings)) {
				
				return bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf2']); 
			
            // If available return users			
			} else {
				return $people;
			}	
		} else {	
			// Return no users yet
			return closeBox($empty_message);	
		}	
	}

	function getTrending($from,$filter) {                                                 // Return Gallery(3x3) from Trending posts 
		global $TEXT ;
		
		// Set limit
		$limit = $this->settings['photos_per_page'] + 1;

		// Add filter
		if($filter == 1) {             // Trending today
			$add_filter = 'AND CURDATE() = date(user_posts.post_time)';
			$head_add = $TEXT['_uni-Trending-today'];
			$empty = $TEXT['_uni-No_photos_today'];

		} elseif($filter == 2) {       // Trending yesterday
			$add_filter = 'AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(user_posts.post_time)';
			$head_add = $TEXT['_uni-Trending-yesterday'];
			$empty = $TEXT['_uni-No_photos_yest'];
		} elseif($filter == 3) {       // Trending last month
			$add_filter = 'AND MONTH(CURDATE()) = MONTH(date(user_posts.post_time)) AND YEAR(CURDATE()) = YEAR(date(user_posts.post_time))';
			$head_add = $TEXT['_uni-Trending-month'];
			$empty = $TEXT['_uni-No_photos_mon'];

		} elseif($filter == 4) {       // Trending last year
			$add_filter = 'AND YEAR(CURDATE()) = YEAR(date(user_posts.post_time))';
			$head_add = $TEXT['_uni-Trending-year'];
			$empty = $TEXT['_uni-No_photos_year'];

		} else {                       // Trending all time
			$add_filter = '';          
			$head_add = $TEXT['_uni-Trending-alltime'];
		}
		
		// Add header and staring point
		if($from > 0 ) {
			$from = 'AND user_posts.post_loves < \''.$this->db->real_escape_string($from).'\'';	
		} else {
			$from = '';
		}		

		// Select trending posts
		$result = $this->db->query(sprintf("SELECT * FROM user_posts,users WHERE user_posts.post_by_id = users.idu AND user_posts.post_type = 1 AND users.p_posts != 1 $add_filter $from ORDER BY user_posts.post_loves DESC LIMIT %s", $limit));

	    $rows = array();

		// If posts exists
		if($result->num_rows) {
			
			// Fetch posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Remove last post
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
			
			// Start responsive grid
			$images = '<div class="brz-gallery white-color" ><section id="responsive-images-columns">';
			
			// Reset
            $x = 0;	
			
		    foreach($rows as $row) {
				
				    // Generate title
					$image_title = sprintf($TEXT['_uni-ttl_trending_img'],readAble($row['post_loves'])).' '.sprintf($TEXT['_uni-getsprint'],($row['post_comments'] > 0) ? sprintf($TEXT['_uni-ttl_trending_img3'],readAble($row['post_comments'])) : '' );
		
		            // Allo multiple images
					$listed_images = explode(',', $row['post_content']);
					
					foreach($listed_images as $listed_image) {

					    $href = '<a onclick="loadPost('.$row['post_id'].');return false" href="/view/'.$row['post_id'].'"><img class="three-columns hvr-opacity pointer" title="'.$image_title.'" src="/thumb.php?src='.$listed_image.'&fol=c&w=252&h=192"/></a>';
						
						// Build responsive gallery from trending posts
                		if($x == 3) { 
				    		$images .= ' <div class="container">'.$href;
				    		$x = 1;
			    		} elseif($x == 2) {
				    		$images .= $href.'</div>';
			        		$x = 3;
			    		} elseif($x == 0) {
				    		$images .= ' <div class="container">'.$href;
			        		$x++;
			    		} else {
				    		$images .= $href;
			        		$x++;
			    		}
					
					}
					
				    // Last processed id
		        	$last = $row['post_loves'];	
					
			}
		
			$images .= '</section></div>';
				
            // Add load more function if more results exists
			$images .= ($loadmore && $last > 0 ) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'loadTrending('.$last.','.$filter.',26);') : closeBox($TEXT['_uni-No_more-photos']);

			// Return gallery
			return $images;

		} else {
			// No photos yet
			return bannerIt('trend-it'.mt_rand(1,4),$empty,$TEXT['_uni-No_photos_trend_inf']).'<script>$("#trending-box-main").find(".block-title").remove();</script>';
		}	
	}
	
	function getGallery($from,$user_id) {                                                 // Return Gallery(3x3) from photos that user has posted 
		global $TEXT ;
		
		// fetch target user
		$view = $this->getUserByID($user_id);
		
		// Update browser title
		$browser = '<script>document.title = \''.$this->db->real_escape_string(fixName(14,$view['username'],$view['first_name'],$view['last_name'])).' | '.$TEXT['_uni-Gallery'].'\';</script>';
		
		// Return i target user doesn't exists
		if(empty($view['idu'])) return showError($TEXT['lang_error_script1']);
		
		// Set limit
		$limit = $this->settings['photos_per_page'] + 1;
		
		// Set starting point
		$from = (is_numeric($from) && $from > 0 ) ? 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\'' : '';
		
		// Add margin
		$margin = (is_numeric($from) && $from > 0 ) ? 'style="margin-top:5px;"' : '';
		
		// Select posts with photos
		$result = $this->db->query(sprintf("SELECT * FROM `user_posts` WHERE `user_posts`.`post_by_id` = '%s' AND `user_posts`.`post_type` = '1' $from ORDER BY `user_posts`.`post_id` DESC LIMIT %s", $view['idu'], $limit));

	    $rows = array();

		// If posts exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Remove last post
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;

			// Start responsive grid
			$images = '<div '.$margin.' class="brz-gallery white-color animate-bottom" ><section id="responsive-images-columns">';
			
			// Reset
            $x = 0;	
			
		    foreach($rows as $row) {
				
				// Generate title
				$image_title = sprintf($TEXT['_uni-ttl_trending_img'],readAble($row['post_loves'])).' '.sprintf($TEXT['_uni-getsprint'],($row['post_comments'] > 0) ? sprintf($TEXT['_uni-ttl_trending_img3'],readAble($row['post_comments'])) : '' );
			
				// Allo multiple images
				$listed_images = explode(',', $row['post_content']);
		
				foreach($listed_images as $listed_image) {
			
					$href = '<a onclick="loadPost('.$row['post_id'].');return false" href="/view/'.$row['post_id'].'"><img class="three-columns hvr-opacity pointer" title="'.$image_title.'" src="/thumb.php?src='.$listed_image.'&fol=c&w=252&h=192"/></a>';
			
					// Build responsive gallery from trending posts
                	if($x == 3) { 
				    	$images .= ' <div class="container">'.$href;
				    	$x = 1;
			    	} elseif($x == 2) {
				    	$images .= $href.'</div>';
			        	$x = 3;
			    	} elseif($x == 0) {
				    	$images .= ' <div class="container">'.$href;
			        	$x++;
			    	} else {
				    	$images .= $href;
			        	$x++;
			    	}
				
					// Last processed id
		            $from = $row['post_id'];

				}

			}
		
			$images .= '</section></div>'.$browser ;
			
            // Add load more function if more results exists
			$images .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'load_more_profile_photos('.$view['idu'].','.$from.');') : closeBox($TEXT['_uni-No_more_photos']);
	
			// Last privacy check
			if($view['p_posts'] == 1 && !in_array($view['idu'],$this->followings)) {
				
				$now = $this->getUser();
				
				if($now['idu'] !== $view['idu']) {
					
					return bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf2']);

				} else {
					return $images;	
				}
			} else {
				return $images;
			}				
		} else {
			// No photos yet
			return  closebox($TEXT['_uni-No-photos']);
		}	
	}
	
    function searchProfile($current_user,$val) {                                          // Return profile search results
		global $TEXT ;
		
		// Set limits
		$limit_posts = $this->posts_results_limit + 1;
		$limit_followers = $this->followers_results_limit + 1;
		$limit_followings = $this->followings_results_limit + 1;
		
		// Reset
		$limit_followers_plus = $limit_followings_plus = $limit_posts_plus = '' ;
		
		// Implode lists
		$people1 = implode(',', $this->followers);   // User followers
		$people2 = implode(',', $this->followings);  // User followings
		
        // Reset		
		$posts = $followings = $followers = $followers_fetched = $posts_fetched = $followings_fetched = array(); 	
		
		// Select search from Followings
		$followings = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4 AND `users`.`idu` IN(%s) ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT 0, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string($people2),$this->db->real_escape_string($limit_followings)));
		
		// Select search from followers
		$followers = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4 AND `users`.`idu` IN(%s) ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT 0, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string($people1),$this->db->real_escape_string($limit_followers)));
		
		// Select search from posts
		$posts = $this->db->query(sprintf("SELECT * FROM `user_posts` WHERE `user_posts`.`post_by_id` = '%s' AND `user_posts`.`posted_as` = '0' AND `user_posts`.`post_text` LIKE '%s' ORDER BY `user_posts`.`post_id` DESC LIMIT %s",$this->db->real_escape_string($current_user['idu']), '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string($limit_posts)));
		
		// Fetch followings if matches
		if(!empty($followings) && $followings->num_rows) {
			while($row = $followings->fetch_assoc()) {
				$followings_fetched[] = $row;
		    }
		}
		
		// Fetch followers if matches
		if(!empty($followers) && $followers->num_rows) {
			while($row2 = $followers->fetch_assoc()) {
				$followers_fetched[] = $row2;
		    }
		}
		
		// Fetch posts if exists
		if(!empty($posts) && $posts->num_rows) {
			while($row3 = $posts->fetch_assoc()) {
				$posts_fetched[] = $row3;
		    }
		}		
		
        // Check whether results in each category exists
		
		if(array_key_exists($limit_followings - 1, $followings_fetched)) {
			
			// Set more results
			$limit_followings_plus = $TEXT['_uni-Followings'];
			
			array_pop($followings_fetched);
			
		}
		
		if(array_key_exists($limit_followers - 1, $followers_fetched)) {
			
			// Set more results
			$limit_followers_plus = $TEXT['_uni-Followers'];
			
			array_pop($followers_fetched);
		}
		
		if(array_key_exists($limit_posts - 1, $posts_fetched)) {
			
			// Set more results
			$limit_posts_plus = $TEXT['_uni-Posts'];
			
			array_pop($posts_fetched);
		}
		
		// Reset
		$full_content = '';
		
		// Add headers
		if(isset($followings_fetched) && !empty($followings_fetched)) {
			

			$all_users = '';
			
			$template = display(templateSrc('/user/search/in_box'),0,1);

		    foreach($followings_fetched as $row) {
			
				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);
			
			    // Generate accordion data according to permissions
				list($d1,$d2,$d3) = $this->genAccordData($following,$row);			
				
				// Add user to list
				$all_users .= listMainUser($row['idu'],fixName(15,$row['username'],$row['first_name'],$row['last_name']),$row['username'],$this->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']),sprintf($TEXT['_uni-Profile_load_text2'],fixName(35,$row['username'],$row['first_name'],$row['last_name'])),$this->verifiedBatch($row['verified']),$d1,$d2,$d3,$this->getRelationButton($following,$row['idu'],$pri),'',$template );

			}
			
			$close = (!empty($limit_followings_plus)) ? sprintf($TEXT['_uni-More_results_sections'],$TEXT['_uni-followings']): $TEXT['_uni-No_more_users'];
			
			$TEXT['temp_standard_content'] = $all_users.closeBox($close);
			$TEXT['temp_standard_title'] = $TEXT['_uni-Followings'];
			$TEXT['temp_standard_id'] = 'followings-results-boxs';
			
            $full_content .= display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box'.$TEXT['templates_extension']);
			
		}
		
		// Set headers
		if(isset($followers_fetched) && !empty($followers_fetched)) {
			
			$all_users = '';

		    foreach($followers_fetched as $row) {
				
	            // Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);
			
			    // Generate accordion data according to permissions
				list($d1,$d2,$d3) = $this->genAccordData($following,$row);			
				
				// Add user to list
				$all_users .= listMainUser($row['idu'],fixName(15,$row['username'],$row['first_name'],$row['last_name']),$row['username'],$this->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']),sprintf($TEXT['_uni-Profile_load_text2'],fixName(35,$row['username'],$row['first_name'],$row['last_name'])),$this->verifiedBatch($row['verified']),$d1,$d2,$d3,$this->getRelationButton($following,$row['idu'],$pri),'',$template );

			}
			
			$close = (!empty($limit_followers_plus)) ? sprintf($TEXT['_uni-More_results_sections'],$TEXT['_uni-followers']): $TEXT['_uni-No_more_users'];
			
            $TEXT['temp_standard_content'] = $all_users.closeBox($close);
			$TEXT['temp_standard_title'] = $TEXT['_uni-Followers'];
			$TEXT['temp_standard_id'] = 'followings-results-boxs';
			
            $full_content .= display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box'.$TEXT['templates_extension']);
		}
		
        // Set headers		
		if(isset($posts_fetched) && !empty($posts_fetched)) {
			
			$full_content .= '';
			$post_template = display(templateSrc('/post/user_post'),0,1);
			
			// Generate posts
			foreach($posts_fetched as $post_row) {	

				// List post buttons and details
				list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $this->listFunctions($post_row,$current_user);
				
				// Parse post text remove all smiles
				$TEXT['temp-pt_ns'] = protectXSS($this->parseText($post_row['post_text'],1));
		
				// Get post type and content
			    list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $this->getPostContent($post_row,$post_row['post_id'],1,NULL,$this->parseText(protectXSS($post_row['post_text'])));	
				
				// Post heading
				list($TEXT['temp-heading'],$TEXT['temp-group_heading']) = $this->getPostHeading($post_row['post_type'],$post_row['posted_as'],$post_row['posted_at'],$post_row['post_extras'],'',$post_row['post_content'],$current_user['gender'],$post_row['post_id']);
				
				// Post id
				$TEXT['temp-id'] = $post_row['post_id'];

				$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
				$TEXT['temp-user_img_c'] = $TEXT['temp-user_img'] = $current_user['image'];
				$TEXT['temp-user_id'] = $TEXT['temp-user_id_c'] = $current_user['idu'];
				$TEXT['temp-username'] = $TEXT['temp-username_c'] = $current_user['username'];
				$TEXT['temp-user_name'] = fixName(25,$current_user['username'],$current_user['first_name'],$current_user['last_name']);
				$TEXT['temp-userttl'] = sprintf($TEXT['_uni-Profile_load_text2'],$TEXT['temp-user_name']);
				
				// Add post in list
				$full_content .= display('',$post_template);
			
		    }
		}
		
		// Return results page
        return (!empty($full_content)) ? $full_content : bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchp_s'],protectXSS($val)));
		
	}

	function searchAtTags($current_user,$from,$val,$user) {                               // Return @user search results
		global $TEXT ;
		
		// Set limit
		$limit = $this->settings['search_results_per_page'] + 1;
		
		// Header
		$header = (is_numeric($from) && $from > 0 ) ? '' : '<div class=" margin-0-10-20-10 h6 dark-font-only text-left active-side padding-10"><a href="' . $user['idu'] . '" class="h7 b2 theme-font" onclick="loadProfile('.$user['idu'].');return false;"><img class="img-42 circle" src="/thumb.php?src='.$user['image'].'&fol=a&w=45&h=45"> <span class="padding-0-6">'.fixName(35,$user['username'],$user['first_name'],$user['last_name']).'</span></a> - '.$TEXT['_uni-Photos'].'</div>';

		// Set strting point
		$startup = (is_numeric($from) && $from > 0 ) ? 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\'' : '';
		
		// Reset
		$rows = array(); $images ='';

        // Verify privacy, existense and posts of user
		if((isset($user['idu']) && !$user['p_posts']) || $user['idu'] == $current_user['idu'] || in_array($user['idu'], $this->followings)) {
		    
		    // Select photos posted by user
		    $result = $this->db->query(sprintf("SELECT * FROM user_posts WHERE user_posts.post_by_id = '%s' AND user_posts.post_type = '1' $startup ORDER BY user_posts.post_id DESC LIMIT %s",$this->db->real_escape_string($user['idu']), $limit));

            // Reset
	        $rows = array();
            
            // If posts exists
		    if(!empty($result) && $result->num_rows) {
			
			    // Fetch posts
			    while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			    }
			
			    // Remove last post
			    $loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
			
			    // Start responsive grid
			    $images = $header.'<div class="brz-gallery block-container-3 white-color animate" ><section id="responsive-images-columns">';
			
			    // Reset
			    $x = 0;	
			
			    foreach($rows as $row) {
				
				    // Generate title
					$image_title = sprintf($TEXT['_uni-ttl_trending_img'],readAble($row['post_loves'])).' '.sprintf($TEXT['_uni-getsprint'],($row['post_comments'] > 0) ? sprintf($TEXT['_uni-ttl_trending_img3'],readAble($row['post_comments'])) : '' );
						
					// Allo multiple images
					$listed_images = explode(',', $row['post_content']);
					
					foreach($listed_images as $listed_image) {
					    
						$href = '<a onclick="loadPost('.$row['post_id'].');return false" href="/view/'.$row['post_id'].'"><img class="three-columns hvr-opacity pointer" title="'.$image_title.'" src="/thumb.php?src='.$listed_image.'&fol=c&w=252&h=192"/></a>';
						
						// Build responsive gallery from trending posts
                		if($x == 3) { 
				    		$images .= ' <div class="container">'.$href;
				    		$x = 1;
			    		} elseif($x == 2) {
				    		$images .= $href.'</div>';
			        		$x = 3;
			    		} elseif($x == 0) {
				    		$images .= ' <div class="container">'.$href;
			        		$x++;
			    		} else {
				    		$images .= $href;
			        		$x++;
			    		}
						
						// Last processed id
		        	    $last = $row['post_id'];
					
					}

					
			    }
		
			    $images .= '</section></div>';
				
			    // Add load more function if more results exists
			    $images .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'search('.$last.',6,4);') : closeBody($TEXT['_uni-No_more-results']);

			    // Return gallery
			    return $images;

			} else {
			    
				// No photos yet
			    $id = mt_rand(0,9999).'-rd';
		   	
			    $update = '<script>loadImage("/thumb.php?src='.$this->getImage($current_user['idu'],$user['idu'],$user['p_image'],$user['image']).'&fol=a&w=60&h=60","#'.$id.'");$("#'.$id.'").addClass("circle");</script>';
			
			    return bannerIt('follow-it',sprintf($TEXT['_uni-No_photos_1'],$user['idu'],fixName(35,$user['username'],$user['first_name'],$user['last_name'])),sprintf($TEXT['_uni-No_photos_1_i'],fixName(25,$user['username'],$user['first_name'],$user['last_name'])),$id).$update; // Private user
  		
			}
		} elseif($user['p_posts']) {
		    
			$id = mt_rand(0,9999).'-sd';
		   	
			$update = '<script>loadImage("/thumb.php?src='.$this->getImage($current_user['idu'],$user['idu'],$user['p_image'],$user['image']).'&fol=a&w=60&h=60","#'.$id.'");$("#'.$id.'").addClass("circle");</script>';
			
			return bannerIt('follow-it',sprintf($TEXT['_uni-No_permissions'],$user['idu'],fixName(15,$user['username'],$user['first_name'],$user['last_name'])),sprintf($TEXT['_uni-No_permissions_i'],fixName(25,$user['username'],$user['first_name'],$user['last_name'])),$id).$update; // Private user
  		
		} else {
  			return bannerIt('username-n',$TEXT['_uni-No_username'],sprintf($TEXT['_uni-No_username_s'],$val)); // Username is not in use
  		}
	}

	function searchHashtags($current_user,$from,$val,$date,$type,$scope) {                // Return hashtag search results
		global $TEXT ;
	
		// Set limit
		$limit = $this->settings['search_results_per_page'] + 1;
		
		// Reset
		$rows = array(); $messages='';
		
		// Add starting point and header
		$startup = (is_numeric($from) && $from > 0 ) ? 'AND user_posts.post_id < \''.$this->db->real_escape_string($from).'\'' : '';
		
		// Available date filters
		$DATE_SET = array("0" => "", "1" => "AND CURDATE() = date(user_posts.post_time)","2" => "AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(user_posts.post_time)",	"3" => "AND YEAR(user_posts.post_time) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(user_posts.post_time) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)",);
		
		// Available type filters
		$TYPE_SET = array("0" => "", "1" => "AND user_posts.post_type = 0","2" => "AND user_posts.post_type = 1",	"3" => "AND user_posts.post_type = 4",);
		
		// Available scope filters
		$SCOPE_SET = array("0" => "AND users.p_posts != 1", "1" => "AND user_posts.post_by_id IN(".$this->db->real_escape_string(implode(',', $this->followings)).")",);

		// Select posts
		$results = $this->db->query(sprintf("SELECT * FROM user_posts, users WHERE users.state != 4  $DATE_SET[$date] $TYPE_SET[$type] $SCOPE_SET[$scope] AND user_posts.post_tags REGEXP '[[:<:]]%s[[:>:]]' AND user_posts.post_by_id = users.idu $startup ORDER BY user_posts.post_id DESC LIMIT %s",$this->db->real_escape_string($val),$this->db->real_escape_string($limit)));
	
		// If posts exists
		if(!empty($results) && $results->num_rows) {
			
			// Fetch posts
			while($row = $results->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Remove last post
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;
			
			// From each row generate post
			foreach($rows as $post_row) {
				
				// Get profile picture
				$TEXT['temp-user_img'] = $this->getImage($current_user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']);			
		
				$pictyure = 1;
          
				if($post_row['posted_as'] == 2) {
					$group = $this->getGroup($post_row['posted_at']);	
                	$available = $this->isPostAvailable($post_row,$poster,$current_user['idu'],$group);     // Confirm post privacy						
				} else {
                	$group['group_name'] = '';					
			    	$available = $this->isPostAvailable($post_row,$poster,$current_user['idu']);
				}

				// List post buttons and details
				list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $this->listFunctions($post_row,$current_user,1,1);
		
				// Parse post text remove all smiles
				$TEXT['temp-pt_ns'] = protectXSS($this->parseText($post_row['post_text'],1));

				// Get post type and content
				list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $this->getPostContent($post_row,$post_row['post_id'],$pictyure,null,$this->parseText(protectXSS($post_row['post_text'])));		

				// Page post
				if($post_row['posted_as'] == 1) {
				
					$get_page = $this->getPage($post_row['posted_at']);
				
					$TEXT['temp-page_link'] = ($get_page['page_username']) ? $get_page['page_username'] : $get_page['page_id'];
				
					// Post heading
			    	$TEXT['temp-heading'] = '<a class="h6 b2 dark-font" href="/page/'.$TEXT['temp-page_link'].'" onclick="loadPage('.$get_page['page_id'].',1,1);return false;">'.$get_page['page_name'].'</a>';
				
					// Post id
					$TEXT['temp-id'] = $post_row['post_id'];
					$TEXT['temp-page_id'] = $get_page['page_id'];
					$TEXT['temp-page_icon'] = $get_page['page_icon'];
				
					// Posted time
					$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
				
					// Add post in list
					$content = display(templateSrc('/post/page_post'));
				
				} else {
			
					// Post heading
					list($TEXT['temp-heading'],$TEXT['temp-group_heading']) = $this->getPostHeading($post_row['post_type'],$post_row['posted_as'],$post_row['posted_at'],$post_row['post_extras'],$group['group_name'],$post_row['post_content'],$post_row['gender'],$post_row['post_id']);

					// Post id
					$TEXT['temp-id'] = $post_row['post_id'];

					// Posted time
					$TEXT['temp-time'] = addStamp($post_row['post_time'],2);
					$TEXT['temp-username_c'] = $current_user['username'];
					$TEXT['temp-user_img_c'] = $current_user['image'];
					$TEXT['temp-user_id_c'] = $current_user['idu'];
					$TEXT['temp-user_id'] = $post_row['idu'];
					$TEXT['temp-username'] = $post_row['username'];
					$TEXT['temp-user_name'] = fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']);
					$TEXT['temp-userttl'] = sprintf($TEXT['_uni-Profile_load_text2'],$u_nm);
				
					// Add post in list
					$content = display(templateSrc('/post/user_post'));
			
				}

				// Privacy check
				if($available == 1) {
					$messages .=  $content;
					
					// Last processed id
					$last = $post_row['post_id'];
				} 

			}
			
            // Add load more function if more results exists
			$messages .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'search('.$last.',6,3);') : closeBody($TEXT['_uni-End_of_results']);

			// Return results
			return $messages;
			  
		} else {	
			// Return no posts found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchp2_i'],protectXSS($val)));	
		}
	}
	
	function search($current_user,$from,$val,$home,$liv,$prof,$edu,$limit_set=NULL) {     // Return search results
		global $TEXT ;

		// Trim value
		$trimmed = trim($val);
		
		// Set limit
		$limit = ($limit_set) ? $limit_set : $this->settings['search_results_per_page'] + 1;
		
		$results = array(); 
		
		// Add starting point and header
		if(is_numeric($from) && $from > 0 ) {
	
            // Select search from users
		    $users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4 AND `users`.`verified` != 1 AND `users`.`idu` < %s ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string($from),$limit));

		} else {

			// Select search from users
		    $users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4 AND `users`.`from` LIKE '%s' AND `users`.`living` LIKE '%s' AND `users`.`study` LIKE '%s' AND `users`.`profession` LIKE '%s' ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT %s, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($home).'%', '%'.$this->db->real_escape_string($liv).'%', '%'.$this->db->real_escape_string($edu).'%', '%'.$this->db->real_escape_string($prof).'%',0,$limit));
		}
		
		if(!empty($users)) {
			while($row = $users->fetch_assoc()) {
				$results[] = $row;
		    }
		}		

		// Check whether more results exists
		$loadmore = (array_key_exists($limit-1, $results)) ? array_pop($results) : NULL ;
		
		$people = (is_numeric($from) && $from > 0 || $limit_set) ? '' : '<div id="search-results-main-box" class="brz-new-container-search">';
		
		// If results exists
		if(!empty($results)) {

		    $template = display(templateSrc(($limit_set) ? '/user/search/in_box' : '/user/search/main'),0,1);

		    foreach($results as $row) {
				
                if (Blocker::IsTypeBlocked(Blocker::GetBlock($current_user['idu'], $row['idu']), 'search')) {
					$last = $row['idu'];
					continue;
                }

				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);

				// Generate accordion data according to permissions
				list($d1,$d2,$d3) = $this->genAccordData($following,$row);			
				
				// Count New posts
				$n_posts = ($following == 1) ? $this->numberNewPosts($row['idu'],$current_user['onfeeds']) : NULL ;

				// Add user to list
				$people .= listMainUser($row['idu'],fixName(35,$row['username'],$row['first_name'],$row['last_name']),$row['username'],$this->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']),sprintf($TEXT['_uni-Profile_load_text2'],fixName(35,$row['username'],$row['first_name'],$row['last_name'])),$this->verifiedBatch($row['verified']),$d1,$d2,$d3,$this->getRelationButton($following,$row['idu'],$pri),'',$template);
			
				// Set last processed id	
				$last = $row['idu'];
				
			}
	
            // Add load more function if more results exists
			if(!$limit_set){	
				$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'search('.$last.',27,1);') : closeBox($TEXT['_uni-End_of_results']);     
 		    } else {
				$people .='	<div onclick="moveSearch(1);scrollToTop();" class="clear pointer padding-10 center h5 b2 tin-light-font" >
                                '.$TEXT['_uni-See_more'].'
	                        </div>';
			}
			
			$people .= (is_numeric($from) && $from > 0 || $limit_set) ? '' : '</div>';
		
			// Return results
			return $people;
			
		} else {	
			// Return no users found
		    $fb_icon = ($limit_set) ? 1 : NULL;
			
			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchp3_i'],protectXSS($val)),4235,$fb_icon);
		
		}
	}

	function searchVideos($current_user,$from,$val,$limit_set=NULL) {                 // Return Video search results
		global $TEXT ;

		// Trim value
		$trimmed = trim($val);
		
		// Set limit
		$limit = ($limit_set) ? $limit_set : $this->settings['search_results_per_page'] + 1;
		
		// Reset
		$results = array();$TEXT['temp-videos_all'] = ''; 
		
		$add_from = (is_numeric($from) && $from > 0 ) ? 'AND `user_posts`.`post_id` < \''.$this->db->real_escape_string($from).'\'' : '';
	
		// Select search groups
		$videos = $this->db->query(sprintf("SELECT * FROM `users`, `user_posts` WHERE `user_posts`.`post_text` LIKE '%s' AND `user_posts`.`post_type` = '4' AND `users`.`p_posts` = '0' AND `users`.`idu` = `user_posts`.`post_by_id` $add_from ORDER BY `user_posts`.`post_id` DESC LIMIT %s", '%'.$this->db->real_escape_string($val).'%', $limit));

		if(!empty($videos)) {
			while($row = $videos->fetch_assoc()) {
				$results[] = $row;
		    }
		}		

		// Check whether more results exists
		$loadmore = (array_key_exists($limit-1, $results)) ? array_pop($results) : NULL ;	
		
		// Load template src
		$t_src = templateSrc('SRC',1);
		
		// If results exists
		if(!empty($results)) {
			
		    // Load template
		    $vd_js_tpl = display($t_src.'/search/videos_search/video_js'.$TEXT['templates_extension'],0,1);
		    $vd_tpl = display( ($limit_set) ? $t_src.'/search/videos_search/video_inbox'.$TEXT['templates_extension']:$t_src.'/search/videos_search/video'.$TEXT['templates_extension'],0,1);
			
		    foreach($results as $row) {

				// Add video to list
			    $TEXT['temp-videos_all'] .= listVideo($row['post_id'],'Loading ...','default.jpg',sprintf($TEXT['_uni-Shared_y_sprint'],fixName(25,$row['username'],$row['first_name'],$row['last_name'])),$vd_tpl);
			   
                // Add video fetihing js function
				$TEXT['temp-post_content'] = $row['post_content'];
				$TEXT['temp-post_id'] = $row['post_id'];
				
				$TEXT['temp-videos_all'] .= display('',$vd_js_tpl);
				
				// Set last processed id	
				$last = $row['post_id'];
				
			}
		    	
            // Add load more function if more results exists
			if(!$limit_set){		
				$TEXT['temp-videos_all'] .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'search('.$last.',27,6);') : closeBox($TEXT['_uni-End_of_results']);       
 		    } else {
				$TEXT['temp-videos_all'] .= display($t_src.'/search/videos_search/move_search'.$TEXT['templates_extension']);
			}
			
			// Return results
			return (is_numeric($from) && $from > 0 || $limit_set) ? $TEXT['temp-videos_all'] : display($t_src.'/search/videos_search/combine'.$TEXT['templates_extension']);
			
		} else {	
		
		    $fb_icon = ($limit_set) ? 1 : NULL;
			
			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchg3_asd'],protectXSS($val)),45,$fb_icon);			
		}
	}

	function searchGroups($current_user,$from,$val,$typ,$desc,$loc,$limit_set=NULL) { // Return group search results
		global $TEXT,$page_settings ;

		// Trim value
		$trimmed = trim($val);
		
		// Set limit
		$limit = ($limit_set) ? $limit_set : $page_settings['group_results_per_page'] + 1;
		
		$results = array(); 
		
		$add_from = (is_numeric($from) && $from > 0 ) ? 'AND `groups`.`group_id` < \''.$this->db->real_escape_string($from).'\'' : '';
		
		// Add group types
		$type_set = array("0" => "1,2", "1" => "1", "2" => "2");
	
	    // Get group type
		$group_type = (isset($type_set[$typ])) ? $type_set[$typ] : '1,2';
		
		// Select search groups
		$groups = $this->db->query(sprintf("SELECT * FROM `groups` WHERE (`groups`.`group_name` LIKE '%s' OR concat_ws(' ', `groups`.`group_description`, `groups`.`group_location`) LIKE '%s') AND `groups`.`group_description` LIKE '%s' AND `groups`.`group_location` LIKE '%s' AND `groups`.`group_privacy` IN(%s) $add_from ORDER BY `groups`.`group_id` DESC LIMIT %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($desc).'%', '%'.$this->db->real_escape_string($loc).'%', $this->db->real_escape_string($group_type) ,$limit));

		if(!empty($groups)) {
			while($row = $groups->fetch_assoc()) {
				$results[] = $row;
		    }
		}		

		// Check whether more results exists
		$loadmore = (array_key_exists($limit-1, $results)) ? array_pop($results) : NULL ;
		
		$groups_all = (is_numeric($from) && $from > 0 || $limit_set) ? '' : '<div id="search-results-main-box" class="brz-new-container-search">';
		
		// If results exists
		if(!empty($results)) {

		    $template = display(templateSrc(($limit_set) ? '/search/groups_search/in_box' : '/search/groups_search/main'),0,1);
		   
		    foreach($results as $row) {
				
		        // Fetch group user
		        $group_user = $this->getGroupUser($current_user['idu'],$row['group_id']);
		
				// Is joined
				if($group_user['group_status'] == 1) {
					$joined = ($group_user['group_role'] == "2") ? 2 : 1;	
				} else {
					$joined = 0 ;
				}
		
		        // Generate permissions
				list($available,$following,$private) = $this->getPermissions($current_user['idu'],$row['group_owner'],$row['group_privacy'],$joined,$group_user['group_status']);
		
				// Add user to list
				$groups_all .= listMainUser($row['group_id'],$row['group_name'],'',getCoverSrc(str_replace('rep_','',$row['group_cover'])),'','',sprintf($TEXT['_uni-ttl_group_members_ttl'],readable($row['group_users'])),$row['group_location'],fixText(45,$row['group_description']),$this->getGroupButton($following,$row['group_id'],$private,1),1,$template);
			
				// Set last processed id	
				$last = $row['group_id'];
				
			}
		    	
            // Add load more function if more results exists
			if(!$limit_set){
			
				$groups_all .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'search('.$last.',27,2);') : closeBox($TEXT['_uni-End_of_results']);
           
 		    } else {
				$groups_all .='	<div onclick="moveSearch(2);scrollToTop();" class="clear pointer padding-10 center h5 b2 tin-light-font" >
                                '.$TEXT['_uni-See_more'].'
	                             </div>';
			}
			
			$groups_all .= (is_numeric($from) && $from > 0 || $limit_set) ? '' : '</div>';
		
			// Return results
			return $groups_all;
			
		} else {	
		
		    $fb_icon = ($limit_set) ? 1 : NULL;
			
			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchg3_i'],protectXSS($val)),45,$fb_icon);			
		}
	}
	
	function searchPages($current_user,$from,$val,$typ,$desc,$loc,$limit_set=NULL) {  // Return group search results
		global $TEXT ;

		// Trim value
		$trimmed = trim($val);
		
		// Set limit
		$limit = ($limit_set) ? $limit_set : $this->settings['search_results_per_page'] + 1;
		
		// Reset
		$results = array();$TEXT['temp-pages_all'] = ''; 
		
		$add_from = (is_numeric($from) && $from > 0 ) ? 'AND `pages`.`page_id` < \''.$this->db->real_escape_string($from).'\'' : '';
		
		// Add group types
		$type_set = array("0" => "1,2,3,4,5,6", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6");
	
	    // Get pages type
		$pages_type = (isset($type_set[$typ])) ? $type_set[$typ] : '1,2,3,4,5,6';
		
		// Select search pages
		$pages = $this->db->query(sprintf("SELECT * FROM `pages` WHERE (`pages`.`page_name` LIKE '%s' OR concat_ws(' ', `pages`.`page_description`, `pages`.`page_location`) LIKE '%s') AND `pages`.`page_description` LIKE '%s' AND `pages`.`page_location` LIKE '%s' AND `pages`.`page_cat` IN(%s) $add_from ORDER BY `pages`.`page_verified` DESC, `pages`.`page_id` DESC LIMIT 0, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($desc).'%', '%'.$this->db->real_escape_string($loc).'%', $this->db->real_escape_string($pages_type) ,$limit));
		
		if(!empty($pages)) {
			while($row = $pages->fetch_assoc()) {
				$results[] = $row;
		    }
		}		

		// Check whether more results exists
		$loadmore = (array_key_exists($limit-1, $results)) ? array_pop($results) : NULL ;
		
		// Load template src
		$t_src = templateSrc('SRC',1);

		// If results exists
		if(!empty($results)) {

		    // Load template
		    $pg_tpl = display(($limit_set) ? $t_src.'/search/pages_search/page_inbox'.$TEXT['templates_extension'] : $t_src.'/search/pages_search/page'.$TEXT['templates_extension'],0,1);
			
		    foreach($results as $row) {
				
				// Set data for template
				$TEXT['temp-page_id'] = $row['page_id'];
				$TEXT['temp-page_icon'] = $row['page_icon'];
				$TEXT['temp-page_name_25'] = fixText(25,$row['page_name']);
				$TEXT['temp-verified_batch'] = $this->verifiedBatch($row['page_verified']);
				$TEXT['temp-page_category'] = nameCategory($row['page_cat'],$row['page_sub_cat'],$this->getCategories(1),$this->db);
				$TEXT['temp-page_likes'] = readAble($row['page_likes']).' '.$TEXT['_uni-likes_this'];
				$TEXT['temp-page_description'] = fixText(125,$row['page_description']);

				// Add user to list
				$TEXT['temp-pages_all'] .=  display('',$pg_tpl);

				// Set last processed id	
				$last = $row['page_id'];
				
			}
		    	
            // Add load more function if more results exists
			if(!$limit_set){
				$TEXT['temp-pages_all'] .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'search('.$last.',27,7);') : closeBox($TEXT['_uni-End_of_results']);    
 		    } else {
				$TEXT['temp-pages_all'] .= display($t_src.'/search/pages_search/move_search'.$TEXT['templates_extension']);
			}
			
			// Return results
			return (is_numeric($from) && $from > 0 || $limit_set) ? $TEXT['temp-pages_all'] : display($t_src.'/search/pages_search/combine'.$TEXT['templates_extension']);
			
		} else {	
		
		    $fb_icon = ($limit_set) ? 1 : NULL;
			
			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchg3_i2'],protectXSS($val)),45,$fb_icon);			
		}
	}
	
	function searchMembers($current_user,$val,$content_id,$type = 0,$vt=null) {       // Return search results for chats
		
		global $TEXT;
		// TYPE 0 : Search in members
		// TYPE 1 : Search in followings
		// TYPE 2 : Search in followings to add in group
		
		// Reset
		$results = array();
		
		// Get followings
		$friends = implode(',',$this->followings);
		
	    // Check whether form is available
		$content = ($type > 1) ? $this->getGroup($content_id) : $this->getChatFormByID($content_id,$current_user['idu']);
		
		// Valid index
		$index = ($type > 1) ? 'group_id' : 'form_id';
		
		// Get form members
		$members = $this->listMembers($content[$index],$type);
		
		$limit = ($vt == 'SUGGESTIONS') ? 4 : 10;
		
		if($type > 1) {
			
			if($type == 2) {
			    
				// Select search from users to add in group
			    $users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`idu` IN(%s) AND `users`.`state` != 4 ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT %s, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string($friends),0,$limit));
			
			    // Set JS function 
			    $function = '1';
			
			} else {
			
				// Select search from users to add in group
			    $users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`idu` IN(%s) AND `users`.`state` != 4 ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT %s, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string(implode(',',$members)),0,10));
			
			    // Set JS function 
			    $function = '0';
			}
			
		} elseif($type == 0) {
			
			// Select search from users
			$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`idu` IN(%s) AND `users`.`state` != 4 ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT %s, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string(implode(',',$members)),0,5));
			
			// Set JS function 
			$function = '0';
			
		} else {
			
			// Select search from users
			$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`idu` IN(%s) AND `users`.`state` != 4 ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT %s, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string($friends),0,5));
			
			// Set JS function 
			$function = '1';
			
		}
		
		if(!empty($users)) {
			while($row = $users->fetch_assoc()) {
				if($type == 1 || $type == 2) {
					if(!in_array($row['idu'],$members)) {
						$results[] = $row;
					}
				} else {
					$results[] = $row;
				}
		    }
		}
		
		// If results exists
		if(!empty($results)) {

			$people = '';
			
			// Load template
		    $mem_tpl = display(templateSrc('/search/members_search/member'),0,1);
		
		    foreach($results as $row) {
				
				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);
				
				// Results design type
				if($vt=='SUGGESTIONS') {
					$properties = array('suggestions_dynamic_view_'.$row['idu'],'w=26&h=26','','','','',',1');
				} else {
					$properties = array('messenger_dynamic_view_'.$row['idu'],'w=70&h=70','','','',$this->isValue($row['followers'],$TEXT['_uni-Followers']),'');
				}
				
				// Set properties
				foreach($properties as $count=>$val) {
					$TEXT['temp-prop-'.$count] = $val;
				}
				
				// Add user to list
				$js_function = ($type > 1) ? 'editGroupMember' : 'editMember';
				
				// Create button
				$TEXT['temp-button'] = ($type == 2 || $type == 1) ? '<button class="btn btn-small btn-light" onclick="'.$js_function.'('.$content[$index].','.$row['idu'].','.$function.$properties[6].');">'.$properties[4].''.$TEXT['_uni-Add_member'].'</button>' : '<button class="btn btn-small btn-light" onclick="'.$js_function.'('.$content[$index].','.$row['idu'].','.$function.$properties[6].');">'.$TEXT['_uni-Remove'].'</button>';
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-user_image'] = $row['image'];
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(32,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_20'] = fixName(20,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-verified_batch'] = $this->verifiedBatch($row['verified'],1);
				
				// Add to list
				$people .= display('',$mem_tpl);
				
			}
			
			// Return results
			return $people;
			
		} else {	
			// Return no users found
			return ($vt == 'SUGGESTIONS') ? '' : showBox($TEXT['_uni-exec_34534']);		
		}
	}
	
	function searchInvites($current_user,$val,$content) {     // Return search results for page invites
		global $TEXT ;

		// Reset
		$results = array();
		
		// Get followings
		$friends = implode(',',$this->followings);
		
		// Valid index
		$index = 'page_id';
		
		// Get page likers
		$likers = $this->listLikers($content[$index]);

		$limit = 4 ;

		$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`idu` NOT IN(%s) AND `users`.`state` != 4 ORDER BY `users`.`verified` DESC, `users`.`idu` DESC LIMIT %s, %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$this->db->real_escape_string(implode(',',$likers)),0,$limit));

		if(!empty($users)) {
			while($row = $users->fetch_assoc()) {

				if(in_array($row['idu'],$this->followings)) {
					$results[] = $row;
				}
				
		    }
		}
		
		// If results exists
		if(!empty($results)) {

			$people = '';

			// Load user template
			$usr_tpl = display(templateSrc('SRC',1).'/page/page_likes/invite'.$TEXT['templates_extension'],0,1);

		    foreach($results as $row) {
				
				// Generate permissions
				list($available,$following,$pri) = $this->getPermissions($current_user['idu'],$row['idu'],$row['p_private']);

				// Set data for template
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-user_image'] = $row['image'];
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(32,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-user_verified_batch'] = $this->verifiedBatch($row['verified'],1);
				$TEXT['temp-invite_cnt'] = $content[$index];
				
                // Add user to list
				$people .= display('',$usr_tpl);
				
			}
			
			// Return results
			return $people;
			
		} else {	
			return '';		
		}
	}
	
    function parseText($text,$type=NULL) {                    // Parse URLs,@mentions,#hashtags for output
		global $TEXT,$emoji_ids,$emoji_files;
		
		// Include emojis list
		require_once(__DIR__ . '/presets/preset_emojis.php');	
		
		// Type 1 remove emojis and dynamic line breaks
		if($type) {  
			foreach($emoji_ids as $key=>$emj) {
				$text = str_replace($emj,'', $text);
			}
			$text = str_replace("{{LINE-BREAK}}",'', $text);
			return $text;
		}
		
		// Replace line breaks
		$text = str_replace("{{LINE-BREAK}}",'<br>', $text);

		// Remove multiple line breaks to max 2
		$text = preg_replace('#(<\s*br[^/>]*/?\s*>\s*){3,}#is', "<br><br>", $text);

		// Parse links
		$parsed_urls = preg_replace_callback('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', "linkHref", $text);
		
		// Get mentions type
		$type = ($this->settings['mentions_type']) ? '$2 !' : '@$2';
		
		// Parse mentions and hashtags
		$parsedMessage = preg_replace(array('/(^|[^a-z0-9_])([a-z0-9_]+)!/i','/(^|[^a-z0-9_])#(\w+)/u'), array('$1<a class="h6 b2 theme-font" href="$2">'.$type.'</a>','$1<a class="h6 b2 theme-font" href="/search/tag/$2">#$2</a>'), preg_replace('/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '$1<a class="h6 b2 theme-font" href="$2">'.$type.'</a>', $parsed_urls));
		
		// Parse emojis
		foreach($emoji_ids as $key=>$emj) {
			$parsedMessage = str_replace($emj,'<img src="/themes/'.$TEXT['theme'].'/img/emojis/'.$emoji_files[$key].'.png" height="18" width="18" data-emoji="'.str_replace(array("}","{"),array("",""),$emoji_ids[$key]).'">', $parsedMessage);
		}
		
		return $parsedMessage;
	}

    function addMentions($text,$c_user,$type,$fix=NULL) {     // Parse both mentions! || @mentions
		// c_id : current user id 
		// type : pending section id
		global $TEXT,$page_settings;
		
		$c_id = $c_user['idu'];

		// Select mentions!
		preg_match_all('/(\w+!)/u', str_replace(array('\r', '\n'), ' ', $text), $mentions);
		
		// Select @mentions
		preg_match_all('/(@\w+)/u', str_replace(array('\r', '\n'), ' ', $text), $mentions2);

		// Send notifications for each mention!
		if(!empty($mentions[0])) {
			
			// Save time to make MySQL queries fast
			$time = time();
			
			foreach($mentions[0] as $mention) {
				
				// Get mentioned user
				$mentioned = $this->getUserByUsername(str_replace('!', '', $mention));
				
				// If user exists
				if(!empty($mentioned['idu'])) {
					
					// Confirm privacy
					if(!$fix && (!$mentioned['p_mention'] || in_array($mentioned['idu'],$this->followings)) && $c_id !== $mentioned['idu']) {
						
						// If user had enabled notifications on mentions
						if($mentioned['n_mention']) {
	                        
							// Check whether notification is already added for this user
							$isMentioned = $this->db->query(sprintf("SELECT * FROM `notifications` WHERE `not_type` = '%s' AND `not_to` = '%s' AND `not_from` = '%s' AND `not_time` > '$time' ",$this->db->real_escape_string($type),$this->db->real_escape_string($mentioned['idu']),$this->db->real_escape_string($c_id)));
	 
	                        if($isMentioned->num_rows == 0) {
							    
								// Add a notification to pending section id (generated at start)
							    $this->db->query(sprintf("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '%s', '%s', '0','0','%s','0', CURRENT_TIMESTAMP) ;",$this->db->real_escape_string($c_id),$this->db->real_escape_string($mentioned['idu']),$this->db->real_escape_string($type)));	
							
								// Send new mention email if user has enable email notifications
			                    if($mentioned['e_mention']) {							   
				                    mailSender($page_settings, $mentioned['email'], $TEXT['_uni-new_mention_ee_ttl_sml'], sprintf($TEXT['_uni-new_mention_ee_ttl'], fixName(35,$mentioned['username'],$mentioned['first_name'],$mentioned['last_name']), $c_user['username'], fixName(35,$c_user['username'],$c_user['first_name'],$c_user['last_name'])), $TEXT['web_mail']);
			                    }
							
							}
						}
						
					// Remove mention! if user has no rights to mention target user
					} else {
						$text = str_replace($mention, str_replace('!', '', $mention), $text);
					}
                 
                // Remove mention if username doesn't exists				 
				} else {
					$text = str_replace($mention, str_replace('!', '', $mention), $text);
				}
			}
		}
	
		// Just add mention if available
		if(!empty($mentions2[0])) {
			foreach($mentions2[0] as $mention) {
				
				// Get mentioned user
				$mentioned = $this->getUserByUsername(str_replace('@', '', $mention));
				
				// If user exists
				if(!empty($mentioned['idu'])) {
					
					// Remove mention if user has no rights to mention target user
					$text = (!$mentioned['p_mention'] || $c_id == $mentioned['idu'] || in_array($mentioned['idu'],$this->followings)) ? $text : str_replace($mention, str_replace('@', '', $mention), $text);	

				// Remove @mention if username doesn't exists
				} else {
					$text = str_replace($mention, str_replace('@', '', $mention), $text);
				}
			}
		}
		
		// Return parsed text
		return $text ;
	}

}

class pages {           // Page management
	
	// Properties
	public $db;                   // Database connection
	public $username;             // Username property
	public $password;             // Password property
	public $followings;           // Logged user followers (ARRAY)
	public $followers;            // Logged user followings (ARRAY)
	public $settings;             // Administration settings
	public $admin;                // Administration detect
		
	function isRoleExists($pid,$name) {                    // Return true if role exists
		
		$role = $this->db->query(sprintf("SELECT * FROM `page_roles` WHERE `page_roles`.`user_id` = '%s' AND `page_roles`.`page_id` = '%s'", $this->db->real_escape_string(strtolower($name)), $this->db->real_escape_string($pid)));	
		
		// Return true if role exists
		return (!empty($role) && $role->num_rows) ? 1 : 0;
		
	}
	
	function addRole($pid,$role_type,$user_id,$by_id) {                          // Add new page role
		
		// Add page role
		$this->db->query(sprintf("INSERT INTO `page_roles` (`pid`, `user_id`, `page_id`, `page_partner_id`, `page_role`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",  $this->db->real_escape_string($user_id), $this->db->real_escape_string($pid),$this->db->real_escape_string($by_id),$this->db->real_escape_string($role_type)));
	
	    // Insert into page log
	    $this->db->query(sprintf("INSERT INTO `page_logs` (`id`, `page_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($pid),$this->db->real_escape_string($by_id),$this->db->real_escape_string($user_id),1));		
	
		// Return true if role exists
		return '<script>updatePageSNav(2);loadPage('.$pid.',11,5,2,0);scrollToTop();</script>';
		
	}
    
	function deleteRole($pid,$page_id,$removed_id,$uid) {         // Delete Page role
	    
		$this->db->query(sprintf("DELETE FROM `page_roles` WHERE `pid` = '%s' AND `page_id` = '%s' ", $this->db->real_escape_string($pid), $this->db->real_escape_string($page_id)));
	
	    // Insert into page log
	    $this->db->query(sprintf("INSERT INTO `page_logs` (`id`, `page_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);",$this->db->real_escape_string($page_id),$this->db->real_escape_string($uid),$this->db->real_escape_string($removed_id),2));		

	}
	
	function sendInvite($page_id,$sid,$uid) {         // Delete Page role
	    
		// Add notification to user about like
		$this->db->query(sprintf("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '%s', '%s', '%s','0','14','0', CURRENT_TIMESTAMP);",$this->db->real_escape_string($uid),$this->db->real_escape_string($sid),$this->db->real_escape_string($page_id))) ;
	    
	}

    function getPageRoleTitle($page_role,$user_id,$founder) {
		return ($founder == $user_id) ? '_uni-Founder' : '_uni-Page_role-'.$page_role;	
	}
	
    function pageRoles($page,$page_role,$from,$current_user) {
        global $TEXT ;

		// Set limit
		$limit = $this->settings['search_results_per_page'] + 1;
		
		$results = array(); 
		
		// Add starting point and header
		$add_from = (is_numeric($from) && $from > 0) ? 'AND `page_roles`.`pid` < \''.$this->db->real_escape_string($from).'\'' : '';
		
		$roles = $this->db->query(sprintf("SELECT * FROM `page_roles`, `users` WHERE `page_roles`.`page_id` = '%s' AND `page_roles`.`user_id` = `users`.`idu` AND `users`.`state` != 4  $add_from ORDER BY `page_roles`.`pid` DESC LIMIT %s", $this->db->real_escape_string($page['page_id']), $this->db->real_escape_string($limit)));
		
		if(!empty($roles)) {
			while($row = $roles->fetch_assoc()) {
				$results[] = $row;
		    }
		}		

		// Check whether more results exists
		$loadmore = (array_key_exists($limit-1, $results)) ? array_pop($results) : NULL ;
		
		$people = (is_numeric($from) && $from > 0) ? '' : '<div id="search-results-main-box" class="brz-new-container-search">';
		
		// If results exists
		if(!empty($results)) {
			
			$main = new main();
		    $main->db = $this->db;
		    $main->followings = $this->followings;

			// Load src
			$t_src = templateSrc('SRC',1);
			
			// Role template
			$role_tpl = display($t_src.'/page/page_roles/role_main'.$TEXT['templates_extension'],0,1);
			// Delete button template
			$btn_tpl = display($t_src.'/page/page_roles/del_btn'.$TEXT['templates_extension'],0,1);
			
		    foreach($results as $row) {

				// Add page role
				$role_index = $this->getPageRoleTitle($row['page_role'],$row['user_id'],$page['page_owner']);
				$TEXT['temp-add_role'] = $TEXT[$role_index];
				
				// If founder
				if($role_index == '_uni-Founder') {
					
					$TEXT['temp-add_dat1'] = addStamp($page['time'],4);
					
					$TEXT['temp-add_dat2'] = '';
					
				} else {
					
					$TEXT['temp-add_dat1'] = $TEXT['_uni-Added'].' '.addStamp($row['time'],4);
					
					$get_parent = $main->getUserByID($row['page_partner_id']);
					
					$TEXT['temp-add_dat2'] = $TEXT['_uni-By'].' '.fixName(15,$get_parent['username'],$get_parent['first_name'],$get_parent['last_name']);
					
				}   

				// Set content for template
				$TEXT['temp-pid'] = $row['pid'];
				$TEXT['temp-page_id'] = $page['page_id'];
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-user_image'] = $main->getImage($current_user['idu'],$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-user_verified_batch'] = $main->verifiedBatch($row['verified']);
				
				// Add removeable buttons
				$TEXT['temp-del_btn'] = ($page_role < 4 || $role_index == '_uni-Founder') ? '' : display('',$btn_tpl);
				
				// Add role to list
				$people .= display('',$role_tpl);
			
				// Set last processed id	
				$last = $row['pid'];
				
			}

			// Add load more
			$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-Load_more'],'loadPage('.$page['page_id'].',11,27,2,'.$last.');') : closeBox($TEXT['_uni-No_more_users']);
           
			$people .= (is_numeric($from) && $from > 0) ? '' : '</div>';
		
			// Return results
			return $people;
			
		} else {	

			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchp3_i'],protectXSS($val)),4235,1);
		
		}
	}	
	
	function getAllPages($ids,$limit,$from=0) {                // Return user pages followings
	    global $TEXT;

		// If post exists
		if(!empty($ids)) {
			
			// Reset
			$rows = array();$TEXT['temp-add_page_list'] = '';$i = 1;
			
			// Set start up
			$add_from = ($from > 0) ? 'AND `pages`.`page_id` < \''.$this->db->real_escape_string($from).'\'' : '';	

			// Select data
			$result = $this->db->query(sprintf("SELECT * FROM `pages` WHERE `pages`.`page_id` IN(%s) $add_from ORDER BY `pages`.`page_id` DESC LIMIT $limit ",$this->db->real_escape_string($ids)));

			// Selected
			$counts = $result->num_rows;

			// If post and user exists
			if(!empty($result) && $counts !== 0) {
				
				// Load template src
				$t_src = templateSrc('SRC',1);
		        $page_tpl = display($t_src.'/page/page_feeds/page'.$TEXT['templates_extension'],0,1);
	   			
				// Fetch data
				while($row = $result->fetch_assoc()) {
			        $rows[] = $row;
				}
				
				// Check for more results
				$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;	

				foreach($rows as $row) {

					// Remov row from count too
					$counts = ($loadmore) ? $counts - 1 : $counts;
					
					// Set data for template
					$TEXT['temp-add_tag_br'] = ($i == $counts && !$loadmore) ? '<br>' : '';
					$TEXT['temp-page_id'] = $row['page_id'];
					$TEXT['temp-page_icon'] = $row['page_icon'];
					$TEXT['temp-page_name'] = fixText(25,$row['page_name']);
					
                    // Add page to list		
				    $TEXT['temp-add_page_list'] .= display('',$page_tpl);
					
					// Set last processed id
				    $TEXT['temp-add_last_id'] = $row['page_id'];
					
					$i++;
				}
				
				// Add load more function if set					
				$TEXT['temp-add_page_list'] .= ($loadmore) ? display($t_src.'/page/page_feeds/load_more'.$TEXT['templates_extension']) : '' ;	
			
			}
		
			// Inclose and return
			return ($from == 0 && !empty($TEXT['temp-add_page_list'])) ? display($t_src.'/page/page_feeds/combined_container'.$TEXT['templates_extension']) : $TEXT['temp-add_page_list'];
		} 
	}
	
	function listPages($user_id) {                // Return user groups joined
		
		$result = $this->db->query(sprintf("SELECT `page_id` FROM `page_users` WHERE `page_users`.`user_id` = '%s' AND `page_users`.`f_feeds` = '1' ", $this->db->real_escape_string($user_id)));	

		$list = array();	
		
		if(!empty($result) && $result->num_rows !== 0) {
			
			// return array of user IDs if users exists
			while($row = $result->fetch_assoc()) {
			    $list[] = $row['page_id'];
		    }
			
			// Return ARRAY
			return implode(',',$list);
			
		} else {
			return '';
		}	
	}
	
	function createPage($owner,$v1,$v2,$v3,$location){               // Create new page
	
	    // Insert page
	    $query = sprintf("INSERT INTO `pages` (`page_id`, `page_username`, `page_name`, `page_icon`, `page_cover`, `page_owner`, `page_cat`, `page_sub_cat`, `page_location`, `page_description`, `page_email`, `page_web`, `page_likes`, `page_follows`, `time`) VALUES (NULL, '', '%s', 'default.png', 'default.png', '%s', '%s', '%s', '%s', '', '', '', '0', '1', CURRENT_TIMESTAMP);",  $this->db->real_escape_string($v1), $this->db->real_escape_string($owner), $this->db->real_escape_string($v2), $this->db->real_escape_string($v3), $this->db->real_escape_string($location)) ;

		// Return results  
		if ($this->db->query($query) === TRUE) {
		
			// Get new group id
			$new_page = $this->db->insert_id ;
			
			// Add group role
			$this->db->query(sprintf("INSERT INTO `page_roles` (`pid`, `user_id`, `page_id`, `page_partner_id`, `page_role`, `time`) VALUES (NULL, '%s', '%s', '%s', '5', CURRENT_TIMESTAMP);",  $this->db->real_escape_string($owner), $this->db->real_escape_string($new_page),$this->db->real_escape_string($owner)));
			
			// Add in feeds
			$this->db->query(sprintf("INSERT INTO `page_users` (`pfid`, `user_id`, `page_id`, `f_feeds`, `time`) VALUES (NULL, '%s', '%s', '1', CURRENT_TIMESTAMP);",  $this->db->real_escape_string($owner), $this->db->real_escape_string($new_page)));			
			
		    // Here is the new line
		    $this->db->query(sprintf("UPDATE `users` SET `page_feeds` = '%s' WHERE `idu` = '%s'", $this->listPages($owner) ,$this->db->real_escape_string($owner)));
 
            return $new_page;
		
		} else {
			
			return NULL;
		}
	}
		
	function pageAct($page,$user,$page_user,$type) {                        // Join | Leave | Undo | Request | Groups
	    global $TEXT;
	
		// If targets exists
		if($page['page_id'] && $user['idu']) {	
		  
			//  Escape variables for MySQL Query
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$page_id_esc = $this->db->real_escape_string($page['page_id']);
			
			$subscribe = (substr_count($user['page_feeds'], ',') < 2999) ? '1' : '0' ;
			
			// Like page
			if($type == 1) {
			   
				$query = "INSERT INTO `page_likes` (`id`, `page_id`, `by_id`, `time`) VALUES (NULL, '$page_id_esc', '$user_id_esc', CURRENT_TIMESTAMP)";
			    
				// If like added
				if($this->db->query($query) === TRUE) {
					
					// Update members count
					$likes_count = countLikes($page['page_id'],$this->db);
					
					$likes_count = ($likes_count > 0) ? $this->db->real_escape_string($likes_count) : 0;
					
					$this->db->query("UPDATE `pages` SET `pages`.`page_likes` = '$likes_count' WHERE `pages`.`page_id` = '$page_id_esc'");	
					
				}
				
			// Unlike page
			} elseif($type == 0) {

			    $query = "DELETE FROM `page_likes` WHERE `page_likes`.`by_id` = '$user_id_esc' AND `page_likes`.`page_id` = '$page_id_esc'";

				// If like removed
				if($this->db->query($query) === TRUE) {
					
					// Update members count
					$likes_count = countLikes($page['page_id'],$this->db);
					
					$likes_count = ($likes_count > 0) ? $this->db->real_escape_string($likes_count) : 0;
					
					$this->db->query("UPDATE `pages` SET `pages`.`page_likes` = '$likes_count' WHERE `pages`.`page_id` = '$page_id_esc'");	
					
				}
				
			// Show in feeds (Subscribe page feeds)
			} elseif($type == 6) { 
			   
				$query = sprintf("INSERT INTO `page_users` (`pfid`, `user_id`, `page_id`, `f_feeds`, `time`) VALUES (NULL, '%s', '%s', '1', CURRENT_TIMESTAMP);",  $user_id_esc, $page_id_esc);
			
				// If user has been updated
				if (substr_count($user['page_feeds'], ',') < 2999 && $this->db->query($query) === TRUE) {
					
					$query = sprintf("UPDATE `users` SET `page_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listPages($user['idu'],1));
			    
				    if($this->db->query($query) === TRUE) {
						
						// Update follower count
						$follows_count = countFollows($page['page_id'],$this->db);
						
						$follows_count = ($follows_count > 0) ? $this->db->real_escape_string($follows_count) : 0;
					
						$this->db->query("UPDATE `pages` SET `pages`.`page_follows` = '$follows_count' WHERE `pages`.`page_id` = '$page_id_esc'");	
					 
						return '<script>loadPage('.$page['page_id'].',1,1);</script>'; 
					} else {
						return '<script>loadPage('.$page['page_id'].',1,1);</script>'.showError($TEXT['lang_error_connection']) ;
					} 
					
					
				} else {
					return '<script>loadPage('.$page['page_id'].',1,1);</script>'.showError($TEXT['_uni-Crossed_page_limit']);
				}
				
			// Hide in feeds (unSubscribe page feeds)
			} elseif($type == 7) {
			   
				$query = "DELETE FROM `page_users` WHERE `page_users`.`user_id` = '$user_id_esc' AND `page_users`.`page_id` = '$page_id_esc'";
			    
				// If user has been updated
				if ($this->db->query($query) === TRUE) {
					
					$query = sprintf("UPDATE `users` SET `page_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listPages($user['idu'],1));
			    
				    // If removing follower confirmed
					if($this->db->query($query) === TRUE) {
						
						// Update follower count
						$follows_count = countFollows($page['page_id'],$this->db);
						
						$follows_count = ($follows_count > 0) ? $this->db->real_escape_string($follows_count) : 0;
					
						$this->db->query("UPDATE `pages` SET `pages`.`page_follows` = '$follows_count' WHERE `pages`.`page_id` = '$page_id_esc'");	
					 
						return '<script>loadPage('.$page['page_id'].',1,1);</script>'; 
					} else {
						return '<script>loadPage('.$page['page_id'].',1,1);</script>'.showError($TEXT['lang_error_connection']) ;
					}

				} else {
					return '<script>loadPage('.$page['page_id'].',1,1);</script>'.showError('sd'.$TEXT['lang_error_connection']) ;
				}
	
			} else {
				return '<script>loadPage('.$page['page_id'].',1,1);</script>';
			}
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}	
	}
	
	function updatePage($pid,$v1,$v2,$v3,$v4,$v5,$user_id) { // Update page settings

		// Prepare to update settings
		$stmt = $this->db->query(sprintf("UPDATE `pages` SET `page_name` = '%s' , `page_email` = '%s' , 
		`page_location` = '%s' , `page_web` = '%s' , `page_description` = '%s' WHERE `page_id` = '%s' ", $this->db->real_escape_string($v1),
		$this->db->real_escape_string($v2), $this->db->real_escape_string($v3),$this->db->real_escape_string($v4),$this->db->real_escape_string($v5),
		$this->db->real_escape_string($pid)));
	
		$changes = ($this->db->affected_rows) ? TRUE : FALSE;

		return $changes;
	}
	
	function updateUsername($pid,$v1,$user_id) {             // Update page username

		// Prepare to update settings
		$stmt = $this->db->query(sprintf("UPDATE `pages` SET `page_username` = '%s' WHERE `page_id` = '%s' ", $this->db->real_escape_string($v1),$this->db->real_escape_string($pid)));
	
		$changes = ($this->db->affected_rows) ? TRUE : FALSE;

		return $changes;
	}
	
}

class groups {          // Group management
	
	// Properties
	public $db;                   // Database connection
	public $username;             // Username property
	public $password;             // Password property
	public $followings;           // Logged user followers (ARRAY)
	public $followers;            // Logged user followings (ARRAY)
	public $settings;             // Administration settings
	public $admin;                // Administration detect


	function listGroups($user_id,$count=NULL) {                // Return user groups joined
		
		// Select users followed
		$add_count = ($count) ? "AND `f_feeds` = '1'" : "";
		
		$result = $this->db->query(sprintf("SELECT `group_id` FROM `group_users` WHERE `group_users`.`user_id` = '%s' AND `group_users`.`group_status` = '1' $add_count", $this->db->real_escape_string($user_id)));	

		$list = array();	
		
		if(!empty($result) && $result->num_rows !== 0) {
			
			// return array of user IDs if users exists
			while($row = $result->fetch_assoc()) {
			    $list[] = $row['group_id'];
		    }
			
			// Return ARRAY
			return implode(',',$list);
			
		} else {
			return '';
		}	
	}
	
	function getImage($current_id,$id,$privacy,$photo) {	   // Generate profile avatars (PRIVACY PROTECTED)
		
		// Privacy check
		if($this->admin || $id == $current_id || in_array($id,$this->followings) || substr($photo, 0, 7) == "default") return $photo ;
		
		// Else confirm privacy check and return image
		return ($privacy == 1) ? 'private.png' : $photo;
		
	}

	function deleteGroup($group_id,$user) {                    // Delete group
	    global $TEXT;
		
		$group_id_esc = $this->db->real_escape_string($group_id);
		
		// Delete group
		$this->db->query("DELETE FROM `groups` WHERE `group_id` = '$group_id_esc'");
		
		// Delete group users
		$this->db->query("DELETE FROM `group_users` WHERE `group_id` = '$group_id_esc'");
		
		// Delete group logs
		$this->db->query("DELETE FROM `group_logs` WHERE `group_id` = '$group_id_esc'");
		
		// Delelte group posts
		$this->db->query("DELETE FROM `user_posts` WHERE `posted_as` = '2' AND `posted_at` = '$group_id_esc'");
		
		// Update group feeds index of leaving user
		$this->db->query(sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listGroups($user['idu'],1)));

		return '<script>loadHome();</script>'.$TEXT['_uni-Delete_group_done'];
	}
	
	function createGroup($v1,$v2,$v3,$user_id) {               // Create new group
	
	    // Insert group
	    $query = sprintf("INSERT INTO `groups`(`group_id`, `group_username`, `group_name`, `group_posting`, `group_privacy`, `group_owner`, `group_cover`, `group_users`,`time`,`group_icon`,`group_description`,`group_location`,`group_web`,`group_email`) VALUES (NULL, '', '%s', '%s', '%s', '%s', 'default.png', '1', CURRENT_TIMESTAMP,'1','','','','');",  $this->db->real_escape_string($v1), $this->db->real_escape_string($v2), $this->db->real_escape_string($v3), $this->db->real_escape_string($user_id)) ;

		// Return results
		if ($this->db->query($query) === TRUE) {
			
			// Get new group id
			$new_group = $this->db->insert_id ;
			
			$this->db->query(sprintf("INSERT INTO `group_users`(`gid`, `user_id`, `group_id`, `group_partner_id`, `group_role`, `f_feeds`, `group_status`,`time`) VALUES (NULL, '%s', '%s', '%s', '2', '1', '1', CURRENT_TIMESTAMP);",  $this->db->real_escape_string($user_id), $this->db->real_escape_string($new_group), $this->db->real_escape_string($user_id), $this->db->real_escape_string($v3), $this->db->real_escape_string($user_id)));
		
		    // Here is the new line
		    $this->db->query(sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '%s'", $this->listGroups($user_id,1) ,$this->db->real_escape_string($user_id)));
 
            return $new_group;
		
		} else {
			
			return NULL;
		}
	}
	
	function updateGroup($gid,$uname,$v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$user_id) { // Update group settings

		// Prepare to update settings
		$stmt = $this->db->query(sprintf("UPDATE `groups` SET `group_username` = '%s', `group_name` = '%s' , `group_email` = '%s' , 
		`group_location` = '%s' , `group_web` = '%s' , `group_description` = '%s' , `group_approval_type` = '%s' , 
		`group_posting` = '%s' , `group_privacy` = '%s' WHERE `group_id` = '%s' ", $this->db->real_escape_string($uname), $this->db->real_escape_string($v1),
		$this->db->real_escape_string($v2), $this->db->real_escape_string($v3),$this->db->real_escape_string($v4),$this->db->real_escape_string($v5),
		$this->db->real_escape_string($v6), $this->db->real_escape_string($v7), $this->db->real_escape_string($v8), $this->db->real_escape_string($gid)));
	
		$changes = ($this->db->affected_rows) ? TRUE : FALSE;

		return $changes;
	}

	function updateGroupMember($group_id,$v1,$v2,$v3,$v4,$user_id) {           // Update group member permissions

		// Prepare to update settings
		$this->db->query(sprintf("UPDATE `group_users` SET `p_post` = '%s', `p_cover` = '%s', `p_activity` = '%s', `group_role` = '%s' WHERE `gid` = '%s'",
		$this->db->real_escape_string($v1),$this->db->real_escape_string($v2), 
		$this->db->real_escape_string($v3),$this->db->real_escape_string($v4), $this->db->real_escape_string($user_id)));	

		return ($this->db->affected_rows) ? TRUE : FALSE;
	}
	
	function getRequests($user,$group_id,$from,$limet) {                       // Return Group member requests
		global $TEXT,$page_settings;
		
		$rows = $update = array();
		
		// Set starting point
		$from = (is_numeric($from) && $from > 0 ) ? 'AND group_users.gid < \''.$this->db->real_escape_string($from).'\'' : ''; 
		
		// Set limit
		$limit = $limet + 1;
		
		// Escape variables for MySQl Query
		$group_id_esc = $this->db->real_escape_string($group_id);
		$user_id = $user['idu'];
	
		// Select requests
		$results = $this->db->query("SELECT * FROM group_users , users WHERE group_users.user_id = users.idu AND group_users.group_id = $group_id_esc AND group_users.group_status = '2' $from ORDER BY group_users.gid DESC LIMIT $limit ;") ;
	
		// If requests exists
		if(!empty($results) && $results->num_rows) {
			
			// fetch requests
			while($row = $results->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Reset
			$requests = '';
		
			// Check whether more requests exists 
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
			
			// Load template
			$req_tpl = display(templateSrc('/group/group_requests/request'),0,1);
			
			foreach($rows as $row) {
		   
				// Set data for template
				$TEXT['temp-unique_id'] = mt_rand(100, 99999);
				$TEXT['temp-user_id'] = $row['user_id'];
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-group_id'] = $group_id;
				$TEXT['temp-time'] = $row['time'];
				
				// Add request to list
				$requests .= display('',$req_tpl);
				
				// Last processed id
                $from = $row['gid'];			
			}
			
			// Add load more function if exists
			$requests .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'groupRequests('.$group_id.','.$from.',1,6,0,0);') : closeBody($TEXT['_uni-No_more-requests'],1);
			
			// Return requests
			return $requests;
			
		} else {
			// Else no requests yet
			return bannerIt('nots'.mt_rand(1,4),$TEXT['_uni-No_requests-all'],$TEXT['_uni-No_requests-all-inf']);	
		}
	}

	function processRequest($group_id,$user_id,$allow,$type,$target_user) {    // Process member request
	    global $TEXT;
	
		// If target user exists
		if(!empty($allow['idu'])) {	
		  
			//  Escape variables for MySQL Query
			$allowed_esc = $this->db->real_escape_string($allow['idu']);
			$by_user_esc = $this->db->real_escape_string($user_id);
			$group_id_esc = $this->db->real_escape_string($group_id);
			
			// Add notiication type
			$not_type = ($type == 1) ? 13 : 14 ;
			
			// Update relationship as accepted
			if($type == 1 && $target_user['group_status'] == 2) {
			   
				// Delete previous notification
			    $query = "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$by_user_esc' AND `notifications`.`not_to` = '$allowed_esc' AND `not_type` =  '$not_type' AND `not_content_id` = '$group_id_esc' ;" ;
				
				// Allow user
				$query .= "UPDATE `group_users` SET `group_users`.`group_status` = '1' , `group_users`.`time` = CURRENT_TIMESTAMP WHERE `group_users`.`user_id` = '$allowed_esc' AND `group_users`.`group_id` = '$group_id_esc' ;" ;	
			    
				// Add new notification
				$query .= "INSERT INTO `notifications`(`id`, `not_to`, `not_from`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$allowed_esc', '$by_user_esc', '$group_id_esc','0','13','0', CURRENT_TIMESTAMP) ;" ;

				// Add activity in log
				$query .= "INSERT INTO `group_logs` (`id`, `group_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '$group_id_esc', '$by_user_esc', '$allowed_esc', '1', CURRENT_TIMESTAMP);";
			
			} elseif($target_user['group_status'] == 2) {
	
				// Delete previous notification
			    $query = "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$by_user_esc' AND `notifications`.`not_to` = '$allowed_esc' AND `not_type` =  '$not_type' AND `not_content_id` = '$group_id_esc' ;" ;
			   
  			    // Delete member request
			    $query .= "DELETE FROM `group_users` WHERE `group_users`.`user_id` = '$allowed_esc' AND `group_users`.`group_id` = '$group_id_esc';" ;
				
				// Add activity in log
				$query .= "INSERT INTO `group_logs` (`id`, `group_id`, `user_id`, `target_id`, `type`, `time`) VALUES (NULL, '$group_id_esc', '$by_user_esc', '$allowed_esc', '2', CURRENT_TIMESTAMP);";
	    
			}
			
			// Perform MySQL multi_query
			$return = database('MULTI', 'QUERY', 'STATUS', sprintf($query));

			// Update new members count
			$update = ($return) ? database('SINGLE', 'QUERY', 'STATUS', sprintf("UPDATE `groups` SET `group_users` = '%s' WHERE `group_id` = '%s' ",$this->db->real_escape_string(numberGroupMembers($group_id,$this->db)),$this->db->real_escape_string($group_id))) : '';

			// // Update group feeds index of target user
			$update_target = ($return && $type == 1 && $target_user['f_feeds'] == 1) ? database('MULTI', 'QUERY', 'STATUS', sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '$allowed_esc'",$this->listGroups($allow['idu'],1))) :'';
	
			return $return;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}	
	}
	
    function getAllGroups($ids,$limit,$from=0) {                // Return user group followings
	    global $TEXT;

		// If exists
		if(!empty($ids)) {
			
			// Reset
			$rows = array();$TEXT['temp-add_group_list'] = '';$i = 1;
			
			// Set start up
			$add_from = ($from > 0) ? 'AND `groups`.`group_id` < \''.$this->db->real_escape_string($from).'\'' : '';	

			// Select data
			$result = $this->db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_id` IN(%s) $add_from ORDER BY `groups`.`group_id` DESC LIMIT $limit ",$this->db->real_escape_string($ids)));

			// If post and user exists
			if(!empty($result) && $result->num_rows !== 0) {
	   			
				// Load template src
				$t_src = templateSrc('SRC',1);
		        $grp_tpl = display($t_src.'/group/group_feeds/group'.$TEXT['templates_extension'],0,1);
				
				// Fetch data
				while($row = $result->fetch_assoc()) {
			        $rows[] = $row;
				}
				
				// Check for more results
				$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;	

				foreach($rows as $row) {

					$TEXT['temp-group_id'] = $row['group_id'];
					$TEXT['temp-group_cover'] = getCoverSrc(str_replace('rep_','',$row['group_cover']));
					$TEXT['temp-group_name'] = fixText(25,$row['group_name']);
					
                    // Add group to list		
				    $TEXT['temp-add_group_list'] .= display('',$grp_tpl);

					// Set last processed id
				    $TEXT['temp-add_last_id'] = $row['group_id'];

				}
				
				// Add load more function if set					
				$TEXT['temp-add_group_list'] .= ($loadmore) ? display($t_src.'/group/group_feeds/load_more'.$TEXT['templates_extension']) : '';
			
			}
		    
			// Inclose and return
			return ($from == 0 && !empty($TEXT['temp-add_group_list'])) ? display($t_src.'/group/group_feeds/combined_container'.$TEXT['templates_extension']) : $TEXT['temp-add_group_list'];
			
		} 
	}
	
	function groupAct($group,$user,$group_user,$type) {                        // Join | Leave | Undo | Request | Groups
	    global $TEXT;
	
		// If targets exists
		if($group['group_id'] && $user['idu']) {	
		  
			//  Escape variables for MySQL Query
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$group_id_esc = $this->db->real_escape_string($group['group_id']);
			
			$subscribe = (substr_count($user['group_feeds'], ',') < 2999) ? '1' : '0' ;
			
			// Join group
			if($type == 1 && $group['group_privacy'] == 1) {
			   
				$query = "INSERT INTO `group_users` (`gid`, `group_id`, `user_id`, `group_partner_id`, `group_role`, `f_feeds`,  `group_status`, `time`) VALUES (NULL, '$group_id_esc', '$user_id_esc', '$user_id_esc', '1', '$subscribe', '1', CURRENT_TIMESTAMP)";
			    
				$return = ($this->db->query($query) === TRUE) ? '<script>if($("#group_view_cover_'.$group['group_id'].'").length){groupFeeds('.$group['group_id'].',0,5);}</script>' : '' ;
				
				// Update members count
				$members_count = numberGroupMembers($group['group_id'],$this->db);
				
				$this->db->query("UPDATE `groups` SET `group_users` = '$members_count' WHERE `group_id` = '$group_id_esc'");	
			
			    // Update group feeds index of leaving user
				$update_target = ($subscribe) ? $this->db->query(sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listGroups($user['idu'],1))) : '';

				return $return;
				
			// Leave group
			} elseif($group_user['group_id'] && $type == 0) {
	
			    $query = "DELETE FROM `group_users` WHERE `group_users`.`user_id` = '$user_id_esc' AND `group_users`.`group_id` = '$group_id_esc'";
				
				$return = ($this->db->query($query) === TRUE) ? '<script>if($("#group_view_cover_'.$group['group_id'].'").length){groupFeeds('.$group['group_id'].',0,5);}</script>' : '' ;
			    
				$members_count = numberGroupMembers($group['group_id'],$this->db);
				
				// Remove ownership if available
				if($group['group_owner'] == $user['idu']) {				
					$this->db->query("UPDATE `groups` SET `group_owner` = '0' , `group_users` = '$members_count' WHERE `group_id` = '$group_id_esc'");			
				} else {
				    $this->db->query("UPDATE `groups` SET `group_users` = '$members_count' WHERE `group_id` = '$group_id_esc'");	
			    }
				
				// Update group feeds index of leaving user
				$this->db->query(sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listGroups($user['idu'],1)));
				
				return $return;
				
			// Request to Join group
		    } elseif($type == 2 && $group['group_privacy'] == 2) {
			   
				$query = "INSERT INTO `group_users` (`gid`, `group_id`, `user_id`, `group_partner_id`, `group_role`, `f_feeds`, `group_status`, `time`) VALUES (NULL, '$group_id_esc', '$user_id_esc', '$user_id_esc', '1', '$subscribe', '2', CURRENT_TIMESTAMP)";
			    
				return ($this->db->query($query) === TRUE) ? '<script>if($("#group_view_cover_'.$group['group_id'].'").length){groupFeeds('.$group['group_id'].',0,5);}</script>' : '' ;
				
			// Undo Join request
		    } elseif($group_user['group_id'] && $type == 3) {
			   
				$query = "DELETE FROM `group_users` WHERE `group_users`.`user_id` = '$user_id_esc' AND `group_users`.`group_id` = '$group_id_esc'";
			    
				return ($this->db->query($query) === TRUE) ? '<script>if($("#group_view_cover_'.$group['group_id'].'").length){groupFeeds('.$group['group_id'].',0,5);}</script>' : '' ;
			
			// Show in feeds (Subscribe group feeds)
			} elseif($group_user['group_id'] && $type == 4) {
			   
				$query = "UPDATE `group_users` SET `group_users`.`f_feeds` = '1' WHERE `group_users`.`user_id` = '$user_id_esc' AND `group_users`.`group_id` = '$group_id_esc'";
			    
				// If user has been updated
				if (substr_count($user['group_feeds'], ',') < 2999 && $this->db->query($query) === TRUE) {
					
					$query = sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listGroups($user['idu'],1));
			    
				    return ($this->db->query($query) === TRUE) ? '<script>loadGroup('.$group['group_id'].',1,1);</script>' : '<script>loadGroup('.$group['group_id'].',1,1);</script>'.showError($TEXT['lang_error_connection']) ;
					
				} else {
					return '<script>loadGroup('.$group['group_id'].',1,1);</script>'.showError($TEXT['_uni-Crossed_group_limit']);
				}
			// Hide in feeds (unSubscribe group feeds)
			} elseif($group_user['group_id'] && $type == 5) {
			   
				$query = "UPDATE `group_users` SET `group_users`.`f_feeds` = '0' WHERE `group_users`.`user_id` = '$user_id_esc' AND `group_users`.`group_id` = '$group_id_esc'";
			    
				// If user has been updated
				if ($this->db->query($query) === TRUE) {
					
					$query = sprintf("UPDATE `users` SET `group_feeds` = '%s' WHERE `idu` = '$user_id_esc'",$this->listGroups($user['idu'],1));
			    
				    return ($this->db->query($query) === TRUE) ? '<script>loadGroup('.$group['group_id'].',1,1);</script>' : '<script>loadGroup('.$group['group_id'].',1,1);</script>'.showError($TEXT['lang_error_connection']) ;
					
				} else {
					return '<script>loadGroup('.$group['group_id'].',1,1);</script>'.showError($TEXT['lang_error_connection']) ;
				}
	
			} else {
				return '<script>loadGroup('.$group['group_id'].',1,1);</script>';
			}
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}	
	}
	
}

class register {	    // Register user										
	
	// Properties
	public $db;                         // Database property
	public $settings;	                // Administration settings	

	function addUser($username,$email,$password,$names,$gender) {                            // Add a new user	
	
	    require_once(__DIR__ . '/presets/preset_queries.php');
	
	    // Prepare statement
	    $query = sprintf(getQueries(),
		        $this->db->real_escape_string(strtolower($username)),
		        md5($this->db->real_escape_string($password)),
		        $this->db->real_escape_string($email),
		        $this->db->real_escape_string($names[0]),
		        $this->db->real_escape_string($names[1]),date('Y-m-d H:i:s'),time(),
		        $this->db->real_escape_string($this->settings['def_p_image']),
		        $this->db->real_escape_string($this->settings['def_p_cover']),
		        $this->db->real_escape_string($this->settings['def_p_verified']),
		        $this->db->real_escape_string(getUserIP()),
				$this->db->real_escape_string($gender),
		        $this->db->real_escape_string($this->settings['def_r_posts_per_page']),
		        $this->db->real_escape_string($this->settings['def_r_followers_per_page']),
		        $this->db->real_escape_string($this->settings['def_r_followings_per_page']),
				$this->db->real_escape_string($this->settings['def_p_moderators']),
				$this->db->real_escape_string($this->settings['def_n_per_page']),
				$this->db->real_escape_string($this->settings['def_n_accept']),
				$this->db->real_escape_string($this->settings['def_n_type']),
				$this->db->real_escape_string($this->settings['def_n_follower']),
				$this->db->real_escape_string($this->settings['def_n_like']),
				$this->db->real_escape_string($this->settings['def_n_comment']),
				$this->db->real_escape_string($this->settings['def_p_posts']),
				$this->db->real_escape_string($this->settings['def_p_followers']),
				$this->db->real_escape_string($this->settings['def_p_followings']),
				$this->db->real_escape_string($this->settings['def_p_profession']),
				$this->db->real_escape_string($this->settings['def_p_hometown']),
				$this->db->real_escape_string($this->settings['def_p_location']),
				$this->db->real_escape_string($this->settings['def_p_private']),
				$this->db->real_escape_string($this->settings['def_b_posts']),	
				$this->db->real_escape_string($this->settings['def_b_comments']),		
				$this->db->real_escape_string($this->settings['def_b_users'])		
		    );

			// Query and check for bugs
			$added = $this->db->query($query);
			$error = $this->db->error;
			
			// If error handle it
			if($error) {handleAll('8A', $error, 'REG_BUG', 'TIX72j8');}
		    
			// Return success
			return ($added === TRUE) ? 1 : 0;
			
        }

	function getAvailabilityUSERNAME($u) {                                    // Return whether USERNAME available 
		
		// Try to select username
		$check = $this->db->query(sprintf("SELECT `idu`,`username` FROM `users` WHERE `username` = '%s'", $this->db->real_escape_string(strtolower($u))));	

		// Return false if exists
		return ($check->num_rows) ? NULL : 1; 
	
	}

    function getAvailabilityMAIL($e) {                                        // Return whether EMAIL available 

		// Try to select email	
		$check = $this->db->query(sprintf("SELECT `idu`,`email` FROM `users` WHERE `email` = '%s'", $this->db->real_escape_string($e)));

		// Return true if rows == 0		
		return ($check->num_rows) ? NULL : 1;
		
	}

}

class Login {	        // Login | Logout user	
	
	// Properties
	public $db;                         // DATABASE
	public $username;	                // USERNAME || IDU
	public $password;	                // PASSWORD || MD5(PASSWORD)
	public $cookie;	                    // 1 || 0
	public $emails_verification;	    // 1 || 0	
	public $mail;                       // Website email	
	public $settings;	                // Administration settings
	public $new_reg;	                // New registration property
	
	function start() {                                        // Full login (Verify for blocks,non verified emails or suspended)
		
		// Unset everything
		$this->logOut();
		
		// Verify profile
		$profile = $this->checkProfile();
		
		// If login success
		if($profile == 1) {
			
			// Check for cookie || Default is enabled 
			if($this->cookie) {

                // Set session and cookies			
				setcookie("username", $this->username, time() + 30 * 24 * 60 * 60,'/'); 
				setcookie("password", md5($this->password), time() + 30 * 24 * 60 * 60,'/'); 	
				$_SESSION['username'] = $this->username;
				$_SESSION['password'] = md5($this->password);
			    return 1;
				
			} else {
				
				// Set sessions only
				$_SESSION['username'] = $this->username;
				$_SESSION['password'] = md5($this->password);
				return 1;
				
			}
			
			// Unset logged out identifier
            unset($_SESSION['loggedout']);
			
		} else {
			
			// Else return error while login
			return $profile;
			
		}	
	}
	
	function log() {                                          // Direct login
		
		// Select user
		if(filter_var($this->db->real_escape_string($this->username), FILTER_VALIDATE_EMAIL)) {
			$profile = $this->db->query(sprintf("SELECT * FROM `users` WHERE `email` = '%s' AND `password` = '%s'", $this->db->real_escape_string(strtolower($this->username)),$this->db->real_escape_string($this->password)));
		} else {
			$profile = $this->db->query(sprintf("SELECT * FROM `users` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string(strtolower($this->username)), $this->db->real_escape_string($this->password)));
		}	
        
        // return profile if exists		
		return ($profile->num_rows) ? $profile->fetch_assoc() : $this->logOut();	
	}
	
	function checkProfile() {                                 // Check profile for Full login
		global $TEXT;
		
		// Check whether input is email and select user
		if(filter_var($this->db->real_escape_string($this->username), FILTER_VALIDATE_EMAIL)) {
			$result = $this->db->query(sprintf("SELECT `idu`,`state` FROM `users` WHERE `email` = '%s' AND `password` = '%s'", $this->db->real_escape_string($this->username), md5($this->db->real_escape_string($this->password))));
		} else {
			$result = $this->db->query(sprintf("SELECT `idu`,`state` FROM `users` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string(strtolower($this->username)), md5($this->db->real_escape_string($this->password))));
		}
		
		// if user exists
		if($result->num_rows) {
			
			// Fetch user
			$profile = $result->fetch_assoc();
			
			// Check user status
			if($profile['state'] == 3) {
				
				// Suspended by Admin temporary
			    return $TEXT['_uni-login-1'];
				
	        } elseif($profile['state'] == 2 && $this->emails_verification == 1 && !isset($this->new_reg)) {
				
				// Email not verified send verification
			    return emailVerification($this->db,$this->settings,$profile['idu'],NULL);
				
	        } elseif($profile['state'] == 4) {
				
				// Permanent Suspended for using other's emails 
			    return $TEXT['_uni-login-3'];
				
	        } else {
				return 1;
			}
			
        // Wrong credentials			
		} else {
			return $TEXT['_uni-Username_password_incorrect'];
		}	
	}
	
	function activateProfile($respond) {                      // Activate email
		global $TEXT;
		
		$result = $this->db->query(sprintf("SELECT `idu`,`username` FROM `users` WHERE `idu` = '%s' ", $this->db->real_escape_string(strtolower($this->username))));

		// if user exists
		if($result->num_rows && !is_null($this->username)) {
			
			// Fetch user row from database
			$result = $result->fetch_assoc();
			
			// Email not verified send verification token
			return emailVerification($this->db,$this->settings,$result['idu'],$respond);

        // Wrong credentials			
		} else {
			return $TEXT['_uni-error-activation-1'];
		}	
	}
	
	function logOut() {                                       // Throw SESSIONS and COOKIES RETURN 0
		
		// unset sessions
		unset($_SESSION['username']);
		unset($_SESSION['password']);

		// unset cookies
		setcookie("username", '', time() + 1*1,'/'); 
        setcookie("password", '', time() + 1*1,'/'); 
		
		return 0;
	}

}

// Add global funcions if don' exists
if(!function_exists('emailVerification')) {
	require_once(__DIR__ . '/functions.php');
}

// load blocking library, from now we had changed the scheme of adding new features 
require_once(__DIR__ . "/processors/blocker.php");

?>