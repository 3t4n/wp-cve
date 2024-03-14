<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Fields_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$app_settings = $this->app->make('/app/settings');
		$values = $app_settings->get();
		$form = $this->app->make('/front.conf/fields/form');

		$helper = $this->app->make('/form/helper');
		$inputs_view = $helper->prepare_render( $form->inputs(), $values );

		$out_inputs = $this->app->make('/html/table-responsive');

		$header = array(
			'field'	=> __('Field', 'locatoraid') . ' / ' . __('Label', 'locatoraid'),
			);

		$rows = array();
		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields();

	// make misc fields come last
		$finalFields = array();
		$miscFields = array();
		foreach( $fields as $k => $v ){
			if( substr($k, 0, strlen('misc')) == 'misc' ){
				$miscFields[$k] = $v;
			}
			else {
				$finalFields[$k] = $v;
			}
		}
		if( $miscFields ){
			foreach( $miscFields as $k => $v ){
				$finalFields[$k] = $v;
			}
		}

		foreach( $finalFields as $fn => $flabel ){
			$label_pname = 'fields:' . $fn  . ':label';
			$use_pname = 'fields:' . $fn  . ':use';
			$noconvert_pname = 'fields:' . $fn  . ':noconvert';

			$this_row = array();
			$fieldView = $this->app->make('/html/list')
				->set_gutter(2)
				;

			if( isset($inputs_view[$use_pname]) ){
				$this_row['use'] = $inputs_view[$use_pname];
			}
			else {
				$this_field_conf = $app_settings->get($use_pname);
				if( $this_field_conf === TRUE ){
					$this_row['use'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('check') )
						->add_attr('class', 'hc-olive')
						;
				}
				elseif( $this_field_conf === FALSE ){
					$this_row['use'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('times') )
						->add_attr('class', 'hc-maroon')
						;
				}
			}

			$flabel = $this->app->make('/html/list-inline')
				->set_gutter(0)
				->add( $this_row['use'] )
				->add( $flabel )
				;

			$fieldView
				->add( $flabel )
				;

			if( isset($inputs_view[$label_pname]) ){
				$fieldView
					->add(
						$inputs_view[$label_pname]
							->add_attr('class', 'hc-block')
						)
					;
			}

			if( isset($inputs_view[$noconvert_pname]) ){
				$this_row['noconvert'] =  $inputs_view[$noconvert_pname];
				$flabel2 = $this->app->make('/html/list-inline')
					->set_gutter(0)
					->add( $this_row['noconvert'] )
					->add( __('Skip automatic convert to HTML', 'locatoraid') )
					;
				$fieldView->add( $flabel2 );
			}

			$this_row['field'] = $fieldView;

			if( isset($inputs_view[$use_pname]) ){
				$this_row['use'] = $inputs_view[$use_pname];
			}
			else {
				$this_field_conf = $app_settings->get($use_pname);
				if( $this_field_conf === TRUE ){
					$this_row['use'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('check') )
						->add_attr('class', 'hc-olive')
						;
				}
				elseif( $this_field_conf === FALSE ){
					$this_row['use'] = $this->app->make('/html/element')->tag('span')
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

		$out_buttons = $this->app->make('/html/list')
			->set_gutter(2)
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
			->url('/front.conf/fields/update')
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
}