<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Form_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();

		$app_settings = $this->app->make('/app/settings');
		$api_key = $app_settings->get('maps_google:api_key');

		$api_key_help = $this->app->make('/html/element')->tag('div')
			->add(
				$this->app->make('/html/list')
					->add(
						__('Or enter "none" to skip it' . '.', 'locatoraid')
						)
					->add(
						__('Usage of the Google Maps APIs now requires an API key which you can get from the Google Maps developers website.', 'locatoraid')
						)
					->add(
						'<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">' .
						__('Get Google Maps API key', 'locatoraid') .
						'</a>'
						)
				)
			;

		$label = __('Google Maps Browser API Key', 'locatoraid');

		$return['maps_google:api_key'] = array(
			'input'	=> $this->app->make('/form/text'),
			'label'	=> $label,
			'validators'	=> array(
				$this->app->make('/validate/required')
				)
			);

		if( 1 OR ! strlen($api_key) ){
			$return['maps_google:api_key']['help'] = $api_key_help;
		}

	// if no api key is set then don't show other inputs
		if( 1 OR strlen($api_key) ){
			$return['maps_google:icon'] = array(
				'input'	=> $this->app->make('/maps-google.conf/icon/input'),
				'label'	=> __('Map Icon', 'locatoraid')
			);

			$return['maps_google:scrollwheel'] =
				$this->app->make('/form/checkbox')
					->set_label( __('Enable Scroll Wheel Zoom', 'locatoraid') )
				;

			$style_help = 'Get your map style code from websites like <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a> and paste it in this textarea.';
			$return['maps_google:map_style'] = array(
				'input' => $this->app->make('/maps-google.conf/input-map-style'),
				'label'	=> __('Custom Map Style', 'locatoraid'),
				'help'	=> $style_help
				);

			$more_options_help = 'JSON code for more map options.';
			$return['maps_google:more_options'] = array(
				'input' => $this->app->make('/form/textarea'),
				'label'	=> __('More Map Options', 'locatoraid'),
				'help'	=> $more_options_help
				);

			$lang_help = 'Examples: it, fr, de. <a target="_blank" href="https://developers.google.com/maps/faq#languagesupport">Full list</a>';
			$lang_help .= '<br>' . __('Leave blank to use user preferred language.', 'locatoraid');

			$return['maps_google:language'] = array(
				'input' => $this->app->make('/form/text'),
				'label'	=> __('Map Language Code', 'locatoraid'),
				'help'	=> $lang_help
				);
		}

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}