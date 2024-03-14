<div class="xpro-contact-form-message"></div>
<form id="xpro-contact-form-<?php echo esc_attr( $this->get_id() ); ?>" class="xpro-contact-form" action="<?php echo esc_url( site_url() . '/wp-admin/admin-ajax.php?action=xpro_elementor_contact_form&nonce=' . wp_create_nonce( 'xpro-contact-nonce' ) ); ?>">
	<?php
	foreach ( $settings['form_fields'] as $i => $item ) {
		?>
		<div class="xpro-contact-form-item xpro-contact-form-item-type-<?php echo esc_attr( $item['field_type'] ); ?><?php echo isset( $item['required'] ) && $item['required'] ? ' xpro-contact-form-require' : ''; ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> <?php echo esc_attr( $item['css_classes'] ); ?>">
			<?php if ( 'html' !== $item['field_type'] ) : ?>
				<label for="form-field-<?php echo esc_attr( $item['_id'] ); ?>" class="xpro-contact-form-item-label"><?php echo esc_html( $item['field_label'] ); ?></label>
			<?php endif; ?>
			<?php
			switch ( $item['field_type'] ) :
				case 'html':
					xpro_elementor_kses( $item['field_html'] );
					break;
				case 'textarea':
					echo $this->textarea_field( $item, $i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;

				case 'select':
					echo $this->select_field( $item, $i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;

				case 'radio':
				case 'checkbox':
					echo $this->radio_checkbox_field( $item, $i, $item['field_type'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
				case 'text':
				case 'email':
				case 'url':
				case 'tel':
				case 'number':
				case 'date':
				case 'time':
					$this->add_render_attribute( 'input' . $i, 'id', 'form-field-' . $item['_id'] );
					$this->add_render_attribute( 'input' . $i, 'name', 'form-field-' . $item['_id'] );
					$this->add_render_attribute( 'input' . $i, 'class', 'xpro-contact-form-field-textual' );
					$this->add_render_attribute( 'input' . $i, 'type', $item['field_type'] );
					$this->add_render_attribute( 'input' . $i, 'placeholder', $item['placeholder'] );
					$this->add_render_attribute( 'input' . $i, 'value', $item['field_value'] );
					if ( isset( $item['required'] ) && $item['required'] ) {
						$this->add_render_attribute( 'input' . $i, 'required', 'required' );
						$this->add_render_attribute( 'input' . $i, 'aria-required', 'true' );
					}
					echo '<input ' . $this->get_render_attribute_string( 'input' . $i ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;
				default:
					$field_type = $item['field_type'];
			endswitch;
			?>
		</div>
	<?php } ?>
	<?php if ( $settings['recaptcha'] ) : ?>
		<div class="xpro-contact-form-item xpro-contact-form-item-type-captcha">
			<?php if ( $user_settings['recaptcha']['site_key'] && $user_settings['recaptcha']['secret_key'] ) { ?>
				<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $user_settings['recaptcha']['site_key'] ); ?>"></div>
				<?php
			} else {
				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					?>
					<div class="xpro-alert xpro-alert-warning" role="alert">
					<span class="xpro-alert-title">
						<?php esc_html_e( 'reCaptcha Keys Not Found!', 'xpro-elementor-addons' ); ?>
					</span>
						<span class="xpro-alert-description">
						<?php esc_html_e( 'Please set your reCaptcha site and secret keys in "Xpro Elementor Addons Settings" to show captcha correctly.', 'xpro-elementor-addons' ); ?>
					</span>
					</div>
					<?php
				}
			}
			?>
		</div>
	<?php endif; ?>
	<div class="xpro-contact-form-item xpro-contact-form-item-type-submit">
		<button type="submit" class="xpro-contact-form-submit-button">
			<?php echo esc_html( $settings['button_text'] ); ?>
			<i aria-hidden="true" class="fas fa-circle-notch"></i>
		</button>
	</div>
</form>
