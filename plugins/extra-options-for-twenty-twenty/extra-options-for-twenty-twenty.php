<?php
/**
 * Plugin Name: Extra Options For The Twenty Twenty Theme
 * Plugin URI: 
 * Description: This plugin lets you set a custom logo for Twenty Twenty WordPress theme's cover template, change the footer credits lines (copyright and powered by WordPress) & use a transparent header with a hero block. More options to come.
 * Author: Acosmin
 * Author URI: 
 * Version: 1.0.0
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * @todo Maybe refactor the code when you have the time :) 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup the admin page
 *
 * @return void
 */
function tteo2020_plugin_setup_menu(){
	add_theme_page(
		__( '2020 Extra Options', 'extra-options-for-twenty-twenty' ),
		__( '2020 Extras', 'extra-options-for-twenty-twenty' ),
		'manage_options',
		'tteo2020-extra-options',
		'tteo2020_plugin_page'
	);
}
add_action( 'admin_menu', 'tteo2020_plugin_setup_menu' );

/**
 * Admin Page Contents
 *
 * @return void
 */
function tteo2020_plugin_page() {
	echo '<div class="wrap">';

	printf( '<h1>%s</h1>', esc_html__( '2020 Extra Options', 'extra-options-for-twenty-twenty' ) );

	// Cover Template Logo
	printf( '<h2>%s</h2>', esc_html__( 'Logo to be used when the Cover Template is selected.', 'extra-options-for-twenty-twenty' ) );
	printf( '<p>%s</p>', __( 'You can use this option to select a custom logo, with a transparent background (.png), that will be used only when the cover page template is detected. This helps because your custom logo might not be perceivable if the cover overlay is of the same color.', 'extra-options-for-twenty-twenty' ) );
	printf( '<p>%s</p>', __( 'This option can be found in: <code>Customizer > Site Identity > Cover Template Logo</code>.', 'extra-options-for-twenty-twenty' ) );
	printf(
		'<a href="%1$s"class="button button-primary">%2$s</a>',
		esc_url( admin_url( 'customize.php?autofocus[control]=cover_tmpl_logo' ) ),
		esc_html__( 'Customize', 'extra-options-for-twenty-twenty' )
	);

	echo '<br /><br />';

	// Transparent header
	printf( '<h2>%s</h2>', esc_html__( 'Transparent header.', 'extra-options-for-twenty-twenty' ) );
	printf( '<p>%s</p>', __( 'Use the transparent header used by the Cover Page template whitout actually selecting that template. This is great if you want to use a custom Hero section (cover block for example). It will also disable the title <code>h1</code> tag (for SEO reasons), so make sure you add a <code>h1</code> tag on the page (maybe via cover block). The logo can be set using the previous option.', 'extra-options-for-twenty-twenty' ) );
	printf( '<p>%s</p>', __( 'This option can be enabled on a per post/page basis. When you edit/create a new page, open the plugin sidebar (Twenty Twenty Extras) and toggle <code>Transparent header</code>.', 'extra-options-for-twenty-twenty' ) );

	echo '<br /><br />';

	// Copyright & Powered By
	printf( '<h2>%s</h2>', esc_html__( 'Change the "copyright" and "powered by" lines.', 'extra-options-for-twenty-twenty' ) );
	printf( '<p>%s</p>', __( 'These options will allow you to easily change the "copyright" and "powered by" lines located in the footer. You can use text, plain html, emojis or some of the custom placeholders.', 'extra-options-for-twenty-twenty' ) );
	printf( '<p>%s</p>', __( 'These options can be found in: <code>Customizer > Footer</code>.', 'extra-options-for-twenty-twenty' ) );
	printf(
		'<a href="%1$s"class="button button-primary">%2$s</a>',
		esc_url( admin_url( 'customize.php?autofocus[section]=footer_options' ) ),
		esc_html__( 'Customize', 'extra-options-for-twenty-twenty' )
	);
	echo '</div>';
}

