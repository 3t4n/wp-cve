function popslide() {

	var $ = jQuery.noConflict();

	var data = {
		'action': 'popslide_get',
		'nonce': popslide_settings.nonce
	};

    $.post(popslide_settings.ajaxurl, data, function(response) {

    	if ( response.success == false ) {
    		return false;
    	}

    	var $pop = $(response.data);

    	if (popslide_settings.position == 'top') {
			$pop.prependTo('body');
    	} else {
    		$pop.appendTo('body');
    	}

    	// FB stuff fix
    	try {
	        FB.XFBML.parse($pop[0]); 
	    } catch(ex){}

    	if (popslide_settings.position == 'top') $pop.slideDown(popslide_settings.animation_duration, 'linear');
		else if (popslide_settings.position == 'bottom') $pop.slideDown(popslide_settings.animation_duration, 'linear');

		$pop.find('.popslide-close span').click(function() {

			$pop.slideUp(popslide_settings.animation_duration, 'linear');

			if ( popslide_settings.cookie.active == 'true'  && popslide_settings.status.demo != 'true' ) {

				$.cookie(popslide_settings.cookie.name, 'true', { expires: parseInt(popslide_settings.cookie.days), path: '/' });

			}

		});

		sessionStorage.removeItem('popslide');

    });


	/*if ( popslide_settings.custom_target.targets != '' ) {

		$pop.find( popslide_settings.custom_target.targets ).one('click', false, function(event) {

			var $target = $(this);

			event.preventDefault();

			var data = {
				'action': 'popslide_ajax_save_cookie'
			};

		    $.post(popslide_settings.ajaxurl, data, function(response) {

		    	console.log('ajax');

		    	$target.off( event );

		    	if ( popslide_settings.custom_target.close == 'true' ) {
					$pop.slideUp(popslide_settings.animation_duration, 'linear');
					console.log('cookie saved');
					$target.click();
				} else {
					$target.click();
				}

		    });

		});

	}*/

}

jQuery(document).ready(function($) {

	if ( popslide_settings.status.active == true && typeof $.cookie(popslide_settings.cookie.name) === 'undefined' ) {

		if ( sessionStorage.getItem('popslide') == null ) {
			var hits = 1;
		} else {
			var hits = parseInt(sessionStorage.getItem('popslide')) + 1;
		}

		sessionStorage.setItem('popslide', hits);

		if ( (hits >= parseInt(popslide_settings.after.hits) && popslide_settings.after.rule == 'and') || popslide_settings.after.rule == 'or') {

			if ( popslide_settings.after.rule == 'or' && hits >= parseInt(popslide_settings.after.hits) ) {
				var timeout = 1000;
			} else {
				var timeout = 1000 * parseInt(popslide_settings.after.seconds);
			}

			window.setTimeout(function() { popslide(); }, timeout);

		}

	}

});