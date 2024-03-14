<?php
$arr = array('strong' => array());
$plugins = array(
        'lws-hide-login' => array('LWS Hide Login', __('This plugin <strong>hide your administration page</strong> (wp-admin) and lets you <strong>change your login page</strong> (wp-login). It offers better security as hackers will have more trouble finding the page.', 'lws-cleaner'), true),
        'lws-optimize' => array('LWS Optimize', __('This plugin lets you boost your website\'s <strong>loading times</strong> thanks to our tools: caching, media optimisation, files minification and concatenation...', 'lws-cleaner'), true),
        'lws-cleaner' => array('LWS Cleaner', __('This plugin lets you <strong>clean your WordPress website</strong> in a few clics to gain speed: posts, comments, terms, users, settings, plugins, medias, files.', 'lws-cleaner'), true),
        'lws-sms' => array('LWS SMS', __('This plugin, designed specifically for WooCommerce, lets you <strong>send SMS automatically to your customers</strong>. You will need an account at LWS and enough credits to send SMS. Create personnalized templates, manage your SMS and sender IDs and more!', 'lws-cleaner'), false),
        'lws-affiliation' => array('LWS Affiliation', __('With this plugin, you can add banners and widgets on your website and use those with your <strong>affiliate account LWS</strong>. Earn money and follow the evolution of your gains on your website.', 'lws-cleaner'), false),
        'lwscache' => array('LWSCache', __('Based on the Varnich cache technology and NGINX, LWSCache let you <strong>speed up the loading of your pages</strong>. This plugin helps you automatically manage your LWSCache when editing pages, posts... and purging all your cache. Works only if your server use this cache.', 'lws-cleaner'), false),
        'lws-tools' => array('LWS Tools', __('This plugin provides you with several tools and shortcuts to manage, secure and optimise your WordPress website. Updating plugins and themes, accessing informations about your server, managing your website parameters, etc... Personnalize every aspect of your website!', 'lws-cleaner'), false)
);

//Adapt the array to change which plugins are featured as ads
$plugins_showcased = array('lws-tools', 'lws-hide-login', 'lwscache');

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

<script>
    var function_ok = true;
</script>

<div class="lws_cl_modal" id="lws_cleaner_modal">
    <div class="lws_cl_modal_dialog">
        <section class="lws_cl_modal_section">
            <h1 class="lws_cl_modal_header">
                <?php esc_html_e("Warning!", "lws-cleaner")?>
            </h1>
            <div class="" style="padding: 15px; text-align:center;">
                <?php echo wp_kses(__('This page allows you to clean your website so as to improve performances and reduce used stockage. Be aware that once a file is deleted, it will <strong>NOT</Strong> be restorable. While this plugin will not delete essential files, you may lose important files if you are not careful.', 'lws-cleaner'), array('strong' => array())); ?>
            </div>
            <div class="lws_cl_modal_button_div">
                <button type="button" class="lws_cl_modal_button" data-close="lws_cleaner_modal"><?php esc_html_e("I took note of it", "lws-cleaner")?></button>
            </div>
        </section>
    </div>
</div>

<?php if (!get_transient('lws_cl_incache_modal')) : ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#lws_cleaner_modal').addClass("is_visible");
        jQuery('body').css({
            'overflow': 'hidden'
        });
    });

    document.querySelector("[data-close]").addEventListener("click", function() {
        var data = {
            action: "lws_cl_in_cache_modal",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('incache_modal_lws_cleaner')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            jQuery('#lws_cleaner_modal').removeClass("is_visible");
            jQuery('body').css({
                'overflow': 'visible'
            });
        });
    });
</script>
<?php endif ?>


