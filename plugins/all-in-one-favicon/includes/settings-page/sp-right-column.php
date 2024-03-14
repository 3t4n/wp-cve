<?php
/**
 * @package Techotronic
 * @subpackage All in one Favicon
 *
 * @since 4.0
 * @author Arne Franken
 *
 * Right column for settings page
 */
?>
<div class="postbox-container all-in-one-favicon-content-right">
    <div class="all-in-one-favicon-content-right">
        <div class="all-in-one-favicon-content-container-right">
            <div class="all-in-one-favicon-promo-box entry-content">
                <p class="all-in-one-favicon-promo-box-header">Your one stop WordPress shop</p>
                <ul>
                   <li>&#8226; Get the latest WordPress software deals</li>
                   <li>&#8226; Plugins, themes, form builders, and more</li>
                   <li>&#8226; Shop with confidence; 60-day money-back guarantee</li>
                </ul>
                <div align="center">
                    <button onclick="window.open('https://appsumo.com/tools/wordpress/?utm_source=sumo&utm_medium=wp-widget&utm_campaign=all-in-one-favicon')" class="all-in-one-favicon-appsumo-capture-container-button" type="submit">Show Me The Deals</button>
                </div>
            </div>

            <div class="all-in-one-favicon-promo-box all-in-one-favicon-promo-box-form  entry-content">
                <?php include plugin_dir_path( __FILE__ ).'../appsumo-capture-form.php'; ?>
            </div>
        </div>
    </div>
</div>