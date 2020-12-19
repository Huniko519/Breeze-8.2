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

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
   
    // Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else { 
	    
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
        
		// Validate JS inputs
		if(isset($_POST['v1']) && !empty($_POST['v1'])) {
		    
			// Get starting point
			$from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
			
			// Reset
			$right_body = $main_body = '';
			
			// Trim searched value
			$search = trim($_POST['v1']);
			
			// Get Ads
			$sponsored = $profile->parseAdd($page_settings['fi_add_search'],1);
			
			// Hashtag search
			if($search[0] == "#" || $_POST['t'] == 3) {
			    
				// Allowed date
				$date = (isset($_POST['v2']) && in_array($_POST['v2'],array("1","2","3"))) ? $_POST['v2'] : 0 ;
				
				// Allowed type
				$type = (isset($_POST['v3']) && in_array($_POST['v3'],array("1","2","3"))) ? $_POST['v3'] : 0 ;
				
				// Allowed scope
				$scope = (isset($_POST['v4']) && in_array($_POST['v4'],array("1","2","0"))) ? $_POST['v4'] : 0 ;
			   
			    if($scope > 1) {$profile->followers = $profile->listFollowers($user['idu']);}
			   
  			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
					
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_tag_sprint'],$db->real_escape_string(ltrim($search, '#'))).'\';</script>';

					// Add filters
					$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_hashtags'.$TEXT['templates_extension']);
			        
					// Get trending hastags if enabled
					$trending_tags = ($page_settings['feature_tags_on_searchtags']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
					
					// Add trending tags
					$TEXT['content'] .= '<span id="RIGHT_TAG_SEARCHED"></span>'.$sponsored.$trending_tags;
			        
					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title;	
					
					// Set search tab type
			        $TEXT['TEMP_TAB_ID'] = '3';
					
			    }
		   
			    $TEXT['content'] = '';
			   
			    // Get taggesd posts
			    $TEXT['posts'] = $profile->searchHashtags($user,$from,ltrim($search, '#'),$date,$type,$scope);
			
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
			
			// Photos search
			} elseif($_POST['v1'][0] == "@" || $_POST['t'] == 4) {
			
			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
			        
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_photos_sprint'],$db->real_escape_string(ltrim($search, '@'))).'\';</script>';
 
					// Get trending hastags if enabled
					$trending_tags = ($page_settings['feature_tags_on_searchphotos']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
					
					// Add trending tags
					$TEXT['content'] .= '<span id="RIGHT_AT_SEARCHED"></span>'.$sponsored.$trending_tags;
			        
					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title;	
					
					// Set search tab type
			        $TEXT['TEMP_TAB_ID'] = '4';
			    }
			   
			    $TEXT['content'] = '';
			   
			    // Get taggesd posts
			    $TEXT['posts'] = $profile->searchAtTags($user,$from,ltrim($search, '@'),$profile->getUserByUsername(ltrim($search, '@')));
			
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];

			// Group search
			} elseif($_POST['v1'][0] == "!" || $_POST['t'] == 2) {
                
				// Add keywords
				$group_types = (isset($_POST['v2']) && !empty($_POST['v2']) && $_POST['v2'] !== 'undefined' ) ? $_POST['v2'] : '' ;
				$desc = (isset($_POST['v4']) && !empty($_POST['v4']) && $_POST['v4'] !== 'undefined' && strlen($_POST['v4']) < 32) ? $_POST['v4'] : '' ;
				$loc = (isset($_POST['v3']) && !empty($_POST['v3']) && $_POST['v3'] !== 'undefined' && strlen($_POST['v3']) < 32) ? $_POST['v3'] : '' ;
				
			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
			        
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_group_sprint'],$db->real_escape_string($search)).'\';</script>';
 
					// Add filters
					$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_groups'.$TEXT['templates_extension']);
			        
					// Get trending hastags if enabled
					$trending_tags = ($page_settings['feature_tags_on_group_search']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
			        
					// Get joined groups
			        $groups_joined = ($page_settings['groups_on_group_search']) ? $profile->getGroups($user['idu'],15) : '';
					
					// Add trending tags
					$TEXT['content'] .= '<span id="RIGHT_GRP_SEARCHED"></span>'.$sponsored.$trending_tags.$groups_joined;
			        
					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title ;	
					
					// Set search tab type
			        $TEXT['TEMP_TAB_ID'] = '2';
			    }		   
			  
			    $TEXT['content'] = '';
			   
			    // Get taggesd posts
			    $TEXT['posts'] = $profile->searchGroups($user,$from,$search,$group_types,$desc,$loc);
			
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
				
			// Pages search
			} elseif($_POST['t'] == 7) {
                
				// Add keywords
				$page_types = (isset($_POST['v2']) && !empty($_POST['v2']) && $_POST['v2'] !== 'undefined' ) ? $_POST['v2'] : '' ;
				$desc = (isset($_POST['v4']) && !empty($_POST['v4']) && $_POST['v4'] !== 'undefined' && strlen($_POST['v4']) < 32) ? $_POST['v4'] : '' ;
				$loc = (isset($_POST['v3']) && !empty($_POST['v3']) && $_POST['v3'] !== 'undefined' && strlen($_POST['v3']) < 32) ? $_POST['v3'] : '' ;
				
			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
			        
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_page_sprint'],$db->real_escape_string($search)).'\';</script>';
 
					// Add filters
					$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_pages'.$TEXT['templates_extension']);
			        
					// Get trending hastags if enabled
					$trending_tags = $profile->getHashtags('', $page_settings['trendinghashtags_limit']);
			        
					// Get joined groups
			        $groups_joined = ($page_settings['groups_on_page_search']) ? $profile->getGroups($user['idu'],15) : '';
					
					// Add trending tags
					$TEXT['content'] .= '<span id="RIGHT_PAGE_SEARCHED"></span>'.$sponsored.$trending_tags.$groups_joined;
			        
					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title ;	
					
					// Set search tab type
			        $TEXT['TEMP_TAB_ID'] = '7';
			    }		   
			  
			    $TEXT['content'] = '';

			    $TEXT['posts'] = $profile->searchPages($user,$from,$search,$page_types,$desc,$loc);
			
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
				
			// Videos search
			} elseif($_POST['t'] == 6) {
             
			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
			        
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_video_sprint'],$db->real_escape_string($search)).'\';</script>';
     
					// Get trending hastags if enabled
					$trending_tags = ($page_settings['feature_tags_on_video_search']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
			        			
					// Add trending tags
					$TEXT['content'] .= '<span id="RIGHT_VID_SEARCHED"></span>'.$sponsored.$trending_tags;
			        
					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title ;	
					
					// Set search tab type
			        $TEXT['TEMP_TAB_ID'] = '6';
			    }		   
			  
			    $TEXT['content'] = '';
			   
			    // Get taggesd posts
			    $TEXT['posts'] = $profile->searchVideos($user,$from,$search);
			
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
				
			// People search
			} elseif($_POST['v1'][0] == "&" || $_POST['t'] == 1) {
                
				// Add keywords
				$home = (isset($_POST['v2']) && !empty($_POST['v2']) && $_POST['v2'] !== 'undefined' && strlen($_POST['v2']) < 32) ? $_POST['v2'] : '' ;
				$liv = (isset($_POST['v3']) && !empty($_POST['v3']) && $_POST['v3'] !== 'undefined' && strlen($_POST['v3']) < 32) ? $_POST['v3'] : '' ;
				$edu = (isset($_POST['v6']) && !empty($_POST['v6']) && $_POST['v6'] !== 'undefined' && strlen($_POST['v6']) < 32) ? $_POST['v6'] : '' ;
				$prof = (isset($_POST['v4']) && !empty($_POST['v4']) && $_POST['v4'] !== 'undefined' && strlen($_POST['v4']) < 32) ? $_POST['v4'] : '' ;
		
			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
			        
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_people_sprint'],$db->real_escape_string($search)).'\';</script>';
 
					// Add filters
					$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_people'.$TEXT['templates_extension']);
			        
					// Get trending hastags if enabled
					$trending_tags = ($page_settings['feature_tags_on_search']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
					
					// Add trending tags
					$TEXT['content'] .= '<span id="RIGHT_PEP_SEARCHED"></span>'.$sponsored.$trending_tags;
			        
					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title ;	
					
					// Set search tab type
			        $TEXT['TEMP_TAB_ID'] = '1';
			    }		   
			  
			    $TEXT['content'] = '';
			   
			    // Get taggesd posts
			    $TEXT['posts'] = $profile->search($user,$from,$search,$home,$liv,$prof,$edu);
			
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
				
			} else {
				
			    // If full page is requested
			    if(!$from && !$_POST['v5']) {
			        
					// Add title
		            $add_title = '<script>document.title = \''.sprintf($TEXT['_uni-Search_top_sprint'],$db->real_escape_string($search)).'\';</script>';

					// Get trending hastags if enabled
					$trending_tags = ($page_settings['feature_tags_on_top_search']) ? $profile->getHashtags('', $page_settings['trendinghashtags_limit']) : '';
			
			        // Get joined groups
			        $groups_joined = ($page_settings['groups_on_top_search']) ? $profile->getGroups($user['idu'],15) : '';
					
					$bible_verse = (substr($page_settings['bible_view'],4,1)) ? getBible() : '';
					
					// Add trending tags
					$TEXT['content'] = '<span id="RIGHT_TOP_SEARCHED"></span>'.$sponsored.$bible_verse.$trending_tags.$groups_joined;

					// Bind filters and tags to right body
			        $right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']).$add_title ;	
					
					// Set search tab type
			       $TEXT['TEMP_TAB_ID'] = '0';
			    
				} 
				
				$TEXT['content'] = '';
				
				// Get Content box   || People search
				$TEXT['temp_standard_content'] = $profile->search($user,$from,$search,'','','','',4);
				$TEXT['temp_standard_title'] = $TEXT['_uni-People'];
				$TEXT['temp_standard_title_img'] = 'people-search';
				$TEXT['temp_standard_id'] = 'user-search-box-main';

				$TEXT['posts'] = display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box_white'.$TEXT['templates_extension']);
				
				// Get Content box   || Pages search
				$TEXT['temp_standard_content'] = $profile->searchPages($user,$from,$search,0,'','',4);
				$TEXT['temp_standard_title'] = $TEXT['_uni-Pages'];
				$TEXT['temp_standard_title_img'] = 'page-search';
				$TEXT['temp_standard_id'] = 'page-search-box-video';

				$TEXT['posts'] .= '<div class="brz-hide-small" style="height:10px;width"></div>'.display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box_white'.$TEXT['templates_extension']);
				
				// Get Content box   || Videos search
				$TEXT['temp_standard_content'] = $profile->searchVideos($user,$from,$search,4);
				$TEXT['temp_standard_title'] = $TEXT['_uni-Videos'];
				$TEXT['temp_standard_title_img'] = 'video-search';
				$TEXT['temp_standard_id'] = 'user-search-box-video';

				$TEXT['posts'] .= '<div class="brz-hide-small" style="height:10px;width"></div>'.display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box_white'.$TEXT['templates_extension']);
				
				
				// Get Content box   || Groups search
				$TEXT['temp_standard_content'] = $profile->searchGroups($user,$from,$search,0,'','',4);
				$TEXT['temp_standard_title'] = $TEXT['_uni-Groups'];
				$TEXT['temp_standard_title_img'] = 'group-search';
				$TEXT['temp_standard_id'] = 'user-search-box-group';

				$TEXT['posts'] .= '<div class="brz-hide-small" style="height:10px;width"></div>'.display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box_white'.$TEXT['templates_extension']);
				
				
			    // Add posts to main body
			    $main_body =  (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
			}
		   
			// Add search tab
			$tab = (!$from && !$_POST['v5']) ? display('../../../themes/'.$TEXT['theme'].'/html/tab/search_tab'.$TEXT['templates_extension']) : '';
		
		    echo $tab.$main_body.$right_body.'<script>$("#swsef89u3hj89sd").addClass("search-icon").removeClass("search-icon-loading");</script>';

		} else {
		    // Invalid JS inputs
			echo showNotification($TEXT['_uni-Please_add_search']).'<script>$("#swsef89u3hj89sd").addClass("search-icon").removeClass("search-icon-loading");</script>';
		}
	}
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>