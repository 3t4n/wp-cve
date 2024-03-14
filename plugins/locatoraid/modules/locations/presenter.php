<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Presenter_LC_HC_MVC extends _HC_MVC
{
	public function fields()
	{
		$return = array(
			'name'			=> __('Name', 'locatoraid'),
			'address'		=> __('Address', 'locatoraid'),
			'distance'		=> __('Distance', 'locatoraid'),
			'phone'			=> __('Phone', 'locatoraid'),
			'website'		=> __('Website', 'locatoraid'),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function esc_html( $ret )
	{
		if( null === $ret ){
			
		}
		elseif( is_array($ret) ){
			foreach( array_keys($ret) as $k ){
				$ret[$k] = $this->esc_html( $ret[$k] );
			}
		}
		elseif( is_object($ret) ){
		}
		else {
			$rep = [ ['<script', '</script>'], ['<!-- ', ' --!>'] ];
			$ret = str_ireplace( $rep[0], $rep[1], $ret );
		}

		return $ret;
	}

	public function fields_labels()
	{
		$return = $this->fields();

		$locale = get_user_locale();
		$app_settings = $this->app->make('/app/settings');
		$always_show = array('name', 'address');

		$keys = array_keys($return);
		foreach( $keys as $k ){
			if( ! in_array($k, $always_show) ){
				$this_field_pname = 'fields:' . $k  . ':use';
				$this_field_conf = $app_settings->get($this_field_pname);
				if( ! $this_field_conf ){
					unset( $return[$k] );
					continue;
				}
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
				$return[ $k ] = $thisLabel;
			}
		}

		foreach( array_keys($return) as $k ){
			$return[$k] = $this->esc_html( $return[$k] );
		}

		return $return;
	}

	public function database_fields()
	{
		$return = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country', 'phone', 'website', 'latitude', 'longitude');

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function present_icon_url( $data )
	{
		$return = NULL;

		$app_settings = $this->app->make('/app/settings');
		$icon_id = $app_settings->get('maps_google:icon');
		if( $icon_id ){
			$your_img_src = wp_get_attachment_image_src( $icon_id, 'full' );
			$have_img = is_array( $your_img_src );
			if( $have_img ){
				$return = $your_img_src[0];
			}
		}

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $data )
			;


		return $return;
	}

	public function present_icon( $data )
	{
		$icon_url = $this->present_icon_url( $data );

		if( ! $icon_url ){
			$icon_url = '//maps.google.com/mapfiles/ms/micons/red-dot.png';
		}

		$return = $this->app->make('/html/element')->tag('img')
			->add_attr('src', $icon_url)
			->add_attr('style', 'max-width:100%;')
			;
		return $return;
	}

	public function present_distance( $data )
	{
		$return = isset($data['distance']) ? $data['distance'] : NULL;
		if( ! $return ){
			$return = NULL;
			return $return;
		}

		$app_settings = $this->app->make('/app/settings');
		$measure = $app_settings->get('core:measure');

		if( $return < 1 ){
			$return = floor( $return * 100 ) / 100;
		}
		elseif( $return < 100 ){
			$return = ceil( $return * 10 ) / 10;
		}
		else {
			$return = ceil( $return );
		}
		$return = $return . ' ' . $measure;

		return $return;
	}

	public function present_address( $data )
	{
		$parts = array();
		$take = array( 'street1', 'street2', 'city', 'state', 'zip', 'country' );

		foreach( $take as $t ){
			$part = isset($data[$t]) ? $data[$t] : '';
			if( strlen($part) ){
				$parts[$t] = $part;
			}
			else {
				$parts[$t] = '';
			}
		}

		$app_settings = $this->app->make('/app/settings');

	// translate if needed
		$locale = get_user_locale();

		if( isset($parts['country']) && $parts['country'] ){
			$srcText = $parts['country'];
			$propName = 'translate:' . $srcText . ':' . $locale;
			$translatedText = $app_settings->get( $propName );
			if( $translatedText ){
				$parts['country'] = $translatedText;
			}
		}

		$template = $app_settings->get('locations_address:format');

		$template = trim($template);

		if( strlen($template) ){
			if( isset($parts['street2']) && strlen($parts['street2']) ){
				$parts['street'] = $parts['street1'] . "\n" . $parts['street2'];
			}
			else {
				$parts['street'] = $parts['street1'];
			}

			$return = $template;
			foreach( $parts as $k => $v ){
				$return = str_replace( '{' . strtoupper($k) . '}', $v, $return );
			}
		}
		else {
			$return = join(', ', $parts);
		}

		$return = trim( $return );
		$return = $this->esc_html( $return );
		$return = nl2br( $return, FALSE );

		return $return;
	}

	public function present_title( $data )
	{
		$return = isset($data['name']) ? $data['name'] : NULL;
		$return = $this->esc_html( $return );
		return $return;
	}

	protected function _prepare_front( $key, $value )
	{
		$return = $value;
		$app_settings = $this->app->make('/app/settings');

		switch( $key ){
			case 'website':
				$ok = FALSE;
				$value = trim($value);
				if( ! strlen($value) ){
					return;
				}

				$href = $value;
				$prfx = array('http://', 'https://', '//');
				foreach( $prfx as $prf ){
					if( substr($href, 0, strlen($prf)) == $prf ){
						$ok = TRUE;
						break;
					}
				}

				if( ! $ok ){
					$href = 'http://' . $href;
					// $href = '//' . $href;
				}

				$this_pname = 'fields:website:label';
				$this_label = $app_settings->get($this_pname);
				$this_label = strlen($this_label) ? $this_label : $value;

				$newWindow = $app_settings->get( 'front:links_new_window' );

				$return = '<a href="' . $href . '"';
				if( $newWindow ){
					$return .= ' target="_blank"';
				}
				$return .= '>' . $this_label . '</a>';
				break;

			case 'phone':
				$value = trim($value);
				if( ! strlen($value) ){
					return;
				}

				$return = '<a href="tel:' . $value . '" target="_blank">' . $value . '</a>';
				break;

			default:
				$this_noconvert_name = 'fields:' . $key . ':noconvert';
				$this_noconvert = $app_settings->get($this_noconvert_name);

				if( (! $this_noconvert) && is_string($value) ){
					$email_regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i';

					if(
						preg_match('/^misc/', $key) &&
						preg_match('/(\.jpg|\.png|\.gif|\.svg)$/i', $value)
						){
						$return = '<img src="' . $value . '" style="max-width: 95%;">';
					}
					elseif( '+' == substr($value, 0, 1) ){
						$return = '<a href="tel:' . $value . '" target="_blank">' . $value . '</a>';
					}
					elseif(
						preg_match('/^misc/', $key) &&
						(
						preg_match('/^https?\:\/\//', $value) OR
						preg_match('/^\/\//', $value)
						)
						){

						$app_settings = $this->app->make('/app/settings');
						$this_pname = 'fields:' . $key . ':label';
						$this_label = $app_settings->get($this_pname);
						$this_label = strlen($this_label) ? $this_label : $value;

						$return = '<a href="' . $value . '" target="_blank">' . $this_label . '</a>';
					}
					elseif(
						preg_match('/^misc/', $key) &&
						preg_match($email_regex, $value)
						){
						// $field_view = '<a href="mailto:' . $e[$f['name']] . '" target="_blank">' . $f['title'] . '</a>';
						// $field_view = '<a href="mailto:' . $e[$f['name']] . '" target="_blank">' . $f['title'] . '</a>';

						$app_settings = $this->app->make('/app/settings');
						$this_pname = 'fields:' . $key . ':label';
						$this_label = $app_settings->get($this_pname);
						$this_label = strlen($this_label) ? $this_label : $value;

						$return = '<a href="mailto:' . $value . '" target="_blank">' . $this_label . '</a>';
					}
				}

// if( in_array($key, array('misc1', 'misc2', 'misc3')) ){
// 	$images = array(
// 		1	=> 'url_to_image1',
// 		2	=> 'url_to_image2',
// 		);

// 	if( isset($images[$value]) ){
// 		$url = $images[$value];
// 		$return = '<img src="' . $url . '">';
// 	}
// }

				break;
		}

		$return = $this->esc_html( $return );

		return $return;
	}

	public function present_front( $data, $search = NULL, $search_coordinates = array() )
	{
		$return = $data;
		$return['address'] = $this->present_address( $return );
		$return['name'] = $this->present_title( $return );
		$return['website_url'] = isset( $return['website'] ) ? $return['website'] : '';

	// process to show urls and emails
		foreach( array_keys($return) as $k ){
			$return[$k] = $this->_prepare_front( $k, $return[$k] );
		}

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $search, $search_coordinates )
			;

		return $return;
	}
}