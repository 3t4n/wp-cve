<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Components\Error;
use Photonic_Plugin\Components\Pagination;
use Photonic_Plugin\Components\Photo;
use Photonic_Plugin\Components\Photo_List;
use Photonic_Plugin\Components\Single_Photo;
use WP_Error;

require_once 'OAuth2.php';
require_once 'Level_One_Module.php';

/**
 * Module to handle Instagram. Instagram uses OAuth2 authentication, and authentication is mandatory to display content.
 * Instagram first issues a short-lived token. This is exchanged for a long-lived token right on an external site.
 * The long-lived token is valid for 60 days, though, to be safe, Photonic swaps it out for a new token if there are less then
 * 30 days of validity left on it.
 */
class Instagram extends OAuth2 implements Level_One_Module {
	public $response_type;
	public $scope;
	public $cached_token;
	public $field_list;
	public $token_valid;
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
		global $photonic_instagram_disable_title_link, $photonic_instagram_access_token;
		$this->provider      = 'instagram';
		$this->oauth_version = '2.0';
		$this->response_type = 'token';
		$this->scope         = 'basic';
		$this->api_key       = 'not-required-but-not-empty';
		$this->api_secret    = 'not-required-but-not-empty';
		$this->token         = $photonic_instagram_access_token; // Used in the Authentication page to see if a token is set
		$this->access_token  = $photonic_instagram_access_token; // Used everywhere else. This is updated later based on the cached value in memory
		$this->field_list    = 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,username';
		$this->soon_limit    = 30;

		$this->link_lightbox_title = empty($photonic_instagram_disable_title_link);
		$this->doc_links           = [
			'general' => 'https://aquoid.com/plugins/photonic/instagram/',
		];
		$this->authenticate($photonic_instagram_access_token);

