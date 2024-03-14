<?php
/**
 * The basic helpers functions
 *
 * @package Canvas
 */

/**
 * Processing path of style.
 *
 * @param string $path URL to the stylesheet.
 */
function cnvs_style( $path ) {
	// Check RTL.
	if ( is_rtl() ) {
		return $path;
	}

	// Check Dev.
	$dev = CNVS_PATH . 'assets/css/canvas-dev.css';

	if ( file_exists( $dev ) ) {
		return str_replace( '.css', '-dev.css', $path );
	}

	return $path;
}


if ( ! function_exists( 'cnvs_powerkit_module_enabled' ) ) {
	/**
	 * Helper function to check the status of powerkit modules
	 *
	 * @param array $name Name of module.
	 */
	function cnvs_powerkit_module_enabled( $name ) {
		if ( function_exists( 'powerkit_module_enabled' ) && powerkit_module_enabled( $name ) ) {
			return true;
		}
	}
}

if ( ! function_exists( 'cnvs_post_views_enabled' ) ) {
	/**
	 * Check post views module.
	 *
	 * @return string Type.
	 */
	function cnvs_post_views_enabled() {

		// Post Views Counter.
		if ( class_exists( 'Post_Views_Counter' ) ) {
			return 'post_views';
		}

		// Powerkit Post Views.
		if ( cnvs_powerkit_module_enabled( 'post_views' ) ) {
			return 'pk_post_views';
		}
	}
}

/**
 * Output error message.
 *
 * @param string $message The error message.
 */
function cnvs_alert_warning( $message ) {
	if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
		?>
		<p class="cnvs-alert cnvs-alert-warning" role="alert">
			<?php echo wp_kses( $message, 'post' ); ?>
		</p>
		<?php
	}
}

if ( ! function_exists( 'cnvs_get_round_number' ) ) {
	/**
	 * Get rounded number.
	 *
	 * @param int $number    Input number.
	 * @param int $min_value Minimum value to round number.
	 * @param int $decimal   How may decimals shall be in the rounded number.
	 */
	function cnvs_get_round_number( $number, $min_value = 1000, $decimal = 1 ) {
		if ( $number < $min_value ) {
			return number_format_i18n( $number );
		}
		$alphabets = array(
			1000000000 => 'B',
			1000000    => 'M',
			1000       => 'K',
		);
		foreach ( $alphabets as $key => $value ) {
			if ( $number >= $key ) {
				return round( $number / $key, $decimal ) . $value;
			}
		}
	}
}

/**
 * Truncates string with specified length
 *
 * @param  string $string      Text string.
 * @param  int    $length      Letters length.
 * @param  string $etc         End truncate.
 * @param  bool   $break_words Break words or not.
 * @return string
 */
function cnvs_str_truncate( $string, $length = 80, $etc = '&hellip;', $break_words = false ) {
	if ( 0 === $length ) {
		return '';
	}

	if ( function_exists( 'mb_strlen' ) ) {

		// MultiBite string functions.
		if ( mb_strlen( $string ) > $length ) {
			$length -= min( $length, mb_strlen( $etc ) );
			if ( ! $break_words ) {
				$string = preg_replace( '/\s+?(\S+)?$/', '', mb_substr( $string, 0, $length + 1 ) );
			}

			return mb_substr( $string, 0, $length ) . $etc;
		}
	} else {

		// Default string functions.
		if ( strlen( $string ) > $length ) {
			$length -= min( $length, strlen( $etc ) );
			if ( ! $break_words ) {
				$string = preg_replace( '/\s+?(\S+)?$/', '', substr( $string, 0, $length + 1 ) );
			}

			return substr( $string, 0, $length ) . $etc;
		}
	}

	return $string;
}

/**
 * Set number to Short Form
 *
 * @param int $n       The number.
 * @param int $decimal The decimal.
 */
function cnvs_abridged_number( $n, $decimal = 1 ) {

	// First strip any formatting.
	$n = (float) str_replace( ',', '', $n );

	// Is this a number?
	if ( ! is_numeric( $n ) ) {
		return false;
	}

	// Return current count.
	if ( $n < 1000 ) {
		return number_format_i18n( $n );
	}

	// Add suffix.
	$suffix = array(
		1000000000 => esc_html__( 'B', 'canvas' ), // Billion.
		1000000    => esc_html__( 'M', 'canvas' ), // Million.
		1000       => esc_html__( 'K', 'canvas' ), // Thousand.
	);
	foreach ( $suffix as $k => $v ) {
		if ( $n >= $k ) {
			return number_format_i18n( $n / $k, $decimal ) . $v;
		}
	}
}

