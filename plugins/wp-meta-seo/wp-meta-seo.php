<?php

/**
 * Plugin Name: WP Meta SEO
 * Plugin URI: http://www.joomunited.com/wordpress-products/wp-meta-seo
 * Description: WP Meta SEO is a plugin for WordPress to fill meta for content, images and main SEO info in a single view.
 * Version: 4.5.11
 * Text Domain: wp-meta-seo
 * Domain Path: /languages
 * Author: JoomUnited
 * Author URI: http://www.joomunited.com
 * License: GPL2
 */
// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if (version_compare(PHP_VERSION, '5.6', '<')) {
    if (!function_exists('wpmsDisablePlugin')) {
        /**
         * Deactivate plugin
         *
         * @return void
         */
        function wpmsDisablePlugin()
        {
            if (current_user_can('activate_plugins') && is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(__FILE__);
                //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Do not load anything more
                unset($_GET['activate']);
            }
        }
    }

    if (!function_exists('wpmsShowError')) {
        /**
         * Show notice
         *
         * @return void
         */
        function wpmsShowError()
        {
            echo '<div class="error"><p><strong>WP Meta SEO</strong>
 need at least PHP 5.6 version, please update php before installing the plugin.</p></div>';
        }
    }

    //Add actions
    add_action('admin_init', 'wpmsDisablePlugin');
    add_action('admin_notices', 'wpmsShowError');

    //Do not load anything more
    return;
}

//Include the jutranslation helpers
include_once('jutranslation' . DIRECTORY_SEPARATOR . 'jutranslation.php');
call_user_func(
    '\Joomunited\WPMetaSEO\Jutranslation\Jutranslation::init',
    __FILE__,
    'wp-meta-seo',
    'WP Meta SEO',
    'wp-meta-seo',
    'languages' . DIRECTORY_SEPARATOR . 'wp-meta-seo-en_US.mo'
);

// Include jufeedback helpers
require_once('jufeedback'. DIRECTORY_SEPARATOR . 'jufeedback.php');
call_user_func(
    '\Joomunited\WPMetaSEO\Jufeedback\Jufeedback::init',
    __FILE__,
    'wpms',
    'wp-meta-seo',
    'WP Meta Seo',
    'wp-meta-seo'
);

if (!class_exists('\Joomunited\WPMS\JUCheckRequirements')) {
    require_once(trailingslashit(dirname(__FILE__)) . 'requirements.php');
}

if (class_exists('\Joomunited\WPMS\JUCheckRequirements')) {
    // Plugins name for translate
    $args = array(
        'plugin_name' => esc_html__('WP Meta SEO', 'wp-meta-seo'),
        'plugin_path' => 'wp-meta-seo/wp-meta-seo.php',
        'plugin_textdomain' => 'wp-meta-seo',
        'requirements' => array(
            'php_version' => '5.6',
            'php_modules' => array(
                'curl' => 'warning',
                'libxml' => 'warning'
            ),
            // Minimum addons version
            'addons_version' => array(
                'wpmsAddons' => '1.3.7'
            )
        ),
    );
    $wpmsCheck = call_user_func('\Joomunited\WPMS\JUCheckRequirements::init', $args);

    if (!$wpmsCheck['success']) {
        //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Do not load anything more
        unset($_GET['activate']);
        return;
    }
}

