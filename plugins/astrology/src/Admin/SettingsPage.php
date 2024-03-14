<?php
/**
 * Plugin settings page controller.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

/*
 * This file is part of Prokerala Astrology WordPress plugin
 *
 * Copyright (c) 2022 Ennexa Technologies Private Limited
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Prokerala\WP\Astrology\Admin;

use Prokerala\WP\Astrology\Configuration;
use Prokerala\WP\Astrology\Plugin;
use Prokerala\WP\Astrology\Templating\Engine;

/**
 * SettingsPage Class.
 *
 * @since   1.0.0
 */
final class SettingsPage {

	/**
	 * Plugin configuration object.
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;

	/**
	 * SettingsPage constructor
	 *
	 * @param Configuration $config Plugin configuration object.
	 *
	 * @since 1.0.0
	 */
	public function __construct( Configuration $config ) {
		$this->register();
		$this->config = $config;
	}

	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		register_setting( 'astrology_plugin_options', 'astrology_plugin_options', [ $this, 'validate' ] );
		add_settings_section( 'api_settings', 'API Settings', [ $this, 'render_section_text' ], 'astrology_plugin' );
		add_settings_field( 'astrology_plugin_setting_client_id', 'Client ID', [ $this, 'render_client_id' ], 'astrology_plugin', 'api_settings' );
		add_settings_field( 'astrology_plugin_setting_client_secret', 'Client Secret', [ $this, 'render_client_secret' ], 'astrology_plugin', 'api_settings' );

