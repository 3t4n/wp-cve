<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Get saved values
$saved_values = get_option('wc_szamlazz_eusafa');

//Apply filters
$conditions = WC_Szamlazz_Conditions::get_conditions('eusafas');

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html( $data['title'] ); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings-eusafas">
			<?php if($saved_values): ?>
				<?php foreach ( $saved_values as $automation_id => $automation ): ?>

					<div class="wc-szamlazz-settings-eusafa wc-szamlazz-settings-repeat-item">
						<div class="wc-szamlazz-settings-eusafa-title">
							<span class="text"><?php esc_html_e('Disable data disclosure towards NTCAs', 'wc-szamlazz'); ?></span>
							<a href="#" class="delete-eusafa"><?php _e('delete', 'wc-szamlazz'); ?></a>
						</div>
						<div class="wc-szamlazz-settings-eusafa-if">
							<label>
								<input type="checkbox" data-name="wc_szamlazz_eusafas[X][condition_enabled]" <?php checked( $automation['conditional'] ); ?> class="condition" value="yes">
								<span><?php _e('Based on the following conditions:', 'wc-szamlazz'); ?></span>
							</label>
							<ul class="wc-szamlazz-settings-eusafa-if-options conditions" <?php if(!$automation['conditional']): ?>style="display:none"<?php endif; ?> <?php if(isset($automation['conditions'])): ?>data-options="<?php echo esc_attr(json_encode($automation['conditions'])); ?>"<?php endif; ?>></ul>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="wc-szamlazz-settings-eusafa-add">
			<a href="#"><span class="dashicons dashicons-plus-alt"></span> <span><?php _e('Add new logic', 'wc-szamlazz'); ?></span></a>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

<script type="text/html" id="wc_szamlazz_eusafa_sample_row">
	<div class="wc-szamlazz-settings-eusafa wc-szamlazz-settings-repeat-item">
		<div class="wc-szamlazz-settings-eusafa-title">
			<span class="text"><?php esc_html_e('Disable data disclosure towards NTCAs', 'wc-szamlazz'); ?></span>
			<a href="#" class="delete-eusafa"><?php _e('delete', 'wc-szamlazz'); ?></a>
		</div>
		<div class="wc-szamlazz-settings-eusafa-if">
			<label>
				<input type="checkbox" data-name="wc_szamlazz_eusafas[X][condition_enabled]" class="condition" value="yes">
				<span><?php _e('Based on the following conditions:', 'wc-szamlazz'); ?></span>
			</label>
			<ul class="wc-szamlazz-settings-eusafa-if-options conditions" style="display:none"></ul>
		</div>
	</div>
</script>

<?php echo WC_Szamlazz_Conditions::get_sample_row('eusafas'); ?>
