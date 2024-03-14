(function( $ ) {

	/**
	 * Enable/Disable usage stats helper.
	 *
	 * @since 1.3.9
	 */
	var NJBAUsage = {

		init: function() {
			NJBAUsage._fadeToggle()
			NJBAUsage._enableClick()
			NJBAUsage._disableClick()
		},
		_fadeToggle: function() {
			$( 'a.stats-info' ).click( function( e ) {
				e.preventDefault();
				$( '.stats-info-data' ).fadeToggle()
			})
		},
		_enableClick: function() {
			$( '.buttons span.enable-stats' ).click( function( e ) {

				nonce = $(this).closest('.buttons').find('#_wpnonce').val()

				data = {
					'action'  : 'njba_usage_toggle',
					'enable'  : 1,
					'_wpnonce': nonce
				}
				NJBAUsage._doAjax( data )
			})
		},
		_disableClick: function() {
			$( '.buttons span.disable-stats' ).click( function( e ) {

				nonce = $(this).closest('.buttons').find('#_wpnonce').val()

				data = {
					'action'  : 'njba_usage_toggle',
					'enable'  : 0,
					'_wpnonce': nonce
				}
				NJBAUsage._doAjax( data )
			})
		},
		_doAjax: function( data ) {
			$.post(ajaxurl, data, function(response) {
				NJBAUsage._close()
			});
		},

		_close: function() {
			$( '.njba-usage').closest('.notice').fadeToggle()
		}
	};

	$( function() {
		NJBAUsage.init();
	});

})( jQuery );