/**
 * Redirect if the plugin is activated.
 *
 * @since 1.0.0
 * @param string $plugin
 * @return void
 */
function tteo2020_redirect_on_activation( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
		$url = esc_url( admin_url( 'themes.php?page=tteo2020-extra-options' ) );
        exit( wp_redirect( $url ) );
    }
}
add_action( 'activated_plugin', 'tteo2020_redirect_on_activation' );

/**
 * Plugin loaded hook 
 *
 * @return void
 */
function tteo2020_on_plugins_loaded() {
	load_plugin_textdomain( 'extra-options-for-twenty-twenty' );
}
add_action( 'plugins_loaded', 'tteo2020_on_plugins_loaded' );

/**
 * Check if the transparent header should be displayed.
 *
 * @since 1.0.0
 * @return boolean
 */
function tteo2020_transparent_check() {
	global $post;
	return ( is_singular() && is_object( $post ) && ! is_page_template( 'templates/template-cover.php' ) );
}

function tteo2020_transparent_meta() {
	global $post;

	if( tteo2020_transparent_check() ) {
		$meta = get_post_meta( $post->ID, 'tteo2020_transparent_header', true );

		if( $meta && '' !== $meta ) {
			return true;
		}
	}

	return false;
}

/**
 * Changes the `custom_logo` theme mod to use a different logo if we're using
 * the Cover Template.
 *
 * @since 1.0.0
 * @param int $value Image ID.
 * @return void
 */
function tteo2020_cover_tmpl_logo_mod( $value ) {
	if ( 
		is_page_template( 'templates/template-cover.php' ) || 
		( tteo2020_transparent_check() && tteo2020_transparent_meta() )
	) {
		$cover_logo_id = get_theme_mod( 'cover_tmpl_logo' );

		return $cover_logo_id ? $cover_logo_id : $value;
	}

	return $value;
}
add_filter( 'theme_mod_custom_logo', 'tteo2020_cover_tmpl_logo_mod', 15 );

/**
 * Customizer register,
 *
 * @todo Refactor some of the code.
 * 
 * @since 1.0.0
 * @param object $manager `WP_Customize_Manager`.
 * @return void
 */
