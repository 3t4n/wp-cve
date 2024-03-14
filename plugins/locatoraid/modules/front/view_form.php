<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Form_LC_HC_MVC extends _HC_MVC
{
	public function render( $params = array() )
	{
		$form = $this->app->make('/front/form');
		$form_inputs = $form->inputs();

		$useLocate = isset( $params['locate'] ) ? $params['locate'] : 1;
		if( 'auto' == $useLocate ){
			$params['start'] = 'no';
		}

		$app_settings = $this->app->make('/app/settings');
		$locale = get_user_locale();

		if( isset($params['where-product']) && $params['where-product'] ){
			if( FALSE !== strpos($params['where-product'], '_') ){
				// if( isset($form_inputs['product']) && method_exists($form_inputs['product'], 'options') ){
				if( isset($form_inputs['product']) ){
					if( is_array($form_inputs['product'] ) && isset($form_inputs['product']['input']) ){
						$input = $form_inputs['product']['input'];
					}
					else {
						$input = $form_inputs['product'];
					}

					$options = $input->options();

					$filteredProducts = explode( '_', $params['where-product'] );
					$filteredProducts = array_map( function($e){ return trim($e); }, $filteredProducts );
					$filteredProducts = array_combine( $filteredProducts, $filteredProducts );

					$options = array_intersect_key( $options, $filteredProducts );

					$input->set_options( $options );
				}
			}
			else {
				unset( $form_inputs['product'] );
			}
		}

		$link_params = array(
			'search'	=> '_SEARCH_',
			'product'	=> '_PRODUCT_',
			'lat'		=> '_LAT_',
			'lng'		=> '_LNG_',
			);

	// country select
		if( isset($params['search-bias-country']) && $params['search-bias-country'] ){
			$countryString = $params['search-bias-country'];
			if( false !== strpos($countryString, ',') ){
				$countryStringList = explode( ',', $countryString );
				$countryOption = [];
				foreach( $countryStringList as $k ){
					$k = trim( $k );

					$thisLabel = $k;

				// translate if needed
					$propName = 'translate:' . $thisLabel . ':' . $locale;
					$translatedText = $app_settings->get( $propName );
					if( $translatedText ){
						$thisLabel = $translatedText;
					}

					$countryOption[ $k ] = $thisLabel;
				}
				$countrySelect = $this->app->make('/form/select')
					->set_options( $countryOption )
					->render( 'country', current($countryOption) )
					->add_attr('class', 'hcj2-country-select')
					->add_attr('style', 'display: block; width: 100%;')
					->add_attr('id', 'locatoraid-search-country-select')
					;
				$form_inputs['country'] = $countrySelect;

				$link_params['country'] = '_COUNTRY_';
			}
		}

	// radius select
		if( isset($params['radius-select']) && $params['radius-select'] ){
			if( isset($params['radius']) && (count($params['radius']) > 1) ){
				$radiusOptions = array();
				$measure = $app_settings->get('core:measure');
				foreach( $params['radius'] as $e ){
					$radiusOptions[ $e ] = $e . ' ' . $measure;
				}

				$radiusSelect = $this->app->make('/form/select')
					->set_options( $radiusOptions )
					->render( 'radius', current($params['radius']) )
					->add_attr('class', 'hcj2-radius-select')
					->add_attr('id', 'locatoraid-search-radius-select')
					;

				$form_inputs['radius'] = $radiusSelect;
			}
		}

		$form_values = array();
		if( isset($params['start']) && ($params['start'] != 'no') ){
			$form_values['search'] = $params['start'];
		}

		if( isset($form_inputs['product']) ){
			$input = ( is_array( $form_inputs['product'] ) && isset($form_inputs['product']['input']) ) ? $form_inputs['product']['input'] : $form_inputs['product'];

			if( method_exists($input, 'options') ){
				$defaultOn = $app_settings->get( 'front:product:default_on' );
				if( $defaultOn ){
					$thisOptions = $input->options();
					$form_values['product'] = array_keys( $thisOptions );
				}
			}
		}

		$search_form_id = 'hclc_search_form' . '_' . rand( 100, 999 );
		$search_form_class = 'hclc_search_form_class';

		if( isset($params['id']) && $params['id'] ){
			$link_params['id'] = $params['id'];
			unset($params['radius']);
		}
		else {
			if( isset($params['limit']) ){
				$link_params['limit'] = $params['limit'];
			}
			if( ! isset($form_inputs['radius']) ){
				if( isset($params['radius']) && (count($params['radius']) <= 1) ){
					$link_params['radius'] = $params['radius'];
				}
			}
			if( isset($params['sort']) ){
				if( substr($params['sort'], -strlen('-reverse')) == '-reverse' ){
					$link_params['sort'] = array( substr($params['sort'], 0, -strlen('-reverse')), 'desc');
				}
				else {
					$link_params['sort'] = $params['sort'];
				}
			}
		}
// _print_r( $link_params );
// exit;

		reset( $params );
		foreach( $params as $k => $v ){
			if( ! (substr($k, 0, strlen('where-')) == 'where-') ){
				continue;
			}
			$k = substr( $k, strlen('where-') );
			if( array_key_exists($k, $link_params) && ('product' != $k) ) continue;
			$link_params[$k] = $v;
		}

		if( ! $link_params['product'] ){
			$link_params['product'] = '_PRODUCT_';
		}

		$link = $this->app->make('/http/uri')
			->mode('api')
			->url('/search', $link_params )
			;

	// radius link which will give us links to results
		$radius_link = '';
		if( ! isset($form_inputs['radius']) ){
			if( isset($params['radius']) && (count($params['radius']) > 1) ){
				$radius_link_params = $link_params;

				$radius_link_params['radius'] = $params['radius'];
				unset( $radius_link_params['sort'] );
				// unset( $radius_link_params['limit'] );

				$radius_link = $this->app->make('/http/uri')
					->mode('api')
					->url('/search/radius', $radius_link_params )
					;
			}
		}

		$form_attr = array(
			'id'				=> $search_form_id,
			'action'			=> $link,
			'data-radius-link'	=> $radius_link,
			'class'				=> 'hc-mb2 ' . $search_form_class,
			);
		if( isset($params['start']) && ($params['start'] != 'no') ){
			$form_attr['data-start'] = $params['start'];
		}

		if( isset($params['locate']) ){
			$form_attr['data-locate'] = $params['locate'];
		}

		$where_param = array();
		reset( $params );
		$take_where = array('where-country', 'where-zip', 'where-state', 'where-city');
		foreach( $params as $k => $v ){
			if( ! in_array($k, $take_where) ){
				continue;
			}
			if( (null === $v) OR (! strlen($v)) ){
				continue;
			}

			$short_k = substr($k, strlen('where-'));
			$where_param[] = $short_k . ':' . $v;
		}

		if( $where_param ){
			$where_param = join(' ', $where_param);
			$form_attr['data-where'] = $where_param;
		}

// prevent default submit before our scripts are loaded
$form_attr['onsubmit'] = 'return false;';

		$helper = $this->app->make('/form/helper');
		$display_form = $helper->render( $form_attr );

		$inputs_view = $helper->prepare_render( $form_inputs, $form_values );

		if( isset($params['start']) && ($params['start'] == 'no') ){
			$inputs_view['search']
				->add_attr( 'required', 1 )
				;
		}

// disable inputs and hide button by default, then activate it by javascript
// $inputs_view['search']
	// ->add_attr( 'disabled', 'disabled' )
	// ;

		$out_inputs = $this->app->make('/html/list')
			->set_gutter(2)
			;

		if( isset($inputs_view['country']) ){
			$inputs_view['search'] = '<div class="hc-grid-3-1"><div>' . $inputs_view['search'] . '</div><div id="locatoraid-search-country-select-container">' . $inputs_view['country'] . '</div></div>';
			unset( $inputs_view['country'] );
		}

		if( isset($inputs_view['radius']) ){
			$inputs_view['search'] = '<div class="hc-lg-flex-auto-grid hc-lg-mxn2"><div class="hc-lg-px2">' . $inputs_view['search'] . '</div><div class="hc-lg-px2 hc-lg-align-center" id="locatoraid-search-radius-select-container">' . $inputs_view['radius'] . '</div></div>';
			unset( $inputs_view['radius'] );
		}

	// locate me
		$locateMeLabel = $app_settings->get('front_text:locate_me');
		if( $locateMeLabel === NULL ){
			$locateMeLabel = __('Locate Me', 'locatoraid');
		}
		else {
			$locateMeLabel = __($locateMeLabel, 'locatoraid');
		}

		$linkLocateMe = $this->app->make('/html/element')->tag('a')
			->add_attr('href', '#')
			->add_attr('class', 'hcj2-locate-me')
			->add_attr('id', 'locatoraid-search-form-locate-me')
			->add( $locateMeLabel )
			;

		$myLocationLabel = $app_settings->get('front_text:my_location');
		if( $myLocationLabel === NULL ){
			$myLocationLabel = __('My Location', 'locatoraid');
		}
		else {
			$myLocationLabel = __($myLocationLabel, 'locatoraid');
		}

		$linkMyLocation = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hcj2-my-location')
			// ->add_attr('class', 'hc-hide')
			->add_attr('id', 'locatoraid-search-form-my-location')
			->add( $myLocationLabel )
			->add_attr('style', 'display: none;')
			;

		$btnLabel = $app_settings->get('front_text:reset_my_location');
		if( $btnLabel === NULL ){
			$btnLabel = __('Reset', 'locatoraid');
		}
		else {
			$btnLabel = __($btnLabel, 'locatoraid');
		}

		$btnResetLocation = $this->app->make('/html/element')->tag('input')
			->add_attr('type', 'button')
			->add_attr('title', $btnLabel )
			->add_attr('value', $btnLabel )
			->add_attr('class', 'hc-block')
			->add_attr('class', 'hcj2-reset-my-location')
			->add_attr('id', 'locatoraid-search-form-reset-location')
			->add_attr('style', 'display: none;')
			;

	// add after search
		if( $useLocate ){
			$new_inputs_view = array();
			foreach( $inputs_view as $k => $v ){
				$new_inputs_view[ $k ] = $v;
				if( 'search' === $k ){
					$new_inputs_view['locate'] = $linkLocateMe;
					$new_inputs_view['locate2'] = $linkMyLocation;
				}
			}
			$inputs_view = $new_inputs_view;
		}

		foreach( $inputs_view as $k => $input ){
			$input_view = $this->app->make('/html/element')->tag('div')
				->add_attr('id', 'locatoraid-search-form-' . $k)
				->add( $input )
				;
			$out_inputs
				->add( $input_view )
				;
		}

		$out_inputs = $this->app->make('/html/element')->tag('div')
			->add_attr('id', 'locatoraid-search-form-inputs')
			->add( $out_inputs )
			;

		$hiddenInputs = array();
		$hiddenInputs[] = $this->app->make('/form/hidden')
			// ->render( 'lat', '30.0121654' )
			->render( 'locatelat' )
			->add_attr('id', 'locatoraid-search-form-lat')
			;
		$hiddenInputs[] = $this->app->make('/form/hidden')
			// ->render( 'lng', '-95.58549839999999' )
			->render( 'locatelng' )
			->add_attr('id', 'locatoraid-search-form-lng')
			;

		foreach( $hiddenInputs as $e ){
			$out_inputs->add( $e );
		}

		$btn_label = $app_settings->get('front_text:submit_button');
		if( $btn_label === NULL ){
			$btn_label = __('Search', 'locatoraid');
		}
		else {
			$btn_label = __($btn_label, 'locatoraid');
		}

		$btnSearch = $this->app->make('/html/element')->tag('input')
			->add_attr('type', 'submit')
			->add_attr('title', $btn_label )
			->add_attr('value', $btn_label )
			->add_attr('class', 'hc-block')
			->add_attr('id', 'locatoraid-search-form-button')
			;

		$out_buttons = $this->app->make('/html/element')->tag('div')
			->add( $btnSearch )
			;

		if( $useLocate ){
			$out_buttons
				->add( $btnResetLocation )
				;
		}

		$form_view = $this->app->make('/html/grid')
			->set_gutter(2)
			;

		$form_view
			->add( $out_inputs, 8 )
			->add( $out_buttons, 4 )
			;

	// more results link
		$more_results_label = $app_settings->get('front_text:more_results');
		if( $more_results_label === NULL ){
			$more_results_label = __('More Results', 'locatoraid');
		}
		else {
			$more_results_label = __($more_results_label, 'locatoraid');
		}

		$more_results_link = $this->app->make('/html/element')->tag('a')
			->add_attr('class', 'hcj2-more-results')
			->add_attr('id', 'locatoraid-search-more-results')
			->add( $more_results_label )
			->add_attr('style', 'display: none; cursor: pointer;')
			;

		$form_view = $this->app->make('/html/element')->tag('div')
			->add( $form_view )
			->add_attr('id', 'locatoraid-search-form-inputs-button')
			;

		$form_view = $this->app->make('/html/list')
			->set_gutter(2)
			// ->add( $linkLocateMe )
			// ->add( $btnLocateMe )
			// ->add( 'OR' )
			->add( $form_view )
			->add( $more_results_link )
			;

		$display_form
			->add( $form_view )
			;

		if( isset($params['id']) && $params['id'] ){
			$display_form = $this->app->make('/html/element')->tag('div')
				->add( $display_form )
				->add_attr('class', 'hc-hide')
				;
		}

		return $display_form;
	}
}