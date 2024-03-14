<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Map_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$values = $this->app->make('/app/settings')->get();
		$form = $this->app->make('/front.conf/map/form');

		$helper = $this->app->make('/form/helper');
		$inputs_view = $helper->prepare_render( $form->inputs(), $values );

		$app_settings = $this->app->make('/app/settings');
		$this_field_pname = 'front_map:advanced';
		$this_advanced = $app_settings->get($this_field_pname);

		$links = $this->app->make('/html/list')
			->set_gutter(2)
			;

		if( $this_advanced ){
			$out_inputs = $this->_render_advanced( $inputs_view );

			$links
				->add(
					$this->app->make('/html/ahref')
						->to('/front.conf/map/mode/basic')
						->add( __('Switch To Basic Mode', 'locatoraid') )
						->add_attr('class', 'hc-theme-btn-submit')
						->add_attr('class', 'hc-block')
					)
				;

			$this_field_pname = 'front_map:template';
			$this_value_modified = $app_settings->get($this_field_pname);
			$this_value_default = $this->app->make('/front/view/map/template')
				->render()
				;

			if( $this_value_modified != $this_value_default ){
				$links
					->add(
						$this->app->make('/html/ahref')
							->to('/front.conf/map/mode/reset')
							->add( $this->app->make('/html/icon')->icon('times') )
							->add( __('Reset Template', 'locatoraid') )
							->add_attr('class', 'hc-theme-btn-submit')
							->add_attr('class', 'hcj2-confirm')
							->add_attr('class', 'hc-theme-btn-danger')
							->add_attr('class', 'hc-block')
						)
					;
			}
		}
		else {
			$out_inputs = $this->_render_simple( $inputs_view );

			$links
				->add(
					$this->app->make('/html/ahref')
						->to('/front.conf/map/mode/advanced')
						->add( __('Switch To Advanced Mode', 'locatoraid') )
						->add_attr('class', 'hc-theme-btn-submit')
						->add_attr('class', 'hc-block')
					)
				;
		}

		$out_buttons = $this->app->make('/html/list')
			->set_gutter(2)
			->add( $links )
			->add(
				$this->app->make('/html/element')->tag('input')
					->add_attr('type', 'submit')
					->add_attr('title', __('Save', 'locatoraid') )
					->add_attr('value', __('Save', 'locatoraid') )
					->add_attr('class', 'hc-theme-btn-submit')
					->add_attr('class', 'hc-theme-btn-primary')
					->add_attr('class', 'hc-block')
				)
			;

		$link = $this->app->make('/http/uri')
			->url('/front.conf/map/update')
			;
		$out = $helper
			->render( array('action' => $link) )
			->add(
				$this->app->make('/html/grid')
					->set_gutter(2)
					->add( $out_inputs, 9, 12 )
					->add( $out_buttons, 3, 12 )
				)
			;

		return $out;
	}

	protected function _render_simple( $inputs_view )
	{
		$app_settings = $this->app->make('/app/settings');
		$out_inputs = $this->app->make('/html/table-responsive');

		$header = array(
			'field'			=> __('Field', 'locatoraid'),
			'show_in_map'	=> __('Show On Map', 'locatoraid'),
			'w_label'	=> __('With Label', 'locatoraid'),
			);

		$rows = array();
		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields_labels();
		foreach( $fields as $fn => $flabel ){
			$show_pname = 'front_map:' . $fn . ':show';
			$wlabel_pname = 'front_map:' . $fn . ':w_label';

			$this_row = array();
			$this_row['field'] = $flabel;

			if( isset($inputs_view[$show_pname]) ){
				$this_row['show_in_map'] = $inputs_view[$show_pname];
			}

			if( isset($inputs_view[$wlabel_pname]) ){
				$this_row['w_label'] = $inputs_view[$wlabel_pname];
			}
			else {
				$this_field_conf = $app_settings->get($wlabel_pname);
				if( $this_field_conf === TRUE ){
					$this_row['w_label'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('check') )
						->add_attr('class', 'hc-olive')
						;
				}
				elseif( $this_field_conf === FALSE ){
					$this_row['w_label'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('times') )
						->add_attr('class', 'hc-maroon')
						;
				}
			}

			$rows[$fn] = $this_row;
		}

		$out_inputs
			->set_header( $header )
			->set_rows( $rows )
			;

		$out_inputs = $this->app->make('/html/list')
			->set_gutter(2)
			->add( $out_inputs )
			;

		return $out_inputs;
	}

	protected function _render_advanced( $inputs_view )
	{
		$helper = $this->app->make('/form/helper');
		$out = $helper->render_inputs( $inputs_view );
		return $out;
	}
}