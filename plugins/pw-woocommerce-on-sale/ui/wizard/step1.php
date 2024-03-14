<?php if ( !defined( 'ABSPATH' ) ) { exit; } ?>

<?php
    global $pw_on_sale;

    $this_step = 1;

    if ( isset( $pwos_sale ) ) {
        $begin_date = $pwos_sale->begin_date;
        $begin_time = $pwos_sale->begin_time;
        $end_date = $pwos_sale->end_date;
        $end_time = $pwos_sale->end_time;
    } else {
        $default_date = new DateTime( 'tomorrow' );
        $begin_date = $default_date->format( 'Y-m-d' );
        $begin_time = '12:00 AM';
        $end_date = $default_date->format( 'Y-m-d' );
        $end_time = '11:59 PM';
    }
?>

<div class="pwos-heading">
    <div class="pwos-heading-step">Step <?php echo $this_step; ?> of <?php echo $GLOBALS['pwos_last_step']; ?></div>
    Schedule
</div>

<div>
    <label for="pwos-begin-date" class="pwos-input-label">Sale Begins</label>
    <input type="text" id="pwos-begin-date" class="pwos-input" value="<?php echo $begin_date; ?>" required="true">
    <input type="text" id="pwos-begin-time" class="pwos-input" value="<?php echo $begin_time; ?>" required="true">
</div>

<div>
    <label for="pwos-end-date" class="pwos-input-label">Sale Ends</label>
    <input type="text" id="pwos-end-date" class="pwos-input" value="<?php echo $end_date; ?>" required="true">
    <input type="text" id="pwos-end-time" class="pwos-input" value="<?php echo $end_time; ?>" required="true">
</div>

<?php
    $pw_on_sale->navigation_buttons( $this_step );
?>

<script>
    jQuery(function() {
        var dates = jQuery(this).find('#pwos-begin-date, #pwos-end-date').datepicker({
            defaultDate: '',
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            showButtonPanel: true,
            onSelect: function(selectedDate) {
                var option   = jQuery(this).is('#pwos-begin-date') ? 'minDate' : 'maxDate';
                var instance = jQuery(this).data('datepicker');
                var date     = jQuery.datepicker.parseDate( instance.settings.dateFormat || jQuery.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                dates.not(this).datepicker('option', option, date);
            }
        });
    });

    function pwosWizardValidateStep<?php echo $this_step; ?>() {
        if (!jQuery('#pwos-begin-date').val()) {
            alert('Begin Date is required.');
            jQuery('#pwos-begin-date').focus();
            return false;
        }
        if (!jQuery('#pwos-begin-time').val()) {
            alert('Begin Time is required.');
            jQuery('#pwos-begin-time').focus();
            return false;
        }

        if (!jQuery('#pwos-end-date').val()) {
            alert('End Date is required.');
            jQuery('#pwos-end-date').focus();
            return false;
        }
        if (!jQuery('#pwos-end-time').val()) {
            alert('End Time is required.');
            jQuery('#pwos-end-time').focus();
            return false;
        }

        return true;
    }
</script>