<?php
//
// Handle Powerups / Settings page 
//
 
// Load all powerups
foreach ( glob( FCA_EOI_PLUGIN_DIR . 'powerups/*', GLOB_ONLYDIR ) as $powerup_path ) {  
	require_once "$powerup_path/powerup.php";
}

function fca_eoi_register_setting_page() {
	add_submenu_page(
		'edit.php?post_type=easy-opt-ins',
		__('Settings', 'easy-opt-ins'),
		__('Settings', 'easy-opt-ins'),
		'manage_options',
		'fca_eoi_settings_page',
		'fca_eoi_settings_page'
	);
	
}
add_action('admin_menu', 'fca_eoi_register_setting_page');

function fca_eoi_register_settings() {
	global $dh_easy_opt_ins_plugin;
	$fca_eoi_options = get_option( 'fca_eoi_settings' );
	if( !$fca_eoi_options ) { 
		add_option( 'fca_eoi_settings' );
	}
	
	if ( $dh_easy_opt_ins_plugin->distro != 'free' ) {
		
		add_settings_section( 'fca_eoi_license_settings_section', __('License', 'easy-opt-ins'), false, 'fca_eoi_settings_page' );		
		
	}

	add_settings_section( 'fca_eoi_powerup_settings_section', __('Powerups', 'easy-opt-ins'), false, 'fca_eoi_settings_page' );
	add_settings_section( 'fca_eoi_gdpr_settings_section', __('EU GDPR Compliance', 'easy-opt-ins'), 'fca_eoi_gdpr_settings_heading', 'fca_eoi_settings_page' );
	
	$option_fields = apply_filters( 'fca_eoi_setting_filter', array() );
	
	foreach( $option_fields as $option ) {
		$id = empty ( $option[0] ) ? '' : $option[0];
		$friendly_text = empty ( $option[1] ) ? '' : $option[1];
		$callback_function = empty ( $option[2] ) ? '' : $option[2];
		$setting_heading = empty ( $option[3] ) ? '' : $option[3];
		
		
		add_settings_field( "fca_eoi_settings[$id]", $friendly_text, $callback_function, 'fca_eoi_settings_page', $setting_heading, $option );
	}
	
	register_setting( 'fca_eoi_main_settings', 'fca_eoi_settings', 'fca_eoi_settings_sanitize_callback' );	
	
}
add_action('admin_init', 'fca_eoi_register_settings');

function fca_eoi_settings_sanitize_callback( $data ) {
	
	$status = get_option( 'fca_eoi_license_status' );
	$settings = get_option( 'fca_eoi_settings' );
	
	$deactivate = empty( $data['fca_eoi_license_deactivate'] ) ? false : true;
	$data['license_key'] = empty( $data['license_key'] ) ? '' : trim( esc_textarea ( $data['license_key'] ) );
	if ( $deactivate ) {
		$key = !empty($settings['license_key']) ? $settings['license_key'] : $data['license_key'];
		fca_eoi_deactivate_license( $key );
		$data['license_key'] = '';
	} else if ( $status != 'valid' && !empty( $data['license_key'] ) ) {
		fca_eoi_activate_license( $data['license_key'] );
	}
	return $data;
}

function fca_eoi_checkbox_callback( $args ) {
	$option_name = $args[0];

	$options = get_option( 'fca_eoi_settings' );
	
	$help_text = empty( $args[4] ) ? '' : $args[4];
	
	/***** SET TO DEFAULT IF THIS OPTION HAS NEVER BEEN SET *****/
	if ( !is_array( $options ) ) {
		$options = array();
	}
	if ( !isset ( $options[ $option_name ] ) ) {
		$options[ $option_name ] = 0;
	}
	
	$html = "<div class='onoffswitch'>";
		$html .= "<input type='checkbox' class='onoffswitch-checkbox' id='fca_eoi_settings[$option_name]' style='display:none;' name='fca_eoi_settings[$option_name]' value='1' " . checked( 1, $options[ $option_name ], false ) . '/>';
		$html .= "<label class='onoffswitch-label' for='fca_eoi_settings[$option_name]'><span class='onoffswitch-inner' data-content-on='".__('ON', 'easy-opt-ins')."' data-content-off='".__('OFF', 'easy-opt-ins')."'><span class='onoffswitch-switch'></span></span></label>";
	$html .= "</div>";
	$html .= "<p class='fca_eoi_help_text'>$help_text</p>";
	   
	echo $html;
}

function fca_eoi_text_box_callback( $args ) {

	$option_name = $args[0];
	
	$placeholder = empty( $args[4] ) ? '' : $args[4];
	
	$help_text = empty( $args[5] ) ? '' : $args[5];
	
	$options = get_option( 'fca_eoi_settings' );
	
	/***** SET TO DEFAULT IF THIS OPTION HAS NEVER BEEN SET *****/
	
	if ( !isset ( $options[ $option_name ] ) ) {
		$value = $options[ $option_name ] = '';
	}else {
		$value = esc_textarea( $options[ $option_name ] );
	}

	$html = "<input type='text' class='fca_eoi_settings_text_input' id='fca_eoi_settings[$option_name]' name='fca_eoi_settings[$option_name]' value='$value' placeholder='$placeholder' />"; 
	
	$html .= "<p class='fca_eoi_help_text'>$help_text</p>";
   
	echo $html;
}

