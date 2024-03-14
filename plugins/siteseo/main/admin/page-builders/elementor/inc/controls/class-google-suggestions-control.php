<?php

namespace SiteSeoElementorAddon\Controls;

if ( ! defined('ABSPATH')) {
	exit();
}

class Google_Suggestions_Control extends \Elementor\Base_Control {
	public function get_type() {
		return 'siteseo-google-suggestions';
	}

	public function enqueue() {
		wp_enqueue_style(
			'siteseo-el-google-suggestions-style',
			SITESEO_ELEMENTOR_ADDON_URL . 'assets/css/google-suggestions.css'
		);

		wp_enqueue_script(
			'siteseo-el-google-suggestions-script',
			SITESEO_ELEMENTOR_ADDON_URL . 'assets/js/google-suggestions.js',
			['jquery'],
			SITESEO_VERSION,
			true
		);

		if ('' != get_locale()) {
			$locale	   = substr(get_locale(), 0, 2);
			$country_code = substr(get_locale(), -2);
		} else {
			$locale	   = 'en';
			$country_code = 'US';
		}

		wp_localize_script(
			'siteseo-el-google-suggestions-script',
			'googleSuggestions',
			[
				'locale'	  => $locale,
				'countryCode' => $country_code,
			]
		);
	}

	protected function get_default_settings() {
		global $post;

		return [
			'label'	   => __('Google suggestions', 'siteseo'),
			'tooltip'	 => siteseo_tooltip(__('Google suggestions', 'siteseo'), __('Enter a keyword, or a phrase, to find the top 10 Google suggestions instantly. This is useful if you want to work with the long tail technique.', 'siteseo'), esc_html('my super keyword,another keyword,keyword')),
			'placeholder' => __('Get suggestions from Google', 'siteseo'),
			'buttonLabel' => __('Get suggestions!', 'siteseo'),
		];
	}

	public function content_template() {
		?>
<div class="elementor-control-field siteseo-google-suggestions">
	<label for="siteseo_google_suggest_kw_meta">
		<div>{{{ data.label }}} {{{ data.tooltip }}}</div>
		<input id="siteseo_google_suggest_kw_meta" type="text" placeholder="{{ data.placeholder }}"
			aria-label="Google suggestions">
	</label>
	<button id="siteseo_get_suggestions" type="button"
		class="btn btnSecondary elementor-button elementor-button-default">{{{ data.buttonLabel }}}</button>
	<ul id='siteseo_suggestions'></ul>
</div>
<?php
	}
}
