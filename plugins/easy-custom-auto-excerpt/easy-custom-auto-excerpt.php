<?php
/*
Plugin Name: Easy Custom Auto Excerpt
Plugin URI: https://www.tonjoostudio.com/addons/easy-custom-auto-excerpt/
Description: Auto Excerpt for your post on home, front_page, search and archive.
Version: 2.4.12
Author: tonjoo
Author URI: https://www.tonjoostudio.com/
Contributor: Todi Adiyatmo Wijoyo, Haris Ainur Rozak
*/

$plugin = plugin_basename( __FILE__ );

define( 'ECAE_VERSION', '2.4.12' );
define( 'ECAE_DIR_NAME', str_replace( '/easy-custom-auto-excerpt.php', '', plugin_basename( __FILE__ ) ) );
define( 'ECAE_HTTP_PROTO', is_ssl() ? 'https://' : 'http://' );

require_once plugin_dir_path( __FILE__ ) . 'tonjoo-library.php';
require_once plugin_dir_path( __FILE__ ) . 'default.php';
require_once plugin_dir_path( __FILE__ ) . 'options-page.php';
require_once plugin_dir_path( __FILE__ ) . 'regex.php';
require_once plugin_dir_path( __FILE__ ) . 'advExcLoc.php';
require_once plugin_dir_path( __FILE__ ) . 'ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'the-post.php';
if ( ! function_exists( 'is_ecae_premium_exist' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'tonjoo-notice.php';
}

add_action( 'plugins_loaded', 'tonjoo_ecae_plugin_init' );
/**
 * Plugin init, commonly for localization purpose.
 */
function tonjoo_ecae_plugin_init() {
	// modify post object here.
	global $is_main_query_ecae;

	$is_main_query_ecae = true;

	// Localization.
	load_plugin_textdomain( 'easy-custom-auto-excerpt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'wp_head', 'tonjoo_ecae_remove_all_filters' );
/**
 * Remove some not needed WordPress filters
 */
function tonjoo_ecae_remove_all_filters() {
	remove_all_filters( 'get_the_excerpt' );
	remove_all_filters( 'the_excerpt' );

	/**
	 * Filter get_the_excerpt to return the_content
	 */
	add_filter( 'get_the_excerpt', 'tonjoo_ecae_get_the_excerpt', 999 );
	add_filter( 'the_excerpt', 'tonjoo_ecae_get_the_excerpt', 999 );
}

/**
 * Direcly call the excerpt.
 *
 * @param  string $output Output.
 * @return string
 */
function tonjoo_ecae_get_the_excerpt( $output ) {
	global $post;

	return apply_filters( 'the_content', $post->post_content );
}

add_filter( 'the_content_more_link', 'modify_read_more_link' );
/**
 * Remove default <-- more --> link.
 */
function modify_read_more_link() {
	return '';
}

add_filter( "plugin_action_links_$plugin", 'tonjoo_ecae_donate' );
/**
 * Donation.
 *
 * @param  array $links Links.
 * @return array
 */
function tonjoo_ecae_donate( $links ) {
	$settings_link = '<a href="' . admin_url( 'admin.php?page=tonjoo_excerpt' ) . '" >Settings</a>';
	array_push( $links, $settings_link );

	if ( ! function_exists( 'is_ecae_premium_exist' ) ) {
		$premium_link = '<a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=plugins_page&utm_campaign=upsell">' . esc_html__( 'Upgrade to Pro', 'easy-custom-auto-excerpt' ) . '</a>';
		array_push( $links, $premium_link );
	}

	return $links;
}


add_shortcode( 'ecae_button', 'ecae_button_shortcode' );
/**
 * ECAE button shortcode
 */
function ecae_button_shortcode() {
	$options = get_option( 'tonjoo_ecae_options' );
	$options = tonjoo_ecae_load_default( $options );

	$button_skin          = explode( '-PREMIUM', $options['button_skin'] );
	$trim_readmore_before = trim( $options['read_more_text_before'] );

	$read_more_text_before = empty( $trim_readmore_before ) ? $options['read_more_text_before'] : $options['read_more_text_before'] . '&nbsp;&nbsp;';

	// localization with WPML or not.
	if ( function_exists( 'icl_object_id' ) && function_exists( 'icl_t' ) ) {
		$local_button_text        = icl_t( 'easy-custom-auto-excerpt', 'Readmore text', $options['read_more'] );
		$local_before_button_text = icl_t( 'easy-custom-auto-excerpt', 'Before readmore link', $read_more_text_before );
	} else {
		$local_button_text        = __( $options['read_more'], 'easy-custom-auto-excerpt' );
		$local_before_button_text = __( $read_more_text_before, 'easy-custom-auto-excerpt' );
	}

	$link          = get_permalink();
	$readmore_link = ' <a class="ecae-link" href="' . esc_url( $link ) . '"><span>' . $local_button_text . '</span></a>';
	$readmore      = '<p class="ecae-button ' . esc_attr( $button_skin[0] ) . '" style="text-align:' . esc_attr( $options['read_more_align'] ) . '" >' . $local_before_button_text . ' ' . $readmore_link . '</p>';

	if ( is_single() ) {
		return '';
	} else {
		return $readmore;
	}
}


add_action( 'admin_enqueue_scripts', 'ecae_admin_enqueue_scripts' );
/**
 * Admin enqueue scripts - equeue on plugin page only
 */
function ecae_admin_enqueue_scripts() {
	if ( isset( $_GET['page'] ) && ( 'tonjoo_excerpt' === $_GET['page'] || 'ecae_about_page' === $_GET['page'] ) ) {
		// print script.
		echo '<script type="text/javascript">';
		echo "var ecae_dir_name = '" . esc_url( plugins_url( ECAE_DIR_NAME, dirname( __FILE__ ) ) ) . "';";
		echo "var ecae_button_dir_name = '" . esc_url( plugins_url( ECAE_DIR_NAME . '/buttons/', dirname( __FILE__ ) ) ) . "';";

		if ( function_exists( 'is_ecae_premium_exist' ) ) {
			echo "var ecae_premium_dir_name = '" . esc_url( plugins_url( ECAE_PREMIUM_DIR_NAME, dirname( __FILE__ ) ) ) . "';";
			echo "var ecae_button_premium_dir_name = '" . esc_url( plugins_url( ECAE_PREMIUM_DIR_NAME . '/buttons/', dirname( __FILE__ ) ) ) . "';";
			echo 'var ecae_premium_enable = true;';
		} else {
			echo "var ecae_button_premium_dir_name = '" . esc_url( plugins_url( ECAE_DIR_NAME . '/assets/premium-promo/', dirname( __FILE__ ) ) ) . "';";
			echo 'var ecae_premium_enable = false;';
		}

		echo '</script>';

		// javascript.
		wp_enqueue_script( 'ace-js', plugin_dir_url( __FILE__ ) . 'assets/ace-min-noconflict-css-monokai/ace.js', array(), ECAE_VERSION );
		wp_enqueue_script( 'select2-js', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.js', array(), ECAE_VERSION );
		wp_enqueue_script( 'cloneya-js', plugin_dir_url( __FILE__ ) . 'assets/jquery-cloneya.min.js', array(), ECAE_VERSION );

		// css.
		wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.css', array(), ECAE_VERSION );

		// admin script and style.
		wp_enqueue_script( 'ecae-admin-js', plugin_dir_url( __FILE__ ) . 'assets/admin-script.js', array(), ECAE_VERSION );
		wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
		wp_enqueue_style( 'ecae-admin-css', plugin_dir_url( __FILE__ ) . 'assets/admin-style.css', array(), ECAE_VERSION );
		add_thickbox();
	}
}


add_action( 'wp_enqueue_scripts', 'ecae_wp_enqueue_scripts', 100 );
/**
 * Enqueue_scripts
 */
function ecae_wp_enqueue_scripts() {
	$options    = get_option( 'tonjoo_ecae_options' );
	$options    = tonjoo_ecae_load_default( $options );
	$inline_css = '';

	// frontend style.
	wp_enqueue_style( 'ecae-frontend', plugin_dir_url( __FILE__ ) . 'assets/style-frontend.css', array(), ECAE_VERSION );

	/**
	 * Font
	 */
	if ( '' !== $options['button_font'] ) {
		switch ( $options['button_font'] ) {
			case 'Open Sans':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext);'; // Open Sans.
				break;
			case 'Lobster':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Lobster);'; // Lobster.
				break;
			case 'Lobster Two':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Lobster+Two:400,400italic,700,700italic);'; // Lobster Two.
				break;
			case 'Ubuntu':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic);'; // Ubuntu.
				break;
			case 'Ubuntu Mono':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic);'; // Ubuntu Mono.
				break;
			case 'Titillium Web':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Titillium+Web:400,300,700,300italic,400italic,700italic);'; // Titillium Web.
				break;
			case 'Grand Hotel':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Grand+Hotel);'; // Grand Hotel.
				break;
			case 'Pacifico':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Pacifico);'; // Pacifico.
				break;
			case 'Crafty Girls':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Crafty+Girls);'; // Crafty Girls.
				break;
			case 'Bevan':
				$inline_css .= '@import url(' . ECAE_HTTP_PROTO . 'fonts.googleapis.com/css?family=Bevan);'; // Bevan.
				break;
			default:
				// do nothing.
		}

		$inline_css .= "span.ecae-button { font-family: '" . $options['button_font'] . "', Helvetica, Arial, sans-serif; }";
	}

	/**
	 * Others
	 */
	$trimmed_custom_css = str_replace( ' ', '', $options['custom_css'] );

	if ( '' !== $trimmed_custom_css ) {
		$inline_css .= $options['custom_css'];
	}

	if ( function_exists( 'is_ecae_premium_exist' ) && isset( $options['button_font_size'] ) ) {
		$inline_css .= '.ecae-button { font-size: ' . $options['button_font_size'] . 'px !important; }';
	}

	if ( 'yes' === $options['readmore_inline'] ) {
		$inline_css .= '.ecae-button {
			display: inline-block !important;
		}';
	}

	// Add inline css.
	wp_add_inline_style( 'ecae-frontend', $inline_css );

	/**
	 * Button skin
	 */
	$array_buttonskins = ecae_get_array_buttonskins();

	if ( ! isset( $options['button_skin'] ) || ! in_array( $options['button_skin'], $array_buttonskins ) ) {
		$options['button_skin'] = 'ecae-buttonskin-none';
	}

	/* filter if premium */
	$exp = explode( '-PREMIUM', $options['button_skin'] );
	if ( count( $exp ) > 1 and $exp[1] == 'true' ) {
		wp_enqueue_style( $exp[0], plugins_url( ECAE_PREMIUM_DIR_NAME . "/buttons/{$exp[0]}.css" ), array(), ECAE_VERSION );
	} else {
		wp_enqueue_style( $exp[0], plugins_url( ECAE_DIR_NAME . "/buttons/{$exp[0]}.css" ), array(), ECAE_VERSION );
	}
}