/**
 * Time ago
 *
 * @param  string $time The time.
 * @return string
 */
function cnvs_timing_ago( $time ) {
	$periods = array( esc_html__( 'second', 'canvas' ), esc_html__( 'minute', 'canvas' ), esc_html__( 'hour', 'canvas' ), esc_html__( 'day', 'canvas' ), esc_html__( 'week', 'canvas' ), esc_html__( 'month', 'canvas' ), esc_html__( 'year', 'canvas' ), esc_html__( 'decade', 'canvas' ) );
	$lengths = array( '60', '60', '24', '7', '4.35', '12', '10' );

	$now = time();

	$difference = $now - $time;
	$tense      = esc_html__( 'ago', 'canvas' );

	$lengths_count = count( $lengths );

	for ( $j = 0; $difference >= $lengths[ $j ] && $j < $lengths_count - 1; $j++ ) {
		$difference /= $lengths[ $j ];
	}

	$difference = round( $difference );

	if ( 1 !== $difference ) {
		$periods[ $j ] .= 's';
	}

	return "$difference $periods[$j] {$tense} ";
}

/**
 * Encode data
 *
 * @param  mixed  $content    The content.
 * @param  string $secret_key The key.
 * @return string
 */
function cnvs_encode_data( $content, $secret_key = 'canvas' ) {

	$content = wp_json_encode( $content );

	return base64_encode( $content );
}

/**
 * Decode data
 *
 * @param  string $content    The content.
 * @param  string $secret_key The key.
 * @return string
 */
function cnvs_decode_data( $content, $secret_key = 'canvas' ) {

	$content = base64_decode( $content );

	return json_decode( $content );
}

/**
 * Encrypt data
 *
 * @param  mixed  $content    The content.
 * @param  string $secret_key The key.
 * @return string
 */
function cnvs_encrypt_data( $content, $secret_key = 'canvas' ) {

	$content = maybe_serialize( $content );

	if ( function_exists( 'openssl_encrypt' ) && function_exists( 'hash' ) ) {
		$encrypt_method = 'AES-256-CBC';

		$key = hash( 'sha256', $secret_key );
		$iv  = substr( hash( 'sha256', 'secret key' ), 0, 16 );

		return base64_encode( openssl_encrypt( $content, $encrypt_method, $key, 0, $iv ) );
	} else {
		return base64_encode( $content );
	}
}

/**
 * Decrypt data
 *
 * @param  string $content    The content.
 * @param  string $secret_key The key.
 * @return string
 */
function cnvs_decrypt_data( $content, $secret_key = 'canvas' ) {

	if ( function_exists( 'openssl_encrypt' ) && function_exists( 'hash' ) ) {
		$encrypt_method = 'AES-256-CBC';

		$key = hash( 'sha256', $secret_key );
		$iv  = substr( hash( 'sha256', 'secret key' ), 0, 16 );

		$content = openssl_decrypt( base64_decode( $content ), $encrypt_method, $key, 0, $iv );
	} else {
		$content = base64_decode( $content );
	}

	$content = maybe_unserialize( $content );

	return $content;
}

/**
 * Get the user uuid
 *
 * @return string
 */
function cnvs_get_user_uuid() {
	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		return getenv( 'HTTP_CLIENT_IP' );
	} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		return getenv( 'HTTP_X_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
		return getenv( 'HTTP_X_FORWARDED' );
	} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		return getenv( 'HTTP_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
		return getenv( 'HTTP_FORWARDED' );
	} elseif ( getenv( 'REMOTE_ADDR' ) ) {
		return getenv( 'REMOTE_ADDR' );
	}

	return uniqid( 'x', true );
}

/**
 * Retrieve paginated link for archive post pages.
 *
 * @param string|array $args Array or string of arguments for generating paginated links for archives.
 */
