<?php
namespace Photonic_Plugin\Core;

use Photonic_Plugin\Admin\Authentication;
use Photonic_Plugin\Admin\Helper;

class AJAX {
	private $core;
	private static $instance = null;

	/**
	 * AJAX constructor.
	 *
	 * @param Photonic $photonic
	 */
	private function __construct($photonic) {
		$this->core = $photonic;

		add_action('wp_ajax_photonic_display_level_2_contents', [&$this, 'display_level_2_contents']);
		add_action('wp_ajax_nopriv_photonic_display_level_2_contents', [&$this, 'display_level_2_contents']);

		add_action('wp_ajax_photonic_display_level_3_contents', [&$this, 'display_level_3_contents']);
		add_action('wp_ajax_nopriv_photonic_display_level_3_contents', [&$this, 'display_level_3_contents']);

		add_action('wp_ajax_photonic_load_more', [&$this, 'load_more']);
		add_action('wp_ajax_nopriv_photonic_load_more', [&$this, 'load_more']);

		add_action('wp_ajax_photonic_lazy_load', [&$this, 'lazy_load']);
		add_action('wp_ajax_nopriv_photonic_lazy_load', [&$this, 'lazy_load']);

		add_action('wp_ajax_photonic_helper_shortcode_more', [&$this, 'helper_shortcode_more']);
		add_action('wp_ajax_nopriv_photonic_helper_shortcode_more', [&$this, 'helper_shortcode_more']);

		add_action('wp_ajax_photonic_invoke_helper', [&$this, 'invoke_helper']);
		add_action('wp_ajax_photonic_obtain_token', [&$this, 'obtain_token']);
		add_action('wp_ajax_photonic_save_token', [&$this, 'save_token_in_options']);
		add_action('wp_ajax_photonic_delete_token', [&$this, 'delete_token_from_options']);

		add_action('wp_ajax_photonic_dismiss_warning', [&$this, 'dismiss_warning']);
	}

	/**
	 * @param Photonic $photonic
	 * @return AJAX
	 */
	public static function get_instance($photonic): AJAX {
		if (null === self::$instance) {
			self::$instance = new AJAX($photonic);
		}
		return self::$instance;
	}


	/**
	 * Clicking on a level 2 object (i.e. an Album / Set / Gallery) triggers this. This will fetch the contents of the level 2 object and generate the markup for it.
	 * This is the hook for an AJAX-invoked call
	 *
	 * @return void
	 */
	public function display_level_2_contents() {
		// Cannot use a nonce here. Users often cache the gallery markup, which would cache the nonce. This would make it impossible to run this call after a certain amount of time.
		$panel = sanitize_text_field($_POST['panel_id']);
		$components = explode('-', $panel);

		if (count($components) <= 5) {
			die();
		}
		$panel = implode('-', array_slice($components, 4, 10, true));
		$query = sanitize_text_field($_POST['query']);
		$query = wp_parse_args($query);

		$popup = sanitize_text_field($_POST['popup']);
		if (empty($popup)) {
			$location = 'lightbox';
		}
		elseif ('page' === $popup) {
			$location = 'template';
		}
		else {
			$location = 'modal';
		}

		$args = [
			'display'    => $location,
			'layout'     => 'square',
			'panel'      => $panel,
			'password'   => !empty($_POST['password']) ? sanitize_text_field($_POST['password']) : '',
			'count'      => sanitize_text_field($_POST['photo_count']),
			'photo_more' => sanitize_text_field($_POST['photo_more']),
			'main_size'  => $query['main_size'],
			'type'       => $components[1]
		];

		$provider = $components[1];
		$type = $components[2];
		if (in_array($provider, ['smug', 'smugmug', 'zenfolio', 'google', 'flickr'], true)) {
			if ('smug' === $provider) {
				$args['view'] = 'album';
				$args['album_key'] = $components[4];
			}
			elseif ('zenfolio' === $provider) {
				$args['view'] = 'photosets';
				$args['object_id'] = $components[4];
				$args['thumb_size'] = sanitize_text_field($_POST['overlay_size']);
				$args['video_size'] = sanitize_text_field($_POST['overlay_video_size']);
				if (isset($_POST['realm_id'])) {
					$args['realm_id'] = sanitize_text_field($_POST['realm_id']);
				}
			}
			elseif ('google' === $provider) {
				$args['view'] = 'photos';
				$args['album_id'] = implode('-', array_slice($components, 4, (count($components) - 1) - 4));
				$args['thumb_size'] = sanitize_text_field($_POST['overlay_size']);
				$args['video_size'] = sanitize_text_field($_POST['overlay_video_size']);
				$args['crop_thumb'] = sanitize_text_field($_POST['overlay_crop']);
			}
			elseif ('flickr' === $provider) {
				if ('gallery' === $type) {
					$args['gallery_id'] = $components[4] . '-' . $components[5];
					$args['gallery_id_computed'] = true;
				}
				elseif ('set' === $type) {
					$args['photoset_id'] = $components[4];
				}
				$args['thumb_size'] = sanitize_text_field($_POST['overlay_size']);
				$args['video_size'] = sanitize_text_field($_POST['overlay_video_size']);
			}

			$gallery = new Gallery($args);
			echo wp_kses($gallery->get_contents(), Photonic::$safe_tags);
		}
		die();
	}

