<?php
/**
 * This file contains the class that defines REST API endpoints for
 * retrieving meta information of shared links.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Shared_Link_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Shared_Link_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Shared_Link_REST_Controller the single instance of this class.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * Hooks into WordPress.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}//end init()

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		register_rest_route(
			nelio_content()->rest_namespace,
			'/shared-link',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_shared_link_meta_data' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'url' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
			)
		);

	}//end register_routes()

	/**
	 * Gets meta data from the given URL.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_shared_link_meta_data( $request ) {

		$url = $request->get_param( 'url' );

		$result = array(
			'responseCode' => 0,
			'author'       => '',
			'date'         => '',
			'email'        => '',
			'excerpt'      => '',
			'image'        => '',
			'permalink'    => '',
			'title'        => '',
			'twitter'      => '',
		);

		// If the URL is empty, return.
		if ( empty( $url ) ) {
			return new WP_Error(
				'empty-url',
				_x( 'URL is empty.', 'text', 'nelio-content' )
			);
		}//end if

		// Let's obtain the contents of the URL.
		$aux = $this->get_page_content( $url );

		// If we couldn't load the page content, return.
		if ( empty( $aux ) ) {
			return new WP_Error(
				'internal-error',
				sprintf(
					/* translators: a URL */
					_x( 'Unable to load URL “%s”.', 'text', 'nelio-content' ),
					$url
				)
			);
		}//end if

		$result['responseCode'] = absint( $aux['responseCode'] );
		$page                   = $aux['content'];
		$page                   = preg_replace( '/\n/', '', $page );

		// If the response code is an error, return.
		if ( in_array( $result['responseCode'], array( 403, 404, 500 ), true ) ) {
			return new WP_Error(
				'internal-error',
				sprintf(
					/* translators: a URL */
					_x( 'Unable to load URL “%s”.', 'text', 'nelio-content' ),
					$url
				)
			);
		}//end if

		// If we couldn't load the page content, return.
		if ( empty( $page ) ) {
			return new WP_Error(
				'internal-error',
				sprintf(
					/* translators: a URL */
					_x( 'Unable to load URL “%s”.', 'text', 'nelio-content' ),
					$url
				)
			);
		}//end if

		$meta_tags                      = array();
		$meta_tags                      = $this->extract_meta_data_from_url( $page );
		$meta_tags['nelio-content:url'] = $url;

		// Let's populate the results object.
		// Author.
		$keys             = array( 'author', 'nelio-content:author' );
		$result['author'] = $this->get_first_option( $meta_tags, $keys, '' );

		// Date.
		$keys           = array( 'article:published_time' );
		$result['date'] = $this->get_first_option( $meta_tags, $keys, '' );

		// Email.
		$keys            = array();
		$result['email'] = $this->get_first_option( $meta_tags, $keys, '' );

		// Excerpt.
		$keys              = array( 'og:description', 'description', 'twitter:description' );
		$result['excerpt'] = $this->get_first_option( $meta_tags, $keys, '' );

		// Image.
		$keys            = array( 'og:image' );
		$result['image'] = $this->get_first_option( $meta_tags, $keys, '' );

		// Permalink.
		$keys                = array( 'og:url', 'nelio-content:url' );
		$result['permalink'] = $this->get_first_option( $meta_tags, $keys, '' );
		$result['domain']    = preg_replace( '/^https?:\/\/([^\/]+).*$/', '$1', $result['permalink'] );

		// Title.
		$keys            = array( 'og:title', 'nelio-content:title', 'twitter:title' );
		$result['title'] = $this->get_first_option( $meta_tags, $keys, '' );

		// Twitter.
		$keys              = array( 'twitter:creator' );
		$result['twitter'] = $this->get_first_option( $meta_tags, $keys, '' );
		$result['twitter'] = preg_replace( '/@|https?:\/\/twitter.com\/?/', '', $result['twitter'] );
		if ( mb_strlen( $result['twitter'] ) ) {
			$result['twitter'] = '@' . $result['twitter'];
		}//end if

		// Finally, if we weren't able to get the post's author, but we did find
		// her twitter, let's see if we can get her name by looking at her twitter
		// account.
		if ( empty( $result['author'] ) && ! empty( $result['twitter'] ) ) {
			$result['author'] = $this->get_author_from_twitter( $result['twitter'] );
		}//end if

		// Strip all HTML tags.
		foreach ( $result as $key => $value ) {
			$result[ $key ] = wp_strip_all_tags( $value );
		}//end foreach

		return new WP_REST_Response( $result, 200 );

	}//end get_shared_link_meta_data()

	private function get_page_content( $url ) {

		$result = array(
			'responseCode' => 0,
			'content'      => '',
		);

		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
			),
		);

		add_filter( 'https_ssl_verify', '__return_false' );
		$response = @wp_safe_remote_request( $url, $args ); // phpcs:ignore
		remove_filter( 'https_ssl_verify', '__return_false' );

		// If we couldn't open the page, let's return an empty result object.
		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		// If the response code is an error, return it.
		$result['responseCode'] = absint( $response['response']['code'] );
		if ( in_array( $result['responseCode'], array( 403, 404, 500 ), true ) ) {
			return $result;
		}//end if

		$page = $response['body'];

		// Fix the page encoding (if necessary).
		if ( isset( $response['headers']['content-type'] ) ) {

			$content_type = $response['headers']['content-type'];

			if ( preg_match( '/charset=([a-zA-Z0-9-]+)/i', $content_type, $matches ) ) {

				$charset = $matches[1];
				if ( stripos( $charset, 'utf' ) !== 0 ) {
					$page = mb_convert_encoding( $page, 'UTF-8', $charset );
				}//end if
			}//end if
		}//end if

		$result['content'] = $page;
		return $result;

	}//end get_page_content()

	private function extract_meta_data_from_url( $page ) {

		// First, we add the URL of the request.
		$meta_tags = array();

		// Then, we obtain the title tag.
		if ( preg_match( '/<title>([^<]*)<\/title>/i', $page, $matches ) ) {
			$meta_tags['nelio-content:title'] = wp_strip_all_tags( $matches[1] );
		}//end if

		// Next, we try to discover who the author is.
		$meta_tags['nelio-content:author'] = $this->get_author( $page );

		// Finally, we look for all meta tags. First, property/name and content.
		if ( preg_match_all( '/<meta\s+(property|name)="([^"]*)"\s+content="([^"]*)"[^>]*>/i', $page, $matches ) ) {

			$count = count( $matches[0] );
			for ( $i = 0; $i < $count; ++$i ) {
				$key               = strtolower( $matches[2][ $i ] );
				$meta_tags[ $key ] = $matches[3][ $i ];
			}//end for
		}//end if

		// Then, content and property/name.
		if ( preg_match_all( '/<meta\s+content="([^"]*)"\s+(property|name)="([^"]*)"[^>]*>/i', $page, $matches ) ) {

			$count = count( $matches[0] );
			for ( $i = 0; $i < $count; ++$i ) {
				$key               = strtolower( $matches[3][ $i ] );
				$meta_tags[ $key ] = $matches[1][ $i ];
			}//end for
		}//end if

		return $meta_tags;

	}//end extract_meta_data_from_url()

	private function get_author( $page ) {

		// First of all, we look for the `vcard author` name.
		if ( preg_match( '/(\bvcard\b[^"]+\bauthor\b|\bauthor\b[^"]+\bvcard\b).{0,200}\bfn\b(.{30,200})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[2], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Then, we try to look for a schema.org or data-vocabulary.org author name.
		if ( preg_match( '/https?:\/\/(data-vocabulary|schema).org\/Person.{0,200}\bname\b(.{3,200})/i', $page, $matches ) ) {

			$match = $matches[2];
			if ( preg_match( '/>([^<]{3,40})</i', $match, $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if

			if ( preg_match( '/content="([^"]{3,40})"/', $match, $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Next, we try to discover the author using WordPress' default class name.
		if ( preg_match( '/\bauthor-name\b(.{3,200})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[1], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Next, we look for the "attributionNameClick" property.
		if ( preg_match( '/\battributionNameClick\b(.{3,150})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[1], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Finally, we try to discover the author looking at a "rel author" link.
		if ( preg_match( '/<a.{0,200}rel=.author.(.{3,200})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[1], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// If everything failed, let's return the empty string.
		return '';

	}//end get_author()

	private function get_author_from_twitter( $username ) {

		// Result variable.
		$author = '';

		// Get $username's twitter profile page.
		$username = str_replace( '@', '', $username );
		$aux      = $this->get_page_content( 'https://twitter.com/' . $username );

		// If we couldn't load the page content, return.
		if ( empty( $aux ) ) {
			return $author;
		}//end if

		// If the response code is an error, return.
		if ( in_array( $aux['responseCode'], array( 403, 404, 500 ), true ) ) {
			return $author;
		}//end if

		// If we were able to load the page, let's loook for the author's name in
		// there.
		$page = $aux['content'];

		if ( preg_match( '/data-screen-name="' . $username . '".+data-name="([^"]+)"/i', $page, $matches ) ) {
			$author = trim( $matches[1] );
		}//end if

		if ( empty( $author ) ) {
			if ( preg_match( '/<title>([^<]*)<\/title>/i', $page, $matches ) ) {
				$author = trim( wp_strip_all_tags( $matches[1] ) );
			}//end if
		}//end if

		return $author;

	}//end get_author_from_twitter()

	/**
	 * Given an ordered list of keys, returns the value of the first key that
	 * has a value in the array.
	 *
	 * @param array        $array       A list of key-value pairs.
	 * @param array|string $key_options An ordered list of keys.
	 * @param mixed        $default     Optional. The value that has to be
	 *                         returned if none of the given keys appear in
	 *                         the given array. Default: `false`.
	 *
	 * @return mixed The value of the first key in `$key_options` that appears
	 *               in $array.
	 */
	private function get_first_option( $array, $key_options, $default = false ) {
		if ( ! is_array( $key_options ) ) {
			$key_options = array( $key_options );
		}//end if

		foreach ( $key_options as $key ) {
			if ( isset( $array[ $key ] ) ) {
				return $array[ $key ];
			}//end if
		}//end foreach

		return $default;
	}//end get_first_option()
}//end class
