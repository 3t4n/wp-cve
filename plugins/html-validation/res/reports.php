<?php
/**
 * REPORT FUNCTIONS
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Refresh report
 **/
function html_validation_rest_refresh_report() {

	check_ajax_referer( 'wp_rest', '_wpnonce' );

	html_validation_report_page();
}

/**
 * Display report
 **/
function html_validation_report_page() {
	global $wpdb;

	$excludedups = '';
	$searchtitle = '';
	$linkid      = '';
	$view        = '1';
	$errortype   = '';
	$type        = '';
	$page        = 1;
	$total       = 0;
	$offset      = 0;
	$per_page    = get_option( 'html_validation_errors_per_page', '5' );

	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$requesturi = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

	}

	// process manual recheck.
	if ( isset( $_GET['validate'] ) && is_numeric( $_GET['validate'] ) ) {
		$validate = (int) $_GET['validate'];
		$linkid   = (int) $_GET['validate'];
		html_validation_recheck_link( $validate );
		if ( isset( $requesturi ) ) {
			$_SERVER['REQUEST_URI'] = preg_replace( '/&validate=(\d)*/', '', $requesturi );
		}
	}

	// purge errors for deleted posts and others.
	html_validation_purge_deleted_records();

	$query             = 'SELECT * FROM ' . $wpdb->prefix . 'html_validation_errors inner join ' . $wpdb->prefix . 'html_validation_links on ' . $wpdb->prefix . 'html_validation_errors.linkid = ' . $wpdb->prefix . 'html_validation_links.linkid where %d ';
	$query_variables[] = 1;

	// validate inputs.
	$errors = html_validation_check_form_values();

	// display validation errors.
	if ( count( $errors ) > 0 ) {
		echo '<div class="notice notice-error">';
		foreach ( $errors as $key => $value ) {
			echo '<p>';
			echo esc_attr( $value );
			echo '</p>';
		}
		echo '</div>';

	} else {
		// filter by search.
		if ( isset( $_GET['searchtitle'] ) ) {
			$searchtitle       = sanitize_text_field( wp_unslash( $_GET['searchtitle'] ) );
			$query            .= ' and (' . $wpdb->prefix . 'html_validation_links.title LIKE %s or ' . $wpdb->prefix . 'html_validation_links.linkid = %d)';
			$query_variables[] = '%' . $searchtitle . '%';
			$query_variables[] = $searchtitle;
		}

		// filter by link id.
		if ( isset( $_GET['linkid'] ) && '' != $_GET['linkid'] ) {
			$linkid            = (int) $_GET['linkid'];
			$query            .= ' and ' . $wpdb->prefix . 'html_validation_links.linkid = %d';
			$query_variables[] = $linkid;
		} elseif ( isset( $linkid ) && '' != $linkid ) {
			$query            .= ' and ' . $wpdb->prefix . 'html_validation_links.linkid = %d';
			$query_variables[] = $linkid;
		}

		// filter by view.
		if ( isset( $_GET['adamarker'] ) && '' != $_GET['adamarker'] ) {
			$query            .= ' and ' . $wpdb->prefix . 'html_validation_errors.adamarker = %d';
			$query_variables[] = 1;
		}

		// filter by view.
		if ( isset( $_GET['view'] ) && '' != $_GET['view'] ) {
			$view = (int) $_GET['view'];
		}

		if ( 3 != $view ) {
			$query .= ' and ' . $wpdb->prefix . 'html_validation_errors.errorignre = %d';
			if ( 2 == $view ) {
				$query_variables[] = 1;
			}
			if ( 1 == $view ) {
				$query_variables[] = 0;
			}
		}

		// filter by error type.
		if ( isset( $_GET['errortype'] ) && '' != $_GET['errortype'] ) {
			$errortype         = sanitize_text_field( wp_unslash( $_GET['errortype'] ) );
			$query            .= ' and ' . $wpdb->prefix . 'html_validation_errors.errortype = %s';
			$query_variables[] = $errortype;
		}

		// filter by content type.
		if ( isset( $_GET['type'] ) && '' != $_GET['type'] ) {
			$type              = sanitize_text_field( wp_unslash( $_GET['type'] ) );
			$query            .= ' and ' . $wpdb->prefix . 'html_validation_links.subtype = %s';
			$query_variables[] = $type;
		}

		// exclude duplicates.
		if ( isset( $_GET['excludedups'] ) && '' != $_GET['excludedups'] ) {
			$query      .= ' GROUP BY ' . $wpdb->prefix . 'html_validation_errors.errorcode ';
			$excludedups = 1;
		}

		// create pagination offset.
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		if ( 1 < $page ) {
			$offset = $page * $per_page - $per_page;
		}
	}

	$totalresults = $wpdb->get_results( $wpdb->prepare( $query, $query_variables ) );
	$total        = count( $totalresults );
	$sortby       = $wpdb->prefix . 'html_validation_errors.linkid, ' . $wpdb->prefix . 'html_validation_errors.errorid DESC';

	$query .= " order by $sortby limit %d offset %d";

	$query_variables[] = $per_page;
	$query_variables[] = $offset;

	$results = $wpdb->get_results( $wpdb->prepare( $query, $query_variables ), ARRAY_A );

	echo '<div class="html_validation_report">';
	if ( ! defined( 'REST_REQUEST' ) ) {
		if ( ! is_plugin_active( 'html-validation-pro/html-validation-pro.php' ) ) {
			echo '<p class="html_validation_marketing notice notice-error">';
			esc_html_e( 'The ', 'html_validation' );
			echo '<a href="https://www.alumnionlineservices.com/php-scripts/html-validation/#proext">';
			esc_html_e( 'HTML Validation Pro Extension ', 'html_validation' );
			echo '</a>';
			esc_html_e( 'could be auto correcting ', 'html_validation' );
			echo esc_attr( html_validation_count_autocorrect_issues() );
			esc_html_e( ' issues.', 'html_validation' );
			echo '</p>';
		}
	}
	echo '<div class="html_validation_report_messages"><button aria-label="';
	esc_html_e( 'hide notices', 'html-validation' );
	echo '" class="htmlvalidationhidenotices"><i class="fas fa-times" aria-hidden="true"></i></button><i class="fas fa-info-circle" aria-hidden="true"></i>';
	esc_html_e( 'Look for status messages here.', 'html-validation' );
	echo '</div>';
	echo '<div>';
	html_validation_dropdown_builder( $view, $errortype, $type, $searchtitle, $excludedups );
	html_validation_display_progress();
	echo '</div>';
	if ( $results ) {

		// display the pagination.
		$pagination = paginate_links(
			array(
				'base'      => add_query_arg(
					array(
						'cpage'    => '%#%',
						'validate' => esc_attr($validate),
					),
					esc_url( get_site_url() ) . '/wp-admin/admin.php?page=html_validation%2Freport.php'
				),
				'format'    => '',
				'mid_size'  => 2,
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $total / $per_page ),
				'current'   => $page,
			)
		);

		if ( '' != $pagination ) {
			echo '<p class="html_validation_pagination">' . wp_kses_post( $pagination );
			echo '</p>';
		}
		echo '<table class="html_validation_report_table"><tr>';
		echo '<th scope="column">';
		esc_html_e( 'Title', 'html-validation' );
		echo '</th>';
		echo '<th scope="column">';
		esc_html_e( 'Content Type', 'html-validation' );
		echo '</th>';
		echo '<th scope="column" style="width: 90px;">';
		esc_html_e( 'Error Type', 'html-validation' );
		echo '</th>';
			echo '<th scope="column">';
			esc_html_e( 'Error', 'html-validation' );
		echo '</th>';
		echo '<th scope="column" >';
		esc_html_e( 'Affected Code', 'html-validation' );
		echo '</th>';
		echo '<th scope="column" style="width: 90px;">';
		esc_html_e( 'Actions', 'html-validation' );
		echo '</th></tr>';

		foreach ( $results as $row ) {

			// get edit link.
			$postid = url_to_postid( $row['link'] );
			if ( '' != $postid ) {
				$editlink = get_edit_post_link( $postid );
			}

			$validateurl = 'https://validator.w3.org/nu/?doc=' . esc_url( $row['link'] ) . '&out=html&showsource=yes';

			echo '<tr>';
				echo '<td>';
			echo '<a href="' . esc_url_raw( get_site_url() ) . '/wp-admin/admin.php?page=html_validation%2Freport.php&linkid=' . esc_attr( $row['linkid'] ) . '">';
				echo esc_attr( $row['title'] );
			echo '</a>';
			if ( 1 == $row['errorignre'] ) {
				echo '<span class="html_validation_Ignored">';
				esc_html_e( '** being ignored', 'html-validation' );
				echo '</span>';
			}

			echo '</td>';
			echo '<td>';
			echo esc_attr( $row['subtype'] );
			echo '</td>';

			if ( 'error' == $row['errortype'] ) {
				$class = 'html_validation_errortype_error';
				$icon  = 'fa-ban';
			} else {
				$class = 'html_validation_errortype_info';
				$icon  = 'fa-info-circle';
			}

			echo '<td class="' . esc_attr( $class ) . '">';
			echo '<i class="fas ' . esc_attr( $icon ) . '" aria-hidden="true"></i>';
			echo esc_attr( $row['errortype'] );

			echo '</td>';
			echo '<td>';
			echo esc_attr( $row['error'] );
			if ( 1 == $row['autocorrect_marker'] && ! strstr( $row['error'], 'Duplicate ID' ) ) {
				echo '<span class="html_validation_Ignored">';
				esc_html_e( '** enable the ', 'html-validation' );
				echo '<a href="https://www.alumnionlineservices.com/php-scripts/html-validation/#proext">';
				esc_html_e( ' pro extension ', 'html-validation' );
				echo '</a>';
				esc_html_e( ' content filters to automatically correct this issue.', 'html-validation' );
					echo '</span>';
			}
			if ( 1 == $row['autocorrect_marker'] && strstr( $row['error'], 'Duplicate ID' ) ) {
				echo '<span class="html_validation_Ignored">';
				echo '** <a href="https://www.alumnionlineservices.com/docs/how-do-i-get-the-html-validation-pro-plugin-to-correct-duplicate-id-attributes/">';
				esc_html_e( 'use the make unique option', 'html-validation' );
				echo '</a>';
				esc_html_e( ' included in the pro extension to correct this issue.', 'html-validation' );
				echo '</span>';
			}
			if ( '1' == $row['adamarker'] ) {
					echo '<span class="html_validation_Ignored">';
					esc_html_e( '** this is an ADA compliance error. Use the', 'html-validation' );
				echo '<a href="https://www.alumnionlineservices.com/">';
				esc_html_e( '  WP ADA Compliance Plugin ', 'html-validation' );
				echo '</a>';
				esc_html_e( ' to identify additional compliance errors.', 'html-validation' );
				echo '</span>';
			}

			echo '</td>';
			echo '<td>';
			echo esc_attr( $row['errorcode'] );
			echo '<p><a href="' . esc_url_raw( $validateurl ) . '#source" target="_blank" title="';
			esc_html_e( 'Opens in a new window', 'html-validation' );
			echo '"><i class="fas fa-file-code" aria-hidden="true"></i>';
			esc_html_e( 'View Results & Source on W3.org', 'html-validation' );
			echo '</a></p>';
			echo '</td>';
			echo '<td>';

			echo '<a href="#" class="html-validation-ignore-options-click"><i class="fas fa-cog" aria-hidden="true"></i>';
			esc_html_e( 'Ignore', 'html-validation' );
			echo '</a>';

			echo '<span class="html-validation-ignore-options">';
			if ( '1' == $row['errorignre'] ) {
				echo '<a href="#" class="html_validation_ignore" data-state="0" data-errorid="';
				echo esc_attr( $row['errorid'] );
				echo '" title="';
				esc_html_e( 'Remove ignore from this error.', 'html-validation' );
				echo '"><i class="fas fa-times-circle"></i>';
				esc_html_e( 'This Error', 'html-validation' );
				echo '</a><br />';

				if ( html_validation_check_duplicate_errorcode( $row['errorcode'] ) ) {
					echo '<a href="#" class="html_validation_ignore_duplicates" data-state="0" data-errorid="';
					echo esc_attr( $row['errorid'] );
					echo '" title="';
					esc_html_e( 'Remove ignore from this error and all errors that appear to be duplicates of it.', 'html-validation' );
					echo '"> <i class="fas fa-times-circle"></i>';
					esc_html_e( 'Duplicates', 'html-validation' );
					echo '</a><br />';
				}
			} else {
				echo '<a href="#" class="html_validation_ignore" data-state="1" data-errorid="';
				echo esc_attr( $row['errorid'] );
				echo '" title="';
				esc_html_e( 'Ignore this error.', 'html-validation' );
				echo '"><i class="fas fa-eye-slash" aria-hidden="true"></i>';
				esc_html_e( 'This Error', 'html-validation' );
				echo '</a><br />';
				if ( html_validation_check_duplicate_errorcode( $row['errorcode'] ) ) {
					echo '<a href="#" class="html_validation_ignore_duplicates" data-state="1" data-errorid="';
					echo esc_attr( $row['errorid'] );
					echo '" title="';
					esc_html_e( 'Ignore this issue and all errors that appear to be duplicates of it.', 'html-validation' );
					echo '"> <i class="far fa-clone" aria-hidden="true"></i>';
					esc_html_e( 'Duplicates', 'html-validation' );
					echo '</a><br />';
				}
			}

			echo '<a href="#" class="html_validation_ignore_link" data-state="1" data-linkid="' . esc_attr( $row['linkid'] ) . '" title="';
			esc_html_e( 'Ignore this file.', 'html-validation' );
			echo '"><i class="far fa-file" aria-hidden="true"></i>';
			esc_html_e( 'This File', 'html-validation' );
			echo '</a><br />';
			echo '</span><br />';
			echo '<a href="' . esc_url( $row['link'] ) . '" target="_blank" title="';
			esc_html_e( 'Opens in a new window', 'html-validation' );
			echo '"><i class="fas fa-eye" aria-hidden="true" ></i>';
			esc_html_e( 'View', 'html-validation' );
			echo '</a>';
			echo '<br />';
			if ( isset( $editlink ) && '' != $editlink ) {
				echo '<a href="' . esc_url( $editlink ) . '" target="_blank" title="';
				esc_html_e( 'Opens in a new window', 'html-validation' );
				echo '"><i class="far fa-edit" aria-hidden="true" ></i>';
				esc_html_e( 'Edit', 'html-validation' );
				echo '</a>';
				echo '<br />';
			}
			echo '<a href="' . esc_url_raw( get_site_url() ) . '/wp-admin/admin.php?page=html_validation%2Freport.php&validate=' . esc_attr( $row['linkid'] ) . '" class="html_validation_recheck" data-linkid="' . esc_attr( $row['linkid'] ) . '"><i class="fas fa-sync-alt" aria-hidden="true"></i>';
			esc_html_e( 'Recheck', 'html-validation' );
			echo '</a><br />';
			echo '<a href="' . esc_url_raw( $validateurl ) . '" target="_blank" title="';
			esc_html_e( 'Opens in a new window', 'html-validation' );
			echo '"><i class="fas fa-code" aria-hidden="true"></i>';
			esc_html_e( 'Validate', 'html-validation' );
			echo '</a>';
			echo '<br />';
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	} else {
		echo '<p class="notice notice-warning">';
		esc_html_e( 'Check back later for error results.', 'html-validation' );
		echo '</p>';
	}

	if ( '' != $pagination ) {
		echo '<p class="html_validation_pagination">' . wp_kses_post( $pagination ) . '</p>';
	}
	echo '<p class="html_validation_error_key">';
		echo '<span class="html_validation_errortype_error">';
	echo '<i class="fas fa-ban" aria-hidden="true"></i> ';
		esc_html_e( 'ERROR', 'html-validation' );
	echo '</span>';
	esc_html_e( '- MUST BE corrected to insure compliance with HTML specification. ', 'html-validation' );
	echo '<br /><br />';
	echo '<span class="html_validation_errortype_info">';
	echo '<i class="fas fa-info-circle" aria-hidden="true"></i> ';
	esc_html_e( 'WARNING', 'html-validation' );
	echo '</span>';
	esc_html_e( '- Recomended changes or informational alerts to provide additional information about errors.', 'html-validation' );
		echo '</p>';
	echo '</div>';

	// stop header sent warnings.
	if ( isset( $_GET['_wpnonce'] ) ) {
		exit;
	}
}


