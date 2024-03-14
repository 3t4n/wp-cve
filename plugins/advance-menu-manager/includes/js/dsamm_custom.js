(function($) {
    $(window).load(function() {
        $('#amm_dialog').dialog({
            modal: true, title: 'Subscribe To Our Newsletter', zIndex: 10000, autoOpen: true,
            width: '400', resizable: false,
            position: {my: 'center', at: 'center', of: window},
            dialogClass: 'dialogButtons',
            buttons: [
                {
                    id: 'Delete',
                    text: 'Subscribe Me',
                    click: function() {
                        // $(obj).removeAttr('onclick');
                        // $(obj).parents('.Parent').remove();
                        var email_id = jQuery('#txt_user_sub_amm').val();
                        var data = {
                            'action': 'add_plugin_user_amm',
                            'email_id': email_id
                        };
                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(ajaxurl, data, function() {
                            jQuery('#amm_dialog').html('<h2>You have been successfully subscribed');
                            jQuery('.ui-dialog-buttonpane').remove();
                        });
                    }
                },
                {
                    id: 'No',
                    text: 'No, Remind Me Later',
                    click: function() {

                        jQuery(this).dialog('close');
                    }
                }
            ]
        });
        jQuery('div.dialogButtons .ui-dialog-buttonset button').removeClass('ui-state-default');
        jQuery('div.dialogButtons .ui-dialog-buttonset button').addClass('button-primary woocommerce-save-button');
    });
    jQuery(document).ready(function() {
		var span_full = $('.toggleSidebar .dashicons');
		var show_sidebar = localStorage.getItem('afrsm-sidebar-display');
		if( ( null !== show_sidebar || undefined !== show_sidebar ) && ( 'hide' === show_sidebar ) ) {
			$('.all-pad').addClass('hide-sidebar');
			span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
		} else {
			$('.all-pad').removeClass('hide-sidebar');
			span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
		}

		$(document).on( 'click', '.toggleSidebar', function(){
			$('.all-pad').toggleClass('hide-sidebar');
			if( $('.all-pad').hasClass('hide-sidebar') ){
				localStorage.setItem('afrsm-sidebar-display', 'hide');
				span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
				$('.all-pad .dots-settings-right-side').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
				$('.all-pad .dots-settings-left-side').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
				setTimeout(function() {
					$('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'none');
				}, 300);
			} else {
				localStorage.setItem('afrsm-sidebar-display', 'show');
				span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
				$('.all-pad .dots-settings-right-side').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
				$('.all-pad .dots-settings-left-side').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
				// setTimeout(function() {
					$('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'block');
				// }, 300);
			}
		});
	});
    // script for plugin rating
	jQuery(document).on('click', '.dotstore-sidebar-section .content_box .et-star-rating label', function(e){
		e.stopImmediatePropagation();
		var rurl = jQuery('#et-review-url').val();
		window.open( rurl, '_blank' );
	});
})(jQuery);