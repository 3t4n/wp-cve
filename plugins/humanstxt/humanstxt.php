<?php
/*
Plugin Name: Humans TXT
Plugin URI: http://wordpress.org/plugins/humanstxt/
Description: Credit the people behind your website in your <strong>humans.txt</strong> file. Easy to edit, directly within WordPress.
Text Domain: humanstxt
Domain Path: /languages
Version: 1.3.1
Author: Till Krüss
Author URI: http://till.kruss.me/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Humans TXT plugin version.
 * @since 1.0.1
 */
define('HUMANSTXT_VERSION', '1.3.1');

/**
 * Required WordPress version.
 * @since 1.1.0
 */
define('HUMANSTXT_VERSION_REQUIRED', '3.0');

/**
 * Absolute path to the main Humans TXT plugin file.
 */
define('HUMANSTXT_PLUGIN_FILE', __FILE__);

/**
 * Absolute path to the Humans TXT plugin directory.
 */
define('HUMANSTXT_PLUGIN_PATH', dirname(HUMANSTXT_PLUGIN_FILE));

/**
 * Default amount of stored revisions.
 * Use the 'humanstxt_max_revisions' filter to change it.
 * @since 1.1.0
 */
define('HUMANSTXT_MAX_REVISIONS', 50);

/**
 * Default Humans TXT plugin settings.
 * @global array $humanstxt_defaults Default plugin settings.
 */
$humanstxt_defaults = array(
    'enabled' => false,
    'authortag' => false,
    'roles' => array()
);

/**
 * Register plugin actions, filters and shortcode.
 */
add_action('init', 'humanstxt_init');
add_action('template_redirect', 'humanstxt_template_redirect', 8);
add_action('do_humans', 'humanstxt_do_humans');
add_filter('humans_txt', 'humanstxt_replace_variables');
add_filter('humanstxt_content', 'humanstxt_content_normalize');
add_shortcode('humanstxt', '_humanstxt_shortcode');

/**
 * Load legacy code, if necessary.
 */
if (version_compare(get_bloginfo('version'), '3.2', '<')) {
    require_once HUMANSTXT_PLUGIN_PATH . '/legacy.php';
}

/**
 * Load plugin code for WordPress backend, if needed.
 */
if (is_admin()) {
    require_once HUMANSTXT_PLUGIN_PATH . '/options.php';
}

/**
 * Echos the content of the virtual humans.txt file.
 *
 * @since 1.0.4
 */
function humanstxt()
{
    echo get_humanstxt();
}

/**
 * Returns the content of the virtual humans.txt file,
 * after applying the 'humans_txt' filter to it.
 *
 * @since 1.0.4
 *
 * @return string Content of the virtual humans.txt file
 */
function get_humanstxt()
{
    return apply_filters('humans_txt', humanstxt_content());
}

/**
 * Echos a XHTML-conform author link tag.
 */
function humanstxt_authortag()
{
    echo get_humanstxt_authortag();
}

/**
 * Returns a XHTML-conform author link tag, pointed to
 * the humans.txt URL, after applying the 'humans_authortag'
 * filter to it.
 *
 * @since 1.0.4
 *
 * @return string XHTML-conform author link tag
 */
function get_humanstxt_authortag()
{
    return apply_filters('humans_authortag', '<link rel="author" type="text/plain" href="' . home_url('humans.txt') . '" />' . "\n");
}

/**
 * Determines if it is a request for the humans.txt file.
 *
 * @return bool
 */
function is_humans()
{
    return (bool) get_query_var('humans');
}

/**
 * Determines if there is a physical humans.txt file in WP's root folder.
 *
 * @return bool
 */
function humanstxt_exists()
{
    return @file_exists(ABSPATH . 'humans.txt');
}

/**
 * Callback function for 'init' action.
 * Registers humans.txt rewrite rules and calls flush_rewrite_rules()
 * if necessary (performance friendly of course).
 *
 * @global $wp_rewrite
 */
function humanstxt_init()
{
    global $wp_rewrite;

    $rewrite_rules = get_option('rewrite_rules');

    if (humanstxt_option('enabled')) {
        add_filter('query_vars', 'humanstxt_query_vars');
        add_rewrite_rule('humans\.txt$', $wp_rewrite->index . '?humans=1', 'top');

        // register author link tag action if enabled
        if (humanstxt_option('authortag')) {
            add_action('wp_head', 'humanstxt_authortag', 1);
        }

        // flush rewrite rules if ours is missing
        if (!isset($rewrite_rules[ 'humans\.txt$' ])) {
            flush_rewrite_rules(false);
        }
    } else {

        // flush rewrite rules if ours shouldn't be there
        if (isset($rewrite_rules[ 'humans\.txt$' ])) {
            flush_rewrite_rules(false);
        }
    }
}

