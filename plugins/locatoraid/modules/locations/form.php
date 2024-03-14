<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array(
			'name' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Name', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required')
					),
				),

			'street1' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Street Address 1', 'locatoraid'),
				),

			'street2' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Street Address 2', 'locatoraid'),
				),

			'city' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('City', 'locatoraid'),
				),

			'state' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('State', 'locatoraid'),
				),

			'zip' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Zip Code', 'locatoraid'),
				),

			'country' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Country', 'locatoraid'),
				),

			'phone' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> (isset($labels['phone']) && strlen($labels['phone'])) ? $labels['phone'] : __('Phone', 'locatoraid'),
				),

			'website' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> (isset($labels['website']) && strlen($labels['website'])) ? $labels['website'] : __('Website', 'locatoraid'),
				),
			);

		$return = $this->app
			->after( $this, $return )
			;

		$locale = get_user_locale();
		$app_settings = $this->app->make('/app/settings');
	// remove unneeded and adjust labels if needed
		$always_show = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country');
		$input_names = array_keys( $return );
		foreach( $input_names as $k ){
			if( ! in_array($k, $always_show) ){
				$this_field_pname = 'fields:' . $k  . ':use';
				$this_field_conf = $app_settings->get($this_field_pname);
				if( ! $this_field_conf ){
					unset( $return[$k] );
					continue;
				}

				$this_field_pname = 'fields:' . $k  . ':label';
				$thisLabel = $app_settings->get($this_field_pname);
				if( (null !== $thisLabel) && strlen($thisLabel) ){
				// translate if needed
					$propName2 = 'translate:' . $thisLabel . ':' . $locale;
					$translatedText = $app_settings->get( $propName2 );
					if( $translatedText ){
						$thisLabel = $translatedText;
					}
					$return[$k]['label'] = $thisLabel;
				}
			}
		}

		$presenter = $this->app->make( '/locations/presenter' );
		foreach( array_keys($return) as $k ){
			if( isset($return[$k]['label']) ){
				$return[$k]['label'] = $presenter->esc_html( $return[$k]['label'] );
			}
		}

		return $return;
	}
}