<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// add admin options page
function vswc_menu_page() {
	add_options_page( esc_attr__( 'Website closed', 'very-simple-website-closed' ), esc_attr__( 'Website closed', 'very-simple-website-closed' ), 'manage_options', 'vswc', 'vswc_options_page' );
}
add_action( 'admin_menu', 'vswc_menu_page' );

// add admin settings and such
function vswc_admin_init() {
	// general section
	add_settings_section( 'vswc-general-section', esc_attr__( 'General', 'very-simple-website-closed' ), '', 'vswc-general' );

	add_settings_field( 'vswc-field-16', esc_attr__( 'Uninstall', 'very-simple-website-closed' ), 'vswc_field_callback_16', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-16', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-1', esc_attr__( 'Preview', 'very-simple-website-closed' ), 'vswc_field_callback_1', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-1', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-15', esc_attr__( 'Exclude', 'very-simple-website-closed' ), 'vswc_field_callback_15', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-15', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-2', esc_attr__( 'Monday', 'very-simple-website-closed' ), 'vswc_field_callback_2', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-2', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-3', esc_attr__( 'Tuesday', 'very-simple-website-closed' ), 'vswc_field_callback_3', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-3', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-4', esc_attr__( 'Wednesday', 'very-simple-website-closed' ), 'vswc_field_callback_4', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-4', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-5', esc_attr__( 'Thursday', 'very-simple-website-closed' ), 'vswc_field_callback_5', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-5', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-6', esc_attr__( 'Friday', 'very-simple-website-closed' ), 'vswc_field_callback_6', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-6', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-7', esc_attr__( 'Saturday', 'very-simple-website-closed' ), 'vswc_field_callback_7', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-7', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-8', esc_attr__( 'Sunday', 'very-simple-website-closed' ), 'vswc_field_callback_8', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-8', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vswc-field-22', esc_attr__( 'Page title', 'very-simple-website-closed' ), 'vswc_field_callback_22', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-22', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-13', esc_attr__( 'Title', 'very-simple-website-closed' ), 'vswc_field_callback_13', 'vswc-general', 'vswc-general-section' );
	register_setting( 'vswc-general-options', 'vswc-setting-13', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-14', esc_attr__( 'Text', 'very-simple-website-closed' ), 'vswc_field_callback_14', 'vswc-general', 'vswc-general-section' );
 	register_setting( 'vswc-general-options', 'vswc-setting-14', array('sanitize_callback' => 'wp_kses_post') );

	// layout section
	add_settings_section( 'vswc-layout-section', esc_attr__( 'Layout', 'very-simple-website-closed' ), '', 'vswc-layout' );

	add_settings_field( 'vswc-field-9', esc_attr__( 'Background', 'very-simple-website-closed' ), 'vswc_field_callback_9', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-9', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-23', esc_attr__( 'Background', 'very-simple-website-closed' ), 'vswc_field_callback_23', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-23', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-20', esc_attr__( 'Color', 'very-simple-website-closed' ), 'vswc_field_callback_20', 'vswc-layout', 'vswc-layout-section' );
 	register_setting( 'vswc-layout-options', 'vswc-setting-20', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-10', esc_attr__( 'Color', 'very-simple-website-closed' ), 'vswc_field_callback_10', 'vswc-layout', 'vswc-layout-section' );
 	register_setting( 'vswc-layout-options', 'vswc-setting-10', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-11', esc_attr__( 'Text align', 'very-simple-website-closed' ), 'vswc_field_callback_11', 'vswc-layout', 'vswc-layout-section' );
 	register_setting( 'vswc-layout-options', 'vswc-setting-11', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-18', esc_attr__( 'Font size', 'very-simple-website-closed' ), 'vswc_field_callback_18', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-18', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-19', esc_attr__( 'Font size', 'very-simple-website-closed' ), 'vswc_field_callback_19', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-19', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-12', esc_attr__( 'Logo', 'very-simple-website-closed' ), 'vswc_field_callback_12', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-12', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-17', esc_attr__( 'Logo', 'very-simple-website-closed' ), 'vswc_field_callback_17', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-17', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vswc-field-21', esc_attr__( 'Custom CSS', 'very-simple-website-closed' ), 'vswc_field_callback_21', 'vswc-layout', 'vswc-layout-section' );
	register_setting( 'vswc-layout-options', 'vswc-setting-21', array('sanitize_callback' => 'wp_kses_post') );
}
add_action( 'admin_init', 'vswc_admin_init' );

// general section callbacks
function vswc_field_callback_16() {
	$value = get_option( 'vswc-setting-16' );
	?>
	<input type='hidden' name='vswc-setting-16' value='no'>
	<label><input type='checkbox' name='vswc-setting-16' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Do not delete settings.', 'very-simple-website-closed' ); ?></label>
	<?php
}

function vswc_field_callback_1() {
	$value = get_option( 'vswc-setting-1' );
	?>
	<input type='hidden' name='vswc-setting-1' value='no'>
	<label><input type='checkbox' name='vswc-setting-1' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Preview mode', 'very-simple-website-closed' ); ?></label>
	<p><i><?php esc_attr_e( 'Logged in administrators can preview the landing page.', 'very-simple-website-closed' ); ?></i></p>
	<?php
}

function vswc_field_callback_15() {
	$value = get_option( 'vswc-setting-15' );
	?>
	<input type='hidden' name='vswc-setting-15' value='no'>
	<label><input type='checkbox' name='vswc-setting-15' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Do not close website for logged in administrators.', 'very-simple-website-closed' ); ?></label>
	<?php
}

function vswc_field_callback_2() {
	$value = get_option( 'vswc-setting-2' );
	?>
	<input type='hidden' name='vswc-setting-2' value='no'>
	<input type='checkbox' name='vswc-setting-2' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_3() {
	$value = get_option( 'vswc-setting-3' );
	?>
	<input type='hidden' name='vswc-setting-3' value='no'>
	<input type='checkbox' name='vswc-setting-3' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_4() {
	$value = get_option( 'vswc-setting-4' );
	?>
	<input type='hidden' name='vswc-setting-4' value='no'>
	<input type='checkbox' name='vswc-setting-4' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_5() {
	$value = get_option( 'vswc-setting-5' );
	?>
	<input type='hidden' name='vswc-setting-5' value='no'>
	<input type='checkbox' name='vswc-setting-5' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_6() {
	$value = get_option( 'vswc-setting-6' );
	?>
	<input type='hidden' name='vswc-setting-6' value='no'>
	<input type='checkbox' name='vswc-setting-6' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_7() {
	$value = get_option( 'vswc-setting-7' );
	?>
	<input type='hidden' name='vswc-setting-7' value='no'>
	<input type='checkbox' name='vswc-setting-7' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_8() {
	$value = get_option( 'vswc-setting-8' );
	?>
	<input type='hidden' name='vswc-setting-8' value='no'>
	<input type='checkbox' name='vswc-setting-8' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
	<?php
}

function vswc_field_callback_22() {
	$value = get_option( 'vswc-setting-22' );
	$placeholder = __( 'Closed', 'very-simple-website-closed' );
	?>
	<input type='text' size='40' name='vswc-setting-22' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vswc_field_callback_13() {
	$value = get_option( 'vswc-setting-13' );
	$placeholder = __( 'Closed', 'very-simple-website-closed' );
	?>
	<input type='text' size='40' name='vswc-setting-13' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vswc_field_callback_14() {
	$value = get_option( 'vswc-setting-14' );
	$placeholder = __( 'Website is closed today.', 'very-simple-website-closed' );
	?>
<textarea name='vswc-setting-14' rows='8' cols='50' maxlength='2000' style='min-width:50%;' placeholder='<?php echo esc_attr($placeholder); ?>'><?php echo wp_kses_post($value); ?></textarea>
	<?php
}

// layout section callbacks
function vswc_field_callback_9() {
	$value = get_option( 'vswc-setting-9' );
	?>
	<input type='text' maxlength='10' id='vswc-setting-9' name='vswc-setting-9' data-default-color='#ffffff' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vswc_field_callback_23() {
	$value = get_option( 'vswc-setting-23' );
	?>
	<input type='number' size='10' name='vswc-setting-23' value='<?php echo esc_attr($value); ?>' />
	<p><?php esc_attr_e( 'Upload your image in the media library and add image ID here.', 'very-simple-website-closed' ) ?></p>
	<p><i><?php esc_attr_e( 'This image will cover whole screen.', 'very-simple-website-closed' ) ?></i></p>
	<?php
}

function vswc_field_callback_20() {
	$value = get_option( 'vswc-setting-20' );
	?>
	<input type='text' maxlength='10' id='vswc-setting-20' name='vswc-setting-20' data-default-color='#333333' value='<?php echo esc_attr($value); ?>' />
	<p><?php esc_attr_e( 'Title', 'very-simple-website-closed' ) ?></p
	<?php
}

function vswc_field_callback_10() {
	$value = get_option( 'vswc-setting-10' );
	?>
	<input type='text' maxlength='10' id='vswc-setting-10' name='vswc-setting-10' data-default-color='#333333' value='<?php echo esc_attr($value); ?>' />
	<p><?php esc_attr_e( 'Text', 'very-simple-website-closed' ) ?></p
	<?php
}

function vswc_field_callback_11() {
	$value = get_option( 'vswc-setting-11' );
 	?>
	<select id='vswc-setting-11' name='vswc-setting-11'>
		<option value='left'<?php echo (esc_attr($value) == 'left')?'selected':''; ?>><?php esc_attr_e( 'Left', 'very-simple-website-closed' ); ?></option>
		<option value='center'<?php echo (esc_attr($value) == 'center')?'selected':''; ?>><?php esc_attr_e( 'Center', 'very-simple-website-closed' ); ?></option>
		<option value='right'<?php echo (esc_attr($value) == 'right')?'selected':''; ?>><?php esc_attr_e( 'Right', 'very-simple-website-closed' ); ?></option>
	</select>
	<?php printf( esc_attr__( 'Default value is %s.', 'very-simple-website-closed' ), __( 'Left', 'very-simple-website-closed' ) ); ?>
	<?php
}

function vswc_field_callback_18() {
	$value = get_option( 'vswc-setting-18' );
	$placeholder = '32';
	?>
	<label><input type='number' size='10' name='vswc-setting-18' min='10' max='100' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-website-closed' ), '32' ); ?></label>
	<p><?php esc_attr_e( 'Title', 'very-simple-website-closed' ) ?></p>
	<?php
}

function vswc_field_callback_19() {
	$value = get_option( 'vswc-setting-19' );
	$placeholder = '16';
	?>
	<label><input type='number' size='10' name='vswc-setting-19' min='10' max='100' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-website-closed' ), '16' ); ?></label>
	<p><?php esc_attr_e( 'Text', 'very-simple-website-closed' ) ?></p>
	<?php
}

function vswc_field_callback_12() {
	$value = get_option( 'vswc-setting-12' );
	?>
	<input type='number' size='10' name='vswc-setting-12' value='<?php echo esc_attr($value); ?>' />
	<p><?php esc_attr_e( 'Upload your image in the media library and add image ID here.', 'very-simple-website-closed' ) ?></p>
	<?php
}

function vswc_field_callback_17() {
	$value = get_option( 'vswc-setting-17' );
	$placeholder = '200';
	?>
	<label><input type='number' size='10' name='vswc-setting-17' min='20' max='2000' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-website-closed' ), '200' ); ?></label>
	<p><i><?php esc_attr_e( 'The width of the logo image in pixels.', 'very-simple-website-closed' ); ?></i></p>
	<?php
}

function vswc_field_callback_21() {
	$value = get_option( 'vswc-setting-21' );
	$placeholder = __( 'Custom CSS', 'very-simple-website-closed' );
	?>
	<textarea class='code' name='vswc-setting-21' rows='8' cols='50' maxlength='2000' style='min-width:50%;' placeholder='<?php echo esc_attr($placeholder); ?>'><?php echo wp_kses_post($value); ?></textarea>
	<?php
	$class_1 = '<code>body</code>';
	$class_2 = '<code>.vswc</code>';
	$class_3 = '<code>.vswc-logo</code>';
	$class_4 = '<code>h1.vswc-title</code>';
	$class_5 = '<code>.vswc-content</code>';
	$sep = ' ';
	?>
	<p><?php echo $class_1.$sep.$class_2.$sep.$class_3.$sep.$class_4.$sep.$class_5; ?></p>
	<?php
}

// display admin options page
function vswc_options_page() {
?>
<div class="wrap">
	<h1><?php esc_attr_e( 'VS Website Closed', 'very-simple-website-closed' ); ?></h1>
	<?php $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general_options'; ?>
	<h2 class="nav-tab-wrapper">
		<a href="?page=vswc&tab=general_options" class="nav-tab <?php echo esc_attr($active_tab) == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'General', 'very-simple-website-closed' ); ?></a>
		<a href="?page=vswc&tab=layout_options" class="nav-tab <?php echo esc_attr($active_tab) == 'layout_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Layout', 'very-simple-website-closed' ); ?></a>
	</h2>
	<?php $preview = get_option( 'vswc-setting-1' );
	$days = array( get_option( 'vswc-setting-2' ), get_option( 'vswc-setting-3' ), get_option( 'vswc-setting-4' ), get_option( 'vswc-setting-5' ), get_option( 'vswc-setting-6' ), get_option( 'vswc-setting-7' ), get_option( 'vswc-setting-8' ) );
	if ($preview == 'yes') { ?>
		<p style="padding:5px 10px;background:#00a32a;color:#fff;font-size:1.2em;"><?php esc_attr_e( 'Preview mode', 'very-simple-website-closed' ); ?></p>
	<?php } elseif (in_array('yes', $days)) { ?>
		<p style="padding:5px 10px;background:#00a32a;color:#fff;font-size:1.2em;"><?php esc_attr_e( 'Website is closed on selected days.', 'very-simple-website-closed' ); ?></p>
	<?php }	?>
	<form action="options.php" method="POST">
		<?php if ( $active_tab == 'general_options' ) {
			settings_fields( 'vswc-general-options' );
			do_settings_sections( 'vswc-general' );
		} else {
			settings_fields( 'vswc-layout-options' );
			do_settings_sections( 'vswc-layout' );
		}
		submit_button(); ?>
	</form
</div>
<?php
}
