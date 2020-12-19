function _masterHIS(id) {         /* History(Dynamic URLs) management  */

    // Admin history index
	var _index_his = [
	"_",                          // - (0) - // Interval counters(Added for reading)
    "/manage/smtp",               // (Start)(Load) SMTP Settings
    "/manage/seo",                // (Start)(Load) SEO Settings
    "_",                          // (Start)(Save) SMTP Settings
    "_",                          // (Start)(Save) SEO Settings
    "/manage/notification",       // (Start)(Load) Notification Settings     - (05) - 
    "_",                          // (Start)(Save) Notification Settings
    "/manage/blocking",           // (Start)(Load) Blocking Settings
    "_",                          // (Start)(Save) Notification Settings
    "/manage/privacy",            // (Start)(Load) Privacy Settings
    "_",                          // (Start)(Save) Notification Settings     - (10) -
    "/manage/regsearch",          // (Start)(Load) User Search Settings
    "_",                          // (Start)(Save) User Search Settings
    "/manage/security",           // (Start)(Load) Security Settings
    "_",                          // (Start)(Save) Security Settings
    "/manage/extra",              // (Start)(Load) Extra features            - (15) -
    "_",                          // (Start)(Save) Extra features
    "/manage/images",             // (Start)(Load) Image Settings
    "_",                          // (Start)(Save) Image Settings
    "/manage/website",            // (Start)(Load) Website Settings
    "_",                          // (Start)(Save) Website Settings          - (20) -
    "/manage/user",               // (Start)(Load) Users Limits
    "_",                          // (Start)(Save) Users Limits 
    "/manage/express",            // (Start)(Load) Express Limits
    "_",                          // (Start)(Save) Express Limits
    "/manage/posts",              // (Start)(Load) Posts Limits              - (25) -
    "_",                          // (Start)(Save) Posts Limits
    "/manage/chats",              // (Start)(Load) Chats Limits
    "_",                          // (Start)(Save) Chats Limits
    "/manage/grouplimits",        // (Start)(Load) Groups Limits
    "_",                          // (Start)(Save) Groups Limits             - (30) -
    "/manage/comments",           // (Start)(Load) Comments Limits
    "_",                          // (Start)(Save) Comments Limits
    "/manage/expcontent",         // (Start)(Load) Express features
    "_",                          // (Start)(Save) Express features
    "/manage/trendingtags",       // (Start)(Load) Tags features             - (35) -
    "_",                          // (Start)(Save) Tags features
    "/manage/groupfeatures",      // (Start)(Load) Groups features
    "_",                          // (Start)(Save) Groups features
    "/manage/themes",             // (Start)(Load) Themes                                    
    "",                           // (Start)(Save) Languages                 - (40) -
    "/manage/languages",          // (Start)(Load) Languages	
    "/manage/users",              // (Start)(Load) Users	
    "_",                          // (Start)(Load) More Users	
    "_",                          // (Start)(Load) Filter Users	
    "_",                          // (Start)(Load) Edit Users	             - (45) -
    "/manage/groups",             // (Start)(Load) Groups
    "_",                          // (Start)(Load) More Groups
    "_",                          // (Start)(Load) Filter Groups
    "_",                          // (Start)(Load) Edit Groups
    "/manage/reports",            // (Start)(Load) Load reports              - (50) -
    "_",                          // (Start)(Load) More reports
    "/manage/backgrounds",        // (Start)(Load) Post backgrounds
    "_",                          // (Start)(Load) Activate backgrounds
    "_",                          // (Start)(Load) Reorder backgrounds
    "/manage/pagecategories",     // (Start)(Load) Page categories           - (55) -
    "_",                          // (Start)(Load) Delete categories      
    "/manage/sponsors",           // (Start)(Load) Sponsors     
    "/manage/extensions",         // (Start)(Load) Extensions     
    "/manage/updates",            // (Start)(Load) Updates     
    "/manage/patches",            // (Start)(Load) Patches                   - (60) -   
    "/manage/access",             // (Start)(Load) Admin                
    "_",                          // (Start)(Save) Admin                
    "/manage/info",               // (Start)(Load) Info pages                
    "_",                          // (Start)(Edit) Info pages                
    "_",                          // (Start)(Load) Info pages                
    "_",                          // (Start)(Save) Info pages                
    "_",                          // (Start)(Dele) Info pages                
    "/manage/polling",            // (Start)(Load) Long polling              - (68) -
    "_",                          // (Start)(Save) Long polling              
    "/manage/blogcats",           // (Start)(Load) Blog categories           - (70) -
    "_",                          // (Start)(Load) Delete categories          
    "/manage/bible",              // (Start)(Load) Bible          
    "_",                          // (Start)(Save) Bible          
    "/manage/fonts",              // (Start)(Load) Fonts          
    "_",                          // (Start)(Save) Fonts                     - (75) -
    "/manage/paypal",             // (Start)(Load) Paypal               
    "_",                          // (Start)(Save) Paypal               
    "/manage/boads",              // (Start)(Load) Boost               
    "_",                          // (Start)(Save) Boost 
    "/manage/videos",             // (Start)(Load) Video Settings            - (80) -
    "_",                          // (Start)(Save) Video Settings	
    "/manage/popular",            // (Start)(Load) Discover Settings	
    "_",                          // (Start)(Save) Discover Settings	
    "/manage/sockets",            // (Start)(Load) sockets settings
    "_",                          // (Start)(Save) sockets settings
    
	];
	
	if(_index_his[id] == "_") {} else {store(_index_his[id]);}	

}