function ecae_get_array_buttonskins() {
	$skins       = scandir( dirname( __FILE__ ) . '/buttons' );
	$button_skin = array();

	foreach ( $skins as $key => $value ) {
		$extension = pathinfo( $value, PATHINFO_EXTENSION );
		$filename  = pathinfo( $value, PATHINFO_FILENAME );
		$extension = strtolower( $extension );
		$the_value = strtolower( $filename );

		if ( 'css' === $extension ) {
			array_push( $button_skin, $the_value );
		}
	}

	if ( function_exists( 'is_ecae_premium_exist' ) ) {
		$dir = dirname( plugin_dir_path( __FILE__ ) ) . '/' . ECAE_PREMIUM_DIR_NAME . '/buttons';

		$skins = scandir( $dir );

		foreach ( $skins as $key => $value ) {
			$extension = pathinfo( $value, PATHINFO_EXTENSION );
			$filename  = pathinfo( $value, PATHINFO_FILENAME );
			$extension = strtolower( $extension );
			$the_value = strtolower( $filename );

			if ( 'css' === $extension ) {
				array_push( $button_skin, $the_value . '-PREMIUMtrue' );
			}
		}
	}

	return $button_skin;
}

add_action( 'loop_end', 'tonjoo_ecae_loop_end' );
/**
 * Main Query Check
 */
