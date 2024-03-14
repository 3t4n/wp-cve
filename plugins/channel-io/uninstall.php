<?php

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

if (get_option('channel_io_plugin_key') != false) {
    delete_option('channel_io_plugin_key');
}

if (get_option('channel_io_hide_default_launcher') != false) {
    delete_option('channel_io_hide_default_launcher');
}

if (get_option('channel_io_custom_launcher_selector') != false) {
    delete_option('channel_io_custom_launcher_selector');
}

if (get_option('channel_io_member_hash') != false) {
    delete_option('channel_io_member_hash');
}

if (get_option('channel_io_z_index') != false) {
    delete_option('channel_io_z_index');
}

if (get_option('channel_io_z_index') != false) {
    delete_option('channel_io_z_index');
}

?>