function _masterNAV(id) {         /* Top navigation preloading management  */

    // Admin top navigation index
	var _index_nav = [
	"_",
    "NULL",                       // (Start)(Load) SMTP Settings
    "NULL",                       // (Start)(Load) SEO Settings
    "_",                          // (Start)(Save) SMTP Settings
    "_",                          // (Start)(Save) SEO Settings
    "NULL",                       // (Start)(Load) Notification Settings
    "_",                          // (Start)(Save) Notification Settings
    "NULL",                       // (Start)(Load) Blocking Settings
    "_",                          // (Start)(Save) Blocking Settings
    "NULL",                       // (Start)(Load) Privacy Settings
    "_",                          // (Start)(Save) Privacy Settings
    "NULL",                       // (Start)(load) User Search Settings
    "_",                          // (Start)(Save) User Search Settings
    "NULL",                       // (Start)(Load) Security Settings
    "_",                          // (Start)(Save) Security Settings
    "NULL",                       // (Start)(Load) Extra features
    "_",                          // (Start)(Save) Extra features
    "NULL",                       // (Start)(Load) Image Settings
    "_",                          // (Start)(Save) Image Settings
    "NULL",                       // (Start)(Load) Website Settings
    "_",                          // (Start)(Save) Website Settings
    "NULL",                       // (Start)(Load) Users Limit
    "_",                          // (Start)(Save) Users Limit
    "NULL",                       // (Start)(Load) Express Limit
    "_",                          // (Start)(Save) Express Limit
    "NULL",                       // (Start)(Load) Posts Limit
    "_",                          // (Start)(Save) Posts Limit
    "NULL",                       // (Start)(Load) Chats Limit
    "_",                          // (Start)(Save) Chats Limit
    "NULL",                       // (Start)(Load) Groups Limit
    "_",                          // (Start)(Save) Groups Limit
    "NULL",                       // (Start)(Load) Comments Limit
    "_",                          // (Start)(Save) Comments Limit
    "NULL",                       // (Start)(Load) Express features
    "_",                          // (Start)(Save) Express features
    "NULL",                       // (Start)(Load) Tags features
    "_",                          // (Start)(Save) Tags features
    "NULL",                       // (Start)(Load) Groups features
    "_",                          // (Start)(Save) Groups features
    "NULL",                       // (Start)(Load) Themes
    "_",                          // (Start)(Save) Languages
    "NULL",                       // (Start)(Load) Languages
    "NULL",                       // (Start)(Load) Users
    "NULL",                       // (Start)(Load) More Users
    "NULL",                       // (Start)(Load) Filter Users
    "NULL",                       // (Start)(Load) Edit Users
    "NULL",                       // (Start)(Load) Load Groups
    "NULL",                       // (Start)(Load) Filter Groups
    "NULL",                       // (Start)(Load) More Groups
    "NULL",                       // (Start)(Load) Edit Groups
    "NULL",                       // (Start)(Load) Load reports              - (50) -
    "NULL",                       // (Start)(Load) More reports
    "NULL",                       // (Start)(Load) Post backgrounds
    "NULL",                       // (Start)(Load) Activate backgrounds
    "NULL",                       // (Start)(Load) Reorder backgrounds
    "NULL",                       // (Start)(Load) Page categories
    "NULL",                       // (Start)(Load) Delete categories
    "NULL",                       // (Start)(Load) Sponsors
    "NULL",                       // (Start)(Load) Extensions
    "NULL",                       // (Start)(Load) Updates
    "NULL",                       // (Start)(Load) Patches
    "NULL",                       // (Start)(Load) Admin
    "NULL",                       // (Start)(Save) Admin
    "NULL",                       // (Start)(Load) Info pages
    "NULL",                       // (Start)(Edit) Info pages
    "NULL",                       // (Start)(Load) Info pages
    "NULL",                       // (Start)(Save) Info pages
    "NULL",                       // (Start)(Dele) Info pages
    "NULL",                       // (Start)(Load) Long polling
    "NULL",                       // (Start)(Save) Long polling
    "NULL",                       // (Start)(Load) Blog categories
    "NULL",                       // (Start)(Load) Delete categories
    "NULL",                       // (Start)(Load) Bible
    "NULL",                       // (Start)(Save) Bible
    "NULL",                       // (Start)(Load) Fonts
    "NULL",                       // (Start)(Save) Fonts
    "NULL",                       // (Start)(Load) Paypal
    "NULL",                       // (Start)(Save) Paypal
    "NULL",                       // (Start)(Load) Boost
    "NULL",                       // (Start)(Save) Boost
    "NULL",                       // (Start)(Load) Video
    "NULL",                       // (Start)(Save) Video
    "NULL",                       // (Start)(Load) Discover
    "NULL",                       // (Start)(Save) Discover
    "NULL",                       // (Start)(Load) Chat
    "NULL",                       // (Start)(Save) Chat

	];
	
	if(_index_nav[id] == "_") {} else {updateTopbar(_index_nav[id]);}	

}

