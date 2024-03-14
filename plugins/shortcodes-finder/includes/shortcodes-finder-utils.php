<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.1.0
 * @package    shortcodes-finder
 * @subpackage shortcodes-finder/admin/partials
 * @author     Scribit <wordpress@scribit.it>
 */

/**
 * Find posts ids by post type.
 * Passing true to include_not_published, results will include draft, future, pending and private contents.
 *
 * @since    1.2.9
 */
function sf_get_posts_ids($post_type = 'post', $include_not_published = false) {

	$post_status = array('publish');

	if ($include_not_published)
		$post_status = array_merge($post_status, array('draft', 'pending', 'private', 'future'));

    $args = array(
        'posts_per_page' => -1,
        'post_type' => $post_type,
        'post_status' => $post_status,
        'orderby' => 'date',
        'order' => 'DESC',
        'fields' => 'ids'
    );
    $posts = get_posts($args);

    return $posts;
}

/**
 * Get shortcodes in every content of "type_name" type. List results by shortcode.
 * Allow to pass an existing $shortcodes array to fill with results.
 * Passing a specific shortcode_to_search, returns only that type of occurrences.
 * Passing true to include_not_published, results will include shortcodes in draft, future, pending and private contents.
 *
 * @since    1.2.9
 */
function sf_get_shortcodes_in_content($type_name, $type_title = '', &$shortcodes = array(), $shortcode_to_search = '', $include_not_published = false) {
    if ($type_title == '') {
        $type_title = ucfirst($type_name);
    }

	$post_status = array('publish');

	if ($include_not_published)
		$post_status = array_merge($post_status, array('draft', 'pending', 'private', 'future'));

    $args = array(
        'posts_per_page' => -1,
        'post_status' => $post_status,
        'orderby' => 'date',
        'post_type' => $type_name,
        'order' => 'DESC'
    );
    $posts = get_posts($args);
    sf_get_shortcodes_by_shortcode($posts, $type_title, $shortcodes, $shortcode_to_search);
}

/**
 * Find shortcodes in a post recursively, using a specific pattern. Returns it divided into posts.
 *
 * @since    1.2.9
 */
function sf_get_shortcodes_by_post_recursive($content, $post, $pattern, &$shortcodes) {
	if (preg_match_all($pattern, $content, $matches) && array_key_exists(2, $matches)) {
        foreach ($matches[0] as $key => $value) {
            $shortcode = array();

            // replace space with '&' for parse_str() function
            parse_str(str_replace(" ", "&", $matches[3][$key]), $params);

            $shortcode = array(
				'name' => $matches[2][$key],
				'params' => $params,
				'params_raw' => $matches[3][$key],
				'content' => $matches[5][$key],
				'code' => $value,
				'post' => array(
					'ID' => $post->ID,
					'title' => $post->post_title,
					'site_name' => is_multisite() ? get_blog_details()->blogname : '',
					'site_home_url' => home_url(),
					'edit_post_link' => get_edit_post_link($post->ID),
					'permalink' => get_permalink($post->ID),
					'status' => $post->post_status,
					'type' => $post->post_type
				)
			);

            $shortcodes[$post->ID][] = $shortcode;

            // Recursivity
            sf_get_shortcodes_by_post_recursive($matches[5][$key], $post, $pattern, $shortcodes);
        }
    }
}

/**
 * Find all shortcodes in an array of posts recursively. Returns it divided into posts.
 *
 * @since    1.2.9
 */
function sf_get_shortcodes_by_post($posts) {
    $shortcodes = array();

    sf_import_third_party_shortcodes();

    $pattern = '/'. get_shortcode_regex() .'/s';

    foreach ($posts as $post) {
        sf_get_shortcodes_by_post_recursive($post->post_content, $post, $pattern, $shortcodes);
    }
    return $shortcodes;
}

/**
 * Find shortcodes in a post recursively, using a specific pattern (and allowing to specify a single shortcode to find). Returns it divided into shortcode name.
 *
 * @since    1.2.9
 */
function sf_get_shortcodes_by_shortcode_recursive($content, $post, $type_title, $pattern, &$shortcodes, $shortcode_to_search = '') {
	if (preg_match_all($pattern, $content, $matches) && array_key_exists(2, $matches)) {
        foreach ($matches[0] as $key => $value) {

            // If no specific shortcode is passed or the current shortcode matched the passed one, add it to the results.
            if (strlen($shortcode_to_search) == 0 || ($shortcode_to_search == $matches[2][$key])) {
                $shortcode = array();

                // replace space with '&' for parse_str() function
                parse_str(str_replace(" ", "&", $matches[3][$key]), $params);

                $shortcode = array(
					'name' => $matches[2][$key],
					'params' => $params,
					'params_raw' => $matches[3][$key],
					'content' => $matches[5][$key],
					'code' => $value,
					'post' => array(
						'ID' => $post->ID,
						'title' => $post->post_title,
						'site_name' =>  is_multisite() ? get_blog_details()->blogname : '',
						'site_home_url' => home_url(),
						'edit_post_link' => get_edit_post_link($post->ID),
						'permalink' => get_permalink($post->ID),
						'type' => (isset($type_title) ? $type_title : $post->post_type),
						'status' => $post->post_status
					)
				);

               $shortcodes[$matches[2][$key]][] = $shortcode;
            }

            // Recursivity
            sf_get_shortcodes_by_shortcode_recursive($matches[5][$key], $post, $type_title, $pattern, $shortcodes, $shortcode_to_search);
        }
    }
}

