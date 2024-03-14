<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.lws.fr
 * @since      1.0
 *
 * @package    lwscache
 * @subpackage lwscache/admin/partials
 */

global $pagenow;
$tabs_list = array(
    array('caching', __('LWS Cache', 'lwscache')),
    array('plugins', __('Our others plugins', 'lwscache')),
);

if (isset($_POST['lwscache_stop_cache'])){
    $array = (explode('/', ABSPATH));
    $path = implode('/', array($array[0], $array[1], $array[2]));        
    $api_key = file_get_contents($path . '/tmp/fc_token_api');
    wp_remote_post(
        "http://localhost:6084/api/domains/" . $_SERVER['HTTP_HOST'],
        array(
        'method'      => 'PUT',
        'headers'     => array('Authorization' => 'Bearer ' . $api_key, 'Content-Type' => "application/x-www-form-urlencoded" ),
        'body'		  => array(
            'template' => 'dev',
            ),
        )
    );

    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
    header("Refresh:0");
}


$plugins = array(
        'lws-hide-login' => array('LWS Hide Login', __('This plugin <strong>hide your administration page</strong> (wp-admin) and lets you <strong>change your login page</strong> (wp-login). It offers better security as hackers will have more trouble finding the page.', 'lwscache'), true),
        'lws-optimize' => array('LWS Optimize', __('This plugin lets you boost your website\'s <strong>loading times</strong> thanks to our tools: caching, media optimisation, files minification and concatenation...', 'lwscache'), true),
        'lws-cleaner' => array('LWS Cleaner', __('This plugin lets you <strong>clean your WordPress website</strong> in a few clics to gain speed: posts, comments, terms, users, settings, plugins, medias, files.', 'lwscache'), true),
        'lws-sms' => array('LWS SMS', __('This plugin, designed specifically for WooCommerce, lets you <strong>send SMS automatically to your customers</strong>. You will need an account at LWS and enough credits to send SMS. Create personnalized templates, manage your SMS and sender IDs and more!', 'lwscache'), false),
        'lws-affiliation' => array('LWS Affiliation', __('With this plugin, you can add banners and widgets on your website and use those with your <strong>affiliate account LWS</strong>. Earn money and follow the evolution of your gains on your website.', 'lwscache'), false),
        'lwscache' => array('LWSCache', __('Based on the Varnich cache technology and NGINX, LWSCache let you <strong>speed up the loading of your pages</strong>. This plugin helps you automatically manage your LWSCache when editing pages, posts... and purging all your cache. Works only if your server use this cache.', 'lwscache'), false),
        'lws-tools' => array('LWS Tools', __('This plugin provides you with several tools and shortcuts to manage, secure and optimise your WordPress website. Updating plugins and themes, accessing informations about your server, managing your website parameters, etc... Personnalize every aspect of your website!', 'lwscache'), false)
    );

$plugins_showcased = array('lws-hide-login', 'lws-cleaner', 'lws-tools');

$plugins_activated = array();
$all_plugins = get_plugins();

foreach ($plugins as $slug => $plugin) {
    if (is_plugin_active($slug . '/' . $slug . '.php')) {
        $plugins_activated[$slug] = "full";
    } elseif (array_key_exists($slug . '/' . $slug . '.php', $all_plugins)) {
        $plugins_activated[$slug] = "half";
    }
}

$is_mutu = false;
$is_cpanel = false;
switch (explode('/', getcwd())[1]) {
    case 'htdocs':
        $is_mutu = true;
        $is_cpanel = false;
        break;
    case 'home':
        $is_cpanel = true;
        $is_mutu = false;
        break;
}