function tteo2020_customizer( $manager ) {
	
	/////////////////////////
	// Cover template logo //
	/////////////////////////
	if ( ! class_exists( 'WP_Customize_Cropped_Image_Control' ) ) {
		require_once( ABSPATH . WPINC . '/customize/class-wp-customize-cropped-image-control.php' );

		add_action( 'wp_loaded', function() use ( $manager ) {
			$manager->register_control_type( 'WP_Customize_Cropped_Image_Control' );
		} );
	}

	$custom_logo_args = get_theme_support( 'custom-logo' );

	$manager->add_setting(
		'cover_tmpl_logo',
		array(
			'transport' => 'postMessage',
		)
	);

	$manager->add_control(
		new WP_Customize_Cropped_Image_Control(
			$manager,
			'cover_tmpl_logo',
			array(
				'label'         => __( 'Cover Template Logo', 'extra-options-for-twenty-twenty' ),
				'section'       => 'title_tagline',
				'priority'      => 9,
				'height'        => $custom_logo_args[0]['height'],
				'width'         => $custom_logo_args[0]['width'],
				'flex_height'   => $custom_logo_args[0]['flex-height'],
				'flex_width'    => $custom_logo_args[0]['flex-width'],
				'button_labels' => array(
					'select'       => __( 'Select cover logo', 'extra-options-for-twenty-twenty' ),
					'change'       => __( 'Change logo', 'extra-options-for-twenty-twenty' ),
					'remove'       => __( 'Remove', 'extra-options-for-twenty-twenty' ),
					'default'      => __( 'Default', 'extra-options-for-twenty-twenty' ),
					'placeholder'  => __( 'No logo selected', 'extra-options-for-twenty-twenty' ),
					'frame_title'  => __( 'Select logo', 'extra-options-for-twenty-twenty' ),
					'frame_button' => __( 'Choose logo', 'extra-options-for-twenty-twenty' ),
				),
			)
		)
	);

	if ( function_exists( 'twentytwenty_customize_partial_site_logo' ) ) {
		$manager->selective_refresh->add_partial(
			'cover_tmpl_logo',
			array(
				'settings'            => array( 'custom_logo', 'cover_tmpl_logo' ),
				'selector'            => '.header-titles [class*=site-]:not(.site-description)',
				'render_callback'     => 'twentytwenty_customize_partial_site_logo',
				'container_inclusive' => true,
			)
		);
	}

	////////////////////
	// Footer credits //
	////////////////////
	$manager->add_section(
		'footer_options',
		array(
			'title' => __( 'Footer', 'extra-options-for-twenty-twenty' ),
		)
	);

		if ( class_exists( 'WP_Customize_Control' ) ) {
			class TTEO2020_Info extends WP_Customize_Control {
				public $type = 'info_2020';
				public function render_content() {
				?>
					<div class="twentytwenty-extras-info">
						<?php if( !empty( $this->label ) ) { ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php } ?>
						<?php if( !empty( $this->description ) ) { ?>
							<span class="customize-control-description"><?php echo $this->description; ?></span>
						<?php } ?>
					</div>
				<?php
				}
			}

			add_action( 'wp_loaded', function() use ( $manager ) {
				$manager->register_control_type( 'TTEO2020_Info' );
			} );

			$manager->add_setting( 'footer_options_info', array() );

			$manager->add_control(
				new TTEO2020_Info(
					$manager,
					'footer_options_info',
					array(
						'label'           => __( 'You can use the following HTML tags:', 'extra-options-for-twenty-twenty' ),
						'description'     => __( '<code>a</code>, <code>strong</code>, <code>br</code>, <code>em</code>, <code>del</code>, <code>ins</code>. <br /><br />You can also use the following placeholders: <br /><code>%copy%</code> - copyright sign;<br /><code>%year%</code> - current year;<br /><code>%site_link%</code> - website link;<br /><code>%site_url%</code> - website url;<br /><code>%site_name%</code> - website name;', 'extra-options-for-twenty-twenty' ),
						'section'         => 'footer_options',
						'active_callback' => function() {
							return tteo2020_enabled_footer_option();
						},
					)
				)
			);
		}

		$footer_html_mods_get = tteo2020_mods( 'footer_html' );

		$footer_html_mods = array(
			'footer_credits' => array(
				'label'    => __( 'Copyright', 'extra-options-for-twenty-twenty' ),
				'priority' => 10,
				'selector' => '.footer-credits > p.' . $footer_html_mods_get[ 'footer_credits' ],

			),
			'footer_powered' => array(
				'label'    => __( 'Powered By', 'extra-options-for-twenty-twenty' ),
				'priority' => 20,
				'selector' => '.footer-credits > p.' . $footer_html_mods_get[ 'footer_powered' ],

			),
		);

		foreach ( $footer_html_mods as $footer_html_mod => $footer_html_mod_args ) {
			$manager->add_setting(
				$footer_html_mod,
				array(
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => 'postMessage',
				)
			);
	
			$manager->add_control(
				$footer_html_mod,
				array(
					'label'    => $footer_html_mod_args[ 'label' ],
					'section'  => 'footer_options',
					'priority' => $footer_html_mod_args[ 'priority' ],
					'type'     => 'textarea',
					'active_callback' => function() {
						return tteo2020_enabled_footer_option();
					},
				)
			);
	
			$manager->selective_refresh->add_partial(
				$footer_html_mod,
				array(
					'selector'        => $footer_html_mod_args[ 'selector' ],
					'render_callback' => function() use ( $footer_html_mod ) {
						return tteo2020_make_credits_great_again( array( 'mod' => $footer_html_mod ) );
					},
				)
			);
		}

		$manager->add_setting(
			'enable_footer_creds_option',
			array(
				'default' => false,
				'sanitize_callback' => function( $input ) {
					return ( ( isset( $input ) && true == $input ) ? true : false );
				}
			)
		);

		$manager->add_control(
			'enable_footer_creds_option',
			array(
				'label'       => __( 'Enable footer credits options', 'extra-options-for-twenty-twenty' ),
				'description' => __( 'If you want to edit the copyright and powered by lines, enable this option. Also, if the footer options do not work or they are causing issues for you, you can uncheck this checkbox.', 'extra-options-for-twenty-twenty' ),
				'section'     => 'footer_options',
				'priority'    => 30,
				'type'        => 'checkbox',
			)
		);

}
add_action( 'customize_register', 'tteo2020_customizer', 15 );

