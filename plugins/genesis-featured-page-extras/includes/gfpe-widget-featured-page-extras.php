<?php
/**
 * GFPE: Featured Page Extras Widget.
 *
 * NOTE: See important notes on widget class PHP Doc block!
 *
 * @package    Genesis Featured Page Extras
 * @subpackage Widgets
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-featured-page-extras/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * The main plugin class - creating the Featured Page Extras widget.
 *
 * NOTE I:
 *    Parts of that widget code were forked from Genesis core by StudioPress/
 *    Copyblogger Media LLC (essential base structure).
 * @author  StudioPress
 * @link    http://studiopress.com/
 * @license GPL-2.0+
 *
 * NOTE II:
 *    Parts of that widget code were forked/ inspired from the plugin "Genesis
 *    Featured Page Extended" by Johan van de Merwe (image URL, Byline, page
 *    excerpt).
 * @author  Johan van de Merwe
 * @link    http://wordpress.org/plugins/genesis-featured-page-extended/
 * @license GPL-2.0+
 *
 * NOTE III:
 *    All other parts of this widget are developed by David Decker of DECKERWEB!
 * @author  David Decker - DECKERWEB
 * @link    http://deckerweb.de/
 * @license GPL-2.0+
 *
 * @since   1.0.0
 */
class DDW_Genesis_Featured_Page_Extras extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since 1.0.0
	 *
	 * @var   array
	 */
	protected $defaults;


	/**
	 * Constructor. Set the default widget options and create widget.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		/** Array of default widget options */
		$this->defaults = array(
			'title'             => '',
			'title_icon'        => '',
			'title_url_page'    => 0,
			'title_url'         => '',
			'title_url_target'  => 0,
			'title_hide'        => 0,
			'page_id'           => '',
			'image_show'        => 0,
			'image_url'         => '',
			'image_alignment'   => '',
			'image_size'        => '',
			'image_link'        => 1,
			'page_title_show'   => 0,
			'page_title_limit'  => '',
			'page_title_cutoff' => '&hellip;',
			'page_title_link'   => 1,
			'page_title_icon'   => '',
			'byline_show'       => 0,
			'page_post_info'    => '[post_date] ' . __( 'By', 'genesis-featured-page-extras' ) . ' [post_author_posts_link] [post_comments] [post_edit]',
			'content_type'      => '',
			'content_limit'     => '',
			'page_keep_wpautop' => 0,
			'more_link_text'    => '',
			'more_link_url'     => '',
			'more_link_target'  => '',
			'more_link_class'   => '',
			'more_link_show'    => 0,
			'custom_content'    => '',
			'custom_wpautop'    => 0,
			'widget_display'    => 'global',
			'not_in_public'     => 0,
			'intro_text'        => '',
			'outro_text'        => '',
		);

		/** Defaults array, filterable */
		$this->defaults = (array) apply_filters( 'gfpe_filter_widget_defaults', $this->defaults );

		/** Widget options */
		$widget_ops = array(
			'classname'   => 'featured-content featuredpage',
			'description' => __(
				'Displays featured page - with support for: thumbnails, title, content, excerpt, custom content and more link/ text.',
				'genesis-featured-page-extras'
			),
		);

		/** Widget controller options */
		$control_ops = array(
			'id_base' => 'featuredpage-extras',
			'width'   => 400
		);

		/** Widget constructor */
		parent::__construct(
			'featuredpage-extras',
			__( 'Genesis - Featured Page Extras', 'genesis-featured-page-extras' ),
			$widget_ops,
			$control_ops
		);

		/** Load our custom assets. */
		add_action( 'sidebar_admin_setup', array( 'DDW_Genesis_Featured_Page_Extras', 'assets_loader' ) );

	}  // end of method __construct


	/**
	 * Necessary in-between-step to load our scripts only on Widgets admin.
	 *
	 * @since 1.1.1
	 */
	function assets_loader() {

		add_action( 'admin_enqueue_scripts', array( 'DDW_Genesis_Featured_Page_Extras', 'assets' ) );

	}  // end of method assets_loader


	/**
	 * Loads any plugin assets we may have.
	 *
	 * @since  1.0.0
	 *
	 * @uses   wp_enqueue_media()
	 * @uses   wp_register_script()
	 * @uses   ddw_gfpe_script_suffix()
	 * @uses   ddw_gfpe_script_version()
	 * @uses   wp_localize_script()
	 * @uses   wp_enqueue_script()
	 */
	public function assets() {

		/** This function loads in the required media files for the media manager. */
		wp_enqueue_media();

		/** Register our custom JavaScript */
		wp_register_script(
			'gfpe-media-uploader',
			plugins_url( '/js/gfpe-media' . ddw_gfpe_script_suffix() . '.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			ddw_gfpe_script_version(),
			TRUE
		);

		/** Localize strings within JavaScript */
		wp_localize_script(
			'gfpe-media-uploader',
			'gfpe_media',
			array(
				'frame_title'  => __( 'Upload or Choose Your Custom Image File', 'genesis-featured-page-extras' ),
				'button_title' => __( 'Insert Image into Input Field', 'genesis-featured-page-extras' )
			)
		);

		/** Enqueue our script */
		wp_enqueue_script( 'gfpe-media-uploader' );

	}  // end of method assets


	/**
	 * Echo the widget content.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param  array $instance The settings for the particular instance of the widget
	 *
	 * @global mixed $wp_query
	 */
	function widget( $args, $instance ) {

		/** Extract arguments */
		extract( $args );

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		
		/** Check SPECIFIC display option for this widget and optionally disable it from displaying */
		if ( $instance[ 'not_in_public' ] && ! is_user_logged_in() ) {
			return;
		}

		/** Check GENERAL display option for this widget and optionally disable it from displaying */
		if (
				/** Posts/ Pages stuff */
			( ( 'single_posts' === $instance[ 'widget_display' ] ) && ! is_singular( 'post' ) )
			|| ( ( 'single_pages' === $instance[ 'widget_display' ] ) && ! is_singular( 'page' ) )
			|| ( ( 'single_posts_pages' === $instance[ 'widget_display' ] ) && ! is_singular( array( 'post', 'page' ) ) )
		) {

			return;

		}  // end-if widget display checks

		/** Get global $wp_query object */
		global $wp_query;

		/** Widget title URL helpers */
		$title_url_custom = ( ! empty( $instance[ 'title_url' ] ) && ! $instance[ 'title_url_page' ] ) ? $instance[ 'title_url' ] : '';
		$title_url        = ( $instance[ 'title_url_page' ] ) ? get_permalink( $instance[ 'page_id' ] ) : $title_url_custom;

		/** Optional title URL target */
		$title_url_target = ( $instance[ 'title_url_target' ] ) ? ' target="_blank"' : '';

		/** Typical WordPress Widget title filter */
		$title = apply_filters( 'widget_title', $instance[ 'title' ], $instance, $this->id_base );

		/** GFPE Widget title filter */
		$title = apply_filters( 'gfpe_filter_widget_title', $instance[ 'title' ], $instance, $this->id_base );

		/** Prepare the title display string */
		$title_display = sprintf(
			'%1$s%2$s%3$s',
			( $instance[ 'title_url_page' ] || ! empty( $instance[ 'title_url' ] ) ) ? '<a href="' . esc_url( $title_url ) . '"' . $title_url_target . '>' : '',
			esc_attr( $instance[ 'title' ] ),
			( $instance[ 'title_url_page' ] || ! empty( $instance[ 'title_url' ] ) ) ? '</a>' : ''
		);

		/** Output the widget wrapper and title */
		echo $before_widget;

		/** Set up the complete title display */
		if ( empty( $instance[ 'title_hide' ] ) && ! empty( $instance[ 'title' ] ) ) {

			echo $before_title . $title_display . $after_title;

		}  // end if

		/** Action hook 'gfpe_before_search_widget' */
		do_action( 'gfpe_before_page_widget', $instance, $this->id_base );

		/** Display widget intro text if it exists */
		if ( ! empty( $instance[ 'intro_text' ] ) ) {

			printf(
				'<div class="textwidget"><p class="%s-intro-text gfpe-intro-text">%s</p></div>',
				$this->id,
				$instance[ 'intro_text' ]
			);

		}  // end-if optional intro

		/** Set new WP_Query instance */
		$wp_query = new WP_Query( array( 'page_id' => $instance[ 'page_id' ] ) );

		/** Loop through pages */
		if ( have_posts() ) :

			while ( have_posts() ) : the_post();

				/** Genesis markup: open */
				genesis_markup( array(
					'html5'   => '<article %s>',
					'xhtml'   => sprintf( '<div class="%s">', implode( ' ', get_post_class() ) ),
					'context' => 'entry',
				) );

				/**
				 * Image center alignment helper - needs to go before actual
				 *    image if/else statement!
				 */
				if ( 'aligncenter' === $instance[ 'image_alignment' ] ) {

					$img_centered = ' centered';

					add_filter( 'genesis_attr_entry-image-widget', 'ddw_gfpe_entry_image_widget_class' );

				} else {

					$img_centered = '';

				}  // end if

				/** a) Featured image support */
				if ( ! empty( $instance[ 'image_show' ] ) && empty( $instance[ 'image_url' ] ) ) {

					/** Retrieve featured image */
					$image = genesis_get_image( array(
						'format'  => 'html',
						'size'    => $instance[ 'image_size' ],
						'context' => 'featured-page-widget',
						'attr'    => genesis_parse_attr( 'entry-image-widget' ),
					) );

					/** Output featured image */
					if ( $image ) {

						printf(
							'<%1$s %2$s title="%3$s" class="%4$s%5$s">%6$s</%1$s>',
							( $instance[ 'image_link' ] ) ? 'a' : 'span',
							( $instance[ 'image_link' ] ) ? 'href="' . get_permalink() . '"' : '',
							the_title_attribute( 'echo=0' ),
							esc_attr( $instance[ 'image_alignment' ] ),
							$img_centered,
							$image
						);

					}  // end if

				}

				/** b) Alternatively support image URL */
				elseif ( ! empty( $instance[ 'image_url' ] ) ) {

					$img_sizes    = genesis_get_image_sizes();		// all image sizes available
					$size_wh      = $instance[ 'image_size' ];		// needed size (set in widget)
					$custom_image = '<img width="' . absint( $img_sizes[ $size_wh ][ 'width' ] ) . '" height="' . absint( $img_sizes[ $size_wh ][ 'height' ] ) . '" src="' . esc_url( $instance[ 'image_url' ] ) . '" class="attachment-' . $size_wh . $img_centered . '" alt="' . the_title_attribute( 'echo=0' ) . '" />';

					printf(
						'<%1$s %2$s title="%3$s" class="%4$s%5$s">%6$s</%1$s>',
						( $instance[ 'image_link' ] ) ? 'a' : 'span',
						( $instance[ 'image_link' ] ) ? 'href="' . esc_url( get_permalink() ) . '"' : '',	//esc_url( get_permalink() ),
						the_title_attribute( 'echo=0' ),
						esc_attr( $instance[ 'image_alignment' ] ),
						$img_centered,
						$custom_image
					);

				}  // end if

				/** Page title support */
				if ( ! empty( $instance[ 'page_title_show' ] ) ) {

					if ( ! empty( $instance[ 'page_title_limit' ] ) ) {

						$page_title = genesis_truncate_phrase( the_title_attribute( 'echo=0' ), $instance[ 'page_title_limit' ] ) . $instance[ 'page_title_cutoff' ];

					} else {

						$page_title = get_the_title();

					}  // end if

					$page_link = sprintf(
						'<a href="%s" title="%s">%s</a>',
						get_permalink(),
						the_title_attribute( 'echo=0' ),
						$page_title //get_the_title()
					);

					/** Genesis 2.0+ / HTML5 way: */
					if ( genesis_html5() ) {

						printf(
							'<header class="entry-header"><h2 class="entry-title">%s</h2></header>',
							( ! $instance[ 'page_title_link' ] ) ? $page_title : $page_link
						);

					}

					/** Backwards compatible, pre Genesis 2.0: */
					else {

						printf(
							'<h2>%s</h2>',
							( ! $instance[ 'page_title_link' ] ) ? $page_title : $page_link
						);

					}  // end if

				}  // end if

				/** Page Byline support */
				if ( ! empty( $instance[ 'byline_show' ] ) && ! empty( $instance['page_post_info'] ) ) {

					printf(
						genesis_html5() ? '<p class="entry-meta">%s</p>' : '<p class="byline post-info">%s</p>',
						do_shortcode( $instance[ 'page_post_info' ] )
					);

				}  // end if


				/** Set optional "autark" custom 'More Link' */
				$more_link_target = ( 'none' != $instance[ 'more_link_target' ] ) ? ' target="' . esc_html( $instance[ 'more_link_target' ] ) . '"' : '';

				$autark_more_link =	sprintf(
					'<div class="gfpe-more-link"><a class="more-link%s" href="%s"%s title="%s">%s</a></div>',
					( ! empty( $instance[ 'more_link_class' ] ) ) ? ' ' . esc_attr( $instance[ 'more_link_class' ] ) : '',
					( empty( $instance[ 'more_link_url' ] ) ) ? get_permalink( $instance[ 'page_id' ] ) : esc_url( $instance[ 'more_link_url' ] ),
					$more_link_target,
					esc_html( $instance[ 'more_link_text' ] ),
					$instance[ 'more_link_text' ]
				);


				/**
				 * 1) Content Type: Original Page Content (optional with wpautop!)
				 */
				if ( 'page_content' === $instance[ 'content_type' ] ) {

					echo genesis_html5() ? '<div class="entry-content">' : '';

					/** a) No content limit is set: */
					if ( empty( $instance[ 'content_limit' ] ) ) {

						global $more;

						$more = 0;

						printf(
							'%s',
							( $instance[ 'page_keep_wpautop' ] ) ? wpautop( the_content() ) . $autark_more_link : get_the_content() . $autark_more_link
						);

					}

					/** b) A custom content limit is set: */
					else {

						printf(
							'%s',
							( empty( $instance[ 'more_link_url' ] ) ) ? the_content_limit( (int) $instance[ 'content_limit' ], esc_html( $instance[ 'more_link_text' ] ) ) : the_content_limit( (int) $instance[ 'content_limit' ], '' ) . $autark_more_link
						);

					}  // end if

					echo genesis_html5() ? '</div>' : '';

				}  // end if

				/**
				 * 2) Content Type: Original Page Excerpt
				 */
				if ( 'page_excerpt' === $instance[ 'content_type' ] ) {

					echo genesis_html5() ? '<div class="entry-content">' : '';

					printf(
						the_excerpt() . '%s',
						( empty( $instance[ 'more_link_show' ] ) ) ? '' : $autark_more_link
					);

					echo genesis_html5() ? '</div>' : '';

				}  // end if

				/**
				 * 3) Content Type: Custom Text by user
				 */
				if ( 'custom_user_content' === $instance[ 'content_type' ] ) {

					echo '<div class="entry-content custom-content">';
					$content = apply_filters(
						'widget_text',
						empty( $instance[ 'custom_content' ] ) ? '' : $instance[ 'custom_content' ],
						$instance
					);
					echo ( $instance[ 'custom_wpautop' ] ) ? wpautop( $content ) : $content; 
					echo '</div>';

					if ( ! empty( $instance[ 'more_link_text' ] ) ) {

						echo $autark_more_link;

					}  // end if
				
				}  // end if

				/**
				 * 4) Content Type: no content at all
				 */
				if ( 'no_content' === $instance[ 'content_type' ] ) {

					if ( $instance[ 'more_link_text' ] ) {

						echo $autark_more_link;

					}  // end if

				}  // end if

				/** Genesis markup: close */
				genesis_markup( array(
					'html5' => '</article>',
					'xhtml' => '</div><!--end post_class()-->' . "\n\n",
				) );

			endwhile;  // end while (when pages exist)

		endif;  // end if loop check

		/** Display widget outro text if it exists */
		if ( ! empty( $instance[ 'outro_text' ] ) ) {

			printf(
				'<div class="textwidget"><p class="%s-outro_text gfpe-outro-text">%s</p></div>',
				$this->id,
				$instance[ 'outro_text' ]
			);

		}  // end-if optional outro

		/** Action hook 'gfpe_after_search_widget' */
		do_action( 'gfpe_after_search_widget', $instance, $this->id_base );

		/** Output the closing widget wrapper */
		echo $after_widget;

		/** Reset our custom $wp_query object */
		wp_reset_query();

	}  // end of method widget


	/**
	 * Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $new_instance New settings for this instance as input by the user via form()
	 * @param  array $old_instance Old settings for this instance
	 *
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$new_instance[ 'title' ]             = strip_tags( $new_instance[ 'title' ] );
		$new_instance[ 'title_url_page' ]    = isset( $new_instance[ 'title_url_page' ] );
		$new_instance[ 'title_url' ]         = strip_tags( $new_instance[ 'title_url' ] );
		$new_instance[ 'title_url_target' ]  = isset( $new_instance[ 'title_url_target' ] );
		$new_instance[ 'title_hide' ]        = isset( $new_instance[ 'title_hide' ] );
		$new_instance[ 'image_link' ]        = isset( $new_instance[ 'image_link' ] );
		$new_instance[ 'page_title_link' ]   = isset( $new_instance[ 'page_title_link' ] );
		$new_instance[ 'page_post_info' ]    = wp_kses_post( $new_instance[ 'page_post_info' ] );
		$new_instance[ 'page_keep_wpautop' ] = isset( $new_instance[ 'page_keep_wpautop' ] );
		$new_instance[ 'custom_content' ]    = current_user_can( 'unfiltered_html' ) ? $new_instance[ 'custom_content' ] : genesis_formatting_kses( $new_instance[ 'custom_content' ] );
		$new_instance[ 'custom_wpautop' ]    = isset( $new_instance[ 'custom_wpautop' ] );
		$new_instance[ 'more_link_text' ]    = strip_tags( $new_instance[ 'more_link_text' ] );
		$new_instance[ 'more_link_url' ]     = strip_tags( $new_instance[ 'more_link_url' ] );
		$new_instance[ 'more_link_class' ]   = strip_tags( $new_instance[ 'more_link_class' ] );
		$new_instance[ 'more_link_target' ]  = strip_tags( $new_instance[ 'more_link_target' ] );
		$new_instance[ 'not_in_public' ]     = isset( $new_instance[ 'not_in_public' ] );
		$new_instance[ 'intro_text' ]        = current_user_can( 'unfiltered_html' ) ? $new_instance[ 'intro_text' ] : genesis_formatting_kses( $new_instance[ 'intro_text' ] );
		$new_instance[ 'outro_text' ]        = current_user_can( 'unfiltered_html' ) ? $new_instance[ 'outro_text' ] : genesis_formatting_kses( $new_instance[ 'outro_text' ] );

		return $new_instance;

	}  // end of method update


	/**
	 * Echo the settings update form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		/** Helper styles */
		$gfpe_hr_style  = 'style="border: 1px dashed #ddd; margin: 15px 0 !important;"';
		$code_font_size = 'style="font-size: 0.85em !important;"';
		$div_intent     = 'style="display: block; margin: -10px 0 0 22px;"';

		/** Helper strings */
		$optional_content_string = __( '(Optional)', 'genesis-featured-page-extras' ) . ' ';
		$full_path_url           = sprintf(
			' <small>(' . __( 'full path, including %s', 'genesis-featured-page-extras' ) . ')</small>',
			'<code ' . $code_font_size . '>http://</code>'
		);
		$wp_string               = _x( 'WP', 'in drop-down select for image sizes', 'genesis-featured-page-extras' ) . ':';
		$gfpe_select_divider     = '<option value="void" disabled="disabled">—————————————————</option>';

		/** Gets the widget ID prefix, very important for image uploader */
		$widget_id_prefix = $this->get_field_id( '' );

		/** Include widget form code -- bail early if Genesis is not active */
		if ( function_exists( 'genesis_get_additional_image_sizes' ) ) {

			include( GFPE_PLUGIN_DIR . 'includes/gfpe-widget-featured-page-extras-form.php' );

		}  // end if

	}  // end of method form

}  // end of main widget class


/**
 * Neccessary go-in-between step to add an addtional "centered" class to
 *    featured image <img> tag!
 *
 * Added to filter: 'genesis_attr_entry-image-widget'
 *
 * @since  1.0.0
 *
 * @param  string $attributes
 *
 * @return string String of CSS classes for entry attributes.
 */
function ddw_gfpe_entry_image_widget_class( $attributes ) {

	$attributes[ 'class' ] = $attributes[ 'class' ] . ' centered';

	return $attributes;

}  // end of function ddw_gfpe_entry_image_widget_class