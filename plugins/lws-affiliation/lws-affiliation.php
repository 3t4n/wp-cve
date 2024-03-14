<?php
/*
* Plugin Name: LWS Affiliation
* Plugin URI: https://affiliation.lws-hosting.com
* Description: Integrate our banners and widgets on your site with our affiliate program !
* Version: 2.3.2
* Author: LWS
* Author URI: https://www.lws.fr
* Tags: lws, hosting, affiliate program, affiliation
* Requires at least: 5.0
* Requires PHP: 7.0
* Tested up to: 6.3
* Stable tag: 2.3.2
* License: GPLv2 or later
*
* @package    lws-affiliation
*/


define('LWS_AFF_URL', plugin_dir_url(__FILE__));
define('LWS_AFF_DIR', plugin_dir_path(__FILE__));

// Chargement du fichier de langue
add_action('init', 'lws_aff_add_language_block');
function lws_aff_add_language_block()
{
    load_plugin_textdomain('lws-affiliation', false, basename(__DIR__) . '/languages');
}

//Chargement plugin TinyMCE pour l'admin
if (get_option('lws_aff_apikey') && !empty(get_option('lws_aff_apikey'))) {
    add_filter('mce_buttons', 'lws_aff_register_buttons');
    add_filter('mce_external_plugins', 'lws_aff_register_tinymce_javascript');
}

register_activation_hook(__FILE__, 'lws_aff_on_activation');
function lws_aff_on_activation()
{
    set_transient('lwsaff_remind_me', 1600000);
}


function lws_aff_register_buttons($buttons)
{
    array_push($buttons, 'example');
    return $buttons;
}

function lws_aff_register_tinymce_javascript($plugin_array)
{
    // On récupere le listing des bannières et des widgets
    if (!get_transient('lws_aff_banners')) {
        $key = json_decode(get_option('lws_aff_apikey'));
        $urlAuth = 'https://affiliation.lws-hosting.com/api/listBanners2/' . $key;
        $return = wp_remote_post($urlAuth)['body'];
        set_transient('lws_aff_banner', $return, 86400);
    }

    $plugin_array['example'] = plugins_url('/js/admin/tinymce-plugin.js', __FILE__);
    $plugin_array['noneditable'] = plugins_url('/js/admin/noneditable/plugin.min.js', __FILE__);
    return $plugin_array;
}


// Ajoute le Widget
add_filter('the_content', 'lws_aff_add_widget', 20);
function lws_aff_add_widget($content)
{
    $matches = array();
    preg_match("'<div id=\"divWidgetDomainAffiliationLWS\" class=\"mceNonEditable\" style=\"(.*?)\">'si", $content, $matches);
    if (isset($matches[1])) {
        $content = str_replace($matches[1], '', $content);
    }

    $matches = array();
    preg_match("'<div id=\"divWidgetTableAffiliationLWS\" class=\"mceNonEditable\" style=\"(.*?)\">'si", $content, $matches);
    if (isset($matches[1])) {
        $content = str_replace($matches[1], '', $content);
    }

    $content = str_replace(esc_html__('Widget Domaine Affiliation LWS', 'lws-affiliation'), '', $content);
    $content = str_replace(esc_html__('Widget Tableau Affiliation LWS', 'lws-affiliation'), '', $content);
    $content = str_replace('<p></p>', '', $content);

    return $content;
}

// Ajoute la feuille de style pour l'admin
add_action('admin_enqueue_scripts', 'lws_aff_add_admin_style');
function lws_aff_add_admin_style()
{
    if (get_current_screen()->base == ('toplevel_page_lws-affiliation-settings')) {
        wp_enqueue_style('lwsaffiliationAdminStyle', plugins_url('./css/admin/style.css', __FILE__), false, '1.0.0');
        wp_enqueue_style('dt_css', plugins_url('css/admin/jquery.dataTables.min.css', __FILE__));
        wp_enqueue_style('dt_resp_css', plugins_url('css/admin/responsive.dataTables.min.css', __FILE__));
        wp_enqueue_script('dt_js', plugins_url('js/jquery.dataTables.min.js', __FILE__));
        wp_enqueue_script('dt_resp_js', plugins_url('js/dataTables.responsive.min.js', __FILE__));
        wp_enqueue_style('lws_aff-Poppins', 'https://fonts.googleapis.com/css?family=Poppins');
    } else {
        wp_enqueue_style('lws_aff_css_out', LWS_AFF_URL . "css/admin/lws_aff_style_out.css");
        if (!get_transient('lwsaff_remind_me') && !get_option('lwsaff_do_not_ask_again')) {
            add_action('admin_notices', 'lws_aff_review_ad_plugin');
        }
    }
}

add_action('wp_enqueue_scripts', 'lws_aff_add_front_style');

function lws_aff_add_front_style()
{
    wp_enqueue_style('lwsaffiliation_Widget', plugins_url('./css/widget/widget.css', __FILE__));
}

