<?php
$arr = array('strong' => array());

$plugins = array(
        'lws-hide-login' => array('LWS Hide Login', __('This plugin <strong>hide your administration page</strong> (wp-admin) and lets you <strong>change your login page</strong> (wp-login). It offers better security as hackers will have more trouble finding the page.', 'lws-tools'), true),
        'lws-optimize' => array('LWS Optimize', __("This plugin lets you boost your website's <strong>loading times</strong> thanks to our tools: caching, media optimisation, files minification and concatenation...", 'lws-tools'), true),
        'lws-cleaner' => array('LWS Cleaner', __('This plugin lets you <strong>clean your WordPress website</strong> in a few clics to gain speed: posts, comments, terms, users, settings, plugins, medias, files.', 'lws-tools'), true),
        'lws-sms' => array('LWS SMS', __('This plugin, designed specifically for WooCommerce, lets you <strong>send SMS automatically to your customers</strong>. You will need an account at LWS and enough credits to send SMS. Create personnalized templates, manage your SMS and sender IDs and more!', 'lws-tools'), false),
        'lws-affiliation' => array('LWS Affiliation', __('With this plugin, you can add banners and widgets on your website and use those with your <strong>affiliate account LWS</strong>. Earn money and follow the evolution of your gains on your website.', 'lws-tools'), false),
        'lwscache' => array('LWSCache', __('Based on the Varnich cache technology and NGINX, LWSCache let you <strong>speed up the loading of your pages</strong>. This plugin helps you automatically manage your LWSCache when editing pages, posts... and purging all your cache. Works only if your server use this cache.', 'lws-tools'), false),
        'lws-tools' => array('LWS Tools', __('This plugin provides you with several tools and shortcuts to manage, secure and optimise your WordPress website. Updating plugins and themes, accessing informations about your server, managing your website parameters, etc... Personnalize every aspect of your website!', 'lws-tools'), false)
);

//Adapt the array to change which plugins are featured as ads
$plugins_showcased = array('lws-hide-login', 'lwscache', 'lws-cleaner');

$plugins_activated = array();
$all_plugins = get_plugins();

foreach ($plugins as $slug => $plugin) {
    if (is_plugin_active($slug . '/' . $slug . '.php')) {
        $plugins_activated[$slug] = "full";
    } elseif (array_key_exists($slug . '/' . $slug . '.php', $all_plugins)) {
        $plugins_activated[$slug] = "half";
    }
}

$tabs_list = array(
    array('notifications', __('Notifications', 'lws-tools')),
    array('server', __('Server', 'lws-tools')),
    array('optimisation', __('Optimisations', 'lws-tools')),
    array('security', __('Security', 'lws-tools')),
    //array('antivirus', __('Antivirus', 'lws-tools')),
    array('mysql', __('MySQL Logs', 'lws-tools')),
    array('tools', __('Other Tools', 'lws-tools')),
    array('plugins', __('Our plugins', 'lws-tools')),
)
// // //
?>

<script>
    var function_ok = true;
</script>