function _masterSAV(id) {         /* Side navigation preloading management  */

    // Admin side navigation index
	var _index_sav = [
	"_",
    "smtp",                       // (Start)(Load) SMTP Settings 
    "seo",                        // (Start)(Load) SEO Settings 
    "_",                          // (Start)(Save) SMTP Settings 
    "_",                          // (Start)(Save) SEO Settings
    "notification",               // (Start)(Load) Notification Settings
    "_",                          // (Start)(Save) Notification Settings
    "blocking",                   // (Start)(Load) Blocking Settings
    "_",                          // (Start)(Save) Blocking Settings
    "privacy",                    // (Start)(Load) Privacy Settings
    "_",                          // (Start)(Save) Privacy Settings
    "search",                     // (Start)(Load) User Search Settings
    "_",                          // (Start)(Save) User Search Settings
    "security",                   // (Start)(Load) Security Settings
    "_",                          // (Start)(Save) Security Settings
    "extra",                      // (Start)(Load) Extra features
    "_",                          // (Start)(Save) Extra features
    "images",                     // (Start)(Load) Image Settings
    "_",                          // (Start)(Save) Image Settings
    "website",                    // (Start)(Load) Website Settings
    "_",                          // (Start)(Save) Website Settings
    "user",                       // (Start)(Load) Users Limit
    "_",                          // (Start)(Save) Users Limit
    "express",                    // (Start)(Load) Express Limit
    "_",                          // (Start)(Save) Express Limit
    "posts",                      // (Start)(Load) Posts Limit
    "_",                          // (Start)(Save) Posts Limit
    "chats",                      // (Start)(Load) Chats Limit
    "_",                          // (Start)(Save) Chats Limit
    "grouplimits",                // (Start)(Load) Groups Limit
    "_",                          // (Start)(Save) Groups Limit
    "comments",                   // (Start)(Load) Comments Limit
    "_",                          // (Start)(Save) Comments Limit
    "expcontent",                 // (Start)(Load) Express features
    "_",                          // (Start)(Save) Express features
    "trendingtags",               // (Start)(Load) Tags features
    "_",                          // (Start)(Save) Tags features
    "groupfeatures",              // (Start)(Load) Groups features
    "_",                          // (Start)(Save) Groups features
    "themes",                     // (Start)(Load) Themes
    "_",                          // (Start)(Save) Languages
    "languages",                  // (Start)(Load) Languages
    "users",                      // (Start)(Load) Users
    "_",                          // (Start)(Load) More Users
    "_",                          // (Start)(Load) Filter Users
    "_",                          // (Start)(Load) Edit Users
    "groups",                     // (Start)(Load) Groups
    "_",                          // (Start)(Load) More Groups
    "_",                          // (Start)(Load) Filter Groups
    "_",                          // (Start)(Load) Edit Groups
    "reports",                    // (Start)(Load) Load reports              - (50) -
    "_",                          // (Start)(Load) More reports
    "backgrounds",                // (Start)(Load) Post backgrounds
    "activateback",               // (Start)(Load) Activate backgrounds
    "reorderback",                // (Start)(Load) Reorder backgrounds
    "pagecats",                   // (Start)(Load) Page categories
    "delcats",                    // (Start)(Load) Delete categories
    "sponsors",                   // (Start)(Load) Sponsors
    "extensions",                 // (Start)(Load) Extensions
    "updates",                    // (Start)(Load) Updates
    "patches",                    // (Start)(Load) Patches
    "access",                     // (Start)(Load) Admin
    "_",                          // (Start)(Save) Admin
    "infopages",                  // (Start)(Load) Info pages
    "_",                          // (Start)(Edit) Info pages
    "_",                          // (Start)(Load) Info pages
    "_",                          // (Start)(Save) Info pages
    "_",                          // (Start)(Dele) Info pages
    "polling",                    // (Start)(Load) Long polling
    "_",                          // (Start)(Save) Long polling
    "blogcats",                   // (Start)(Load) Blog categories
    "_",                          // (Start)(Load) Delete categories
    "bibleview",                  // (Start)(Load) Bible
    "_",                          // (Start)(Save) Bible
    "fonts",                      // (Start)(Load) Fonts
    "_",                          // (Start)(Save) Fonts
    "paypal",                     // (Start)(Load) Paypal
    "_",                          // (Start)(Save) Paypal
    "boads",                      // (Start)(Load) Boost
    "_",                          // (Start)(Save) Boost
    "videos",                     // (Start)(Save) Video
    "_",                          // (Start)(Save) Video
    "popular",                    // (Start)(Load) Discover
    "_",                          // (Start)(Save) Discover
    "sockets",                    // (Start)(Load) sockets
    "_",                          // (Start)(Save) Chat

	];
	
	if(_index_sav[id] == "_") {} else {updateNavAdmin(_index_sav[id]);}	

}

