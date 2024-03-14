<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WPPSGS_Field_icon' ) ) {
  class WPPSGS_Field_icon extends WPPSGS_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'wppsgs' ),
        'remove_title' => esc_html__( 'Remove Icon', 'wppsgs' ),
      ) );

      echo $this->field_before();

      $nonce  = wp_create_nonce( 'wppsgs_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="wppsgs-icon-select">';
      echo '<span class="wppsgs-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></span>';
      echo '<a href="#" class="button button-primary wppsgs-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. $args['button_title'] .'</a>';
      echo '<a href="#" class="button wppsgs-warning-primary wppsgs-icon-remove'. esc_attr( $hidden ) .'">'. $args['remove_title'] .'</a>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="wppsgs-icon-value"'. $this->field_attributes() .' />';
      echo '</div>';

      echo $this->field_after();

    }

    public function enqueue() {
      add_action( 'admin_footer', array( 'WPPSGS_Field_icon', 'add_footer_modal_icon' ) );
      add_action( 'customize_controls_print_footer_scripts', array( 'WPPSGS_Field_icon', 'add_footer_modal_icon' ) );
    }

    public static function add_footer_modal_icon() {
    ?>
      <div id="wppsgs-modal-icon" class="wppsgs-modal wppsgs-modal-icon hidden">
        <div class="wppsgs-modal-table">
          <div class="wppsgs-modal-table-cell">
            <div class="wppsgs-modal-overlay"></div>
            <div class="wppsgs-modal-inner">
              <div class="wppsgs-modal-title">
                <?php esc_html_e( 'Add Icon', 'wppsgs' ); ?>
                <div class="wppsgs-modal-close wppsgs-icon-close"></div>
              </div>
              <div class="wppsgs-modal-header">
                <input type="text" placeholder="<?php esc_html_e( 'Search...', 'wppsgs' ); ?>" class="wppsgs-icon-search" />
              </div>
              <div class="wppsgs-modal-content">
                <div class="wppsgs-modal-loading"><div class="wppsgs-loading"></div></div>
                <div class="wppsgs-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

  }
}
