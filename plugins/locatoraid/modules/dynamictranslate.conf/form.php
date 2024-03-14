<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Dynamictranslate_Conf_Form_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$ret = array();

		$langList = get_available_languages();
		$appSettings = $this->app->make('/app/settings');

		$ret['translate:help'] = __('Here you can translate your custom dynamically created content, such as country names, product labels, custom field labels etc.', 'locatoraid');

	// country list
		$readLocationCommand = $this->app->make('/locations/commands/read');
		$args = array();
		$args[] = array( 'distinct', 'country' );
		$resList = $readLocationCommand->execute( $args );

		$countryList = [];
		foreach( $resList as $e ){
			$e = current( $e );
			if( ! $e ) continue;
			$e = trim( $e );
			if( ! $e ) continue;
			$countryList[ $e ] = $e;
		}

		foreach( $countryList as $country ){
			$pname = 'translate:' . $country;
			$pname = str_replace( ' ', '_', $pname );

			$ret[ $pname ] = '<small>' . __('Country', 'locatoraid') . '</small> <b>' . esc_html($country) . '</b>';
			foreach( $langList as $lang ){
				$ret[ $pname  . ':' . $lang ] = array(
					'input'	=> $this->app->make('/form/text'),
					'label'	=> $lang,
					);
			}
		}

	// product list
		if( $this->app->has_module('products') ){
			$readProductCommand = $this->app->make('/products/commands/read');
			$args = array();
			$args[] = array( 'distinct', 'title' );
			$resList = $readProductCommand->execute( $args );

			$productList = [];
			foreach( $resList as $e ){
				$e = current( $e );
				$productList[ $e ] = $e;
			}

			foreach( $productList as $product ){
				$pname = 'translate:' . $product;
				$pname = str_replace( ' ', '_', $pname );

				$ret[ $pname ] = '<small>' . __('Product', 'locatoraid') . '</small> <b>' . esc_html($product) . '</b>';
				foreach( $langList as $lang ){
					$ret[ $pname . ':' . $lang ] = array(
						'input'	=> $this->app->make('/form/text'),
						'label'	=> $lang,
						);
				}
			}
		}

	// custom fields
		if( $this->app->has_module('custom_fields') ){
			$cfieldList = [];
			for( $ii = 1; $ii <= 20; $ii++ ){
				$k1 = 'fields:misc' . $ii . ':label';
				$k2 = 'fields:misc' . $ii . ':use';

				if( ! $appSettings->get($k2) ) continue; 
				$cfield = $appSettings->get($k1); 
				if( ! $cfield ) continue;

				foreach( $langList as $lang ){
					$pname = 'translate:' . $cfield;
					$pname = str_replace( ' ', '_', $pname );

					$ret[ $pname ] = '<small>' . __('Custom Field', 'locatoraid') . '</small> <b>' . esc_html($cfield) . '</b>';
					$ret[ $pname . ':' . $lang ] = array(
						'input'	=> $this->app->make('/form/text'),
						'label'	=> $lang,
						);
				}
			}
		}

		return $ret;
	}
}