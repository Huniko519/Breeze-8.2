<?php 
// Add active notifications if enabled
function notifications($identifier,$path,$path2) {
	global $TEXT,$page_settings;

    $not_type  = ($page_settings['poll_notifications']) ? 'dfsaafe45e(data);' : 'again2 = setTimeout(function(){again = setTimeout(function(){dfsaafe45e(data);},5000);},10000);';
	
    $mess_type  = ($page_settings['poll_inbox']) ? 'sadasdsasd(data);' : 'again2 = setTimeout(function(){sadasdsasd(data);},10000);';
	
	$echo = '<script>
			function dfsaafe45e(p) {	
				var installation = $(\'#installation_select\').val();
				$.ajax({
					type: "POST",
					url: installation + "%s",
					data: "pre=" + p,
					cache: false,
					success: function(data) {
						
						if(data > 9) {
							var value = \'9+\';
						} else {
							var value = data;
						}
						
						if(value == 0) {
							$("#dfbhne78342jsdf").empty().parent().find(\'.header-icon\').removeClass(\'no-opacity\');
							$("#NOTIFS_COUNTER_MB").empty().parent().removeClass(\'header-mob-icon-active-text-only\');
						} else {
							if(data !== p) play("sound-to_the_pont");
							x = $("#367u8sdfv55ysdfg");
							if(x.hasClass(\'brz-dfertwesdfsdfdf\')) {
								$("#43if89").prepend(\''.showBox($TEXT['_uni-Notifications_pending']).'\');
								x.removeClass("brz-dfertwesdfsdfdf");}
								$("#NOTIFS_COUNTER_MB").html(\'<span class="header-icon-counter">\'+value+\'</span>\').parent().addClass(\'header-mob-icon-active-text-only\');
								$("#dfbhne78342jsdf").html(\'<span class="header-icon-counter">\'+value+\'</span>\').parent().find(\'.header-icon\').addClass(\'no-opacity\');
						}
						
						'.$not_type.'
					}
				});

			};
			
			dfsaafe45e(0);
			
			function sadasdsasd(p) {
				var installation = $(\'#installation_select\').val();
				$.ajax({
					type: "POST",
					url: installation + "%s",
					data: "pre=" + p,
					cache: false,
					success: function(data) {
						
						if(data > 9) {
							var value = \'9+\';
						} else {
							var value = data;
						}
						
						if(value == 0) {
							$("#INBOX_COUNTER").empty().parent().find(\'.header-icon\').removeClass(\'no-opacity\');
							$("#INBOX_COUNTER_MB").empty().parent().removeClass(\'header-mob-icon-active-text-only\');
						} else {
							$("#INBOX_COUNTER").html(\'<span class="header-icon-counter">\'+value+\'</span>\').parent().find(\'.header-icon\').addClass(\'no-opacity\');
							$("#INBOX_COUNTER_MB").html(\'<span class="header-icon-counter">\'+value+\'</span>\').parent().addClass(\'header-mob-icon-active-text-only\');
							if(data !== p) play("sound-all-eyes-on-me");
						}
						
						'.$mess_type.'
					}
				});
				
			};
			
			sadasdsasd(0);
		    </script>';
				
	return ($identifier == 1) ? sprintf($echo,$path,$path2) : ''; 
}
?>