if ($is_cpanel){
    // If Fastest Cache, then get the API Key on page launch
    function lwscache_get_api_key_cache(){
        $array = (explode('/', ABSPATH));
        $path = implode('/', array($array[0], $array[1], $array[2]));        
        if (file_exists($path . '/tmp/fc_token_api')){
            $file = file_get_contents($path . '/tmp/fc_token_api');
            if (time() - filemtime($path) < 31556926){
                return $file;
            }
            else{
                $var = wp_remote_post(
                    "http://localhost:6084/api/login",
                    array(
                    'method'      => 'POST',
                    'headers'     => array('Content-Type' => "application/x-www-form-urlencoded"),
                    'body'        => array(
                        'user' =>  get_current_user(),
                        ),
                    )
                );
            
                file_put_contents($path . '/tmp/fc_token_api', file_get_contents($path . '/tmp/fc_token_' . json_decode($var['body'], true)['name']));
                return file_get_contents($path . '/tmp/fc_token_api');
            }
        }
        else{
            $var = wp_remote_post(
                "http://localhost:6084/api/login",
                array(
                'method'      => 'POST',
                'headers'     => array('Content-Type' => "application/x-www-form-urlencoded"),
                'body'        => array(
                    'user' =>  get_current_user(),
                    ),
                )
            );
        
            file_put_contents($path . '/tmp/fc_token_api', file_get_contents($path . '/tmp/fc_token_' . json_decode($var['body'], true)['name']));
            return file_get_contents($path . '/tmp/fc_token_api');
        }

    }

    $api_key_fc = lwscache_get_api_key_cache();
}

// $var2 = wp_remote_post(
// 	"http://localhost:6084/api/domains/stagiaire-wordpress.site",
// 	array(
// 	'method'      => 'GET',
// 	'headers'     => array('Authorization' => 'Bearer ' . $api_key ),
// 	),
// );

// $var2 = wp_remote_post(
// 		"http://localhost:6084/api/domains",
// 		array(
// 		'method'      => 'GET',
// 		'headers'     => array('Authorization' => 'Bearer ' . $api_key ),
// 		),
// 	);

// var_dump($var2);

?>


