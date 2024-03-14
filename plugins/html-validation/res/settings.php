<?php
/**
 * SETTING FUNCTIONS
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Register settings and fields.
 **/
function html_validation_menu() {
	add_management_page(
		__( 'HTML Validation', 'html-validation' ),
		__( 'HTML Validation', 'html-validation' ),
		'manage_options',
		'html-validation',
		'html_validation_tools'
	);
}
add_action( 'admin_init', 'html_validation_register_settings' );

/**
 * Create options page
 **/
function html_validation_options_page() {

		// reset settings.
	if ( isset( $_GET['purge'] ) && is_numeric( $_GET['purge'] ) ) {
		html_validation_purge_all();
		// schedule initial scan.
		wp_clear_scheduled_hook( 'html_validation_initial_scan_cron_hook' );
		if ( ! wp_next_scheduled( 'html_validation_inital_scan_cron_hook' ) ) {
			wp_schedule_event( time(), 'htmlvalidation5minutes', 'html_validation_initial_scan_cron_hook' );
		}
		html_validation_reset_scan_flag();
		update_option( 'html_validation_completed_scan', '' );
		echo '<p class="notice notice-success">';
			esc_html_e( 'Report data has been reset.', 'html-validation' );
		echo '</p>';
	}

	echo '<p>';
	echo '<a href="' . esc_url( get_site_url() ) . '/wp-admin/admin.php?page=html_validation/report.php" class="btn btn-primary"><i class="fas fa-file-alt" aria-hidden="true"></i> ';
	esc_html_e( 'View Report', 'html-validation' );
	echo '</a> ';

	echo '<a href="' . esc_url( get_site_url() ) . '/wp-admin/admin.php?page=html_validation/settings.php&purge=1" onclick="return confirm(\'';
	esc_html_e( 'Are you sure you want to reset all error data?', 'html-validation' );
	echo '\')" onkeypress="return confirm(\'';
	esc_html_e( 'Are you sure you want to reset all error data?', 'html-validation' );
	echo '\')" class="btn btn-primary"><i class="fas fa-eraser" aria-hidden="true"></i> ';
	esc_html_e( 'Reset Report Data', 'html-validation' );
	echo '</a> ';
	echo '</p>';
	?>
<form method="post" action="options.php" id="html_validation_options">
	<?php
	echo '<p class="html_validation_instructions">';
	echo wp_kses_post( 'Once activated, the HTML Validation plugin uses WordPress cron to scan your website content in the background. Adjust the "Content to Monitor" below, then check back later to review the results and correct HTML validation errors. The progress bar on the report screen will indicate scan progress. HTML Validation is provided by <a href="https://about.validator.nu/">Validator.nu</a>. Please refer to the provided <a href="https://about.validator.nu/#tos">privacy policy and terms of use</a>. Posts may also be scanned using the Validate HTML link provided on the "All Posts" screen.', 'html-validation' );
	echo '</p>';

	settings_fields( 'html-validation-group' );

	html_validation_do_settings_sections_tabs( 'html-validation-admin' );
	echo '<input id="html_validation_settings_save" name="Submit" type="submit" value="';
	esc_html_e( 'Save Changes', 'html-validation' );
	echo '" />';
	?>
</form>
	<?php
}

/**
 * Do tabbed sections
 **/
function html_validation_do_settings_sections_tabs( $page ) {

	global $wp_settings_sections, $wp_settings_fields;

	if ( ! isset( $wp_settings_sections[ $page ] ) ) :
		return;
	endif;

	echo '<div id="abb-tabs">';
	echo '<ul>';

	foreach ( (array) $wp_settings_sections[ $page ] as $section ) :

		if ( ! isset( $section['title'] ) ) {
			continue;
		}

		printf(
			'<li><a href="#%1$s">%2$s</a></li>',
			esc_attr( $section['id'] ),     /** %1$s - The ID of the tab */
			esc_attr( $section['title'] )   /** %2$s - The Title of the section */
		);

	endforeach;
	echo '</ul>';

	foreach ( (array) $wp_settings_sections[ $page ] as $section ) :

		printf(
			'<div id="%1$s">',
			esc_attr( $section['id'] )     /** %1$s - The ID of the tab */
		);

		if ( ! isset( $section['title'] ) ) {
			continue;
		}

		if ( $section['callback'] ) {
			call_user_func( $section['callback'], $section );
		}

		if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
			continue;
		}

		echo '<div class="form-table">';
		html_validation_do_settings_fields( $page, $section['id'] );
		echo '</div>';

		echo '</div>';

	endforeach;

	echo '</div>';
}
/**
 * Display WordPress settings without table
 **/
