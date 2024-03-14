<?php
/** Better Admin Bar's functions.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Output menu items on settings page.
 *
 * @param string $widget_key The widget key.
 */
function swift_control_settings_output_widget_item( $widget_key ) {

	if ( ! isset( $GLOBALS['swift_control_default_settings'] ) || ! isset( $GLOBALS['swift_control_widget_settings'] ) ) {
		return;
	}

	$default_settings        = $GLOBALS['swift_control_default_settings'];
	$default_widget_settings = isset( $default_settings[ $widget_key ] ) ? $default_settings[ $widget_key ] : array();
	$saved_widget_settings   = $GLOBALS['swift_control_widget_settings'];

	// If the setting from database is empty, then take the data from default settings.
	$widget_settings = ! empty( $saved_widget_settings[ $widget_key ] ) ? $saved_widget_settings[ $widget_key ] : array();
	$parsed_settings = swift_control_parse_widget_settings( $widget_key, $widget_settings );

	$icon_class   = $parsed_settings['icon_class'];
	$widget_name  = $parsed_settings['text'];
	$widget_url   = $parsed_settings['url'];
	$tab_target   = $parsed_settings['new_tab'];
	$redirect_url = $parsed_settings['redirect_url'];
	?>

	<li class="widget-item" data-widget-key="<?php echo esc_attr( $widget_key ); ?>">
		<div class="heatbox-cols widget-default-area">
			<div class="widget-item-col drag-wrapper">
				&nbsp;
				<span class="drag-handle"></span>
			</div>
			<div class="widget-item-col icon-wrapper">
				<div class="widget-icon dblclick-trigger">
					<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					<button type="button" class="icon-picker blur-trigger"></button>
				</div>
			</div>
			<div class="widget-item-col text-wrapper">
				<input type="text" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_text" class="text-field widget-text-field dblclick-trigger" value="<?php echo esc_html( $widget_name ); ?>" readonly>
			</div>
			<div class="widget-item-col extra-settings-wrapper">

				<?php if ( isset( $default_widget_settings['redirect_url'] ) || isset( $widget_settings['redirect_url'] ) ) : ?>
					<div class="widget-item-control edit-mode-control redirect-url-setting">
						<input type="url" id="swift_control_<?php echo esc_attr( $widget_key ); ?>_redirect_url" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_redirect_url" class="text-field redirect-url-field" value="<?php echo esc_html( $redirect_url ); ?>" placeholder="<?php _e( 'Redirect Url', 'better-admin-bar' ); ?>">
					</div>
				<?php endif; ?>

				<?php if ( isset( $default_widget_settings['new_tab'] ) || isset( $widget_settings['new_tab'] ) ) : ?>
					<div class="widget-item-control edit-mode-control new-tab-setting">
						<label for="swift_control_<?php echo esc_attr( $widget_key ); ?>_new_tab" class="label checkbox-label blur-trigger">
							<?php _e( 'New tab', 'better-admin-bar' ); ?>
							<input type="checkbox" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_new_tab" id="swift_control_<?php echo esc_attr( $widget_key ); ?>_new_tab" value="1" class="new-tab-field" <?php checked( $tab_target, 1 ); ?>>
							<div class="indicator"></div>
						</label>
					</div>
				<?php endif; ?>

			</div>
			<div class="widget-item-col actions-wrapper">
				<button type="button" class="widget-item-control edit-button">
					<?php _e( 'Edit', 'better-admin-bar' ); ?>
				</button>
			</div>
		</div><!-- .cols -->
	</li><!-- .widget-item -->

	<?php
}

/**
 * Check if swift control has active widgets.
 *
 * @return boolean Whether swift control has active widget or not.
 */
function swift_control_has_active_widgets() {
	// Fetch active widgets from db.
	$active_widgets = get_option( 'swift_control_active_widgets' );

	// Check if this is fresh install.
	if ( false === $active_widgets ) {
		$active_widgets = require __DIR__ . '/inc/active-widgets.php';
	}

	return empty( $active_widgets ) ? false : true;
}

