/* jshint asi: true */

//PREVENT ANNOYING SCREEN SCROLL ON FOCUS()
jQuery('body').bind('focus', function(e){
	e.preventDefault()
})

jQuery( document ).ready( function( $ ) {
	
	////////////////
	// MEDIA UPLOAD
	////////////////
			
	function attach_image_upload_handlers() {
		var $ = jQuery
		//ACTION WHEN CLICKING IMAGE UPLOAD
		$('.fca_eoi_image').attr('src', $('#image_input').val() )
		$('.fca_eoi_image_wrapper').css('cursor', 'pointer')
		if ( $('#image_input').val() === '' ) {
			$('.fca_eoi_image_wrapper').addClass('placeholder')
		}
		$('.fca_eoi_image_wrapper').unbind( 'click' )
		//HANDLER FOR RESULTS AND META IMAGES
		$('.fca_eoi_image_wrapper').click(function(e) {
			var image = wp.media({ 
				multiple: false
			}).open()
			.on('select', function(){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first()

				var image_url = uploaded_image.toJSON().url
				// Assign the url value to the input field
				
				$('#image_input').val( image_url )
				$('.fca_eoi_image').attr( 'src', image_url )
				if ( image_url ) {
					$('.fca_eoi_image_wrapper').removeClass('placeholder')
				}
			})
		})
		

	}
	
	//ORDER THE LAYOUTS	
	$('.fca_eoi_layout').parent().html( $('.fca_eoi_layout').sort( function( a, b ) {
		return $(a).data('layout-order') - $(b).data('layout-order')
	}) )

   
	// Remove FOUC, don't show metaboxes and likes until page is fully loaded
	jQuery( '#post' ).addClass('loaded')
	jQuery( '#poststuff' ).show()
	
	////////////
	// DEFINE GLOBALS
	////////////
	var post_ID = $( '#post_ID' ).val()
	//console.log ( fcaEoiLayouts )
	
	//MAP OF SETTINGS TO LOAD / SAVE
	var targetSelectors = {
		'form-background-color': '[name="fca_eoi[form_background_color]"]',
		'form-bottom-color': '[name="fca_eoi[form_bottom_color]"]',
		'form-border-color': '[name="fca_eoi[form_border_color]"]',
		'form-width': '[name="fca_eoi[form_width]"]',
		'form-text-align': '[name="fca_eoi[form_alignment]"]',
		'headline-font-size': '[name="fca_eoi[headline_font_size]"]',
		'headline-color': '[name="fca_eoi[headline_font_color]"]',
		'headline-fill': '[name="fca_eoi[headline_fill_color]"]',
		'headline-background-color': '[name="fca_eoi[headline_background_color]"]',
		'description-font-size': '[name="fca_eoi[description_font_size]"]',
		'description-color': '[name="fca_eoi[description_font_color]"]',
		'name_field-font-size': '[name="fca_eoi[name_font_size]"]',
		'name_field-color': '[name="fca_eoi[name_font_color]"]',
		'name_field-background-color': '[name="fca_eoi[name_background_color]"]',
		'name_field-border-color': '[name="fca_eoi[name_border_color]"]',
		'name_field-width': '[name="fca_eoi[name_width]"]',
	//	'name_field-text-align': '[name="fca_eoi[name_alignment]"]',
		'email_field-font-size': '[name="fca_eoi[email_font_size]"]',
		'email_field-color': '[name="fca_eoi[email_font_color]"]',
		'email_field-background-color': '[name="fca_eoi[email_background_color]"]',
		'email_field-border-color': '[name="fca_eoi[email_border_color]"]',
		'email_field-width': '[name="fca_eoi[email_width]"]',
	//	'email_field-text-align': '[name="fca_eoi[email_alignment]"]',
		'button-font-size': '[name="fca_eoi[button_font_size]"]',
		'button-color': '[name="fca_eoi[button_font_color]"]',
		'button-hover-color': '[name="fca_eoi[button_hover_color]"]',
		'button-background-color': '[name="fca_eoi[button_background_color]"]',
		'button-border-bottom-color': '[name="fca_eoi[button_wrapper_background_color]"]',	
		'button-border-color': '[name="fca_eoi[button_border_color]"]',	
		'button-width': '[name="fca_eoi[button_width]"]',
	//	'button-text-align': '[name="fca_eoi[button_alignment]"]',
		'privacy-font-size': '[name="fca_eoi[privacy_font_size]"]',
		'privacy-color': '[name="fca_eoi[privacy_font_color]"]',
		'fatcatapps-color': '[name="fca_eoi[branding_font_color]"]',
	}
	
	var layoutSaves = []
	
	function save_layout( layout_id ) {
		layoutSave = {}
		
		for ( var key in targetSelectors ) {
			layoutSave[key] = $(targetSelectors[key]).val()
		}
		layoutSaves[layout_id] = layoutSave
	}
	
	function load_layout( layout_id ) {
		if ( layoutSaves[layout_id] ) {
			this_save = layoutSaves[layout_id]
			for ( var key in targetSelectors ) {
				if ( this_save[key] ) {
					$(targetSelectors[key]).val( this_save[key] )
				}
			}
		}
	}

	
	////////////
	// MAIN EDITOR EVENT HANDLERS
	////////////
		
	//COLOR PICKERS
	$('.fca-color-picker').wpColorPicker({
		palettes: [ '#000', '#fff', '#42d0e1', '#fdd01f', '#ecf0f1', '#0ff1a3', '#ffe26e', '#99d7d9', '#e74c3c' ],
		'change':
		function(event, ui) {
			//$(this).attr( 'value', $(this).val() )
			var $target = $( $(this).data('css-target') )
			var layout_id = $('#fca_eoi_layout_select').val()
			var layout_name = $('.fca_eoi_layout.active').find('.fca_eoi_layout_info h3').html()
			
			if ( $(this).attr('name').indexOf('button_background_color') != -1 ){
				$('.fca_eoi_form_button_element').css('background-color', ui.color.toString() )
				
			} else if ( $(this).attr('name').indexOf('button_wrapper_background_color') != -1 ) {
				$('.fca_eoi_layout_submit_button_wrapper').css('background-color', ui.color.toString() )
				$('[name="fca_eoi[button_wrapper_background_color]"]').val( ui.color.toString() )
				
			} else if ( $(this).attr('name').indexOf('button_hover_color') != -1 ) {
				hover_color_css ( ui.color.toString(), 'background-color')
				$('[name="fca_eoi[button_hover_color]"]').val( ui.color.toString() )
				
			} else if ( $(this).attr('name').indexOf('background') != -1 ) {
				if ( $(this).attr('name') == 'fca_eoi[headline_background_color]' ) {
					$target.css('background-color', ui.color.toString() )
					//SPECIAL CASE FOR WIDGET 3
					if ( layout_id == 'layout_3' ) {
						$('.fca_eoi_layout_headline_copy_triangle polygon').css('fill', ui.color.toString() )
					}
				} else if ( $(this).attr('name') == 'fca_eoi[name_background_color]' ) {
					$target = $( $(this).data('css-target') )
					$target.css('background-color', ui.color.toString() )
					$('[name="fca_eoi[email_background_color]"]').val(ui.color.toString())
					$target = $( $('[name="fca_eoi[email_background_color]"]').data('css-target') )
					$target.css('background-color', ui.color.toString() )
					
				} else if ( $(this).attr('name') == 'fca_eoi[email_background_color]' ) {
					$target = $( $(this).data('css-target') )
					$target.css('background-color', ui.color.toString() )
					$('[name="fca_eoi[name_background_color]"]').val(ui.color.toString())
					$target = $( $('[name="fca_eoi[name_background_color]"]').data('css-target') )
					$target.css('background-color', ui.color.toString() )
				} else if ( $(this).attr('name') == 'fca_eoi[form_background_color]' ) {
					if ( layout_name === 'Image' ){
						$target = $('div.fca_eoi_top_wrapper')
					}
					$target.css('background-color', ui.color.toString() )
				}

			} else if ( $(this).attr('name').indexOf('form_bottom_color') != -1 ) {
				$target = $('div.fca_eoi_layout_inputs_wrapper')
				$target.css('background-color', ui.color.toString() )

			} else if ( $(this).attr('name').indexOf('border') != -1 ) {
				if ( $(this).attr('name') == 'fca_eoi[name_border_color]' ) {
					$target = $( $(this).data('css-target') )
					$target.css('border-color', ui.color.toString() )
					$('[name="fca_eoi[email_border_color]"]').val(ui.color.toString())
					$target = $( $('[name="fca_eoi[email_border_color]"]').data('css-target') )
					$target.css('border-color', ui.color.toString() )
					
				} else if ( $(this).attr('name') == 'fca_eoi[email_border_color]' ) {
					$target = $( $(this).data('css-target') )
					$target.css('border-color', ui.color.toString() )
					$('[name="fca_eoi[name_border_color]"]').val(ui.color.toString())
					$target = $( $('[name="fca_eoi[name_border_color]"]').data('css-target') )
					$target.css('border-color', ui.color.toString() )
				} else {		
					$target.css('border-color', ui.color.toString() )
				}
				
			} else {
				if ( $(this).attr('name') == 'fca_eoi[name_font_color]' ) {
					$target = $( $(this).data('css-target') )
					$target.css('color', ui.color.toString() )
					$('[name="fca_eoi[email_font_color]"]').val(ui.color.toString())
					$target = $( $('[name="fca_eoi[email_font_color]"]').data('css-target') )
					$target.css('color', ui.color.toString() )
					placeholder_color_css ( ui.color.toString() )
					
				} else if ( $(this).attr('name') == 'fca_eoi[email_font_color]' ) {
					$target = $( $(this).data('css-target') )
					$target.css('color', ui.color.toString() )
					$('[name="fca_eoi[name_font_color]"]').val(ui.color.toString())
					$target = $( $('[name="fca_eoi[name_font_color]"]').data('css-target') )
					$target.css('color', ui.color.toString() )
					placeholder_color_css ( ui.color.toString() )
				} else {		
					$target.css('color', ui.color.toString() )
				}
			}
		}
	})
	
	$('.fca-color-picker').change( function() {	
		var $target = $( $(this).data('css-target') )
		var layout_id = $('#fca_eoi_layout_select').val()
		var layout_name = $('.fca_eoi_layout.active').find('.fca_eoi_layout_info h3').html()
		var special_layouts = ["Flat","Padded Image","Image","Wide Image","Content Upgrade","Content Upgrade - Image", "Content Upgrade - Wide Image"]
		
		if ( $(this).attr('name').indexOf('button_background_color') != -1 ){
			$('.fca_eoi_form_button_element').css('background-color', $(this).val() )

		} else if ( $(this).attr('name').indexOf('button_hover_color') != -1 ) {
			hover_color_css ( $(this).val(), 'background-color')
			$('[name="fca_eoi[button_hover_color]"]').val( $(this).val() )
				
		} else if ( $(this).attr('name').indexOf('button_wrapper_background_color') != -1 ) {
			$('.fca_eoi_layout_submit_button_wrapper').css('background-color', $(this).val() )
			$('[name="fca_eoi[button_wrapper_background_color]"]').val( $(this).val() )

		} else if ( $(this).attr('name').indexOf('form_bottom_color') != -1 ) {
			if ( special_layouts.includes(layout_name) ) {
				$('div.fca_eoi_layout_inputs_wrapper').css('background-color', $(this).val() )
				$('[name="fca_eoi[form_bottom_color]"]').val( $(this).val() )
			} else {
				$('div.fca_eoi_layout_inputs_wrapper').css('background-color', 'unset' )
				$('[name="fca_eoi[form_bottom_color]"]').val( 'unset' )
			}
	
		} else if ( $(this).attr('name').indexOf('background') != -1 ) {
			if ( $(this).attr('name') == 'fca_eoi[headline_background_color]' ) {
				var value = $(this).val()
				$target.css('background-color', value )
				//SPECIAL CASE FOR WIDGET 3
				if ( layout_id == 'layout_3' ) {
					$('.fca_eoi_layout_headline_copy_triangle polygon').css('fill', value )
				}
			} else if ( $(this).attr('name') === 'fca_eoi[name_background_color]' ) {
				var value = $(this).val()
				$target.css('background-color', value )
			} else if ( $(this).attr('name') === 'fca_eoi[email_background_color]' ) {
				var value = $(this).val()
				$target.css('background-color', value )
			} else if ( $(this).attr('name') === 'fca_eoi[form_background_color]' ) {
				if ( layout_name === 'Image' ){
					$('div.fca_eoi_top_wrapper').css('background-color', $(this).val() + ' !important' )
				} else {
					var value = $(this).val()
					$target.css('background-color', value )
				}
			}
		} else if ( $(this).attr('name').indexOf('border') != -1 ) {
			$target.css('border-color', $(this).val() )
		} else if ( $(this).attr('name').indexOf('name_font_color') != -1 || $(this).attr('name').indexOf('email_font_color') != -1 ){
			$target.css('color', $(this).val() )
			placeholder_color_css ( $(this).val() )
		} else {
			$target.css('color', $(this).val() )
		}

	})
	
	//WIDTH SELECT PICKERS
	$('.fca_eoi_width_input').on('change input', function() {
		var $target = $( $(this).data('cssTarget') )
		var units = $(this).next().val()
		if ( units === '%' && $(this).val() > 100 ) {
			$(this).val(100)
		}
		$target.css('max-width', $(this).val() + units )
	})
	
	$('.fca_eoi_width_units_select').change( function() {
		$(this).prev().change()
	})
	
	//ALIGNMENT SELECT
	$('.fca-eoi-align-button').click(function(){
		var $target = $( $(this).siblings('.fca_eoi_alignment_input').data('cssTarget') )
		$(this).siblings('.fca-eoi-align-button').removeClass('fca-alignment-selected')
		$(this).addClass('fca-alignment-selected')
		$(this).siblings('.fca_eoi_alignment_input').val( $(this).data('value') )
		$target.css('text-align', $(this).data('value') )
	})

	//FONTS SIZE PICKERS
	$('.fca-font-size-picker').change( function() {
		if ( $(this).attr('name') == 'fca_eoi[name_font_size]' ) {
			$target = $( $(this).data('css-target') )
			$target.css('font-size', $(this).val() )
			$('[name="fca_eoi[email_font_size]"]').val($(this).val())
			$target = $( $('[name="fca_eoi[email_font_size]"]').data('css-target') )
			$target.css('font-size', $(this).val() )
			
		} else if ( $(this).attr('name') == 'fca_eoi[email_font_size]' ) {
			$target = $( $(this).data('css-target') )
			$target.css('font-size', $(this).val() )
			$('[name="fca_eoi[name_font_size]"]').val($(this).val())
			$target = $( $('[name="fca_eoi[name_font_size]"]').data('css-target') )
			$target.css('font-size', $(this).val() )
		} else {		
			$target = $( $(this).data('css-target') )
			$target.css('font-size', $(this).val() )
		}
	})
	
	//CHANGE LAYOUT BUTTON CLICK
	$('#fca_eoi_layout_select_button').click(function() {

		var layout_id = $('#fca_eoi_layout_select').val()
		save_layout ( layout_id )
		// Go back to the layout select tab
		$( '.postbox' ).hide()
		$( '#fca_eoi_meta_box_setup' ).show()
				
		if ( layout_id.indexOf('lightbox') !== -1 ) {
			$( '#layouts_types_tabs li').eq(0).click()
		} else if ( layout_id.indexOf('postbox') !== -1 ) {
			$( '#layouts_types_tabs li').eq(1).click()
		} else if ( layout_id.indexOf('banner') !== -1 ) {
			$( '#layouts_types_tabs li').eq(3).click()
		} else if ( layout_id.indexOf('overlay') !== -1 ) {
			$( '#layouts_types_tabs li').eq(4).click()
		} else {
			$( '#layouts_types_tabs li').eq(2).click()
		}
	})
	
	var max_character_count = 16
	var hidden_icon = '<span class="dashicons dashicons-hidden"></span>'
	
	//BUTTON COPY KEYUP HANDLERS
	$('[name="fca_eoi[headline_copy]"]').keyup(function() {
		$('#fca_eoi_preview_headline_copy').html( $(this).val())
		$('#accordion-info-headline').html( trim_str( $(this).val(), max_character_count ) )
	})
	//TEXT EDITOR TAB
	$('[name="fca_eoi[description_copy]"]').keyup(function() {
		$('.fca_eoi_layout_description_copy_wrapper div').html( $(this).val() )
		$('#accordion-info-description').html( trim_str( $(this).val(), max_character_count ) )
	})

	$('[name="fca_eoi[name_placeholder]"]').keyup(function() {
		$('.fca_eoi_layout_name_field_inner').children().first().attr('placeholder', $(this).val())
		$('#accordion-info-name_field').html( trim_str( $(this).val(), max_character_count ) )
	})
	$('[name="fca_eoi[email_placeholder]"]').keyup(function() {
		$('.fca_eoi_layout_email_field_inner').children().first().attr('placeholder', $(this).val())
		$('#accordion-info-email_field').html( trim_str( $(this).val(), max_character_count ) )
	})	
	$('[name="fca_eoi[button_copy]"]').keyup(function() {
		$('.fca_eoi_form_button_element').val( $(this).val())
		$('#accordion-info-button').html( trim_str( $(this).val(), max_character_count ) )
	})
	$('[name="fca_eoi[privacy_copy]"]').keyup(function() {
		$('.fca_eoi_layout_privacy_copy_wrapper').children().first().html( $(this).val())
		$('#accordion-info-after_button_area').html( trim_str( $(this).val(), max_character_count ) )
	})
	
	//BRANDING TOGGLE BUTTON
	$('[name="fca_eoi[show_fatcatapps_link]"]').change(function() {
		$('.fca_eoi_layout_fatcatapps_link_wrapper').toggle(this.checked)
		if ( !this.checked ) {
			$('#accordion-info-branding').html( hidden_icon )
		} else {
			$('#accordion-info-branding').html( '' )
		}
	})
	
	//NAME FIELD TOGGLE BUTTON
	$('[name="fca_eoi[show_name_field]"]').change(function() {
		$('.fca_eoi_layout_name_field_wrapper').toggle(this.checked)
		var layout_id = $('#fca_eoi_layout_select').val()

		if ( this.checked ) {
			$('#fca_eoi_name_field').show()
			$('[name="fca_eoi[name_placeholder]"]').keyup()
		}
		else {
			$('#fca_eoi_name_field').hide()
		}
	})
	
	//HEADLINE FIELD TOGGLE BUTTON
	$('[name="fca_eoi[show_headline_field]"]').change(function() {
		$('.fca_eoi_layout_headline_copy_wrapper').toggle(this.checked)
		if ( !this.checked ) {
			$('#accordion-info-headline').html( hidden_icon )
		} else {
			$('[name="fca_eoi[headline_copy]"]').keyup()
		}

	})

	//DESCRIPTION FIELD TOGGLE BUTTON
	$('[name="fca_eoi[show_description_field]"]').change(function() {
		$('.fca_eoi_layout_description_copy_wrapper').toggle(this.checked)
		if ( !this.checked ) {
			$('#accordion-info-description').html( hidden_icon )
		} else {
			$('[name="fca_eoi[description_copy]"]').keyup()
		}
	})		
	
	//PRIVACY / AFTER-BUTTON FIELD TOGGLE BUTTON
	$('[name="fca_eoi[show_privacy_field]"]').change(function() {
		$('.fca_eoi_layout_privacy_copy_wrapper').toggle(this.checked)
		if ( !this.checked ) {
			$('#accordion-info-after_button_area').html( hidden_icon )
		} else {
			$('[name="fca_eoi[privacy_copy]"]').keyup()
		}
	})
	
	//SHOW CLOSE ICON TOGGLE BUTTON
	$('[name="fca_eoi[show_close]"]').change(function() {
		$('.fca_eoi_banner_close_btn').toggle(this.checked)
	})	
	
	//LAYOUT POSITION TOGGLE BUTTON
	$('[name="fca_eoi[toggle_overlay_position]"]').change(function() {
		var layout_id = $( '#fca_eoi_layout_select' ).val()
		if ( layout_id.indexOf('banner') !== -1 ) {
			if ( this.checked ) {
				$('#fca_eoi_preview_form').css('margin-top', 170 )
			} else {
				$('#fca_eoi_preview_form').css('margin-top', -20)
			}
		}
		
		if ( layout_id.indexOf('overlay') !== -1 ) {
			if ( this.checked ) {
				$('#fca_eoi_preview_form').css('margin-left', 5 )
				$('#fca_eoi_preview_form').css('margin-right', 'auto' )
			} else {
				$('#fca_eoi_preview_form').css('margin-right', 5 )
				$('#fca_eoi_preview_form').css('margin-left', 'auto' )
			}
			
		}
		$('[name="fca_eoi[offset]"]').change()
	})
	
	//OFFSET 
	$('[name="fca_eoi[offset]"]').on( 'input change', function() {
		var layout_id = $( '#fca_eoi_layout_select' ).val()
		var value = parseInt ( $(this).val() )
		if ( layout_id.indexOf('banner') !== -1 ) {
			if ( $('[name="fca_eoi[toggle_overlay_position]"]').prop('checked') ) {
				$('#fca_eoi_offset_p').find('.control-title').html('Margin Bottom')
				$('#fca_eoi_preview_form').css('margin-top', 170 - value )
				$('#fca_eoi_push_page_p').find('.control-title').html('Push Page Up')
				
			} else {
				$('#fca_eoi_preview_form').css('margin-top', value - 20 )
				$('#fca_eoi_offset_p').find('.control-title').html('Margin Top')
				$('#fca_eoi_push_page_p').find('.control-title').html('Push Page Down')
			}
		}
		if ( layout_id.indexOf('overlay') !== -1 ) {
			if ( $('[name="fca_eoi[toggle_overlay_position]"]').prop('checked') ) {
				$('#fca_eoi_preview_form').css('margin-left', value )
				$('#fca_eoi_offset_p').find('.control-title').html('Margin Left')
			} else {
				$('#fca_eoi_preview_form').css('margin-right', value )
				$('#fca_eoi_offset_p').find('.control-title').html('Margin Right')
			}
			
		}
	})	
	
	//////////////////
	// LAYOUT STUFF
	//////////////////
		
	//LOADS A LAYOUT OBJECT INTO THE EDITOR FIELDS
	function update_layout ( currentLayout, rebuild ) {
		console.log ("Updating layout...")
		//console.log ( currentLayout )

		$( '#fca_eoi_layout_revert_button' ).show()
		
		//ADD NEW HTML
		$('#fca-preview-style').remove()
		$('#fca_eoi_preview_form_container').remove()
		$('#fca_eoi_preview').append(currentLayout.html)
		
		$( '#accordion-info-layout' ).html( $('.fca_eoi_layout.active').find('.fca_eoi_layout_info h3').html() )

		var editables = currentLayout.editables
		var target, value, $selector = ''
		
		//CLEAR EXISTING TARGETS
		for (var key in targetSelectors ) {
			var css_hidden_input = targetSelectors[key].replace(']"]', '_selector]"]')
			$(css_hidden_input).val('')
		}
		
		//SETS DATA CSS-TARGETS & HIDDEN CSS TARGET INPUT INPUT AND LOADS INPUTS
		for (var field in editables ) {
						
			for ( var cssSelector in editables[field] ) {
				
				for ( var attribute in editables[field][cssSelector] ) {
					
					target = field + '-' + attribute
					$selector = $(targetSelectors[target])
					value = editables[field][cssSelector][attribute][1]
					
					if ( target.indexOf('font-size') !== -1 ) { 
						//Need something different for font size inputs
						rebuild ? $selector.children().filter( function() {
							return $( this ).val() == value
						}).prop('selected', true) : ''
						$selector.data('css-target', cssSelector)

					} else if ( target.indexOf('width') !== -1 ) { 
						if ( rebuild ) {
							value.indexOf('px') > 0 ? $selector.next().val('px') : $selector.next().val('%')
							$selector.val( parseInt(value) )
						}
						$selector.data('css-target', cssSelector)
					
					} else {
						rebuild ? $selector.val(value) : ''						
						$selector.data('css-target', cssSelector)
					}
					//Update hidden selector input with target
					var targetString = targetSelectors[target]
					if ( targetString ) {
						var css_hidden_input = targetString.replace(']"]', '_selector]"]')
						$(css_hidden_input).val(cssSelector)
					} else {
						console.log("can't find target: " + target)
					}
				}
			}
		}
		
		//LOAD SAVE IF APPLICABLE
		load_layout ( $( '#fca_eoi_layout_select' ).val() )
		
		//REFRESH CUSTOMIZATION PICKERS
		$('.fca-color-picker').change()
		
		$('.fca-font-size-picker').change()
		
		$('.fca_eoi_width_units_select').change()
		
		load_default_texts( $( '#fca_eoi_layout_select' ).val() )
		
		set_form_defaults( $( '#fca_eoi_layout_select' ).val() )
		
		
		//TRIGGER INPUTS
		$('[name="fca_eoi[email_placeholder]"], [name="fca_eoi[button_copy]"]').keyup()
		$('[name="fca_eoi[show_name_field]"], [name="fca_eoi[show_headline_field]"], [name="fca_eoi[show_description_field]"], [name="fca_eoi[show_privacy_field]"], [name="fca_eoi[toggle_overlay_position]"], [name="fca_eoi[show_close]"] ').change()
		
		//VARIOUS RESETS
		hide_unused_inputs( $( '#fca_eoi_layout_select' ).val() )
		set_publication()
		attach_image_upload_handlers()
				
		//OVERRIDE BANNER CLOSE BUTTON CLICK
		$('.fca_eoi_banner_close_btn, .fca_eoi_scrollbox_close_btn').unbind('click').click(function(e){ $(this.parentNode).show() } )
			
	}
	
	var setInitialDefault = false
	function set_form_defaults( layout_id ) {
		
		if ( layout_id.indexOf('banner') !== -1 || layout_id.indexOf('overlay') !== -1) {
			$( '[name="fca_eoi[show_privacy_field]"]' ).attr('checked', false )
			$( '#fca_eoi_fieldset_privacy' ).hide()
			$( '#fca_eoi_overlay_position_p' ).find('.switch-label').attr('data-on', 'Left').attr('data-off', 'Right')
			$( '#fca_eoi_overlay_position_p' ).show()
			$( '#fca_eoi_offset_p' ).show()
			
		} else {
			$( '#fca_eoi_fieldset_privacy' ).show()
			$( '#fca_eoi_overlay_position_p' ).hide()
			$( '#fca_eoi_offset_p' ).hide()
		}
		
		if ( layout_id.indexOf('overlay') !== -1 ) {
			$('#fca_eoi_preview_form').css('margin-top', 111 )
		} else {
			$('#fca_eoi_preview_form').css('margin-top', 0 )
		}
		
		if ( layout_id.indexOf('banner') !== -1 ) {
			$( '#fca_eoi_close_button_p' ).show()
			$( '#fca_eoi_overlay_position_p' ).find('.switch-label').attr('data-on', 'Bot.').attr('data-off', 'Top')
		} else {
			$(' #fca_eoi_preview_form' ).css( 'overflow-wrap', 'break-word' )
			$( '#fca_eoi_close_button_p' ).hide()
		}
		if ( location.search.indexOf('action=edit') === -1 && layout_id.indexOf('lightbox') !== -1 && !setInitialDefault ) {
			setInitialDefault = true
			$('[name="fca_eoi[publish_lightbox][devices]"]').val('desktop')
		}

	}
	
	function hide_unused_inputs( layout_id ) {
		
		//HIDE FATCAT APPS LINK FOR PREMIUM
		if ( $('[name="fca_eoi[show_fatcatapps_link]"]').length === 0 ) {
			$('.fca_eoi_layout_fatcatapps_link_wrapper').hide()
		} else {
			$('[name="fca_eoi[show_fatcatapps_link]"]').change()
		}
		
		//HIDE THE ANIMATIONS UNLESS THE LIGHTBOX/POPUP LAYOUT TYPE IS SELECTED
		if ( layout_id.indexOf('lightbox') !== -1 || layout_id.indexOf('banner') !== -1 || layout_id.indexOf('overlay') !== -1) {
			$( '.eoi-custom-animation-form' ).show()
		} else {
			//if there are no others, hide the whole box fca_eoi_meta_box_powerups
			var children = $( '#fca_eoi_meta_box_powerups' ).children(".inside").children()
			$( '#fca_eoi_show_animation_checkbox_label' ).attr('checked', false)
			if (children.length == 1 && $(children[0]).hasClass('eoi-custom-animation-form') )  {
				$( '#fca_eoi_meta_box_powerups' ).hide()
			} else {
				$( '.eoi-custom-animation-form' ).hide()
			}
		}
		
		//HIDE ACCORDITION INPUTS IF THE FORM DOESN'T HAVE THAT SETTING
		for ( var key in targetSelectors ) {
			target = targetSelectors[key]
			var css_hidden_input = target.replace(']"]', '_selector]"]')
			if ( key.indexOf('name_field') !== -1 ) {
					$(target).closest('p').hide()
			} else if ( $(css_hidden_input).val() !== '' ) {
				if ( $(target).hasClass('wp-color-picker')  || $(target).hasClass('fca_eoi_width_input') ) {
					$(target).closest('p').show()
				} else {
					$(target).show()
				}
			} else {
				if ( $(target).hasClass('wp-color-picker') || $(target).hasClass('fca_eoi_width_input') ) {
					$(target).closest('p').hide()
				} else {
					$(target).hide()
				}
			}
		}
		
		if ( layout_id.indexOf('banner') !== -1 ) {
			$( '#fca_eoi_fieldset_description' ).hide()
			$( '#fca_eoi_push_page_p' ).show()
		} else {
			$( '#fca_eoi_fieldset_description' ).show()
			$( '#fca_eoi_push_page_p' ).hide()
		}
		
	}	
	
	$( '#fca_eoi_layout_revert_button' ).click( function( e ) {
		var old_layout_id = $('#fca_eoi_layout_select').val()
		$( '[data-layout-id="' + old_layout_id + '"]' ).click()
	})
	
	// Switch layout when screenshot clicked
	$( '.fca_eoi_layout' ).click( function( e ) {
		
		if ( $(this).hasClass('layout-disabled') ) {
			window.open( $(this).find('.upgrade-link').attr('href'), '_blank');
			return false
		}
		
		// Determine the layout that was clicked
		var old_layout_id = $('#fca_eoi_layout_select').val()
		var layout_id = $( this ).data( 'layout-id' )
		
		// Mark active
		$( '.fca_eoi_layout' ).removeClass( 'active' )
		$( this ).addClass( 'active' )
		
		// Update hidden input value for new layout
		$( '#fca_eoi_layout_select' ).val( layout_id )
		
		//REBUILD LAYOUT, UNLESS ITS THE SAME ONE, JUST GO BACK
		if ( old_layout_id !== layout_id ) {
			update_layout( fcaEoiLayouts[layout_id], true )
		}
		
		// Go back to the build tab
		$( '.postbox' ).show()
		$( '#fca_eoi_meta_box_setup' ).hide()
		$( '#fca_eoi_meta_box_build, #fca_eoi_meta_box_provider, #fca_eoi_meta_box_thanks, #fca_eoi_meta_box_publish' ).show()
		$(window).scrollTop(0) //GO BACK TO TOP

	})
	
	function load_default_texts( layout_id ) {	
		//LAYOUTS WITH ALTERNATE TEXT
		var content_upgrade = [ 'lightbox_17', 'lightbox_21', 'lightbox_22', 'postbox_17', 'postbox_21', 'postbox_22', 'layout_17', 'layout_22' ]
		var headline_text = 'Free Email Updates'
		
		if ( content_upgrade.indexOf( layout_id ) !== -1 ) {
			headline_text = 'Almost done: complete this form and click the button to gain instant access.'
		}
		if ( $('[name="fca_eoi[headline_copy]"]').val() === '' ) {
			$('[name="fca_eoi[headline_copy]"]').val( headline_text )	
		}
		if ( $('[name="fca_eoi[description_copy]"]').val() === '' ) {
			$('[name="fca_eoi[description_copy]"]').val('Get the latest content first.')	
		}
		if ( $('[name="fca_eoi[name_placeholder]"]').val() === '' ) {
			$('[name="fca_eoi[name_placeholder]"]').val('First Name')	
		}
		if ( $('[name="fca_eoi[email_placeholder]"]').val() === '' ) {
			$('[name="fca_eoi[email_placeholder]"]').val('Email')	
		}
		if ( $('[name="fca_eoi[button_copy]"]').val() === '' ) {
			$('[name="fca_eoi[button_copy]"]').val('Join Now')	
		}
		if ( $('[name="fca_eoi[privacy_copy]"]').val() === '' ) {
			$('[name="fca_eoi[privacy_copy]"]').val('We respect your privacy.')	
		}		
	}

	//ACCORDION CLICK HANDLER
	$( '.accordion-section, .accordion-section-title' ).click(function() {
		var $parent = $(this).closest( '[id^="fca_eoi_fieldset_"]' )
		var field_id = $parent.attr('id')

		$( '.fca_eoi_highlighted', '#fca_eoi_preview' ).removeClass( 'fca_eoi_highlighted' )
		// If nothing else was highlighted, highlight the whole form
		if ( $( '.fca_eoi_highlighted' ).length === 0 ) {
			$( '#fca_eoi_preview_form' ).addClass( 'fca_eoi_highlighted' )
		}
	})
	
	// Expand working fieldset
	var expand_fieldset = function ( fieldset_id ) {
		if ( fieldset_id === 'name_field' ) {
			$( '#fca_eoi_fieldset_email_field.accordion-section:not(.open) .accordion-section-title').click()
		} else {
			$( '#fca_eoi_fieldset_' + fieldset_id + '.accordion-section:not(.open) .accordion-section-title').click()
		}
		$('.wp-editor-area:visible').focus()
		$( '#fca_eoi_fieldset_' + fieldset_id ).find('.fca_eoi_text_input').focus()
	}		

	if ( $('.fca_eoi_custom_css_textbox').length > 0 ) {
		wp.codeEditor.initialize( $( '.fca_eoi_custom_css_textbox' ) )
	}

	$( '#fca_eoi_preview' ).click( function( event ) {
		if ( isNaN(event.clientX) ) {
			event.clientX = 0
		}
		if ( isNaN(event.clientY) ) {
			event.clientY = 0
		}
		var $element = $( document.elementFromPoint( event.clientX, event.clientY ) ).closest( '[data-fca-eoi-fieldset-id]' )		
		if ( $element.length > 0 ) {		
			expand_fieldset( $element.data( 'fca-eoi-fieldset-id' ) )		
		} else {		
			expand_fieldset( 'form' )		
		}		
	})		

	// Highlight current preview element
	$( '#fca_eoi_settings .accordion-section' ).each( function() {
	
		$(this).click( function() {
			$( '.fca_eoi_highlighted', '#fca_eoi_preview' ).removeClass( 'fca_eoi_highlighted' )
			var $fieldset = $( this )
			var $fieldset_id = $fieldset.attr( 'id' ).replace( 'fca_eoi_fieldset_', '' )
			var $element = $( '[data-fca-eoi-fieldset-id=' + $fieldset_id + ']', '#fca_eoi_preview' )
			if ( $fieldset_id === 'email_field' ){
				var $element2 = $( '[data-fca-eoi-fieldset-id=name_field]', '#fca_eoi_preview' )
			}

			// Highlight element or closest block level element
			if( $element.is( 'p, div, h1, h2, h3, h4, h5, h6' ) ) {
				$element.addClass( 'fca_eoi_highlighted' )
				if ( $element2 ){
					$element2.addClass( 'fca_eoi_highlighted' )
				}
			} else {
				$element.closest( 'p, div, h1, h2, h3, h4, h5, h6' ).addClass( 'fca_eoi_highlighted' )
			}
			// If nothing else was highlighted, highlight the whole form
			if ( $( '.fca_eoi_highlighted' ).length === 0 ) {
				$( '#fca_eoi_preview_form' ).addClass( 'fca_eoi_highlighted' )
			}				
		})
	})
	
	/////////////////
	// PROVIDERS
	/////////////////
	
	// Show only the selected provider
	$( '[name="fca_eoi[provider]"]' ).change( function() {
		var provider_id = $( this ).val()
		$( '[id^=fca_eoi_fieldset_form_][id$=_integration]' ).slideUp( 'fast' )
		if ( provider_id ) {
			$( '#fca_eoi_fieldset_form_' + provider_id + '_integration' ).slideDown( 'fast' )
		}
	}).change()
	
	//////////////////
	// PUBLICATION METABOX
	//////////////////
	
	function set_publication() {
		$('#fca_eoi_publish_widget, #fca_eoi_publish_postbox, #fca_eoi_publish_lightbox').hide()
		var layout_id = $('#fca_eoi_layout_select').val()
		
		$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(0).closest('p').show()
		$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(1).closest('p').show()
			
		if ( layout_id.indexOf('postbox') !== -1 ) {
			$('#fca_eoi_publish_postbox').show()
		} else if ( layout_id.indexOf('lightbox') !== -1 ) {
			$('#fca_eoi_publish_lightbox').show()
			$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(0).closest('p').show()
		} else if ( layout_id.indexOf('banner') !== -1 || layout_id.indexOf('overlay') !== -1 ) {
			$('#fca_eoi_publish_lightbox').show()
			$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(0).closest('p').hide()
			$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(1).closest('p').hide()
			$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(1).prop( 'checked', true )	
			$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(1).trigger('change')
		} else {
			$('#fca_eoi_publish_widget').show()
		}
	}
	
	// Show/hide popup publication modes
	$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).on( 'click change', function() {
		var $this = $( this )
		var mode = $this.val()
		var $mode = $( '#fca_eoi_publish_lightbox_mode_' + mode )
		$mode.removeClass( 'hidden' )
		$( '[id^=fca_eoi_publish_lightbox_mode_]' ).hide( 'fast' )
		if ( $this.prop( 'checked' ) ) {
			$mode.show( 'fast' )
		}
	})
	
	if ( $( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).length === 1 ) {
		$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).click().hide()
	} else if ( $( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).filter(':checked').length > 0 ) {
		$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).filter(':checked').click()
	} else {
		$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).eq(1).click()
	}
	
	$( 'input[name="fca_eoi[publish_lightbox_mode]"]' ).unbind('click')
	
	// Update popup link HTML code
	$( 'input[name="fca_eoi[lightbox_cta_text]"]' ).change( function() {
		var link = '<button data-optin-cat="{{post_ID}}">{{text}}</button>'
		$( 'input[name="fca_eoi[lightbox_cta_link]"]' ).val( link.replace( '{{post_ID}}', post_ID ).replace( '{{text}}', $( this ).val() ) )
	}).change()
		
	//////////////////
	// SAVE BUTTON
	//////////////////	
		
	// Change buttons texts 
	$('#publish').val('Save')

	// Override saving throbber text
	$( '#publish' ).click(function(){
		postL10n.publish = 'Saving'
		postL10n.update= 'Saving'
	})

	// Duplicate the Save button and add to the button of the page
	$( '#submitdiv' ).clone( true ).appendTo( '#normal-sortables' )
	
	//FIX AN ISSUE WITH EMAIL FIELD NEEDING VALIDATION ON FORM SAVING
	$('input[name="save"]').click(function(){
		$('.fca_eoi_form_input_element').val('')
	})
	
	//////////////////
	// THANK YOU MODE
	//////////////////
		
	// Show/hide Thank you page modes
	$( 'input[name="fca_eoi[thankyou_page_mode]"]' ).on( 'click change', function() {
		if ( !this.checked ) {
			$('#fca_eoi_thankyou_ajax_msg').show( 'fast' )
			$('#fca_eoi_thankyou_redirect').hide()
			$('#fca_eoi_thank_you_text_color').show( 'fast' )
			$('#fca_eoi_thank_you_bg_color').show( 'fast' )
		
		} else {
			$('#fca_eoi_thankyou_ajax_msg').hide()
			$('#fca_eoi_thank_you_text_color').hide()
			$('#fca_eoi_thank_you_bg_color').hide()
			$('#fca_eoi_thankyou_redirect').show( 'fast' )
		}
	}).change()
	
	
	//REDIRECT MODE TOGGLE
	$( '[name="fca_eoi[redirect_page_mode]"]' ).on( 'change', function() {
		if ( $(this).val() === 'page' ) {
			$( '#fca_eoi_redirect_url_span' ).hide()
			$( '#fca_eoi_redirect_page_span' ).show()
		} else {
			$( '#fca_eoi_redirect_page_span' ).hide()
			$( '#fca_eoi_redirect_url_span' ).show()
		}
	}).change()
	
	//////////////////
	// ANIMATIONS
	//////////////////
	
	// Show/hide animations checkbox
	if (document.getElementById("fca_eoi_show_animation_checkbox")) {
		if (!($('#fca_eoi_show_animation_checkbox')[0].checked)) {
			$( '#fca_eoi_animations_div' ).hide()
		}
	}
		
	// Add toggle to animations checkbox
	$('#fca_eoi_show_animation_checkbox').click(function () {
		$("#fca_eoi_animations_div").toggle('fast')
	})
	
	//Display change text when you pick a new animation
	$( '#fca_eoi_animations' ).select2().on("select2-open", function() {
		$( "#fca_eoi_animations_choice_text" ).removeClass()
	})
		
	$( '#fca_eoi_animations' ).select2().on("select2-close", function() {
		if (this.value != 'None') {
			$( "#fca_eoi_animations_choice_text" ).addClass( 'animated ' + this.value )
			$( "#fca_eoi_animations_choice_text" ).text( "Solid choice!  You've selected a great entrance effect.")
			
		}else{
			$( "#fca_eoi_animations_choice_text" ).text('')
		}
	})
	
	///////////////
	// TAB NAVIGATION
	///////////////

	// Use tabs in the main metabox
	$( '#layouts_types_tabs li' ).click( function( e ) {
		
		$( '#layouts_types_tabs li.active' ).removeClass( 'active' )
		
		$( this ).addClass( 'active' ).blur()

		$( '.fca_eoi_layout' ).hide()
		
		var target = $( this ).data( 'target' )
		$('[data-layout-type="' + target + '"]').show()
		
	})
	
	////////////
	// RANDOM STUFF
	////////////
	
	//TOOLTIPS	
	$('.fca_eoi_tooltip').tooltipster({
		"trigger": "hover",
		"side": "right",
		"arrow": false,
		"theme": ['tooltipster-borderless', 'tooltipster-optin-cat-admin']
	})

	function hover_color_css ( colorStr ) {
		
		var css = '.fca_eoi_layout_submit_button_wrapper input:hover, .fca_eoi_layout_submit_button_wrapper:hover { background-color:' + colorStr + '!important}' 
		
		$('.fca_eoi_css_hover_shim' ).remove()
		
		var div = $('<div />', {
			html: '<style>' + css + '</style>'
		}).appendTo("body").addClass('fca_eoi_css_hover_shim' )
		
	}
	
	function placeholder_color_css ( colorStr ) {
		var css =	'.fca_eoi_form_input_element::-webkit-input-placeholder {opacity: 0.6; color:' + colorStr + '}' + 
			'.fca_eoi_form_input_element::-moz-placeholder {opacity: 0.6;  color:' + colorStr + '}' + 
			'.fca_eoi_form_input_element:-ms-input-placeholder {opacity: 0.6; color:' + colorStr + '}' +
			'.fca_eoi_form_input_element:-moz-placeholder {opacity: 0.6; color:' + colorStr + '}'
		
		$('.fca_eoi_css_placeholder_shim').remove()
		
		var div = $('<div />', {
			html: '<style>' + css + '</style>'
		}).appendTo("body").addClass('fca_eoi_css_placeholder_shim')
		
	}
	
	function trim_str ( string, maxLength ) {
		
		if ( string.trim().length > maxLength ) {
			return string.trim().substring(0,maxLength) + '..'
		} else {
			return string.trim()
		}
		
	}
	
	function ColorLuminance(hex, lum) {
		if ( typeof(hex) != 'string' || hex === '' || isNaN(parseFloat(lum)) ) {
			return false;
		}
		
		// validate hex string
		hex = String(hex).replace(/[^0-9a-f]/gi, '');
		if (hex.length < 6) {
			hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
		}
		lum = lum || 0;

		// convert to decimal and change luminosity
		var rgb = "#", c, i;
		for (i = 0; i < 3; i++) {
			c = parseInt(hex.substr(i*2,2), 16);
			c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
			rgb += ("00"+c).substr(c.length);
		}

		return rgb;
	}
	
	$( document ).on( 'submit', '#post', function () {
		$("#post").attr("enctype", "application/x-www-form-urlencoded")
		$("#post").attr("encoding", "application/x-www-form-urlencoded")
		$( window ).unbind( 'beforeunload' )
	})
	
	// Apply select2
	$( 'select.select2' ).select2()
	
	// Disable enter doing accidential save
	$( document ).on( 'keypress', ':input:not(textarea)', function( e ) {
		if (e.keyCode == 13) {
			e.preventDefault()
		}
	})
	// Disable submit of the preview form
	$( document ).on( 'click', '#fca_eoi_preview input[type=submit]', function( e ) {
		e.preventDefault()
	})
	
	// Autoselect
	$(".autoselect").bind( 'click focus mouseenter', function(){ $( this ).select() }).mouseup( function(e){ e.preventDefault } )
	
	// SET STARTING STATE
	set_publication()
	
	if ( $('#fca_eoi_layout_select').hasClass('fca-new-layout') ) {
		$('#fca_eoi_layout_select_button').click()
		$('#fca_eoi_layout_select').removeClass('fca-new-layout')
	} else {
		//BUILD LAYOUT
		var layout_id = $('#fca_eoi_layout_select').val()
		//GIVE LAYOUT "ACTIVE" CLASS
		$( '[data-layout-id="' + layout_id + '"]' ).addClass( 'active' )
		update_layout(fcaEoiLayouts[layout_id], false)
		$( '#fca_eoi_meta_box_setup' ).hide()
		$( '#fca_eoi_meta_box_build' ).show()
	}
})