function _masterPRE(id) {         /* HTML body preloading management  */

    // Admin preloader index
	var _index_pre = [
	"_",
    "content-body",               // (Start)(Load) SMTP Settings
    "content-body",               // (Start)(Load) SEO Settings
    "_",                          // (Start)(Save) SMTP Settings
    "_",                          // (Start)(Save) SEO Settings
    "content-body",               // (Start)(Load) Notification Settings
    "_",                          // (Start)(Save) Notification Settings
    "content-body",               // (Start)(Load) Blocking Settings
    "_",                          // (Start)(Save) Blocking Settings
    "content-body",               // (Start)(Load) Privacy Settings
    "_",                          // (Start)(Save) Privacy Settings
    "content-body",               // (Start)(load) User Search Settings
    "_",                          // (Start)(Save) User Search Settings
    "content-body",               // (Start)(Load) Security Settings
    "_",                          // (Start)(Save) Security Settings
    "content-body",               // (Start)(Load) Extra features
    "_",                          // (Start)(Save) Extra features
    "content-body",               // (Start)(Load) Image Settings
    "_",                          // (Start)(Save) Image Settings
    "content-body",               // (Start)(Load) Website Settings
    "_",                          // (Start)(Save) Website Settings
    "content-body",               // (Start)(Load) Users Limit
    "_",                          // (Start)(Save) Users Limit
    "content-body",               // (Start)(Load) Express Limit
    "_",                          // (Start)(Save) Express Limit
    "content-body",               // (Start)(Load) Posts Limit
    "_",                          // (Start)(Save) Posts Limit
    "content-body",               // (Start)(Load) Chats Limit
    "_",                          // (Start)(Save) Chats Limit
    "content-body",               // (Start)(Load) Groups Limit
    "_",                          // (Start)(Save) Groups Limit
    "content-body",               // (Start)(Load) Comments Limit
    "_",                          // (Start)(Save) Comments Limit
    "content-body",               // (Start)(Load) Express features
    "_",                          // (Start)(Save) Express features
    "content-body",               // (Start)(Load) Tags features
    "_",                          // (Start)(Save) Tags features
    "content-body",               // (Start)(Load) Groups features
    "_",                          // (Start)(Save) Groups features
    "content-body",               // (Start)(Load) Themes
    "content-body",               // (Start)(Save) Languages
    "content-body",               // (Start)(Load) Languages
    "content-body",               // (Start)(Load) Users
    "_",                          // (Start)(Load) More Users
    "threequarter",               // (Start)(Load) Filter Users
    "threequarter",               // (Start)(Load) Edit Users
    "content-body",               // (Start)(Load) Groups
    "_",                          // (Start)(Load) More Groups
    "threequarter",               // (Start)(Load) Filter Groups
    "threequarter",               // (Start)(Load) Edit Groups
    "content-body",               // (Start)(Load) Load reports              - (50) -
    "_",                           // (Start)(Load) More reports
    "content-body",               // (Start)(Load) Post backgrounds
    "content-body",               // (Start)(Load) Activate backgrounds
    "content-body",               // (Start)(Load) Reorder backgrounds
    "content-body",               // (Start)(Load) Page categories
    "content-body",               // (Start)(Load) Delete categories
    "content-body",               // (Start)(Load) Sponsors
    "content-body",               // (Start)(Load) Extensions
    "content-body",               // (Start)(Load) Updates
    "content-body",               // (Start)(Load) Patches
    "content-body",               // (Start)(Load) Admin
    "_",                          // (Start)(Save) Admin
    "content-body",               // (Start)(Load) Info pages
    "content-body",               // (Start)(Edit) Info pages
    "content-body",               // (Start)(Load) Info pages
    "_",                          // (Start)(Save) Info pages
    "_",                          // (Start)(Dele) Info pages
    "content-body",               // (Start)(Load) Long polling
    "_",                          // (Start)(Save) Long polling
    "content-body",               // (Start)(Load) Blog categories
    "content-body",               // (Start)(Load) Delete categories
    "content-body",               // (Start)(Load) Bible
    "_",                          // (Start)(Save) Bible
    "content-body",               // (Start)(Load) Fonts
    "_",                          // (Start)(Save) Fonts
    "content-body",               // (Start)(Load) Paypal
    "_",                          // (Start)(Save) Paypal
    "content-body",               // (Start)(Load) Boost
    "_",                          // (Start)(Save) Boost
    "content-body",               // (Start)(Load) Video
    "_",                          // (Start)(Save) Video
    "content-body",               // (Start)(Load) Discover
    "_",                          // (Start)(Save) Discover
    "content-body",               // (Start)(Load) sockets
    "_",                          // (Start)(Save) sockets

	];
	
	if(_index_pre[id] == "_") {} else {bodyLoader(_index_pre[id]);}	

}

function _masterBTN(id) {         /* Button preloading management  */

    // Admin preloader index
	var _index_btn = [
	"_",
    "-",                          // (Start)(Load) SMTP Settings
    "_",                          // (Start)(Load) SEO Settings
    "1",                          // (Start)(Save) SMTP Settings
    "1",                          // (Start)(Save) SEO Settings
    "_",                          // (Start)(Load) Notification Settings
    "1",                          // (Start)(Save) Notification Settings
    "_",                          // (Start)(Load) Blocking Settings
    "1",                          // (Start)(Save) Blocking Settings
    "_",                          // (Start)(Load) Privacy Settings
    "1",                          // (Start)(Save) Privacy Settings
    "_",                          // (Start)(Load) User Search Settings
    "1",                          // (Start)(Save) User Search Settings
    "_",                          // (Start)(load) Security Settings
    "1",                          // (Start)(Save) Security Settings
    "_",                          // (Start)(Load) Extra features
    "1",                          // (Start)(Save) Extra features
    "_",                          // (Start)(Load) Image Settings
    "1",                          // (Start)(Save) Image Settings
    "_",                          // (Start)(Load) Website Settings
    "1",                          // (Start)(Save) Website Settings
    "_",                          // (Start)(Load) Users Limit
    "1",                          // (Start)(Save) Users Limit
    "_",                          // (Start)(Load) Express Limit
    "1",                          // (Start)(Save) Express Limit
    "_",                          // (Start)(Load) Posts Limit
    "1",                          // (Start)(Save) Posts Limit
    "_",                          // (Start)(Load) Chats Limit
    "1",                          // (Start)(Save) Chats Limit
    "_",                          // (Start)(Load) Groups Limit
    "1",                          // (Start)(Save) Groups Limit
    "_",                          // (Start)(Load) Comments Limit
    "1",                          // (Start)(Save) Comments Limit
    "_",                          // (Start)(Load) Express features
    "1",                          // (Start)(Save) Express features
    "_",                          // (Start)(Load) Tags features
    "1",                          // (Start)(Save) Tags features
    "_",                          // (Start)(Load) Groups features
    "1",                          // (Start)(Save) Groups features
    "_",                          // (Start)(Load) Themes
    "_",                          // (Start)(Save) Languages
    "_",                          // (Start)(Load) Languages
    "_",                          // (Start)(Load) Users
    "_",                          // (Start)(Load) More Users
    "_",                          // (Start)(Load) Filter Users
    "_",                          // (Start)(Load) Edit Users
    "_",                          // (Start)(Load) Groups
    "_",                          // (Start)(Load) More Groups
    "_",                          // (Start)(Load) Filter Groups
    "_",                          // (Start)(Load) Edit Groups
    "_",                          // (Start)(Load) Load reports              - (50) -
    "_",                          // (Start)(Load) More reports
    "_",                          // (Start)(Load) Post backgrounds
    "_",                          // (Start)(Load) Activate backgrounds
    "_",                          // (Start)(Load) Reorder backgrounds
    "_",                          // (Start)(Load) Page categories
    "_",                          // (Start)(Load) Delete categories
    "_",                          // (Start)(Load) Sponsors
    "_",                          // (Start)(Load) Extensions
    "_",                          // (Start)(Load) Updates
    "_",                          // (Start)(Load) Patches
    "_",                          // (Start)(Load) Admin
    "1",                          // (Start)(Save) Admin
    "_",                          // (Start)(Load) Info pages
    "_",                          // (Start)(Edit) Info pages
    "_",                          // (Start)(Load) Info pages
    "1",                          // (Start)(Save) Info pages
    "1",                          // (Start)(Dele) Info pages
    "_",                          // (Start)(Load) Long polling
    "1",                          // (Start)(Save) Long polling
    "_",                          // (Start)(Load) Blog categories
    "_",                          // (Start)(Load) Delete categories
    "_",                          // (Start)(Load) Bible
    "1",                          // (Start)(Save) Bible
    "_",                          // (Start)(Load) Fonts
    "1",                          // (Start)(Save) Fonts
    "_",                          // (Start)(Load) Paypal
    "1",                          // (Start)(Save) Paypal
    "_",                          // (Start)(Load) Boost
    "1",                          // (Start)(Save) Boost
    "_",                          // (Start)(Load) Video
    "1",                          // (Start)(Save) Video
    "_",                          // (Start)(Load) Discover
    "1",                          // (Start)(Save) Discover
    "_",                          // (Start)(Load) sockets
    "1",                          // (Start)(Save) sockets

	];
	
	if(_index_btn[id] == "_") {} else {btnLoader(1,'');}	

}

