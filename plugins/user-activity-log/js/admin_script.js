jQuery( document ).ready(
	function ($) {
		'use strict';
		// deactivation popup code
		var ual_plugin_admin = jQuery( '.documentation_ual_plugin' ).closest( 'div' ).find( '.deactivate' ).find( 'a' );
		ual_plugin_admin.click(
			function (event) {
				event.preventDefault();
				jQuery( '#deactivation_thickbox_ual' ).trigger( 'click' );
				jQuery( '#TB_window' ).removeClass( 'thickbox-loading' );
				change_thickbox_size_ual();
			}
		);
		checkOtherDeactivate();
		jQuery( '.sol_deactivation_reasons' ).click(
			function () {
				checkOtherDeactivate();
			}
		);
		$(document).on("click", ".ualp_ip_details_popup_close", function (e) {
            $('.ualp_ip_details_popup').removeClass("is-visible");
        })
        $(document).on( "click",".ualp_ip_details", function (e) {
            var $elm = $(this);
            $('.ualp_ip_details_popup').addClass("is-visible");
            var offset = $elm.offset();
            $('.ualp_ip_details_popup').css({
                top: offset.top,
                left: offset.left,
              });
            var ip_details =$(this).attr('data-ip');
            $.getJSON('https://ipapi.co/'+ip_details+'/json', function(data){
               
                var content = '<table class="ualp_info_table">';
                if ( typeof(data.latitude) != "undefined" && data.latitude && typeof(data.longitude) != "undefined" && data.longitude ) {
                    var ualp_map_js =  'http://maps.google.com/maps/api/js?sensor=false';
                    $('head').append('<script type="text/javascript" id="maps-js" src="' + ualp_map_js + '">');
                    content +='<tr><td colspan="2" style="padding:0;"><a href="https://www.google.com/maps/place/'+data.latitude+'/@'+data.longitude+',6z" target="_blank" class="ualp_map_popup_link"> <iframe src="http://maps.google.com/maps?q='+data.latitude+','+data.longitude+'&z=15&iwloc=near&output=embed" height="150" width="350" frameborder="0"></iframe></a></td></tr>';
                }
                if ( typeof(ip_details) != "undefined" && ip_details ) {
                    content += '<tr><td>'+ualpJSObject.ip_address+'</td><td>'+ip_details+'</td></tr>';
                }
                if ( typeof(data.org) != "undefined" && data.org ) {
                    content += '<tr><td>'+ualpJSObject.network+'</td><td>'+ data.asn + ' '+ data.org+'</td></tr>';
                }
                if ( typeof(data.city) != "undefined" && data.city ) {
                    content += '<tr><td>'+ualpJSObject.city+'</td><td>'+data.city+'</td></tr>';
                }
                if ( typeof(data.region) != "undefined" && data.region ) {
                    content += '<tr><td>'+ualpJSObject.region+'</td><td>'+data.region+'</td></tr>';
                }
                if ( typeof(data.country_name) != "undefined" && data.country_name ) {
                    content += '<tr><td>'+ualpJSObject.country+'</td><td>'+data.country_name+'</td></tr>';
                }
                content += '</table>';
                $('.ualp_ip_details_popup_content').html(content);
            });
        });
		jQuery( '#sbtDeactivationFormCloseual' ).click(
			function (event) {
				event.preventDefault();
				jQuery( "#TB_closeWindowButton" ).trigger( 'click' );
			}
		);

		jQuery( '.ual-deactivation' ).on(
			'click',
			function() {
				window.location.href = ual_plugin_admin.attr( 'href' );
			}
		);

		jQuery( 'script' ).each(
			function () {
				var src = jQuery( this ).attr( 'src' );
				if (typeof src !== typeof undefined && src !== false) {
					if (src.search( 'bootstrap.js' ) !== -1 || src.search( 'bootstrap.min.js' ) !== -1) {
						if (jQuery.fn.button.noConflict) {
							var bootstrapButton    = jQuery.fn.button.noConflict();
							jQuery.fn.bootstrapBtn = bootstrapButton;
						}
					}
				}
			}
		);
		jQuery( '#ualUserSettings .ual-check-user input' ).click(
			function () {
				jQuery( '.ual-overlay' ).show();
				var type     = 'role';
				var value    = '';
				var selected = 'false';
				type         = jQuery( '.user_role' ).val();
				value        = jQuery( this ).val();
				if (jQuery( this ).is( ':checked' )) {
					selected = 'true';
				} else {
					selected = 'false';
				}
				jQuery.ajax(
					{
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'ual_enable_user_for_notification',
							type: type,
							value: value,
							selected: selected,
							nonce: ualpJSObject.ajax_nonce
						},
						success: function (data) {
							// console.log( data );
							jQuery( '.ual-overlay' ).hide();
						},
					}
				);
			}
		);

		if (jQuery( 'form.sol-form input[name="emailEnable"]:checked' ).val() == 0) {
			jQuery( 'form.sol-form .ui-button.ui-corner-right' ).addClass( 'active' );
			jQuery( 'form.sol-form .ui-button.ui-corner-left' ).removeClass( 'active' );
		} else {
			jQuery( 'form.sol-form .ui-button.ui-corner-left' ).addClass( 'active' );
			jQuery( 'form.sol-form .ui-button.ui-corner-right' ).removeClass( 'active' );
		}

		jQuery( 'form.sol-form input[name="emailEnable"]' ).click(
			function () {
				if (jQuery( 'form.sol-form input[name="emailEnable"]:checked' ).val() == 0) {
					jQuery( 'form.sol-form .ui-button.ui-corner-right' ).addClass( 'active' );
					jQuery( 'form.sol-form .ui-button.ui-corner-left' ).removeClass( 'active' );
				} else {
					jQuery( 'form.sol-form .ui-button.ui-corner-left' ).addClass( 'active' );
					jQuery( 'form.sol-form .ui-button.ui-corner-right' ).removeClass( 'active' );
				}
			}
		);

		// settings tab script
		if (window.localStorage.getItem( "lasttab" ) == null ||
		(window.localStorage.getItem( "lasttab" ) != 'ualGeneralSettings' &&
			window.localStorage.getItem( "lasttab" ) != 'ualUserSettings' &&
			window.localStorage.getItem( "lasttab" ) != 'ualEmailSettings')) {
			jQuery( '.ualParentTabs .nav-tab-wrapper a.nav-tab' ).removeClass( 'nav-tab-active' );
			jQuery( '.ualParentTabs .nav-tab-wrapper a.nav-tab.ualUserSettings' ).addClass( 'nav-tab-active' );
			jQuery( '.ualpContentDiv' ).hide();
			jQuery( '#ualUserSettings.ualpContentDiv' ).show();
			jQuery( '#ualUserSettings.ualpContentDiv' ).css( 'display', 'block' );
		} else {
			jQuery( '.ualParentTabs .nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
			jQuery( '.' + window.localStorage.getItem( "lasttab" ) ).addClass( 'nav-tab-active' );
			jQuery( '.ualpContentDiv' ).hide();
			jQuery( '#' + window.localStorage.getItem( "lasttab" ) ).css( 'display', 'block' );
			jQuery( '.ualpContentDiv#' + window.localStorage.getItem( "lasttab" ) ).show();
		}
		jQuery( '.ualParentTabs .nav-tab-wrapper a' ).not( ".ual-pro-feature" ).click(
			function (e) {
				e.preventDefault();
				jQuery( '.ualpAdminNotice.is-dismissible' ).hide();
				var this_tab  = jQuery( this );
				var data_href = jQuery( this ).attr( 'data-href' );
				jQuery( '.ualpContentDiv' ).hide();
				jQuery( '#' + data_href ).show();
				jQuery( '.nav-tab-wrapper a.nav-tab' ).removeClass( 'nav-tab-active' );
				this_tab.addClass( 'nav-tab-active' );
				if (window.localStorage) {
					window.localStorage.setItem( "lasttab", data_href );
				}
			}
		);

		// Enable email notification start
		if (jQuery( '.sol-email-table input[name="emailEnable"]:checked' ).val() == 0) {
			jQuery( '.sol-email-table .fromEmailTr,.sol-email-table .toEmailTr,.sol-email-table .messageTr' ).hide();
		} else {
			jQuery( '.sol-email-table .fromEmailTr,.sol-email-table .toEmailTr,.sol-email-table .messageTr' ).show();
		}
		jQuery( '.sol-email-table input[name="emailEnable"]' ).click(
			function () {
				if (jQuery( '.sol-email-table input[name="emailEnable"]:checked' ).val() == 0) {
					jQuery( '.sol-email-table .fromEmailTr,.sol-email-table .toEmailTr,.sol-email-table .messageTr' ).hide();
				} else {
					jQuery( '.sol-email-table .fromEmailTr,.sol-email-table .toEmailTr,.sol-email-table .messageTr' ).show();
				}
			}
		);
		// Enable email notification end

		jQuery( '.ual-pro-feature' ).on(
			'click',
			function (e) {
				e.preventDefault();
				jQuery( "#ual-advertisement-popup" ).dialog(
					{
						resizable: false,
						draggable: false,
						modal: true,
						height: "auto",
						width: 'auto',
						maxWidth: '100%',
						dialogClass: 'ual-advertisement-ui-dialog',
						buttons: [
						{
							text: 'x',
							"class": 'ual-btn ual-btn-gray',
							click: function () {
								jQuery( this ).dialog( "close" );
							}
						}
						],
						open: function (event, ui) {
							jQuery( this ).parent().children( '.ui-dialog-titlebar' ).hide();
							jQuery( '.ui-widget-overlay' ).bind(
								'click',
								function () {
									jQuery( "#ual-advertisement-popup" ).dialog( 'close' );
								}
							);
						},
						hide: {
							effect: "fadeOut",
							duration: 500
						},
						close: function (event, ui) {
							jQuery( "#ual-advertisement-popup" ).dialog( 'close' );
						},
					}
				);
			}
		);

		jQuery('select[name="logs_failed_login"]').on('change', function() {
			if( 'no' == jQuery(this).val() ) {
				jQuery('.no_of_failed_login').hide();
			} else {
				jQuery('.no_of_failed_login').show();
			}
		});
		if( 'no' == jQuery('select[name="logs_failed_login"]').val() ) {
			jQuery('.no_of_failed_login').hide();
		} else {
			jQuery('.no_of_failed_login').show();
		}
		jQuery('input[name="ualAllowIp"]').on('change', function() {
			if ( jQuery(this).is(":checked") ) {
				jQuery('.ual_get_ips').show();
			} else {
				jQuery('.ual_get_ips').hide();
			}
		});
		if( jQuery('input[name="ualAllowIp"]').is(":checked") ) {
			jQuery('.ual_get_ips').show();
		} else {
			jQuery('.ual_get_ips').hide();
		}
	}
);

