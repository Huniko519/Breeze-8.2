
function uploadVideo(t,error) {
	if(t == 1) {
		
		$("#form-blogger").append(error);
		
		$('#track-form-submit').attr('onclick','uploadVideo();').find('span').show();

		$('#pre-loader-update-track').remove();
		
	} else {
		
		$('#track-form-submit').attr('onclick','').find('span').hide();
	
		// Add pre loaders to submit button
		$('#track-form-submit').append('<div id="pre-loader-update-track"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/search_loader.svg" class="load-tin"></img></div>');
	
		// Submit form
		document.getElementById("video-form-data").submit();
		
	}
}