function html_validation_do_settings_fields( $page, $section ) {
	global $wp_settings_fields;

	if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
		return;
	}
	$count = 0;
	$class = 'html_validation_white';
	foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {

		echo '<div ';
		if ( ! empty( $field['args']['class'] ) ) {
			echo ' class="' . esc_attr( $field['args']['class'] ) . esc_attr( $class ) . '"';
		} else {
			echo ' class="' . esc_attr( $class ) . '"';
		}

		echo '>';

		call_user_func( $field['callback'], $field['args'] );

		if ( 1 === $count ) {
			$count = 0;
			$class = 'html_validation_white';
		} else {
				++$count;
			$class = 'html_validation_grey';
		}
		echo '</div>';
	}
}
/**
 * Register settings and fields.
 **/
function html_validation_register_settings() {

	add_settings_section(
		'html-validation-section',
		__( 'Scan Settings', 'html-validation' ),
		'html_validation_scan_settings_section_text',
		'html-validation-admin'
	);

	register_setting(
		'html-validation-group',
		'html_validation_cron_frequency',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'html_validation_validate_cron_frequency',
		)
	);
	add_settings_field(
		'html_validation_cron_frequency',
		'',
		'html_validation_cron_frequency_field',
		'html-validation-admin',
		'html-validation-section'
	);

	add_settings_section(
		'html-validation-monitor',
		__( 'Content to Monitor', 'html-validation' ),
		'html_validation_content_text',
		'html-validation-admin'
	);

	register_setting(
		'html-validation-group',
		'html_validation_scan_themes',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'html_validation_validate_scan_themes',
		)
	);
	add_settings_field(
		'html_validation_scan_themes',
		'',
		'html_validation_settings_scan_themes',
		'html-validation-admin',
		'html-validation-monitor'
	);

	register_setting(
		'html-validation-group',
		'html_validation_posttypes',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'html_validation_validate_post_types',
		)
	);
	add_settings_field(
		'html_validation_posttypes',
		'',
		'html_validation_post_types_field',
		'html-validation-admin',
		'html-validation-monitor'
	);

	register_setting(
		'html-validation-group',
		'html_validation_terms',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'html_validation_validate_terms',
		)
	);
	add_settings_field(
		'html_validation_terms',
		'',
		'html_validation_terms_field',
		'html-validation-admin',
		'html-validation-monitor'
	);

	register_setting(
		'html-validation-group',
		'html_validation_external_sources',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'html_validation_validate_false_default_true',
		)
	);

	add_settings_field(
		'html_validation_scan_external_sources',
		'',
		'html_validation_external_sources_field',
		'html-validation-admin',
		'html-validation-monitor'
	);

	add_settings_section(
		'html-validation-rules',
		__( 'Error Types', 'html-validation' ),
		'html_validation_ruletext',
		'html-validation-admin'
	);

	register_setting(
		'html-validation-group',
		'html_validation_error_types',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'html_validation_validate_error_types',
		)
	);
	add_settings_field(
		'html_validation_error_types',
		'',
		'html_validation_error_types_field',
		'html-validation-admin',
		'html-validation-rules'
	);

	add_settings_section(
		'html-validation-ignored',
		__( 'Ignored Content', 'html-validation' ),
		'html_validation_ignored_content_text',
		'html-validation-admin'
	);
}
/**
 * Display content to monitor text.
 **/