function ual_show_hide_permission() {
	jQuery( '.ual_permission_cover' ).slideToggle();
}

function ual_submit_optin(options) {
	result        = {};
	result.action = 'ual_submit_optin';
	result.email  = jQuery( '#ual_admin_email' ).val();
	result.type   = options;
	result.nonce = ualpJSObject.ajax_nonce;

	if (options == 'submit') {
		if (jQuery( 'input#ual_agree_gdpr' ).is( ':checked' )) {
			jQuery.ajax(
				{
					url: ajaxurl,
					type: 'POST',
					data: result,
					error: function () { },
					success: function () {
						window.location.href = "admin.php?page=user_action_log";
					},
					complete: function () {
						window.location.href = "admin.php?page=user_action_log";
					}
				}
			);
		} else {
			jQuery( '.ual_agree_gdpr_lbl' ).css( 'color', '#ff0000' );
		}
	} else if (options == 'deactivate') {
		if (jQuery( 'input#ual_agree_gdpr_deactivate' ).is( ':checked' )) {
			var ual_plugin_admin            = jQuery( '.documentation_ual_plugin' ).closest( 'div' ).find( '.deactivate' ).find( 'a' );
			result.selected_option_de       = jQuery( 'input[name=sol_deactivation_reasons_ual]:checked', '#frmDeactivationual' ).val();
			result.selected_option_de_id    = jQuery( 'input[name=sol_deactivation_reasons_ual]:checked', '#frmDeactivationual' ).attr( "id" );
			result.selected_option_de_text  = jQuery( "label[for='" + result.selected_option_de_id + "']" ).text();
			result.selected_option_de_other = jQuery( '.sol_deactivation_reason_other_ual' ).val();
			jQuery.ajax(
				{
					url: ajaxurl,
					type: 'POST',
					data: result,
					error: function () { },
					success: function () {
						window.location.href = ual_plugin_admin.attr( 'href' );
					},
					complete: function () {
						window.location.href = ual_plugin_admin.attr( 'href' );
					}
				}
			);
		} else {
			jQuery( '.ual_agree_gdpr_lbl' ).css( 'color', '#ff0000' );
		}
	} else {
		jQuery.ajax(
			{
				url: ajaxurl,
				type: 'POST',
				data: result,
				error: function () { },
				success: function () {
					window.location.href = "admin.php?page=user_action_log";
				},
				complete: function () {
					window.location.href = "admin.php?page=user_action_log";
				}
			}
		);
	}
}

function change_thickbox_size_ual() {
	jQuery( document ).find( '#TB_window' ).width( '700' ).height( 'auto' ).css( 'margin-left', -700 / 2 );
	jQuery( document ).find( '#TB_ajaxContent' ).width( '640' ).height( 'auto' );
	var doc_height = jQuery( window ).height();
	var doc_space  = doc_height - 500;
	if (doc_space > 0) {
		jQuery( document ).find( '#TB_window' ).css( 'margin-top', doc_space / 2 );
	}
}

function checkOtherDeactivate() {
	var selected_option_de = jQuery( 'input[name=sol_deactivation_reasons_ual]:checked', '#frmDeactivationual' ).val();
	if (selected_option_de == '7') {
		jQuery( '.sol_deactivation_reason_other_ual' ).val( '' );
		jQuery( '.sol_deactivation_reason_other_ual' ).show();
	} else {
		jQuery( '.sol_deactivation_reason_other_ual' ).val( '' );
		jQuery( '.sol_deactivation_reason_other_ual' ).hide();
	}
}

jQuery( window ).resize(
	function (){
		change_thickbox_size_ual();
		jQuery( document ).find( '#TB_ajaxContent' ).width( '640' ).height( 'calc(100% - 50px)' );
	}
);
