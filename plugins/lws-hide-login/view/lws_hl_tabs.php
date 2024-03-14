<?php
// Prepare the Our Plugin page
$tabs_list = array(
    array('login', __('LWS Hide Login', 'lws-hide-login')),
    array('plugins', __('Our plugins', 'lws-hide-login')),
);

//Adapt the array to change which plugins to feature in the page
$plugins = array(
        'lws-hide-login' => array('LWS Hide Login', __('This plugin <strong>hide your administration page</strong> (wp-admin) and lets you <strong>change your login page</strong> (wp-login). It offers better security as hackers will have more trouble finding the page.', 'lws-hide-login'), true),
        'lws-optimize' => array('LWS Optimize', __('This plugin lets you boost your website\'s <strong>loading times</strong> thanks to our tools: caching, media optimisation, files minification and concatenation...', 'lws-hide-login'), true),
        'lws-cleaner' => array('LWS Cleaner', __('This plugin lets you <strong>clean your WordPress website</strong> in a few clics to gain speed: posts, comments, terms, users, settings, plugins, medias, files.', 'lws-hide-login'), true),
        'lws-sms' => array('LWS SMS', __('This plugin, designed specifically for WooCommerce, lets you <strong>send SMS automatically to your customers</strong>. You will need an account at LWS and enough credits to send SMS. Create personnalized templates, manage your SMS and sender IDs and more!', 'lws-hide-login'), false),
        'lws-affiliation' => array('LWS Affiliation', __('With this plugin, you can add banners and widgets on your website and use those with your <strong>affiliate account LWS</strong>. Earn money and follow the evolution of your gains on your website.', 'lws-hide-login'), false),
        'lwscache' => array('LWSCache', __('Based on the Varnich cache technology and NGINX, LWSCache let you <strong>speed up the loading of your pages</strong>. This plugin helps you automatically manage your LWSCache when editing pages, posts... and purging all your cache. Works only if your server use this cache.', 'lws-hide-login'), false),
        'lws-tools' => array('LWS Tools', __('This plugin provides you with several tools and shortcuts to manage, secure and optimise your WordPress website. Updating plugins and themes, accessing informations about your server, managing your website parameters, etc... Personnalize every aspect of your website!', 'lws-hide-login'), false)
);

//Adapt the array to change which plugins are featured as ads
$plugins_showcased = array('lwscache', 'lws-cleaner', 'lws-tools');

$plugins_activated = array();
$all_plugins = get_plugins();

foreach ($plugins as $slug => $plugin) {
    if (is_plugin_active($slug . '/' . $slug . '.php')) {
        $plugins_activated[$slug] = "full";
    } elseif (array_key_exists($slug . '/' . $slug . '.php', $all_plugins)) {
        $plugins_activated[$slug] = "half";
    }
}
// // //
?>

