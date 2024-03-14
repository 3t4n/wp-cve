<?php
/**
 * VALIDATION FUNCTIONS
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}


/**
 * Validate html code
 **/
function html_validation_validate_code( $url, $linkid ) {
	$errortypes = get_option( 'html_validation_error_types', array( 'warning', 'error' ) );

	$validateurl   = 'https://validator.w3.org/nu/?doc=' . $url . '&out=json&showsource=yes';
	$args          = array(
		'timeout'     => 10,
		'redirection' => 10,
		'sslverify'   => false,
	);
	$response      = wp_remote_get( $validateurl, $args );
	$response_code = wp_remote_retrieve_response_code( $response );
	if ( '200' == $response_code ) {

		$content = wp_remote_retrieve_body( $response );

		if ( ! is_wp_error( $content ) ) {

			// mark errors for removal.
			html_validation_mark_error( $linkid, 1 );

			$response_array = json_decode( $content );
			if ( is_array( $response_array ) || is_object( $response_array ) ) {
				foreach ( $response_array as $key => $messages ) {
					if ( is_array( $messages ) ) {
						foreach ( $messages as $key => $errors ) {

							$error = sanitize_text_field( $errors->message );
							if ( isset( $errors->extract ) ) {
								$errorcode = sanitize_text_field( $errors->extract );
							} else {
								$errorcode = '';
							}

							$type = sanitize_text_field( $errors->type );
							if ( 'info' == $type ) {
								$type = 'warning';
							}

							if ( in_array( $type, $errortypes ) ) {

								// save error.
								if ( ! html_validation_error_check( $linkid, $type, $error, $errorcode ) ) {

										html_validation_insert_error( $linkid, $type, $error, $errorcode );
								}
							}
						}
					}
				}
			}
			// purge errors.
			html_validation_purge_missing_error( $linkid );
		}
	}

	return 1;
}

/**
 * Mark errors for removal
 **/
function html_validation_mark_error( $linkid, $direction, $errorid = '', $error = '' ) {
	global $wpdb;

	if ( '' == $errorid ) {

		$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_errors SET purgemarker = %d WHERE linkid = %d', $direction, $linkid ), ARRAY_A );
	} else {
		$autocorrect_marker = html_validation_get_autocorrect_marker( $error );
		$adamarker          = html_validation_get_adamarker( $error );
		$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_errors SET purgemarker = %d, autocorrect_marker = %d, adamarker = %d WHERE errorid = %d and linkid = %d', $direction, $autocorrect_marker, $adamarker, $errorid, $linkid ), ARRAY_A );
	}
}

/**
 * Check if error is already saved
 **/
function html_validation_error_check( $linkid, $type, $error, $errorcode ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT errorid FROM ' . $wpdb->prefix . 'html_validation_errors  where errortype = %s and linkid = %d and errorcode = %s and error = %s', $type, $linkid, $errorcode, $error ), ARRAY_A );

	if ( is_array( $results ) && count( $results ) != 0 ) {
		foreach ( $results as $row ) {
			html_validation_mark_error( $linkid, 0, $row['errorid'], $error );
			return 1;
		}
	}
	return 0;
}

/**
 * Get link url
 **/
function html_validation_get_url_by_linkid( $linkid ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT link FROM ' . $wpdb->prefix . 'html_validation_links  where linkid = %d ', $linkid ), ARRAY_A );

	foreach ( $results as $row ) {
		return $row['link'];
	}
}

/**
 * Get link by post id
 **/
function html_validation_get_linkid_by_postid( $postid ) {
	global $wpdb;
	$values = array();

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT linkid FROM ' . $wpdb->prefix . 'html_validation_links  where postid = %d ', $postid ), ARRAY_A );

	foreach ( $results as $row ) {
		return $row['linkid'];
	}
}

/**
 * Get linktype
 **/
function html_validation_get_linktype_by_linkid( $linkid ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT subtype FROM ' . $wpdb->prefix . 'html_validation_links  where linkid = %d ', $linkid ), ARRAY_A );

	foreach ( $results as $row ) {
		return $row['subtype'];
	}
}

/**
 * Save error
 **/
function html_validation_insert_error( $linkid, $type, $error, $errorcode ) {
	global $wpdb;
	$linktype           = html_validation_get_linktype_by_linkid( $linkid );
	$autocorrect_marker = html_validation_get_autocorrect_marker( $error );
	$adamarker          = html_validation_get_adamarker( $error );

	$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'html_validation_errors (errortype, linkid, errorcode, error, linktype,autocorrect_marker, adamarker) values (%s, %d, %s, %s, %s,%d,%d)', $type, $linkid, $errorcode, $error, $linktype, $autocorrect_marker, $adamarker ), ARRAY_A );
}