function lws_aff_review_ad_plugin()
{
?>
    <script>
        function lws_aff_remind_me() {
            var data = {
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('reminder_for_aff')); ?>',
                action: "lws_aff_reminder_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response) {
                jQuery("#lws_aff_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lws_aff_review_notice").addClass("lws_hidden");
                }, 800);
            });

        }

        function lws_aff_do_not_bother_me() {
            var data = {
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('donotask_for_aff')); ?>',
                action: "lws_aff_donotask_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response) {
                jQuery("#lws_aff_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lws_aff_review_notice").addClass("lws_hidden");
                }, 800);
            });
        }
    </script>

    <div class="notice notice-info is-dismissible lws_aff_review_block_general" id="lws_aff_review_notice">
        <div class="lws_aff_circle">
            <img class="lws_aff_review_block_image" src="<?php echo esc_url(LWS_AFF_URL . '/images/plugin_lws-affiliation.svg') ?>" width="40px" height="40px">
        </div>
        <div style="padding:16px">
            <h1 class="lws_aff_review_block_title"> <?php esc_html_e('Thank you for using LWS Affiliation!', 'lws-affiliation'); ?></h1>
            <p class="lws_aff_review_block_desc"><?php _e('Evaluate our plugin to help others discover the affiliate program and earn money!', 'lws-affiliation'); ?></p>
            <a class="lws_aff_button_rate_plugin" href="https://wordpress.org/support/plugin/lws-affiliation/reviews/" target="_blank"><img style="margin-right: 8px;" src="<?php echo esc_url(plugins_url('images/noter.svg', __FILE__)) ?>" width="15px" height="15px"><?php esc_html_e('Rate', 'lws-affiliation'); ?></a>
            <a class="lws_aff_review_button_secondary" onclick="lws_aff_remind_me()"><?php esc_html_e('Remind me later', 'lws-affiliation'); ?></a>
            <a class="lws_aff_review_button_secondary" onclick="lws_aff_do_not_bother_me()"><?php esc_html_e('Do not ask again', 'lws-affiliation'); ?></a>
        </div>
    </div>
<?php
}

//AJAX Reminder//
add_action("wp_ajax_lws_aff_reminder_ajax", "lws_aff_remind_me_later");
function lws_aff_remind_me_later()
{
    check_ajax_referer('reminder_for_aff', '_ajax_nonce');
    if (isset($_POST['data'])) {
        set_transient('lwsaff_remind_me', 1600000);
    }
}

//AJAX Reminder//
add_action("wp_ajax_lws_aff_donotask_ajax", "lws_aff_do_not_ask");
function lws_aff_do_not_ask()
{
    check_ajax_referer('donotask_for_aff', '_ajax_nonce');
    if (isset($_POST['data'])) {
        update_option('lwsaff_do_not_ask_again', true);
    }
}

// Ajoute une balise script permettant de définir l'URL du plugin Wordpress
add_action('admin_print_scripts', 'lws_aff_script_admin');
function lws_aff_script_admin()
{
    //Path to get wp-load.php
    $query = http_build_query(array('path' => ABSPATH));
?>
    <script type='text/javascript'>
        var affiliationConfigWidgetImage = "<?php echo esc_url(plugins_url('/images/lws_Icone.svg', __FILE__)); ?>"
        var affiliationConfigWidgetQuery = "<?php echo esc_url($query); ?>"
        var affiliationConfigWidgetN = "<?php echo esc_attr(wp_create_nonce('load_banner_modal_n')); ?>";
    </script>
    <?php
}

// Ajoute une page d'option du plugin
add_action('admin_menu', 'lws_aff_menu');
function lws_aff_menu()
{
    add_menu_page(esc_html__('LWS Affiliation Settings', 'lws-affiliation'), 'LWS Affiliation', 'manage_options', 'lws-affiliation-settings', 'lws_aff_setup', plugins_url('/images/plugin_lws_affiliation.svg', __FILE__));
}

//setup
function lws_aff_setup()
{
    // Suppression de la config actuelle
    if (isset($_POST['del_config'])) {
        delete_option('lws_aff_apikey');
    }

    if (isset($_POST['validate-config-aff-lws'])) {
        if (!empty(sanitize_text_field($_POST['username-aff-lws'])) && !empty(sanitize_text_field($_POST['password-aff-lws']))) {
            if (is_numeric(sanitize_text_field($_POST['username-aff-lws']))) {
                $urlAuth = 'https://affiliation.lws-hosting.com/api/auth/%d/%s';
                $retourApi = json_decode(file_get_contents(sprintf($urlAuth, sanitize_text_field($_POST['username-aff-lws']), sanitize_text_field($_POST['password-aff-lws']))));

                if (property_exists($retourApi, 'error')) {
                    $formError = esc_html__('Please verify your login parameters.', 'lws-affiliation');
                } elseif (property_exists($retourApi, 'apikey')) {
                    update_option('lws_aff_apikey', json_encode($retourApi->apikey, JSON_PRETTY_PRINT));
                    $formSuccess = true;
                } else {
                    $formError = esc_html__('An error occured, please try to login again later.', 'lws-affiliation');
                }
            } else {
                $formError = esc_html__('Your affiliate ID must only contain numbers.', 'lws-affiliation');
            }
        } elseif (empty(sanitize_text_field($_POST['username-aff-lws']))) {
            $formError = esc_html__('Please enter your affiliate ID.', 'lws-affiliation');
        } elseif (empty(sanitize_text_field($_POST['password-aff-lws']))) {
            $formError = esc_html__('Please enter you affiliate password.', 'lws-affiliation');
        }
    }

    if ($has_api = get_option('lws_aff_apikey')) {
        $data_global = lws_aff_apiStats();
        $last_sales = lws_aff_apiLastSales();
    }

    include __DIR__ . '/view/admin/tabs.php';
}

// Ajout d'une alerte lorsqu'aucun identifiant affilié n'a été renseigné
add_action('admin_notices', 'lws_aff_check_username');
function lws_aff_check_username()
{
    if (!strstr($_SERVER['QUERY_STRING'], 'page=lws-affiliation-settings')) {
        if (!get_option('lws_aff_apikey')) {
    ?>
            <div class="error ithemes">
                <strong><?php echo esc_html('LWS Affiliation'); ?></strong>:
                <?php echo esc_html__("You have not filled in your LWS identifiers. You can do so directly from this page:", 'lws-affiliation'); ?>
                <a href="plugins.php?page=lws-affiliation-settings"> <?php echo esc_html__("Enter your username LWS", 'lws-affiliation'); ?></a>
            </div>';
    <?php
        }
    }
}

