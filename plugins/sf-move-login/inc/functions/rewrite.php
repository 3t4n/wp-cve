<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/*------------------------------------------------------------------------------------------------*/
/* !INCLUDES ==================================================================================== */
/*------------------------------------------------------------------------------------------------*/

require_once( ABSPATH . WPINC . '/functions.php' );
require_once( ABSPATH . 'wp-admin/includes/misc.php' );


/*------------------------------------------------------------------------------------------------*/
/* !REWRITE RULES =============================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Get generic rules for the rewrite rules, based on the settings.
 *
 * @param (array) $actions An array of action => slug.
 *
 * @return (array) An array with the rewritted URIs (slug) as keys and the real URIs as values.
 */
function sfml_rules( $actions = null ) {
	if ( ! $actions || ! is_array( $actions ) ) {
		$actions = sfml_get_slugs();
	} else {
		$actions = array_merge( SFML_Options::get_instance()->get_non_customizable_actions(), $actions );
	}

	$rules = array();

	foreach ( $actions as $action => $slug ) {
		$rules[ $slug ] = 'wp-login.php' . ( 'login' === $action ? '' : '?action=' . $action );
	}

	return $rules;
}


/**
 * Add rules into the `.htaccess` or `web.config` file.
 *
 * @param (array) $rules Generic rules to write.
 *
 * @return (bool) True on success, false if the file is not writable or upon failure.
 */
function sfml_write_rules( $rules = null ) {
	$rules = isset( $rules ) ? $rules : sfml_rules();

	// Nginx.
	if ( sfml_is_nginx() ) {
		$success = false;
	}
	// The file is not writable.
	elseif ( ! sfml_can_write_file() ) {
		$success = false;
	}
	// Apache.
	elseif ( sfml_is_apache() ) {
		$success = sfml_insert_apache_rewrite_rules( $rules );
	}
	// IIS.
	elseif ( sfml_is_iis7() ) {
		$success = sfml_insert_iis7_rewrite_rules( $rules );
	}
	// Souldn't happen.
	else {
		$success = false;
	}

	/**
	 * Triggered each time the `.htaccess` or `web.config` file is edited (or not).
	 *
	 * @param (array) $rules   Generic rules to write.
	 * @param (bool)  $success Whether the rules have successfully added to the file or removed from the file.
	 */
	do_action( 'sfml_write_rules', $rules, $success );

	return $success;
}


/**
 * Get infos for the rewrite rules.
 * The main concern is about directories.
 *
 * @return (array) An array containing the following keys:
 *         'base'      => Rewrite base, or "home directory".
 *         'wp_dir'    => WP directory.
 *         'site_dir'  => Directory containing the WordPress files.
 *         'is_sub'    => Is it a subfolder install? (Multisite).
 *         'site_from' => Regex for first part of the rewrite rule (WP files).
 *         'site_to'   => First part of the rewrited address (WP files).
 *                        In case of MultiSite with sub-folders, this is not really where the files are: WP rewrites the admin URL for example, which is based on the "site URL".
 *         'home_from' => Regex for first part of the rewrite rule (home URL).
 *         'home_to'   => First part of the rewrited address (home URL).
 */