function cnvs_paginate_links( $args = '' ) {
	global $wp_query, $wp_rewrite;

	// Setting up default values based on the current URL.
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$url_parts    = explode( '?', $pagenum_link );

	// Get max pages and current page out of the current query, if available.
	$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
	$current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

	// Append the format placeholder to the base URL.
	$pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

	// URL base depends on permalink settings.
	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	$defaults = array(
		'base'               => $pagenum_link,
		'format'             => $format,
		'total'              => $total,
		'current'            => $current,
		'aria_current'       => 'page',
		'show_all'           => false,
		'prev_next'          => true,
		'prev_text'          => esc_html__( '&laquo; Previous', 'canvas' ),
		'next_text'          => esc_html__( 'Next &raquo;', 'canvas' ),
		'end_size'           => 1,
		'mid_size'           => 2,
		'type'               => 'plain',
		'add_args'           => array(),
		'add_fragment'       => '',
		'before_page_number' => '',
		'after_page_number'  => '',
		'merge_query_vars'   => true,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ! is_array( $args['add_args'] ) ) {
		$args['add_args'] = array();
	}

	// Merge additional query vars found in the original URL into 'add_args' array.
	if ( isset( $url_parts[1] ) && $args['merge_query_vars'] ) {
		// Find the format argument.
		$format       = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
		$format_query = isset( $format[1] ) ? $format[1] : '';
		wp_parse_str( $format_query, $format_args );

		// Find the query args of the requested URL.
		wp_parse_str( $url_parts[1], $url_query_args );

		// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
		foreach ( $format_args as $format_arg => $format_arg_value ) {
			unset( $url_query_args[ $format_arg ] );
		}

		$args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
	}

	// Who knows what else people pass in $args.
	$total = (int) $args['total'];
	if ( $total < 2 ) {
		return;
	}
	$current  = (int) $args['current'];
	$end_size = (int) $args['end_size'];
	if ( $end_size < 1 ) {
		$end_size = 1;
	}
	$mid_size = (int) $args['mid_size'];
	if ( $mid_size < 0 ) {
		$mid_size = 2;
	}
	$add_args   = $args['add_args'];
	$r          = '';
	$page_links = array();
	$dots       = false;

	if ( $args['prev_next'] && $current && 1 < $current ) :
		$link = str_replace( '%_%', 2 === $current ? '' : $args['format'], $args['base'] );
		$link = str_replace( '%#%', $current - 1, $link );
		if ( $add_args ) {
			$link = add_query_arg( $add_args, $link );
		}
		$link .= $args['add_fragment'];

		/**
		 * Filters the paginated links for the given archive pages.
		 *
		 * @param string $link The paginated link URL.
		 */
		$page_links[] = '<a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
	endif;
	for ( $n = 1; $n <= $total; $n++ ) :
		if ( $n === $current ) :
			$page_links[] = "<span aria-current='" . esc_attr( $args['aria_current'] ) . "' class='page-numbers current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . '</span>';
			$dots         = true;
		else :
			if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
				$link = str_replace( '%_%', 1 === $n ? '' : $args['format'], $args['base'] );
				$link = str_replace( '%#%', $n, $link );
				if ( $add_args ) {
					$link = add_query_arg( $add_args, $link );
				}
				$link .= $args['add_fragment'];

				/** This filter is documented in wp-includes/general-template.php */
				$page_links[] = "<a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . '</a>';
				$dots         = true;
			elseif ( $dots && ! $args['show_all'] ) :
				$page_links[] = '<span class="page-numbers dots">' . esc_html__( '&hellip;', 'canvas' ) . '</span>';
				$dots         = false;
			endif;
		endif;
	endfor;
	if ( $args['prev_next'] && $current && $current < $total ) :
		$link = str_replace( '%_%', $args['format'], $args['base'] );
		$link = str_replace( '%#%', $current + 1, $link );
		if ( $add_args ) {
			$link = add_query_arg( $add_args, $link );
		}
		$link .= $args['add_fragment'];

		/** This filter is documented in wp-includes/general-template.php */
		$page_links[] = '<a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
	endif;
	switch ( $args['type'] ) {
		case 'array':
			return $page_links;

		case 'list':
			$r .= "<ul class='page-numbers'>\n\t<li>";
			$r .= join( "</li>\n\t<li>", $page_links );
			$r .= "</li>\n</ul>\n";
			break;

		default:
			$r = join( "\n", $page_links );
			break;
	}
	return $r;
}