/**
 * Find all shortcodes in an array of posts recursively. Returns it divided into shortcode name. Allow to specify a single shortcode to find.
 *
 * @since    1.2.9
 */
function sf_get_shortcodes_by_shortcode($posts, $type_title, &$shortcodes = array(), $shortcode_to_search = '') {
    sf_import_third_party_shortcodes();

    $pattern = '/'. get_shortcode_regex() .'/s';

    foreach ($posts as $post) {
        sf_get_shortcodes_by_shortcode_recursive($post->post_content, $post, $type_title, $pattern, $shortcodes, $shortcode_to_search);
    }
}

/**
 * Find all unused shortcodes in an array of posts.
 *
 * @since    1.2.9
 */
function sf_get_shortcodes_unused_by_post($posts) {

	/**
	 * Strip HTML comments from content
	 *
	 * @since 1.4.0
	 **/
	function remove_html_comments($content = '') {
		return preg_replace('/<!--(.|\s)*?-->/', '', $content);
	}

    $pattern = '/'. sf_get_shortcode_unused_regex() .'/s';

    foreach ($posts as $post) {
        if (preg_match_all($pattern, remove_html_comments($post->post_content), $matches) && array_key_exists(2, $matches)) {
            foreach ($matches[0] as $key => $value) {
                $shortcode = array();

                // replace space with '&' for parse_str() function
                parse_str(str_replace(" ", "&", $matches[3][$key]), $params);

                $shortcode = array(
					'name' => $matches[2][$key],
					'params' => $params,
					'params_raw' => $matches[3][$key],
					'content' => $matches[5][$key],
					'code' => $value,
                    'post' => array(
						'ID' => $post->ID,
						'title' => $post->post_title,
						'edit_post_link' => get_edit_post_link($post->ID),
						'permalink' => get_permalink($post->ID),
						'status' => $post->post_status
					)
				);

                $shortcodes[$matches[2][$key]][] = $shortcode;
            }
        }
    }
    return $shortcodes;
}

/**
 * Import third party shortcodes into $shortcode_tags global system variable.
 * Supported external shortcodes:
 * - Tablepress
 * - WeForms
 * - WPBakery Visual Composer
 *
 * @since    1.2.9
 */
function sf_import_third_party_shortcodes() {
    // WPBakery Visual Composer
    if (class_exists('WPBMap') && method_exists('WPBMap', 'addAllMappedShortcodes')) {
        WPBMap::addAllMappedShortcodes();
    }

	if (class_exists('TablePress') && method_exists('TablePress', 'init_shortcodes')) {
		add_action( 'init', array( 'TablePress', 'init_shortcodes' ), 20 );
	}
	
	// WeForms
	if (class_exists('WeForms')){
		require_once WEFORMS_INCLUDES . '/class-frontend-form.php';
		if (class_exists('WeForms_Frontend_Form')){
			new WeForms_Frontend_Form();
		}
	}
}

/**
 * Retrieve a list of available shortcodes ordered by tag name.
 * If load_origin = true, the return array will contain informations about shortcode origin (plugin, theme, wordpress) and source code. Otherwise it will contain only handler function.
 * If load_attributes = true, the function will search for shortcodes attributes informations and save them into "attributes" field.
 * If object_to_load is passed, function load only selected object informations, speeding up the search process.
 *
 * @since    1.3.0
 */
