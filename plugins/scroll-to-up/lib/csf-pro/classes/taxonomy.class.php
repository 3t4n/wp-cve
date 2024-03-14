<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Taxonomy Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Taxonomy' ) ) {
  class CSF_Taxonomy extends CSF_Abstract{

    /**
     *
     * taxonomy options
     * @access public
     * @var array
     *
     */
    public $options = array();

    // run taxonomy construct
    public function __construct( $options ) {

      $this->options = apply_filters( 'csf/options/taxonomy', $options );

      $this->addAction( 'admin_init', 'add_taxonomy_fields' );

      $this->addEnqueue( $this->options );

    }

    // instance
    public static function instance( $options = array() ) {
      return new self( $options );
    }

    // add taxonomy add/edit fields
    public function add_taxonomy_fields() {

      foreach ( $this->options as $option ) {

        $opt_taxonomy = $option['taxonomy'];
        $get_taxonomy = csf_get_var( 'taxonomy' );

        if( $get_taxonomy == $opt_taxonomy ) {

          $this->addAction( $opt_taxonomy .'_add_form_fields', 'render_taxonomy_form_fields' );
          $this->addAction( $opt_taxonomy .'_edit_form', 'render_taxonomy_form_fields' );

          $this->addAction( 'created_'. $opt_taxonomy, 'save_taxonomy' );
          $this->addAction( 'edited_'. $opt_taxonomy, 'save_taxonomy' );
          $this->addAction( 'delete_'. $opt_taxonomy, 'delete_taxonomy' );

        }

      }

    }

    // render taxonomy add/edit form fields
    public function render_taxonomy_form_fields( $term ) {

      global $csf;

      $value     = '';
      $form_edit = ( is_object( $term ) && isset( $term->taxonomy ) ) ? true : false;
      $taxonomy  = ( $form_edit ) ? $term->taxonomy : $term;
      $classname = ( $form_edit ) ? 'edit' : 'add';

      wp_nonce_field( 'csf-taxonomy', 'csf-taxonomy-nonce' );

      do_action( 'csf/html/taxonomy/before' );

      echo '<div class="csf csf-taxonomy csf-taxonomy-'. $classname .'-fields csf-onload">';

        foreach( $this->options as $option ) {

          if( $taxonomy == $option['taxonomy'] ) {

            if( $form_edit ) {

              $value   = get_term_meta( $term->term_id, $option['id'], true );
              $timenow = round( microtime(true) );
              $expires = ( isset( $value['_transient']['expires'] ) ) ? $value['_transient']['expires'] : 0;
              $errors  = ( isset( $value['_transient']['errors'] ) ) ? $value['_transient']['errors'] : array();
              $timein  = csf_timeout( $timenow, $expires, 30 );

              $csf['errors'] = ( $timein ) ? $errors : array();

            }

            foreach ( $option['fields'] as $field ) {

              $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
              $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
              $elem_value = ( is_array( $value ) && isset( $value[$elem_id] ) ) ? $value[$elem_id] : $default;

              echo csf_add_field( $field, $elem_value, $option['id'], 'taxonomy' );

            }

          }

        }

      echo '</div>';

      do_action( 'csf/html/taxonomy/after' );

    }

    // save taxonomy form fields
    public function save_taxonomy( $term_id ) {

      if ( wp_verify_nonce( csf_get_var( 'csf-taxonomy-nonce' ), 'csf-taxonomy' ) ) {

        $errors = array();
        $taxonomy = csf_get_var( 'taxonomy' );

        foreach ( $this->options as $request_value ) {

          if( $taxonomy == $request_value['taxonomy'] ) {

            $request_key = $request_value['id'];
            $request = csf_get_var( $request_key, array() );

            // ignore _nonce
            if( isset( $request['_nonce'] ) ) {
              unset( $request['_nonce'] );
            }

            // sanitize and validate
            if( ! empty( $request_value['fields'] ) ) {

              foreach( $request_value['fields'] as $field ) {

                if( ! empty( $field['id'] ) ) {

                  // sanitize
                  if( ! empty( $field['sanitize'] ) ) {

                    $sanitize = $field['sanitize'];

                    if( function_exists( $sanitize ) ) {

                      $value_sanitize = csf_get_vars( $request_key, $field['id'] );
                      $request[$field['id']] = call_user_func( $sanitize, $value_sanitize );

                    }

                  }

                  // validate
                  if( ! empty( $field['validate'] ) ) {

                    $validate = $field['validate'];

                    if( function_exists( $validate ) ) {

                      $value_validate = csf_get_vars( $request_key, $field['id'] );
                      $has_validated  = call_user_func( $validate, array( 'value' => $value_validate, 'field' => $field ) );

                      if( ! empty( $has_validated ) ) {

                        $meta_value = get_term_meta( $term_id, $request_key, true );

                        $errors[$field['id']] = array( 'code' => $field['id'], 'message' => $has_validated, 'type' => 'error' );
                        $default_value = isset( $field['default'] ) ? $field['default'] : '';
                        $request[$field['id']] = ( isset( $meta_value[$field['id']] ) ) ? $meta_value[$field['id']] : $default_value;

                      }

                    }

                  }

                  // auto sanitize
                  if( ! isset( $request[$field['id']] ) || is_null( $request[$field['id']] ) ) {
                    $request[$field['id']] = '';
                  }

                }

              }

            }

            $request['_transient']['expires']  = round( microtime(true) );

            if( ! empty( $errors ) ) {
              $request['_transient']['errors'] = $errors;
            }

            $request = apply_filters( 'csf/save/taxonomy', $request, $request_key, $term_id );

            if( empty( $request ) ) {

              delete_term_meta( $term_id, $request_key );

            } else {

              update_term_meta( $term_id, $request_key, $request );

            }

          }

        }

        set_transient( 'csf-taxonomy-transient', $errors, 10 );

      }

    }

    // delete taxonomy
    public function delete_taxonomy( $term_id ) {

      $taxonomy = csf_get_var( 'taxonomy' );

      if( ! empty( $taxonomy ) ) {

        foreach ( $this->options as $request_value ) {

          if( $taxonomy == $request_value['taxonomy'] ) {

            $request_key = $request_value['id'];

            delete_term_meta( $term_id, $request_key );

          }

        }

      }

    }

  }
}
