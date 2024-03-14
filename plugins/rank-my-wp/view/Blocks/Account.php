<?php if (RKMW_Classes_Helpers_Tools::getMenuVisible('account_info') && current_user_can('manage_options')) { ?>
    <div class="card-text col-sm-12 p-0 m-0 border-0">
        <div class="author">
            <i class="avatar rkmw_icons rkmw_icon_package"></i>
        </div>
        <div class="block block-pricing text-center">
            <h3 class="block-caption mt-2">
                <?php echo esc_html__("Your Account", RKMW_PLUGIN_NAME) ?>:
                <?php if (isset($view->checkin->product_name)) { ?>
                    <strong style="color: #f7681b; text-transform: uppercase"><?php echo $view->checkin->product_name ?></strong>
                <?php } ?>
            </h3>
        </div>
        <div class="block text-center p-2">
            <?php if (isset($view->checkin->subscription_email)) { ?>
                <h6><?php echo sanitize_email($view->checkin->subscription_email) ?></h6>
            <?php } ?>
        </div>
        <div class="bg-light border-top py-2 mt-2 text-center">
            <a href="<?php echo RKMW_Classes_RemoteController::getCloudLink('account')?>" class="font-weight-bold" target="_blank"><?php echo esc_html__("See Account Details", RKMW_PLUGIN_NAME)  ?></a>
        </div>
    </div>
<?php }