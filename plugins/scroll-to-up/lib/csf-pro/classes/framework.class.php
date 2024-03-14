<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Options class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Options' ) ) {
  class CSF_Options extends CSF_Abstract {

    /**
     *
     * unique
     * @access public
     * @var string
     *
     */
    public $unique = '';

    /**
     *
     * notice
     * @access public
     * @var boolean
     *
     */
    public $notice = false;

    /**
     *
     * settings
     * @access public
     * @var array
     *
     */
    public $settings = array();

    /**
     *
     * options
     * @access public
     * @var array
     *
     */
    public $options = array();

    /**
     *
     * sections
     * @access public
     * @var array
     *
     */
    public $sections = array();

    /**
     *
     * fields
     * @access public
     * @var array
     *
     */
    public $fields = array();

    /**
     *
     * options store
     * @access public
     * @var array
     *
     */
    public $db_option = array();

    // run framework construct
    public function __construct( $settings = array(), $options = array() ) {

      $this->settings  = apply_filters( 'csf/settings/framework', $settings );
      $this->options   = apply_filters( 'csf/options/framework', $options );

      $this->unique    = $this->settings['option_name'];
      $this->sections  = $this->getSections( $this->options );
      $this->fields    = $this->getFields( $this->options );
      $this->db_option = get_option( $this->unique );
      $this->installed = get_option( $this->unique . '_installed' );

      $this->addAction( 'admin_init', 'setup' );
      $this->addAction( 'admin_menu', 'add_admin_menu' );

      $this->addEnqueue( $this->options );

    }

    // instance of framework
    public static function instance( $settings = array(), $options = array() ) {
      return new self( $settings, $options );
    }

    // wp settings api
    public function setup() {

      foreach( $this->sections as $section ) {

        $unique = $this->unique .'_'. $section['name'];

        register_setting( $this->unique .'_group', $this->unique, array( &$this,'validate_save' ) );

        if( ! empty( $section['fields'] ) ) {

          add_settings_section( $unique .'_section', $section['title'], '', $unique .'_section_group' );

          foreach( $section['fields'] as $key => $field ) {

            add_settings_field( $key .'_field', '', array( &$this, 'field_callback' ), $unique .'_section_group', $unique .'_section', $field );

            // set default option if isset
            if( isset( $field['id'] ) ) {

              $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
              $field_value   = ( isset( $this->db_option[$field['id']] ) ) ? $this->db_option[$field['id']] : $field_default;

              $this->db_option[$field['id']] = $field_value;

            }

          }

        }

      }

      // check for is saved defaults in database
      if( $this->settings['save_defaults'] && empty( $this->installed ) ) {
        update_option( $this->unique, $this->db_option );
        update_option( $this->unique .'_installed', true );
      }

    }

    // section fields validate in save
    public function validate_save( $request ) {

      $add_errors = array();
      $section_id = ( ! empty( $request['_transient']['section'] ) ) ? $request['_transient']['section'] : '';

      // ignore nonce requests
      if( isset( $request['_nonce'] ) ) { unset( $request['_nonce'] ); }

      // import
      if ( isset( $request['import'] ) && ! empty( $request['import'] ) ) {
        $decode_string = csf_decode_string( $request['import'] );
        if( is_array( $decode_string ) ) {
          return $decode_string;
        }
        $add_errors[] = $this->add_settings_error( __( 'Success. Imported backup options.', 'csf' ), 'updated' );
      }

      // reset all options
      if ( isset( $request['resetall'] ) ) {
        $add_errors[] = $this->add_settings_error( __( 'Default options restored.', 'csf' ), 'updated' );
        return;
      }

      // restore section
      if ( isset( $request['restore'] ) && ! empty( $section_id ) ) {
        foreach ( $this->sections as $value ) {
          if( $this->unique .'_'. $value['name'] == $section_id ) {
            foreach ( $value['fields'] as $field ) {
              if( isset( $field['id'] ) ) {
                if( isset( $field['default'] ) ) {
                  $request[$field['id']] = $field['default'];
                } else {
                  unset( $request[$field['id']] );
                }
              }
            }
          }
        }
        $add_errors[] = $this->add_settings_error( __( 'Default options restored for only this section.', 'csf' ), 'updated' );
      }

      // sanitize and validate
      foreach( $this->fields as $field ) {

        if( ! empty( $field['id'] ) ) {

          // sanitize
          if( ! empty( $field['sanitize'] ) ) {

            $sanitize = $field['sanitize'];

            if( function_exists( $sanitize ) ) {

              $value_sanitize = isset( $request[$field['id']] ) ? $request[$field['id']] : '';
              $request[$field['id']] = call_user_func( $sanitize, $value_sanitize );

            }

          }

          // validate
          if( ! empty( $field['validate'] ) ) {

            $validate = $field['validate'];

            if( function_exists( $validate ) ) {

              $value_validate = isset( $request[$field['id']] ) ? $request[$field['id']] : '';
              $has_validated  = call_user_func( $validate, array( 'value' => $value_validate, 'field' => $field ) );

              if( ! empty( $has_validated ) ) {
                $add_errors[] = $this->add_settings_error( $has_validated, 'error', $field['id'] );
                $request[$field['id']] = ( isset( $this->db_option[$field['id']] ) ) ? $this->db_option[$field['id']] : '';
              }

            }

          }

          // auto sanitize
          if( ! isset( $request[$field['id']] ) || is_null( $request[$field['id']] ) ) {
            $request[$field['id']] = '';
          }

        }

      }

      $request = apply_filters( 'csf/save/validate', $request );

      do_action( 'csf/save/validate/after', $request );

      // set transient
      $request['_transient']['expires'] = round( microtime(true) );
      $request['_transient']['errors']  = $add_errors;
      $request['_transient']['section'] = $section_id;

      return $request;

    }

    // field callback
    public function field_callback( $field ) {
      $value = ( isset( $field['id'] ) && isset( $this->db_option[$field['id']] ) ) ? $this->db_option[$field['id']] : '';
      echo csf_add_field( $field, $value, $this->unique, 'options' );
    }

    // wp api: settings sections
    public function do_settings_sections( $page ) {

      global $wp_settings_sections, $wp_settings_fields;

      if ( ! isset( $wp_settings_sections[$page] ) ){
        return;
      }

      foreach ( $wp_settings_sections[$page] as $section ) {

        if ( $section['callback'] ){
          call_user_func( $section['callback'], $section );
        }

        if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) ){
          continue;
        }

        $this->do_settings_fields( $page, $section['id'] );

      }

    }

    // wp api: settings fields
    public function do_settings_fields( $page, $section ) {

      global $wp_settings_fields;

      if ( ! isset( $wp_settings_fields[$page][$section] ) ) {
        return;
      }

      foreach ( $wp_settings_fields[$page][$section] as $field ) {
        call_user_func($field['callback'], $field['args']);
      }

    }


    // custom add settings error
    public function add_settings_error( $message, $type = 'error', $id = 'global' ) {
      return array( 'setting' => 'csf-errors', 'code' => $id, 'message' => $message, 'type' => $type );
    }

    // custom transient
    public function transient( $option = '', $default = '' ) {
      return ( ! empty( $this->db_option['_transient'][$option] ) ) ? $this->db_option['_transient'][$option] : $default;
    }

    // wp api: admin menu
    public function add_admin_menu() {

      $defaults = array(
        'menu_parent'     => '',
        'menu_title'      => '',
        'menu_type'       => '',
        'menu_slug'       => '',
        'menu_icon'       => '',
        'menu_capability' => 'manage_options',
        'menu_position'   => null,
      );

      $args = wp_parse_args( $this->settings, $defaults );

      if( $args['menu_type'] == 'submenu' ) {
        call_user_func( 'add_'. $args['menu_type'] .'_page', $args['menu_parent'], $args['menu_title'], $args['menu_title'], $args['menu_capability'], $args['menu_slug'], array( &$this, 'add_options_html' ) );
      } else {
        call_user_func( 'add_'. $args['menu_type'] .'_page', $args['menu_title'], $args['menu_title'], $args['menu_capability'], $args['menu_slug'], array( &$this, 'add_options_html' ), $args['menu_icon'], $args['menu_position'] );
      }

    }

    // option page html output
    public function add_options_html() {

      $timenow      = round( microtime(true) );
      $timein       = csf_timeout( $timenow, $this->transient( 'expires', 0 ), 20 );
      $section_data = $this->transient( 'section' );
      $errors       = $this->transient( 'errors' );
      $has_nav      = ( count( $this->options ) <= 1 ) ? ' csf-show-all' : '';
      $section_id   = ( ! empty( $section_data ) && $timein ) ? $section_data : $this->unique .'_'. $this->sections[0]['name'];
      $section_id   = csf_get_var( 'csf-section', $section_id );
      $ajax_class   = ( $this->settings['ajax_save'] ) ? ' csf-save-ajax' : '';
      $sticky_class = ( $this->settings['sticky_header'] ) ? ' csf-sticky-header' : '';

      do_action( 'csf/html/options/before' );

      echo '<div class="csf csf-options">';

        echo '<form method="post" action="options.php" enctype="multipart/form-data" id="CSF_form">';
        echo '<input type="hidden" class="csf-section-id" name="'. $this->unique .'[_transient][section]" value="'. $section_id .'">';

        if( $this->settings['ajax_save'] !== true && ! empty( $errors ) && $timein ) {

          global $csf;

          foreach ( $errors as $error ) {

            if( in_array( $error['setting'], array( 'general', 'csf-errors' ) ) ) {

              echo '<div class="csf-settings-error '. $error['type'] .'">';
              echo '<p><strong>'. $error['message'] .'</strong></p>';
              echo '</div>';

            }

            $csf['errors'] = $errors;

          }

        }

        settings_fields( $this->unique . '_group' );

        echo '<div class="csf-header'. esc_attr( $sticky_class ) .'">';
        echo '<div class="csf-header-inner">';

          echo '<div class="csf-header-left">';
          echo '<h1>'. $this->settings['framework_title'] .'</h1>';
          echo ( $this->settings['show_search'] ) ? '<div class="csf-search"><input type="text" placeholder="'. __( 'Search option(s)', 'csf' ) .'" /></div>' : '';
          echo '</div>';

          echo '<div class="csf-header-right">';

            echo '<div class="csf-buttons">';
            submit_button( __( 'Save', 'csf' ), 'primary csf-save'. $ajax_class, 'save', false, array( 'data-save' => __( 'Saving...', 'csf' ) ) );
            submit_button( __( 'Restore', 'csf' ), 'secondary csf-restore csf-confirm', $this->unique .'[restore]', false );
            if( $this->settings['show_reset'] ) {
              submit_button( __( 'Reset All Options', 'csf' ), 'secondary csf-reset csf-warning-primary csf-confirm', $this->unique .'[resetall]', false );
            }
            echo '</div>';
            echo ( empty( $has_nav ) && $this->settings['show_all_options'] ) ? '<a href="#" class="csf-expand-all"><i class="fa fa-eye-slash"></i> '. __( 'show all options', 'csf' ) .'</a>' : '';

          echo '</div>';

          echo '<div class="clear"></div>';
          echo '</div>';
        echo '</div>';

        echo '<div class="csf-wrapper'. $has_nav .'">';

          echo '<div class="csf-nav">';

            echo '<ul>';
            foreach ( $this->options as $key => $tab ) {
              if( ! empty( $tab['sections'] ) ) {

                $tab_active   = csf_array_search( $tab['sections'], 'name', str_replace( $this->unique.'_', '', $section_id ) );
                $active_list  = ( ! empty( $tab_active ) ) ? ' csf-tab-active' : '';
                $tab_icon     = ( ! empty( $tab['icon'] ) ) ? '<i class="'. $tab['icon'] .'"></i>' : '';

                echo '<li class="csf-sub'. $active_list .'">';

                  echo '<a href="#" class="csf-arrow">'. $tab_icon . $tab['title'] .'</a>';

                  echo '<ul>';
                  foreach ( $tab['sections'] as $tab_section ) {

                    $active_tab = ( $section_id == $this->unique .'_'. $tab_section['name'] ) ? ' class="csf-section-active"' : '';
                    $icon = ( ! empty( $tab_section['icon'] ) ) ? '<i class="'. $tab_section['icon'] .'"></i>' : '';

                    echo '<li><a href="#"'. $active_tab .' data-section="'. $this->unique .'_'. $tab_section['name'] .'">'. $icon . $tab_section['title'] .'</a></li>';

                  }
                  echo '</ul>';

                echo '</li>';

              } else {

                $icon = ( ! empty( $tab['icon'] ) ) ? '<i class="'. $tab['icon'] .'"></i>' : '';

                if( ! empty( $tab['fields'] ) ) {

                  $active_list = ( $section_id == $this->unique .'_'. $tab['name'] ) ? ' class="csf-section-active"' : '';
                  echo '<li><a href="#"'. $active_list .' data-section="'. $this->unique .'_'. $tab['name'] .'">'. $icon . $tab['title'] .'</a></li>';

                } else {

                  echo '<li><div class="csf-seperator">'. $icon . $tab['title'] .'</div></li>';

                }

              }
            }
            echo '</ul>';

          echo '</div>';

          echo '<div class="csf-content">';

            echo '<div class="csf-sections">';
            foreach( $this->sections as $section ) {
              if( ! empty( $section['fields'] ) ) {

                $active_content = ( $section_id !== $this->unique .'_'. $section['name'] ) ? 'hidden' : 'csf-onload';
                echo '<div id="csf-tab-'. $this->unique .'_'. $section['name'] .'" class="csf-section '. $active_content .'">';
                echo ( ! empty( $section['title'] ) && empty( $has_nav ) ) ? '<div class="csf-section-title"><h3>'. $section['title'] .'</h3></div>' : '';
                $this->do_settings_sections( $this->unique .'_'. $section['name'] . '_section_group' );
                echo '</div>';

              }
            }

            echo '</div>';

            echo '<div class="clear"></div>';

          echo '</div>';

          echo '<div class="csf-nav-background"></div>';

        echo '</div>';

        if( $this->settings['show_footer'] ) {
          echo '<div class="csf-footer">';
          echo '<div class="csf-buttons">';

          submit_button( __( 'Save', 'csf' ), 'primary csf-save'. $ajax_class, 'save', false, array( 'data-save' => __( 'Saving...', 'csf' ) ) );
          submit_button( __( 'Restore', 'csf' ), 'secondary csf-restore csf-confirm', $this->unique .'[restore]', false );

          if( $this->settings['show_reset'] ) {
            submit_button( __( 'Reset All Options', 'csf' ), 'secondary csf-reset csf-warning-primary csf-confirm', $this->unique .'[resetall]', false );
          }

          echo '</div>';
          echo '<div class="csf-copyright">Powered by Codestar Framework v2.0-beta</div>';
          echo '<div class="clear"></div>';
          echo '</div>';
        }

        echo '</form>';

        echo '<div class="clear"></div>';

      echo '</div>';

      do_action( 'csf/html/options/after' );

    }

  }
}
