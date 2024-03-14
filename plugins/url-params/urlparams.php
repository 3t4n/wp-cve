<?php
/*
Plugin Name: URL Params
Plugin URI: http://asandia.com/wordpress-plugins/urlparams/
Description: Short Code to grab any URL Parameter
Version: 2.5
Author: Jeremy B. Shapiro
Author URI: http://www.asandia.com/
*/

/*
URL Params (Wordpress Plugin)
Copyright (C) 2011-2023 Jeremy Shapiro

*/

// error_reporting(E_ALL); // Helpful for checking for warnings that are TYPICALLY hidden but may be present on some installs

// Tell WordPress to register the shortcodes
add_shortcode("urlparam", "urlparam");
add_shortcode("ifurlparam", "ifurlparam");

register_uninstall_hook(__FILE__, 'uninstall_urlparams');

// If we're an admin, add a URL Params options page under Settings
if (is_admin()) {
    add_action('admin_menu', 'urlparams_admin_menu');
    add_action('admin_init', 'admin_init_urlparams');
}
function urlparams_admin_menu()
{
    add_options_page('URL Params', 'URL Params', 'manage_options', 'urlparams', 'options_page_urlparams');
}

// Add a link to the Options page from the Plugins page
add_filter( 'plugin_action_links', 'urlparams_plugin_action_links', 10, 2 );
function urlparams_plugin_action_links( $links, $file )
{
    if ( $file == plugin_basename( __DIR__ .'/urlparams.php' ) ) {
        $links[] = '<a href="options-general.php?page=urlparams"><b>'.__('Settings').'</b></a>';
    }
    return $links;
}

function options_page_urlparams()
{
    include __DIR__ .'/options.php';
}

function urlparam($attributes, $content) {
    $defaults = array(
        'param'          => '',
        'default'        => '',
        'dateformat'	 => '',
        'attr'           => '',
        'htmltag'        => false,
    );

    // If $attributes is an array, we merge it with our defaults
    if(is_array($attributes)) {
        // We used to use shortcode_atts(), but that would nuke an extra attributes that we don't know about but want. array_merge() keeps them all.
        $attributes = array_merge($defaults, $attributes);
    } else {
        $attributes = $defaults;
    }

    $params = preg_split('/,\s*/',$attributes['param']);

    $return = false;

    foreach($params as $param)
    {
        if(!$return
            && array_key_exists($param, $_REQUEST)
            && ($rawText = $_REQUEST[$param])
        ) {
            if(($attributes['dateformat'] != '')
                && strtotime($rawText)
            ) {
                $return = date($attributes['dateformat'], strtotime($rawText));
            } else {
                $return = esc_html($rawText);
            }
        }
    }

    if(!$return) {
        $return = $attributes['default'];
    }

    if($attr = $attributes['attr']) {
        $return = ' ' . sanitize_key($attr) . '="' . esc_attr($return) . '" ';

        if($attributes['htmltag']) {
            $tagName = $attributes['htmltag'];

            foreach(array_keys($defaults) as $key) {
                unset($attributes[$key]);
            }
            $otherAttributes = "";
            foreach($attributes as $key => $val) {
                $otherAttributes .= ' '.sanitize_key($key).'="'.esc_attr($val).'" ';
            }

            $return = "<$tagName $otherAttributes $return".($content ? ">$content</$tagName>" : "/>");
        }
    }

    $allowedHtml = wp_kses_allowed_html('post');

    // Do we have any custom tags set? Add those
    if($customTags = urlparams_get_customtags()) {
        foreach($customTags as $customTag => $attributes) {
            // Is this already an allowed tag? Merge our approved attributes in, too
            if(array_key_exists($customTag, $allowedHtml)) {
                $allowedHtml[$customTag] = array_merge($allowedHtml[$customTag], $attributes);
            } else {
                $allowedHtml[$customTag] = $attributes;
            }
        }
    }

    return wp_kses($return, $allowedHtml);
}

/*
 * If 'param' is found and 'is' is set, compare the two and display the contact if they match
 * If 'param' is found and 'is' isn't set, display the content between the tags
 * If 'param' is not found and 'empty' is set, display the content between the tags
 *
 */
function ifurlparam($attributes, $content) {
    $attributes = shortcode_atts(array(
        'param'           => '',
        'empty'          => false,
        'is'            => false,
    ), $attributes);

    $params = preg_split('/,\s*/',$attributes['param']);

    foreach($params as $param)
    {
        if(isset($_REQUEST[$param])
            && ($value = $_REQUEST[$param]))
        {
            if($attributes['empty'])
            {
                return '';
            } elseif(!($isAttribute = $attributes['is'])
                || ($value == $isAttribute)
            ) {
                return do_shortcode($content);
            }
        }
    }

    if ($attributes['empty'])
    {
        return do_shortcode($content);
    }

    return '';
}

function uninstall_urlparams() {
    delete_option('urlparams_customtags');

}

function admin_init_urlparams()
{
    register_setting('urlparams', 'urlparams_customtags', [
        'sanitize_callback' => 'urlparams_sanitize_customtags',
    ]);
}

/**
 *
 * Sanitize our custom tag options
 *
 * @param string $newCustomTags
 * @return string
 */
function urlparams_sanitize_customtags($newCustomTags) {
    $isValid = true;

    $lines = explode("\n", $newCustomTags);
    $validLines = [];

    foreach($lines as $line) {
        $lineParts = explode(':', $line);
        if(count($lineParts) !== 2) {
            $isValid = false;
            continue;
        }

        if(!($tagName = trim(strtolower($lineParts[0])))) {
            $isValid = false;
            continue;
        }

        if(!($attributes = explode(',', $lineParts[1]))) {
            $isValid = false;
            continue;
        }

        $validAttributes = [];
        foreach($attributes as $attribute) {
            if($attribute = strtolower(trim($attribute))) {
                $validAttributes[] = $attribute;
            }
        }

        $validAttributes = array_unique($validAttributes);

        if(!$validAttributes) {
            $isValid = false;
            continue;
        }

        $validLines[] = $tagName.': '.implode(', ', $validAttributes);
    }

    if(!$isValid) {
        add_settings_error('urlparams_customtags', 'urlparams_customtags', __('Invalid Custom Tags were removed', 'urlparams'), 'warning');
    }

    return join("\n", $validLines);
}

/**
 *
 * Return our array of custom tag options
 *
 * @return array
 */
function urlparams_get_customtags() {
    $lines = explode("\n", get_option('urlparams_customtags'));
    $customTags = [];

    foreach($lines as $line) {
        $lineParts = explode(':', $line);
        if(count($lineParts) !== 2) {
            continue;
        }

        $tagName = trim($lineParts[0]);
        $attributes = explode(',', $lineParts[1]);

        $customTags[$tagName] = [];

        foreach($attributes as $attribute) {
            $customTags[$tagName][trim($attribute)] = true;
        }
    }

    return $customTags;
}

?>
