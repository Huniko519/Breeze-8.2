/*--------------------------------------------------------*/
/* Breeze Ultimate Social networking platform             */
/* MAIN JAVASCRIPT FILE - Standards theme                 */
/*                                                        */
/* Copyright (c) Gurkookers - All rights reserved.          */
/*--------------------------------------------------------*/
 
// Linked Files
var installation = $('#installation_select').val(),                                 // Installation URL
    post_love_file = '/require/requests/actions/love.php',                          // Like | Love post
    save_edit_file = '/require/requests/actions/save_edits.php',                    // Save edited posts or chats
    send_message_file = '/require/requests/actions/quick_message.php',              // Send message from profile page
    share_post_file = '/require/requests/actions/share_post.php',                   // Share post
    add_comment_file = '/require/requests/actions/comment.php',                     // Post comment
    add_message_file = '/require/requests/actions/message.php',                     // Post message
	load_tabs_file = '/require/requests/load/settings_tabs.php',                    // Load settings tabs
    load_tab_content_file = '/require/requests/load/settings_wizards.php',          // Load tab settings
    save_tabs_file = '/require/requests/update/tab_settings.php',                   // Save settings
    edit_member_file = '/require/requests/actions/edit_member.php',                 // Add or remove chat member 
    edit_chat_member_request = '/require/requests/actions/request_member.php',      // Add or remove chat member request
    report_submit_file = '/require/requests/actions/report.php',                    // Report content
    users_file = '/require/requests/update/users.php',                              // User relations updater
    settings_file = '/require/requests/update/settings.php',                        // User settings updater
    more_photos_file = '/require/requests/more/photos.php',                         // Show more gallery photos
    more_search_file = '/require/requests/more/search.php',                         // Show more search results
    more_results_file = '/require/requests/more/results.php',                       // Show more relatives (profile page)
    more_relatives_file = '/require/requests/more/relatives.php',                   // Show more relatives
    more_feeds_file = '/require/requests/more/feeds.php',                           // Show more feeds
    more_notifications_file = '/require/requests/more/notifications.php',           // Show more notifications
    load_settings_file = '/require/requests/load/settings.php',                     // Load settings full page
    load_comments_file = '/require/requests/load/comments.php',                     // Load post comments
    load_lovers_file = '/require/requests/load/lovers.php',                         // Load post lovers
    load_ads_file = '/require/requests/load/ads.php',                               // Load ads file
    load_post_file = '/require/requests/load/pages/post.php',                       // Load post related data
    load_timeline_file = '/require/requests/load/pages/timeline.php',               // Load profile Time line
    load_results_file = '/require/requests/load/pages/results.php',                 // Load relatives (profile page)
    load_relatives_file = '/require/requests/load/pages/relatives.php',             // Load relatives
    load_home_file = '/require/requests/load/pages/home.php',                       // Load user home
    load_blog_file = '/require/requests/load/blogs.php',                            // Load blogs home
    load_blogd_file = '/require/requests/load/blog_data.php',                       // Load blogs home
    load_trending_file = '/require/requests/load/pages/trending.php',               // Load trending posts
    load_discover_file = '/require/requests/load/pages/discover.php',               // Load discover posts
    load_gallery_file = '/require/requests/load/gallery.php',                       // Load profile gallery
	load_chats_file = '/require/requests/load/chats.php',                           // Load chats page
    load_edit_chat_file = '/require/requests/load/edit_chat.php',                   // Load edit chat page
    load_chat_form = '/require/requests/load/chat_window.php',                      // Load chat form
    load_about_file = '/require/requests/load/pages/about.php',                     // Load user's about section
    load_notifications_file = '/require/requests/load/notifications.php',           // Load notifications
    load_photos_file = '/require/requests/load/photos.php',                         // Load photos
    load_messages_file = '/require/requests/load/messages.php',                     // Load chat messages
    live_chat_file = '/require/requests/content/active_chat.php',                   // Live chat
    live_cont_file = '/require/requests/content/html.php',                          // Get basic conent file
    live_cont_file2 = '/require/requests/content/html_parsed.php',                  // Get parsed html content
    load_profile_file = '/require/requests/load/pages/profile.php',                 // Load profile
    load_group_file = '/require/requests/load/pages/group.php',                     // Load group processor
    load_block_user = '/require/requests/load/pages/block.php',                     // Load block processor
    load_page_file = '/require/requests/load/pages/page.php',                       // Load page processor
    load_post_options = '/require/requests/load/post_options.php',                  // Load post options
    misc_updates = '/require/requests/update/misc.php',                             // Misc operations
    create_page_file = '/main/pages/create_page.php',                               // Create new page
    load_search_file = '/require/requests/load/search.php',                         // Load search results
    load_searchadmin_file = '/require/requests/load/admin_search.php',              // Load search results for administration
    load_search_p_file = '/require/requests/load/search_profile.php',               // Load search results from profile
    load_search_m_file = '/require/requests/load/search_members.php',               // Load search results from chats
    load_edit_profile_file = '/require/requests/load/settings_page.php',            // Load edit profile
    delete_content_file = '/require/requests/delete/content.php' ,                  // Delete Post | Comment
    execute_report_file = '/require/requests/actions/execute_report.php',           // Accept report and delete content
    execute_temp_file = '/require/requests/actions/execute_temp.php',               // Clear temp data
    load_admin_content_file = '/require/requests/load/admin_content.php',           // Load content for administration
    admin_settings_file = '/require/requests/update/admin_settings.php',            // Administration settings updater
	update_web_settings_file = '/require/requests/update/admin_websettings.php',    // Update web settings administration
	update_adds_settings_file = '/require/requests/update/admin_addsettings.php',   // Update adds settings administration
    update_users_settings_file = '/require/requests/update/admin_user_settings.php',// Update user settings administration
    update_profile_admin_file = '/require/requests/update/admin_user_profile.php',  // Update user profile administration
    update_view_file = '/require/requests/update/views.php',                        // Update counts
    admin_updater_file = '/require/requests/admin/updater.php',                     // Update website handler
    admin_controller_file = '/require/requests/admin/controller.php';               // Administration settings controller

$(document).ready(function() {                                        // Document ready functions
	
	// Reload All Plugins
	refreshElements();

	// Manage window on scroll
	$(window).scroll(function() {
	    onScroll();
	});
	
	// Manage responsive window on resize
	$(window).resize(function() {
	    sizeElements();
	});

	// Manage on pop state
	$(window).on('popstate', function(ev) {                             // Display previous page
    
		// Fix pop state bug for old browsers and mobile browsers
		if (navigator.userAgent.match(/AppleWebKit/) && !navigator.userAgent.match(/Chrome/) && !window.history.ready && !ev.originalEvent.state) {
			return; 
		}
		// For apple users
		if (navigator.userAgent.match(/(iPad|iPhone|iPod|Android)/g) && !window.history.ready && !ev.originalEvent.state) {
			return; 
		}
		
		// Load from history
		location.reload();
		
	});
	
	// Universal search typing timer
	var typingTimer;
	
	// Time to wait for user typing response
    var typingInterval = 1000;
	

	// Live search on chat page
	$(document).on('keyup', '#add-members-search', function() { searchMembers(1); });
	$(document).on('keyup', '#remove-members-search', function() { searchMembers(0); });
	
	// Live search General 
	$(document).on('keyup', '#swsef89u3hj89sd', function(event) { 
		
		// Clear timer
		clearTimeout(typingTimer);
		
		// If some typing response from user found
		if ($('#swsef89u3hj89sd').val()) {
			
			// Add preloader
			$("#swsef89u3hj89sd").addClass("search-icon-loading").removeClass("search-icon");
            
			// Add timer to trigger for results
			typingTimer = setTimeout(function() {detachResults(); search(0,1,0);}, typingInterval);
			
		} else {
			
			// Clear preloader and timer if no letters typed
			$("#swsef89u3hj89sd").addClass("search-icon").removeClass("search-icon-loading");
		
		}
	});
	
	// Live search for admin and profile search for users (Combined)
	$(document).on('keyup', '#w2rsdf', function() {
	    
		clearTimeout(typingTimer);
	    
		// Admin search
	    if ($('#w2rsdf').val() && $('#w2rsdf').hasClass('admin-search')) {
			$(".swsef89u3hj89sd").addClass("search-icon-loading").removeClass("search-icon");
		    typingTimer = setTimeout(function() {searchAdmin(0,0,1);}, typingInterval);
		} else {
			
			if($('#w2rsdf').hasClass('admin-search')) $("#w2rsdf").addClass("search-icon").removeClass("search-icon-loading");
		
		}
		
		// User profile search
	    if ($('#w2rsdf').val() && !$('#w2rsdf').hasClass('admin-search')) {
		    typingTimer = setTimeout(function() {searchProfile();}, typingInterval);		
		}
		
	});
	
	// Close photo preview when click outside
	$("#gallery-modal").click(function(event) {
		if(event.target.id=="gallery-modal"){
			$("#GALLERY-BUTTON-CLOSE").click();
		}
	});
		
	// Manage Modals close
	$('body').on('click', 'div.brz-modal-close', function() {
	    $(this).parent().parent().parent().remove();
	});
	
	// Manage Intelligent dropdowns
	$('body').on('click', '.dropdown-click', function() {
	    
		// Convert to clickable dropdown once accessed
		$(this).removeClass('dropdown-click').addClass('dropdown-clicked');
		
		// Handle hovering effect on desktops
		if($(this).find('.dropdown-content').hasClass('show') && ($(window).width() > 993 || $(window).width() == 993)) {
			$(this).find('.dropdown-content').removeClass('show');
		}
		
	});
	
	// Notifications
	$(".notification").initialize( function(){
		$(".notification").not(this).remove();
    	$(this).fadeIn({queue: false, duration: 500}).animate({left: '10%'}, 300);
		play('sound-unsure');
	});	

	$.initialize( ".message-bos", function() {

		$(this).siblings('[id="'+ $(this).attr('id') +'"]').remove();

    });

	// Bible verse
	$(".bible-verse").initialize( function(){
		var widget = $(".bible-verse");
		
		$.ajax({
            url: "https://labs.bible.org/api/?passage=random&type=json&callback=myCallback", 
            crossDomain: true,
            dataType: 'jsonp',
            success: function(result) {
				//widget.find('.btn').attr();
				widget.find('.h4').html(result[0].bookname + " " + result[0].chapter + " " + result[0].verse);
				widget.find('.h12').removeClass('h12 b4').addClass('h6 b1 italic').html(result[0].text);
				//widget.find('a').remove();
				widget.removeClass('hidden').show();
            }
        });

	});	
	
	// Video embeds
	$(".video-embed").initialize( function(){
		
		// Get post id
		var video_data_id = $(this).attr('data-id');
		
		var video_data_rand = $(this).attr('data-rand');
		
		var data_icon = $(this).attr('data-icon');

		// Get video link(Embed)
		var video_data_link = $(this).attr('data-link');
		
		var video_data_error = $(this).attr('data-error');
		
		var video_data_src = $(this).attr('data-src');
		
		// For facebook videos, directly embed them
		if(video_data_src == "FACEBOOK.COM") {

			$('#video-embed-'+video_data_rand+'.'+video_data_id).after('<div class="brz-ratio-container"> <div class="brz-ratio-container-content"><iframe allowfullscreen="" height="100%" width="100%" class="rounded" src="https://www.facebook.com/plugins/video.php?href='+video_data_link+'"></iframe></div></div>');
			
		} else {

			// Create html modal
			var video_html = '<div id="video-embeded-'+video_data_rand+'" class="block-container '+video_data_id+' clear2 noflow video-embeded full rounded">' +
								'<div id="video-img-'+video_data_rand+'" class="relative '+video_data_id+' noflow vid-img left">' +
									'<a onclick="loadEmbed(\''+video_data_link+'\',1,\''+video_data_src+'\');return false;" href="" class="display-middle shadow img-40 border-bold circle filter-opacity-little theme-x-color">' +
										'<img class="img-18 display-middle '+data_icon+'-btn" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/buttons/'+data_icon+'.svg" >' +
									'</a>' +
								'</div>' +
								'<div class="padding-10 noflow">' +
									'<div onclick="loadEmbed(\''+video_data_link+'\',1,\''+video_data_src+'\');" id="video-title-'+video_data_rand+'" class="h11 '+video_data_id+' pointer b0 dark-font">' +
										'<img class="img-20" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg">' +
									'</div>' +
									'<div class="h4 text-left b1 padding-10-0">' +
										'<a href="" id="video-href-'+video_data_rand+'" target="_blank" class="tin-light-font '+video_data_id+'">'+video_data_src+'</a>' +
									'</div>' +
								'<div>' +
							'</div>';
						
			if($(this).hasClass('video-embed')) {
				
				// For uploaded videos
				if($(this).hasClass('video-uploaded')) { 
					// Nothing
				} else {
					$('#video-embed-'+video_data_rand+'.'+video_data_id).after(video_html);
			
					$.getJSON('https://noembed.com/embed',
						{format: 'json', url: video_data_link}, function (data) {
	
							if(data.error) {							
								$('#video-title-'+video_data_rand+'.'+video_data_id).html('<span class="">'+video_data_error+'</span>')
								$('#video-href-'+video_data_rand+'.'+video_data_id).attr('href','');
							} else {
								$('#video-img-'+video_data_rand+'.'+video_data_id).append('<img src="'+data.thumbnail_url.replace(/hqdefault/g, 'sddefault')+'" class="vid-img"></img>')
								$('#video-title-'+video_data_rand+'.'+video_data_id).html('<span class="">'+data.title+'</span>')
								$('#video-href-'+video_data_rand+'.'+video_data_id).attr('href',data.url);	
							}
						}
					);
			
				}
		
			}
		
		}
	});	
	
	// Modal closer
	$('#new-modal').on('click', function(e) {
  		if (e.target !== this)
		return;
    	loadModal(0);
	});

	$("#side-chat").on('click', '.chat-form-header', function(e){
		if(e.target == this){
		  	$(this).next().slideToggle();
		}
	 });

	
});

function play(s) {	                                                  // Audio Player (Background)
	var channel_max = 10;
	
	audiochannels = new Array();
	
	for (a=0;a<channel_max;a++) {
		audiochannels[a] = new Array();
		audiochannels[a]['channel'] = new Audio();
		audiochannels[a]['finished'] = -1;
	}
	
    for (a=0;a<audiochannels.length;a++) {
        thistime = new Date();
        if (audiochannels[a]['finished'] < thistime.getTime()) {
            audiochannels[a]['finished'] = thistime.getTime() + document.getElementById(s).duration*1000;
            audiochannels[a]['channel'].src = document.getElementById(s).src;
			audiochannels[a]['channel'].load();
			audiochannels[a]['channel'].play();
			break;
		}
	}
}

function readBible() {                                             // Open Bible
	loadModal(1);
	if($(window).width() > 600) {
	    $("#new-modal-content").empty().animate({
            width: '+=200px',height: '+=400px'
        }, 600,function() {
			$("#new-modal-content").html('<div style="width:100%;height:100%;" id="new-modal-inner"></div>');
			$("#new-modal-inner").fadeOut(0).html('<object style="width:100%;height:100%;" data="https://www.bible.com/bible/"/>');
			$("#new-modal-inner").fadeIn();
		});
	} else {
	    $("#new-modal-content").empty().animate({
			height: '+=200px'
        }, 500,function() {
			$("#new-modal-content").html('<div style="width:100%;height:100%;" id="new-modal-inner"></div>');
			$("#new-modal-inner").fadeOut(0).html('<object style="width:100%;height:100%;" data="https://www.bible.com/bible/"/>');
			$("#new-modal-inner").fadeIn();
		});
	}
}

