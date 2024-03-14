<?php
add_thickbox();

if (array_key_exists('socialwidget_global_notification', $_GET) && $_GET['socialwidget_global_notification'] == 0) {
        update_option('socialwidget_global_notification', 0);
}
?>
<div class="wrap">
<div class="notice" style="padding: 11px 15px; border-width:1px;"><a style="text-decoration:none;" href="https://appsumo.com/search/?tags=wordpress&utm_source=sumo&utm_medium=wp-widget&utm_campaign=social-media-widget" target="_blank"><b>Turn your WordPress site into a marketing machine 🚀</b></a></div>

        <div class="social-media-widget-content-left">
                <?php settings_errors(); ?>
                <style type="text/css">
                        #socialwidget_global_notification a.button:active {vertical-align:baseline;}
                </style>
                <h1>Social Media Widget</h1>
                <p>To add or edit a social media widget please <a href="<?php echo admin_url('widgets.php'); ?>">visit the widgets page</a>.</p>
        </div>
        <div class="social-media-widget-content-right">
            <div class="social-media-widget-content-container-right">
                <div class="social-media-widget-promo-box entry-content">
                    <p class="social-media-widget-promo-box-header">Your one stop WordPress shop</p>
                    <ul>
                       <li>&#8226; Get the latest WordPress software deals</li>
                       <li>&#8226; Plugins, themes, form builders, and more</li>
                       <li>&#8226; Shop with confidence; 60-day money-back guarantee</li>
                    </ul>
                    <div align="center">
                        <button onclick="window.open('https://appsumo.com/search/?tags=wordpress&utm_source=sumo&utm_medium=wp-widget&utm_campaign=social-media-widget')" class="social-media-widget-appsumo-capture-container-button" type="submit">Show Me The Deals</button>
                    </div>
                </div>

                <div class="social-media-widget-promo-box social-media-widget-promo-box-form  entry-content">
                    <?php include plugin_dir_path( __FILE__ ).'appsumo-capture-form.php'; ?>
                </div>
            </div>
        </div>
</div>