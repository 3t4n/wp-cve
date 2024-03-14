<?php
/**
 * Admin Settings.
 */

$data = $this->data();
?>

<div class="wrap">

	<h2><?php esc_html_e( 'Contact Me on Zalo Settings', 'contact-me-on-zalo' ); ?></h2>

	<?php $this->message(); ?>

	<form method="post" id="cmoz-settings-form" action="<?php echo $this->form_action(); ?>">

		<hr>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e( 'Zalo Phone Number', 'contact-me-on-zalo' ); ?>
					</th>
					<td>
						<input id="cmoz_phone" name="cmoz_options[phone]" type="text" class="regular-text" value="<?php echo esc_attr( $data['phone'] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e( 'Margin Bottom (px)', 'contact-me-on-zalo' ); ?>
					</th>
					<td>
						<input id="cmoz_margin" name="cmoz_options[margin]" type="number" value="<?php echo esc_attr( $data['margin'] ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e( 'Style', 'contact-me-on-zalo' ); ?>
					</th>
					<td>
						<select name="cmoz_options[style]">
							<option value="1" <?php selected( $data['style'], '1' ) ?>><?php esc_html_e( 'Style 1', 'contact-me-on-zalo' ); ?></option>
							<option value="2" <?php selected( $data['style'], '2' ) ?>><?php esc_html_e( 'Style 2', 'contact-me-on-zalo' ); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e( 'Position', 'contact-me-on-zalo' ); ?>
					</th>
					<td>
						<select name="cmoz_options[position]">
							<option value="left" <?php selected( $data['position'], 'left' ) ?>><?php esc_html_e( 'Left', 'contact-me-on-zalo' ); ?></option>
							<option value="right" <?php selected( $data['position'], 'right' ) ?>><?php esc_html_e( 'Right', 'contact-me-on-zalo' ); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
		<?php wp_nonce_field( 'cmoz-settings', 'cmoz-settings-nonce' ); ?>

	</form>

	<hr />

	<h2><?php esc_html_e( 'Support', 'contact-me-on-zalo' ); ?></h2>
	<p>
		<?php _e( 'For submitting any support queries, feedback, bug reports or feature requests, please visit <a href="https://namncn.com/lien-he/" target="_blank">this link</a>. Other great plugins by Nam Truong, please visit <a href="https://namncn.com/chuyen-muc/plugins/" target="_blank">this link</a>.', 'contact-me-on-zalo' ); ?>
	</p>

</div>
