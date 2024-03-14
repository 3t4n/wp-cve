<?php


/**
 * class DokanStore
 *
 * @link       https://appcheap.io
 * @since      1.0.13
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Vendor;

defined( 'ABSPATH' ) || exit;

use WC_Countries;
use WP_REST_Controller;
use WP_REST_Server;
use WP_User_Query;

class WCMpStore extends BaseStore {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'dc_vendor';

	public function get_stores( $request ) {
		$params = $request->get_params();

		$args = array(
			'number' => $params['per_page'],
			'offset' => ( $params['page'] - 1 ) * $params['per_page']
		);

		if ( ! empty( $params['orderby'] ) ) {
			$args['orderby'] = $params['orderby'];
		}

		if ( ! empty( $params['order'] ) ) {
			$args['order'] = $params['order'];
		}

		if ( ! empty( $params['status'] ) ) {
			if ( $params['status'] == 'pending' ) {
				$args['role'] = 'dc_pending_vendor';
			} else {
				$args['role'] = $this->post_type;
			}
		}

		$object   = array();
		$response = array();

		$args       = wp_parse_args( $args, array(
			'role'    => 'dc_vendor',
			'fields'  => 'ids',
			'orderby' => 'registered',
			'order'   => 'ASC'
		) );
		$user_query = new WP_User_Query( $args );
		if ( ! empty( $user_query->results ) ) {
			foreach ( $user_query->results as $vendor_id ) {
				$vendor   = get_wcmp_vendor( $vendor_id );
				$is_block = get_user_meta( $vendor->id, '_vendor_turn_off', true );
				if ( $is_block ) {
					continue;
				}
				$vendor_data = $this->prepare_item_for_response( $vendor, $request );
				$object[]    = $this->prepare_response_for_collection( $vendor_data );
			}

			$per_page    = (int) ( ! empty( $request['per_page'] ) ? $request['per_page'] : 10 );
			$page        = (int) ( ! empty( $request['page'] ) ? $request['page'] : 1 );
			$total_count = $user_query->get_total();
			$max_pages   = ceil( $total_count / $per_page );

			$response = rest_ensure_response( $object );

			$response->header( 'X-WP-Total', $total_count );
			$response->header( 'X-WP-TotalPages', (int) $max_pages );

			$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );

			if ( $page > 1 ) {
				$prev_page = $page - 1;
				if ( $prev_page > $max_pages ) {
					$prev_page = $max_pages;
				}
				$prev_link = add_query_arg( 'page', $prev_page, $base );
				$response->link_header( 'prev', $prev_link );
			}

			if ( $max_pages > $page ) {
				$next_page = $page + 1;
				$next_link = add_query_arg( 'page', $next_page, $base );
				$response->link_header( 'next', $next_link );
			}
		}

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WC_Data $object Object data.
		 * @param WP_REST_Request $request Request object.
		 */
		return apply_filters( "wcmp_rest_prepare_{$this->post_type}_object", $response, $object, $request );
	}

	/**
	 * Prepare a single vendor output for response
	 *
	 * @param object $method
	 * @param WP_REST_Request $request Request object.
	 * @param array $additional_fields (optional)
	 *
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response( $method, $request, $additional_fields = [] ) {
		$vendor_term_id     = get_user_meta( $method->id, '_vendor_term_id', true );
		$vendor_review_info = wcmp_get_vendor_review_info( $vendor_term_id );
		$avg_rating         = number_format( floatval( $vendor_review_info['avg_rating'] ), 1 );
		$rating_count       = $vendor_review_info['total_rating'];

		$address = array(
			'address_1' => $method->address_1,
			'address_2' => $method->address_2,
			'city'      => $method->city,
			'state'     => $method->state,
			'country'   => $method->country,
			'postcode'  => $method->postcode,
			'phone'     => $method->phone,
		);

		$avatar = $method->banner ? wp_get_attachment_url($method->image) : '';
		$banner = $method->banner ? wp_get_attachment_url($method->banner) : '';

		$data = array(
			'id'               => intval( $method->id ),
			'store_name'       => $method->page_title,
			'first_name'       => get_user_meta( $method->id, 'first_name', true ),
			'last_name'        => get_user_meta( $method->id, 'last_name', true ),
			'phone'            => '',
			'show_email'       => true,
			'email'            => $method->user_data->data->user_email,
			'vendor_address'   => $this->get_address_string( $method ),
			'banner'           => $banner,
			'mobile_banner'    => $banner,
			'list_banner'      => $banner,
			'gravatar'         => $avatar,
			'shop_description' => $method->description,
			'social'           => array(
				'facebook'    => $method->fb_profile,
				'twitter'     => $method->twitter_profile,
				'google_plus' => $method->google_plus_profile,
				'linkdin'     => $method->linkdin_profile,
				'youtube'     => $method->youtube,
				'instagram'   => $method->instagram,
			),
			'address'          => $address,
			'customer_support' => '',
			'featured'         => false,
			'rating'           => array(
				'rating' => intval( $avg_rating ),
				'count'  => intval( $rating_count ),
				'avg'    => intval( $avg_rating ),
			)
		);

		$vendor_object = apply_filters( "wcmp_rest_prepare_vendor_object_args", $data, $method, $request );

		$vendor_object = array_merge( $vendor_object, $additional_fields );
		$response      = rest_ensure_response( $vendor_object );
		$response->add_links( $this->prepare_links( $vendor_object, $request ) );

		return apply_filters( "wcmp_rest_prepare_{$this->post_type}_method", $response, $method, $request );
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param WC_Data $object Object data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array                   Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

		$links = array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $object['id'] ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			)
		);

		return $links;
	}

	/**
	 * Get the shop address
	 *
	 * @return array
	 */
	public function get_address_string( $store ) {

		$address = $store->address;
		$addr_1  = $store->address1;
		$addr_2  = $store->address_2;
		$city    = $store->city;
		$zip     = $store->zip;
		$country = $store->country;
		$state   = $store->state;

		// Country -> States
		$country_obj  = new WC_Countries();
		$countries    = $country_obj->countries;
		$states       = $country_obj->states;
		$country_name = '';
		$state_name   = '';
		if ( $country ) {
			$country_name = $country;
		}
		if ( $state ) {
			$state_name = $state;
		}
		if ( $country && isset( $countries[ $country ] ) ) {
			$country_name = $countries[ $country ];
		}
		if ( $state && isset( $states[ $country ] ) && is_array( $states[ $country ] ) ) {
			$state_name = isset( $states[ $country ][ $state ] ) ? $states[ $country ][ $state ] : '';
		}

		$store_address = '';
		if ( $addr_1 ) {
			$store_address .= $addr_1 . ", ";
		}
		if ( $addr_2 ) {
			$store_address .= $addr_2 . ", ";
		}
		if ( $city ) {
			$store_address .= $city . ", ";
		}
		if ( $state_name ) {
			$store_address .= $state_name;
		}
		if ( $country_name ) {
			$store_address .= " " . $country_name;
		}
		if ( $zip ) {
			$store_address .= " - " . $zip;
		}

		$store_address = str_replace( '"', '&quot;', $store_address );

		return apply_filters( 'wcpm_store_address_string', $store_address, $store );

	}
}