function humanstxt_query_vars($qv)
{
    $qv[] = "humans";

    return $qv;
}

/**
 * Callback function for 'template_redirect' action.
 * Calls 'do_humans' action if is_humans() is positive.
 */
function humanstxt_template_redirect()
{
    if (is_humans()) {
        do_action('do_humans');
        exit;
    }
}

/**
 * Callback function for 'do_humans' action.
 * Calls 'do_humanstxt' action and echos get_humanstxt().
 */
function humanstxt_do_humans()
{
    header('Content-Type: text/plain; charset=utf-8');
    do_action('do_humanstxt');

    echo get_humanstxt();
}

if (!function_exists('humanstxt_shortcode')) :
/**
 * Former callback function for [humanstxt] shortcode.
 * Now passing through calls to _humanstxt_shortcode() for
 * backwards compatiblity and to prevent plugin conflicts.
 *
 * @since 1.0.4
 * @see _humanstxt_shortcode()
 *
 * @param array $attributes
 * @return string
 */
function humanstxt_shortcode($attributes)
{
    return _humanstxt_shortcode($attributes);
}
endif;

/**
 * Callback function for [humanstxt] shortcode. Processes
 * shortcode call and returns result as string. The un-wrapped
 * output can be filtered with the 'humanstxt_shortcode_content'
 * filter and the final output can be filtered with the
 * 'humanstxt_shortcode_output' filter.
 *
 * Renamed in 1.1.3 to prevent plugin conflicts.
 *
 * @since 1.1.3
 * @see humanstxt_shortcode()
 *
 * @param array $attributes
 * @return string
 */
function _humanstxt_shortcode($attributes)
{
    extract(shortcode_atts(array(
        'id' => '', // id-attribute of wrapping HTML element, if $wrap isn't false
        'pre' => false, // perfect for preformatted text: <pre>[humanstxt pre="1"]</pre>
        'plain' => false, // clean output, all options are ignored except $wrap and $id
        'wrap' => true, // wrap content of humans.txt in <p> element
        'filter' => true, // convert/format common entities and encode plain text email addresses
        'clickable' => true, // make URLs, email addresses and Twitter accounts clickable
        'urls' => true, // force/prevent clickable URLs, regardless $clickable
        'emails' => true, // force/prevent clickable email addresses, regardless $clickable
        'twitter' => true // force/prevent clickable Twitter accounts, regardless $clickable
    ), $attributes));

    $classes = array( 'humanstxt' );
    $content = get_humanstxt();
    $content = esc_html($content);

    if (!$plain) {
        if (!$pre) {
            $content = nl2br($content);
        } // convert line breaks

        if ($filter) {
            if (!$pre) {
                $content = wptexturize($content);
            } // format common entities
            $content = convert_chars($content); // convert certain characters
            $content = capital_P_dangit($content); // correct "Wordpress"
        }

        // format standard headlines
        if (!$pre) {
            $headline_replacement = '<strong class="humanstxt-headline">$1</strong>';
            $headline_replacement = apply_filters('humanstxt_shortcode_headline_replacement', $headline_replacement);
            $content = preg_replace('~/\*(.+?)\*/~', $headline_replacement, $content);
        }

        // make URLs clickable
        if (($clickable && $urls) || (!$clickable && $urls && isset($attributes[ 'urls' ]))) {
            $_content = preg_replace_callback('#(?<!=[\'"])(?<=[*\')+.,;:!&$\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#%~/?@\[\]-]{1,2000}|[\'*(+.,;:!=&$](?![\b\)]|(\))?([\s]|$))|(?(1)\)(?![\s<.,;:]|$)|\)))+)#is', '_make_url_clickable_cb', $content);
            if (!is_null($_content)) {
                $content = $_content;
            }
            $content = preg_replace_callback('#([\s>])((www)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]+)#is', '_make_web_ftp_clickable_cb', $content);
        }

        // make email addresses clickable
        if (($clickable && $emails) || (!$clickable && $emails && isset($attributes[ 'emails' ]))) {
            $content = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $content);
        }

        // make Twitter account names clickable
        if (($clickable && $twitter) || (!$clickable && $twitter && isset($attributes[ 'twitter' ]))) {
            $twitter_replacement = '$1<a href="http://twitter.com/$2" rel="external">@$2</a>';
            $twitter_replacement = apply_filters('humanstxt_shortcode_twitter_replacement', $twitter_replacement);
            $content = preg_replace('/(^|[^a-z0-9_])[@＠]([a-z0-9_]{1,20})([@＠\xC0-\xD6\xD8-\xF6\xF8-\xFF]?)/iu', $twitter_replacement, $content);
        }

        if ($filter) {
            // encode email addresses to block spam bots
            $content = preg_replace_callback('{(?:mailto:)?((?:[-!#$%&\'*+/=?^_`.{|}~\w\x80-\xFF]+|".*?")\@(?:[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+|\[[\d.a-fA-F:]+\]))}xi', create_function('$matches', 'return antispambot( $matches[0] );'), $content);
        }

        if ($pre) {
            $classes[] = 'humanstxt-pre';
        }
    } else {
        $classes[] = 'humanstxt-plain';
    }

    $content = apply_filters('humanstxt_shortcode_content', $content, $attributes);

    // do we have an id attribute?
    $id = preg_replace('~[^a-z0-9_-]~i', '', $id);
    if (!empty($id)) {
        $id = ' id="'.$id.'"';
    }

    // do we have a class attribute?
    $classes = preg_replace('~[^a-z0-9_-]~i', '', $classes);
    foreach ($classes as $key => $classname) {
        if (empty($classname)) {
            unset($classes[$key]);
        }
    }
    $class = empty($classes) ? '' : ' class="'.implode(' ', $classes).'"';

    // wrap the output?
    if ($wrap) {
        $content = '<p'.$id.$class.'>'.$content.'</p>';
    }

    return apply_filters('humanstxt_shortcode_output', $content, $attributes);
}

