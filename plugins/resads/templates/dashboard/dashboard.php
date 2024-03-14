<?php if(!defined('ABSPATH')) die('You are not allowed to call this page directly.'); ?>

<div class="wrap" id="resads-dashboard">
    <h2><?php _e('ResAds Dashboard', RESADS_ADMIN_TEXTDOMAIN); ?></h2>
    <div class="updated is-dismissible">
        <p><strong><?php _e('First Step', RESADS_ADMIN_TEXTDOMAIN); ?></strong></p>
        <ol>
            <li><?php printf(__('Go to the menu %s AdSpots %s and create an AdSpot', RESADS_ADMIN_TEXTDOMAIN), '<a href="' . admin_url('admin.php?page=resads-adspots') . '">', '</a>'); ?></li>
            <li><?php _e('Copy created Shortcode and put them into an post or create an widget', RESADS_ADMIN_TEXTDOMAIN) ?></li>
            <li><?php printf(__('Go to the menu %s AdManagement %s and create one adsense', RESADS_ADMIN_TEXTDOMAIN), '<a href="' . admin_url('admin.php?page=resads-admanagement') . '">', '</a>'); ?></li>
            <li><?php _e('Assign created banner to adspot', RESADS_ADMIN_TEXTDOMAIN); ?></li>
            <li><?php _e('Ready!', RESADS_ADMIN_TEXTDOMAIN); ?></li>
        </ol>
    </div>
    <div id="poststuff">
        <?php
        wp_nonce_field('some-' . $_REQUEST['page']);
        wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
        wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); 
        ?>
        <div id="post-body" class="metabox-holder columns-2">
            <div id="postbox-container-1" class="postbox-container">
                <?php do_meta_boxes('','side',null); ?>
            </div>
            <div id="postbox-container-2" class="postbox-container">
                <?php do_meta_boxes('','normal',null); ?>
            </div>
        </div>
    </div>
</div>