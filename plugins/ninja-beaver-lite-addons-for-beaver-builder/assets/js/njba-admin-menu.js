(function($){
	
	/**
	 * Helper class for dealing with the builder's admin
	 * settings page.
	 *
	 * @class NJBAuilderAdminSettings
	 * @since 1.0
	 */
	NJBAuilderAdminSettings = {
		
		/**
		 * An instance of wp.media used for uploading icons.
		 *
		 * @since 1.4.6
		 * @access private
		 * @property {Object} _iconUploader
		 */
		_iconUploader: null,
	
		/**
		 * Initializes the builder's admin settings page.
		 *
		 * @since 1.0
		 * @method init
		 */ 
		init: function()
		{
			this._bind();
			this._initNav();
			
		},
		
		/**
		 * Binds events for the builder's admin settings page.
		 *
		 * @since 1.0
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$('.njba-admin-settings-section .njba-admin-settings-nav a').on('click', NJBAuilderAdminSettings._navClicked);
			
		},
	
		
		/**
		 * Initializes the nav for the builder's admin settings page.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initNav
		 */
		_initNav: function()
		{
			var links  = $('.njba-admin-settings-section .njba-admin-settings-nav a'),
				hash   = window.location.hash,
				active = hash === '' ? [] : links.filter('[href~="'+ hash +'"]');
				
				
			$('a.fl-active').removeClass('fl-active');
			$('.njba-admin-settings-section .fl-settings-form').hide();
				
			if(hash === '' || active.length === 0) {
				active = links.eq(0);
			}
			
			active.addClass('fl-active');
			$('.njba-admin-settings-section #fl-njba-'+ active.attr('href').split('#').pop() +'-form').fadeIn();
		},
		
		/**
		 * Fires when a nav item is clicked.
		 *
		 * @since 1.0
		 * @access private
		 * @method _navClicked
		 */
		_navClicked: function()
		{
			if($(this).attr('href').indexOf('#') > -1) {
				$('a.fl-active').removeClass('fl-active');
				$('.njba-admin-settings-section .fl-settings-form').hide();
				$(this).addClass('fl-active');
				//console.log(this)''
				$('.njba-admin-settings-section #fl-njba-'+ $(this).attr('href').split('#').pop() +'-form').fadeIn();
			}
		},
		
		
	};
	
	/* Initializes the builder's admin settings. */
	$(function(){
		NJBAuilderAdminSettings.init();
	});
	
})(jQuery);