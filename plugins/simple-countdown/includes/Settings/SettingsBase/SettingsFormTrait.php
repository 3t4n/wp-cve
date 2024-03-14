<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\Field;

/**
 * Settings Form Handle.
 *
 */
trait SettingsFormTrait {

	/**
	 * List settings HTML.
	 *
	 * @return void
	 */
	protected function settings_listing_html( $tab ) {
		if ( empty( $this->fields ) ) {
			return;
		}
		$this->show_messages();
		?>
		<div class="<?php echo esc_attr( $this->id . '-settings-wrapper' ); ?> bg-white shadow-lg px-4 py-3" >
			<?php
			$this->base_css();
			if ( method_exists( $this, 'inline_css' ) ) {
				$this->inline_css();
			}
			?>
			<div class="container-fluid">
				<div class="row">
					<div class="col">
						<div class="settings-list row">
							<?php
							$tab_fields = $this->get_tab_fields( $tab );
							foreach ( $tab_fields as $section_name => $section_arr ) :
								?>
							<!-- Section -->
							<div class="tab-section-wrapper <?php echo esc_attr( 'tab-section-' . $section_name ); ?> col-12 my-3 p-3 bg-white shadow-lg">
								<?php if ( ! empty( $section_arr['section_title'] ) ) : ?>
									<h4><?php echo wp_kses_post( $section_arr['section_title'] ); ?></h4>
								<?php endif; ?>
								<?php if ( ! empty( $section_arr['section_heading'] ) ) : ?>
									<span><?php echo wp_kses_post( $section_arr['section_heading'] ); ?></span>
								<?php endif; ?>
								<?php do_action( $this->id . '-before-settings-fields', $this ); ?>
								<div class="container-fluid border mt-4">
									<?php
									foreach ( $section_arr['settings_list'] as $field_name => $field_arr ) :
										$field_arr      = array_merge( array( 'key' => $field_name ), $field_arr );
										$settings_field = Field::new_field( $this->id, $field_arr );
										if ( is_null( $settings_field ) ) {
											continue;
										}
										$settings_field->get_field();
									endforeach;
									?>
								</div>
								<?php do_action( $this->id . '-' . $tab . '-after-settings-fields', $tab_fields ); ?>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

    /**
	 * Print Settinsg HTML.
	 *
	 * @return void
	 */
	public function print_settings( $tab, $full_form = true ) {

		do_action( $this->id . '-form-submit' );
		do_action( $this->id . '-form-submit-' . $tab );

		$this->refresh_settings();

		if ( $full_form ) {
			$this->form_open();
		}

		$this->settings_listing_html( $tab );

		if ( $full_form ) {
			$this->form_close();
		}

		do_action( $this->id . '-settings-tabs-action', $this->settings );
	}

	/**
	 * Form HTML Open
	 *
	 * @return void
	 */
	public function form_open() {
		do_action( $this->id . '-before-form-open', $this->id );
		?>
		<form method="post" id="mainform" action enctype="multipart/form-data">
		<?php
	}

	/**
	 * Form HTML Close.
	 *
	 * @return void
	 */
	public function form_close() {
		if ( empty( $GLOBALS[ $this->id . '-hide-save-btn' ] ) ) :
		?>
			<p class="submit">
				<button name="save" class="button-primary" type="submit" value="Save Changes"><?php esc_html_e( 'Save changes', '' ); ?></button>
				<?php $this->nonce_field(); ?>
				<input type="hidden" name="_wp_http_referer" value="<?php echo esc_attr( wp_get_referer() ); ?>" />
				<?php do_action( $this->id . '-form-close-submit-fields' ); ?>
			</p>
		</form>
		<?php
		endif;
		do_action( $this->id . '-after-form-close', $this->id );
	}

	/**
	 * Nonce Field.
	 *
	 * @return void
	 */
	public function nonce_field() {
		?>
		<input type="hidden" id="<?php echo esc_attr( $this->id . '-settings-nonce' ); ?>" name="<?php echo esc_attr( $this->id . '-settings-nonce' ); ?>" value="<?php echo esc_attr( $this->nonce ); ?>">
		<?php
	}
}