/**
 * Loads the plugin text-domain, if not already loaded.
 */
function humanstxt_load_textdomain()
{
    if (!is_textdomain_loaded('humanstxt')) {
        load_plugin_textdomain('humanstxt', false, 'humanstxt/languages');
    }
}

/**
 * Loads plugin options from database and sets missing
 * options to their default values.
 *
 * @since 1.0.5
 *
 * @global $humanstxt_options
 * @global $humanstxt_defaults
 */
function humanstxt_load_options()
{
    global $humanstxt_options, $humanstxt_defaults;

    // already loaded?
    if (is_null($humanstxt_options)) {
        $humanstxt_options = get_option('humanstxt_options');

        // populate with defaults options if missing...
        foreach ($humanstxt_defaults as $option => $value) {
            if (!isset($humanstxt_options[ $option ])) {
                $humanstxt_options[ $option ] = $value;
            }
        }
    }
}

/**
 * Returns options value of given $option.
 * Returns NULL if $option doesn't exist.
 *
 * @global $humanstxt_options
 *
 * @param string $option Name of the option.
 * @return mixed|null Plugin option value
 */
function humanstxt_option($option)
{
    global $humanstxt_options;

    humanstxt_load_options();
    return isset($humanstxt_options[ $option ]) ? $humanstxt_options[ $option ] : null;
}

/**
 * Returns stored humans.txt file content.
 * Stores and returns default humans.txt content, if not stored yet.
 *
 * @return string $content
 */
function humanstxt_content()
{
    $content = get_option('humanstxt_content');

    // add option if missing
    if ($content === false) {
        $content = humanstxt_default_content();
        add_option('humanstxt_content', $content, '', 'no');
    }

    return apply_filters('humanstxt_content', $content);
}

/**
 * Normalizes the line endings of the given $string.
 *
 * @since 1.1.0
 *
 * @param string $string String to be normalized
 * @return string Normalized string
 */
function humanstxt_content_normalize($string)
{
    $string = str_replace("\r\n", "\n", $string);
    $string = str_replace("\r", "\n", $string);
    return $string;
}

/**
 * Returns an array with all stored revisions, containing each
 * revions's content, author-id and it's time of creation.
 * Returns FALSE if revisions are disabled.
 *
 * @since 1.1.0
 *
 * @return array|false Revisions of the humans.txt file
 */
function humanstxt_revisions()
{

    // are revisions disabled?
    if ((int) apply_filters('humanstxt_max_revisions', 1) < 1) {
        return false;
    }

    $revisions = get_option('humanstxt_revisions');

    // add or reset option?
    if (!is_array($revisions)) {
        $revisions = array(array(
            'date' => current_time('timestamp'),
            'user' => 0,
            'content' => humanstxt_content()
        ));
        add_option('humanstxt_revisions', $revisions, '', 'no');
    }

    return $revisions;
}

