<?php // Host Header Injection Fix - Uninstall Remove Options

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

delete_option('hhif_options');
