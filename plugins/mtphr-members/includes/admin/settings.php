<?php

/* --------------------------------------------------------- */
/* !Get the settings - 1.2.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings') ) {
function mtphr_members_settings() {
	$settings = get_option( 'mtphr_members_settings', array() );
	
	// Translate the settings
	$settings = mtphr_members_translate_settings( $settings );

	return wp_parse_args( $settings, mtphr_members_settings_defaults() );
}
}
if( !function_exists('mtphr_members_settings_defaults') ) {
function mtphr_members_settings_defaults() {
	$defaults = array(
		'slug' => 'galleries',
		'singular_label' => __( 'Gallery', 'mtphr-members' ),
		'plural_label' => __( 'Galleries', 'mtphr-members' ),
		'public' => 'true',
		'has_archive' => 'false'
	);
	return $defaults;
}
}



/* --------------------------------------------------------- */
/* !Initializes the settings page - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_members_initialize_settings() {

	$settings = mtphr_members_settings();


	/* --------------------------------------------------------- */
	/* !Add the setting sections - 1.0.5 */
	/* --------------------------------------------------------- */

	add_settings_section( 'mtphr_members_settings_section', __( 'General settings', 'mtphr-members' ).'<input type="submit" class="button button-small" value="'.__('Save Changes', 'mtphr-members').'">', false, 'mtphr_members_settings' );


	/* --------------------------------------------------------- */
	/* !Add the general settings - 1.0.9 */
	/* --------------------------------------------------------- */

	/* Slug */
	$title = '<div class="mtphr-members-label-alt"><label>'.__( 'Slug', 'mtphr-members' ).'</label><small>'.__('Set the slug for the gallery post type and category', 'mtphr-members').'</small></div>';
	add_settings_field( 'mtphr_members_settings_slug', $title, 'mtphr_members_settings_slug', 'mtphr_members_settings', 'mtphr_members_settings_section', array('settings' => $settings) );

	/* Singular label */
	$title = '<div class="mtphr-members-label-alt"><label>'.__( 'Singular label', 'mtphr-members' ).'</label><small>'.__('Set the singular label for the gallery post type and category', 'mtphr-members').'</small></div>';
	add_settings_field( 'mtphr_members_settings_singular_label', $title, 'mtphr_members_settings_singular_label', 'mtphr_members_settings', 'mtphr_members_settings_section', array('settings' => $settings) );

	/* Plural label */
	$title = '<div class="mtphr-members-label-alt"><label>'.__( 'Plural label', 'mtphr-members' ).'</label><small>'.__('Set the plural label for the gallery post type and category', 'mtphr-members').'</small></div>';
	add_settings_field( 'mtphr_members_settings_plural_label', $title, 'mtphr_members_settings_plural_label', 'mtphr_members_settings', 'mtphr_members_settings_section', array('settings' => $settings) );
	
	/* Public */
	$title = '<div class="mtphr-members-label-alt"><label>'.__( 'Public', 'mtphr-members' ).'</label><small>'.__('Set whether or not the post type should be public and has single posts', 'mtphr-members').'</small></div>';
	add_settings_field( 'mtphr_members_settings_public', $title, 'mtphr_members_settings_public', 'mtphr_members_settings', 'mtphr_members_settings_section', array('settings' => $settings) );
	
	/* Has archive */
	$title = '<div class="mtphr-members-label-alt"><label>'.__( 'Has archive', 'mtphr-members' ).'</label><small>'.__('Set whether or not the post type has an archive page', 'mtphr-members').'</small></div>';
	add_settings_field( 'mtphr_members_settings_has_archive', $title, 'mtphr_members_settings_has_archive', 'mtphr_members_settings', 'mtphr_members_settings_section', array('settings' => $settings) );


	/* --------------------------------------------------------- */
	/* !Register the settings - 1.0.5 */
	/* --------------------------------------------------------- */

	if( false == get_option('mtphr_members_settings') ) {
		add_option( 'mtphr_members_settings' );
	}
	register_setting( 'mtphr_members_settings', 'mtphr_members_settings', 'mtphr_members_settings_sanitize' );

}
add_action( 'admin_init', 'mtphr_members_initialize_settings' );



