<?php

// Import settings
$import = $db->query("SELECT * FROM `settings`");

// if settings exists
if($import->num_rows) {
	
	// Reset imports
	$imported = array();
	
	// Fetch settings
	while($imports = $import->fetch_assoc()) {
		$imported[] = $imports;
	}
	
	// Bind settings
	foreach($imported as $setting) {
		$page_settings[$setting['key']] = $setting['value'];
	}
	
// Else set settings manually
} else {
    
    // Main settings 
    $page_settings['web_name'] = 'Breeze'; 
    $page_settings['title'] = 'Breeze';
    $page_settings['theme'] = 'Standards';
	
    // Time line | Home | News feeds
    $page_settings['posts_per_page'] = 10;  

    // Gallery
    $page_settings['photos_per_page'] = 12;

	// Multi image upload
    $page_settings['MAX_IMAGES'] = 10 ;	

    // Followers | Followings          
    $page_settings['results_per_page'] = 10;
	
    // Lovers | Comments          
    $page_settings['lovers_per_page'] = 10;
    $page_settings['comments_per_widget'] = 6;
	
	// FOLLOWERS FOR PUBLIC
    $page_settings['public_profile_followers'] = 8;

    // FOLLOWINGS FOR PUBLIC	
    $page_settings['public_profile_followings'] = 8; 
	
	// TRENDING VERIFIED PROFILES
    $page_settings['trendind_per_limit'] = 10;
	
	// EXPRESS FRIENDS ACTIVITY LIMIT
    $page_settings['express_activity_per_limit'] = 10;    
	
	// EXPRESS SUGGESTIONS LIMIT
    $page_settings['suggestions_limit'] = 10;             
    
	// EXPRESS ONLINE USERS LIMIT
	$page_settings['active_limit'] = 10;                  

	// SIMILAR USERS FOR PUBLIC LIMIT
	$page_settings['public_profile_similar'] = 10; 
	
	// CHAT NAME MIN LIMIT
	$page_settings['min_chat_len1'] = 6;                  
	
	// CHAT NAME MAX LIMIT
	$page_settings['max_chat_len1'] = 32;                 
	
	// CHAT DESCRIPTION MAX LIMIT
	$page_settings['max_chat_len2'] = 500;               

	// Chats per page
    $page_settings['chats_per_page'] = 10;

	// Search results
    $page_settings['search_results_per_page'] = 15;
    $page_settings['group_results_per_page'] = 10;       
 
    // MAX characters
    $page_settings['max_post_length'] = 500;
    $page_settings['max_comment_length'] = 500;
    $page_settings['max_message_length'] = 500;

    // Image settings
	$page_settings['jpeg_quality'] = 90;
	$page_settings['max_img_size'] = 2;
    $page_settings['max_image_size'] = 1280;
    $page_settings['max_main_pics'] = 1280;
    $page_settings['max_cover_pics'] = 1280;
	$page_settings['max_chat_icons'] = 1280 ;
    $page_settings['max_chat_covers'] = 1280 ;
   	
	// Welcome screen fonts because background image colours might make fonts less visible
    $page_settings['font_colors_welcome'] = 'grey'; 

	// On registration
	$page_settings['captcha'] = 1;
	
	// @mentions || mentions!
	$page_settings['mentions_type'] = 1;
	
	// Infinite scrolling on desktop
	$page_settings['inf_scroll'] = 1;

	// SMTP integration settings 	
	$page_settings['smtp_email'] = 0; 
	$page_settings['smtp_host'] = ''; 
	$page_settings['smtp_port'] = ''; 
	$page_settings['smtp_auth'] = 0;
	$page_settings['smtp_username'] = ''; 
	$page_settings['smtp_password'] = '';
	
	// GROUP MEMBER REQUESTS LIMIT
	$page_settings['group_requests_per_page'] = 15;  
    
	// GROUP ACTIVITY LOG LIMIT    
	$page_settings['group_log_per_page'] = 15;

    // GROUP JOINED LIMIT	
	$page_settings['groups_joined_limit'] = 15;            

	// USERNAME MIN and MAX lenghts
    $page_settings['username_min_len'] = 6;  
    $page_settings['username_max_len'] = 32;

	// Password MIN and MAX lengths
    $page_settings['password_min_len'] = 6;  
    $page_settings['password_max_len'] = 32;

	// User emails must be confirmed ? for new as well as registered
	$page_settings['emails_verification'] = 0;
	
	// Whether new profiles are verified
    $page_settings['def_p_verified'] = 0;
	
    // Profile default images name
    $page_settings['def_p_image'] = 'default.png'; 
    $page_settings['def_p_cover'] = 'default.png'; 

    // Default notifications settings
    $page_settings['def_n_per_page'] = 5 ; 
    $page_settings['def_n_accept'] = 0 ; 
    $page_settings['def_n_type'] = 1 ; 
    $page_settings['def_n_follower'] = 1 ; 
    $page_settings['def_n_like'] = 1 ; 
    $page_settings['def_n_comment'] = 1 ; 

    // Privacies default
	$page_settings['def_p_moderators'] = 0 ; 
    $page_settings['def_p_posts'] = 0 ; 
    $page_settings['def_p_followers'] = 0 ; 
    $page_settings['def_p_followings'] = 0 ; 
    $page_settings['def_p_profession'] = 0 ; 
    $page_settings['def_p_hometown'] = 0 ; 
    $page_settings['def_p_location'] = 0 ; 
    $page_settings['def_p_private'] = 0 ; 

    // Default features blocking users for new users
    $page_settings['def_b_posts'] = 0 ; 
    $page_settings['def_b_comments'] = 0 ; 
    $page_settings['def_b_users'] = 0 ; 

    // Profile page search settings
    $page_settings['def_r_posts_per_page'] = 5;
    $page_settings['def_r_followers_per_page'] = 10;
    $page_settings['def_r_followings_per_page'] = 10;

	// Pop-up ads
	$page_settings['po_add_visit'] = '';
	$page_settings['po_add_out'] = '';
	$page_settings['po_add_home'] = '';
	$page_settings['po_add_trending'] = '';
	$page_settings['po_add_conn_user'] = '';
	$page_settings['po_add_conn_post'] = '';
	
	// Fixed ads
	$page_settings['fi_add_home1'] = '';
	$page_settings['fi_add_search'] = '';
	$page_settings['fi_add_feed'] = '';
	$page_settings['fi_add_trending'] = '';
	$page_settings['fi_add_post'] = '';
	$page_settings['fi_add_relatives'] = '';
	
	// Active post backgrounds
	$page_settings['post_backgrounds'] = '10,7,5,6,3,4,1,2,8,9';
	
	// Default Language
	$page_settings['default_lang'] = 'English';
	
	// EXPRESS FRIENDS ONLINE
	$page_settings['feature_expressfriends'] = 1;

	// EXPRESS FRIENDS ACTIVITY
    $page_settings['feature_expressactivity'] = 1;
	
	// EXPRESS FRIENDS SUGGESTIONS
	$page_settings['feature_expresssuggestions'] = 1;

    // EXPRESS FRIENDS SUGGESTIONS AUTOPLAY
	$page_settings['feature_expressautoplay'] = 1;
	
	// TRENDING HASTAGS ON SEARCH PEOPLE
	$page_settings['feature_tags_on_search'] =1;          
	
	// TRENDING HASTAGS ON SEARCH GROUP
	$page_settings['feature_tags_on_group_search'] = 1;
	
	// TRENDING HASTAGS ON TOP SEARCH
	$page_settings['feature_tags_on_top_search'] = 1;     
	
	// TRENDING HASTAGS ON VIDEO SEARCH PAGE
	$page_settings['feature_tags_on_video_search'] = 1;   
	
	// TRENDING HASTAGS ON SEARCH HASHTAGS PAGE
	$page_settings['feature_tags_on_searchtags'] = 1;     
	
	// TRENDING HASTAGS ON SEARCH PHOTOS PAGE
	$page_settings['feature_tags_on_searchphotos'] = 1;
	
	// TRENDING HASTAGS ON HOME
	$page_settings['feature_tags_on_home'] = 1; 

	// GROUPS JOINED ON HOME
	$page_settings['groups_on_home'] = 1; 
 
    // GROUPS JOINED ON TOP SEARCH PAGE 
	$page_settings['groups_on_top_search'] = 1;  

    // GROUPS JOINED ON GROUP SEARCH PAGE	
	$page_settings['groups_on_group_search'] = 0; 

    // GROUPS JOINED ON PAGE SEARCH PAGE	
	$page_settings['groups_on_page_search'] = 0; 

    $TEXT['FONT_SIZE'] = '10';	
    $TEXT['REFRESH_CODE'] = '?4.2.01';	

}

