<?php
if ( !defined('GP_SPACELY_INCLUDED') ):
	define('GP_SPACELY_INCLUDED', true);

	class GP_Spacely_Widget extends WP_Widget
	{
		var $field_wrapper_element ='div';
		
		public function __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		{
			parent::__construct($id_base, $name, $widget_options, $control_options);
		}
		
		function text_field($options)
		{
			$defaults = array(
				'id' => '',
				'name' => '',
				'value' => '',
				'class' => 'widefat',
				'wrapper_class' => ''
			);
			$options = array_merge($defaults, $options);
			extract($options);
			$field_id = $this->get_field_id( $id );
			$field_name = $this->get_field_name( $name );

			// open tag
			$attr_str = $this->build_attr_str( array('class' => $wrapper_class) );
			$output = sprintf('<%s %s>', $this->field_wrapper_element, $attr_str);
			
			// add label
			$atts = array(
				'for' => $field_id
			);
			$output .= sprintf('<label %s>%s</label>', $this->build_attr_str($atts), $label );

			// add field-specific class
			$class = !empty($class)
					 ? $class . ' ' . 'number_field'
					 : 'text_field';

			// add input
			$atts = array(
				'id' => $field_id,
				'class' => $class,
				'name' => $field_name,
				'type' => 'text',
				'value' => $value
			);
			$output .= sprintf('<input %s />', $this->build_attr_str($atts) );

			//close tag
			$output .= sprintf('</%s>', $this->field_wrapper_element);
			return $output;
		}

		function number_field($options)
		{
			$defaults = array(
				'id' => '',
				'name' => '',
				'value' => '',
				'class' => 'widefat',
				'wrapper_class' => ''
			);
			$options = array_merge($defaults, $options);
			extract($options);
			$field_id = $this->get_field_id( $id );
			$field_name = $this->get_field_name( $name );

			// open tag
			$attr_str = $this->build_attr_str( array('class' => $wrapper_class) );
			$output = sprintf('<%s %s>', $this->field_wrapper_element, $attr_str);
			
			// add label
			$atts = array(
				'for' => $field_id
			);
			$output .= sprintf('<label %s>%s</label>', $this->build_attr_str($atts), $label );
			
			// add field-specific class
			$class = !empty($class)
					 ? $class . ' ' . 'number_field'
					 : 'number_field';

			// add input
			$atts = array(
				'id' => $field_id,
				'class' => $class,
				'name' => $field_name,
				'type' => 'number',
				'value' => $value
			);
			
			//add min/max if specified
			if ( isset($options['min']) ) {
				$atts['min'] = $options['min'];
			}
			
			if ( isset($options['max']) ) {
				$atts['max'] = $options['max'];
			}
			
			$output .= sprintf('<input %s />', $this->build_attr_str($atts) );

			//close tag
			$output .= sprintf('</%s>', $this->field_wrapper_element);
			return $output;
		}
		
		function build_attr_str($atts)
		{			
			array_walk ( $atts, array($this, 'render_attr'));
			return implode(' ', $atts);
		}
		
		function render_attr(&$val, $key)
		{
			$val = sprintf( '%s="%s"', $key, htmlentities($val) );
		}
	}

endif; // !defined(GP_SPACELY_INCLUDED)