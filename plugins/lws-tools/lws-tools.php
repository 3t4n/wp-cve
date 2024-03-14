<?php

/**
 * Plugin Name:       LWS Tools
 * Plugin URI:        https://www.lws.fr/
 * Description:       Optimize and modify your website's parameters
 * Version:           2.4.6
 * Author:            LWS
 * Author URI:        https://www.lws.fr
 * Tested up to:      6.4
 * Domain Path:       /languages
 * Requires PHP :     7.3
 *

 * @since             1.0
 * @package           lwstools
*/

if (! defined('ABSPATH')) {
    exit;
}

define('LWS_TK_URL', plugin_dir_url(__FILE__));
define('LWS_TK_DIR', plugin_dir_path(__FILE__));
require_once(ABSPATH . '/wp-admin/includes/class-wp-upgrader.php');
require_once(ABSPATH . '/wp-admin/includes/class-core-upgrader.php');
require_once(ABSPATH . '/wp-admin/includes/class-theme-upgrader.php');
require_once(ABSPATH . '/wp-admin/includes/class-plugin-upgrader.php');
require_once(ABSPATH . '/wp-admin/includes/class-language-pack-upgrader.php');

if (! function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

/**
 * Load translations
 */
add_action('init', 'lws_tk_traduction');
function lws_tk_traduction()
{
    load_plugin_textdomain('lws-tools', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

register_activation_hook(__FILE__, 'lws_tk_on_activation');
function lws_tk_on_activation()
{
    set_transient('lwstk_remind_me', 2592000);        
}

register_uninstall_hook(__FILE__, 'lws_tk_uninstalling_plugin');
function lws_tk_uninstalling_plugin()
{
    if (!get_option('lws_tk_keep_data_on_delete')) {
        $delete_all = array(
            'err_co',
            'sanitize_media',
            'hide_gen',
            'delete_live_writer',
            'less_revision',
            'no_h1_mce',
            'no_emote_wp',
            'no_apirest',
            'medium_large',
            'page_author_link',
            'no_rss',
            'remove_feeds_links',
            'no_comment_rss',
            'no_user_sitemap',
            'no_user_ep_rest',
            'no_self_ping',
            'remove_shortlink'
        );
        foreach ($delete_all as $list) {
            delete_option('lws_tk_' . $list);
        }
        delete_option('lws_tk_keep_data_on_delete');
        delete_option('lws_tk_reduce_revisions_number');
    }
}

/**
 * Enqueue any CSS or JS script needed
 */
add_action('admin_enqueue_scripts', 'lws_tk_scripts');
function lws_tk_scripts()
{
    if (get_current_screen()->base == ('toplevel_page_lws-tk-config')) {
        wp_enqueue_style('lws_tk-css', LWS_TK_URL . "css/lws_tk_style.css");
        wp_enqueue_style('lws_tk-dt-css', LWS_TK_URL . "DataTables/datatables.min.css");
        wp_enqueue_script('lws_tk-dt', LWS_TK_URL . "DataTables/datatables.min.js");
        wp_enqueue_style('lws_sms-Poppins', 'https://fonts.googleapis.com/css?family=Poppins');
    }
    else{
        wp_enqueue_style('lws_tk_css_out', LWS_TK_URL . "css/lws_tk_style_out.css");
        if (!get_transient('lwstk_remind_me') && !get_option('lwstk_do_not_ask_again')){
            add_action( 'admin_notices', 'lwstk_review_ad_plugin' );
        }
    }
}

function lwstk_review_ad_plugin(){
    ?>
    <script>
        function lwstk_remind_me(){
            var data = {                
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('reminder_for_tk')); ?>',        
                action: "lws_tk_reminder_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response){
                jQuery("#lwstk_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lwstk_review_notice").addClass("lws_hidden");
                }, 800);    
            });

        }

        function lwstk_do_not_bother_me(){
            var data = {                
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('donotask_for_tk')); ?>',        
                action: "lws_tk_donotask_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response){
                jQuery("#lwstk_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lwstk_review_notice").addClass("lws_hidden");
                }, 800);    
            });            
        }
    </script>

    <div class="notice notice-info is-dismissible lwstk_review_block_general" id="lwstk_review_notice">
        <div class="lwstk_circle">
            <img class="lwstk_review_block_image" src="<?php echo esc_url(plugins_url('images/plugin_lws-tools.svg', __FILE__))?>" width="40px" height="40px">
        </div>
        <div style="padding:16px">
            <h1 class="lwstk_review_block_title"> <?php esc_html_e('Thank you for using LWS Tools!', 'lws-tools'); ?></h1>
            <p class="lwstk_review_block_desc"><?php _e('Evaluate our plugin to help others optimise and secure their WordPress website!', 'lws-tools' ); ?></p>
            <a class="lwstk_button_rate_plugin" href="https://wordpress.org/support/plugin/lws-tools/reviews/" target="_blank" ><img style="margin-right: 8px;" src="<?php echo esc_url(plugins_url('images/noter.svg', __FILE__))?>" width="15px" height="15px"><?php esc_html_e('Rate', 'lws-tools'); ?></a>
            <a class="lwstk_review_button_secondary" onclick="lwstk_remind_me()"><?php esc_html_e('Remind me later', 'lws-tools'); ?></a>
            <a class="lwstk_review_button_secondary" onclick="lwstk_do_not_bother_me()"><?php esc_html_e('Do not ask again', 'lws-tools'); ?></a>
        </div>
    </div>
    <?php
}

//AJAX Reminder//
add_action("wp_ajax_lws_tk_reminder_ajax", "lws_tk_remind_me_later");
function lws_tk_remind_me_later(){
    check_ajax_referer('reminder_for_tk', '_ajax_nonce');
    if (isset($_POST['data'])){
        set_transient('lwstk_remind_me', 2592000);        
    }
}