function store(url) {                                                 // Dynamically update history
	
	// Set URL
	var add = $('#installation_select').val() + url;

	// Return if user has reloaded page
	if(add == window.location.href) {
		return true;
	}	
    
    if (isIE()) {
	   
	    // Workout for old browsers( < IE9)	
		locate(add);	
	
    } else {
        window.history.pushState({path:add}, '', add);
    }
}

function isIE() {                                                     // Detect Internet Explorer < 10 
    if ($('html').hasClass('ie')){
        return true;
    }else{
        return false;
    }
}

function copyToClipboard(ee,t) {                                      // Copy content to clipboard 
	var $var = $("<input>");
    $("body").append($var);
    $var.val(ee).select();
    document.execCommand("copy");
    $var.remove();
	alert(t);
}

$.fn.isOnScreen = function(){                                         // Check whether element is on screen
	
	var win = $(window);
	
	var viewport = {
		top : win.scrollTop(),
		left : win.scrollLeft()
	};
	
	if(!$(this).length) {return false;}
	
	viewport.right = viewport.left + win.width();
	viewport.bottom = viewport.top + win.height();
	
	var bounds = this.offset();
	
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
	
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
	
};

function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);
            
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}

function parseSVGs() {
	jQuery('img.svg').each(function(){
		
		if($(this).hasClass('replaced-svg')) {
			// IGNORE
		} else {
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data) {

                var $svg = jQuery(data).find('svg');

                if(typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }

                if(typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg').attr('data-src',imgURL);
                }

                $svg = $svg.removeAttr('xmlns:a');

                if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                }

                $img.replaceWith($svg);

            }, 'xml');
		}
    });
}

function angleIt(e,angle) {
    $({deg: 0}).animate({deg: angle}, {
        duration: 300,
        step: function(now) {
            $(e).css({
                transform: 'rotate(' + now + 'deg)'
            });
        }
    });
}

function postPin(id,revert,t) {
    text=$("#post-pin-"+id).text();	
    if(t==1) {
	    $("#post-pin-"+id).attr("onclick","postPin("+id+",'"+text+"',0);").text(revert);
	} else {
		$("#post-pin-"+id).attr("onclick","postPin("+id+",'"+text+"',1);").text(revert);
	}
	var performed = ajaxProtocol(misc_updates,id,0,t,0,0,0,0,0,0,0,0,0,0,"pin",0,0,0);
}
 
function loadPostOptions(id) {                                         // Load post options
   
    if($("#preloader-post-"+id).length == 0) {
        $("#drop-content-"+id).prepend('<img id="preloader-post-'+id+'" class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img>');
        var performed = ajaxProtocol(load_post_options,0,0,id,0,0,0,0,0,0,0,0,"#preloader-post-"+id,"#drop-content-"+id,0,0,0,34);
	}
   
}

