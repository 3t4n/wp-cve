<?php

/**
 * Reserve form template.
 * version 1.2.1
 */

extract( $args ); ?>
<form id="wpim_reserve" name="wpinventory_reserve" method="post" action="#wpim_reserve" class="wpinventory_reserve">
	<?php if ( $form_title ) { ?>
      <h2><?php echo esc_attr( $form_title ); ?></h2>
		<?php
	}
	if ( $error ) { ?>
      <div class="wpinventory_error"><?php echo esc_attr( $error ); ?></div>
	<?php } ?>
	<?php if ( $display_name ) {
		$required = ( $display_name == 2 ) ? ' required' : ''; ?>
      <div class="name<?php echo $required; ?>">
        <label><?php esc_attr_e( $name_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_name" value="<?php esc_attr_e( $name ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_address ) {
		$required = ( $display_address == 2 ) ? ' required' : ''; ?>
      <div class="address<?php echo $required; ?>">
        <label><?php esc_attr_e( $address_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_address" value="<?php esc_attr_e( $address ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_city ) {
		$required = ( $display_city == 2 ) ? ' required' : ''; ?>
      <div class="city"<?php echo $required; ?>>
        <label><?php esc_attr_e( $city_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_city" value="<?php esc_attr_e( $city ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_state ) {
		$required = ( $display_state == 2 ) ? ' required' : ''; ?>
      <div class="state"<?php echo $required; ?>>
        <label><?php esc_attr_e( $state_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_state" value="<?php esc_attr_e( $state ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_zip ) {
		$required = ( $display_zip == 2 ) ? ' required' : ''; ?>
      <div class="zip"<?php echo $required; ?>>
        <label><?php esc_attr_e( $zip_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_zip" value="<?php esc_attr_e( $zip ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_phone ) {
		$required = ( $display_phone == 2 ) ? ' required' : ''; ?>
      <div class="phone"<?php echo $required; ?>>
        <label><?php esc_attr_e( $phone_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_phone" value="<?php esc_attr_e( $phone ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_email ) {
		$required = ( $display_email == 2 ) ? ' required' : ''; ?>
      <div class="email"<?php echo $required; ?>>
        <label><?php esc_attr_e( $email_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_email" value="<?php esc_attr_e( $email ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php if ( $display_quantity ) {
		$required = ( $display_quantity == 2 ) ? ' required' : ''; ?>
      <div class="quantity"<?php echo $required; ?>>
        <label><?php esc_attr_e( $quantity_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <input type="text" name="wpinventory_reserve_quantity" value="<?php esc_attr_e( $quantity ); ?>"<?php echo $required; ?> />
      </div>
	<?php } ?>
	<?php do_action( 'wpim_reserve_form_after_quantity', $args ); ?>
	<?php if ( $display_message ) {
		$required = ( $display_message == 2 ) ? ' required' : ''; ?>
      <div class="message"<?php echo $required; ?>>
        <label><?php esc_attr_e( $message_label ); ?><?php if ( $required ) {
				echo '<span class="req">*</span>';
			} ?></label>
        <textarea name="wpinventory_reserve_message"<?php echo $required; ?>><?php echo esc_textarea( $message ); ?></textarea>
      </div>
	<?php } ?>
	<?php do_action( 'wpim_reserve_form', $args ); ?>
  <div class="submit">
    <input type="hidden" name="_wpim_inventory_id" value="<?php esc_attr_e( $inventory_id ); ?>"/>
    <input type="hidden" name="_wpim_reserve_nonce" value="<?php esc_attr_e( $reserve_nonce ); ?>"/>
    <input type="hidden" name="_wpim_reserve_submit" value="1"/>
    <input type="submit" name="wpinventory_reserve_submit" id="wpim_reserve_submit" value="<?php esc_attr_e( $submit_label ); ?>"/>
  </div>
</form>
