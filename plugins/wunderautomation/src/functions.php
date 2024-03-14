<?php

use WunderAuto\Types\Actions\BaseAction;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Initialize global variables
 *
 * @return void
 */
function wa_initialize_globals()
{
    global $wa_wp_timezone_offset;

    $wa_wp_timezone_offset = false;
}

/**
 * Return the global WunderAuto instance
 *
 * @return WunderAuto\WunderAuto
 */
function wa_wa()
{
    global $wunderAuto;
    return $wunderAuto;
}

/**
 * Add Trigger class
 *
 * @param string[]|string $classes
 *
 * @return void
 */
function wa_add_trigger($classes)
{
    $wunderAuto = wa_wa();
    if (!is_array($classes)) {
        $classes = [$classes];
    }

    foreach ($classes as $class) {
        $wunderAuto->addObject('trigger', $class);
    }
}

/**
 * Add Filter class
 *
 * @param string[]|string $classes
 *
 * @return void
 */
function wa_add_filter($classes)
{
    $wunderAuto = wa_wa();
    if (!is_array($classes)) {
        $classes = [$classes];
    }

    foreach ($classes as $class) {
        $wunderAuto->addObject('filter', $class);
    }
}

/**
 * Add Action class
 *
 * @param string[]|string $classes
 *
 * @return void
 */
function wa_add_action($classes)
{
    $wunderAuto = wa_wa();
    if (!is_array($classes)) {
        $classes = [$classes];
    }

    foreach ($classes as $class) {
        $wunderAuto->addObject('action', $class);
    }
}

/**
 * Add Parameter class
 *
 * @param string[]|string $classes
 *
 * @return void
 */
function wa_add_parameter($classes)
{
    $wunderAuto = wa_wa();
    if (!is_array($classes)) {
        $classes = [$classes];
    }

    foreach ($classes as $class) {
        $wunderAuto->addObject('parameter', $class);
    }
}

/**
 * Add Settings class
 *
 * @param string[]|string $classes
 *
 * @return void
 */
function wa_add_settings($classes)
{
    $wunderAuto = wa_wa();
    if (!is_array($classes)) {
        $classes = [$classes];
    }

    foreach ($classes as $class) {
        $wunderAuto->addObject('settings', $class);
    }
}

/**
 * Return array of Filter object instances
 *
 * @param string $class
 *
 * @return BaseFilter|null
 */
function wa_get_filter($class)
{
    $wunderAuto = wa_wa();
    $object     = $wunderAuto->getObject('filter', $class);
    return $object instanceof BaseFilter ? $object : null;
}

/**
 * Return array of Action object instances
 *
 * @param string $class
 *
 * @return BaseAction|null
 */
function wa_get_action($class)
{
    $wunderAuto = wa_wa();
    $object     = $wunderAuto->getObject('action', $class);
    return $object instanceof BaseAction ? $object : null;
}

/**
 * Adds a known object type
 *
 * @param string      $type
 * @param string      $description
 * @param bool        $transfer
 * @param string|null $parent
 *
 * @return void
 */
function wa_add_object_type($type, $description, $transfer = true, $parent = null)
{
    $wunderAuto = wa_wa();
    $wunderAuto->addObjectType($type, $description, $transfer, $parent);
}

/**
 * Wraps the global define DOING_AUTOSAVE
 *
 * @return bool
 */
function wa_doing_autosave()
{
    return defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
}

/**
 * @return WP_User
 */
function wa_empty_wp_user()
{
    return new \WP_User();
}

/**
 * @param array<string, int|string> $args
 *
 * @return WP_Post
 */
function wa_wp_new_post($args = [])
{
    return new \WP_Post((object)$args);
}

/**
 * Return a link back to Wundermatics homepage
 *
 * @param string $path
 * @param string $utmSource
 *
 * @return string
 */
