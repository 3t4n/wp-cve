<?php if ( !defined( 'ABSPATH' ) ) { exit; } ?>

<?php
    global $pw_on_sale;

    $this_step = 3;
?>

<div class="pwos-heading">
    <div class="pwos-heading-step">Step <?php echo $this_step; ?> of <?php echo $GLOBALS['pwos_last_step']; ?></div>
    Sale Name
</div>

<div>This is for your reference only, it will not be shown to customers.</div>
<div>
    <input type="text" id="pwos-title" class="pwos-input" style="width: 100%" value="<?php echo isset( $pwos_sale ) ? esc_html( $pwos_sale->post_title ) : ''; ?>" required="true">
</div>

<div style="margin: 3.0em 0;">
    <div class="pwos-subheading">Note</div>
    All products in the store will be on sale for the given sale dates.<br>
    Want to run a sale for specific product categories?<br>
    <a href="https://pimwick.com/pw-woocommerce-on-sale" class="pwos-link pwos-pro-link" target="_blank">Get PW WooCommerce On Sale! Pro</a>
</div>

<?php
    $pw_on_sale->navigation_buttons( $this_step );
?>

<script>
    function pwosWizardValidateStep<?php echo $this_step; ?>() {
        if (!jQuery('#pwos-title').val() || jQuery('#pwos-title').val().trim() == '') {
            alert('Title is required.');
            jQuery('#pwos-title').focus();
            return false;
        }

        return true;

    }
</script>