// Ajout d'une alerte lorsque allow_url_fopen est désactivé
add_action('admin_notices', 'lws_aff_check_functions');
function lws_aff_check_functions()
{
    //ini_set('allow_url_fopen', 0);
    if (!ini_get('allow_url_fopen')) {
        echo '<div class="update-nag"><p><strong>' . esc_html('LWS Affiliation') . '</strong>:' . esc_html__("Please activate the 'allow_url_fopen' variable in your PHP.ini file, otherwise the plugin will not work correctly.", 'lws-affiliation') . '</p></div>';
    }
}

//API CALL//
function lws_aff_apiStats()
{
    $urlAuth = 'https://affiliation.lws-hosting.com/api/getStatsPlugin/%s';
    $url = sprintf($urlAuth, json_decode(get_option('lws_aff_apikey')));
    $retourApi = json_decode(file_get_contents($url, true), true);
    return $retourApi;
}

function lws_aff_apiLastSales()
{
    $urlAuth = 'https://affiliation.lws-hosting.com/api/getListingVentePlugin/%s';
    $retourApi = json_decode(file_get_contents(sprintf($urlAuth, json_decode(get_option('lws_aff_apikey')))), true);
    return $retourApi['last_vente'];
}

// AJAX PART FOR THE DOWNLOAD //
/*AJAX DOWNLOAD AND ACTIVATE PLUGINS*/

//AJAX DL Plugin//
add_action("wp_ajax_lws_aff_downloadPlugin", "wp_ajax_install_plugin");
//

//AJAX Activate Plugin//
add_action("wp_ajax_lws_aff_activatePlugin", "lws_aff_activate_plugin");
function lws_aff_activate_plugin()
{
    if (isset($_POST['ajax_slug'])) {
        switch (sanitize_textarea_field($_POST['ajax_slug'])) {
            case 'lws-hide-login':
                activate_plugin('lws-hide-login/lws-hide-login.php');
                break;
            case 'lws-sms':
                activate_plugin('lws-sms/lws-sms.php');
                break;
            case 'lws-tools':
                activate_plugin('lws-tools/lws-tools.php');
                break;
            case 'lws-affiliation':
                activate_plugin('lws-affiliation/lws-affiliation.php');
                break;
            case 'lws-cleaner':
                activate_plugin('lws-cleaner/lws-cleaner.php');
                break;
            case 'lwscache':
                activate_plugin('lwscache/lwscache.php');
                break;
            case 'lws-optimize':
                activate_plugin('lws-optimize/lws-optimize.php');
                break;
        }
    }
    wp_die();
}
//