<!-- Beginning main content block -->
<div class="lws_tk_main_bloc">
    <!-- Beginning of the blue part (ad part) -->
    <div class="lws_tk_adbloc">
        <div class="lws_tk_adbloc_left">
            <span
                class="lws_tk_ad_title"><?php echo esc_html('LWS Tools'); ?></span>
            <span class="lws_tk_ad_subtext">
                <?php esc_html_e('by', 'lws-tools'); ?></span>
            <img class="lws_tk_ad_img"
                src="<?php echo esc_url(plugins_url('images/logo_lws.png', __DIR__))?>"
                alt="LWS Logo" width="238px" height="60px">
            <!-- Need to adapt the URL -->
        </div>
        <div class="lws_tk_adbloc_right">
            <span class="lws_tk_ad_t1">
                <?php esc_html_e('Discover LWS efficient, fast and secure web hosting!', 'lws-tools'); ?></span>
            <br>
            <img style="vertical-align:sub; margin-right:5px"
                src="<?php echo esc_url(plugins_url('images/wordpress_blanc.svg', __DIR__))?>"
                alt="LWS Cache Logo" width="20px" height="20px">
            <!-- Need to adapt the URL -->
            <span class="lws_tk_ad_t2">
                <?php esc_html_e('15% off your WordPress-optimized hosting with the code: ', 'lws-tools'); ?></span>
            <br>
            <div style="margin-top:10px">
                <label onclick="lws_tk_copy_clipboard(this)" class="lws_tk_ad_label lws_tk_tooltip" readonly
                    text="WPEXT15">
                    <span><?php echo esc_html('WPEXT15'); ?></span>
                    <img style="vertical-align: middle; padding-left: 47px;"
                        src="<?php echo esc_url(plugins_url('images/copier.svg', __DIR__))?>"
                        alt="Logo Copy Element" width="15px" height="18px">
                    <!-- Need to adapt the URL -->
                </label>
                <a target="_blank"
                    href="<?php echo esc_url('https://www.lws.fr/hebergement_wordpress.php');?>"><button
                        type="button"
                        class="lws_tk_ad_button"><?php esc_html_e("Let's go!", 'lws-tools'); ?></button></a>
            </div>
        </div>
    </div>
    <!--  END -->
    <!-- Sub-block, where the plugin is presented -->
    <div class="lws_tk_subtitlebloc">
        <img style="margin-top:20px"
            src="<?php echo esc_url(plugins_url('images/plugin_lws_tools_logo.svg', __DIR__))?>"
            alt="LWS Cache Logo" width="100px" height="100px">
        <!-- Change image -->
        <!-- Change next block with new text -->
        <div class="lws_tk_title-text">
            <p class="lws_tk_top_side_desc">
                <?php esc_html_e('LWS Tools offer toolkits and shortcuts to manage your WordPress website. It lets you secure and optimize  your websites easily and visualize several useful informations about your server, website and database.', 'lws-tools'); ?>
            </p>
            <p class="lws_tk_top_side_desc">
                <strong><?php esc_html_e("Manage your WordPress website now!", 'lws-tools'); ?></strong>
            </p>
        </div>
    </div>

    <!-- Home to the tabs + content + ads -->
    <div class="lws_tk_main_content">

        <!-- tabs + content -->
        <div class="lws_tk_list_block_content">
            <!-- Tabs -->
            <div class="tab_lws_tk" id='tab_lws_tk_block'>
                <div id="tab_lws_tk" role="tablist" aria-label="Onglets_lws_tk">
                    <?php foreach ($tabs_list as $tab) : ?>
                    <button
                        id="<?php echo esc_attr('nav-' . $tab[0]); ?>"
                        class="tab_nav_lws_tk <?php echo $tab[0] == 'notifications' ? esc_attr('active') : ''; ?>"
                        data-toggle="tab" role="tab"
                        aria-controls="<?php echo esc_attr($tab[0]);?>"
                        aria-selected="<?php echo $tab[0] == 'notifications' ? esc_attr('true') : esc_attr('false'); ?>"
                        tabindex="<?php echo $tab[0] == 'notifications' ? esc_attr('0') : '-1'; ?>">
                        <?php echo esc_html($tab[1]); ?>
                    </button>
                    <?php endforeach ?>
                    <div id="selector" class="selector_tab">&nbsp;</div>
                </div>

                <div class="tab_lws_tk_select hidden">
                    <select name="tab_lws_tk_select" id="tab_lws_tk_select" style="text-align:center">
                        <?php foreach ($tabs_list as $tab) : ?>
                        <option
                            value="<?php echo esc_attr("nav-" . $tab[0]); ?>">
                            <?php echo esc_html($tab[1]); ?>
                        </option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <?php foreach ($tabs_list as $tab) : ?>
            <div class="tab-pane main-tab-pane"
                id="<?php echo esc_attr($tab[0])?>" role="tabpanel"
                aria-labelledby="nav-<?php echo esc_attr($tab[0])?>"
                <?php echo $tab[0] == 'notifications' ? esc_attr('tabindex="0"') : esc_attr('tabindex="-1" hidden')?>>
                <div id="post-body"
                    class="<?php echo $tab[0] == 'plugins' ? esc_attr('lws_tk_configpage_plugin') : esc_attr('lws_tk_configpage'); ?> ">
                    <?php include plugin_dir_path(__FILE__) . $tab[0] . '.php'; ?>
                </div>
            </div>
            <?php endforeach?>
        </div>



        <!-- ad blocks, need to change image, ID, name, text... -->
        <!-- Choose 3 -->
        <div class="lws_tk_list_block_ad">
            <?php if (!get_transient('lwstk_remind_me') && !get_option('lwstk_do_not_ask_again')) : ?>
                <div class="lws_tk_block_ad_for_review">
                    <div class="lws_tk_block_ad_review_title">
                        <?php esc_html_e('Thank you for using LWS Tools!', 'lws-tools');?>
                    </div>
                    <div class="lws_tk_block_ad_review_stars">
                        <img src="<?php echo esc_url(plugins_url('images/notation.svg', __DIR__))?>" 
                        height="25px" width="159px">
                    </div>
                    <div class="lws_tk_block_ad_review_description">
                        <?php echo wp_kses(__('<a href="https://wordpress.org/support/plugin/lws-tools/reviews/" target="_blank">Evaluate our plugin</a> to help others optimise and secure their WordPress website!', 'lws-tools'), array('a' => array("href" => array())));?>
                    </div>
                </div>
            <?php endif ?>
            <!-- Same as before -->
            <div class="lws_tk_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugins_url('images/plugin_lws_hide_login.svg', __DIR__))?>"
                            alt="LWS Cache Logo" width="25px" height="23px">
                        <span
                            class="lws_tk_block_ad_text"><?php echo esc_html('Hide Login');?></span>
                    </span>
                    <button class="lws_tk_button_ad_block" onclick="install_plugin(this)" value="lws-hide-login"
                        id="lws-hide-login">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                                alt="LWS Cache Logo" width="20px" height="19px">
                            <span
                                class="lws_tk_button_text"><?php esc_html_e('Install', 'lws-tools'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden"
                            name="activate"><?php echo esc_html_e('Activate', 'lws-tools'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                            <?php esc_html_e('Activated', 'lws-tools'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_tk_text_ad">
                    <?php esc_html_e("Hide your administration page (wp-admin) and change your login page's URL (wp-login)", 'lws-tools'); ?>
                </span>
            </div>

            <!-- Same old... -->
            <div class="lws_tk_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugins_url('images/lws_cache_menu.svg', __DIR__))?>"
                            alt="LWS Cache" width="25px" height="23px">
                        <span
                            class="lws_tk_block_ad_text"><?php echo esc_html('LWSCache');?></span>
                    </span>
                    <button class="lws_tk_button_ad_block" onclick="install_plugin(this)" value="lwscache"
                        id="lwscache">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                                alt="" width="20px" height="19px">
                            <span
                                class="lws_tk_button_text"><?php esc_html_e('Install', 'lws-tools'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden"
                            name="activate"><?php echo esc_html_e('Activate', 'lws-tools'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                            <?php esc_html_e('Activated', 'lws-tools'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_tk_text_ad">
                    <?php esc_html_e('Automatically manage your LWSCache when editing pages, posts, ... and purge it.', 'lws-tools'); ?>
                </span>
            </div>

            <div class="lws_tk_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugins_url('images/plugin_lws_cleaner.svg', __DIR__))?>"
                            alt="LWS Cache Logo" width="25px" height="23px">
                        <!-- Need to change -->
                        <span
                            class="lws_tk_block_ad_text"><?php echo esc_html('LWS Cleaner');?></span>
                        <!-- Need to change -->
                    </span>
                    <button class="lws_tk_button_ad_block" onclick="install_plugin(this)" value="lws-cleaner"
                        id="lws-cleaner">
                        <!-- Need to change -->
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                                alt="LWS Cache Logo" width="20px" height="19px">
                            <!-- Need to change -->
                            <span
                                class="lws_tk_button_text"><?php esc_html_e('Install', 'lws-tools'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                                alt="" width="18px" height="18px">
                            <!-- Need to change -->
                        </span>
                        <span class="hidden"
                            name="activate"><?php echo esc_html_e('Activate', 'lws-tools'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                            <!-- Need to change -->
                            <?php esc_html_e('Activated', 'lws-tools'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_tk_text_ad">
                    <?php esc_html_e('Clean your WordPress website in a few clics to gain in speed: posts, medias...', 'lws-tools'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    function lws_tk_copy_clipboard(input) {
        navigator.clipboard.writeText(input.innerText.trim());
        setTimeout(function() {
            jQuery('#copied_tip').remove();
        }, 500);
        jQuery(input).append("<div class='tip' id='copied_tip'>" +
            "<?php esc_html_e('Copied!', 'lws-tools');?>" +
            "</div>");
    }
</script>


<!-- Here, need to change id of the selector and tabs -->
<script>
    const tabs = document.querySelectorAll('.tab_nav_lws_tk[role="tab"]');

    // Add a click event handler to each tab
    tabs.forEach((tab) => {
        tab.addEventListener('click', lws_tk_changeTabs);
    });

    <?php if (isset($change_tab)) : ?>
        var element = document.getElementById(
        "<?php echo esc_attr($change_tab); ?>");
        lws_tk_changeTabs(element);
    <?php else : ?>
        lws_tk_selectorMove(document.getElementById('nav-notifications'), document.getElementById('nav-notifications').parentNode);
    <?php endif ?>

    function lws_tk_selectorMove(target, parent) {
        const cursor = document.getElementById('selector');
        var element = target.getBoundingClientRect();
        var bloc = parent.getBoundingClientRect();

        var padding = parseInt((window.getComputedStyle(target, null).getPropertyValue('padding-left')).slice(0, -
            2));
        var margin = parseInt((window.getComputedStyle(target, null).getPropertyValue('margin-left')).slice(0, -2));
        var begin = (element.left - bloc.left) - margin;
        var ending = target.clientWidth + 2 * margin;

        cursor.style.width = ending + "px";
        cursor.style.left = begin + "px";
    }

    function lws_tk_changeTabs(e) {
        var target;
        if (e.target === undefined) {
            target = e;
        } else {
            target = e.target;
        }
        const parent = target.parentNode;
        const grandparent = parent.parentNode.parentNode;

        // Remove all current selected tabs
        parent
            .querySelectorAll('.tab_nav_lws_tk[aria-selected="true"]')
            .forEach(function(t) {
                t.setAttribute('aria-selected', false);
                t.classList.remove("active")
            });

        // Set this tab as selected
        target.setAttribute('aria-selected', true);
        target.classList.add('active');

        // Hide all tab panels
        grandparent
            .querySelectorAll('.tab-pane.main-tab-pane[role="tabpanel"]')
            .forEach((p) => p.setAttribute('hidden', true));

        // Show the selected panel
        grandparent.parentNode
            .querySelector(`#${target.getAttribute('aria-controls')}`)
            .removeAttribute('hidden');


        lws_tk_selectorMove(target, parent);
        if (target.id == 'nav-mysql') {
            reset_table();
        }
    }
</script>

<script>
    var reset_table = (function() {
        var executed_template = false;
        return function() {
            if (!executed_template && jQuery('#lws_tk_mysqltable') != null) {
                executed_template = true;
                jQuery(document).ready(function() {
                    jQuery('#lws_tk_mysqltable').DataTable().columns.adjust();
                });
            }
        };
    })();

    jQuery(document).ready(function() {
        <?php foreach ($plugins_activated as $slug => $activated) : ?>
        <?php if ($activated == "full") : ?>
        <?php if (in_array($slug, $plugins_showcased)): ?>
        var button = jQuery(
            "<?php echo esc_attr("#" . $slug); ?>"
        );
        button.children()[3].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        button.prop('onclick', false);
        button.addClass('lws_tk_button_ad_block_validated');
        <?php endif ?>
        /**/
        var button = jQuery(
            "<?php echo esc_attr("#bis_" . $slug); ?>"
        );
        button.children()[3].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        button.prop('onclick', false);
        button.addClass('lws_tk_button_ad_block_validated');

        <?php elseif ($activated == "half") : ?>
        <?php if (in_array($slug, $plugins_showcased)): ?>
        var button = jQuery(
            "<?php echo esc_attr("#" . $slug); ?>"
        );
        button.children()[2].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        <?php endif ?>
        /**/
        var button = jQuery(
            "<?php echo esc_attr("#bis_" . $slug); ?>"
        );
        button.children()[2].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        <?php endif ?>
        <?php endforeach ?>
    });

    function install_plugin(button) {
        var newthis = this;
        if (this.function_ok) {
            this.function_ok = false;
            const regex = /bis_/;
            bouton_id = button.id;
            bouton_sec = "";
            if (bouton_id.match(regex)) {
                bouton_sec = bouton_id.substring(4);
            } else {
                bouton_sec = "bis_" + bouton_id;
            }

            button_sec = document.getElementById(bouton_sec);

            button.children[0].classList.add('hidden');
            button.children[3].classList.add('hidden');
            button.children[2].classList.add('hidden');
            button.children[1].classList.remove('hidden');
            button.classList.remove('lws_tk_button_ad_block_validated');
            button.setAttribute('disabled', true);

            if (button_sec !== null) {
                button_sec.children[0].classList.add('hidden');
                button_sec.children[3].classList.add('hidden');
                button_sec.children[2].classList.add('hidden');
                button_sec.children[1].classList.remove('hidden');
                button_sec.classList.remove('lws_tk_button_ad_block_validated');
                button_sec.setAttribute('disabled', true);
            }

            var data = {
                action: "lws_tk_downloadPlugin",
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('updates')); ?>',
                slug: button.getAttribute('value'),
            };
            jQuery.post(ajaxurl, data, function(response) {
                if (!response.success) {
                    if (response.data.errorCode == 'folder_exists') {
                        var data = {
                            action: "lws_tk_activatePlugin",
                            ajax_slug: response.data.slug,
                            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_activate_plugin')); ?>',
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                            jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                            jQuery('#' + bouton_id).children()[3].classList.remove('hidden');
                            jQuery('#' + bouton_id).addClass('lws_tk_button_ad_block_validated');
                            newthis.function_ok = true;

                            if (button_sec !== null) {
                                jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                                jQuery('#' + bouton_sec).children()[2].classList.add('hidden');
                                jQuery('#' + bouton_sec).children()[3].classList.remove('hidden');
                                jQuery('#' + bouton_sec).addClass(
                                    'lws_tk_button_ad_block_validated');
                                newthis.function_ok = true;
                            }
                        });

                    } else {
                        jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[3].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[0].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[4].classList.remove('hidden');
                        jQuery('#' + bouton_id).addClass('lws_tk_button_ad_block_failed');
                        setTimeout(() => {
                            jQuery('#' + bouton_id).removeClass('lws_tk_button_ad_block_failed');
                            jQuery('#' + bouton_id).prop('disabled', false);
                            jQuery('#' + bouton_id).children()[0].classList.remove('hidden');
                            jQuery('#' + bouton_id).children()[4].classList.add('hidden');
                            newthis.function_ok = true;
                        }, 2500);

                        if (button_sec !== null) {
                            jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                            jQuery('#' + bouton_sec).children()[2].classList.add('hidden');
                            jQuery('#' + bouton_sec).children()[3].classList.add('hidden');
                            jQuery('#' + bouton_sec).children()[0].classList.add('hidden');
                            jQuery('#' + bouton_sec).children()[4].classList.remove('hidden');
                            jQuery('#' + bouton_sec).addClass('lws_tk_button_ad_block_failed');
                            setTimeout(() => {
                                jQuery('#' + bouton_sec).removeClass(
                                    'lws_tk_button_ad_block_failed');
                                jQuery('#' + bouton_sec).prop('disabled', false);
                                jQuery('#' + bouton_sec).children()[0].classList.remove('hidden');
                                jQuery('#' + bouton_sec).children()[4].classList.add('hidden');
                                newthis.function_ok = true;
                            }, 2500);
                        }
                    }
                } else {
                    jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[2].classList.remove('hidden');
                    jQuery('#' + bouton_id).prop('disabled', false);
                    newthis.function_ok = true;

                    if (button_sec !== null) {
                        jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                        jQuery('#' + bouton_sec).children()[2].classList.remove('hidden');
                        jQuery('#' + bouton_sec).prop('disabled', false);
                        newthis.function_ok = true;
                    }
                }
            });
        }
    }
</script>

<!-- If need a select -->
<!-- Change lws_tk! -->
<script>
    if (window.innerWidth <= 1780) {
        jQuery('#tab_lws_tk').addClass("hidden");
        jQuery('#tab_lws_tk_select').parent().removeClass("hidden");
    }

    jQuery(window).on('resize', function() {
        if (window.innerWidth <= 1780) {
            jQuery('#tab_lws_tk').addClass("hidden");
            jQuery('#tab_lws_tk_select').parent().removeClass("hidden");
            document.getElementById('tab_lws_tk_select').value = document.querySelector(
                '.tab_nav_lws_tk[aria-selected="true"]').id;
        } else {
            jQuery('#tab_lws_tk').removeClass("hidden");
            jQuery('#tab_lws_tk_select').parent().addClass("hidden");
            const target = document.getElementById(document.getElementById('tab_lws_tk_select').value);
            lws_tk_selectorMove(target, target.parentNode);
        }
    });

    jQuery('#tab_lws_tk_select').on('change', function() {
        const target = document.getElementById(this.value);
        const parent = target.parentNode;
        const grandparent = parent.parentNode.parentNode;

        // Remove all current selected tabs
        parent
            .querySelectorAll('.tab_nav_lws_tk[aria-selected="true"]')
            .forEach(function(t) {
                t.setAttribute('aria-selected', false);
                t.classList.remove("active")
            });

        // Set this tab as selected
        target.setAttribute('aria-selected', true);
        target.classList.add('active');

        // Hide all tab panels
        grandparent
            .querySelectorAll('.tab-pane.main-tab-pane[role="tabpanel"]')
            .forEach((p) => p.setAttribute('hidden', true));

        // Show the selected panel
        grandparent.parentNode
            .querySelector(`#${target.getAttribute('aria-controls')}`)
            .removeAttribute('hidden');
    });
</script>