<!-- Beginning main content block -->
<div class="lws_cl_main_bloc">
    <!-- Beginning of the blue part (ad part) -->
    <div class="lws_cl_adbloc">
        <div class="lws_cl_adbloc_left">
            <span class="lws_cl_ad_title"><?php echo esc_html('LWS Cleaner'); ?></span>
            <span class="lws_cl_ad_subtext"> <?php esc_html_e('by', 'lws-cleaner'); ?></span>
            <img class="lws_cl_ad_img"
                src="<?php echo esc_url(plugins_url('images/logo_lws.png', __DIR__))?>"
                alt="LWS Logo" width="238px" height="60px">
            <!-- Need to adapt the URL -->
        </div>
        <div class="lws_cl_adbloc_right">
            <span class="lws_cl_ad_t1"> <?php esc_html_e('Discover LWS efficient, fast and secure web hosting!', 'lws-cleaner'); ?></span>
            <br>
            <img style="vertical-align:sub; margin-right:5px"
                src="<?php echo esc_url(plugins_url('images/wordpress_blanc.svg', __DIR__))?>"
                alt="LWS Cache Logo" width="20px" height="20px">
            <!-- Need to adapt the URL -->
            <span class="lws_cl_ad_t2"> <?php esc_html_e('15% off your WordPress-optimized hosting with the code: ', 'lws-cleaner'); ?></span>
            <br>
            <div style="margin-top:10px">
                <label onclick="lws_cl_copy_clipboard(this)" class="lws_cl_ad_label lws_cl_tooltip" readonly
                    text="WPEXT15">
                    <span><?php echo esc_html('WPEXT15'); ?></span>
                    <img style="vertical-align: middle; padding-left: 47px;"
                        src="<?php echo esc_url(plugins_url('images/copier.svg', __DIR__))?>"
                        alt="Logo Copy Element" width="15px" height="18px">
                    <!-- Need to adapt the URL -->
                </label>
                <a target="_blank"
                    href="<?php echo esc_url('https://www.lws.fr/hebergement_wordpress.php');?>"><button
                        type="button" class="lws_cl_ad_button"><?php esc_html_e("Let's go!", 'lws-cleaner'); ?></button></a>
            </div>
        </div>
    </div>
    <!--  END -->
    <!-- Sub-block, where the plugin is presented -->
    <div class="lws_cl_subtitlebloc">
        <img style="margin-top:20px"
            src="<?php echo esc_url(plugins_url('images/plugin_lws-cleaner.svg', __DIR__))?>"
            alt="LWS Cache Logo" width="100px" height="100px">
        <!-- Change image -->
        <!-- Change next block with new text -->
        <div class="lws_cl_title-text">
            <p class="lws_cl_top_side_desc"><?php esc_html_e('LWS Cleaner let you entirely clean your WordPress installation. Clean in a few clicks your posts, comments, terms, users, settings, plugins, themes, files.', 'lws-cleaner'); ?>
            </p>
            <p class="lws_cl_top_side_desc">
                <strong><?php esc_html_e("Optimise your website now!", 'lws-cleaner'); ?></strong>
            </p>
        </div>
    </div>

    <!-- Home to the tabs + content + ads -->
    <div class="lws_cl_main_content">

        <!-- tabs + content -->
        <div class="lws_cl_list_block_content">
            <!-- Tabs -->
            <div class="tab_lws_cl" id='tab_lws_cl_block'>
                <div id="tab_lws_cl" role="tablist" aria-label="Onglets_lws_cl">
                    <?php foreach ($tabs_list as $tab) : ?>
                    <button
                        id="<?php echo esc_attr('nav-' . $tab[0]); ?>"
                        class="tab_nav_lws_cl <?php echo $tab[0] == 'posts' ? esc_attr('active') : ''; ?>"
                        data-toggle="tab" role="tab"
                        aria-controls="<?php echo esc_attr($tab[0]);?>"
                        aria-selected="<?php echo $tab[0] == 'posts' ? esc_attr('true') : esc_attr('false'); ?>"
                        tabindex="<?php echo $tab[0] == 'posts' ? esc_attr('0') : '-1'; ?>">
                        <?php echo esc_html($tab[1]); ?>
                    </button>
                    <?php endforeach ?>
                    <div id="selector" class="selector_tab">&nbsp;</div>
                </div>

                <div class="tab_lws_cl_select hidden">
                    <select name="tab_lws_cl_select" id="tab_lws_cl_select" style="text-align:center">
                        <?php foreach ($tabs_list as $tab) : ?>
                        <option
                            value="<?php echo esc_attr("nav-" . $tab[0]); ?>">
                            <?php echo esc_html($tab[1]); ?>
                        </option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <?php foreach ($first_tabs as $tab) : ?>
            <?php $plugin_lists = $lws_content_6_pages[$tab[0]];
                $lws_cl_page_type = $tab[0]; ?>
            <div class="tab-pane main-tab-pane"
                id="<?php echo esc_attr($tab[0])?>" role="tabpanel"
                aria-labelledby="nav-<?php echo esc_attr($tab[0])?>"
                <?php echo $tab[0] == 'posts' ? esc_attr('tabindex="0"') : esc_attr('tabindex="-1" hidden')?>>
                <div id="post-body" class="lws_cl_configpage">
                    <?php include plugin_dir_path(__FILE__) . 'lws_cl_main_pages.php'; ?>
                </div>
            </div>
            <?php endforeach?>


            <!-- <div class="tab-pane main-tab-pane" id="medias" role="tabpanel" aria-labelledby="nav-medias" tabindex="-1"
                hidden>
                <div id="post-body" class="lws_cl_configpage">
                    <?php //include plugin_dir_path(__FILE__) . 'lws_cl_medias.php'; ?>
                </div>
            </div> -->

            <div class="tab-pane main-tab-pane" id="files" role="tabpanel" aria-labelledby="nav-files" tabindex="-1"
                hidden>
                <div id="post-body" class="lws_cl_configpage">
                    <?php include plugin_dir_path(__FILE__) . 'lws_cl_files.php'; ?>
                </div>
            </div>

            <div class="tab-pane main-tab-pane" id="plugins" role="tabpanel" aria-labelledby="nav-plugins" tabindex="-1"
                hidden>
                <div id="post-body" class="lws_cl_configpage_plugin">
                    <?php include plugin_dir_path(__FILE__) . 'lws_cl_plugins.php'; ?>
                </div>
            </div>
        </div>

        <!-- ad blocks, need to change image, ID, name, text... -->
        <!-- Choose 3 -->
        <div class="lws_cl_list_block_ad">
            <?php if (!get_transient('lwscleaner_remind_me') && !get_option('lwscleaner_do_not_ask_again')) : ?>
            <div class="lws_cl_block_ad_for_review">
                <div class="lws_cl_block_ad_review_title">
                    <?php esc_html_e('Thank you for using LWS Cleaner!', 'lws-cleaner');?>
                </div>
                <div class="lws_cl_block_ad_review_stars">
                    <img src="<?php echo esc_url(plugins_url('images/notation.svg', __DIR__))?>" 
                    height="25px" width="159px">
                </div>
                <div class="lws_cl_block_ad_review_description">
                    <?php echo wp_kses(__('<a href="https://wordpress.org/support/plugin/lws-cleaner/reviews/" target="_blank">Evaluate our plugin</a> to help others clean their WordPress website!', 'lws-cleaner'), array('a' => array("href" => array())));?>
                </div>
            </div>
            <?php endif ?>
            <div class="lws_cl_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugins_url('images/plugin_lws_tools.svg', __DIR__))?>"
                            alt="LWS Cache Logo" width="25px" height="23px">
                        <!-- Need to change -->
                        <span class="lws_cl_block_ad_text"><?php echo esc_html('LWS Tools');?></span>
                        <!-- Need to change -->
                    </span>
                    <button class="lws_cl_button_ad_block" onclick="install_plugin(this)" value="lws-tools"
                        id="lws-tools">
                        <!-- Need to change -->
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                                alt="LWS Cache Logo" width="20px" height="19px">
                            <!-- Need to change -->
                            <span class="lws_cl_button_text"><?php esc_html_e('Install', 'lws-cleaner'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                                alt="" width="18px" height="18px">
                            <!-- Need to change -->
                        </span>
                        <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-cleaner'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                            <!-- Need to change -->
                            <?php esc_html_e('Activated', 'lws-cleaner'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_cl_text_ad">
                    <?php esc_html_e('Toolkits and shortcuts to manage, secure and optimise your WordPress website.', 'lws-cleaner'); ?>
                    <!-- Need to change -->
                </span>
            </div>

            <!-- Same as before -->
            <div class="lws_cl_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugins_url('images/plugin_lws_hide_login.svg', __DIR__))?>"
                            alt="LWS Cache Logo" width="25px" height="23px">
                        <span class="lws_cl_block_ad_text"><?php echo esc_html('Hide Login');?></span>
                    </span>
                    <button class="lws_cl_button_ad_block" onclick="install_plugin(this)" value="lws-hide-login"
                        id="lws-hide-login">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                                alt="LWS Cache Logo" width="20px" height="19px">
                            <span class="lws_cl_button_text"><?php esc_html_e('Install', 'lws-cleaner'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-cleaner'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                            <?php esc_html_e('Activated', 'lws-cleaner'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_cl_text_ad">
                    <?php esc_html_e("Hide your administration page (wp-admin) and change your login page's URL (wp-login)", 'lws-cleaner'); ?>
                </span>
            </div>

            <!-- Same old... -->
            <div class="lws_cl_block_ad">
                <div style="display: flex; justify-content: space-between; margin-bottom:15px">
                    <span style="margin-top:5px">
                        <img style="vertical-align:sub; margin-right:5px"
                            src="<?php echo esc_url(plugins_url('images/lws_cache_menu.svg', __DIR__))?>"
                            alt="LWS Cache" width="25px" height="23px">
                        <span class="lws_cl_block_ad_text"><?php echo esc_html('LWSCache');?></span>
                    </span>
                    <button class="lws_cl_button_ad_block" onclick="install_plugin(this)" value="lwscache"
                        id="lwscache">
                        <span>
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                                alt="" width="20px" height="19px">
                            <span class="lws_cl_button_text"><?php esc_html_e('Install', 'lws-cleaner'); ?></span>
                        </span>
                        <span class="hidden" name="loading" style="padding-left:5px">
                            <img style="vertical-align:sub; margin-right:5px"
                                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                                alt="" width="18px" height="18px">
                        </span>
                        <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-cleaner'); ?></span>
                        <span class="hidden" name="validated">
                            <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                                src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                            <?php esc_html_e('Activated', 'lws-cleaner'); ?>
                        </span>
                    </button>
                </div>
                <span class="lws_cl_text_ad">
                    <?php esc_html_e('Automatically manage your LWSCache when editing pages, posts, ... and purge it.', 'lws-cleaner'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    function lws_cl_copy_clipboard(input) {
        navigator.clipboard.writeText(input.innerText.trim());
        setTimeout(function() {
            jQuery('#copied_tip').remove();
        }, 500);
        jQuery(input).append("<div class='tip' id='copied_tip'>" +
            "<?php esc_html_e('Copied!', 'lws-cleaner');?>" +
            "</div>");
    }
</script>


<!-- Here, need to change id of the selector and tabs -->
<script>
    const tabs = document.querySelectorAll('.tab_nav_lws_cl[role="tab"]');

    // Add a click event handler to each tab
    tabs.forEach((tab) => {
        tab.addEventListener('click', lws_cl_changeTabs);
    });

    <?php if (isset($change_tab)) : ?>
        var element = document.getElementById(
        "<?php echo esc_attr($change_tab); ?>");
        lws_cl_changeTabs(element);
    <?php else : ?>
        lws_cl_selectorMove(document.getElementById('nav-posts'), document.getElementById('nav-posts').parentNode);
    <?php endif ?>

    function lws_cl_selectorMove(target, parent) {
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

    function lws_cl_changeTabs(e) {
        var target;
        if (e.target === undefined) {
            target = e;
        } else {
            target = e.target;
        }
        const parent = target.parentNode;
        const grandparent = parent.parentNode.parentNode;

        // If accessing "files" tab, load all files for the tab. Helps loading the page faster
        if(target.getAttribute('aria-controls') == "files"){
            loading_files();
        }

        // Remove all current selected tabs
        parent
            .querySelectorAll('.tab_nav_lws_cl[aria-selected="true"]')
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


        lws_cl_selectorMove(target, parent);
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
        button.addClass('lws_cl_button_ad_block_validated');
        <?php endif ?>
        /**/
        var button = jQuery(
            "<?php echo esc_attr("#bis_" . $slug); ?>"
        );
        button.children()[3].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        button.prop('onclick', false);
        button.addClass('lws_cl_button_ad_block_validated');

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
            button.classList.remove('lws_cl_button_ad_block_validated');
            button.setAttribute('disabled', true);

            if (button_sec !== null) {
                button_sec.children[0].classList.add('hidden');
                button_sec.children[3].classList.add('hidden');
                button_sec.children[2].classList.add('hidden');
                button_sec.children[1].classList.remove('hidden');
                button_sec.classList.remove('lws_cl_button_ad_block_validated');
                button_sec.setAttribute('disabled', true);
            }

            var data = {
                action: "lws_cl_downloadPlugin",
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('updates')); ?>',
                slug: button.getAttribute('value'),
            };
            jQuery.post(ajaxurl, data, function(response) {
                if (!response.success) {
                    if (response.data.errorCode == 'folder_exists') {
                        var data = {
                            action: "lws_cl_activatePlugin",
                            ajax_slug: response.data.slug,
                            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('updates')); ?>',
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                            jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                            jQuery('#' + bouton_id).children()[3].classList.remove('hidden');
                            jQuery('#' + bouton_id).addClass('lws_cl_button_ad_block_validated');
                            newthis.function_ok = true;

                            if (button_sec !== null) {
                                jQuery('#' + bouton_sec).children()[1].classList.add('hidden');
                                jQuery('#' + bouton_sec).children()[2].classList.add('hidden');
                                jQuery('#' + bouton_sec).children()[3].classList.remove('hidden');
                                jQuery('#' + bouton_sec).addClass('lws_cl_button_ad_block_validated');
                                newthis.function_ok = true;
                            }
                        });

                    } else {
                        jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[3].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[0].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[4].classList.remove('hidden');
                        jQuery('#' + bouton_id).addClass('lws_cl_button_ad_block_failed');
                        setTimeout(() => {
                            jQuery('#' + bouton_id).removeClass('lws_cl_button_ad_block_failed');
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
                            jQuery('#' + bouton_sec).addClass('lws_cl_button_ad_block_failed');
                            setTimeout(() => {
                                jQuery('#' + bouton_sec).removeClass('lws_cl_button_ad_block_failed');
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
<!-- Change lws_cl! -->
<script>
    if (window.innerWidth <= 1665) {
        jQuery('#tab_lws_cl').addClass("hidden");
        jQuery('#tab_lws_cl_select').parent().removeClass("hidden");
    }

    jQuery(window).on('resize', function() {
        tab_lws_cl_block
        if (window.innerWidth <= 1665) {
            jQuery('#tab_lws_cl').addClass("hidden");
            jQuery('#tab_lws_cl_select').parent().removeClass("hidden");
            document.getElementById('tab_lws_cl_select').value = document.querySelector(
                '.tab_nav_lws_cl[aria-selected="true"]').id;
        } else {
            jQuery('#tab_lws_cl').removeClass("hidden");
            jQuery('#tab_lws_cl_select').parent().addClass("hidden");
            const target = document.getElementById(document.getElementById('tab_lws_cl_select').value);
            lws_cl_selectorMove(target, target.parentNode);
        }
    });

    jQuery('#tab_lws_cl_select').on('change', function() {
        const target = document.getElementById(this.value);
        const parent = target.parentNode;
        const grandparent = parent.parentNode.parentNode;

        // Remove all current selected tabs
        parent
            .querySelectorAll('.tab_nav_lws_cl[aria-selected="true"]')
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