function _masterVAL(id) {         /* AJAX call Params(Values) management  */
	
	// If AJAX ID requires values
	var _index_val = [
	"_START",
	"_",                          // (Start)(Load) SMTP Settings
	"_",                          // (Start)(Load) SEO Settings
	"1",                          // (Start)(Save) SMTP Settings
	"1",                          // (Start)(Save) SEO Settings
	"_",                          // (Start)(Load) Notification Settings
	"1",                          // (Start)(Save) Notification Settings
	"_",                          // (Start)(Load) Blocking Settings
	"1",                          // (Start)(Save) Blocking Settings
	"_",                          // (Start)(Load) Privacy Settings
	"1",                          // (Start)(Save) Privacy Settings
	"_",                          // (Start)(Load) User Search Settings
	"1",                          // (Start)(Save) User Search Settings
	"_",                          // (Start)(Load) Security Settings
	"1",                          // (Start)(Save) Security Settings
	"_",                          // (Start)(Load) Extra features
	"1",                          // (Start)(Save) Extra features
	"_",                          // (Start)(Load) Image Settings
	"1",                          // (Start)(Save) Image Settings
	"_",                          // (Start)(Load) Website Settings
	"1",                          // (Start)(Save) Website Settings
	"_",                          // (Start)(Load) Users Limit
	"1",                          // (Start)(Save) Users Limit
	"_",                          // (Start)(Load) Express Limit
	"1",                          // (Start)(Save) Express Limit
	"_",                          // (Start)(Load) Posts Limit
	"1",                          // (Start)(Save) Posts Limit
	"_",                          // (Start)(Load) Chats Limit
	"1",                          // (Start)(Save) Chats Limit
	"_",                          // (Start)(Load) Groups Limit
	"1",                          // (Start)(Save) Groups Limit
	"_",                          // (Start)(Load) Comments Limit
	"1",                          // (Start)(Save) Comments Limit
	"_",                          // (Start)(Load) Express features
	"1",                          // (Start)(Save) Express features
	"_",                          // (Start)(Load) Tags features
	"1",                          // (Start)(Save) Tags features
	"_",                          // (Start)(Load) Groups features
	"1",                          // (Start)(Save) Groups features
	"_",                          // (Start)(Load) Themes
	"1",                          // (Start)(Save) Languages
	"_",                          // (Start)(Load) Languages
	"_",                          // (Start)(Load) Users
	"_",                          // (Start)(Load) More Users
	"_",                          // (Start)(Load) Filter Users
	"1",                          // (Start)(Load) Edit Users
	"_",                          // (Start)(Load) Groups
	"_",                          // (Start)(Load) More Groups
	"_",                          // (Start)(Load) Filter Groups
	"1",                          // (Start)(Load) Edit Groups
	"_",                          // (Start)(Load) Load reports              - (50) -
	"_",                          // (Start)(Load) More reports
	"_",                          // (Start)(Load) Post backgrounds
	"1",                          // (Start)(Load) Activate backgrounds
	"1",                          // (Start)(Load) Reorder backgrounds
	"_",                          // (Start)(Load) Page categories
	"1",                          // (Start)(Load) Delete categories
	"_",                          // (Start)(Load) Sponsors
	"_",                          // (Start)(Load) Extensions
	"_",                          // (Start)(Load) Updates
	"_",                          // (Start)(Load) Patches
	"_",                          // (Start)(Load) Admin
	"1",                          // (Start)(Save) Admin
	"_",                          // (Start)(Load) Info pages
	"1",                          // (Start)(Edit) Info pages
	"_",                          // (Start)(Load) Info pages
	"1",                          // (Start)(Save) Info pages
	"1",                          // (Start)(Dele) Info pages
	"_",                          // (Start)(Load) Long polling
	"1",                          // (Start)(Save) Long polling
	"_",                          // (Start)(Load) Blog categories
	"1",                          // (Start)(Load) Delete categories
	"_",                          // (Start)(Load) Bible
	"1",                          // (Start)(Save) Bible
	"_",                          // (Start)(Load) Fonts
	"1",                          // (Start)(Save) Fonts
	"_",                          // (Start)(Load) Paypal
	"1",                          // (Start)(Save) Paypal
	"_",                          // (Start)(Load) Boost
	"1",                          // (Start)(Save) Boost
	"_",                          // (Start)(Load) Video
	"1",                          // (Start)(Save) Video
	"_",                          // (Start)(Load) Discover
    "1",                          // (Start)(Save) Discover
	"_",                          // (Start)(Load) sockets
    "1",                          // (Start)(Save) sockets
    
	];
	
	if(_index_val[id] == "_") {
		
		// Return default values
		return values = ["_START",0,0,0,0,0,0,0,0,0,0];
		
	} else {
		
		// Return parsed values
		return values = ["_START",$('#am-val-1').val(),$('#am-val-2').val(),$('#am-val-3').val(),$('#am-val-4').val(),$('#am-val-5').val(),$('#am-val-6').val(),$('#am-val-7').val(),$('#am-val-8').val(),$('#am-val-9').val(),$('#am-val-10').val()];

	} 
	
}

