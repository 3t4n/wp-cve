<?php

/**
 *  View of Facebook button area when connected.
 *
 * @uses at class-htcc-admin.php
 */

if (!defined('ABSPATH')) exit;
?>

<div class="connected-page-bottom">
    <div class="active-page-info">
        <h3 class="acc-title colapse">Page Connection <i class="fa fa-angle-down step_fa"></i></h3>

        <div class="acc-content" style="display: none">
            <div class="accordionItemHeading">
                <div class="log_out__wrapper">
                <h5>Connected Facebook Page</h5>
                </div>
                <div class="disconnect_page__wrapper">
                    <p><?php echo $connected_page['name']; ?><b> (Connected)</b></p>
                <div class="connected-page-settings">
                    <div id="button_disconnect_page" class="button-lazy-load">Disconnect
                        <div class="lazyload"></div>
                    </div>


                </div>
                </div>
                <input type="hidden" name="htcc_options[fb_page_id]" value="<?php echo $connected_page['remote_id'] ?>">
            </div>
        </div>
    </div>
</div>