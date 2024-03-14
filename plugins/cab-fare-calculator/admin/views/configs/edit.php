<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-config', 'tblight_create_config' ); ?>
		<input type="hidden" name="action" value="save" />
		
		<input type="hidden" name="title" id="title" value="<?php echo esc_attr( $item->title ); ?>" />
		<input type="hidden" name="alias" id="alias" value="<?php echo esc_attr( $item->alias ); ?>" />

		<div class="form-group clearfix">
			<label class="label"><?php esc_attr_e( 'Minimum Passengers', 'cab-fare-calculator' ); ?></label>
			<input type="number" name="min_passenger_no" id="min_passenger_no" class="form-control small-text" value="<?php echo esc_attr( $item->min_passenger_no ); ?>" />
		</div>

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
