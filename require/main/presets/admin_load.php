<?php

$bible_settings = explode(',',$page_settings['bible_view']);

// Boost ads
$boost_settings_inputs = array(
    
	// Paypal
	"paypal" => array(
	        array("input",$TEXT['_uni-Email'],"am-val-1",$TEXT['_uni-pp_email'],$page_settings['pp_email'],"SEP"),
	        array("input",$TEXT['_uni-pp_user1'],"am-val-2",$TEXT['_uni-pp_user'],$page_settings['pp_user'],"SEP"),
	        array("input",$TEXT['_uni-pp_pass1'],"am-val-3",$TEXT['_uni-pp_pass'],$page_settings['pp_pass'],"SEP"),
	        array("input",$TEXT['_uni-pp_sign1'],"am-val-4",$TEXT['_uni-pp_sign'],$page_settings['pp_sign'],"SEP"),
	        array("select",$TEXT['_uni-pp_sandbox1'],"am-val-5",$page_settings['pp_sandbox'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"NA",$TEXT['_uni-pp_sandbox']),
		),
		
	// Ads
	"boads" => array(
	        array("select",$TEXT['_uni-bo_ads1'],"am-val-1",$page_settings['bo_ads'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-bo_ads']),
	        array("selectthree",$TEXT['_uni-Limit'],"am-val-2",$page_settings['sp_feeds'],'1','2','3',"SEP",$TEXT['_uni-Limit_boost_2']),
	        array("input",$TEXT['_uni-per_view1'],"am-val-3",$TEXT['_uni-per_view'],$page_settings['v_price'],"SEP"),
	        array("input",$TEXT['_uni-curr1'],"am-val-4",$TEXT['_uni-curr'],$page_settings['v_cc'],"NA"),
		),
	
);

// Boost ads data	
$boost_settings_data = array(
    
	// Paypal
	"paypal" => array(
	        "heading" => $TEXT['_uni-Paypal_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(77,0,0,'save','paypal',0,7);",
	    ),
		
	// Boost ads settings
	"boads" => array(
	        "heading" => $TEXT['_uni-Ads_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(79,0,0,'save','boads',0,7);",
	    ),
		
);

// Plus features inputs
$plus_settings_inputs = array(
    
	// Bibleview
	"bibleview" => array(
	        array("select",$TEXT['_uni-News_feeds'],"am-val-1",$bible_settings[0],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Bible-title']),
	        array("select",$TEXT['_uni-Profile'],"am-val-2",$bible_settings[1],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",''),
	        array("select",$TEXT['_uni-Search'],"am-val-3",$bible_settings[2],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Bible-title2']),
	        array("select",$TEXT['_uni-Blogs'],"am-val-4",$bible_settings[3],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Bible-title3']),
	        array("select",$TEXT['_uni-Blog_post'],"am-val-5",$bible_settings[4],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Bible-title4']),
	        array("select",$TEXT['_uni-Trending'],"am-val-6",$bible_settings[5],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"NA",$TEXT['_uni-Bible-title5']),
	    ),
	
    // Fonts	
	"fonts" => array(
	        array("input",$TEXT['_uni-Font_size'],"am-val-1",$TEXT['_uni-Font_size_ttl'],$page_settings['font_size'],"NA"),
	    ),
		
);

// Plus features data	
$plus_settings_data = array(
    
	// Bible
	"bibleview" => array(
	        "heading" => $TEXT['_uni-Bible_verses'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(73,0,0,'save','bibleview',0,7);",
	    ),
		
	// Fonts
	"fonts" => array(
	        "heading" => $TEXT['_uni-Font_options'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(75,0,0,'save','fonts',0,7);",
	    ),
	
);

// Features settings inputs
$fea_settings_inputs = array(
    
	// Express
	"expcontent" => array(
	        array("select",$TEXT['_uni-Online'],"am-val-1",$page_settings['feature_expressfriends'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle9']),
	        array("select",$TEXT['_uni-Activity'],"am-val-2",$page_settings['feature_expressactivity'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle8']),
	        array("select",$TEXT['_uni-Suggestions'],"am-val-3",$page_settings['feature_expresssuggestions'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle6']),
	        array("select",$TEXT['_uni-Autoplay'],"am-val-4",$page_settings['feature_expressautoplay'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"NA",$TEXT['_uni-per_page_ttle66']),
		),
		
	// Trending tags
	"trendingtags" => array(
	        array("select",$TEXT['_uni-Home'],"am-val-1",$page_settings['feature_tags_on_home'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle27']),
	        array("select",$TEXT['_uni-Search'],"am-val-2",$page_settings['feature_tags_on_top_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle30']),
	        array("select",$TEXT['_uni-Groups'],"am-val-3",$page_settings['feature_tags_on_group_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle29']),
	        array("select",$TEXT['_uni-People'],"am-val-4",$page_settings['feature_tags_on_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle28']),
	        array("select",$TEXT['_uni-Videos'],"am-val-5",$page_settings['feature_tags_on_video_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle31']),
	        array("select",$TEXT['_uni-Tags'],"am-val-6",$page_settings['feature_tags_on_searchtags'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle32']),
	        array("select",$TEXT['_uni-Photos'],"am-val-7",$page_settings['feature_tags_on_searchphotos'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle33']),
		),
		
	// Groups joined
	"groupfeatures" => array(
	        array("select",$TEXT['_uni-Home'],"am-val-1",$page_settings['groups_on_home'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle34']),
	        array("select",$TEXT['_uni-Search'],"am-val-2",$page_settings['groups_on_top_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle35']),
	        array("select",$TEXT['_uni-Groups'],"am-val-3",$page_settings['groups_on_group_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle36']),
	        array("select",$TEXT['_uni-Pages'],"am-val-4",$page_settings['groups_on_page_search'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-per_page_ttle37']),
		),
		
	// Extra features
	"extra" => array(
	        array("select",$TEXT['_uni-Mentions'],"am-val-1",$page_settings['mentions_type'],$TEXT['_uni-Mentions-2'],$TEXT['_uni-Mentions-1'],"SEP",$TEXT['_uni-Mentions-title']),
	        array("select",$TEXT['_uni-Scrolling'],"am-val-2",$page_settings['inf_scroll'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"NA",$TEXT['_uni-inf_scroll']),
	    ),
		
);

// Features settings data	
$fea_settings_data = array(
    
	// Express
	"expcontent" => array(
	        "heading" => $TEXT['_uni-Express_content'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(34,0,0,'save','expcontent',0,7);",
	    ),
	
    // Trending tags
	"trendingtags" => array(
	        "heading" => $TEXT['_uni-Trending_hashtags'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(36,0,0,'save','trendingtags',0,7);",
	    ),
		
    // Groups joined
	"groupfeatures" => array(
	        "heading" => $TEXT['_uni-Groups_joined'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(38,0,0,'save','groupfeatures',0,7);",
	    ),

	// Extra features
	"extra" => array(
	        "heading" => $TEXT['_uni-Extra_features'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(16,0,0,'save','extra',0,7);",
	    ),
);

// Content settings inputs
$con_settings_inputs = array(
    
	// Users
	"users" => array(
	        array("selectthree",$TEXT['_uni-Limit'],"am-val-1",$page_settings['results_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle1']),
	        array("selectthree",$TEXT['_uni-Lovers'],"am-val-2",$page_settings['lovers_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle2']),
	        array("selectthree",$TEXT['_uni-Search'],"am-val-3",$page_settings['search_results_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle3']),
	        array("selectthree",$TEXT['_uni-Followers'],"am-val-4",$page_settings['public_profile_followers'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle4']),
	        array("selectthree",$TEXT['_uni-Followings'],"am-val-5",$page_settings['public_profile_followings'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle5']),
	        array("selectthree",$TEXT['_uni-Trending'],"am-val-6",$page_settings['trendind_per_limit'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle7']),
	        array("selectthree",$TEXT['_uni-Similar_users'],"am-val-7",$page_settings['public_profile_similar'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle11']),
	    ),
	
    // Express limits	
	"express" => array(
	       array("selectthree",$TEXT['_uni-Suggestions'],"am-val-1",$page_settings['suggestions_limit'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle6']),
	       array("selectthree",$TEXT['_uni-Activity'],"am-val-2",$page_settings['express_activity_per_limit'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle8']),
	       array("selectthree",$TEXT['_uni-Online'],"am-val-3",$page_settings['active_limit'],'10','15','25',"NA",$TEXT['_uni-per_page_ttle9']),
	    ),
		
	// Popular page
	"popular" => array(
	       array("selectthree",$TEXT['_uni-Articles'],"am-val-1",$page_settings['readable_blogs'],'3','6','9',"SEP",$TEXT['_uni-Popular-articles']),
	       array("selectthree",$TEXT['_uni-Groups'],"am-val-2",$page_settings['joinable_groups'],'5','10','15',"SEP",$TEXT['_uni-Popular-groups']),
	       array("selectthree",$TEXT['_uni-Pages'],"am-val-3",$page_settings['trendind_pages_limit'],'6','12','18',"SEP",$TEXT['_uni-Popular-pages']),
	       array("selectthree",$TEXT['_uni-Posts'],"am-val-4",$page_settings['t_posts_per_load'],'10','15','25',"SEP",$TEXT['_uni-Popular-posts']),
	       array("input",$TEXT['_uni-Categories'],"am-val-5",$TEXT['_uni-Popular-categories'],$page_settings['readable_categories'],"NA"),
	    ),
		
    // Posts limits	
	"posts" => array(
	       array("selectthree",$TEXT['_uni-Length'],"am-val-1",$page_settings['max_post_length'],'500','1500','2500',"SEP",$TEXT['_uni-per_page_ttle12']),
	       array("selectthree",$TEXT['_uni-Limit'],"am-val-2",$page_settings['posts_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle13']),
	       array("selectthree",$TEXT['_uni-Photos'],"am-val-3",$page_settings['photos_per_page'],'12','18','24',"SEP",$TEXT['_uni-per_page_ttle14']),
	       array("selectthree",$TEXT['_uni-Selection'],"am-val-4",$page_settings['MAX_IMAGES'],'5','10','25',"NA",$TEXT['_uni-per_page_ttle15']),
	    ),
		
    // Chats limits	
	"chats" => array(
	       array("selectthree",$TEXT['_uni-Length'],"am-val-1",$page_settings['max_message_length'],'500','1500','2500',"SEP",$TEXT['_uni-per_page_ttle16']),
	       array("selectthree",$TEXT['_uni-Limit'],"am-val-2",$page_settings['chats_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle17']),
	       array("selectthree",$TEXT['_uni-Name'],"am-val-3",$page_settings['min_chat_len1'],'6','8','12',"",$TEXT['_uni-per_page_ttle18']),
	       array("selectthree","","am-val-4",$page_settings['max_chat_len1'],'16','32','42',"SEP",$TEXT['_uni-per_page_ttle19']),
	       array("selectthree",$TEXT['_uni-Description'],"am-val-5",$page_settings['max_chat_len2'],'500','1000','1500',"NA",$TEXT['_uni-per_page_ttle20']),
	    ),
		
    // Groups limits	
	"groups" => array(
	       array("selectthree",$TEXT['_uni-Search'],"am-val-1",$page_settings['group_results_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle21']),
	       array("selectthree",$TEXT['_uni-Requests'],"am-val-2",$page_settings['group_requests_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle22']),
	       array("selectthree",$TEXT['_uni-Logs'],"am-val-3",$page_settings['group_log_per_page'],'10','15','25',"SEP",$TEXT['_uni-per_page_ttle23']),
	       array("selectthree",$TEXT['_uni-Joined'],"am-val-4",$page_settings['groups_joined_limit'],'10','15','25',"NA",$TEXT['_uni-per_page_ttle24']),
	    ),
		
    // Comments limits	
	"comments" => array(
	       array("selectthree",$TEXT['_uni-Length'],"am-val-1",$page_settings['max_comment_length'],'500','1500','2500',"SEP",$TEXT['_uni-per_page_ttle25']),
	       array("selectthree",$TEXT['_uni-Limit'],"am-val-2",$page_settings['comments_per_widget'],'4','6','12',"SEP",$TEXT['_uni-per_page_ttle26']),
	    ),
		
);

// Major settings data	
$con_settings_data = array(
    
	// Users
	"users" => array(
	        "heading" => $TEXT['_uni-Users'].' '.$TEXT['_uni-limits'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(22,0,0,'save','users',0,7);",
	    ),
    
	// Posts
	"posts" => array(
	        "heading" => $TEXT['_uni-Posts'].' '.$TEXT['_uni-limits'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(26,0,0,'save','posts',0,7);",
	    ),
		
	// Express
	"express" => array(
	        "heading" => $TEXT['_uni-Express_content'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(24,0,0,'save','express',0,7);",
	    ),
		
	// Popular
	"popular" => array(
	        "heading" => $TEXT['_uni-Popular_page'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(83,0,0,'save','popular',0,7);",
	    ),
		
	// Chats
	"chats" => array(
	        "heading" => $TEXT['_uni-Chats'].' '.$TEXT['_uni-limits'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(28,0,0,'save','chats',0,7);",
	    ),
		
	// Groups
	"groups" => array(
	        "heading" => $TEXT['_uni-Groups'].' '.$TEXT['_uni-limits'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(30,0,0,'save','groups',0,7);",
	    ),
		
	// Comments
	"comments" => array(
	        "heading" => $TEXT['_uni-Comments'].' '.$TEXT['_uni-limits'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(32,0,0,'save','comments',0,7);",
	    ),
);
		
// Major settings inputs
$maj_settings_inputs = array(
    
	// SMTP settings
	"smtp" => array(
	        array("select",$TEXT['_uni-Integration'],"am-val-1",$page_settings['smtp_email'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Enable_SMTP_integration']),
	        array("select",$TEXT['_uni-Authentication'],"am-val-2",$page_settings['smtp_auth'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Enable_SMTP_Authentication']),
	        array("input",$TEXT['_uni-SMTP_host'],"am-val-3",$TEXT['_uni-Your_SMTP_host'],$page_settings['smtp_host'],"SEP"),
	        array("input",$TEXT['_uni-SMTP_port'],"am-val-4",$TEXT['_uni-SMTP_port_emails'],$page_settings['smtp_port'],"SEP"),
	        array("input",$TEXT['_uni-Username'],"am-val-5",$TEXT['_uni-Your_email_SMTP_username'],$page_settings['smtp_username'],"SEP"),
	        array("input",$TEXT['_uni-Password'],"am-val-6",$TEXT['_uni-Your_email_password'],$page_settings['smtp_password'],"NA"),
	    ),
		
    // Polling settings
	"polling" => array(
	        array("select",$TEXT['_uni-Notifications'],"am-val-1",$page_settings['poll_notifications'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Polling_settings_ttl-2']),
	        array("select",$TEXT['_uni-Messenger'],"am-val-2",$page_settings['poll_inbox'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Polling_settings_ttl-1']), 
			array("select",$TEXT['_uni-Chats'],"am-val-3",$page_settings['poll_messages'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"NA",$TEXT['_uni-Polling_settings_ttl-3']),
		),
		
    // socket settings
	"sockets" => array(
		array("select",$TEXT['_uni-Allow_Sockets'],"am-val-1",$page_settings['websocketserver'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Allow_Sockets_ttl']),
		array("input",$TEXT['_uni-Socket_host'],"am-val-2",$TEXT['_uni-Scoket_host_ttl'],$page_settings['socket_host'],"NA"),
	),
		
	// SEO settings
	"seo" => array(
	        array("discription",$TEXT['_uni-Meta_keywords'],"am-val-1",$TEXT['_uni-Meta_keywords_ttl'],getMetaTags(1),"SEP"),
	        array("discription",$TEXT['_uni-Description'],"am-val-2",$TEXT['_uni-Meta_description_ttl'],getMetaTags(0),"NA"),
	    ),

	// Image settings
	"images" => array(
			array("selectthree",$TEXT['_uni-Quality'],"am-val-1",$page_settings['jpeg_quality'],'75','90','100',"SEP",$TEXT['_uni-Quality_ttl']),
			array("selectthree",$TEXT['_uni-Image_Size'],"am-val-2",$page_settings['max_img_size'],'5','10','20',"SEP",$TEXT['_uni-Image_Size_ttl']),
			array("selectthree",$TEXT['_uni-Post'],"am-val-3",$page_settings['max_image_size'],'720','1080','2160',"SEP",$TEXT['_uni-Image_comm_1']),
			array("selectthree",$TEXT['_uni-Avatars'],"am-val-4",$page_settings['max_main_pics'],'720','1080','2160',"SEP",$TEXT['_uni-Image_comm_2']),
			array("selectthree",$TEXT['_uni-Covers'],"am-val-5",$page_settings['max_cover_pics'],'720','1080','2160',"SEP",$TEXT['_uni-Image_comm_3']),
			array("selectthree",$TEXT['_uni-Chat_icon'],"am-val-6",$page_settings['max_chat_icons'],'720','1080','2160',"SEP",$TEXT['_uni-Image_comm_4']),
			array("selectthree",$TEXT['_uni-Chat_cover'],"am-val-7",$page_settings['max_chat_covers'],'720','1080','2160',"",$TEXT['_uni-Image_comm_5']),
		),
		
	// Video settings
	"videos" => array(
			array("input",$TEXT['_uni-Video_Size'],"am-val-1",$TEXT['_uni-Video_Size_ttl'],$page_settings['max_vid_size'],"SEP"),
			array("input",$TEXT['_uni-Extensions'],"am-val-2",$TEXT['_uni-Video_ext_ttl'],$page_settings['video_extensions'],"NA"),
		),
		
	// Website settings
	"website" => array(
	        array("input",$TEXT['_uni-Name'],"am-val-1",$TEXT['_uni-Website_name_ttl'],$page_settings['web_name'],"SEP"),
	        array("input",$TEXT['_uni-Title'],"am-val-2",$TEXT['_uni-Website_title_ttl'],$page_settings['title'],"SEP"),
	        array("input",$TEXT['_uni-Timezone'],"am-val-3",$TEXT['_uni-Timezone_ttl'],$page_settings['timezone'],"NA"),
		),		
    );
	
// Major settings data	
$maj_settings_data = array(
    
	// SMTP settings
	"smtp" => array(
	        "heading" => $TEXT['_uni-SMTP_conf'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(3,0,0,'save','smtp',0,7);",
	    ),
		
	// SEO settings
	"seo" => array(
	        "heading" => $TEXT['_uni-SEO_Settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(4,0,0,'save','seo',0,7);",
	    ),
			
	// Image settings
	"images" => array(
	        "heading" => $TEXT['_uni-Image_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(18,0,0,'save','images',0,7);",
	    ),
		
	// Video settings
	"videos" => array(
	        "heading" => $TEXT['_uni-Video_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(81,0,0,'save','videos',0,7);",
	    ),
		
     // Polling settings
	"polling" => array(
	        "heading" => $TEXT['_uni-Polling_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(69,0,0,'save','polling',0,7);",
		),

	// Socket settings
	"sockets" => array(
	        "heading" => $TEXT['_uni-WebSockets_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(85,0,0,'save','sockets',0,7);",
		),

	// Website settings
	"website" => array(
	        "heading" => $TEXT['_uni-Website_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(20,0,0,'save','website',0,7);",
	    ),
		
    );
	
// Registration settings	
$reg_settings_inputs = array(
    
	// Notification settings
	"notification" => array(
	        array("select",$TEXT['_uni-Method'],"am-val-1",$page_settings['def_n_type'],$TEXT['_uni-Real_time'],$TEXT['_uni-Manual'],"SEP",$TEXT['_uni-TTL-not-type-sml']),
	        array("selectthree",$TEXT['_uni-Limit'],"am-val-2",$page_settings['def_n_per_page'],'5','10','15',"SEP",$TEXT['_uni-TTL-not-page-sml']),
	        array("title",$TEXT['_uni-Notifications_me_when2']),
	        array("select",$TEXT['_uni-Request'],"am-val-3",$page_settings['def_n_accept'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Accept_my_re2']),
	        array("select",$TEXT['_uni-TTL-not-foll-sml'],"am-val-4",$page_settings['def_n_follower'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",""),
	        array("select",$TEXT['_uni-TTL-not-like-sml'],"am-val-5",$page_settings['def_n_like'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",""),
	        array("select",$TEXT['_uni-TTL-not-cmmt-sml'],"am-val-6",$page_settings['def_n_comment'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"NA",""),
	    ),
		
	// Blocking settings
	"blocking" => array(
	        array("select",$TEXT['_uni-Users'],"am-val-1",$page_settings['def_b_users'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Block_new_users_reporting']),
	        array("select",$TEXT['_uni-Posts'],"am-val-2",$page_settings['def_b_posts'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Block_new_users_reporting']),
	        array("select",$TEXT['_uni-Comments'],"am-val-3",$page_settings['def_b_comments'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Block_new_users_reporting']),
	    ),	

	// Privacy settings
	"privacy" => array(
	        array("select",$TEXT['_uni-Follow'],"am-val-1",$page_settings['def_p_private'],$TEXT['_uni-Requires_approval'],$TEXT['_uni-Yes'],"SEP",$TEXT['_uni-Can_follow2']),
	        array("title",$TEXT['_uni-Who_can_see2']),
	        array("select",$TEXT['_uni-Posts'].' / '.$TEXT['_uni-Gallery'],"am-val-2",$page_settings['def_p_posts'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],"SEP",""),
	        array("select",$TEXT['_uni-Followers'],"am-val-3",$page_settings['def_p_followers'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],"SEP",""),
	        array("select",$TEXT['_uni-Followings'],"am-val-4",$page_settings['def_p_followings'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],"SEP",""),
	        array("select",$TEXT['_uni-Profession'],"am-val-5",$page_settings['def_p_profession'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],"SEP",""),
	        array("select",$TEXT['_uni-Hometown'],"am-val-6",$page_settings['def_p_hometown'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],"SEP",""),
	        array("select",$TEXT['_uni-Living_in'],"am-val-7",$page_settings['def_p_location'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],"SEP",""),
	    ),

	// Search settings
	"search" => array(
	        array("title",$TEXT['_uni-Profile_search_results']),
	        array("selectthree",$TEXT['_uni-Posts'],"am-val-1",$page_settings['def_r_posts_per_page'],"5","8","12","SEP",$TEXT['_uni-Maximum_posts_included']),
	        array("selectthree",$TEXT['_uni-Followers'],"am-val-2",$page_settings['def_r_followers_per_page'],"8","10","15","SEP",$TEXT['_uni-Maximum_followers_included']),
	        array("selectthree",$TEXT['_uni-Followings'],"am-val-3",$page_settings['def_r_followings_per_page'],"8","10","15","SEP",$TEXT['_uni-Maximum_followings_included']),
	    ),
		
	// Security settings
	"security" => array(
	        array("select",$TEXT['_uni-Captcha'],"am-val-1",$page_settings['captcha'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Enable_Captcha']),
	        array("select",$TEXT['_uni-E-Mail_verification3'],"am-val-2",$page_settings['emails_verification'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Enable_E-Mail_verification']),
	        array("selectthree",$TEXT['_uni-Username'],"am-val-3",$page_settings['username_min_len'],"4","6","8","NA",$TEXT['_uni-Username_Minimum_length']),
	        array("selectthree","","am-val-4",$page_settings['username_max_len'],"15","20","32","SEP",$TEXT['_uni-Username_Maximum_length']),
	        array("selectthree",$TEXT['_uni-Password'],"am-val-5",$page_settings['password_min_len'],"4","6","8","NA",$TEXT['_uni-Password_Minimum_length']),
	        array("selectthree","","am-val-6",$page_settings['password_max_len'],"15","20","32","SEP",$TEXT['_uni-Password_Maximum_length']),
			array("select",$TEXT['_uni-Verified'],"am-val-7",$page_settings['def_p_verified'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],"SEP",$TEXT['_uni-Profile_verified_2']),
		),

    );
	
// Registration settings
$reg_settings_data = array(
    
	// Notification settings
	"notification" => array(
	        "heading" => $TEXT['_uni-Notifications_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(6,0,0,'save','notification',0,7);",
	    ),
		
	// Blocking settings
	"blocking" => array(
	        "heading" => $TEXT['_uni-Blocking_Settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(8,0,0,'save','blocking',0,7);",
	    ),
		
	// Privacy settings
	"privacy" => array(
	        "heading" => $TEXT['_uni-Privacy_settings'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(10,0,0,'save','privacy',0,7);",
	    ),
	
    // Search settings	
	"search" => array(
	        "heading" => $TEXT['_uni-Search_options'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(12,0,0,'save','search',0,7);",
	    ),
		
    // Security settings	
	"security" => array(
	        "heading" => $TEXT['_uni-Security_options'],
	        "heading_btn" => $TEXT['_uni-Save_changes'],
	        "jsfunction" => "_admin(14,0,0,'save','security',0,7);",
	    ),
	
		
    );

?>