function sfml_get_rewrite_bases() {
	static $bases;

	if ( isset( $bases ) ) {
		return $bases;
	}

	$base     = wp_parse_url( trailingslashit( get_option( 'home' ) ) );
	$base     = $base['path'];
	$wp_dir   = sfml_get_wp_directory();     // WP in its own directory.
	$is_sub   = sfml_is_subfolder_install(); // MultiSite by sub-folders.
	$site_dir = $base . ltrim( $wp_dir, '/' );

	$bases = array(
		'base'     => $base,     // Possible values: '/' or '/sub-dir/'.
		'wp_dir'   => $wp_dir,   // Possible values: '' or '/wp-dir/'.
		'site_dir' => $site_dir, // Possible values: '/', '/wp-dir/', '/sub-dir/', or '/sub-dir/wp-dir/'.
		'is_sub'   => $is_sub,   // Possible values: true or false.
	);

	// Apache.
	if ( sfml_is_apache() ) {
		/**
		 * In the `*_from` fields, we don't add `$base` because we use `RewriteBase $base` in the rewrite rules.
		 * In the `*_to` fields, `$base` is optional, but WP adds it so we do the same for concistancy.
		 */
		if ( $is_sub ) {
			// MultiSite by sub-folders.
			return ( $bases = array_merge( $bases, array(
				// 'site_from' and 'site_to': no `$wp_dir` here, because it is used only for the main blog.
				'site_from' => $wp_dir ? '([_0-9a-zA-Z-]+/)' : '(([_0-9a-zA-Z-]+/)?)',
				'site_to'   => $base . '$1',
				'home_from' => '([_0-9a-zA-Z-]+/)?',
				'home_to'   => $base . '$1',
			) ) );
		} else {
			// Not MultiSite, or MultiSite by sub-domains.
			return ( $bases = array_merge( $bases, array(
				'site_from' => $wp_dir,
				'site_to'   => $site_dir,
				'home_from' => '',
				'home_to'   => $base,
			) ) );
		}
	}

	// Nginx.
	if ( sfml_is_nginx() ) {
		if ( $is_sub ) {
			// MultiSite by sub-folders.
			return ( $bases = array_merge( $bases, array(
				// 'site_from' and 'site_to': no `$wp_dir` here, because it is used only for the main blog.
				'site_from' => $base . '(' . ( $wp_dir ? '[_0-9a-zA-Z-]+/' : '([_0-9a-zA-Z-]+/)?' ) . ')',
				'site_to'   => $base . '$1',
				'home_from' => $base . '([_0-9a-zA-Z-]+/)?',
				'home_to'   => $base . '$1',
			) ) );
		} else {
			// Not MultiSite, or MultiSite by sub-domains.
			return ( $bases = array_merge( $bases, array(
				'site_from' => $site_dir,
				'site_to'   => $site_dir,
				'home_from' => $base,
				'home_to'   => $base,
			) ) );
		}
	}

	// IIS7.
	if ( sfml_is_iis7() ) {
		$base     = ltrim( $base, '/' );     // No heading slash for IIS: '' or 'sub-dir/'.
		$site_dir = ltrim( $site_dir, '/' ); // No heading slash for IIS: '', 'wp-dir/', 'sub-dir/', or 'sub-dir/wp-dir/'.

		if ( $is_sub ) {
			// MultiSite by sub-folders.
			return ( $bases = array_merge( $bases, array(
				'base'      => $base,
				'site_dir'  => $site_dir,
				// 'site_from' and 'site_to': no `$wp_dir` here, because it is used only for the main blog.
				'site_from' => $base . '(' . ( $wp_dir ? '[_0-9a-zA-Z-]+/' : '([_0-9a-zA-Z-]+/)?' ) . ')',
				'site_to'   => $base . '{R:1}',
				'home_from' => $base . '([_0-9a-zA-Z-]+/)?',
				'home_to'   => $base . '{R:1}',
			) ) );
		} else {
			// Not MultiSite, or MultiSite by sub-domains.
			return ( $bases = array_merge( $bases, array(
				'base'      => $base,
				'site_dir'  => $site_dir,
				'site_from' => $site_dir,
				'site_to'   => $site_dir,
				'home_from' => $base,
				'home_to'   => $base,
			) ) );
		}
	}

	return ( $bases = false );
}


/**
 * Get WP Direct filesystem object. Also define chmod constants if not done yet.
 *
 * @since 2.4.3
 *
 * @return (object) A WP_Filesystem_Direct object.
 */
function sfml_get_filesystem() {
	static $filesystem;

	if ( $filesystem ) {
		return $filesystem;
	}

	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );

	$filesystem = new WP_Filesystem_Direct( new StdClass() ); // WPCS: override ok.

	// Set the permission constants if not already set.
	if ( ! defined( 'FS_CHMOD_DIR' ) ) {
		define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
	}
	if ( ! defined( 'FS_CHMOD_FILE' ) ) {
		define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
	}

	return $filesystem;
}


/*------------------------------------------------------------------------------------------------*/
/* !NGINX ======================================================================================= */
/*------------------------------------------------------------------------------------------------*/

/**
 * Get the rewrite rules for Nginx that should be added into the `nginx.conf` file.
 *
 * @param (array) $rules Generic rules to write.
 *
 * @return (array) The rewrite rules.
 */