/**
 * Checks if the footer credits options should be enabled.
 *
 * @since 1.0.0
 * @return boolean
 */
function tteo2020_enabled_footer_option() {
	$filtered = apply_filters( 'tteo2020_enabled_footer_option', false );
	$mod = get_theme_mod( 'enable_footer_creds_option', false );

	return $filtered || $mod;
}

/**
 * Replaces placeholders with actual content. Used for footer credits.
 *
 * @since 1.0.0
 * @param string $content Saved content in theme mods.
 * @return string Content with replaced placeholders.
 */
function tteo2020_change_footer_placeholders( $content ) {
	$placeholders = array(
		'copy'      => '&copy;',
		'year'      => date_i18n(
			/* translators: Copyright date format, see https://secure.php.net/date */
			_x( 'Y', 'copyright date format', 'extra-options-for-twenty-twenty' )
		),
		'site_link' => sprintf( '<a href="%1$s">%2$s</a>', esc_url( home_url( '/' ) ), get_bloginfo( 'name' ) ),
		'site_url'  => esc_url( home_url( '/' ) ),
		'site_name' => get_bloginfo( 'name' ),
	);

	return str_replace(
		array_map( function( $el ) {
			return '%' . $el . '%';
		}, array_keys( $placeholders ) ),
		array_values( $placeholders ),
		$content
	);
}

/**
 * Used to sanitize & escape the content used for credits.
 *
 * @todo Document this.
 * @todo Maybe rename it :).
 *
 * @since 1.0.0
 * @param array $args
 * @return string Sanitized or escaped content.
 */
function tteo2020_make_credits_great_again( $args = array() ) {
	$defaults = array(
		'mod' => '',
		'not' => false,
		'phs' => true,
	);

	$args = wp_parse_args( $args, $defaults );

	$allowed = array(
		'a'      => array(
			'href'  => array(),
			'title' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'del'    => array(),
		'ins'    => array(),
	);

	$content = $args[ 'not' ] ? $args[ 'mod' ] : get_theme_mod( $args[ 'mod' ] );

	if ( $args[ 'phs' ] ) {
		$content = tteo2020_change_footer_placeholders( $content );
	}

	return wp_kses( $content, $allowed );
}

/**
 * Live preview of our Customizer modifications.
 *
 * @since 1.0.0
 * @return void
 */
function tteo2020_customize_preview() {
	wp_enqueue_script(
		'2020-customize-preview', 
		plugins_url( '/js/customize-preview.js', __FILE__ ),
		array( 
			'customize-preview',
			'customize-selective-refresh',
			'jquery' 
		), 
		'1.0', 
		true 
	);

	wp_add_inline_script(
		'2020-customize-preview',
		sprintf(
			'_2020FooterExtras = {
				credits: %1$s,
				powered: %2$s,
				enabled: %3$s
			};',
			wp_json_encode( tteo2020_make_credits_great_again( array( 'mod' => 'footer_credits' ) ) ),
			wp_json_encode( tteo2020_make_credits_great_again( array( 'mod' => 'footer_powered' ) ) ),
			wp_json_encode( tteo2020_enabled_footer_option() )
		),
		'before'
	);
}
add_action( 'customize_preview_init', 'tteo2020_customize_preview' );

