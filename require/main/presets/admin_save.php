<?php
// Protection set enables protectInput() function for all the fields listed in array()s
$protection_smtp = array(); 
$protection_notification = array(); 
$protection_blocking = array(); 
$protection_privacy = array(); 
$protection_search = array(); 
$protection_security = array(); 
$protection_extra = array(); 
$protection_images = array(); 
$protection_website = array(); 
$protection_users = array(); 
$protection_express = array(); 
$protection_posts = array(); 
$protection_chats = array(); 
$protection_groups = array(); 
$protection_comments = array(); 
$protection_expcontent = array(); 
$protection_trendingtags = array(); 
$protection_groupfeatures = array(); 
$protection_polling = array(); 
$protection_bible = array(); 
$protection_fonts = array(); 
$protection_paypal = array(); 
$protection_boads = array(); 
$protection_videos = array(); 
$protection_popular = array(); 
$protection_sockets = array(); 

// Boost
$boost_settings_keys = array(
    
	// Paypal
	"paypal" => array(
	        0 => "pp_email",
	        1 => "pp_user",
	        2 => "pp_pass",
	        3 => "pp_sign",
	        4 => "pp_sandbox",
	        5 => "_uni-Settings_updated",
	    ),
	
    // Boost Ads	
	"boads" => array(
	        0 => "bo_ads",
	        1 => "sp_feeds",
	        2 => "v_price",
	        3 => "v_cc",
	        4 => "_uni-Settings_updated",
	    ),
		
);

// Plus features
$plus_settings_keys = array(
    
	// Bible view
	"bibleview" => array(
	        0 => "bible_view",
	        1 => "_uni-Settings_updated",
	    ),
		
	// Fonts
	"fonts" => array(
	        0 => "font_size",
	        1 => "refresh_code",
	        2 => "_uni-Settings_updated",
	    ),
		
);
		
// Feature settings
$fea_settings_keys = array(
    
	// Groups joined
	"groupfeatures" => array(
	        0 => "groups_on_home",
	        1 => "groups_on_top_search",
	        2 => "groups_on_group_search",
	        3 => "groups_on_page_search",
	        4 => "_uni-Settings_updated",
	    ),

	// Express
	"expcontent" => array(
	        0 => "feature_expressfriends",
	        1 => "feature_expressactivity",
	        2 => "feature_expresssuggestions",
	        3 => "feature_expressautoplay",
	        4 => "_uni-Settings_updated",
	    ),
		
	// Trending Tags
	"trendingtags" => array(
	        0 => "feature_tags_on_home",
	        1 => "feature_tags_on_top_search",
	        2 => "feature_tags_on_group_search",
	        3 => "feature_tags_on_search",
	        4 => "feature_tags_on_video_search",
	        5 => "feature_tags_on_searchtags",
	        6 => "feature_tags_on_searchphotos",
	        7 => "_uni-Settings_updated",
	    ),
		
	// Extra settings
	"extra" => array(
	        0 => "mentions_type",
	        1 => "inf_scroll",
	        2 => "_uni-Settings_updated",
	    ),
);