function sfml_nginx_rewrite_rules( $rules = array() ) {
	if ( ! $rules || ! is_array( $rules ) ) {
		return array();
	}

	$bases = sfml_get_rewrite_bases();
	$out   = array();

	foreach ( $rules as $slug => $rule ) {
		$out[] = 'rewrite ^' . $bases['site_from'] . $slug . '/?$ ' . $bases['site_dir'] . $rule . ' last;';
	}

	return $out;
}


/*------------------------------------------------------------------------------------------------*/
/* !APACHE ====================================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Get the rewrite rules for Apache that should be added into the `.htaccess` file.
 *
 * @param (array) $rules Generic rules to write.
 *
 * @return (array) The rewrite rules.
 */
function sfml_apache_rewrite_rules( $rules = array() ) {
	if ( ! $rules || ! is_array( $rules ) ) {
		return array();
	}

	$bases = sfml_get_rewrite_bases();
	$out   = array(
		'<IfModule mod_rewrite.c>',
		'    RewriteEngine On',
		'    RewriteBase ' . $bases['base'],
	);

	foreach ( $rules as $slug => $rule ) {
		$out[] = '    RewriteRule ^' . $bases['site_from'] . $slug . '/?$ ' . $bases['site_dir'] . $rule . ' [QSA,L]';
	}

	$out[] = '</IfModule>';

	return $out;
}


/**
 * Add or remove rules into the `.htaccess` file.
 *
 * @param (array) $rules Generic rules to write.
 *
 * @return (bool) True on success, false on failure.
 */
function sfml_insert_apache_rewrite_rules( $rules = array() ) {
	if ( $rules ) {
		$rules = sfml_apache_rewrite_rules( $rules );
		$rules = implode( "\n", $rules );
		$rules = trim( $rules );
	} else {
		$rules = '';
	}

	$filesystem    = sfml_get_filesystem();
	$htaccess_file = sfml_get_home_path() . '.htaccess';
	$has_htaccess  = $filesystem->exists( $htaccess_file );

	if ( ! $rules ) {
		// We want to remove the rules.
		if ( ! $has_htaccess ) {
			// The file does not exist (uh?). All good.
			return true;
		}

		$htaccess_is_writable = $has_htaccess && wp_is_writable( $htaccess_file );

		if ( ! $htaccess_is_writable ) {
			// The file is not writable.
			return false;
		}
		// No need to test for mod rewrite, we want to remove rules.
	} elseif ( ! sfml_can_write_file() ) {
		// We can't add rules.
		return false;
	}

	$marker  = 'SF Move Login';
	// Current htaccess content.
	$content = $has_htaccess ? $filesystem->get_contents( $htaccess_file ) : '';
	// Remove the SF Move Login marker.
	$content = preg_replace( "/# BEGIN $marker.*# END $marker\n*/is", '', $content );

	// The new content is inserted at the begining of the file.
	if ( $rules ) {
		$content = "# BEGIN $marker\n$rules\n# END $marker\n\n\n$content";
	}

	// Update the `.htaccess` file.
	return (bool) $filesystem->put_contents( $htaccess_file , $content );
}


/*------------------------------------------------------------------------------------------------*/
/* !IIS ========================================================================================= */
/*------------------------------------------------------------------------------------------------*/

/**
 * Get the rewrite rules for IIS that should be added into the `web.config` file.
 *
 * @param (array) $rules Generic rules to write.
 *
 * @return (array) The rewrite rules.
 */
function sfml_iis7_rewrite_rules( $rules = array() ) {
	if ( ! $rules || ! is_array( $rules ) ) {
		return array();
	}

	$bases  = sfml_get_rewrite_bases();
	$rule_i = 1;
	$space  = str_repeat( ' ', 8 );
	$out    = array();

	foreach ( $rules as $slug => $rule ) {
		$full_rule  = $space . '<rule name="SF Move Login Rule ' . $rule_i . '" stopProcessing="true">' . "\n";
		$full_rule .= $space . '  <match url="^' . $bases['site_from'] . $slug . '/?$" ignoreCase="false" />' . "\n";
		$full_rule .= $space . '  <action type="Redirect" url="' . $bases['site_dir'] . $rule . '" redirectType="Permanent" />' . "\n";
		$full_rule .= $space . '</rule>';

		$out[] = $full_rule;
		$rule_i++;
	}

	return $out;
}