/**
 * Get default active widgets.
 *
 * @return array The default active widgets.
 */
function swift_control_get_default_active_widgets() {
	$default_active_widgets = require __DIR__ . '/inc/active-widgets.php';
	$active_widgets         = array();

	foreach ( $default_active_widgets as $widget_key => $widget_setting ) {
		array_push( $active_widgets, $widget_key );
	}

	return $active_widgets;
}

/**
 * Get un-used default active widgets.
 *
 * @return array The un-used default active widgets.
 */
function swift_control_get_unused_default_active_widgets() {
	$default_active_widgets = swift_control_get_default_active_widgets();
	$active_widgets         = swift_control_get_active_widgets();
	$unused_widgets         = array();

	foreach ( $default_active_widgets as $widget_key ) {
		if ( ! in_array( $widget_key, $active_widgets, true ) ) {
			array_push( $unused_widgets, $widget_key );
		}
	}

	return $unused_widgets;
}

/**
 * Get active widgets.
 *
 * @return array The active widgets.
 */
function swift_control_get_active_widgets() {
	// Fetch active widgets from db.
	$active_widgets = get_option( 'swift_control_active_widgets' );

	// Check if this is fresh install.
	if ( false === $active_widgets ) {
		$active_widgets = swift_control_get_default_active_widgets();
	}

	if ( empty( $active_widgets ) ) {
		return array();
	}

	return $active_widgets;
}

/**
 * Get available widgets.
 *
 * @return array The available widgets.
 */
function swift_control_get_available_widgets() {
	$default_available_widgets = require __DIR__ . '/inc/available-widgets.php';
	$available_widgets         = array();
	$active_widgets            = swift_control_get_active_widgets();
	$unused_active_widgets     = swift_control_get_unused_default_active_widgets();

	// Loop over available widgets and collect their keys.
	foreach ( $default_available_widgets as $widget_key => $default_setting ) {
		array_push( $available_widgets, $widget_key );
	}

	// Merge the un-used default active widgets with existing available widgets.
	$available_widgets = array_merge( $unused_active_widgets, $available_widgets );

	// Reduce the available widgets by the real active widgets.
	$available_widgets = array_diff( $available_widgets, $active_widgets );
	$available_widgets = empty( $available_widgets ) ? array() : $available_widgets;

	return $available_widgets;
}

/**
 * Get locked pro widgets.
 *
 * @return array The locked widgets.
 */
function swift_control_get_locked_widgets() {
	$pro_widgets    = require __DIR__ . '/inc/pro-widgets.php';
	$locked_widgets = array();

	// Loop over pro widgets and collect their keys.
	foreach ( $pro_widgets as $widget_key => $locked_setting ) {
		array_push( $locked_widgets, $widget_key );
	}

	return $locked_widgets;
}

/**
 * Get default settings of both active & available widgets.
 *
 * @return array The default widget settings.
 */
function swift_control_get_default_widget_settings() {
	// Import the default widgets.
	$default_available_widgets = require __DIR__ . '/inc/available-widgets.php';
	$default_active_widgets    = require __DIR__ . '/inc/active-widgets.php';

	// Define default settings.
	$default_settings = array();

	// Loop over active widgets to get default settings.
	foreach ( $default_active_widgets as $widget_key => $default_setting ) {
		$default_settings[ $widget_key ] = $default_setting;
	}

	// Also loop over available widgets.
	foreach ( $default_available_widgets as $widget_key => $default_setting ) {
		// Prevent duplicated widget settings.
		if ( ! isset( $default_settings[ $widget_key ] ) ) {
			$default_settings[ $widget_key ] = $default_setting;
		}
	}

	return $default_settings;
}

/**
 * Get saved settings from database.
 *
 * @return array The saved settings.
 */
function swift_control_get_saved_widget_settings() {
	$settings = get_option( 'swift_control_widget_settings', array() );

	return $settings;
}

/**
 * Get default settings of locked widgets.
 *
 * @return array The locked widget settings.
 */
