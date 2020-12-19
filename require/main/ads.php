<?php
// Get post content
function postContent($id,$fetch=0) {
	global $TEXT,$db;
	
	
	if($fetch) {
		$post = $id ;
	} else {
		
		$post = $db->query(sprintf("SELECT * FROM `user_posts` WHERE `user_posts`.`post_id` = '%s' LIMIT 1;",$db->real_escape_string($id)));

		$post = $post->fetch_assoc();
	}
	
	// If exists
	if(!empty($post)) {
	
		
		$p_text = $post['post_text'];
		
		if($post['post_type'] == 1) { // Added photos
			$images = explode(',', $post['post_content']);
			$count = (count($images) == 1) ? '' : count($images);
			$p_con = '<div class="padding-10"><img src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=650&h=600" style="margin:5px;max-width:200px;max-height:200px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center"></div>';
		} elseif($type == 3) {      // Updated profile picture	
			$p_con = '<div class="padding-10"><img src="'.$TEXT['installation'].'/thumb.php?src='.$post['post_content'].'&fol=a&w=650&h=600" style="margin:5px;max-width:200px;max-height:200px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center"></div>';	
		} elseif($type == 4) {      // Shared a youtube video
			$p_text = 'YOUTUBE.COM : '.$post['post_content'];
		} elseif($type == 7) {      // Shared a Soundcloud track
		    $p_text = 'SOUNDCLOUD.COM : '.$post['post_content'];
		} elseif($type == 8) {      // Shared a Dailymotion video
			$p_text = 'DAILYMOTION.COM : '.$post['post_content'];
		} elseif($type == 9) {      // Shared a Vimeo video
		    $p_text = 'VIMEO.COM : '.$post['post_content'];
		} elseif($type == 5) {      // Updated his cover photo		
			$p_con = '<div class="padding-10"><img '.$TEXT['installation'].'/thumb.php?src='.$post['post_content'].'&fol=b&w=650&h=300 style="margin:5px;max-width:200px;max-height:200px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center"></div>';	
		} 
    
	    return array($p_text,$p_con);
		
	    // return array($post['post_text'],'');

	} else {
		return array($TEXT['_uni-doesnt-exists-post'],'');
	}
	
}

function pageContent($id,$fetch=0) {
	global $TEXT,$db;
	
	if($fetch) {
		$page = $id ;
	} else {
		$page = $db->query(sprintf("SELECT * FROM `pages` WHERE `pages`.`page_id` = '%s' LIMIT 1;",$db->real_escape_string($id)));
		$page = $page->fetch_assoc();
	}
	
	// If exists
	if(!empty($page)) {
		
		$TEXT['temp-page_id'] = $page['page_id'];
		$TEXT['temp-page_icon'] = $page['page_icon'];
		$TEXT['temp-page_name'] = $page['page_name'];
		
		return display(templateSrc('/sponsors/page_listed'));

	} else {
		return $TEXT['_uni-Info_pages_nt'];
	}
	
}

