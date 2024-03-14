<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_background' ) ) {
  class CSF_Field_background extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();

      $value_defaults = array(
        'image'       => '',
        'repeat'      => '',
        'position'    => '',
        'attachment'  => '',
        'size'        => '',
        'color'       => '',
      );

      $this->value  = wp_parse_args( $this->element_value(), $value_defaults );

      if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }

      $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
      $button_title = ( isset( $button_title ) ) ? $button_title : __( 'Upload', 'csf' );
      $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : __( 'Upload', 'csf' );
      $insert_title = ( isset( $insert_title ) ) ? $insert_title : __( 'Use Image', 'csf' );
      $wrap_class   = ( isset( $this->field['wrap_class'] ) ) ? $this->field['wrap_class'] : '';

      echo '<div class="csf-field csf-field-upload csf-pseudo-field '.  $wrap_class .'">';
      echo '<div class="csf-table">';
      echo '<div class="csf-table-cell"><input type="text" name="'. $this->element_name( '[image]' ) .'" value="'. $this->value['image'] .'"'. $this->element_class() . $this->element_attributes() .'/></div>';
      echo '<div class="csf-table-cell"><a href="#" class="button csf-button" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a></div>';
      echo '</div>';
      echo '</div>';

      // background attributes
      echo '<fieldset>';

      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[repeat]' ),
          'options'     => array(
            ''          => 'repeat',
            'repeat-x'  => 'repeat-x',
            'repeat-y'  => 'repeat-y',
            'no-repeat' => 'no-repeat',
            'inherit'   => 'inherit',
          ),
          'attributes'  => array(
            'data-atts' => 'repeat',
          ),
      ), $this->value['repeat'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'      => $wrap_class,
          'pseudo'          => true,
          'type'            => 'select',
          'name'            => $this->element_name( '[position]' ),
          'options'         => array(
            ''              => 'left top',
            'left center'   => 'left center',
            'left bottom'   => 'left bottom',
            'right top'     => 'right top',
            'right center'  => 'right center',
            'right bottom'  => 'right bottom',
            'center top'    => 'center top',
            'center center' => 'center center',
            'center bottom' => 'center bottom'
          ),
          'attributes'      => array(
            'data-atts'     => 'position',
          ),
      ), $this->value['position'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[attachment]' ),
          'options'     => array(
            ''          => 'scroll',
            'fixed'     => 'fixed',
          ),
          'attributes'  => array(
            'data-atts' => 'attachment',
          ),
      ), $this->value['attachment'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[size]' ),
          'options'     => array(
            ''          => 'size',
            'cover'     => 'cover',
            'contain'   => 'contain',
            'inherit'   => 'inherit',
            'initial'   => 'initial',
          ),
          'attributes'  => array(
            'data-atts' => 'size',
          ),
      ), $this->value['size'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'id'          => $this->field['id'].'_color',
          'type'        => 'color_picker',
          'name'        => $this->element_name('[color]'),
          'attributes'  => array(
            'data-atts' => 'bgcolor',
          ),
          'default'     => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
          'rgba'        => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
      ), $this->value['color'], '', 'field/background' );
      echo '</fieldset>';

      echo $this->element_after();

    }
  }
}