function sf_get_shortcodes_ordered( $load_origin = false, $load_attributes = false, $object_to_load = '' ) {
    sf_import_third_party_shortcodes();
    global $shortcode_tags;

    $shortcodes_result = array();

	if (is_array($shortcode_tags)) {

		if ( $load_origin )
			$all_plugins = get_plugins();

		foreach ($shortcode_tags as $tag => $handler_function) {

			if ( $load_origin ){
				$shortcode_origin  = sf_admin_page_shortcode_origin( $handler_function );

				// Skip wordpress-inactive shortcodes
				if ( $shortcode_origin['type'] == 'wordpress-inactive' ) continue;

				switch ( $shortcode_origin['type'] ) {
					case 'plugin':
						$object_name = __('Plugin', 'shortcodes-finder');
						foreach ($all_plugins as $plugin_name => $plugin) {
							$plugin_path = preg_replace( '|^/([^/]*/).*$|', '\\1', $shortcode_origin['name'] );
							if ( strpos( $plugin_name, $plugin_path ) === 0 ) {
								$object_name .= ': ' . $plugin['Name'];
								break;
							}
						}
						break;
					case 'theme':
						$object_name = __('Theme', 'shortcodes-finder');
						$theme = wp_get_theme( $shortcode_origin['name'] );
						if ( $theme ) {
							$object_name .= ': ' . $theme->display('Name',false);
						}
						break;
					case 'wordpress-frontend':
						$object_name = __('WordPress: Front-end', 'shortcodes-finder');
						break;
					/*case 'wordpress-inactive':
						$object_name = __('WordPress: Inactive', 'shortcodes-finder');
						break;*/
					case 'wordpress-admin':
						$object_name = __('WordPress: Admin', 'shortcodes-finder');
						break;
					default:
						$object_name = 'error';
				}

				if ( $object_name != 'error' && ( $object_to_load == '' || $object_to_load == $shortcode_origin['tag'] ) ) {
					$shortcode_origin['object'] = $object_name;

					if ( $load_attributes )
						$shortcode_origin['attributes'] = sf_shortcode_attributes_processor( $tag );

					$shortcodes_result[$tag] = $shortcode_origin;
				}
			}

			else {
				$shortcodes_result[$tag] = $handler_function;
			}
		}
	}

    uksort($shortcodes_result, 'strcasecmp');

    return $shortcodes_result;
}

/**
 * Get origin of shortcode based on callback (plugin, theme, or Wordpress).
 *
 * Similar to https://developer.wordpress.org/reference/functions/_get_plugin_from_callback/
 *
 * @since    1.4.0
 */
function sf_admin_page_shortcode_origin( $callback ) {

    try {
        if ( is_array( $callback ) ) {
			$callback_type     = 'class';

			if (is_object( $callback[0] ))
				$callback_class = get_class( $callback[0] );
			else
				$callback_class = $callback[0];

			$callback_name = $callback_class . '::' . $callback[1];

            $reflection = new ReflectionMethod( $callback_class, $callback[1] );
        } elseif ( is_string( $callback ) && false !== strpos( $callback, '::' ) ) {
			$callback_type = 'class';
			$callback_name = $callback;

            $reflection = new ReflectionMethod( $callback );
        } else {
			$callback_type = 'function';
			$callback_name = $callback;

            $reflection = new ReflectionFunction( $callback );
        }
    } catch ( ReflectionException $exception ) {
        // We could not properly reflect on the callable, so we abort here.
        return null;
    }

    // Don't show an error if it's an internal PHP function.
    if ( $reflection->isInternal() ) {
        return null;
	}

	$shortcode_line      = $reflection->getStartLine();
	$shortcode_file_name = wp_normalize_path( $reflection->getFileName() );
	$shortcode_file_name = '/' . str_replace( ABSPATH, '', $shortcode_file_name );

	// Is it a plugin?
	preg_match( '/\/wp-content\/plugins\/([^\/]+)\/(.*)/i', $shortcode_file_name, $shortcode_path );

	if ( isset( $shortcode_path[1] ) ) {
		$shortcode_type = 'plugin';
	} else {
		// Is it a theme?
		preg_match( '/\/wp-content\/themes\/([^\/]+)\/(.*)/i', $shortcode_file_name, $shortcode_path );

		if ( isset( $shortcode_path[1] ) ) {
			$shortcode_type = 'theme';
		} else {
			// Is it a wordpress front-end?
			preg_match( '/\/(wp-includes)\/(.*)/i', $shortcode_file_name, $shortcode_path );

			if ( isset( $shortcode_path[1] ) ) {
				if ( strpos( $callback_name, '__return_' ) === false ) {
					$shortcode_type = 'wordpress-frontend';
				} else {
					$shortcode_type = 'wordpress-inactive';
				}
			} else {
				// Is it a wordpress admin?
				preg_match( '/\/(wp-admin)\/(.*)/i', $shortcode_file_name, $shortcode_path );

				if ( isset( $shortcode_path[1] ) ) {
					$shortcode_type = 'wordpress-admin';
				} else {
					// That's odd, oh well...
					return null;
				}
			}
		}
	}

	$shortcode_name = $shortcode_path[1];

	$shortcode_origin =	array(
		'tag'           => $shortcode_type . ':' . $shortcode_name,
		'object'        => '', 			// set in sf_admin_page_get_all_shortcodes
		'type'          => $shortcode_type,
		'name'          => $shortcode_name,
		'attributes'    => array(),  	// set in sf_admin_page_get_all_shortcodes
		'file'          => $shortcode_file_name,
		'line'          => $shortcode_line,
		'callback-type' => $callback_type,
		'callback-name' => $callback_name );

	return $shortcode_origin;
}

