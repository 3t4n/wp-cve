<?php
/**
 * Absolute Addons Helper Functions
 *
 * @package AbsoluteAddons
 * @version 1.0.0
 */

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Check if Pro Version is installed.
 *
 * @return bool
 */
function absp_has_pro() {
	return defined( 'ABSOLUTE_ADDONS_PRO_VERSION' );
}

function absp_get_site_hash() {
	return md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() );
}

/**
 * Encrypt data
 *
 * @param mixed $data
 *
 * @return string
 */
function absp_encrypt( $data = '' ) {

	$data = maybe_serialize( $data );

	// bail early if no encrypt function
	if ( ! function_exists( 'openssl_encrypt' ) ) {
		return base64_encode( $data );
	}

	// generate a key
	$key = wp_hash( 'absp_encrypt_' . AUTH_KEY );

	// Generate an initialization vector
	$iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( 'aes-256-cbc' ) );

	// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
	$encrypted_data = openssl_encrypt( $data, 'aes-256-cbc', $key, 0, $iv );

	// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
	return base64_encode( $encrypted_data . '::' . $iv );
}

/**
 * decrypt data
 *
 * @param string $data
 *
 * @return mixed
 */
function absp_decrypt( $data = '' ) {

	if ( empty( $data ) || ! is_string( $data ) ) {
		return $data;
	}

	$data = base64_decode( $data );

	// bail early if no decrypt function
	if ( function_exists( 'openssl_decrypt' ) && false !== strpos( $data, '::' ) ) {
		// generate a key
		$key = wp_hash( 'absp_encrypt_' . AUTH_KEY );
		// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
		list( $encrypted_data, $iv ) = explode( '::', $data, 2 );
		// decrypt
		$data = openssl_decrypt( $encrypted_data, 'aes-256-cbc', $key, 0, $iv );
	}

	return maybe_unserialize( $data );
}

function absp__return_no() {
	return 'no';
}

function absp__return_yes() {
	return 'yes';
}

function absp_get_unsafe_client_ip() {
	$client_ip = false;

	// In order of preference, with the best ones for this purpose first.
	$address_headers = array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR',
	);

	foreach ( $address_headers as $header ) {
		if ( array_key_exists( $header, $_SERVER ) ) {
			/*
			 * HTTP_X_FORWARDED_FOR can contain a chain of comma-separated
			 * addresses. The first one is the original client. It can't be
			 * trusted for authenticity, but we don't need to for this purpose.
			 */
			$address_chain = explode( ',', sanitize_text_field( $_SERVER[ $header ] ) );
			$client_ip     = trim( $address_chain[0] );

			break;
		}
	}

	if ( ! $client_ip ) {
		return false;
	}

	$anon_ip = wp_privacy_anonymize_ip( $client_ip, true );

	if ( '0.0.0.0' === $anon_ip || '::' === $anon_ip ) {
		return false;
	}

	return $anon_ip;
}

