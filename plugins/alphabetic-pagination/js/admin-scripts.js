	function ap_premium_alert(show){
		var $ = jQuery;
		var wp_alert = $('.premium-alert');
		wp_alert.hide();
		if(show && ap_object.ap_pro=='false'){			
			wp_alert.fadeIn();
			$('html, body').animate({ scrollTop: $(wp_alert).offset().top-70 }, 1000);
			setTimeout(function(){ wp_alert.hide(); }, 10000);
		}else{
			wp_alert.hide();
		}
	}
jQuery(document).ready(function($){
	
	$('input[name="ap_reset_theme"]').on('click', function(){
		var val = $(this).val();
		$('.ap_reset').removeClass('dark').removeClass('light').addClass(val);
		var img_obj = $('.ap_reset_sign_label img');
		var new_src = (val=='dark'?img_obj.data('dark'):img_obj.data('light'));
		img_obj.attr('src', new_src);
		
	});

    $('.ap_settings_div a.nav-tab').click(function(){

        $(this).parents().eq(1).find('a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        var data_tab = $(this).data('tab');
		var data_type = $(this).data('type');
        $('.nav-tab-content').hide();
        $('.nav-tab-content[data-content="'+data_tab+'"]').show();
        window.history.replaceState('', '', ap_object.this_url+'&t='+data_tab);
        ap_object.ap_tab = data_tab;
        $('input[name="ap_tn"]').val(data_tab);
		
		switch(data_type){
			case 'free':
				ap_premium_alert(false);
			break;
			case 'pro':
				ap_premium_alert(true);
			break;
		}
    });

    setTimeout(function(){
        $('.ap_alert_show').fadeOut();
    }, 5000);



});