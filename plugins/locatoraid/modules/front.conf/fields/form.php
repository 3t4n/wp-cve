<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Fields_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();

		$app_settings = $this->app->make('/app/settings');

		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields();
		$no_label_for = array('name', 'address');

		foreach( $fields as $fn => $flabel ){
			if( ! in_array($fn, $no_label_for) ){
				$return[ 'fields:' . $fn  . ':label' ] = 
					$this->app->make('/form/text')
					;
			}

			$checkboxes = array( 'use' );
			foreach( $checkboxes as $ch ){
				$this_field_pname = 'fields:' . $fn  . ':' . $ch;
				$this_field_conf = $app_settings->get($this_field_pname);

				if( ($this_field_conf === TRUE) OR ($this_field_conf === FALSE) ){
				}
				else {
					$return[ $this_field_pname ] = 
						$this->app->make('/form/checkbox')
						;

					if( substr($fn, 0, strlen('misc')) == 'misc' ){
						$this_field_pname2 = 'fields:' . $fn  . ':noconvert';
						$return[ $this_field_pname2 ] = 
							$this->app->make('/form/checkbox')
							;
					}
				}
			}
		}

		return $return;
	}
}