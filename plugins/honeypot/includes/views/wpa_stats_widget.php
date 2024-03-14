<?php if ( ! defined( 'ABSPATH' ) ) exit;  ?>
<style type="text/css">
    .wpa_stat_table{max-width: 100%;filter: blur(2px);-webkit-filter: blur(2px);}
    .wpa_stat_table_holder{position: relative;}
    .wpa_stat_overlay{position: absolute; z-index: 10; width: 80%; height: 80%;top: 10%; left: 10%;box-shadow: 0 0 25px 10px rgba(0,0,0,0.08); background: #fff; border-radius: 5px; text-align:center;}
    .wpa_stat_overlay .wpa_stat_headline{font-size: 20px; margin-top: 20px; padding: 5px;}
    .wpa_stat_overlay .wpa_stat_content{padding: 5px;}
    .wpa_stat_button a{ padding: 5px 30px !important; }
</style>


<div class="wpa_stat_table_holder">
    <img class="wpa_stat_table" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'img/dashboard_stats.png'; ?>" />
    <div class="wpa_stat_overlay">
            <div class="wpa_stat_headline">
                View all spam statistics from dashboard
            </div>
            <div class="wpa_stat_content">
                <p>Enable stats widgets with WP Armour Extended.</p>
                <p>Also, it can auto block spammer's IP and record what spammer are trying to submit.</p>
            </div>
            <div class="wpa_stat_button">
                <a href="https://dineshkarki.com.np/buy-wp-armour-extended" target="_blank" class="button button-primary">Get WP Armour Extended</a>
            </div>

    </div>
</div>

