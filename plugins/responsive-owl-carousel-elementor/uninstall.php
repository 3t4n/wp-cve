<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'owce_version' );
delete_option( 'owce_installed' );