/**
 * Create filter drop downs
 **/
function html_validation_dropdown_builder( $view, $errortype, $type, $searchtitle, $excludedups ) {
	global $wpdb;

	echo '<form name="filtererrors" class="filtererrors" action="' . esc_url( get_site_url() ) . '/wp-admin/admin.php" method="get"><input type="hidden" name="page" value="html_validation/report.php" />
<input type="hidden" name="sort" value="' . esc_attr( $view ) . '" />';

	// display view by.
	echo '<label for="view" class="html_validation_label">';
	esc_html_e( 'View: ', 'html-validation' );
	echo '<select name="view" id="view">';
	echo '<option value="3"';
	if ( '3' == $view ) {
		echo ' selected';
	}
	echo '>';
	esc_html_e( 'All', 'html-validation' );
	echo '</option>';
	echo '<option value="2"';
	if ( '2' == $view ) {
		echo ' selected';
	}
	echo '>';
	esc_html_e( 'Ignored', 'html-validation' );
	echo '</option>';
	echo '<option value="1"';
	if ( '1' == $view ) {
		echo ' selected';
	}
	echo '>';
	esc_html_e( 'Current', 'html-validation' );
	echo '</option>';
	echo '</select></label>';

	// filter by error type.

	$results = $wpdb->get_results( 'SELECT distinct(errortype) FROM ' . $wpdb->prefix . 'html_validation_errors order by errortype', ARRAY_A );
	echo '<label for="errortype" class="html_validation_label">';
	esc_html_e( 'Error Type: ', 'html-validation' );
	echo '<select name="errortype" id="errortype">';
	echo '<option value="">';
	esc_html_e( 'Any', 'html-validation' );
	echo '</option>';

	foreach ( $results as $row ) {
		echo '<option value="' . esc_attr( $row['errortype'] ) . '"';
		if ( $errortype == $row['errortype'] ) {
			echo ' selected';
		}
		echo '>';
		echo esc_attr( strtoupper( str_replace( '_', ' ', $row['errortype'] ) ) );
		echo '</option>';
	}
	echo '</select></label>';

	// filter by post type.
	$results = $wpdb->get_results( 'SELECT distinct(subtype) FROM ' . $wpdb->prefix . 'html_validation_links where linkignre = 0', ARRAY_A );
	echo '<label for="type" class="html_validation_label">';
	esc_html_e( 'Content Type: ', 'html-validation' );
	echo '<select name="type" id="type">';
	echo '<option value="">';
	esc_html_e( 'Any', 'html-validation' );
	echo '</option>';

	foreach ( $results as $row ) {
		echo '<option value="' . esc_attr( $row['subtype'] ) . '"';
		if ( $type == $row['subtype'] ) {
			echo ' selected';
		}
		echo '>' . esc_attr( strtolower( $row['subtype'] ) ) . '</option>';
	}

	echo '</select></label> ';

	echo '<input size="20" type="text" name="searchtitle" id="searchtitle" value="';
	if ( '' != $searchtitle ) {
		echo esc_attr( $searchtitle );
	}
	echo '" aria-label="';
	esc_html_e( 'Search', 'html-validation' );
	echo '" placeholder="';
	esc_html_e( 'Search', 'html-validation' );
	echo '" onfocus="this.value=\'\'"></label>';

	echo '<label for="excludedups" class="html_validation_label"><input type="checkbox" name="excludedups" id="excludedups" value="1" ';
	if ( '1' == $excludedups ) {
		echo ' checked ';
	}
	echo '>';
	esc_html_e( 'hide duplicates', 'html-validation' );
	echo '</a>';

	echo '<input type="submit" value="';
	esc_html_e( 'Filter', 'html-validation' );
	echo '" class="btn btn-primary" />';
	echo '<a href="' . esc_url( get_site_url() ) . '/wp-admin/admin.php?page=html_validation%2Freport.php&view=3&errortype=&linkid=&type=" class="btn btn-primary"><i class="fas fa-filter" aria-hidden="true"></i> ';
	esc_html_e( 'Clear Filters', 'html-validation' );
	echo '</a> ';
	echo '</form>';
}

