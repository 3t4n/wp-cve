<?php
/**
 * Handle frontend styles & scripts enqueues
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upstream Perform Aggressive Dequeue
 *
 * @return void
 */
function upstream_perform_aggressive_dequeue() {
	global $wp_styles, $wp_scripts;

	// Dequeue styles.
	if ( is_array( $wp_styles->queue ) ) {
		/**
		 * Style Whitelist
		 *
		 * @param array $style_whitelist
		 *
		 * @return array
		 */
		$style_whitelist = (array) apply_filters(
			'upstream_frontend_style_whitelist',
			array(
				'framework',
				'media-views',
				'imgareaselect',
				'wp-color-picker',
			)
		);

		foreach ( $wp_styles->queue as $style ) {
			if ( ! in_array( $style, $style_whitelist, true ) &&
				substr( $style, 0, strlen( 'up-' ) ) !== 'up-' &&
				substr( $style, 0, strlen( 'upstream' ) ) !== 'upstream'
			) {
				wp_dequeue_style( $style );
			}
		}
	}

	// Dequeue scripts.
	if ( is_array( $wp_scripts->queue ) ) {
		/**
		 * Style Whitelist
		 *
		 * @param array $script_whitelist
		 *
		 * @return array
		 */
		$script_whitelist = (array) apply_filters(
			'upstream_frontend_script_whitelist',
			array(
				'media-editor',
				'media-audiovideo',
				'wp-embed',
				'wp-color-picker-alpha',
			)
		);

		foreach ( $wp_scripts->queue as $script ) {
			if (
				! in_array( $script, $script_whitelist, true ) &&
				substr( $script, 0, strlen( 'jquery' ) ) !== 'jquery' &&
				substr( $script, 0, strlen( 'up-' ) ) !== 'up-' &&
				substr( $script, 0, strlen( 'upstream' ) ) !== 'upstream'
			) {
				wp_dequeue_script( $script );
			}
		}
	}

}

/**
 * Removing / Dequeueing All Stylesheets And Scripts
 *
 * @return void
 */