/**
 * Add or remove rules into the `web.config` file.
 *
 * @param (array) $rules Generic rules to write.
 *
 * @return (bool) True on success, false on failure.
 */
function sfml_insert_iis7_rewrite_rules( $rules = array() ) {
	if ( ! class_exists( 'DOMDocument' ) ) {
		return false;
	}

	if ( $rules ) {
		$rules = sfml_iis7_rewrite_rules( $rules );
		$rules = implode( "\n", $rules );
		$rules = trim( $rules );
	} else {
		$rules = '';
	}

	$web_config_file = sfml_get_home_path() . 'web.config';
	$filesystem      = sfml_get_filesystem();
	$has_web_config  = $filesystem->exists( $web_config_file );

	if ( ! $rules ) {
		// We want to remove the rules.
		if ( ! $has_web_config ) {
			// The file does not exist (uh?). All good.
			return true;
		}

		if ( ! wp_is_writable( $web_config_file ) ) {
			// The file is not writable.
			return false;
		}
		// No need to test for permalinks support, we want to remove rules.
	} elseif ( ! sfml_can_write_file() ) {
		// We can't add rules.
		return false;
	}

	// If configuration file does not exist then we create one.
	if ( ! $has_web_config ) {
		$filesystem->put_contents( $web_config_file, '<configuration/>' );
	}

	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = false;

	if ( false === $doc->load( $web_config_file ) ) {
		return false;
	}

	$marker = 'SF Move Login';
	$xpath  = new DOMXPath( $doc );
	$path   = '/configuration/system.webServer/rewrite/rules';

	// Remove old rules.
	$old_rules = $xpath->query( "$path/*[starts-with(@name,'$marker')]" );

	if ( $old_rules->length > 0 ) {
		foreach ( $old_nodes as $old_node ) {
			$old_node->parentNode->removeChild( $old_node );
		}
	}

	// No new rules? Stop here.
	if ( ! $rules ) {
		$doc->formatOutput = true;
		saveDomDocument( $doc, $web_config_file );
		return true;
	}

	// Indentation.
	$spaces = explode( '/', trim( $path, '/' ) );
	$spaces = count( $spaces ) - 1;
	$spaces = str_repeat( ' ', $spaces * 2 );

	// Create fragment.
	$fragment = $doc->createDocumentFragment();
	$fragment->appendXML( "\n$spaces  $rules" );

	// Maybe create child nodes and then, prepend new nodes.
	sfml_get_iis7_node( $doc, $xpath, $path, $fragment );

	// Save and finish.
	$doc->encoding     = 'UTF-8';
	$doc->formatOutput = true;
	saveDomDocument( $doc, $web_config_file );

	return true;
}


/**
 * Get a DOMNode node.
 * If it does not exist it is created recursively.
 *
 * @param (object) $doc   DOMDocument element.
 * @param (object) $xpath DOMXPath element.
 * @param (string) $path  Path to the desired node.
 * @param (object) $child DOMNode to be prepended.
 *
 * @return (object) The DOMNode node.
 */
function sfml_get_iis7_node( $doc, $xpath, $path, $child ) {
	$nodelist = $xpath->query( $path );

	if ( $nodelist->length > 0 ) {
		return sfml_prepend_iis7_node( $nodelist->item( 0 ), $child );
	}

	$path = explode( '/', $path );
	$node = array_pop( $path );
	$path = implode( '/', $path );

	$final_node = $doc->createElement( $node );

	if ( $child ) {
		$final_node->appendChild( $child );
	}

	return sfml_get_iis7_node( $doc, $xpath, $path, $final_node );
}


/**
 * A shorthand to prepend a DOMNode node.
 *
 * @param (object) $container_node DOMNode that will contain the new node.
 * @param (object) $new_node       DOMNode to be prepended.
 *
 * @return (object) DOMNode containing the new node.
 */
function sfml_prepend_iis7_node( $container_node, $new_node ) {
	if ( ! $new_node ) {
		return $container_node;
	}

	if ( $container_node->hasChildNodes() ) {
		$container_node->insertBefore( $new_node, $container_node->firstChild );
	} else {
		$container_node->appendChild( $new_node );
	}

	return $container_node;
}
