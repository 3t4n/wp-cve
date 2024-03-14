<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: attribute
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_attribute' ) ) {
  class CSF_Field_attribute extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                         = wp_parse_args( $this->field, array(                
        'cs_position'           => true,
        'cs_shape'              => true,        
        'cs_size'               => true,        
        'cs_animation'          => true,
		'compact'				=> false,
      ) );	  
      $default_value = array(        
        'cs-position'           => '',
        'cs-shape'              => '',        
        'cs-size'               => '',        
        'cs-animation'          => '', 
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo $this->field_before();
	  
      echo '<div class="csf--cs-attributes">';
      //
      // Button Position
      if ( ! empty( $args['cs_position'] ) ) {

        CSF::field( array(
          'id'        => 'cs-position',
          'type'      => 'select',
          'options'   => array(
            ''        	=> esc_html__( 'Button Position', 'csf' ),
            'right'		=> esc_html__( 'Right', 'csf' ),            
            'left'		=> esc_html__( 'Left', 'csf' ),            
          ),
        ), $this->value['cs-position'], $this->field_name(), 'field/attribute' );

      }
	  //
      // Button Shape
      if ( ! empty( $args['cs_shape'] ) ) {

        CSF::field( array(
          'id'        => 'cs-shape',
          'type'      => 'select',
          'options'   => array(
            ''        	=> esc_html__( 'Button Shape', 'csf' ),
            'circle'		=> esc_html__( 'Circle', 'csf' ),            
            'square'		=> esc_html__( 'Square', 'csf' ),
			'rsquare'		=> esc_html__( 'Round Square', 'csf' ),
          ),
        ), $this->value['cs-shape'], $this->field_name(), 'field/attribute' );

      }
	  //
      // Button Size
      if ( ! empty( $args['cs_size'] ) ) {

        CSF::field( array(
          'id'        => 'cs-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Button Size', 'csf' ),
            'small'   => esc_html__( 'Small', 'csf' ),
            'medium' => esc_html__( 'Medium', 'csf' ),
            'large'    => esc_html__( 'Large', 'csf' ),
          ),
        ), $this->value['cs-size'], $this->field_name(), 'field/attribute' );

      }
	  //
      // Button Animation
      if ( ! empty( $args['cs_animation'] ) ) {

        CSF::field( array(
          'id'        => 'cs-animation',
          'type'      => 'select',
          'options'   => array(
            ''        		=> esc_html__( 'Button Animation', 'csf' ),
            'fade'   		=> esc_html__( 'Fade', 'csf' ),
            'rotate' 		=> esc_html__( 'Rotate', 'csf' ),
            'scale'    		=> esc_html__( 'Scale', 'csf' ),
            'rotate-scale'	=> esc_html__( 'Rotate & Scale', 'csf' ),
            'translate'		=> esc_html__( 'Translate', 'csf' ),
            'translate-rotate'	=> esc_html__( 'Translate Rotate', 'csf' ),
            'rotate-translate-scale'	=> esc_html__( 'Rotate Translate Scale', 'csf' ),
            'translate-scale'	=> esc_html__( 'Translate Scale', 'csf' ),
          ),
        ), $this->value['cs-animation'], $this->field_name(), 'field/attribute' );

      }
		echo '</div>';
      echo $this->field_after();

    }

    public function output() {

      $output    = '';      
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Button Color
      $cs_color        = ( ! empty( $this->value['cs-color']              ) ) ? $this->value['cs-color']              : '';
	  if ( $cs_color) {        
        unset( $this->value['cs-color'] );
      }
      // Common button properties
      $properties = array('position','shape','size','animation');

      foreach ( $properties as $property ) {
        $property = 'cs-'. $property;
        if ( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if ( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
