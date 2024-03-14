<?php
defined('ABSPATH') or die("No script kiddies please!");

$edac_settings = $this->edac_settings;
$booked_dates = $edac_settings['booked_date'];
$booked_dates = implode(',',$booked_dates);   
    //echo '<pre>';
//    print_r($edac_settings);
//    echo '</pre>';
//echo $booked_dates;
?>

<div class="edac-calendar-wrapper">
    <div class="edac-backend-title"><?php _e('Admin Calendar','edac-plugin');?></div>
    <div class="edac-inner-calendar-wrap">
        <div class="edac-calendar"></div>
        <div id="edac-datepicker"></div>
    </div>
</div>
<?php
/**
 * Creating a nonce field
 * */
 wp_nonce_field('edac-book-nonce','edac_book_nonce_field');
?>
<div class="edac-hidden-field">
    <input type="hidden" id="edac-booked-dates" value="<?php echo $booked_dates;?>" data-message="<?php _e('Successfully booked.','edac-plugin')?>" data-message-remove="<?php _e('Successfully removed.','edac-plugin')?>" />
</div>