function swift_control_get_locked_widget_settings() {
	$pro_widgets     = require __DIR__ . '/inc/pro-widgets.php';
	$locked_settings = array();

	// Loop over pro widgets and collect their settings.
	foreach ( $pro_widgets as $widget_key => $locked_setting ) {
		$locked_settings[ $widget_key ] = $locked_setting;
	}

	return $locked_settings;
}

/**
 * Get default color settings.
 *
 * @return array The default color settings.
 */
function swift_control_get_default_color_settings() {
	return array(
		'widget_bg_color'           => '#f5f5f7',
		'widget_bg_color_hover'     => '#ededf0',
		'widget_icon_color'         => '#616666',
		'setting_button_bg_color'   => '#860ee6',
		'setting_button_icon_color' => '#ffffff',
	);
}

/**
 * Get color settings.
 *
 * @return array The color settings.
 */
function swift_control_get_color_settings() {
	$saved_color_settings   = get_option( 'swift_control_color_settings', array() );
	$default_color_settings = swift_control_get_default_color_settings();
	$color_settings         = array();

	foreach ( $default_color_settings as $color_key => $color_value ) {
		$color_settings[ $color_key ] = isset( $saved_color_settings[ $color_key ] ) ? $saved_color_settings[ $color_key ] : $default_color_settings[ $color_key ];
	}

	return $color_settings;
}

/**
 * Get miscellaneous settings.
 *
 * @return array The miscellaneous settings.
 */
function swift_control_get_misc_settings() {
	$misc_settings = get_option( 'swift_control_misc_settings', array() );

	return $misc_settings;
}

/**
 * Get admin bar settings.
 *
 * @return array The admin bar settings.
 */
function swift_control_get_admin_bar_settings() {
	$saved_settings = get_option( 'swift_control_admin_bar_settings', array() );

	$admin_bar_settings = array(
		'remove_by_roles'         => isset( $saved_settings['remove_by_roles'] ) ? $saved_settings['remove_by_roles'] : array(),
		'remove_top_gap'          => isset( $saved_settings['remove_top_gap'] ) ? absint( $saved_settings['remove_top_gap'] ) : 0,
		'fix_menu_item_overflow'  => isset( $saved_settings['fix_menu_item_overflow'] ) ? absint( $saved_settings['fix_menu_item_overflow'] ) : 0,
		'hide_below_screen_width' => isset( $saved_settings['hide_below_screen_width'] ) && '' !== $saved_settings['hide_below_screen_width'] ? absint( $saved_settings['hide_below_screen_width'] ) : '',
		'inactive_opacity'        => isset( $saved_settings['inactive_opacity'] ) && '' !== $saved_settings['inactive_opacity'] ? absint( $saved_settings['inactive_opacity'] ) : '',
		'active_opacity'          => isset( $saved_settings['active_opacity'] ) && '' !== $saved_settings['active_opacity'] ? absint( $saved_settings['active_opacity'] ) : '',
		'auto_hide'               => isset( $saved_settings['auto_hide'] ) ? absint( $saved_settings['auto_hide'] ) : 0,
		'showing_intent'          => isset( $saved_settings['showing_intent'] ) && '' !== $saved_settings['showing_intent'] ? absint( $saved_settings['showing_intent'] ) : '',
		'hiding_intent'           => isset( $saved_settings['hiding_intent'] ) && '' !== $saved_settings['hiding_intent'] ? absint( $saved_settings['hiding_intent'] ) : '',
		'hiding_transition_delay' => isset( $saved_settings['hiding_transition_delay'] ) && '' !== $saved_settings['hiding_transition_delay'] ? absint( $saved_settings['hiding_transition_delay'] ) : '',
		'transition_duration'     => isset( $saved_settings['transition_duration'] ) && '' !== $saved_settings['transition_duration'] ? absint( $saved_settings['transition_duration'] ) : '',
	);

	// Backward compatibility: old value's format was int (0 / 1).
	if ( is_numeric( $admin_bar_settings['remove_by_roles'] ) ) {
		$admin_bar_settings['remove_by_roles'] = absint( $admin_bar_settings['remove_by_roles'] );
		$admin_bar_settings['remove_by_roles'] = $admin_bar_settings['remove_by_roles'] ? array( 'all' ) : array();
	}

	return $admin_bar_settings;
}