function html_validation_content_text() {
	echo '<div class="ada_compliance_settings_text">';
	echo '<p>';
	esc_html_e( 'Choose options for automatically correcting issues found on your website.', 'html-validation-pro' );
	echo '</p>';
	echo '<p class="wp-ada-important">';
	esc_html_e( 'The settings marked with a red bar on the left should be reviewed and set according to your website requirements.', 'html-validation-pro' );
	echo '</p>';
	echo '</div>';
}
/**
 * Display rule text.
 **/
function html_validation_ruletext() {
}
/**
 * Display filter text.
 **/
function html_validation_filtertext() {
}
/**
 * Display scn heading text
 **/
function html_validation_scan_settings_section_text() {
}
/**
 * Display ignored content text
 **/
function html_validation_ignored_content_text() {

	// display ignored posts.
	html_validation_settings_ignored_files();
}


// DEFINE THEME FILE SCAN ITEMS.
$html_validation_theme_scan_items[0] = __( 'Blog Home', 'html-validation' );
$html_validation_theme_scan_items[1] = __( '404 Page', 'html-validation' );
$html_validation_theme_scan_items[2] = __( 'Search Page', 'html-validation' );
$html_validation_theme_scan_items[3] = __( 'Author Page', 'html-validation' );

/**
 * Select if theme files should be scanned
 */
function html_validation_settings_scan_themes() {
	global $html_validation_theme_scan_items;
	html_validation_purge_theme_scan_item();
	$scan_themes = get_option( 'html_validation_scan_themes', array( 'Blog Home', '404 Page', 'Search Page' ) );

	echo '<fieldset style="margin-top: 20px;"  class="wp-ada-important">';
	echo '<legend>';
	esc_html_e( 'Choose the theme content to be monitored:', 'html-validation' );
	echo '</legend>';
	foreach ( $html_validation_theme_scan_items as $key => $value ) {
		echo '<input type="checkbox" name="html_validation_scan_themes[]" id="html_validation_scan_themes_' . esc_attr( str_replace( ' ', '_', $value ) ) . '" value="' . esc_attr( $value ) . '" ';
		if ( is_array( $scan_themes ) && in_array( $value, $scan_themes ) ) {
			echo ' checked';
		}
		echo '><label for="html_validation_scan_themes_' . esc_attr( str_replace( ' ', '_', $value ) ) . '">';
		echo esc_attr( strtolower( $value ) );
		echo '</label> <br />';
	}
	echo '</fieldset>';
}
/**
 * Validate scan themes
 **/
function html_validation_validate_scan_themes( $values ) {
	$accepted = array( 'Blog Home', '404 Page', 'Search Page', 'Author Page' );
	if ( ! is_array( $accepted ) ) {
		$accepted = array();
	}
	if ( is_array( $values ) || '' === $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( ! in_array( $value, $accepted ) ) {
					unset( $values[ $key ] );
				}
			}
		}
		return html_validation_sanitize_text_or_array_field( $values );
	}
	return $accepted;
}

/**
 * Post types
 **/
function html_validation_post_types_field() {
	html_validation_purge_post_types();
	$checked = get_option( 'html_validation_posttypes', array( 'page', 'post' ) );
	echo '<fieldset style="margin-top: 20px;">';

	echo '<fieldset class="wp-ada-important">';
	echo '<legend  >';
	esc_html_e( 'Choose the post types to be monitored (includes archives):', 'html-validation' );
	echo '</legend>';
	echo '<label for="html_validation_posttypes_attachment"><input id="html_validation_posttypes_attachment" type="checkbox"  name="html_validation_posttypes[]" value="attachment"';
	if ( is_array( $checked ) && in_array( 'attachment', $checked ) ) {
		echo ' checked="checked"';
	}
		echo ' /> attachment </label><br />';
	global $wpdb;

	// post types to ignore.
	$ignore_these_post_types   = array();
	$ignore_these_post_types[] = 'acf-field';
	$ignore_these_post_types[] = 'acf-field-group';
	$ignore_these_post_types[] = 'accordions';
	$ignore_these_post_types[] = 'component';
	$ignore_these_post_types[] = 'nav_menu_item';
	$ignore_these_post_types[] = 'oembed_cache';
	$ignore_these_post_types[] = 'wp_block';

	$post_type_list = array();
	// set default post types.
	$post_type_list[] = 'post';
	$post_type_list[] = 'page';

	$results = $wpdb->get_results( 'SELECT distinct(post_type) FROM ' . $wpdb->prefix . 'posts', ARRAY_A );

	if ( $results ) {
		foreach ( $results as $row ) {
			if ( ! in_array( $row['post_type'], $post_type_list ) ) {
				$post_type_list[] = $row['post_type'];
			}
		}
	}
	foreach ( $post_type_list as $post_type ) {
		if ( post_type_supports( $post_type, 'editor' ) && ! in_array( $post_type, $ignore_these_post_types ) ) {

			echo '<label for="html_validation_posttypes_' . esc_attr( $post_type ) . '"><input id="html_validation_posttypes_' . esc_attr( $post_type ) . '" type="checkbox" name="html_validation_posttypes[]" value="' . esc_attr( $post_type ) . '"';
			if ( is_array( $checked ) && in_array( $post_type, $checked ) ) {
				echo ' checked="checked"';
			}
			echo ' /> ' . esc_attr( $post_type ) . '</label><br />';
		}
	}
	echo '</fieldset>';
}

