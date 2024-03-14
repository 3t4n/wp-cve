<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza-Manager
 * @subpackage linkPizza-Manager/public
 */
class linkPizza_Manager_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {}


	/**
	 * Register the styles for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'pzz-public', plugin_dir_url( __FILE__ ) . 'css/pzz-public.css', array(), PZZ_VERSION );
	}

	/**
	 * Add the javascript that loads LinkPizza asynchronously
	 *
	 * @since    4.4.1
	 */
	public function add_linkpizza_head() {
		?>
		<?php
		$postid                    = get_the_ID();
		$disabled_domains          = get_post_meta( $postid, '_linkpizza_disabled_domains', true );
		$tracking_only_domains     = get_post_meta( $postid, '_linkpizza_tracking_only_domains', true );
		$indexable_javascript_path = '';
		$indexable_javascript      = get_option( 'pzz_indexable_javascript' );

		if ( '1' === $indexable_javascript ) {
			$indexable_javascript = 'alt/';
		}
		?>

		<script>
			<?php if ( current_user_can( 'edit_posts' ) ) : ?>
				// Logged in admin, do not measure views or reads.
				window.pzzMeasureViews = false;
			<?php endif; ?>
			<?php if ( ! empty( $disabled_domains ) && 'empty' !== $disabled_domains ) : ?>
				window.pzzIgnoredDomains = <?php echo wp_json_encode( $disabled_domains ); ?>;
			<?php endif; ?>

			<?php if ( ! empty( $tracking_only_domains ) && 'empty' !== $tracking_only_domains ) : ?>
				window.pzzTrackingOnlyDomains = <?php echo wp_json_encode( $tracking_only_domains ); ?>;
			<?php endif; ?>

			<?php
				$user_id = get_option( 'pzz_id' );
				$pzz_url = '//pzz.io/' . $indexable_javascript . 'pzz.js';
				$js_url  = add_query_arg(
					array(
						'uid' => $user_id,
					),
					$pzz_url
				);
			?>

			(function(p,z,Z){
				z=p.createElement("script");z.async=1;
				z.src="<?php echo esc_url( $js_url ); ?>&host="+p.domain;
				(p.head||p.documentElement).insertBefore(z,Z);
			})(document);
		</script>
		<?php
	}

	/**
	 * Adds LinkPizza classes to the body tag.
	 *
	 * The JavaScript uses this classes to get the settings for this page.
	 *
	 * TODO: Maybe it's better to switch to data attributes?
	 *
	 * @param array $classes The array with class names.
	 * @return array The array with class names including the LinkPizza options (if they are set)
	 */
	public function add_linkpizza_body_classes( $classes ) {
		$postid                   = get_the_ID();
		$linkpizza_disabled       = get_post_meta( $postid, '_linkPizza_disabled', true );
		$linkpizza_tracking_only  = get_post_meta( $postid, '_linkPizza_tracking_only', true );
		$tracking_only_categories = get_option( 'pzz_tracking_only_categories' );
		$tracking_before_boolean  = 0;
		$tracking_only_before     = get_option( 'pzz_tracking_only_on_posts_before' );
		if ( strtotime( $tracking_only_before ) > strtotime( get_the_date( 'm/d/Y', $postid ) ) ) {
			$tracking_before_boolean = 1;
		}

		$categories                = get_the_category();
		$is_tracking_only_category = false;

		// Loop through categories to check if its enabled.
		if ( ! empty( $categories ) && ! empty( $tracking_only_categories ) ) {
			if ( is_array( $categories ) ) {
				foreach ( $categories as $category ) {
					if ( in_array( $category->cat_ID, $tracking_only_categories ) ) {
						$is_tracking_only_category = true;
					}
				}
			} else {
				if ( in_array( $categories->cat_ID, $tracking_only_categories ) ) {
					$is_tracking_only_category = true;
				}
			}
		}

		if ( '1' === $linkpizza_tracking_only || $is_tracking_only_category || '1' === $tracking_before_boolean ) {
			array_push( $classes, 'pzz-tracking-only' );
			if ( '1' === $linkpizza_tracking_only ) {
				array_push( $classes, 'pzz-post-is-tracking-only' );
			}
			if ( $is_tracking_only_category ) {
				array_push( $classes, 'pzz-category-is-tracking-only' );
			}
			if ( '1' === $tracking_before_boolean ) {
				array_push( $classes, 'pzz-tracking-only-date' );
			}
		}

		if ( '1' === $linkpizza_disabled ) {
			array_push( $classes, 'pzz-ignore' );
			array_push( $classes, 'pzz-post-is-disabled' );
		}

		return $classes;

	}

	/**
	 * Adds Link summary to the content of a page.
	 *
	 * @param string $content The content of the page.
	 * @return string The content of the page including the link summary (if this is enabled).
	 */
	public function pzz_add_link_summary( $content ) {
		$postid                         = get_the_ID();
		$pzz_link_summary_enabled       = get_option( 'pzz_link_summary_enabled' );
		$pzz_link_summary_disabled_post = get_post_meta( $postid, '_pzz_link_summary_disabled_post', true );

		if ( '1' !== $pzz_link_summary_disabled_post && '1' === $pzz_link_summary_enabled ) {
			$pzz_link_summary_border_color         = get_option( 'pzz_link_summary_border_color' );
			$pzz_link_summary_border_width         = get_option( 'pzz_link_summary_border_width' );
			$pzz_link_summary_border_padding       = get_option( 'pzz_link_summary_border_padding' );
			$pzz_link_summary_width                = get_option( 'pzz_link_summary_width' );
			$pzz_link_summary_link_color           = get_option( 'pzz_link_summary_link_color' );
			$pzz_link_summary_layout_type          = get_option( 'pzz_link_summary_layout_type' );
			$pzz_link_summary_position             = get_option( 'pzz_link_summary_position' );
			$pzz_link_summary_tag_background_color = get_option( 'pzz_link_summary_tag_background_color' );
			$pzz_link_summary_use_text_title       = get_option( 'pzz_link_summary_use_title_text' );

			if ( class_exists( 'DOMDocument' ) ) {
				libxml_use_internal_errors( true );
				$dom_document = new DOMDocument();
				if ( '' !== $content ) {
					if ( $dom_document->loadHTML( $content ) ) {
						// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$dom_document->preserveWhiteSpace = false;

						// uUe DOMXpath to navigate the html with the DOM.
						$elements = $dom_document->getElementsByTagName( 'a' );

						if ( ! ( 0 === $elements->length ) ) {
							$pzz_tags_background_style = '';
							if ( '1' === $pzz_link_summary_layout_type ) {
								$pzz_layout_ol_style = '';
								$pzz_class           = 'pzz-link-summary-list';
								switch ( $pzz_link_summary_position ) {
									case 1:
										$pzz_layout_style = 'text-align: left; border: ' . $pzz_link_summary_border_width . 'px solid ' . $pzz_link_summary_border_color . '; width:' . $pzz_link_summary_width . '%; padding: ' . $pzz_link_summary_border_padding . 'px;  ';
										break;
									case 2:
										$pzz_layout_style = 'text-align: center; border: ' . $pzz_link_summary_border_width . 'px solid ' . $pzz_link_summary_border_color . '; width:' . $pzz_link_summary_width . '%; padding: ' . $pzz_link_summary_border_padding . 'px; margin: 0 auto; ';
										break;
									case 3:
										$pzz_layout_style = 'text-align: left; border: ' . $pzz_link_summary_border_width . 'px solid ' . $pzz_link_summary_border_color . '; width:' . $pzz_link_summary_width . '%; padding: ' . $pzz_link_summary_border_padding . 'px; margin: 0 ' . ( 100 - $pzz_link_summary_width ) . '%; ';
										break;
								}
							} else {
								$pzz_class        = 'pzz-link-summary-tags';
								$pzz_layout_style = '';
								switch ( $pzz_link_summary_position ) {
									case 1:
										$pzz_layout_ol_style = 'text-align: left;';
										break;
									case 2:
										$pzz_layout_ol_style = 'text-align: center; ';
										break;
									case 3:
										$pzz_layout_ol_style = 'text-align: right;';
										break;
								}
								if ( empty( $pzz_link_summary_tag_background_color ) || '1' === $pzz_link_summary_tag_background_color ) {
									$pzz_link_summary_tag_background_color = 'transparent';
								}
								$pzz_tags_background_style = 'style="background-color:' . esc_attr( $pzz_link_summary_tag_background_color ) . ';"';
							}
							$aftercontent = '<div class="' . esc_attr( $pzz_class ) . '" style="' . esc_attr( $pzz_layout_style ) . '"><h5>' . esc_html__( 'Links in this article', 'linkpizza-manager' ) . '</h5><ol style="' . esc_attr( $pzz_layout_ol_style ) . '">';
							foreach ( $elements as $element ) {
								$target_url      = $element->getAttribute( 'href' );
								$domain          = wp_parse_url( $element->getAttribute( 'href' ), PHP_URL_HOST );
								$stripped_domain = preg_replace( '/^www\./', '', $domain );
								// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
								$text_title      = $element->textContent;
								$title_attribute = $element->getAttribute( 'title' );
								if ( ! empty( $title_attribute ) && '' !== $title_attribute && '1' === $pzz_link_summary_use_text_title ) {
									$aftercontent = $aftercontent . '<li ' . esc_attr( $pzz_tags_background_style ) . '><a style="color:' . esc_attr( $pzz_link_summary_link_color ) . ';" href="' . esc_url( $target_url ) . '">' . $title_attribute . '</a></li>';
								} else {
									if ( ! empty( $text_title ) && '' !== $text_title && '1' === $pzz_link_summary_use_text_title ) {
										$aftercontent = $aftercontent . '<li ' . esc_attr( $pzz_tags_background_style ) . '><a style="color:' . esc_attr( $pzz_link_summary_link_color ) . ';" href="' . esc_url( $target_url ) . '">' . $text_title . '</a></li>';
									} else {
										$aftercontent = $aftercontent . '<li ' . $pzz_tags_background_style . '><a style="color:' . esc_attr( $pzz_link_summary_link_color ) . ';" href="' . esc_url( $target_url ) . '">' . $stripped_domain . '</a></li>';
									}
								}
							}
							$aftercontent = $aftercontent . '</ol></div>';
							$fullcontent  = $content . $aftercontent;
							return $fullcontent;
						}
						return $content;
					}
					return $content;
				}
				libxml_clear_errors();
				return $content;
			}
		}
		return $content;
	}

}