function _masterEXE(id,VAL,p,f,t,ff,req,b) { /* AJAX call management  */

    // Admin AJAX index
	var _index_cat = [
	"_",
    "maj_set",                    // (Start)(Load) SMTP Settings
    "maj_set",                    // (Start)(Load) SEO Settings
    "maj_set",                    // (Start)(Save) SMTP Settings
    "maj_set",                    // (Start)(Save) SEO Settings
    "reg_set",                    // (Start)(Load) Notification Settings
    "reg_set",                    // (Start)(Save) Notification Settings
    "reg_set",                    // (Start)(Load) Blocking Settings
    "reg_set",                    // (Start)(Save) Blocking Settings
    "reg_set",                    // (Start)(Load) Privacy Settings
    "reg_set",                    // (Start)(Save) Privacy Settings
    "reg_set",                    // (Start)(Load) User Search Settings
    "reg_set",                    // (Start)(Save) User Search Settings
    "reg_set",                    // (Start)(Load) Security Settings
    "reg_set",                    // (Start)(Save) Security Settings
    "fea_set",                    // (Start)(Load) Extra Features
    "fea_set",                    // (Start)(Save) Extra Features
    "maj_set",                    // (Start)(Load) Image Settings
    "maj_set",                    // (Start)(Save) Image Settings
    "maj_set",                    // (Start)(Load) Website Settings
    "maj_set",                    // (Start)(Save) Website Settings
    "con_set",                    // (Start)(Load) Users Limit
    "con_set",                    // (Start)(Save) Users Limit
    "con_set",                    // (Start)(Load) Express Limit
    "con_set",                    // (Start)(Save) Express Limit
    "con_set",                    // (Start)(Load) Posts Limit
    "con_set",                    // (Start)(Save) Posts Limit
    "con_set",                    // (Start)(Load) Chats Limit
    "con_set",                    // (Start)(Save) Chats Limit
    "con_set",                    // (Start)(Load) Groups Limit
    "con_set",                    // (Start)(Save) Groups Limit
    "con_set",                    // (Start)(Load) Comments Limit
    "con_set",                    // (Start)(Save) Comments Limit
    "fea_set",                    // (Start)(Load) Express features
    "fea_set",                    // (Start)(Save) Express features
    "fea_set",                    // (Start)(Load) Tags features
    "fea_set",                    // (Start)(Save) Tags features
    "fea_set",                    // (Start)(Load) Groups features
    "fea_set",                    // (Start)(Save) Groups features
    "maj_set",                    // (Start)(Load) Themes
    "maj_set",                    // (Start)(Save) Languages
    "maj_set",                    // (Start)(Load) Languages
    "man_set",                    // (Start)(Load) Users
    "man_set",                    // (Start)(Load) More Users
    "man_set",                    // (Start)(Load) Filter Users
    "man_set",                    // (Start)(Load) Edit Users
    "man_set",                    // (Start)(Load) Groups
    "man_set",                    // (Start)(Load) More Groups
    "man_set",                    // (Start)(Load) Filter Groups
    "man_set",                    // (Start)(Load) Edit Groups
    "man_set",                    // (Start)(Load) Load reports              - (50) -
    "man_set",                    // (Start)(Load) More reports
    "mna_set",                    // (Start)(Load) Post backgrounds
    "mna_set",                    // (Start)(Load) Activate backgrounds
    "mna_set",                    // (Start)(Load) Reorder backgrounds
    "mna_set",                    // (Start)(Load) Page categories
    "mna_set",                    // (Start)(Load) Delete categories
    "mna_set",                    // (Start)(Load) Sponsors
    "ext_set",                    // (Start)(Load) Extensions
    "cor_set",                    // (Start)(Load) Updates
    "cor_set",                    // (Start)(Load) Patches                   - (60) -
    "adm_set",                    // (Start)(Load) Admin
    "adm_set",                    // (Start)(Save) Admin
    "mna_set",                    // (Start)(Load) Info pages
    "mna_set",                    // (Start)(Edit) Info pages
    "mna_set",                    // (Start)(Load) Info pages                - (65) -
    "mna_set",                    // (Start)(Save) Info pages
    "mna_set",                    // (Start)(Dele) Info pages
    "maj_set",                    // (Start)(Load) Long polling
    "maj_set",                    // (Start)(Save) Long polling
    "mna_set",                    // (Start)(Load) Blog categories           - (70) -
    "mna_set",                    // (Start)(Load) Delete categories
    "plu_set",                    // (Start)(Load) Bible
    "plu_set",                    // (Start)(Save) Bible
    "plu_set",                    // (Start)(Load) Fonts
    "plu_set",                    // (Start)(Save) Fonts
    "spo_set",                    // (Start)(Load) Paypal
    "spo_set",                    // (Start)(Save) Paypal
    "spo_set",                    // (Start)(Load) Boost
    "spo_set",                    // (Start)(Save) Boost
    "maj_set",                    // (Start)(Load) Video
    "maj_set",                    // (Start)(Save) Video
    "con_set",                    // (Start)(Load) Discover
    "con_set",                    // (Start)(Save) Discover
    "maj_set",                    // (Start)(Load) sockets
    "maj_set",                    // (Start)(Save) sockets
	
	];
	
	if(_index_cat[id] == "_") {} else {
		ajaxProtocol(admin_controller_file,p,f,t,VAL[1],VAL[2],VAL[3],VAL[4],VAL[5],VAL[6],VAL[7],VAL[8],VAL[9],VAL[10],ff,_index_cat[id],req,b)
	}	

}

