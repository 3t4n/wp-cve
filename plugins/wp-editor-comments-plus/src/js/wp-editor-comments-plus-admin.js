'use strict';

var
	wpecp = window.wpecp || {},
	wpecpGlobals = window.wpecpGlobals || {},
	$ = jQuery
;

// console shortcut for debugging
if ( window.console && window.console.log && window.console.log.bind ) { window.cl = console.log.bind( console ); }

wpecp.initAdmin = function() {

	if ( typeof wpecpGlobals != 'undefined' ) {
		wpecpGlobals = JSON.parse( wpecpGlobals );
	}

	wpecp.ajaxModel = Backbone.Model.extend({
		defaults: {
			action: '',
			security: '',
			content: ''
		}
	});


	wpecp.ajaxSaveOption = function( data ) {
		return $.ajax({
			url: wpecpGlobals.ajaxUrl,
			type: 'post',
			data: data
		});
	};


	// Admin Confirmation Notices

	wpecp.confirmationSaving = function( view ) {
		view.$confirmed
			.find( '.dashicons' )
			.attr( 'class', 'dashicons dashicons-update' );
		view.$confirmed
			.removeClass( 'fade' )
			.addClass( 'saving' )
			.find( '.message' )
			.text( 'Saving...' );
		view.$confirmed.show();
	}

	wpecp.confirmationFail = function( view ) {
		view.$confirmed
			.find( '.dashicons' )
			.attr( 'class', 'dashicons dashicons-no' );
		view.$confirmed
			.addClass( 'fail' )
			.find( '.message' )
			.text( 'Failed to Save!' );
		view.$confirmed.show();
		wpecp.confirmationFade( view );
	}

	wpecp.confirmationDone = function( view ) {
		view.$confirmed
			.find( '.dashicons' )
			.attr( 'class', 'dashicons dashicons-yes' );
		view.$confirmed
			.removeClass( 'saving' )
			.find( '.message' )
			.text( view.confirmationMessage );
		view.$confirmed.show();
		wpecp.confirmationFade( view );
	}

	wpecp.confirmationFade = function( view ) {
		clearTimeout( view.fadeAnimation );
		clearTimeout( view.hideAnimation );
		view.fadeAnimation = setTimeout( function(){
			view.$confirmed.addClass('fade');
		}, wpecpGlobals.optionConfirmationDelay );
		view.hideAnimation = setTimeout( function(){
			view.$confirmed.hide();
			// add 1000ms to optionConfirmationDelay for fade animation
		}, wpecpGlobals.optionConfirmationDelay + 1000 );
	}

	wpecp.validInputKey = function( key ) {

		switch ( key ) {
			// skip non input keys
			case 9: // tab
			case 13: // enter
			case 16: // shift
			case 17: // ctrl
			case 18: // alt
			case 19: // pause/break
			case 27: // escape
			// page up, page down, end, home, left/up/right/down arrows, insert
			case ( key >= 33 && key <= 45 ):
			// windows keys, select key
			case ( key >= 91 && key <= 93 ):
			// function keys F1-F12
			case ( key >= 112 && key <= 123 ):
			// num & scroll lock
			case ( key >= 144 && key <= 145 ):
				return false;
			break;

			default:
				return true;
			break;
		}
	}

	wpecp.validHtmlClassKey = function( key ) {
		return key.replace( /([^0-9a-z-_ ])+/gi ) ? true : false;
	}

	wpecp.sanitizeHtmlClass = function( string ) {
		return string.replace( /([^0-9a-z-_ ])+/gi, '' );
	}

	wpecp.validHtmlIdKey = function( key ) {
		return key.replace( /([^0-9a-z-_.# ])+/gi ) ? true : false;
	}

	wpecp.sanitizeHtmlId = function( string ) {
		return string.replace( /([^0-9a-z-_.# ])+/gi, '' );
	}


	// Admin Views

	wpecp.editingEnabled = Backbone.View.extend({
		events: {
			'click input[type="checkbox"]': 'editingEnabled',
			'click label': 'editingEnabled'
		},

		editingEnabled: function( event ) {
			var that = this;
			this.$input = this.$el.find( 'input[type=checkbox]' );
			this.$label = this.$el.find( 'label' );
			this.nonce = this.$input.data( 'wpecp-nc' );

			// Toggle check box if click element is label
			if ( event.currentTarget == this.$label[0] ) {
				this.$input.prop( 'checked', ( this.$input.is( ':checked' ) ? false : true ) );
			}

			this.checkValue = ( this.$input.is( ':checked' ) ? 'on' : 'off' );

			this.model.set( 'security', this.nonce );
			this.model.set( 'action', wpecpGlobals.editingEnabledAction );
			this.model.set( 'content', this.checkValue );

			wpecp.ajaxSaveOption( this.model.toJSON() )
			.done( function( response ) {
				that.$label.text( ( that.$input.is( ':checked' ) ? 'Enabled' : 'Disabled' ) );
			});
		}
	});


	wpecp.adjustExpiration = Backbone.View.extend({
		events: {
			'change input': 'changeExpiration'
		},

		initialize: function() {
			var that = this;

			this.$expiration = this.$el.find( '.expiration-control' );
			this.$days = this.$el.find('.days > input');
			this.$hours = this.$el.find('.hours > input');
			this.$minutes = this.$el.find('.minutes > input');
			this.$seconds = this.$el.find('.seconds > input');
			this.$confirmed = this.$el.find( '.confirmed' );
			this.nonce = this.$expiration.data( 'wpecp-nc' );
			this.timeoutUpdate = false;

			this.$days.spinner({
				min: 0,
				spin: function( event, ui ) {
					event.target.value = ui.value;
					that.changeExpiration( event );
				}
			});

			this.$hours.spinner({
				spin: function ( event, ui ) {
					if (ui.value >= 24) {
						that.$hours.spinner('value', ui.value - 24);
						that.$days.spinner('stepUp');
						return false;
					} else if (ui.value < 0) {
						that.$hours.spinner('value', ui.value + 24);
						that.$days.spinner('stepDown');
						return false;
					}
					event.target.value = ui.value;
					that.changeExpiration( event );
      	}
			});

			this.$minutes.spinner({
				spin: function ( event, ui ) {
					if (ui.value >= 60) {
						that.$minutes.spinner('value', ui.value - 60);
						that.$hours.spinner('stepUp');
						return false;
					} else if (ui.value < 0) {
						that.$minutes.spinner('value', ui.value + 60);
						that.$hours.spinner('stepDown');
						return false;
					}
					event.target.value = ui.value;
					that.changeExpiration( event );
      	}
			});

			this.$seconds.spinner({
				spin: function ( event, ui ) {
					if (ui.value >= 60) {
						that.$seconds.spinner('value', ui.value - 60);
						that.$minutes.spinner('stepUp');
						return false;
					} else if (ui.value < 0) {
						that.$seconds.spinner('value', ui.value + 60);
						that.$minutes.spinner('stepDown');
						return false;
					}
					event.target.value = ui.value;
					that.changeExpiration( event );
				}
      });
		},

		updateExpirationValues: function() {
			this.days = parseInt( this.$days.spinner('value') )  * 24 * 60 * 60;
			this.hours = parseInt( this.$hours.spinner('value') ) * 60 * 60;
			this.minutes = parseInt( this.$minutes.spinner('value') ) * 60;
			this.seconds = parseInt( this.$seconds.spinner('value') );
		},

		changeExpiration: function( event ) {
			this.updateExpirationValues();

			switch( event.target.name ) {
				case 'days': this.days = parseInt( event.target.value ) * 24 * 60 * 60; break;
				case 'hours': this.hours = parseInt( event.target.value ) * 60 * 60; break;
				case 'minutes': this.minutes = parseInt( event.target.value ) * 60; break;
				case 'seconds': this.seconds = parseInt( event.target.value ); break;
			}

			this.expiration = Math.floor( this.days + this.hours + this.minutes + this.seconds );

			this.updateExpiration();
		},

		updateExpiration: function() {
			var that = this;
			this.model.set( 'security', this.nonce );
			this.model.set( 'action', wpecpGlobals.editingExpirationAction );
			this.model.set( 'content', this.expiration );

			clearTimeout( this.timeoutUpdate );

			this.timeoutUpdate = setTimeout( function(){
				wpecp.confirmationSaving( that );
				wpecp.ajaxSaveOption( that.model.toJSON() )
				.fail(function( data ) {
					wpecp.confirmationFail( that );
				})
				.done(function( data ) {
					that.confirmationMessage = 'Editing Period Saved';
					wpecp.confirmationDone( that );
				});
				clearTimeout( that.timeoutUpdate );
			}, wpecpGlobals.optionUpdateDelay );
		}
	});

	wpecp.customToolbars = Backbone.View.extend({
		events: {
			'keyup': 'updateToolbars'
		},

		initialize: function() {
			this.$box = this.$el.find( '.box' );
			this.$confirmed = this.$box.find( '.confirmed' );
			this.nonce = this.$box.data( 'wpecp-nc' );
			this.listOpen = ( this.$box.is( ':visible' ) ? 'yes' : 'no' );
		},

		updateToolbars: function( event ) {
			var
				that = this,
				charCode = (typeof event.which == "number") ? event.which : event.keyCode,
				keyChar = String.fromCharCode( charCode )
			;

			// validate input key and character input
			if ( ! wpecp.validInputKey( event.which ) ||
					 ! wpecp.validHtmlClassKey( keyChar ) ) {
				event.preventDefault();
				return false;
			}

			this.content = {};
			this.$inputs = this.$el.find( 'input[type=text]' );
			$.each( this.$inputs, function( key, input ){
				let field = $( input ).data( 'wpecp-field' );
				// sanitize input data
				that.content[ field ] = wpecp.sanitizeHtmlClass( input.value );
			});

			this.model.set( 'security', this.nonce );
			this.model.set( 'action', wpecpGlobals.customToolbarsAction );
			this.model.set( 'content', this.content );

			clearTimeout( this.timeoutUpdate );

			this.timeoutUpdate = setTimeout( function(){
				wpecp.confirmationSaving( that );
				wpecp.ajaxSaveOption( that.model.toJSON() )
				.fail(function( data ) {
					wpecp.confirmationFail( that );
				})
				.done(function( data ) {
					that.confirmationMessage = 'Toolbar layouts Saved';
					wpecp.confirmationDone( that );
				});
				clearTimeout( that.timeoutUpdate );
			}, wpecpGlobals.optionUpdateDelay );
		}
	});

	wpecp.customClasses = Backbone.View.extend({
		events: {
			'keyup': 'handleKeypress'
		},

		initialize: function() {
			this.$box = this.$el.find( '.box' );
			this.$confirmed = this.$box.find( '.confirmed' );
			this.nonce = this.$box.data( 'wpecp-nc' );
			this.timeoutUpdate = false;
		},

		handleKeypress: function( event ) {
			var
				that = this,
				charCode = (typeof event.which == "number") ? event.which : event.keyCode,
				keyChar = String.fromCharCode( charCode )
			;

			// validate input key and character input
			if ( ! wpecp.validInputKey( event.which ) ||
					 ! wpecp.validHtmlClassKey( keyChar ) ) {
				event.preventDefault();
				return false;
			}

			this.content = {};
			this.$inputs = this.$el.find( 'input[type=text]' );
			$.each( this.$inputs, function( key, input ){
				let field = $( input ).data( 'wpecp-field' );
				// sanitize input data
				that.content[ field ] = wpecp.sanitizeHtmlClass( input.value );
			});

			this.model.set( 'security', this.nonce );
			this.model.set( 'action', wpecpGlobals.customClassesAction );
			this.model.set( 'content', this.content );

			clearTimeout( this.timeoutUpdate );

			this.timeoutUpdate = setTimeout( function(){
				wpecp.confirmationSaving( that );
				wpecp.ajaxSaveOption( that.model.toJSON() )
				.fail(function( data ) {
					wpecp.confirmationFail( that );
				})
				.done(function( data ) {
					that.confirmationMessage = 'CSS Classes Saved';
					wpecp.confirmationDone( that );
				});
				clearTimeout( that.timeoutUpdate );
			}, wpecpGlobals.optionUpdateDelay );
		}
	});

	wpecp.wordpressIds = Backbone.View.extend({
		events: {
			'keyup': 'updateIDs'
		},

		initialize: function() {
			this.$box = this.$el.find( '.box' );
			this.$confirmed = this.$box.find( '.confirmed' );
			this.nonce = this.$box.data( 'wpecp-nc' );
			this.listOpen = ( this.$box.is( ':visible' ) ? 'yes' : 'no' );
		},

		updateIDs: function( event ) {
			var
				that = this,
				charCode = (typeof event.which == "number") ? event.which : event.keyCode,
				keyChar = String.fromCharCode( charCode )
			;

			// validate input key and character input
			if ( ! wpecp.validInputKey( event.which ) ||
					 ! wpecp.validHtmlIdKey( keyChar ) ) {
				event.preventDefault();
				return false;
			}

			this.content = {};
			this.$inputs = this.$el.find( 'input[type=text]' );
			$.each( this.$inputs, function( key, input ){
				let field = $( input ).data( 'wpecp-field' );
				// sanitize input data
				that.content[ field ] = wpecp.sanitizeHtmlId( input.value );
			});

			this.model.set( 'security', this.nonce );
			this.model.set( 'action', wpecpGlobals.wordpressIdsAction );
			this.model.set( 'content', this.content );

			clearTimeout( this.timeoutUpdate );

			this.timeoutUpdate = setTimeout( function(){
				wpecp.confirmationSaving( that );
				wpecp.ajaxSaveOption( that.model.toJSON() )
				.fail(function( data ) {
					wpecp.confirmationFail( that );
				})
				.done(function( data ) {
					that.confirmationMessage = 'WordPress IDs & Classes Saved';
					wpecp.confirmationDone( that );
				});
				clearTimeout( that.timeoutUpdate );
			}, wpecpGlobals.optionUpdateDelay );
		}
	});

	new wpecp.editingEnabled({
		el: $( '.wpecp-option .comment-editing' ),
		model: new wpecp.ajaxModel
	});

	new wpecp.adjustExpiration({
		el: $( '.wpecp-option .comment-expiration' ),
		model: new wpecp.ajaxModel
	});

	new wpecp.customClasses({
		el: $( '.wpecp-option .custom-classes' ),
		model: new wpecp.ajaxModel
	});

	new wpecp.wordpressIds({
		el: $( '.wpecp-option .wordpress-ids' ),
		model: new wpecp.ajaxModel
	});

	new wpecp.customToolbars({
		el: $( '.wpecp-option .custom-toolbars' ),
		model: new wpecp.ajaxModel
	});

};

( function( $ ){
	$( function() {
		if ( $( '.wpecp-settings' ).length ) {
			wpecp.initAdmin();
		}
	});
}( jQuery ) );
