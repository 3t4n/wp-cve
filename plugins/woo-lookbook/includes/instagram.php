<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VillaTheme_Instagram' ) ) {

	/**
	 * Create Import Image from Instagram
	 * Class VillaTheme_Instagram
	 */
	class VillaTheme_Instagram {
		protected $data = array();
		protected $setting;
		public $fb;

		public function __construct() {
			add_action( 'admin_init', array( $this, 'save_fb_info' ) );
			$this->setting = new WOO_F_LOOKBOOK_Data();
			$this->fb      = $this->fb_connect();
		}

		public function save_fb_info() {
			if ( ! empty( $_GET['code'] ) ) {
				$link_call_back = add_query_arg( array( 'post_type' => 'woocommerce-lookbook', ), admin_url( 'edit.php' ) );

				$link_call_back = isset( $_GET['page'] ) && $_GET['page'] == 'woocommerce-lookbook-settings' ? add_query_arg( array( 'page' => 'woocommerce-lookbook-settings#/instagram' ), $link_call_back ) : $link_call_back;
				$token          = $this->get_token( $link_call_back );
				$user_token     = $this->extoken( $token );

				if ( $user_token == "error" ) {
					wp_safe_redirect( $link_call_back );
				} else {
					$current_data = get_option( 'woo_lookbook_params' );
					$list_page    = $this->Get_List_Page( $user_token );
					if ( ! empty( $list_page ) && is_array( $list_page ) ) {
						if ( isset( $list_page['accounts'] ) && is_array( $list_page['accounts'] ) ) {
							$current_data['ins_page_id']      = $list_page['accounts'][0]['id'];
							$current_data['ins_access_token'] = $list_page['accounts'][0]['access_token'];
							update_option( 'woo_lookbook_params', $current_data );
						}
					}
				}
				wp_safe_redirect( $link_call_back );
				exit();
			}
		}

		/**
		 * Import Lookbook
		 * @return bool
		 */
		public function import( $cache = true ) {
			$this->data = get_transient( 'wlb_instagram_data' );

			if ( ! $this->data || ! $cache ) {
				$this->get();
				if ( is_array( $this->data ) && count( $this->data ) ) {
					set_transient( 'wlb_instagram_data', $this->data,86400 );
				} else {
					return false;
				}
			}

			$post_status = 'pending';

			foreach ( $this->data as $image ) {
				$shortcode = str_replace( '/', '', str_replace( 'https://www.instagram.com/p/', '', $image['permalink'] ) );
				$post_id   = $this->check_duplicate( $shortcode );
				if (  ! $post_id ) {
					$thumb_id = $this->upload_image( $image['media_url'], $shortcode );
					if ( ! $thumb_id ) {
						return false;
					}
					$post_arg = array( // Set up the basic post data to insert for our lookbook
						'post_status' => $post_status,
						'post_title'  => $image['caption'] ?? '',
						'post_type'   => 'woocommerce-lookbook',
						'post_date'   => $image['timestamp'] ?? ''
					);

					$post_id = wp_insert_post( $post_arg ); // Insert the post returning the new post id

					if ( ! $post_id ) {
						return false;
					}

					$metabox = array(
						'image'     => $thumb_id,
						'instagram' => "1",
						'code'      => $shortcode,
						'date'      => $image['timestamp'] ?? '',
						'comments'  => $image['comments_count'] ?? 0,
						'likes'     => $image['like_count'] ?? 0,
					);
					update_post_meta( $post_id, 'wlb_params', $metabox );
				} elseif ( $post_id && $this->setting->get_ins_duplicate() == 2 ) {
					$metabox             = get_post_meta( $post_id, 'wlb_params', true );
					$metabox['comments'] = $image['comments_count'] ?? 0;
					$metabox['likes']    = $image['like_count'] ?? 0;
					update_post_meta( $post_id, 'wlb_params', $metabox );
				}
			}
			if ( wp_doing_ajax() ) {
				wp_send_json_success();
			}
		}

		/**
		 * Check post duplicate
		 *
		 * @param $code
		 *
		 * @return bool
		 */
		protected function check_duplicate( $code ) {
			$args      = array(
				'post_type'   => 'woocommerce-lookbook',
				'post_status' => array(
					'any',
					'auto-draft',
					'trash', // - post is in trashbin (available with Version 2.9).
				),
				'meta_query'  => array(
					array(
						'key'     => 'wlb_params',
						'value'   => $code,
						'compare' => 'LIKE',
					),
				)
			);
			$the_query = new WP_Query( $args );


			// The Loop
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					return get_the_ID();
				}
				wp_reset_postdata();

			} else {
				return false;
			}

		}

		/**
		 * Get image instagram link
		 * @return bool
		 */
		public function get() {
			$access_token = $this->setting->get_access_token();
			if ( ! $access_token ) {
				if ( wp_doing_ajax() ) {
					wp_send_json_error( __( 'Access token is not exist', 'woocommerce-lookbook' ) );
				}

				return;
			}

			$id = $this->setting->get_fb_page_id();
			if ( ! $id ) {
				if ( wp_doing_ajax() ) {
					wp_send_json_error( __( 'Instagram id is not exist', 'woocommerce-lookbook' ) );
				}

				return;
			}

			if ( $this->check_token_live( $access_token ) ) {
				$fb_ins_id = $this->fb_get( $id, $access_token, 'instagram_business_account', 1 );
				$fb_ins_id = $fb_ins_id['instagram_business_account']['id'] ?? '';
				if ( ! $fb_ins_id ) {
					return;
				}
				$fields = 'caption,like_count,media_url,comments_count,permalink,username,timestamp';
				$limit  = 8;
				$result = $this->fb_get( $fb_ins_id, $access_token, $fields, $limit, 'edge', 'media' );
				if ( ! empty( $result ) ) {
					$this->data = $result;
				}
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_error( __( 'Access token is expired', 'woocommerce-lookbook' ) );
				}
			}
		}

		public function fb_get( $id, $access_token, $fields, $limit = '', $graph = 'node', $type = '' ) {

			$limit = $limit ? '&limit=' . $limit : '';
			$type  = $type ? '/' . $type : '';
			try {
				// Returns a `FacebookFacebookResponse` object
				$response = $this->fb->get( $id . $type . '?fields=' . $fields . $limit, $access_token );

			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}

			$graph_response = $graph == 'node' ? $response->getGraphNode()->asArray() : $response->getGraphEdge()->asArray();

			return $graph_response;
		}

		public function fb_connect() {
			$app_id     = $this->setting->get_ins_client_id();
			$app_secret = $this->setting->get_ins_client_secret();
			if ( ! ( $app_id && $app_secret ) ) {
//				$link_call_back = add_query_arg( array( 'post_type' => 'woocommerce-lookbook', 'page' => 'woocommerce-lookbook-settings#/instagram' ), admin_url( 'edit.php' ) );
//				wp_safe_redirect( $link_call_back );
//				exit;\
				return false;
			}
			try {
				$fb = new \Facebook\Facebook( [
					'app_id'                => $app_id,
					'app_secret'            => $app_secret,
					'default_graph_version' => 'v5.0',
				] );
			} catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
				return $e;
			} catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
				return $e;
			}

			return $fb;
		}

		public function get_link_login( $link_callback, $permissions = '' ) {
			if ( ! $this->fb ) {
				return false;
			}
			if ( empty( $link_callback ) ) {
				return array( 'status' => false, 'msg' => 'Link Callback not found!' );
			}
			$helper = $this->fb->getRedirectLoginHelper();
			if ( empty( $permissions ) ) {
				$permissions = [ 'email' ];
			}
			$loginUrl = $helper->getLoginUrl( $link_callback, $permissions );

			return $loginUrl;
		}

		public function get_token( $link_call_back ) {
			if ( ! $this->fb ) {
				return false;
			}
			$helper = $this->fb->getRedirectLoginHelper();
			if ( isset( $_GET['state'] ) ) {
				$helper->getPersistentDataHandler()->set( 'state', $_GET['state'] );
			}
			try {
				$accessToken = $helper->getAccessToken( $link_call_back );

				return $accessToken;
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				// When Graph returns an error
				//return $e;
				return $e->getMessage();
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				//return $e;
				return $e->getMessage();
			}
		}

		public function extoken( $token ) {
			if ( ! $this->fb ) {
				return false;
			}
			try {
				$extoken  = $this->fb->getOAuth2Client();
				$ex_token = $extoken->getLongLivedAccessToken( $token );

				return $ex_token->getValue();
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				return $e->getMessage();
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				return $e->getMessage();
			}
		}

		public function Get_List_Page( $token ) {
			if ( ! $this->fb ) {
				return false;
			}
			try {
				$response = $this->fb->get( '/me?fields=accounts.limit(9999){id,access_token}', $token );   // only get picture, name, id , access_token
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				return $e->getMessage();
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				return $e;
			}
			$user = $response->getGraphObject()->asArray();

			return $user;
		}


		/**
		 * Get data
		 *
		 * @param $url
		 *
		 * @return array|bool|mixed|null|object|WP_Error
		 */
		protected function remote( $url, $api = false ) {
			$request = wp_remote_get(
				$url, array(
					'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36',
					'timeout'    => 20,
				)
			);
			if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
				if ( $api ) {
					$html = $request['body'];

					return json_decode( $html, true );
				} else {
					$html = $request['body'];
					$html = str_replace( "\n", ' ', $html );
					$html = str_replace( "\t", ' ', $html );
					$html = str_replace( "\r", ' ', $html );
					$html = str_replace( "\0", ' ', $html );
					preg_match_all( '/(_sharedData\s=)+(.+?);<\/script>/i', $html, $result );
					if ( isset( $result[2][0] ) ) {
						$request = trim( $result[2][0] );
					} else {
						return false;
					}
					if ( $request ) {
						$request = json_decode( $request, true );
					} else {
						return false;
					}

					return $request;
				}

			} else {
				return false;
			}
		}

		/**
		 * Upload image
		 *
		 * @param $url
		 *
		 * @return int|object
		 */
		protected function upload_image( $url, $desc = '' ) {
			//add product image:
			//require_once 'inc/add_pic.php';
			require_once( ABSPATH . 'wp-includes/pluggable.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$thumb_url = $url;

			// Download file to temp location
			$tmp = download_url( $thumb_url );
			// Set variables for storage
			// fix file name for query strings
			preg_match( '/[^\?]+\.(jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG)/', $thumb_url, $matches );
			$file_array['name']     = basename( $matches[0] );
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink( $file_array['tmp_name'] );
			} else {

			}

			//use media_handle_sideload to upload img:
			$thumbid = media_handle_sideload( $file_array, '', $desc );
			//			print_r($thumbid);
			// If error storing permanently, unlink
			if ( is_wp_error( $thumbid ) ) {
				@unlink( $file_array['tmp_name'] );
			} else {

			}

			return $thumbid;
		}

		public function check_token_live( $token ) {
			if ( ! $this->fb ) {
				return false;
			}
			try {
				$extoken  = $this->fb->getOAuth2Client();
				$ex_token = $extoken->debugToken( $token );

				return $ex_token->getIsValid();
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				return $e->getMessage();
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				return $e->getMessage();
			}
		}
	}


}
new  VillaTheme_Instagram();
