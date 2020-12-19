<?php

function manPages($db,$page_settings,$user) {                          
    global $TEXT,$profile;
 
	// Select available posts
	$result = $db->query(sprintf("SELECT * FROM `pages`, `users` WHERE `pages`.`page_id` IN(SELECT DISTINCT `page_id` FROM `page_roles` WHERE `page_roles`.`user_id` = '%s') AND `pages`.`page_owner` = `users`.`idu` ORDER BY `pages`.`page_id` DESC", $db->real_escape_string($user['idu'])));

	// Reset
	$rows = array();$not_first = 0;$TEXT['temp-page_items'] = $TEXT['temp-pages'] = '';

	// set conditions
	if(!empty($result) && $result->num_rows !== 0) {
	
		// Fetch all posts
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}

		$pagedrop_template = display(templateSrc('/page/manager/dropdown_item'),0,1);
		$page_template = display(templateSrc('/page/manager/page_info'),0,1);

		// From each row generate post
		foreach($rows as $page_row) {
			
			$TEXT['temp-page_id'] = $page_row['page_id'];
			$TEXT['temp-page_icon'] = $page_row['page_icon'];
			$TEXT['temp-page_cover'] = $page_row['page_cover'];
			$TEXT['temp-page_name'] = fixText(25,$page_row['page_name']);
			$TEXT['temp-page_likes'] = number_format($page_row['page_likes']);
			$TEXT['temp-page_followers'] = number_format($page_row['page_follows']);
			$TEXT['temp-node_display'] = ($not_first) ?  'style="display:none;"' : ''; $not_first = 1;
			$TEXT['temp-page_full_info'] = display(templateSrc('/page/manager/page_likes_follows'));
		
			$TEXT['temp-page_items'] .= display('',$pagedrop_template);
			$TEXT['temp-pages'] .= display('',$page_template);
			
		}

		return display(templateSrc('/page/manager/dropdown'));

	} else {
		
		$TEXT['temp-widget_class'] = 'mentions';
		$TEXT['temp-widget_color'] = 'theme';

		$TEXT['temp-js'] = 'locate(\''.$TEXT['installation'].'/?new=page\');';
		$TEXT['temp-href'] = '#';
		$TEXT['temp-btn'] = $TEXT['_uni-Create'];
		$TEXT['temp-title'] = $TEXT['_uni-Pages_create_info'] ;
		$TEXT['temp-info'] = $TEXT['_uni-Pages_create_info_ttl'] ;

		// Generate widget from template 
		return display(templateSrc('/modals/widget'));	
		
	}	    	
}

function recArticles($db,$page_settings,$user,$limit) {                          
    global $TEXT,$profile;
 
	return display(templateSrc('/blogs/article/no_recommendation'));			
    	
}

function trendingPages($db,$user,$limit) {                                        // Get groups list
	global $TEXT;
	
	// Reset
	$content = $TEXT['temp-content']  = '';
	
	$TEXT['temp-data'] = $TEXT['_uni-Popular_pages'];
	
	$TEXT['temp-data-id'] = 'POP_PAGES';
	
	// Select pages
	$pages = $db->query(sprintf("SELECT * FROM `pages` WHERE `page_id` NOT IN(SELECT `page_id` FROM `page_likes` WHERE `by_id` = '%s') AND `page_icon` != 'default.png' ORDER BY `page_likes`, RAND() LIMIT %s",$db->real_escape_string($user['idu']),$db->real_escape_string($limit)));
    
    $count = $pages->num_rows;
	
	// If to groups exists
	if($count) {
		
		// Fetch pages ARRAys
		while($row = $pages->fetch_assoc()) {
			$rows[] = $row;
		}

		// load template
	    $page_template = display(templateSrc('/discover/pop_page'),0,1);
	
	    // Create pages box
		foreach($rows as $row) {
			
			$TEXT['temp-page_id'] = $row['page_id'];
			$TEXT['temp-page_ttl'] = $row['page_name'].' , '.readAble($row['page_likes']).' '.$TEXT['_uni-likes_this'];
			$TEXT['temp-page_img'] = $row['page_icon'];	
			$TEXT['temp-loop'] = ($count > 3) ? 'true' : 'false';	
			$TEXT['temp-content'] .= display('',$page_template);

		}

		return display(templateSrc('/group/box'));
		
	}
}
	