/**
 * Register editor css and js assets + meta fields.
 *
 * @since 1.0.0
 * @return void
 */
function tteo2020_register_editor_stuff() {
    wp_register_script(
        '2020-extras-editor-js',
        plugins_url( '/js/editor.js', __FILE__ ),
        array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-compose', 'wp-i18n' )
	);

	wp_register_style(
        '2020-extras-editor',
        plugins_url( '/css/editor.css', __FILE__ )
    );
	
	register_post_meta( '', 'tteo2020_transparent_header', array(
		'show_in_rest' => array(
			'schema' => array(
				'type'    => 'boolean',
				'default' => false,
			),
		),
		'single' => true,
		'type' => 'boolean',
	) );
}
add_action( 'init', 'tteo2020_register_editor_stuff' );

/**
 * Enqueue editor assets.
 *
 * @since 1.0.0
 * @return void
 */
function tteo2020_enqueue_editor_assets() {
	wp_enqueue_script( '2020-extras-editor-js' );
	wp_enqueue_style( '2020-extras-editor' );
}
add_action( 'enqueue_block_editor_assets', 'tteo2020_enqueue_editor_assets' );

/**
 * Enqueues scripts for customizer controls & settings.
 *
 * @since 1.0.0
 *
 * @return void
 */
function tteo2020_customize_enqueue() {
	wp_enqueue_script(
		'2020-customize',
		plugins_url( '/js/customize.js', __FILE__ ),
		array( 'jquery' ),
		'1.0',
		false
	);
}
add_action( 'customize_controls_enqueue_scripts', 'tteo2020_customize_enqueue' );

/**
 * Add conditional body classes.
 *
 * @param array $classes Classes added to the body tag.
 *
 * @return array $classes Classes added to the body tag.
 */