if (!defined('WPMETASEO_PLUGIN_URL')) {
    /**
     * Url to WP Meta SEO plugin
     */
    define('WPMETASEO_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('WPMETASEO_PLUGIN_DIR')) {
    /**
     * Path to WP Meta SEO plugin
     */
    define('WPMETASEO_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('URL')) {
    /**
     * Site url
     */
    define('URL', get_site_url());
}

if (!defined('WPMSEO_VERSION')) {
    /**
     * Plugin version
     */
    define('WPMSEO_VERSION', '4.5.11');
}

if (!defined('WPMS_CLIENTID')) {
    /**
     * Default client id to connect to google application
     */
    define('WPMS_CLIENTID', '992432963228-t8pc9ph1i7afaqocnhjl9cvoovc9oc7q.apps.googleusercontent.com');
}

if (!defined('WPMS_CLIENTSECRET')) {
    /**
     * Default client secret to connect to google application
     */
    define('WPMS_CLIENTSECRET', 'tyF4XICemXdORWX2qjyazfqP');
}

if (!defined('MPMSCAT_TITLE_LENGTH')) {
    /**
     * Default meta title length
     */
    define('MPMSCAT_TITLE_LENGTH', 60);
}

if (!defined('MPMSCAT_DESC_LENGTH')) {
    /**
     * Default meta description length
     */
    define('MPMSCAT_DESC_LENGTH', 158);
}

if (!defined('MPMSCAT_KEYWORDS_LENGTH')) {
    /**
     * Default meta keywords length
     */
    define('MPMSCAT_KEYWORDS_LENGTH', 256);
}

if (!defined('WPMSEO_FILE')) {
    /**
     * Path to this file
     */
    define('WPMSEO_FILE', __FILE__);
}


if (!defined('WPMSEO_ADDON_FILENAME')) {
    /**
     * WP Meta SEO addon file
     */
    define('WPMSEO_ADDON_FILENAME', 'wp-meta-seo-addon/wp-meta-seo-addon.php');
}

if (!defined('WPMSEO_TEMPLATE_BREADCRUMB')) {
    /**
     * Default template for breadcrumb
     */
    define(
        'WPMSEO_TEMPLATE_BREADCRUMB',
        '<span property="itemListElement" typeof="ListItem">
<span property="name">%htitle%</span><meta property="position" content="%position%"></span>'
    );
}
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
register_activation_hook(__FILE__, array('WpMetaSeo', 'pluginActivation'));
register_deactivation_hook(__FILE__, array('WpMetaSeo', 'pluginDeactivation'));

require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.wp-metaseo.php');
add_action('init', array('WpMetaSeo', 'init'));
require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-sitemap.php');
$GLOBALS['metaseo_sitemap'] = new MetaSeoSitemap;
/**
 * Get default settings
 *
 * @return array
 */
function wpmsGetDefaultSettings()
{
    return array(
        'home_meta_active'       => 1,
        'metaseo_title_home'     => '',
        'metaseo_desc_home'      => '',
        'metaseo_showfacebook'   => '',
        'metaseo_showfbappid'    => '',
        'metaseo_showtwitter'    => '',
        'metaseo_twitter_card'   => 'summary',
        'metaseo_showkeywords'   => 0,
        'metaseo_showtmetablock' => 1,
        'metaseo_showsocial'     => 1,
        'metaseo_seovalidate'    => 0,
        'metaseo_linkfield'      => 1,
        'metaseo_metatitle_tab'  => 1,
        'metaseo_follow'         => 0,
        'metaseo_index'          => 0,
        'metaseo_overridemeta'   => 1,
        'metaseo_canonical' => 0,
        'metaseo_removecatprefix' => 0,
        'metaseo_specific_keywords' => '',
        'metaseo_enable_default_fb_image' => array(
            'active' => 0,
            'source' => 'featured',
            'default_set' => ''
        ),
        'metaseo_enable_default_twitter_image' => array(
            'active' => 0,
            'source' => 'featured',
            'default_set' => ''
        ),
    );
}

/**
 * Retrieve the date of the post/page/cpt for use as replacement string
 *
 * @param object $current_post Current post info
 *
 * @return string|null
 */
function wpmsRetrieveDate($current_post)
{
    $replacement = null;
    if (isset($current_post->post_date) && $current_post->post_date !== '') {
        $replacement = mysql2date(get_option('date_format'), $current_post->post_date, true);
    } else {
        if (get_query_var('day') && get_query_var('day') !== '') {
            $replacement = get_the_date();
        } else {
            if (single_month_title(' ', false) && single_month_title(' ', false) !== '') {
                $replacement = single_month_title(' ', false);
            } elseif (get_query_var('year') !== '') {
                $replacement = get_query_var('year');
            }
        }
    }

    return $replacement;
}

/**
 * Extract specific HTML tags and their attributes from a string.
 *
 * You can either specify one tag, an array of tag names, or a regular expression that matches the tag name(s).
 * If multiple tags are specified you must also set the $selfclosing parameter and it must be the same for
 * all specified tags (so you can't extract both normal and self-closing tags in one go).
 *
 * The function returns a numerically indexed array of extracted tags. Each entry is an associative array
 * with these keys :
 *    tag_name    - the name of the extracted tag, e.g. "a" or "img".
 *    offset        - the numberic offset of the first character of the tag within the HTML source.
 *    contents    - the inner HTML of the tag. This is always empty for self-closing tags.
 *    attributes    - a name -> value array of the tag's attributes, or an empty array if the tag has none.
 *    full_tag    - the entire matched tag, e.g. '<a href="http://example.com">example.com</a>'. This key
 *                  will only be present if you set $return_the_entire_tag to true.
 *
 * @param string       $html                  The HTML code to search for tags.
 * @param string|array $tag                   The tag(s) to extract.
 * @param boolean      $selfclosing           Whether the tag is self-closing or not.
 *                                            Setting it to null will force the script to try and make an educated guess.
 * @param boolean      $return_the_entire_tag Return the entire matched tag in 'full_tag' key of the results array.
 * @param string       $charset               The character set of the HTML code. Defaults to ISO-8859-1.
 *
 * @return array An array of extracted tags, or an empty array if no matching tags were found.
 */
function wpmsExtractTags(
    $html,
    $tag,
    $selfclosing = null,
    $return_the_entire_tag = false,
    $charset = 'ISO-8859-1'
) {

    if (is_array($tag)) {
        $tag = implode('|', $tag);
    }

    $selfclosing_tags = array(
        'area',
        'base',
        'basefont',
        'br',
        'hr',
        'input',
        'img',
        'link',
        'meta',
        'col',
        'param'
    );
    if (is_null($selfclosing)) {
        $selfclosing = in_array($tag, $selfclosing_tags);
    }

    if ($selfclosing) {
        $tag_pattern = '@<(?P<tag>' . $tag . ')			# <tag
				(?P<attributes>\s[^>]+)?		# attributes, if any
				\s*/?>							# /> or just >, being lenient here 
				@xsi';
    } else {
        $tag_pattern = '@<(?P<tag>' . $tag . ')			# <tag
				(?P<attributes>\s[^>]+)?		# attributes, if any
				\s*>							# >
				(?P<contents>.*?)				# tag contents
				</(?P=tag)>						# the closing </tag>
				@xsi';
    }

    $attribute_pattern = '@
			(?P<name>\w+)											# attribute name
			\s*=\s*
			(
				(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)	# a quoted value
				|							# or
				(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)	 # an unquoted value (terminated by whitespace or EOF) 
			)
			@xsi';

    //Find all tags
    if (!preg_match_all($tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
        //Return an empty array if we didn't find anything
        return array();
    }

    $tags = array();
    foreach ($matches as $match) {
        //Parse tag attributes, if any
        $attributes = array();
        if (!empty($match['attributes'][0])) {
            if (preg_match_all($attribute_pattern, $match['attributes'][0], $attribute_data, PREG_SET_ORDER)) {
                //Turn the attribute data into a name->value array
                foreach ($attribute_data as $attr) {
                    if (!empty($attr['value_quoted'])) {
                        $value = $attr['value_quoted'];
                    } elseif (!empty($attr['value_unquoted'])) {
                        $value = $attr['value_unquoted'];
                    } else {
                        $value = '';
                    }

                    //Passing the value through html_entity_decode is handy when you want
                    //to extract link URLs or something like that. You might want to remove
                    //or modify this call if it doesn't fit your situation.
                    $value = html_entity_decode($value, ENT_QUOTES, $charset);

                    $attributes[$attr['name']] = $value;
                }
            }
        }

        $tag = array(
            'tag_name'   => $match['tag'][0],
            'offset'     => $match[0][1],
            'contents'   => !empty($match['contents']) ? $match['contents'][0] : '', //empty for self-closing tags
            'attributes' => $attributes,
        );
        if ($return_the_entire_tag) {
            $tag['full_tag'] = $match[0][0];
        }

        $tags[] = $tag;
    }

    return $tags;
}

require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-rewrite.php');
$settings  = get_option('_metaseo_settings');
if (isset($settings['metaseo_removecatprefix']) && $settings['metaseo_removecatprefix'] === '1') {
    $GLOBALS['metaseo_rewrite'] = new MetaSeoRewrite;
}

if (is_admin()) {
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-content-list-table.php');
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-category-meta-table.php');
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-image-list-table.php');
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-dashboard.php');
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-broken-link-table.php');
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-google-analytics.php');
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-admin.php');
    add_action('plugins_loaded', 'wpmsAdminInit', 15);

    /**
     * Admin init
     *
     * @return void
     */
    function wpmsAdminInit()
    {
        $GLOBALS['metaseo_admin'] = new MetaSeoAdmin;
    }

    /*add_filter('wp_prepare_attachment_for_js', array('MetaSeoImageListTable', 'addMoreAttachmentSizes'), 10, 2);
    add_filter('image_size_names_choose', array('MetaSeoImageListTable', 'addMoreAttachmentSizesChoose'), 10, 1);*/
    add_filter('user_contactmethods', 'metaseoContactuser', 10, 1);

    /**
     * Custom field for user profile
     *
     * @param array $contactusers List contact users
     *
     * @return mixed
     */
    function metaseoContactuser($contactusers)
    {
        $contactusers['mtwitter']  = esc_html__('Twitter username (without @)', 'wp-meta-seo');
        $contactusers['mfacebook'] = esc_html__('Facebook profile URL', 'wp-meta-seo');
        return $contactusers;
    }

    include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmsga.php');
    // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Not a form, nonce is not required
    if (isset($_GET['task']) && $_GET['task'] === 'wpms_ga') {
        if (!empty($_GET['code'])) {
            $google_analytics =  get_option('wpms_google_alanytics');

            if (is_array($google_analytics)) {
                $google_analytics['code'] = $_GET['code'];

                require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
                $ggClient = WpmsGaTools::initClient($google_analytics['wpmsga_dash_clientid'], $google_analytics['wpmsga_dash_clientsecret']);

                try {
                    $ggClient->authenticate($google_analytics['code']);
                    $getAccessToken = $ggClient->getAccessToken();

                    if ($getAccessToken) {
                        $ggClient->setAccessToken($getAccessToken);
                        $google_analytics['googleCredentials'] = $getAccessToken;

                        if (empty($google_alanytics['profile_list'])) {
                            $service          = new WPMSGoogle_Service_Analytics($ggClient);
                            $midnight      = strtotime('tomorrow 00:00:00'); // UTC midnight
                            $midnight      = $midnight + 8 * 3600; // UTC 8 AM
                            $error_timeout = $midnight - time();

                            $profiles = WpmsGaTools::refreshProfiles($service, $getAccessToken['access_token'], $error_timeout);
                            $google_analytics['profile_list'] = $profiles;
                        }

                        update_option('wpms_google_alanytics', $google_analytics);
                        // Do redirect
                        $redirectUrl = admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data');
                        header('Location: '.$redirectUrl, true, 302);
                        exit;
                    }
                } catch (WPMSGoogle\Service\Exception $e) {
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Log error for debug
                    error_log($e->getMessage());
                } catch (Exception $e) {
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Log error for debug
                    error_log($e->getMessage());
                }
            }
        }
        // phpcs:enable
    }
} else {
    /**
     * Outputs the breadcrumb
     *
     * @param boolean $return  Whether to return or echo the trail. (optional)
     * @param boolean $reverse Whether to reverse the output or not. (optional)
     *
     * @return string
     */
    function wpmsBreadcrumb($return = false, $reverse = false)
    {
        require_once(WPMETASEO_PLUGIN_DIR . 'inc/breadcrumb/class.metaseo-breadcrumb.php');
        $breadcrumb = new MetaSeoBreadcrumb;
        if ($breadcrumb !== null) {
            $breadcrumb->checkPosts();
            return $breadcrumb->breadcrumbDisplay($return, $reverse);
        }
        return '';
    }

    /*
    * shortcode for breadcrumb
    */
    add_shortcode('wpms_breadcrumb', 'wpmsBreadcrumbShortcode');

    if (!function_exists('wpmsBreadcrumbShortcode')) {
        /**
         * Create shortcode for breadcrumb
         *
         * @param array $params Shortcode attribute
         *
         * @return string
         */
        function wpmsBreadcrumbShortcode($params)
        {
            $html = '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
            if (isset($params['reverse']) && (int)$params['reverse'] === 1) {
                $html .= wpmsBreadcrumb(true, true);
            } else {
                $html .= wpmsBreadcrumb(true, false);
            }
            $html .= '</div>';

            return $html;
        }
    }

    // Check again and modify title, meta title, meta description before output
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-opengraph.php');

    add_action('wpmsseo_head', 'wpmsopengraph', 30);
    // Remove actions that we will handle through our wpseo_head call, and probably change the output of.
    remove_action('wp_head', 'rel_canonical');
    /**
     * Render graph meta tag
     *
     * @return void
     */
    function wpmsopengraph()
    {
        global $wp_query;
        $is_shop = false;
        if (function_exists('is_shop')) {
            if (is_shop()) {
                $is_shop = true;
                $id      = wc_get_page_id('shop');
                $post    = get_post($id);
                $content = $post->post_content;
            } else {
                if (empty($wp_query->post)) {
                    return;
                }
                $id      = $wp_query->post->ID;
                $content = $wp_query->post->post_content;
            }
        } else {
            if (empty($wp_query->post)) {
                return;
            }
            $id      = $wp_query->post->ID;
            $content = $wp_query->post->post_content;
        }
        wp_reset_query();

        $default_settings = wpmsGetDefaultSettings();
        $settings         = get_option('_metaseo_settings');
        if (is_array($settings)) {
            $settings = array_merge($default_settings, $settings);
        } else {
            $settings = $default_settings;
        }

        // get meta title
        $opengraph      = new MetaSeoOpenGraph();
        $meta_title     = $opengraph->getTitle($is_shop, $id);
        $meta_title_esc = $opengraph->getMetaTitle($settings, $meta_title);
        // get meta description
        $meta_desc_esc = $opengraph->getDesc($settings, $id, $content);
        // get meta keyword
        $meta_keywords_esc = $opengraph->getKeyword($settings, $id);

        $page_follow       = get_post_meta($id, '_metaseo_metafollow', true);
        $page_index        = get_post_meta($id, '_metaseo_metaindex', true);
        // get meta title for twitter
        $meta_twtitle = $opengraph->getTwtitle($id);
        // get meta description for twitter
        $meta_twdesc = $opengraph->getTwdesc($id);
        // get twiter card
        $meta_twcard = $opengraph->getTwCard($settings);

        // get facebook admin and twiter site meta
        $usermeta          = $opengraph->getUserMeta($settings, $id);
        $meta_twitter_site = $usermeta['meta_twitter_site'];
        $facebook_admin    = $usermeta['facebook_admin'];
        $sitename          = get_bloginfo('name');

        // get meta title for facebook
        $meta_fbtitle = $opengraph->getFbtitle($id);
        // get meta description for facebook
        $meta_fbdesc = $opengraph->getFbdesc($id);
        // get facebook app id
        if (isset($settings['metaseo_showfbappid'])) {
            $fbapp_id = esc_attr($settings['metaseo_showfbappid']);
        } else {
            $fbapp_id = '';
        }
        // get meta image for facebook & twiter
        $images       = $opengraph->getImage($id, $settings);
        $meta_fbimage = $images[0];
        $meta_twimage = $images[1];

        $current_url      = $opengraph->getCurentUrl($settings, $id);
        $type             = $opengraph->getType();
        $home_meta_active = wpmsGetOption('home_meta_active');
        // check homepage is latest post
        if (is_home() && !empty($home_meta_active)) {
            $metas          = $opengraph->getHome($settings);
            $meta_title_esc = $metas['title'];
            $meta_twtitle   = $metas['title'];
            $meta_fbtitle   = $metas['title'];
            if ($metas['desc']) {
                $meta_desc_esc = $metas['desc'];
                $meta_twdesc   = $metas['desc'];
                $meta_fbdesc   = $metas['desc'];
            }
            $page_follow = $metas['page_follow'];
            $page_index  = $metas['page_index'];
        }

        // is front page
        if (is_front_page() && 'page' === get_option('show_on_front') && is_page(get_option('page_on_front'))) {
            $metas          = $opengraph->getFrontPageMeta($settings, $id);
            $meta_title_esc = $metas['title'];
            $meta_twtitle   = $metas['title'];
            $meta_fbtitle   = $metas['title'];
            if ($metas['desc']) {
                $meta_desc_esc  = $metas['desc'];
                $meta_twdesc    = $metas['desc'];
                $meta_fbdesc    = $metas['desc'];
            }

            $page_follow    = $metas['page_follow'];
            $page_index     = $metas['page_index'];
        }

        if (is_category() || is_tag() || is_tax()) {
            $metas             = $opengraph->getTagMeta($wp_query, $settings);
            $meta_keywords_esc = $metas['keyword'];
            $meta_title_esc    = $metas['title'];
            $meta_fbtitle      = $metas['title'];
            $meta_twtitle      = $metas['title'];
            $meta_desc_esc     = $metas['desc'];
            $meta_fbdesc       = $metas['desc'];
            $meta_twdesc       = $metas['desc'];
            if (!empty($metas['canonical'])) {
                $current_url = $metas['canonical'];
            }
            $page_follow       = 'follow';
        }

        if (empty($page_index)) {
            $page_index = 'index';
        }

        if (empty($page_follow)) {
            $page_follow = 'follow';
        }

        // Create canonical meta link
        $canonical_meta = $opengraph->getCanonical($id, $settings, $current_url, $page_index);
        if (!empty($canonical_meta)) {
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in some method in class MetaSeoOpenGraph
            echo $canonical_meta;
        }

        $patterns = $opengraph->getPatterns(
            $id,
            $settings,
            $meta_twimage,
            $meta_twcard,
            $meta_twitter_site,
            $sitename,
            strip_tags(html_entity_decode($meta_twdesc, ENT_COMPAT, 'UTF-8')),
            strip_tags(html_entity_decode($meta_twtitle, ENT_COMPAT, 'UTF-8')),
            $facebook_admin,
            $meta_fbimage,
            strip_tags(html_entity_decode($meta_fbdesc, ENT_COMPAT, 'UTF-8')),
            $current_url,
            $type,
            $fbapp_id,
            strip_tags(html_entity_decode($meta_fbtitle, ENT_COMPAT, 'UTF-8')),
            esc_html($meta_desc_esc, 'wp-meta-seo'),
            esc_html($meta_keywords_esc, 'wp-meta-seo'),
            strip_tags(html_entity_decode($meta_title_esc, ENT_COMPAT, 'UTF-8')),
            $page_index,
            $page_follow
        );

        foreach ($patterns as $k => $pattern) {
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in some method in class MetaSeoOpenGraph
            echo $pattern[1];
        }
    }

    add_action('wp_head', 'wpmshead', 30);
    /**
     * WPMS frontend head
     *
     * @return void
     */
    function wpmshead()
    {
        global $wp_query;

        $old_wp_query = null;

        if (!$wp_query->is_main_query()) {
            $old_wp_query = $wp_query;
            wp_reset_query();
        }

        do_action('wpmsseo_head');

        if (!empty($old_wp_query)) {
            // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- This combines all the output on the frontend
            $GLOBALS['wp_query'] = $old_wp_query;
            unset($old_wp_query);
        }
    }

    add_filter('pre_get_document_title', 'wpmstitle', 15);
    add_filter('wp_title', 'wpmstitle', 15, 3);
    add_filter('thematic_doctitle', 'wpmstitle', 15);
    add_filter('woo_title', 'wpmstitle', 99);
    /**
     * Render meta title tag
     *
     * @param string $title Title
     *
     * @return mixed|string
     */
    function wpmstitle($title)
    {
        global $wp_query;
        $default_settings = wpmsGetDefaultSettings();
        $settings         = get_option('_metaseo_settings');
        if (is_array($settings)) {
            $settings = array_merge($default_settings, $settings);
        } else {
            $settings = $default_settings;
        }
        if (empty($settings)) {
            return esc_html($title);
        }

        if (empty($settings['metaseo_metatitle_tab'])) {
            return esc_html($title);
        }
        if (empty($wp_query->post)) {
            return esc_html($title);
        }

        $is_shop = false;
        if (function_exists('is_shop')) {
            if (is_shop()) {
                $is_shop = true;
                $id      = wc_get_page_id('shop');
            } else {
                $id = $wp_query->post->ID;
            }
        } else {
            $id = $wp_query->post->ID;
        }

        $opengraph  = new MetaSeoOpenGraph();
        $meta_title = $opengraph->getTitle($is_shop, $id);

        if (is_home()) {
            $metas      = $opengraph->getHome($settings);
            $meta_title = $metas['title'];
        }

        // is front page
        if (is_front_page() && 'page' === get_option('show_on_front') && is_page(get_option('page_on_front'))) {
            $metas      = $opengraph->getFrontPageMeta($settings, $id);
            $meta_title = $metas['title'];
        }

        $term = $wp_query->get_queried_object();
        if (is_category() || is_tag() || is_tax()) {
            if (function_exists('get_term_meta')) {
                $meta_title = get_term_meta($term->term_id, 'wpms_category_metatitle', true);
            } else {
                $meta_title = get_metadata('term', $term->term_id, 'wpms_category_metatitle', true);
            }

            $meta_title = $opengraph->replaceSnippet($meta_title, null, $term);
        }

        if (empty($meta_title)) {
            $meta_title = $title;
        }

        return esc_html($meta_title);
    }

    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-front_end.php');
    $GLOBALS['metaseo_front'] = new MetaSeoFront;

    /*     * ******************************************** */
}

/**
 * Set a option
 *
 * @param string              $option_name Option name
 * @param string|array|object $value       Value of option
 *
 * @return void
 */
function wpmsSetOption($option_name, $value)
{
    $settings = get_option('_metaseo_settings');
    if (empty($settings)) {
        $settings               = array();
        $settings[$option_name] = $value;
    } else {
        $settings[$option_name] = $value;
    }

    update_option('_metaseo_settings', $settings);
}

/**
 * Get a option
 *
 * @param string $option_name Option name
 *
 * @return mixed
 */
function wpmsGetOption($option_name)
{
    $default_settings = wpmsGetDefaultSettings();
    $settings         = get_option('_metaseo_settings');
    if (isset($settings) && isset($settings[$option_name])) {
        return $settings[$option_name];
    }

    return $default_settings[$option_name];
}

/* * ****** Check and import meta data from other installed plugins for SEO ******* */

/**
 * Handle import of meta data from other installed plugins for SEO
 *
 * @return void
 */
function wpmsYoastMessage()
{
    $activated = 0;
    // Check if All In One Pack is active
    if (!get_option('_aio_import_notice_flag')) {
        if (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
            add_action('admin_notices', 'wpmsImportMetaNotice', 2);
            $activated ++;
        }

        if (get_option('_aio_import_notice_flag') === false) {
            update_option('_aio_import_notice_flag', 0);
        }
    }
    // Check if Yoast is active
    if (!get_option('_yoast_import_notice_flag', false)) {
        if (is_plugin_active('wordpress-seo/wp-seo.php')
            || is_plugin_active('Yoast-SEO-Premium/wp-seo-premium.php') || class_exists('WPSEO_Premium')) {
            add_action('admin_notices', 'wpmsImportYoastMetaNotice', 3);
            $activated ++;
        }

        if (get_option('_yoast_import_notice_flag') === false) {
            update_option('_yoast_import_notice_flag', 0);
        }
    }

    if ($activated === 2 && !get_option('plugin_to_sync_with', false)) {
        add_action('admin_notices', 'wpmsNoticeSameFeature', 1);
    }
}

/**
 * Show notice
 *
 * @return void
 */
function wpmsNoticeSameFeature()
{
    echo '<div class="error metaseo-import-wrn"><p>' . esc_html__('Be careful you installed 2 extensions doing almost the same thing, please deactivate AIOSEO or Yoast in order to work more clearly!', 'wp-meta-seo') . '</p></div>';
}

add_action('admin_init', 'wpmsYoastMessage');

/**
 * Notice import meta
 *
 * @return void
 */
function wpmsImportMetaNotice()
{
    echo '<div class="error metaseo-import-wrn"><p>' . sprintf(esc_html__('We have found that you&#39;re using
     All In One Pack Plugin, WP Meta SEO can import the meta
      from this plugin, %s', 'wp-meta-seo'), '<a href="#" class="button mseo-import-action"
 style="position:relative" onclick="importMetaData(this, event)" id="_aio_">
 <span class="spinner-light"></span>Import now</a> or
  <a href="#" class="dissmiss-import">dismiss this</a>') . '</p></div>';
}

/**
 * Notice import Yoast meta
 *
 * @return void
 */
function wpmsImportYoastMetaNotice()
{
    echo '<div class="error metaseo-import-wrn"><p>' . sprintf(esc_html__('We have found that you&#39;re using Yoast SEO Plugin,
     WP Meta SEO can import the meta from this plugin, %s', 'wp-meta-seo'), '<a href="#"
 class="button mseo-import-action" style="position:relative"
  onclick="importMetaData(this, event)" id="_yoast_">Import now<span class="spinner-light"></span></a>
   or <a href="#" class="dissmiss-import">dismiss this</a>') . '</p></div>';
}

/**
 * Encode or decode all values in string format of an array
 *
 * @param array  $obj    List string
 * @param string $action Action
 *
 * @return mixed
 */
function wpmsUtf8($obj, $action = 'encode')
{
    $action = strtolower(trim($action));
    $fn     = 'utf8_' . $action;
    if (is_array($obj)) {
        foreach ($obj as &$el) {
            if (is_array($el)) {
                if (is_callable($fn)) {
                    $el = wpmsUtf8($el, $action);
                }
            } elseif (is_string($el)) {
                $isASCII = mb_detect_encoding($el, 'ASCII');
                if ($action === 'encode' && !$isASCII) {
                    $el = mb_convert_encoding($el, 'UTF-8', 'auto');
                }

                $el = $fn($el);
            }
        }
    } elseif (is_object($obj)) {
        $vars = array_keys(get_object_vars($obj));
        foreach ($vars as $var) {
            wpmsUtf8($obj->$var, $action);
        }
    }

    return $obj;
}

/**
 * Cmb render text link
 *
 * @param array  $field Field
 * @param string $meta  Meta value
 *
 * @return void
 */
function textLink($field, $meta)
{
    echo '<input class="cmb_text_link" type="text" size="45" id="', esc_html($field['id']), '"
     name="', esc_html($field['id']), '" value="', esc_html($meta), '" />';
    echo '<input class="cmb_link_button button" type="button"
 value="Voeg link toe" />', '<p class="cmb_metabox_description">', esc_html($field['desc']), '</p>';
}

add_action('cmb_render_text_link', 'textLink', 10, 2);
add_action('template_redirect', 'wpmsTemplateRedirect');

/**
 * Redirect 404 url and insert url to database
 *
 * @return void
 */
function wpmsTemplateRedirect()
{
    global $wpdb;
    // redirect by rule
    $url = '';
    if (isset($_SERVER['REQUEST_URI'])) {
        $url = $_SERVER['REQUEST_URI'];
    }

    if (!is_home() && !is_front_page()) {
        $redirects       = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE (link_url = %s AND link_url != "/") OR type = "add_rule" OR type = "add_custom"', array(
            $url
        )));
        $target          = '';
        $status_redirect = 302;
        foreach ($redirects as $link) {
            // If redirect success, not redirect again
            $parse_url = parse_url($link->link_url_redirect);
            if (isset($parse_url['path'])) {
                if (rtrim($parse_url['path'], '/') === rtrim($url, '/')) {
                    break;
                }
            }
            // Fix with xlink
            $query_str = parse_url($url, PHP_URL_QUERY);
            if (!empty($query_str) && strpos($query_str, 'xlink') !== false) {
                break;
            }

            $link->link_url = str_replace(' ', '%20', $link->link_url);
            $matches        = false;
            // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- remove warning if match the URL
            if ((($link->link_url === $url || $link->link_url === rtrim($url, '/') || $link->link_url === urldecode($url))) || (@preg_match('@' . str_replace('@', '\\@', $link->link_url) . '@', $url, $matches) > 0) || (@preg_match('@' . str_replace('@', '\\@', $link->link_url) . '@', urldecode($url), $matches) > 0)) {
                $target = $link->link_url_redirect;
                if ($link->type === 'add_custom') {
                    if (!is_plugin_active(WPMSEO_ADDON_FILENAME)) {
                        return;
                    }
                    $status_redirect = $link->meta_title;
                }

                break;
            }
        }

        if ($target) {
            if (empty($status_redirect) && !in_array($status_redirect, array(301, 302, 307))) {
                $status_redirect = 302;
            }
            wp_redirect($target, $status_redirect);
            exit();
        } else {
            if (is_404()) {
                if (isset($_SERVER['REQUEST_URI'])) {
                    $path_infos = pathinfo($_SERVER['REQUEST_URI']);
                    if (isset($path_infos['extension'])) {
                        return;
                    }

                    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    if (isset($_SERVER['HTTPS']) &&
                        ($_SERVER['HTTPS'] === 'on' || (int) $_SERVER['HTTPS'] === 1) ||
                        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                        $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                        $protocol = 'https://';
                    } else {
                        $protocol = 'http://';
                    }
                    $esc_url = str_replace(array('http://', 'https://'), $protocol, esc_url($url));
                    $check   = $wpdb->get_results($wpdb->prepare(
                        'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE (link_url=%s OR link_url=%s OR link_url=%s)',
                        array(
                            $url,
                            $esc_url,
                            $_SERVER['REQUEST_URI']
                        )
                    ));

                    // get referer url
                    if (wp_get_raw_referer()) {
                        $referer_url = wp_get_raw_referer();
                    } else {
                        $referer_url = get_home_url();
                    }

                    if ($referer_url === '') {
                        $referer_url = get_home_url();
                    }
                    if (count($check) === 0) {
                        if (!function_exists('wp_validate_redirect')) {
                            require_once(ABSPATH . 'wp-includes/pluggable.php');
                        }

                        // insert url
                        $insert = array(
                            'link_url'        => ($url),
                            'status_code'     => '404 Not Found',
                            'status_text'     => '404 Not Found',
                            'type'            => '404_automaticaly',
                            'broken_indexed'  => 1,
                            'broken_internal' => 0,
                            'warning'         => 0,
                            'dismissed'       => 0,
                            'referrer'        => $referer_url
                        );

                        $wpdb->insert($wpdb->prefix . 'wpms_links', $insert);
                    } else {
                        // update url
                        $links_broken = $wpdb->get_row($wpdb->prepare(
                            'SELECT * FROM ' . $wpdb->prefix . 'wpms_links WHERE (link_url=%s OR link_url=%s OR link_url=%s) ',
                            array(
                                $url,
                                $esc_url,
                                $_SERVER['REQUEST_URI']
                            )
                        ));

                        if (!empty($links_broken)) {
                            $list_referrers = explode('||', $links_broken->referrer);
                            if (!in_array($referer_url, $list_referrers)) {
                                $list_referrers[] = $referer_url;
                            }

                            $referrers = implode('||', $list_referrers);
                            $referrers = trim($referrers, '||');
                            $value     = array(
                                'hit'      => (int) $links_broken->hit + 1,
                                'referrer' => $referrers
                            );

                            $wpdb->update(
                                $wpdb->prefix . 'wpms_links',
                                $value,
                                array('id' => $links_broken->id),
                                array('%d', '%s'),
                                array('%d')
                            );

                            if (($url === $links_broken->link_url || $esc_url === $links_broken->link_url)
                                && $links_broken->link_url_redirect !== '') {
                                if (!empty($links_broken->meta_title)) {
                                    $status_redirect = $links_broken->meta_title;
                                } else {
                                    $status_redirect = 302;
                                }

                                if (empty($status_redirect) && !in_array($status_redirect, array(301, 302, 307))) {
                                    $status_redirect = 302;
                                }

                                wp_redirect($links_broken->link_url_redirect, $status_redirect);
                                exit();
                            }
                        }
                    }
                }

                $defaul_settings_404 = array(
                    'wpms_redirect_homepage' => 0,
                    'wpms_type_404'          => 'none',
                    'wpms_page_redirected'   => 'none'
                );
                $wpms_settings_404   = get_option('wpms_settings_404');

                if (is_array($wpms_settings_404)) {
                    $defaul_settings_404 = array_merge($defaul_settings_404, $wpms_settings_404);
                }

                // redirect url by settings
                if (isset($defaul_settings_404['wpms_redirect_homepage'])
                    && (int) $defaul_settings_404['wpms_redirect_homepage'] === 1) {
                    wp_redirect(get_home_url());
                    exit();
                } else {
                    if (isset($defaul_settings_404['wpms_type_404'])) {
                        switch ($defaul_settings_404['wpms_type_404']) {
                            case 'wp-meta-seo-page':
                                global $wpdb;
                                $wpms_page = $wpdb->get_row($wpdb->prepare(
                                    'SELECT * FROM ' . $wpdb->prefix . 'posts WHERE post_title = %s AND post_excerpt = %s',
                                    array(
                                        '404 Error, content does not exist anymore',
                                        'metaseo_404_page'
                                    )
                                ));
                                if (!empty($wpms_page)) {
                                    $link_redirect = get_permalink($wpms_page->ID);
                                    if ($link_redirect) {
                                        wp_redirect($link_redirect);
                                        exit();
                                    }
                                }
                                break;

                            case 'custom_page':
                                if (isset($defaul_settings_404['wpms_page_redirected'])
                                    && $defaul_settings_404['wpms_page_redirected'] !== 'none') {
                                    $link_redirect = get_permalink($defaul_settings_404['wpms_page_redirected']);
                                    if ($link_redirect) {
                                        wp_redirect($link_redirect);
                                        exit();
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
    }
}

add_action(
    'after_setup_theme',
    function () {
        //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, no need verification
        if (defined('ET_CORE') && !empty($_GET['et_fb'])) {
            require_once(WPMETASEO_PLUGIN_DIR . 'inc/divi-seo/divi.php');
            new Divi;
        }
    },
    11
);