function tonjoo_ecae_loop_end( $query ) {
	// modify post object here.
	global $is_main_query_ecae;

	$is_main_query_ecae = false;

	if ( $query->is_main_query() ) {
		$is_main_query_ecae = true;
	}
}

/**
 * Do Filter after this
 * add_filter('the_content', 'do_shortcode', 11); // AFTER wpautop()
 * So we can preserve shortcode
 */
add_filter( 'the_content', 'tonjoo_ecae_execute', 10 );
function tonjoo_ecae_execute( $content, $width = 400 ) {
	global $content_pure;
	global $post;

	// if password protected.
	if ( post_password_required( $post ) ) {
		return $content;
	}

	$options = get_option( 'tonjoo_ecae_options' );
	$options = tonjoo_ecae_load_default( $options );

	// if post type is FRS.
	if ( get_post_type() === 'pjc_slideshow' ) {
		return $content;

		exit;
	}

	// if RSS FEED.
	if ( is_feed() && 'yes' === $options['disable_on_feed'] ) {
		return $content;

		exit;
	}

	if ( isset( $options['special_method'] ) && 'yes' === $options['special_method'] ) {
		global $is_main_query_ecae;

		if ( ! $is_main_query_ecae ) {
			return $content;
		}
	}

	$content_pure = $content;

	$width   = $options['width'];
	$justify = $options['justify'];

	/**
	 * No limit number if 1st-paragraph mode
	 */
	if ( strpos( $options['excerpt_method'], '-paragraph' ) ) {
		$width = strlen( wp_kses( $content, array() ) ); // max integer in 32-bit system.
	}

	if ( 'basic' === $options['location_settings_type'] ) {
		if ( 'yes' === $options['home'] && is_home() ) {
			return tonjoo_ecae_excerpt( $content, $width, $justify );
		}

		if ( 'yes' === $options['front_page'] && is_front_page() ) {
			return tonjoo_ecae_excerpt( $content, $width, $justify );
		}

		if ( 'yes' === $options['search'] && is_search() ) {
			return tonjoo_ecae_excerpt( $content, $width, $justify );
		}

		if ( 'yes' === $options['archive'] && is_archive() ) {
			return tonjoo_ecae_excerpt( $content, $width, $justify );
		}

		/**
		 * Excerpt in pages
		 */
		$excerpt_in_page = $options['excerpt_in_page'];
		$excerpt_in_page = trim( $excerpt_in_page );

		if ( '' !== $excerpt_in_page ) {
			$excerpt_in_page = explode( '|', $excerpt_in_page );
		} else {
			$excerpt_in_page = array();
		}

		foreach ( $excerpt_in_page as $key => $value ) {
			if ( '' !== $value && is_page( $value ) ) {
				return tonjoo_ecae_excerpt( $content, $width, $justify );
				break;
			}
		}
	} else {
		$adv_exc_loc = new advExcLoc( $options, $content, $width, $justify );

		if ( is_home() ) {
			$options['excerpt_method'] = $adv_exc_loc->get_excerpt_method( 'advanced_home' );
			return $adv_exc_loc->do_excerpt( 'advanced_home', 'home_post_type', 'home_category' );
		}

		if ( is_front_page() ) {
			$options['excerpt_method'] = $adv_exc_loc->get_excerpt_method( 'advanced_frontpage' );
			return $adv_exc_loc->do_excerpt( 'advanced_frontpage', 'frontpage_post_type', 'frontpage_category' );
		}

		if ( is_archive() ) {
			$options['excerpt_method'] = $adv_exc_loc->get_excerpt_method( 'advanced_archive' );
			return $adv_exc_loc->do_excerpt( 'advanced_archive', 'archive_post_type', 'archive_category' );
		}

		if ( is_search() ) {
			$options['excerpt_method'] = $adv_exc_loc->get_excerpt_method( 'advanced_search' );
			return $adv_exc_loc->do_excerpt( 'advanced_search', 'search_post_type', 'search_category' );
		}

		// Page Excerpt.
		if ( 'disable' !== $options['advanced_page_main'] ) {
			$type                      = 'advanced_page_main';
			$excerpt_in_page           = $options['excerpt_in_page_advanced'];
			$options['excerpt_method'] = $adv_exc_loc->get_excerpt_method( $type );

			// excerpt size.
			if ( isset( $options[ $type . '_width' ] ) && $options[ $type . '_width' ] > 0 ) {
				$width = $options[ $type . '_width' ];
			}

			if ( is_array( $excerpt_in_page ) ) {
				foreach ( $excerpt_in_page as $key => $value ) {
					if ( '' !== $value && is_page( $value ) ) {
						return tonjoo_ecae_excerpt( $content, $width, $justify );
						break;
					}
				}
			}

			if ( 'selection' === $options['advanced_page_main'] ) {
				$advanced_page  = $options['advanced_page'];
				$page_post_type = $options['page_post_type'];
				$page_category  = $options['page_category'];

				if ( is_array( $advanced_page ) && count( $advanced_page ) > 0 ) {
					foreach ( $advanced_page as $key => $value ) {
						if ( '' !== $value && is_page( $value ) ) {
							$return['data']    = $content;
							$return['excerpt'] = false;

							if ( isset( $page_post_type[ $key ] ) ) {
								$return = $adv_exc_loc->excerpt_in_post_type( $page_post_type[ $key ], $width );
							}

							if ( ! $return['excerpt'] ) {
								if ( isset( $page_category[ $key ] ) ) {
									$return = $adv_exc_loc->excerpt_in_category( $page_category[ $key ], $width );
								}

								return $return['data'];
							} else {
								return $return['data'];
							}
						}
					}
				}
			}
			// end advanced_page_main.
		}
		// end location_settings_type.
	}

	return $content;
}

