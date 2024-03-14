<?php 

require_once FCA_EOI_PLUGIN_DIR . 'includes/classes/k/k.php';


function fca_eoi_tooltip ( $text = 'Tooltip', $icon = 'dashicons dashicons-editor-help' ) {
	return "<span class='$icon fca_eoi_tooltip' title='" . htmlentities( $text ) . "'></span>";
}

	
/* REMOVE LINKS FOR USERS WITH FEWER PERMISSIONS THAN EDITOR */
			
function fca_eoi_remove_admin_bar_link() {
	if (!current_user_can( 'delete_others_pages' ) && function_exists( 'remove_meta_box' )){
		remove_meta_box( 'fca_eoi_dashboard_widget', 'dashboard', 'normal' );	
	}
}
add_action( 'wp_dashboard_setup', 'fca_eoi_remove_admin_bar_link', 50 );

function fca_eoi_get_error_texts($post_meta) {
		
	$name_error = empty ( $post_meta[ 'error_text_field_required' ] ) ? "Please enter a valid email address. For example \"example@example.com\"." : $post_meta[ 'error_text_field_required' ];
	$email_error = empty ( $post_meta[ 'error_text_invalid_email' ] ) ? 'Please fill out this field to continue.' : $post_meta[ 'error_text_invalid_email' ];
	
	$errorTexts = array(
		'field_required' => $name_error,
		'invalid_email' => $email_error,
	);
	
	return $errorTexts;
}

function fca_eoi_get_thanks_mode($post_meta) {

	$mode = empty ( $post_meta[ 'thankyou_page_mode' ] ) ? 'ajax' : $post_meta[ 'thankyou_page_mode' ];
	return $mode;
}


//CHECK IF USER HAS AN ACTIVE CUSTOM FORM IN ONE OF THEIR OPT INS, OTHERWISE TURN OFF THIS FEATURE
function fca_eoi_set_custom_form_depreciation() {
	
	$optins_using_customform = get_posts( array(
		'post_type' => 'easy-opt-ins',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'meta_key'		=> 'fca_eoi_provider',
		'meta_value'	=> 'customform',
		
	));
	
	$forms_in_trash = get_posts( array(
		'post_type' => 'easy-opt-ins',
		'posts_per_page' => -1,
		'post_status' => 'trash',
		'meta_key'		=> 'fca_eoi_provider',
		'meta_value'	=> 'customform',
		
	)); 
	
	if ( count ( $optins_using_customform ) > 0 OR count ( $forms_in_trash ) > 0) {
		update_option ( 'fca_eoi_allow_customform', 'true' );
	} else {
		update_option ( 'fca_eoi_allow_customform', 'false' );
	}
	
}