add_action("wp_ajax_load_banner_modal", "load_configMCE");
function load_configMCE()
{
    check_ajax_referer('load_banner_modal_n', '_ajax_nonce');
    $path = $_GET['path'];
    $query = http_build_query(array('path' => $path));
    global $wpdb;
    $banners = $wpdb->get_results("SELECT option_value FROM `" . $wpdb->options . "` WHERE option_name='_transient_lws_aff_banner'")[0]->option_value;
    $banners = json_decode($banners, true);

    $authorized_tags = '{ "address": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "a": { "href": true, "rel": true, "rev": true, "name": true, "target": true, "download": { "valueless": "y" }, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "abbr": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "acronym": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "area": { "alt": true, "coords": true, "href": true, "nohref": true, "shape": true, "target": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "article": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "aside": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "audio": { "autoplay": true, "controls": true, "loop": true, "muted": true, "preload": true, "src": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "b": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "bdo": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "big": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "blockquote": { "cite": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "br": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "button": { "disabled": true, "name": true, "type": true, "value": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "caption": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "cite": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "code": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "col": { "align": true, "char": true, "charoff": true, "span": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "colgroup": { "align": true, "char": true, "charoff": true, "span": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "del": { "datetime": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dd": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dfn": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "details": { "align": true, "open": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "div": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dl": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dt": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "em": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "fieldset": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "figure": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "figcaption": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "font": { "color": true, "face": true, "size": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "footer": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h1": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h2": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h3": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h4": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h5": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h6": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "header": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "hgroup": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "hr": { "align": true, "noshade": true, "size": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "i": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "img": { "alt": true, "align": true, "border": true, "height": true, "hspace": true, "loading": true, "longdesc": true, "vspace": true, "src": true, "usemap": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ins": { "datetime": true, "cite": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "kbd": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "label": { "for": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "legend": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "li": { "align": true, "value": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "main": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "map": { "name": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "mark": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "menu": { "type": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "nav": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "object": { "data": { "required": true, "value_callback": "_wp_kses_allow_pdf_objects" }, "type": { "required": true, "values": [ "application\/pdf" ] }, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "p": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "pre": { "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "q": { "cite": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rb": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rp": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rt": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rtc": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ruby": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "s": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "samp": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "span": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "section": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "small": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "strike": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "strong": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "sub": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "summary": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "sup": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "table": { "align": true, "bgcolor": true, "border": true, "cellpadding": true, "cellspacing": true, "rules": true, "summary": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tbody": { "align": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "td": { "abbr": true, "align": true, "axis": true, "bgcolor": true, "char": true, "charoff": true, "colspan": true, "headers": true, "height": true, "nowrap": true, "rowspan": true, "scope": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "textarea": { "cols": true, "rows": true, "disabled": true, "name": true, "readonly": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tfoot": { "align": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "th": { "abbr": true, "align": true, "axis": true, "bgcolor": true, "char": true, "charoff": true, "colspan": true, "headers": true, "height": true, "nowrap": true, "rowspan": true, "scope": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "thead": { "align": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "title": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tr": { "align": true, "bgcolor": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "track": { "default": true, "kind": true, "label": true, "src": true, "srclang": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tt": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "u": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ul": { "type": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ol": { "start": true, "type": true, "reversed": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "var": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "video": { "autoplay": true, "controls": true, "height": true, "loop": true, "muted": true, "playsinline": true, "poster": true, "preload": true, "src": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true } }';
    $authorized_tags = json_decode($authorized_tags, true);
    $authorized_tags['script'] = array();
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>LWS Affiliation Widget Configuration</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo esc_url(LWS_AFF_URL . "css/widget/in_widget_style.css"); ?>">
    </head>

    <body class="mce-container-lws-body">

        <div class="tab_affiliation hidden" id="tab_affiliation" role="tablist" aria-label="Onglets Widget">
            <button id="nav-banners" class="tab_nav_affiliation active" data-toggle="tab" role="tab" aria-controls="banners" aria-selected="true" tabindex="0">
                <?php esc_html_e('Banners', 'lws-affiliation'); ?>
            </button>
            <button id="nav-widget" class="tab_nav_affiliation" data-toggle="tab" role="tab" aria-controls="widgets_ds" aria-selected="false" tabindex="-1">
                <?php esc_html_e('Domains search', 'lws-affiliation'); ?>
            </button>
            <button id="nav-widget-t" class="tab_nav_affiliation" data-toggle="tab" role="tab" aria-controls="widgets_t" aria-selected="false" tabindex="-1">
                <?php esc_html_e('Offers boards', 'lws-affiliation'); ?>
            </button>
            <div id="selector" class="selector_tab">&nbsp;</div>
        </div>

        <div class="select_aff_div">
            <select name="tab_site_select" id="tab_site_select" class="select_affiliation_banners_site">
                <option value="nav-banners">
                    <?php esc_html_e('Banners', 'lws-affiliation'); ?>
                </option>
                <option value="nav-widget">
                    <?php esc_html_e('Domains search', 'lws-affiliation'); ?>
                </option>
                <option value="nav-widget-t">
                    <?php esc_html_e('Offers boards', 'lws-affiliation'); ?>
                </option>
            </select>
        </div>

        <button id="button_scroll" class="hide_lws" title="Go to top"><img src="<?php echo esc_url(plugin_dir_url(__FILE__) . "svg/icon_arrow.svg"); ?>"></button>

        <div class="tab-content" id="tab_affiliation_content">
            <!-- Bannières -->
            <div class="tab-pane main-tab-pane" id="banners" role="tabpanel" aria-labelledby="nav-banners" tabindex="0">
                <div class="tab_content_general_lws">
                    <div class="tab_affiliation_banners_site" id="tab_banners_site" role="tablist" aria-label="Onglets Site Bannières">
                        <?php $count = 0; ?>
                        <?php foreach ($banners['image'] as $n => $site) : ?>
                            <button id="nav-site-<?php echo esc_html($count); ?>" class="tab_nav_affiliation_banner <?php echo $count == 0 ? esc_html("active") : esc_html("") ?>" data-toggle="tab" role="tab" aria-controls="<?php echo esc_html("tab_" . $count); ?>" aria-selected="<?php echo $count == 0 ? esc_html("true") : esc_html("false") ?>" <?php echo $count == 0 ? esc_html("tabindex='0'") : esc_html("tabindex='-1'") ?>>
                                <?php echo esc_html($n); ?>
                            </button>
                            <?php $count++; ?>
                        <?php endforeach ?>
                    </div>

                    <div class="select_aff_banners_div hidden">
                        <select name="tab_banners_site_select" id="tab_banners_site_select" class="select_affiliation_banners_site">
                            <?php $count = 0; ?>
                            <?php foreach ($banners['image'] as $n => $site) : ?>
                                <option value="nav-site-<?php echo esc_html($count); ?>">
                                    <?php echo esc_html($n); ?>
                                </option>
                                <?php $count++; ?>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <?php $count = 0; ?>
                    <div class="tab-content" id="tab_affiliation_banner_content">
                        <?php foreach ($banners['image'] as $n => $site) : ?>
                            <div class="tab-pane lws-content-page" id="<?php echo esc_html("tab_" . $count); ?>" role="tabpanel" aria-labelledby="nav-site-<?php echo esc_html($count); ?>" <?php echo $count == 0 ? esc_html("tabindex='0'") : esc_html("tabindex='-1'") ?> <?php echo $count == 0 ? esc_html('') : esc_html("hidden='true'") ?>>
                                <div class="tab_content_lws">
                                    <?php foreach ($site as $s => $size) : ?>
                                        <div id=<?php echo esc_html($count . "_" . $s); ?>>
                                            <h2 class="banner_size"><?php echo esc_html($s); ?>
                                            </h2>
                                            <?php foreach ($size as $banner) : ?>
                                                <h4 class="banner_name"><?php echo esc_html($banner['name']) ?>
                                                </h4>
                                                <figure class="banner_figure">
                                                    <img loading="lazy" class="banner_image" src="<?php preg_match('@src="([^"]+)"@', $banner['code_source'], $match);
                                                                                                    $src = array_pop($match);
                                                                                                    echo wp_kses_post($src); ?>" data-source='<figure> <?php echo wp_kses($banner['code_source'], $authorized_tags); ?> 
                                                        <?php if ($banners['code_promo']['have'] == "yes") : ?>
                                                            <figcaption class="caption_banner"> <?php echo esc_html("Profitez de -15% sur vos achats grâce au code promo : " . $banners['code_promo']['code_p']); ?> </figcaption>
                                                        <?php endif ?>
                                                    </figure>' />
                                                    <?php if ($banners['code_promo']['have'] == "yes") : ?>
                                                        <figcaption class="caption_banner"> <?php echo esc_html("Profitez de -15% sur vos achats grâce au code promo : " . $banners['code_promo']['code_p']); ?>
                                                        </figcaption>
                                                    <?php endif ?>
                                                </figure>
                                                <br />
                                            <?php endforeach ?>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                            <?php $count++; ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <!-- Widgets Domaine -->
            <div class="tab-pane main-tab-pane" id="widgets_ds" role="tabpanel" aria-labelledby="nav-widget" hidden='true' tabindex="-1">
                <div class="tab_content_general_lws">
                    <?php foreach ($banners['widget'] as $k => $type) : ?>
                        <?php if ($k == 'domain_search') : ?>
                            <h4 class="banner_size"><?php esc_html_e('Domain search Widget', 'lws-affiliation'); ?></h4>

                            <?php foreach ($type as $widget) : ?>
                                <div class="banner_widget">
                                    <div id="div-<?php echo esc_html($widget['id']); ?>" style="text-align:center;">
                                        <h4 class="lws_aff_widget_title" id="config-name-<?php echo esc_html($widget['id']); ?>">
                                            <?php echo esc_html($widget['name']); ?>
                                        </h4>
                                        <a class="preview_button" name="open_preview_button" value="<?php echo esc_html($widget['id']); ?>" target="_blank" href="<?php echo esc_url($_GET['url']); ?>?action=load_preview_widget&_ajax_nonce=<?php echo esc_attr(wp_create_nonce('load_preview_widget_nonce')); ?>&id=<?php echo esc_html($widget['id']); ?>"><?php esc_html_e('Preview', 'lws-affiliation'); ?></a>
                                        <a class="config_button" id="open-config-<?php echo esc_html($widget['id']); ?>" data-source='<figure> <?php echo wp_kses($widget['code_source'], $authorized_tags); ?> 
                                            <?php if ($banners['code_promo']['have'] == "yes") : ?>
                                            <figcaption class="caption_banner"> <?php echo esc_html("Profitez de -15% sur vos achats grâce au code promo : " . $banners['code_promo']['code_p']); ?> 
                                            </figcaption>
                                            <?php endif ?>
                                            </figure>'>
                                            <?php esc_html_e('Widget configuration', 'lws-affiliation'); ?>
                                        </a>
                                    </div>
                                    <br />

                                    <?php preg_match('@src="([^"]+)"@', $widget['code_source'], $match);
                                    $src = array_pop($match);
                                    $src_prim = substr($src, 0, strpos($src, '/com/')) . '/';
                                    ?>
                                    <form id="form-<?php echo esc_html($widget['id']); ?>" style="display:none;">
                                        <br />
                                        <input id="url-base-<?php echo esc_html($widget['id']); ?>" type="hidden" value="<?php echo esc_html($src); ?>" />
                                        <input id="url-prim-<?php echo esc_html($widget['id']); ?>" type="hidden" value="<?php echo esc_html($src_prim); ?>" />
                                        <input id="type-widget-<?php echo esc_html($widget['id']); ?>" type="hidden" value="<?php echo esc_html($k); ?>" />
                                        <table class="lws_aff_widget_table">
                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Extension: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <input type="text" class="newtag widget_config_content form-input-tip input-lws" placeholder="extension" value="com" id="extensionInput-<?php echo esc_html($widget['id']); ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('The extension shown first among the suggestions. To see all our avaiable extensions, refer to ', 'lws-affiliation'); ?><a href="https://www.lws.fr/tarif_nom_de_domaine.php" target="_blank"><?php esc_html_e('our website', 'lws-affiliation'); ?></a>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Button theme: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <select class="select-lws widget_config_content" onChange="lws_aff_change(<?php echo esc_html($widget['id']) ?>)" id="themeSelect-<?php echo esc_html($widget['id']); ?>">
                                                        <option value="default" selected="selected">default</option>
                                                        <option value="primary">primary</option>
                                                        <option value="info">info</option>
                                                        <option value="success">success</option>
                                                        <option value="warning">warning</option>
                                                        <option value="danger">danger</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('The color on the button, on the right of the searchbar', 'lws-affiliation'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Button text: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <input type="text" onchange="lws_aff_change(<?php echo esc_html($widget['id']); ?>)" class="newtag widget_config_content form-input-tip input-lws" placeholder="extension" value="Commander" maxlength="30" id="txtButtonInput-<?php echo esc_html($widget['id']); ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('The text inside the button', 'lws-affiliation'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="widget_config_button_preview">
                                                    <p class="lws_aff_preview_text" style="margin: -15px 0 35px 0;"><?php esc_html_e('Button preview: ', 'lws-affiliation'); ?>
                                                        <button id="btn-color" type="button" class="button_preview_lws"><?php esc_html_e('Order', 'lws-affiliation'); ?></button></<p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Target: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <select class="select-lws widget_config_content" id="targetSelect-<?php echo esc_html($widget['id']); ?>">
                                                        <option value="blank" selected="selected">blank</option>
                                                        <option value="parent">parent</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('Choose whether to redirect on the current page (parent) or in a new tab (blank)', 'lws-affiliation'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Language: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <select class="select-lws widget_config_content" id="langSelect-<?php echo esc_html($widget['id']); ?>">
                                                        <option value="fra" selected="selected"><?php esc_html_e('French', 'lws-affiliation'); ?></option>
                                                        <option value="eng"><?php esc_html_e('English', 'lws-affiliation'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('Choose the display language of the widget', 'lws-affiliation'); ?></p>
                                                </td>
                                            </tr>

                                        </table>

                                        <div class="no_center">
                                            <a class="preview_button" id="cancelWidgetConfig-<?php echo esc_html($widget['id']); ?>" onclick=""><?php esc_html_e('Abort', 'lws-affiliation'); ?></a>
                                            <a class="config_button" id="validateWidgetConfig-<?php echo esc_html($widget['id']); ?>" onclick=""><?php esc_html_e('Insert widget', 'lws-affiliation'); ?></a>
                                        </div>
                                    </form>

                                </div><br />
                            <?php endforeach ?>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- Widgets Tableaux -->
            <div class="tab-pane main-tab-pane" id="widgets_t" role="tabpanel" aria-labelledby="nav-widget-t" hidden='true' tabindex="-1">
                <div class="tab_content_general_lws">
                    <?php foreach ($banners['widget'] as $k => $type) : ?>
                        <?php if ($k != 'domain_search') : ?>
                            <h4 class="banner_size"><?php esc_html_e('Offers boards', 'lws-affiliation'); ?></h4>

                            <?php foreach ($type as $widget) : ?>
                                <div class="banner_widget">
                                    <div id="div-<?php echo esc_html($widget['id']); ?>" style="text-align:center;">
                                        <h4 class="lws_aff_widget_title" id="config-name-<?php echo esc_html($widget['id']); ?>">
                                            <?php echo esc_html($widget['name']) ?>

                                        </h4>
                                        <a class="preview_button" name="open_preview_button" value="<?php echo esc_html($widget['id']); ?>" target="_blank" href="<?php echo esc_url($_GET['url']); ?>?action=load_preview_widget&_ajax_nonce=<?php echo esc_attr(wp_create_nonce('load_preview_widget_nonce')); ?>&id=<?php echo esc_html($widget['id']); ?>"><?php esc_html_e('Preview', 'lws-affiliation'); ?></a>
                                        <a class="config_button" id="open-config-<?php echo esc_html($widget['id']); ?>" data-source='<figure> <?php echo wp_kses($widget['code_source'], $authorized_tags); ?> 
                                        <?php if ($banners['code_promo']['have'] == "yes") : ?>
                                            <figcaption class="caption_banner"> <?php echo esc_html("Profitez de -15% sur vos achats grâce au code promo : " . $banners['code_promo']['code_p']); ?> </figcaption>
                                        <?php endif ?>
                                    </figure>'><?php esc_html_e('Widget configuration', 'lws-affiliation'); ?></a>
                                    </div>
                                    <br />

                                    <?php preg_match('@src="([^"]+)"@', $widget['code_source'], $match);
                                    $src = array_pop($match);
                                    if ($k != 'domain_search') {
                                        $src_prim = substr($src, 0, strpos($src, '/blank')) . '/';
                                    }
                                    ?>
                                    <form id="form-<?php echo esc_html($widget['id']); ?>" style="display:none;">
                                        <br />
                                        <input id="url-base-<?php echo esc_html($widget['id']); ?>" type="hidden" value="<?php echo esc_html($src); ?>" />
                                        <input id="url-prim-<?php echo esc_html($widget['id']); ?>" type="hidden" value="<?php echo esc_html($src_prim); ?>" />
                                        <input id="type-widget-<?php echo esc_html($widget['id']); ?>" type="hidden" value="<?php echo esc_html($k); ?>" />
                                        <table class="lws_aff_widget_table">
                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Target: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <select class="select-lws widget_config_content" id="targetSelect-<?php echo esc_html($widget['id']); ?>">
                                                        <option value="blank" selected="selected">blank</option>
                                                        <option value="parent">parent</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('Choose whether to redirect on the current page (parent) or in a new tab (blank)', 'lws-affiliation'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="widget_label"><label><?php esc_html_e('Language: ', 'lws-affiliation'); ?></label></th>
                                                <td>
                                                    <select class="select-lws widget_config_content" id="langSelect-<?php echo esc_html($widget['id']); ?>">
                                                        <option value="fra" selected="selected"><?php esc_html_e('French', 'lws-affiliation'); ?></option>
                                                        <option value="eng"><?php esc_html_e('English', 'lws-affiliation'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="widget_config_help" colspan="2">
                                                    <p class="help"><?php esc_html_e('Choose the display language of the widget', 'lws-affiliation'); ?></p>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="no_center">
                                            <a class="preview_button" style="" id="cancelWidgetConfig-<?php echo esc_html($widget['id']); ?>" onclick=""><?php esc_html_e('Abort', 'lws-affiliation'); ?></a>
                                            <a class="config_button" id="validateWidgetConfig-<?php echo esc_html($widget['id']); ?>" onclick=""><?php esc_html_e('Insert widget', 'lws-affiliation'); ?></a>
                                        </div>
                                    </form>

                                </div><br />
                            <?php endforeach ?>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </body>

    <script>
        if (typeof(jQuery) == "undefined") {
            var iframeBody = document.getElementsByTagName("body")[0];
            var jQuery = function(selector) {
                return parent.jQuery(selector, iframeBody);
            };
            var $ = jQuery;
        }
    </script>

    <script>
        function lws_aff_change(val) {
            var value = document.getElementById("themeSelect-" + val).value
            var color = document.getElementById("btn-color");
            var text = document.getElementById("txtButtonInput-" + val)
            if ((text.value).length < 1) {
                color.textContent = "Commander";
            } else {
                color.textContent = text.value;
            }
            switch (value) {
                case "default":
                    color.style.backgroundColor = '#EEF1F5';
                    color.style.color = "black";
                    break;
                case "primary":
                    color.style.backgroundColor = '#286090';
                    color.style.color = "white";
                    break;
                case "info":
                    color.style.backgroundColor = '#31b0d5';
                    color.style.color = "white";
                    break;
                case 'success':
                    color.style.backgroundColor = '#449d44';
                    color.style.color = "white";
                    break;
                case "warning":
                    color.style.backgroundColor = '#ec971f';
                    color.style.color = "white";
                    break;
                case "danger":
                    color.style.backgroundColor = '#c9302c';
                    color.style.color = "white";
                    break;
                default:
                    break;
            }
        }

        button = jQuery("#button_scroll");

        // When the user scrolls down 300px from the top of the document, show the button
        window.onscroll = function() {
            if (jQuery(document).scrollTop() >= 300) {
                button.removeClass('hide_lws');
            } else {
                button.addClass('hide_lws');
            }
        };

        // When the user clicks on the button, scroll to the top of the document
        button.on('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <script>
        var prev_id = null;
        var widget_hidden = false;
        var interval_timeout_lws;

        window.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab_nav_affiliation[role="tab"]');
            const tabList = document.querySelector('#tab_affiliation[role="tablist"]');

            const tabs_banner = document.querySelectorAll('.tab_nav_affiliation_banner[role="tab"]');
            const tabList_banner = document.querySelector('#tab_affiliation_banners_site[role="tablist"]');

            // Add a click event handler to each tab
            tabs.forEach((tab) => {
                tab.addEventListener('click', lws_aff_changeTabs);
            });

            // Add a click event handler to each tab
            tabs_banner.forEach((tab_b) => {
                tab_b.addEventListener('click', lws_aff_changeTabs_banner);
            });
        });

        function lws_aff_selectorMove(target, parent) {
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

        function lws_aff_changeTabs(e) {
            const target = e.target;
            const parent = target.parentNode;
            const grandparent = parent.parentNode;

            // Remove all current selected tabs
            parent
                .querySelectorAll('.tab_nav_affiliation[aria-selected="true"]')
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

            lws_aff_selectorMove(target, parent);
        }

        function lws_aff_changeTabs_banner(e) {
            const target = e.target;
            const parent = target.parentNode;
            const grandparent = parent.parentNode;

            // Remove all current selected tabs
            parent
                .querySelectorAll('.tab_nav_affiliation_banner[aria-selected="true"]')
                .forEach(function(t) {
                    t.setAttribute('aria-selected', false);
                    t.classList.remove("active")
                });

            // Set this tab as selected
            target.setAttribute('aria-selected', true);
            target.classList.add('active');

            // Hide all tab panels
            grandparent
                .querySelectorAll('.tab-pane.lws-content-page[role="tabpanel"]')
                .forEach((p) => p.setAttribute('hidden', true));

            // Show the selected panel
            grandparent.parentNode
                .querySelector(`#${target.getAttribute('aria-controls')}`)
                .removeAttribute('hidden');

        }

        // Au clic sur une bannière image
        jQuery('.banner_image').click(function() {
            var argz = {
                source: jQuery(this).data('source'),
                type: 'image',
            };
            top.tinymce.activeEditor.windowManager.setParams(argz);
            top.tinymce.activeEditor.windowManager.close();
        });

        // Affiche le formulaire de configuration du Widget
        jQuery('a[id^="open-config-"]').click(function() {
            var id = jQuery(this).attr('id');
            id = id.split('-');
            jQuery('#form-' + id[2]).show('normal');
            if (prev_id != null && prev_id != id[2]) {
                jQuery('#form-' + prev_id).hide('normal');
            }
            prev_id = id[2];
            clearInterval(interval_timeout_lws);
            interval_timeout_lws = setTimeout(() => {
                document.querySelector('#div-' + id[2]).scrollIntoView({
                    behavior: 'smooth',
                    block: "center",
                });
            }, 500);


        });
        // Masque le formulaire de configuration du Widget
        jQuery('a[id^="cancelWidgetConfig-"]').click(function() {
            var id = jQuery(this).attr('id');
            id = id.split('-');
            jQuery('#form-' + id[1]).hide('normal');
            prev_id = null;
        });
        // Insertion Widget
        jQuery('a[id^="validateWidgetConfig-"]').click(function() {
            var id = jQuery(this).attr('id');
            id = id.split('-');
            id = id[1];

            // code source
            var source = jQuery('#open-config-' + id).data('source');
            // Url à remplacer
            var urlreplace = jQuery('#url-base-' + id).val();
            // Url prim (sans les paramètres modifiables)
            var urlprim = jQuery('#url-prim-' + id).val();

            //le type de widget
            var type = jQuery('#type-widget-' + id).val();
            if (type == 'domain_search') {
                // Extension
                var extension = encodeURI(jQuery('#extensionInput-' + id).val());
                // theme bouton
                var theme = jQuery('#themeSelect-' + id).val();
                // texte bouton
                var textBtn = encodeURI(jQuery('#txtButtonInput-' + id).val());
                if (textBtn.length < 1) {
                    textBtn = "Commander";
                }
                // cible
                var target = jQuery('#targetSelect-' + id).val();
                // langue
                var lang = jQuery('#langSelect-' + id).val();

                source = source.replace(urlreplace, urlprim + extension + '/' + theme + '/' + textBtn + '/' +
                    target + '/' + lang);

            } else {
                // cible
                var target = jQuery('#targetSelect-' + id).val();
                // langue
                var lang = jQuery('#langSelect-' + id).val();

                source = source.replace(urlreplace, urlprim + target + '/' + lang);
            }

            console.log(source);
            var argz = {
                source: source,
                type: type,
            };


            top.tinymce.activeEditor.windowManager.setParams(argz);
            top.tinymce.activeEditor.windowManager.close();
        });

        // <div class="select_aff_div">
        // <select name="tab_site_select" id="tab_site_select" class="select_affiliation_banners_site">

        if (window.innerWidth >= 740) {
            //jQuery('#tab_banners_site').removeClass("hidden");
            //jQuery('#tab_banners_site_select').addClass("hidden");
            jQuery('#tab_affiliation').removeClass("hidden");
            jQuery('#tab_site_select').parent().addClass("hidden");
            lws_aff_selectorMove(document.getElementById('nav-banners'), document.getElementById('nav-banners').parentNode);
        }

        jQuery(window).on('resize', function() {

            if (window.innerWidth <= 740) {
                //jQuery('#tab_banners_site').addClass("hidden");
                //jQuery('#tab_banners_site_select').removeClass("hidden");
                //document.getElementById('tab_banners_site_select').value = document.querySelector(
                //    '.tab_nav_affiliation_banner[aria-selected="true"]').id;


                jQuery('#tab_affiliation').addClass("hidden");
                jQuery('#tab_site_select').parent().removeClass("hidden");
                document.getElementById('tab_site_select').value = document.querySelector(
                    '.tab_nav_affiliation[aria-selected="true"]').id;
            } else {
                //jQuery('#tab_banners_site').removeClass("hidden");
                //jQuery('#tab_banners_site_select').addClass("hidden");

                jQuery('#tab_affiliation').removeClass("hidden");
                jQuery('#tab_site_select').parent().addClass("hidden");
                const target = document.getElementById(document.getElementById('tab_site_select').value);
                lws_aff_selectorMove(target, target.parentNode);
            }
        });

        jQuery('#tab_banners_site_select').on('change', function() {
            const target = document.getElementById(this.value);
            const parent = target.parentNode;
            const grandparent = parent.parentNode;

            // Remove all current selected tabs
            parent
                .querySelectorAll('.tab_nav_affiliation_banner[aria-selected="true"]')
                .forEach(function(t) {
                    t.setAttribute('aria-selected', false);
                    t.classList.remove("active")
                });

            // Set this tab as selected
            target.setAttribute('aria-selected', true);
            target.classList.add('active');

            // Hide all tab panels
            grandparent
                .querySelectorAll('.tab-pane.lws-content-page[role="tabpanel"]')
                .forEach((p) => p.setAttribute('hidden', true));

            // Show the selected panel
            grandparent.parentNode
                .querySelector(`#${target.getAttribute('aria-controls')}`)
                .removeAttribute('hidden');
        });

        jQuery('#tab_site_select').on('change', function() {
            const target = document.getElementById(this.value);
            const parent = target.parentNode;
            const grandparent = parent.parentNode;

            // Remove all current selected tabs
            parent
                .querySelectorAll('.tab_nav_affiliation[aria-selected="true"]')
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

    </html>
    <!-- End of the Widget iframe-->
<?php
    wp_die();
}

add_action("wp_ajax_load_preview_widget", "load_previewMCE");
function load_previewMCE()
{
    check_ajax_referer('load_preview_widget_nonce', '_ajax_nonce');
    // Listing des bannières
    global $wpdb;
    $banners = $wpdb->get_results("SELECT option_value FROM `" . $wpdb->options . "` WHERE option_name='_transient_lws_aff_banner'")[0]->option_value;
    $banners = json_decode($banners, true);

    $authorized_tags = '{ "address": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "a": { "href": true, "rel": true, "rev": true, "name": true, "target": true, "download": { "valueless": "y" }, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "abbr": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "acronym": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "area": { "alt": true, "coords": true, "href": true, "nohref": true, "shape": true, "target": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "article": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "aside": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "audio": { "autoplay": true, "controls": true, "loop": true, "muted": true, "preload": true, "src": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "b": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "bdo": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "big": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "blockquote": { "cite": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "br": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "button": { "disabled": true, "name": true, "type": true, "value": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "caption": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "cite": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "code": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "col": { "align": true, "char": true, "charoff": true, "span": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "colgroup": { "align": true, "char": true, "charoff": true, "span": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "del": { "datetime": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dd": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dfn": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "details": { "align": true, "open": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "div": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dl": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "dt": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "em": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "fieldset": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "figure": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "figcaption": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "font": { "color": true, "face": true, "size": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "footer": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h1": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h2": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h3": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h4": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h5": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "h6": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "header": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "hgroup": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "hr": { "align": true, "noshade": true, "size": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "i": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "img": { "alt": true, "align": true, "border": true, "height": true, "hspace": true, "loading": true, "longdesc": true, "vspace": true, "src": true, "usemap": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ins": { "datetime": true, "cite": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "kbd": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "label": { "for": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "legend": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "li": { "align": true, "value": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "main": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "map": { "name": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "mark": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "menu": { "type": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "nav": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "object": { "data": { "required": true, "value_callback": "_wp_kses_allow_pdf_objects" }, "type": { "required": true, "values": [ "application\/pdf" ] }, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "p": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "pre": { "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "q": { "cite": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rb": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rp": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rt": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "rtc": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ruby": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "s": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "samp": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "span": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "section": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "small": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "strike": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "strong": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "sub": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "summary": { "align": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "sup": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "table": { "align": true, "bgcolor": true, "border": true, "cellpadding": true, "cellspacing": true, "rules": true, "summary": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tbody": { "align": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "td": { "abbr": true, "align": true, "axis": true, "bgcolor": true, "char": true, "charoff": true, "colspan": true, "headers": true, "height": true, "nowrap": true, "rowspan": true, "scope": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "textarea": { "cols": true, "rows": true, "disabled": true, "name": true, "readonly": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tfoot": { "align": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "th": { "abbr": true, "align": true, "axis": true, "bgcolor": true, "char": true, "charoff": true, "colspan": true, "headers": true, "height": true, "nowrap": true, "rowspan": true, "scope": true, "valign": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "thead": { "align": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "title": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tr": { "align": true, "bgcolor": true, "char": true, "charoff": true, "valign": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "track": { "default": true, "kind": true, "label": true, "src": true, "srclang": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "tt": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "u": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ul": { "type": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "ol": { "start": true, "type": true, "reversed": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "var": { "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true }, "video": { "autoplay": true, "controls": true, "height": true, "loop": true, "muted": true, "playsinline": true, "poster": true, "preload": true, "src": true, "width": true, "aria-controls": true, "aria-current": true, "aria-describedby": true, "aria-details": true, "aria-expanded": true, "aria-label": true, "aria-labelledby": true, "aria-hidden": true, "class": true, "data-*": true, "dir": true, "id": true, "lang": true, "style": true, "title": true, "role": true, "xml:lang": true } }';
    $authorized_tags = json_decode($authorized_tags, true);
    $authorized_tags['script'] = array();
    ?>

    <html>
        <head>
            <title>Preview Widget Affiliation LWS</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>

        <body>
            <div>
                <?php foreach ($banners['widget'] as $k => $type) :
                    foreach ($type as $widget) : 
                        if ($widget['id'] == $_GET['id']) :
                            echo wp_kses($widget['code_source'], $authorized_tags);
                            break; 
                        endif;
                    endforeach;
                endforeach; ?>
            </div>
        </body>
    </html>
<?php
wp_die();
}

/*END AJAX*/
