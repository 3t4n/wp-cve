<?php

/**
 * NSACMB2Types short summary.
 *
 * NSACMB2Types description.
 *
 * @version 1.0
 * @author Jake.Hulse
 */
class NSA_CMB2_Types //extends CMB2_Types
{
    private $plugin = null;
    private $field_type_object = null;

    public function __construct($plugin) {
        $this->plugin = $plugin;


        $Types = array(
            'nsa_section_start',
            'nsa_section_end',
            'radio_inline_pro',
        );



        foreach ($Types as $Type)
        {
            add_action( 'cmb2_render_'.$Type, array($this, 'render_'.$Type), 10, 5 );
            add_filter( 'cmb2_sanitize_'.$Type, array($this, 'sanitize_'.$Type), 10, 2 );
        	
        }
        

    }


    /**
     * Generates html for concatenated items
     * @since  1.1.0
     * @param  array   $args Optional arguments
     * @return string        Concatenated html items
     */
	public function concat_items( $args = array() ) {

		$method = isset( $args['method'] ) ? $args['method'] : 'select_option';
		unset( $args['method'] );

		$value = $this->field_type_object->field->escaped_value()
			? $this->field_type_object->field->escaped_value()
			: $this->field_type_object->field->args( 'default' );

		$concatenated_items = ''; $i = 1;

		$options = array();
		if ( $option_none = $this->field_type_object->field->args( 'show_option_none' ) ) {
			$options[ '' ] = $option_none;
		}
		$options = $options + (array) $this->field_type_object->field->options();
		foreach ( $options as $opt_value => $opt_label ) {

			// Clone args & modify for just this item
			$a = $args;

			$a['value'] = $opt_value;
			$a['label'] = $opt_label;

			// Check if this option is the value of the input
			if ( $value == $opt_value ) {
				$a['checked'] = 'checked';
			}

            if (substr($opt_value, 0, 4) === 'pro_') {
                $a['class'] = 'ispro';
                $a['ispro'] = true;

                if(!$this->plugin->ProEnabled) {
                    $a['readonly'] = 'readonly';
                    $a['disabled'] = 'disabled';
                }
            }

			$concatenated_items .= $this->$method( $a, $i++ );
		}

		return $concatenated_items;
	}
    function list_input_pro($args = array(), $i) {
        $defaults = array(
            'type'  => 'radio',
            'class' => 'cmb2-option',
            'name'  => $this->field_type_object->_name(),
            'id'    => $this->field_type_object->_id( $i ),
            'value' => $this->field_type_object->field->escaped_value(),
            'label' => '',
        );

        $a = $this->field_type_object->parse_args( $args, 'list_input', $defaults );
        if(isset($args['class']))
            $a['class'] .= " {$defaults['class']}";

		return sprintf( "\t" . '<li class="%s"><input%s/> <label for="%s">%s</label></li>' . "\n", $a['class'], $this->field_type_object->concat_attrs( $a, array( 'label' ) ), $a['id'], $a['label'] );
    }
    function render_radio_inline_pro( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
        $this->field_type_object = $field_type_object;

        $args = array();
        $a = $field_type_object->parse_args( $args, 'radio_inline', array(
            'class'   => 'cmb2-radio-list cmb2-list',
            'options' => $this->concat_items( array( 'label' => 'test', 'method' => 'list_input_pro' ) ),
            'desc'    => $field_type_object->_desc( true ),
        ) );

        echo sprintf( '<ul class="%s">%s</ul>%s', $a['class'], $a['options'], $a['desc'] );

    }
    function sanitize_radio_inline_pro( $override_value, $value ) {
        
        // Validate Input and set to '' if not valid
        //if ( ! is_email( $value ) ) {
        //    // Empty the value
        //    $value = '';
        //}
        return $value;
    }


    function render_nsa_section_start( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
        echo "<div id='{$field->args['id']}_section' class='nsa_cmb2_section'>
            <{$field->args['element']} id='{$field->args['id']}' class='nsa_cmb2_section_title'>{$field->args['header']}</{$field->args['element']}>
            <p class='cmb2-metabox-description'>{$field->args['desc']}</p>";
    }
    function sanitize_nsa_section_start( $override_value, $value ) {
        
        // Validate Input and set to '' if not valid
        //if ( ! is_email( $value ) ) {
        //    // Empty the value
        //    $value = '';
        //}
        return $value;
    }

    function render_nsa_section_end( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
        echo "</div>";
    }
    function sanitize_nsa_section_end( $override_value, $value ) {
        
        // Validate Input and set to '' if not valid
        //if ( ! is_email( $value ) ) {
        //    // Empty the value
        //    $value = '';
        //}
        return $value;
    }

}