function fca_eoi_get_html( $form_id, $fca_eoi_meta ) {
	
	$selected_layout = $fca_eoi_meta[ 'layout' ];

	if( empty( $selected_layout ) OR empty ( $fca_eoi_meta ) ) {
		return 'Form data missing';
	}
	
	$list_ids = array ( 'mailchimp_list_id', 'campaignmonitor_list_id', 'getresponse_list_id', 'aweber_list_id', 'drip_list_id' );
	$list_id = 'Not Set';
	
	forEach ($list_ids as $id ) {
		if ( !empty ($fca_eoi_meta[$id]) ) {
			$list_id = $fca_eoi_meta[$id];
			break;
		}
	}
		
	$errorTexts = fca_eoi_get_error_texts($fca_eoi_meta);
	
	//determine if ajax or thank you page redirect
	$thanks_mode = fca_eoi_get_thanks_mode($fca_eoi_meta);
	$thank_you_text_color = empty ( $fca_eoi_meta[ 'thank_you_text_color' ] ) ? '#fff' : $fca_eoi_meta[ 'thank_you_text_color' ];
	$thank_you_bg_color = empty ( $fca_eoi_meta[ 'thank_you_bg_color' ] ) ? '#00b894' : $fca_eoi_meta[ 'thank_you_bg_color' ];
	$email_error_text_color = empty ( $fca_eoi_meta[ 'email_error_text_color' ] ) ? '#fff' : $fca_eoi_meta[ 'email_error_text_color' ];
	$email_error_bg_color = empty ( $fca_eoi_meta[ 'email_error_bg_color' ] ) ? '#d63031' : $fca_eoi_meta[ 'email_error_bg_color' ];
	if ( $thanks_mode === 'redirect' ) {
		$mode = empty ( $fca_eoi_meta[ 'redirect_page_mode' ] ) ? 'page' : $fca_eoi_meta[ 'redirect_page_mode' ];
		$home_url = get_site_url();
		if ( $mode === 'page' ) {
			$thanks_page =	get_permalink( K::get_var( 'thank_you_page', $fca_eoi_meta, $home_url ) );
		} else {
			$thanks_page =	empty ( $fca_eoi_meta[ 'thank_you_url' ] ) ? $home_url : $fca_eoi_meta[ 'thank_you_url' ];
		}
		
	} else {
		$thanks_page =	htmlentities ( K::get_var( 'thankyou_ajax', $fca_eoi_meta, 'Thank you! Please check your inbox for your confirmation email.' ), ENT_QUOTES, "UTF-8");
	}
	
	$sub_msg = htmlentities ( K::get_var( 'subscribing_message', $fca_eoi_meta, 'Subscribing...' ), ENT_QUOTES, "UTF-8");
	
	$cookie_dur = K::get_var( 'success_duration', $fca_eoi_meta['publish_lightbox'], 0 );
	
	$layout_helper	= new EasyOptInsLayout( $selected_layout );
	$php_path		= $layout_helper->path_to_resource( 'layout', 'php' );
	$html_path		= $layout_helper->path_to_resource( 'layout', 'html' );
	$html_wrap_path	= $layout_helper->path_to_html_wrapper();
	$scss_path		= $layout_helper->path_to_resource( 'layout', 'scss' );
		
	$form_wrapper = '';
	$form_wrapper_end = '';

	if ( $layout_helper->layout_type != 'lightbox' ) {
		$form_wrapper =
			'<div class="' .
				'fca_eoi_form_wrapper ' .
				$layout_helper->layout_class . '_wrapper ' .
				'fca_eoi_layout_' . $layout_helper->layout_number . '_wrapper' .
			'">';
		$form_wrapper_end = '</div>';
	}
	
	//SET UP TEMPLATE
	
	$layout_html = str_replace('{{{layout}}}', file_get_contents( $html_path ), file_get_contents( $html_wrap_path ));
	
	global $dh_easy_opt_ins_plugin;
	
	if ( $dh_easy_opt_ins_plugin->post_type_obj->settings[ 'distribution' ] === 'free' ) {
		$banding = K::get_var( 'show_fatcatapps_link', $fca_eoi_meta, false );
		$banding = $banding ? "<div class='fca_eoi_layout_fatcatapps_link_wrapper fca_eoi_form_text_element'><a href='https://fatcatapps.com/optincat/' target='_blank'>Powered by Optin Cat</a></div>" : '';
	} else {
		$banding = '';
	}
	
	$animation = get_post_meta( $form_id, 'fca_eoi_animation', true );
	$animation_class = ( empty ( $animation ) || $animation == 'None' ) ? '' : "animated $animation";
	
	
	if ( $layout_helper->layout_type === 'banner' ) { 
		$push_page = K::get_var( 'push_page', $fca_eoi_meta, 'on' );
		$toggle_position = K::get_var( 'toggle_overlay_position', $fca_eoi_meta, 'off' );
		
		if ( $push_page === 'on' && $toggle_position === 'off' ) {
			$push_page = 'down';
		}

		if ( $push_page === 'on' && $toggle_position === 'on'  ) {
			$push_page = 'up';
		}
	} else {
		$push_page = '';
	}
		
	$form_head = $form_wrapper;
	$form_head .= "<div id='fca_eoi_form_$form_id' class='fca_eoi_form_content'>";
	$form_head .= "<form method='post' action='#' class='fca_eoi_form fca_eoi_layout_$layout_helper->layout_number $layout_helper->layout_class $animation_class' ";
	$form_head .= "data-fca_eoi_list_id='$list_id' data-fca_eoi_push_page='$push_page' data-fca_eoi_success_cookie_duration='$cookie_dur' data-fca_eoi_thank_you_page='$thanks_page' data-fca_eoi_thank_you_text_color='$thank_you_text_color' data-fca_eoi_thank_you_bg_color='$thank_you_bg_color' data-fca_eoi_email_error_text_color='$email_error_text_color' data-fca_eoi_email_error_bg_color='$email_error_bg_color' data-fca_eoi_thank_you_mode='$thanks_mode' data-fca_eoi_sub_msg='$sub_msg' novalidate>";
	$form_head .= "<input type='hidden' class='fca_eoi_form_id' name='fca_eoi_form_id' value='$form_id'>";
	
	
	$email_replace = '<input class="fca_eoi_form_input_element" type="email" name="email" placeholder="{{{email_placeholder}}}">';
	
	$options = get_option( 'fca_eoi_settings' );
	if ( !empty ( $options['honeypot'] ) && function_exists( 'fca_eoi_update_honeypot_html' ) ) {
		$email_replace .= '<input class="fca_eoi_form_input_element" type="checkbox" name="i_agree">';
	}

	//FILL TEMPLATE
	$layout_html = str_replace(
		array(
			'<form>',
			'{{{description_copy}}}',
			'{{{headline_copy}}}',
			'{{{image_url}}}',
			'{{{name_field}}}',
			'{{{email_field}}}',
			'{{{submit_button}}}',
			'{{{privacy_copy}}}',
			'{{{fatcatapps_link}}}',
			'</form>',
		),
		array(
			$form_head,
			'<div>{{{description_copy}}}</div>',
			'<div>{{{headline_copy}}}</div>',
			K::get_var( 'image_input', $fca_eoi_meta, false ),
			"<input class='fca_eoi_form_input_element' type='text' name='name' placeholder='{{{name_placeholder}}}'>",
			$email_replace,
			'<input class="fca_eoi_form_button_element" type="submit" value="{{{button_copy}}}">',
			'<div>{{{privacy_copy}}}</div>',
			$banding,
			'<input type="hidden" name="fca_eoi" value="1">
			<input type="hidden" name="fca_eoi_error_texts_email" class="fca_eoi_error_texts_email" value="' . htmlspecialchars ($errorTexts['invalid_email']) . '">
			<input type="hidden" name="fca_eoi_error_texts_required" class="fca_eoi_error_texts_required" value="' . htmlspecialchars ($errorTexts['field_required']) . '"></form></div>' . $form_wrapper_end,
		),
		$layout_html
	);
	
	//ADD TEXTS
	$show_headline = K::get_var( 'show_headline_field', $fca_eoi_meta, 'on' );
	$show_description = K::get_var( 'show_description_field', $fca_eoi_meta, 'on' );
	$show_privacy = K::get_var( 'show_privacy_field', $fca_eoi_meta, 'on' );
	
	$headline_copy = $show_headline == 'off' ? '' : $fca_eoi_meta[ 'headline_copy' ];
	$description_copy  = $show_description == 'off' ? '' : $fca_eoi_meta[ 'description_copy' ];
	
	$name_placeholder = $fca_eoi_meta[ 'name_placeholder' ];
	$email_placeholder = $fca_eoi_meta[ 'email_placeholder' ];
	
	$button_copy = $fca_eoi_meta[ 'button_copy' ];
	$privacy_copy = $show_privacy == 'off' ? '' : $fca_eoi_meta[ 'privacy_copy' ];
			
	$output = str_replace( 
		array(
			'{{{headline_copy}}}',
			'{{{description_copy}}}',
			'{{{name_placeholder}}}',
			'{{{email_placeholder}}}',
			'{{{button_copy}}}',
			'{{{privacy_copy}}}',
		),
		array(
			$headline_copy,
			$description_copy,
			$name_placeholder,
			$email_placeholder,
			$button_copy,
			$privacy_copy,
		),
		$layout_html
	);
	
	$output = apply_filters( 'fca_eoi_alter_form', $output, $fca_eoi_meta );

	return $output;
}