		add_settings_section( 'astrology_display_options', 'Display Settings', [ $this, 'render_section_text' ], 'astrology_plugin' );
		add_settings_field( 'astrology_plugin_setting_theme', 'Theme', [ $this, 'render_theme' ], 'astrology_plugin', 'astrology_display_options' );
		add_settings_field( 'astrology_plugin_setting_ayanamsa', 'Ayanamsa', [ $this, 'render_ayanamsa' ], 'astrology_plugin', 'astrology_display_options' );
		add_settings_field( 'astrology_plugin_setting_timezone', 'Default Timezone', [ $this, 'render_timezone' ], 'astrology_plugin', 'astrology_display_options' );
		add_settings_field( 'astrology_plugin_setting_attribution', 'Attribution', [ $this, 'render_attribution' ], 'astrology_plugin', 'astrology_display_options' );
	}

	/**
	 * Validate settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,string> $input Form input.
	 *
	 * @return array<string,string>
	 */
	public function validate( $input ) {
		$input['client_id']     = trim( $input['client_id'] );
		$input['client_secret'] = trim( $input['client_secret'] );
		$input['attribution']   = trim( $input['attribution'] );

		$this->validate_client( $input['client_id'], $input['client_secret'] );

		return $input;
	}

	/**
	 * Render section head.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,mixed> $section Section details.
	 * @return void
	 */
	public function render_section_text( $section ) {
		if ( 'api_settings' === $section['id'] ) {
			echo '<p>Configure Prokerala API credentials. You can find your client id/secret on your <a href="https://api.prokerala.com/account/client">dashboard</a>.</p>';

			return;
		}

		if ( 'form_options' === $section['id'] ) {
			echo '<p>Customize your user input form.</p>';

			return;
		}
	}

	/**
	 * Render settings input form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_form() {
		echo $this->render( __DIR__ . '/../../templates/admin/settings.tpl.php' ); // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render input field for client id.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_client_id() {
		$client_id = $this->config->get_option( 'client_id' );
		echo "<input id='astrology_plugin_setting_client_id' name='astrology_plugin_options[client_id]' ";
		echo "type='text' size='40' pattern='[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}' ";
		echo "value='" . esc_attr( $client_id ) . "' size='50' required autocomplete='off'/>";
		echo '<p>Your Prokerala API client id</p>';
	}

	/**
	 * Render input field for client secret.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_client_secret() {
		$client_secret = $this->config->get_option( 'client_secret' );
		echo "<input id='astrology_plugin_setting_client_secret' name='astrology_plugin_options[client_secret]' ";
		echo "type='password' value='" . esc_attr( $client_secret ) . "' size='40' required  autocomplete='off'/>";
		echo '<p>Your Prokerala API client secret</p>';
	}

	/**
	 * Render input field for theme
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_theme() {
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		static $themes = [
			'default' => 'Use WordPress Theme (Default)',
			'dark'    => 'Dark',
			'light'   => 'Light',
		];
		$theme         = $this->config->get_option( 'theme' );
		echo '<select id="astrology_plugin_setting_theme" name="astrology_plugin_options[theme]">';
		foreach ( $themes as $id => $label ) {
			echo "<option value='{$id}'" . ( (string) $id === $theme ? ' selected="selected"' : '' ) . '>';
			echo $label . '</options>';
		}
		echo '</select>';
	}

	/**
	 * Render input field for toggling attribution visibility
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_attribution() {
		$attribution = $this->config->get_option( 'attribution' );
		?>
		<label>
			<input type="checkbox" id="astrology_plugin_setting_attribution" name="astrology_plugin_options[attribution]" <?php echo '1' === (string) $attribution ? 'checked' : ''; ?> value="1" onclick="return this.checked || confirm('Please continue only if you are on our paid plan. Attribution is mandatory on free plan. Hiding attribution on free plan can lead to account suspension and domain blacklisting without further notice.')" />
			Show <em>Powered by Prokerala</em> attribution.
		</label>
		<p class="help">Attribution is mandatory on free plan. Violation can lead to account suspension and domain blacklisting without further notice.</p>
		<?php
	}

	/**
	 * Render input field for default ayanamsa.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_ayanamsa() {
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		static $ayanamsas = [
			''  => 'Let user choose',
			'1' => 'Lahiri',
			'3' => 'Raman',
			'5' => 'KP',
		];
		$ayanamsa         = $this->config->get_option( 'ayanamsa' );
		echo '<select id="astrology_plugin_setting_ayanamsa" name="astrology_plugin_options[ayanamsa]">';
		foreach ( $ayanamsas as $id => $label ) {
			echo "<option value='{$id}'" . ( (string) $id === $ayanamsa ? ' selected="selected"' : '' ) . '>';
			echo $label . '</options>';
		}
		echo '</select>';
	}

	/**
	 * Render input field for default timezone.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_timezone() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$regions = $this->get_timezone_list();
		$current = $this->config->get_option( 'timezone' );

		echo '<select id="astrology_plugin_setting_timezone" name="astrology_plugin_options[timezone]">';
		foreach ( $regions as $region => $zones ) {
			echo "<optgroup label='{$region}'>";
			foreach ( $zones as $zone => $label ) {
				echo "<option value='{$zone}'" . ( $current === $zone ? ' selected' : '' ) . ">{$label}</option>";
			}
			echo '</optgroup>';
		}
		echo '</select>';
	}

	/**
	 * Generate list of timezones
	 *
	 * @since 1.0.6
	 *
	 * @return array
	 */
	private function get_timezone_list() {

		$tz_list = \DateTimeZone::listIdentifiers( \DateTimeZone::ALL );
		$now     = new \DateTimeImmutable();
		$regions = [
			'Common' => [ 'UTC' => '+00:00 UTC (GMT)' ],
		];
		foreach ( $tz_list as $value ) {
			$tmp = explode( '/', $value );
			if ( 2 !== count( $tmp ) ) {
				continue;
			}
			$tz                           = new \DateTimeZone( $value );
			$regions[ $tmp[0] ][ $value ] = $now->setTimezone( $tz )->format( 'P' ) . ' ' . $value;
		}
		array_walk(
			$regions,
			function ( &$val ) {
				asort( $val );
			}
		);

		return $regions;
	}

	/**
	 * Validate API client id with server.
	 *
	 * @since 1.0.0
	 *
	 * @param string $client_id Client id.
	 * @param string $client_secret Client secret.
	 */
	private function validate_client( $client_id, $client_secret ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh,Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		static $messages = [
			'invalid_client'        => [ 'client_id', 'Server rejected the client id. Please check.' ],
			'invalid_client_secret' => [ 'client_secret', 'Client secret is incorrect' ],
			'unauthorized_origin'   => [ 'client_id', 'Add <code>%ORIGIN%</code> to <a href="https://api.prokerala.com/account/client/%CLIENT_ID%">authorized origins</a> for your app.' ],
			'http_error'            => [ null, 'Failed to verify credentials. Check your firewall' ],
			'*'                     => [ null, 'Failed to validate credentials' ],
		];

		$origin  = $this->config->get_origin();
		$headers = [
			'Authority'      => 'api.prokerala.com',
			'Content-Length' => '0',
			'User-Agent'     => 'PK Astrology WP Client v' . Plugin::VERSION,
			'Origin'         => $origin,
		];

		$response = wp_remote_post(
			"https://api.prokerala.com/client/verify/{$client_id}",
			[
				'headers' => $headers,
			]
		);

		$result = $this->handle_response( $response );
		$status = $result['status'];

		$this->config->set_client_status( $status );

		if ( 'ok' === $status ) {
			$this->config->remove_notice( 'client_status' );
			return;
		}

		$error_info = isset( $messages[ $status ] ) ? $messages[ $status ] : $messages['*'];
		$message    = str_replace( [ '%ORIGIN%', '%CLIENT_ID%' ], [ $origin, $client_id ], $error_info[1] );

		if ( $error_info[0] ) {
			add_settings_error( $error_info[0], 'invalid-' . $error_info[0], $message );
		}

		$this->config->add_notice( 'client_status', $message, 0, 'error' );
	}

	/**
	 * Handle HTTP response.
	 *
	 * @param array<string,mixed>|\WP_Error $response HTTP response.
	 *
	 * @return array|mixed
	 */
	private function handle_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return [
				'status' => 'http_error',
				'error'  => $response->get_error_message(),
			];
		}

		return json_decode( $response['body'], true );
	}

	/**
	 * Render settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param string              $template Template name.
	 * @param array<string,mixed> $data Additional data.
	 *
	 * @return string
	 */
	private function render( $template, array $data = [] ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$engine = new Engine();

		return $engine->render(
			$template,
			[
				'settings' => $this,
			]
		);
	}
}
