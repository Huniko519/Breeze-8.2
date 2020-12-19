// Linked files
var login_file = installation + '/require/requests/actions/log.php',                               // {LOG_IN} main method
    signup_file = installation + '/require/requests/actions/sign.php',                             // {SIGN_UP} main method
    extras_file = installation + '/require/requests/actions/extras.php',                           // {EXTRAS} main method
    browse_file = installation + '/require/requests/actions/browse.php',                           // Terms loader
    admin_file  = installation + '/require/requests/actions/admin_log.php';                        // Administration LOG_IN method

$(document).ready(function() {                                        // Document ready functions
	$(".notification").initialize( function(){
    	$(this).fadeIn({queue: false, duration: 500}).animate({left: '10%'}, 300);
	});	
	
	
	$(document).mouseup(function(e) {
		var container = $("#main-module");

		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.animate({ 
			'padding-top' : 0,
			'padding-right' : 0,
			'padding-bottom' : 0,
			'padding-left' : 0,
			}, 	100, function() {
				$("#main-module").removeClass("add-gradient");
			} );
		} else {
			container.addClass("add-gradient").animate({ 
			'padding-top' : 3,
			'padding-right' : 3,
			'padding-bottom' : 3,
			'padding-left' : 3,
			}, 	100);
		}
		
	});
	
});

function toggleForms(t,type) {
	type = type || 0;
    if($("#"+t).hasClass("DOT")) {
		return false;
	} else {

		$('html, body').animate({scrollTop: 0},"slow");
		
		$("#LOAD_FORM").fadeIn(200,function(){
			$("#LOAD_FORM").delay(300).fadeIn(10,function(){
				$(".main-forms").removeClass("DOT").slideUp(300);
				$("#"+t).addClass("DOT").slideDown(300,function() {$("#LOAD_FORM").fadeOut(200)});
			});
		});
		
		if(type == 0) {
			$(".connect-user").remove();
		}
	}
}

function returnForms(t,type) {
	type = type || 0;
	if(type==1) {
		$("#LOAD_FORM").fadeIn(200);
	} else {
		if($("#"+t).hasClass("DOT")) {
            $("#LOAD_FORM").delay(400).fadeOut(200);
		} else {
			$("#LOAD_FORM").fadeOut(200);
			$("#LOAD_FORM").fadeIn(200,function(){
				$("#LOAD_FORM").delay(500).fadeIn(10,function(){
					$(".main-forms").removeClass("DOT").slideUp(500);
					$("#"+t).addClass("DOT").slideDown(500,function() {$("#LOAD_FORM").fadeOut(200)});
				});
			});
		}
	}
}

// Clear Captcha
function captchaClear(t) {
	if(t == 0) {
	} else {
		mainLoaders(1);removeCaptcha();
	}
}

function submitLogin(e) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	returnForms('LOAD_FORM',1);
	
	// Perform login
	logIn();
	
	// Prevent default behaviour	
	return false;
	
}

function submitRegistration(e) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	returnForms('LOAD_FORM',1);
	
	// Perform login
	signUp();
	
	// Prevent default behaviour	
	return false;
	
}

function clearLogin(id) {
	
	// Delete recent login
	var clear_login = ajaxProtocol(extras_file,0,0,1,id,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
	if(!$("#RECENT_LOGINS > div").length) {
		$('#RECENT_LOGINS_TITLE').hide();
		addContent(1);
	}
	
}

function reLogin(e) {
	
	e.preventDefault();

	returnForms('LOAD_FORM',1);

	// Perform login
	var performed = ajaxProtocol(login_file,0,0,1,$("#login-modal-recent-add-name").val(),$("#login-modal-recent-add-pass").val(),1,0,0,0,0,0,0,0,0,0,0,1);

}

function loadLogin(username,name) {

    $("#login-user-ctrls").slideUp(200,function() {
		$('#login-modal-recent-add-name').val(username).next().val('');
		$('#login-modal-recent-img').attr('src',$('#R_LOGIN_IMG_NAME_'+username).attr('src'));
		$('#login-modal-recent-name').html(name);
		$("#login-user-ctrls").slideDown(200);
	});
	
	toggleForms('RECENT_FORM');
	
}

function addContent(t) {
	if(t == 1) {
		$('#CONTENT_MAIN_DISPLAY').show();
	}
}

function submitAdmin(e) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	// perform login
	directAdmin();
	
	// Prevent default behaviour	
	return false;
	
}

function directAdmin() {
	
	returnForms('LOAD_FORM',1);

    // Scroll to top
	$("html,body").animate({scrollTop: 0},"slow");
	
	// Perform search
	admin();
	
}

