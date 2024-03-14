<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'uhe_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 $cmb CMB2 object.
 *
 * @return bool      True if metabox should show
 */
function uhe_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template.
	if ( get_option( 'page_on_front' ) !== $cmb->object_id ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field $field Field object.
 *
 * @return bool              True if metabox should show
 */
function uhe_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category.
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}

/**
 * Manually render a field.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object.
 */
function uhe_render_row_cb( $field_args, $field ) {
	$classes     = $field->row_classes();
	$id          = $field->args( 'id' );
	$label       = $field->args( 'name' );
	$name        = $field->args( '_name' );
	$value       = $field->escaped_value();
	$description = $field->args( 'description' );
	?>
	<div class="custom-field-row <?php echo esc_attr( $classes ); ?>">
		<p><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label></p>
		<p><input id="<?php echo esc_attr( $id ); ?>" type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo $value; ?>"/></p>
		<p class="description"><?php echo esc_html( $description ); ?></p>
	</div>
	<?php
}

/**
 * Manually render a field column display.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object.
 */
function uhe_display_text_small_column( $field_args, $field ) {
	?>
	<div class="custom-column-display <?php echo esc_attr( $field->row_classes() ); ?>">
		<p><?php echo $field->escaped_value(); ?></p>
		<p class="description"><?php echo esc_html( $field->args( 'description' ) ); ?></p>
	</div>
	<?php
}

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array      $field_args Array of field parameters.
 * @param  CMB2_Field $field      Field object.
 */
function uhe_before_row_if_2( $field_args, $field ) {
	if ( 2 == $field->object_id ) {
		echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
	} else {
		echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
	}
}

