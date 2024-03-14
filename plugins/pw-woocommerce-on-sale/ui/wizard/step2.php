<?php if ( !defined( 'ABSPATH' ) ) { exit; } ?>

<?php
    global $pw_on_sale;

    $this_step = 2;

    if ( isset( $pwos_sale ) ) {
        $discount_percentage = $pwos_sale->discount_percentage;
    } else {
        $discount_percentage = 20;
    }
?>

<div class="pwos-heading">
    <div class="pwos-heading-step">Step <?php echo $this_step; ?> of <?php echo $GLOBALS['pwos_last_step']; ?></div>
    Sale Discount
</div>

<select id="pwos-discount-percentage" class="pwos-input">
    <?php
        for( $x = 1; $x <= 100; $x++ ) {
            ?>
            <option value="<?php echo $x; ?>" <?php selected( $discount_percentage, $x ); ?>><?php echo $x; ?>%</option>
            <?php
        }
    ?>
</select> off Regular Price

<?php
    $pw_on_sale->navigation_buttons( $this_step );
?>

<script>
    function pwosWizardValidateStep<?php echo $this_step; ?>() {
        if (!jQuery('#pwos-discount-percentage').val() || jQuery('#pwos-discount-percentage').val() < 1 || jQuery('#pwos-discount-percentage').val() > 100) {
            alert('Invalid Discount Percentage.');
            jQuery('#pwos-discount-percentage').focus();
            return false;
        }

        return true;
    }
</script>