function groupContent($id,$fetch=0) {
	global $TEXT,$db;
	
	if($fetch) {
		$group = $id ;
	} else {
		$group = $db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_id` = '%s' LIMIT 1;",$db->real_escape_string($id)));
        $group = $group->fetch_assoc();
	}
	
	// If exists
	if(!empty($group)) {
		
		$TEXT['temp-group_id'] = $group['group_id'];
		$TEXT['temp-group_cover'] = getCoverSrc(str_replace('rep_','',$group['group_cover']));
		$TEXT['temp-group_name'] = $group['group_name'];
		
		return display(templateSrc('/sponsors/group_listed'));

	} else {
		return $TEXT['_uni-Not_found'];
	}
	
}

function publishAd($user,$payment) {
	global $db;
	
	// Insert ad
	$db->query(sprintf("INSERT INTO `ads` (`aid`, `by_id`, `cid`, `type`, `v_left`, `time`) VALUES (NULL, '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP);", $db->real_escape_string($user['idu']), $db->real_escape_string($payment['c_id']), $db->real_escape_string($payment['type']), $db->real_escape_string($payment['views'])));
	
}

function updatePayment($id,$tx_id,$type) {
    global $db;
	return $db->query(sprintf("UPDATE `payments` SET `status` = '%s', `txn_id` = '%s' WHERE `payments`.`id` = '%s';", $db->real_escape_string($type), $db->real_escape_string($tx_id), $db->real_escape_string($id)));
}

function getPayment($id) {
	global $db;
	$payment = $db->query(sprintf("SELECT * FROM `payments` WHERE `payments`.`id` = '%s';", $db->real_escape_string($id)));
    return ($payment->num_rows) ? $payment->fetch_assoc() : 0;
}

function createInvoice($user,$c_type,$c_id,$c_views) {
	global $TEXT,$db,$page_settings;
	
	$pa_type = array(
	    "post" => "1",
	    "page" => "2",
	    "group" => "3",
	);
	
	$pa_type2 = array(
	    "post" => "Post Boost",
	    "group" => "Group Boost",
	    "page" => "Page Boost",
	);
	
	// Set data for the invoice
	$sandbox = ($page_settings['pp_sandbox']) ? 'sandbox.' : ''; 
	$TEXT['temp-paypal_url'] = 'https://www.'.$sandbox.'paypal.com/cgi-bin/webscr'; 
    $TEXT['temp-cc'] = $page_settings['v_cc'];
    $TEXT['temp-c_type'] = $pa_type2[$c_type];
    $TEXT['temp-paypal_email'] = $page_settings['pp_email'];
    $TEXT['temp-payer_email'] = $user['email'];
	$TEXT['temp-price'] = $c_views * $page_settings['v_price'];
	$TEXT['temp-return'] = $TEXT['installation'].'/includes/paypal/payments.php';
	$TEXT['temp-cancel_return'] = $TEXT['installation'].'/includes/paypal/payment-cancelled.html';
	
	// Insert invoice
	$db->query(sprintf("INSERT INTO `payments` (`id`, `by_id`, `c_id`, `views`, `payer_email`, `amount`, `txn_id`, `currency`, `type`, `status`, `time`) VALUES (NULL, '%s', '%s',  '%s', '%s', '%s', '', '%s', '%s', '0', NOW());", $db->real_escape_string($user['idu']), $db->real_escape_string($c_id), $db->real_escape_string($c_views), $db->real_escape_string($user['email']), $db->real_escape_string($TEXT['temp-price']), $db->real_escape_string($TEXT['temp-cc']), $db->real_escape_string($pa_type[$c_type])));
	
	$TEXT['temp-payment_id'] = $db->insert_id;
	
	return display(templateSrc(($TEXT['temp-payment_id']) ? '/sponsors/invoice' : '/sponsors/invoice_fail'));
	
}
	
function getAdWizard($user,$type,$id) {
	global $TEXT,$db,$page_settings;
	
	if(!$page_settings['bo_ads']) {
		return showError($TEXT['_uni-Boost_er2']);
	}
	
	// COnfirm ownership of the content
	if($type == 'post') {
		
		$results = $db->query(sprintf("SELECT * FROM `user_posts` WHERE `user_posts`.`post_id` = '%s' AND `user_posts`.`post_type` IN(0,1,3,4,7,8,9,5);",$db->real_escape_string($id)));

        $post = (!empty($results) && $results->num_rows) ? $results->fetch_assoc() : 0;
		
		if($post['post_by_id'] !== $user['idu']) return '';
		
		list($post_text,$post_img) = postContent($post,1);

		$TEXT['temp-content'] = $post_text.$post_img;
		$TEXT['temp-type'] = $TEXT['_uni-Post'];
		$TEXT['temp-time'] = addStamp($row['post_time'],2);
		$TEXT['temp-c_id'] = $post['post_id'];
		$TEXT['temp-c_type'] = 'post';
		
	} elseif($type == 'page') {
		
	    $results = $db->query(sprintf("SELECT * FROM `pages` WHERE `pages`.`page_id` = '%s'",$db->real_escape_string($id)));

        $page = (!empty($results) && $results->num_rows) ? $results->fetch_assoc() : 0;
		
		if($page['page_owner'] !== $user['idu']) return '';
		
		$TEXT['temp-content'] = pageContent($page,1);
		$TEXT['temp-type'] = $TEXT['_uni-Page'];
		$TEXT['temp-c_id'] = $page['page_id'];
		$TEXT['temp-c_type'] = 'page';
		$TEXT['temp-time'] = addStamp($row['time'],2);
		
	} elseif($type == 'group') {
	   
	    $results = $db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_id` = '%s'",$db->real_escape_string($id)));

        $group = (!empty($results) && $results->num_rows) ? $results->fetch_assoc() : 0;
		
		if($group['group_owner'] !== $user['idu']) return '';
		
		$TEXT['temp-content'] = groupContent($group,1);
		$TEXT['temp-type'] = $TEXT['_uni-Group'];
		$TEXT['temp-c_id'] = $group['group_id'];
		$TEXT['temp-c_type'] = 'group';
		$TEXT['temp-time'] = addStamp($row['time'],2);
		
	}
	
	$TEXT['temp-currency'] = $page_settings['v_cc'];
	$TEXT['temp-cc_node'] = $page_settings['v_price'];
	$TEXT['temp-price'] = '0.0';
	
	$content = display(templateSrc('/sponsors/listed_ad'));
	
	return $content.display(templateSrc('/sponsors/view_select'));

}