function tteo2020_body_classes( $classes ) {
	global $post;

	if ( tteo2020_transparent_check() ) {
		$meta = get_post_meta( $post->ID, 'tteo2020_transparent_header', true );

		if( $meta && '' !== $meta ) {
			$classes[] = 'overlay-header';
			$classes[] = 'transparent-disable-title';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'tteo2020_body_classes' );

function tteo2020_set_title_disable_var( $slug, $name, $templates ) {
	if( 'template-parts/entry-header' === $slug ) {
		set_query_var( 'tteo2020_title_disable_var', true );
	}
}
add_action( 'get_template_part', 'tteo2020_set_title_disable_var', 10, 3 );

function tteo2020_transparent_header_no_title( $title, $id ) {
	global $post;

	$disable = get_query_var( 'tteo2020_title_disable_var', false );

	if ( $disable && tteo2020_transparent_check() ) {
		$meta = get_post_meta( $post->ID, 'tteo2020_transparent_header', true );

		if( $meta && '' !== $meta ) {
			set_query_var( 'tteo2020_title_disable_var', false );
			return '';
		}
	}

	return $title;
}
add_filter( 'the_title', 'tteo2020_transparent_header_no_title', 99, 2 );

function tteo2020_no_header_css() {
	global $post;

	if ( tteo2020_transparent_check() ) {
		$meta = get_post_meta( $post->ID, 'tteo2020_transparent_header', true );

		if( $meta && '' !== $meta ) {
			echo '<style>.post header.entry-header { display: none !important; } .post-inner { padding-top: 0 !important; } .post-inner > .entry-content *:first-child { margin-top: 0 !important; }</style>';
		}
	}
}
add_action( 'wp_head', 'tteo2020_no_header_css', 99 );

/**
 * Sets the credits if the theme mod isn't set yet.
 *
 * @since 1.0.0
 * @param string $mod
 * @param DomDocument $dom
 * @param DomElement $node
 * @return void
 */
function tteo2020_set_default_credits( $mod, $dom, $node ) {
	if ( false === get_theme_mod( $mod ) ) {
		$contents = '';

		if( $node->childNodes->length ) {
			foreach ( $node->childNodes as $part ) {
				$contents .= $dom->saveHTML( $part );
			}
		}

		$contents = trim( preg_replace( '/\s+/', ' ', $contents ) );

		$args = array(
			'mod' => $contents,
			'not' => true,
			'phs' => false,
		);
		
		set_theme_mod( $mod, tteo2020_make_credits_great_again( $args ) );
	}
}

/**
 * Gets a theme mod name and its selector.
 *
 * @todo Redo/rethink this for a larger scale.
 *
 * @since 1.0.0
 * @param string $item Array key.
 * @return array Theme mods names and selectors.
 */
function tteo2020_mods( $item ) {
	$mods = array(
		'footer_html' => array(
			'footer_credits' => 'footer-copyright', 
			'footer_powered' => 'powered-by-wordpress',
		)
	);

	return $mods[ $item ];
}

/**
 * Appends the sanitized/escaped & encoded content where it's needed.
 *
 * @todo Maybe change `auto` to utf8.
 * 
 * @since 1.0.0
 * @param string $mod The theme mod name.
 * @param DomDocument $dom
 * @param DomElement $parent The parent node where we append the html.
 * @return void
 */
function tteo2020_append_new_html( $mod, $dom, $parent ) {
	$html = tteo2020_make_credits_great_again( array( 'mod' => $mod ) );
	$html = mb_convert_encoding( $html, 'HTML-ENTITIES', 'auto' );

	$tempDoc = new DOMDocument();

	$tempDoc->loadHTML( '<div>' . $html . '</div>' );

    foreach ( $tempDoc->getElementsByTagName( 'div' )->item( 0 )->childNodes as $node ) {
        $node = $parent->ownerDocument->importNode( $node, true );
        $parent->appendChild( $node );
	}
}

/**
 * Output buffer callback use to filter the footer credits and change them with our theme mods.
 * Using the DOM api to change the exact html element.
 *
 * @todo Document this.
 * 
 * @since 1.0.0
 * @param string $buffer Contents of the output buffer.
 * @return string New buffer contents.
 */
function tteo2020_html( $buffer ) { 
	libxml_use_internal_errors( true );

	$dom = new DOMDocument();

	$dom->loadHTML( $buffer );

	$dom->formatOutput = false;
	$dom->preserveWhiteSpace = false;

	$finder = new DomXPath( $dom );

	$footerCredits = $finder->query( '//div[@class="footer-credits"]/p' );

	$mods = tteo2020_mods( 'footer_html' );

	$flipMods = array_flip( $mods );

	foreach( $footerCredits as $node ) {
		$classAttr = $node->getAttribute( 'class' );

		if ( empty( $classAttr ) ) {
			continue;
		}

		$classNames = explode( ' ', $classAttr );

		foreach ( $classNames as $className ) {
			if ( ! array_key_exists( $className, $flipMods ) ) {
				continue;
			}

			$theme_mod = $flipMods[ $className ];

			tteo2020_set_default_credits( $theme_mod, $dom, $node );

			while ( $node->childNodes->length ) {
				$node->removeChild( $node->firstChild );
			}

			tteo2020_append_new_html( $theme_mod, $dom, $node );
		}
	}

	$newBuffer = $dom->saveHTML( $dom->documentElement );

	libxml_clear_errors();

	return $newBuffer;
}

/**
 * Filter the html on `template_redirect`
 * Using this method until this PR https://github.com/WordPress/twentytwenty/pull/782 lands in Core.
 * Inspired by this plugin: https://wordpress.org/plugins/remove-footer-credit/ 
 *
 * @since 1.0.0
 * @return void
 */
function tteo2020_on_template_redirect() {
	$disable = ! tteo2020_enabled_footer_option();

	if( is_customize_preview() || $disable ) {
		return;
	}
	
	ob_start( 'tteo2020_html' );
}
add_action( 'template_redirect', 'tteo2020_on_template_redirect' );