//AJAX Reminder//
add_action("wp_ajax_lws_tk_donotask_ajax", "lws_tk_do_not_ask");
function lws_tk_do_not_ask(){
    check_ajax_referer('donotask_for_tk', '_ajax_nonce');
    if (isset($_POST['data'])){
        update_option('lwstk_do_not_ask_again', true);        
    }
}


function lws_tk_convert($size)
{
    $unit=array(__('b', 'lws-cleaner'),__('K', 'lws-cleaner'),__('M', 'lws-cleaner'),__('G', 'lws-cleaner'),__('T', 'lws-cleaner'),__('P', 'lws-cleaner'));
    if ($size <= 0) {
        return '0 ' . $unit[1];
    }
    return @round($size/pow(1024, ($i=floor(log($size, 1024)))), 2).''.$unit[$i];
}


/**
 * Create plugin menu in wp-admin
 */
add_action('admin_menu', 'lws_tk_menu_admin');
function lws_tk_menu_admin()
{
    $menu_slug = 'lws-tk-config';
    add_menu_page(__('LWS Tools - Overview', 'lws-tools'), 'LWS Tools', 'manage_options', $menu_slug, 'lws_tk_create_page', LWS_TK_URL . 'images/plugin_lws_tools.svg');
}

/**
 * Generate the setting page in admin
 */