if ( ! function_exists( 'timezone_offset_string' ) ) {
	/**
	 * @param int $offset
	 *
	 * @return string
	 */
	function timezone_offset_string( $offset ) {
		return sprintf( "%s%02d:%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ), abs( $offset % 3600 ) / 60 );
	}
}

/**
 * Parse arguments with defaults recursively.
 *
 * @param array|object $args Arguments to parse.
 * @param array|object $defaults Default set.
 *
 * @return array|object Parsed arguments.
 * @see wp_parse_args()
 *
 */
function absp_parse_args_recursive( $args, $defaults = [] ) {

	if ( is_object( $args ) ) {
		$merged = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$merged = $args;
	} else {
		wp_parse_str( $args, $merged );
	}

	if ( is_object( $defaults ) ) {
		$defaults = get_object_vars( $defaults );
	}

	if ( ! is_array( $defaults ) || empty( $defaults ) ) {
		return $merged;
	}

	if ( empty( $merged ) ) {
		return $defaults;
	}

	foreach ( $defaults as $key => $value ) {

		if ( ! isset( $merged[ $key ] ) ) {
			$merged[ $key ] = $value;
		} else {
			if ( is_array( $value ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = absp_parse_args_recursive( $merged[ $key ], $value );
			}
		}
	}

	return $merged;
}

/**
 * @param array $a
 * @param array $b
 *
 * @return int
 */
function absp_usort_position( $a, $b ) {

	if ( $a['position'] == $b['position'] ) {
		return 0;
	}

	return ( $a['position'] < $b['position'] ) ? - 1 : 1;
}

/**
 * Retrieve or display nonce hidden field for forms.
 * Extend WordPress core wp_nonce_field for id conflict.
 *
 * @param int|string $action Optional. Action name. Default -1.
 * @param string $name Optional. Nonce name. Default '_wpnonce'.
 * @param bool $referer Optional. Whether to set the referer field for validation. Default true.
 * @param bool $echo Optional. Whether to display or return hidden form field. Default true.
 *
 * @return string Nonce field HTML markup.
 * @see wp_nonce_field()
 *
 */
function absp_nonce_field( $action = - 1, $name = '_wpnonce', $referer = true, $echo = true ) {
	$name        = esc_attr( $name );
	$id          = wp_unique_id( $name );
	$nonce_field = '<input type="hidden" id="' . $id . '" data-id="' . $name . '" name="' . $name . '" value="' . esc_attr( wp_create_nonce( $action ) ) . '">';

	if ( $referer ) {
		$nonce_field .= wp_referer_field( false );
	}

	if ( $echo ) {
		echo $nonce_field; // phpcs:ignore
	}

	return $nonce_field;
}

/**
 * Common Kses for Widget Title.
 *
 * @param string $string string for stripping unwanted tag from.
 *
 * @return string|void
 */
function absp_widget_title_kses( $string, $echo = true ) {
	$allowed = [
		'span'   => [
			'style' => [],
			'class' => [],
		],
		'b'      => [
			'style' => [],
			'class' => [],
		],
		'strong' => [
			'style' => [],
			'class' => [],
		],
		'u'      => [
			'style' => [],
			'class' => [],
		],
		'i'      => [
			'style' => [],
			'class' => [],
		],
		'br'     => [
			'style' => [],
			'class' => [],
		],
	];
	$allowed = apply_filters( 'absp/widgets/the_title/allowed_html', $allowed );
	if ( ! $echo ) {
		return wp_kses( $string, $allowed );
	}
	echo wp_kses( $string, $allowed );
}

if ( ! function_exists( 'wp_kses_post_e' ) ) {
	/**
	 * Sanitizes content for allowed HTML tags for large (post) content.
	 *
	 * @param string $data Content to filter.
	 *
	 * @return void
	 */
	function wp_kses_post_e( $data ) {
		echo wp_kses_post( $data );
	}
}

if ( ! function_exists( 'esc_tag' ) ) {
	function esc_tag( $tag, $echo = true ) {
		if ( false !== $echo ) {
			echo tag_escape( $tag );
		}

		return tag_escape( $tag );
	}
}

/**
 * @param $post
 *
 * @return false|WP_Post
 */
function absp_get_displayable_post( $post ) {
	$post = get_post( $post );
	if ( empty( $post ) || post_password_required( $post ) ) {
		return false;
	}

	return $post;
}

function absp_tag_name( $tag ) {
	echo tag_escape( Utils::validate_html_tag( $tag ) );
}

/**
 * @param string $tag
 * @param string $attribute_key
 * @param AbsoluteAddons\Absp_Widget $widget
 */
function absp_tag_start( $tag, $attribute_key = '', $widget = null ) {
	if ( $attribute_key && $widget && method_exists( $widget, 'print_attribute' ) ) {
		?><<?php absp_tag_name( $tag ); ?> <?php $widget->print_attribute( $attribute_key )?>><?php
	} else {
		?><<?php absp_tag_name( $tag ); ?>><?php
	}
}

function absp_tag_end( $tag ) {
	?></<?php absp_tag_name( $tag ); ?>><?php
}

/**
 * Filter input content intended for content title with optional markup.
 *
 * @param string $before Optional. Markup to prepend to the title. Default empty.
 * @param string $after Optional. Markup to append to the title. Default empty.
 * @param bool $echo Optional. Whether to echo or return the title. Default true for echo.
 *
 * @return void|string Void if `$echo` argument is true, current post title if `$echo` is false.
 */
function absp_render_title( $title, $before = '', $after = '', $echo = true ) {
	$title = apply_filters( 'absp/widgets/the_title', $title );

	if ( strlen( $title ) == 0 ) {
		return;
	}

	if ( $echo ) {
		if ( $before ) {
			wp_kses_post_e( $before );
		}
		absp_widget_title_kses( $title );
		if ( $after ) {
			wp_kses_post_e( $after );
		}
	} else {
		return $before . $title . $after;
	}
}

function absp_trim_render_title( $title, $length = 55, $excerpt_more = '[&hellip;]', $before = '', $after = '', $echo = true ) {
	return absp_render_title( absp_trim_excerpt( $title, $length, $excerpt_more ), $before, $after, $echo );
}

/**
 * @param int|WP_Post $post
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_post_title( $post, $before = '', $after = '', $echo = true ) {
	$post = absp_get_displayable_post( $post );

	if ( $post ) {
		$content = absp_render_title( $post->post_title, $before, $after, $echo );

		if ( ! $echo ) {
			return $content;
		}
	}
}

/**
 * Filter input string intended for main content.
 *
 * @param string $content string to filter and output.
 * @param bool $echo Optional. Whether to echo or return the title. Default true for echo.
 *
 * @return void|string Void if `$echo` argument is true, current post title if `$echo` is false.
 */
function absp_render_content( $content, $echo = true ) {

	/**
	 * Filters the content.
	 *
	 * @param string $content Content.
	 */
	$content = apply_filters( 'absp/widgets/the_content', $content );

	if ( $echo ) {
		echo wp_kses_post( $content );
	} else {
		return $content;
	}
}

/**
 * @param int|WP_Post $post
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_post_content( $post, $echo = true ) {
	$post = absp_get_displayable_post( $post );

	if ( $post ) {
		$content = absp_render_content( $post->post_content, $echo );

		if ( ! $echo ) {
			return $content;
		}
	}
}

/**
 * @param $content
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_content_no_pe( $content, $echo = true ) {
	remove_filter( 'absp/widgets/the_content', 'wpautop' );
	// render content auto echo if $echo is true.
	$content = absp_render_content( $content, $echo );
	add_filter( 'absp/widgets/the_content', 'wpautop' );
	if ( ! $echo ) {
		return $content;
	}
}

/**
 * @param int|WP_Post $post
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_post_content_no_pe( $post, $echo = true ) {
	$post = absp_get_displayable_post( $post );

	if ( $post ) {
		$content = absp_render_content_no_pe( $post->post_content, $echo );

		if ( ! $echo ) {
			return $content;
		}
	}
}

/**
 * @param $content
 * @param int $length
 * @param string $excerpt_more
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_excerpt( $content, $length = 55, $excerpt_more = '[&hellip;]', $echo = true ) {

	$excerpt = apply_filters( 'absp/widgets/the_excerpt', absp_trim_excerpt( $content, $length, $excerpt_more ) );

	if ( $echo ) {
		echo wp_kses_post( $excerpt );
	} else {
		return $excerpt;
	}
}

/**
 * @param int|WP_Post $post
 * @param int $length
 * @param string $excerpt_more
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_post_excerpt( $post, $length = 55, $excerpt_more = '[&hellip;]', $echo = true ) {

	$post = absp_get_displayable_post( $post );

	if ( $post ) {
		$excerpt = absp_render_excerpt( $post->post_excerpt, $length, $excerpt_more, $echo );

		if ( ! $echo ) {
			return $excerpt;
		}
	}
}

/**
 * @param $content
 * @param int $length
 * @param string $excerpt_more
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_excerpt_no_pe( $content, $length = 55, $excerpt_more = '[&hellip;]', $echo = true ) {
	remove_filter( 'absp/widgets/the_excerpt', 'wpautop' );
	// render content auto echo if $echo is true.
	$content = absp_render_excerpt( $content, $length, $excerpt_more, $echo );
	add_filter( 'absp/widgets/the_excerpt', 'wpautop' );
	if ( ! $echo ) {
		return $content;
	}
}

/**
 * @param int|WP_Post $post
 * @param int $length
 * @param string $excerpt_more
 * @param bool $echo
 *
 * @return string|void
 */
function absp_render_post_excerpt_no_pe( $post, $length = 55, $excerpt_more = '[&hellip;]', $echo = true ) {

	$post = absp_get_displayable_post( $post );

	if ( $post ) {
		$excerpt = absp_render_excerpt_no_pe( $post->post_excerpt, $length, $excerpt_more, $echo );

		if ( ! $echo ) {
			return $excerpt;
		}
	}
}

/**
 * Trim Excerpt content remove shortcodes to cleanup.
 *
 * @param string $text Content
 * @param int $length Length
 * @param string $excerpt_more More...
 *
 * @see wp_trim_excerpt()
 *
 * @return string
 */
function absp_trim_excerpt( $text = '', $length = 55, $excerpt_more = '[&hellip;]' ) {
	$text = trim( $text );
	if ( '' !== $text ) {
		$text = strip_shortcodes( $text );
		$text = excerpt_remove_blocks( $text );
		$text = absp_render_content( $text, false );
		$text = str_replace( ']]>', ']]&gt;', $text );
		$text = wp_trim_words( $text, $length, $excerpt_more );
	}

	return $text;
}

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
 *
 * @return bool
 */
function absp_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
}

/**
 * Remove trailing and leading slash from file name or path.
 *
 * @param string $path Name of the file or directory.
 *
 * @return string
 */
function absp_clean_file_path( $path ) {
	$path = ltrim( $path, '/\\' );

	return untrailingslashit( $path );
}

function absp_plugin_install_url( $slug ) {
	return admin_url( 'plugin-install.php?s=' . $slug . '&tab=search&type=term&absp_dependency=' . $slug );
}

/**
 * Filter plugin-install-api results to help the user find correct plugin for widget dependency.
 *
 * @param object|WP_Error $result
 * @param string $action
 * @param object $args
 *
 * @return object|WP_Error
 * @noinspection PhpUnusedParameterInspection
 */
function absp_plugin_api_results_dependency_filter( $result, $action, $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
	$is_dep_check = isset( $_GET['absp_dependency'] ) && ! empty( $_GET['absp_dependency'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $is_dep_check && ! is_wp_error( $result ) ) {

		$plugins = array_filter( $result->plugins, function ( $plugin ) use ( $args ) {
			return $args->search === $plugin['slug'];
		} );

		if ( ! empty( $plugins ) ) {
			$result->plugins = $plugins;
		}

		$result->info = [
			'page'    => 1,
			'pages'   => 1,
			'results' => 1,
		];

		unset( $plugins );
	}

	return $result;
}

/**
 * Check if a plugin is installed.
 *
 * @param string $basename Plugin dir/main-file path.
 *
 * @return bool
 */
function absp_is_plugin_installed( $basename ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		include_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	$plugins = get_plugins();

	return isset( $plugins[ $basename ] );
}

/**
 * Determines whether a plugin is active.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 *
 * @return bool True, if in the active plugins list. False, not in the list.
 * @uses is_plugin_active()
 */
function absp_is_plugin_active( $plugin ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	return is_plugin_active( $plugin );
}

/**
 * Determines whether the plugin is inactive.
 *
 * Reverse of absp_is_plugin_active(). Used as a callback.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 *
 * @return bool True if inactive. False if active.
 * @see absp_is_plugin_active()
 *
 */
function absp_is_plugin_inactive( $plugin ) {
	return ! absp_is_plugin_active( $plugin );
}

/**
 * Get full path for file relative to plugin directory.
 *
 * @param string $path File or path to resolve.
 *
 * @return string
 */
function absp_plugin_path( $path ) {
	return ABSOLUTE_ADDONS_PATH . absp_clean_file_path( $path );
}

/**
 * Get full url for file relative to this plugin directory.
 *
 * @param string $path Name of the file or directory to get the url for.
 * @param bool $echo echo or return default echo.
 *
 * @return string|void
 */
function absp_plugin_url( $path, $echo = true ) {
	$url = plugins_url( absp_clean_file_path( $path ), ABSOLUTE_ADDONS_FILE );
	if ( ! $echo ) {
		return $url;
	} else {
		echo esc_url( $url );
	}
}

/**
 * Get Default Placeholder url
 *
 * @param string $file_name Optional. File name
 *
 * @return string
 */
function absp_get_default_placeholder( $file_name = '' ) {
	if ( empty( $file_name ) ) {
		return Elementor\Utils::get_placeholder_image_src();
	}

	$file_name = absp_clean_file_path( $file_name );
	$file_name = 'assets/images/placeholder/' . $file_name;

	if ( ! file_exists( absp_plugin_path( $file_name ) ) ) {
		return '';
	}

	return absp_plugin_url( $file_name, false );
}

/**
 * @param string $file_name
 * @param bool $echo
 *
 * @return string|void
 */
function absp_default_placeholder_src( $file_name = '', $echo = true ) {
	if ( ! $echo ) {
		return absp_get_default_placeholder( $file_name );
	} else {
		echo esc_url( absp_get_default_placeholder( $file_name ) );
	}
}

/**
 * Get Default Placeholder Args for widget
 *
 * @return array
 */
function absp_get_placeholder() {
	return [ 'url' => Elementor\Utils::get_placeholder_image_src() ];
}

/**
 * @param $regular_price
 *
 * @return array
 */
function absp_regular_pricing_price_format( $regular_price ) {
	$regular_price_float = number_format( $regular_price, 2, '.', '' );
	$regular_price_parts = explode( '.', $regular_price_float );

	return [
		'price_original_value'      => $regular_price,
		'price_float_value'         => $regular_price_float,
		'price_only_int'            => $regular_price_parts[0],
		'price_after_decimal_point' => $regular_price_parts[1],
		'price_decimal_point'       => _x( '.', 'Price Decimal Point', 'absolute-addons' ),
	];
}

/**
 * @param $price
 *
 * @return array
 */
function absp_pricing_price_format( $price ) {
	$price_float = number_format( $price, 2, '.', '' );
	$price_parts = explode( '.', $price_float );

	return [
		'original_value'      => $price,
		'float_value'         => $price_float,
		'only_int'            => $price_parts[0],
		'after_decimal_point' => $price_parts[1],
		'decimal_point'       => _x( '.', 'Price Table Decimal Point', 'absolute-addons' ),
		'period_separator'    => _x( '/', 'Pricing Table Period Separator', 'absolute-addons' ),
	];
}

function absp_remote_get( $url, $args = [] ) {
	if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
		return vip_safe_wp_remote_get( $url, $args );
	} else {
		return wp_safe_remote_get( $url, $args );
	}
}

function absp_remote_post( $url, $args = [] ) {
	if ( function_exists( 'vip_safe_wp_remote_post' ) ) {
		return vip_safe_wp_remote_post( $url, $args );
	} else {
		return wp_safe_remote_post( $url, $args );
	}
}

function absp_get_wp_time( $time = null ) {
	if ( null === $time ) {
		$time = time();
	} elseif ( ! is_numeric( $time ) ) {
		$time = strtotime( $time );
	}
	return false !== $time ? $time + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) : time();
}

/**
 * Check elementor version
 *
 * @param string $operator
 * @param string $version
 * @return bool
 */
function absp_is_elementor_version( $operator = '<', $version = '3.2.5' ) {
	return defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, $version, $operator);
}

if ( ! function_exists( 'wp_readonly' ) ) {
	function wp_readonly( $readonly, $current = true, $echo = true ) {
		return __checked_selected_helper( $readonly, $current, $echo, 'readonly' );
	}
}

/**
 *
 */
function absp_wc_loop_add_to_cart( $args = array() ) {
	global $product;

	if ( $product ) {
		$defaults = array(
			'quantity'   => 1,
			'class'      => implode(
				' ',
				array_filter(
					array(
						'button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
					)
				)
			),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
		}

		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'absp/widgets/product-carousel/woocommerce_loop_add_to_cart_link',
			sprintf(
				'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
			$product,
			$args
		);
	}
}
// End of file helper.php.