function upstream_enqueue_styles_scripts() {
	global $wp_styles, $wp_scripts;

	$server_data = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array( 'REQUEST_URI' => null );

	if ( get_post_type() === false ) {
		if ( ! upstream_is_project_base_uri( sanitize_text_field( $server_data['REQUEST_URI'] ) ) ) {
			return;
		}
	} elseif ( get_post_type() !== 'project' ) {
		return;
	}

	// Dequeue styles.
	if ( is_array( $wp_styles->queue ) ) {
		/**
		 * Style Whitelist
		 *
		 * @param array $style_whitelist
		 *
		 * @return array
		 */
		$style_whitelist = (array) apply_filters(
			'upstream_frontend_style_whitelist',
			array(
				'framework',
				'media-views',
				'imgareaselect',
				'wp-color-picker',
			)
		);

		foreach ( $wp_styles->queue as $style ) {
			if ( ! in_array( $style, $style_whitelist, true ) ) {
				wp_dequeue_style( $style );
			}
		}
	}

	// Dequeue scripts.
	if ( is_array( $wp_scripts->queue ) ) {
		/**
		 * Script Whitelist
		 *
		 * @param array $script_whitelist
		 *
		 * @return array
		 */
		$script_whitelist = (array) apply_filters(
			'upstream_frontend_script_whitelist',
			array(
				'jquery',
				'media-editor',
				'media-audiovideo',
				'wp-embed',
				'wp-color-picker-alpha',

			)
		);

		foreach ( $wp_scripts->queue as $script ) {
			if ( ! in_array( $script, $script_whitelist, true ) ) {
				wp_dequeue_script( $script );
			}
		}
	}

	$up_url  = UPSTREAM_PLUGIN_URL;
	$up_ver  = UPSTREAM_VERSION;
	$lib_dir = 'templates/assets/libraries/';
	$js_dir  = 'templates/assets/js/';
	$css_dir = 'templates/assets/css/';

	/*
	 * Enqueue styles
	 */

	$dir        = upstream_template_path();
	$maintheme  = trailingslashit( get_template_directory() ) . $dir . 'assets/css/';
	$childtheme = trailingslashit( get_stylesheet_directory() ) . $dir . 'assets/css/';

	if ( ! is_admin() ) {
		wp_enqueue_style( 'up-bootstrap', $up_url . $css_dir . 'bootstrap.min.css', array(), $up_ver, 'all' );
		wp_enqueue_style( 'up-tableexport', $up_url . $css_dir . 'vendor/tableexport.min.css', array(), $up_ver, 'all' );
		wp_enqueue_style( 'up-select2', $up_url . $css_dir . 'vendor/select2.min.css', array(), $up_ver, 'all' );
		wp_enqueue_style( 'up-chosen', $up_url . $lib_dir . 'chosen/chosen.min.css', array(), $up_ver, 'all' );
		wp_enqueue_style( 'up-fontawesome', $up_url . $css_dir . 'fontawesome.min.css', array(), $up_ver, 'all' );
		wp_enqueue_style( 'framework', $up_url . $css_dir . 'framework.css', array(), $up_ver, 'all' );
		wp_enqueue_style(
			'upstream-datepicker',
			$up_url . $js_dir . 'vendor/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker3.css',
			array(),
			$up_ver,
			'all'
		);
		wp_enqueue_style( 'upstream', $up_url . $css_dir . 'upstream.css', array( 'admin-bar' ), $up_ver, 'all' );
		wp_enqueue_style( 'up-bootstrap-migration-5.1.3', $up_url . $css_dir . 'bootstrap-migration-5.1.3.css', array(), $up_ver, 'all' );

		if ( isset( $GLOBALS['login_template'] ) ) {
			wp_enqueue_style( 'up-login', $up_url . $css_dir . 'login.css', array(), $up_ver, 'all' );
		}

		if ( file_exists( $childtheme ) ) {
			$custom = trailingslashit( get_stylesheet_directory_uri() ) . $dir . 'assets/css/upstream-custom.css';
			wp_enqueue_style( 'child-custom', $custom, array(), $up_ver, 'all' );
		}
		if ( file_exists( $maintheme ) ) {
			$custom = trailingslashit( get_template_directory_uri() ) . $dir . 'assets/css/upstream-custom.css';
			wp_enqueue_style( 'theme-custom', $custom, array(), $up_ver, 'all' );
		}

		// Enqueue style for poopy sandbox to complement the admin bar.
		if ( class_exists( 'Sandbox_API' ) && Sandbox_API::getInstance()->is_poopy_site() ) {
			if ( file_exists( ABSPATH . 'wp-content/plugins/' . plugin_dir_path( 'sandbox/sandbox.php' ) . 'static/css/poopy.css' ) ) {
				wp_enqueue_style( 'poopy', plugin_dir_url( 'sandbox/sandbox.php' ) . '/static/css/poopy.css', array(), $up_ver );
			}
		}

		/*
		 * Enqueue scripts
		 */

		wp_enqueue_script( 'up-filesaver', $up_url . $js_dir . 'vendor/FileSaver.min.js', array(), $up_ver, true );
		wp_enqueue_script( 'up-tableexport', $up_url . $js_dir . 'vendor/tableexport.min.js', array(), $up_ver, true );
		wp_enqueue_script( 'up-select2', $up_url . $js_dir . 'vendor/select2.full.min.js', array(), $up_ver, true );
		wp_enqueue_script( 'up-chosen', $up_url . $lib_dir . '/chosen/chosen.jquery.js', array( 'jquery' ), $up_ver, true );
		wp_enqueue_script( 'up-bootstrap', $up_url . $js_dir . 'bootstrap.bundle.min.js', array( 'jquery' ), $up_ver, true );
		wp_enqueue_script( 'up-fastclick', $up_url . $js_dir . 'fastclick.js', array( 'jquery' ), $up_ver, true );
		wp_enqueue_script( 'up-nprogress', $up_url . $js_dir . 'nprogress.js', array( 'jquery' ), $up_ver, true );

		wp_enqueue_script(
			'upstream-datepicker',
			$up_url . $js_dir . 'vendor/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js',
			array( 'jquery', 'up-bootstrap' ),
			$up_ver,
			true
		);
		wp_enqueue_script( 'up-modal', $up_url . $js_dir . 'vendor/modal.min.js', array( 'jquery' ), $up_ver, true );

		wp_enqueue_script(
			'upstream',
			$up_url . $js_dir . 'upstream.js',
			array( 'jquery', 'jquery-ui-sortable', 'up-modal', 'admin-bar' ),
			$up_ver,
			true
		);

		wp_enqueue_script( 'up-google-charts', 'https://www.gstatic.com/charts/loader.js', array( 'jquery' ), $up_ver, false );

		// translators: %s: item name, ie Milestones, Tasks, Bugs, Files, Discussion.
		$no_data_string_template = _x(
			"You haven't created any %s yet",
			'%s: item name, ie Milestones, Tasks, Bugs, Files, Discussion',
			'upstream'
		);

		wp_localize_script(
			'upstream',
			'upstream',
			apply_filters(
				'upstream_localized_javascript',
				array(
					'ajaxurl'              => admin_url( 'admin-ajax.php' ),
					'upload_url'           => admin_url( 'async-upload.php' ),
					'security'             => wp_create_nonce( 'upstream-nonce' ),
					'js_date_format'       => upstream_php_to_js_dateformat(),
					'datepickerDateFormat' => upstream_get_date_format_for_js_datepicker(),
					'langs'                => array(
						'LB_COPY'                 => __( 'Copy', 'upstream' ),
						'LB_CSV'                  => __( 'CSV', 'upstream' ),
						'LB_SEARCH'               => __( 'Search:', 'upstream' ),
						// translators: %s: item name, ie Milestones, Tasks, Bugs, Files, Discussion.
						'MSG_TABLE_NO_DATA_FOUND' => _x(
							"You haven't created any %s yet",
							'%s: item name, ie Milestones, Tasks, Bugs, Files, Discussion',
							'upstream'
						),
						'MSG_NO_MILESTONES_YET'   => sprintf( $no_data_string_template, upstream_milestone_label_plural() ),
						'MSG_NO_TASKS_YET'        => sprintf( $no_data_string_template, upstream_task_label_plural() ),
						'MSG_NO_BUGS_YET'         => sprintf( $no_data_string_template, upstream_bug_label_plural() ),
						'MSG_NO_FILES_YET'        => sprintf( $no_data_string_template, upstream_file_label_plural() ),
						'MSG_NO_DISCUSSION_YET'   => sprintf( $no_data_string_template, upstream_discussion_label() ),
						'LB_SUNDAY'               => __( 'Sunday', 'upstream' ),
						'LB_MONDAY'               => __( 'Monday', 'upstream' ),
						'LB_TUESDAY'              => __( 'Tuesday', 'upstream' ),
						'LB_WEDNESDAY'            => __( 'Wednesday', 'upstream' ),
						'LB_THURSDAY'             => __( 'Thursday', 'upstream' ),
						'LB_FRIDAY'               => __( 'Friday', 'upstream' ),
						'LB_SATURDAY'             => __( 'Saturday', 'upstream' ),
						'LB_SUN'                  => __( 'Sun', 'upstream' ),
						'LB_MON'                  => __( 'Mon', 'upstream' ),
						'LB_TUE'                  => __( 'Tue', 'upstream' ),
						'LB_WED'                  => __( 'Wed', 'upstream' ),
						'LB_THU'                  => __( 'Thu', 'upstream' ),
						'LB_FRI'                  => __( 'Fri', 'upstream' ),
						'LB_SAT'                  => __( 'Sat', 'upstream' ),
						'LB_SU'                   => __( 'Su', 'upstream' ),
						'LB_MO'                   => __( 'Mo', 'upstream' ),
						'LB_TU'                   => __( 'Tu', 'upstream' ),
						'LB_WE'                   => __( 'We', 'upstream' ),
						'LB_TH'                   => __( 'Th', 'upstream' ),
						'LB_FR'                   => __( 'Fr', 'upstream' ),
						'LB_SA'                   => __( 'Sa', 'upstream' ),
						'LB_JANUARY'              => __( 'January', 'upstream' ),
						'LB_FEBRUARY'             => __( 'February', 'upstream' ),
						'LB_MARCH'                => __( 'March', 'upstream' ),
						'LB_APRIL'                => __( 'April', 'upstream' ),
						'LB_MAY'                  => __( 'May', 'upstream' ),
						'LB_JUNE'                 => __( 'June', 'upstream' ),
						'LB_JULY'                 => __( 'July', 'upstream' ),
						'LB_AUGUST'               => __( 'August', 'upstream' ),
						'LB_SEPTEMBER'            => __( 'September', 'upstream' ),
						'LB_OCTOBER'              => __( 'October', 'upstream' ),
						'LB_NOVEMBER'             => __( 'November', 'upstream' ),
						'LB_DECEMBER'             => __( 'December', 'upstream' ),
						'LB_JAN'                  => __( 'Jan', 'upstream' ),
						'LB_FEB'                  => __( 'Feb', 'upstream' ),
						'LB_MAR'                  => __( 'Mar', 'upstream' ),
						'LB_APR'                  => __( 'Apr', 'upstream' ),
						'LB_JUN'                  => __( 'Jun', 'upstream' ),
						'LB_JUL'                  => __( 'Jul', 'upstream' ),
						'LB_AUG'                  => __( 'Aug', 'upstream' ),
						'LB_SEP'                  => __( 'Sep', 'upstream' ),
						'LB_OCT'                  => __( 'Oct', 'upstream' ),
						'LB_NOV'                  => __( 'Nov', 'upstream' ),
						'LB_DEC'                  => __( 'Dec', 'upstream' ),
						'LB_TODAY'                => __( 'Today', 'upstream' ),
						'LB_CLEAR'                => __( 'Clear', 'upstream' ),
						'LB_NO_RESULTS'           => __( 'No results', 'upstream' ),
					),
				)
			)
		);
		do_action( 'upstream_frontend_enqueue_scripts' );
	}
}

add_action(
	'wp_enqueue_scripts',
	'upstream_enqueue_styles_scripts',
	1000
);
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

/**
 * Upstream Deregister Assets
 *
 * @return void
 */
function upstream_deregister_assets() {
	$is_admin  = is_admin();
	$post_type = get_post_type();

	if ( $is_admin || 'project' !== $post_type ) {
		return;
	}

	wp_dequeue_script( 'jquery-ui-datepicker' );
	wp_deregister_script( 'jquery-ui-datepicker' );
}
add_action( 'wp_enqueue_scripts', 'upstream_deregister_assets', 10 );
