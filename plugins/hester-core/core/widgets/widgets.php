<?php
/**
 * Hester Core - Register new widgets.
 *
 * @package     Hester Core
 * @author      Peregrine Themes <peregrinethemes@gmail.com>
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return list of available widgets.
 *
 * @since 1.0.0
 */
function hester_core_get_widgets() {

	$widgets = array(
		'hester-core-custom-list-widget'  => 'Hester_Core_Custom_List_Widget',
		'hester-core-social-links-widget' => 'Hester_Core_Social_Links_Widget',
		'hester-core-posts-list-widget'   => 'Hester_Core_Posts_List_Widget',
	);

	return apply_filters( 'hester_core_widgets', $widgets );
}

/**
 * Register widgets.
 *
 * @since 1.0.0
 */
function hester_core_register_widgets() {

	// Get available widgets.
	$widgets = hester_core_get_widgets();

	if ( empty( $widgets ) ) {
		return;
	}

	// Path to widgets folder.
	$path = HESTER_CORE_PLUGIN_DIR . 'core/widgets';

	// Register widgets.
	foreach ( $widgets as $key => $value ) {

		// Include class and register widget.
		$widget_path = $path . '/class-' . $key . '.php';

		if ( file_exists( $widget_path ) ) {
			require_once $widget_path;
			register_widget( $value );
		}
	}
}
add_action( 'widgets_init', 'hester_core_register_widgets' );

/**
 * Enqueue admin styles.
 *
 * @since 1.0.0
 */
function hester_core_widgets_enqueue( $page ) {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'hester-admin-widgets-css',
		HESTER_CORE_PLUGIN_URL . 'assets/css/admin-widgets' . $suffix . '.css',
		HESTER_CORE_VERSION,
		true
	);

	wp_enqueue_script(
		'hester-admin-widgets-js',
		HESTER_CORE_PLUGIN_URL . 'assets/js/admin-widgets' . $suffix . '.js',
		array( 'jquery' ),
		HESTER_CORE_VERSION,
		true
	);
}
add_action( 'admin_print_footer_scripts-widgets.php', 'hester_core_widgets_enqueue' );

/**
 * Enqueue front styles.
 *
 * @since 1.0.0
 */
function hester_core_enqueue_widget_assets() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	$widgets = hester_core_get_widgets();

	if ( is_array( $widgets ) ) {
		foreach ( $widgets as $id_slug => $class ) {
			if ( is_active_widget( false, false, $id_slug, true ) ) {

				wp_enqueue_style(
					'hester-core-widget-styles',
					HESTER_CORE_PLUGIN_URL . 'assets/css/widgets' . $suffix . '.css',
					false,
					HESTER_CORE_VERSION,
					'all'
				);
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hester_core_enqueue_widget_assets' );



/**
 * Print repeatable template.
 *
 * @since  1.0.0
 * @return void
 */
function hester_core_print_widget_templates() {
	?>
	<script type="text/template" id="tmpl-hester-core-repeatable-item">
		<div class="hester-repeatable-item open">

			<div class="hester-repeatable-item-title">
				<?php echo esc_attr_x( 'New Item', 'Widget', 'hester-core' ); ?>

				<div class="hester-repeatable-indicator">
					<span class="accordion-section-title" aria-hidden="true"></span>
				</div>

			</div>

			<div class="hester-repeatable-item-content">

				<p>
					<label for="{{data.id}}-{{data.index}}-icon">
						<?php echo esc_attr_x( 'Icon', 'Widget', 'hester-core' ); ?>
					</label>

					<textarea class="widefat" id="{{data.id}}-{{data.index}}-icon" name="{{data.name}}[{{data.index}}][icon]" rows="3"></textarea>
				</p>
				
				<p>
					<label for="{{data.id}}-{{data.index}}-description">
						<?php echo esc_attr_x( 'Item Description', 'Widget', 'hester-core' ); ?>
					</label>
					<textarea class="widefat" id="{{data.id}}-{{data.index}}-description" name="{{data.name}}[{{data.index}}][description]" rows="3"></textarea>
					<em class="description hester-description">
						<?php
						echo wp_kses_post(
							sprintf(
								_x( 'HTML tags allowed.', 'Widget', 'hester-core' ),
								'<a href="http://docs.peregrine-themes.com/" rel="nofollow noreferrer" target="_blank">',
								'</a>'
							)
						);
						?>
					</em>
				</p>

				<p>
					<input type="checkbox" id="{{data.name}}[{{data.index}}][separator]" name="{{data.name}}[{{data.index}}][separator]" />
					<label for="{{data.name}}[{{data.index}}][separator]"><?php _ex( 'Add bottom separator', 'Widget', 'hester-core' ); ?></label>
				</p>

				<button type="button" class="remove-repeatable-item button-link button-link-delete"><?php _ex( 'Remove', 'Widget', 'hester-core' ); ?></button>
			</div>
		</div>
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts-widgets.php', 'hester_core_print_widget_templates' );
