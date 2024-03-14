<?php
/**
 * The CTA class.
 *
 * @package    Rock_Convert\Inc\Frontend
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\inc\frontend;

use Rock_Convert\Inc\Admin\Admin;
use Rock_Convert\inc\Admin\Utils;
use Rock_Convert\inc\libraries\Content;

/**
 * This class is responsible to render CTA before and after the content
 *
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */
class CTA {

	/**
	 * The string for UTM medium
	 *
	 * @since  2.0.0
	 * @access private
	 * @var string
	 */
	private $medium_name = 'rock-convert';

	/**
	 * CTA constructor.
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'register' ) );
		add_shortcode(
			'rock-convert-cta',
			array( $this, 'render_cta_from_shortcode' )
		);
	}

	/**
	 * Receive the post content
	 *
	 * @param string $content Content to be rendered.
	 *
	 * @return string
	 */
	public function register( $content ) {
		global $post;

		if ( is_singular( 'post' ) ) {
			return $this->insert_cta( $content, $post );
		}

		return $content;
	}

	/**
	 * Automatically insert banner in posts. Customized WordPress Looping.
	 *
	 * @param string $content Content to be inserted.
	 * @param object $post Post object.
	 *
	 * @return string
	 */
	public function insert_cta( $content, $post ) {
		$categories = $this->get_post_categories_ids( $post->ID );
		$tags       = $this->get_post_tags_ids( $post->ID );

		$analytics_enabled = Admin::analytics_enabled();

		// Unset if default category.
		if ( in_array( get_option( 'default_category' ), $categories, true ) ) {
			unset( $categories[ array_search( get_option( 'default_category' ), $categories, true ) ] );
		}

		$cta_args = array(
			'post_type'      => 'cta',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'date',
			'tax_query'      => array( // phpcs:ignore
				'relation' => 'OR',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $categories,
					'operator' => 'IN',
				),
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tags,
					'operator' => 'IN',
				),
			),
		);
		$ctas     = new \WP_Query( $cta_args );

		if ( $ctas->have_posts() ) {
			array_filter(
				$ctas->posts,
				function ( $key ) use ( $ctas, $tags, $categories ) {
					$cta_id   = $ctas->posts[ $key ]->ID;
					$cta_cats = $this->get_post_categories_ids( $cta_id );
					$cta_tags = $this->get_post_tags_ids( $cta_id );
					if ( ! empty( $cta_cats ) && ! empty( $cta_tags ) ) {
						if ( ! empty( array_diff( $cta_cats, $categories ) ) || ! empty( array_diff( $cta_tags, $tags ) ) ) {
							unset( $ctas->posts[ $key ] );
						}
					}
				},
				ARRAY_FILTER_USE_KEY
			);

			while ( $ctas->have_posts() ) {
				$ctas->the_post();

				$cta_id = get_the_ID();
				$metas  = $this->get_cta_values(
					$cta_id,
					get_the_title()
				);

				$permalink = get_permalink( $post );

				/**
				 * Check if banner shouldn't be displayed in this post
				 */
				if ( $this->exclude_cta(
					$metas['visibility'],
					$metas['excluded_urls'],
					$permalink
				)
				) {
					continue;
				}

				if ( ! empty( $metas['image_tag'] ) ) {
					$content = $this->render_cta(
						$metas,
						$content,
						$cta_id,
						$analytics_enabled
					);
				}
			}
		}

		wp_reset_postdata();

		return $content;
	}

	/**
	 * Get post categories
	 *
	 * @param int $post_id The id of post.
	 *
	 * @return array
	 */
	public function get_post_categories_ids( $post_id ) {
		$cats            = array();
		$post_categories = wp_get_post_categories( $post_id );

		foreach ( $post_categories as $c ) {
			$cat    = get_category( $c );
			$cats[] = $cat->term_id;
		}

		return $cats;
	}

	/**
	 * Get post tags
	 *
	 * @param int $post_id The id of post.
	 *
	 * @return array
	 */
	public function get_post_tags_ids( $post_id ) {
		$tags      = array();
		$post_tags = wp_get_post_tags( $post_id );

		foreach ( $post_tags as $t ) {
			$tag    = get_tag( $t );
			$tags[] = $tag->term_id;
		}

		return $tags;
	}

	/**
	 * Get CTA meta attributes
	 *
	 * @param int    $cta_id Id of banner.
	 * @param string $cta_title Title of banner.
	 *
	 * @return array
	 */
	public function get_cta_values( $cta_id, $cta_title ) {
		$custom_fields = get_post_custom( $cta_id );

		$url        = isset( $custom_fields['_rock_convert_title'] )
					? $custom_fields['_rock_convert_title'][0] : null;
		$source     = isset( $custom_fields['_rock_convert_utm_source'] )
					? $custom_fields['_rock_convert_utm_source'][0] : null;
		$campaign   = isset( $custom_fields['_rock_convert_utm_campaign'] )
					? $custom_fields['_rock_convert_utm_campaign'][0] : null;
		$image_id   = isset( $custom_fields['_rock_convert_image_media'] )
					? $custom_fields['_rock_convert_image_media'][0] : null;
		$position   = isset( $custom_fields['_rock_convert_position'] )
					? $custom_fields['_rock_convert_position'][0] : null;
		$medium     = isset( $custom_fields['_rock_convert_utm_medium'] )
					? $custom_fields['_rock_convert_utm_medium'][0] : null;
		$visibility = isset( $custom_fields['_rock_convert_visibility'] )
					? $custom_fields['_rock_convert_visibility'][0] : null;

		$image_tag     = null;
		$excluded_urls = array();

		if ( ! empty( $image_id ) ) {
			$image_tag = wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array( 'title' => $cta_title )
			);
		}

		if ( 'exclude' === $visibility ) {
			$excluded_urls = get_post_meta(
				$cta_id,
				'_rock_convert_excluded_urls',
				true
			);
		}

		return array(
			'image_tag'     => $image_tag,
			'title'         => $cta_title,
			'url'           => $url,
			'source'        => $source,
			'campaign'      => $campaign,
			'position'      => $position,
			'medium'        => $medium,
			'excluded_urls' => $excluded_urls,
			'visibility'    => $visibility,
		);
	}

	/**
	 * Return true if banner sould not be displayed in a URL
	 *
	 * @param string $visibility Visibility of banner.
	 * @param array  $urls URL of banner.
	 * @param string $permalink Link of banner.
	 *
	 * @return bool
	 */
	public function exclude_cta( $visibility, $urls, $permalink ) {
		if ( 'all' === $visibility ) {
			return false;
		}

		return in_array( $permalink, $urls, true );
	}

	/**
	 * Render a CTA based on position
	 *
	 * @param string  $metas Metadata about banner.
	 * @param string  $content Banner content.
	 * @param int     $cta_id Banner ID.
	 * @param boolean $analytics_enabled Define if analytics is enabled.
	 * @return string
	 */
	public function render_cta( $metas, $content, $cta_id, $analytics_enabled ) {
		$html_content = new Content();
		$banner       = $this->build_banner( $metas, $cta_id, $analytics_enabled );

		return $html_content->insert_banner( $content, $banner, $metas['position'] );
	}

	/**
	 * Build banner function
	 *
	 * @param string  $metas Metadata about banner.
	 * @param int     $id Banner id.
	 * @param boolean $analytics_enabled Define if analytics is enabled.
	 * @return string
	 */
	public function build_banner( $metas, $id, $analytics_enabled ) {
		$html  = '';
		$class = $analytics_enabled ? 'rock-convert-cta-link' : '';

		$html .= '<div class="rock-convert-banner">';
		$html .= '<a href="' . $this->build_link( $metas )
				. '" target="_blank" class="' . $class . '" data-cta-id="'
				. $id . '" data-cta-title="' . $metas['title'] . '">';
		$html .= $metas['image_tag'];
		$html .= '</a>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Build link for CTA banners.
	 *
	 * If url already have query_string params this function will merge these parameters with the parameters
	 * saved on CTA using utm_campaign and utm_source fields.
	 *
	 * @param string $metas Metada about banner.
	 *
	 * @return string URL with query_string containing utm_source, utm_medium and utm_campaign
	 *
	 * @since 2.1.2
	 */
	public function build_link( $metas ) {
		$url_data             = $this->extract_params_from_url( $metas['url'] );
		$params               = array();
		$params['utm_medium'] = $this->medium_name;

		if ( ! empty( $metas['source'] ) ) {
			$params['utm_source'] = $metas['source'];
		}

		/**
		 * Add support for version 1.0 that medium field was open to edit.
		 */
		if ( ! empty( $metas['campaign'] ) || ! empty( $metas['medium'] ) ) {
			if ( ! empty( $metas['campaign'] ) ) {
				$params['utm_campaign'] = $metas['campaign'];
			} else {
				$params['utm_campaign'] = $metas['medium'];
			}
		}

		/**
		 * Merge params from original URL with Rock Convert UTM params.
		 */
		$full_params = array_merge( $params, $url_data['params'] );

		/**
		 * Render URL with query strings.
		 */
		return esc_url_raw( add_query_arg( $full_params, $url_data['raw_url'] ) );
	}

	/**
	 * Extract query string params from URL and return the URL without the params
	 * and the params as an array key-value based.
	 *
	 * @param string $url URL.
	 *
	 * @return array
	 *
	 * @since 2.1.2
	 */
	public function extract_params_from_url( $url ) {
		$new_url = $url;
		$params  = array();

		if ( strpos( $url, '?' ) !== false ) {
			$settings    = explode( '?', $url );
			$new_url     = $settings[0];
			$params_list = explode( '&', $settings[1] );

			foreach ( $params_list as $param ) {
				if ( strpos( $param, '=' ) !== false ) {
					$p               = explode( '=', $param );
					$params[ $p[0] ] = $p[1];
				}
			}
		}

		return array(
			'raw_url' => $new_url,
			'params'  => $params,
		);
	}


	/**
	 * Banner shortcode
	 *
	 * @param array $atts Attributes of banner.
	 *
	 * @return string
	 */
	public function render_cta_from_shortcode( $atts ) {
		$id = $atts['id'];

		if ( ! empty( $id ) ) {
			$post = get_post( $id );

			if ( ! empty( $post ) ) {
				$metas = $this->get_cta_values(
					$id,
					$post->post_title
				);

				$analytics_enabled = Admin::analytics_enabled();

				return $this->build_banner( $metas, $id, $analytics_enabled );
			}
		}
	}
}