function trendingFeeds($db,$page_settings,$user,$followings,$from,$limit) {                          
    global $TEXT,$profile;
 
	// Set results start up
	$startup = (is_numeric($from)) ? 'AND `user_posts`.`post_loves` < \''.$db->real_escape_string($from).'\'' : '';

	// Select available posts
	$result = $db->query(sprintf("SELECT * FROM `user_posts`, `users` WHERE `user_posts`.`post_by_id` != '%s' AND `users`.`p_posts` = '0' $startup  AND `user_posts`.`post_type` NOT IN(3,5,6) AND `user_posts`.`posted_as` = '0' AND `user_posts`.`post_by_id` = `users`.`idu` ORDER BY `user_posts`.`post_loves` DESC LIMIT %s", $db->real_escape_string($user['idu']), $limit+1));

	// Reset
	$rows = array();$messages = '';

	// set conditions
	if(!empty($result) && $result->num_rows !== 0) {
	
		// Fetch all posts
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}

		// Add load more button if available
		$loadmore = (array_key_exists($limit, $rows)) ? array_pop($rows) : NULL ;

		$post_template = display(templateSrc('/post/user_post'),0,1);

		// From each row generate post
		foreach($rows as $post_row) {
			
			// Get profile picture
			$TEXT['temp-user_img'] = $profile->getImage($user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']);

			// List post buttons and details
			list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $profile->listFunctions($post_row,$user);
		
			// Parse post text remove all smiles
			$TEXT['temp-pt_ns'] = protectXSS($profile->parseText($post_row['post_text'],1));
		
			// Get post type and content
			list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $profile->getPostContent($post_row,$post_row['post_id'],1,0,$profile->parseText(protectXSS($post_row['post_text'])));		

			// Post heading
			list($TEXT['temp-heading'],$TEXT['temp-group_heading']) = $profile->getPostHeading($post_row['post_type'],$post_row['posted_as'],$post_row['posted_at'],$post_row['post_extras'],'',$post_row['post_content'],$post_row['gender'],$post_row['post_id']);
		
			// Post id
			$TEXT['temp-id'] = $post_row['post_id'];

			// Posted time
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
			$last = $post_row['post_loves'];
			
		}
		
		// If more results available add function
		$messages .= ($loadmore) ? addLoadmore($page_settings['inf_scroll'],'','trendingFeeds('.$last.');') : closeBody($TEXT['_uni-No_more_feeds']);	
		
		return $messages;

	} else {
		return (!is_numeric($from)) ? bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-No_feeds'],$TEXT['_uni-No_feeds-inf']) : closeBody($TEXT['_uni-No_more_feeds']);			
	}	    	
}

function getCategoryById($id,$db) {                  

	// Select category
	$cat = $db->query(sprintf("SELECT * FROM `blog_categories` WHERE `cid` = '%s' ",$db->real_escape_string($id)));

	// Return categories
	return ($cat->num_rows !== 0) ? $cat->fetch_assoc() : 0;
	
}
	
function getReadableCategoires($db,$limit) {
	global $TEXT;
	
	$blogs = $db->query(sprintf("SELECT * FROM `blog_categories` WHERE `cat_img` != '0' ORDER BY `cat_name` LIMIT %s",$db->real_escape_string($limit)));
	
	if(!empty($blogs) && $blogs->num_rows !== 0) {		    
		
		while($row = $blogs->fetch_assoc()) {
			$rows[] = $row;
		}

		$template = display(templateSrc('/discover/category'),0,1);
		$TEXT['temp-add_cats'] = '';
		
		foreach($rows as $row) {
				
			$TEXT['temp-id'] = $row['cid'];
			$TEXT['temp-name'] =  $TEXT[$row['cat_name']];
			$TEXT['temp-src'] =  $row['cat_img'];
			$TEXT['temp-add_cats'] .= display('',$template);

		}
		
		return display(templateSrc('/discover/categories_box'));
	}
}