/**
 * Get blog posts page URL.
 *
 * @param array $attributes The attributes.
 */
function cnvs_get_block_posts_page_url( $attributes ) {
	global $wp_rewrite;

	// The front page IS the posts page. Get its URL.
	$url = get_home_url();

	// If front page is set to display a static page, get the URL of the posts page.
	if ( 'page' === get_option( 'show_on_front' ) ) {
		$url = null;

		if ( get_option( 'page_for_posts' ) ) {
			$url = get_permalink( get_option( 'page_for_posts' ) );
		}
	}

	if ( isset( $attributes['query']['categories'] ) && $attributes['query']['categories'] ) {

		$url = get_term_link( $attributes['query']['categories'], 'category' );
	}

	if ( isset( $attributes['query']['tags'] ) && $attributes['query']['tags'] ) {

		$url = get_term_link( $attributes['query']['tags'], 'post_tag' );
	}

	if ( $url ) {
		if ( $wp_rewrite->using_permalinks() ) {
			$url = untrailingslashit( $url ) . '/page/%#%';
		} else {
			$url = add_query_arg( array( 'paged' => '%#%' ), untrailingslashit( $url ) );
		}

		return user_trailingslashit( $url );
	}
}

/**
 * Get the available image sizes
 */
function cnvs_get_available_image_sizes() {
	$wais = & $GLOBALS['_wp_additional_image_sizes'];

	$sizes       = array();
	$image_sizes = get_intermediate_image_sizes();

	if ( is_array( $image_sizes ) && $image_sizes ) {
		foreach ( $image_sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$sizes[ $size ] = array(
					'width'  => get_option( "{$size}_size_w" ),
					'height' => get_option( "{$size}_size_h" ),
					'crop'   => (bool) get_option( "{$size}_crop" ),
				);
			} elseif ( isset( $wais[ $size ] ) ) {
				$sizes[ $size ] = array(
					'width'  => $wais[ $size ]['width'],
					'height' => $wais[ $size ]['height'],
					'crop'   => $wais[ $size ]['crop'],
				);
			}

			// Size registered, but has 0 width and height.
			if ( 0 === (int) $sizes[ $size ]['width'] && 0 === (int) $sizes[ $size ]['height'] ) {
				unset( $sizes[ $size ] );
			}
		}
	}

	return $sizes;
}

/**
 * Gets the data of a specific image size.
 *
 * @param string $size Name of the size.
 */
function cnvs_get_image_size( $size ) {
	if ( ! is_string( $size ) ) {
		return;
	}

	$sizes = cnvs_get_available_image_sizes();

	return isset( $sizes[ $size ] ) ? $sizes[ $size ] : false;
}

/**
 * Get the list available image sizes
 */
function cnvs_get_list_available_image_sizes() {
	$intermediate_image_sizes = get_intermediate_image_sizes();

	$image_sizes = array();

	foreach ( $intermediate_image_sizes as $size ) {
		$image_sizes[ $size ] = $size;

		$data = cnvs_get_image_size( $size );

		if ( isset( $data['width'] ) || isset( $data['height'] ) ) {

			$width  = '~';
			$height = '~';

			if ( isset( $data['width'] ) && $data['width'] ) {
				$width = $data['width'] . 'px';
			}
			if ( isset( $data['height'] ) && $data['height'] ) {
				$height = $data['height'] . 'px';
			}

			$image_sizes[ $size ] .= sprintf( ' [%s, %s]', $width, $height );
		}
	}

	$image_sizes = apply_filters( 'canvas_list_available_image_sizes', $image_sizes );

	return $image_sizes;
}

/**
 * Get fields array for Button in some PK blocks
 *
 * @param string $field_prefix    Field prefix.
 * @param string $section_name    Section name.
 * @param array  $active_callback Active callback.
 */
