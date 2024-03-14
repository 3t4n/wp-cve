<?php if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.'); ?>
<?php if(isset($this->submit_response['error'])) : ?>
    <div class="notice error is-dismissible below-h2">
        <p><?php print implode('<br />', $this->submit_response['error']); ?></p>
    </div>
<?php endif; ?>
<div id="poststuff">
    <form action="" method="post">
        <?php 
        wp_nonce_field('some-' . $_REQUEST['action']);
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
    </form>
</div>