<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Helper_HC_MVC extends _HC_MVC
{
	protected function _parse_form_inputs( $inputs_conf = array() )
	{
		$inputs = array();
		$labels = array();
		$validators = array();
		$helps = array();

		foreach( $inputs_conf as $name => $input_conf ){
			if( ! is_array($input_conf) ){
				$inputs[ $name ] = $input_conf;
				continue;
			}

			if( isset($input_conf['input']) ){
				$inputs[ $name ] = $input_conf['input'];
			}

			if( isset($input_conf['validators']) ){
				$validators[ $name ] = $input_conf['validators'];
			}

			if( isset($input_conf['label']) ){
				$labels[ $name ] = $input_conf['label'];
			}

			if( isset($input_conf['help']) ){
				$helps[ $name ] = $input_conf['help'];
			}
		}

		$return = array( $inputs, $labels, $validators, $helps );
		return $return;
	}

	public function grab( $inputs_conf, $post = array() )
	{
		$nonce = isset( $_REQUEST['hc_nonce'] ) ? $_REQUEST['hc_nonce'] : '';
		if( ! wp_verify_nonce( $nonce, 'locatoraid' ) ){
			die( 'Security check failed' ); 
		}

		$values = array();
		list( $inputs, $labels, $validators, $helps ) = $this->_parse_form_inputs( $inputs_conf );

		foreach( $inputs as $name => $input ){
			if( is_object($input) && method_exists($input, 'grab') ){
				$input_value = $input->grab( $name, $post );
				if( $input_value !== NULL ){
					$values[ $name ] = $input_value;
				}
			}
			elseif( is_string($input) ){
				$v = '';
				$postName = 'hc-' . $name;
				if( isset($post[$postName]) ){
					$v = $post[$postName];
				}
				$values[ $name ] = $v;
			}
		}

		$session = $this->app->make('/session/lib');

		$session
			->set_flashdata('form_values', $values)
			;

		$errors = $this->app->make('/validate/helper')
			->validate( $values, $validators )
			;
		if( $errors ){
			$session
				->set_flashdata('form_errors', $errors)
				;
		}

		$return = array( $values, $errors );
		return $return;
	}

	public function render( $attr = array() )
	{
		$default_attr = array(
			'action'			=> NULL,
			'method'			=> 'post',
			'accept-charset'	=> 'utf-8',
			);

		foreach( $default_attr as $k => $v ){
			if( ! isset($attr[$k]) ){
				$attr[$k] = $default_attr[$k];
			}
		}

		$out = $this->app->make('/html/element')->tag('form')
			->add_attr('class', 'hcj2-observe' )
			;

		foreach( $attr as $k => $v ){
			$out
				->add_attr( $k, $v )
				;
		}

		$nonceVal = wp_create_nonce( 'locatoraid' );
		$out->add( '<input type="hidden" name="hc_nonce" value="' . $nonceVal . '"/>' );

		$out = $this->app
			->after( array($this, __FUNCTION__), $out )
			;

		return $out;
	}

	public function prepare_render( $inputs_conf, $values = array() )
	{
		list( $inputs, $labels, $validators, $helps ) = $this->_parse_form_inputs( $inputs_conf );
		$id = 'hc2_' . hc_random();

		$session = $this->app->make('/session/lib');

		$session_values = $session->flashdata('form_values');
		$entered_values = $session_values ? $session_values : array();
		if( $entered_values ){
			$values = array_merge( $values, $entered_values );
		}

		$session_errors = $session->flashdata('form_errors');
		$errors = $session_errors ? $session_errors : array();

		$return = array();

		foreach( $inputs as $name => $input ){
			$value = isset($values[$name]) ? $values[$name] : NULL;
			$label = isset($labels[$name]) ? $labels[$name] : NULL;
			$error = isset($errors[$name]) ? $errors[$name] : NULL;
			$help = isset($helps[$name]) ? $helps[$name] : NULL;

			if( is_object($input) && method_exists($input, 'render') ){
				$input_view = $input
					->render( $name, $value )
					;

				if( $label OR $error ){
					$input_view = $this->app->make('/html/label-input')
						->set_content( $input_view )
						;

					if( $label ){
						$input_view
							->set_label( $label )
							;
					}

					if( $error ){
						$input_view
							->set_error( $error )
							;
					}

					if( $help ){
						$input_view
							->set_help( $help )
							;
					}

				}
			}
			else {
				$input_view = $input;
			}

			$return[$name] = $input_view;
		}

		return $return;
	}

	public function render_inputs( $inputs = array(), $columns_conf = array() )
	{
		if( ! $columns_conf ){
			$columns_conf = array_keys($inputs);
		}

		$out = $this->app->make('/html/list')
			->set_gutter(1)
			;

		reset( $columns_conf );
		foreach( $columns_conf as $columns ){
			if( ! is_array($columns) ){
				$columns = array($columns);
			}

			$this_inputs = array();
			$this_columns = array();
			foreach( $columns as $column ){
				$final_columns = array();

				if( ! is_array($column) ){
					$column = array($column);
				}

				foreach( $column as $input_name ){
					if( strpos($input_name, '*') === FALSE ){
						if( isset($inputs[$input_name]) ){
							$this_inputs[$input_name] = $inputs[$input_name];
							unset( $inputs[$input_name] );
							$final_columns[] = $input_name;
						}
					}
					else {
						$re = '/' . str_replace('*', '.+', $input_name) . '/i';
						$names = array_keys( $inputs );
						reset( $names );
						foreach( $names as $k ){
							if( ! preg_match($re, $k) ){
								continue;
							}

							$this_inputs[$k] = $inputs[$k];
							unset( $inputs[$k] );

							$final_columns[] = $k;
						}
					}
				}

				$this_columns[] = $final_columns;
			}

			if( count($columns) > 1 ){
				$this_view = $this->_render_inputs_columns( $this_inputs, $this_columns );
			}
			else {
				$this_view = $this->_render_inputs_stack( $this_inputs );
			}

			$out
				->add( $this_view )
				;
		}

		if( $inputs ){
			$remaining_view = $this->_render_inputs_stack( $inputs );
			$out
				->add( $remaining_view )
				;
		}

		return $out;
	}

	protected function _render_inputs_stack( $inputs = array() )
	{
		$out = $this->app->make('/html/list')
			->set_gutter(1)
			;

		reset( $inputs );
		foreach( $inputs as $name => $input_view ){
			$out
				->add( $input_view )
				;
		}
		return $out;
	}

	protected function _render_inputs_columns( $inputs = array(), $columns = array() )
	{
		if( ! $columns ){
			return $this->_render_inputs_stack($inputs);
		}

		$grid_widths = array( 1 => 12, 2 => 6, 3 => 4, 4 => 3 );
		$grid_width = $grid_widths[ count($columns) ];

		$out = $this->app->make('/html/grid')
			->set_gutter(3)
			;

		foreach( $columns as $column ){
			$column_inputs = array();
			foreach( $column as $input_name ){
				$column_inputs[$input_name] = $inputs[$input_name];
			}

			$column_view = $this->_render_inputs_stack( $column_inputs );
			$out
				->add( $column_view, $grid_width )
				;
		}

		return $out;
	}
}