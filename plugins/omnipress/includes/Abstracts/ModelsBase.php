<?php
/**
 * Patterns and Demos models base class.
 *
 * @package Omnipress\Abstract
 */

namespace Omnipress\Abstracts;

use Omnipress\Helpers;
use Omnipress\Transient;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ModelsBase {

	/**
	 * Current user id.
	 *
	 * @var integer
	 */
	protected $user_id = 0;

	/**
	 * Model type.
	 *
	 * @var string patterns | demos
	 */
	protected $model_type = '';

	/**
	 * Transient class object.
	 *
	 * @var Transient
	 */
	protected $transient;

	/**
	 * Expiration of transient.
	 *
	 * @var integer
	 */
	protected $expiration = WEEK_IN_SECONDS * 2;

	/**
	 * Cached data.
	 *
	 * @var object
	 */
	protected $data;

	/**
	 * Class construct.
	 */
	public function __construct() {

		$this->setup_environment();
		$this->user_id = get_current_user_id();

		$this->transient = new Transient( "{$this->model_type}_data" );
		$this->set();
	}

	/**
	 * Setup required variables.
	 *
	 * @return void
	 * @since 1.1.5
	 */
	protected function setup_environment() {

		@ini_set( 'max_input_time', '-1' );

		if ( Helpers::is_php_func_enabled( 'set_time_limit' ) ) {
			@set_time_limit( 0 );
			@ini_set( 'max_execution_time', 0 );
		}

		wp_raise_memory_limit();
	}

	/**
	 * Set data.
	 *
	 * @return void
	 */
	protected function set() {
		$data = $this->transient->get();

		if ( ! $data ) {
			return;
		}

		/**
		 * Start normalizing links.
		 */

		$regex       = '/href="[^"]+"/';
		$replacement = 'href="#"';

		$home_url = home_url();

		switch ( $this->model_type ) {
			case 'patterns':
				$patterns = $data->patterns;

				if ( is_array( $patterns ) && ! empty( $patterns ) ) {
					foreach ( $patterns as $key => $pattern ) {
						$content = $pattern->content;

						if ( ! $content ) {
							continue;
						}

						$data->patterns[ $key ]->content = preg_replace( $regex, $replacement, $content );

					}
				}
				break;

			case 'demos':
				$demos = $data->demos;

				if ( is_array( $demos ) && ! empty( $demos ) ) {
					foreach ( $demos as $demo_key => $demo ) {
						$pages     = $demo->pages;
						$parts     = $demo->parts;
						$templates = $demo->templates;

						if ( is_array( $pages ) && ! empty( $pages ) ) {
							foreach ( $pages as $key => $page ) {
								$content = $page->content;

								if ( ! $content ) {
									continue;
								}

								preg_match_all( $regex, $content, $matches );

								if ( empty( $matches[1] ) ) {
									continue;
								}

								$data->demos[ $demo_key ]->pages[ $key ]->content = preg_replace( $regex, $replacement, $content );

							}
						}

						if ( is_object( $parts ) && ! empty( $parts ) ) {
							foreach ( $parts as $key => $part ) {

								if ( ! $part ) {
									continue;
								}

								/**
								 * Normalize urls and links.
								 */

								$data->demos[ $demo_key ]->parts->{$key} = preg_replace_callback(
									$regex,
									function ( $matches ) use ( $home_url ) {
										if ( false !== strpos( $matches[0], 'omnipressteam.com' ) ) {
											return $matches[0];
										}

										$pattern = '/href="([^"]+)"/i';

										preg_match( $pattern, $matches[0], $href_matches );

										$url_parts = wp_parse_url( $href_matches[1] );

										if ( empty( $url_parts['host'] ) ) {
											return 'href="#"';
										}

										$path_segments = explode( '/', trim( $url_parts['path'], '/' ), 2 );

										$patternlib_urls = array(
											'http' . '://' . $url_parts['host'] . '/' . $path_segments[0],
											'https' . '://' . $url_parts['host'] . '/' . $path_segments[0],
										);

										return str_replace( $patternlib_urls, $home_url, $matches[0] );
									},
									$part
								);
							}
						}

						if ( is_object( $templates ) && ! empty( $templates ) ) {
							foreach ( $templates as $key => $template ) {

								if ( ! $template ) {
									continue;
								}

								$data->demos[ $demo_key ]->templates->{$key} = preg_replace( $regex, $replacement, $template );

							}
						}
					}
				}

				break;

			default:
				break;
		}

		$this->data = apply_filters( 'omnipress_filter_model_data', $data, $this->model_type );
	}

	/**
	 * Fetch latest data from server and set transient.
	 *
	 * @return void
	 */
	protected function fetch() {

		$url_base = defined( 'OMNIPRESS_API_TEST_URL' ) ? OMNIPRESS_API_TEST_URL : 'https://api.omnipressteam.com/wp-json/omnipress-api';

		$args = apply_filters( 'omnipress_modelbase_remote_args', array(), $this->model_type );
		$body = wp_remote_retrieve_body( wp_remote_post( "{$url_base}/{$this->model_type}", $args ) );
		$data = json_decode( $body );

		if ( $data ) {
			$this->transient->delete();
			$this->transient->set( $data, $this->expiration );
		}
	}

	/**
	 * Sync latest data from the server.
	 *
	 * @return void
	 */
	public function sync() {
		$this->fetch();
		$this->set();
	}

	public function filter( $key ) {
		if ( ! $key ) {
			$this->set();
			return;
		}

		$data = $this->data;

		if ( empty( $data->{$this->model_type} ) ) {
			return;
		}

		$filter = array();

		if ( is_array( $data->{$this->model_type} ) && ! empty( $data->{$this->model_type} ) ) {
			foreach ( $data->{$this->model_type} as $demo_pattern ) {
				if ( empty( $demo_pattern->category->key ) ) {
					continue;
				}

				if ( $demo_pattern->category->key !== $key ) {
					continue;
				}

				$filter[] = $demo_pattern;
			}
		}

		$this->data->{$this->model_type} = $filter;
	}

	/**
	 * Get data.
	 */
	public function get() {
		if ( ! $this->data ) {
			$this->sync();
		}

		if ( ! is_object( $this->data ) ) {
			$this->data = (object) array();
		}

		$this->data->favorites = $this->get_favorites();

		return $this->data;
	}

	/**
	 * Returns demos or patterns by their unique identifier keys.
	 *
	 * @param string $key Unique identifier of demos.
	 *
	 * @return object[]|object Returns array of objects or single object if $key is passed.
	 */
	public function get_by_keys( $key = null ) {
		$data = array_combine( array_keys( wp_list_pluck( $this->data->{$this->model_type}, 'key', 'key' ) ), array_values( $this->data->{$this->model_type} ) );

		if ( is_null( $key ) ) {
			return $data;
		}

		return isset( $data[ $key ] ) ? $data[ $key ] : null;
	}

	/**
	 * Set `$this->model_type` to favorite.
	 *
	 * @param string $key Unique identifier of demos.
	 */
	public function set_favorite( $key ) {

		if ( ! $key ) {
			return;
		}

		$favorites = $this->get_favorites();

		$favorites[ $key ] = $this->get_by_keys( $key );

		update_user_meta( $this->user_id, "omnipress_favorite_{$this->model_type}", $favorites );
	}

	public function remove_favorite( $key ) {

		if ( ! $key ) {
			return;
		}

		$favorites = $this->get_favorites();

		if ( ! isset( $favorites[ $key ] ) ) {
			return;
		}

		unset( $favorites[ $key ] );

		update_user_meta( $this->user_id, "omnipress_favorite_{$this->model_type}", $favorites );
	}

	/**
	 * Returns user favorites.
	 *
	 * @return array
	 */
	public function get_favorites() {
		$favorites = get_user_meta( $this->user_id, "omnipress_favorite_{$this->model_type}", true );

		if ( ! $favorites ) {
			$favorites = array();
		}

		return $favorites;
	}
}
