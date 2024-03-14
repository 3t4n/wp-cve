<?php defined( 'ABSPATH' ) || exit; ?>
<div>
    <div class="emtmpl-setting-row" style="display: none">
        <div class="emtmpl-option-label">
			<?php esc_html_e( 'Email type', '9mail-wp-email-templates-designer' ); ?>
        </div>
        <select class="emtmpl-input emtmpl-set-email-type" name="emtmpl_settings_type" required>
			<?php
			foreach ( $email_types as $id => $title ) {
				printf( "<option value='%s' %s>%s</option>", esc_attr( $id ), selected( $type_selected, $id, false ), esc_html( $title ) );
			}
			?>
        </select>
    </div>
	<?php do_action( 'emtmpl_setting_options', $type_selected ); ?>
    <div>
        <div class="emtmpl-option-label">
			<?php esc_html_e( 'Direction', '9mail-wp-email-templates-designer' ); ?>
        </div>

		<?php
		$directions = [
			'ltr' => esc_html__( 'Left to right', '9mail-wp-email-templates-designer' ),
			'rtl' => esc_html__( 'Right to left', '9mail-wp-email-templates-designer' )
		];
		?>
        <select class="emtmpl-settings-direction" name="emtmpl_settings_direction">
			<?php
			foreach ( $directions as $value => $text ) {
				printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $direction_selected, $value, false ), esc_html( $text ) );
			}
			?>
        </select>
    </div>
</div>
