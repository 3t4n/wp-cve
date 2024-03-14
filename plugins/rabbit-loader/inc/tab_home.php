<?php

if (class_exists('RabbitLoader_21_Tab_Home')) {
    #it seems we have a conflict
    return;
}

final class RabbitLoader_21_Tab_Home extends RabbitLoader_21_Tab_Init
{

    public static function init()
    {
        add_settings_section(
            'rabbitloader_section_home',
            '',
            __CLASS__ . '::echoTopSectionContent',
            'rabbitloader-home'
        );
    }

    public static function echoTopSectionContent($args)
    {

        $showInProgressMessage = false;
        $showConnectionMessage = false;

        $integration_start_time = RabbitLoader_21_Core::getWpOptVal('token_update_ts');

        $warming_progress_bar = 100;
        if (!empty($integration_start_time)) {
            $maxTimeSec = 1 * 3600; //1hour
            $elapsedTimeSec = time() - $integration_start_time;
            $showInProgressMessage =  $elapsedTimeSec < $maxTimeSec;
            $warming_progress_bar = ($elapsedTimeSec * 100) / $maxTimeSec;
            if ($warming_progress_bar < 1) {
                $warming_progress_bar = round($warming_progress_bar, 2);
            } else if ($warming_progress_bar < 10) {
                $warming_progress_bar = round($warming_progress_bar, 1);
            } else if ($warming_progress_bar > 100) {
                $warming_progress_bar = 100;
            } else {
                $warming_progress_bar = round($warming_progress_bar);
            }
        }

        $overview = self::getOverviewData($apiError, $apiMessage);

        if ($apiError) {
            $showInProgressMessage = false;
            $showConnectionMessage = true;
        } else {
        }

        if ($apiMessage == 'AUTH_REQUIRED' || $apiMessage == 'INVALID_DOMAIN') {
            $apiMessage = 'Authentication failed. Please try to reconnect the plugin from Settings tab.';
        } else if ($apiError) {
            $apiMessage = 'Could not connect to RabbitLoader server. This may happen because of a temporary network issues. Please try again in while. (' . $apiMessage . ')';
        }

?>

        <div class="" style="max-width: 1160px; margin:40px auto;">
            <?php if ($showConnectionMessage) { ?>
                <div class="row mb-4">
                    <div class="col">
                        <div class="bg-white rounded p-4">
                            <div class="row">
                                <div class="col px-4 text-secondary">
                                    <h2>Temporary Connection error!</h2>
                                    <span><?php echo $apiMessage; ?>.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($showInProgressMessage) { ?>
                <div class="row mb-4">
                    <div class="col">
                        <div class="bg-white rounded p-4">
                            <div class="row">
                                <div class="text-center" style="width:auto;">
                                    <!-- <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/loader.gif" width="100" />  -->
                                    <div class="score_circle" id="warming_progress_bar" data-percent="<?php echo $warming_progress_bar; ?>%" data-size="120" data-line="5" data-icolor="#1f0757" data-ocolor="#f4f4f4" data-fsize="30px"></div>
                                </div>
                                <div class="col px-4 text-secondary">
                                    <h2>Rabbit is Warming Up</h2>
                                    <span>Some good things require patience. Rabbit has started tuning Core Web Vitals and improving Google PageSpeed Insights score for your pages. Depending on the number of pages on your website, for the first time it may take around an hour to tune all pages and update PageSpeed Insights Score.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <div class="bg-white rounded p-4">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 px-4 text-secondary">
                                    <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/quick-tips.png" class="img-fluid d-inline-block float-end" />
                                    <h2 class="mt-2 mb-4"><?php RL21UtilWP::_e('Recommendations'); ?></h2>
                                    <ul style="list-style-type:disc">
                                        <li><a href="https://rabbitloader.com/kb/disable-themes-pre-loader-feature/" title="Disable preloader" target="_blank">Disable "preloader"</a> if you are using one.</li>
                                        <li>Keep the "Instant content change" checkbox off under the Settings tab to <a href="https://rabbitloader.com/kb/fluctuations-in-pagespeed-performance-score/" title="Performance stability" target="_blank">keep the performance stable</a>.</li>
                                        <li>If you are using a firewall (BitNinja, Cloudflare, Wordfence etc), please <a href="https://rabbitloader.com/kb/rabbitloader-ip-address-whitelisting/" title="Whitelist IP address" target="_blank">whitelist RabbitLoader's IPs</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>
            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 text-center">
                                <div class="score_circle" id="score_circle_best" data-percent="<?php echo $overview['score_circle_best']; ?>"></div>
                                <span class="text-secondary d-block mt-2">Best PageSpeed Score</span>
                            </div>
                            <div class="col-sm-12 col-md-8 px-4">
                                <h5 class="mt-3">Average Score</h5>
                                <div class="progress">
                                    <div class="progress-bar rl-bg-primary " role="progressbar" aria-valuenow="<?php echo $overview['score_circle_avg']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $overview['score_circle_avg']; ?>%"><?php echo $overview['score_circle_avg']; ?> / 100</div>
                                </div>
                                <p class="text-secondary"><?php echo $overview['score_circle_avg']; ?> out of 100, calculated based on all discovered pages, including un-optimized pages.</p>

                                <h5 class="mt-4">Quota Usage (<?php echo $overview['plan_title']; ?> Plan)</h5>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $overview['pp_used'] < 80 ? ' rl-bg-primary  ' : ' bg-danger '; ?>" role="progressbar" aria-valuenow="<?php echo $overview['pp_used']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $overview['pp_used']; ?>%"><?php echo $overview['pp_used']; ?>%</div>
                                </div>
                                <p class="text-secondary"><?php RL21UtilWP::_e(sprintf('You have consumed %s (%s%%) out of %s Page-Views monthly quota available in your current plan.', $overview['pv_used'], round($overview['pp_used'], 2), $overview['pv_quota'])); ?> <a target="_blank" href="<?php echo self::getUpgradeLink('quota_remaining', $overview['plan_title']); ?>" class="badge rl-bg-primary text-white" style="text-decoration: none;"><?php RL21UtilWP::_e('Upgrade'); ?></a></p>
                                <!--<p class="text-secondary"><?php RL21UtilWP::_e(sprintf("%d out of %d detected pages are optimized.", $overview['optimized_url_count'], $overview['canonical_url_count'])); ?> <a href="https://rabbitloader.com/kb/slow-warm-up-wordpress-website/" title="Troubleshooting slow warm-up" target="_blank">Troubleshoot page discovery</a></p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5 class="mt-2 mb-4"><?php RL21UtilWP::_e('Not ready for the world yet?'); ?></h5>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Me</span>
                                <label class="rl-switch">
                                    <?php
                                    RabbitLoader_21_Core::getWpUserOption($user_options);
                                    $private_mode_val = !empty($user_options['private_mode_val']);
                                    ?>
                                    <input type="checkbox" id="rl_chk_public" <?php echo $private_mode_val ? '' : ' checked="checked" ' ?>>
                                    <span class="rl-switch-slider rl-switch-round"></span>
                                </label>
                                <span>Everyone</span>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-secondary"><?php RL21UtilWP::_e('Only you can see the optimized version of the website'); ?></small>
                            </div>
                            <div class="col-6 text-start">
                                <small class="text-secondary"><?php RL21UtilWP::_e('Everyone on the internet sees the optimized version of the website'); ?></small>
                            </div>
                            <primer data-video-id="ol4nuYuYTeM" data-duration="70" data-align="center"></primer>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-12 col-md-6">
                    <?php self::urls_detected_box($overview, true); ?>
                </div>
                <!-- <div class="col-sm-12 col-md-4">
                    <?php //self::optimization_image_home($overview, true);
                    ?>
                </div> -->
                <div class="col-sm-12 col-md-6">
                    <?php self::optimization_css_home($overview, true); ?>
                </div>
                <!-- <div class="col-sm-12 col-md-4">
                    <?php //self::quota_used_box($overview, true);
                    ?>
                </div> -->
                <!-- <div class="col-sm-12 col-md-4">
                    <?php //self::quota_remaining_box($overview);
                    ?>
                </div> -->
            </div>
            <?php
            if ($overview['pp_used'] >= 100) { ?>
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <div class="bg-warning rounded p-4">
                            <h5 class="mt-2"><?php RL21UtilWP::_e('Monthly Quota Exhausted'); ?></h5>
                            <?php RL21UtilWP::_e(sprintf('Optimization services are impacted due to the monthly quota exhausted for the current cycle which ends on %s. Your Google PageSpeed Score may not be maintained anymore. Please upgrade your plan for higher quota.', date('F j, Y', strtotime($overview['plan_end_date'])))); ?>
                            <br>
                            <a target="_blank" href="<?php echo self::getUpgradeLink('quota_exhausted', $overview['plan_title']); ?>" class="rl-btn rl-btn-primary mt-2" style="text-decoration: none;"><?php RL21UtilWP::_e('Upgrade Now'); ?></a>
                        </div>
                    </div>
                </div>
            <?php } else if ($overview['pp_used'] >= 80) { ?>
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <div class="bg-warning rounded p-4">
                            <h5 class="mt-2"><?php RL21UtilWP::_e(sprintf('%s%% Quota Used',  $overview['pp_used'])); ?></h5>
                            <?php RL21UtilWP::_e(sprintf('You\'re using %s (%s%%) of the %s page-views quota available in the current plan\'s cycle which ends on %s. Once it is full, the optimization services on your site may get impacted and hence Google PageSpeed Score may not be maintained anymore.',  $overview['pv_used'], $overview['pp_used'], $overview['pv_quota'],  date('F j, Y', strtotime($overview['plan_end_date'])))); ?>
                            <br>
                            <a target="_blank" href="<?php echo self::getUpgradeLink('quota_warning', $overview['plan_title']); ?>" class="rl-btn rl-btn-primary mt-2" style="text-decoration: none;"><?php RL21UtilWP::_e('Upgrade Now'); ?></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="col-sm-12 col-md-8 px-4 text-secondary">
                                <h5 class="mt-2 mb-4"><?php RL21UtilWP::_e('Clear All Cache'); ?></h5>
                                <span><?php RL21UtilWP::_e('Site content are cached at various CDN locations to improve page load times, and increasing global availability of content. When you place a purge request, contents from all CDN locations are discarded making a few pages load slower till the cache is rebuild. RabbitLoader plugin <b>automatically detects the modified pages and rebuilds cache</b> for them.'); ?></span>
                                <primer data-video-id="QGAuLgOjCu0" data-duration="83"></primer>
                                <div class="mt-5">
                                    <a class="rl-btn rl-btn-primary mb-1 mb-sm-0" href="#" id="rabbitloader_purge_all"><?php RL21UtilWP::_e('Purge All Pages'); ?></a>

                                    <a class="rl-btn rl-btn-outline-primary" href="https://rabbitloader.com/kb/purging-cache-wordpress-plugin/" title="Purge a single page" target="_blank"><?php RL21UtilWP::_e('Purge a Single Page'); ?></a>

                                </div>

                                <?php
                                if (RL21UtilWP::is_flywheel()) {

                                    echo '<span class="d-block mt-2">', sprintf(RL21UtilWP::__('Flywheel Note: You also need to Flush Cache manually from Flywheel dashboard <a href="%s">check details</a>'), "https://rabbitloader.com/kb/settings-for-flywheel/"), '</span>';
                                }
                                ?>
                                <h6 class="mt-2" class="<?php $apiError ? 'text-danger' : 'text-success'; ?>"><?php echo $apiMessage; ?></h6>
                            </div>
                            <div class="col-sm-12 col-md-4 text-center">
                                <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/delete.png" class="img-fluid" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 text-center">
                                <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/help.jpg" class="img-fluid" />
                            </div>
                            <div class="col-lg-8 col-md-12 px-4 text-secondary">
                                <h5 class="mt-2 mb-4"><?php RL21UtilWP::_e('Need Help?'); ?></h5>
                                <span>Facing issue with RabbitLoader plugin? Browse KB for common issues or reach out to our support team at <a href="mailto:support@rabbitloader.com">support@rabbitloader.com</a></span>

                                <div class="mt-5">
                                    <a class="rl-btn rl-btn-outline-primary mb-1 mb-sm-0" href="https://rabbitloader.com/kb/" target="_blank"><?php RL21UtilWP::_e('Browse Knowledge Base'); ?></a>
                                    <a class="rl-btn rl-btn-outline-primary" href="mailto:support@rabbitloader.com" target="_blank"><?php RL21UtilWP::_e('Contact Support'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row  d-none">
                <h6 class="mt-2" class="<?php $apiError ? 'text-danger' : 'text-success'; ?>"><?php echo $apiMessage; ?></h6>
            </div>

        </div>
    <?php
    }

    public static function echoMainContent()
    {
        do_settings_sections('rabbitloader-home');
    ?>
<?php
    }
}
?>