function getPopularTags($db) {
	$blogs = $db->query("SELECT * FROM `user_posts` WHERE `user_posts`.`post_tags` != '' ORDER BY `user_posts`.`post_tags` DESC LIMIT 15");
	$tags = '';
	
	if(!empty($blogs)) {
		
		while($row = $blogs->fetch_assoc()) {
			$tags .= ','.$row['post_tags'];
		}
		
		$all_tags = array();
	
		$split = explode(',',$tags);
		
		foreach($split as $tag) {
			if(!empty(trim($tag))) {
				$all_tags[] = $tag;
			}
		}
	
		return implode(',',array_unique($all_tags));
	}
	
}

function getParTags($tags,$tag_temp='tag',$type='tags_box_popular',$pro_tags=array()) {                            // All tags
	global $TEXT;

	$tag_template = display(templateSrc('/discover/tags/'.$tag_temp),0,1);
	$TEXT['temp-add_categories'] = $TEXT['temp-add_tags'] = '';

	if(!empty($pro_tags)) {
		
		$tags = $values = array();

		foreach($pro_tags as $val=>$tag) {
			$TEXT['temp-tag'] = $tag;
			$TEXT['temp-value'] = $val;
			$TEXT['temp-add_tags'] .= display('',$tag_template);
		}

		return display(templateSrc('/discover/tags/'.$type));

	} elseif(!empty($tags)) {

		$rows = explode(',',$tags);

		foreach($rows as $row) {
			$TEXT['temp-tag'] = $row;
			$TEXT['temp-add_tags'] .= display('',$tag_template);
		}
		
		return display(templateSrc('/discover/tags/'.$type));
		
	}
	
}

function getReadableBlogs($db,$user,$limit) {
	global $TEXT;
	
	$blogs = $db->query(sprintf("SELECT * FROM `blogs`, `users` WHERE `users`.`idu` = `blogs`.`blog_by_id` AND `users`.`p_image` = '0' ORDER BY `blogs`.`blog_loves` DESC, RAND() LIMIT %s",$db->real_escape_string($limit + 20)));
	
	if(!empty($blogs) && $blogs->num_rows !== 0) {		    
		
		while($row = $blogs->fetch_assoc()) {
			$rows[] = $row;
		}
		
		shuffle($rows);
		
		$i=1;$TEXT['temp-items']='';
		
		$template = display(templateSrc('/discover/article_popular'),0,1);
		
		foreach($rows as $row) {
			if($i > $limit) break;
			$i++;
			$TEXT['temp-id'] = $row['blog_id'];
			$TEXT['temp-name'] =  $row['blog_heading'];
			$TEXT['temp-title'] = $row['blog_description'];
			$TEXT['temp-src'] = $row['blog_image'];	
			$TEXT['temp-src_user'] = $row['image'];	
			$TEXT['temp-username'] = $row['username'];	
			$TEXT['temp-title_user'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name']));	
			$TEXT['temp-user_id'] = $row['idu'];	
			$TEXT['temp-items'] .= display('',$template);
		    
		}
		
		return display(templateSrc('/discover/articles_box'));;
		
	}
	
}
 
function getJoinableGroups($db,$user,$limit) {
	global $TEXT;
	
	$groups = $db->query(sprintf("SELECT * FROM `groups` WHERE `group_privacy` = '1' AND `group_id` NOT IN(SELECT `group_id` FROM `group_users` WHERE `user_id` = '%s' AND `group_status` = '1') ORDER BY RAND() LIMIT %s",$db->real_escape_string($user['idu']),$db->real_escape_string($limit + 20)));
	
	if(!empty($groups) && $groups->num_rows !== 0) {		    
		
		while($row = $groups->fetch_assoc()) {
			$rows[] = $row;
		}
		
		shuffle($rows);
		
		$i=1;$TEXT['temp-items']='';
		
		$template = display(templateSrc('/discover/group_popular'),0,1);
		
		foreach($rows as $row) {
			if($i > $limit) break; $i++;
			$TEXT['temp-id'] = $row['group_id'];
			$TEXT['temp-name'] =  $row['group_name'];
			$TEXT['temp-title'] = sprintf($TEXT['_uni-ttl_group_members_ttl'],readAble($row['group_users']));
			$TEXT['temp-src'] = getCoverSrc(str_replace('rep_','',$row['group_cover']));	
			$TEXT['temp-items'] .= display('',$template);
		}
		
		return display(templateSrc('/discover/group_box'));;
		
	}
}
 	
?>