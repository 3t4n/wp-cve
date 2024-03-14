<form action="<?php echo admin_url('admin.php?page=the-tribal-plugin#api-key');?>" method="post" class="dashboard-form">
    <div class="mb-3">
        <label class="form-label">API Key</label>
        <input type="password" class="form-control" name="ttt_api_key" value="<?php echo $apiKey;?>">
        <div id="apiHelp" class="ttt-form-text">
            <div class="container-ttt-content">
                <div class="row">
                    <div class="col-md-1">STATUS: </div>
                    <div class="col-md-11">
                        <?php if(!tttIsKeyActive()) : ?>
                            <span style="color:red;font-weight:bold;">Inactive</span> (Please grab your API key <a href="https://portal.thetechtribe.com/my-tribe-membership" target="_blank">from here</a>)
                        <?php else: ?>
                            <span style="color:green;font-weight:bold;">Active</span> (You're good to go!)
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="action" value="ttt_activate_key">
    <?php wp_nonce_field( 'ttt_client_update_plugin_' . get_current_user_id() ); ?>
    <button type="submit" class="btn btn-primary">ACTIVATE API KEY</button>
</form>