function _masterPRO(b) {
	if(b == 6) {lastPostLoads(1);}
}

function _admin(id,p,f,t,ff,req,b) {  /* Admin->controller  */
	
	// Add history 
	var HIS = _masterHIS(id);
	
	// Prepare Ajax values
	var VAL = _masterVAL(id);
	
	// Update top bar navigation
	var NAV = _masterNAV(id);

	// Update side navigation
	var SAV = _masterSAV(id);

	// Add Preloader
	var PRE = _masterPRE(id);
	
	// Add posts preloader
	var PRO = _masterPRO(b);
	
	// Add button loader
	var BTN = _masterBTN(id);
	
	// Final ajax call
	var EXE = _masterEXE(id,VAL,p,f,t,ff,req,b);	

}

function addedBackground(r,t) {                                              // After addition of new background

    // Remove preloader
    $("#add_background_trigger").html('<img src="'+$('#installation_select').val()+'/index.php?thumb=1&src=add-image.png&fol=bb&w=252&h=192" style="width:100%;cursor:pointer"></img>');

    // Show errors if any
    $('#addBackground_error').html(r);
	
	// If updated load new backgrounds
	if(t == 1) {
		_admin(52,0,0,'load','backgrounds',1,1);
	}
}

function addBackground() {                                                // Add new background
	
	// Add preloader
	$("#add_background_trigger").html('<img style="width:30%;margin:20px auto auto 20px;" class="settings-tab-loader brz-center nav-text-inverse-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img>');
	
	// Submit form
	document.getElementById("add_background").submit();
	
	return false;
}

function executeReport(id,t) {                                        // Delete or mark safe reported content
	
	// Perform AJAX request to mark action on selected report
	var performed = ajaxProtocol(execute_report_file,0,0,0,id,t,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function addCat(t) {                                                  // Add new category
	
	var val  = $("#add_cat_"+t).val();
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,t,val,0,0,0,0,0,0,0,0,0,17,0,1,1);
	
}

function saveAdds() {                                                 // Update adds settings
	
	// Installation value
	var gen = $('#installation_select').val() + update_adds_settings_file;
	
	// Set values
	var v1 = $('#sponsor1').val(),v2 = $('#sponsor2').val(), 
	v3 = $('#sponsor3').val(),v4 = $('#sponsor4').val(),v5 = $('#sponsor5').val(),
	v6 = $('#sponsor6').val(),v7 = $('#sponsor7').val(),v8 = $('#sponsor8').val(),
	v9 = $('#sponsor9').val(),v10 = $('#sponsor10').val(),v11 = $('#sponsor11').val(),
	v12 = $('#sponsor12').val();
    
    // Add loader animation to submit button
	btnLoader(1,'');
	
	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: gen,
        data: {v1: v1, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10,v11: v11,v12: v12},
		cache: false,
		success: function(data) {
            return handover(v9,v10,data,7);   
		}
	});		
}

function updateGroup(p) {                                                   // Update group for administration

	// Add loader animations to button
	btnLoader(1,''); 
    
	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: $('#installation_select').val() + admin_controller_file,
		data: {i:'man_set',ff:'savegroup',id: p, username: $('#uUSAA-vv1').val(), name: $('#uUSAA-vv2').val(), description: $('#uUSAA-vv3').val(), 
			email: $('#uUSAA-vv4').val(), location: $('#uUSAA-vv5').val(), website: $('#uUSAA-vv6').val(), type:'GROUP'},
		cache: false,
		success: function(data) {
			// Handover data to engine for further data management
            handover(0,0,data,7);   
		}
	});		
}

function updateUser(p) {                                                        // Update User for administration
 
	// Set values
	var v2 = $('#uUSAA-v2').val(), v3 = $('#uUSAA-v3').val(),
	v4 = $('#uUSAA-v4').val(),v5 = $('#uUSAA-v5').val(),v6 = $('#uUSAA-v6').val(),
	v7 = $('#uUSAA-v7').val(),v8 = $('#uUSAA-vv1').val(),v9 = $('#uUSAA-vv2').val(),
	v10 = $('#uUSAA-vv3').val(),v11 = $('#uUSAA-vv4').val(),v12 = $('#uUSAA-vv5').val(),
	v13 = $('#uUSAA-vv6').val(),v14 = $('#uUSAA-ss1').val(),v15 = $('#uUSAA-ss2').val()
	,v16 = $('#uUSAA-ss3').val(),v17 = $('#uUSAA-as1').val();
   
	btnLoader(1,''); 

	$.ajax({
		type: "POST",
	    url: $('#installation_select').val() + admin_controller_file,
		data: {i:'man_set',ff:'saveuser',v1: p, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10,v11: v11,v12: v12,v13: v13,v14: v14,v15: v15,v16: v16,v17: v17},
		cache: false,
		success: function(data) {
            return handover(v9,v10,data,7);   
		}
	});		
}