/**
 * Validate post types
 **/
function html_validation_validate_post_types( $values ) {
	global $wpdb;
	$post_type_list = array();
	$results        = $wpdb->get_results( 'SELECT distinct(post_type) FROM ' . $wpdb->prefix . 'posts', ARRAY_A );
	if ( $results ) {
		foreach ( $results as $row ) {
			if ( ! in_array( $row['post_type'], $post_type_list ) ) {
				$post_type_list[] = $row['post_type'];
			}
		}
	}
	if ( is_array( $values ) || '' == $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( ! in_array( $value, $post_type_list ) ) {
					unset( $values[ $key ] );
				}
			}
		}
		return html_validation_sanitize_text_or_array_field( $values );
	}
	return array( 'page', 'post' );
}

/**
 * Display terms field
 **/
function html_validation_terms_field() {
	html_validation_purge_terms();
	echo '<fieldset style="margin-top: 20px;"  class="wp-ada-important">';
	echo '<legend>';
	esc_html_e( 'Choose the term or category content to be monitored:', 'html-validation' );
	echo '</legend>';

	global $wpdb;

	// terms to ignore.
	$ada_ignore_types   = array();
	$ada_ignore_types[] = 'nav_menu';

	$checked = get_option( 'html_validation_terms', array( 'category' ) );

	$term_list = array();
	// set default term list.
	$term_list[] = 'category';

	$results = $wpdb->get_results( 'SELECT distinct(taxonomy) FROM ' . $wpdb->prefix . 'term_taxonomy', ARRAY_A );

	if ( $results ) {
		foreach ( $results as $row ) {
			if ( ! in_array( $row['taxonomy'], $term_list ) ) {
				$term_list[] = $row['taxonomy'];
			}
		}
	}

	foreach ( $term_list as $term ) {
		if ( ! in_array( $term, $ada_ignore_types ) ) {
			echo '<label for="html_validation_terms_' . esc_attr( $term ) . '"><input id="html_validation_terms_' . esc_attr( $term ) . '" type="checkbox" name="html_validation_terms[]" value="' . esc_attr( $term ) . '"';
			if ( is_array( $checked ) && in_array( $term, $checked ) ) {
				echo ' checked="checked"';
			}
			echo ' /> ' . esc_attr( $term ) . '</label><br />';
		}
	}
	echo '</fieldset>';
}

/**
 * Validate terms
 **/
function html_validation_validate_terms( $values ) {
	global $wpdb;
	$term_list = array();
	$results   = $wpdb->get_results( 'SELECT distinct(taxonomy) FROM ' . $wpdb->prefix . 'term_taxonomy', ARRAY_A );
	if ( $results ) {
		foreach ( $results as $row ) {
			if ( ! in_array( $row['taxonomy'], $term_list ) ) {
				$term_list[] = $row['taxonomy'];
			}
		}
	}
	if ( is_array( $values ) || '' == $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( ! in_array( $value, $term_list ) ) {
					unset( $values[ $key ] );
				}
			}
		}
		return html_validation_sanitize_text_or_array_field( $values );
	}
	return array( 'category' );
}