/**
 * Stores a new revision with the given $content.
 * Ensures that only the last 50 revisons are stored.
 * Limit can be changed with the 'humanstxt_max_revisions' filter.
 *
 * @since 1.1.0
 *
 * @param string $content Revisions content
 */
function humanstxt_add_revision($content)
{
    $current_user = wp_get_current_user();
    $revisions = humanstxt_revisions();
    $revisions[] = array( 'date' => current_time('timestamp'), 'user' => $current_user->ID, 'content' => $content );

    // limit amount of revisions (with PHP4 compatibility)
    $keys = array_slice(array_keys($revisions), -abs((int) apply_filters('humanstxt_max_revisions', HUMANSTXT_MAX_REVISIONS)), count($revisions));
    $_revisions = array();
    foreach ($keys as $key) {
        $_revisions[ $key ] = $revisions[ $key ];
    }

    update_option('humanstxt_revisions', $_revisions);
}

/**
 * Replaces all valid content-variables in given string and returns it.
 *
 * @param string $string String in which content-variables should be replaced.
 * @return string $string Given string with replaced content-variables.
 */
function humanstxt_replace_variables($string)
{
    $variables = humanstxt_valid_variables();

    foreach ($variables as $variable) {

        // 1 = english; 2 = translated varname
        $varnames = array( '$' . $variable[1] . '$', '$' . $variable[2] . '$' );

        // does one of the variables occur in the string?
        if (stripos($string, $varnames[0]) !== false || stripos($string, $varnames[1]) !== false) {

            // do we have a valid callback result?
            if (($result = @call_user_func($variable[3])) !== false) {
                // replace all occurrences of the variables with callback result
                $string = str_ireplace($varnames, (string) $result, $string);
            }
        }
    }

    return $string;
}

/**
 * Returns an array of default content-variables after
 * applying the 'humanstxt_variables' filter to it.
 *
 * Each array value represents a content-variable:
 * array(string $group, string $varname, string $translated-varname,
 *   callback $function [, string $description, bool $preview = true]);
 *
 * @return array $variables Default content-variables.
 */