		$this->cached_token = $this->get_cached_token();
		if (!empty($this->cached_token)) {
			$this->access_token = $this->cached_token['oauth_token'];
		}
	}

	/**
	 * Main function that fetches the images associated with the shortcode.
	 *
	 * @param array $attr
	 * @return array
	 */
	public function get_gallery_images($attr = []): array {
		global $photonic_instagram_media, $photonic_thumbnail_style, $photonic_instagram_carousel_caption_position;
		$this->push_to_stack('Get Gallery Images');

		$attr = array_merge(
			$this->common_parameters,
			[
				// Common overrides ...
				'caption'           => 'title',

				// Instagram-specific ...
				'count'             => 25,
				'layout'            => (empty($photonic_thumbnail_style) || 'square' === $photonic_thumbnail_style) ? 'random' : $photonic_thumbnail_style,
				'media'             => $photonic_instagram_media,
				'embed_type'        => 'embed',
				'carousel_handling' => 'expand',
				'carousel_caption'  => $photonic_instagram_carousel_caption_position
			],
			$attr
		);

		$attr = array_map('trim', $attr);

		if (empty($this->token) || empty($this->cached_token) || !$this->token_valid) {
			$components = [new Error(esc_html__("Instagram Access Token not valid. Please reauthenticate.", 'photonic'))];
			return $components;
		}

		if (empty($attr['user_id'])) {
			$user_id = 'me';
		}
		else {
			$user_id = $attr['user_id'];
		}

		$base_url = 'https://graph.instagram.com/';

		$display_what = 'media';
		if (!empty($attr['media_id'])) {// Trumps all else. A single photo will be shown.
			$id_format = preg_match('/^[A-Z][A-Z0-9_-]+/i', $attr['media_id']) ? 'old' : 'new';
			if ('old' === $id_format) {
				$components = [new Error(sprintf(esc_html__('The %1$s used, %2$s has been deprecated by Instagram. Please regenerate the gallery with a new id.', 'photonic'), "<code>media_id</code>", esc_attr("<code>" . $attr['media_id'] . "</code>")))];
				return $components;
			}
			else {
				$query_url = $base_url . $attr['media_id'] . '?fields=' . $this->field_list;
			}
			$display_what = 'single-media';
		}
		elseif (!empty($attr['carousel'])) {
			$query_url    = $base_url . $attr['carousel'] . '?fields=' . $this->field_list;
			$display_what = 'carousel';
		}
		elseif (!empty($user_id)) {
			$query_url = $base_url . $user_id . '/media?fields=' . $this->field_list; // Doesn't matter what the other values are. User's photos will be shown.
		}
		else {
			if (empty($attr['view'])) {
				$components = [new Error(sprintf(esc_html__('The %s parameter has to be defined.', 'photonic'), '<code>view</code>'))];
				return $components;
			}
			else {
				$components = [
					new Error(sprintf(esc_html__('Malformed shortcode. Either %1$s or %2$s or %3$s has to be defined.', 'photonic'), '<code>media_id</code>', '<code>carousel</code>', '<code>view</code>'))
				];
				return $components;
			}
		}

		if (isset($attr['count'])) {
			$query_url = add_query_arg(['limit' => $attr['count']], $query_url);
		}

		if (isset($attr['after'])) {
			$query_url = add_query_arg(['after' => $attr['after']], $query_url);
		}

		$components = $this->make_call($query_url, $display_what, $attr);
		$this->pop_from_stack();

		if (!empty($this->stack_trace[$this->gallery_index])) {
			$components[] = $this->stack_trace[$this->gallery_index];
		}
		return $components;
	}

	protected function make_call($query_url, $display_what, array &$shortcode_attr) {
		$this->push_to_stack("Make call $query_url");

		$components = [];

		if (!empty($this->cached_token) && $this->token_valid) {
			$query = add_query_arg(['access_token' => $this->access_token], $query_url);
		}
		else {
			$this->pop_from_stack(); // Make call, error encountered
			$components = [new Error(esc_html__("Instagram Access Token not valid. Please reauthenticate.", 'photonic'))];
			return $components;
		}

		$this->push_to_stack('Send request');
		$response = wp_remote_request(
			$query,
			[
				'sslverify' => PHOTONIC_SSL_VERIFY,
			]
		);
		$this->pop_from_stack(); // Send request

		$this->push_to_stack('Process response');
		if (!is_wp_error($response)) {
			if (isset($response['response']) && isset($response['response']['code'])) {
				if (200 === $response['response']['code']) {
					$body = json_decode($response['body']);

					if (isset($body->paging) && isset($body->paging->next) && isset($body->paging->cursors) && isset($body->paging->cursors->after)) {
						$shortcode_attr['after'] = $body->paging->cursors->after;
					}
					else {
						if (isset($shortcode_attr['after'])) {
							unset($shortcode_attr['after']);
						}
					}

					if (isset($body->data) && 'media' === $display_what) {
						$data         = $body->data;
						$components[] = $this->process_media($data, $shortcode_attr);
					}
					elseif ('single-media' === $display_what || 'carousel' === $display_what) {
						// Special handling for cases where the user was passing a media_id that was really a carousel.
						// Prior to Instagram disabling oEmbed, this would be displayed as an inline slideshow.
						// Now we will make an additional call to fetch the carousel
						if ('CAROUSEL_ALBUM' === $body->media_type) {
							$carousel_query_url = 'https://graph.instagram.com/' . $body->id . '/children?fields=id,media_type,media_url,permalink,thumbnail_url&access_token=' . $this->access_token;
							$carousel_contents  = $this->get_carousel_contents($carousel_query_url);
							if (!empty($carousel_contents)) {
								if ('above' === $shortcode_attr['carousel_caption'] && !empty($body->caption)) {
									$components[] = "<div class='photonic-caption photonic-instagram-caption'>{$body->caption}</div>";
								}

								$components[] = $this->process_media($carousel_contents, $shortcode_attr);

								if ('below' === $shortcode_attr['carousel_caption'] && !empty($body->caption)) {
									$components[] = "<div class='photonic-caption photonic-instagram-caption'>{$body->caption}</div>";
								}
							}
						}
						else {
							$this->pop_from_stack(); // 'Process response'
							$this->pop_from_stack(); // 'Make call'
							$single_photo = new Single_Photo($body->media_url, $body->permalink, '', isset($body->caption) ? $body->caption : '');
							$components[] = $single_photo;
							return $components;
						}
					}
					else {
						$this->pop_from_stack(); // 'Process response'
						$this->pop_from_stack(); // 'Make call'
						$components[] = new Error(esc_html__('No data returned. Unknown error', 'photonic'));
						return $components;
					}
				}
				elseif (isset($response['body'])) {
					$body = json_decode($response['body']);
					if (isset($body->meta) && isset($body->meta->error_message)) {
						$this->pop_from_stack(); // 'Process response'
						$this->pop_from_stack(); // 'Make call'
						$components[] = new Error(esc_html($body->meta->error_message));
						return $components;
					}
					else {
						$this->pop_from_stack(); // 'Process response'
						$this->pop_from_stack(); // 'Make call'
						$components[] = new Error(esc_html__('Unknown error', 'photonic'));
						return $components;
					}
				}
				elseif (isset($response['response']['message'])) {
					$this->pop_from_stack(); // 'Process response'
					$this->pop_from_stack(); // 'Make call'
					$components[] = new Error(esc_html($response['response']['message']));
					return $components;
				}
				else {
					$this->pop_from_stack(); // 'Process response'
					$this->pop_from_stack(); // 'Make call'
					$components[] = new Error(esc_html__('Unknown error', 'photonic'));
					return $components;
				}
			}
		}
		else {
			$this->pop_from_stack(); // 'Process response'
			$this->pop_from_stack(); // 'Make call'
			$components[] = $this->wp_error_message($response);
			return $components;
		}

		$this->pop_from_stack(); // 'Process response'
		$this->pop_from_stack(); // 'Make call'
		return $components;
	}

	private function process_media($data, $short_code) {
		global $photonic_instagram_photo_title_display;

		$photo_objects   = $this->build_level_1_objects($data, $short_code);
		$row_constraints = ['constraint-type' => null, 'padding' => 0, 'count' => 0];

		$photo_list                  = new Photo_List($short_code);
		$photo_list->photos          = $photo_objects;
		$photo_list->title_position  = $photonic_instagram_photo_title_display;
		$photo_list->row_constraints = $row_constraints;
		$photo_list->parent          = 'stream';
		$pagination                  = new Pagination();
		$pagination->end             = 0;
		$pagination->total           = empty($short_code['after']) ? 0 : $short_code['count'];
		$photo_list->pagination      = $pagination;

		return $photo_list;
	}

	public function build_level_1_objects($response, array $short_code, $module_parameters = [], $options = []): array {
		$output = [];

		$media     = explode(',', $short_code['media']);
		$videos_ok = in_array('videos', $media, true) || in_array('all', $media, true);
		$photos_ok = in_array('photos', $media, true) || in_array('all', $media, true);

		foreach ($response as $photo) {
			if (isset($photo->media_type) && ((('image' === strtolower($photo->media_type) || 'carousel_album' === strtolower($photo->media_type)) && $photos_ok) || ('video' === strtolower($photo->media_type) && $videos_ok)) && isset($photo->media_url)) {
				if ('carousel_album' === strtolower($photo->media_type) && 'expand' === $short_code['carousel_handling']) {
					$query_url         = 'https://graph.instagram.com/' . $photo->id . '/children?fields=id,media_type,media_url,permalink,thumbnail_url&access_token=' . $this->access_token;
					$carousel_contents = $this->get_carousel_contents($query_url);
					$carousel_caption  = empty($photo->caption) ? null : $photo->caption;

					foreach ($carousel_contents as $carousel_photo) {
						$ret = $this->process_single_item($carousel_photo, $photos_ok, $videos_ok, $carousel_photo->permalink, $carousel_photo->id, $carousel_caption); // Carousel photos have no caption, so use the post caption
						if (!empty($ret)) {
							$output[] = $ret;
						}
					}
				}
				else {
					$ret = $this->process_single_item($photo, $photos_ok, $videos_ok, $photo->permalink, $photo->id);
					if (!empty($ret)) {
						$output[] = $ret;
					}
				}
			}
		}
		return $output;
	}

	public function renew_token($current_token) {
		$token = [];
		$error = '';
		$soon  = $this->is_token_expiring_soon($this->soon_limit);

		if (is_null($soon)) {
			// No token exists. Do nothing, because the API secret is needed and authentication will need the back-end.
			// The call to is_token_expired will return true, prompting validation.
		}
		else {
			$transient_token         = get_transient('photonic_' . $this->provider . '_token');
			$photonic_authentication = get_option('photonic_authentication');

			// Check for a transient first
			$instagram_token = (false === $transient_token) ? $photonic_authentication['instagram'] : $transient_token;
			if ($soon > 0) {    // Token is expiring soon
				if (!empty($current_token)) {
					$response = wp_remote_request(
						'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $current_token,
						[
							'sslverify' => PHOTONIC_SSL_VERIFY,
						]
					);

					if (!is_wp_error($response)) {
						$token = $this->parse_token($response);
						if (!empty($token)) {
							$token['client_id'] = $instagram_token['client_id'];
							$user_response = wp_remote_request(
								'https://graph.instagram.com/me?fields=id,username&access_token=' . $token['oauth_token'],
								[
									'sslverify' => PHOTONIC_SSL_VERIFY,
								]
							);
							if (!is_wp_error($user_response)) {
								$user_response = $user_response['body'];
								$user_response = json_decode($user_response);
								$token['user'] = $user_response->username;
							}
						}
						// Replace the transient, or set it up anew.
						set_transient('photonic_' . $this->provider . '_token', $token, $token['oauth_token_expires']);
					}
					else {
						$error = $response->get_error_message();
					}
				}
			}
			elseif ($soon < 0) {
				// Token has expired. Do nothing, because the API secret is needed and authentication will need the back-end.
				// The call to is_token_expired will return true, prompting validation.
			}
			else {
				// Token is still good. But, don't create a transient if none exists... transients have an expiry date; that will get messed up.
				$token = $instagram_token;
			}
		}
		return [$token, $error];
	}

	public function is_token_expired($token) {
		if (empty($token)) {
			return true;
		}

		if (!isset($token['oauth_token']) || !isset($token['oauth_token_created']) || !isset($token['oauth_token_expires'])) {
			return true;
		}

		$current = time();
		if ($token['oauth_token_created'] + $token['oauth_token_expires'] < $current) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if a token will expire soon. This is used to trigger a refresh for sources such as Instagram. Google uses a separate "Refresh Token",
	 * so this is not applicable to it. The <code>$soon_limit</code> defines how many days is "soon", and a refresh is triggered if the current date
	 * is in the "soon" range. E.g. If you have a soon limit of 30 days, and your token expires in 15 days when you load the page, this method will
	 * return <code>true</code>.
	 *
	 * For cases where the token does not exist yet, the method returns <code>null</code>.
	 *
	 * @param $soon_limit int Number of days to check the expiry limit for.
	 * @return int|null If there is no token, return <code>null</code>. Otherwise, if there are < <code>$soon_limit</code> days left, return 1, if token is expired return -1, and if there is time return 0.
	 */
	public function is_token_expiring_soon($soon_limit) {
		$transient_token         = get_transient('photonic_' . $this->provider . '_token');
		$photonic_authentication = get_option('photonic_authentication');

		if (false === $transient_token &&
			(empty($photonic_authentication) || empty($photonic_authentication[$this->provider]) ||
				empty($photonic_authentication[$this->provider]['oauth_token']) || empty($photonic_authentication[$this->provider]['oauth_token_created']) || empty($photonic_authentication[$this->provider]['oauth_token_expires']))) {
			return null; // There is no token!
		}

		$token        = (false === $transient_token) ? $photonic_authentication[$this->provider] : $transient_token;
		$token_expiry = $token['oauth_token_created'] + $token['oauth_token_expires'];

		$current     = time();
		$test_expiry = $current + $soon_limit * 24 * 60 * 60;

		$time_left = $token_expiry - $test_expiry;

		if ($current >= $token_expiry) {
			return -1; // already expired
		}
		elseif ($time_left <= 0) {
			return 1; // Expiring soon
		}
		else {
			return 0; // There is still time
		}
	}

	/**
	 * @param $photo
	 * @param $photos_ok
	 * @param $videos_ok
	 * @param null $photo_link
	 * @param null $photo_id
	 * @param null $photo_caption
	 *
	 * @return Photo
	 */
	private function process_single_item($photo, $photos_ok, $videos_ok, $photo_link = null, $photo_id = null, $photo_caption = null) {
		if (isset($photo->media_type) && ((('image' === strtolower($photo->media_type) || 'carousel_album' === strtolower($photo->media_type)) && $photos_ok) || ('video' === strtolower($photo->media_type) && $videos_ok)) && isset($photo->media_url)) {
			$photonic_photo = new Photo();
			$main_image     = esc_url($photo->media_url);
			if (!empty($photo->thumbnail_url)) {
				$photonic_photo->thumbnail  = esc_url($photo->thumbnail_url);
				$photonic_photo->tile_image = esc_url($photo->thumbnail_url);
			}
			else {
				$photonic_photo->thumbnail  = $main_image;
				$photonic_photo->tile_image = $main_image;
			}

			$photonic_photo->main_image = $main_image;

			if (isset($photo->caption)) {
				$photonic_photo->title = wp_kses_post($photo->caption);
			}
			elseif (!empty($photo_caption)) {
				$photonic_photo->title = wp_kses_post($photo_caption);
			}
			else {
				$photonic_photo->title = '';
			}

			$photonic_photo->alt_title   = $photonic_photo->title;
			$photonic_photo->description = $photonic_photo->title;
			$photonic_photo->main_page   = $photo_link;
			$photonic_photo->id          = $photo_id;

			if ('video' === strtolower($photo->media_type)) {
				$photonic_photo->video = esc_url($photo->media_url);
				$photonic_photo->main_image = esc_url($photo->thumbnail_url);

				$parse                = wp_parse_url($photonic_photo->video);
				$parse                = explode('.', $parse['path']);
				$photonic_photo->mime = 'video/' . $parse[count($parse) - 1];
			}

			return $photonic_photo;
		}
		return null;
	}

	public function authentication_URL() {
		// Not needed; placeholder for abstract class method stub
	}

	public function access_token_URL() {
		// Not needed; placeholder for abstract class method stub
	}

	protected function set_token_validity($validity) {
		$this->token_valid = $validity;
	}

	/**
	 * @param string $query_url
	 * @return array|WP_Error
	 */
	private function get_carousel_contents($query_url) {
		$this->push_to_stack('Fetch carousel');
		$response = wp_remote_request(
			$query_url,
			[
				'sslverify' => PHOTONIC_SSL_VERIFY,
			]
		);

		$carousel_contents = [];

		if (!is_wp_error($response)) {
			if (isset($response['response']) && isset($response['response']['code'])) {
				if (200 === $response['response']['code']) {
					$body = json_decode($response['body']);
					if (isset($body->data)) {
						$carousel_contents = $body->data;
					}
				}
			}
		}
		$this->pop_from_stack(); // Fetch carousel
		return $carousel_contents;
	}
}