function cnvs_get_gutenberg_button_fields( $field_prefix = 'button', $section_name = '', $active_callback = array() ) {

	$fields = array(
		array(
			'key'             => $field_prefix . 'Style',
			'label'           => esc_html__( 'Style', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'select',
			'default'         => '',
			'choices'         => array(
				''        => esc_html__( 'Default', 'canvas' ),
				'outline' => esc_html__( 'Outline', 'canvas' ),
				'squared' => esc_html__( 'Squared', 'canvas' ),
			),
			'active_callback' => $active_callback,
		),
		array(
			'key'             => $field_prefix . 'Size',
			'label'           => esc_html__( 'Size', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'select',
			'default'         => '',
			'choices'         => array(
				''   => esc_html__( 'Default', 'canvas' ),
				'sm' => esc_html__( 'Small', 'canvas' ),
				'lg' => esc_html__( 'Large', 'canvas' ),
			),
			'active_callback' => $active_callback,
		),
		array(
			'key'             => $field_prefix . 'FullWidth',
			'label'           => esc_html__( 'Full Width', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'toggle',
			'default'         => false,
			'active_callback' => $active_callback,
		),
		array(
			'key'             => $field_prefix . 'ColorBg',
			'label'           => esc_html__( 'Background Color', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'color',
			'default'         => '',
			'output'          => array(
				array(
					'element'  => '$ .wp-block-button a.wp-block-button__link',
					'property' => 'background-color',
					'suffix'   => '!important',
				),
			),
			'active_callback' => $active_callback,
		),
		array(
			'key'             => $field_prefix . 'ColorBgHover',
			'label'           => esc_html__( 'Background Color Hover', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'color',
			'default'         => '',
			'output'          => array(
				array(
					'element'  => '$ .wp-block-button a.wp-block-button__link:hover, $ .wp-block-button a.wp-block-button__link:focus',
					'property' => 'background-color',
					'suffix'   => '!important',
				),
			),
			'active_callback' => $active_callback,
		),
		array(
			'key'             => $field_prefix . 'ColorText',
			'label'           => esc_html__( 'Text Color', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'color',
			'default'         => '',
			'output'          => array(
				array(
					'element'  => '$ .wp-block-button__link',
					'property' => 'color',
					'suffix'   => '!important',
				),
			),
			'active_callback' => $active_callback,
		),
		array(
			'key'             => $field_prefix . 'ColorTextHover',
			'label'           => esc_html__( 'Text Color Hover', 'canvas' ),
			'section'         => $section_name,
			'type'            => 'color',
			'default'         => '',
			'output'          => array(
				array(
					'element'  => '$ .wp-block-button a.wp-block-button__link:hover, $ .wp-block-button a.wp-block-button__link:focus',
					'property' => 'color',
					'suffix'   => '!important',
				),
			),
			'active_callback' => $active_callback,
		),
	);

	return $fields;
}

/**
 * Print core/button in some PK blocks
 *
 * @param string $text         Text of button.
 * @param string $url          Url of button.
 * @param string $target       Target.
 * @param string $field_prefix Field prefix.
 * @param array  $attributes   Attributes.
 */
function cnvs_print_gutenberg_blocks_button( $text, $url, $target = '', $field_prefix = 'button', $attributes = array() ) {
	$class_name      = 'wp-block-button';
	$link_class_name = 'wp-block-button__link';

	// Style.
	if ( isset( $attributes[ $field_prefix . 'Style' ] ) && $attributes[ $field_prefix . 'Style' ] ) {
		$class_name .= ' is-style-' . $attributes[ $field_prefix . 'Style' ];
	}

	// Size.
	if ( isset( $attributes[ $field_prefix . 'Size' ] ) && $attributes[ $field_prefix . 'Size' ] ) {
		$class_name .= ' is-cnvs-button-size-' . $attributes[ $field_prefix . 'Size' ];
	}

	// FullWidth.
	if ( isset( $attributes[ $field_prefix . 'FullWidth' ] ) && $attributes[ $field_prefix . 'FullWidth' ] ) {
		$class_name .= ' is-cnvs-button-full-width';
	}

	// Color.
	if ( isset( $attributes[ $field_prefix . 'ColorText' ] ) && $attributes[ $field_prefix . 'ColorText' ] ) {
		$link_class_name .= ' has-text-color';
	}

	// Background.
	if ( isset( $attributes[ $field_prefix . 'ColorBg' ] ) && $attributes[ $field_prefix . 'ColorBg' ] ) {
		$link_class_name .= ' has-background';
	}
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>">
		<a class="<?php echo esc_attr( $link_class_name ); ?>" href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>">
			<?php echo wp_kses_post( $text ); ?>
		</a>
	</div>
	<?php
}