/**
 * Get display settings.
 *
 * @return array The display settings.
 */
function swift_control_get_display_settings() {
	$saved_settings = get_option( 'swift_control_display_settings', array() );

	$display_settings = array(
		'disable_swift_control' => isset( $saved_settings['disable_swift_control'] ) ? absint( $saved_settings['disable_swift_control'] ) : 0,
		'remove_indicator'      => isset( $saved_settings['remove_indicator'] ) ? absint( $saved_settings['remove_indicator'] ) : 0,
		'expanded'              => isset( $saved_settings['expanded'] ) ? absint( $saved_settings['expanded'] ) : 0,
	);

	return $display_settings;
}

/**
 * Parse widget settings
 *
 * @param array $widget_key The widget key.
 * @param array $widget_settings The widget settings from database.
 *
 * @return array The parsed widget settings.
 */
function swift_control_parse_widget_settings( $widget_key, $widget_settings ) {
	$default_settings        = swift_control_get_default_widget_settings();
	$default_widget_settings = isset( $default_settings[ $widget_key ] ) ? $default_settings[ $widget_key ] : array();
	$manual_defaults         = array(
		'icon_class'   => '',
		'text'         => '',
		'url'          => '',
		'new_tab'      => '',
		'redirect_url' => '',
	);

	$parsed_defaults = wp_parse_args( $default_widget_settings, $manual_defaults );

	return array(
		'icon_class'   => isset( $widget_settings['icon_class'] ) ? $widget_settings['icon_class'] : $parsed_defaults['icon_class'],
		'text'         => isset( $widget_settings['text'] ) ? $widget_settings['text'] : $parsed_defaults['text'],
		'url'          => isset( $widget_settings['url'] ) ? $widget_settings['url'] : $parsed_defaults['url'],
		'new_tab'      => isset( $widget_settings['new_tab'] ) ? absint( $widget_settings['new_tab'] ) : $parsed_defaults['new_tab'],
		'redirect_url' => isset( $widget_settings['redirect_url'] ) ? $widget_settings['redirect_url'] : $parsed_defaults['redirect_url'],
	);
}

/**
 * Get edit page url.
 *
 * @return string The edit page url.
 */
function swift_control_get_edit_post_url() {
	return get_edit_post_link();
}

/**
 * Check if current page is in edit mode inside page builder.
 *
 * @return boolean
 */
function swift_control_is_inside_page_builder() {
	global $post;

	if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['fl_builder'] ) || isset( $_GET['brizy-edit-iframe'] ) || isset( $_GET['ct_builder'] ) ) {
		return true;
	}

	return false;
}

/**
 * Generate random string.
 *
 * @link https://stackoverflow.com/questions/4356289/php-random-string-generator/#answer-4356295
 *
 * @param integer $length The wanted character length.
 * @return string The random string.
 */
function swift_control_generate_random_string( $length = 10 ) {
	$characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters_length = strlen( $characters );
	$random_string     = '';

	for ( $i = 0; $i < $length; $i++ ) {
		$random_string .= $characters[ rand( 0, $characters_length - 1 ) ];
	}

	return $random_string;
}

/**
 * Parse widget name.
 *
 * @param string $widget_name The widget name.
 * @param string $widget_key The widget key.
 * @param array  $settings The parsed widget settings.
 *
 * @return string The parsed widget name.
 */
function swift_control_parse_widget_name( $widget_name, $widget_key, $settings = array() ) {
	if ( is_singular() ) {
		global $post;

		if ( false !== stripos( $widget_name, '{post_type}' ) ) {
			$post_type_object        = get_post_type_object( $post->post_type );
			$post_type_singular_name = $post_type_object->labels->singular_name;
			$widget_name             = str_ireplace( '{post_type}', $post_type_singular_name, $widget_name );
		}
	} else {
		if ( 'edit_post_type' === $widget_key ) {
			$widget_name = __( 'Disabled', 'better-admin-bar' );
		}
	}

	return ucwords( $widget_name );
}