function lws_tk_create_page()
{
    global $wpdb;
    include_once __DIR__ . '/view/change_htaccess.php';


    //NOTIF//
    $plugins_update = array();
    $themes_update = array();
    $unused_plugins = array();
    $unused_themes = array();
    
    $actual_version = get_bloginfo('version');
    $up_to_date = true;
    $translations_ready = false;
    $cert_invalid = false;
    
    $all_plugins = get_plugins();
    $all_themes = wp_get_themes();
    $my_theme = wp_get_theme();

    
    //Number of themes/plugins
    $count_themes = count($all_themes);
    $count_inactive_themes = $count_themes - 1;
    $count_plugins = count($all_plugins);

    //SSL Expiration & Issuer @sameer|Reading SSL certificates in PHP
    if (!$lws_tk_ssl_cert = get_transient('lws_tk_ssl_cert')) {
        $errno = 0;
        $errstr = '';
        $timeout = 30;
        $ssl_info = stream_context_create(array("ssl" => array("capture_peer_cert" => true)));
    
        $stream = stream_socket_client("ssl://" . parse_url(site_url(), PHP_URL_HOST) . ":443", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $ssl_info);
    
        if ($stream) {
            $cert_resource = stream_context_get_params($stream);
            $certificate = $cert_resource['options']['ssl']['peer_certificate'];
            $certinfo = openssl_x509_parse($certificate);
            fclose($stream);
            $lws_tk_ssl_cert = $certinfo;
            set_transient('lws_tk_ssl_cert', $certinfo, 24 * HOUR_IN_SECONDS);
        }
    }
    
    if ($lws_tk_ssl_cert['validTo_time_t'] - time() < 0) {
        $cert_invalid = true;
    }
    $expiration_ssl = date("m/j/Y", $lws_tk_ssl_cert['validTo_time_t']);
    $issued_by = $lws_tk_ssl_cert['issuer']['CN'];
    $up_to_date = (wp_get_update_data()['counts']['wordpress']);
    
    
    if (!empty(wp_get_translation_updates())) {
        $translations_ready = true;
    }

    //Check if DB prefix is different from default
    $db_prefix = $wpdb->prefix == "wp_" ? true /* Default */ : false /* Not Default */ ;

    if (isset($_POST['lws_tk_update_prefix'])) {
        if ( ! isset( $_POST['nonce_updating_prefix_nonce'] ) || ! wp_verify_nonce( $_POST['nonce_updating_prefix_nonce'], 'lws_tk_update_prefix' ) ) {
            wp_die();
        }
        $config = ABSPATH . 'wp-config.php';
        $lines = file($config);//file in to an array
        $config_file = '';
        $new_prefix = 'wp';
        
        //Create new prefix
        $characters = array_merge(range('a', 'z'), range('0', '9'));
        $length = rand(2, 4);
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, count($characters)-1);
            $new_prefix .= $characters[$rand];
        }
        $new_prefix .='_';

        //Modify wp-config to change said prefix
        foreach ($lines as $line) {
            if (strpos($line, '$table_prefix') === 0) {
                $line = '$table_prefix = ' . '"' . $new_prefix . '";' . "\n\r";
            }
            $config_file .= $line;
        }

        //Update each table to reflect new prefix
        if ($wpdb->get_results("SHOW TABLES LIKE '" . $wpdb->prefix . "%'", ARRAY_N) !== null ) {
            foreach ($wpdb->get_results("SHOW TABLES LIKE '" . $wpdb->prefix . "%'", ARRAY_N) as $table) {
                $new_name = substr_replace($table[0], $new_prefix, 0, strlen($wpdb->prefix));
                $wpdb->query("RENAME TABLE `{$table[0]}` TO `{$new_name}`");
            }
        }

        //Update specific options with new prefix
        $wpdb->query("UPDATE {$new_prefix}options SET option_name='{$new_prefix}user_roles' WHERE option_name='{$wpdb->prefix}user_roles';");

        $wpdb->query("UPDATE {$new_prefix}usermeta SET meta_key = 
        CONCAT(
            REPLACE(LEFT(meta_key, " . strlen($wpdb->prefix) . "), '{$wpdb->prefix}', '{$new_prefix}'),
            SUBSTR(meta_key, " . (strlen($wpdb->prefix) + 1) . ")
        )  WHERE
            meta_key in (
                '{$wpdb->prefix}capabilities', '{$wpdb->prefix}user_level',
                '{$wpdb->prefix}user-settings', '{$wpdb->prefix}user-settings-time',
                '{$wpdb->prefix}dashboard_quick_press_last_post_id'
                )");

        //Save the new wp-config
        if (! empty($config_file)) {
            file_put_contents($config, $config_file);
        }

        header("Refresh:0;");

        //Check if DB prefix is different from default
        $db_prefix = $new_prefix == "wp_" ? true /* Default */ : false /* Not Default */ ;
    }


    //Get every plugins in need of an update
    if (get_site_transient('update_plugins') && get_site_transient('update_plugins')->response !== null) {
        foreach (get_site_transient('update_plugins')->response as $plugin) {
            $plugin_data = get_plugin_data($dir = plugin_dir_path(__DIR__). "/" . $plugin->plugin);
            $plugins_update[] = array('name' => $plugin_data['Name'], 'version' => $plugin_data['Version'], 'new_version' => $plugin->new_version, 'package' => $plugin->plugin, 'slug' => $plugin->slug);
        }
    }
    
    //Get every themes in need of an update
    if (get_site_transient('update_themes') && get_site_transient('update_themes')->response !== null) {
        foreach (get_site_transient('update_themes')->response as $theme) {
            $theme_data = wp_get_theme($theme['theme']);
            $themes_update[] = array('name' => $theme_data['Name'], 'version' => $theme_data['Version'], 'new_version' => $theme['new_version'], 'package' => $theme['package'], 'slug' => $theme['theme']);
        }
    }
    
    //Get every unused plugins and the number of used plugins
    $active_plugins = 0;
    $inactive_plugins = 0;
    foreach ($all_plugins as $slug => $plugin) {
        if (!is_plugin_active($slug) && !is_plugin_active_for_network($slug)) {
            $unused_plugins[] = array('name' => $plugin['Name'], 'author' => $plugin['AuthorName'], 'version' => $plugin['Version'], 'slug' => $plugin['TextDomain'], 'package' => $slug);
            $inactive_plugins += 1;
        } else {
            $active_plugins += 1;
        }
    }
    
    //Get every unused themes
    foreach ($all_themes as $slug => $theme) {
        if ($theme['Name'] != $my_theme->name) {
            $unused_themes[] = array('name' => $theme['Name'], 'author' => $theme['Author'], 'version' => $theme['Version'], 'slug' => $slug);
        }
    }
        
    //SERVER
    
    $environment = sanitize_text_field($_SERVER['SERVER_SOFTWARE']);
    $user_ip = sanitize_text_field($_SERVER['HTTP_X_REAL_IP']);
    $server_port =sanitize_text_field($_SERVER['SERVER_PORT']);
    
    if (sanitize_text_field($_SERVER['HTTPS']) == 'on') {
        $is_https = __('Yes', 'lws-tools');
    } else {
        $is_https = __('No', 'lws-tools');
    }
    
    $server_name = sanitize_text_field($_SERVER['SERVER_NAME']);
    $server_ip = sanitize_text_field($_SERVER['SERVER_ADDR']);
    $server_protocol = sanitize_text_field($_SERVER['SERVER_PROTOCOL']);

    $php_ver = phpversion();
    $is_debug = WP_DEBUG;
    if ($is_debug) {
        $is_debug = __('Yes', 'lws-tools');
    } else {
        $is_debug = __('No', 'lws-tools');
    }
    
    $fopen = ini_get("allow_url_fopen");
    if ($fopen) {
        $fopen = __('Yes', 'lws-tools');
    } else {
        $fopen = __('No', 'lws-tools');
    }
    
    $timezone = ini_get("date.timezone");
    $charset = ini_get("default_charset");
    $can_file_upload = ini_get("file_uploads");
    
    if ($can_file_upload) {
        $can_file_upload = __('Yes', 'lws-tools');
    } else {
        $can_file_upload = __('No', 'lws-tools');
    }
    
    $max_exec_time = ini_get("max_execution_time");
    $max_file_upload = ini_get("max_file_uploads");
    $max_input_vars = ini_get("max_input_vars");
    $memory_limit = ini_get("memory_limit");
    $post_max_size = ini_get("post_max_size");
    $upload_max_filesize = ini_get("upload_max_filesize");
    $php_memory_usage = lws_tk_convert(memory_get_usage());
        
    //OPTIMISATION
    $opti_list = array(
        'delete_live_writer' =>
        array(__('Delete Windows Live Writer manifest', 'lws-tools'), __('Delete the line WordPress add in the header of your website. Useless if you do not use Windows Live Writer.', 'lws-tools'), true),
        'less_revision' =>
        array(__('Reduce the amount of available revisions to ', 'lws-tools'), __('WordPress automatically save posts and pages every 2 minutes. Do not completely deactivate those, you should reduce the maximum amount of revisions so as to not obstruct the database uselessly.', 'lws-tools'), true),
        'page_author_link' =>
        array(__('Remove author\'s pages and their links', 'lws-tools'), __('If you do not wish to possess individual pages for authors, delete those here. It also help hiding their login informations.', 'lws-tools'), true),
        'no_self_ping' =>
        array(__('Prevent WordPress from pingbacking yourself in your posts', 'lws-tools'), __('A Pingback is a link created automatically between two contents. It generally helps with SEO but also generate for contents on the same website, which can be bothersome.', 'lws-tools'), false),
        'no_emote_wp' =>
        array(__('Use visitor\'s emotes instead of WordPress', 'lws-tools'), __('Once activated, emotes in your posts and pages will use the vistor\'s emote instead of loading WordPress, helping with performances.', 'lws-tools'), false),
        'no_h1_mce' =>
        array(__('Remove Heading 1 from TinyMCE', 'lws-tools'), __('By deactivating H1 tags, you remove the possibility for content creators on your website to put multiple Main Title on your website. You must only have one for good SEO.', 'lws-tools'), true),
        'remove_shortlink' =>
        array(__('Remove shortlinks from the page', 'lws-tools'), __('A shortlink is a shortened link of your post or page. It then exists in the source code of your page. you can gain a small performance boost by deactivating that option if you do not use it.', 'lws-tools'), true),
        'medium_large' =>
        array(__('Add back hidden \'Medium Large\' image size ', 'lws-tools'), __('Add a new size for images (768 pixels), already existing but hidden, when inserting medias in a post', 'lws-tools'), false),
        'sanitize_media' =>
        array(__('Provide a new way of sanitizing uploaded media name', 'lws-tools'), __('Medias names are cleaned even more thoroughly than normal for better names', 'lws-tools'), false),
        'no_rss' =>
        array(__('Remove RSS feeds', 'lws-tools'), __('RSS feeds allows visitors to subscribe to your posts and power some apps. Remove RSS if you do not use the blogging part of WordPress or do not want to manage it.', 'lws-tools'), false),
        'remove_feeds_links' =>
        array(__('Remove RSS feeds links', 'lws-tools'), __('Remove URLs to manage RSS feeds', 'lws-tools'), false),
        'no_comment_rss' =>
        array(__('Remove comments RSS feeds', 'lws-tools'), __('Remove feeds related to comments', 'lws-tools'), false),
    );

    $secu_list = array(
        'err_co' =>
        array(__('Hide connexion errors on wp-login', 'lws-tools'), __('Hide errors when a person try to connect to your WordPress (notably, hide your username)', 'lws-tools'), true),
        'hide_gen' =>
        array(__('Hide "WordPress Version" meta on pages', 'lws-tools'), __('WordPress version is shown in multiple places.  Security breachs from older versions can be used by hackers to hack your website. You should hide your version.', 'lws-tools'), true),
        'no_apirest' =>
        array(__('Deactivate REST API', 'lws-tools'), __('WordPress latest breachs focus on REST API, deactivate-it if you have no use for it.', 'lws-tools'), false),
        'no_user_sitemap' =>
        array(__('Hide users pages from WordPress sitemap', 'lws-tools'), __('Hide users pages of your WordPress from the plan of your site.', 'lws-tools'), false),
        'no_user_ep_rest' =>
        array(__('Hide users endpoints from REST API', 'lws-tools'), __('Deactivate users endpoints, hiding the users list from disconnected users.', 'lws-tools'), false),
    );

    $wp_manager = array(
        'autoindex' =>
        //Vous pouvez activer cette option pour sécuriser vos données sensibles (fichiers personnels, listing d'adresses mail, mots de passe, sauvegarde de bases de données,...). Cela empêchera l'accès à vos fichiers dans un dossier sans fichier index.
        array(__('Deactivate files listing for directories with no index', 'lws-tools'), __('You can activate this option to secure your sensible datas (personnal files, email addresses listing, password, database backups,...). It will prevent acces to files in a directory with no index file.', 'lws-tools'), true),
        'authorid' =>
        //En cachant votre identifiant, un pirate aura plus de difficulté à se connecter à votre wordpress.
        array(__('Hide author\'s username', 'lws-tools'), __('By hiding your login, a hacker will have a harder time connection to your WordPress.', 'lws-tools'), true),
        'comments' =>
        // En activant cette option, vous bloquerez les tentatives de spams et les abus de commentaires envoyés par les robots et les mauvaises requêtes
        array(__('Block access to comment pages to browser without UserAgent or Referer', 'lws-tools'), __('By activating this option, you will block spam attempts and comments abuse sent by bots and bad requests', 'lws-tools'), false),
        'sqlfiles' =>
        // Il est nécessaire de bloquer le téléchargement de vos fichiers .sql pour éviter toute attaque malveillante. Vos fichiers peuvent contenir des informations sensibles.
        array(__('Block downloading of SQL files', 'lws-tools'), __('It is necessary to block downloading of your SQL files to prevent all malicious attacks. Your files can contains sensible informations.', 'lws-tools'), false),
        'readmelicense' =>
        // Augmentez votre sécurité en bloquant l’accès à vos fichiers situés à la racine de votre site et/ou plugin.
        //Ces fichiers peuvent contenir des informations exploitables par les pirates pour trouver des failles.
        array(__('Block access to readme and license files', 'lws-tools'), __('Improve security by blocking access to files situate at the root of your website/plugin. Those files can contains informations usable by hackers to find breaches.', 'lws-tools'), false),
        'xmlrpc' =>
        // Il est nécessaire de bloquer l’accès au fichier xml-rpc s’il n’est pas utilisé car celui-ci permet de se connecter à distance à Wordpress et peut être utilisé à des fins malveillantes.
        array(__('Block access to xmlrpc.php', 'lws-tools'), __('It is necessary to block access to xmlrpc if it is not used as it allows to connect remotely to WordPress and could be used for malicious intent.', 'lws-tools'), false),
        'phpuploads' =>
        // Cette option vous permet d'interdire l'exécution de fichier PHP dans ce dossier, qui n'est pas prévu pour cela.
        array(__('Forbid PHP execution in the uploads directory', 'lws-tools'), __('This option lets you forbid execution of PHP in files in this directory, which is not made for that.', 'lws-tools'), false),
    );
    
    if (isset($_POST['lws_tk_optimisations'])) {
        if ( ! isset( $_POST['nonce_opti_listing_nonce'] ) || ! wp_verify_nonce( $_POST['nonce_opti_listing_nonce'], 'lws_tk_optimisations' ) ) {
            wp_die();
        }
        //Sanitize array
        $checkboxes = isset($_POST['lws_tk_optimisation_list']) ? (array) $_POST['lws_tk_optimisation_list'] : array();
        $checkboxes = array_map('sanitize_text_field', $checkboxes);
        //Update checkboxes
        foreach ($opti_list as $key => $list) {
            if (in_array($key, $checkboxes)) {
                update_option('lws_tk_' . $key, 'yes');
                if ($key == 'less_revision') {
                    $value = sanitize_text_field($_POST['less_revision_revision_number']);
                    update_option('lws_tk_reduce_revisions_number', $value);
                }
            } else {
                delete_option('lws_tk_' . $key);
                if ($key == 'less_revision') {
                    delete_option('lws_tk_reduce_revisions_number');
                }
            }
        }

        $change_tab = 'nav-optimisation';
    }

    if (isset($_POST['lws_tk_security'])) {
        if ( ! isset( $_POST['nonce_security_listing_nonce'] ) || ! wp_verify_nonce( $_POST['nonce_security_listing_nonce'], 'lws_tk_security' ) ) {
            wp_die();
        }
        //Sanitize array
        $checkboxes = isset($_POST['lws_tk_security_list']) ? (array) $_POST['lws_tk_security_list'] : array();
        $checkboxes = array_map('sanitize_text_field', $checkboxes);
        //Update checkboxes
        foreach ($secu_list as $key => $list) {
            if (in_array($key, $checkboxes)) {
                update_option('lws_tk_' . $key, 'yes');
            } else {
                delete_option('lws_tk_' . $key);
            }
        }
        if (isset($_SERVER['lwsapitoken']) && explode('/', getcwd())[1] == 'htdocs') {
            $wp_manager_checkboxes = isset($_POST['lws_tk_wpmanager_list']) ? (array) $_POST['lws_tk_wpmanager_list'] : array();
            $wp_manager_checkboxes = array_map('sanitize_text_field', $wp_manager_checkboxes);
            foreach ($wp_manager as $key => $list) {
                if (in_array($key, $wp_manager_checkboxes)) {
                    $opts[$key] = true;
                } else {
                    $opts[$key] = false;
                }
            }
            include_once __DIR__ . '/view/update_htaccess.php';
        }

        $change_tab = 'nav-security';
    }
        
    $results = $wpdb->get_results("SHOW TABLE STATUS");
    $db_size = 0;
    $list_tables = array();
    foreach ($results as $size) {
        $db_size += $size->Data_length + $size->Index_length;
        $list_tables[] = array('name' => $size->Name, 'size' => lws_tk_convert($size->Data_length + $size->Index_length), 'charset' => $size->Collation, 'created' => $size->Create_time, 'engine' => $size->Engine);
    }

    $table_number = count($list_tables);
    $db_size = (lws_tk_convert($db_size));

    //TOOLS
    $revisions_amount = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "posts` WHERE post_type='revision'");
    $revisions_amount = count($revisions_amount);
    
    $trashed_comments = count($wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "comments` WHERE comment_approved='trash'"));
    $spam_comments = count($wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "comments` WHERE comment_approved='spam'"));

    if (isset($_POST['lws_tk_reset_plugin'])) {
        if ( ! isset( $_POST['nonce_security_reset_nonce'] ) || ! wp_verify_nonce( $_POST['nonce_security_reset_nonce'], 'lws_tk_reset_plugin' ) ) {
            wp_die();
        }
        $delete_all = array(
            'err_co',
            'sanitize_media',
            'hide_gen',
            'delete_live_writer',
            'less_revision',
            'no_h1_mce',
            'no_emote_wp',
            'no_apirest',
            'medium_large',
            'page_author_link',
            'no_rss',
            'remove_feeds_links',
            'no_comment_rss',
            'no_user_sitemap',
            'no_user_ep_rest',
            'no_self_ping',
            'remove_shortlink'
        );
        foreach ($delete_all as $key => $list) {
            delete_option('lws_tk_' . $list);
        }
    }
    include __DIR__ . '/view/tabs.php';
}