/**
 * Recheck link
 **/
function html_validation_rest_recheck_link() {

	check_ajax_referer( 'wp_rest', '_wpnonce' );

	if ( isset( $_GET['linkid'] ) && is_numeric( $_GET['linkid'] ) ) {
		$linkid = (int) $_GET['linkid'];
		$link   = html_validation_get_url_by_linkid( $linkid );
		if ( '' != $link ) {
			html_validation_validate_code( $link, $linkid );
		}
	}
}
/**
 * Manual recheck
 **/
function html_validation_recheck_link( $linkid ) {

	$link = html_validation_get_url_by_linkid( $linkid );

	html_validation_validate_code( $link, $linkid );
}

/**
 * Ignore error
 **/
function html_validation_rest_ignore_error() {
	global $wpdb;
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	if ( isset( $_GET['errorid'] ) && is_numeric( $_GET['errorid'] ) && isset( $_GET['state'] ) && is_numeric( $_GET['state'] ) ) {
		$errorid = (int) $_GET['errorid'];
		$state   = (int) $_GET['state'];
		$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_errors set errorignre = %d where errorid = %d ', $state, $errorid ), ARRAY_A );
	}
}


/**
 * Ignore file
 **/
function html_validation_rest_ignore_link() {
	global $wpdb;
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	if ( isset( $_GET['linkid'] ) && is_numeric( $_GET['linkid'] ) && isset( $_GET['state'] ) && is_numeric( $_GET['state'] ) ) {
		$linkid = (int) $_GET['linkid'];
		$state  = (int) $_GET['state'];
		$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set linkignre = %d where linkid = %d ', $state, $linkid ), ARRAY_A );

		html_validation_purge_errors_by_linkid( $linkid );
	}
}


/**
 * Ignore error
 **/
function html_validation_rest_ignore_duplicates() {
	global $wpdb;
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	if ( isset( $_GET['errorid'] ) && is_numeric( $_GET['errorid'] ) && isset( $_GET['state'] ) && is_numeric( $_GET['state'] ) ) {
		$errorid   = (int) $_GET['errorid'];
		$state     = (int) $_GET['state'];
		$errordata = html_validation_get_errorcode_by_errorid( $errorid );

		$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_errors set errorignre = %d where errorcode = %s and error = %s ', $state, $errordata['errorcode'], $errordata['error'] ), ARRAY_A );
	}
}


/**
 * Get error code by errorid
 **/
function html_validation_get_errorcode_by_errorid( $errorid ) {

	global $wpdb;

	$data = array();

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT errorcode, error FROM ' . $wpdb->prefix . 'html_validation_errors where errorid = %d ', $errorid ), ARRAY_A );

	foreach ( $results as $row ) {
		$data['errorcode'] = sanitize_text_field( $row['errorcode'] );
		$data['error']     = sanitize_text_field( $row['error'] );
	}

	return $data;
}

/**
 * Check if  duplicates exists
 **/
function html_validation_check_duplicate_errorcode( $errorcode ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT errorcode FROM ' . $wpdb->prefix . 'html_validation_errors where errorcode = %s', $errorcode ), ARRAY_A );

	return count( $results );
}

/**
 * Mark auto correct
 **/
