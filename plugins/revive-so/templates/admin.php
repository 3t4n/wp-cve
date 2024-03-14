<?php
/**
 * The Main dashboard file.
 *
 */
defined( 'ABSPATH' ) || exit;
?>

<div id="reviveso-nav-container" class="reviveso-admin-toolbar">
	<h2>Revive.so<span class="title-count<?php echo esc_attr( $class_name ); ?>"><?php echo esc_html( $head_tag ); ?></span></h2>
    <div class="top-sharebar">
        <a class="share-btn rate-btn no-popup" href="https://wordpress.org/support/plugin/revive-so/reviews/?filter=5#new-post" target="_blank" title="<?php esc_html_e( 'Please rate 5 stars if you like Reviveso', 'revive-so' ); ?>"><span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Rate 5 stars', 'revive-so' ); ?></a>
    </div>
    <div class="reviveso-main-nav">
        <?php $this->render_settings_tabs(); ?>
    </div>
</div>
<div class="wrap reviveso-wrap" data-reload="no">
    <div id="post-body-content" class="reviveso-metaboxes">
        <?php

            $this->render_settings();

            do_action( 'reviveso_dashboard_settings_section' );
        ?>
    </div>
</div>