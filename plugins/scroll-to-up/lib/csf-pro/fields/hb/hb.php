<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Header Builder
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_hb' ) ) {
  class CSF_Field_hb extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output(){

      //echo $this->element_before();
      //echo '<input type="'. $this->element_type() .'" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
      //echo $this->element_after();

      echo '<div class="hb-settings-wrap">';

        echo '<div class="hb-notice"><p>Welcome to Drag and Drop Header Builder</p>';

        echo '<input type="text" name="'. $this->element_name('[settings][width]') .'" value="" placeholder="width" />';
        echo '<input type="text" name="'. $this->element_name('[settings][height]') .'" value="" placeholder="height" />';

        //echo csf_add_field( $this->field, $this->value(), $this->unique, 'customize' );

        echo '</div>';

        echo '
          <div class="hb-settings hb-settings-1">
            <p>Settings #1</p>
            <input type="text" />
          </div>
        ';

        echo '
          <div class="hb-settings hb-settings-2">
            <p>Settings #2</p>
            <input type="text" />
            <input type="text" />
          </div>
        ';

        echo '
          <div class="hb-settings hb-settings-3">
            <p>Settings #3</p>
            <input type="text" />
            <input type="text" />
            <input type="text" />
          </div>
        ';


        echo '
          <div class="hb-settings hb-settings-4">
            <p>Loop 1</p>
          </div>
        ';


      echo '</div>';

    }

  }
}