function html_validation_get_autocorrect_marker( $error ) {
	$autocorrect_marker = 0;

	$markers[] = 'The “banner” role is unnecessary';
	$markers[] = 'The “navigation” role is unnecessary';
	$markers[] = 'The “main” role is unnecessary';
	$markers[] = 'The “complementary” role is unnecessary ';
	$markers[] = 'The “contentinfo” role is unnecessary';
	$markers[] = 'The “name” attribute is obsolete.';
	$markers[] = 'Bad value “” for attribute “target” on element';
	$markers[] = 'Attribute “placeholder” not allowed';
	$markers[] = 'Attribute “placeholder” is only allowed when the input type';
	$markers[] = 'Element “style” not allowed as child of element “div”';
	$markers[] = 'Duplicate ID';
	$markers[] = 'Bad value “” for attribute “id”';
	$markers[] = 'The “type” attribute is unnecessary';
	$markers[] = 'The “language” attribute on the “script”';
	$markers[] = 'The “type” attribute for the “style” element is not needed';
	$markers[] = 'The “align” attribute on the';
	$markers[] = 'The “big” element is obsolete';
	$markers[] = 'The “border” attribute is obsolete';
	$markers[] = 'The “vspace” attribute on the “img” element is obsolete';
	$markers[] = 'The “hspace” attribute on the “img” element is obsolete';
	$markers[] = 'must not appear as a descendant of the “th” element';
	$markers[] = 'attribute on the “hr” element is obsolete';
	$markers[] = 'attribute on the “iframe” element is obsolete';
	$markers[] = 'The “width” attribute on the “table” element is obsolete';
	$markers[] = 'The “border” attribute on the “table”';
	$markers[] = 'Attribute “height” not allowed on element “table”';
	$markers[] = 'Attribute “nowrap” not allowed on element “table”';
	$markers[] = 'The “cellpadding” attribute on the “table”';
	$markers[] = 'The “cellspacing” attribute on the “table” element is obsolete';
	$markers[] = 'The “bgcolor” attribute on the “table”';
	$markers[] = 'Attribute “width:” not allowed on element “td”';
	$markers[] = 'The “valign” attribute on the “td”';
	$markers[] = 'The “bgcolor” attribute on the “td”';
	$markers[] = 'The “nowrap” attribute on the “td” element is obsolete';
	$markers[] = 'Attribute “width:” not allowed on element “th”';
	$markers[] = 'The “valign” attribute on the “th”';
	$markers[] = 'The “bgcolor” attribute on the “th”';
	$markers[] = 'The “nowrap” attribute on the “th” element is obsolete';
	$markers[] = 'The “height” attribute on the “td” element is obsolete';
	$markers[] = 'The “height” attribute on the “th” element is obsolete';
	$markers[] = 'The “width” attribute on the “td” element is obsolete';
	$markers[] = 'The “width” attribute on the “th” element is obsolete';
	$markers[] = 'Attribute “cellpadding” not allowed on element “td”';
	$markers[] = 'Attribute “cellpadding” not allowed on element “th”';
	$markers[] = 'Attribute “cellspacing” not allowed on element “td”';
	$markers[] = 'Attribute “cellspacing” not allowed on element “th”';
	$markers[] = 'Attribute “border” not allowed on element “td”';
	$markers[] = 'Attribute “border” not allowed on element “th”';
	$markers[] = 'Text not allowed in element “iframe” in this context';
	$markers[] = ' for attribute “height” on element “img”: Expected a digit but saw “p” instead.';
	$markers[] = 'for attribute “width” on element “img”: Expected a digit but saw “p” instead.';

	foreach ( $markers as $key => $value ) {
		if ( stristr( $error, $value ) ) {
			$autocorrect_marker = 1;
		}
	}

	return $autocorrect_marker;
}

/**
 * Mark ada issues
 **/
function html_validation_get_adamarker( $error ) {
	$adamarker = 0;

	$markers[] = 'Duplicate ID';
	$markers[] = 'Bad value “” for attribute “id”';
	$markers[] = 'element in scope but';
	$markers[] = 'stray end tag';
	$markers[] = 'violates nesting rules';
	$markers[] = 'seen, but there were open elements';
	$markers[] = 'Unclosed element';
	$markers[] = 'element must have an “alt” attribute';
	$markers[] = 'The “aria-labelledby” attribute must point to an element in the same document.';
	$markers[] = 'Element “head” is missing a required instance of child element “title”';
	$markers[] = 'not allowed as child of element';
	$markers[] = 'established by element “th” have no cells beginning in them';
	$markers[] = 'but there is no “th” element with that ID in the same table';
	$markers[] = 'on element “input”';
	$markers[] = 'must be the ID of a non-hidden form control';
	$markers[] = 'must be contained in, or owned by, an element with';
	$markers[] = 'did not match the name of the current open element';
	$markers[] = 'seen but an element of the same type was already open';
	$markers[] = 'The “aria-describedby” attribute must point to an element in the same document.';
	$markers[] = 'Element “option” without attribute “label” must not be empty.';

	foreach ( $markers as $key => $value ) {
		if ( stristr( $error, $value ) ) {
			$adamarker = 1;
		}
	}

	return $adamarker;
}
/**
 * Count auto correct
 **/
function html_validation_count_autocorrect_issues() {
	global $wpdb;
	$results = $wpdb->get_results( 'SELECT autocorrect_marker FROM ' . $wpdb->prefix . 'html_validation_errors where autocorrect_marker = 1', ARRAY_A );

	return count( $results );
}