function fca_eoi_wysi_callback( $args ) {

	$option_name = $args[0];
	
	$placeholder = empty( $args[4] ) ? '' : $args[4];
	
	$help_text = empty( $args[5] ) ? '' : $args[5];
	
	$options = get_option( 'fca_eoi_settings' );
	
	if ( !is_array( $options ) ) {
		$options = array();
	}
	/***** SET TO DEFAULT IF THIS OPTION HAS NEVER BEEN SET *****/
	if ( !isset ( $options[ $option_name ] ) ) {
		if ( $option_name === 'consent_headline' ) {
			$value = $options[ $option_name ] = "In order to comply with privacy regulations in the European Union we'll need you to provide consent before confirming you to our email list:";
		} else {
			$value = $options[ $option_name ] = '';
		}
	} else {
		$value = esc_textarea( $options[ $option_name ] );
	}

	$html = '';
	$html .= "<div class='fca-wysiwyg-nav' style='display:none;'>";
		$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-text-group">';
			$html .= '<button type="button" data-wysihtml5-command="bold" class="fca-nav-bold fca-nav-rounded-left" ><span class="dashicons dashicons-editor-bold"></span></button>';
			$html .= '<button type="button" data-wysihtml5-command="italic" class="fca-nav-italic fca-nav-no-border" ><span class="dashicons dashicons-editor-italic"></span></button>';
			$html .= '<button type="button" data-wysihtml5-command="underline" class="fca-nav-underline fca-nav-rounded-right" ><span class="dashicons dashicons-editor-underline"></span></button>';
		$html .= "</div>";
				
		$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-link-group">';
			$html .= '<button type="button" data-wysihtml5-command="createLink" style="border-right: 0;" class="fca-wysiwyg-link-group fca-nav-rounded-left"><span class="dashicons dashicons-admin-links"></span></button>';
			$html .= '<button type="button" data-wysihtml5-command="unlink" class="fca-wysiwyg-link-group fca-nav-rounded-right"><span class="dashicons dashicons-editor-unlink"></span></button>';
		$html .= "</div>";
		
		$html .= '<div class="fca-wysiwyg-url-dialog" data-wysihtml5-dialog="createLink" style="display: none">';
			$html .= '<input data-wysihtml5-dialog-field="href" value="http://">';
			$html .= '<a class="button button-secondary" data-wysihtml5-dialog-action="cancel">' . __('Cancel', 'quiz-cat') . '</a>';
			$html .= '<a class="button button-primary" data-wysihtml5-dialog-action="save">' . __('OK', 'quiz-cat') . '</a>';
		$html .= "</div>";
		$html .= '<button class="fca-wysiwyg-view-html action" type="button" data-wysihtml5-action="change_view">HTML</button>';

	$html .= "</div>";
	$html .= "<textarea class='fca_eoi_wysi_input fca-wysiwyg-html' id='fca_eoi_settings[$option_name]' name='fca_eoi_settings[$option_name]' placeholder='$placeholder' />$value</textarea>"; 
	
	if ( $help_text ) {
		$html .= "<p class='fca_eoi_help_text'>$help_text</p>";
	}
   
	echo $html;
}

function fca_eoi_settings_page(){
	
	wp_enqueue_style( 'fca_eoi_settings_page_css', FCA_EOI_PLUGIN_URL . '/assets/powerups/powerup-page.min.css', array(), FCA_EOI_VER );
	wp_enqueue_style( 'fca_eoi_wysi_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysi.css', array(), FCA_EOI_VER );
	wp_enqueue_script( 'fca_eoi_wysi_core_js', FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysihtml.min.js', array(), FCA_EOI_VER, true );
	wp_enqueue_script( 'fca_eoi_settings_page_js', FCA_EOI_PLUGIN_URL . '/assets/powerups/powerup-page.min.js', array( 'jquery', 'fca_eoi_wysi_core_js' ), FCA_EOI_VER, true );
	wp_localize_script( 'fca_eoi_settings_page_js', 'fcaEoiSettings', array( 'css' => FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysi.css' ) );
	
	do_action('fca_eoi_setting_page_enqueue');
	
	ob_start(); ?>
	<form method='post' action='options.php' id='fca_eoi_save_settings_form' style='display:none;'>
		<?php 
			settings_fields( 'fca_eoi_main_settings' );
			do_settings_sections( 'fca_eoi_settings_page' );
		?>
		
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save', 'easy-opt-ins')?>"  />
	
	</form>
	<?php 
	echo ob_get_clean();
	
}