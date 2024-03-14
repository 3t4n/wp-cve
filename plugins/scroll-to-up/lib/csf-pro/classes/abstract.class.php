<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Abstract Class
 * A helper class for action and filter hooks
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Abstract' ) ) {
  abstract class CSF_Abstract {

    /**
     *
     * absFields
     * @access public
     * @var array
     *
     */
    public $absFields = array();

    public function __construct() {}

    public function addAction( $hook, $function_to_add, $priority = 30, $accepted_args = 1 ) {

      add_action( $hook, array( &$this, $function_to_add), $priority, $accepted_args );

    }

    public function addFilter( $tag, $function_to_add, $priority = 30, $accepted_args = 1 ) {

      add_action( $tag, array( &$this, $function_to_add), $priority, $accepted_args );

    }

    public function getSections( $options = array() ) {

      $sections = array();

      foreach ( $options as $option ) {

        if( ! empty( $option['sections'] ) ) {

          foreach ( $option['sections'] as $section ) {

            if( ! empty( $section['fields'] ) ) {

              $sections[] = $section;

            }

          }

        } else {

          if( ! empty( $option['fields'] ) ) {
            $sections[] = $option;
          }

        }

      }

      return $sections;

    }

    public function getFields( $options = array() ) {

      $fields   = array();
      $sections = $this->getSections( $options );

      if( ! empty( $sections ) ) {

        foreach( $sections as $section ) {

          if( ! empty( $section['fields'] ) ) {

            foreach ( $section['fields'] as $field ) {

              $fields[] = $field;

            }

          }

        }

      }

      return $fields;

    }

    public function addEnqueue( $options ) {

      $this->absFields = $this->getFields( $options );

      $this->addAction( 'admin_enqueue_scripts', 'add_field_enqueue' );

    }

    public function add_field_enqueue() {

      foreach( $this->absFields as $field ) {

        if( ! empty( $field['type'] ) ) {

          $field_class = 'CSF_Field_'. $field['type'];

          if( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {

            $field_class::enqueue();

          }

        }

      }

    }

  }
}
