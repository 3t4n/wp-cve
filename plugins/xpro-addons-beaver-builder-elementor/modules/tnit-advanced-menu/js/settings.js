(function($){

	FLBuilder.registerModuleHelper(
		'tnit-advanced-menu',
		{

			init: function()
		{

				var form = $( '.fl-builder-settings' ),
				tab      = $( '.fl-builder-tnit-advanced-menu-settings' ).find( '.fl-builder-settings-tabs a' );

				this.callbackRenderFunction();

				$( '.fl-builder-content' ).on( 'fl-builder.layout-rendered', this.callbackRenderFunction );
				tab.on( 'click', this._openSubmenu );

				$( '.tnit-responsive-preview' ).on( 'click',this._previewDropdown );
			},

			callbackRenderFunction: function() {
				var form = $( '.fl-builder-settings' ),
				id       = form.data( 'node' );

				if ( $( '.fl-active' ).attr( 'href' ) === '#fl-builder-settings-tab-submenu') {
					$( '.fl-node-' + id + ' .tnit-menu-has-child' ).first().addClass( 'open' );
				} else {
					$( '.fl-node-' + id + ' .tnit-menu-has-child' ).first().removeClass( 'open' );
				}
			},

			_openSubmenu: function() {
				var form   = $( '.fl-builder-settings' ),
				id         = form.data( 'node' ),
				anchorHref = $( this ).attr( 'href' );

				if ( anchorHref === '#fl-builder-settings-tab-submenu') {

					$( '.fl-node-' + id + ' .tnit-menu-has-child' ).first().addClass( 'open' );

				} else {
					$( '.fl-node-' + id + ' .tnit-menu-has-child' ).first().removeClass( 'open' );
				}

			},

			_previewDropdown: function (e){
				e.preventDefault();
				e.stopPropagation();

				var form              = $( '.fl-builder-settings' ),
				responsive_breakpoint = form.find( 'select[name=responsive_breakpoint]' ),
				layout                = form.find( 'select[name=responsive_layout]' );

				if (responsive_breakpoint.val() !== 'all') {
					FLBuilderResponsiveEditing._switchTo( responsive_breakpoint.val() );
					FLBuilderResponsiveEditing._switchAllSettingsToCurrentMode();
				}

				$( '.tnit-hamburger-layout-accordion .tnit-hamburger-menu-expand' ).show();
				$( '.tnit-advance-menu-wrapper' ).toggleClass( 'tnit-open-menu' );
				$( '.tnit-hamburger-menu-expand .tnit-advance-sub-menu' ).show();
			},

		}
	);

})( jQuery );
