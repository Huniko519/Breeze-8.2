var messa;
var cover = getCover();

// Start repositing
function startRepos() {
	if($('#btn-cover-repositioner').hasClass('working')) {
		return false;
	} else {	
		var cover = getCover();
    	var c_height = cover.height();
    	var downloadingCover = new Image();
    	$("#repos-ctrls").before('<div id="temp_lod" class="display-topmiddle small padding-10 background-0">'+$("#sav_ttl_1").html()+' ...</div>');
    	$("#upload-ctrls").fadeOut();
		downloadingCover.onload = function(){
			$("#temp_lod").remove();
			$("#repos-ctrls").fadeIn();	
        	$("#drag_ttl_1").show().next().html("").hide();	
        	cover.attr("src",this.src);
        	$("#cover-containee").show().addClass("enabled");
	    	cover.detach().prependTo("#cover-containee");
	    	$("#cover-container").hide();
	    	setVars(c_height);		
    	};	
		downloadingCover.src = cover.attr('src').replace('rep_','');
	}	
}

function setVars(c_height) {
	if(cover.get(0).complete) {
		ImageLoaded();
		clearTimeout(timer);
        var top_slide = '-' + (cover.height() - c_height);
		cover.css({ top:  top_slide+'px', left: '0px' });
	} else {
		var timer = setTimeout(function() {setVars(c_height)}, 100);
	}
	
}

function cancelRepos() {
    var src = cover.attr('src');

    if (~src.indexOf('fault')) {
       // Leave cover change
    } else {
       cover.attr("src",cover.attr('src').replace('covers/rep_','covers/').replace('covers/','covers/rep_'));
    }
    cover.css({ top:  '0px', left: '0px' });
	$("#upload-ctrls").fadeIn();
    $("#repos-ctrls").fadeOut();
    $("#cover-container").html("").show();
	cover.detach().prependTo("#cover-container");
	$("#cover-containee").hide();
}

function coverRepositioned(url) {
	clearTimeout(messa);
	$("#saving-ctrls").fadeOut();
	var downloadingCover = new Image();
    $("#repos-ctrls").before('<div id="temp_lod" class="display-topmiddle small padding-10 background-0">'+$("#sav_ttl_2").html()+' ...</div>');
	downloadingCover.onload = function(){
		$("#temp_lod").remove();
		$("#upload-ctrls").fadeIn();
		$("#repos-ctrls").fadeOut();
		$("#cover-container").html("").show();
		cover.detach().prependTo("#cover-container");
		$("#cover-containee").hide();
        cover.attr("src",this.src);
		cover.css({ top:  '0px', left: '0px' });
        $("#cover-container").html("").show();
        cover.detach().prependTo("#cover-container");
        $("#cover-container-loader").html("").hide();		
    };	
    downloadingCover.src = $("#installation_select").val()+$("#rep_cvr_src").val()+url;	
	
}

function returnRepositon(type,message) {
	clearTimeout(messa);
	$("#sav_ttl_1, #sav_ttl_2").toggleClass("brz-hide");
	$("#saving-ctrls").fadeOut();
	$("#repos-ctrls").fadeIn();
	$("#cover-containee").html("").show();
	cover.detach().prependTo("#cover-containee");
	$("#cover-container-loader").hide();
	$("#drag_ttl_1").hide().next().html(message).show();
}

function saveRepos(t=0) {
    $("#repos-ctrls").fadeOut();
    $("#saving-ctrls").fadeIn();
	$("#cover-container-loader").html("").show();
	cover.detach().prependTo("#cover-container-loader");
	$("#cover-containee").html("").hide();
	clearTimeout(messa);

	if(t == 1) {
		
		// Revert to original image
		setResponso('0');
		
	} else {
		
		// Toggle messages
		messa = setTimeout(function() {$("#sav_ttl_1, #sav_ttl_2").toggleClass("brz-hide");}, 1500);

		// Set cover data
		var tImg = new Image();

		// Load image dynamically
		$(tImg).load(function () {

			// Set cover position (Revoulutionary version)
			setResponso($("#code-y").val() * (tImg.width / cover.parent().width()));

            // Delete temp image
			delete tImg;

		}).error(function () {
			
			// If image loading failed, Handover task of cover position to PHP
			setResponso($("#code-y").val());
			
    	}).attr({ src: cover.attr('src') });

	}

}