///OPTIMISATIONS///

add_action('init', 'lws_tk_optimisations');
function lws_tk_optimisations()
{
    /**
     * Sanitize more the name of media uploaded
     */
    if (get_option('lws_tk_sanitize_media')) {
        add_filter('sanitize_file_name', 'lws_tk_sanitize_file_name');
    }
    
    /**
     * Deactivate Windows Live Writer Manifest Link
     */
    if (get_option('lws_tk_delete_live_writer')) {
        remove_action('wp_head', 'wlwmanifest_link');
    }
    
    /**
     * Remove any errors shown in the login page
     */
    if (get_option('lws_tk_err_co')) {
        add_filter('login_errors', function ($error) {
            return $error = esc_html__('Failed to connect', 'lws-tools');
        });
    }
    
    /**
     * Remove the element indicating the use of WordPress
     */
    if (get_option('lws_tk_hide_gen')) {
        remove_action('wp_head', 'wp_generator');
    }

    /**
     * Reduce number of revisions [Based on option _number - 1]
     */
    if (get_option('lws_tk_less_revision')) {
        add_filter('wp_revisions_to_keep', 'lws_tk_reduce_revisions', 10, 2);
        function lws_tk_reduce_revisions($num, $post)
        {
            return $num = get_option('lws_tk_reduce_revisions_number');
        }
    }
    
    /**
     * Remove heading1 for TMCE
     */
    if (get_option('lws_tk_no_h1_mce')) {
        add_filter('tiny_mce_before_init', 'lws_tk_remove_h1_tmce');
        function lws_tk_remove_h1_tmce($block)
        {
            $block['block_formats'] = "Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre";
            return $block;
        }
    }
    
    /**
     * Disable the emoji's
     */
    if (get_option('lws_tk_no_emote_wp')) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', 'lws_tk_disable_emojis_tinymce');
        add_filter('wp_resource_hints', 'lws_tk_disable_emojis_remove_dns_prefetch', 10, 2);
    }
    
    /**
     * Remove Author Page and its link
     */
    if (get_option('lws_tk_page_author_link')) {
        add_action('template_redirect', function () {
            if (is_author()) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
            }
        });
        add_filter('author_link', function () {
            return get_option('home');
        });
    }
    
    /**
     * Add one more size type for images
     */
    if (get_option('lws_tk_medium_large')) {
        add_filter('image_size_names_choose', function ($size_names) {
            $new_sizes = array(
                'medium_large' => esc_html__('Medium-Large', 'lws-tools'),
            );
            return array_merge($size_names, $new_sizes);
        });
    }
    
    /**
     * Disable REST API in its entirety
     */
    if (get_option('lws_tk_no_apirest')) {
        add_filter('rest_authentication_errors', function ($access) {
            return new WP_Error(
                'rest_disabled',
                esc_html__('The WordPress REST API has been disabled.', 'lws-tools'),
                array( 'status' => rest_authorization_required_code() )
            );
        });
    }

    /**
     * Disable only the endpoints for users and users/id when disconnected
     */
    if (get_option('lws_tk_no_user_ep_rest')) {
        if (!is_user_logged_in()) {
            add_filter('rest_endpoints', 'lws_tk_disable_custom_rest_endpoints');
        }
    }
    
    /**
     * Disable all RSS Feeds
     */
    if (get_option('lws_tk_no_rss')) {
        add_action('do_feed', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
        add_action('do_feed_rdf', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
        add_action('do_feed_rss', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
        add_action('do_feed_rss2', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
        add_action('do_feed_atom', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
        add_action('do_feed_rss2_comments', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
        add_action('do_feed_atom_comments', function () {
            wp_die(esc_html__('No feed available', 'lws-tools'));
        }, 1);
    }
        
    /**
     * Disable only Comments RSS Feed
     */
    if (get_option('lws_tk_no_comment_rss')) {
        add_action('do_feed', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
        add_action('do_feed_rdf', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
        add_action('do_feed_rss', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
        add_action('do_feed_rss2', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
        add_action('do_feed_atom', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
        add_action('do_feed_rss2_comments', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
        add_action('do_feed_atom_comments', function ($comments) {
            if ($comments) {
                wp_die(esc_html__('No feed available', 'lws-tools'));
            }
        }, 1);
    }
}

/**
 * Remove links to users in the sitemap
 */
if (get_option('lws_tk_no_user_sitemap')) {
    add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
        return ($name == 'users') ? false : $provider;
    }, 10, 2);
}

/**
 * Remove password strength check.
 */
if (get_option('lws_tk_remove_password_strength_meter')) {
    add_action('admin_enqueue_scripts', 'lws_tk_remove_password_strength_meter');
}
function lws_tk_remove_password_strength_meter($hook)
{
    if ($hook != "user-new.php") {
        return;
    }
    wp_dequeue_script('wc-password-strength-meter');
    wp_dequeue_script('user-profile');
    wp_dequeue_script('password-strength-meter');
    wp_deregister_script('user-profile');
    $suffix = SCRIPT_DEBUG ? '' : '.min';
    $admin = explode('/', admin_url('', 'relative'));
    end($admin);
    $admin = prev($admin);
    wp_enqueue_script('user-profile', '/' . $admin . "/js/user-profile$suffix.js", array( 'jquery', 'wp-util' ), false, 1);
}

/**
 * Remove self-pingbacks in posts
 */
if (get_option('kws_tk_no_self_ping')) {
    add_action('pre_ping', 'lws_tk_no_self_ping');
}
function lws_tk_no_self_ping(&$links)
{
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}

/**
 * Remove shorlink from head
 */
if (get_option('lws_tk_remove_shortlink')) {
    add_filter('after_setup_theme', 'lws_tk_remove_shortlink');
}
function lws_tk_remove_shortlink()
{
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
}

if (get_option('lws_tk_remove_feeds_links')) {
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
}


/**
 * Produces cleaner filenames for uploads
 * @wpartisan
 */
function lws_tk_sanitize_file_name($filename)
{
    $sanitized_filename = remove_accents($filename);
 
    // Standard replacements
    $invalid = array(
        ' '   => '-',
        '%20' => '-',
        '_'   => '-',
    );
    
    //Replace invalid characters defined above in the name by '-'
    $sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $sanitized_filename);
 
    $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except '.'
    $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last '.'
    $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
    $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
    $sanitized_filename = strtolower($sanitized_filename); // Lowercase
 
    return $sanitized_filename;
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 */
function lws_tk_disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array( 'wpemoji' ));
    } else {
        return array();
    }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 */
function lws_tk_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

        $urls = array_diff($urls, array( $emoji_svg_url ));
    }

    return $urls;
}

/**
 * Disable access to the users and users/id endpoints of RESTAPI
 */
function lws_tk_disable_custom_rest_endpoints($endpoints)
{
    $routes = array( '/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)' );
    foreach ($routes as $route) {
        if (!empty($endpoints[ $route ])) {
            foreach ($endpoints[ $route ] as $i => $handlers) {
                if (is_array($handlers) && isset($handlers['methods']) &&
                    'GET' === $handlers['methods']) {
                    unset($endpoints[ $route ][ $i ]);
                }
            }
        }
    }

    return $endpoints;
}

///END OPTIMISATIONS///

////AJAX////

// AJAX PART FOR THE DOWNLOAD //
/*AJAX DOWNLOAD AND ACTIVATE PLUGINS*/

//AJAX DL Plugin//
add_action("wp_ajax_lws_tk_downloadPlugin", "wp_ajax_install_plugin");
//

//AJAX Activate Plugin//
add_action("wp_ajax_lws_tk_activatePlugin", "lws_tools_activate_plugin");
function lws_tools_activate_plugin()
{
    check_ajax_referer('tools_activate_plugin', '_ajax_nonce');

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

/*END AJAX*/

//AJAX Plugins//
add_action("wp_ajax_lwstools_updateAllPlugin", "lws_tk_update_all_plugin");
function lws_tk_update_all_plugin()
{
    check_ajax_referer('tools_update_every_plugin', '_ajax_nonce');
    $pu = new Plugin_Upgrader();
    $update_all = array();
    foreach (get_site_transient('update_plugins')->response as $plugin) {
        $update_all[] = $plugin->plugin;
    }
    $pu->bulk_upgrade($update_all);
    wp_die();
}

add_action("wp_ajax_lwstools_updatePlugin", "lws_tk_update_plugin");
function lws_tk_update_plugin()
{
    check_ajax_referer('tools_update_one_plugin', '_ajax_nonce');
    $pu = new Plugin_Upgrader();
    $plugin_package = sanitize_text_field($_POST['lws_tk_update_plugin_specific']);
    $pu->upgrade($plugin_package);
    $pu->plugin_info();
    wp_die();
}
//

//AJAX Themes//
add_action("wp_ajax_lwstools_updateAllTheme", "lws_tk_update_all_theme");
function lws_tk_update_all_theme()
{
    check_ajax_referer('tools_update_all_theme', '_ajax_nonce');
    $tu = new Theme_Upgrader();
    $update_all = array();
    foreach (get_site_transient('update_themes')->response as $theme) {
        $update_all[] = $theme['theme'];
    }
    $tu->bulk_upgrade($update_all);
    wp_die();
}

add_action("wp_ajax_lwstools_updateTheme", "lws_tk_update_theme");
function lws_tk_update_theme()
{
    check_ajax_referer('tools_update_one_theme', '_ajax_nonce');
    $tu = new Theme_Upgrader();
    $theme_package = sanitize_text_field($_POST['lws_tk_update_theme_specific']);
    $tu->upgrade($theme_package);
    wp_die();
}
//

//AJAX Unused Plugins//
add_action("wp_ajax_lwstools_deleteAllPlugin", "lws_tk_delete_all_plugin");
function lws_tk_delete_all_plugin()
{
    check_ajax_referer('tools_delete_all_plugin', '_ajax_nonce');
    $to_delete = array();
    foreach (get_plugins() as $slug => $plugin) {
        if (!is_plugin_active($slug) && !is_plugin_active_for_network($slug)) {
            $to_delete[] = $slug;
        }
    }
    delete_plugins($to_delete);
    wp_die();
}

add_action("wp_ajax_lwstools_deletePlugin", "lws_tk_delete_plugin");
function lws_tk_delete_plugin()
{
    check_ajax_referer('tools_delete_one_plugin', '_ajax_nonce');
    $plugin_package = sanitize_text_field($_POST['lws_tk_delete_plugin_specific']);
    delete_plugins([$plugin_package]);
    wp_die();
}
//

//AJAX Unused Themes//
add_action("wp_ajax_lwstools_deleteAllTheme", "lws_tk_delete_all_theme");
function lws_tk_delete_all_theme()
{
    check_ajax_referer('tools_delete_all_theme', '_ajax_nonce');
    foreach (wp_get_themes() as $slug => $theme) {
        if ($theme['Name'] != wp_get_theme()->name) {
            delete_theme($slug);
        }
    }
    wp_die();
}

add_action("wp_ajax_lwstools_deleteTheme", "lws_tk_delete_theme");
function lws_tk_delete_theme()
{
    check_ajax_referer('tools_delete_one_theme', '_ajax_nonce');
    $theme_package = sanitize_text_field($_POST['lws_tk_delete_theme_specific']);
    delete_theme($theme_package);
    wp_die();
}
//

//AJAX DL Plugin//
add_action("wp_ajax_lwstools_downloadPlugin", "wp_ajax_install_plugin");
//

//AJAX Update Trads//
add_action("wp_ajax_lwstools_updateTrads", "lws_tk_update_trads");
function lws_tk_update_trads()
{
    check_ajax_referer('tools_upgrade_tools_trad', '_ajax_nonce');
    $lp = new Language_Pack_Upgrader();
    $lp->bulk_upgrade();
    wp_die();
}
//

//AJAX Repair DB//
add_action("wp_ajax_lwstools_repairdb", "lws_tk_repairdb");
function lws_tk_repairdb()
{
    check_ajax_referer('tools_repair_only_db', '_ajax_nonce');
    $config_page = ABSPATH . 'wp-config.php';
    $config_page_content = file($config_page);
    foreach ($config_page_content as $content) {
        if (preg_match('/^define\(\s*\'([A-Z_]+)\',(.*)\)/', $content, $match)) {
            if ('WP_ALLOW_REPAIR' === $match[1]) {
                echo esc_url(get_site_url() . "/wp-admin/maint/repair.php?repair=1");
                wp_die();
            }
        }
    }
    array_shift($config_page_content);
    array_unshift($config_page_content, "<?php\r\ndefine('WP_ALLOW_REPAIR', true);\r\n");
    $file = @fopen($config_page, 'w');
    foreach ($config_page_content as $line) {
        @fwrite($file, $line);
    }
    @fclose($file);
    echo esc_url(get_site_url() . "/wp-admin/maint/repair.php?repair=1");
    wp_die();
    /*@exec("wp config set WP_ALLOW_REPAIR true --raw");
    echo esc_url(get_site_url() . "/wp-admin/maint/repair.php?repair=1");
    wp_die();*/
}
//

//AJAX Opti DB//
add_action("wp_ajax_lwstools_optidb", "lws_tk_optidb");
function lws_tk_optidb()
{
    check_ajax_referer('tools_optimize_all_db', '_ajax_nonce');
    $config_page = ABSPATH . 'wp-config.php';
    $config_page_content = file($config_page);
    foreach ($config_page_content as $content) {
        if (preg_match('/^define\(\s*\'([A-Z_]+)\',(.*)\)/', $content, $match)) {
            if ('WP_ALLOW_REPAIR' === $match[1]) {
                echo esc_url(get_site_url() . "/wp-admin/maint/repair.php?repair=2");
                wp_die();
            }
        }
    }
    array_shift($config_page_content);
    array_unshift($config_page_content, "<?php\r\ndefine('WP_ALLOW_REPAIR', true);\r\n");
    $file = @fopen($config_page, 'w');
    foreach ($config_page_content as $line) {
        @fwrite($file, $line);
    }
    @fclose($file);
    echo esc_url(get_site_url() . "/wp-admin/maint/repair.php?repair=2");
    wp_die();
    /*@exec("wp config set WP_ALLOW_REPAIR true --raw");
    echo esc_url(get_site_url() . "/wp-admin/maint/repair.php?repair=2");
    wp_die();*/
}
//

//AJAX Deactivate Repair DB//
add_action("wp_ajax_lwstools_deactivate_repair", "lws_tk_deactivate_repairdb");
function lws_tk_deactivate_repairdb()
{
    check_ajax_referer('tools_deactivate_repair_option', '_ajax_nonce');
    $config_page = ABSPATH . 'wp-config.php';
    $config_page_content = file($config_page);
    foreach ($config_page_content as $key => $content) {
        if (preg_match('/^define\(\s*\'([A-Z_]+)\',(.*)\)/', $content, $match)) {
            if ('WP_ALLOW_REPAIR' === $match[1]) {
                $config_page_content[$key] = "";
                $file = @fopen($config_page, 'w');
                foreach ($config_page_content as $line) {
                    @fwrite($file, $line);
                }
                @fclose($file);
                wp_die();
            }
        }
    }
    wp_die();
    /*@exec("wp config delete WP_ALLOW_REPAIR");
    wp_die();*/
}
//

//AJAX Disconnect everyone but user//
add_action("wp_ajax_lwstools_disconnectall", "lws_tk_disconnect_all");
function lws_tk_disconnect_all()
{
    check_ajax_referer('disconnect_all_and_everyone', '_ajax_nonce');
    foreach (get_users(array( 'fields' => array( 'ID' ))) as $user) {
        if ($user->ID == get_current_user_id()) {
            $sessions = WP_Session_Tokens::get_instance(get_current_user_id());
            $sessions->destroy_others(wp_get_session_token());
        } else {
            $sessions = WP_Session_Tokens::get_instance($user->ID);
            $sessions->destroy_all();
        }
    }
    wp_die();
}
//

//AJAX Delete revisions older than $days//
add_action("wp_ajax_lwstools_delete_revisions", "lws_tk_delete_revision");
function lws_tk_delete_revision()
{
    check_ajax_referer('delete_all_revisions', '_ajax_nonce');
    global $wpdb;
    $days = sanitize_text_field($_POST['lws_tk_days_revisions']);
    $wpdb->get_results("DELETE FROM `" . $wpdb->prefix . "posts` WHERE post_type='revision' AND post_modified < '" . date("Y-m-d H:i:s", time() - (24*60*60*$days)) . "';");
    wp_die();
}
//

//AJAX Delete Trashed comments//
add_action("wp_ajax_lwstools_delete_trash_comments", "lws_tk_delete_trash_comments");
function lws_tk_delete_trash_comments()
{
    check_ajax_referer('delete_all_trash_comments', '_ajax_nonce');
    global $wpdb;
    $wpdb->get_results("DELETE FROM `" . $wpdb->prefix . "comments` WHERE comment_approved='trash'");
    wp_die();
}
//

//AJAX Delete Trashed comments//
add_action("wp_ajax_lwstools_delete_spam_comments", "lws_tk_delete_spam_comments");
function lws_tk_delete_spam_comments()
{
    check_ajax_referer('delete_all_spam_comms', '_ajax_nonce');
    global $wpdb;
    $wpdb->get_results("DELETE FROM `" . $wpdb->prefix . "comments` WHERE comment_approved='spam'");
    wp_die();
}
//

//AJAX Delete old transients//
add_action("wp_ajax_lwstools_delete_transients", "lws_tk_delete_old_transients");
function lws_tk_delete_old_transients()
{
    check_ajax_referer('delete_all_transients', '_ajax_nonce');
    delete_expired_transients();
    wp_die();
}
//

//AJAX Keep Config even after delete//
add_action("wp_ajax_lwstools_keep_changes", "lws_tk_keep_changes");
function lws_tk_keep_changes()
{
    check_ajax_referer('keep_on_delete_change', '_ajax_nonce');
    $is_checked = sanitize_text_field($_POST['state']);
    $is_checked == 'true' ? update_option('lws_tk_keep_data_on_delete', true) : delete_option('lws_tk_keep_data_on_delete');
    wp_die();
}
//

///END AJAX///