/**
 * Parse widget url.
 *
 * @param string $widget_url The widget url.
 * @param string $widget_key The widget key.
 * @param array  $settings The parsed widget settings.
 *
 * @return string The parsed widget url.
 */
function swift_control_parse_widget_url( $widget_url, $widget_key, $settings = array() ) {
	global $wp;

	switch ( $widget_key ) {
		case 'theme_customizer':
			$widget_url = add_query_arg(
				array(
					'url' => rawurlencode( home_url( $wp->request ) ),
				),
				$widget_url
			);
			break;
		case 'edit_post_type':
			$widget_url = swift_control_get_edit_post_url();
			break;
	}

	return $widget_url;
}

/**
 * Parse widget class.
 *
 * @param string $widget_class The widget class names.
 * @param string $widget_key The widget key.
 * @param array  $settings The parsed widget settings.
 *
 * @return string The parsed widget class.
 */
function swift_control_parse_widget_class( $widget_class, $widget_key, $settings = array() ) {
	$space_prefix = empty( $widget_class ) ? '' : ' ';

	if ( 'edit_post_type' === $widget_key ) {
		if ( ! is_singular() ) {
			$widget_class .= $space_prefix . 'is-disabled';
		}
	}

	return $widget_class;
}

/**
 * Output quick access panel.
 *
 * @param bool $is_preview Whether the panel is in preview mode inside admin area or not.
 */