/**
 * Scan external sources
 **/
function html_validation_external_sources_field() {
	$external_sources = get_option( 'html_validation_external_sources', 'true' );

	echo '<fieldset >';
	echo '<legend>';
	esc_html_e( 'Validate links to content that have been identified by the ', 'html-validation' );
	echo '<a href="https://www.alumnionlineservices.com/">';
	esc_html_e( '  WP ADA Compliance Check Plugin', 'html-validation' );
	echo '.</a>';
	if ( ! is_plugin_active( 'wp-ada-compliance/wp-ada-compliance.php' ) ) {
		echo '<span class="html_validation_Ignored">';
		esc_html_e( '** the  WP ADA Compliance Check Plugin is not installed.', 'html-validation' );
		echo '</span>';
	}
	echo '</legend>';
	echo '<br />';

	echo '<input type="radio" name="html_validation_external_sources" id="html_validation_external_sources_true" value="true" ';
	if ( 'true' === $external_sources ) {
		echo ' checked';
	}
	echo '><label for="html_validation_external_sources_true">';
	esc_html_e( 'Yes', 'html-validation' );
	echo '</label> ';

	echo '<input type="radio" name="html_validation_external_sources" id="html_validation_external_sources_false" value="false" ';
	if ( 'false' === $external_sources ) {
		echo ' checked';
	}
	echo '><label for="html_validation_external_sources_false">';
	esc_html_e( 'No', 'html-validation' );
	echo '</label> ';

	echo '</fieldset>';
}


/**
 * Choose the cron frequency
 **/
function html_validation_cron_frequency_field() {

	// reset cron frequncy.
	html_validation_set_auto_scan_cron();

	$setting = get_option( 'html_validation_cron_frequency', 'daily' );

	echo '<fieldset>';
	echo '<legend>';
	esc_html_e( 'The WordPress cron frequency for the initial scan is every 5 minutes. Select the cron frequency for conducting scans after the initial scan completes. (WordPress cron must be configured)', 'html-validation' );
	echo '</legend>';
	echo '<br />';
	echo '<input type="radio" name="html_validation_cron_frequency" id="html_validation_cron_frequency_none" value="false" ';
	if ( 'false' === $setting ) {
		echo ' checked';
	}
	echo '><label for="html_validation_cron_frequency_none">';
	esc_html_e( 'disable scans', 'html-validation' );
	echo '</label> ';
	echo '<br />';
	echo '<input type="radio" name="html_validation_cron_frequency" id="html_validation_cron_frequency_daily" value="daily" ';
	if ( 'daily' === $setting ) {
		echo ' checked';
	}
	echo '><label for="html_validation_cron_frequency_daily">';
	esc_html_e( 'daily (default)', 'html-validation' );
	echo '</label> ';
	echo '<br />';
	echo '<input type="radio" name="html_validation_cron_frequency" id="html_validation_cron_frequency_twicedaily" value="twicedaily" ';
	if ( 'twicedaily' === $setting ) {
		echo ' checked';
	}
	echo '><label for="html_validation_cron_frequency_twicedaily">';
	esc_html_e( 'twice daily', 'html-validation' );
	echo '</label> ';
	echo '<br />';
	echo '<input type="radio" name="html_validation_cron_frequency" id="html_validation_cron_frequency_hourly" value="hourly" ';
	if ( 'hourly' === $setting ) {
		echo ' checked';
	}
	echo '><label for="html_validation_cron_frequency_hourly">';
	esc_html_e( 'hourly ', 'html-validation' );
	echo '</label> ';
	echo '<br />';
}

/**
 * Validate cron frequency
 **/
function html_validation_validate_cron_frequency( $value ) {
	global $wpdb;

	$accepted = array( 'hourly', 'twicedaily', 'daily', 'false' );

	if ( in_array( $value, $accepted ) ) {
		return html_validation_sanitize_text_or_array_field( $value );
	}

	return 'hourly';
}