/**
 * Validate input
 **/
function html_validation_check_form_values() {
	global $wpdb;
	$error = array();

	foreach ( $_GET as $key => $value ) {

		// check view.
		if ( 'view' == $key && ! is_numeric( $value ) && '' != $value ) {
			$error[] = __( 'View is invalid', 'html_validation' );
		}

		// error types.
		if ( 'errortype' == $key && ! in_array( $value, array( 'error', 'warning', '' ) ) && '' != $value ) {
			$error[] = __( 'Error Type is invalid', 'html_validation' );
		}

		// check exclude duplicates.
		if ( 'excludedups' == $key && ! is_numeric( $value ) && '' != $value ) {
			$error[] = __( 'Hide Duplicates is invalid', 'html_validation' );
		}

		// check title search.
		if ( 'searchtitle' == $key && ! preg_match( "/^([[:alnum:]]|-|[[:space:]]|[[:punct:]]|')+$/D", $value ) && '' != $value ) {
			$error[] = __( 'Search keyword is invalid', 'html_validation' );
		}

		// content types.
		if ( 'type' == $key ) {
			$accepted = array( '' );
			$results  = $wpdb->get_results( 'SELECT distinct(subtype) FROM ' . $wpdb->prefix . 'html_validation_links', ARRAY_A );
			foreach ( $results as $row ) {
				$accepted[] = $row['subtype'];
			}

			if ( ! in_array( $value, $accepted ) && '' != $value ) {
				$error[] = __( 'Post Type is invalid', 'html_validation' );
			}
		}

		if ( 'cpage' == $key && ! is_numeric( $value ) ) {
			$error[] = __( 'Page is invalid', 'html_validation' );
		}
	} // end get loop.

	return $error;
}