function swift_control_quick_access_panel( $is_preview = false ) {

	$setting_url = admin_url( 'options-general.php?page=better-admin-bar' );

	$active_widgets   = swift_control_get_active_widgets();
	$display_settings = swift_control_get_display_settings();

	if ( ! $is_preview && $display_settings['disable_swift_control'] ) {
		return;
	}

	// Get saved widget settings.
	$saved_widget_settings = swift_control_get_saved_widget_settings();

	$widget_list  = '';
	$extra_styles = '';

	$transition_value = 75;
	$showing_delay    = 0;
	$hiding_delay     = ( count( $active_widgets ) - 1 ) * $transition_value;

	foreach ( $active_widgets as $widget_key ) {
		// If the setting from database is empty, then take the data from default settings.
		$widget_settings = ! empty( $saved_widget_settings[ $widget_key ] ) ? $saved_widget_settings[ $widget_key ] : array();
		$parsed_settings = swift_control_parse_widget_settings( $widget_key, $widget_settings );

		$widget_class = '';
		$widget_class = swift_control_parse_widget_class( $widget_class, $widget_key );
		$icon_class   = $parsed_settings['icon_class'];
		$widget_name  = $parsed_settings['text'];
		$widget_name  = swift_control_parse_widget_name( $widget_name, $widget_key );
		$widget_url   = $parsed_settings['url'];
		$widget_url   = swift_control_parse_widget_url( $widget_url, $widget_key );
		$tab_target   = $parsed_settings['new_tab'];
		$redirect_url = $parsed_settings['redirect_url'];
		$target_attr  = $tab_target ? 'target="_blank"' : '';
		$target_attr  = 'logout' === $widget_key ? '' : $target_attr;

		ob_start();
		?>

		<li class="swift-control-widget-item <?php echo esc_attr( $widget_class ); ?>" data-widget-key="<?php echo esc_attr( $widget_key ); ?>">
			<a class="swift-control-widget-link" href="<?php echo esc_url( $widget_url ); ?>" <?php echo $target_attr; ?>>
				<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
			</a>
			<span class="swift-control-widget-title"><?php echo esc_html( $widget_name ); ?></span>
		</li>

		<?php
		$widget_list .= ob_get_clean();

		ob_start();
		?>

		.swift-control-widgets [data-widget-key="<?php echo esc_attr( $widget_key ); ?>"] {
			transition-delay: <?php echo esc_attr( $hiding_delay ); ?>ms;
		}

		.swift-control-widgets.is-expanded [data-widget-key="<?php echo esc_attr( $widget_key ); ?>"] {
			transition-delay: <?php echo esc_attr( $showing_delay ); ?>ms;
		}

		<?php
		$extra_styles  .= ob_get_clean();
		$showing_delay += $transition_value;
		$hiding_delay  -= $transition_value;
	}
	?>

	<style class="swift-control-transition-style">
		<?php
		// We don't hook this extra styles to `class-setup.php` because we need the loop.
		echo $extra_styles;
		?>
	</style>

	<?php if ( $is_preview ) : ?>
		<style class="swift-control-preview-style" data-field-id="setting_button_bg_color"></style>
		<style class="swift-control-preview-style" data-field-id="setting_button_icon_color"></style>
		<style class="swift-control-preview-style" data-field-id="widget_bg_color"></style>
		<style class="swift-control-preview-style" data-field-id="widget_bg_color_hover"></style>
		<style class="swift-control-preview-style" data-field-id="widget_icon_color"></style>

		<style class="swift-control-preview-transition-style"></style>
	<?php endif; ?>

	<?php
	$position       = array();
	$saved_position = get_user_meta( get_current_user_id(), 'swift_control_position', true );
	$saved_position = empty( $saved_position ) ? array() : $saved_position;

	if ( $is_preview ) {
		$position['x']            = 0;
		$position['x_direction']  = 'right';
		$position['y']            = 0;
		$position['y_direction']  = 'bottom';
		$position['y_percentage'] = 0;
	} else {
		$position['x']            = isset( $saved_position['x'] ) ? (float) esc_attr( $saved_position['x'] ) : 0;
		$position['x_direction']  = isset( $saved_position['x_direction'] ) ? esc_attr( $saved_position['x_direction'] ) : 'left';
		$position['y']            = isset( $saved_position['y'] ) ? (float) esc_attr( $saved_position['y'] ) : 0;
		$position['y_direction']  = isset( $saved_position['y_direction'] ) ? esc_attr( $saved_position['y_direction'] ) : 'bottom';
		$position['y_percentage'] = isset( $saved_position['y_percentage'] ) ? (float) esc_attr( $saved_position['y_percentage'] ) : 0;
	}

	$has_arrow_class = $display_settings['remove_indicator'] ? '' : 'has-arrow';
	$expanded_class  = $display_settings['expanded'] ? 'is-expanded' : '';
	$pinned_class    = '';

	if ( $is_preview ) {
		if ( is_rtl() ) {
			$position['x_direction'] = 'left';

			$pinned_class = 'is-pinned-left';
		} else {
			$pinned_class = 'is-pinned-right';
		}
	} else {
		if ( ! isset( $saved_position['x_direction'] ) ) {
			if ( is_rtl() ) {
				$position['x_direction'] = 'right';

				$pinned_class = 'is-pinned-right';
			}
		} else {
			$pinned_class = 'right' === $position['x_direction'] ? 'is-pinned-right' : '';
		}
	}
	?>

	<ul class="swift-control-widgets is-invisible <?php echo esc_attr( $has_arrow_class ); ?> <?php echo esc_attr( $expanded_class ); ?> <?php echo esc_attr( $pinned_class ); ?>">

		<li class="swift-control-widget-item swift-control-widget-setting">
			<a class="swift-control-widget-link" href="<?php echo esc_url( $setting_url ); ?>" target="_blank">
				<i class="fas fa-cog"></i>
			</a>
		</li>

		<?php echo $widget_list; ?>

	</ul>

	<div class="swift-control-helper-panels"></div>

	<?php
	wp_localize_script(
		( $is_preview ? 'swift-control-preview' : 'swift-control' ),
		'swiftControlOpt',
		array(
			'size'          => 55,
			'settingButton' => array(
				'hidingDelay' => $showing_delay + 350,
			),
			'position'      => array(
				'x'            => $position['x'],
				'x_direction'  => $position['x_direction'],
				'y'            => $position['y'],
				'y_direction'  => $position['y_direction'],
				'y_percentage' => $position['y_percentage'],
			),
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'save_position' ),
		)
	);

}
