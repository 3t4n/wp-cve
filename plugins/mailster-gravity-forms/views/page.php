<?php

if ( ! function_exists( 'mailster' ) ) {

	echo '<h3>Please enable the <a href="https://mailster.co/?utm_campaign=wporg&utm_source=Gravity+Forms+Mailster+Addon&utm_medium=plugin">Mailster Newsletter Plugin</a></h3>';

	return;
}

$form_id  = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : null;
$form     = RGFormsModel::get_form_meta( $form_id );
$mailster = isset( $form['mailster'] ) ? $form['mailster'] : array(
	'lists'             => array(),
	'map'               => array(),
	'conditional_field' => null,
);

?>
<form action="" method="post" id="gform_form_settings">

	<fieldset id="gform-settings-section-general-settings" class="gform-settings-panel gform-settings-panel--full gform-settings-panel--with-title">
		<legend class="gform-settings-panel__title gform-settings-panel__title--header"><?php esc_html_e( 'Mailster Settings', 'mailster-gravityforms' ); ?></legend>

	<div class="gform-settings-panel__content">

		<div id="gform_setting_preventIP" class="gsttings-field">
			<table class="gforms_form_settings" cellspacing="0" cellpadding="0">
			<tr>
				<th></th>
				<td><label><input type="checkbox" name="mailster[active]" value="1" <?php checked( isset( $mailster['active'] ) ); ?>> <?php esc_html_e( 'Enable Mailster for this Form', 'mailster-gravityforms' ); ?></label>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Map Fields', 'mailster-gravityforms' ); ?></th>
				<td>
				<p class="description">
					<?php esc_html_e( 'Define which field represents which value from your Mailster settings.', 'mailster-gravityforms' ); ?><br>
					<?php esc_html_e( 'Mailster can create lists if certain fields are checked or not empty.', 'mailster-gravityforms' ); ?>
				</p>
				<?php
				$fields = array(
					'email'     => mailster_text( 'email' ),
					'firstname' => mailster_text( 'firstname' ),
					'lastname'  => mailster_text( 'lastname' ),
				);

				if ( $customfields = mailster()->get_custom_fields() ) {
					foreach ( $customfields as $field => $data ) {
						$fields[ $field ] = $data['name'];
					}
				}
				$optionsdd = '<option value="-1">' . esc_html__( 'choose', 'mailster-gravityforms' ) . '</option>';
				foreach ( $fields as $id => $name ) {
					$optionsdd .= '<option value="' . $id . '">' . $name . '</option>';
				}

				if ( is_array( $form['fields'] ) && ! empty( $form['fields'] ) ) {
					echo '<ul id="mailster-map">';
					foreach ( $form['fields'] as $field ) {
						if ( isset( $field['inputs'] ) && is_array( $field['inputs'] ) ) {
							echo '<li><strong>' . ( ! empty( $field['label'] ) ? $field['label'] : esc_html__( 'Untitled', 'mailster-gravityforms' ) ) . ':</strong><ul>';

							foreach ( $field['inputs'] as $input ) {
								echo '<li><label>' . $input['label'] . '</label> ➨ <select name="mailster[map][' . $input['id'] . ']" >';
								echo '<option value="-1">' . esc_html__( 'not mapped', 'mailster-gravityforms' ) . '</option>';
								echo '<optgroup label="' . esc_html__( 'Fields', 'mailster-gravityforms' ) . '">';
								foreach ( $fields as $id => $name ) {
									echo '<option value="' . $id . '" ' . selected( $id, @$mailster['map'][ $input['id'] . '' ], false ) . '>' . $name . '</option>';
								}
								echo '</optgroup>';
								echo '<optgroup label="' . esc_html__( 'List specific', 'mailster-gravityforms' ) . '">';
								echo '<option value="_list" ' . selected( '_list', @$mailster['map'][ $input['id'] . '' ], false ) . '>' . esc_html__( 'Add to List if checked or not empty', 'mailster-gravityforms' ) . '</option>';
								echo '</optgroup>';
								echo '</select></li>';
							}

							echo '</ul></li>';

						} else {
							echo '<li> <label><strong>' . $field['label'] . '</strong></label> ➨ <select name="mailster[map][' . $field['id'] . ']">';
							echo '<option value="-1">' . esc_html__( 'not mapped', 'mailster-gravityforms' ) . '</option>';
							echo '<optgroup label="' . esc_html__( 'Fields', 'mailster-gravityforms' ) . '">';
							foreach ( $fields as $id => $name ) {
								echo '<option value="' . $id . '" ' . selected( $id, @$mailster['map'][ $field['id'] . '' ], false ) . '>' . $name . '</option>';
							}
							echo '</optgroup>';
							echo '<optgroup label="' . esc_html__( 'List specific', 'mailster-gravityforms' ) . '">';
								echo '<option value="_list" ' . selected( '_list', @$mailster['map'][ $field['id'] . '' ], false ) . '>' . esc_html__( 'Add to List if checked or not empty', 'mailster-gravityforms' ) . '</option>';
							echo '</optgroup>';
							echo '</select></li>';
						}
					}
					echo '</ul>';
				} else {
					esc_html_e( 'no fields defined!', 'mailster-gravityforms' );
				}

				?>

				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Subscribe new users to', 'mailster-gravityforms' ); ?></th>
				<td>
				<?php
				$selected = isset( $mailster['lists'] ) ? $mailster['lists'] : array();
				mailster( 'lists' )->print_it( null, null, $name = 'mailster[lists]', false, $selected );
				?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Double Opt In', 'mailster-gravityforms' ); ?></th>
				<td><label><input type="checkbox" name="mailster[double-opt-in]" value="1" <?php checked( isset( $mailster['double-opt-in'] ) ); ?>> <?php esc_html_e( 'Users have to confirm their subscription', 'mailster-gravityforms' ); ?></label><br>

				</td>
			</tr>

			<tr>
				<th><?php esc_html_e( 'Conditional check', 'mailster-gravityforms' ); ?></th>
				<td><label><input type="checkbox" name="mailster[conditional]" value="1" <?php checked( isset( $mailster['conditional'] ) ); ?>> <?php esc_html_e( 'Enable Conditional check', 'mailster-gravityforms' ); ?></label>
				<p><?php esc_html_e( 'subscribe user only if', 'mailster-gravityforms' ); ?>
				<?php
				if ( is_array( $form['fields'] ) ) {
					echo '<select name="mailster[conditional_field]"><option value="-1">-</option>';
					foreach ( $form['fields'] as $field ) {
						if ( ! in_array( $field['type'], array( 'checkbox', 'radio' ) ) ) {
							continue;
						}

						if ( isset( $field['inputs'] ) && is_array( $field['inputs'] ) ) {
							echo '<optgroup label="' . ( $field['label'] ? $field['label'] : esc_html__( 'Checkbox', 'mailster-gravityforms' ) ) . '">';
							foreach ( $field['inputs'] as $input ) {
								echo '<option value="' . $input['id'] . '" ' . selected( $input['id'], $mailster['conditional_field'], false ) . '>' . $input['label'] . '</option>'; }
							echo '</optgroup>';

						} elseif ( isset( $field['choices'] ) && is_array( $field['choices'] ) ) {
							echo '<optgroup label="' . $field['label'] . '">';
							foreach ( $field['choices'] as $input ) {
								echo '<option value="' . $field['id'] . '|' . $input['value'] . '" ' . selected( $input['value'], $mailster['conditional_field'], false ) . '>' . $input['text'] . '</option>'; }
							echo '</optgroup>';

						} else {
							echo '<option value="' . $field['id'] . '" ' . selected( $input['id'], $mailster['conditional_field'], false ) . '>sss' . $field['label'] . '</option>';
						}
					}
					echo '</select>';
				}
				?>
				<?php esc_html_e( 'is checked', 'mailster-gravityforms' ); ?></p>
				</td>
			</tr>
		</table>
		<?php wp_nonce_field( 'mailster_gf_save_form', 'gform_save_form_settings' ); ?>
		<input type="hidden" id="gform_meta" name="gform_meta">
		</div>
	</div>

	</fieldset>

	<div class="gform-settings-save-container">
		<button type="submit" id="gform-settings-save" name="gform_save_settings" value="save" form="gform_form_settings" class="primary button large"><?php esc_html_e( 'Update Form Settings', 'mailster-gravityforms' ); ?> &nbsp;→</button>
	</div>

</form>