function searchAdmin(f,ff,b) {                                        // load search results (admin)
	
	// Search input box value
	var v = $('#w2rsdf').val();
	
	if(b == 6) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		// Add history 
		if(!isIE()) store('/manage/search/'+v);		
		
	    // Add animation to full body	
	    bodyLoader('content-body');
		
	}
	
	$("#w2rsdf").addClass("search-icon").removeClass("search-icon-loading");

	
	// Perform AJAX request to get search for administration
	var performed = ajaxProtocol(load_searchadmin_file,0,f,0,v,ff,0,0,0,0,0,0,0,0,0,0,1,b);
	
}

function loadRegChart(t) {                                            // Show admin dashboard in page

	// Add animation to left body
	$("#graphs_top").html('<div class="center padding-20"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" class="img-35"></img></div>');
	
	// Perform AJAX request to show admin dashboard
	var performed = ajaxProtocol(load_admin_content_file,0,0,t,t,t,0,0,0,0,0,0,0,"#graphs_top","graphs_top",0,0,13);
	
}

function toggleAdminCates(name) {                                         // Toggle navi on admin page

    if($('#'+name).prev().hasClass('ACTIVE-DC')) {
		return true;
	} else {
		$('.brz-admin-cat').slideUp().prev().removeClass('leftbar-active dark-font-only ACTIVE-DC').addClass('tin-light-font leftbar');
	
		$('#'+name).slideToggle();
	
		$('#'+name).prev().removeClass('tin-light-font leftbar b1').addClass('leftbar-active dark-font-only ACTIVE-DC');
	}
} 

function updateNavAdmin(id) {                                           // Update naigation on admin page
	
	// Update side navigation
	$('.brz-add-listner1').removeClass('dark-font-only b2').addClass('tin-light-font b1');
	$('#brz-add-listner1-'+id).addClass('dark-font-only b2').removeClass('tin-light-font b1');
	
}

function loadStats() {                                                // Load Main Administration page

    // Store history
	store('/manage/home')
	
	// Update top navigation activate->home
	updateTopbar('brz-class-stats');

	// Update side navigation
	updateNavAdmin('1');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load administration home || STATS
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,4,0,1,1);
	
}

function uploadExtension(text,t) {                                     // Upload website extensions
    
	// Check response type
	if(t==1) {
		
		$('#LOADER_EXTENTION').remove();

		if(text==1) {
			_admin(58,0,0,'load','extensions',1,1);
		} else {
			$("#AVA_EXTS").show().append(text);
		}
		
		return true;	
	}
	
	// Add preloader
	$('#AVA_EXTS').hide();
	$('#LOADER_EXTENTION').remove();	
	$('#AVA_EXTS').after('<div id="LOADER_EXTENTION" class="center padding-10"><img class="center img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>');

	// Submit etension for upload
	document.getElementById("EXT_FORM").submit();
	
	return false;

}

function uploadUpdate(text,t) {                                     // Upload website update
    
	// Check response type
	if(t==1) {
		
		$('#LOADER_EXTENTION').remove();

		if(text==1) {
			updateWebsite();
		} else {
			$("#UP_UPLOAD").show().append(text);
		}
		
		return true;	
	}
	
	// Add preloader
	$('#UP_UPLOAD').hide();
	$('#LOADER_EXTENTION').remove();	
	$('#UP_UPLOAD').after('<div id="LOADER_EXTENTION" class="center padding-10"><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>');

	// Submit etension for upload
	document.getElementById("EXT_FORM").submit();
	
	return false;

}

function downloadUpdates(get,version) {

	version  = typeof version !== 'undefined' ? version : 'NO-SET';
	
	if(get == 0) {
		bodyLoader('content-body');
		bdy = 1;
		var performed = ajaxProtocol(admin_updater_file,0,0,0,0,0,0,0,0,0,0,0,'#NODIVTOHIDE','#UP_CONSOLE',get,0,1,bdy);
	} else {

		window.setTimeout(function () {
			
			$('.preloading-updater:first').removeClass("b2 preloading-updater");
			
			bdy = 43;
			
			var performed = ajaxProtocol(admin_updater_file,version,0,0,0,0,0,0,0,0,0,0,'#NODIVTOHIDE','#UP_CONSOLE',get,0,1,bdy);
		
		}, 1000, get, version);
		
	}
	
}

function applyPatch(text,t) {                                     // Apply patch
    
	// Check response type
	if(t==1) {
		
		$('#LOADER_EXTENTION').remove();

		if(text==1) {
			_admin(60,0,0,'load','patches',1,1);
		} else {
			$("#UP_UPLOAD").show().append(text);
		}
		
		return true;	
	}
	
	// Add preloader
	$('#UP_UPLOAD').hide();
	$('#LOADER_EXTENTION').remove();	
	$('#UP_UPLOAD').after('<div id="LOADER_EXTENTION" class="center padding-20" ><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>');

	// Submit etension for upload
	document.getElementById("EXT_FORM").submit();
	
	return false;

}

function updateExtension(name,t) {                                     // Update website extensions
	
	// Check request type
	if(t==1) {
		el = $('#AVA_EXTS');
		var type = 'install';
	} else {
		el = $('#INS_EXTS');
		var type = 'uninstall';
	}
	
	// Add preloader
	el.html('<div class="center padding-20"><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>');
	
	// Submit request to install/uninstall extension
	$.ajax({
		type: "POST",
		url: $('#installation_select').val()+'/index.php?extend='+type+'&name='+name,
		data: '&no=2ha',
		cache: false,
		success: function(data) {
			
			el.html(data);	
		
		}
	});	
}
 
function clearTemp() {                                                  // Clear temp files such as logs

    // Add pre-loader
    $("#TEMP_FILE_CONTAINER").html('<div align="center" class="padding-10" ><img src="'+$("#installation_select").val()+'/themes/'+$("#theme_select").val()+'/img/icons/loader-small.svg" class="load-tin"></div>');
	
	// Perform AJAX request to mark action on selected report
	var performed = ajaxProtocol(execute_temp_file,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,98);
	
}