<div id='modal_popup' class='modal fade' data-result="warning" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-body'>
                <div class="container-modal">
                    <div class="success-animation">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </div>
                    <div class="error-animation">
                        <svg class="circular red-stroke">
                            <circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10" />
                        </svg>
                        <svg class="cross red-stroke">
                            <g transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-502.652,-204.518)">
                                <path class="first-line" d="M634.087,300.805L673.361,261.53" fill="none" />
                            </g>
                            <g transform="matrix(-1.28587e-16,-0.79961,0.79961,-1.28587e-16,-204.752,543.031)">
                                <path class="second-line" d="M634.087,300.805L673.361,261.53" />
                            </g>
                        </svg>
                    </div>
                    <div class="warning-animation">
                        <svg class="circular yellow-stroke">
                            <circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10" />
                        </svg>
                        <svg class="alert-sign yellow-stroke">
                            <g transform="matrix(1,0,0,1,-615.516,-257.346)">
                                <g transform="matrix(0.56541,-0.56541,0.56541,0.56541,93.7153,495.69)">
                                    <path class="line" d="M634.087,300.805L673.361,261.53" fill="none" />
                                </g>
                                <g transform="matrix(2.27612,-2.46519e-32,0,2.27612,-792.339,-404.147)">
                                    <circle class="dot" cx="621.52" cy="316.126" r="1.318" />
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="content_message" id="container_content"></div>
                    <div>
                        <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="closemodal()">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="lwscache_container">    
    <div class="lwscache_container_top">
        <div class="lwscache_main_header">
            <div class="lwscache_header">
                <div class="lwscache_header_left">
                    <span><?php echo esc_html('LWSCache'); ?></span>
                    <span><?php esc_html_e('by', 'lwscache'); ?></span>
                    <span class="logo_lws"></span>
                </div>
                <div class="lwscache_header_right">
                    <div class="lwscache_header_right_top">
                        <img src="<?php echo esc_url(plugins_url('icons/wordpress_blanc.svg', __DIR__))?>" alt="Logo WP blanc" width="20px" height="20px">
                        <span><?php esc_html_e('Exclusive: ', 'lwscache'); ?></span></span>
                        <span><?php esc_html_e('-15% on your WordPress hosting', 'lwscache'); ?></span>
                    </div>
                    <div class="lwscache_header_right_bottom">
                        <label onclick="lwscache_copy_clipboard(this)" readonly text="WPEXT15">
                            <span><?php echo esc_html('WPEXT15'); ?></span>
                            <img src="<?php echo esc_url(plugins_url('icons/copier.svg', __DIR__))?>" alt="Logo Copy Element" width="15px" height="18px">
                        </label>
                        <a target="_blank" href="<?php echo esc_url('https://www.lws.fr/hebergement_wordpress.php');?>"><?php esc_html_e("Let's go!", 'lwscache'); ?></a>
                    </div>
                </div>
            </div>

            <div class="lwscache_subheader">
                <img src="<?php echo esc_url(plugins_url('icons/lws_cache.svg', __DIR__))?>" alt="LWS Cache Logo" width="100px" height="100px">
                <div class="lwscache_subheader_text">
                    <?php echo esc_html_e('Boost the speed of your WordPress website with our dynamic server cache NGINX, reducing loading times and improving SEO. Simple, efficient and automatic.', 'lwscache'); ?>
                    <?php if($is_mutu) : ?>
                        <a href='https://aide.lws.fr/a/1579' target="_blank"><?php esc_html_e("Learn more", 'lwscache'); ?></a>
                    <?php elseif($is_cpanel) : ?>
                        <a href='https://aide.lws.fr/a/1515' target="_blank"><?php esc_html_e("Learn more", 'lwscache'); ?></a>            
                    <?php else : ?>
                        <a href='https://aide.lws.fr/a/1579' target="_blank"><?php esc_html_e("Learn more", 'lwscache'); ?></a>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="lwscache_rating">
            <img src="<?php echo esc_url(plugins_url('icons/noter_plugin.svg', __DIR__))?>" alt="5 stars, fifth bigger" width="135px" height="55x">
            <div class="lwscache_rating_title"><?php esc_html_e('Rate this plugin!', 'lwscache'); ?></div>
            <div class="lwscache_rating_content"><?php esc_html_e('Support us and help other users choisir the right plugin.', 'lwscache'); ?></div>
            <a href="https://wordpress.org/support/plugin/lwscache/reviews/#new-post" target="_blank"><?php esc_html_e('Leave a review', 'lwscache'); ?></a>
        </div>
    </div>

    <?php // Check if LWSCache or FC are activated ; on cPanel, allow its activation ; on LWSPanel, just say you need to activate it ; on others, not compatible ?>
    <?php if( ( $is_mutu && !isset($_SERVER['lwscache']) ) || ( $is_cpanel && !isset($_SERVER['HTTP_X_CACHE_ENGINE']) ) || ( !isset($_SERVER['lwscache']) && !isset($_SERVER['HTTP_X_CACHE_ENGINE']) ) ) : ?>
        <div class="notif_cache">
            <?php esc_html_e('Your site is not compatible with this plugin.', 'lwscache'); ?>
        </div>
    <?php else : ?>
        <?php if ($is_mutu && $_SERVER['lwscache'] != 'On') : ?>
            <div class="notif_cache">
                <p>
                    <?php esc_html_e('The plugin cannot currently work with your service because LWSCache caching has not been enabled in your client panel.', 'lwscache'); ?>
                </p>
                <p>
                    <?php esc_html_e('We invite you to activate this feature by logging into your LWS account and following', 'lwscache'); ?>
                    <a href='https://aide.lws.fr/a/1573' target='_blank'><?php esc_html_e('this documentation', 'lwscache'); ?></a>.
                </p>
                <p>
                    <?php esc_html_e('After activation, it will be taken into consideration after up to 15 minutes.', 'lwscache'); ?>
                </p>
            </div>
        <?php elseif ($is_cpanel && $_SERVER['HTTP_X_CACHE_ENGINE_ENABLED'] != '1') : ?>
            <div class="notif_cache">
                <p>
                    <?php esc_html_e('The plugin cannot currently work with your service because FastestCache caching has not been enabled in your cPanel.', 'lwscache'); ?>
                </p>
                <p>
                    <?php echo __('You can activate it on your domain "', 'lwscache') . $_SERVER['HTTP_HOST'] . __('" by clicking on the button below: ', 'lwscache'); ?>
                </p>
                <button class="lwscache_input_stop2" name="lwscache_activate_cache" onclick="change_cache_fastest_cache('wordpress')">
                    <?php esc_html_e('Activate Fastest Cache', 'lwscache'); ?>
                </button>
            </div>
        <?php endif ?>
    <?php endif ?>

    <div class="lwscache_main_content">                
        <div class="tab_lwscache" id='tab_lwscache_block'>
            <?php if(($is_mutu && isset($_SERVER['lwscache'])) || ($is_cpanel && isset($_SERVER['HTTP_X_CACHE_ENGINE']))): ?>
                <?php if(($is_mutu && $_SERVER['lwscache'] == 'On') || ($is_cpanel && $_SERVER['HTTP_X_CACHE_ENGINE_ENABLED'] == '1')) : ?>
                        <div id="tab_lwscache" role="tablist" aria-label="Onglets_lwscache">
                            <?php foreach ($tabs_list as $tab) : ?>
                                <button
                                    id="<?php echo esc_attr('nav-' . $tab[0]); ?>"
                                    class="tab_nav_lwscache <?php echo $tab[0] == 'caching' ? esc_attr('active') : ''; ?>"
                                    data-toggle="tab" role="tab"
                                    aria-controls="<?php echo esc_attr($tab[0]);?>"
                                    aria-selected="<?php echo $tab[0] == 'caching' ? esc_attr('true') : esc_attr('false'); ?>"
                                    tabindex="<?php echo $tab[0] == 'caching' ? esc_attr('0') : '-1'; ?>">
                                    <?php echo esc_html($tab[1]); ?>
                                </button>
                            <?php endforeach ?>
                            <div id="selector" class="selector_tab"></div>
                        </div>
                <?php endif ?>
            <?php endif ?>

            <div class="tab-pane main-tab-pane" id="caching" role="tabpanel" aria-labelledby="nav-caching" tabindex="0"
                <?php if ($is_mutu) : ?>
                    <?php echo isset($_SERVER['lwscache']) ? ($_SERVER['lwscache'] == 'On' ? '' : esc_attr('hidden')) : esc_attr('hidden') ?>>
                <?php elseif ($is_cpanel) : ?>
                    <?php echo isset($_SERVER['HTTP_X_CACHE_ENGINE']) ? ($_SERVER['HTTP_X_CACHE_ENGINE_ENABLED'] == '1' ? '' : esc_attr('hidden')) : esc_attr('hidden') ?>>
                <?php endif ?>
                <div id="post-body" class="lwscache_configpage">
                    <?php include plugin_dir_path(__FILE__) . 'lws-cache-general-options.php'; ?>
                </div>
            </div>

            <div class="tab-pane main-tab-pane" id="plugins" role="tabpanel" aria-labelledby="nav-plugins" tabindex="-1"
                <?php if ($is_mutu) : ?>
                    <?php echo isset($_SERVER['lwscache']) ? ($_SERVER['lwscache'] == 'On' ? esc_attr('hidden') : '') : '' ?>>
                <?php elseif ($is_cpanel) : ?>
                    <?php echo isset($_SERVER['HTTP_X_CACHE_ENGINE']) ? ($_SERVER['HTTP_X_CACHE_ENGINE_ENABLED'] == '1' ? esc_attr('hidden') : '') : '' ?>>
                <?php endif ?>
                <div id="post-body" class="lwscache_configpage">
                    <?php include plugin_dir_path(__FILE__) . 'lws-cache-our-plugins.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function lwscache_copy_clipboard(input) {
        navigator.clipboard.writeText(input.innerText.trim());
        setTimeout(function() {
            jQuery('#copied_tip').remove();
        }, 500);
        jQuery(input).append("<div class='tip' id='copied_tip'>" +
            "<?php esc_html_e('Copied!', 'lwscache');?>" +
            "</div>");
    }