add_action( 'cmb2_admin_init', 'uhe_register_demo_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function uhe_register_demo_metabox() {
	$prefix = 'uhe_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$hover_items = new_cmb2_box( array(
		'id'            => $prefix . 'hover_items',
		'title'         => esc_html__( 'Hover Effects Options', 'cmb2' ),
		'object_types'  => array( 'u_hover_effect' ), // Post type
		// 'show_on_cb' => 'uhe_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
		// 'classes_cb' => 'uhe_add_some_classes', // Add classes through a callback.

		/*
		 * The following parameter is any additional arguments passed as $callback_args
		 * to add_meta_box, if/when applicable.
		 *
		 * CMB2 does not use these arguments in the add_meta_box callback, however, these args
		 * are parsed for certain special properties, like determining Gutenberg/block-editor
		 * compatibility.
		 *
		 * Examples:
		 *
		 * - Make sure default editor is used as metabox is not compatible with block editor
		 *      [ '__block_editor_compatible_meta_box' => false/true ]
		 *
		 * - Or declare this box exists for backwards compatibility
		 *      [ '__back_compat_meta_box' => false ]
		 *
		 * More: https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
		 */
		// 'mb_callback_args' => array( '__block_editor_compatible_meta_box' => false ),
	) );

	$group_field_id = $hover_items->add_field( array(
		'id'          => 'options',
		'type'        => 'group',
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'       => __( 'Add New Hover Item', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'        => __( 'Add New Hover Item', 'cmb2' ),
			'remove_button'     => __( 'Remove Hover Item', 'cmb2' ),
			'sortable'          => true,
			// 'closed'         => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$hover_items->add_group_field( $group_field_id, array(
		'name' => 'Hover Image',
		'id'   => $prefix . 'image',
		'type' => 'file',
	) );

	$hover_items->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'Select Effect', 'cmb2' ),
		'desc'             => esc_html__( '', 'cmb2' ),
		'id'               => $prefix . 'effect',
		'type'             => 'select',
		'options'          => array(
			'effect-lily' => 'Effect 1',
                'effect-sadie' => 'Effect 2',
                'effect-honey' => 'Effect 3',
                'effect-layla' => 'Effect 4',
                'effect-zoe' => 'Effect 5',
                'effect-oscar' => 'Effect 6',
                'effect-marley' => 'Effect 7',
                'effect-ruby' => 'Effect 8',
                'effect-roxy' => 'Effect 9',
                'effect-bubba' => 'Effect 10',
                'effect-romeo' => 'Effect 11',
                'effect-dexter' => 'Effect 12',
                'effect-sarah' => 'Effect 13',
                'effect-chico' => 'Effect 14',
                'effect-milo' => 'Effect 15',
                'effect-ming' => 'Effect 16',
                'effect-julia' => 'Effect 17',
                'effect-goliath' => 'Effect 18',
                'effect-hera' => 'Effect 19',
                'effect-winston' => 'Effect 20',
		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$hover_items->add_group_field( $group_field_id, array(
		'name' => 'Title',
		'id'   => 'title',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$hover_items->add_group_field( $group_field_id, array(
		'name' => 'Description',
		'description' => '',
		'id'   => 'desc',
		'type' => 'textarea_small',
	) );

	$hover_items->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'On Click:', 'cmb2' ),
		'desc'             => esc_html__( 'Pro Only', 'cmb2' ),
		'id'               => $prefix . 'on_click',
		'type'             => 'select',
		'options'          => array(
			'do_nothing' => 'Do Nothing',
		),
	) );

	$hover_items->add_group_field( $group_field_id, array(
		'name'             => ( '<h3 align="center">To get all features working, please buy the pro version here <a target="_blank" href="https://codenpy.com/item/ultimate-hover-effects-pro/">Ultimate Hover Effects Pro</a> for only $11</h3>'),
		'desc'             => (''),
		'id'               => 'tton_click',
		'type'             => 'title',
		'options'          => array(
			'do_nothing' => 'Do Nothing',
		),
	) );







	$settings = new_cmb2_box( array(
		'id'            => $prefix . 'settings',
		'title'         => esc_html__( 'Settings', 'cmb2' ),
		'object_types'  => array( 'u_hover_effect' ), // Post type

	) );

	$settings->add_field( array(
		'name'             => esc_html__( 'Number of Column:', 'cmb2' ),
		'desc'             => esc_html__( '', 'cmb2' ),
		'id'               => $prefix . 'column_number',
		'type'             => 'select',
		'options'  => array(
                '1'  => '1',
                '2'   => '2',
                '3'   => '3',
                '4'   => '4',
                //'5'   => '5',
                '6'   => '6',
              ),
        'default'  => '3',
	) );

	$settings->add_field( array(
		'id' => $prefix . 'custom_image_size',
      'type'     => 'select',
      'name'    => 'Image Size:',
      'options'  => array(
        ''  => 'Default',
        'custom'   => 'Custom (pro only)',
      ),
      'default'  => 'custom',
	) );

	$settings->add_field( array(
		'id'    => $prefix . 'extra_class',
      'type'  => 'text',
      'name' => 'Extra CSS Class (Pro Only)',
      'desc' => 'Extra css class for customizing',
      'default'  => '',
	) );

	$settings->add_field( array(
		'id'   => $prefix . 'custom_css',
      'type'  => 'textarea',
      'name' => 'Custom CSS (Pro Only)',
      'desc' => 'You can override css here',
	) );

	$settings->add_field( array(
		'name'             => ( '<h3 align="center">To get all features working, please buy the pro version here <a target="_blank" href="https://codenpy.com/item/ultimate-hover-effects-pro/">Ultimate Hover Effects Pro</a> for only $11</h3>'),
		'desc'             => (''),
		'id'               => 'dddd',
		'type'             => 'title',
	) );







	$typography = new_cmb2_box( array(
		'id'            => $prefix . 'typography',
		'title'         => esc_html__( 'Typography', 'cmb2' ),
		'object_types'  => array( 'u_hover_effect' ), // Post type

	) );

	$typography->add_field( array(
		'name'             => esc_html__( 'Select Heading Font (Pro Only)', 'cmb2' ),
		'desc'             => esc_html__( '', 'cmb2' ),
		'id'               => $prefix . 'heading_font',
		'type'             => 'select',
		'options'  => uhe_google_font()
	) );

	$typography->add_field( array(
		'name'    => 'Heading Font Size (Pro Only)',
		'desc'    => '',
		'default' => '24',
		'id'      => $prefix . 'heading_font_size',
		'type'    => 'text',
	) );

	$typography->add_field( array(
		'id'      => $prefix . 'heading_color',
      'type'    => 'colorpicker',
      'name'   => 'Heading Color',
      'default' => '#fff',
      'desc'    => 'default color is #fff',
	) );

	$typography->add_field( array(
		'id'       => $prefix . 'heading_text_transform',
      'type'     => 'select',
      'name'    => 'Heading Text Transform (Pro Only)',
      'options'  => array(
        ''  => 'Default',
        'uppercase'   => 'Upercase',
      ),
	) );

	$typography->add_field( array(
		'id'       => $prefix . 'heading_italic',
      'type'     => 'select',
      'name'    => 'Heading Font Style (Pro Only)',
      'options'  => array(
        'normal'  => 'Default',
        'italic'   => 'Italic',
      ),
	) );


	$typography->add_field( array(
		'name'             => esc_html__( 'Select Description Font (Pro Only)', 'cmb2' ),
		'desc'             => esc_html__( '', 'cmb2' ),
		'id'               => $prefix . 'desc_font',
		'type'             => 'select',
		'options'  => uhe_google_font()
	) );


	$typography->add_field( array(
		'name'    => 'Description Font Size (Pro Only)',
		'desc'    => '',
		'default' => '24',
		'id'      => $prefix . 'desc_font_size',
		'type'    => 'text',
	) );

	$typography->add_field( array(
		'id'      => $prefix . 'desc_color',
      'type'    => 'colorpicker',
      'name'   => 'Description Color',
      'default' => '#fff',
      'desc'    => 'default color is #fff',
	) );

	$typography->add_field( array(
		'id'       => $prefix . 'desc_text_transform',
      'type'     => 'select',
      'name'    => 'Description Text Transform (Pro Only)',
      'options'  => array(
        ''  => 'Default',
        'uppercase'   => 'Upercase',
      ),
	) );

	$typography->add_field( array(
		'id'       => $prefix . 'desc_italic',
      'type'     => 'select',
      'name'    => 'Description Font Style (Pro Only)',
      'options'  => array(
        'normal'  => 'Default',
        'italic'   => 'Italic',
      ),
	) );

	$typography->add_field( array(
		'id'      => 'desc_line_height',
      'type'    => 'text',
      'name'   => 'Description Text Line Height <br /><span style="color: #d63434">Pro Only</span>',
      'desc'    => 'default value is 22px',
      'default'  => '22',
	) );

	$typography->add_field( array(
		'name'             => ( '<h3 align="center">To get all features working, please buy the pro version here <a target="_blank" href="https://codenpy.com/item/ultimate-hover-effects-pro/">Ultimate Hover Effects Pro</a> for only $11</h3>'),
		'desc'             => (''),
		'id'               => 'ssaon_click',
		'type'             => 'title',s
	) );

}