// Send password recovery
function resetPassword() {
	window.location.href = installation + '/index.php?respond=3425sd&type=startrecovery&for=' + $("#forgot-password-page-username").val() ;
    return false;
}

// Login user
function logIn() {
	var performed = ajaxProtocol(login_file,0,0,1,$("#login-page-name").val(),$("#login-page-password").val(),0,0,0,0,0,0,0,0,0,0,0,1);	
}

// Sign up user
function signUp(){
	var pass = $("#signup-page-password").val();
	var performed = ajaxProtocol(signup_file,0,0,1,$("#signup-page-name").val(),$("#signup-page-email").val(),pass,pass,$("#signup-page-cap").val(),'','',$("input[name='gender_radio']:checked").val(),0,0,0,0,0,1);
}

// Login administration
function admin(){
	var performed = ajaxProtocol(admin_file,0,0,1,$("#admin-page-name").val(),$("#admin-page-password").val(),0,0,0,0,0,0,0,0,0,0,0,1);
}

function removeCaptcha() {
	$(".element_captcha").remove();
}

// Message receiver
function switchMessage(message,type) {
	$("#response").html('<div id="modal-info-on" class="notification hide background-0"><div id="modal-text" style="width:85%;" class="h6 b1 left text-left padding-10 white-font-only">'+message+'</div><div onclick="$(\'.notification\').remove();" style="width:15%;" class="right pointer h5 b2 padding-10 theme-x-font noselect">X</div></div>');
}

function refreshElements() {                                          // Refresh PLUGINS
    return true;
}

// Request captcha image
function captcha(){

    var string = "",
    character_set = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	// Generate captcha
    for( var i=0; i < 5; i++ )
        string += character_set.charAt(Math.floor(Math.random() * character_set.length));

	$('#element_captcha').empty().html('<input id="signup-page-cap" class="captcha hm-input trans" type="text" placeholder="Captcha" style="background-image: url(./require/requests/content/captcha.php?capt='+ string +'&dark=blue)">'); 
    $('.element_captcha').fadeIn();
};

// JS pre loaders
function mainLoaders(t) {                                           
	var el = ".mainloaders-class",
	el2 = "#mainloaders-id" ,
	lod = "mainloaders-bar" 
	lod2 = "#mainloaders-bar";
	$(el2).html('');		
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
}

// Animation
function rotate(load,el){                                            
    $(load).animate( { left: $(el).width() }, 1300, function(){
    $(load).css("left", -($(load).width()) + "px");
    rotate(load,el);
    });
}

// AJAX request
function ajaxProtocol(fl,p,f,t,v1,v2,v3,v4,v5,v6,v7,v8,v9,v10,ff,i,req,body) {
    
    // Reset
    p = p || 0;
    t = t || 0;
    ff = ff || 0;
    i = i || 0;
    req = req || 0;
    body = body || 0;
    v1 = v1 || 0;
    v2 = v2 || 0;
    v3 = v3 || 0;
    v4 = v4 || 0;
    v5 = v5 || 0;
    v6 = v6 || 0;
    v7 = v7 || 0;
    v8 = v8 || 0;
    v9 = v9 || 0;
    v10 = v10 || 0;
  
	$.ajax({
		type: "POST",
		url: fl,
		data: { p: p, f: f, t: t, ff: ff, i: i, v1: v1, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10, bo: body},
		cache: false,
		success: function(data) {
			return handover(v9,v10,data,body);   
		}
	});
}

// AJAX response handler
function handover(v9,v10,data,body) {
	if(body == 1) {      // Universal pop up managing component
		$("#attach-response").append(data);
	}
	
	if(body == 12) {      // Universal pop up managing component
		$("#browse_container").html(data);
	}
}

function store(url) {                                                 // Dynamically update history
	
	// Set URL
	var add = installation + url;

	// Return if user has reloaded page
	if(add == window.location.href) {
		return true;
	}	
    

    window.history.pushState({path:add}, '', add);
    
}

function browseData(id) {
	store('/browse/'+id);
	$("#terms-nav-"+id).addClass("b2 theme-x-font active-side").removeClass("white-x-font").siblings().removeClass("b2 theme-x-font active-side").addClass("white-x-font");
	$("#browse_container").html('<div align="center"><img class="img-35 center" src="'+installation+'/themes/'+theme+'/img/icons/loader.svg"></img></div>')
    ajaxProtocol(browse_file,0,0,id,0,0,0,0,0,0,0,0,0,0,0,0,1,12);
}
