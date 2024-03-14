<?php

namespace Photonic_Plugin\Admin;

if (!current_user_can('edit_theme_options')) {
	wp_die(esc_html__('You are not authorized to use this capability.', 'photonic'));
}

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Platforms\Authenticator;
use Photonic_Plugin\Platforms\Base;
use Photonic_Plugin\Platforms\Flickr;
use Photonic_Plugin\Platforms\Google_Photos;
use Photonic_Plugin\Platforms\Instagram;
use Photonic_Plugin\Platforms\SmugMug;
use Photonic_Plugin\Platforms\Zenfolio;

require_once 'Admin_Page.php';

class Authentication extends Admin_Page {
	private static $instance;

	private function __construct() {
		require_once PHOTONIC_PATH . '/Platforms/Instagram.php';
		require_once PHOTONIC_PATH . '/Platforms/Zenfolio.php';
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Authentication();
		}
		return self::$instance;
	}

	public function render_content() {
		?>
		<form method="post" id="photonic-auth-form">
			<h2 id="#photonic-flickr-auth-section" class="photonic-section">Flickr</h2>
			<?php $this->display_flickr(); ?>

			<h2 id="#photonic-smugmug-auth-section" class="photonic-section">SmugMug</h2>
			<?php $this->display_smugmug(); ?>

			<h2 id="#photonic-google-auth-section" class="photonic-section">Google Photos</h2>
			<?php $this->display_google(); ?>

			<h2 id="#photonic-instagram-auth-section" class="photonic-section">Instagram</h2>
			<?php $this->display_instagram(); ?>

			<h2 id="#photonic-zenfolio-auth-section" class="photonic-section">Zenfolio</h2>
			<?php $this->display_zenfolio(); ?>
		</form>
		<?php
	}

	private function display_flickr() {
		global $photonic_flickr_api_key, $photonic_flickr_api_secret, $photonic_flickr_access_token;
		$auth = [
			'api_key' => trim($photonic_flickr_api_key),
			'api_secret' => trim($photonic_flickr_api_secret),
			'token' => trim($photonic_flickr_access_token),
		];
		$this->show_token_section($auth, 'flickr', 'Flickr');
	}

	private function display_smugmug() {
		global $photonic_smug_api_key, $photonic_smug_api_secret, $photonic_smug_access_token;
		$auth = [
			'api_key' => !empty($photonic_smug_api_key) ? trim($photonic_smug_api_key) : '86MZ8N8TqJf5x2fQ4FRWXRtJ3C6Jm7XV',
			'api_secret' => trim($photonic_smug_api_secret),
			'token' => trim($photonic_smug_access_token),
		];
		$this->show_token_section($auth, 'smug', 'SmugMug');
	}

	private function display_google() {
		global $photonic_google_client_id, $photonic_google_client_secret, $photonic_google_refresh_token;
		echo "<div class=\"photonic-token-header\">\n";
		if (empty($photonic_google_client_id) || empty($photonic_google_client_secret)) {
			echo sprintf(
				esc_html__('Please set up your Google Client ID and Client Secret under %s', 'photonic'),
				'<em>Photonic &rarr; Settings &rarr; Google Photos &rarr; Google Photos Settings</em>'
			);
		}
		else {
			$parameters = Base::parse_parameters($_SERVER['QUERY_STRING']);

			if (!empty($photonic_google_refresh_token)) {
				$this->print_auth_done_all_good();
			}

			echo "</div>\n";
			echo "<div class=\"photonic-token-header\">\n";
			echo "<p>\n";
			esc_html_e('You first have to authorize Photonic to connect to your Google account.', 'photonic');
			echo "</p>\n";
			if (!isset($parameters['code']) || !isset($parameters['source']) || 'google' !== $parameters['source']) {

				$url = add_query_arg('test', 'test');
				$url = remove_query_arg('test', $url);
				$parameters = [
					'response_type' => 'code',
					'redirect_uri' => admin_url('admin.php?page=photonic-auth&source=google'),
					'client_id' => $photonic_google_client_id,
					'scope' => 'https://www.googleapis.com/auth/photoslibrary.readonly',
					'access_type' => 'offline',
					'state' => md5($photonic_google_client_secret . 'google') . '::' . rawurlencode($url),
					'prompt' => 'consent',
				];
				$url = 'https://accounts.google.com/o/oauth2/auth?' . Authenticator::build_query($parameters);

				echo "<a href='" . esc_url($url) . "' class='button button-primary'>" . esc_html__('Step 1: Authenticate', 'photonic') . '</a>';
				echo "</div>\n";
				echo "<div class=\"photonic-token-header\">\n";
				echo "<p>\n";
				echo esc_html__('Next, you have to obtain the token.', 'photonic') . '<br/>';
				echo "</p>\n";
				echo "<span class='button photonic-helper-button-disabled'>" .
					esc_html__('Step 2: Obtain Token', 'photonic') . '</span>';
			}
			else {
				echo "<span class='button photonic-helper-button-disabled'>" .
					esc_html__('Step 1: Authenticate', 'photonic') . '</span>';
				echo "</div>\n";
				echo "<div class=\"photonic-token-header\">\n";
				echo esc_html__('Next, you have to obtain the token.', 'photonic') . '<br/>';
				$nonce = wp_create_nonce('google-obtain-token-' . $photonic_google_client_secret);
				echo "<a href='#' class='button button-primary photonic-google-refresh' data-photonic-nonce='" . esc_attr($nonce) . "'>" .
					esc_html__('Step 2: Obtain Token', 'photonic') . '</a>';
				echo '<input type="hidden" value="' . esc_attr($parameters['code']) . '" id="photonic-google-oauth-code"/>';
				echo '<input type="hidden" value="' . esc_attr($parameters['state']) . '" id="photonic-google-oauth-state"/>';
			}
		}
		echo "</div>\n";
		echo '<div class="result" id="google-result">&nbsp;</div>';
		echo sprintf(
			esc_html__('If you are facing issues with the authentication please follow the workaround %1$shere%2$s', 'photonic'),
			'<a href="https://aquoid.com/plugins/photonic/google-photos/#auth-workaround" target="_blank">',
			'</a>'
		);

		// $this->show_token_deletion_button('Google');
	}

	private function display_instagram() {
		global $photonic_instagram_access_token;
		$auth = [
			'api_key' => 'not-required-but-not-empty',
			'api_secret' => 'not-required-but-not-empty',
			'token' => trim($photonic_instagram_access_token),
		];

		$header = $this->show_token_section_header($auth, 'Instagram');
/*		$response = Core::parse_parameters($_SERVER['QUERY_STRING']);
		if (empty($response['access_token'])) {
			echo "<a href='https://api.instagram.com/oauth/authorize/?client_id=1089620424711320&scope=user_profile,user_media&redirect_uri=https://aquoid.com/photonic-router/instagram/&state=" .
				esc_url(admin_url('admin.php?page=photonic-auth')) . "&response_type=code' class='button button-primary'>" .
				wp_kses_post($this->get_login_button('Instagram')) . '</a>';

			if (!empty($header['deletion'])) {
				// $this->show_token_deletion_button('Instagram');
			}
		}
		elseif (!empty($response['access_token'])) {
			echo "<span class='button photonic-helper-button-disabled'>" . wp_kses_post($this->get_login_button('Instagram')) . '</span>';
			if (!empty($header['deletion'])) {
				// $this->show_token_deletion_button('Instagram');
			}

			echo '<div class="result">' .
				(!empty($response['access_token']) ? 'Access token: <code id="instagram-token">' . esc_html($response['access_token']) . '</code><br/>' : '&nbsp;') .
				(!empty($response['expires_in']) ? '<input type="hidden" id="instagram-token-expires-in" value="' . esc_attr($response['expires_in']) . '" />' : '') .
				(!empty($response['user_id']) ? '<input type="hidden" id="instagram-token-client-id" value="' . esc_attr($response['user_id']) . '" />' : '') .
				(!empty($response['user']) ? 'User name: <code id="instagram-token-user">' . esc_html($response['user']) . '</code><br/>' : '') .
				'</div>';

			$nonce = wp_create_nonce('instagram-save-token-' . $response['access_token']);
			echo "<a href='#' class='button button-primary photonic-save-token' data-photonic-provider='instagram' data-photonic-nonce='" . esc_attr($nonce) . "'>" . esc_html__('Save Token', 'photonic') . '</a>';
		}*/
	}

	private function display_zenfolio() {
		global $photonic_zenfolio_default_user;
		$gallery = Zenfolio::get_instance();

		echo "<div class=\"photonic-token-header\">\n";
		if (empty($photonic_zenfolio_default_user)) {
			echo sprintf(esc_html__('Please set up the default user for Zenfolio under %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Default User</em>') . "\n";
		}
		elseif (!empty($gallery->token)) {
			$this->print_auth_done_all_good();
		}
		echo "</div>\n";

		$response = Base::parse_parameters($_SERVER['QUERY_STRING']);
		if (!empty($photonic_zenfolio_default_user) && (empty($response['provider']) || 'zenfolio' !== $response['provider'])) {
			echo '<label>' . esc_html__('Password:', 'photonic') . "<input type='password' name='zenfolio-password' id='zenfolio-password'></label>";
		}

		echo "<div style='display: block; width: 100%;'>\n";
		if (!empty($photonic_zenfolio_default_user) && (empty($response['provider']) || 'zenfolio' !== $response['provider'])) {
			$nonce = wp_create_nonce('zenfolio-save-token');
			echo "<a href='#' class='button button-primary' data-photonic-provider='zenfolio' style='margin-right: 1em;' data-photonic-nonce='" . esc_attr($nonce) . "'>" . esc_html__('Login and Authenticate', 'photonic') . '</a>';
		}

		if (!empty($gallery->token)) {
			$nonce = wp_create_nonce('zenfolio-delete-token');
			echo "<a href='#' class='button button-primary photonic-zenfolio-delete' data-photonic-nonce='" . esc_attr($nonce) . "'>" . esc_html__('Delete current Zenfolio authentication data', 'photonic') . '</a>';
		}
		echo "</div>\n";

		echo '<div class="result" id="zenfolio-result">&nbsp;</div>';
	}

	private function show_token_section($auth, $provider_slug, $provider_text) {
		$this->show_token_section_header($auth, $provider_text);
		if (!empty($auth['api_key']) && !empty($auth['api_secret'])) {
			$this->show_token_section_body($auth, $provider_slug, $provider_text);
		}
		else {
			echo '<div class="result" id="' . esc_attr($provider_slug) . '-result">&nbsp;</div>';
		}
	}

	/**
	 * @param $auth array
	 * @param $provider string
	 * @return array
	 */
	private function show_token_section_header($auth, $provider) {
		$ret = [];
		echo "<div class=\"photonic-token-header\">\n";

		if (empty($auth['api_key']) || empty($auth['api_secret'])) {
			echo sprintf(esc_html__('Please set up your %1$s API key under %2$s.', 'photonic'), esc_html($provider), sprintf('<em>Photonic &rarr; Settings &rarr; %1$s &rarr; %1$s Settings</em>', esc_html($provider)));
		}
		elseif ('Instagram' === $provider) {
			echo '<p class="notice notice-error">' . esc_html__("Unfortunately Instagram is no longer supported in Photonic. This is due to a change in Meta's Terms and Conditions, that only allow businesses to access their API. As Photonic is developed by an individual, the API is no longer accessible to the developer.", 'photonic') . '</p><br/>';
		}
		elseif ('Instagram' === $provider && !empty($auth['token'])) {
			require_once PHOTONIC_PATH . '/Platforms/Instagram.php';
			$module = Instagram::get_instance();
			$expiring_soon = $module->is_token_expiring_soon(30);
			if (is_null($expiring_soon)) {
				// Not yet authenticated with the new API.
				echo '<p class="notice notice-error">' . esc_html__('Your authentication credentials are for the old Instagram API. Please reauthenticate to keep Photonic working.', 'photonic') . '</p><br/>';
			}
			elseif (1 === $expiring_soon) {
				echo '<p class="notice notice-warning">' . esc_html__('Your authentication credentials are expiring soon. Please reauthenticate to keep Photonic working.', 'photonic') . '</p><br/>';
			}
			elseif (-1 === $expiring_soon) {
				echo '<p class="notice notice-error">' . esc_html__('Your authentication credentials have expired! Please reauthenticate to keep Photonic working.', 'photonic') . '</p><br/>';
			}
			else {
				$cached_token = $module->get_cached_token();

				if (!empty($cached_token) && !empty($cached_token['user'])) {
					$this->print_auth_done_all_good(sprintf(esc_html__('You are logged in as %1$s.', 'photonic'), '<code>' . $cached_token['user'] . '</code>'));
				}
				else {
					$this->print_auth_done_all_good();
				}
			}
			$ret['deletion'] = true;
		}
		elseif (!empty($auth['token'])) {
			$this->print_auth_done_all_good();
		}
		echo "</div>\n";
		return $ret;
	}

	/**
	 * @param $auth
	 * @param $provider
	 * @param $provider_text
	 */
	public function show_token_section_body($auth, $provider, $provider_text) {
		$photonic_authentication = get_option('photonic_authentication');
		$response = Base::parse_parameters($_SERVER['QUERY_STRING']);

		if (empty($response['provider']) || (!empty($response['provider']) && $provider !== $response['provider'])) {
			$nonce = wp_create_nonce($provider . '-request-token-' . $auth['api_secret']);
			echo "<a href='#' class='button button-primary photonic-token-request' data-photonic-provider='" . esc_attr($provider) . "' data-photonic-nonce='" . esc_attr($nonce) . "'>" . wp_kses_post($this->get_login_button($provider_text)) . '</a>';
		}
		elseif (!empty($response['oauth_token']) && !empty($response['oauth_verifier'])) {
			if (in_array($provider, ['flickr', 'smug', 'smugmug'], true)) {
				if ('flickr' === $provider) {
					require_once PHOTONIC_PATH . '/Platforms/Flickr.php';
					$module = Flickr::get_instance();
				}
				else {
					require_once PHOTONIC_PATH . '/Platforms/SmugMug.php';
					$module = SmugMug::get_instance();
				}
				echo "<span class='button photonic-helper-button-disabled'>" . wp_kses_post($this->get_login_button($provider_text)) . '</span>';
				$authorization = ['oauth_token' => $response['oauth_token'], 'oauth_verifier' => $response['oauth_verifier']];
				if (isset($photonic_authentication) && isset($photonic_authentication[$provider]) && isset($photonic_authentication[$provider]['oauth_token_secret'])) {
					$authorization['oauth_token_secret'] = $photonic_authentication[$provider]['oauth_token_secret'];
				}
				$access_token = $module->get_access_token($authorization);
				if (isset($access_token['oauth_token'])) {
					echo '<div class="result">Access Token: <code id="' . esc_attr($provider) . '-token">' . esc_html($access_token['oauth_token']) . '</code><br/>Access Token Secret: <code id="' . esc_attr($provider) . '-token-secret">' . esc_html($access_token['oauth_token_secret']) . '</code></div>' . "\n";
					$nonce = wp_create_nonce($provider . '-save-token-' . $access_token['oauth_token']);
					echo "<a href='#' class='button button-primary photonic-save-token' data-photonic-provider='" . esc_attr($provider) . "' data-photonic-nonce='" . esc_attr($nonce) . "'>" . esc_html__('Save Token', 'photonic') . '</a>';
				}
			}
		}
	}

	private function print_auth_done_all_good($additional_msg = null) {
		echo '<p class="notice notice-success">';
		esc_html_e('You have already set up your authentication. Unless you wish to regenerate the token this step is not required. ', 'photonic');
		if (!empty($additional_msg)) {
			echo esc_html($additional_msg);
		}
		echo '</p>';
	}

	private function get_login_button($provider) {
		return sprintf(esc_html__('Login and get Access Token from %s', 'photonic'), $provider);
	}

	private function show_token_deletion_button($provider) {
		// echo "<a href='#' class='button button-primary photonic-" . strtolower($provider) . "-delete'>" . esc_html__(sprintf('Delete current %s authentication data', $provider), 'photonic') . '</a>';
	}

	public function obtain_token() {
		$provider = sanitize_text_field($_POST['provider']);
		global $photonic_google_client_secret, $photonic_flickr_api_secret, $photonic_smug_api_secret;
		if ('google' === $provider && check_ajax_referer('google-obtain-token-' . $photonic_google_client_secret, '_ajax_nonce')) {
			$code = sanitize_text_field($_POST['code']);
			require_once PHOTONIC_PATH . '/Platforms/Google_Photos.php';
			$module = Google_Photos::get_instance();
			// if (!empty($photonic_google_use_own_keys) || (!empty($photonic_google_client_id) && !empty($photonic_google_client_secret))) {
			$response = Photonic::http(
				$module->access_token_URL(),
				'POST',
				[
					'code' => $code,
					'grant_type' => 'authorization_code',
					'client_id' => $module->client_id,
					'client_secret' => $module->client_secret,
					'redirect_uri' => admin_url('admin.php?page=photonic-auth&source=google'),
				]
			);

			/*
					  }
						else {
							$response = Photonic::http($module->access_token_URL(), 'POST', [
								'code' => $code,
								'grant_type' => 'authorization_code',
								'client_id' => $module->client_id,
								'client_secret' => $module->client_secret,
								'redirect_uri' => 'https://aquoid.com/photonic-router/google.php',
								'state' => admin_url('admin.php?page=photonic-auth&source=google'),
							]);
						}
			*/

			if (!is_wp_error($response) && is_array($response)) {
				$body = json_decode($response['body']);
				// Add nonce to the response. This will be used to save the information in the options.
				$body->nonce = wp_create_nonce('google-save-token-' . $body->refresh_token);
				echo(wp_json_encode($body));
			}
		}
		elseif ('flickr' === $provider && check_ajax_referer('flickr-request-token-' . $photonic_flickr_api_secret, '_ajax_nonce')) {
			require_once PHOTONIC_PATH . '/Platforms/Flickr.php';
			$module = Flickr::get_instance();
			if (empty($_POST['oauth_token']) && empty($_POST['oauth_verifier'])) {
				$request_token = $module->get_request_token(admin_url('admin.php?page=photonic-auth&provider=flickr'));
				$authorize_url = $module->get_authorize_URL($request_token);
				$authorize_url .= '&perms=read';
				$module->save_token($request_token);
				echo esc_url_raw($authorize_url);
			}
		}
		elseif ('smug' === $provider && check_ajax_referer('smug-request-token-' . $photonic_smug_api_secret, '_ajax_nonce')) {
			require_once PHOTONIC_PATH . '/Platforms/SmugMug.php';
			$module = SmugMug::get_instance();
			if (empty($_POST['oauth_token']) && empty($_POST['oauth_verifier'])) {
				$request_token = $module->get_request_token(admin_url('admin.php?page=photonic-auth&provider=smug'));
				$authorize_url = $module->get_authorize_URL($request_token);
				$authorize_url .= '&Access=Full&Permissions=Read';
				$module->save_token($request_token);
				echo esc_url_raw($authorize_url);
			}
		}
		elseif ('zenfolio' === $provider) {
			$module = Zenfolio::get_instance();
			if (!empty($_POST['password'])) {
				$response = $module->authenticate($_POST['password']);
				if (!empty($response['error'])) {
					echo wp_kses_post($response['error']);
				}
				elseif (!empty($response['success'])) {
					esc_html_e('Authentication successful! All your galleries will be displayed with Authentication in place.', 'photonic');
				}
			}
		}
	}

	public function delete_token_from_options() {
		if (isset($_POST['provider']) && check_ajax_referer($_POST['provider'] . '-delete-token')) {
			$provider = strtolower(sanitize_text_field($_POST['provider']));
			if (in_array($provider, ['flickr', 'smug', 'zenfolio', 'google', 'instagram'], true) && current_user_can('edit_theme_options')) {
				$photonic_authentication = get_option('photonic_authentication');
				if ('zenfolio' === $provider) {
					if (isset($photonic_authentication[$provider])) {
						unset($photonic_authentication[$provider]);
					}
				}
				update_option('photonic_authentication', $photonic_authentication);
			}
		}
		die();
	}
}
