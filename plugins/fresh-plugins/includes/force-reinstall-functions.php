<?php

/**
 * Plugin misc functions
 * @since 1.0.1
 */

/**
 * Get plugin slug
 *
 * @since 1.0.1
 * @return void
 */
function force_reinstall_name()
{
    return "force-reinstall";
}

/**
 * Get a url targetting self
 *
 * @param array $arguments
 * @since 1.0.1
 * @return void
 */
function force_reinstall_target_self($arguments)
{
    $arguments = array_merge(
        array(
            "action" => force_reinstall_name(),
            force_reinstall_name() . "-nonce" => wp_create_nonce(force_reinstall_name())
        ),
        filter_input_array(INPUT_GET) ?: array(),
        $arguments
    );

    return admin_url(get_current_screen()->base . ".php") . "?" . http_build_query($arguments);
}