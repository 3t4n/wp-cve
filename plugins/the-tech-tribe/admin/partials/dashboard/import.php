<form action="<?php echo admin_url('admin.php?page=the-tribal-plugin#import');?>" method="post" class="dashboard-form-import">

    <p>Your server will automatically check for new Blog Posts approximately every 24 hours. If you want to run a Manual Import, simply smash the <b>START MANUAL IMPORT</b> button below.</p>
    <div id="apiHelp" class="ttt-form-text">
        <div class="container-ttt-content">
            <div class="row">
                <div class="col-md-3">Last Check: </div>
                <div class="col-md-8"><span class="last-check"><?php echo esc_attr($lastChecked && !empty($lastChecked)) ? date('d F Y h:i A', strtotime($lastChecked)) : '';?></span></div>
            </div>
            <div class="row">
                <div class="col-md-3">Next Schedule Check: </div>
                <div class="col-md-8"><?php echo esc_attr($nextScheduleCron);?> </div>
            </div>
            <div class="row">
                <div class="col-md-3">Last Successfull Import: </div>
                <div class="col-md-8"><span class="last-success-import"><?php echo esc_attr($lastDownload && !empty($lastDownload)) ? date('d F Y h:i A', strtotime($lastDownload)) : '';?></span></div>
            </div>

            <div class="import-ajax-status"></div>

        </div>
    </div>
    
    <input type="hidden" name="action" value="ttt_force_import">
    <?php wp_nonce_field( 'ttt_client_update_plugin_' . get_current_user_id() ); ?>
    <button type="submit" class="btn btn-primary btn-import">START MANUAL IMPORT</button>
</form> 