</script>

<?php if(($is_mutu && isset($_SERVER['lwscache'])) || ($is_cpanel && isset($_SERVER['HTTP_X_CACHE_ENGINE']))) : ?>
    <?php if(($is_mutu && $_SERVER['lwscache'] == 'On') || ($is_cpanel && $_SERVER['HTTP_X_CACHE_ENGINE_ENABLED'] == '1')) : ?>
        <script>
            const tabs = document.querySelectorAll('.tab_nav_lwscache[role="tab"]');

            // Add a click event handler to each tab
            tabs.forEach((tab) => {
                tab.addEventListener('click', lwscache_changeTabs);
            });

            lwscache_selectorMove(document.getElementById('nav-caching'), document.getElementById('nav-caching').parentNode);

            function lwscache_selectorMove(target, parent) {
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

            function lwscache_changeTabs(e) {
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
                    .querySelectorAll('.tab_nav_lwscache[aria-selected="true"]')
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


                lwscache_selectorMove(target, parent);
            }
        </script>
    <?php endif ?>
<?php endif ?>

<script>
    jQuery(document).ready(function() {
        <?php foreach ($plugins_activated as $slug => $activated) : ?>
        <?php if ($activated == "full") : ?>

        var button = jQuery(
            "<?php echo esc_attr("#bis_" . $slug); ?>"
        );
        button.children()[3].classList.remove('hidden');
        button.children()[0].classList.add('hidden');
        button.prop('onclick', false);
        button.addClass('lwscache_button_ad_block_validated');

        <?php elseif ($activated == "half") : ?>
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
        button.classList.remove('lwscache_button_ad_block_validated');
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
            action: "lwscache_downloadPlugin",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('updates')); ?>',
            slug: button.getAttribute('value'),
        };
        jQuery.post(ajaxurl, data, function(response) {
            var success = response.success;
            var slug = response.data.slug;
            if (!success) {
                if (response.data.errorCode == 'folder_exists') {
                    var data = {
                        action: "lwscache_activatePlugin",
                        ajax_slug: slug,
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                        jQuery('#' + bouton_id).children()[3].classList.remove('hidden');
                        jQuery('#' + bouton_id).addClass('lwscache_button_ad_block_validated');
                    });
                } else {
                    jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[2].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[3].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[0].classList.add('hidden');
                    jQuery('#' + bouton_id).children()[4].classList.remove('hidden');
                    jQuery('#' + bouton_id).addClass('lws_cache_button_ad_block_failed');
                    setTimeout(() => {
                        jQuery('#' + bouton_id).removeClass('lws_cache_button_ad_block_failed');
                        jQuery('#' + bouton_id).prop('disabled', false);
                        jQuery('#' + bouton_id).children()[0].classList.remove('hidden');
                        jQuery('#' + bouton_id).children()[4].classList.add('hidden');
                    }, 2500);
                }
            } else {
                jQuery('#' + bouton_id).children()[1].classList.add('hidden');
                jQuery('#' + bouton_id).children()[2].classList.remove('hidden');
                jQuery('#' + bouton_id).prop('disabled', false);
            }
        });
    }
</script>

<script>
function change_cache_fastest_cache(state){
    var data = {
        action: "lwscache_change_cache_state",
        _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('fastest_cache_change_state')); ?>',
        cache_state: state,
    };
    jQuery.post(ajaxurl, data, function(response) {

        document.location.reload();
    });

}
</script>