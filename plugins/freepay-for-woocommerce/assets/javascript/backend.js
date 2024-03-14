( function( $ ) {
	"use strict";

	FreePay.prototype.init = function() {
		// Add event handlers
		this.actionBox.on( 'click', '[data-action]', $.proxy( this.callAction, this ) );
	};

	FreePay.prototype.callAction = function( e ) {
		e.preventDefault();
		var target = $( e.target );
		var action = target.attr( 'data-action' );

		if( typeof this[action] !== 'undefined' ) {
			var message = target.attr('data-confirm') || 'Are you sure you want to continue?';
			if( confirm( message ) ) {
				this[action]();	
			}
		}	
	};

	FreePay.prototype.capture = function() {
		var request = this.request( {
			freepay_action : 'capture'
		} );
	};

	FreePay.prototype.captureAmount = function () {
		var request = this.request({
			freepay_action: 'capture',
			freepay_amount: $('#fp-balance__amount-field').val()
		} );
	};

	FreePay.prototype.cancel = function() {
		var request = this.request( {
			freepay_action : 'cancel'
		} );
	};

	FreePay.prototype.refund = function() {
		var request = this.request( {
			freepay_action : 'refund'
		} );
	};

	FreePay.prototype.request = function( dataObject ) {
		var that = this;
		var request = $.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType: 'json',
			data : $.extend( {}, { action : 'freepay_manual_transaction_actions', freepay_postid : this.postID.val() }, dataObject ),
			beforeSend : $.proxy( this.showLoader, this, true ),
			success : function() {
				$.get( window.location.href, function( data ) {
					var newData = $(data).find( '#' + that.actionBox.attr( 'id' ) + ' .inside' ).html();
					that.actionBox.find( '.inside' ).html( newData );
					that.showLoader( false );
				} );
			},
			error : function(jqXHR, textStatus, errorThrown) {
				alert(jqXHR.responseText);
				that.showLoader( false );
			}
		} );

		return request;
	};

	FreePay.prototype.showLoader = function( e, show ) {
		if( show ) {
			this.actionBox.append( this.loaderBox );
		} else {
			this.actionBox.find( this.loaderBox ).remove();
		}
	};
    
	// DOM ready
	$(function() {
		new FreePay().init();

		function wcfpInsertAjaxResponseMessage(response) {
			if (response.hasOwnProperty('status') && response.status == 'success') {
				var message = $('<div id="message" class="updated"><p>' + response.message + '</p></div>');
				message.hide();
				message.insertBefore($('#wcfp_wiki'));
				message.fadeIn('fast', function () {
					setTimeout(function () {
						message.fadeOut('fast', function () {
							message.remove();
						});
					},5000);
				});
			}
		}

        var emptyLogsButton = $('#wcfp_logs_clear');
        emptyLogsButton.on('click', function(e) {
        	e.preventDefault();
        	emptyLogsButton.prop('disabled', true);
        	$.getJSON(ajaxurl, { action: 'freepay_empty_logs' }, function (response) {
				wcfpInsertAjaxResponseMessage(response);
				emptyLogsButton.prop('disabled', false);
        	});
        });

        var flushCacheButton = $('#wcfp_flush_cache');
		flushCacheButton.on('click', function(e) {
        	e.preventDefault();
			flushCacheButton.prop('disabled', true);
        	$.getJSON(ajaxurl, { action: 'freepay_flush_cache' }, function (response) {
				wcfpInsertAjaxResponseMessage(response);
				flushCacheButton.prop('disabled', false);
        	});
        });
	});

	function FreePay() {
		this.actionBox 	= $( '#freepay-payment-actions' );
		this.postID		= $( '#post_ID' );
		this.loaderBox 	= $( '<div class="loader"></div>');
	}
	
})(jQuery);