function humanstxt_variables()
{
    humanstxt_load_textdomain();
    require_once HUMANSTXT_PLUGIN_PATH . '/callbacks.php';

    $variables = array();
    $variables[] = array( 'wordpress', 'wp-title', /* translators: variable name for the site/blog name (title) */ __('wp-title', 'humanstxt'), 'humanstxt_callback_wpblogname', __('Name (title) of site/blog', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-tagline', /* translators: variable name for the site/blog tagline (description) */ __('wp-tagline', 'humanstxt'), 'humanstxt_callback_wptagline', __('Tagline (description) of site/blog', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-posts', /* translators: variable name for the number of published posts */ __('wp-posts', 'humanstxt'), 'humanstxt_callback_wpposts', __('Number of published posts', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-pages', /* translators: variable name for the number of published pages */ __('wp-pages', 'humanstxt'), 'humanstxt_callback_wppages', __('Number of published pages', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-authors', /* translators: variable name for the author list */ __('wp-authors', 'humanstxt'), 'humanstxt_callback_wpauthors', __('Active authors and their contact details', 'humanstxt'), false);
    $variables[] = array( 'wordpress', 'wp-lastupdate', /* translators: variable name for the "last modified" timestamp */ __('wp-lastupdate', 'humanstxt'), 'humanstxt_callback_lastupdate', __('Date of last modified post/page', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-language', /* translators: variable name for WordPress languages(s) */ __('wp-language', 'humanstxt'), 'humanstxt_callback_wplanguage', __('WordPress language(s)', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-timezone', /* translators: variable name for WordPress timezone */ __('wp-timezone', 'humanstxt'), 'humanstxt_callback_wptimezone', __('WordPress timezone', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-version', /* translators: variable name for the installed WordPress version */ __('wp-version', 'humanstxt'), 'humanstxt_callback_wpversion', __('Installed WordPress version', 'humanstxt') );
    $variables[] = array( 'wordpress', 'wp-charset', /* translators: variable name for the encoding (charset) used by WordPress */ __('wp-charset', 'humanstxt'), 'humanstxt_callback_wpcharset', __('Encoding used for pages and feeds', 'humanstxt') );

    $variables[] = array( 'server', 'server-timezone', /* translators: variable name for server timezone */ __('server-timezone', 'humanstxt'), 'humanstxt_callback_timezone', __('Server timezone', 'humanstxt') );
    $variables[] = array( 'server', 'server-ip', /* translators: variable name for server ip address */ __('server-ip', 'humanstxt'), 'humanstxt_callback_ip', __('Server IP address', 'humanstxt') );
    $variables[] = array( 'server', 'server-os', /* translators: variable name for operating system name */ __('server-os', 'humanstxt'), 'humanstxt_callback_os', __('Operating system name', 'humanstxt') );
    $variables[] = array( 'server', 'server-identity', /* translators: variable name server identification string  */ __('server-identity', 'humanstxt'), 'humanstxt_callback_server', __('Server identification', 'humanstxt') );
    $variables[] = array( 'server', 'php-version', /* translators: variable name for php parser version */ __('php-version', 'humanstxt'), 'humanstxt_callback_phpversion', __('PHP parser version', 'humanstxt') );
    $variables[] = array( 'server', 'zend-version', /* translators: variable name for zend engine version */ __('zend-version', 'humanstxt'), 'humanstxt_callback_zendversion', __('Zend Engine version', 'humanstxt') );
    $variables[] = array( 'server', 'mysql-version', /* translators: variable name for MySQL server version */ __('mysql-version', 'humanstxt'), 'humanstxt_callback_mysqlversion', __('MySQL server version', 'humanstxt') );

    $variables[] = array( 'addons', 'wp-plugins', /* translators: variable name for activated WordPress plugins */ __('wp-plugins', 'humanstxt'), 'humanstxt_callback_wpplugins', __('Activated WordPress plugins', 'humanstxt') );
    $variables[] = array( 'addons', 'wp-theme', /* translators: variable name for the summary of the active WordPress theme */ __('wp-theme', 'humanstxt'), 'humanstxt_callback_wptheme', __('Summary of the active WordPress theme', 'humanstxt') );
    $variables[] = array( 'addons', 'wp-theme-name', /* translators: variable name for the name of the active WordPress theme */ __('wp-theme-name', 'humanstxt'), 'humanstxt_callback_wptheme_name', __('Name of the active theme', 'humanstxt') );
    $variables[] = array( 'addons', 'wp-theme-version', /* translators: variable name for the version of the active WordPress theme */ __('wp-theme-version', 'humanstxt'), 'humanstxt_callback_wptheme_version', __('Version of the active theme', 'humanstxt') );
    $variables[] = array( 'addons', 'wp-theme-author', /* translators: variable name for the author name of the active WordPress theme */ __('wp-theme-author', 'humanstxt'), 'humanstxt_callback_wptheme_author', __('Author name of the active theme', 'humanstxt') );
    $variables[] = array( 'addons', 'wp-theme-author-link', /* translators: variable name for the author link of the active WordPress theme */ __('wp-theme-author-link', 'humanstxt'), 'humanstxt_callback_wptheme_author_link', __('Author link of the active theme', 'humanstxt') );

    return (array) apply_filters('humanstxt_variables', $variables);
}

/**
 * Returns an array all valid content-variables.
 *
 * @return array $variables Valid content-variables.
 */
function humanstxt_valid_variables()
{
    $variables = humanstxt_variables();

    // return empty array if $variables is empty or not an array
    if (!is_array($variables) || empty($variables)) {
        return array();
    }

    foreach ($variables as $key => $variable) {
        // delete if variable hasn't enought params
        if (count($variable) < 5) {
            unset($variables[ $key ]);
            continue;
        }
        // delete if variable callback is not a function
        if (!function_exists($variable[3])) {
            unset($variables[ $key ]);
            continue;
        }
    }

    return $variables;
}

/**
 * Returns default content of humans.txt file. Quite ugly function,
 * but fast, since we have a translated string *without* having to
 * load the plugin text-domain on every request.
 *
 * @return string Default humans.txt file content.
 */
function humanstxt_default_content()
{
    humanstxt_load_textdomain();

    /* translators: only translate the text inside angle brackets < > to keep the humans.txt international. if the variable names are translated, you may translate them here too. */
    return humanstxt_content_normalize(__(
'/* the humans responsible & colophon */
/* humanstxt.org */

/* TEAM */
	<your title>: <your name>
	Site: <website url>
	Twitter: <@username>
	Location: <city, country>

		[...]

/* THANKS */
	<name>: <link, email, @twittername, ...>

		[...]

/* SITE */
	Last update: $wp-lastupdate$
	Standards: <HTML5, CSS3, ...>
	CMS: WordPress $wp-version$ (running PHP $php-version$)
	Language: <English, Klingon, ...>
	Components: <jQuery, Typekit, Modernizr, ...>
	IDE: <Coda, Zend Studio, Photoshop, Terminal, ...>
',
        'humanstxt'
    ));
}