	/**
	 * Clicking on the expander for a level 3 object (e.g. a Flickr Collection etc.) triggers this. This will fetch the nested level 2 objects and generate the corresponding markup.
	 * This is the hook for an AJAX-invoked call.
	 */
	public function display_level_3_contents() {
		// Cannot use a nonce here. Users often cache the gallery markup, which would cache the nonce. This would make it impossible to run this call after a certain amount of time.
		$node = sanitize_text_field($_POST['node']);
		$components = explode('-', $node);

		if (count($components) <= 3) {
			die();
		}

		$args = ['display' => 'local', 'headers' => '', 'layout' => sanitize_text_field($_POST['layout']), 'stream' => sanitize_text_field($_POST['stream'])];

		$provider = $components[0];
		if ('flickr' === $provider) {
			$args['collection_id'] = implode('-', array_slice($components, 2, 2, true));
			$args['user_id'] = $components[4];
			$args['type'] = 'flickr';
			$args['strip_top_level'] = 'remove';
			$gallery = new Gallery($args);
			echo wp_kses($gallery->get_contents(), Photonic::$safe_tags);
		}
		die();
	}

	public function load_more() {
		// Cannot use a nonce here. Users often cache the gallery markup, which would cache the nonce. This would make it impossible to run this call after a certain amount of time.
		$provider = sanitize_text_field($_POST['provider']);
		$query = sanitize_text_field($_POST['query']);
		$attr = wp_parse_args($query);

		$attr['type'] = $provider;
		if ('flickr' === $provider) {
			$attr['page'] = isset($attr['page']) ? $attr['page'] + 1 : 0;
		}
		elseif ('smug' === $provider) {
			$attr['start'] = $attr['start'] + $attr['count'];
		}
		elseif ('zenfolio' === $provider) {
			$attr['offset'] = $attr['offset'] + $attr['limit'];
		}
		elseif ('wp' === $provider) {
			$attr['page'] = $attr['page'] + 1;
		}
		elseif ('google' !== $provider && 'instagram' !== $provider) {
			unset($attr['type']);
		}

		if (!empty($attr['type'])) {
			$gallery = new Gallery($attr);
			echo wp_kses($gallery->get_contents(), Photonic::$safe_tags);
		}
		die();
	}

	public function lazy_load() {
		// $_POST['shortcode'] only contains the parameters of a URL, to be passed to photonic after being broken down. Sanitization functions are killing
		// characters such as "@" (used in Flickr user ids) or its escaped form. So we use esc_url_raw.
		// However, esc_url_raw needs a domain, so we prepend a random one, sanitize it, then pull out only the 'query' part from it.
		$shortcode = esc_url_raw('https://randomurl.com?' . $_POST['shortcode']);
		$shortcode_parse = wp_parse_url($shortcode);
		$attr = [];
		parse_str($shortcode_parse['query'], $attr);

		$images = $this->core->get_gallery_images($attr);
		// echo $images;
		echo wp_kses($images, Photonic::$safe_tags);
		die();
	}

