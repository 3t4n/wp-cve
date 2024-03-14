<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form id="trackship_integrations_form">
	<div class="trackship_integrations">
		<div class="ts_integrations_row">
			<?php
			$integrations = $this->get_trackship_integrations_data();
			foreach ( $integrations as $key => $value ) {
				if ( $value['value'] ) {
					$checked = 'checked';
				} else {
					$checked = '';
				}
				?>
				<div class="ts-grid-item">
					<div class="ts_integration_image"><img src="<?php echo esc_url($value['image']); ?>"></div>
					<div class="ts_integration_title"><?php echo esc_html($value['title']); ?></div>
					<div class="ts_integration_checkbox">
						<input type="hidden" name="<?php echo esc_html( $key ); ?>" value="0"/>
						<input class="tgl tgl-flat" id="<?php echo esc_html( $key ); ?>" name="<?php echo esc_html( $key ); ?>" type="checkbox" <?php echo esc_html( $checked ); ?> value="1"/>
						<label class="tgl-btn" for="<?php echo esc_html( $key ); ?>"></label>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<input type="hidden" name="action" value="trackship_integration_form_update">
	<?php $nonce = wp_create_nonce( 'ts_integrations' ); ?>
	<input type="hidden" id="integrations_nonce" name="integrations_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
</form>
