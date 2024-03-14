<?php if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.'); ?>
<div class="wrap" id="resads-settings">   
    <h2><?php _e('General Settings', RESADS_ADMIN_TEXTDOMAIN); ?></h2>
    <div id="poststuff">
        <?php
        wp_nonce_field('some-' . $_REQUEST['page']);
        wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
        wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); 
        ?>
        <div id="post-body" class="metabox-holder columns-1">
            <div id="postbox-container-1" class="postbox-container">
                <?php do_meta_boxes('','normal',null); ?>
            </div>
        </div>
    </div>
</div>