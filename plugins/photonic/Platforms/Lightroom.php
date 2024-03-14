<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Components\Pagination;
use Photonic_Plugin\Core\Photonic;

require_once 'OAuth2.php';
require_once 'Level_One_Module.php';
require_once 'Level_Two_Module.php';

class Lightroom extends OAuth2 implements Level_One_Module, Level_Two_Module {
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
		global $photonic_google_client_secret, $photonic_google_refresh_token;

		$this->client_id = '6cf8382c9a9b48d8a0b91d0ed15a4e6a';
		$this->provider = 'lr';
		$this->oauth_version = '2.0';

		$this->scope               = 'https://www.googleapis.com/auth/photoslibrary.readonly';
		$this->link_lightbox_title = false; // empty($photonic_google_disable_title_link);

		// Documentation
		$this->doc_links = [
			'general' => 'https://aquoid.com/plugins/photonic/lightroom/',
		];

		$this->error_date_format = esc_html__('Dates must be entered in the format Y/M/D where Y is from 0 to 9999, M is from 0 to 12 and D is from 0 to 31. You entered %s.', 'photonic');
		$this->oauth_done        = false;
		$this->authenticate($photonic_google_refresh_token);
	}

	public function get_gallery_images($attr = []): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		return [];
	}

	public function authentication_URL() {
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	public function access_token_URL() {
		return 'https://accounts.google.com/o/oauth2/token';
	}

	protected function set_token_validity($validity) {
		$this->refresh_token_valid = $validity;
	}

	public function renew_token($refresh_token) {
		$token    = [];
		$error    = '';
		$response = Photonic::http(
			$this->access_token_URL(),
			'POST',
			[
				'client_id'     => $this->client_id,
				'client_secret' => $this->client_secret,
				'refresh_token' => $refresh_token,
				'grant_type'    => 'refresh_token'
			]
		);

		if (!is_wp_error($response)) {
			$token = $this->parse_token($response);
			if (!empty($token)) {
				$token['client_id'] = $this->client_id;
			}
			set_transient('photonic_' . $this->provider . '_token', $token, $token['oauth_token_expires']);
			if (empty($token)) {
				$error = print_r(wp_remote_retrieve_body($response), true);
			}
		}
		else {
			$error = $response->get_error_message();
		}

		return [$token, $error];
	}

	/**
	 * Not applicable for Google. We make this always return 0.
	 *
	 * @param int $soon_limit
	 * @return int|null
	 */
	public function is_token_expiring_soon($soon_limit) {
		return 0;
	}

	public function build_level_1_objects($response, array $short_code, $module_parameters = [], $options = []): array {
		return [];
	}

	public function build_level_2_objects($objects_or_response, array $short_code, array $filter_list = [], array &$options = [], Pagination &$pagination = null): array {
		return [];
	}
}