/**
 * Callback to define the optionss-saved message.
 *
 * @param CMB2  $cmb The CMB2 object.
 * @param array $args {
 *     An array of message arguments
 *
 *     @type bool   $is_options_page Whether current page is this options page.
 *     @type bool   $should_notify   Whether options were saved and we should be notified.
 *     @type bool   $is_updated      Whether options were updated with save (or stayed the same).
 *     @type string $setting         For add_settings_error(), Slug title of the setting to which
 *                                   this error applies.
 *     @type string $code            For add_settings_error(), Slug-name to identify the error.
 *                                   Used as part of 'id' attribute in HTML output.
 *     @type string $message         For add_settings_error(), The formatted message text to display
 *                                   to the user (will be shown inside styled `<div>` and `<p>` tags).
 *                                   Will be 'Settings updated.' if $is_updated is true, else 'Nothing to update.'
 *     @type string $type            For add_settings_error(), Message type, controls HTML class.
 *                                   Accepts 'error', 'updated', '', 'notice-warning', etc.
 *                                   Will be 'updated' if $is_updated is true, else 'notice-warning'.
 * }
 */
function uhe_options_page_message_callback( $cmb, $args ) {
	if ( ! empty( $args['should_notify'] ) ) {

		if ( $args['is_updated'] ) {

			// Modify the updated message.
			$args['message'] = sprintf( esc_html__( '%s &mdash; Updated!', 'cmb2' ), $cmb->prop( 'title' ) );
		}

		add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
	}
}

/**
 * Only show this box in the CMB2 REST API if the user is logged in.
 *
 * @param  bool                 $is_allowed     Whether this box and its fields are allowed to be viewed.
 * @param  CMB2_REST_Controller $cmb_controller The controller object.
 *                                              CMB2 object available via `$cmb_controller->rest_box->cmb`.
 *
 * @return bool                 Whether this box and its fields are allowed to be viewed.
 */
function uhe_limit_rest_view_to_logged_in_users( $is_allowed, $cmb_controller ) {
	if ( ! is_user_logged_in() ) {
		$is_allowed = false;
	}

	return $is_allowed;
}

add_action( 'cmb2_init', 'uhe_register_rest_api_box' );
/**
 * Hook in and add a box to be available in the CMB2 REST API. Can only happen on the 'cmb2_init' hook.
 * More info: https://github.com/CMB2/CMB2/wiki/REST-API
 */
function uhe_register_rest_api_box() {
	$prefix = 'uhe_rest_';

	$cmb_rest = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'REST Test Box', 'cmb2' ),
		'object_types'  => array( 'page' ), // Post type
		'show_in_rest' => WP_REST_Server::ALLMETHODS, // WP_REST_Server::READABLE|WP_REST_Server::EDITABLE, // Determines which HTTP methods the box is visible in.
		// Optional callback to limit box visibility.
		// See: https://github.com/CMB2/CMB2/wiki/REST-API#permissions
		// 'get_box_permissions_check_cb' => 'uhe_limit_rest_view_to_logged_in_users',
	) );

	$cmb_rest->add_field( array(
		'name'       => esc_html__( 'REST Test Text', 'cmb2' ),
		'desc'       => esc_html__( 'Will show in the REST API for this box and for pages.', 'cmb2' ),
		'id'         => $prefix . 'text',
		'type'       => 'text',
	) );

	$cmb_rest->add_field( array(
		'name'       => esc_html__( 'REST Editable Test Text', 'cmb2' ),
		'desc'       => esc_html__( 'Will show in REST API "editable" contexts only (`POST` requests).', 'cmb2' ),
		'id'         => $prefix . 'editable_text',
		'type'       => 'text',
		'show_in_rest' => WP_REST_Server::EDITABLE,// WP_REST_Server::ALLMETHODS|WP_REST_Server::READABLE, // Determines which HTTP methods the field is visible in. Will override the cmb2_box 'show_in_rest' param.
	) );
}