function tonjoo_ecae_get_img_added_css( $options ) {
	/**
	 * Image position
	 */
	switch ( $options['image_position'] ) {
		case 'right':
			$img_added_css = '';
			break;

		case 'left':
			$img_added_css = '';
			break;

		case 'center':
			$img_added_css = 'margin-left:auto !important; margin-right:auto !important;';
			break;

		case 'float-left':
			$img_added_css = 'float:left;';
			break;

		case 'float-right':
			$img_added_css = 'float:right;';
			break;

		default:
			$img_added_css = 'text-align:left;';
			break;
	}

	$image_width = absint( $options['image_width'] );
	if ( 'manual' === $options['image_width_type'] ) {
		$img_added_css .= "width:{$image_width}px;";
	}

	$image_padding_top    = absint( $options['image_padding_top'] );
	$image_padding_right  = absint( $options['image_padding_right'] );
	$image_padding_bottom = absint( $options['image_padding_bottom'] );
	$image_padding_left   = absint( $options['image_padding_left'] );

	$img_added_css .= "padding:{$image_padding_top}px {$image_padding_right}px {$image_padding_bottom}px {$image_padding_left}px;";

	return $img_added_css;
}

function tonjoo_ecae_excerpt( $content, $width, $justify ) {
	global $post;

	$options         = get_option( 'tonjoo_ecae_options' );
	$options         = tonjoo_ecae_load_default( $options );
	$postmeta        = get_post_meta( $post->ID, 'ecae_meta', true );
	$disable_excerpt = isset( $postmeta['disable_excerpt'] ) && 'yes' === $postmeta['disable_excerpt'];
	$width           = (int) $width;

	if ( function_exists( 'is_ecae_premium_exist' ) && $disable_excerpt ) {
		return $content;
		exit;
	}

	$total_width        = 0;
	$pos                = strpos( $content, '<!--more-->' );
	$array_replace_list = array();

	// if read more.
	if ( $pos ) {
		// check shortcode optons.
		if ( 'yes' === $options['strip_shortcode'] ) {
			$content = strip_shortcodes( $content );
		}

		$content = substr( $content, 0, $pos );
	} // If excerpt column is not empty
	elseif ( '' !== $post->post_excerpt ) {
		$text    = $post->post_excerpt;
		$content = '<p>' . implode( "</p>\n\n<p>", preg_split( '/\n(?:\s*\n)+/', $text ) ) . '</p>';

		// if featured image.
		if ( 'featured-image' === $options['show_image'] ) {
			// enable thumbnail for ecae.
			add_filter( 'ecae-thumbnail-mode', 'ecae_enable_thumbnail' );

			// check featured image.
			$featured_image = has_post_thumbnail( get_the_ID() );
			$image          = false;

			if ( $featured_image ) {
				$image = get_the_post_thumbnail( get_the_ID(), $options['image_thumbnail_size'] );
			}

			// disable thumbnail for non ecae.
			remove_filter( 'ecae-thumbnail-mode', 'ecae_enable_thumbnail' );

			$img_added_css = tonjoo_ecae_get_img_added_css( $options );

			// only put image if there is image :p.
			if ( $image ) {
				if ( 'left' === $options['image_position'] ) {
					$content = '<div class="ecae-image ecae-table-left"><div class="ecae-table-cell" style="' . esc_attr( $img_added_css ) . '">' . $image . '</div><div class="ecae-table-cell">' . $content . '</div>';
				} elseif ( 'right' === $options['image_position'] ) {
					$content = '<div class="ecae-image ecae-table-right"><div class="ecae-table-cell" style="' . esc_attr( $img_added_css ) . '">' . $image . '</div><div class="ecae-table-cell">' . $content . '</div>';
				} else {
					$content = '<div class="ecae-image" style="' . esc_attr( $img_added_css ) . '">' . $image . '</div>' . $content;
				}
			}
		}
	} elseif ( 0 === $width ) {
		$content = '';
	} else {
		// Do caption shortcode.
		$content = ecae_convert_caption( $content, $options );

		$caption_image_replace   = new eace_content_regex( '|$', '/<div[^>]*class="[^"]*wp-caption[^"]*".*>(.*)<img[^>]+\>(.*)<\/div>/', $options, true );
		$figure_replace          = new eace_content_regex( '|:', '/<figure.*?\>([^`]*?)<\/figure>/', $options, true );
		$hyperlink_image_replace = new eace_content_regex( '|#', "/<a[^>]*>(\n|\s)*(<img[^>]+>)(\n|\s)*<\/a>/", $options, true );
		$image_replace           = new eace_content_regex( '|(', '/<img[^>]+\>/', $options, true );

		// biggest -> lowest the change code.
		$html_replace = array();
		$extra_markup = $options['extra_html_markup'];
		$extra_markup = trim( $extra_markup );

		// prevent white space explode.
		if ( '' !== $extra_markup ) {
			$extra_markup = explode( '|', $extra_markup );
		} else {
			$extra_markup = array();
		}

		$extra_markup_tag = array( '*=', '(=', ')=', '_=', '<=', '>=', '/=', '\=', ']=', '[=', '{=', '}=', '|=' );

		// default order
		$array_replace_list['pre']        = '=@'; // syntax highlighter like crayon.
		$array_replace_list['video']      = '=}';
		$array_replace_list['table']      = '={';
		$array_replace_list['p']          = '=!';
		$array_replace_list['b']          = '=&';
		$array_replace_list['a']          = '=*';
		$array_replace_list['i']          = '=)';
		$array_replace_list['h1']         = '=-';
		$array_replace_list['h2']         = '`=';
		$array_replace_list['h3']         = '!=';
		$array_replace_list['h4']         = '#=';
		$array_replace_list['h5']         = '$=';
		$array_replace_list['h6']         = '%=';
		$array_replace_list['ul']         = '=#';
		$array_replace_list['ol']         = '=$';
		$array_replace_list['strong']     = '=(';
		$array_replace_list['blockquote'] = '=^';

		foreach ( $extra_markup as $markup ) {
			$counter = 0;

			if ( ! isset( $array_replace_list[ $markup ] ) ) {
				$array_replace_list[ $markup ] = $extra_markup_tag[ $counter ];
			}

			$counter++;
		}

		// push every markup into processor.
		foreach ( $array_replace_list as $key => $value ) {
			// use image processing algorithm for table and video.
			if ( 'video' === $key || 'table' === $key ) {
				$push = new eace_content_regex( "{$value}", "/<{$key}.*?\>([^`]*?)<\/{$key}>/", $options, true );
			} else {
				$push = new eace_content_regex( "{$value}", "/<{$key}.*?\>([^`]*?)<\/{$key}>/", $options );
			}

			array_push( $html_replace, $push );
		}

		$pattern = get_shortcode_regex();

		if ( ! strpos( 'hana-flv-player', $pattern ) ) {
			$pattern = str_replace( 'embed', 'caption|hana-flv-player', $pattern );
		}

		$shortcode_replace = new eace_content_regex( '+*', '/' . $pattern . '/s', $options );

		// trim image.
		$option_image = $options['show_image'];

		if ( 'yes' === $option_image || 'first-image' === $option_image ) {
			$number = false;
			// limit the image excerpt.
			if ( 'first-image' === $option_image ) {
				$number = 1;
			}

			$caption_image_replace->replace( $content, $width, $number );
			$figure_replace->replace( $content, $width, $number );
			$hyperlink_image_replace->replace( $content, $width, $number );
			$image_replace->replace( $content, $width, $number );
		} else {
			// remove image , this is also done for featured-image option.
			$caption_image_replace->remove( $content );
			$figure_replace->remove( $content );
			$hyperlink_image_replace->remove( $content );
			$image_replace->remove( $content );
		}

		// check shortcode optons.
		if ( 'yes' === $options['strip_shortcode'] ) {
			$content = strip_shortcodes( $content );
		}

		// Replace remaining tag.
		foreach ( $html_replace as $replace ) {
			$replace->replace( $content, $width, false, $total_width );
		}

		$shortcode_replace->replace( $content, $width, false, $total_width );

		// use wp kses to fix broken element problem.
		$content = wp_kses( $content, array() );

		if ( strpos( $content, '<!--STOP THE EXCERPT HERE-->' ) === false ) {
			// give the stop mark so the plugin can stop.
			$content = $content . '<!--STOP THE EXCERPT HERE-->';
		}

		// strip the text.
		$content = substr( $content, 0, strpos( $content, '<!--STOP THE EXCERPT HERE-->' ) );

		// do the restore 3 times, avoid nesting.
		$shortcode_replace->restore( $content );

		foreach ( $html_replace as $restore ) {
			$restore->restore( $content, $width );
		}
		foreach ( $html_replace as $restore ) {
			$restore->restore( $content, $width );
		}
		foreach ( $html_replace as $restore ) {
			$restore->restore( $content, $width );
		}

		$shortcode_replace->restore( $content );

		$img_added_css = tonjoo_ecae_get_img_added_css( $options );

		if ( 'yes' === $option_image ) {
			$caption_image_replace->restore( $content, false, true );
			$figure_replace->restore( $content, false, true );
			$hyperlink_image_replace->restore( $content, false, true );
			$image_replace->restore( $content, false, true );
		} elseif ( 'first-image' === $option_image ) {
			// catch all of hyperlink and image on the content => '|#'  and '|(' and '|$'.
			preg_match_all( '/\|\([0-9]*\|\(|\|\#[0-9]*\|\#|\|\$[0-9]*\|\$|\|\:[0-9]*\|\:/', $content, $result, PREG_PATTERN_ORDER );

			if ( isset( $result[0] ) ) {
				$remaining = array_slice( $result[0], 0, 1 );

				if ( isset( $remaining[0] ) ) {
					// delete remaining image.
					$content = preg_replace( '/\|\:[0-9]*\|\:/', '', $content );
					$content = preg_replace( '/\|\([0-9]*\|\C/', '', $content );
					$content = preg_replace( '/\|\#[0-9]*\|\#/', '', $content );
					$content = preg_replace( '/\|\$[0-9]*\|\$/', '', $content );

					if ( 'yes' === $options['clickable_image'] ) {
						$remaining[0] = '<a href="' . get_permalink( $post ) .  '">' . $remaining[0] . '</a>';
					}

					if ( 'left' === $options['image_position'] ) {
						$content = '<div class="ecae-image ecae-table-left"><div class="ecae-table-cell" style="' . esc_attr( $img_added_css ) . '">' . $remaining[0] . '</div>' . "<div class='ecae-table-cell'>" . $content . '</div>';
					} elseif ( 'right' === $options['image_position'] ) {
						$content = '<div class="ecae-image ecae-table-right"><div class="ecae-table-cell" style="' . esc_attr( $img_added_css ) . '">' . $remaining[0] . '</div>' . "<div class='ecae-table-cell'>" . $content . '</div>';
					} else {
						$content = '<div class="ecae-image" style="' . esc_attr( $img_added_css ) . '">' . $remaining[0] . '</div>' . $content;
					}

					$caption_image_replace->restore( $content, 1, true );
					$figure_replace->restore( $content, 1, true );
					$hyperlink_image_replace->restore( $content, 1, true );
					$image_replace->restore( $content, 1, true );
				}
			}
		} elseif ( 'featured-image' === $option_image ) {
			// enable thumbnail for ecae.
			add_filter( 'ecae-thumbnail-mode', 'ecae_enable_thumbnail' );

			// check featured image.
			$featured_image = has_post_thumbnail( get_the_ID() );
			$image          = false;

			if ( $featured_image ) {
				$image = get_the_post_thumbnail( get_the_ID(), $options['image_thumbnail_size'] );
			}

			// disable thumbnail for non ecae.
			remove_filter( 'ecae-thumbnail-mode', 'ecae_enable_thumbnail' );

			// only put image if there is image :p.
			if ( $image ) {

				if ( 'yes' === $options['clickable_image'] ) {
					$image = '<a href="' . get_permalink( $post ) .  '">' . $image . '</a>';
				}
					
				if ( 'left' === $options['image_position'] ) {
					$content = '<div class="ecae-image ecae-table-left"><div class="ecae-table-cell" style="' . esc_attr( $img_added_css ) . '">' . $image . '</div><div class="ecae-table-cell">' . $content . '</div>';
				} elseif ( 'right' === $options['image_position'] ) {
					$content = '<div class="ecae-image ecae-table-right"><div class="ecae-table-cell" style="' . esc_attr( $img_added_css ) . '">' . $image . '</div><div class="ecae-table-cell">' . $content . '</div>';
				} else {
					$content = '<div class="ecae-image" style="' . esc_attr( $img_added_css ) . '">' . $image . '</div>' . $content;
				}
			}
		}

		// remove empty html tags.
		if ( 'yes' === $options['strip_empty_tags'] ) {
			$content = strip_empty_tags( $content );
		}

		// delete remaining image.
		$content = preg_replace( '/\|\([0-9]*\|\C/', '', $content );
		$content = preg_replace( '/\|\#[0-9]*\|\#/', '', $content );

		// delete remaining.
		$extra_markup_tag = array( '*=' . '(=', ')=', '_=', '<=', '>=', '/=', '\=', ']=', '[=', '{=', '}=', '|=' );

		foreach ( $extra_markup_tag as $value ) {
			$char = str_split( $value );

			$content = preg_replace( '/' . '\\' . "{$char[0]}" . '\\' . "{$char[1]}" . '[0-9]*' . '\\' . "{$char[0]}" . '\\' . "{$char[1]}" . '/', '', $content );
		}

		foreach ( $array_replace_list as $key => $value ) {
			$char = str_split( $value );

			$content = preg_replace( '/' . '\\' . "{$char[0]}" . '\\' . "{$char[1]}" . '[0-9]*' . '\\' . "{$char[0]}" . '\\' . "{$char[1]}" . '/', '', $content );
		}
	}

	/**
	 * Readmore text
	 */
	if ( isset( $post ) && isset( $post->ID ) ) {
		$link = get_permalink( $post->ID );
	} else {
		$link = get_permalink();
	}

	$readmore    = '';
	$is_readmore = false;

	// remove last div is image position left / right.
	if ( 'left' === $options['image_position'] || 'right' === $options['image_position'] && strpos( $content, 'ecae-table-cell' ) ) {
		if ( strpos( $content, 'ecae-table-cell' ) ) {
			$content = substr( $content, 0, -6 );
		}
	}

	// readmore.
	if ( trim( $options['read_more'] ) !== '-' ) {
		// failsafe.
		$options['read_more_text_before'] = isset( $options['read_more_text_before'] ) ? $options['read_more_text_before'] : '...';

		$button_skin          = explode( '-PREMIUM', $options['button_skin'] );
		$trim_readmore_before = trim( $options['read_more_text_before'] );

		$read_more_text_before = empty( $trim_readmore_before ) ? $options['read_more_text_before'] : $options['read_more_text_before'] . '&nbsp;&nbsp;';

		// localization with WPML or not.
		if ( function_exists( 'icl_t' ) ) {
			$local_button_text        = icl_t( 'easy-custom-auto-excerpt', 'Readmore text', $options['read_more'] );
			$local_before_button_text = icl_t( 'easy-custom-auto-excerpt', 'Before readmore link', $read_more_text_before );
		} else {
			$local_button_text        = __( $options['read_more'], 'easy-custom-auto-excerpt' );
			$local_before_button_text = __( $read_more_text_before, 'easy-custom-auto-excerpt' );
		}

		$readmore_link = ' <a class="ecae-link" href="' . esc_url( $link ) . '"><span>' . $local_button_text . '</span></a>';
		$readmore      = '<span class="ecae-button ' . esc_attr( $button_skin[0] ) . '" style="text-align:' . esc_attr( $options['read_more_align'] ) . '" >' . $local_before_button_text . ' ' . $readmore_link . '</span>';

		if ( ! empty( $options['custom_html'] ) ) {
			$readmore = str_replace( '{link}', $link, wp_kses_post( wp_unslash( $options['custom_html'] ) ) );
		}

		// button_display_option.
		if ( ! strpos( $options['excerpt_method'], '-paragraph' ) ) {
			if ( strpos( $content, '<!-- READ MORE TEXT -->' ) ) {
				$is_readmore = true;
			}

			if ( 'always_show' === $options['button_display_option'] ) {
				$content = str_replace( '<!-- READ MORE TEXT -->', '', $content );
				$content = $content . $readmore;
			} elseif ( 'always_hide' === $options['button_display_option'] ) {
				$content = str_replace( '<!-- READ MORE TEXT -->', '', $content );

				$is_readmore = false;
			} else {
				$content = str_replace( '<!-- READ MORE TEXT -->', $readmore, $content );
			}
		}
	}

	// show dots.
	if ( '' !== $options['text_after_content'] ) {
		$content = str_replace( '<!-- DOTS -->', '&nbsp;<span class="ecae-dots">' . esc_html( $options['text_after_content'] ) . '</span>&nbsp;', $content );
	} else {
		$content = str_replace( '<!-- DOTS -->', '', $content );
	}

	/**
	 * Filter if 1st-paragraph mode
	 */
	if ( strpos( $options['excerpt_method'], '-paragraph' ) ) {
		$num_paragraph = substr( $options['excerpt_method'], 0, 1 );
		$content       = ecae_get_per_paragraph( intval( $num_paragraph ), $content, $options );

		global $content_pure;

		$len_content      = strlen( wp_kses( $content, array() ) ) + 1;  // 1 is a difference between them
		$len_content_pure = strlen( wp_kses( $content_pure, array() ) );

		// button_display_option.
		if ( 'always_show' === $options['button_display_option'] ) {
			$content     = str_replace( '<!-- READ MORE TEXT -->', $readmore, $content );
			$is_readmore = true;
		} elseif ( 'always_hide' === $options['button_display_option'] ) {
			$content = str_replace( '<!-- READ MORE TEXT -->', '', $content );
		} else {
			if ( $len_content < $len_content_pure ) {
				$content     = str_replace( '<!-- READ MORE TEXT -->', $readmore, $content );
				$is_readmore = true;
			} else {
				$content = str_replace( '<!-- READ MORE TEXT -->', '', $content );
			}
		}
	}

	// wrap with a container.
	$custom_attrs = array();
	if ( 'no' !== $justify ) {
		$custom_attrs[] = 'text-align: ' . $justify;
	}
	if ( 0 < floatval( $options['font_size'] ) ) {
		$custom_attrs[] = 'font-size: ' . floatval( $options['font_size'] ) . 'px';	
	}
	$content = '<div class="ecae" style="' . esc_attr( implode( ";", $custom_attrs ) ) . '">' . $content . '</div>';

	// add last div is image position left / right.
	if ( 'left' === $options['image_position'] || 'right' === $options['image_position'] ) {
		if ( strpos( $content, 'ecae-table-cell' ) ) {
			$content .= '</div>';
		}
	}

	// remove empty html tags.
	if ( 'yes' === $options['strip_empty_tags'] ) {
		$content = strip_empty_tags( $content );
	}

	return "<!-- Begin :: Generated by Easy Custom Auto Excerpt -->$content<!-- End :: Generated by Easy Custom Auto Excerpt -->";
}