function sf_shortcode_attributes_processor( $shortcode ) {
	global $sf_admin_page_shortcode_atts;

	// Register filter for shortcode attributes
	$filter = 'shortcode_atts_' . $shortcode;

	add_filter( $filter, 'sf_shortcode_attributes_collector', PHP_INT_MAX, 4 );

	ob_start();

	// Process shortcode which will trigger the attribute filter
	$out = do_shortcode( '[' . $shortcode . ']' );

	ob_end_clean();

 	// Unregister filter
 	remove_filter( $filter, 'sf_shortcode_attributes_collector', PHP_INT_MAX );

	if ( is_array( $sf_admin_page_shortcode_atts[$shortcode] ) ) {
		$atts = $sf_admin_page_shortcode_atts[$shortcode];
	} else {
		$atts = '';
	}

	return $atts;
}

/**
 * Add supported attributes, and their defaults, to the global attributes variable.
 *
 * @since    1.4.0
 */
function sf_shortcode_attributes_collector( $out, $pairs, $atts, $shortcode ) {
	global $sf_admin_page_shortcode_atts;

	$sf_admin_page_shortcode_atts[$shortcode] = $pairs;
}

/**
 * Get all objects (plugin, theme or wordpress) that provide shortcodes.
 *
 * @since    1.4.0
 */
function sf_get_objects_with_shortcodes( $shortcodes ) {

	$all_objects = array();

	foreach ($shortcodes as $shortcode_origin) {
		$object_tag = $shortcode_origin['tag'];

		if ( ! array_key_exists( $object_tag, $all_objects ) ) {
			$all_objects[$object_tag] = $shortcode_origin['object'];
		}
	};

	asort($all_objects);

	return $all_objects;
}

/**
 * Retrieve the regex pattern to find unused shortcodes.
 *
 * @since    1.2.9
 */
function sf_get_shortcode_unused_regex() {
    sf_import_third_party_shortcodes();
    global $shortcode_tags;

    $tagnames = array_keys($shortcode_tags);
    $tagregexp = join('|', array_map('preg_quote', $tagnames));

    $regex = "\["                              // Opening bracket
        . "(\[?)"                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]

        . "(?!\/)"                 // SCRIBIT: Not starting with slash

        . "(?!$tagregexp$)"                     // 2: Shortcode name different from registered shortcodes
        . "("                                // 3: Unroll the loop: Inside the opening shortcode tag
        .     "[^\]\/]*"                   // Not a closing bracket or forward slash
        .     "(?:"
        .         "\/(?!\])"               // A forward slash not followed by a closing bracket
        .         "[^\]\/]*"               // Not a closing bracket or forward slash
        .     ")*?"
        . ")"
        . "(?:"
        .     "(\/)"                        // 4: Self closing tag ...
        .     "\]"                          // ... and closing bracket
        . "|"
        .     "\]"                          // Closing bracket
        .     "(?:"
        .         "("                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
        .             "[^\[]*+"             // Not an opening bracket
        .             "(?:"
        .                 "\[(?!\/\2\])" // An opening bracket not followed by the closing shortcode tag
        .                 "[^\[]*+"         // Not an opening bracket
        .             ")*+"
        .         ")"
        .         "\[\/\2\]"             // Closing shortcode tag
        .     ")?"
        . ")"
        . "(\]?)";

    return $regex;
}

/**
 * Clear a string from unused shortcodes (mantaining internal content).
 *
 * @since    1.3.0
 */
function sf_clear_content_from_shortcode_unused($content) {
    //remove_all_shortcodes();
    sf_import_third_party_shortcodes();
    global $shortcode_tags;

    $tagnames = array_keys($shortcode_tags);
    $r1 = md5(microtime());
    $r2 = md5(((int)microtime()) + 1);

    // Replace slash (not in shortcodes closing tag) with random value
    $content = str_replace("[/", $r1, $content);
    $content = str_replace("/", $r2, $content);
    $content = str_replace($r1, "[/", $content);

    if (!empty($tagnames)) {
        $tagregexp = join('|', array_map('preg_quote', $tagnames));
        $content= preg_replace("~(?:\[/?)(?!(?:$tagregexp))[^/\]]+/?\]~s", '', $content);
    } else {
        $content= preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content);
    }

    // Take back the slash to the content
    return str_replace($r2, "/", $content);
}

/**
 * Escape html text for webpage printing.
 *
 * @since    1.2.9
 */
function sf_html_to_text($html) {
    $html = str_replace('<', '&lt;', $html);
    $html = str_replace('>', '&gt;', $html);
    return $html;
}
