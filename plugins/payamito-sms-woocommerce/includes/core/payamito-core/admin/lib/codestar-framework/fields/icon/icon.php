<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: icon
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_icon' ) ) {
	class KIANFR_Field_icon extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$args = wp_parse_args( $this->field, [
				'button_title' => esc_html__( 'Add Icon', 'kianfr' ),
				'remove_title' => esc_html__( 'Remove Icon', 'kianfr' ),
			] );

			echo $this->field_before();

			$nonce  = wp_create_nonce( 'kianfr_icon_nonce' );
			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo '<div class="kianfr-icon-select">';
			echo '<span class="kianfr-icon-preview' . esc_attr( $hidden ) . '"><i class="' . esc_attr( $this->value ) . '"></i></span>';
			echo '<a href="#" class="button button-primary kianfr-icon-add" data-nonce="' . esc_attr( $nonce ) . '">' . $args['button_title'] . '</a>';
			echo '<a href="#" class="button kianfr-warning-primary kianfr-icon-remove' . esc_attr( $hidden ) . '">' . $args['remove_title'] . '</a>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '" class="kianfr-icon-value"' . $this->field_attributes() . ' />';
			echo '</div>';

			echo $this->field_after();
		}

		public function enqueue()
		{
			add_action( 'admin_footer', [ 'KIANFR_Field_icon', 'add_footer_modal_icon' ] );
			add_action( 'customize_controls_print_footer_scripts', [ 'KIANFR_Field_icon', 'add_footer_modal_icon' ] );
		}

		public static function add_footer_modal_icon()
		{
			?>
            <div id="kianfr-modal-icon" class="kianfr-modal kianfr-modal-icon hidden">
                <div class="kianfr-modal-table">
                    <div class="kianfr-modal-table-cell">
                        <div class="kianfr-modal-overlay"></div>
                        <div class="kianfr-modal-inner">
                            <div class="kianfr-modal-title">
								<?php
								esc_html_e( 'Add Icon', 'kianfr' ); ?>
                                <div class="kianfr-modal-close kianfr-icon-close"></div>
                            </div>
                            <div class="kianfr-modal-header">
                                <input type="text" placeholder="<?php
								esc_html_e( 'Search...', 'kianfr' ); ?>" class="kianfr-icon-search"/>
                            </div>
                            <div class="kianfr-modal-content">
                                <div class="kianfr-modal-loading">
                                    <div class="kianfr-loading"></div>
                                </div>
                                <div class="kianfr-modal-load"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}

	}
}
