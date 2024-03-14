<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Shortcodes Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Shortcode' ) ) {
  class CSF_Shortcode extends CSF_Abstract{

    /**
     *
     * shortcode options
     * @access public
     * @var array
     *
     */
    public $options = array();

    /**
     *
     * shortcodes options
     * @access public
     * @var array
     *
     */
    public $shortcodes = array();

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run shortcode construct
    public function __construct( $settings = array(), $options = array() ) {

      $this->settings     = apply_filters( 'csf/settings/shortcode', $settings );
      $this->options      = apply_filters( 'csf/options/shortcode', $options );

      $this->unique_id    = $this->settings['id'];
      $this->button_title = $this->settings['button_title'];
      $this->select_title = $this->settings['select_title'];
      $this->insert_title = $this->settings['insert_title'];

      $this->addAction( 'media_buttons', 'add_shortcode_buttons', 99 );
      $this->addAction( 'admin_footer', 'add_shortcode_modal' );
      $this->addAction( 'customize_controls_print_footer_scripts', 'add_shortcode_modal' );
      $this->addAction( 'wp_ajax_csf-get-shortcode-'. $this->unique_id, 'get_shortcode' );

    }

    // instance
    public static function instance( $settings = array(), $options = array() ) {
      return new self( $settings, $options );
    }

    public function add_shortcode_buttons( $editor_id ) {
      echo '<a href="#" class="button button-primary csf-shortcode-button" data-editor-id="'. $editor_id .'" data-modal-button-id="'. $this->unique_id .'">'. $this->button_title .'</a>';
    }

    public function add_shortcode_modal() {
    ?>
      <div id="csf-modal-<?php echo $this->unique_id; ?>" class="csf-modal csf-shortcode" data-modal-id="<?php echo $this->unique_id; ?>">
        <div class="csf-modal-table">
          <div class="csf-modal-table-cell">
            <div class="csf-modal-overlay"></div>
            <div class="csf-modal-inner">
              <div class="csf-modal-title">
                <?php echo $this->button_title; ?>
                <div class="csf-modal-close"></div>
              </div>
              <div class="csf-modal-header">
                <select>
                  <option value=""><?php echo $this->select_title; ?></option>
                  <?php
                    foreach ( $this->options as $option ) {
                      echo ( ! empty( $option['title'] ) ) ? '<optgroup label="'. $option['title'] .'">' : '';
                      foreach ( $option['shortcodes'] as $shortcode ) {
                        $view = ( isset( $shortcode['view'] ) ) ? $shortcode['view'] : 'normal';
                        echo '<option value="'. $shortcode['name'] .'" data-view="'. $view .'">'. $shortcode['title'] .'</option>';
                      }
                      echo ( ! empty( $option['title'] ) ) ? '</optgroup>' : '';
                    }
                  ?>
                </select>
              </div>
              <div class="csf-modal-content"></div>
              <div class="csf-modal-insert-wrapper hidden"><a href="#" class="button button-primary csf-modal-insert"><?php echo $this->insert_title; ?></a></div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

    public function get_shortcode() {

      $unallows = array( 'wysiwyg', 'group', 'fieldset' );

      $request  = csf_get_var( 'shortcode' );

      if( empty( $request ) ) { die(); }

      $shortcode = array_pop( csf_array_search( $this->options, 'name', $request ) );

      if( ! empty( $shortcode ) ) {

        foreach ( $shortcode['fields'] as $field ) {

          if( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

          if( ! empty( $field['id'] ) ) {
            $field['attributes'] = ( ! empty( $field['attributes'] ) ) ? wp_parse_args( array( 'data-atts' => $field['id'] ), $field['attributes'] ) : array( 'data-atts' => $field['id'] );
          }

          $field_default = ( ! empty( $field['default'] ) ) ? $field['default'] : '';

          if( in_array( $field['type'], array('image_select', 'checkbox') ) && ! empty( $field['options'] ) ) {
            $field['attributes']['data-check'] = true;
          }

          echo csf_add_field( $field, $field_default, 'shortcode', 'shortcode' );

        }

      }

      if( ! empty( $shortcode['clone_fields'] ) ) {

        $clone_id = ! empty( $shortcode['clone_id'] ) ? $shortcode['clone_id'] : $shortcode['name'];

        echo '<div class="csf-shortcode-clone" data-clone-id="'. $clone_id .'">';
        echo '<a href="#" class="csf-remove-clone"><i class="fa fa-trash"></i></a>';

        foreach ( $shortcode['clone_fields'] as $field ) {

          if( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

          $field['sub']        = true;
          $field['attributes'] = ( ! empty( $field['attributes'] ) ) ? wp_parse_args( array( 'data-clone-atts' => $field['id'] ), $field['attributes'] ) : array( 'data-clone-atts' => $field['id'] );
          $field_default       = ( ! empty( $field['default'] ) ) ? $field['default'] : '';

          if( in_array( $field['type'], array('image_select', 'checkbox') ) && ! empty( $field['options'] ) ) {
            $field['attributes']['data-check'] = true;
          }

          echo csf_add_field( $field, $field_default, 'shortcode', 'shortcode' );

        }

        echo '</div>';

        echo '<div class="csf-clone-button-wrapper"><a class="button csf-clone-button" href="#"><i class="fa fa-plus-circle"></i> '. $shortcode['clone_title'] .'</a></div>';

      }

      die();
    }

  }
}
