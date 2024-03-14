jQuery(document).ready(function($) {
	
	if( $("#toplevel_page_gf_edit_forms .auto-advance-for-gravity-forms.pricing.upgrade-mode").length > 0) {
		$("#toplevel_page_gf_edit_forms .auto-advance-for-gravity-forms.pricing.upgrade-mode").parent().attr("href", "https://multipagepro.com/").attr("target", "_blank");
	}
	
	
	if( $('.gfaa-cg-field').length ) {
		
		$('.gfaa-cg-field input').wpColorPicker();
	}
	
	
	function gfaa_perform_hide_show() {
		
		if( ! $('#gform-settings-section-animation-settings').length ) {
			return;
			
		}
		
		var _gform_setting_gfaa_type = $('[name=_gform_setting_gfaa_type]');
		var _gform_setting_enable_animation = $('input[name=_gform_setting_enable_animation]');
		var _gform_setting_enable_step_colors = $('input[name=_gform_setting_enable_step_colors]');
		var _gform_setting_enable_conversational = $('input[name=_gform_setting_enable_conversational]');
		
		/*if( _gform_setting_enable_conversational.val() == 1 ) {
			
			if( _gform_setting_enable_animation.val() == 0 ) {
				_gform_setting_enable_animation.prop('checked', true);
				_gform_setting_enable_animation.trigger('change');
			}
			$('#gform-settings-section-step-colors-settings').hide();
		}*/
		
		if( _gform_setting_enable_animation.val() == 1 ) {
			
			if( _gform_setting_enable_conversational.val() == 1 ) {
				$('#gform-settings-section-step-colors-settings').hide();
			}
			else {
				$('#gform-settings-section-step-colors-settings').show();
			}
			
		}
		
		/*if( _gform_setting_enable_animation.val() == 0 ) {
			$('#gform-settings-section-step-colors-settings').hide();
		}*/
		
		if( _gform_setting_gfaa_type.val() == 'basic' ) {
			$('#gform-settings-section-step-colors-settings').show();
			$('#gform-settings-section-conversational-form-settings').hide();
			$('#gform-settings-section-animation-settings').hide();
		}
		if( _gform_setting_gfaa_type.val() == 'animationed' ) {
			$('#gform-settings-section-step-colors-settings').show();
			$('#gform-settings-section-conversational-form-settings').hide();
			$('#gform-settings-section-animation-settings').show();
		}
		if( _gform_setting_gfaa_type.val() == 'conversational' ) {
			$('#gform-settings-section-step-colors-settings').hide();
			$('#gform-settings-section-conversational-form-settings').show();
			$('#gform-settings-section-animation-settings').show();
		}
		
		
		
		$connections = [ 
			'animation' 
		];
		$action = _gform_setting_enable_animation.val() == 1 ? 'show' : 'hide';
		gfa_hide_show_inner_elements( $connections, $action );
		
		
		$connections = [ 
			'active-step-color-fields', 'inactive-step-color-fields', 'completed-step-color-fields'
		];
		$action = _gform_setting_enable_step_colors.val() == 1 ? 'show' : 'hide';
		gfa_hide_show_inner_elements( $connections, $action );
		
		
		$connections = [ 
			'page', 'cover-image', 'logo-image', 'conversational-layout', 'side-image', 'intro_heading', 'intro_description', 'side-image', 'form_style'	
		];
		$action = _gform_setting_enable_conversational.val() == 1 ? 'show' : 'hide';
		gfa_hide_show_inner_elements( $connections, $action );
		
		if( _gform_setting_enable_conversational.val() == 1 && _gform_setting_gfaa_type.val() == 'conversational') {
			
			$connections = [ 
				'cover-image'
			];
			$action = $('[name=_gform_setting_conversational-layout]').val() == 'bgimage' ? 'show' : 'hide';
			console.log( $action );
			gfa_hide_show_inner_elements( $connections, $action );
			
			
			$connections = [ 
				'side-image'
			];
			$action = $('[name=_gform_setting_conversational-layout]').val() == 'leftimage' || $('[name=_gform_setting_conversational-layout]').val() == 'rightimage'  ? 'show' : 'hide';
			
			gfa_hide_show_inner_elements( $connections, $action );
		}
		
	
	}
	
	
	function gfa_hide_show_inner_elements( $connections, $action ) {
		$.each($connections, function(i, e) {
			
			if( $action == 'show' ) {
				$('#gform_setting_' + e ).show();
			}
			else {
				$('#gform_setting_' + e ).hide();
			}
		});
	}
	
	
	function aagf_check_premium_field(field) {
		
		var result = false;
		
		if( typeof aafg.prem != 'undefined' ) {
		
			prem = aafg.prem;
			
			if( typeof prem[ field.type ] != 'undefined' ) {
				
				available_types = prem[ field.type ];
				
				if( jQuery.isArray( available_types ) ) {
					jQuery.each( available_types, function(i, available_type) {
						if( field.inputType == available_type ) {
							result = true;
						}
					} );
				}
				else if( available_types == 'all' || available_types == 1 ) {
					result = true;
				}
			}
		}
		
		return result;
	}
	
	
	setTimeout(gfaa_perform_hide_show, 100);
	
	
	$(document).on(
		'change', 
		'#enable_animation, #enable_conversational, #enable_step_colors, #gform-settings-section-conversational-form-settings select, #gfaa_type', 
		function() {
		setTimeout(gfaa_perform_hide_show, 100);
	});
	
	
	$(document).on('click', '.gfaa-cg-heading', function() {
		var $this = $(this);
		var $parent = $this.parents('.gfaa-cg-wrap');
		$parent.find('.gfaa-cg-fields').slideToggle(300);
		setTimeout(function() { $parent.toggleClass('expanded'); }, 100);
	});
	
	
	gform.addAction("formEditorNullClick", function(e) {
		
		var $target = $(e.target);
		
		if( $target.hasClass('custom-button') ) {
			var custom_uploader = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				},
				multiple: false  // Set this to true to allow multiple files to be selected
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#gfac_image_url').val(attachment.url);
				
				SetFieldProperty('gfac_image_url', attachment.url);						
				
			}).open();
		}
	});
	
	
	$(document).on('click', '.custom-button-field', function(e) {
		
		e.preventDefault();
		var $this = $(this);
		var $parent = $this.parents('.gfaa-field-wrap');
		
		var custom_uploader = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		}).on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			
			$parent.find('.gfaa-field-text input').val(attachment.url);					
			
		}).open();
	});
	
	
	$(document).on('input propertychange', '.gfac_image_url', function(){
		SetFieldProperty('gfac_image_url', this.value);
	});
	
	
	$(document).bind("gform_load_field_settings", function(event, field, form){
		
		if( typeof field.gfac_image_url != 'undefined' ) {
			$('#gfac_image_url').val(field.gfac_image_url);
		}
		else {
			$('#gfac_image_url').val('');
		}
		
	});
	
	
	$(document).bind('gform_load_field_settings', function(event, field, form){
				
		$('#gfaa_tab_tab_toggle').show();
		$('.gfaa_image_setting.field_setting').show();
		$('.gfaa_hide_next_button.field_setting').show();
		$('.gfaa_hidePreviousButton.field_setting').show();
		
		
		$('#hide_next_button').prop('checked', field['hideNextButton'] == true);
		$('#hidePreviousButton').prop('checked', field['hidePreviousButton'] == true);
		$('#hideSubmitButton').prop('checked', field['hideSubmitButton'] == true);
		
		$('#field_list_value').prop('checked', field['autoAdvancedField'] == true);
		$('#inputNumberKeys').val(field['inputNumberKeys']);
		
		// console.log( aafg );
		if( typeof aafg.inputNumberKeys_selection_string != 'undefined' ) {
			if( field.type == 'checkbox' ) {
				var $change_string = aafg.inputNumberKeys_selection_string;
				$('.gfaa_inputNumberKeys > label').html($change_string);
			}
			else {
				var $change_string = aafg.inputNumberKeys_inputs_string;
				$('.gfaa_inputNumberKeys > label').html($change_string);
			}
		}
		
		var fields = ['radio', 'select', 'quiz', 'poll'];
		if ( $.inArray( field.type, fields) !== -1) { 
			$('.gfaa_inputNumberKeys').hide();
			$('.gfaa_field_list_value').show();
		}
		
		else if(field.type == 'survey' ) { 
			var type = $('#gsurvey-field-type').val();
			var types = ['radio', 'select', 'likert', 'rating'];
			if ( $.inArray( type, types) !== -1) {
				$('.gfaa_inputNumberKeys').hide();
				$('.gfaa_field_list_value').show();
			}
			else {
				$('.gfaa_inputNumberKeys').hide();
				$('.gfaa_field_list_value').hide();
			}
		}
		
		else if (typeof aagf_check_premium_field == 'function' && aagf_check_premium_field(field) ) { 
			
			$('.gfaa_field_list_value').show();
			
			// console.log(field.type);
			
			if( field.type == 'product' ) {
				$('.gfaa_inputNumberKeys').hide();
			}
			else if( typeof field.autoAdvancedField != 'undefined' && ( field.autoAdvancedField == 'true' || field.autoAdvancedField === true ) ) {
				$('.gfaa_inputNumberKeys').show();				
			}
		}
		
		else {
			
			$('.gfaa_inputNumberKeys').hide();
			$('.gfaa_field_list_value').hide();
		}
		
		
	});


});