/* --------------------------------------------------------- */
/* !Slug - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings_slug') ) {
function mtphr_members_settings_slug( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_members_settings_slug">';
		echo '<input type="text" name="mtphr_members_settings[slug]" value="'.$settings['slug'].'" /><br/>';
		echo '<small style="display:block;line-height:13px;font-style:italic;padding-top:3px;">* '.__('You must update permalinks after changing the slug.', 'mtphr-members').'<br/>* '.__('You must not have a page slug with the same name as this slug.', 'mtphr-members').'</small>';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Singular label - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings_singular_label') ) {
function mtphr_members_settings_singular_label( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_members_settings_singular_label">';
		echo '<input type="text" name="mtphr_members_settings[singular_label]" value="'.$settings['singular_label'].'" />';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Plural label - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings_plural_label') ) {
function mtphr_members_settings_plural_label( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_members_settings_plural_label">';
		echo '<input type="text" name="mtphr_members_settings[plural_label]" value="'.$settings['plural_label'].'" />';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Public - 1.0.9 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings_public') ) {
function mtphr_members_settings_public( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_members_settings_public">';
		echo '<select name="mtphr_members_settings[public]">';
			echo '<option value="false" '.selected('false', $settings['public'], false).'>'.__('Not Public', 'mtphr-members').'</option>';
			echo '<option value="true" '.selected('true', $settings['public'], false).'>'.__('Public', 'mtphr-members').'</option>';
		echo '</select>';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Has archive - 1.0.9 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings_has_archive') ) {
function mtphr_members_settings_has_archive( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_members_settings_has_archive">';
		echo '<select name="mtphr_members_settings[has_archive]">';
			echo '<option value="false" '.selected('false', $settings['has_archive'], false).'>'.__('No Archive Page', 'mtphr-members').'</option>';
			echo '<option value="true" '.selected('true', $settings['has_archive'], false).'>'.__('Has Archive Page', 'mtphr-members').'</option>';
		echo '</select>';
	echo '</div>';
}
}




/* --------------------------------------------------------- */
/* !Sanitize the setting fields - 1.2.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_settings_sanitize') ) {
function mtphr_members_settings_sanitize( $fields ) {

	// Create an array for WPML to translate
	$wpml = array();

	// General settings
	if( isset($fields['slug']) ) {
		$fields['slug'] = isset( $fields['slug'] ) ? sanitize_text_field($fields['slug']) : '';
		$fields['singular_label'] = $wpml['singular_label'] = isset( $fields['singular_label'] ) ? sanitize_text_field($fields['singular_label']) : '';
		$fields['plural_label'] = $wpml['plural_label'] = isset( $fields['plural_label'] ) ? sanitize_text_field($fields['plural_label']) : '';
	}
	
	// Register translatable fields
	mtphr_members_register_translate_settings( $wpml );

	return wp_parse_args( $fields, get_option('mtphr_members_settings', array()) );
}
}



/* --------------------------------------------------------- */
/* !Add a menu page to display options - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_members_settings_page() {

	add_submenu_page(
		'edit.php?post_type=mtphr_member',							// The ID of the top-level menu page to which this submenu item belongs
		__('Settings', 'mtphr-members'),							// The value used to populate the browser's title bar when the menu page is active
		__('Settings', 'mtphr-members'),							// The label of this submenu item displayed in the menu
		'administrator',																// What roles are able to access this submenu item
		'mtphr_members_settings_menu',								// The ID used to represent this submenu item
		'mtphr_members_settings_display'							// The callback function used to render the options for this submenu item
	);
}
add_action( 'admin_menu', 'mtphr_members_settings_page' );

/* --------------------------------------------------------- */
/* !Render the settings page - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_members_settings_display() {
	$settings = mtphr_members_settings();
	?>
	<div class="wrap">

		<div id="icon-mtphr_members" class="icon32"></div>
		<h2><?php printf( __('%s Settings', 'mtphr-members'), $settings['singular_label']); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'mtphr_members_settings' );
			do_settings_sections( 'mtphr_members_settings' );
			submit_button();
			?>
		</form>

	</div><!-- /.wrap -->
	<?php
}

