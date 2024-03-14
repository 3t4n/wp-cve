(function($){
	"use strict";

	FLBuilder.registerModuleHelper('slides_items', {

		init: function()
		{
				var form        = $( '.fl-builder-settings' ),
					template_edit_button = form.find( '.xpro-template-edit-button' ),
					row_edit_button = form.find( '.xpro-row-edit-button' );

			template_edit_button.on( 'click', $.proxy( this._edit_template, this ) );
			row_edit_button.on( 'click', $.proxy( this._edit_row, this ) );

			},

			_edit_template: function(e) {
				e.preventDefault();
				var form        = $( '.fl-builder-settings' ),
					template_id = form.find( 'select[name=content_row]' ),
					home_url = form.find( '.xpro-template-edit-button' ).data('url'),
					slug = home_url + '?post_type=xpro_bb_templates&p=' + template_id.val()+'&fl_builder';
				if(template_id.val() !== 'no_template'){
						window.open(slug);
				}else{
					alert("Template Not Found");
				}
			},

			_edit_row: function(e) {
			e.preventDefault();
			var form        = $( '.fl-builder-settings' ),
				template_id = form.find( 'select[name=content_bb_row]' ),
				home_url = form.find( '.xpro-row-edit-button' ).data('url'),
				slug = home_url + '?post_type=fl-builder-template&p=' + template_id.val()+'&fl_builder';
			if(template_id.val() !== 'no_template'){
				window.open(slug);
			}else{
				alert("Template Not Found");
			}
		},

		}
	);

})( jQuery );
