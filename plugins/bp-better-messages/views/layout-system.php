<?php
/**
 * Settings page
 */
defined( 'ABSPATH' ) || exit;?>

<div class="wrap">
    <h1><?php _ex( 'System', 'WP Admin', 'bp-better-messages' ); ?></h1>

    <div class="nav-tab-wrapper" id="bpbm-tabs">
        <a class="nav-tab nav-tab-active" id="migrate-db-tab" href="#migrate-db"><?php _ex('Database Tools', 'WP Admin', 'bp-better-messages'); ?></a>
    </div>

    <div id="migrate-db" class="bpbm-tab active"></div>
</div>
