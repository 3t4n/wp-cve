<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Metabox Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Metabox' ) ) {
  class CSF_Metabox extends CSF_Abstract{

    /**
     *
     * options
     * @access public
     * @var array
     *
     */
    public $options = array();

    // run metabox construct
    public function __construct( $options ) {

      $this->options  = apply_filters( 'csf/options/metabox', $options );

      $this->addAction( 'add_meta_boxes', 'add_meta_box' );
      $this->addAction( 'save_post', 'save_meta_box', 10, 2 );

      $this->addEnqueue( $this->options );

    }

    // instance
    public static function instance( $options = array() ) {
      return new self( $options );
    }

    // add metabox
    public function add_meta_box( $post_type ) {

      foreach ( $this->options as $value ) {
        add_meta_box( $value['id'], $value['title'], array( &$this, 'add_meta_box_content' ), $value['post_type'], $value['context'], $value['priority'], $value );
      }

    }

    // add metabox content
    public function add_meta_box_content( $post, $callback ) {

      global $post, $csf, $typenow;

      wp_nonce_field( 'csf-metabox', 'csf-metabox-nonce' );

      $args       = $callback['args'];
      $unique     = $args['id'];
      $sections   = $args['sections'];
      $meta_value = get_post_meta( $post->ID, $unique, true );
      $has_nav    = ( count( $sections ) >= 2 && $args['context'] != 'side' ) ? true : false;
      $show_all   = ( ! $has_nav ) ? ' csf-show-all' : '';
      $timenow    = round( microtime(true) );
      $errors     = ( isset( $meta_value['_transient']['errors'] ) ) ? $meta_value['_transient']['errors'] : array();
      $section    = ( isset( $meta_value['_transient']['section'] ) ) ? $meta_value['_transient']['section'] : false;
      $expires    = ( isset( $meta_value['_transient']['expires'] ) ) ? $meta_value['_transient']['expires'] : 0;
      $timein     = csf_timeout( $timenow, $expires, 20 );
      $section_id = ( $timein && $section ) ? $section : '';
      $section_id = csf_get_var( 'csf-section', $section_id );

      // add erros
      $csf['errors'] = ( $timein ) ? $errors : array();

      do_action( 'csf/html/metabox/before' );

      echo '<div class="csf csf-metabox">';

        echo '<input type="hidden" name="'. $unique .'[_transient][section]" class="csf-section-id" value="'. $section_id .'">';

        echo '<div class="csf-wrapper'. $show_all .'">';

          if( $has_nav ) {

            echo '<div class="csf-nav">';

              echo '<ul>';
              $num = 0;
              foreach( $sections as $value ) {

                if( ! empty( $value['typenow'] ) && $value['typenow'] !== $typenow ) { continue; }

                $tab_icon = ( ! empty( $value['icon'] ) ) ? '<i class="csf-icon '. $value['icon'] .'"></i>' : '';

                if( isset( $value['fields'] ) ) {
                  $active_section = ( ( empty( $section_id ) && $num === 0 ) || $section_id == $unique .'_'. $value['name'] ) ? ' class="csf-section-active"' : '';
                  echo '<li><a href="#"'. $active_section .' data-section="'. $unique .'_'. $value['name'] .'">'. $tab_icon . $value['title'] .'</a></li>';
                } else {
                  echo '<li><div class="csf-seperator">'. $tab_icon . $value['title'] .'</div></li>';
                }

                $num++;
              }
              echo '</ul>';

            echo '</div>';

          }

          echo '<div class="csf-content">';

            echo '<div class="csf-sections">';
            $num = 0;
            foreach( $sections as $v ) {

              if( ! empty( $v['typenow'] ) && $v['typenow'] !== $typenow ) { continue; }

              if( isset( $v['fields'] ) ) {

                $active_content = ( ( empty( $section_id ) && $num === 0 ) || $section_id === $unique .'_'. $v['name'] ) ? 'csf-onload' : 'hidden';

                echo '<div id="csf-tab-'. $unique .'_'. $v['name'] .'" class="csf-section '. $active_content .'">';
                echo ( isset( $v['title'] ) ) ? '<div class="csf-section-title"><h3>'. $v['title'] .'</h3></div>' : '';

                foreach ( $v['fields'] as $field_key => $field ) {

                  $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                  $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                  $elem_value = ( is_array( $meta_value ) && isset( $meta_value[$elem_id] ) ) ? $meta_value[$elem_id] : $default;
                  echo csf_add_field( $field, $elem_value, $unique, 'metabox' );

                }
                echo '</div>';

              }

              $num++;
            }
            echo '</div>';

            echo '<div class="clear"></div>';

            if( ! empty( $args['show_restore'] ) ) {

              echo '<div class=" csf-metabox-restore">';
              echo '<label>';
              echo '<input type="checkbox" name="'. $unique .'[_restore]" />';
              echo '<span class="button csf-button-restore">'. __( 'Restore', 'csf' ) .'</span>';
              echo '<span class="button csf-button-cancel">'. sprintf( '<small>( %s )</small> %s', __( 'update post for restore ', 'csf' ), __( 'Cancel', 'csf' ) ) .'</span>';
              echo '</label>';
              echo '</div>';

            }

          echo '</div>';

          echo ( $has_nav ) ? '<div class="csf-nav-background"></div>' : '';

          echo '<div class="clear"></div>';

        echo '</div>';

      echo '</div>';

      do_action( 'csf/html/metabox/after' );

    }

    // save metabox
    public function save_meta_box( $post_id, $post ) {

      if ( wp_verify_nonce( csf_get_var( 'csf-metabox-nonce' ), 'csf-metabox' ) ) {

        $errors = array();
        $post_type = csf_get_var( 'post_type' );

        foreach ( $this->options as $request_value ) {

          if( in_array( $post_type, (array) $request_value['post_type'] ) ) {

            $request_key = $request_value['id'];
            $request = csf_get_var( $request_key, array() );

            // ignore _nonce
            if( isset( $request['_nonce'] ) ) {
              unset( $request['_nonce'] );
            }

            // sanitize and validate
            foreach( $request_value['sections'] as $key => $section ) {

              if( ! empty( $section['fields'] ) ) {

                foreach( $section['fields'] as $field ) {

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

                          $meta_value = get_post_meta( $post_id, $request_key, true );

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

            }

            $request['_transient']['expires']  = round( microtime(true) );

            if( ! empty( $errors ) ) {
              $request['_transient']['errors'] = $errors;
            }

            $request = apply_filters( 'csf/save/metabox', $request, $request_key, $post );

            if( empty( $request ) || ! empty( $request['_restore'] ) ) {

              delete_post_meta( $post_id, $request_key );

            } else {

              update_post_meta( $post_id, $request_key, $request );

            }

          }

        }

      }

    }

  }
}
