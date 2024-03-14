<form class="pmagic-form pm-dbfl pg-woocommerce-address-form" method="post" action="" id="pg_shipping_address_form" name="pg_shipping_address_form" onsubmit="return pm_save_shipping_address()">
<?php
$this->pg_edit_woocommerce_address('shipping');
?>
<div class="buttonarea pm-full-width-container">
    <div class="pg_shipping_errors"></div>
    <input type="submit" class="button" name="pg_saved_shipping_address" value="<?php esc_attr_e( 'Save Address', 'profilegrid-woocommerce' ); ?>" />
</div>    
</form>