function ecae_enable_thumbnail( $is_enable ) {
	return true;
}

if ( ! function_exists( 'ecae_get_per_paragraph' ) ) :
	/**
	 * Show 1st - 3rd paragraph
	 */
	function ecae_get_per_paragraph( $num, $content, $options ) {
		$arr_content = explode( '</p>', $content );
		$ret_content = '';
		$elapsed_num = 0;

		$count_arr_content = count( $arr_content );
		for ( $i = 0; $i < $count_arr_content; $i++ ) {
			if ( trim( $arr_content[ $i ] ) === '' ) {
				break;
			}

			$elapsed_num++;

			$ret_content .= $arr_content[ $i ];

			if ( $elapsed_num >= $num ) {
				// show dots.
				if ( '' !== $options['text_after_content'] ) {
					$ret_content .= "&nbsp;<span class='ecae-dots'>{$options['text_after_content']}</span>&nbsp;";
				}

				$ret_content .= '<!-- READ MORE TEXT --></p>';

				break;
			}

			$ret_content .= '</p>';
		}

		return $ret_content;
	}
endif;

/**
 * Redirect to about page after plugin activated
 *
 * @param string $plugin Plugin name.
 */
function ecae_activation_redirect( $plugin ) {
	if ( 'easy-custom-auto-excerpt/easy-custom-auto-excerpt.php' === $plugin ) {
		wp_safe_redirect( admin_url( 'admin.php?page=ecae_about_page' ) );
		exit;
	}
	if ( 'easy-custom-auto-excerpt-premium/easy-custom-auto-excerpt-premium.php' === $plugin ) {
		wp_safe_redirect( admin_url( 'admin.php?page=ecae_about_page' ) );
		exit;
	}
}
add_action( 'activated_plugin', 'ecae_activation_redirect' );