// Content settings
$con_settings_keys = array(
    
	// Users
	"users" => array(
	        0 => "results_per_page",
	        1 => "lovers_per_page",
	        2 => "search_results_per_page",
	        3 => "public_profile_followers",
	        4 => "public_profile_followings",
	        5 => "trendind_per_limit",
	        6 => "public_profile_similar",
	        7 => "_uni-Settings_updated",
	    ),
		
	// Express
	"express" => array(
	        0 => "suggestions_limit",
	        1 => "express_activity_per_limit",
	        2 => "active_limit",
	        3 => "_uni-Settings_updated",
	    ),
		
	// Popular
	"popular" => array(
	        0 => "readable_blogs",
	        1 => "joinable_groups",
	        2 => "trendind_pages_limit",
	        3 => "t_posts_per_load",
	        4 => "readable_categories",
	        5 => "_uni-Settings_updated",
	    ),
		
	// Posts
	"posts" => array(
	        0 => "max_post_length",
	        1 => "posts_per_page",
	        2 => "photos_per_page",
	        3 => "MAX_IMAGES",
	        4 => "_uni-Settings_updated",
	    ),
		
	// Chats
	"chats" => array(
	        0 => "max_message_length",
	        1 => "chats_per_page",
	        2 => "min_chat_len1",
	        3 => "max_chat_len1",
	        4 => "max_chat_len2",
	        5 => "_uni-Settings_updated",
	    ),
		
	// Groups
	"groups" => array(
	        0 => "group_results_per_page",
	        1 => "group_requests_per_page",
	        2 => "group_log_per_page",
	        3 => "groups_joined_limit",
	        4 => "_uni-Settings_updated",
	    ),
		
	// Comments
	"comments" => array(
	        0 => "max_comment_length",
	        1 => "comments_per_widget",
	        2 => "_uni-Settings_updated",
	    ),
);

// Major settings
$maj_settings_keys = array(
    
	// SMTP settings
	"smtp" => array(
	        0 => "smtp_email",
	        1 => "smtp_auth",
	        2 => "smtp_host",
	        3 => "smtp_port",
	        4 => "smtp_username",
	        5 => "smtp_password",
	        6 => "_uni-Settings_updated",
	    ),
		
	// Polling settings
	"polling" => array(
	        0 => "poll_notifications",
	        1 => "poll_inbox",
	        2 => "poll_messages",
	        3 => "_uni-Settings_updated",
	    ),
	
	// Socket settings
	"sockets" => array(
	        0 => "websocketserver",
	        1 => "socket_host",
	        2 => "_uni-Settings_updated",
		),
		
	// Image settings
	"images" => array(
	        0 => "jpeg_quality",
	        1 => "max_img_size",
	        2 => "max_image_size",
	        3 => "max_main_pics",
	        4 => "max_cover_pics",
	        5 => "max_chat_icons",
	        6 => "max_chat_covers",
	        7 => "_uni-Settings_updated",
	    ),
		
	// Video settings
	"videos" => array(
	        0 => "max_vid_size",
	        1 => "video_extensions",
	        2 => "_uni-Settings_updated",
	    ),
		
	// Website settings
	"website" => array(
	        0 => "web_name",
	        1 => "title",
	        2 => "timezone",
	        3 => "_uni-Settings_updated",
	    ),
		
    );

// Registration settings	
$reg_settings_keys = array(

	// Notification settings
	"notification" => array(
	        0 => "def_n_type",
	        1 => "def_n_per_page",
	        2 => "def_n_accept",
	        3 => "def_n_follower",
	        4 => "def_n_like",
	        5 => "def_n_comment",
	        6 => "_uni-Settings_updated",
	    ),

	// Blocking settings
	"blocking" => array(
	        0 => "def_b_users",
	        1 => "def_b_posts",
	        2 => "def_b_comments",
	        3 => "_uni-Settings_updated",
	    ),

	// Privacy settings
	"privacy" => array(
	        0 => "def_p_private",
	        1 => "def_p_posts",
	        2 => "def_p_followers",
	        3 => "def_p_followings",
	        4 => "def_p_profession",
	        5 => "def_p_hometown",
	        6 => "def_p_location",
	        7 => "_uni-Settings_updated",
	    ),
		
	// Search settings
	"search" => array(
	        0 => "def_r_posts_per_page",
	        1 => "def_r_followers_per_page",
	        2 => "def_r_followings_per_page",
	        3 => "_uni-Settings_updated",
	    ),
		
	// Security settings
	"security" => array(
	        0 => "captcha",
	        1 => "emails_verification",
	        2 => "username_min_len",
	        3 => "username_max_len",
	        4 => "password_min_len",
	        5 => "password_max_len",
	        6 => "def_p_verified",
	        7 => "_uni-Settings_updated",
	    ),
		
    );

?>