//ADD KEYUP ACTION TO TINY MCE VISUAL EDITOR
function fca_eoi_tiny_mce_before_init( $initArray ) {
	global $post;
	if ( is_object ( $post ) && $post->post_type == 'easy-opt-ins' ) {
		$initArray['setup'] = <<<JS
[function(ed) {
	ed.on('keyup', function(ed, e) {
		jQuery('[name="fca_eoi[description_copy]"]').html(tinymce.activeEditor.getContent()).trigger('keyup')
	});

}][0]
JS;
		return $initArray;
	} else {
		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'fca_eoi_tiny_mce_before_init' );

//CONVERTS OLD EDITOR SAVE FORMAT TO NEW FORMAT
function fca_eoi_convert_post_meta() {
	require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';

	$args = array(
		'post_type' => 'easy-opt-ins',
		'post_status' => 'publish',
		'posts_per_page'=> -1, 
	);
	
	$posts_array = get_posts( $args );
	
	$settings = array (
		'form_background_color_selector' => 'background-color',
		'form_border_color_selector' => 'border-color',
		'headline_font_size_selector' => 'font-size',
		'headline_font_color_selector' => 'color',
		'headline_background_color_selector' => 'background-color',
		'description_font_size_selector' => 'font-size',
		'description_font_color_selector' => 'color',
		'name_font_size_selector' => 'font-size',
		'name_font_color_selector' => 'color',
		'name_background_color_selector' => 'background-color',
		'name_border_color_selector' => 'border-color',
		'email_font_size_selector' => 'font-size',
		'email_font_color_selector' => 'color',
		'email_background_color_selector' => 'background-color',
		'email_border_color_selector' => 'border-color',
		'button_font_size_selector' => 'font-size',
		'button_font_color_selector' => 'color',
		'button_background_color_selector' => 'background-color',
		'button_wrapper_background_color_selector' => 'background-color',
		'privacy_font_size_selector' => 'font-size',
		'privacy_font_color_selector' => 'color',
		'branding_font_color_selector' => 'color',	
		
	);

	forEach ( $posts_array as $post ) {
		$form_id = $post->ID;
		$version = get_post_meta( $post->ID, 'fca_eoi_meta_format', true );
		
		//ONLY CONVERT OLD VERSIONS
		if ( $version != '2.0' ) {
			$meta = get_post_meta( $post->ID, 'fca_eoi', true );
			//FIX FOR EMPTY LAYOUT? SET THE DEFAULT
			$layout = empty ( $meta['layout'] ) ? 'postbox_1' : $meta['layout'];
			$new_meta['layout'] = $layout;
				
			$new_meta = $meta;
			
			if ( isSet( $new_meta[$layout] ) ) {
				unset ( $new_meta[$layout] );
			}
			
			$i = 0;
			
			forEach ( $meta[$layout] as $selector => $array ) {
				$type = '';
				
				if ( $i == 0 ) {
					$type = 'form';
				} else if ( strpos($selector, 'headline') !== false ) {
					$type = 'headline';
				} else if ( strpos($selector, 'description') !== false ) {
					$type = 'description';
				} else if ( strpos($selector, 'name') !== false ) {
					$type = 'name';
				} else if ( strpos($selector, 'email') !== false ) {
					$type = 'email';
				} else if ( strpos($selector, 'submit_button_wrapper input:hover') !== false ) {
					$type = 'hover';
				} else if ( strpos($selector, 'submit_button_wrapper input') !== false && strpos($selector, 'hover') === false	) {
					$type = 'button';
				} else if ( strpos($selector, 'privacy') !== false ) {
					$type = 'privacy';
				} else if ( strpos($selector, 'fatcatapps') !== false ) {
					$type = 'branding';
				}
				
				forEach ($array as $css_property => $value ) {
					if ( !empty ( $type ) ) {
						
						if ( $type == 'hover' ) {
							
							$new_meta['button_wrapper_background_color_selector'] = '.fca_eoi_layout_submit_button_wrapper';
							$new_meta['button_wrapper_background_color'] = $value;
													
						} else {
							
							$new_key = $type . '_' . str_replace ('-', '_', $css_property) . '_selector';
							if ( strpos( $new_key, 'color' ) !== false && strpos( $new_key, 'border' ) === false && strpos( $new_key, 'background' ) === false ) {
								$new_key = str_replace ('_color', '_font_color', $new_key);
							}						
							$new_meta[trim($new_key)] = $selector;
							
							$new_value_key = $type . '_' . str_replace ('-', '_', $css_property);
							
							if ( strpos( $new_value_key, 'color' ) !== false && strpos( $new_value_key, 'border' ) === false && strpos( $new_value_key, 'background' ) === false ) {
								$new_value_key =  str_replace ('_color', '_font_color', $new_value_key);
							}
							
							$new_meta[trim($new_value_key)] = $value;
						}
					}
				}
				
				$i++;
			}
			
			//SOME SPECIAL CASES
			if ( empty ( $new_meta['email_font_size'] ) ) {
				$new_meta['email_font_size_selector'] = 'div.fca_eoi_layout_email_field_wrapper';
				$new_meta['email_font_size'] = '13px';
			}
			if ( empty ( $new_meta['name_font_size'] ) ) {
				$new_meta['name_font_size_selector'] = 'div.fca_eoi_layout_name_field_wrapper';
				$new_meta['name_font_size'] = '13px';
			}
			
			//POSSIBLE FIX FOR PUBLICATION MODE
			if ( stripos ( $new_meta[ 'layout' ], 'lightbox') === FALSE && array_key_exists( 'publish_lightbox_mode', $new_meta )) {
				unset ( $new_meta['publish_lightbox_mode'] );
			}
			
			//COMPILE CSS AND SAVE INTO 'HEAD' META
						
			// General CSS for all forms
			$css = "<style type='text/css' class='fca-eoi-style'>.fca_eoi_form{ margin: auto; } .fca_eoi_form p { width: auto; } #fca_eoi_form_$form_id input{ max-width: 9999px; }";
				
			// CACHE (ALMSOT) ALL THE OUTPUT HERE
			$layout_helper = new EasyOptInsLayout( $new_meta[ 'layout' ] );
			$scss_path = $layout_helper->path_to_resource( 'layout', 'scss' );
			
			if ( file_exists( $scss_path ) ) {
				$css_path = str_replace ( '.scss' , '_min.css', $scss_path );
				$css_file = file_get_contents( $css_path );
			}
			
			$show_name = !empty( $new_meta['show_name_field'] ) ? $new_meta['show_name_field'] : false;
			
			if ( !$show_name ) {
				$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_email_field_wrapper {width: 100% !important;}";
				$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_name_field_wrapper {display: none !important;}";
			}
			
			//ADD CSS FROM FILE
			$css .= $css_file;
			
			//ADD CUSTOM CSS FROM SAVE
			$added_widget_3_css_rule = false;
			
			foreach ( $settings as $key => $property ) {
				$input = str_replace ( '_selector', '', $key);
													
				$selector = empty ( $new_meta[$key] ) ? '' : $new_meta[$key];
				
				if ( !empty ( $selector ) ) {


					//SPECIAL CASE FOR WIDGET 3
					if ( $selector == '.fca_eoi_layout_3.fca_eoi_layout_widget div.fca_eoi_layout_headline_copy_wrapper div' && !$added_widget_3_css_rule && $input == 'headline_background_color' ) {
						$css .= "#fca_eoi_form_$form_id form.fca_eoi_layout_3.fca_eoi_layout_widget svg.fca_eoi_layout_headline_copy_triangle { fill: $new_meta[$input] !important; }";
						$added_widget_3_css_rule = true;
					}
										
					$css .= "#fca_eoi_form_$form_id $selector {	$property: $new_meta[$input] !important; }";
				}
			}
		
			$head = $css . '</style>';
			
			$html = fca_eoi_get_html ( $form_id, $new_meta );
							
			$head = $head . $html;
	
			update_post_meta( $post->ID, 'fca_eoi', $new_meta );
			update_post_meta( $post->ID, 'fca_eoi_meta_format', '2.0' );
			update_post_meta( $post->ID, 'fca_eoi_head', $head );
			
		}
	}
}

function fca_eoi_convert_option_save() {
	
	$old_options = get_option('paf');
	$license = get_option('fca_eoi_license_key');
	
	$new_options = array(
		'license_key' => $license, 
	);
	
	$options = array ( 

		'eoi_powerup_custom_css' => 'custom_css',
		'eoi_powerup_optin_bait' => 'optin_bait',
		'eoi_powerup_animation' => 'animation',
		'eoi_powerup_mp_groups' => 'mailchimp_groups',
		
	);
	
	if ( !empty( $old_options ) ) {
		forEach ( $options as $key => $value ) {	
			if ( !empty ( $old_options[$key] ) && $old_options[$key][0] == 'on' ) {
				$new_options[$value] = 1;
			}
		}
		
		update_option( 'fca_eoi_settings', $new_options );
		delete_option ( 'paf' );
		delete_option ( 'fca_eoi_license_key' );
		
	}
	
}
