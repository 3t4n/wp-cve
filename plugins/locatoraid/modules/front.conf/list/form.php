<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_List_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();
		$app_settings = $this->app->make('/app/settings');

		$this_field_pname = 'front_list:advanced';
		$this_advanced = $app_settings->get($this_field_pname);

		if( $this_advanced ){
			$this_field_pname = 'front_list:template';
			$return[ $this_field_pname ] = $this->app->make('/form/textarea')
				->set_rows( 14 )
				;
		}
		else {
			$p = $this->app->make('/locations/presenter');
			$fields = $p->fields_labels();

			foreach( $fields as $fn => $flabel ){
				$checkboxes = array( 'show', 'w_label' );
				foreach( $checkboxes as $ch ){
					$this_field_pname = 'front_list:' . $fn  . ':' . $ch;
					$this_field_conf = $app_settings->get($this_field_pname);

					if( ($this_field_conf === TRUE) OR ($this_field_conf === FALSE) ){
					}
					else {
						$return[ $this_field_pname ] = 
							$this->app->make('/form/checkbox')
						;
					}
				}
			}
		}

		return $return;
	}
}