// Set timezone
if($page_settings['timezone']) {
	date_default_timezone_set($page_settings['timezone']);
}

// YOU CAN CHANGE THESE SETTINGS --------------------------------------------------------

// UPDATION URL
$page_settings['UP_ADDRESS'] = 'https://purc.in/project/breeze/kta.express/interactive/v1/';

// ENABLE DISABLE FEATURES (More features are coming to admin panel)
$page_settings['feature_pe_tren_on_home'] = 1;        // PEOPLE YOU MAY KNOW & TRENDING LAST MONTH ON HOME
$page_settings['feature_trending_on_home'] = 1;       // TRENDING VERIFIED PROFILES ON HOME

// PER PAGE LIMITS
$page_settings['trending_per_page'] = 18;             // TRENDING PHOTOS LIMIT
$page_settings['trendinghashtags_limit'] = 15;        // TRENDING HASTAGS LIMIT


// Fixed settings (Don't change these settings) -----------------------------------------

// ---------- IMAGE DATAs ----------- //  
$TEXT['DATA-IMG-12'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAxAQMAAACiZWhcAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAAA5JREFUeNpjYBgFgwEAAAGIAAHvCeInAAAAAElFTkSuQmCC';
$TEXT['DATA-IMG-5'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkAQMAAADbzgrbAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAAAxJREFUeNpjYBieAAAA2AABB43sDAAAAABJRU5ErkJggg==';
$TEXT['DATA-IMG-7'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYAQMAAADaua+7AAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAAAxJREFUeNpjYKAtAAAAYAAB2E7biwAAAABJRU5ErkJggg==';

// HTACCESS RELATED
$TEXT['settings_htaccess_enabled'] = 1;          
$TEXT['profile_url'] = '/profile/';                   
$TEXT['post_url'] = '/view/'; 

// TEMPLATE SETTINGS
$TEXT['BOX_TEMP_CLASSES'] = '';  
$TEXT['REFRESH_CODE'] = $page_settings['refresh_code'];  
$TEXT['FONT_SIZE'] = $page_settings['font_size'];  
$TEXT['templates_extension'] = '.html';  

// UPLOAD RELATED
$page_settings['folder_post_photos'] = '../../../uploads/posts/photos/';
$page_settings['folder_main_photos'] = '../../../uploads/profile/main/';
$page_settings['folder_cover_photos'] = '../../../uploads/profile/covers/';
$page_settings['folder_group_cover_photos'] = '../../../uploads/groups/';
$page_settings['folder_page_cover_photos'] = '../../../uploads/pages/covers/';
$page_settings['folder_page_main_photos'] = '../../../uploads/pages/main/';
$page_settings['folder_chat_icons'] = '../../../uploads/chats/icons/';
$page_settings['folder_chat_covers'] = '../../../uploads/chats/covers/';
$page_settings['folder_blog_photos'] = '../../../uploads/blogs/photos/';

// APPLY SETTINGS
$TEXT['web_name'] = $page_settings['web_name'];
$TEXT['title'] = $TEXT['webtitle'] = $page_settings['title'];
$TEXT['theme'] = $page_settings['theme'] ;
$TEXT['font_colors_welcome'] = $page_settings['font_colors_welcome'];
$TEXT['ACTIVE_BACKGROUNDS'] = $page_settings['post_backgrounds'];

// CAPTCHA SETTINGS
$TEXT['temp-CAPTCHA_AVAIL'] = ($page_settings['captcha'] == 1) ? 'captcha();captchaClear(0);' : 'captchaClear(1);';
$TEXT['temp-REF_AVAIL'] = '';
$TEXT['temp-poll_inbox'] = $page_settings['poll_inbox'];
$TEXT['temp-websocketserver'] = $page_settings['websocketserver'];
$TEXT['temp-host'] = $page_settings['socket_host'];
$TEXT['temp-poll_messages'] = $page_settings['poll_messages'];
$TEXT['temp-poll_notifications'] = $page_settings['poll_notifications'];

// INFO PAGES
$i_pages = $db->query("SELECT * FROM `info_pages` WHERE `public` = '1' AND `published` = '1' ORDER BY `id` ASC");
if($i_pages->num_rows) {
	
	// Reset imports
	$i_all_pages = array();
	$TEXT['temp-i_pages'] = '<a href="'.$TEXT['installation'].'/blogs">Blogs</a>';
	
	// Fetch settings
	while($i_page = $i_pages->fetch_assoc()) {
		$i_all_pages[] = $i_page;
	}
	
	// Bind settings
	foreach($i_all_pages as $i_page) {
		$TEXT['temp-i_pages'] .= '<a href="'.$TEXT['installation'].'/browse/'.$i_page['id'].'">'.$i_page['title'].'</a>';
	}
}

// -------------------------------------------------------------------------------------	

?>