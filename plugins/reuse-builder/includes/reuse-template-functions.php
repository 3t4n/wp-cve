<?php

/**
 * Get template part (for templates like the post-loop).
 *
 * @param mixed  $slug
 * @param string $name (default: '')
 */
function reuse_builder_get_template_part($slug, $name = '')
{
    $template = '';

    // Look in yourtheme/slug-name.php and yourtheme/scholar/slug-name.php
    if ($name && !SCWP_TEMPLATE_DEBUG_MODE) {
        $template = locate_template(array("{$slug}-{$name}.php", reuse_builder()->template_path()."{$slug}-{$name}.php"));
    }

    // Get default slug-name.php
    if (!$template && $name && file_exists(reuse_builder()->plugin_path()."/templates/{$slug}-{$name}.php")) {
        $template = reuse_builder()->plugin_path()."/templates/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/scholar/slug.php
    if (!$template && !SCWP_TEMPLATE_DEBUG_MODE) {
        $template = locate_template(array("{$slug}.php", reuse_builder()->template_path()."{$slug}.php"));
    }

    // Allow 3rd party plugin filter template file from their plugin
    if ((!$template && SCWP_TEMPLATE_DEBUG_MODE) || $template) {
        $template = apply_filters('reuse_builder_get_template_part', $template, $slug, $name);
    }

    if ($template) {
        load_template($template, false);
    }
}

/**
 * Get other templates.
 *
 * @param string $template_name
 * @param array  $args          (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path  (default: '')
 */
function reuseb_get_template($template_name, $args = array(), $template_path = '', $default_path = '')
{
    if ($args && is_array($args)) {
        extract($args);
    }

    $located = reuseb_locate_template($template_name, $template_path, $default_path);

    if (!file_exists($located)) {
        _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '1.0');

        return;
    }

    // Allow 3rd party plugin filter template file from their plugin
    $located = apply_filters('reuseb_get_template', $located, $template_name, $args, $template_path, $default_path);

    do_action('redqfw_before_template_part', $template_name, $template_path, $located, $args);

    include $located;

    do_action('redqfw_after_template_part', $template_name, $template_path, $located, $args);
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /   $template_name
 *      yourtheme       /   $template_name
 *      $default_path   /   $template_name
 *
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path  (default: '')
 *
 * @return string
 */
function reuseb_locate_template($template_name, $template_path = '', $default_path = '')
{
    if (!$template_path) {
        $template_path = reuse_builder()->template_path();
    }

    if (!$default_path) {
        $default_path = reuse_builder()->plugin_path().'/templates/';
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template(
        array(
            trailingslashit($template_path).$template_name,
            $template_name,
        )
    );

    // Get default template
    if (!$template || SCWP_TEMPLATE_DEBUG_MODE) {
        $template = $default_path.$template_name;
    }

    // Return what we found
    return apply_filters('reuseb_locate_template', $template, $template_name, $template_path);
}

/**
 * Enables template debug mode.
 */
function reuseb_template_debug_mode()
{
    if (!defined('SCWP_TEMPLATE_DEBUG_MODE')) {
        $status_options = get_option('redq_wwc_status_options', array());
        if (!empty($status_options['template_debug_mode']) && current_user_can('manage_options')) {
            define('SCWP_TEMPLATE_DEBUG_MODE', true);
        } else {
            define('SCWP_TEMPLATE_DEBUG_MODE', false);
        }
    }
}
add_action('after_setup_theme', 'reuseb_template_debug_mode', 20);

/**
 * Array Partition // need to remove this from here.
 */
function reuseb_partition($list, $p)
{
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $p; ++$px) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }

    return $partition;
}

if (!function_exists('reuseb_wpex_fix_shortcodes')) {
    function reuseb_wpex_fix_shortcodes($content)
    {
        $array = array(
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']',
        );
        $content = strtr($content, $array);

        return $content;
    }
    add_filter('the_content', 'reuseb_wpex_fix_shortcodes');
}

function reuseb_get_all_taxonomies()
{
    $restricted_taxonomies = array(
        'nav_menu',
        'link_category',
        'post_format',
    );
    $args = array();
    $output = 'objects'; // or objects
    $operator = 'or'; // 'and' or 'or'
    $taxonomies = get_taxonomies($args, $output, $operator);
    $formatted_taxonomies = array();
    if ($taxonomies) {
        foreach ($taxonomies as $key => $taxonomy) {
            if (!in_array($key, $restricted_taxonomies)) {
                $formatted_taxonomies[$taxonomy->name] = $taxonomy->labels->singular_name;
            }
        }
    }

    return $formatted_taxonomies;
}
function reuseb_get_all_post_types()
{
    $restricted_post_types = array(
        'userplace_faq',
        'page',
        'reuseb_template',
        'userplace_component',
        'reuseb_taxonomy',
        'reuseb_term_metabox',
        'reuseb_metabox',
        'reuseb_form_builder',
        'userplace_plan',
        'userplace_rb_post',
        'reuseb_post_type',
        'userplace_console',
        'reactive_builder',
        'reactive_grid',
        'notification',
    );

    $args = array(
        'public' => true,
    );
    $output = 'objects'; // 'names' or 'objects' (default: 'names')
    $operator = 'and'; // 'and' or 'or' (default: 'and')
    $post_types = get_post_types($args, $output, $operator);
    $formatted_post_types = array();
    foreach ($post_types as $key => $post_type) {
        if (!in_array($key, $restricted_post_types)) {
            $formatted_post_types[$post_type->name] = $post_type->labels->singular_name;
        }
    }

    return $formatted_post_types;
}

// remove_filter( 'the_content', 'wpautop' );
// add_filter( 'the_content', 'do_shortcode', 100 );
