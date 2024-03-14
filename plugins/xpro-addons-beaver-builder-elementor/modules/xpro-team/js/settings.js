(function($){
	"use strict";
	
	var enable_social_icons_global = '',
		team_style_global = '';
	
	FLBuilder.registerModuleHelper('tnit-team-carousel', {

		init: function()
		{
			var form    	= $('.fl-builder-settings'),
				team_style	= form.find('select[name=team_style]'),
				enable_social_icons	= form.find('select[name=enable_social_icons]'),
				arrows_position 	= form.find('select[name=arrows_position]');
			
			team_style_global = team_style.val();
			enable_social_icons_global = enable_social_icons.val();
			
			// Init validation events.
			this._toggleBorderOptions();
			this._toggleSocialIcons();
			this._arrows_positionChanged();

			team_style.on('change', $.proxy( this._toggleBorderOptions, this ) );
			enable_social_icons.on('change', $.proxy( this._toggleSocialIcons, this ) );
			arrows_position.on('change', $.proxy( this._arrows_positionChanged, this ) );
		},
		
		_toggleBorderOptions: function() {
			var form		 = $('.fl-builder-settings'),
				team_style 	 = form.find('select[name=team_style]').val();
			
			team_style_global = team_style;
			
			if( team_style == '5'  ) 
			{
				form.find('#fl-field-icon_border').show();
				form.find('#fl-field-icon_border_hover_color').show();
			}
			
			if( team_style === '7'  ) 
			{
				form.find('#fl-field-items_number').hide();
				form.find('#fl-field-items_spacing').hide();
				form.find('#fl-field-img_margin_top').hide();
				form.find('#fl-field-img_margin_bottom').hide();
			}
		},
		
		_toggleSocialIcons: function() {
			var form		 		= $('.fl-builder-settings'),
				enable_social_icons = form.find('select[name=enable_social_icons]').val();

			enable_social_icons_global = enable_social_icons;
			
		},

		_arrows_positionChanged: function() {

			var form        		= $('.fl-builder-settings'),
				arrows_position   	= form.find('select[name=arrows_position]').val();
			
			if ( arrows_position === 'left-right' ) {
				// Add arrows offset placeholder
				form.find('#fl-field-arrows_offset .tnit_arrows_offset').attr('placeholder','-80');
			}
			else{
				// Add arrows offset placeholder
				form.find('#fl-field-arrows_offset .tnit_arrows_offset').attr('placeholder','-30');
			}
			
		},
		
	});
	
	FLBuilder.registerModuleHelper('tnit_team_member_form', {

        init: function()
        {
            var form = $('.fl-builder-settings');
			
			if (team_style_global === '7')
			{
				form.find('#fl-field-photo_position').show();
			} else {
				form.find('#fl-field-photo_position').hide();
			}
			
            if (enable_social_icons_global === 'yes')
			{
            	form.find('a[href="#fl-builder-settings-tab-social_links"]').show();
            } else {
            	form.find('a[href="#fl-builder-settings-tab-social_links"]').hide();
            }
        }

    });

})(jQuery);