/**
 * Choose the error levels to include
 **/
function html_validation_error_types_field() {
	html_validation_purge_errors_by_type();
	$setting = get_option( 'html_validation_error_types', array( 'warning', 'error' ) );
	if ( ! is_array( $setting ) ) {
		$setting = array();
	}

	echo '<fieldset>';
	echo '<legend>';
	esc_html_e( 'Select the error types to include:', 'html-validation' );
	echo '</legend>';
	echo '<br />';
	echo '<input type="checkbox" name="html_validation_error_types[]" id="html_validation_error_types_error" value="error" ';
	if ( in_array( 'error', $setting ) ) {
		echo ' checked';
	}
	echo '><label for="html_validation_error_types_error">';
	esc_html_e( 'Error - MUST BE corrected to insure compliance with HTML specification. ', 'html-validation' );
	echo '</label> ';
	echo '<br />';
	echo '<input type="checkbox" name="html_validation_error_types[]" id="html_validation_error_types_warning" value="warning" ';
	if ( in_array( 'warning', $setting ) ) {
		echo ' checked';
	}
	echo '><label for="html_validation_error_types_warning">';
	esc_html_e( 'Warning - Normally informational alerts to provide additional information about errors or recomendations provided for convenience only.', 'html-validation' );
	echo '</label> ';

	echo '</fieldset>';
}

/**
 * Validate error types
 **/
function html_validation_validate_error_types( $values ) {
	global $wpdb;

	$accepted = array( 'error', 'warning' );

	if ( is_array( $values ) || '' == $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( ! in_array( $value, $accepted ) ) {
					unset( $values[ $key ] );
				}
			}
		}
		return html_validation_sanitize_text_or_array_field( $values );
	}

	return $accepted;
}

/**
 * Display ignored files
 **/
function html_validation_settings_ignored_files() {
	global $wpdb;
	echo '<fieldset>';
	echo '<legend style="font-weight:bold;">';
	esc_html_e( 'The following content is not being checked for issues. Uncheck items to remove them from the ignored list.', 'html-validation' );
	echo '</legend>';
	$found     = 0;
	$posttypes = get_option( 'html_validation_posttypes', array( 'page', 'post' ) );
	if ( ! is_array( $posttypes ) ) {
		$posttypes = array();
	}
	$termtypes = get_option( 'html_validation_terms', array( 'category' ) );
	if ( ! is_array( $termtypes ) ) {
		$termtypes = array();
	}
	$scan_themes = get_option( 'html_validation_scan_themes', array( 'Blog Home', '404 Page', 'Search Page' ) );
	if ( ! is_array( $scan_themes ) ) {
		$scan_themes = array();
	}

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'html_validation_links where linkignre = %d ', 1 ), ARRAY_A );

	foreach ( $results as $row ) {

		if ( ( 'posttype' === $row['type'] && in_array( $row['subtype'], $posttypes ) ) || ( 'term' === $row['type'] && in_array( $row['subtype'], $termtypes ) ) || ( 'theme' === $row['type'] && in_array( $row['subtype'], $scan_themes ) ) ) {
			echo '<input type="checkbox" data-state="0" name="html_validation_ignored[]" id="html_validation_ignored' . esc_attr( $row['linkid'] ) . '" checked class="html_validation_ignore_link" data-linkid="' . esc_attr( $row['linkid'] ) . '">';
			echo '<label for="html_validation_ignored' . esc_attr( $row['linkid'] ) . '">' . esc_attr( $row['title'] ) . '</label>';
			echo '<br/>';
			$found = 1;
		}
	}
	if ( 0 == $found ) {
		esc_html_e( 'Nothing is being ignored', 'html-validation' );
	}
	echo '</fieldset>';
}


/**
 * CREATE MENU LINKS AND PAGES
 **/

/**
 * Add admin submenu links to menu
 **/
