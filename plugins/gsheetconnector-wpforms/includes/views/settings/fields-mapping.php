<?php
/**
 * Fields mapping table.
 *
 * @var array $args
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wpforms-field-map-table wfgs_mappings wpgs_panel_section">
	<h3><?php echo wp_kses_post( $args['title'] ); ?></h3>
	<input type="hidden" class="submit_json_for_row" value="">
	<table>
		<tbody>
		<?php
		$i = 0;
		foreach ( $args['meta'] as $key => $value ) :

			$flds_name   = [
				'source' => '',
				'custom' => '',
				'secure' => '',
			];
			$extra_class = '';
			$is_custom   = false;

			$key = ( false !== $value ) ? esc_attr( $key ) : '';

			if ( ! wpforms_is_empty_string( $key ) ) {
				$is_custom = ( 0 === strpos( $key, 'custom_' ) && is_array( $value ) );

				if ( $is_custom ) {
					$key                 = substr_replace( $key, '', 0, 7 );
					$value['value']      = ! empty( $value['secure'] ) ? \WPForms\Helpers\Crypto::decrypt( $value['value'] ) : $value['value'];
					$flds_name['custom'] = sprintf( '%1$s[custom_%2$s][value]', $args['name'], $key );
					$flds_name['secure'] = sprintf( '%1$s[custom_%2$s][secure]', $args['name'], $key );

					$extra_class = ' field-is-custom-value';

				} else {
					$flds_name['source'] = sprintf( '%1$s[%2$s]', $args['name'], $key );
				}
			}
			
			$field_id = "wfgs-wpforms-field-option-".$args['wpgs_feed_id'].'-'.$key;
		?>
			<tr>
				<td class="key">
					<input type="text" value="<?php echo esc_attr( str_replace("___", " ",$key) ); ?>" placeholder="<?php esc_attr_e( 'Enter Column Name Upgrade To Pro &hellip;', 'gsheetconnector-wpforms' ); ?>" class="http-key-source" data-gs_index="<?php echo $i; $i++; ?>" data-feed-id="<?php echo $args['wpgs_feed_id'] ?>">
					<label for="<?php echo $field_id ?>">&nbsp;</label>
					
				</td>
				<td class="field<?php echo esc_attr( $extra_class ); ?>">
					<div class="wpforms-field-map-wrap">
						<div class="wpforms-field-map-wrap-l">

							<?php 
							if ((is_plugin_active('wpforms-lite/wpforms.php') ) && (!is_plugin_active('wpforms/wpforms.php') )) {
							?>
								<input type="hidden" id="is_wpform_lite" value="1" id="is_wpform_lite" />
							<?php }else{
								?>
								<input type="hidden" name="is_wpform_lite" value="0" id="is_wpform_lite" />
								<?php
							} ?>
							
							<input type="text" value="<?php echo esc_attr( $value ); ?>" class="key-destination wpforms-field-map-inputText" name="<?php echo esc_attr( $flds_name['source'] ); ?>" data-name="<?php echo esc_attr( $args['name'] ); ?>" data-suffix="[{source}]" data-field-map-allowed="<?php echo isset($args['allowed_types']) ? esc_attr( $args['allowed_types'] ) : ''; ?>" data-custom-value-support="true" id="<?php echo $field_id ?>" >
							
							<select style="display: none;" data-name="<?php echo esc_attr( $args['name'] ); ?>" data-suffix="[{source}]" data-field-map-allowed="<?php echo isset($args['allowed_types']) ? esc_attr( $args['allowed_types'] ) : ''; ?>" data-custom-value-support="true"><option></option></select>
							
							<label for="<?php echo $field_id ?>">
								<a href="#" class="toggle-smart-tag-display" data-type="all" data-fields=""><i class="fa fa-tags"></i> <span>Show Smart Tags</span></a>
							</label>
						</div>
						
					</div>
				</td>
				<td class="actions">
					<a class="add" href="#"><i class="fa fa-plus-circle"></i></a>
					<a class="remove" href="#"><i class="fa fa-minus-circle"></i></a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