function getPosts($user,$start=0) {
	global $TEXT,$db,$page_settings;
	
	// Add start
	$from = (is_numeric($start) && $start > 0 ) ? 'AND `user_posts`.`post_id` < \''.$db->real_escape_string($start).'\'' : ''; 

	// Reset
	$rows = array();$content='';$limit=12;
	
	// Select ads
	$results = $db->query(sprintf("SELECT * FROM `user_posts` WHERE `user_posts`.`post_by_id` = '%s' AND `user_posts`.`post_type` IN(0,1,3,4,7,8,9,5) $from ORDER BY `user_posts`.`post_id` DESC LIMIT %s;",$db->real_escape_string($user['idu']),$db->real_escape_string($limit)));

	// If ads exists
	if(!empty($results) && $results->num_rows) {

		// fetch ads
		while($row = $results->fetch_assoc()) {
			$rows[] = $row;
		}
		
		$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
	
	    $ad_tpl = display(templateSrc('/sponsors/listed_ad'),0,1);
		
		foreach($rows as $row) {
			
			$TEXT['temp-content'] = $TEXT['temp-icon'] = $TEXT['temp-type'] = '';
			
		    $TEXT['temp-type'] = $TEXT['_uni-Post'];

			list($post_text,$post_img) = postContent($row,1);

			$TEXT['temp-content'] = $post_text.$post_img;
			$TEXT['temp-icon'] = 'credit-card';
			
			if($row['posted_as'] == 1) {
				$TEXT['temp-content'] .= '<div class="padding-10-0">'.groupContent($row['posted_at']).'</div>';
			} elseif($row['posted_as'] == 2) {
				$TEXT['temp-content'] .= '<div class="padding-10-0">'.pageContent($row['posted_at']).'</div>';
			}
			
			$TEXT['temp-time'] = addStamp($row['post_time'],2);
			$TEXT['temp-views'] = '<div onclick="adsData(\'create_post_ad\','.$row['post_id'].',5);" class="btn btn-small btn-light">'.$TEXT['_uni-Boost'].'</div>';
			
			$content .= display('',$ad_tpl);
			
			$last = $row['post_id'];
		}
		
		// Add load more function if exists
		$content .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'adsData(\'post_ads\','.$last.',6);') : '';

	} else {
		$content = closeBody($TEXT['_uni-No_posts_ads']);
	}
	
	return $content;

}

function getGroups($user,$start=0) {
	global $TEXT,$db,$page_settings;
	
	// Add start
	$from = (is_numeric($start) && $start > 0 ) ? 'AND `groups`.`group_id` < \''.$db->real_escape_string($start).'\'' : ''; 

	// Reset
	$rows = array();$content='';$limit=12;
	
	// Select ads
	$results = $db->query(sprintf("SELECT * FROM `groups` WHERE `groups`.`group_owner` = '%s' $from ORDER BY `groups`.`group_id` DESC LIMIT %s;",$db->real_escape_string($user['idu']),$db->real_escape_string($limit)));

	// If ads exists
	if(!empty($results) && $results->num_rows) {

		// fetch ads
		while($row = $results->fetch_assoc()) {
			$rows[] = $row;
		}
		
		$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
	
	    $ad_tpl = display(templateSrc('/sponsors/listed_ad'),0,1);
		
		foreach($rows as $row) {
			
			$TEXT['temp-content'] = $TEXT['temp-icon'] = $TEXT['temp-type'] = '';
			
		    $TEXT['temp-type'] = $TEXT['_uni-Group'];

			$TEXT['temp-content'] = groupContent($row,1);
			$TEXT['temp-icon'] = 'users';
			
			$TEXT['temp-time'] = addStamp($row['time'],2);
			$TEXT['temp-views'] = '<div onclick="adsData(\'create_group_ad\','.$row['group_id'].',5);" class="btn btn-small btn-light">'.$TEXT['_uni-Boost'].'</div>';
			
			$content .= display('',$ad_tpl);
			
			$last = $row['group_id'];
		}
		
		// Add load more function if exists
		$content .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'adsData(\'group_ads\','.$last.',6);') : '';

	} else {
		$content = closeBody($TEXT['_uni-No_groups_ads']);
	}
	
	return $content;

}

