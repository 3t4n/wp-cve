<?php
/**
 * Demos model class.
 *
 * @package Omnipress\Models
 */

namespace Omnipress\Models;

use Omnipress\Abstracts\ModelsBase;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Patterns model class.
 *
 * @since 1.1.0
 */
class DemosModel extends ModelsBase {

	protected $model_type    = 'demos';
	protected $global_styles = null;

	private function _demo_data_contents( $file_contents, $file_url ) {

		if ( ! class_exists( 'WP_Import' ) ) {
			require_once OMNIPRESS_PATH . 'includes/Libraries/importer/init.php';
		}

		require_once ABSPATH . 'wp-includes/post.php';
		require_once ABSPATH . 'wp-admin/includes/admin.php';

		$tmpfile = trailingslashit( get_temp_dir() ) . md5( $file_url ) . '-' . basename( $file_url );

		file_put_contents( $tmpfile, $file_contents );

		/**
		 * Bufferring because WP_Import echos and dies progress during import which causes JSON error at client side.
		 */
		ob_start();
		$wp_import                    = new \WP_Import();
		$wp_import->fetch_attachments = true;
		$wp_import->import( $tmpfile );
		ob_end_clean();
	}

	public function set_demo_data( $key ) {
		$demo = $this->get_by_keys( $key );

		if ( empty( $demo->data ) ) {
			return;
		}

		$pages = $demo->pages;

		$posts_to_delete = array();

		/**
		 * Delete existing pages if title matches.
		 */
		if ( is_array( $pages ) && ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				array_push(
					$posts_to_delete,
					get_posts(
						array(
							'post_type'   => 'page',
							'post_status' => array_merge( array_keys( get_post_statuses() ), array( 'trash' ) ),
							'title'       => $page->title,
						)
					)
				);
			}
		}

		/**
		 * Existing global styles.
		 */
		array_push(
			$posts_to_delete,
			get_posts(
				array(
					'post_type' => 'wp_global_styles',
				)
			)
		);

		/**
		 * Existing navigations.
		 */
		array_push(
			$posts_to_delete,
			get_posts(
				array(
					'post_type' => 'wp_navigation',
				)
			)
		);

		if ( is_array( $posts_to_delete ) && ! empty( $posts_to_delete ) ) {
			foreach ( $posts_to_delete as $post_to_delete ) {
				if ( is_array( $post_to_delete ) && ! empty( $post_to_delete ) ) {
					foreach ( $post_to_delete as $post ) {
						/**
						 * Lets get nasty here.
						 */
						wp_delete_post( $post->ID, true );
					}
				}
			}
		}

		wp_cache_flush();

		if ( is_object( $demo->data ) && ! empty( $demo->data ) ) {
			foreach ( $demo->data as $key => $file_url ) {

				$file_contents = wp_remote_retrieve_body( wp_remote_get( $file_url ) );

				if ( ! $file_contents ) {
					continue;
				}

				switch ( $key ) {
					case 'contents':
						$this->_demo_data_contents( $file_contents, $file_url );
						break;

					default:
						break;
				}
			}
		}

		/**
		 * Normalize navigation menu items after contents import
		 */
		$navigations = get_posts(
			array(
				'post_type' => 'wp_navigation',
			)
		);

		$home_url = home_url();

		if ( is_array( $navigations ) && ! empty( $navigations ) ) {
			foreach ( $navigations as $navigation ) {

				preg_match_all( '/"url":"(.*?)"/', $navigation->post_content, $matches );

				$patternlib_urls = array();

				if ( is_array( $matches[1] ) && ! empty( $matches[1] ) ) {

					foreach ( $matches[1] as $url ) {

						$url_parts = wp_parse_url( $url );

						if ( empty( $url_parts['host'] ) ) {
							continue;
						}

						if ( false !== strpos( $url_parts['host'], 'omnipressteam.com' ) ) {
							continue;
						}

						$path_segments = explode( '/', trim( $url_parts['path'], '/' ), 2 );

						$patternlib_urls = array(
							'http' . '://' . $url_parts['host'] . '/' . $path_segments[0],
							'https' . '://' . $url_parts['host'] . '/' . $path_segments[0],
						);

					}

					wp_update_post(
						array(
							'ID'           => $navigation->ID,
							'post_content' => str_replace( $patternlib_urls, $home_url, $navigation->post_content ),
						)
					);

				}
			}
		}

