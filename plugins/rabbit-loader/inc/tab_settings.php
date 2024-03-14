<?php

if (class_exists('RabbitLoader_21_Tab_Settings')) {
    #it seems we have a conflict
    return;
}

class RabbitLoader_21_Tab_Settings extends RabbitLoader_21_Tab_Init
{

    public static function init()
    {
    }

    public static function echoMainContent()
    {

        $isConnected = self::isPluginActivated();

        $rlaction = RabbitLoader_21_Util_Core::get_param('rlaction');
        $page = RabbitLoader_21_Util_Core::get_param('page');
        $tab = RabbitLoader_21_Util_Core::get_param('tab');

        $urlparts = parse_url(home_url());
        $domain = $urlparts['scheme'] . '://' . $urlparts['host'];
        if (!empty($urlparts['port'])) {
            $domain .= ':' . $urlparts['port'];
        }

        if (strcmp($rlaction, 'disconnect') === 0) {
            RabbitLoader_21_Core::update_api_tokens('', '', '', 'user action disconnect');
            $isConnected = false;
            $url_connect = add_query_arg(array('tab' => $tab, 'page' => $page, 'rlaction' => false));
            echo '<script>window.location="' . $url_connect . '";</script>';
            return;
        } else if (strcmp($rlaction, 'savekeys') === 0) {
            $connected = false;

            $tokens = RabbitLoader_21_Util_Core::get_param('rl-token', true);
            $tokens = base64_decode($tokens);
            if ($tokens) {
                $tokens = json_decode($tokens, true);
                if (!empty($tokens['api_token'])) {
                    RabbitLoader_21_Core::update_api_tokens($tokens['api_token'], $urlparts['host'], $tokens['did'], '');
                    $connected = true;
                }
                if (isset($tokens['cdn_prefix'])) {
                    update_option('rabbitloader_cdn_prefix', $tokens['cdn_prefix'], true);
                }
            }

            if ($connected) {
                $url_connect = add_query_arg(array('tab' => 'home', 'page' => $page, 'rlaction' => false, 'token' => false));
                do_action('rl_site_connected');
                echo '<script>window.location="' . $url_connect . '";</script>';
            }
        }
        if ($isConnected) {
            $url_disconnect = add_query_arg(array('tab' => $tab, 'page' => $page, 'rlaction' => 'disconnect'), null);
?>
            <div class="" style="max-width: 1160px; margin:40px auto;">
                <?php
                self::show_cf_box();
                self::general();
                self::excludeUrls();
                self::ignoreParams();
                self::advanceSettings();
                self::echoConnectedBox($url_disconnect);
                ?>
            </div>
        <?php
        } else {

            $url_redirect = $domain . add_query_arg(array('tab' => $tab, 'page' => $page, 'rlaction' => 'savekeys'));

            $url_oauth = RabbitLoader_21_Core::getRLDomain() . "account/?source=wp-plugin&action=connect&site_url=" . urlencode(site_url()) . "&redirect_url=" . urlencode($url_redirect) . '&cms_v=' . get_bloginfo('version') . '&plugin_v=' . RABBITLOADER_PLUG_VERSION;

        ?>
            <style>
                @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap");
            </style>
            <div class="div-connect-parent">
                <div class="rl-content-area border-bottom">
                    <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/logo-dark.svg" width="140" />
                </div>
                <div class=" rl-content-area border-bottom">
                    <h4>Get 100/100 PageSpeed</h4>
                    <p>on Google PageSpeed Insight</p>

                    <?php
                    $conflictPluginMessages = RabbitLoader_21_Conflicts::getMessages();
                    if (empty($conflictPluginMessages)) {
                        echo '<a href="' . $url_oauth . '" class="rl-btn rl-btn-primary rl-btn-lg my-4">Activate RabbitLoader</a>';
                    } else {
                        foreach ($conflictPluginMessages as $plugMessage) {
                            echo '<div class="alert alert-danger" role="alert">';
                            _e($plugMessage, RABBITLOADER_TEXT_DOMAIN);
                            echo '</div>';
                        }

                        echo '<div class="my-4">';
                        _e('The above warning(s) need to be fixed before activating RabbitLoader.');
                        echo '</div>';
                    }
                    ?>

                </div>
                <div class="rl-content-area footer-area">
                    <div class="footer-nav">
                        <ul class="d-flex my-2">
                            <li><a href="https://rabbitloader.com/wordpress-crash-course/" target="_blank">Crash Courses</a></li>
                            <li><a href="https://rabbitloader.com/terms/" target="_blank">Terms &amp; Conditions</a></li>
                            <li><a href="mailto:support@rabbitloader.com" target="_blank">Get Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php
    }

    private static function echoConnectedBox($disconnect_url)
    {
    ?>
        <div class="row mb-4">
            <div class="col">
                <div class="bg-white rounded p-4">
                    <div class="row">

                        <div class="col-8 text-secondary">
                            <h5 class="mt-0">Connected</h5>
                            <span>Your website is connected with RabbitLoader service.</span>

                            <div class="mt-4">
                                <a type="button" class="btn btn-outline-danger" href="<?php echo $disconnect_url; ?>">Disconnect from RabbitLoader</a>
                            </div>
                        </div>

                        <div class="col-4 text-center">
                            <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/checked-2.png" class="img-fluid" style="max-height:150px;" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    private static function general()
    {
        //introduced@2.14.0
        RabbitLoader_21_Core::getWpUserOption($user_options);
        if (isset($_POST['form_name']) && strcmp($_POST['form_name'], 'general_settings') == 0) {
            $user_options['purge_on_change'] = !empty($_POST['chk_purge_on_change']);
            $user_options['private_mode_val'] = !empty($_POST['chk_private_mode']);
            RabbitLoader_21_Core::updateUserOption($user_options);
        }
        $purge_on_change = !empty($user_options['purge_on_change']);
        $private_mode_val = !empty($user_options['private_mode_val']);
    ?>
        <div class="row mb-4">
            <div class="col">
                <div class="bg-white rounded p-4">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 text-secondary">
                            <h5 class="mt-0">General Settings</h5>
                            <span></span>
                            <div class="mt-4">
                                <form method="post">
                                    <input type="hidden" name="form_name" value="general_settings" />
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="chk_purge_on_change" name="chk_purge_on_change" style="margin-top: 0.25em; vertical-align: top;" <?php echo $purge_on_change ? ' checked="checked" ' : ''; ?>>
                                        <label class="form-check-label" for="chk_purge_on_change">
                                            <?php RL21UtilWP::_e('Instant content change is crucial for my visitors (default: off)'); ?>
                                        </label>
                                        <span class="dashicons dashicons-info-outline text-secondary tpopup" title="Click to know more" title-html="<div style='text-align:left'><h4>Cache replace behavior when content is modified</h4><br><b>If on:</b> When you update a page or post content, the current cache will be wiped out and the pages would be optimized again. This is useful for sites where content freshness is crucial over the speed performance. This can cause an intermittent low PageSpeed score.<br><b>If off</b>: When you update a page or post content, the system will keep serving the visitors from currently cached content but start updating the cache gradually with the fresh content. This is recommended for most users.<br><a href='https://rabbitloader.com/kb/purging-cache-wordpress-plugin/' target='_blank'>read more</a></div>" style="font-size: 16px; line-height: 28px;"></span>

                                        <?php
                                        if ($purge_on_change) {
                                            echo '<span class="text-danger d-block mb-2" style="font-size: 0.75rem;">';
                                            RL21UtilWP::_e('You may see fluctuations in SpeedScore because the above checkbox is on.');
                                            echo ' <a href="https://rabbitloader.com/kb/fluctuations-in-pagespeed-performance-score/" target="_blank" title="Click to know more about this.">Know Why.</a></span>';
                                            echo '<div class="notice notice-error"><p><b>';
                                            RL21UtilWP::_e('Warning');
                                            echo ': </b> ';
                                            RL21UtilWP::_e('You may see fluctuations in SpeedScore because the "Instant content change" checkbox is on.');
                                            echo '</p></div>';
                                        }
                                        ?>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="chk_private_mode" name="chk_private_mode" style="margin-top: 0.25em; vertical-align: top;" <?php echo $private_mode_val ? ' checked="checked" ' : ''; ?>>
                                        <label class="form-check-label" for="chk_private_mode">
                                            <?php RL21UtilWP::_e('Turn on \'Me\' mode to do testing or resolving conflicts (default: off)'); ?>
                                        </label>
                                        <span class="dashicons dashicons-info-outline text-secondary tpopup" title="Click to know more about Me mode" title-html="<div style='text-align:left'><h4>'Me' Mode</h4><br><b>If on:</b> Me mode is for testing and debugging. Only you can see the RabbitLoader optimized pages by appending '?rltest=1' to  URLs. Regular visitors will continue to see the original webpages without any affect of RabbitLoader. Keep this on  if you are facing any compatibility issues likely due to RabbitLoader.<br><br><b>If off</b>: All public pages will be served with RabbitLoader optimization on. This is recommended for most users.</div>" style="font-size: 16px; line-height: 28px;"></span>
                                    </div>

                                    <button type="submit" class="rl-btn rl-btn-primary mt-2">Save</button>
                                    <?php
                                    self::saveNotice();
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    private static function saveNotice()
    {
        if (RL21UtilWP::is_flywheel()) {
            echo '<span class="d-block mt-2">', sprintf(RL21UtilWP::__('Flywheel Note: You need to Flush Cache manually from Flywheel dashboard after saving the settings <a href="%s">check details</a>'), "https://rabbitloader.com/kb/settings-for-flywheel/"), '</span>';
        }
    }
    private static function excludeUrls()
    {
        //depreciated@2.14.0
        RabbitLoader_21_Core::getWpOption($rl_wp_options);
        if (empty($rl_wp_options['exclude_patterns'])) {
            $rl_wp_options['exclude_patterns'] = '';
        }
        $exclude_patterns = $rl_wp_options['exclude_patterns'];

        //introduced@2.14.0
        RabbitLoader_21_Core::getWpUserOption($user_options);
        $shouldMigrate = !empty($exclude_patterns) && empty($user_options['exclude_patterns']);
        $userUpdating = isset($_POST['exclude_patterns']);
        if ($userUpdating || $shouldMigrate) {
            if ($userUpdating) {
                $user_options['exclude_patterns'] = sanitize_textarea_field($_POST['exclude_patterns']);
                RL21UtilWP::onPostChange(RL21UtilWP::POST_ID_ALL);
            } else {
                $user_options['exclude_patterns'] = $exclude_patterns;
            }
            RabbitLoader_21_Core::updateUserOption($user_options);
        }
        $exclude_patterns = $user_options['exclude_patterns'];

    ?>
        <div class="row mb-4">
            <div class="col">
                <div class="bg-white rounded p-4">
                    <div class="row">
                        <div class="col-sm-12 col-md-8 text-secondary">
                            <h5 class="mt-0">Exclude URLs</h5>
                            <span>Any URL matching the below patterns will be skipped from RabbitLoader optimization-</span>
                            <div class="mt-4">
                                <form method="post">
                                    <textarea class="form-control" rows="5" placeholder="e.g. /path/* without domain name" name="exclude_patterns"><?php echo $exclude_patterns; ?></textarea>
                                    <button type="submit" class="rl-btn rl-btn-primary mt-2">Save</button>
                                </form>
                                <?php
                                self::saveNotice();
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <h5 class="mt-0">Guidelines</h5>
                            <ul class="" style="list-style:circle;">
                                <li>If the request URL matches the given <b>shell wildcard pattern</b>, it will not be optimized.</li>
                                <li>You can put one pattern per line</li>
                                <li>A wildcard character (*) can be used in the pattern. For example, /category/* will exclude all URLs starting with /category/.</li>
                                <li><a target="_blank" href="https://rabbitloader.com/kb/exclude-urls-from-cached/" title="Excluding URLs from being cached">Read documentation</a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    private static function ignoreParams()
    {
        //depreciated@2.14.0
        RabbitLoader_21_Core::getWpOption($rl_wp_options);
        if (empty($rl_wp_options['ignore_params'])) {
            $rl_wp_options['ignore_params'] = '';
        }
        $ignore_params = $rl_wp_options['ignore_params'];

        //introduced@2.14.0
        RabbitLoader_21_Core::getWpUserOption($user_options);
        $shouldMigrate = !empty($ignore_params) && empty($user_options['ignore_params']);
        $userUpdating = isset($_POST['ignore_params']);
        if ($userUpdating || $shouldMigrate) {
            if ($userUpdating) {
                $user_options['ignore_params'] = sanitize_textarea_field($_POST['ignore_params']);
            } else {
                $user_options['ignore_params'] = $ignore_params;
            }
            RabbitLoader_21_Core::updateUserOption($user_options);
        }
        $ignore_params = $user_options['ignore_params'];
    ?>
        <div class="row mb-4">
            <div class="col">
                <div class="bg-white rounded p-4">
                    <div class="row">
                        <div class="col-sm-12 col-md-8 text-secondary">
                            <h5 class="mt-0">Ignore Parameters</h5>
                            <span>Query/GET parameters mentioned below will be ignored when creating cached copy of a page -</span>
                            <div class="mt-4">
                                <form method="post">
                                    <textarea class="form-control" rows="5" placeholder="e.g. fbclid" name="ignore_params"><?php echo $ignore_params; ?></textarea>
                                    <button type="submit" class="rl-btn rl-btn-primary mt-2">Save</button>
                                </form>
                                <?php
                                self::saveNotice();
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <h5 class="mt-0">Guidelines</h5>
                            <ul class="" style="list-style:circle;">
                                <li>You can put one parameter per line.</li>
                                <li>Parameter name should be without any special characters such as &amp;, ?or =.</li>
                                <li>Regex or shell wildcard pattern can not be used here.</li>
                                <li>Many popular params are ignored by default, <a target="_blank" href="https://rabbitloader.com/kb/ignori-url-parameters-caching/" title="Ignoring URL parameters from caching key">read documentation</a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    private static function advanceSettings()
    {
        $links = [
            'rules' => ['label' => 'Image/CSS/JS Settings', 'videoID' => 'lC0vWlugHJ4', 'duration' => 73],
            'cloudflare' => ['label' => 'Cloudflare Integration', 'videoID' => 'uBPMn2mvnrs', 'duration' => 128],
            'share' => ['label' => 'Delegate Access', 'videoID' => 'QxwaDxtRw-I', 'duration' => 79]
        ];
    ?>
        <div class="row">
            <div class="col">
                <p>Advance reports and more controls are available on your RabbitLoader account portal.</p>
            </div>
        </div>
        <div class="row mb-5">
            <?php
            foreach ($links as $hash => $item) {
                $icon = ' <span class="dashicons dashicons-external mt-1"></span>';
                $link = ' href="https://rabbitloader.com/account/#' . $hash . '" target="_blank" ';

                echo '
            <div class="col text-center">
                <div class="bg-white rounded p-2">
                    <a class="btn btn-link" ' . $link . '>' . $item['label'] . $icon . ' </a>
                    <primer data-video-id="' . $item['videoID'] . '" data-duration="' . $item['duration'] . '"></primer>
                </div>
            </div>';
            }
            ?>
        </div>
    <?php
    }

    private static function show_cf_box()
    {
        if (empty($_SERVER['HTTP_CDN_LOOP']) || $_SERVER['HTTP_CDN_LOOP'] != 'cloudflare') {
            return;
        }
    ?>
        <div class="row mb-4">
            <div class="col">
                <div class="bg-white rounded p-4">
                    <div class="row">
                        <div class="col-8 text-secondary">
                            <h5 class="mt-0"><?php RL21UtilWP::_e('Using Cloudflare?'); ?></h5>
                            <span><?php RL21UtilWP::_e('If you are using Cloudflare, there are a few settings required in order to avoid conflicts and get the best performance.'); ?></span>
                            <primer data-video-id="uBPMn2mvnrs" data-duration="128"></primer>

                            <div class="mt-5">
                                <a target="_blank" class="rl-btn rl-btn-primary" href="https://rabbitloader.com/kb/cloudflare-settings-for-best-performance/"><?php RL21UtilWP::_e('Show recommended settings'); ?></a>
                            </div>

                        </div>
                        <div class="col-4 text-center">
                            <img src="<?php echo RABBITLOADER_PLUG_URL; ?>/assets/help.jpg" class="img-fluid" style="max-height:170px;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}

?>