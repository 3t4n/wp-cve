(function( $ ) {
	
	/**
	 * JavaScript class for working with third party services.
	 *
	 * @since 0.1.3
	 */
	var SJEaServices = {
		
		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		init: function()
		{
			var body = $('body');
			
			// Standard Events
			body.delegate( '.sjea-service-select', 'change', this._serviceChange );
			body.delegate( '.sjea-service-connect-button', 'click', this._connectClicked );
			body.delegate( '.sjea-service-account-select', 'change', this._accountChange );
			body.delegate( '.sjea-service-account-delete', 'click', this._accountDeleteClicked );
			body.delegate( '.sjea-service-list-select', 'change', this._accountListChange );

			// Campaign Events
			body.delegate( '.sjea-save-campaign', 'click', this._campaignSaveClicked );
			body.delegate( '.sjea-delete-campaign', 'click', this._campaignDeleteClicked );

			// MailChimp Events
			body.delegate( '.sjea-mailchimp-list-select', 'change', this._mailChimpListChange );
		},

		/**
		 * AJAX call to services.
		 *
		 * @param {Object} args Arguments to AJAX call.
		 * @param func: Callback function name.
		 * @return void
		 * @since 0.1.3
		 */
		ajaxCall: function( args, func ) {

			$.ajax( {
				data: args,
				action: args.action,
				url: sjea.ajaxurl,
				success: func,
				method: 'post',
				success  : func
			});

		},
		
		/**
		 * Show the lightbox loading graphic and remove errors.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_startSettingsLoading: function()
		{
			console.log( 'Loader Start' );
			var loader 	= $( '.sjea-loader' ),
				error 	= $( '.sjea-service-error' );
			
			loader.removeClass( 'sjea-hidden' );
			error.remove();
		},
		
		/**
		 * Remove the lightbox loading graphic.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_finishSettingsLoading: function()
		{
			console.log( 'Loader End' );
			var loader 	= $( '.sjea-loader' );
			
			loader.addClass( 'sjea-hidden' );
		},
		
		/**
		 * Fires when the service select changes.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_serviceChange: function()
		{
			var select      = $( this ),
				selectRow   = select.closest( '.sjea-field-wrap' ),
				service     = select.val();
			
			selectRow.siblings( 'div.sjea-service-account-row' ).remove();
			selectRow.siblings( 'div.sjea-service-connect-row' ).remove();
			selectRow.siblings( 'div.sjea-service-field-row' ).remove();
			$( '.sjea-service-error' ).remove();
				
			if ( '' === service ) {
				return;
			}
			
			SJEaServices._startSettingsLoading();
			
			SJEaServices.ajaxCall( {
				action  : 'render_service_settings',
				service : service
			}, SJEaServices._serviceChangeComplete );
		},
		
		/**
		 * AJAX callback for when the service select changes.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 0.1.3
		 */
		_serviceChangeComplete: function( response )
		{	
			var data        = JSON.parse( response ),
				wrap        = $( '.sjea-new-connections' ),
				selectRow   = wrap.find( '.sjea-service-select-row' );
				
			selectRow.after( data.html );
			SJEaServices._finishSettingsLoading();
			SJEaServices._addAccountDelete( wrap );
		},
		
		/**
		 * Fires when the service connect button is clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_connectClicked: function()
		{
			var wrap            = $( this ).closest( '.sjea-new-connections' ),
				select          = wrap.find( '.sjea-service-select' ),
				connectRows     = wrap.find( '.sjea-service-connect-row' ),
				connectInputs   = wrap.find( '.sjea-service-connect-input' ),
				input           = null,
				name            = null,
				i               = 0,
				data            = {
					action          : 'connect_service',
					service         : select.val(),
					fields          : {}
				};
			
			for ( ; i < connectInputs.length; i++ ) {
				input                   = connectInputs.eq( i );
				name                    = input.attr( 'name' );
				data.fields[ name ]  	= input.val();
			}
			
			connectRows.hide();
			SJEaServices._startSettingsLoading();
			SJEaServices.ajaxCall( data, SJEaServices._connectComplete );
		},
		
		/**
		 * AJAX callback for when the service connect button is clicked.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 0.1.3
		 */
		_connectComplete: function( response )
		{
			var data        = JSON.parse( response ),
				wrap        = $( '.sjea-new-connections' ),
				selectRow   = wrap.find( '.sjea-service-select-row' ),
				select      = wrap.find( '.sjea-service-select' ),
				accountRow  = wrap.find( '.sjea-service-account-row' ),
				account     = wrap.find( '.sjea-service-account-select' ),
				connectRows = wrap.find( '.sjea-service-connect-row' );

			$("#btn2").click(function(){
        		$("ol").append("<li>Appended item</li>");
    		});
			
			if ( data.error ) {
				
				connectRows.show();

				errorBeforeWrap = wrap.find('.connection-configration');
				
				if ( 0 === account.length ) {
					errorBeforeWrap.before( '<div class="sjea-service-error">' + data.error + '</div>' );
				}
				else {
					errorBeforeWrap.before( '<div class="sjea-service-error">' + data.error + '</div>' );
				}
			}
			else {
				connectRows.remove();
				accountRow.remove();
				selectRow.after( data.html );
			}
			
			SJEaServices._addAccountDelete( wrap );
			SJEaServices._finishSettingsLoading();
		},
		
		/**
		 * Fires when the service account select changes.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_accountChange: function()
		{
			var wrap        = $( '.sjea-new-connections' ),
				select      = wrap.find( '.sjea-service-select' ),
				account     = wrap.find( '.sjea-service-account-select' ),
				connectRows = wrap.find( '.sjea-connect-row' ),
				fieldRows   = wrap.find( '.sjea-service-field-row' ),
				error       = $( '.sjea-service-error' ),
				value       = account.val(),
				data        = null;
			
			connectRows.remove();
			fieldRows.remove();
			error.remove();
			
			if ( 'add_new_account' == value ) {
				data = {
					action  : 'render_service_settings',
					service : select.val(),
					add_new : true
				};
			}
			else if ( '' !== value ) {
				data = {
					action  : 'render_service_fields',
					service : select.val(),
					account : value
				};
			}
			
			if ( data ) {
				SJEaServices._startSettingsLoading( select );
				SJEaServices.ajaxCall( data, SJEaServices._accountChangeComplete );
			}
			
			SJEaServices._addAccountDelete( wrap );
		},
		
		/**
		 * AJAX callback for when the service account select changes.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 0.1.3
		 */
		_accountChangeComplete: function( response )
		{
			console.log( 'account changes' )
			var data        = JSON.parse( response ),
				wrap        = $( '.sjea-new-connections' ),
				accountRow  = wrap.find( '.sjea-service-account-row' );
			
			accountRow.after( data.html );
			SJEaServices._finishSettingsLoading();
		},
		
		/**
		 * Adds an account delete link.
		 *
		 * @param {Object} wrap An element within the lightbox.
		 * @return void
		 * @since 0.1.3
		 */
		_addAccountDelete: function( wrap )
		{
			var account = wrap.find( '.sjea-service-account-select' );
			
			if ( account.length > 0 ) {
				
				wrap.find( '.sjea-service-account-delete' ).remove();
				
				if ( '' !== account.val() && 'add_new_account' != account.val() ) {
					account.after( '<a href="javascript:void(0);" class="sjea-service-account-delete"> Delete Account </a>' );
				}
			}
		},
		
		/**
		 * Fires when the account delete link is clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_accountDeleteClicked: function()
		{
			var wrap        = $( '.sjea-new-connections' ),
				select      = wrap.find( '.sjea-service-select' ),
				account     = wrap.find( '.sjea-service-account-select' );
			
			if ( confirm( 'Do you really want to delete account?' ) ) {
			
				SJEaServices.ajaxCall( {
					action  : 'delete_service_account',
					service : select.val(),
					account : account.val()
				}, SJEaServices._accountDeleteComplete );
				
				SJEaServices._startSettingsLoading( account );
			}
		},
		
		/**
		 * AJAX callback for when the account delete link is clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_accountDeleteComplete: function()
		{
			var wrap   = $( '.sjea-new-connections' ),
				select = wrap.find( '.sjea-service-select' );
				
			SJEaServices._finishSettingsLoading();
				
			select.trigger( 'change' );
		},
		
		/**
		 * Fires when the account list change.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_accountListChange: function()
		{
			var wrap        	= $( '.sjea-new-connections' ),
				listValue      	= wrap.find( '.sjea-service-list-select' ).val(),
				campaignBtnWrap = $( '.sjea-save-campaign-wrap' );
			
			if ( listValue == '' ) {
				campaignBtnWrap.addClass('sjea-hidden');
			}else{
				campaignBtnWrap.removeClass('sjea-hidden');
			}
		},

		/**
		 * Fires when the campaign save clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_campaignSaveClicked: function( e )
		{
			e.preventDefault();
			var wrap        = $( '.sjea-new-connections' ),
				name     	= wrap.find( '.sjea-campaign-input' ).val(),
				service     = wrap.find( '.sjea-service-select' ),
				account     = wrap.find( '.sjea-service-account-select' ),
				list      	= wrap.find( '.sjea-service-list-select' ),
				data		= {};
			
			if ( name == '' ) {

				errorBeforeWrap = wrap.find('.connection-configration');
				errorBeforeWrap.before( '<div class="sjea-service-error">Campaign name shouldn\'t be empty.</div>' );
				return;
			}

			data[service.attr('name')] 	= service.val();
			data[account.attr('name')] 	= account.val();
			data[list.attr('name')] 	= list.val();

			
			if ( service.val() == 'mailchimp' ) {
				
				group 	= wrap.find( '.sjea-mailchimp-group-select' );
				data[group.attr('name')] 	= group.val();
			}
			
			SJEaServices._startSettingsLoading();

			SJEaServices.ajaxCall( {
				action  : 'save_mailer_campaign',
				campaign_name : name,
				campaign_data : data
			}, SJEaServices._campaignSaveComplete );
			
		},
		
		/**
		 * AJAX callback for when the campaign save is clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_campaignSaveComplete: function( response )
		{
			console.log( response );

			var data        = JSON.parse( response ),
				wrap        = $( '.sjea-new-connections' );

			if ( data.error ) {
				
				errorBeforeWrap = wrap.find('.connection-configration');
				errorBeforeWrap.before( '<div class="sjea-service-error">' + data.error + '</div>' );
			}
			else {
				window.location.reload(true);
			}
			
			SJEaServices._finishSettingsLoading();
		},

		/**
		 * Fires when the campaign delete link is clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_campaignDeleteClicked: function(e)
		{
			e.preventDefault();
			console.log( $(this) );
			var campaign_name     = $(this).attr('data-campaign');
			
			if ( confirm( 'Do you really want to delete campaign? It will affect all linked form.' ) ) {
			
				SJEaServices.ajaxCall( {
					action  : 'delete_mailer_campaign',
					campaign_name : campaign_name
				}, SJEaServices._campaignDeleteComplete );
				
				// SJEaServices._startSettingsLoading();
			}
		},
		
		/**
		 * AJAX callback for when the account delete link is clicked.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_campaignDeleteComplete: function()
		{
			location.reload();
		},

		/* MailChimp
		----------------------------------------------------------*/
		
		/**
		 * Fires when the MailChimp list select is changed.
		 *
		 * @return void
		 * @since 0.1.3
		 */
		_mailChimpListChange: function()
		{
			var wrap        = $( '.sjea-new-connections' ),
				select      = wrap.find( '.sjea-service-select' ),
				account     = wrap.find( '.sjea-service-account-select' ),
				list        = wrap.find( '.sjea-service-list-select' );
			
			$( '.sjea-mailchimp-group-select' ).closest( 'div' ).remove();
			
			if ( '' === list.val() ) {
				return;
			}
			
			SJEaServices._startSettingsLoading( select );
			
			SJEaServices.ajaxCall( {
				action  : 'render_service_fields',
				service : select.val(),
				account : account.val(),
				list_id : list.val()
			}, SJEaServices._mailChimpListChangeComplete );
		},
		
		/**
		 * AJAX callback for when the MailChimp list select is changed.
		 *
		 * @param {String} response The JSON response.
		 * @return void
		 * @since 0.1.3
		 */
		_mailChimpListChangeComplete: function( response )
		{
			var data    = JSON.parse( response ),
				wrap    = $( '.sjea-new-connections' ),
				list    = wrap.find( '.sjea-service-list-select' );
			
			list.closest( 'div' ).after( data.html );
			SJEaServices._finishSettingsLoading();
		},
	};

	$ ( function() {
		SJEaServices.init();
	});

})( jQuery );