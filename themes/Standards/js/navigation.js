// Open side navigation
function sidenav_open() {
	
	if($("#SIDE_NAVIGATION").hasClass("admin-nav")) {
		$("#SIDE_NAVIGATION, #OVERLAY_NAV").show();
	} else {
		if($("#pri-nav").parent().hasClass("InProc")) { 
			// Postpone
		} else {
			
			
			$("#pri-nav").parent().addClass("InProc");
			
			if($(window).width() > 1300) {
				$("#pri-nav").animate({width:'hide'}, 270, function() { 
					$("#sec-nav").animate({width:'show'}, 270);
					$("#pri-nav").parent().addClass("opened").removeClass("InProc");
				});			
			} else {
				$("#sec-nav").animate({width:'show'}, 270);
				$("#pri-nav").parent().addClass("opened").removeClass("InProc");	
			}

		}	
	}	
}

function sidenav_close() {
	
	if($("#SIDE_NAVIGATION").hasClass("admin-nav")) {
		var e=$("#SIDE_NAVIGATION");
		if($(window).width()<980) {e.removeClass("brz-43wsd"),e.hide()}
	} else {

		if($("#pri-nav").parent().hasClass("InProc")) { 
			// Postpone
		} else {
			
			if($("#pri-nav").parent().hasClass("opened")) {
				
				$("#pri-nav").parent().addClass("InProc");
				
				if($(window).width() > 1300) {
					$("#sec-nav").animate({width:'hide'}, 270, function() { 
						$("#pri-nav").animate({width:'show'}, 270);
						$("#pri-nav").parent().removeClass("opened InProc");
					});
				} else {
					$("#sec-nav").animate({width:'hide'}, 270);
					$("#pri-nav").parent().removeClass("opened InProc");
				}
			}
			
		}
		
	}

}

// Used to toggle the menu on small screens when clicking on the menu button
function nav_controller() {
    var x = $("#tos_nav");
    x.toggleClass("brz-hide");
}

// Post form 
function alterForm(x,y) {
    if($("#youtube-76efh").is(':visible') && x.valueOf() !== String("#youtube-76efh").valueOf()){$("#movie_text-2134").removeClass('brz-text-active');$("#youtube-76efh").slideUp(200,function () {$(x).slideDown();});}   
    if($("#photo-84u78").is(':visible') && x.valueOf() !== String("#photo-84u78").valueOf()){$("#photo_text-2134").removeClass('brz-text-active');$("#photo-84u78").slideUp(200,function () {$(x).slideDown();});}   
    if($("#status-t4njhsdf").is(':visible') && x.valueOf() !== String("#status-t4njhsdf").valueOf()){$("#status_text-2134").removeClass('brz-text-active');$("#status-t4njhsdf").slideUp(200,function () {$(x).slideDown();});}   
	$(y).addClass('brz-text-active');
}

// Toggle navigation for browsable sections
function toggle_side_nav() {
	var e=$("#SIDE_NAVIGATION");	
	if(e.hasClass("brz-43wsd")) {
		e.removeClass("brz-43wsd").hide(); 
		$("#admin-nav-opener").removeClass('header-mob-icon-active').siblings().removeClass('header-mob-icon-active');
	} else{ 
		e.addClass("brz-43wsd").show();
		$("#admin-nav-opener").addClass('header-mob-icon-active').siblings().removeClass('header-mob-icon-active');
	}
}
	
// Toggle profile navigation
function showProfileNav(e) {
	var a=document.getElementById(e);
	
	-1==a.className.indexOf("brz-show") ? a.className+=" brz-show" : a.className=a.className.replace(" brz-show","") 
}

// User logout
function user_logOut(){
	var e=$("#installation_select").val();
    
	$.ajax({type:"POST",url:e+"/require/requests/actions/user_logout.php",cache:!1,
	
		beforeSend:function(){
			$("#nav-item-logout").empty(),
			$("#nav-item-logout").after('<div class="full center"><img class="load-tin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg"></img></div>') 
		},
		
		success:function(e){$("body").append(e);clearInterval(again);}
		
	})
}
	
// Administration logout
function admin_logOut(){
	var e=$("#installation_select").val();
	$.ajax({type:"POST",url:e+"/require/requests/actions/admin_logout.php",cache:!1,
	   
    	beforeSend:function(){
			$("#nav-item-logout").empty(),
			$("#nav-item-logout").after('<div class="full center padding-0-0-0-10"><img class="load-tin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.svg"></img></div>') 
		},
			
		success:function(e){$("body").append(e)}
	})
}