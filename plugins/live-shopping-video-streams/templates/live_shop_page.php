<?php

/**
 * Description: Template file for Channelize Shop page
 * 
 * @package Channelize Shopping
 * 
 * 
 */
defined('ABSPATH') || exit;
$channelize_page_url = get_permalink(get_page_by_path(chls_get_main_stream_page_details()));


?>

<div id="wpbody" role="main">

    <div id="wpbody-content">
        <div class="js-wc-facebook-for-woocommerce-admin-notice-placeholder"></div>
        <h1>Live Shopping & Video Streams</h1>
        <div class="wrap components-card" id="wpcf7-integration">
            <h1 class="title">Live Shop Page</h1>
            <p>The Live Shop Page displays a list of all the Shopping Shows on your store frontend that you have created using the Live Shopping & Video Streams Dashboard. When you configure the plugin from Settings, this page is automatically generated. This page is also available in Pages with the title <strong>"Streams".</strong>
            </p>
            <div class="card" id="instructions" style="max-width:fit-content;">
                <h2 class="title">Live Shop Page URL</h2>
                <?php
                $config  = get_option('channelize_live_shopping');
                if (isset($config['private_key']) && isset($config['public_key'])) {

                    echo "<div class='inside' >
                       <a href='".esc_url($channelize_page_url)."' target='_blank'>$channelize_page_url</a>
                       <p><strong>Note: </strong>&nbsp;A Live Shop Page's URL usually ends with" . '&nbsp;"/streams".' . " However, if a page with the URL" . ' "/streams"' . " already exists on your website prior to installing our plugin, then the Live Shop page will be created with a different URL, such as " . ' &nbsp;"/streams-1", &nbsp;"/streams-2",' . " and so on. In this case, this page will not list shopping shows, and you must change the URL from Pages to " . '"/streams"' . " to resolve the issue.</p>

                      <p> You'll also need to change the URL of the website's existing page, which currently includes " . '"/streams"' . " to something else.</p>
                       
                       <p>If you still have queries or problems after making the changes, please contact us at <a href='".esc_url("mailto:support@channelize.io")."'>support@channelize.io</a>.</p>
                        </div>";
                } else {
                    echo '<div class="inside">
                        <p>It seems that you have not configured the plugin, please configure the plugin from settings to see "Live Shop" Page URL.</p>
                        </div>';
                }

                ?>
                <div class="inside">
                </div>
            </div>

            <div class="card" id="instructions" style="max-width:620px;">
                <h2 class="title">Add Menu For Live Shop Page</h2>


                <div class="inside">
                    <ul>
                        <li> 1. Navigate to the Admin dashboard's Menu tab (Appearance > Menus).</li>
                        <li> 2. Then, in the Add menu items section, select the Streams page and click <strong>Add To Menu</strong>.</li>
                        <li> 3. Navigate to the site and click the Streams menu.</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="clear"></div>
    </div><!-- wpbody-content -->
    <div class="clear"></div>
</div>