<!-- Beginning main content block -->
<div class="lws_hl_main_bloc">
    <!-- Beginning of the blue part (ad part) -->
    <div class="lws_hl_adbloc">
        <div class="lws_hl_adbloc_left">
            <span class="lws_hl_ad_title"><?php echo esc_html('LWS Hide Login'); ?></span>
            <span class="lws_hl_ad_subtext"> <?php esc_html_e('by', 'lws-hide-login'); ?></span>
            <img class="lws_hl_ad_img"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/logo_lws.png')?>"
                alt="LWS Logo" width="238px" height="60px">
        </div>
        <div class="lws_hl_adbloc_right">
            <span class="lws_hl_ad_t1"> <?php esc_html_e('Discover LWS efficient, fast and secure web hosting!', 'lws-hide-login'); ?></span>
            <br>
            <img style="vertical-align:sub; margin-right:5px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/wordpress_blanc.svg')?>"
                alt="LWS Cache Logo" width="20px" height="20px">
            <span class="lws_hl_ad_t2"> <?php esc_html_e('15% off your WordPress-optimized hosting with the code: ', 'lws-hide-login'); ?></span>
            <br>
            <div style="margin-top:10px">
                <label onclick="lws_hl_copy_clipboard(this)" class="lws_hl_ad_label lws_hl_tooltip" readonly
                    text="WPEXT15">
                    <span><?php echo esc_html('WPEXT15'); ?></span>
                    <img style="vertical-align: middle; padding-left: 47px;"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/copier.svg')?>"
                        alt="Logo Copy Element" width="15px" height="18px">
                </label>
                <a target="_blank"
                    href="<?php echo esc_url('https://www.lws.fr/hebergement_wordpress.php');?>"><button
                        type="button" class="lws_hl_ad_button"><?php esc_html_e("Let's go!", 'lws-hide-login'); ?></button></a>
            </div>
        </div>
    </div>
    <!--  END -->
    <!-- Sub-block, where the plugin is presented -->
    <div class="lws_hl_subtitlebloc">
        <img style="margin-top:20px"
            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/plugin_lws-hide-login.svg')?>"
            alt="LWS Hide Login Logo" width="100px" height="100px">
        <div class="lws_hl_title-text">
            <p class="lws_hl_text_p"><?php esc_html_e('LWS Hide Login lets you secure your WordPress websites by choosing a new URL to connect to the WordPress administration page and a new redirection to the dashboard.', 'lws-hide-login'); ?>
            </p>
            <p class="lws_hl_text_p"><strong><?php esc_html_e("Secure your WordPress administration now!", 'lws-hide-login'); ?></strong>
            </p>
        </div>
    </div>

    <!-- Home to the tabs + content + ads -->
    <div class="lws_hl_main_content">

        <!-- tabs + content -->
        <div class="lws_hl_list_block_content">
            <!-- Tabs -->
            <div class="tab_lws_hl" id='tab_lws_hl_block'>
                <div id="tab_lws_hl" role="tablist" aria-label="Onglets_lws_hl">
                    <?php foreach ($tabs_list as $tab) : ?>
                    <button
                        id="<?php echo esc_attr('nav-' . $tab[0]); ?>"
                        class="tab_nav_lws_hl <?php echo $tab[0] == 'login' ? esc_attr('active') : ''; ?>"
                        data-toggle="tab" role="tab"
                        aria-controls="<?php echo esc_attr($tab[0]);?>"
                        aria-selected="<?php echo $tab[0] == 'login' ? esc_attr('true') : esc_attr('false'); ?>"
                        tabindex="<?php echo $tab[0] == 'login' ? esc_attr('0') : '-1'; ?>">
                        <?php echo esc_html($tab[1]); ?>
                    </button>
                    <?php endforeach ?>
                    <div id="selector" class="selector_tab">&nbsp;</div>
                </div>
            </div>

            <div class="tab-pane main-tab-pane" id="login" role="tabpanel" aria-labelledby="nav-login" tabindex="0">
                <div id="post-body" class="lws_hl_configpage">
                    <?php include plugin_dir_path(__FILE__) . 'lws_hl_configpage.php'; ?>
                </div>
            </div>

            <div class="tab-pane main-tab-pane" id="plugins" role="tabpanel" aria-labelledby="nav-plugins" tabindex="0"
                hidden>
                <div id="post-body" class="lws_hl_configpage">
                    <?php include plugin_dir_path(__FILE__) . 'lws_hl_ourplugins.php'; ?>
                </div>
            </div>
        </div>

        <!-- Three ad blocks, need to change image, ID, name, text... -->
        <div class="lws_hl_list_block_ad">
            <?php if (!get_transient('lwshl_remind_me') && !get_option('lwshl_do_not_ask_again')) : ?>
                <div class="lws_hl_block_ad_for_review">
                    <div class="lws_hl_block_ad_review_title">
                        <?php esc_html_e('Thank you for using LWS Hide Login!', 'lws-hide-login');?>
                    </div>
                    <div class="lws_hl_block_ad_review_stars">
                        <img src="<?php echo esc_url(plugins_url('images/notation.svg', __DIR__))?>" 
                        height="25px" width="159px">
                    </div>
                    <div class="lws_hl_block_ad_review_description">
                        <?php echo wp_kses(__('<a href="https://wordpress.org/support/plugin/lws-hide-login/reviews/" target="_blank">Evaluate our plugin</a> to help others hide their dashboard and login page on their WordPress website', 'lws-hide-login'), array('a' => array("target" => array(), "href" => array())));?>
                    </div>
                </div>
            <?php endif ?>
            <div class="lws_hl_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/plugin_lws_tools.svg')?>"
                            alt="LWS Cache Logo" width="25px" height="23px">
                        <span class="lws_hl_block_ad_text"><?php echo esc_html('LWS Tools');?></span>
                    </span>
                    <button class="lws_hl_button_ad_block" onclick="install_plugin(this)" value="lws-tools"
                        id="lws-tools">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securise.svg')?>"
                                alt="" width="20px" height="19px">
                            <span class="lws_hl_button_text"><?php esc_html_e('Install', 'lws-hide-login'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-hide-login'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                            <?php esc_html_e('Activated', 'lws-hide-login'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_hl_text_ad">
                    <?php esc_html_e('Toolkits and shortcuts to manage, secure and optimise your WordPress website.', 'lws-hide-login'); ?>
                </span>
            </div>

            <div class="lws_hl_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/lws_cache_menu.svg')?>"
                            alt="LWS Cache" width="25px" height="23px">
                        <span class="lws_hl_block_ad_text"><?php echo esc_html('LWSCache');?></span>
                    </span>
                    <button class="lws_hl_button_ad_block" onclick="install_plugin(this)" value="lwscache"
                        id="lwscache">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securise.svg')?>"
                                alt="" width="20px" height="19px">
                            <span class="lws_hl_button_text"><?php esc_html_e('Install', 'lws-hide-login'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-hide-login'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                            <?php esc_html_e('Activated', 'lws-hide-login'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_hl_text_ad">
                    <?php esc_html_e('Automatically manage your LWSCache when editing pages, posts, ... and purge it.', 'lws-hide-login'); ?>
                </span>
            </div>

            <div class="lws_hl_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/plugin_lws_cleaner.svg')?>"
                            alt="LWS Cleaner" width="25px" height="23px">
                        <span class="lws_hl_block_ad_text"><?php echo esc_html('LWS Cleaner');?></span>
                    </span>
                    <button class="lws_hl_button_ad_block" onclick="install_plugin(this)" value="lws-cleaner"
                        id="lws-cleaner">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securise.svg')?>"
                                alt="" width="20px" height="19px">
                            <span class="lws_hl_button_text"><?php esc_html_e('Install', 'lws-hide-login'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-hide-login'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                            <?php esc_html_e('Activated', 'lws-hide-login'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_hl_text_ad">
                    <?php esc_html_e('Clean your WordPress website in a few clics to gain in speed: posts, medias...', 'lws-hide-login'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    function lws_hl_copy_clipboard(input) {
        navigator.clipboard.writeText(input.innerText.trim());
        setTimeout(function() {
            jQuery('#copied_tip').remove();
        }, 500);
        jQuery(input).append("<div class='tip' id='copied_tip'>" +
            "<?php esc_html_e('Copied!', 'lws-hide-login');?>" +
            "</div>");
    }
</script>


<script>
    const tabs = document.querySelectorAll('.tab_nav_lws_hl[role="tab"]');

    // Add a click event handler to each tab
    tabs.forEach((tab) => {
        tab.addEventListener('click', lws_hl_changeTabs);
    });

    lws_hl_selectorMove(document.getElementById('nav-login'), document.getElementById('nav-login').parentNode);

    function lws_hl_selectorMove(target, parent) {
        const cursor = document.getElementById('selector');
        var element = target.getBoundingClientRect();
        var bloc = parent.getBoundingClientRect();

        var padding = parseInt((window.getComputedStyle(target, null).getPropertyValue('padding-left')).slice(0, -2));
        var margin = parseInt((window.getComputedStyle(target, null).getPropertyValue('margin-left')).slice(0, -2));
        var begin = (element.left - bloc.left) - margin;
        var ending = target.clientWidth + 2 * margin;

        cursor.style.width = ending + "px";
        cursor.style.left = begin + "px";
    }

    function lws_hl_changeTabs(e) {
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
            .querySelectorAll('.tab_nav_lws_hl[aria-selected="true"]')
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


        lws_hl_selectorMove(target, parent);
    }
</script>

<script>
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
        button.addClass('lws_hl_button_ad_block_validated');
        <?php endif ?>
        /**/
        var button = jQuery(
            "<?php echo esc_attr("#bis_" . $slug); ?>"
        );
        button.children()[3].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        button.prop('onclick', false);
        button.addClass('lws_hl_button_ad_block_validated');

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
        button.classList.remove('lws_hl_button_ad_block_validated');
        button.setAttribute('disabled', true);

        if (button_sec !== null) {
            button_sec.children[0].classList.add('hidden');
            button_sec.children[3].classList.add('hidden');
            button_sec.children[2].classList.add('hidden');
            button_sec.children[1].classList.remove('hidden');
            button_sec.classList.remove('lws_hl_button_ad_block_validated');
            button_sec.setAttribute('disabled', true);
        }

        var data = {
            action: "lwshidelogin_downloadPlugin",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('updates')); ?>',
            slug: button.getAttribute('value'),
        };
        jQuery.post(ajaxurl, data, function(response) {
            var success = response.success;
            var slug = response.data.slug;
            if (!success) {
                if (response.data.errorCode == 'folder_exists') {
                    var data = {
                        action: "lwshidelogin_activatePlugin",
                        ajax_slug: slug,
                        _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('activate_plugin_nonce_pluginpage')); ?>',        
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[3].classList.remove('hidden');
                        jQuery('#' + bouton_id).addClass('lws_hl_button_ad_block_validated');

                        if (button_sec !== null) {
                            jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                            jQuery('#' + bouton_sec).children()[2].classList.add('hidden');
                            jQuery('#' + bouton_sec).children()[3].classList.remove('hidden');
                            jQuery('#' + bouton_sec).addClass('lws_hl_button_ad_block_validated');
                        }
                    });

                } else {
                    jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[3].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[0].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[4].classList.remove('hidden');
                    jQuery('#' + bouton_id).addClass('lws_hl_button_ad_block_failed');
                    setTimeout(() => {
                        jQuery('#' + bouton_id).removeClass('lws_hl_button_ad_block_failed');
                        jQuery('#' + bouton_id).prop('disabled', false);
                        jQuery('#' + bouton_id).children()[0].classList.remove('hidden');
                        jQuery('#' + bouton_id).children()[4].classList.add('hidden');
                    }, 2500);

                    if (button_sec !== null) {
                        jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                        jQuery('#' + bouton_sec).children()[2].classList.add('hidden');
                        jQuery('#' + bouton_sec).children()[3].classList.add('hidden');
                        jQuery('#' + bouton_sec).children()[0].classList.add('hidden');
                        jQuery('#' + bouton_sec).children()[4].classList.remove('hidden');
                        jQuery('#' + bouton_sec).addClass('lws_hl_button_ad_block_failed');
                        setTimeout(() => {
                            jQuery('#' + bouton_sec).removeClass('lws_hl_button_ad_block_failed');
                            jQuery('#' + bouton_sec).prop('disabled', false);
                            jQuery('#' + bouton_sec).children()[0].classList.remove('hidden');
                            jQuery('#' + bouton_sec).children()[4].classList.add('hidden');
                        }, 2500);
                    }
                }
            } else {
                jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                jQuery('#' + bouton_id).children()[2].classList.remove('hidden');
                jQuery('#' + bouton_id).prop('disabled', false);

                if (button_sec !== null) {
                    jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                    jQuery('#' + bouton_sec).children()[2].classList.remove('hidden');
                    jQuery('#' + bouton_sec).prop('disabled', false);
                }
            }
        });
    }
</script>