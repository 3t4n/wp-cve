function cdp_link_upgrade_labels()
{
	if (jQuery('.plugin_is_not_registered').length == 0) {
		return;
	}
	jQuery('.easy-t-radio-button').each(function (index) {
		var my_radio = jQuery(this).find('input[type=radio]');
		if (my_radio)
		{
			var disabled = (my_radio.attr('disabled') && my_radio.attr('disabled').toLowerCase() == 'disabled');
			if (disabled) {
				var my_em = jQuery(this).find('label em:first');
				if (my_em.length > 0) {
					var my_id = my_radio.attr('id');
					var buy_url = 'https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/';
					var link_template = '<a href="@buy_url" target="_blank"></a>';
					var link = link_template.replace(/@buy_url/g, buy_url);
					my_em.wrap(link);
				}				
			}
		}
	});
}

/* galahad */
var cdp_replace_last_instance = function (srch, repl, str) {
	n = str.lastIndexOf(srch);
	if (n >= 0 && n + srch.length >= str.length) {
		str = str.substring(0, n) + repl;
	}
	return str;
}

var cdp_submit_ajax_form = function (f) {
	var msg = jQuery('<p><span class="fa fa-refresh fa-spin"></span><em> One moment..</em></p>');	
	var f = jQuery(f).after(msg).detach();
	var enc = f.attr('enctype');
	var act = f.attr('action');
	var meth = f.attr('method');
	var submit_with_ajax = ( f.data('ajax-submit') == 1 );
	var ok_to_send_site_details = ( f.find('input[name="include_wp_info"]:checked').length > 0 );
	
	if ( !ok_to_send_site_details ) {
		f.find('.gp_galahad_site_details').remove();
	}
	
	var wrap = f.wrap('<form></form>').parent();
	wrap.attr('enctype', f.attr('enctype'));
	wrap.attr('action', f.attr('action'));
	wrap.attr('method', f.attr('method'));
	wrap.find('#submit').attr('id', '#notsubmit');

	if ( !submit_with_ajax ) {
		jQuery('body').append(wrap);
		setTimeout(function () {
			wrap.submit();
		}, 500);	
		return false;
	}
	
	data = wrap.serialize();
	
	$.ajax(act,
	{
		crossDomain: true,
		method: 'post',
		data: data,
		dataType: "json",
		success: function (ret) {
			var r = jQuery(ret)[0];
			msg.html('<p class="ajax_response_message">' + r.msg + '</p>');
		}
	});		
};

var cdp_submit_ajax_contact_form = function (f) {
	$ = jQuery;
	
	// initialize the form
	var ajax_url = 'https://goldplugins.com/tickets/galahad/catch.php';
	//f.attr('action', ajax_url);
	
	// show 'one moment' emssage
	var msg = '<p><span class="fa fa-refresh fa-spin"></span><em> One moment..</em></p>';
	$('.gp_ajax_contact_form_message').html(msg);
	
	var f = jQuery(f).after(msg).detach();
	var enc = f.attr('enctype');
	var act = f.attr('action');
	var meth = f.attr('method');

	jQuery('body').append(f);	
	var wrap = f.wrap('<form></form>').parent();
	wrap.attr('enctype', f.attr('enctype'));
	wrap.attr('action', f.attr('action'));
	wrap.attr('method', f.attr('method'));	
	wrap.find('#submit').attr('id', '#notsubmit');

	setTimeout(function () {
		wrap.submit();
	}, 100);
	
	
	
	
	
	data = f.serialize();
	
	$.ajax(
		ajax_url,
		'post',
		data,
		function (ret) {
			alert(ret);
		}
	);
	return false; // prevent form from submitting normally
};

var cdp_setup_contact_forms = function() {
	$ = jQuery;
	var forms = $('.gp_support_form_wrapper div[data-gp-ajax-contact-form="1"]');
	if (forms.length > 0) {
		forms.each(function () {
			var f = this;
			var btns = $(this).find('.button[type="submit"]').on('click', 
				function () {
					cdp_submit_ajax_contact_form(f);
					return false;
				} 
			);
		});
	}
	jQuery('.gp_ajax_contact_form').on('submit', cdp_submit_contact_form);
};

var cdp_setup_ajax_forms = function() {
	$ = jQuery;
	var forms = $('div[data-gp-ajax-form="1"]');
	if (forms.length > 0) {
		forms.each(function () {
			var f = this;
			var btns = $(this).find('.button[type="submit"]').on('click', 
				function () {
					cdp_submit_ajax_form(f);
					return false;
				} 
			);
		});
	}
};

var cdp_setup_admin_notices = function() {
	jQuery( '#company_directory_notice_bar' ).on( 'click', '.dismiss', function( e ) {

		var $btn = jQuery( this ),
			$notice = $btn.closest( '#company_directory_notice_bar' ),
			$data = {
				action: 'company_directory_notice_bar_dismiss',
				_ajax_nonce: $btn.data( 'nonce' ),
			};

		$notice.addClass( 'out' );
		jQuery('body').removeClass( 'company_directory_notice_bar_show' );

		setTimeout(
			function() {
				$notice.remove();
			},
			300
		);

		jQuery.get( ajaxurl, $data );
	} );
};

jQuery(function () {
	cdp_setup_ajax_forms();
	cdp_setup_admin_notices();
	//cdp_setup_contact_forms();
});