function html_validation_admin_menu() {

	if ( current_user_can( 'edit_pages' ) ) {

		add_menu_page( __( 'HTML Validation', 'html-validation' ), __( 'HTML Validation', 'html-validation' ), 'edit_pages', 'html_validation/report.php', 'html_validation_report_page', 'dashicons-media-document', 10 );

		add_submenu_page( 'html_validation/report.php', __( 'Error Report', 'html-validation' ), __( 'Error Report', 'html-validation' ), 'edit_pages', 'html_validation/report.php', 'html_validation_report_page' );

		// settings link.
		add_submenu_page( 'html_validation/report.php', __( 'Settings', 'html-validation' ), __( 'Settings', 'html-validation' ), 'manage_options', 'html_validation/settings.php', 'html_validation_options_page' );

	}
}
add_action( 'admin_menu', 'html_validation_admin_menu' );




/**
 * REGISTER REST ENDPOINTS
*/
add_action(
	'rest_api_init',
	function () {
		// register endpoint recheck link.
		register_rest_route(
			'html_validation/v1',
			'/recheck',
			array(
				'methods'             => 'GET',
				'callback'            => 'html_validation_rest_recheck_link',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},

			)
		);

		// register endpoint refresh report.
		register_rest_route(
			'html_validation/v1',
			'/refresh',
			array(
				'methods'             => 'GET',
				'callback'            => 'html_validation_rest_refresh_report',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},

			)
		);

		// register endpoint ignore error.
		register_rest_route(
			'html_validation/v1',
			'/ignoreError',
			array(
				'methods'             => 'GET',
				'callback'            => 'html_validation_rest_ignore_error',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},

			)
		);

		// register endpoint ignore link.
		register_rest_route(
			'html_validation/v1',
			'/ignoreLink',
			array(
				'methods'             => 'GET',
				'callback'            => 'html_validation_rest_ignore_link',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},

			)
		);

		// register endpoint ignore duplicates.
		register_rest_route(
			'html_validation/v1',
			'/ignoreDuplicates',
			array(
				'methods'             => 'GET',
				'callback'            => 'html_validation_rest_ignore_duplicates',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},

			)
		);
	}
);


/**
 * Add scan link to post and page list
 **/
function html_validation_add_post_editor_link( $actions, $post ) {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return $actions;
	}

	$posttypes = get_option( 'html_validation_posttypes', array( 'page', 'post' ) );
	if ( is_array( $posttypes ) && in_array( $post->post_type, $posttypes ) ) {
		$linkid = html_validation_get_linkid_by_postid( $post->ID );
		if ( '' === $linkid ) {
			$linkid = $post->ID;
		}
		$link = '<a href="' . get_site_url() . '/wp-admin/admin.php?page=html_validation%2Freport.php&validate=' . esc_attr( $linkid ) . '">' . __( 'Validate HTML', 'html-validation' ) . '</a>';

		if ( array_key_exists( 'w3cvalidatepost', $actions ) ) {
			$actions['w3cvalidatepost'] = $link;
		} else {
			$actions['htmlvalidate'] = $link;
		}
	}

	return $actions;
}
add_filter( 'post_row_actions', 'html_validation_add_post_editor_link', 99, 2 );
add_filter( 'page_row_actions', 'html_validation_add_post_editor_link', 10, 2 );

/**
 * Sanitize array
 **/
function html_validation_sanitize_text_or_array_field( $array_or_string ) {
	if ( is_string( $array_or_string ) ) {
		$array_or_string = sanitize_text_field( $array_or_string );
	} elseif ( is_array( $array_or_string ) ) {
		foreach ( $array_or_string as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = html_validation_sanitize_text_or_array_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
		}
	}

	return $array_or_string;
}


/**
 * Validate true false values DEFAULT to true
 **/
function html_validation_validate_false_default_true( $value ) {
	if ( in_array( $value, array( 'true', 'false' ) ) ) {
		return html_validation_sanitize_text_or_array_field( $value );
	} else {
		return 'true';
	}
}

/**
 * Validate true false values DEFAULT to false
 **/
function html_validation_true_default_false( $value ) {
	if ( in_array( $value, array( 'true', 'false' ) ) ) {
		return html_validation_sanitize_text_or_array_field( $value );
	} else {
		return 'false';
	}
}
?>
