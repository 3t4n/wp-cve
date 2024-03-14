<?php
/**
 * SCAN FUNCTIONS
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/**
 * Run manual scan
 **/
function html_validation_manual_scan() {
	if ( isset( $_GET['runnow'] ) && is_numeric( $_GET['runnow'] ) ) {
		html_validation_auto_scan_cron();
	}
}
add_action( 'wp_loaded', 'html_validation_manual_scan' );

/**
 * Inventory links
 **/
function html_validation_inventory_links() {
	global $wpdb;

	// set locate flag.
	html_validation_set_locate_flag();

	// get theme files to be scanned.
	$theme_scan_items = array( 'Blog Home', '404 Page', 'Search Page', 'Author Page' );
	$scan_themes      = get_option( 'html_validation_scan_themes', array( 'Blog Home', '404 Page', 'Search Page' ) );
	if ( ! is_array( $scan_themes ) ) {
		$scan_themes = array();
	}

	// get author pages.
	if ( ! in_array( 'Author Page', $scan_themes ) ) {
		$ignore = 1;
	} else {
		$ignore = 0;
	}
	foreach ( $wpdb->get_results( "SELECT DISTINCT post_author FROM $wpdb->posts GROUP BY post_author" ) as $row ) {
		$author = get_userdata( $row->post_author );

		if ( is_object( $author ) ) {
			$link = get_author_posts_url( $author->ID );
			if ( '' != $link ) {
				$link = esc_url_raw( $link );
			}
			if ( '' != $link ) {
				if ( html_validation_check_existing_link( $link ) ) {
					html_validation_save_links( $link, $author->display_name, 'theme', 'Author Page', $ignore );
				} else {
					html_validation_update_locate_flag( $link, 0 );
				}
			}
		}
	}

	foreach ( $theme_scan_items as $key => $value ) {

		if ( 'Author Page' != $value ) {

			if ( ! in_array( $value, $scan_themes ) ) {
				$ignore = 1;
			} else {
				$ignore = 0;
			}

			$link = html_validation_get_url_to_scan( strtolower( str_replace( ' ', '', $value ) ) );
			if ( '' != $link ) {
				$link = esc_url_raw( $link );
			}

			if ( '' != $link ) {
				if ( html_validation_check_existing_link( $link ) ) {
					html_validation_save_links( $link, $value, 'theme', $value, $ignore );
				} else {
					html_validation_update_locate_flag( $link, 0 );
				}
			}
		}
	}

	// inventory terms.
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'term_taxonomy inner join ' . $wpdb->prefix . 'terms ON  ' . $wpdb->prefix . 'term_taxonomy.term_id = ' . $wpdb->prefix . 'terms.term_id where %d', 1 ), ARRAY_A );
	$terms   = get_option( 'html_validation_terms', array( 'category' ) );
	if ( ! is_array( $terms ) ) {
		$terms = array();
	}
	foreach ( $results as $row ) {
		$link = get_term_link( (int) $row['term_id'], $row['taxonomy'] );

		if ( ! in_array( $row['taxonomy'], $terms ) ) {
			$ignore = 1;
		} else {
			$ignore = 0;
		}

		if ( is_string( $link ) && '' != $link ) {

			if ( '' != $link ) {
				$link = esc_url_raw( $link );
			}
			if ( html_validation_check_existing_link( $link ) ) {
				html_validation_save_links( $link, $row['name'], 'term', $row['taxonomy'], $ignore, $row['term_id'] );
			} else {
				html_validation_update_locate_flag( $link, $row['term_id'] );
			}
		}
	}

	// inventory posts.
	$poststatus = array();
	array_unshift( $poststatus, 'trash' );
	array_unshift( $poststatus, 'tao_sc_publish' );
	array_unshift( $poststatus, 'draft' );
	array_unshift( $poststatus, 'auto-draft' );
	array_unshift( $poststatus, 'revision' );
	array_unshift( $poststatus, 'oembed_cache' );
	array_unshift( $poststatus, 'nav_menu' );
	array_unshift( $poststatus, 'nav_menu_item' );

	$results   = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'posts where post_type NOT IN(%s, %s, %s, %s) and post_status NOT IN(%s, %s, %s, %s)  ', $poststatus ), ARRAY_A );
	$posttypes = get_option( 'html_validation_posttypes', array( 'page', 'post' ) );
	if ( ! is_array( $posttypes ) ) {
		$posttypes = array();
	}
	foreach ( $results as $row ) {
		if ( ! in_array( $row['post_type'], $posttypes ) ) {
			$ignore = 1;
		} else {
			$ignore = 0;
		}

		if ( post_type_exists( $row['post_type'] ) && post_type_supports( $row['post_type'], 'editor' ) || 'attachment' == $row['post_type'] ) {
			$link = get_permalink( $row['ID'] );
			if ( '' != $link ) {
				$link = esc_url_raw( $link );
			}
			if ( is_string( $link ) && '' != $link ) {
				if ( html_validation_check_existing_link( $link ) ) {
					html_validation_save_links( $link, $row['post_title'], 'posttype', $row['post_type'], $ignore, $row['ID'] );
				} else {
					html_validation_update_locate_flag( $link, $row['ID'] );
				}
			}
		}
	}

	// inventory post archives.
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT post_type FROM ' . $wpdb->prefix . 'posts where post_status NOT IN(%s, %s, %s, %s)  ', $poststatus ), ARRAY_A );

	foreach ( $results as $row ) {
		if ( ! in_array( $row['post_type'], $posttypes ) ) {
			$ignore = 1;
		} else {
			$ignore = 0;
		}

		if ( post_type_exists( $row['post_type'] ) && post_type_supports( $row['post_type'], 'editor' ) ) {
			$link = get_post_type_archive_link( $row['post_type'] );
			if ( '' != $link ) {
				$link = esc_url_raw( $link );
			}
			if ( '' != $link ) {
				if ( html_validation_check_existing_link( $link ) ) {
					html_validation_save_links( $link, $row['post_type'] . ' Archive', 'archive', $row['post_type'] . '_archive', $ignore );
				} else {
					html_validation_update_locate_flag( $link, 0 );
				}
			}
		}
	}

	// add links from wp ada compliance plugin.
	$external_sources = get_option( 'html_validation_external_sources', 'true' );
	if ( 'true' == $external_sources && html_validation_is_plugin_active( 'wp-ada-compliance/wp-ada-compliance.php' ) ) {
		$results = $wpdb->get_results( $wpdb->prepare( 'SELECT link FROM ' . $wpdb->prefix . 'wp_ada_compliance_links where type = %s and ignre = %d and linkstatus = %d', 'link', 0, '200' ), ARRAY_A );
		foreach ( $results as $row ) {
			if ( '' != $row['link'] ) {
				$link = esc_url_raw( $row['link'] );
			}
			if ( '' != $link ) {
				if ( html_validation_check_existing_link( $link ) ) {
					html_validation_save_links( $link, $link, 'link', 'link', 0, 0 );
				} else {
					html_validation_update_locate_flag( $link, 0 );
				}
			}
		}
	}// end add external links

	// purge inventory links not found.
	html_validation_purge_links();
}



