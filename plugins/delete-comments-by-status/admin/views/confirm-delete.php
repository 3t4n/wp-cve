<?php if(!defined('WP_DEBUG')) { exit; } ?>

<div class="wrap">
    <h2 class="title">Confirm the status of comments to delete</h2>
    
    <?php if (array_key_exists('error', $_GET)): ?>
        <div class="notice notice-error"><p><?php echo esc_html($_GET['error']); ?></p></div>
    <?php endif; ?>

    <?php if (array_key_exists('success', $_GET)): ?>
        <div class="notice notice-success"><p><?php echo esc_html($_GET['success']); ?></p></div>
    <?php endif; ?>
    
    <?php
        global $wpdb;
        
        $total_comments_delete = 0;
        
        $submitValues = isset($_POST['msbd_comnts_statuses']) ? msbd_sanitization($_POST['msbd_comnts_statuses']) : array(); 
        $comment_statuses = msbddelcom_comment_statuses();
        
        $actionMessage = array();
        $toDeleteCSV = '';
        
        foreach ($comment_statuses as $i=>$v) {
            //$actionMessage[] = 'i: '.$i.'   v: '.$v;
            $str_query = "SELECT count(comment_ID) as counted_rows FROM $wpdb->comments";
            
            if (isset($submitValues[$v]) && $submitValues[$v]==$v) {
                $rs = $wpdb->get_results($str_query." WHERE comment_approved = '".$i."'");
                $count = $rs[0]->counted_rows;
                if (intval($count)>0) {
                    $toDeleteCSV .= $i.',';
                    $total_comments_delete += $count;
                    $actionMessage[] = $count.' '. $v .' comments found to delete!';
                }
            }
        }

        $toDeleteCSV = trim($toDeleteCSV, ",");
    ?>

    <form name="msbddelcom" method="post" action="">
        <ul>
            <?php foreach ($actionMessage as $i=>$v) { ?>
                <li><?php echo $v; ?></li>
            <?php } ?>
        </ul>

        <ul>
            <?php
                if ($total_comments_delete>0) {
                    echo '<li><strong>** Total '.$total_comments_delete.' comments are ready to delete</strong></li>';
                    echo '<li><strong>** Please get backup of your database before delete comments because this action can not be undone!</strong></li>';
                } else {
                    echo '<li><strong>No comments found to delete</strong></li>';
                }
            ?>
        </ul>

        <input type="hidden" name="msbd_comment_statuses" value="<?= $toDeleteCSV; ?>" />
        <input type="hidden" name="action" value="action-confirm-comments-delete">

        <?php wp_nonce_field( 'msbd-nonce-confirm-comments-delete' ); ?>
        <?php submit_button( __( 'Confirm Delete Comments', 'msbddelcom' ), 'primary', 'msbd_btn_confirm_comments_delete' ); ?>
    </form>
</div>
