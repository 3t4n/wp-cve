<div class="ga-connect-place">
    <?php if (isset($view->checkin->connection_gsc) && $view->checkin->connection_gsc) { ?>
        <div class="card col-sm-12 bg-googlesc px-0 py-0 mx-0">
            <div class="card-heading my-2">
                <h3 class="card-title text-white">
                    <div class="google-icon fa fa-google mx-2"></div><?php echo esc_html__("Google Search Console", RKMW_PLUGIN_NAME); ?>
                </h3>
            </div>
            <div class="card-body bg-light py-3">
                <div class="row">
                    <h6 class="col-sm-7 py-3 m-0  text-black-50"><?php echo esc_html__("You are connected to Google Search Console", RKMW_PLUGIN_NAME) ?></h6>
                    <div class="col-sm-5">
                        <form method="post" class="p-0 m-0" onsubmit="if(!confirm('Are you sure?')){return false;}">
                            <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_settings_gsc_revoke', 'rkmw_nonce'); ?>
                            <input type="hidden" name="action" value="rkmw_settings_gsc_revoke"/>
                            <button type="submit" class="btn btn-block btn-social btn-google text-info btn-lg">
                                <?php echo esc_html__("Disconnect", RKMW_PLUGIN_NAME) ?>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    <?php } else { ?>
        <div class="col-sm-12 bg-googlesc py-2 mb-2">
            <div class="col-sm-12">
                <h4 class="text-white py-3"><?php echo esc_html__("Connect this site to Google Search Console", RKMW_PLUGIN_NAME); ?></h4>
                <p><?php echo esc_html__("Connect Google Search Console and get traffic insights for your website on each Audit.", RKMW_PLUGIN_NAME) ?></p>
                <p><?php echo sprintf(esc_html__("Need Help Connecting Google Search Console? %sClick Here%s", RKMW_PLUGIN_NAME),'<a href="https://howto.rankmywp.com/faq/need-help-connecting-google-search-console-both-tracking-code-and-api-connection/" target="_blank" style="color: lightyellow; text-decoration: underline">','</a>') ?></p>
            </div>
            <div class="rkmw_step1 mt-1">
                <a href="<?php echo RKMW_Classes_RemoteController::getApiLink('gscoauth'); ?>" onclick="jQuery('.rkmw_step1').hide();jQuery('.rkmw_step2').show();" target="_blank" type="button" class="btn btn-block btn-social btn-google text-info connect-button connect btn-lg">
                    <span class="fa fa-google"></span> <?php echo esc_html__("Sign in", RKMW_PLUGIN_NAME); ?>
                </a>
            </div>
            <div class="rkmw_step2 mt-1" style="display: none">
                <button type="button" onclick="location.reload();" class="btn btn-block btn-social btn-warning btn-lg">
                    <span class="fa fa-google"></span> <?php echo esc_html__("Check connection", RKMW_PLUGIN_NAME); ?>
                </button>
            </div>
        </div>
    <?php } ?>
</div>