function wa_make_link($path, $utmSource = '')
{
    return 'https://www.wundermatics.com' . $path . $utmSource;
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string                $templateName Template name.
 * @param array<string, string> $args         Arguments. (default: array).
 * @param string                $templatePath Template path. (default: '').
 * @param string                $defaultPath  Default path. (default: '').
 *
 * @return void
 */
function wa_get_template($templateName, $args = [], $templatePath = '', $defaultPath = '')
{
    global $wunderautomation_version;

    $cacheKey = sanitize_key(join('-', ['template', $templateName, $templatePath, $defaultPath]));
    $template = (string)wp_cache_get($cacheKey, 'wunderauto');

    if (!$template) {
        $template = wa_locate_template($templateName, $templatePath, $defaultPath);
        wp_cache_set($cacheKey, $template, 'wunderauto');
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $filterTemplate = apply_filters('wa_get_template', $template, $templateName, $args, $templatePath, $defaultPath);

    if ($filterTemplate !== $template) {
        if (!file_exists($filterTemplate)) {
            /* translators: %s template */
            _doing_it_wrong(
                __FUNCTION__,
                sprintf(
                    __('%s does not exist.', 'wunderauto'),
                    '<code>' . $template . '</code>'
                ),
                $wunderautomation_version
            );
            return;
        }
        $template = $filterTemplate;
    }

    $action_args = [
        'templateName' => $templateName,
        'templatePath' => $templatePath,
        'located'      => $template,
        'args'         => $args,
    ];

    if (!empty($args) && is_array($args)) {
        if (isset($args['action_args'])) {
            _doing_it_wrong(
                __FUNCTION__,
                __('action_args should not be overwritten when calling wa_get_template.', 'wunderauto'),
                $wunderautomation_version
            );
            unset($args['action_args']);
        }
        extract($args); // @codingStandardsIgnoreLine
    }

    do_action(
        'wunderautomation_before_template_part',
        $action_args['templateName'],
        $action_args['templatePath'],
        $action_args['located'],
        $action_args['args']
    );

    include $action_args['located'];

    do_action(
        'wunderautomation_after_template_part',
        $action_args['templateName'],
        $action_args['templatePath'],
        $action_args['located'],
        $action_args['args']
    );
}

/**
 * Get the timezone offset in hours
 *
 * @throws Exception
 * @return int
 */
function wa_get_wp_timezone_offset()
{
    global $wa_wp_timezone_offset;

    // Try getting timezone via time zone string
    if ($wa_wp_timezone_offset === false) {
        $timezoneString = get_option('timezone_string');
        if (!empty($timezoneString)) {
            $time                  = new \DateTime('now', new \DateTimeZone($timezoneString));
            $wa_wp_timezone_offset = $time->getOffset();
        }
    }

    // Try getting timezone via gmt offset
    if ($wa_wp_timezone_offset === false) {
        $offset                = get_option('gmt_offset');
        $wa_wp_timezone_offset = $offset * 3600;
    }

    // Default to zero
    if (!$wa_wp_timezone_offset) {
        $wa_wp_timezone_offset = 0;
    }

    return (int)$wa_wp_timezone_offset;
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$templatePath/$templateName
 * yourtheme/$templateName
 * $defaultPath/$templateName
 *
 * @param string $templateName Template name.
 * @param string $templatePath Template path. (default: '').
 * @param string $defaultPath  Default path. (default: '').
 *
 * @return string
 */
function wa_locate_template($templateName, $templatePath = '', $defaultPath = '')
{
    if (!$templatePath) {
        $templatePath = apply_filters('wunderautomation_template_path', 'wunderautomation/');
    }

    if (!$defaultPath) {
        $defaultPath = untrailingslashit(plugin_dir_path(WUNDERAUTO_FILE)) . '/templates/';
    }

    // Look within passed path within the theme - this is priority.
    $template = locate_template([
        trailingslashit($templatePath) . $templateName,
        $templateName,
    ]);

    // Get default template/.
    if (!$template) {
        $template = $defaultPath . $templateName;
    }

    // Return what we found.
    return apply_filters('wunderautomation_locate_template', $template, $templateName, $templatePath);
}

/**
 * WordPress handler for wp_die()
 *
 * @param string                $message
 * @param string                $title
 * @param array<string, string> $args
 *
 * @return void
 */
function wa_die_default_requests($message, $title, $args)
{
    list($message, $title, $parsed_args) = _wp_die_process_input($message, $title, $args);

    if (!headers_sent()) {
        header("Content-Type: text/plain");
        if (null !== $parsed_args['response']) {
            status_header($parsed_args['response']);
        }
        nocache_headers();
    }

    echo "code: " . esc_html($parsed_args['code']) . "\n";
    echo "message: " . esc_html($message) . "\n";
    echo "status: " . esc_html($parsed_args['response']) . "\n";

    if ($parsed_args['exit']) {
        die();
    }
}

/**
 * Wrapper for getting the global $wpdb
 *
 * @return wpdb
 */
function wa_get_wpdb()
{
    global $wpdb;
    return $wpdb;
}

/**
 * Wrapper for require_once
 *
 * @param string $file
 *
 * @return void
 */
function wa_require_once($file)
{
    require_once $file;
}

/**
 * Wrapper for getting the global wp_post_statuses array
 *
 * @return array<string, mixed>
 */
function wa_get_wp_post_statuses()
{
    global $wp_post_statuses;
    return $wp_post_statuses;
}

/**
 * Wrapper for getting the global wp_post_statuses array
 *
 * @return array<int, WP_Taxonomy>
 */
function wa_get_taxonomies()
{
    global $wp_taxonomies;
    return $wp_taxonomies;
}