		/**
		 * This? Well... I am kinda ashamed to explain it hehe...
		 *
		 * But anyway, we are converting default "Hello World" posts status to draft cuz you know, ASTHETICS?
		 * This way, user won't see the Hello World post ( which is without the featured image or valid contents in most cases ) while viewing the website.
		 */
		$helloworld_posts = get_posts(
			array(
				'post_type' => 'post',
				'title'     => 'Hello world!',
			)
		);
		if ( is_array( $helloworld_posts ) && ! empty( $helloworld_posts ) ) {
			foreach ( $helloworld_posts as $helloworld_post ) {

				wp_update_post(
					array(
						'ID'          => $helloworld_post->ID,
						'post_status' => 'draft',
					)
				);
			}
		}
	}

	public function set_templates( $key ) {
		$demo = $this->get_by_keys( $key );

		if ( empty( $demo->templates ) ) {
			return;
		}

		$active_theme    = get_stylesheet();
		$demo_templates  = $demo->templates;
		$suggested_theme = $demo->theme;

		$current_templates = get_block_templates();
		$current_templates = wp_list_pluck( $current_templates, 'id', 'slug' );

		if ( is_object( $demo_templates ) && ! empty( $demo_templates ) ) {
			foreach ( $demo_templates as $slug => $content ) {

				$request = new \WP_REST_Request( 'POST', '/wp/v2/templates/' . $current_templates[ $slug ] );

				$request_get = new \WP_REST_Request( 'GET', '/wp/v2/templates' );

				$result = rest_do_request( $request_get );

				$templates_data = null;

				if ( ! $result->is_error() ) {
					$templates_data = $result->get_data();
				} else {
					error_log( 'Error occurred' ); // phpcs:ignore
				}

				$request->set_body_params(
					array(
						'slug'    => $slug,
						'content' => str_replace( $suggested_theme, $active_theme, $content ),
					)
				);

				$request->set_body_params(
					array(
						'slug'    => $slug,
						'content' => str_replace( $suggested_theme, $active_theme, $content ),
					)
				);

				rest_do_request( $request );
			}
		}
	}

	/**
	 * It will create and update template parts.
	 *
	 * @param  string $key demo key.
	 * @return void
	 */
	public function set_template_parts( string $key ): void {
		$demo = $this->get_by_keys( $key );

		if ( empty( $demo->parts ) ) {
			return;
		}

		$active_theme        = get_stylesheet();
		$suggested_theme     = $demo->theme;
		$demo_template_parts = $demo->parts;

		$current_template_parts = get_block_templates( array(), 'wp_template_part' );
		$current_template_parts = wp_list_pluck( $current_template_parts, 'id', 'slug' );

		if ( is_object( $demo_template_parts ) && ! empty( $demo_template_parts ) ) {
			foreach ( $demo_template_parts as $slug => $content ) {
				/**
				 * Felt cute about this block of code. Might change later LOL :P
				 */
				if ( false !== strpos( $content, 'wp:navigation' ) ) {

					$navigation = get_posts(
						array(
							'post_type'   => 'wp_navigation',
							'numberposts' => 1,
						)
					);

					if ( ! empty( $navigation[0] ) ) {
						$content = preg_replace( '/("ref":)\d+/', '${1}' . $navigation[0]->ID, $content );
					} else {
						$content = preg_replace( '/"ref":\d+,\s*/', '', $content );
					}
				}

				$request   = null;
				$has_parts = $current_template_parts[ $slug ] ?? false;
				$params    = null;

				if ( $has_parts ) {
					$request = new \WP_REST_Request( 'POST', '/wp/v2/template-parts/' . $current_template_parts[ $slug ] );
					$params  = array(
						'slug'    => $slug,
						'type'    => 'wp_template_part',
						'content' => str_replace( $suggested_theme, $active_theme, $content ),
					);

				} else {
					$request = new \WP_REST_Request( 'POST', '/wp/v2/template-parts' );
					$params  = array(
						'slug'    => $slug,
						'theme'   => $active_theme,
						'type'    => 'wp_template_part',
						'content' => str_replace( $suggested_theme, $active_theme, $content ),
					);
				}

				$request->set_body_params( $params );
				rest_do_request( $request );
			}
		}
	}
}
