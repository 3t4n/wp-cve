<?php if(!defined('WP_DEBUG')) { exit; } ?>

<div class="wrap">
    <h2 class="title">Select the status of comments to delete</h2>

    <?php if (array_key_exists('error', $_GET)): ?>
        <div class="notice notice-error"><p><?php echo esc_html($_GET['error']); ?></p></div>
    <?php endif; ?>

    <?php if (array_key_exists('success', $_GET)): ?>
        <div class="notice notice-success"><p><?php echo esc_html($_GET['success']); ?></p></div>
    <?php endif; ?>

    <form name="msbddelcom" method="post" action="<?php echo admin_url( 'admin.php?page=msbd-delete-comments&action=confirm-delete' ); ?>">       
        <ul>
            <?php
            $comment_statuses = msbddelcom_comment_statuses();
            
            foreach ($comment_statuses as $i=>$v) {
                $field_id = 'msbd_comnts_status_'.$v;
            ?>
            <li><label for="<?php echo $field_id; ?>"><input type="checkbox" id="<?php echo $field_id; ?>" name="msbd_comnts_statuses[<?php echo $v; ?>]" value="<?php echo $v; ?>" /> Delete <?php echo $v; ?> comments</label></li>
            <?php
            }
            ?>
        </ul>

        <?php wp_nonce_field( 'msbd-nonce-comments-delete' ); ?>
        <?php submit_button( __( 'Delete Comments', 'msbddelcom' ), 'primary', 'msbd_btn_comments_delete' ); ?>
        
    </form>
</div>
