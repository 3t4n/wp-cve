<?php
if (!defined('ABSPATH')) {
    exit;
}

function ke_wpform_doing_it_wrong($function, $message, $version)
{
    $message .= ' Backtrace: ' . wp_debug_backtrace_summary();

    if (is_ajax()) {
        do_action('doing_it_wrong_run', $function, $message, $version);
        error_log("{$function} was called incorrectly. {$message}. This message was added in version {$version}.");
    } else {
        _doing_it_wrong($function, $message, $version);
    }
}

function load_view($name, array $args = array(), $as_var = false)
{
    $args = apply_filters('ke_wpform_view_arguments', $args, $name);

    foreach ($args as $key => $val) {
        $$key = $val;
    }

    $file = KIRIMEMAIL_WPFORM_ABSPATH . 'views/' . $name . '.php';

    if (!$as_var) {
        include $file;

    } else {
        ob_start();
        include $file;

        return ob_get_clean();
    }
}

function get_asset($ref)
{
    $ret = plugin_dir_url(KIRIMEMAIL_WPFORM_PLUGIN_FILE) . 'assets/' . $ref;

    return ($ret);
}