function getSettings(t) {                                             // Load settings page
	
	// Update sidebar navigation activate->Edit profile	
	updateNavigation('e');
	
	// Add animation to left body	
	bodyLoader('content-body');
	
	updateMainTab('NULL');
	
	// Perform AJAX request to get requested settings page  
	var performed = ajaxProtocol(load_edit_profile_file,0,0,t,0,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
	// Add history 
	store('/user/edit');
	
}

function loadSettings(type) {                                         // Load settings type
	
	// Update settings nav
	setSettings(type);
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Remove older part
	if($(window).width() < 600) { 
	   $("#settings-nav").remove();
	}
	
	// Perform AJAX request to get requested settings page  
	var performed = ajaxProtocol(load_settings_file,0,0,type,0,0,0,0,0,0,0,0,0,0,0,0,1,25);
}

function loadBlockSettings() {                                         // Load settings type
	
	// Update settings nav
	setSettings('6');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Remove older part
	if($(window).width() < 600) { 
	   $("#settings-nav").remove();
	}
	
	// Perform AJAX request to get requested settings page  
	var performed = ajaxProtocol(load_block_user,'p',0,0,0,0,0,0,0,0,0,0,0,0,1,0,1,5);
}

function loadMoreBlockedUsers(from)
{
	lastPostLoads(1);
	var performed = ajaxProtocol(load_block_user,'p',from,0,0,0,0,0,0,0,0,0,0,0,3,0,1,6);
}

function newBlockUser()
{
	var id = $("#block_username").val();

	var performed = ajaxProtocol(load_block_user,id,0,0,0,0,0,0,0,0,0,0,0,0,2,0,1,5);
}

function profileLoadFollowings(p,f,t) {                               // Load people which are followed by user
    
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	
	updateProfileTab('4');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user followings	
	var performed = ajaxProtocol(load_results_file,p,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,5);

    // Update other stuff
	updateExpress();updateFriends();
}

function profileLoadFollowers(p,f,t) {                                // Load followers
 	
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	    
	updateProfileTab('3');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user followers	
	var performed = ajaxProtocol(load_results_file,p,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,5);

	// Update other stuff
	updateExpress();updateFriends();	
}

function profileLoadTimeline(p,t) {                                    // Load profile time line
   
    t  = typeof t !== 'undefined' ? t : '';
	
	// Update inPAGE navigation 
	updateProfileTab('1'+t);
	
	// Add animation to left body
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user posts	
	var performed = ajaxProtocol(load_timeline_file,p,0,0,0,0,0,0,0,0,0,0,0,0,t,0,1,5);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function profileLoadGallery(p) {                                      // Load profile gallery
    
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	     
	updateProfileTab('2');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user gallery	
	var performed = ajaxProtocol(load_gallery_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,5);

	// Update other stuff
	updateExpress();updateFriends();	
}

function profileLoadAbout(p) {                                        // Load profile about
    
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	     
	updateProfileTab('5');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user about section	
	var performed = ajaxProtocol(load_about_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,5);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function load_more_results(p,f,t) {                                   // Load More ( Followers | Followings ) profile page
	
	// Add animation after last result (Actually used for users)
	lastPostLoads(1);
	
	// Perform AJAX request to get more users (Followers || Followings on profile page)	
	var performed = ajaxProtocol(more_results_file,p,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,23);
	
}

function load_more_profile_photos(p,f) {                              // Load More gallery photos
	
	// Add animation after last result
    lastPostLoads(1);
	
	// Perform AJAX request to fetch more photos (Profile page)
	var performed = ajaxProtocol(more_photos_file,p,f,0,0,0,0,0,0,0,0,0,0,0,0,0,1,20);
	
}

function load_more_search(f,v) {                                      // Load More search results
	
	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to get more search results
	var performed = ajaxProtocol(more_search_file,0,f,0,v,0,0,0,0,0,0,0,0,0,0,0,1,6);
	
}

function load_more_feeds(f,p) {                                       // Load More home feeds
	
	// Add animation after last result HTML content
    lastPostLoads(1);
 	
	// Perform AJAX request to get more news feeds
	var performed = ajaxProtocol(more_feeds_file,p,f,0,0,0,0,0,0,0,0,0,0,0,0,0,1,6);
	
	// Update other stuff
	updateExpress();updateFriends();
}

function load_more_profile_feeds(f,p,t) {                             // Load More feeds on profile page
	
	t  = typeof t !== 'undefined' ? t : '';
	
	// Add animation after last result HTML content
    lastPostLoads(1);
	
	// Perform AJAX request to get more feeds (On profile page)
    var performed = ajaxProtocol(more_feeds_file,p,f,0,0,0,0,0,0,0,0,0,0,1,t,0,1,6);
	
}

function load_Lovers(id) {                                            // Load post lovers 
	
	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to get list of post lovers || LiKers
	var performed = ajaxProtocol(load_lovers_file,0,0,0,id,id,id,0,0,0,0,0,0,0,0,0,1,8);
	
}

function load_more_lovers(el,f,id) {                                  // Load More post lovers 
	
	// Add animation
	$("#"+el).find('div.preloader').append('<img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img>');
	
	// Perform AJAX request to fetch more post lovers
	var performed = ajaxProtocol(load_lovers_file,0,f,0,id,0,0,0,0,0,0,0,1,"#"+el,0,0,1,66);
	
}

function load_more_pages(el,f,id) {                                  // Load More pages
	
	// Add animation
	$("#"+el).find('div.preloader').append('<img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img>');
	
	// Perform AJAX request to fetch more pages
	var performed = ajaxProtocol(load_page_file,0,f,0,id,0,0,0,0,0,0,0,1,"#"+el,2,0,1,66);
	
}

function load_more_groups(el,f,id) {                                  // Load More groups 
	
	// Add animation
	$("#"+el).find('div.preloader').append('<img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img>');
	
	// Perform AJAX request to fetch more groups
	var performed = ajaxProtocol(load_group_file,0,f,0,id,0,0,0,0,0,0,0,1,"#"+el,17,0,1,66);
	
}

function load_more_relatives(f,t) {                                   // Load more followers or followings 

	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to fetch more relatives ( followers || followings )
	var performed = ajaxProtocol(load_relatives_file,0,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,23);
	
}

function ggj4wdf(v,t) {                                               // Notifications #1
	
	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to get notifications
	var performed = ajaxProtocol(more_notifications_file,0,v,0,0,0,0,0,0,0,0,0,0,0,t,0,1,6); 
	
}

function loadNotifications() {                                        // Load notifications from side bar full page mode
	
	// Add history 
	store('/user/notifications');

	// Update top navigation activate->NULL
	updateTopbar('NULL');
	
	// Update side navigation activate->Notifications
	updateNavigation('2');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load notifications
	var performed = ajaxProtocol(load_notifications_file,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,1,1);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function loadPhotos() {                                               // Load user gallery from side bar full page mode

	// Update side navigation
	updateNavigation('3');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load photos 
	var performed = ajaxProtocol(load_photos_file,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,1,1);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function loadBlogHome(t) {                                               // Load Blogs page
	
	// Add history 
	if(t==1) {
		var z = 1;
	} else {
		store('/blogs');
		var z = 0;
	};
	
	// Close Notifications widget if opened
	s23u89dssh();

	// Scroll to top
	scrollToTop();
	
	// Update top navigation activate->blogs
	updateTopbar('class-blog');

	// Update side navigation activate->blogs
	updateNavigation('1');

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load blogs
	var performed = ajaxProtocol(load_blog_file,0,z,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1);

	// Update other stuff
	updateExpress();updateFriends();
}

function trendingFeeds(f) {                                              // Load Discover feeds

	lastPostLoads(1);
	
	var performed = ajaxProtocol(load_discover_file,0,f,0,0,0,0,0,0,0,0,0,0,0,1,0,1,6);

	updateExpress();updateFriends();

} 

function loadDiscover() {                                                // Load Discover page
	
	store('/user/popular');
	
	// Close Notifications widget if opened
	s23u89dssh();

	// Update top navigation activate->blogs
	updateTopbar('class-v');

	// Update side navigation activate->blogs
	updateNavigation('populer');

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load blogs
	var performed = ajaxProtocol(load_discover_file,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1);

	// Update other stuff
	updateExpress();updateFriends();
}


function loadHome(t) {                                                 // Load Main page
	
	// Add history 
	if(t==1) {
		var z = 1;
	} else {
		store('/user/feeds');
		var z = 0;
	};
	
	// Close Notifications widget if opened
	s23u89dssh();

	// Update top navigation activate->Home
	updateTopbar('class-home');

	// Update side navigation activate->Home
	updateNavigation('1');

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load home
	var performed = ajaxProtocol(load_home_file,0,z,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1);

	// Update other stuff
	updateExpress();updateFriends();
}

function share(id,t) {                                                // Share post
    
	$(".loader-share-post").remove();
	
	if(t == 0) {
		$("#share-post-modal").fadeOut();
	} else {
		if(t == 1) {
			$("#share-post-submit").attr("onclick","share("+id+",3);");
			$("#share-post-modal").fadeIn();
		} else {
			$("#share-post-modal-content").append('<div align="center" class="loader-share-post"><img class="load-tin center" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>');
		    $("#share-post-submit").attr("onclick","");
			var performed = ajaxProtocol(share_post_file,0,0,0,id,"",0,0,0,0,0,0,0,"share",0,0,0,6);
		}
	}
}

function quickMessage(id,t) {                                         // Send a qucik message from profile page
	if(t == 0) {
		$("#chat-start-modal").fadeOut();

	} else {
		
		if(t == 2) {
			$("#chat-start-ctrls").show();
			$("#chat-start-modal-content").append('<div class="h5 b1 padding-10 light-font-only"><textarea id="quick-message-text" class="no-border padding-10" style="width:100%;min-height:200px;max-width:100%" placeholder="...."></textarea></div>');	
		} else {
	
			if(t == 3) {
				$("#chat-start-ctrls").show();
				$("#chat-start-modal-content").html('<div class="h5 b1 padding-10 light-font-only"><textarea id="quick-message-text" class="no-border padding-10" style="width:100%;min-height:200px;max-width:100%" placeholder="...."></textarea></div>');
				$("#chat-start-submit").attr("onclick","quickMessage("+id+",1);");
				$("#chat-start-modal").fadeIn();
			} else {
		
				if(t == 1) {	
					var message = $("#quick-message-text").val();	

            		// fade out text boxes and cancel button			
					$("#chat-start-modal-content").html('<div align="center"><img class="load-tin center" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>');
		    
					$("#chat-start-ctrls").hide();
			
					// Perform AJAX request to send message
	        		var performed = ajaxProtocol(send_message_file,0,0,0,id,message,0,0,0,0,0,0,0,0,0,0,0,15);
			
				}
			}
		}
	}
}  

function loadGallery(p_id,i_id,n) {                                   // Load image gallery preview
	
	// If gallery is visile
	if($("#gallery-modal").is(':visible')) {
		
		$('#gallery-modal-image-inner-img').find('img').addClass('hide');
		
		// Add required image on preview set
		if($("#GALLERY-INNER-IMAGE-FINAL-"+n).length) {
			$("#GALLERY-INNER-IMAGE-FINAL-"+n).removeClass('hide');
		} else {			
			$("#gallery-modal-image-inner-img").append('<img id="GALLERY-INNER-IMAGE-FINAL-'+n+'" style="height:auto;max-width:100%;" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg">');
		    $("#GALLERY-INNER-IMAGE-FINAL-"+n).css('max-height',$("#gallery-modal-view").height());
			loadImage(i_id,"#GALLERY-INNER-IMAGE-FINAL-"+n);
		}
	
	// Else open gallery and add post content
	} else {

        $("#POST_IMAGE_"+p_id).remove();
        
		$("#DROP_POST_FUCS"+p_id).css('bottom','');
		
        $("#gallery-post-view").html($("#post_view_"+p_id).html());		       
	    
		$("#GALLERY-BUTTON-CLOSE").attr('onclick','$(\'#gallery-post-view\').html(\'\');ajaxProtocol(load_post_file,0,0,1,'+p_id+',0,0,0,0,0,0,0,0,'+p_id+',3,0,0,31);$(\'#gallery-modal\').fadeOut()');
		
		$("#gallery-modal-image-inner-img").html('<img id="GALLERY-INNER-IMAGE-FINAL-'+n+'" style="height:auto;max-width:100%;" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg">');

		$("#gallery-modal").fadeIn(500,function(){$("#post_view_"+p_id).html('')});

	    $("#GALLERY-INNER-IMAGE-FINAL-"+n).css('max-height',$("#gallery-modal-view").height());
	    
		loadImage(i_id,"#GALLERY-INNER-IMAGE-FINAL-"+n);
	
	}
	
	var gallery = $("#GALLERY_LOAD_"+p_id);
	
	// Toggle next image button
	if(gallery.find('input').length > n) {
		
		var onlcick = '$(\'#post_gallery_image_'+(n+1)+'_'+p_id+'\').click();';
		
		$("#GALLERY-INNER-BUTTON-NEXT").fadeIn().attr('onclick',onlcick);	
		
	} else {
		$("#GALLERY-INNER-BUTTON-NEXT").fadeOut().attr('onclick','');
	}
	
	// Toggle prev image button
	if(n !== 1) {

	    var onlcick = '$(\'#post_gallery_image_'+(n-1)+'_'+p_id+'\').click();';
		
	    $("#GALLERY-INNER-BUTTON-PREV").fadeIn().attr('onclick',onlcick);
	
	}else {
		$("#GALLERY-INNER-BUTTON-PREV").fadeOut().attr('onclick','');
	}
	
	$('#gallery-post-view').niceScroll({autohidemode: false});
	
}
  
function doLiveChat(formid, userid, websockets) {                                    // New Live chatting
	
	// Get latest message ID
	var form_id = (formid) ? formid : $(".active-form-" + formid).val(),
	latest = $(".latest-message-"+form_id).val();

	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: $("#installation_select").val() + live_chat_file,
		data: "v1=" + form_id + "&v2=" + latest ,
		cache: false,
		success: function(data) {

			if($(".messages-container-"+form_id).length) {

				$(".messages-container-"+form_id).each(function() {
					$(this).append(data);
				});
				
				refreshElements();
				
				if(poll_messages == 1 && websockets == 0) {
					doLiveChat(formid, userid, websockets);
				}
				
			}
			
		}
	});
	
	if(poll_messages == 0 && websockets == 0 && $(".messages-container-"+form_id).length > 0) {
		setTimeout(doLiveChat, 5000, formid, userid, websockets);
	}
	
}

function matchToken(t,token) {                                         // Token match
  
	// Chat token
    if(t == 1) {
        return($("#_TOKEN_CHAT").val() == token) ? true : false;	
    }
	
}

function generateToken(t) {                                           // Tokenizer for AJAX requests
  
    var token = "";
  
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    // Generate token
    for (var i = 0; i < 1060; i++) token += possible.charAt(Math.floor(Math.random() * possible.length));

	// Chat token
    if(t == 1) {
        $("#_TOKEN_CHAT").val(token);
    }

	return token;

}

function scrollToTop() {                                              // Scroll page to top
   $('html, body').animate({scrollTop: 0},"slow");
}

function scrollFull(el,ty) {                                             // Scroll page to bottom
   
    if(ty == 1 && $(el).hasClass("AD_TOP")) { 
        $(el).animate({scrollTop: 0},"slow",function(){$(el).removeClass("AD_TOP");angleIt('#modal-toggle-scroll',360)});	
    } else { 
        $(el).animate({scrollTop: $(el)[0].scrollHeight},"slow",function(){if(ty == 1){$(el).addClass("AD_TOP");angleIt('#modal-toggle-scroll',180)}});
    }
   
}

function loadChats(f,v1,b) {                                          // Load all chats

	// Add history 
	store('/user/chats');
	
	// Close Notifications widget if opened
	s23u89dssh();
	
	if(b == 24) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		updateTopbar('class-chats');
		
		// Update side navigation 
		updateNavigation('6');

	    // Add animation to full body	
	    bodyLoader('threequarter');
		
	}
	
	// Check for filters
	l = ($("#RIGHT_CHAT_TYPE").length && b == 2) ? 0 :1;
	
	// Perform AJAX request to get chats
	var performed = ajaxProtocol(load_chats_file,0,f,0,v1,l,0,0,0,0,0,0,0,0,0,0,1,b);
	
}

function loadEditChat(id) {                                           // Load edit chat page
	
	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load chat info
	var performed = ajaxProtocol(load_edit_chat_file,0,0,0,id,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function loadChat(id) {                                               // Load live chatting window
	
	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load chat window
	var performed = ajaxProtocol(load_chat_form,0,0,0,id,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function moreMessages(f,form) {                                       // Fetch old chat messages
	
	$("#side-chat").find(".chat-form-"+form).find("#load-more-data").remove();

	// Add animation after last message
	lastPostLoads(1);
	
	// Perform AJAX request to get more messages
	var performed = ajaxProtocol(load_messages_file,0,f,0,form,0,0,0,0,0,0,0,0,form,0,0,1,12);
	
}

function loadTrending(f,ff,b) {                                       // Load trending posts
	
	// Add history 
	store('/user/trends');
	
	if(b == 26) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		// Close Notifications widget if opened
	    s23u89dssh();
		
		// Update sidebar navigation activate->Edit profile	
	    updateNavigation('TREN');
		
		// Update top navigation
	    updateTopbar('class-trending');

	    // Add animation to full body	
	    bodyLoader('threequarter');
		
	}

	// Perform AJAX request to get trending posts
	var performed = ajaxProtocol(load_trending_file,0,f,0,ff,0,0,0,0,0,0,0,0,0,0,0,1,b);
    
	// Update other stuff
	updateExpress();updateFriends();	
}

function loadBlogData(p,t,b) {                                        // Load blog data 	
	
	if(b == 1) {

		// Add animation to full body	
		bodyLoader('content-body');
		scrollToTop();
		updateNavigation('blogs');
		
	} 
	if(b == 5) {
	
		// Add animation to left body
		bodyLoader('threequarter');
	
	}
	
	if(b == 6) {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_blogd_file,p,0,0,0,0,0,0,0,0,0,0,0,0,t,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
}

function blogAricles(p,f,b) {                                           // Load blog articles

    // Add animation to left body
	if(b == 25) {
	    bodyLoader('threequarter');
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_blogd_file,p,f,0,0,0,0,0,0,0,0,0,0,0,2,0,1,b);
	
}

function loadBlog(id) {                                               // Load blog page	
	bodyLoader('content-body');
	scrollToTop();
	var performed = ajaxProtocol(load_blogd_file,id,0,0,0,0,0,0,0,0,0,0,0,0,3,0,1,1);
}

function loadPost(id) {                                               // Load post page	
 
	// Add history
	store('/view/'+id);
	
	// Scroll to top
	scrollToTop();
	
	// Update top navigation activate->NULL
	updateTopbar('NULL');
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load post
	var performed = ajaxProtocol(load_post_file,0,0,0,id,0,0,0,0,0,0,0,0,0,1,0,1,1);
	
}

function search(f,b,t) {                                              // load search 
	
	// Search input box value
	var v = $.trim($('.swsef89u3hj89sd').val());
	
	switch(t) {
		
		// Pages search
		case 7:
		
			// Add history
			if(b==1){store('/search/page/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_PAGE_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			// Get filters
			var fil1 = $("input[name='filter-page-type']:checked").val();var fil2 = $("input[name='filter-people-liv']:checked").val();var fil3 = $("input[name='filter-people-edu']:checked").val();
			
			break;
			
		// Videos search
		case 6:
		
			// Add history
			if(b==1){store('/search/video/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_VID_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			break;
			
		// Photos search
		case 4:
		
			// Add history
			if(b==1){store('/search/at/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_AT_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			break;
        
        // Hashtag search
        case 3:

			if(b==1){store('/search/tag/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_TAG_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			// Get filters
			var fil1 = $("input[name='filter-hashtag-date']:checked").val();var fil2 = $("input[name='filter-hashtag-type']:checked").val();var fil3 = $("input[name='filter-hashtag-scope']:checked").val();
			
			break;
			
        // Group search
        case 2:

			if(b==1){store('/search/group/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_GRP_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
	
	        // Get filters
			var fil1 = $("input[name='filter-group-type']:checked").val();var fil2 = $("input[name='filter-people-liv']:checked").val();var fil3 = $("input[name='filter-people-edu']:checked").val();
			
			break;
			
        // People search(General)
		case 1:
			
			if(b==1){store('/search/people/'+v)};
			
			// Check whether page is already fetched
	    	if($("#RIGHT_PEP_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			// Get filters
			var fil1 = $("input[name='filter-people-home']:checked").val();var fil2 = $("input[name='filter-people-liv']:checked").val();var fil3 = $("input[name='filter-people-prof']:checked").val();var fil4 = $("input[name='filter-people-edu']:checked").val();
			
			break;
			
		case 0 :
		
			if(b==1){store('/search/top/'+v)};
			
			// Check whether page is already fetched
	    	if($("#RIGHT_TOP_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			break;
	}
	
	// Add preloader	
	if(b==1){
	    bodyLoader('content-body');
	} else {
	    if(b==25) {
	        bodyLoader('threequarter');
	    } else {
	        lastPostLoads(1);
	   }
	};
	
	// Remove typing preloader
	$("#swsef89u3hj89sd").addClass("search-icon").removeClass("search-icon-loading");
	
	// Perform AJAX request to get search results
	var performed = ajaxProtocol(load_search_file,0,f,t,v,fil1,fil2,fil3,fetch,fil4,0,0,0,0,0,0,0,b);
	
    // Update other stuff
	updateExpress();updateFriends();
}

function detachResults() {                                            // Detach search results
    $("#RIGHT_TAG_SEARCHED").remove();  // Detach Tags
    $("#RIGHT_AT_SEARCHED").remove();   // Detach Photos
    $("#RIGHT_PEP_SEARCHED").remove();  // Detach users
    $("#RIGHT_GRP_SEARCHED").remove();  // Detach groups
    $("#RIGHT_TOP_SEARCHED").remove();  // Detach top results
    $("#RIGHT_VID_SEARCHED").remove();  // Detach top results
    $("#RIGHT_PAGE_SEARCHED").remove(); // Detach top results
}

function moveSearch(t) {                                              // Change search type
    
	// Use profile search input if main input is empty
	if($('#swsef89u3hj89sd').val().length) {var v = $('#swsef89u3hj89sd').val();} else {var v = $('#w2rsdf').val();}
	
	detachResults();
	
	switch(t) {
		
		// Pages search
		case 7:
		$('.swsef89u3hj89sd').val(v.replace(RegExp("#","g"), ""));
		search(0,1,7);
        break;
		
		// Videos search
		case 6:
		$('.swsef89u3hj89sd').val(v.replace(RegExp("#","g"), ""));
		search(0,1,6);
        break;
        
		// Profile search
		case 5:
		$('#w2rsdf').val(v.replace(RegExp("#","g"), ""));
		searchProfile();
        break;	
		
		// Photos search
		case 4:
		$('.swsef89u3hj89sd').val(v.replace(RegExp("#","g"), ""));
		search(0,1,4);
		break;
		
		// Hashtag search
		case 3:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,3);
		break;
		
		// Group search
		case 2:
		$('.swsef89u3hj89sd').val(v.replace(RegExp("#","g"), ""));
		search(0,1,2);
		break;
		
        // People search
		case 1:
		$('.swsef89u3hj89sd').val(v.replace(RegExp("#","g"), ""));
		search(0,1,1);
        break;
		
		// Top search
		default :
		$('.swsef89u3hj89sd').val(v.replace(RegExp("#","g"), ""));
		search(0,1,0);
		break;
	}
}

function searchProfile() {                                            // load search results (profile)

	// Search input box value
	var v = $('#w2rsdf').val();

	store('/search/me/'+v);

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to search profile
	var performed = ajaxProtocol(load_search_p_file,0,0,0,v,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function editMember(form_id,id,t) {                                   // Add or remove chat member

    // Get type add or remove
    if(t == 1) {
		var box = "#addable-members";
	} else {
		var box = "#removable-members";
	}
	
	// Add pre loader
	$(box).html('<div align="center"><img class="load-tin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg" style="margin:10%;"></img></div>')
	
	// Perform request
	var performed = ajaxProtocol(edit_member_file,0,0,t,form_id,id,0,0,0,0,0,0,0,box,0,0,0,13);
	
}

function editGroupMember(group_id,id,t,ss) {                           // Add or remove group member

	// Add pre loader
	var el = (ss) ? "#grp_sb_sl_sub" : "#new-modal-inner-content";
	
	$(el).html('<div align="center"><img class="load-tin center" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img></div>')
	
	// Work only for suggestions/sidebar add member wizards
	$('#messenger_dynamic_view_'+id+' ,#suggestions_dynamic_view_'+id).fadeOut(1000,
		function() {
			$('#messenger_dynamic_view_'+id+' ,suggestions_dynamic_view_'+id).remove();
			if($("#grp_sb_sl > div").length == 0) {$("#grp_sb_sl_ttl, #grp_sb_sl_cont").remove()};
		}
	);
	
	// Perform request
	var performed = ajaxProtocol(edit_member_file,0,0,t,group_id,id,0,0,0,0,0,0,0,el,1,0,0,13);
	
}

function inviteFriend(page_id,id) {                           // Send like invite

	// Add pre loader
	var el = "#grp_sb_sl_sub" ;
	
	$(el).html('<div align="center"><img class="center" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" class="load-tin"></img></div>')
	
	// Work only for suggestions/sidebar add member wizards
	$('#messenger_dynamic_view_'+id+' ,#suggestions_dynamic_view_'+id).fadeOut(1000,
		function() {
			$('#messenger_dynamic_view_'+id+' ,suggestions_dynamic_view_'+id).remove();
			if($("#grp_sb_sl > div").length == 0) {$("#grp_sb_sl_ttl, #grp_sb_sl_cont").remove()};
		}
	);
	
	// Perform request
	var performed = ajaxProtocol(edit_member_file,0,0,0,page_id,id,0,0,0,0,0,0,0,el,2,0,0,13);
	
}
 
function requestMember(owner,form_id,user_id,type) {                  // Request a chat member

    // Get type 
    if(type == 1) {
		var box = "#addable-members";	
	} else {
		var box = "#removable-members";		
	}
	
	// Add pre loader
	$(box).html('<div align="center"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg" style="margin:10%;" width="30" height="30"></img></div>')
	
	// Scroll to bottom
	if(/Mobi/.test(navigator.userAgent) == true) {
		$("html,body").animate({scrollTop: $(document).height()},2000);
	}
	
	// Send request
	var performed = ajaxProtocol(edit_chat_member_request,0,0,type,owner,form_id,user_id,0,0,0,0,0,0,box,0,0,0,13);
	
}

function searchMembers(t) {                                           // Search in chat members || friends

    // Get search type
	if(t == 1) {
		var val = $("#add-members-search").val(),
		    box = "#addable-members" ;	
		    box2 = "#removable-members" ;	
	} else {
		var val = $("#remove-members-search").val(),
			box = "#removable-members" ;
			box2 = "#addable-members" ;
	}
	
	// Add pre loader
	$(box).html('<div align="center"><img class="load-tin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img></div>')
	
	// Create a view space
	$(box2).html('');
	
	// Get results
	var performed = ajaxProtocol(load_search_m_file,0,0,t,val,$("#active-form").val(),0,0,0,0,0,0,0,box,0,0,0,13);
	
}

function loadRelatives(t) {                                           // Load followers or followings from side bar in full page mode 
	
	// Update side navigation whether followers or followings
	if(t == 1) {
		updateNavigation('5');
		
		// Add history 
	    store('/user/followings');
		
	} else {
		
		// Add history 
	    store('/user/followers');
	
		updateNavigation('4');
	}

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request
	var performed = ajaxProtocol(load_relatives_file,0,0,t,0,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function searchFollowings() {                                         // Load profile page 	

	var performed = ajaxProtocol(load_group_file,p,0,0,$("#new-modal-input").val(),0,0,0,0,0,0,0,0,"#new-modal-inner-content",3,0,1,13);
	
}

function pageFeeds(p,f,b,c) {                                          // Load group feeds

    var filter = (c !== undefined) ? c : 0;
	
    // Add animation to left body
	if(b == 5 || b == 1) {
    	
		if(c==1) {
			var gnav_up = 151;
		} else {
			if(c == 2) {
				var gnav_up = 152;
			} else {
				var gnav_up = 15;
			}
		}
		
		// Toggel G-Nav
		toggleGNav(gnav_up);
		
		updateProfileTab(filter);
		
		if(p == 0) {
        	// Update side navigation activate->Home
			updateNavigation('PA');
            store('/user/pages');
        }
		if(b == 1) {bodyLoader('content-body');} else{ bodyLoader('threequarter');}
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_page_file,p,f,filter,0,0,0,0,0,0,0,0,0,0,15,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
	
}

function groupFeeds(p,f,b) {                                          // Load group feeds

    // Add animation to left body
	if(b == 5 || b == 1) {
    	
		// Toggel G-Nav
		toggleGNav(15);
		
		updateProfileTab(1);
		
		if(p == 0) {
        	// Update side navigation activate->Home
			updateNavigation('GR');
            store('/user/groups');
        }
		if(b == 1) {bodyLoader('content-body');} else{ bodyLoader('threequarter');}
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,15,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
	
}

function groupLog(p,f,b) {                                            // Load group log

    // Add animation to left body
	if(b == 5) {
	
	    bodyLoader('threequarter');

		// Toggel G-Nav
		toggleGNav(10);
		
		updateProfileTab(4);
		
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,10,0,1,b);
	
}

function pageLog(p,f,b) {                                            // Load page log

    // Add animation to left body
	if(b == 5) {
	
	    bodyLoader('threequarter');
		
		updateProfileTab(4);
	    
		// Toggel G-Nav
		toggleGNav(10);
		
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_page_file,p,f,0,0,0,0,0,0,0,0,0,0,0,10,0,1,b);
	
}

function groupMembers(p,f,b) {                                        // Load group members 

	// Add pre loader animation
	if(b == 5) {
		
		bodyLoader('threequarter');
		
		// Toggel G-Nav
		toggleGNav(11);
		
		updateProfileTab(2);
		
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request to fetch more relatives ( followers || followings )
	var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,11,0,1,b);
	
}
	
function groupRequests(p,f,t,uid,el,ex) {                             // Load group member requests	

	// Load requests
	if(t == 1) {
		
		// Toggel G-Nav
		toggleGNav(6);
		
		updateProfileTab(5);

		// Add animation to left body
		if(uid == 5) {bodyLoader('threequarter');}
		
		// Perform AJAX request
		var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,6,0,1,uid);
		
		// Update other stuff
	    updateExpress();updateFriends();
	
	// Process requests
	} else {
		
		// Fade out request
		$("#"+el).fadeOut();
 	
	    // Perform AJAX request to allow || remove user follow request
	    var performed = ajaxProtocol(load_group_file,p,0,t,uid,ex,0,0,0,0,0,0,0,0,7,0,0,0);
	}
	
}

function savePage(p,t) {                                               // Save page settings                           
    
	// Add wizard loader
	contentLoader(1,1);
 	
	// Perform AJAX request to allow || remove user follow request
	var performed = ajaxProtocol(load_page_file,p,0,0,t,$("#settings-page-1").val(),$("#settings-page-2").val(),$("#settings-page-3").val(),$("#settings-page-4").val(),$("#settings-page-5").val(),0,0,0,1,9,0,0,30);

}

function saveGroup(p) {                                               // Save group settings                           
    
	// Add wizard loader
	contentLoader(1,1);
 	
	// Perform AJAX request to allow || remove user follow request
	var performed = ajaxProtocol(load_group_file,p,0,0,$("#settings-group-u").val(),$("#settings-group-1").val(),$("#settings-group-2").val(),$("#settings-group-3").val(),$("#settings-group-4").val(),$("#settings-group-5").val(),$("input[name='group_approval_radio']:checked").val(),$("input[name='group_post_radio']:checked").val(),$("input[name='group_privacy_radio']:checked").val(),1,9,0,0,30);

}

function editGroupMemberPermissions(p,uid,t) {                        // Edit group user permissions

    // Load edit user wizard
    if(t == 0) {	
		// Load user permissions
	    loadModal(1);
		var performed = ajaxProtocol(load_group_file,p,0,0,uid,0,0,0,0,0,0,0,0,0,12,0,1,32);
	} else {
		contentLoader(1,2);
		var performed = ajaxProtocol(load_group_file,p,0,0,uid,$("input[name='group_post_radio']:checked").val(),$("input[name='group_cover_radio']:checked").val(),$("input[name='group_actvity_radio']:checked").val(),$("input[name='group_admin_radio']:checked").val(),0,0,0,0,2,13,0,1,30);
	}
	
}

function profileLoadBlock(uid, t)
{
    // Load block user wizard
    if(t == 0) {	
		// Load user permissions
	    loadModal(1);
		var performed = ajaxProtocol(load_block_user,'p',0,0,uid,0,0,0,0,0,0,0,0,0,12,0,1,32);
	} else {
		contentLoader(1,2);
		var performed = ajaxProtocol(load_block_user,'p',0,0,uid,$("input[name='block_follow']:checked").val(),$("input[name='block_chat']:checked").val(),$("input[name='block_search']:checked").val(),$("input[name='block_profile']:checked").val(),$("input[name='block_groups']:checked").val(),$("input[name='block_page_invite']:checked").val(),0,0,2,13,0,1,30);
	}	
}

function unblockUser(uid)
{
	$("#block_user_"+uid).slideUp(function()
	{
		$(this).remove();
	});

	var performed = ajaxProtocol(load_block_user,'p',0,0,uid,0,0,0,0,0,0,0,0,0,9,0,1,32);
}

function groupNav() {
	
	// Clear sub nav if any
	$("#S_NAV_LEFT").empty();
	
	// Add group nav
	$("#S_LEFT_CONTENT").detach().prependTo("#S_NAV_LEFT");
	
}

function toggleGNav(id) {
	
	// If togglable
	if($("#brz-add-GNav-"+id).length) {
		$(".btn-nav").removeClass("btn-nav-active").find("img.preloader").hide();
		$("#brz-add-GNav-"+id).addClass("btn-nav-active").find("img.preloader").show();
		$("#brz-add-GNav-"+id).find("img.preloader").show();
	}
	
}
	
function loadGroup(p,t,b) {                                           // Load group data 	
	
	// Close side navigation if user is on mobile
	sidenav_close();
	
	if(b == 1) {
	    
		// For IE < 9
		if (isIE()) {
			store('/'+p);
		}
	
		scrollToTop();
		
		// Remove active state from side navigation
		updateNavigation(0);
	
		// Update top navigation activate->NULL
		updateTopbar('NULL');
	
		// Add animation to full body	
		bodyLoader('content-body');
		
	} 
	if(b == 5) {
	
		// Add animation to left body
		bodyLoader('threequarter');
	
	}
	
	// Toggel G-Nav
	toggleGNav(t);
	
	if(t == 8) {updateProfileTab(3);}

	// Perform AJAX request
	var performed = ajaxProtocol(load_group_file,p,0,0,0,0,0,0,0,0,0,0,0,0,t,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
}

function listAds(f) {                                                 // Load ads

    lastPostLoads(1);
	
    // Perform AJAX request to get trending posts
	var performed = ajaxProtocol(load_ads_file,0,f,'list_ads',0,0,0,0,0,0,0,0,0,0,0,0,1,6);
	
}

function adsData(type,f,b) {
	
	if(b == 5) {bodyLoader('threequarter');}
	
	var performed = ajaxProtocol(load_ads_file,0,f,type,0,0,0,0,0,0,0,0,0,0,0,0,1,b);
}

function loadAdsManager() {                                           // Load ads management
    
	// Add history 
	store('/user/ads');

	// Close Notifications widget if opened
	s23u89dssh();

	// Update sidebar navigation activate->Edit profile	
	updateNavigation('ads');

	// Update top navigation
	updateTopbar('class-ads');

	// Add animation to full body	
	bodyLoader('threequarter');	
	
	// Perform AJAX request to get trending posts
	var performed = ajaxProtocol(load_ads_file,0,0,'home',0,0,0,0,0,0,0,0,0,0,0,0,1,1);
    
	// Update other stuff
	updateExpress();updateFriends();
	
}

function loadPage(p,t,b,ex,f) {                                           // Load group data 	
	
    // Reset
    ex = typeof ex !== 'undefined' ? ex : 0;
	f = typeof f !== 'undefined' ? f : 0;

	// Close side navigation if user is on mobile
	sidenav_close();

	if(b == 1) {
	    
		// For IE < 9
		if (isIE()) {
			store('/pages/'+p);
		}
	
		scrollToTop();
		
		// Remove active state from side navigation
		updateNavigation(0);
	
		// Update top navigation activate->NULL
		updateTopbar('NULL');
	
		// Add animation to full body	
		bodyLoader('content-body');
		
	} 
	if(b == 5) {
	
		// Add animation to left body
		bodyLoader('threequarter');
	
	}
	if(b == 27) {
		lastPostLoads(1);
	}
	
	// Toggel G-Nav
	toggleGNav(t);
	
	// Perform AJAX request
	var performed = ajaxProtocol(load_page_file,p,f,ex,0,0,0,0,0,0,0,0,0,0,t,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
}

function createPage(t) {                                              // Create New page
	
	// Get page name
	var page_name = $("#page_name_"+t).val();
	
	// Get page category
	var page_category = (t == 1) ? $("#page_category").val() : $("#pag_cat_"+t).val() ;
	
	// Get page info : address
	var page_address = (t == 1) ? $("#page_address").val() : 0 ;

    // Add wizard loader
	contentLoader(1,t);

	// Load tab
	var performed = ajaxProtocol(create_page_file,0,0,t,page_name,page_category,page_address,0,0,0,0,0,0,t,0,0,0,30);	
	
}

function loadProfile(p) {                                             // Load profile page 	
	
	// Close side navigation if user is on mobile
	sidenav_close();
	
	// For IE < 9
	if (isIE()) {
		store('/'+p);
	}
	
	// Scroll to top
	scrollToTop();
	
	// Remove active state from side navigation
	updateNavigation(0);
	
	// Update top navigation activate->NULL
	updateTopbar('NULL');
	
	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request
	var performed = ajaxProtocol(load_profile_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
	// Update other stuff
	updateExpress();updateFriends();
}

function relBtn(el,h,o,rmv,add) {                                         // Follow | Request | undo follow | undo request | -> CSS styling 
	
	// Add requested on click event
	$("#"+el).attr("onclick",o).html(h).removeClass(rmv).addClass(add);

}


function follow(id,el,ha,hb) {                            // Follow user
	
	// Update button styles,on click events and titles
	relBtn(el,hb,"unfollow('"+id+"','"+el+"','"+hb+"','"+ha+"')","","btn-active");

	// Perform AJAX request to follow user
	var performed = ajaxProtocol(users_file,id,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function unfollow(id,el,ha,hb) {                          // Undo follow | request 

	// Update button styles,on click events and titles
	relBtn(el,hb,"follow('"+id+"','"+el+"','"+hb+"','"+ha+"')","btn-active","");
	
	// Perform AJAX request to UnFollow user
	var performed = ajaxProtocol(users_file,id,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);	
}

function request(id,el,ha,hb) {                           // Request user

	// Update button styles,on click events and titles
	relBtn(el,hb,"unrequest('"+id+"','"+el+"','"+hb+"','"+ha+"')","","");
	
	// Perform AJAX request to request user for following
	var performed = ajaxProtocol(users_file,id,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function unrequest(id,el,ha,hb) {                         // Undo request

	// Update button styles,on click events and titles
	relBtn(el,hb,"request('"+id+"','"+el+"','"+hb+"','"+ha+"')","","");

	// Perform AJAX request to UNDO request
	var performed = ajaxProtocol(users_file,id,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function requestGroup(id,el,ha,hb) {               

	relBtn(el,hb,"unrequestGroup('"+id+"','"+el+"','"+hb+"','"+ha+"')","","");

	var performed = ajaxProtocol(users_file,id,0,2,0,0,0,0,0,0,0,0,0,0,1,0,0,98);
	
}

function unrequestGroup(id,el,ha,hb) {           

	relBtn(el,hb,"requestGroup('"+id+"','"+el+"','"+hb+"','"+ha+"')","","");

	var performed = ajaxProtocol(users_file,id,0,3,0,0,0,0,0,0,0,0,0,0,1,0,0,98);
	
}

function joinGroup(id,el,ha,hb) {
	
	relBtn(el,hb,"unjoinGroup('"+id+"','"+el+"','"+hb+"','"+ha+"')","","btn-active");

	var performed = ajaxProtocol(users_file,id,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,98);	
	
}

function unjoinGroup(id,el,ha,hb) {                    

	relBtn(el,hb,"joinGroup('"+id+"','"+el+"','"+hb+"','"+ha+"')","btn-active","");
	
	var performed = ajaxProtocol(users_file,id,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,98);		
}

function likePage(id,el,ha,hb,perform,callback) {                      // Like/Unlike page
	
	var class1 = class2 = "";
	if(perform == 1) {class1 = "btn-active";} else {class2 = "btn-active";}
	
	// Update button styles,on click events and titles
	relBtn(el,hb,"likePage('"+id+"','"+el+"','"+hb+"','"+ha+"','"+callback+"','"+perform+"');",class2,class1);

	var performed = ajaxProtocol(users_file,id,0,perform,0,0,0,0,0,0,0,0,0,0,2,0,0,98);	
	
}

function likeComment(id,t) {                                         // Like/Unlike comment
	
	// Import strings
	importLang();
	
	if(t == 0) { 
		$("#comment-view-"+id).attr("onclick","likeComment("+id+",1)").text(lang[2]);
	} else {
        $("#comment-view-"+id).attr("onclick","likeComment("+id+",0)").text(lang[3]);
	}	

	var performed = ajaxProtocol(post_love_file,0,0,t,id,0,0,0,0,0,0,0,0,0,"comment",0,0,98);
	
}

function addReact(el,id,call,type,url) {                                      // Add reaction
	
	// Import strings
	importLang();
	
	// Reconvert
	$(el).find('img.active-img').attr('src',$("#installation_select").val() + '/themes/' + $("#theme_select").val()+ '/' + url);

	// Reconvert
	$(el).find('img.active-img').attr('src',$(el).find('img.active-img').attr('src').replace(/\-nc.svg$/, '.svg'))
	
	// Update like button styles
	$(el).attr("onclick","noLove('"+el+"','"+id+"','"+call+"',"+type+")").addClass("btn-liked").removeClass("btn-like");
	
	// Slecet value
	if($('#post_view_is_like_'+id+' .like-others-count').length === 0) {var count = lang[1]} else {var count = lang[0];}
	
	// Update like counter
	$('#post_view_is_like_'+id+' .like-count').html(count);

	// Perform AJAX request to love post
	var performed = ajaxProtocol(post_love_file,1,0,'1',id,call,0,0,0,0,0,0,0,0,type,0,0,98);
	
}

function doLove(el,id,call,type) {                                         // Love post
	
	// Import strings
	importLang();
	
	// Reconvert
	$(el).find('img.active-img').attr('src',$(el).find('img.active-img').attr('src').replace(/\-nc.svg$/, '.svg'))
	
	// Update like button styles
	$(el).attr("onclick","noLove('"+el+"','"+id+"','"+call+"',"+type+")").addClass("btn-liked").removeClass("btn-like");
	
	// Slecet value
	if($('#post_view_is_like_'+id+' .like-others-count').length === 0) {var count = lang[1]} else {var count = lang[0];}
	
	// Update like counter
	$('#post_view_is_like_'+id+' .like-count').html(count);

	// Perform AJAX request to love post
	var performed = ajaxProtocol(post_love_file,0,0,'1',id,call,0,0,0,0,0,0,0,0,type,0,0,98);
	
}

function noLove(el,id,call,type) {                                         // Undo love
	
	// Import strings
	importLang();

	$(el).attr("onclick","doLove('"+el+"','"+id+"','"+call+"',"+type+")").addClass("btn-like").removeClass("btn-liked").find('img.active-img').addClass('ssvg').removeClass('clr-svg');
	
	$(el).find('img.active-img').attr('src', $(el).find('img.active-img').attr('src').replace(/\.svg$/, '-nc.svg'));
  
	if($('#post_view_is_like_'+id+' .like-others-count').length) {var count = ''} else {var count = lang[10];}
	
	// Update like counter
	$('#post_view_is_like_'+id+' .like-count').html(count);
	
	// Perform AJAX request to remove love from post
	var performed = ajaxProtocol(post_love_file,0,0,0,id,call,0,0,0,0,0,0,0,0,type,0,0,98);
	
}

function loadComments(id) {                                           // Load comments 

	// fade in comments
    $("#comments-view-"+id).fadeIn();
    
	// Add preloader
	csLoader(id,1,1);
	
	// Load post comments
	var performed = ajaxProtocol(load_comments_file,0,0,0,id,0,0,0,0,0,0,0,id,0,0,0,0,9);	
	
}

// Simple comment reply
function reply(to,on) {
	var re = new RegExp(to,"g");
	$(on).val($(on).val().replace(re, "") + to);
	if($("#gallery-modal").isOnScreen() && $("#gallery-modal").is(":visible")) {
		$("#gallery-post-view").animate({scrollTop: $("#gallery-post-view").scrollHeight},500, function() {
			$(on).focus();
		});
	} else {
		var center = $(window).height()/2;
    	var top = $(on).offset().top ;
    	if (top > center) {
       	 	$('html, body').animate({scrollTop: top-center},"slow", function() {$(on).focus();});
    	}
	}
}

function ___________________________________________________loadComments(id) {                                           // Load comments 

	// fade in comments
    $("#comments-view-"+id).fadeIn(0);
    
	// Add preloader
	csLoader(id,1,1);
	
	// Load post comments
	var performed = ajaxProtocol(load_comments_file,0,0,0,id,0,0,0,0,0,0,0,id,0,0,0,0,9);	
	
}

function loadPreviousComments(id,f) {                                 // Load more comments 
    
	// Remove value container
	csLoader(id,1,1);
	
	// Perform AJAX request to load more comments
	var performed = ajaxProtocol(load_comments_file,0,f,0,id,2,0,0,0,0,0,0,id,2,0,0,0,9);
	
}

function submitMessage(e,id, el) {                                        // Submit chat message
	
	// Prevent form submit and URL redirect
	e.preventDefault();
	
	// Call add Comment function 
	addMessage(id, el);
	
	// Return false also prevent default
	return false;
	
}

function addMessage(id, el) {                                             // Add chat message
	
	// Comment text
	var text = $("#add-message-text-"+el+"-"+id).val();
	
	resetForm("chat-form-"+el+"-"+id);
	
	// Add loader animations to comments container
	chatLoaders(1, id);
	
	// Perform AJAX request
	var performed = ajaxProtocol(add_message_file,0,0,0,id,text,0,0,0,0,0,0,0,0,0,0,0,98);	
	
}

function exitChat(el,id,t){                                           // Exit chat form
    
	// Update button style
	$("#"+el).attr('onclick','').removeClass('brz-hover-red brz-btn brz-light-grey').html('<div id ="pre-loader-update-status" style="margin-top:1px;"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" width="22" height="22"></img></div>');
	
	// Perform AJAX request to exit chat
	var performed = ajaxProtocol(delete_content_file,0,0,t,id,0,0,0,0,0,0,0,0,0,0,0,0,98);
	
}

function submitComment(e,id) {                                        // Submit Comment 
	
	// Prevent form submit and URL redirect
	e.preventDefault();
	
	// Add preloader
	csLoader(id,2,1);
	
	// Disable form
	$("#form-add-comment-"+id).css('pointer-events','none');

	// Perform AJAX request
	var performed = ajaxProtocol(add_comment_file,0,0,0,id,1,$("#form-add-comment-text-"+id).val(),0,0,0,0,0,id,1,0,0,0,9);
	
	// Return false also prevent default
	return false;
	
}

function proccessRequest(p,el,t){                                     // Respond to friend request
	
	// Add loader animations in notifications widget
	notificationLoaders(1,1);
	
	// Remove last notification id holding element
	$('#7h4sd4').remove();
	
	// Fade out notification
	$("#"+el).fadeOut();
 	
	// Perform AJAX request to allow || remove user follow request
	var performed = ajaxProtocol(users_file,p,0,t,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
	
}

function notsEditMember(type,el,nid,formid,userid,t){                 // Respond to member request
	
	// Remove last notification id holding element
	$('#7h4sd4').remove();
	
	// Fade out notification
	$("#"+el).fadeOut();
 	
	// Perform AJAX request to delete notification
	var performed = ajaxProtocol(delete_content_file,0,0,4,nid,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
	if(t == 1) {
		// Add member
	    editMember(formid,userid,1);	
	} else {
		// Remove member
	    editMember(formid,userid,0);
	}
	
}

function report(id,t){                                                // Report form generator
    
	// Reset report form (Uncheck everything)
	$("#report-1").prop('checked',false);$("#report-2").prop('checked',false);$("#report-3").prop('checked',false);$("#report-4").prop('checked',false);                                   
	
	// Fade in report modal
	$('#report-modal').fadeIn();
	
	// Add post id | user id | comment id to on click event
	$('#confirm-report-submit').attr("onclick","submitReport("+id+","+t+")");
	
}

function submitReport(id,t){                                          // Report form submit
    
	// Get report comments marked by user (reporter)
	var v2 = valCheck("report-1") , v3 = valCheck("report-2") , v4 = valCheck("report-3") , v5 = valCheck("report-4") ;
    
	// Disable report submit button
	$('#confirm-report-submit').attr("onclick","");                                
	
	// Add loader animation in report modal
	reportLoaders(1);
	
	// Perform AJAX request to submit report
	var performed = ajaxProtocol(report_submit_file,0,0,t,id,v2,v3,v4,v5,0,0,0,0,"report-modal",0,0,0,10);
	
}

function valCheck(el){                                                // RETURN 1 if check box checked
    
	// Return true whether element is checked
	if($('input[id="'+el+'"]').is(':checked')){return 1;} else { return 0;}
	
}

function deleteContent(id,t){                                         // Delete form generator                                    
	
	// Fade in delete wizard || modal
	$('#confirm-delete').fadeIn();
	
	// Add post id | user id | comment id to on click event 
	$('#confirm-delete-submit').attr("onclick","deleteSubmit("+id+","+t+")");
	
}

function deleteChat(el,id,t){                                         // Delete form generator                                    
	
	// Fade in delete wizard || modal
	$('#confirm-delete').fadeIn();
	
	// Add post id | user id | comment id to on click event 
	$('#confirm-delete-submit').attr("onclick","exitChat("+el+","+id+","+t+");");
	
}

function editChatFormSubmit(id){                                      // Submit new chat edit
    
	// Get new text
	var v2 = $("#chat-edit-modal-name").val(),v3 = $("#chat-edit-modal-description").val();                                      
   
    $('#chat-submiter').remove();                                
	
	// Add loader animation in edit modal
	editLoaders2(1);
	
	// Perform AJAX request to save chat edit
	var performed = ajaxProtocol(save_edit_file,0,0,2,id,v2,v3,0,0,0,0,0,1,id,0,0,0,14);
	
}

function editPost(id){                                                // Edit post form generator                                    

	// Fade in edit post wizard || modal	
	$('#edit-modal').fadeIn();
	
	// Add post id to on click event 	
	$('#confirm-edit-submit').attr("onclick","editSubmit("+id+")");
	
	$('#edit-modal-content').html('<div class="center padding-20"><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img></div>');
	
	// Perform AJAX request	to get post content editor
	var performed = ajaxProtocol(load_post_file,0,0,0,id,0,0,0,0,0,0,0,0,0,2,0,0,11);
	
}

function editSubmit(id){                                              // Submit new text
    
	// Get new text
	var v2 = $("#edit-modal-text").val();                                      
   
    // Disable edit submit button
    $('#confirm-edit-submit').attr("onclick","");                                
	
	// Add loader animation in edit modal
	editLoaders(1);
	
	// Perform AJAX request to save post edit
	var performed = ajaxProtocol(save_edit_file,0,0,1,id,v2,0,0,0,0,0,0,id,"edit-modal",0,0,0,10);
	
}

function deleteSubmit(id,t){                                          // Delete form submit
    
	// Dynamically disable delete submit button
	$('#confirm-delete-submit').attr("onclick","");                                
	
	// Loader animations in delete wizard || modal
	deleteLoaders(1);
	
	if(t == 1) {
		
		// Remove deleted post HTML existence
		if($("#post_view_"+id).hasClass('shared')) {
		    $("#post_view_"+id).parent().fadeOut(500,function(){("#post_view_"+id).parent().remove();});
		} else {
	        $("#post_view_"+id).fadeOut(500,function(){$("#post_view_"+id).remove();});
		}
		
		$("#RIGHT_RECENT_LIKES").fadeOut(500,function(){$("#RIGHT_RECENT_LIKES").remove();});
		
	} 
	if(t == 2) {
		
		// Remove deleted comment HTML existence
		$("#comment-id-"+id).remove();
		
	}
	if(t == 3) {
		
		// Remove deleted comment HTML existence
		$("#message-id-"+id).remove();
		
	}
	
	// Perform AJAX request to delete post || comment
	var performed = ajaxProtocol(delete_content_file,0,0,t,id,0,0,0,0,0,0,0,0,"confirm-delete",0,0,0,10);
	
}

function aOverlow() {                                                 // Add body overflow (makes body scrollable)
	
	// Setting empty means removing CSS property which in future prevents jerks
	$('html,body').css('overflowY','');	
	
}

function hOverlow() {                                                 // Hide body overflow(allow scrolling for POP UP MODALS)

	// Hide overflow remove scrolls on body and fix page
	$('html,body').css('overflowY','hidden');

}
 
function resetForm(x) {                                               // Reset request FORM
    
	// Reset form X data
	document.getElementById(x).reset();

}

function upStatus() {                                                // Update status

	// Unwrap all emojis
	if($("#update-status-emoji-target").val()=="#update-status-form-text-viewable") {
		var html = $("#update-status-form-text-viewable").html();
	}else{
		$("#update-status-form-file").val("");
		var html = $("#update-status-form-btext-viewable").html();
	}
	
	$("#update-status-form-text-viewable-2").html(html);
	$("#update-status-form-text-viewable-2 img").unwrap();

	// Add space after each line
	$('#update-status-form-text-viewable-2').children('div').each(function () {
        $(this).after(" "); 
	});
	
	// Remove extra tags
	$("#update-status-form-text-viewable-2 span, br").remove();
	
	// Remove starting empty divs
	$('#update-status-form-text-viewable-2').children('div').each(function () {
        if($(this).is(':empty')) {
			$(this).remove();
		} else {
			return false;
		}
    });
	
	// Remove tailing empty lines
	$($('#update-status-form-text-viewable-2').children('div').get().reverse()).each(function () {
        if($(this).is(':empty')) {
			$(this).remove();
		} else {
			return false;
		}
    });
	
	// Regex to match all div
	var regex = /<br\s*[\/]?>/gi;
	
	$("#update-status-form-text-viewable-2").html( $("#update-status-form-text-viewable-2").html().replace(/<div>/ig,"<br>").replace(regex, "{{LINE-BREAK}}").replace("{{LINE-BREAK}}",''));

	// Get html data
	var get_text = $("#update-status-form-text-viewable-2").html().replace(/&nbsp;/g,'');

	// first create an element and add the string as its HTML
	var container = $('<div>').html(get_text);

	// then use .replaceWith(function()) to modify the HTML structure
	container.find('img').replaceWith(function() { return "{"+$(this).attr("data-emoji")+"}"; })

	// finally get the HTML back as string
	var strAlt = container.text();

	$("#update-status-form-text").val(strAlt);

	// Edit submit button
	$('#update-status-form-submit').attr('onclick','');
	
	// Add pre loaders to submit button
	$('#update-status-form-submit').html('<div id = "pre-loader-update-photo"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/search_loader.svg" class="load-tin"></img></div>');
	
	// Submit form
	document.getElementById("update-status-form-data").submit();

}

// Post status update
function statusUpdated(data) {                                          
	
	// Enable submit button
	$('#update-status-form-submit').attr('onclick','upStatus();');

	// Remove pre loaders
	$('#pre-loader-update-status').remove();

	// Reset inputs
	$('#update-status-form-text-viewable-2, #update-status-form-text-viewable, #update-status-form-btext-viewable').html('');

	// Add fetched data to body
	$('#all_posts').prepend(data);

	$('#post-file-1').removeClass('green-selected');
	
	document.getElementById("update-status-form-file").value = "";
	
	return true; 
	
}

function commentsLoaders(t,id) {                                      // Loader animations for comments

	// Select loaders
	var el = ".n23sd23-losdf43ad3"+id,el2 = "#n23sd23-losdf43ad-3"+id ,lod = "comments-bar-"+id ,lod2 = "#comments-bar-"+id ;
	
	// Remove animations
	$(el2).html('');		
	
	// If requested start animations 
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}

}

function chatLoaders(t, id) {                                             // Loader animations for chat window
	
	// Select loaders
	var el = ".add-message-loader-class",el2 = "#add-message-loader-"+id ,
	lod = "messages-loader-bar-"+id ,
	lod2 = "#messages-loader-bar-"+id ,
	lod3 = "messages-loader-bar-side-"+id ,
	lod4 = "#messages-loader-bar-side-"+id ;
	
	// Remove animations
	$(el2).html('');		
	$("#add-message-loader-side-"+id).html('');		
	
	// If requested start animations 
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    $("#add-message-loader-side-"+id).html('<div id="'+lod3+'" class="bar"></div>');
	    rotate(lod2,el);
	    rotate(lod4,el);
	}
	
}

function modalLoaders(t) {                                            // Loader animations modal
	
	// Select loaders	
	var el = ".confirmLoaders-class",el2 = "#confirmLoaders-id" ,lod = "confirmLoaders-bar" ,lod2 = "#confirmLoaders-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function deleteLoaders(t) {                                           // Loader animations delete
	
	// Select loaders	
	var el = ".confirmLoaders2-class",el2 = "#confirmLoaders2-id" ,lod = "confirmLoaders2-bar" ,lod2 = "#confirmLoaders2-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function editLoaders(t) {                                             // Loader animations edit post
	
	// Select loaders	
	var el = ".confirmLoaders3-class",el2 = "#confirmLoaders3-id" ,lod = "confirmLoaders3-bar" ,lod2 = "#confirmLoaders3-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
	
}

function editLoaders2(t) {                                            // Loader animations edit post ads version
	
	// Select loaders	
	var el = ".confirmLoaders5-class",el2 = "#confirmLoaders5-id" ,lod = "confirmLoaders5-bar" ,lod2 = "#confirmLoaders5-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
	
}

function reportLoaders(t) {                                           // Loader animations reports
	
	// Select loaders	
	var el = ".confirmLoaders4-class",el2 = "#confirmLoaders4-id" ,lod = "confirmLoaders4-bar" ,lod2 = "#confirmLoaders4-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function btnLoader(t,tt) {                                            // Loader animations within submit button
	
	// Add loaders if requested
	
	if(t == 1) {
		$('#update-status-form-submit').css('pointer-events','none').append('<img id="settings-content-loader" class="settings-content-loader load-tin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/search_loader.svg"></img>').find('span').hide();
	} else {
		$("#update-status-form-submit").css('pointer-events','auto').find('span').show();
		$("#settings-content-loader").remove();
	}
	
}

function rotate(load,el){                                             // Loader animations using bars
    
	// Animation set
	$(load).animate( { left: $(el).width() }, 1300, function() {

		// Update CSS left 
		$(load).css("left", -($(load).width()) + "px");
    
		// Again rotate 
		rotate(load,el);
    
	});
 }

function chatIcon() {                                                 // Update chat icon
	
	// Submit photo form	
	document.getElementById("chat-icon-update-form").submit();
	
	$('#chat_upload-loader').fadeIn();
}

function chatCover() {                                                // Update chat cover photo
	
	// Submit photo form	
	document.getElementById("chat-cover-update-form").submit();

	$('#chat_upload-loader').fadeIn();	
	
}
  
function chatIconUpdated(data) {                                      // Post Updated chat icon
	
	// Remove pre loaders
	$('#chat-icon').attr('src',$('#installation_select').val()+'/index.php?'+data);
	
	$('#chat_upload-loader').fadeOut();	
	
	// Reset submit form
	document.getElementById("chat-icon-update-form").reset();
	
	return true;
	
}

function chatCoverUpdated(data) {                                     // Post Updated chat cover
	
	// Display fetched message
	$('#chat-cover').attr('src',$('#installation_select').val()+'/index.php?'+data);
	
	$('#chat_upload-loader').fadeOut();
	
	// Reset submit form
	document.getElementById("chat-cover-update-form").reset();
	
	return true;
	
}

function chatIconReturn(data,type) {                                  // Post error chat icon
	
	if(type == 0) {
		// Remove pre loaders
	    $('#chat-icon').attr('src',$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/error.png');
	}
	
	// Reset submit form
	document.getElementById("chat-icon-update-form").reset();
	
	// Display fetched message
	$('#chat-icon-update-error').html(data);
	
	return true;
	
}

function chatCoverReturn(data,type) {                                 // Post error chat cover
	
	if(type == 0) {
		$('#chat-cover').css('background-image','url('+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/error.png)');
		$('#chat-cover').css('background-repeat','no-repeat');
		$('#chat-cover').css('background-position','center');	
	}

	// Reset submit form
	document.getElementById("chat-cover-update-form").reset();
	
	// Display fetched message
	$('#chat-cover-update-error').html(data);
	
	return true;
	
}

function showModal(data) {                                            // Show error
	$("#success-modal").after(data);
}

function loadModal(t) {                                            // Show error
	if(t==1) {
		$("#new-modal").html('<div id="new-modal-content" class="mi-modal white-color clear noflow"><div align="center" class="padding-20" ><div class="padding-20"><img src="'+$("#installation_select").val()+'/themes/'+$("#theme_select").val()+'/img/icons/loader-small.svg" class="load-mid"></div></div></div>').fadeIn(0);
	} else {
	    $("#new-modal").fadeOut(100);	
	}
}

function loadImageFull(el,img,id,t) {                                 // Load image dynamically
    
	// Add preloader for cover
	if(t == 1) {
	    $(el).removeAttr('src');
		var pp = '#btn-cover-chn';
	    $(el).parent().css("background","url('"+$('#installation_select').val()+"/themes/"+$('#theme_select').val()+"/img/icons/cc_loader.gif') no-repeat center center"); 
	} else {
	    var pp = '#btn-photo-chn';
	}

	var downloadingImage = new Image();
    downloadingImage.onload = function(){
        $(el).parent().css("background","none");   
        $(el).attr("src", this.src);     		    
		if(id !== 0) {
			profileLoadTimeline(id);
		}	
		smartLoader(0,pp);
    };
    downloadingImage.src = img;

}

function smartLoader(t,el) {                                          // User profile photos loader
    
	var ell = $(el);
	
	if(t==1) {
	    
		//  Remove camera	
	    ell.find('i.fa').remove();
		
		// Pervent browser image caching
		ell.find('img').remove();
		
		// Remove hovering effect
	    ell.parent().parent().removeClass('display-hover');
		
	    $('#btn-cover-repositioner').addClass('working');
		
		// Add preloader
	    ell.prepend('<img style="width:15px;height:15px;" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/search_loader.svg" ></img>')
	    
		// Disable button
		ell.css('pointer-events','none');
		
	} else {
     
	    // enable button
	    ell.css('pointer-events','auto');
		
		$('#btn-cover-repositioner').removeClass('working');
   
     	// Add camera	
	    ell.prepend('<i aria-hidden="true" class="fa fa-camera"></i> ');
		
		// Add hovering effect
	    ell.parent().parent().addClass('display-hover');
		
		// Remove preloader
	    ell.find('img').remove();	
	}
}

function csLoader(c_id,t,tt) {                                        // Comment loader
    
	// Add | remove preloader
	if(tt == 1) {
	    $("#comments-loader-"+t+"-"+c_id).html('<img class="load-tin margin-10" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img>');
	} else {
	    $("#comments-loader-"+t+"-"+c_id).html('');
	}
}

function loadImage(url,el) {                                          // Dynamic Image loader
    
    var downloadingImage = new Image();
    downloadingImage.onload = function(){
        $(el).parent().css("background","none");   
        $(el).attr("src", this.src);   
   };
   downloadingImage.src = url;
}

function sizeElements() {                                             // Refresh PLUGINS
  	
	$('.brz-dettachable').each(function() {
	    if($(this).hasClass('brz-detach-to-threequarter')) {
		    
			if($(window).width() < 600) {
			    $(this).detach().prependTo('#threequarter');
			} else {
			    if($(window).width() > 600) {
			        $(this).detach().prependTo('#right_content');
			    } 
			}    
		}
	});
	
	sidenav_close();

	var side_chat = $("#side-chat");
	var lastformid = side_chat.find("div.chat-form").first().find("div.data-item").data("id");
	var openedchats = side_chat.find('div.chat-form').length;

	var width = $(window).width();

	// Reached the max limit of chats, remove a older form
	if(width > 1515 && openedchats > 4) {
		removeChatForm(lastformid);
	} else {

		if(width < 1510 && openedchats > 3) {
			removeChatForm(lastformid);
		} else {
			if(width < 1250 && openedchats > 2) {
				removeChatForm(lastformid);
			}
		}

	}
	
	// side_chat.find('div.chat-form').last().html($(window).width());

}

function onScroll() {                                                 // Scroll functions

	if(/Mobi/.test(navigator.userAgent) == false) {
		$('.load-more-data').each(function() {
			if($(this).parent().hasClass("AUTO-LOAD") && $(this).isOnScreen()) {
				$(this).click();
				$(this).parent().prev().remove();
				$(this).parent().remove();
			}
		});

		// If window is large
		if($(window).width() > 1000 && $(window).height() > 500) {
		
		    // Cross browser scroll support
		    var vis_cross = (!$("#RIGHT_SCROLL_CRACK").prev().isOnScreen() && !$("#RIGHT_SCROLL_CRACK").is(":visible") && $("#RIGHT_SCROLL_CRACK").prev().length ) ? true : false;

			if($("#RIGHT_SCROLL_FINISH").isOnScreen() && $("#RIGHT_SCROLL_FINISH").is(":visible") && ( $("#RIGHT_SCROLL_CRACK").offset().top - $(window).scrollTop() < 62 || vis_cross)) {
	            $("#RIGHT_FIXED").width($("#RIGHT_LANGUAGE").width());
				$("#RIGHT_SCROLL_CRACK").nextAll('div.crackable').detach().appendTo("#RIGHT_FIXED");				
			} else {
		        $("#right_content").append($("#RIGHT_FIXED").html());
				$("#RIGHT_FIXED").html('');
			} 
		} else {
		    $("#right_content").append($("#RIGHT_FIXED").html());
			$("#RIGHT_FIXED").html('');
		} 
	}
}

function getBox() {                                                   // Return fixable fox
    
}

function addCrack() {                                                 // Add fixing point to right body
    
	$("#RIGHT_SCROLL_CRACK").remove();
	
	// Prevent height because of the images
	var height_margin = 10;

	if($("#GROUPS_YOU_I").length) height_margin = height_margin  + 75;
	
    var boxes = $($('.crackable').get().reverse());
    var crack = '<div class="" id="RIGHT_SCROLL_CRACK"></div>';

	var box0 = (boxes.eq(0) == null) ? null : boxes.eq(0);
	var box1 = (boxes.eq(1) == null) ? null : boxes.eq(1);
	var box2 = (boxes.eq(2) == null) ? null : boxes.eq(2);
	var boxh0 = (boxes.eq(0) == null) ? 0 : boxes.eq(0).height();
	var boxh1 = (boxes.eq(1) == null) ? 0 : boxes.eq(1).height();
	var boxh2 = (boxes.eq(2) == null) ? 0 : boxes.eq(2).height();
	
	var total_content_height = boxh0 + boxh1 + boxh2 + height_margin;
	var secondary_content_height = boxh0 + boxh1 + height_margin;
	var available_height = $(window).height() - 100;

	// No fixable box exists
    if(box0 == null) {
		// box00.before(crack);
	} else {
		
		// Only language changer box is fixable
		if(box1 == null || secondary_content_height > available_height) {
			box0.before(crack);
		} else {
			if(box2 == null || total_content_height > available_height) {
			    box1.before(crack);
		    } else {
				box2.before(crack);
			}
		}
		
	}
}

function loadSideChat(formid) {
	var side_chat = $("#side-chat");
	if(!manageForms(formid)) {	
		
		side_chat.append('<div id="side-chat-form-'+formid+'" class="inline chat-form chat-form-'+formid+'" > <div class="white-color shadow border rounded center padding-10 text-center margin-10"><img class="load-mid img-35" src="'+$("#installation_select").val()+'/themes/'+$("#theme_select").val()+'/img/icons/loader-small.svg"></div><div class="data-item" data-id="'+formid+'"></div></div>');
		
		var performed = ajaxProtocol(load_chats_file,0,0,0,formid,0,0,0,0,0,0,0,0,"#side-chat-form-"+formid,'side-chat',0,0,45);

		createChatForm(formid, 'asd', 'new form');
	}
}

function manageForms(formid) {

	var returnto = false;
	var side_chat = $("#side-chat");
	var openedchats = side_chat.find('div.chat-form').length;
	var lastformid = side_chat.find("div.chat-form").first().find("div.data-item").data("id");
	var width = $(window).width();

	side_chat.find("div.chat-form").each(function() {
		if($(this).find("div.data-item").data("id") == formid) {
			var formalready = $(this);
			formalready.detach().appendTo("#side-chat");
			returnto = true;
		}
	});

	if(width > 1525 && openedchats > 3) {
		removeChatForm(lastformid);
	} else {

		if(width < 1515 && openedchats > 2) {
			removeChatForm(lastformid);
		} else {
			if(width < 1250 && openedchats > 1) {
				removeChatForm(lastformid);
			} else {
				if(width < 900 && openedchats) {
					removeChatForm(lastformid);
				}
			}
		}

	}

	return returnto;

}

function createChatForm(formid, formicon, formname) {

	var side_chat = $("#side-chat");
	var openedchats = side_chat.find('div.chat-form').length;
	
}

function removeChatForm(formid) {

	var side_chat = $("#side-chat");

	// Remove HTML data
	userid = side_chat.find(".chat-form-"+formid).data('userid');
	side_chat.find(".chat-form-"+formid).remove();

	content = ($(".chat-form-"+formid).length > 0) ? "1" : "NA";

	if(websocketserver) {

		initSocketServer();

		var register_message = {
			formid: formid,
			userid: userid,
			type: "END",
			content: content
		};

		socket.send(JSON.stringify(register_message));

	}

}

function addExpress() {                                               // Add express show
    if($(window).height() > 550 && $("#NO_EXPRESS").length == 0) {
	    $.ajax({
		    type: "POST",
		    url: $('#installation_select').val() + '/require/requests/load/express_content.php',
		    data: "p=EXPRESS_SHOW",
		    cache: false,
		    success: function(data) {
				$('#body-start').after(data);		
		    }
	    }); 
	}
}

function updateExpress() {                                            // Update express activity

	if($("#right_express").isOnScreen() && $("#right_express").is(":visible")) {
	
		$.ajax({
			type: "POST",
			url: $('#installation_select').val() + "/require/requests/content/active_express.php",
			cache: false,
			success: function(data) {
            	$("#EXPRESS_ACTIVITY").html(data);
			}
   	 	});
		
	}
}

function updateFriends() {                                            // Update active friends
	var installation = $('#installation_select').val();
	if($("#right_express").isOnScreen() && $("#right_express").is(":visible")) {
	
		$.ajax({
			type: "POST",
			url: installation + "/require/requests/content/active_friends.php",
			cache: false,
			success: function(data) {
            	$("#EXPRESS_FRIENDS").html(data);
			}
   	 	});
		
	}
}

function getContent(get,saved,addto) {                                 // Get content

    // Select requested content
    switch(get) {	
	
		// Get Emoji selecter
		case 'emoji-selecter':
			if(!$(addto).hasClass('addedContent')){
				var get_content = ajaxProtocol(live_cont_file,0,0,get,0,0,0,0,0,0,0,0,0,addto,0,0,0,13);
			}
			break;
			
		// Get Recommended articles
		case 'rec-articles':
			if(!$(addto).hasClass('addedContent')){
				var get_content = ajaxProtocol(live_cont_file2,0,0,get,0,0,0,0,0,0,0,0,0,addto,0,0,0,13);
			}
			break;	

		// Get Manage pages
		case 'rec-pages':
			if(!$(addto).hasClass('addedContent')){
				var get_content = ajaxProtocol(live_cont_file2,0,0,get,0,0,0,0,0,0,0,0,0,addto,0,0,0,13);
			}
			break;	

		// Get Admin updates
		case 'list-updates':
			if(!$(addto).hasClass('addedContent')){
				var get_content = ajaxProtocol(load_admin_content_file,0,0,get,0,0,0,0,0,0,0,0,0,addto,get,0,1,13);
			}
			break;			
			
	}
}

function getPageInfo(page_id) {                               
	$(".pages-with-infos").hide();
	$("#info-page-vss-"+page_id).show();
}

function addEmoji(element,emoji_name) {                                // Add emoji in contentable div
	
	var el = $(element).val();
	
	var html = $(el).html().replace(/\s+/g, " ");
	
	var add_emoji = "{"+emoji_name+"}";
	
	$(el).html(html+" "+add_emoji);
	
	$(el).html(function (i,text) {
        $.each(f,function (i,v) {	
		    var emoji_is = ""+re[i]+"";
		    text = text.replace(re[i],"<span><img class=\"noselect\" data-emoji=\""+emoji_name+"\" src=\""+$('#installation_select').val()+"/themes/"+$('#theme_select').val()+"/img/emojis/"+ r[i] +".png\" width=\""+18+"\"></span> ");
        });
        return text;
    });
	
	$(element+' span:empty,br').remove();
	checkContentbb();
}

function refreshElements() {                                          // Refresh PLUGINS
    
	// Move elements to their right positions
	sizeElements();

	// Reload Time ago PLUGIN on all DIVs and SPANs
	jQuery("div.timeago").timeago();
    jQuery("span.timeago").timeago();

	// Reload nice select PLUGIN
	$('select').niceSelect();
	
	// Reload nice scroll
    if($(window).width() > 600) {$('.nicescrollit').niceScroll();}
	
	// Import strings 
	importLang();

	// Max visible chars for posts
	var max = 300;
	
	// Auto compress large text pharas
	$('.brz-c-text').each(function() {
	
		// Select unparsed hidden text
		var content = $(this).prev('.hide').html(); 
		var linebreaks = $(this).prev('.hide br').length;
        
		// If content exceeds
		if(content.length > max || linebreaks > 2) {
			var c = content.substr(0, max);	
			var h = content.substr(max, content.length - max);
     		var html = c + '<span class="h4 b2 tin-light-font block right brz-text-triggers pointer">... ' + lang[4] + '</span>';
			$(this).html(html);	
			$(this).prev('.hide').html(html);
		 
        // Else insert parsed text		 
		} else {
		    var span = $(this).next('.hide').html();
            $(this).prev('.hide').html(span);			
            $(this).html(span);			
            $(this).removeClass('brz-c-text');		
		}
	});
	
	// Toggle large text to show/hide
	$('body').on('click', 'span.brz-text-triggers', function() {
		if($(this).hasClass('brz-viewed')) {
			var viewable = $(this).parent();	
		    viewable.html(viewable.prev('.hide').html());	
		} else {   
			var view = $(this).parent();	
		    view.html(view.next('.hide').html()+'<span class="h4 b2 block right tin-light-font pointer brz-viewed brz-text-triggers">' + lang[5] + '</span>');        
		}
		return false;
	});
	
	// Text trigger handler
	$('body').on('click', 'button.load-more-data', function() {
		$(this).remove();
	});
	
	// Parse SVG icons(If any)
	parseSVGs();
	
	if ($('.usser-post').length > 1) {
		$('.suser-post').not(':last').remove()
	}
	
	$('[id] .user-post').each(function () {
       if($('[id="' + this.id + '"]').length > 1) $('[id="' + this.id + '"]').not('.sponsored, .pinned').remove();
    });
	
}

function t56zs3() {                                                   // Notifications #2
	
	// This functions are UGLIFIED because of too much user interactions
	
	// Installation URL
	var installation = $('#installation_select').val(),	
	
	// Last processed notification
	v = $('#7h4sd4').val(); 
	
	// Remove identifier
	$('#7h4sd4').remove();
	
	// Add loading animations to notifications widget
	notificationLoaders(0,1);	  
	rotate('#not-bar',".n23sd23-losdf43ad");	
    
	// Abort other request assigned to VAR content
	var content;
	
	if(content && content.readyState != 4) {
		content.abort();
	}
	
	// Assign a new request to VAR content
    content = $.ajax({
		
		// POST || GET
		type: "POST",
	    
		// Request host
		url: installation + "/require/requests/more/widget_notifications.php",
		
		// Whether build up content in memory
		cache: false, 
		
		// Add last processed notification ID
		data: "f=" + v, 
		
		// On request completion
		success: function(data) {
			
			// If no more notifications available 
			if(data == 0) {
				
				// Remove "load more" element
				$("#3423534w").hide();
				
				// Add "No more notifications element"
				$("#fdg54sdr").show();

			} else{
				
				// Append newly processed notifications
				$("#43if89").append(data);

				// Refresh PLUGINS
			    refreshElements();
				
			}
		
		    // Stop loading animations
		    notificationLoaders(0,0);			
			
		}
	});		
}	

function mobSearch(t,parse) {                                            // Update Header navigation
    parse = typeof parse !== 'undefined' ? parse : 0;
	if(t == 1) {
		$("#mb_search").addClass('brz-navmainicon-active acc').siblings().removeClass('brz-navmainicon-active');
		if(parse==0) {
			loadTrending(0,0,1);
		}
		$("#mb_search_container").empty().parent().show().prev().hide();
		$("#345234123").detach().appendTo("#mb_search_container");
		$(".swsef89u3hj89sd").removeClass("brz-serch-main brz-animate-input").addClass("brz-serch-main-mb");
	} else {
		if($("#mb_search").hasClass("acc")) {
			$("#mb_search").removeClass('brz-navmainicon-active acc');
			$("#345234123_cnt").empty();
			$("#mb_search_container").parent().hide().prev().show();
			$("#345234123").detach().appendTo("#345234123_cnt");
			$(".swsef89u3hj89sd").addClass("brz-serch-main brz-animate-input").removeClass("brz-serch-main-mb");
		}
	}
}

function updateTopbar(x) {                                            // Update Header navigation
	
	// Close side nav if opened
	sidenav_close();
	
	s23u89dssh();
	
	// Remove all active classes
	$(".header-icon-container, .header-fonts").each(function() {
	    $(this).find('img.activeable').removeClass('no-opacity header-icon-active header-mob-icon-active-text-only').find('.carret-down').hide();		
	});
	
	// Add active class to requested element
	$("."+x).addClass('no-opacity header-icon-active header-mob-icon-active-text-only');

}

function s23u89dssh() {                                               // Notifications #3

	// This functions are UGLIFIED because of too much user interactions
	
	// Notifications container
	x = $("#NOTIFICATIONS_DESK");
	
	// if Container is visible
	if(x.hasClass('brz-fade332asdugyu')){ 
	 
	    // Add overflow
		aOverlow();
		
	    // Remove this identifier
		x.removeClass("brz-fade332asdugyu");
        
		// Fade out notifications container
		$("#NOTIFICATIONS_DESK_CARRET").hide();
		x.hide();
		
    }		
}

function sNX(f) {                                                     // Show notifications in page

	// Add animation to left body
	bodyLoader('threequarter');
	
	// Perform AJAX request to show requested notifications type
	var performed = ajaxProtocol(load_notifications_file,0,0,0,0,0,0,0,0,0,0,0,0,0,f,0,1,25);
	
}

function checkNewNots(){                                                // Notifications #5
    if($("#dfbhne78342jsdf").find('span').length || $("#NOTIFICATIONS_DESK").hasClass("inactive")) {
		$("#NOTIFICATIONS_DESK").removeClass("inactive");
		refreshNots();
	}
}

function refreshNots(){                                                 // Notifications #4
	if($(window).width() < 601) {
		
		// Load notifications on full page
		sidenav_close();loadNotifications();
		
	} else {
		
		// Installation URL
		var installation = $('#installation_select').val();
		var theme = $('#theme_select').val();
	
		// Select container
		x = $("#NOTIFICATIONS_DESK");
	
		$("#43if89").removeClass("no-opacity").html('<div align="center" class="margin-10"><img class="load-mid" src="'+installation+'/themes/'+theme+'/img/icons/loader-small.svg"></div>')
		
		// Abort other request assigned to VAR content
		var content;
	   	if(content && content.readyState != 4) {
		   	content.abort();
	   	}
		
        content = $.ajax({
		    type: "POST",
			url: installation + "/require/requests/content/check_notifications.php",
		    cache: false,
			success: function(data) {			
				if(data == 0) {} else {
					// Add fetched data
					$("#43if89").addClass("no-opacity").html(data);
					refreshElements();
				}
			}
		});
    }		
}

function submitSearch(e) {                                            // Search value
	
	// Prevent default behaviour
	e.preventDefault();
	
	// Trigger search on submit for mobile users
	if(/Mobi/.test(navigator.userAgent) == true) {
		detachResults();
	
		// Perform search
	    search(0,1,0)
	};

	// Prevent default behaviour	
	return false;

}

function manualLoaders(el,t) {                                        // Add manual loaders to elements 
	
	// Select and empty requested element
	$("#"+el).empty();
	
	// Add animations to requested element if requested
	if(t == 1) {
		$("#"+el).html('<div id="pre-loader-main" name="pre-loader-main" class="padding-20 center"><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg" style="margin-left:40%;margin-top:20%;" width="50" height="50"></img><div>');
	}
	
}

function updateProfileTab(el) {                                       // Update profile navigation
    
	// Remove active classes
	$(".activable-tab, .activable-tab-white").removeClass('active-down b2');
	
	// Add active class to requested element
	$("#profile_view_tab_"+el).addClass('active-down b2');
	
}

function updateMainTab(el) {                                          // Update profile navigation
	
	// Fade out mobile version of navigation if opened
	$("#sd3a4srtsfadgfssdf334erfgs").removeClass('brz-show');
	
	// Remove active classes	
	$(".brz-element-main-tab").removeClass('brz-active-small');
	
	// Add active class to requested element	
	$("#ki43"+el).addClass('brz-active-small');
	
}

function updateNavigation(el) {                                       // Update Side navigation
	
	// Remove active classes	
	$(".nav-item").removeClass('nav-item-active');
	
	
	// Check whether animations are requested
	if(el !== 0) {
		
	    // Add active class if requested to a element
		$(".nav-item-"+el).addClass('nav-item-active');
		
	}
	
}

function bodyLoader(el) {                                             // Loader animations for body
	
	// Remove body loaders
	$("#"+el).html('');
	
	// If requested add loader animations to body
	$("#"+el).html('<div id="pre-loader-main" name="pre-loader-main" class="full"><img class="load-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" style="margin:10% auto auto 40%;" ></img><div>');

}

function lastPostLoads(t) {                                           // Loader animations for last post 
	
	if($("#last_post_preload").parent().hasClass('side-messages-container')) return true;

	// Remove loader animations
	$("#last_post_preload").html('');
	
	// Check whether animations are requested
	if(t == 1) {
		
		// Disabled animations origin
		$("#pre-loader-starter").addClass('brz-disabled');
		
		// Add animations
		$("#last_post_preload").html('<div class="bar" id="more-load-bar"></div>');
		
		// Start animations
		rotate('#more-load-bar',"#last_post_preload");
		
	}
	
	// Else remove animations
	if(t == 0) {
		
		// Remove Animations 
		$("#load-more-data").remove();
		
		// Remove origin 
		$("#last_post_preload").remove();
		
	}
	
}

function lastPostLoaders(t) {                                         // Loader animations for last post version 2.0
	
	// Animations requested
	if(t == 1) {
		
		// Remove if animations exists
		$("#last_post_preload").empty();
		
		// Add animations
		$("#last_post_preload").after('<div name="temp_pre_loader_load_more_feed" id="temp_pre_loader_load_more_feed" class="brz-animate-top"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg" style="margin-left:40%;margin-top:20%;" width="50" height="50"></img></div>');
	    
		// Remove origin
		$("#last_post_preload").remove();
		
	} else {
		
		// Remove animations if not requested
		$("#temp_pre_loader_load_more_feed").remove();
		
	}
	
}

function notificationLoaders(n1,n2) {                                 // Loader animations for notifications widget
	
	// Remove animations in top bar of notifications container
	$("#n23sd23-losdf43ad-1").html('');	
	
	// Remove animations in bottom bar of notifications container	
	$("#n23sd23-losdf43ad-2").html('');		
	
	// If animations requested
	if(n1 == 1) {
	    
		// Add animations in top bar of notifications container
		$("#n23sd23-losdf43ad-1").html('<div id="not-bar" class="bar"></div>');	
	    
		// Start animations
		rotate('#not-bar',".n23sd23-losdf43ad");
	
	}	
	
	
	if(n2 == 1) {
	    
		// Add animations in bottom bar of notifications container		
		$("#n23sd23-losdf43ad-2").html('<div id="not-bar" class="bar"></div>');
	    
		// Start animations
		rotate('#not-bar',".n23sd23-losdf43ad");
	
	}
	
}

function loadEmbed(link,t,src) {                                      // Load video
    
	if(t == 0) {
		$("#video-cinema").fadeOut();
		$("#video-frame").html('');
	} else {
		
		if(src=='YOUTUBE.COM') {
			$("#video-frame").html('<iframe class="center display-middle video-player" allowfullscreen="" frameborder="0" src="'+link+'"></iframe>');
		} else {
			$("#video-frame").html('<div class="center display-middle"><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg" ></img></div>');
			$.getJSON('https://noembed.com/embed',
				{format: 'json', url: link}, function (data) {
					$("#video-frame").html('<div class="center noflow display-middle video-player">'+data.html.replace(/width="480"/g, '').replace(/height="204"/g, 'class="video-player"').replace(/height="360"/g, 'class="video-player"').replace(/width="640"/g, 'class="video-player"')+'</div>');
			    }
		    );
        }
		$("#video-cinema").fadeIn();
	}
}

function playVideo(link,poster,id,t) {                                      // Load video
	
	var extension = link.substr( (link.lastIndexOf('.') +1) );

    switch(extension) {
        case 'flv': var format = 'video/x-flv'; break;
        case 'mp4': var format = 'video/mp4'; break;
        case 'm3u8': var format = 'application/x-mpegURL'; break;
        case 'ts': var format = 'video/MP2T'; break;
        case '3gp': var format = 'video/3gpp'; break;
        case 'mov': var format = 'video/quicktime'; break;
        case 'avi': var format = 'video/x-msvideo'; break;
        case 'wmv': var format = 'video/x-ms-wmv'; break;
    }
	
	if(t == 0) {
		$("#video-custom-cinema").fadeOut();
		$("#video-custom-frame").html('');
	} else {
		$("#video-custom-frame").html('<div class="center padding-10 white-color display-middle video-player"><video id="'+id+'" class="video-js full" style="height:auto;" controls preload="auto" poster="'+poster+'" data-setup="{}"><source src="'+link+'" type="'+format+'"><p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that<a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p></video></div>');
		$("#video-custom-cinema").fadeIn();
		var performed = ajaxProtocol(update_view_file,id,0,0,0,0,0,0,0,0,0,0,0,0,'VID-CNT',0,1,0);
	}
}

function removeEl(id) {                                               // Fade out element
	$("#"+id).remove();
}

function locate(to) {                                                 // Redirect	
	window.location.href = to ;
}

function resetElement(id,text) {                                      // Update html data of element
	$(id).html(text); 
}

function updateSRC(id,src) {                                          // Update IMG SRC
	$(id).attr('src',src); 
}
 
function ajaxProtocol(fl,p,f,t,v1,v2,v3,v4,v5,v6,v7,v8,v9,v10,ff,i,req,body) {   // Breeze Ultimate JQuery AJAX engine Executer(Imported)
	
    // Reset
    p  = typeof p !== 'undefined' ? p : 0;
    t  = typeof t !== 'undefined' ? t : 0;
    i  = typeof i !== 'undefined' ? i : 0;
    v1 = typeof v1 !== 'undefined' ? v1 : 0;
    v2 = typeof v2 !== 'undefined' ? v2 : 0;
    v3 = typeof v3 !== 'undefined' ? v3 : 0;
    v4 = typeof v4 !== 'undefined' ? v4 : 0;
    v5 = typeof v5 !== 'undefined' ? v5 : 0;
    v6 = typeof v6 !== 'undefined' ? v6 : 0;
    v7 = typeof v7 !== 'undefined' ? v7 : 0;
    v8 = typeof v8 !== 'undefined' ? v8 : 0;
    v9 = typeof v9 !== 'undefined' ? v9 : 0;
    v10 = typeof v10 !== 'undefined' ? v10 : 0;
	ff  = typeof ff !== 'undefined' ? ff : 0;
	req = typeof req !== 'undefined' ? req : 0;
    body= typeof body !== 'undefined' ? body : 0;

    var gen = $('#installation_select').val() + fl;

	// This abort all pending requests
	if(req == 1) {
		
		// Generate token
		var token = generateToken(1);
		
		var xhr;
		if(xhr && xhr.readyState != 4) {
			xhr.abort();
		}
		xhr = $.ajax({
		    type: "POST",
		    url: gen,
		    data: { p: p, f: f, t: t, ff: ff, i: i, v1: v1, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10, bo: body},
			cache: false,
		    success: function(data) {
				
				// Match token
				var valid_token = matchToken(1,token);
			
			    // Confirm token
				if(valid_token) {
					
					// Handover data to organise in body
                	return handover(v9,v10,data,body);
					
				}
				
		    }
	    });

	// This type of requests will keep processing till finish
	} else {
		$.ajax({
		    type: "POST",
		    url: gen,
		    data: { p: p, f: f, t: t, ff: ff, i: i, v1: v1, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10, bo: body},
			cache: false,
		    success: function(data) {
				
				// Handover data to organise in body
                return handover(v9,v10,data,body);  
				
		    }
	    });			
	}
}

function handover(v9,v10,data,body) {	                                            // Breeze Ultimate JQuery AJAX engine DATA handler for Social network
	if(body == 1) {       // Full body replace component
		$("#content-body").empty();
		$("#content-body").html(data);
	    refreshElements();
		return 1;
	} 
	if(body == 2) {		  // Mid body Internal replacing component
		$("#threequarter").empty();
		$("#threequarter").html(data);
		refreshElements();
		return 1;
	}
	if(body == 3) {       // Remove notification loader
		notificationLoaders(0,0);	
		return 1;
	}
	if(body == 4) {       // Append fetched data to previous ROWED content
		lastPostLoaders(0);
		$("#all_posts").append(data);
	    refreshElements();
	}
	if(body == 5) {       // Replace mid body with fetched data
		$("#threequarter").replaceWith(data);
	    refreshElements();
	}
	if(body == 6) {       // Posts appending data component
		lastPostLoads(0);
		$("#all_posts").append(data);
	    refreshElements();
	}
	if(body == 7) {       // Remove button loader
		// Import strings
	    importLang();
		btnLoader(0,lang[7]);	
		$("#errors-eb").html(data);
	}
	if(body == 8) {       // Clean posts appending data component
        lastPostLoaders(0);
		$("#all_posts").empty();
		$("#all_posts").append(data);
	    refreshElements();
	}
	if(body == 9) {       // Comments updating component
		
		if(v10 == 2) {
		    csLoader(v9,1,0);
		    $("#comments-"+v9).prepend(data);
		} else {
		    if(v10 == 1) {
			    csLoader(v9,2,0);
				$("#comments-"+v9).append(data);
				$("#form-add-comment-"+v9).css('pointer-events','auto');
			} else {
			    csLoader(v9,1,0);
			    $("#comments-"+v9).html(data);
			}	    
		}

        refreshElements();		
	}
	if(body == 10) {      // Universal pop up managing component
		editLoaders(0);
        $("#"+v10).fadeOut();
		$("#success-modal-content").html(data);
		$("#success-modal").fadeIn();
		$("#post_view_"+v9).html('<div class="center padding-20"><img class="img-35" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg" ></img></div>');
	    var performed = ajaxProtocol(load_post_file,0,0,1,v9,0,0,0,0,0,0,0,0,v9,3,0,0,31);
	}
	if(body == 11) {      // Edit post content fetcher
		$("#edit-modal-content").html(data);
	}
	if(body == 12) {      // Chats messages fetcher
		lastPostLoads(0);
		$(".messages-container-"+v10).prepend(data);
	}if(body == 13) {     // Chat search management
		$(v10).html(data);
	}
	if(body == 14) {      // Edit chat content fetcher
		editLoaders2(0);
		if(v9 == 1) {		    
			$("#chat-edit-modal-msg").html(data);
			$("#confirm-edit-submit").attr("onclick","editChatFormSubmit("+v10+")");
		} else { 
			$("#chat-edit-modal-content").html(data);
		}
	}if(body == 15) {      // Edit chat content fetcher
		$("#chat-start-modal-content").html(data);
	}if(body == 20) {      // Gallery box on profile
		$("#gallery-box-main").find('div#last_post_preload').remove();
		$("#gallery-box-main").find('div#load-more-data').remove();	
		$("#gallery-box-main").append(data);
	}
	if(body == 21) {      // Gallery box on profile
		$("#gallery-box-main").html(data);
	}
	if(body == 23) {      // Gallery box on profile
		$("#people-box-main").find('div#last_post_preload').remove();
		$("#people-box-main").find('div#load-more-data').remove();	
		$("#people-box-main").append(data);
	}
	if(body == 24) {      // Gallery box on profile
		$("#chats-box-main").find('div#last_post_preload').remove();
		$("#chats-box-main").find('div#load-more-data').remove();	
		$("#chats-box-main").append(data);
	}
	if(body == 25) {      // Gallery box on profile
		$("#threequarter").replaceWith(data);
	}
	if(body == 26) {      // Gallery box on profile
		$("#trending-box-main").find('div#last_post_preload').remove();
		$("#trending-box-main").find('div#load-more-data').remove();	
		$("#trending-box-main").append(data);
	}
	if(body == 27) {      // Search
		$("#search-results-main-box").find('div#last_post_preload').remove();
		$("#search-results-main-box").find('div#load-more-data').remove();	
		$("#search-results-main-box").append(data);
	}
	if(body == 28) {      // Settings content
		var el = $("#settings-tab-"+v10);  
		el.fadeOut(0);
		el.after(data);
	}
	if(body == 29) {      // Settings tab
		var el = $("#settings-tab-"+v10);
		$(".settings-content-class").fadeOut(0);
		el.replaceWith(data);
		$('html, body').animate({scrollTop: 0},"slow");
	}
    if(body == 30) {      // Settings saved
		$("#settings-content-mess-"+v10).html(data);
	}
	if(body == 31) {
	    $("#post_view_"+v10).replaceWith(data);
	}
	if(body == 32) {
	
	    if($(window).width() > 600) {
	    	$("#new-modal-content").empty().animate({
            	width: '+=200px',height: '+=400px'
        	}, 600,function() {
			    $("#new-modal-content").html('<div id="new-modal-inner"></div>');
			    $("#new-modal-inner").fadeOut(0).html(data);
			    $("#new-modal-inner").fadeIn();
			});
		} else {
	    	$("#new-modal-content").empty().animate({
            	height: '+=200px'
        	}, 500,function() {
			    $("#new-modal-content").html('<div id="new-modal-inner"></div>');
			    $("#new-modal-inner").fadeOut(0).html(data);
			    $("#new-modal-inner").fadeIn();
			});
		}
		
	}
	if(body == 66) {      // Settings saved
		$(v10).find('div.preloader').remove();
		if(v9 == 0){
		    $(v10).find('div.block-container-content').html(data);
		} else {
		    if(v9 == 1){
		        $(v10).find('div.block-container-content').append(data);
		    } else {
			    $(v10).find('div.block-container-content').prepend(data);
			}
	    }
	}
	if(body == 0) {       // RETURN fetched data
		refreshElements();
		return data;
	}
    if(body == 67) {      // Add post to top
       $("#all_posts").prepend(data);
	   refreshElements();
	   $(".preloader-posts").remove();
	}
	if(body == 97) {      // reload on return
       location.reload();
	}
	if(body == 98) {     
		$("#execute_responses").append(data);
	}
    if(body == 34) {      
		$(v10).prepend(data);
		$(v9).hide();
	}if(body == 43) {      
		$(v10).append(data);
		$(v9).hide();
	}if(body == 45) {      // Replace ID element
		$(v10).replaceWith(data);
	}	
	if(body == 99) {      // ALERT fetched data without processing
       alert(data);
	} 
	refreshElements();
}

function userDescription(id) {                                        // Toggle accordion data
    
	// Select accordion
	var x = $("#"+id);
    
	// If accordion data is already visible
	if(x.hasClass('brz-fade332asdugyu')){ 
	    
		// remove above identifier
		x.removeClass("brz-fade332asdugyu");
        
		// fade out accordion data
		x.slideUp();
		
		x.prev().removeClass("brz-white-3").find("i.rotateable").removeClass("brz-rotated");
    
	} else {	 
	    
		// Add fade in identifier
		x.addClass("brz-fade332asdugyu");
		
		x.prev().addClass("brz-white-3").find("i.rotateable").addClass("brz-rotated");
		
		// Fade in accordion data
		x.slideDown();
		
	}
	
}

function openTab(t) {                                                 // Open settings tab
  
	// Select tab
	var el = $("#settings-tab-"+t);

	closeAll();
	
	// Add tab loader
	tabLoader(1,t);
	
	// Load tab
	var performed = ajaxProtocol(load_tab_content_file,0,0,0,0,t,0,0,0,0,0,0,0,t,0,0,1,28);
	
}

function saveTab(t) {                                                 // Save settings tab
    
	v1=v2=v3=v4=v5=v6=v7=v8=v9=0;
	
	// General settings | information requries only three params
	var v1 = $("#settings-input-1").val(),
	    v2 = $("#settings-input-2").val(),
	    v3 = $("#settings-input-3").val();
		
	// Privacy settins requires 9-10 params
	if(t > 14) {
		var v4 = $("#settings-input-4").val(),
	    	v5 = $("#settings-input-5").val(),
	    	v6 = $("#settings-input-6").val(),
	    	v7 = $("#settings-input-7").val(),
	    	v8 = $("#settings-input-8").val(),
	    	v9 = $("#settings-input-9").val();
	}

    // Add wizard loader
	contentLoader(1,t);
	
	// Load tab
	var performed = ajaxProtocol(save_tabs_file,0,0,t,v1,v2,v3,v4,v5,v6,v7,v8,v9,t,0,0,1,30);
	
}

function closeTab(t,s) {                                              // Close settings tabs
    
	// Select tab
	var el = $("#settings-tab-"+t);

	// Load tab
	var performed = ajaxProtocol(load_tabs_file,0,0,0,s,t,0,0,0,0,0,0,0,t,0,0,1,29);
	
}

function tabLoader(tt,t) {                                            // Tab loader
    
	var el = $("#settings-tab-"+t);
	if(tt == 1) {
       el.css('pointer-events','none');
       $("#settings-tab-space-"+t).prepend('<img class="preloader img-18" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></img>');
    } 
}

function contentLoader(tt,t) {                                        // Settings loader
   	if(tt == 1) {
        $("#settings-content-close-"+t).css('pointer-events','none');
        $("#settings-content-save-"+t).css('pointer-events','none');
		$("#settings-content-save-"+t).append('<img id="settings-content-loader" class="settings-content-loader load-tin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/search_loader.svg"></img>').find('span').hide();
    } else {
	    $("#settings-content-close-"+t).css('pointer-events','auto');
        $("#settings-content-save-"+t).css('pointer-events','auto').find('span').show();
		$("#settings-content-loader").remove();
	}
}

function setSettings(type) {                                          // Update user settings navigation
	$("#settings-nav-"+type).addClass("b2 dark-font-only active-side").removeClass("tin-light-font").siblings().removeClass("b2 dark-font-only active-side").addClass("tin-light-font");
}

function toggleIt(x,y) {                                              // Togle topbar
	// fade out notifications widget
    if($(x).hasClass("g-showing")){ 
        $(x).removeClass("g-showing").hide();
		updateTopbar('NULL');    
	} else {
	    $(x).addClass("g-showing").show();
		updateTopbar(y);
	}
	 
}

function addKeys(type,t) {                                              // Attach dynamic keywords to search results
    
	// Get custom keywords
	var v = $.trim($('#add-'+type+'-keys').val());
	
	// Random number
	var id = "",
    character_set = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	// Generate captcha
    for( var i=0; i < 5; i++ )
        id += character_set.charAt(Math.floor(Math.random() * character_set.length));
	
	// Check random id
	if($('#filter-people-'+type+'-'+id).length) {var id = 1 + Math.floor(Math.random() * 6 + Math.random() * 2223);}

	// Uncheck previous
	$("input[name='filter-people-"+type+"']:checked").next().removeClass('checked');
	
	// Remove attribute too
	$("input[name='filter-people-"+type+"']:checked").prop('checked', false);

	// Add new radio with custom filter
	$('<li class="tin-light-font"><input type="radio" checked id="filter-people-'+type+'-'+id+'" onclick="search(0,25,'+t+');scrollToTop();"  name="filter-people-'+type+'" value="'+v+'" checked><label class="h5 b3">'+v+'</label><div class="bullet"><div class="line zero"></div><div class="line one"></div><div class="line two"></div><div class="line three"></div><div class="line four"></div><div class="line five"></div><div class="line six"></div><div class="line seven"></div></div></li>').insertBefore("#add-"+type+"-keys-root");

	// Empty wizard
	$("#add-"+type+"-keys").val('');
	
	// Remove wizard
	$("#add-"+type+"-keys").next().fadeOut(200);
	
	// Search with new filters
	search(0,25,t);scrollToTop();

}

function getKeys(type) {                                              // Get keywords
    var v = $.trim($("#add-"+type+"-keys").val());
	
	if(v.length > 0) {
	    $("#add-"+type+"-keys").next().fadeIn();
	} else {
	    $("#add-"+type+"-keys").next().fadeOut(200);
	}
}

function closeAll() {                                                 // Close settings tabs
    
	// Remove all previous opened tabs
	$(".settings-content-class").each(function() { 
	    $(this).prev().fadeIn(0).css('pointer-events','auto').find("img.preloader").remove();
		$(this).remove();	
    });
	
}

function returnFalse() {}                                             // Disable href for IE9