	public function helper_shortcode_more() {
		if (!empty($_POST['provider'])) {
			$provider = sanitize_text_field($_POST['provider']);
			if (in_array($provider, ['google'], true)) {
				$attr = ['type' => $provider];
				if ('google' === $provider) {
					$attr['nextPageToken'] = sanitize_text_field($_POST['nextPageToken']);
					$attr['album_type'] = sanitize_text_field($_POST['access']);
					$gallery = new Gallery($attr);
					echo wp_kses($gallery->get_helper_contents(), Photonic::$safe_tags);
				}
			}
		}
		die();
	}

	public function invoke_helper() {
		require_once PHOTONIC_PATH . "/Admin/Helper.php";
		$helper = new Helper();
		$helper->invoke_helper();
	}

	public function obtain_token() {
		require_once PHOTONIC_PATH . "/Admin/Authentication.php";
		$auth = Authentication::get_instance();
		$auth->obtain_token();
		die();
	}

	/**
	 * Invoked via AJAX in the "Authentication" page, when the user clicks on "Save Token"
	 */
	public function save_token_in_options() {
		if (isset($_POST['provider']) && isset($_POST['token']) && check_ajax_referer($_POST['provider'] . '-save-token-' . $_POST['token']) && current_user_can('edit_theme_options')) {
			$provider = strtolower(sanitize_text_field($_POST['provider']));
			$token = sanitize_text_field($_POST['token']);
			$secret = sanitize_text_field($_POST['secret']);
			if (!empty($_POST['expires_in'])) {
				$expires_in = sanitize_text_field($_POST['expires_in']);
			}

			if (in_array($provider, ['flickr', 'smug', 'zenfolio', 'google', 'instagram'], true)) {
				$options = get_option('photonic_options');
				if (empty($options)) {
					$options = [];
				}
				$option_set = false;
				if (in_array($provider, ['flickr', 'smug', 'zenfolio'], true)) {
					$options[$provider . '_access_token'] = $token;
					$options[$provider . '_token_secret'] = $secret;
					$option_set = true;
				}
				elseif (in_array($provider, ['google'], true)) {
					$options[$provider . '_refresh_token'] = $token;
					$option_set = true;
				}
				elseif (in_array($provider, ['instagram'], true)) {
					$client_id = sanitize_text_field($_POST['client_id']);
					$user = sanitize_text_field($_POST['user']);

					$options[$provider . '_access_token'] = $token;

					$auth_token = [];
					$auth_token['oauth_token'] = $token;
					$auth_token['oauth_token_created'] = time();
					if (!empty($expires_in)) {
						$auth_token['oauth_token_expires'] = $expires_in;
					}
					$auth_token['client_id'] = $client_id;
					$auth_token['user'] = $user;

					self::save_provider_authentication($provider, $auth_token);

					$option_set = true;
				}

				if ($option_set) {
					update_option('photonic_options', $options);
					echo esc_url(admin_url('admin.php?page=photonic-options-manager')) . '&tab=' . esc_attr($this->core->provider_map[$provider]) . '.php';
				}
			}
		}
		die();
	}

	/**
	 * @param string $provider
	 * @param array $auth_token
	 */
	private static function save_provider_authentication($provider, $auth_token) {
		if (current_user_can('edit_theme_options')) { // Method is private, and is only called from save_token_in_options, where there is a nonce check
			$photonic_authentication = get_option('photonic_authentication');
			if (empty($photonic_authentication)) {
				$photonic_authentication = [];
			}
			$photonic_authentication[$provider] = $auth_token;
			update_option('photonic_authentication', $photonic_authentication);
			set_transient('photonic_' . $provider . '_token', $auth_token);
		}
	}

	public function dismiss_warning() {
		$user_id = get_current_user_id();
		$response = [];
		if (!empty($_POST['dismissible']) && check_ajax_referer('dismiss-warning-' . $user_id)) {
			add_user_meta($user_id, "photonic_" . sanitize_text_field($_POST['dismissible']), 'true', true);
			$response[$_POST['dismissible']] = 'true';
		}
		echo wp_json_encode($response);
		die();
	}
}
