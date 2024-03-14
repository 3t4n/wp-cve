<?php return; ?>
<?php $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', '')); ?>
<div id="rkmw_notification_bar" style="margin: 20px 0 -15px 0;">

    <?php if (isset($view->checkin)) {
        ?>
        <?php if ($page == 'rkmw_rankings') { ?>
            <?php if ($view->checkin->subscription_serpcheck) { ?>
                <div class="alert alert-success text-center m-0 p-1 small">
                    <?php echo sprintf(esc_html__("%sGoogle Ranking Checker:%s We update the best ranks for each keyword, daily. 100%% accurate and objective.", RKMW_PLUGIN_NAME), '<strong>', '</strong>'); ?>
                    <?php if (!$view->checkin->subscription_serps) { ?>
                        <div class="alert alert-warning text-center m-0 p-1">
                            <?php echo sprintf(esc_html__("%sNo Google Ranking Credits remained.%s Please buy more %s Google Ranking Credits %s", RKMW_PLUGIN_NAME), '<strong>', '</strong>', '<a href="' . RKMW_Classes_RemoteController::getCloudLink('plans') . '" target="_blank"><strong>', '</strong></a>'); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="alert alert-warning text-center m-0  p-1 small">
                    <?php echo sprintf(esc_html__("%sGoogle Search Console Ranking:%s We show ranks according to what Google shows you in Google Search Console. %sPositions shown by GSC are averages, not the exact Google Rank possitions. %sTo have your rankings checked daily you can buy %s Google Ranking Credits %s", RKMW_PLUGIN_NAME), '<strong>', '</strong>', '<br />', '<br />', '<a href="' . RKMW_Classes_RemoteController::getCloudLink('plans') . '" target="_blank"><strong>', '</strong></a>'); ?>
                </div>
            <?php } ?>
        <?php } ?>
        <?php if ($page == 'rkmw_research' && RKMW_Classes_Helpers_Tools::getValue('tab', 'research') == 'research' && isset($view->checkin->subscription_kr)) { ?>
            <?php if ($view->checkin->subscription_kr) { ?>
                <div class="alert alert-success  text-center m-0 p-1 small">
                    <?php echo sprintf(esc_html__("%sKeyword Research:%s You have %s researches left for your account. The research will return up to 20 results for each research depending on the chances of ranking and search volume.", RKMW_PLUGIN_NAME), '<strong>', '</strong>', (int)$view->checkin->subscription_kr); ?>
                </div>
            <?php } else { ?>
                <div class="alert alert-danger  text-center m-0 p-1 small">
                    <?php echo sprintf(esc_html__("%sKeyword Research:%s You have %s researches left for your account. Get more %s Keyword Research Credits %s .", RKMW_PLUGIN_NAME), '<strong>', '</strong>', (int)$view->checkin->subscription_kr, '<a href="' . RKMW_Classes_RemoteController::getCloudLink('plans') . '" target="_blank"><strong>', '</strong></a>'); ?>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ($page == 'rkmw_research' && RKMW_Classes_Helpers_Tools::getValue('tab', '') == 'briefcase') { ?>
            <div class="alert alert-success text-center m-0 p-1 small">
                <?php echo sprintf(esc_html__("%sKeywords Briefcase:%s Add unlimited keywords in your Keywords Briefcase to optimize your posts and pages.", RKMW_PLUGIN_NAME), '<strong>', '</strong>'); ?>
            </div>
        <?php } ?>
        <?php if ($page == 'rkmw_research' && RKMW_Classes_Helpers_Tools::getValue('tab', '') == 'labels') { ?>
            <div class="alert alert-success text-center m-0 p-1 small">
                <?php echo sprintf(esc_html__("%sBriefcase Labels:%s Add unlimited Labels for the Keywords Briefcase to organize the keywords by your SEO strategy.", RKMW_PLUGIN_NAME), '<strong>', '</strong>'); ?>
            </div>
        <?php } ?>
        <?php if ($page == 'rkmw_research' && RKMW_Classes_Helpers_Tools::getValue('tab', '') == 'suggested') { ?>
            <div class="alert alert-success text-center m-0 p-1 small">
                <?php echo sprintf(esc_html__("%sKeyword Suggestion:%s You'll get keyword suggestions every week if we find better matching keywords based on your research history.", RKMW_PLUGIN_NAME), '<strong>', '</strong>'); ?>
            </div>
        <?php } ?>

    <?php } ?>


    <?php if (RKMW_Classes_Helpers_Tools::getMenuVisible('offers') && !isset($_COOKIE['rkmw_nooffer'] )) { ?>
        <?php
        if (isset($view->checkin->offer) && $view->checkin->offer <> '') {
            echo $view->checkin->offer;
        }
        ?>
    <?php } ?>
</div>