/**
 * Get url to scan
 **/
function html_validation_get_url_to_scan( $scantype, $postid = '', $taxonomy = '' ) {

	// term page.
	if ( 'term' == $scantype && '' != $postid && '' != $taxonomy ) {
		$url = get_term_link( $postid, $taxonomy );
		if ( is_string( $url ) ) {
			return esc_url_raw( $url );
		} else {
			return '';
		}
	}

	// post or page.
	if ( 'post' == $scantype && '' != $postid ) {
		return esc_url_raw( get_permalink( $postid ) );
	}

	// home page.
	if ( str_replace( ' ', '', strtolower( $scantype ) ) == 'bloghome' ) {
		return esc_url_raw( get_site_url() );
	}

	// 404 error.
	if ( str_replace( ' ', '', strtolower( $scantype ) ) == '404page' ) {
		return esc_url_raw( get_site_url() . '/?page_id=010000000000000000000000000000' );
	}

	// search page.
	if ( str_replace( ' ', '', strtolower( $scantype ) ) == 'searchpage' ) {
		return esc_url_raw( get_site_url() . '/?s=' );
	}

	// get author page.
	if ( str_replace( ' ', '', strtolower( $scantype ) ) == 'authorpage' ) {
		return esc_url_raw( get_author_posts_url( 1 ) );
	}

	// return archive url.
	if ( strstr( str_replace( ' ', '', strtolower( $scantype ) ), '_archive' ) ) {
		$posttype = str_replace( '_archive', '', $scantype );
		return esc_url_raw( get_post_type_archive_link( $posttype ) );
	}
}




/**
 * Check for existing link
 **/
function html_validation_check_existing_link( $link ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'html_validation_links where link = %s', $link ), ARRAY_A );

	if ( is_array( $results ) && count( $results ) == 0 ) {
		return 1;
	}
	return 0;
}



/**
 * Save links
 **/
function html_validation_save_links( $link, $title, $type, $subtype, $ignore, $postid = '' ) {
	global $wpdb;

	// ignore certain types.
	$ignore_these_items   = array();
	$ignore_these_items[] = 'acf-field';
	$ignore_these_items[] = 'acf-field-group';
	$ignore_these_items[] = 'accordions';
	$ignore_these_items[] = 'component';
	$ignore_these_items[] = 'nav_menu_item';
	$ignore_these_items[] = 'oembed_cache';
	$ignore_these_items[] = 'wp_block';
	$ignore_these_items[] = 'nav_menu';
	if ( in_array( $type, $ignore_these_items ) ) {
		return 1;
	}

	$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'html_validation_links (link, title, type, subtype, locateflag, scanflag, linkignre, postid) values(%s, %s, %s, %s, %d, %d, %d, %d)', $link, $title, $type, $subtype, 1, 0, $ignore, $postid ) );
}


/**
 * Set locate flag
 **/
function html_validation_set_locate_flag() {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set locateflag = %d', 0 ) );
}



/**
 * Update locate flag
 **/
function html_validation_update_locate_flag( $link, $postid ) {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set locateflag = %d, postid = %d where link = %s ', 1, $postid, $link ) );
}


/**
 * Purge links not located
 **/
function html_validation_purge_links() {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'html_validation_links where locateflag = %d ', 0 ) );
}

/**
 * Uupdate scan flag
 **/
function html_validation_update_scan_flag( $linkid ) {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set scanflag = %d where linkid = %d', 1, $linkid ) );
}
/**
 * Reset scan flag
 **/
function html_validation_reset_scan_flag() {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set scanflag = %d ', 0 ) );
}



/**
 * Scan links
 **/
function html_validation_scan_links() {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT linkid, link FROM ' . $wpdb->prefix . 'html_validation_links where linkignre = %d and scanflag = %d LIMIT 5', 0, 0 ), ARRAY_A );

	foreach ( $results as $row ) {

		html_validation_validate_code( $row['link'], $row['linkid'] );
		html_validation_update_scan_flag( $row['linkid'] );

	}
}

/**
 * Check active plugins
 **/
function html_validation_is_plugin_active( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}
