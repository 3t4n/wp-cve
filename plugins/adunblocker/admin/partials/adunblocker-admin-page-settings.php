<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://digitalapps.com
 * @since      1.0.0
 *
 * @package    Adblock Detect
 * @subpackage Adblock Detect/admin/partials
 */

?>

<div class="wrap">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content" class="daabd-admin-body">
                <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

                <h3 class="nav-tab-wrapper">
                    <a href="<?php echo network_admin_url( 'admin.php?page=' . $this->plugin_name . '-general-settings-page' . '&tab=general_settings' ); ?>" class="nav-tab <?php echo ( $this->get_current_tab() == 'general_settings' ) ? 'nav-tab-active' : ''; ?>">General</a>
                    <a href="<?php echo network_admin_url( 'admin.php?page=' . $this->plugin_name . '-general-settings-page' . '&tab=content_settings' ); ?>" class="nav-tab <?php echo ( $this->get_current_tab() == 'content_settings' ) ? 'nav-tab-active' : ''; ?>">Content</a>
                    <a href="<?php echo network_admin_url( 'admin.php?page=' . $this->plugin_name . '-general-settings-page' . '&tab=style_settings' ); ?>" class="nav-tab <?php echo ( $this->get_current_tab() == 'style_settings' ) ? 'nav-tab-active' : ''; ?>">Style</a>
                </h3>

                <div class="notice notice-success is-dismissible"> 
                    <p><strong>Adblockers must be disabled for the UI to work correctly.</strong></p>
                </div>

                <form method="post" action="options.php"><?php

                    if( $this->get_current_tab() == 'content_settings' ) {

                        settings_fields( $this->plugin_name . '-content-settings-group' );
                        do_settings_sections( $this->plugin_name . '-content-settings-page' );

                    }
                    elseif( $this->get_current_tab() == 'style_settings' ) {

                        settings_fields( $this->plugin_name . '-style-settings-group' );
                        do_settings_sections( $this->plugin_name . '-style-settings-page' );
                        submit_button( 'Save Settings' );

                    }
                    else {

                        settings_fields( $this->plugin_name . '-general-settings-group' );
                        do_settings_sections( $this->plugin_name . '-general-settings-page' );
                        submit_button( 'Save Settings' );

                    }

                ?></form>
            </div>
            <?php $this->page_sidebar(); ?>
        <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>