function getPages($user,$start=0) {
	global $TEXT,$db,$page_settings;
	
	// Add start
	$from = (is_numeric($start) && $start > 0 ) ? 'AND `pages`.`page_id` < \''.$db->real_escape_string($start).'\'' : ''; 

	// Reset
	$rows = array();$content='';$limit=12;
	
	// Select ads
	$results = $db->query(sprintf("SELECT * FROM `pages` WHERE `pages`.`page_owner` = '%s' $from ORDER BY `pages`.`page_id` DESC LIMIT %s;",$db->real_escape_string($user['idu']),$db->real_escape_string($limit)));

	// If ads exists
	if(!empty($results) && $results->num_rows) {

		// fetch ads
		while($row = $results->fetch_assoc()) {
			$rows[] = $row;
		}
		
		$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
	
	    $ad_tpl = display(templateSrc('/sponsors/listed_ad'),0,1);
		
		foreach($rows as $row) {
			
			$TEXT['temp-content'] = $TEXT['temp-icon'] = $TEXT['temp-type'] = '';
			
		    $TEXT['temp-type'] = $TEXT['_uni-Page'];

			$TEXT['temp-content'] = pageContent($row,1);
			$TEXT['temp-icon'] = 'flag';
			
			$TEXT['temp-time'] = addStamp($row['time'],2);
			$TEXT['temp-views'] = '<div onclick="adsData(\'create_page_ad\','.$row['page_id'].',5);" class="btn btn-small btn-light">'.$TEXT['_uni-Boost'].'</div>';
			
			$content .= display('',$ad_tpl);
			
			$last = $row['page_id'];
		}
		
		// Add load more function if exists
		$content .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'adsData(\'page_ads\','.$last.',6);') : '';

	} else {
		$content = closeBody($TEXT['_uni-No_pages_ads']);
	}
	
	return $content;

}

// Get list of ads created by the user
function listAds($user,$start=0) {
	global $TEXT,$db,$page_settings;
	
	// Add start
	$from = (is_numeric($start) && $start > 0 ) ? 'AND `ads`.`aid` < \''.$db->real_escape_string($start).'\'' : ''; 

	// Reset
	$rows = array();$content='';$limit=12;
	
	// Select ads
	$results = $db->query(sprintf("SELECT * FROM `ads` WHERE `ads`.`by_id` = '%s' $from ORDER BY `ads`.`aid` DESC LIMIT %s;",$db->real_escape_string($user['idu']),$db->real_escape_string($limit)));

	// If ads exists
	if(!empty($results) && $results->num_rows) {

		// fetch ads
		while($row = $results->fetch_assoc()) {
			$rows[] = $row;
		}
		
		$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;
	
	    $ad_tpl = display(templateSrc('/sponsors/listed_ad'),0,1);
		
		foreach($rows as $row) {
			
			$TEXT['temp-content'] = $TEXT['temp-icon'] = $TEXT['temp-type'] = '';
			
			// Post
			if($row['type'] == 1) {
				$TEXT['temp-type'] = $TEXT['_uni-Post'];
				
				list($post_text,$post_img) = postContent($row['cid']);
				
				$TEXT['temp-content'] = $post_text.$post_img;
				$TEXT['temp-icon'] = 'credit-card';
			
			// Page
			} elseif($row['type'] == 2) {
				$TEXT['temp-type'] = $TEXT['_uni-Page'];
				$TEXT['temp-icon'] = 'flag';
			    $TEXT['temp-content'] = pageContent($row['cid']);
				
			// Group
			} elseif($row['type'] == 3) {
				$TEXT['temp-type'] = $TEXT['_uni-Group'];
				$TEXT['temp-icon'] = 'users';
				$TEXT['temp-content'] = groupContent($row['cid']);
			}
			
			$TEXT['temp-time'] = addStamp($row['time'],2);
			$TEXT['temp-views'] = $row['v_left'];
			
			$content .= display('',$ad_tpl);
			
			$last = $row['aid'];
		}
		
		// Add load more function if exists
		$content .= (isset($loadmore)) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-Load_more'],'listAds('.$last.');') : '';

	} 
	
	return $content;

}

?>