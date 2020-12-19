// Linked Files
var blog_comment_file = '/require/requests/actions/blog_comment.php',  // Comment
    blog_like_file = '/require/requests/actions/blog_like.php';        // Like

$(document).ready(function() {
	
	// Universal search typing timer
	var typingTimer;
	
	// Time to wait for user typing response
    var typingInterval = 1000;
	
	$(document).on('keyup', '.search-articles-input', function() {
		
		// Clear timer
		clearTimeout(typingTimer);
		
		var inputs = $($('.search-articles-input').get());
		
		var val = $(this).val();  
		$('.search-articles-input').val('');
		$(this).val(val);
		
		var val = (inputs.eq(0).val() == $(this).val()) ? (inputs.eq(0).val()) :  (inputs.eq(1).val());
		
		// If some typing response from user found
		if (val) {
			
			// Add preloader
			$(".search-articles-input").addClass("search-icon-loading").removeClass("search-icon");
            
			// Add timer to trigger for results
			typingTimer = setTimeout(function() {searchArticles(0,1);}, typingInterval);
			
		} else {
			
			// Clear preloader and timer if no letters typed
			$(".search-articles-input").addClass("search-icon").removeClass("search-icon-loading");
		
		}
		
	});
	
});

function submitArticleSearch(e) {                                            // Search value
	
	// Prevent default behaviour
	e.preventDefault();
	
	if(/Mobi/.test(navigator.userAgent) == true) {
		// Perform search
		searchArticles(0,1);
	}

	// Prevent default behaviour	
	return false;

}

function searchArticles(f,b) {                                              //  Load article search
	
	// Search input box value
	var inputs = $($('.search-articles-input').get());

	var v = (inputs.eq(0).val()) ? ($.trim(inputs.eq(0).val())) :  ($.trim(inputs.eq(1).val()));

    // Add history
	if(b==1){store('/blogs/search/'+v)};

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
	$(".search-articles-input").addClass("search-icon").removeClass("search-icon-loading");
	
	// Perform AJAX request to get search results
	var performed = ajaxProtocol(load_blogd_file,0,f,0,v,0,0,0,0,0,0,0,0,0,6,0,0,b);
;
}

function likeBlog(id,el,ha,hb) {                            // Like blog
	
	// Update button styles,on click events and titles
	relBtn(el,hb,"unlikeBlog('"+id+"','"+el+"','"+hb+"','"+ha+"')","","btn-active");

	var performed = ajaxProtocol(blog_like_file,id,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function unlikeBlog(id,el,ha,hb) {                          // Undo Like 

	relBtn(el,hb,"likeBlog('"+id+"','"+el+"','"+hb+"','"+ha+"')","btn-active","");
	
	var performed = ajaxProtocol(blog_like_file,id,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);	
}
	
function deleteBlog(id,t) {                                            // Delete blog data                                   
	
	// Fade in delete wizard || modal
	$('#confirm-delete').fadeIn();
	
	// Add post id | user id | comment id to on click event 
	$('#confirm-delete-submit').attr("onclick","deleteSubmitBlog("+id+","+t+")");
	
}

function deleteSubmitBlog(id,t){                                       // Delete blog data submit
    
	// Dynamically disable delete submit button
	$('#confirm-delete-submit').attr("onclick","");                                
	
	deleteLoaders(1);
	
	// Comment
	if(t == 1) {
		$("#comment-id-"+id).fadeOut(500,function(){$("#comment-id-"+id).remove();});
	}
	
	// Perform AJAX request to delete blog related data
	var performed = ajaxProtocol(load_blogd_file,0,0,t,id,0,0,0,0,0,0,0,0,"confirm-delete",4,0,0,10);
	
}

function blogComments(id,f,b){                                       // Delete blog data submit
    
	lastPostLoads(1);
	
	// Perform AJAX request to delete blog related data
	var performed = ajaxProtocol(load_blogd_file,0,f,0,id,0,0,0,0,0,0,0,0,0,5,0,0,b);
	
}

function submitBlogMessage(e,id) {                                        // Submit Comment 
	
	// Prevent form submit and URL redirect
	e.preventDefault();
	
	// Add preloader
	$("#all_posts").prepend('<div align="center" class="margin-10 preloader-posts"><img class="load-mid" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.svg"></div>');
	
	// Disable form
	$("#form-add-comment-"+id).css('pointer-events','none');

	// Perform AJAX request
	var performed = ajaxProtocol(blog_comment_file,0,0,1,id,1,$("#form-add-comment-text-"+id).val(),0,0,0,0,0,id,1,0,0,0,67);
	
	// Return false also prevent default
	return false;
	
}

function createBlog(t,error) {
	if(t == 1) {
		
		$("#form-blogger").append(error);
		
		$('#blog-form-submit').attr('onclick','createBlog();');

		$('#pre-loader-update-blog').remove();
		
	} else {
		
		$('#blog-form-submit').attr('onclick','');
	
		// Add pre loaders to submit button
		$('#blog-form-submit').html('<div id="pre-loader-update-blog"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/search_loader.svg" class="load-tin"></img></div>');
	
		// Submit form
		document.getElementById("blog-form-data").submit();
		
	}
}