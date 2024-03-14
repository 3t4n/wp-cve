<div class="card col-sm-12 p-0 border-0">
    <div class="card-body">
        <div class="col-sm-12 p-0 m-0"><?php echo apply_filters('rkmw_form_notices', $view->message); ?></div>

            <div class="col-sm-12 p-0 text-center">
                <a href="https://cloud.rankmywp.com/login?action=register&source=wordpress" target="_blank" class="btn btn-lg btn-primary my-3 noloading"><?php echo esc_html__("Connect and get Free API Key", RKMW_PLUGIN_NAME); ?></a>

                <hr>
                <form class="form-inline justify-content-center" method="post" action="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_dashboard', 'login') ?>">
                    <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_login', 'rkmw_nonce'); ?>
                    <input type="hidden" name="action" value="rkmw_login"/>
                    <div class="form-group m-2">
                        <label for="token"><?php echo esc_html__("Enter API Key", RKMW_PLUGIN_NAME) . ': '; ?></label>
                    </div>
                    <div class="form-group m-2"  style="min-width: 50%">
                        <input type="text" class="form-control bg-light" name="token">
                    </div>
                    <div class="form-group m-2">
                        <button type="submit" class="btn btn-light"><?php echo esc_html__("Connect with API Key", RKMW_PLUGIN_NAME); ?></button>
                    </div>

                </form>
            </div>

    </div>

</div>