function setResponso(val) {
	$.ajax({
		type: "POST",
		url: $('#installation_select').val() + '/require/requests/update/reposition_cover.php',
		data: {yexs: val,group: $('#rep_cvr_id').val(),page: $('#rep_cvr_page_id').val()},
		cache: false,
		success: function(data) {
            return handover(0,0,data,98);	
		}
	});
}

function getCover() {
	if ($("#cover-containee").find('img').length) {   
		return $($("#cover-containee").find('img'));
	} else {
		if ($("#cover-container-loader").find('img').length) { 
		    return $($("#cover-container-loader").find('img'));
		} else {
			return $($("#cover-container").find('img'));
		}
	}
}

function ImageLoaded() {
	_IMAGE_WIDTH = cover.width();
	_IMAGE_HEIGHT = cover.height();
	_IMAGE_LOADED = 1;	
}

var cover = getCover();
var _DRAGGGING_STARTED = 0;
var _LAST_MOUSEMOVE_POSITION = { x: null, y: null };
var _DIV_OFFSET = $('#cover-containee').offset();
var _CONTAINER_WIDTH = $("#cover-containee").outerWidth();
var _CONTAINER_HEIGHT = $("#cover-containee").outerHeight();
var _IMAGE_WIDTH;
var _IMAGE_HEIGHT;
var _IMAGE_LOADED = 0;

if(cover.get(0).complete) {
	ImageLoaded();
	_CONTAINER_WIDTH = $("#cover-containee").outerWidth();
    _CONTAINER_HEIGHT = $("#cover-containee").outerHeight();
} else {
	cover.on('load', function() {
		ImageLoaded();
		_CONTAINER_WIDTH = $("#cover-containee").outerWidth();
        _CONTAINER_HEIGHT = $("#cover-containee").outerHeight();
	});
}

$('#cover-containee').on('mousedown', function(event) {	
	if(_IMAGE_LOADED == 1) { 		
		if($(cover).parent().hasClass("enabled")) {			
			_DRAGGGING_STARTED = 1;
			_LAST_MOUSE_POSITION = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };		
		} else {			
			_DRAGGGING_STARTED = 0;
		}
	}
});

$('#cover-containee').on('mouseup', function() {
	_DRAGGGING_STARTED = 0;
});

$('#cover-containee').on('mousemove', function(event) {
	if(_DRAGGGING_STARTED == 1) {
		var current_mouse_position = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
		var change_x = current_mouse_position.x - _LAST_MOUSE_POSITION.x;
		var change_y = current_mouse_position.y - _LAST_MOUSE_POSITION.y;

		_LAST_MOUSE_POSITION = current_mouse_position;

		var img_top = parseInt(cover.css('top'), 10);
		var img_left = parseInt(cover.css('left'), 10);

		var img_top_new = img_top + change_y;
		var img_left_new = img_left + change_x;

		if(img_top_new > 0) img_top_new = 0;
		if(img_top_new < (_CONTAINER_HEIGHT - _IMAGE_HEIGHT)) img_top_new = _CONTAINER_HEIGHT - _IMAGE_HEIGHT;

		if(img_left_new > 0) img_left_new = 0;
		if(img_left_new < (_CONTAINER_WIDTH - _IMAGE_WIDTH)) img_left_new = _CONTAINER_WIDTH - _IMAGE_WIDTH;

		$("#code-y").val(img_top_new);
		cover.css({ top: img_top_new + 'px', left: img_left_new + 'px' });
	}
});