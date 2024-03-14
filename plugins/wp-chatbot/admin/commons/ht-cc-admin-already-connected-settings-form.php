<?php

/**
 * sidebar in admin area - plugin settings page.
 *
 * @uses at settings_page.php
 *
 */

if (!defined('ABSPATH')) exit;

?>
<div class="already-connected tab-content current">
    <div class="already-connected-info">
        All set! The chat widget is now installed on your website.
        <br />
        Go back to MobileMonkey to activate it.
    </div>


    <a target="_blank" rel="noopener noreferrer" href="<?php echo $app_domain ?>chatbot-editor/<?php echo $connected_page['bot_id'] ?>/chat-starters?kind=customer_chat_widget" class="go_to_mm"><?php _e('Go to MobileMonkey') ?></a>

    <?php

    $fb_connected_area_active_page_settings = [
        'connected_page' => $connected_page
    ];
    HT_CC::view('ht-cc-admin-form-bottom-connect', $fb_connected_area_active_page_settings); ?>

    <div id="to_pro" class="modal">
        <div class="modal_close">X</div>
        <div class="upgrade__wrapper">
            <div class="upgrade__content">
                <h4><?php _e('Are you sure that you want to disconnect this page?') ?></h4>
                <p><?php _e('Disconnecting will remove the chat widget from your WordPress site.  Your chatbot will remain connected to your Facebook Page and can still be managed directly from MobileMonkey.') ?></p>
            </div>
            <div class="upgrade__button">
                <a class="button-close-modal blues" href="#"><?php _e('Cancel') ?></a>
                <a href="<?php echo $connected_page['path']; ?>" id="disconnect" class="button-lazy-load reds"><?php _e('Disconnect') ?>
                    <div class="lazyload"></div>
                </a>


            </div>
        </div>
    </div>

    <div class="modal-overlays" id="modal-overlay">
    </div>

</div>
