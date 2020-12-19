// Linked files
var login_file = '/require/requests/actions/log.php',                               // {LOG_IN} main method
    login_content_file = '/require/requests/public/login_content.php';              // {LOG_IN} content

function submitLogin(e,t) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	// Switch to processing page
	switchPage('#login-page','#animated-loader-page');
	
	// Start pre loaders
	mainLoaders(1);
	
	// Perform login
	logIn(t);
	
	// Prevent default behaviour	
	return false;
	
}

// Login user
function logIn(t){
	
	if(t == 1) {
		var performed = ajaxProtocol(login_file,0,0,1,$("#login-name-dr").val(),$("#login-pass-dr").val(),0,0,0,0,0,0,0,0,0,0,0,98);
	} else {
		var performed = ajaxProtocol(login_file,0,0,1,$("#login-page-name").val(),$("#login-page-password").val(),0,0,0,0,0,0,0,0,0,0,0,98);
	}
	
}

function switchPage(fr,to) {
    
	// Slide loaders
	if(to == "#animated-loader-page"){

		$("#attach-response").html('<img class="center padding-10" src="'+installation+'/themes/'+theme+'/img/icons/loader-small.svg" class="img-35"></img>');
		
		// Scroll to top
	    $("html,body").animate({scrollTop: 0},"slow");	
		
	}

	// Scroll to top
	$("html,body").animate({scrollTop: 0},200);
	
}

function loadLogin(type,id) {

    // Close Modal
    if(type == 0) {
		loadModal(0);
	} else {
		
		// Load login notification
		if(type == 1) {
			var performed = ajaxProtocol(login_content_file,0,0,1,id,0,0,0,0,0,0,0,0,0,0,0,0,32);	
		}
	}
	
}

// Message receiver
function switchMessage(message,type) {
	$("#attach-response").html('<div class="brz-error-new brz-padding">'+message+'</div>');
	
}

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