import Swal from 'sweetalert2';

/**
 * Handles:
 * - Copy to Clipboard functionality
 * - Dismissable Notices
 *
 * @since 1.5.0
 */

(function($, window, document, envira_gallery_admin ) {

	let envira_notifications,
		envira_connect;
	window.envira_notifications = envira_notifications = {
		init() {
			var app = this;
			app.$drawer = $( '#envira-notifications-drawer' );
			app.find_elements();
			app.init_open();
			app.init_close();
			app.init_dismiss();
			app.init_view_switch();
			app.update_count( app.active_count );
		},

		should_init() {
			var app = this;
			return app.$drawer.length > 0;
		},
		find_elements() {
			var app = this;
			app.$open_button      = $( '#envira-notifications-button' );
			app.$count            = app.$drawer.find( '#envira-notifications-count' );
			app.$dismissed_count  = app.$drawer.find( '#envira-notifications-dismissed-count' );
			app.active_count      = app.$open_button.data( 'count' ) ? app.$open_button.data( 'count' ) : 0;
			app.dismissed_count   = app.$open_button.data( 'dismissed' );
			app.$body             = $( 'body' );
			app.$dismissed_button = $( '#envira-notifications-show-dismissed' );
			app.$active_button    = $( '#envira-notifications-show-active' );
			app.$active_list      = $( '.envira-notifications-list .envira-notifications-active' );
			app.$dismissed_list   = $( '.envira-notifications-list .envira-notifications-dismissed' );
			app.$dismiss_all      = $( '#envira-dismiss-all' );
		},
		update_count( count ) {
			var app = this;
			app.$open_button.data( 'count', count ).attr( 'data-count', count );
			if ( 0 === count ) {
				app.$open_button.removeAttr( 'data-count' );
			}
			app.$count.text( count );
			app.dismissed_count += Math.abs( count - app.active_count );
			app.active_count     = count;

			app.$dismissed_count.text( app.dismissed_count );

			if ( 0 === app.active_count ) {
				app.$dismiss_all.hide();
			}
		},
		init_open() {
			var app = this;
			app.$open_button.on(
				'click',
				function ( e ) {
					e.preventDefault();
					app.$body.addClass( 'envira-notifications-open' );
				}
			);
		},
		init_close() {

			var app = this;
			app.$body.on(
				'click',
				'.envira-notifications-close, .envira-notifications-overlay',
				function ( e ) {
					e.preventDefault();
					app.$body.removeClass( 'envira-notifications-open' );
				}
			);
		},
		init_dismiss() {
			var app = this;
			app.$drawer.on(
				'click',
				'.envira-notification-dismiss',
				function ( e ) {
					e.preventDefault();
					const id = $( this ).data( 'id' );
					app.dismiss_notification( id );
					if ( 'all' === id ) {
						app.move_to_dismissed( app.$active_list.find( 'li' ) );
						app.update_count( 0 );
						return;
					}
					app.move_to_dismissed( $( this ).closest( 'li' ) );
					app.update_count( app.active_count - 1 );
				}
			);
		},
		move_to_dismissed( element ) {
			var app = this;
			element.slideUp(
				function () {
					$( this ).prependTo( app.$dismissed_list ).show();
				}
			);
		},
		dismiss_notification( id ) {
			var app = this;
			return $.post(
				ajaxurl,
				{
					action: 'envira_notification_dismiss',
					nonce: envira_gallery_admin.dismiss_notification_nonce,
					id: id,
				}
			);
		},
		init_view_switch() {
			var app = this;
			app.$dismissed_button.on(
				'click',
				function ( e ) {
					e.preventDefault();
					app.$drawer.addClass( 'show-dismissed' );
				}
			);
			app.$active_button.on(
				'click',
				function ( e ) {
					e.preventDefault();
					app.$drawer.removeClass( 'show-dismissed' );
				}
			);
		}
	};

	window.envira_connect = envira_connect = {

		init() {
			$( this.ready() );
		},
		ready(){
			this.connectClicked();

		},
		connectClicked() {
			let app = this;
			$( '#envira-gallery-settings-connect-btn' ).on(
				'click',
				function (e) {
					e.preventDefault();
					app.gotoUpgradeUrl();
				}
			);
		},
		gotoUpgradeUrl() {
			let app = this;
			let data = {
				action: 'envira_connect', key: $( '#envira-settings-key' ).val(), _wpnonce: envira_gallery_admin.connect_nonce,
			};

			$.post( ajaxurl, data ).done(
				function ( res ) {
					if ( res.success ) {
						if ( res.data.reload ) {
							app.proAlreadyInstalled( res );
							return;
						}
						window.location.href = res.data.url;
						return;
					}

					Swal.fire(
						{
							title: envira_gallery_admin.oops,
							html: res.data.message,
							icon: 'warning',
							confirmButtonColor: '#3085d6',
							confirmButtonText: envira_gallery_admin.ok,
							customClass: {
								confirmButton: 'envira-button',
							},
						}
					);
				}
			).fail(
				function ( xhr ) {
					app.failAlert( xhr );
				}
			);
		},
		proAlreadyInstalled( res ) {
			Swal.fire(
				{
					title: envira_gallery_admin.almost_done,
					text: res.data.message,
					icon: 'success',
					confirmButtonColor: '#3085d6',
					confirmButtonText: envira_gallery_admin.plugin_activate_btn,
					customClass: {
						confirmButton: 'envira-button',
					},
				}
			).then(
				( result ) => {
					if ( result.isConfirmed ) {
						window.location.reload();
					}
				}
			);
		},
		failAlert() {
			Swal.fire(
				{
					title: envira_gallery_admin.oops,
					html: envira_gallery_admin.server_error + '<br>' + xhr.status + ' ' + xhr.statusText + ' ' + xhr.responseText,
					icon: 'warning',
					confirmButtonColor: '#3085d6',
					confirmButtonText: envira_gallery_admin.ok,
					customClass: {
						confirmButton: 'envira-button',
					},
				}
			);
		},
	}

	// DOM ready
	$(function() {
		envira_notifications.init();
		envira_connect.init();
		$('#screen-meta-links').prependTo('#envira-header-temp');
		$('#screen-meta').prependTo('#envira-header-temp');

		/**
		 * Copy to Clipboard
		 */
		if (typeof ClipboardJS !== 'undefined') {
			$(document).on('click', '.envira-clipboard', function (e) {
				var envira_clipboard = new ClipboardJS('.envira-clipboard');
				e.preventDefault();
			});
		}

		/**
		 * Dismissable Notices
		 * - Sends an AJAX request to mark the notice as dismissed
		 */
		$('div.envira-notice').on('click', '.notice-dismiss', function (e) {
			e.preventDefault();

			$(this).closest('div.envira-notice').fadeOut();

			// If this is a dismissible notice, it means we need to send an AJAX request
			if ($(this).hasClass('is-dismissible')) {
				$.post(
					envira_gallery_admin.ajax,
					{
						action: 'envira_gallery_ajax_dismiss_notice',
						nonce: envira_gallery_admin.dismiss_notice_nonce,
						notice: $(this).parent().data('notice'),
					},
					function (response) {},
					'json',
				);
			}
		});

		$('#envira-top-notification').on('click', '.envira-dismiss', function (e) {
			e.preventDefault();

			$(this).closest('div.envira-header-notification').fadeOut();
			$.post(
				envira_gallery_admin.ajax,
				{
					action: 'envira_gallery_ajax_dismiss_topbar',
					nonce: envira_gallery_admin.dismiss_topbar_nonce,
				},
				function (response) {},
				'json',
			);
		});

		let svg = '<svg xmlns="http://www.w3.org/2000/svg" width="33" height="23" fill="none" viewBox="0 0 33 23">' +
		'<path fill="#3871AC" d="M27.687 10.021a4.908 4.908 0 00.146-3.052 4.927 4.927 0 00-1.69-2.552 4.963 4.963 0 00-5.832-.244A8.188 8.188 0 0016.357.636a8.234 8.234 0 00-5.302-.379 8.203 8.203 0 00-4.421 2.937 8.142 8.142 0 00-1.684 5.02v.46a7.386 7.386 0 00-3.97 3.208 7.333 7.333 0 001.626 9.415A7.412 7.412 0 007.425 23H26.4c1.75 0 3.43-.692 4.667-1.925A6.557 6.557 0 0033 16.43c0-3.122-2.277-5.8-5.313-6.407zm-7.21 3.286l-.578.559a1.24 1.24 0 01-1.749-.066l-1.65-1.758v6.424c0 .723-.561 1.232-1.238 1.232h-.825a1.191 1.191 0 01-1.157-.753 1.178 1.178 0 01-.08-.48V12.06L11.5 13.8a1.24 1.24 0 01-1.749.05l-.577-.576c-.512-.492-.512-1.281 0-1.741l4.785-4.764a1.224 1.224 0 011.716 0l4.785 4.764c.528.46.528 1.232 0 1.741l.017.033z"></path>' +
	  	'</svg>';
		let colspan = $(".post-type-envira table > thead > tr:first > th").length + 1; // add for checkbox td?
		var $unlock = '<tr class="envira_tr"><td scope="col" colspan="'+ colspan +'" width="100%"><div>'+ svg + '<hgroup><h3>'+ envira_gallery_admin.unlock_title +'</h3><h5>'+ envira_gallery_admin.unlock_text +'</h5></hgroup><a href="' + envira_gallery_admin.unlock_url + '" class="button envira-button-blue" target="_blank">' + envira_gallery_admin.unlock_btn + '</a></div></td></tr>';
		$('.post-type-envira .wp-list-table tbody').append($unlock );

	});

})(jQuery, window, document, envira_gallery_admin );

