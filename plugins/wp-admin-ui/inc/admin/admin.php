<?php

defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

class wpui_options
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	
    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 10 );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
	
	public function activate() {
        update_option($this->wpui_options, $this->data);
    }

    public function deactivate() {
        delete_option($this->wpui_options);
    }
	
    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_menu_page('WP UI Option Page', 'WP Admin UI', 'manage_options', 'wpui-option', array( $this, 'create_admin_page' ), 'dashicons-admin-generic', 90);
        add_submenu_page('wpui-option', __('Login','wp-admin-ui'), __('Login','wp-admin-ui'), 'manage_options', 'wpui-login', array( $this, 'wpui_login_page' ));
        add_submenu_page('wpui-option', __('Global','wp-admin-ui'), __('Global','wp-admin-ui'), 'manage_options', 'wpui-global', array( $this, 'wpui_global_page'));
        add_submenu_page('wpui-option', __('Dashboard','wp-admin-ui'), __('Dashboard','wp-admin-ui'), 'manage_options', 'wpui-dashboard', array( $this,'wpui_dashboard_page'));
        add_submenu_page('wpui-option', __('Admin Menu','wp-admin-ui'), __('Admin Menu','wp-admin-ui'), 'manage_options', 'wpui-admin-menu', array( $this,'wpui_admin_menu_page'));
        add_submenu_page('wpui-option', __('Admin Bar','wp-admin-ui'), __('Admin Bar','wp-admin-ui'), 'manage_options', 'wpui-admin-bar', array( $this,'wpui_admin_bar_page'));
        add_submenu_page('wpui-option', __('Editor','wp-admin-ui'), __('Editor','wp-admin-ui'), 'manage_options', 'wpui-editor', array( $this,'wpui_editor_page'));
        add_submenu_page('wpui-option', __('Media Library','wp-admin-ui'), __('Media Library','wp-admin-ui'), 'manage_options', 'wpui-library', array( $this,'wpui_library_page'));
        add_submenu_page('wpui-option', __('Profile','wp-admin-ui'), __('Profile','wp-admin-ui'), 'manage_options', 'wpui-profil', array( $this,'wpui_profil_page'));
        add_submenu_page('wpui-option', __('Role Manager','wp-admin-ui'), __('Role Manager','wp-admin-ui'), 'manage_options', 'wpui-roles', array( $this,'wpui_roles_page'));
        add_submenu_page('wpui-option', __('Import / Export / Reset settings','wp-admin-ui'), __('Import / Export / Reset','wp-admin-ui'), 'manage_options', 'wpui-import-export', array( $this,'wpui_import_export_page'));
    }

    function wpui_login_page(){
        $this->options = get_option( 'wpui_login_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_login_option_group' );
        do_settings_sections( 'wpui-settings-admin-login' );
        submit_button(); ?>
        </form>
        <?php
    }
    
    function wpui_global_page(){
        $this->options = get_option( 'wpui_global_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $current_tab = '';
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_global_option_group' );
        ?>
    
         <div id="wpui-tabs" class="wrap">
         <?php 
            
            $plugin_settings_tabs = array(
                'tab_wpui_global_display' => __( "Display", "wp-admin-ui" ), 
                'tab_wpui_global_advanced' => __( "Advanced", "wp-admin-ui" ), 
                'tab_wpui_global_updates' => __( "Updates", "wp-admin-ui" ), 
                'tab_wpui_global_front' => __( "Front", "wp-admin-ui" ), 
                'tab_wpui_global_debug' => __( "Debug", "wp-admin-ui" ), 
            );

            echo '<h2 class="nav-tab-wrapper">';
            foreach ( $plugin_settings_tabs as $tab_key => $tab_caption ) {
                echo '<a id="'. $tab_key .'-tab" class="nav-tab" href="?page=wpui-global#tab=' . $tab_key . '">' . $tab_caption . '</a>'; 
            }
            echo '</h2>';
        ?>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_global_display') { echo 'active'; } ?>" id="tab_wpui_global_display"><?php do_settings_sections( 'wpui-settings-admin-global-display' ); ?></div>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_global_advanced') { echo 'active'; } ?>" id="tab_wpui_global_advanced"><?php do_settings_sections( 'wpui-settings-admin-global-advanced' ); ?></div>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_global_updates') { echo 'active'; } ?>" id="tab_wpui_global_updates"><?php do_settings_sections( 'wpui-settings-admin-global-updates' ); ?></div>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_global_front') { echo 'active'; } ?>" id="tab_wpui_global_front"><?php do_settings_sections( 'wpui-settings-admin-global-front' ); ?></div>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_global_debug') { echo 'active'; } ?>" id="tab_wpui_global_debug"><?php do_settings_sections( 'wpui-settings-admin-global-debug' ); ?></div>
        </div>

        <?php submit_button(); ?>
        </form>
        <?php
    }
    function wpui_dashboard_page(){
        $this->options = get_option( 'wpui_dashboard_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_dashboard_option_group' );
        do_settings_sections( 'wpui-settings-admin-dashboard' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_admin_menu_page(){
        $this->options = get_option( 'wpui_admin_menu_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_admin_menu_option_group' );
        do_settings_sections( 'wpui-settings-admin-menu' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_admin_bar_page(){
        $this->options = get_option( 'wpui_admin_bar_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_admin_bar_option_group' );
        do_settings_sections( 'wpui-settings-admin-bar' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_editor_page(){
        $this->options = get_option( 'wpui_editor_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_editor_option_group' );
        do_settings_sections( 'wpui-settings-admin-editor' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_library_page(){
        $this->options = get_option( 'wpui_library_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $current_tab = '';
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_library_option_group' ); ?>

        <div id="wpui-tabs" class="wrap">
         <?php 
            
            $plugin_settings_tabs = array(
                'tab_wpui_library_base' => __( "Base", "wp-admin-ui" ), 
                'tab_wpui_library_filters' => __( "Filters", "wp-admin-ui" ), 
            );

            echo '<h2 class="nav-tab-wrapper">';
            foreach ( $plugin_settings_tabs as $tab_key => $tab_caption ) {
                echo '<a id="'. $tab_key .'-tab" class="nav-tab" href="?page=wpui-library#tab=' . $tab_key . '">' . $tab_caption . '</a>'; 
            }
            echo '</h2>';
        ?>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_library_base') { echo 'active'; } ?>" id="tab_wpui_library_base"><?php do_settings_sections( 'wpui-settings-admin-library-base' ); ?></div>
            <div class="wpui-tab <?php if ($current_tab == 'tab_wpui_library_filters') { echo 'active'; } ?>" id="tab_wpui_library_filters"><?php do_settings_sections( 'wpui-settings-admin-library-filters' ); ?></div>
        </div>

        <?php do_settings_sections( 'wpui-settings-admin-library' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_profil_page(){
        $this->options = get_option( 'wpui_profil_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_profil_option_group' );
        do_settings_sections( 'wpui-settings-admin-profil' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_roles_page(){
        $this->options = get_option( 'wpui_roles_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php 
        global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        settings_fields( 'wpui_roles_option_group' );
        do_settings_sections( 'wpui-settings-admin-roles' );
        submit_button(); ?>
        </form>
        <?php
    }    
    function wpui_import_export_page(){
        $this->options = get_option( 'wpui_import_export_option_name' );
        ?>
        <?php global $wp_version, $title;
        $tag = version_compare( $wp_version, '4.4' ) >= 0 ? 'h1' : 'h2';
        echo '<'.$tag.'>'.$title.'</'.$tag.'>';
        ?>
        <h3><span><?php _e( 'Import / Export Settings', 'wp-admin-ui' ); ?></span></h3>
        <?php print __('Import / Export WP Admin UI settings from site to site', 'wp-admin-ui'); ?>
        <div class="metabox-holder">
            <div class="postbox">
                <h3><span><?php _e( 'Export Settings', 'wp-admin-ui' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'wp-admin-ui' ); ?></p>
                    <form method="post">
                        <p><input type="hidden" name="wpui_action" value="export_settings" /></p>
                        <p>
                            <?php wp_nonce_field( 'wpui_export_nonce', 'wpui_export_nonce' ); ?>
                            <?php submit_button( __( 'Export', 'wp-admin-ui' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e( 'Import Settings', 'wp-admin-ui' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'wp-admin-ui' ); ?></p>
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="file" name="import_file"/>
                        </p>
                        <p>
                            <input type="hidden" name="wpui_action" value="import_settings" />
                            <?php wp_nonce_field( 'wpui_import_nonce', 'wpui_import_nonce' ); ?>
                            <?php submit_button( __( 'Import', 'wp-admin-ui' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e( 'Reset All Settings', 'wp-admin-ui' ); ?></span></h3>
                <div class="inside">
                    <p style="color:red"><?php _e( '<strong>WARNING:</strong> Delete all options related to WP Admin UI in your database.', 'wp-admin-ui' ); ?></p>
                     <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="hidden" name="wpui_action" value="reset_settings" />
                            <?php wp_nonce_field( 'wpui_reset_nonce', 'wpui_reset_nonce' ); ?>
                            <?php submit_button( __( 'Reset settings', 'wp-admin-ui' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
	
        // Set class property
        $this->options = get_option( 'wpui_option_name' );
        ?>
            <div id="wpui-header">
				<div id="wpui-admin">
					<h1>
						<span class="screen-reader-text"><?php _e( 'WP Admin UI', 'wp-admin-ui' ); ?></span>
                        <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                            <span class="wpui-info-version">
                                <strong>
                                    <?php _e('PRO', 'wp-admin-ui'); ?>
                                    <?php echo WPUIPRO_VERSION; ?>
                                </strong>
                            </span>
                        <?php } else { ?>
                            <span class="wpui-info-version"><?php echo WPUI_VERSION; ?></span>
                        <?php } ?>
					</h1>
					<div id="wpui-notice">
                        <a href="https://www.seopress.org/?utm_source=plugin&utm_medium=banner&utm_campaign=wpadminui" target="_blank"><img class="wpalacarte-banner" width="450" height="350" src="<?php echo plugins_url('assets/img/seopress.png', dirname(dirname(__FILE__))); ?>" /></a>
						<h2><?php _e( 'The best WordPress SEO plugin!', 'wp-admin-ui' ); ?></h2>
                        <p class="small">
                            <span class="dashicons dashicons-wordpress"></span>
                            <?php _e( 'You like WP Admin UI? Don\'t forget to rate it 5 stars!', 'wp-admin-ui' ); ?>

                            <div class="wporg-ratings rating-stars">
                                <a href="//wordpress.org/support/view/plugin-reviews/wp-admin-ui?rate=1#postform" data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                <a href="//wordpress.org/support/view/plugin-reviews/wp-admin-ui?rate=2#postform" data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                <a href="//wordpress.org/support/view/plugin-reviews/wp-admin-ui?rate=3#postform" data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                <a href="//wordpress.org/support/view/plugin-reviews/wp-admin-ui?rate=4#postform" data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                <a href="//wordpress.org/support/view/plugin-reviews/wp-admin-ui?rate=5#postform" data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            </div>
                            <script>
                                jQuery(document).ready( function($) {
                                    $('.rating-stars').find('a').hover(
                                        function() {
                                            $(this).nextAll('a').children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                                            $(this).prevAll('a').children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                            $(this).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                        }, function() {
                                            var rating = $('input#rating').val();
                                            if (rating) {
                                                var list = $('.rating-stars a');
                                                list.children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                                                list.slice(0, rating).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                            }
                                        }
                                    );
                                });
                            </script>
                        </p>
						<p class="small">
							<a href="https://twitter.com/wp_seopress" target="_blank"><div class="dashicons dashicons-twitter"></div><?php _e( 'Follow us on Twitter!', 'wp-admin-ui' ); ?></a>
                            &nbsp;
                            <a href="https://www.wpadminui.net/" target="_blank"><div class="dashicons dashicons-info"></div><?php _e( 'Our website', 'wp-admin-ui' ); ?></a>
                            &nbsp;
                            <a href="https://www.wpadminui.net/support" target="_blank"><div class="dashicons dashicons-sos"></div><?php _e( 'Knowledge base', 'wp-admin-ui' ); ?></a>
						</p>
					</div>
                    <table class="wpui-page-list" cellspacing="16">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-lock"></span>
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Login','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Custom logo, custom background image, custom css...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-login' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-admin-site"></span>                                    
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Global','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Custom footer credits, remove screen options, revisions...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-global' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-dashboard"></span>                                    
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Dashboard','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Remove widgets, remove welcome panel...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-dashboard' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-menu"></span>                                   
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Admin Menu','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Hide menus/submenus, reorder them with drag and drop...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-admin-menu' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-plus"></span>                                   
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Admin Bar','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Remove items in admin bar...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-admin-bar' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-editor-kitchensink"></span>                                   
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Editor','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Full TinyMCE, add new buttons, add quicktags...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-editor' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                                    <td>  
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-index-card"></span>                                 
                                        </div> 
                                        <span class="inner">
                                            <h4><?php _e('Metaboxes','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Hide metaboxes from your custom post types...','wp-admin-ui'); ?></p>
                                            <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-metaboxes' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } else { ?>
                                    <td>   
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-index-card"></span>                                    
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Metaboxes','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Hide metaboxes...','wp-admin-ui'); ?></p>
                                            <a class="button-primary" href="https://wpadminui.net/pro" target="_blank"><span class="dashicons dashicons-cart"></span><?php _e('Get it','wp-admin-ui'); ?></a>                                        
                                        </span>
                                    </td>
                                <?php } ?>
                                <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-list-view"></span>                                  
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Columns','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Hide columns from list view, add post ID column...','wp-admin-ui'); ?></p>
                                            <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-columns' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-list-view"></span>                                   
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Columns','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Hide columns from list view, add post ID column...','wp-admin-ui'); ?></p>
                                            <a class="button-primary" href="https://wpadminui.net/pro" target="_blank"><span class="dashicons dashicons-cart"></span><?php _e('Get it','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-admin-media"></span>                                    
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Library','wp-admin-ui'); ?></h4>
                                        <p><?php _e('JPEG quality, add filters type (PDF, DOCX...)','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-library' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-admin-users"></span>                                    
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Profile','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Color scheme, add custom field in profile...','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-profil' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                                    <td>   
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-admin-plugins"></span>                                    
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Plugins','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Remove WP SEO notifications, WPML Ads, WooThemes updater...','wp-admin-ui'); ?></p>
                                            <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-plugins' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-admin-plugins"></span>                                    
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Plugins','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Remove WP SEO notifications, WPML Ads, WooThemes updater plugin...','wp-admin-ui'); ?></p>
                                            <a class="button-primary" href="https://wpadminui.net/pro" target="_blank"><span class="dashicons dashicons-cart"></span><?php _e('Get it','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } ?>
                                <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-admin-appearance"></span>                                    
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Themes','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Create your own admin theme, or choose a preset one.','wp-admin-ui'); ?></p>
                                            <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-themes' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-admin-appearance"></span>                                    
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Themes','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Create your own admin theme, or choose a preset one.','wp-admin-ui'); ?></p>
                                            <a class="button-primary" href="https://wpadminui.net/pro" target="_blank"><span class="dashicons dashicons-cart"></span><?php _e('Get it','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-email-alt"></span>                                  
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Mails','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Optimize WP Mails','wp-admin-ui'); ?></p>
                                            <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-mails' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-email-alt"></span>                                   
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('Mails','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Optimize WP Mails','wp-admin-ui'); ?></p>
                                            <a class="button-primary" href="https://wpadminui.net/pro" target="_blank"><span class="dashicons dashicons-cart"></span><?php _e('Get it','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } ?>
                                <?php if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-cart"></span>                                  
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('WooCommerce','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Customize WooCommerce','wp-admin-ui'); ?></p>
                                            <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-woocommerce' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <div class="img-tool">
                                            <span class="dashicons dashicons-cart"></span>                                   
                                        </div>
                                        <span class="inner">
                                            <h4><?php _e('WooCommerce','wp-admin-ui'); ?></h4>
                                            <p><?php _e('Customize WooCommerce','wp-admin-ui'); ?></p>
                                            <a class="button-primary" href="https://wpadminui.net/pro" target="_blank"><span class="dashicons dashicons-cart"></span><?php _e('Get it','wp-admin-ui'); ?></a>
                                        </span>
                                    </td>
                                <?php } ?>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-groups"></span>                                    
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Role manager','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Apply WP Admin UI settings to specific roles.','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-roles' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                                <td>
                                    <div class="img-tool">
                                        <span class="dashicons dashicons-admin-settings"></span>                                   
                                    </div>
                                    <span class="inner">
                                        <h4><?php _e('Import / Export / Reset','wp-admin-ui'); ?></h4>
                                        <p><?php _e('Import / export WP Admin UI settings from site to site.','wp-admin-ui'); ?></p>
                                        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpui-import-export' ); ?>"><?php _e('Manage','wp-admin-ui'); ?></a>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
				</div>
			</div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'wpui_option_group', // Option group
            'wpui_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_login_option_group', // Option group
            'wpui_login_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_global_option_group', // Option group
            'wpui_global_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_dashboard_option_group', // Option group
            'wpui_dashboard_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_admin_menu_option_group', // Option group
            'wpui_admin_menu_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_admin_bar_option_group', // Option group
            'wpui_admin_bar_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_editor_option_group', // Option group
            'wpui_editor_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_library_option_group', // Option group
            'wpui_library_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_profil_option_group', // Option group
            'wpui_profil_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_roles_option_group', // Option group
            'wpui_roles_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );        

        register_setting(
            'wpui_import_export_option_group', // Option group
            'wpui_import_export_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

		//LOGIN SECTION============================================================================
		add_settings_section( 
            'wpui_setting_section_login', // ID
            __("Login settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_login' ), // Callback
            'wpui-settings-admin-login' // Page 
        ); 	

        add_settings_field(
            'wpui_login_custom_css', // ID
           __("Custom login CSS","wp-admin-ui"), // Title
            array( $this, 'wpui_login_custom_css_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section                  
        );

        add_settings_field(
            'wpui_login_logo_url', // ID
           __("Custom logo url","wp-admin-ui"), // Title
            array( $this, 'wpui_login_logo_url_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_logo', // ID
           __("Custom logo (recommanded width: 320px, height: 75px","wp-admin-ui"), // Title
            array( $this, 'wpui_login_logo_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_custom_logo_title', // ID
           __("Custom logo title","wp-admin-ui"), // Title
            array( $this, 'wpui_login_custom_logo_title_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_custom_bg_img', // ID
           __("Custom background image","wp-admin-ui"), // Title
            array( $this, 'wpui_login_custom_bg_img_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_always_checked', // ID
           __("Always checked remember me","wp-admin-ui"), // Title
            array( $this, 'wpui_login_always_checked_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_error_message', // ID
           __("Remove error message for security","wp-admin-ui"), // Title
            array( $this, 'wpui_login_error_message_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_shake_effect', // ID
           __("Disable Shake Effect if wrong login","wp-admin-ui"), // Title
            array( $this, 'wpui_login_shake_effect_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_logout_redirect', // ID
            __("Redirect users to a specific url after logout","wp-admin-ui"), // Title
            array( $this, 'wpui_login_logout_redirect_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section  
        );

        add_settings_field(
            'wpui_login_register_redirect', // ID
            __("Redirect users to a specific url after registration","wp-admin-ui"), // Title
            array( $this, 'wpui_login_register_redirect_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section  
        );        

        add_settings_field(
            'wpui_login_disable_email', // ID
            __("Disable login by email for users","wp-admin-ui"), // Title
            array( $this, 'wpui_login_disable_email_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section  
        );

        //GLOBAL SECTION===============================================================================
        add_settings_section( 
            'wpui_setting_section_global_display', // ID
            '',
            //__("Display","wp-admin-ui"), // Title
            array( $this, 'print_section_info_global' ), // Callback
            'wpui-settings-admin-global-display' // Page
        );  

        add_settings_section( 
            'wpui_setting_section_global_advanced', // ID
            '',
            //__("Advanced","wp-admin-ui"), // Title
            array( $this, 'print_section_info_global' ), // Callback
            'wpui-settings-admin-global-advanced' // Page
        ); 

        add_settings_section( 
            'wpui_setting_section_global_updates', // ID
            '',
            //__("Updates","wp-admin-ui"), // Title
            array( $this, 'print_section_info_global' ), // Callback
            'wpui-settings-admin-global-updates' // Page
        ); 

        add_settings_section( 
            'wpui_setting_section_global_front', // ID
            'These settings apply to the entire site, visitors AND connected users.',
            //__("Front","wp-admin-ui"), // Title
            array( $this, 'print_section_info_global' ), // Callback
            'wpui-settings-admin-global-front' // Page
        ); 

        add_settings_section( 
            'wpui_setting_section_global_debug', // ID
            '',
            //__("Debug","wp-admin-ui"), // Title
            array( $this, 'print_section_info_global' ), // Callback
            'wpui-settings-admin-global-debug' // Page
        ); 

        add_settings_field(
            'wpui_global_custom_css', // ID
           __("Custom admin CSS","wp-admin-ui"), // Title
            array( $this, 'wpui_global_custom_css_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section           
        );

        add_settings_field(
            'wpui_global_version_footer', // ID
           __("Remove WordPress version in footer","wp-admin-ui"), // Title
            array( $this, 'wpui_global_version_footer_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section  
        );

        add_settings_field(
            'wpui_global_custom_version_footer', // ID
           __("Custom WordPress version in footer","wp-admin-ui"), // Title
            array( $this, 'wpui_global_custom_version_footer_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section           
        );

        add_settings_field(
            'wpui_global_credits_footer', // ID
           __("Remove WordPress credits in footer","wp-admin-ui"), // Title
            array( $this, 'wpui_global_credits_footer_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section           
        );

        add_settings_field(
            'wpui_global_custom_credits_footer', // ID
           __("Custom WordPress credits in footer","wp-admin-ui"), // Title
            array( $this, 'wpui_global_custom_credits_footer_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section            
        );

        add_settings_field(
            'wpui_global_custom_favicon', // ID
           __("Custom favicon in admin","wp-admin-ui"), // Title
            array( $this, 'wpui_global_custom_favicon_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section            
        );

        add_settings_field(
            'wpui_global_help_tab', // ID
           __("Remove help tab","wp-admin-ui"), // Title
            array( $this, 'wpui_global_help_tab_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section           
        );

        add_settings_field(
            'wpui_global_screen_options_tab', // ID
           __("Remove screen options tab","wp-admin-ui"), // Title
            array( $this, 'wpui_global_screen_options_tab_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section           
        );

        add_settings_field(
            'wpui_global_open_sans', // ID
            __("Disable Open Sans loading from Google","wp-admin-ui"), // Title
            array( $this, 'wpui_global_open_sans_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section  
        );   

        add_settings_field(
            'wpui_global_custom_avatar', // ID
           __("Define a new avatar for comments","wp-admin-ui"), // Title
            array( $this, 'wpui_global_custom_avatar_callback' ), // Callback
            'wpui-settings-admin-global-display', // Page
            'wpui_setting_section_global_display' // Section            
        );    

        add_settings_field(
            'wpui_global_password_notification', // ID
           __("Hide autogenerated password message","wp-admin-ui"), // Title
            array( $this, 'wpui_global_password_notification_callback' ), // Callback
            'wpui-settings-admin-global-advanced', // Page
            'wpui_setting_section_global_advanced' // Section            
        );

        add_settings_field(
            'wpui_global_edit_per_page', // ID
           __("Number of items per page in list view (default 20)","wp-admin-ui"), // Title
            array( $this, 'wpui_global_edit_per_page_callback' ), // Callback
            'wpui-settings-admin-global-advanced', // Page
            'wpui_setting_section_global_advanced' // Section          
        );

        add_settings_field(
            'wpui_global_default_view_mode', // ID
            __("Define default view mode in view list","wp-admin-ui"), // Title
            array( $this, 'wpui_global_default_view_mode_callback' ), // Callback
            'wpui-settings-admin-global-advanced', // Page
            'wpui_setting_section_global_advanced' // Section  
        );

        add_settings_field(
            'wpui_global_disable_file_editor', // ID
            __("Disable file editor for themes and plugins","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_file_editor_callback' ), // Callback
            'wpui-settings-admin-global-advanced', // Page
            'wpui_setting_section_global_advanced' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_file_mods', // ID
            __("Disable Plugin and Theme Update, and Installation","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_file_mods_callback' ), // Callback
            'wpui-settings-admin-global-advanced', // Page
            'wpui_setting_section_global_advanced' // Section  
        );        

        add_settings_field(
            'wpui_global_block_admin', // ID
            __("Block WordPress admin for specific user roles","wp-admin-ui"), // Title
            array( $this, 'wpui_global_block_admin_callback' ), // Callback
            'wpui-settings-admin-global-advanced', // Page
            'wpui_setting_section_global_advanced' // Section  
        );
        
        add_settings_field(
            'wpui_global_update_notification', // ID
           __("Disable WordPress updates notifications","wp-admin-ui"), // Title
            array( $this, 'wpui_global_update_notification_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section            
        ); 

        add_settings_field(
            'wpui_global_disable_all_wp_udpates', // ID
            __("Disable all updates","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_all_wp_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_core_udpates', // ID
            __("Disable core updates","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_core_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_core_dev_udpates', // ID
            __("Disable core development updates","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_core_dev_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_core_minor_udpates', // ID
            __("Disable minor core updates","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_core_minor_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_core_major_udpates', // ID
            __("Disable major core updates","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_core_major_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_enable_core_vcs_udpates', // ID
            __("Enable automatic updates on Versioning Control System (GIT/SVN)","wp-admin-ui"), // Title
            array( $this, 'wpui_global_enable_core_vcs_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_plugin_udpates', // ID
            __("Disable automatic updates for all plugins","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_plugin_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_theme_udpates', // ID
            __("Disable automatic updates for all themes","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_theme_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_translation_udpates', // ID
            __("Disable automatic updates for all translations","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_translation_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );
        
        add_settings_field(
            'wpui_global_disable_email_udpates', // ID
            __("Disable update emails notifications","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_email_udpates_callback' ), // Callback
            'wpui-settings-admin-global-updates', // Page
            'wpui_setting_section_global_updates' // Section  
        );    

        add_settings_field(
            'wpui_global_disable_emoji', // ID
            __("Disable Emoji support for old browsers","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_emoji_callback' ), // Callback
            'wpui-settings-admin-global-front', // Page
            'wpui_setting_section_global_front' // Section  
        );     

        add_settings_field(
            'wpui_global_disable_json_rest_api', // ID
            __("Disable JSON REST API","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_json_rest_api_callback' ), // Callback
            'wpui-settings-admin-global-front', // Page
            'wpui_setting_section_global_front' // Section  
        );

        add_settings_field(
            'wpui_global_disable_xmlrpc', // ID
            __("Disable XML RPC","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_xmlrpc_callback' ), // Callback
            'wpui-settings-admin-global-front', // Page
            'wpui_setting_section_global_front' // Section  
        );

        add_settings_field(
            'wpui_global_disable_js_concatenation', // ID
            __("Disable JS/CSS concatenation","wp-admin-ui"), // Title
            array( $this, 'wpui_global_disable_js_concatenation_callback' ), // Callback
            'wpui-settings-admin-global-debug', // Page
            'wpui_setting_section_global_debug' // Section  
        );             

        //DASHBOARD SECTION============================================================================
        add_settings_section( 
            'wpui_setting_section_dashboard', // ID
            __("Dashboard settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_dashboard' ), // Callback
            'wpui-settings-admin-dashboard' // Page
        );  

        add_settings_field(
            'wpui_dashboard_welcome_panel', // ID
           __("Remove Welcome Panel","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_welcome_panel_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );  

        add_settings_field(
            'wpui_dashboard_single_column', // ID
           __("Display Dashboard in a single column","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_single_column_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );       

        add_settings_field(
            'wpui_dashboard_list_widgets', // ID
           __("Remove dashboard widgets","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_list_widgets_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_drag_and_drop_widgets', // ID
           __("Disable drag and drop for dashboard widgets (block disabling widgets from Screen options too)","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_drag_and_drop_widgets_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_at_a_glance_cpt', // ID
           __("Display all custom post types in At a glance dashboard widget","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_at_a_glance_cpt_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );         

        add_settings_field(
            'wpui_dashboard_users_at_a_glance', // ID
           __("Display number of users in At a glance dashboard widget","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_users_at_a_glance_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );        

        add_settings_field(
            'wpui_dashboard_custom_widget_title', // ID
           __("Display your custom widget title","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_custom_widget_title_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_custom_widget', // ID
           __("Display your custom widget","wp-admin-ui"), // Title
            array( $this, 'wpui_dashboard_custom_widget_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        //ADMIN MENU SECTION===========================================================================
        add_settings_section( 
            'wpui_setting_section_admin_menu', // ID
            __("Admin menu settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_admin_menu' ), // Callback
            'wpui-settings-admin-menu' // Page
        ); 

        add_settings_field(
            'wpui_admin_menu', // ID
           __("Menu Structure","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_menu_callback' ), // Callback
            'wpui-settings-admin-menu', // Page
            'wpui_setting_section_admin_menu' // Section           
        );

        add_settings_field(
            'wpui_admin_menu_all_settings', // ID
           __("Display all settings in menu","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_menu_all_settings_callback' ), // Callback
            'wpui-settings-admin-menu', // Page
            'wpui_setting_section_admin_menu' // Section           
        );

        //ADMIN BAR SECTION============================================================================
        add_settings_section( 
            'wpui_setting_section_admin_bar', // ID
            __("Admin bar settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_admin_bar' ), // Callback
            'wpui-settings-admin-bar' // Page
        );  

        add_settings_field(
            'wpui_admin_bar_wp_logo', // ID
           __("Remove WordPress logo in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_wp_logo_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_custom_logo', // ID
           __("Custom logo in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_custom_logo_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_site_name', // ID
           __("Remove Site Name in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_site_name_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_my_account', // ID
           __("Remove My Account in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_my_account_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_menu_toggle', // ID
           __("Remove Menu Toggle in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_menu_toggle_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_edit', // ID
           __("Remove Edit in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_edit_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_view', // ID
           __("Remove View in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_view_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_preview', // ID
           __("Remove Preview in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_preview_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_comments', // ID
           __("Remove Comments in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_comments_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_new_content', // ID
           __("Remove New Content in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_new_content_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_view_site', // ID
           __("Remove View Site in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_view_site_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_updates', // ID
           __("Remove Updates in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_updates_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        ); 

        add_settings_field(
            'wpui_admin_bar_customize', // ID
           __("Remove customize in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_customize_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        ); 

        add_settings_field(
            'wpui_admin_bar_search', // ID
           __("Remove search in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_search_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        ); 

        add_settings_field(
            'wpui_admin_bar_howdy', // ID
           __("Remove Howdy in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_howdy_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_wpui', // ID
           __("Remove WP Admin UI in admin bar","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_wpui_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );      

        add_settings_field(
            'wpui_admin_bar_disable', // ID
           __("Disable admin bar in front-end","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_bar_disable_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        //EDITOR SECTION===============================================================================
        add_settings_section( 
            'wpui_setting_section_editor', // ID
            __("Editor settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_editor' ), // Callback
            'wpui-settings-admin-editor' // Page
        );  

        add_settings_field(
            'wpui_admin_editor_full_tinymce', // ID
           __("Enable Full TinyMCE by default","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_full_tinymce_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );        

        add_settings_field(
            'wpui_admin_editor_font_size', // ID
           __("Add Font Size select","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_font_size_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_font_family', // ID
           __("Add Font Family select","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_font_family_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_custom_fonts', // ID
           __("Add custom Fonts select","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_custom_fonts_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_formats_select', // ID
           __("Add Formats select (styles)","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_formats_select_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );        

        add_settings_field(
            'wpui_admin_editor_get_shortlink', // ID
           __("Remove Get Shortlink button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_get_shortlink_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );    

        add_settings_field(
            'wpui_admin_editor_get_shortlink', // ID
           __("Remove Get Shortlink button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_get_shortlink_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_btn_newdocument', // ID
           __("Add New Document button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_btn_newdocument_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_btn_cut', // ID
           __("Add Cut button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_btn_cut_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_btn_copy', // ID
           __("Add Copy button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_btn_copy_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_btn_paste', // ID
           __("Add Paste button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_btn_paste_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_btn_backcolor', // ID
           __("Add Backcolor button","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_btn_backcolor_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_media_insert', // ID
           __("Remove Insert Media in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_insert_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_upload', // ID
           __("Remove Upload Files in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_upload_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_library', // ID
           __("Remove Media Library in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_library_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_gallery', // ID
           __("Remove Create Gallery in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_gallery_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_playlist', // ID
           __("Remove Create Playlist in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_playlist_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_media_featured_img', // ID
           __("Remove Set Featured Image in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_featured_img_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_insert_url', // ID
           __("Remove Insert From URL in Media Modal","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_media_insert_url_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_quicktags_p', // ID
            __("Add \"p\" quicktags in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_quicktags_p_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );
        
        add_settings_field(
            'wpui_admin_editor_quicktags_hr', // ID
            __("Add \"hr\" quicktags in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_quicktags_hr_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );
        
        add_settings_field(
            'wpui_admin_editor_quicktags_pre', // ID
            __("Add \"pre\" quicktags in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_quicktags_pre_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );

        add_settings_field(
            'wpui_admin_editor_formatting_shortcuts', // ID
            __("Disable formatting_shortcuts in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_formatting_shortcuts_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );

        add_settings_field(
            'wpui_admin_editor_img_def_align', // ID
            __("Set a default image alignment in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_img_def_align_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );

        add_settings_field(
            'wpui_admin_editor_img_def_link', // ID
            __("Set a default link type on images in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_img_def_link_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );

        add_settings_field(
            'wpui_admin_editor_img_def_size', // ID
            __("Set a default image size in Text Editor","wp-admin-ui"), // Title
            array( $this, 'wpui_admin_editor_img_def_size_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section
        );

        //Profile SECTION==================================================================================
        add_settings_section( 
            'wpui_setting_section_profil', // ID
            __("Profile settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_profil' ), // Callback
            'wpui-settings-admin-profil' // Page
        );  

        add_settings_field(
            'wpui_profil_visual_editor', // ID
           __("Remove Disable the visual editor when writing","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_visual_editor_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_admin_color_scheme', // ID
           __("Remove Admin Color Scheme","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_admin_color_scheme_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_default_color_scheme', // ID
           __("Set a default admin color scheme","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_default_color_scheme_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_keyword_shortcuts', // ID
           __("Remove Enable Keyboard Shortcuts for comment moderation","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_keyword_shortcuts_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_show_toolbar', // ID
           __("Remove Show Toolbar when viewing site","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_show_toolbar_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );   


        add_settings_field(
            'wpui_profil_facebook_field', // ID
           __("Add Facebook field in user profile","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_facebook_field_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_twitter_field', // ID
           __("Add Twitter field in user profile","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_twitter_field_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_instagram_field', // ID
           __("Add Instagram field in user profile","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_instagram_field_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        ); 

        add_settings_field(
            'wpui_profil_linkedin_field', // ID
           __("Add LinkedIn field in user profile","wp-admin-ui"), // Title
            array( $this, 'wpui_profil_linkedin_field_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );       

        //Media Library SECTION===========================================================================

        add_settings_section( 
            'wpui_setting_section_library_base', // ID
            '',
            //__("Base","wp-admin-ui"), // Title
            array( $this, 'print_section_info_library' ), // Callback
            'wpui-settings-admin-library-base' // Page
        );  

        add_settings_section( 
            'wpui_setting_section_library_filters', // ID
            '',
            //__("Advanced","wp-admin-ui"), // Title
            array( $this, 'print_section_info_library' ), // Callback
            'wpui-settings-admin-library-filters' // Page
        ); 

        add_settings_field(
            'wpui_library_jpeg_quality', // ID
           __("Define JPG image quality (default 82%)","wp-admin-ui"), // Title
            array( $this, 'wpui_library_jpeg_quality_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section            
        ); 

        add_settings_field(
            'wpui_library_clean_filename', // ID
           __("Clean filenames when uploading files to WordPress","wp-admin-ui"), // Title
            array( $this, 'wpui_library_clean_filename_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section          
        );  

        add_settings_field(
            'wpui_library_svg_mimes_type', // ID
           __("Allow SVG file in media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_svg_mimes_type_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section           
        );  

        add_settings_field(
            'wpui_library_url_col', // ID
           __("Add URL column in media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_url_col_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section           
        ); 

        add_settings_field(
            'wpui_library_dimensions_col', // ID
           __("Add dimensions column in media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_dimensions_col_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section           
        ); 

        add_settings_field(
            'wpui_library_exif_col', // ID
           __("Add EXIF metadata column in media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_exif_col_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section           
        ); 

        add_settings_field(
            'wpui_library_id_col', // ID
           __("Add ID column in media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_id_col_callback' ), // Callback
            'wpui-settings-admin-library-base', // Page
            'wpui_setting_section_library_base' // Section          
        );       

        add_settings_field(
            'wpui_library_filters_pdf', // ID
           __("Add PDF filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_pdf_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section           
        );

        add_settings_field(
            'wpui_library_filters_zip', // ID
           __("Add ZIP filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_zip_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section            
        );

        add_settings_field(
            'wpui_library_filters_rar', // ID
           __("Add RAR filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_rar_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        );

        add_settings_field(
            'wpui_library_filters_7z', // ID
           __("Add 7Z filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_7z_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        );

        add_settings_field(
            'wpui_library_filters_tar', // ID
           __("Add TAR filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_tar_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        );

        add_settings_field(
            'wpui_library_filters_swf', // ID
           __("Add SWF filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_swf_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        );

        add_settings_field(
            'wpui_library_filters_doc', // ID
           __("Add DOC filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_doc_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section            
        );

        add_settings_field(
            'wpui_library_filters_docx', // ID
           __("Add DOCX filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_docx_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        );

        add_settings_field(
            'wpui_library_filters_ppt', // ID
           __("Add PPT filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_ppt_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section            
        );

        add_settings_field(
            'wpui_library_filters_pptx', // ID
           __("Add PPTX filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_pptx_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section            
        );

        add_settings_field(
            'wpui_library_filters_xls', // ID
           __("Add XLS filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_xls_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        );

        add_settings_field(
            'wpui_library_filters_xlsx', // ID
           __("Add XLSX filtering to media library","wp-admin-ui"), // Title
            array( $this, 'wpui_library_filters_xlsx_callback' ), // Callback
            'wpui-settings-admin-library-filters', // Page
            'wpui_setting_section_library_filters' // Section             
        ); 

        //Roles SECTION============================================================================================
        add_settings_section( 
            'wpui_setting_section_roles', // ID
            __("Roles settings","wp-admin-ui"), // Title
            array( $this, 'print_section_info_roles' ), // Callback
            'wpui-settings-admin-roles' // Page
        );  

        add_settings_field(
            'wpui_roles_list_role', // ID
           __("&nbsp;","wp-admin-ui"), // Title
            array( $this, 'wpui_roles_list_role_callback' ), // Callback
            'wpui-settings-admin-roles', // Page
            'wpui_setting_section_roles' // Section            
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {	
        if( !empty( $input['wpui_login_custom_css'] ) )
            $input['wpui_login_custom_css'] = sanitize_textarea_field( $input['wpui_login_custom_css'] );

        if( !empty( $input['wpui_login_logo'] ) )
            $input['wpui_login_logo'] = sanitize_text_field( $input['wpui_login_logo'] );

        if( !empty( $input['wpui_login_logo_url'] ) )
            $input['wpui_login_logo_url'] = sanitize_text_field( $input['wpui_login_logo_url'] );

        if( !empty( $input['wpui_login_custom_logo_title'] ) )
            $input['wpui_login_custom_logo_title'] = sanitize_text_field( $input['wpui_login_custom_logo_title'] );

        if( !empty( $input['wpui_login_custom_bg_img'] ) )
            $input['wpui_login_custom_bg_img'] = sanitize_text_field( $input['wpui_login_custom_bg_img'] );

        if( !empty( $input['wpui_global_custom_css'] ) )
            $input['wpui_global_custom_css'] = sanitize_textarea_field( $input['wpui_global_custom_css'] );

        if( !empty( $input['wpui_global_edit_per_page'] ) )
            $input['wpui_global_edit_per_page'] = sanitize_text_field( $input['wpui_global_edit_per_page'] );

        if( !empty( $input['wpui_login_logout_redirect'] ) )
            $input['wpui_login_logout_redirect'] = sanitize_text_field( $input['wpui_login_logout_redirect'] );

        if( !empty( $input['wpui_login_register_redirect'] ) )
            $input['wpui_login_register_redirect'] = sanitize_text_field( $input['wpui_login_register_redirect'] );

        if( !empty( $input['wpui_library_jpeg_quality'] ) )
            $input['wpui_library_jpeg_quality'] = sanitize_text_field( $input['wpui_library_jpeg_quality'] );

        if( !empty( $input['wpui_dashboard_custom_widget_title'] ) )
            $input['wpui_dashboard_custom_widget_title'] = sanitize_text_field( $input['wpui_dashboard_custom_widget_title'] );

        if( !empty( $input['wpui_global_custom_avatar'] ) )
            $input['wpui_global_custom_avatar'] = sanitize_text_field( $input['wpui_global_custom_avatar'] );

        if( !empty( $input['wpui_admin_bar_custom_logo'] ) )
            $input['wpui_admin_bar_custom_logo'] = sanitize_text_field( $input['wpui_admin_bar_custom_logo'] );

        return $input;
    }

    /** 
     * Print the Section text
     */
	 
	public function print_section_info_login()
    {
        echo '<p>'.__('Customize your login screen', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_global()
    {
    }

    public function print_section_info_dashboard()
    {
        echo '<p>'.__('Customize your Dashboard', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_admin_menu()
    {
        echo '<p>'.__('Drag each item into the order you prefer.<br>Click the arrow on the right of the item to reveal submenus.<br>Check an item to <strong>HIDE</strong> in WP admin.<br><strong>NOTE:</strong> Admin Menu Settings does not apply to this specific page to avoid conflicts.<br><span style="color:red"><strong>WARNING:</strong> Be careful if you hide WP Admin UI menu for admins too!</span>', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_admin_bar()
    {
        echo '<p>'.__('Customize your Admin bar', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_editor()
    {
        echo '<p>'.__('Customize TINY MCE Editor', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_profil()
    {
        echo '<p>'.__('Manage Profile', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_library()
    {
        echo '<p>'.__('Customize your Media Library', 'wp-admin-ui').'</p>';
    }

    public function print_section_info_roles()
    {
        echo '<p>'.__('Apply settings for specific Roles', 'wp-admin-ui').'</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
	
    //Login
    public function wpui_login_custom_css_callback()
    {
        printf(
        '<textarea name="wpui_login_option_name[wpui_login_custom_css]">%s</textarea>',
        esc_html( $this->options['wpui_login_custom_css'])
        
        );
        
    } 

    public function wpui_login_logo_url_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_logo_url]" type="text" value="%s" /><span class="dashicons dashicons-info" title="'.__('Link URL when you click on the logo, default WordPress.org','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_login_logo_url'])
        
        );
        
    } 

    public function wpui_login_logo_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_logo]" type="text" value="%s" /><span class="dashicons dashicons-info" title="'.__('Image URL of your custom logo','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_login_logo'])
        
        );
        
    } 

    public function wpui_login_custom_logo_title_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_custom_logo_title]" type="text" value="%s" /><span class="dashicons dashicons-info" title="'.__('Default: Powered by WordPress.org','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_login_custom_logo_title'])
        
        );
        
    } 

    public function wpui_login_custom_bg_img_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_custom_bg_img]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_login_custom_bg_img'])
        
        );
        
    } 

    public function wpui_login_always_checked_callback()
    {
        $options = get_option( 'wpui_login_option_name' );  
        
        $check = isset($options['wpui_login_always_checked']);
        
        echo '<input id="wpui_login_always_checked" name="wpui_login_option_name[wpui_login_always_checked]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_login_always_checked">'. __( 'Always checked remember me', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_login_always_checked'])) {
            esc_attr( $this->options['wpui_login_always_checked']);
        }
    } 

    public function wpui_login_error_message_callback()
    {
        $options = get_option( 'wpui_login_option_name' );  
        
        $check = isset($options['wpui_login_error_message']);
        
        echo '<input id="wpui_login_error_message" name="wpui_login_option_name[wpui_login_error_message]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_login_error_message">'. __( 'Remove error message for security', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_login_error_message'])) {
            esc_attr( $this->options['wpui_login_error_message']);
        }
    }     

    public function wpui_login_shake_effect_callback()
    {
        $options = get_option( 'wpui_login_option_name' );  
        
        $check = isset($options['wpui_login_shake_effect']);
        
        echo '<input id="wpui_login_shake_effect" name="wpui_login_option_name[wpui_login_shake_effect]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_login_shake_effect">'. __( 'Disable Shake Effect if wrong login', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_login_shake_effect'])) {
            esc_attr( $this->options['wpui_login_shake_effect']);
        }
    }    

    public function wpui_login_logout_redirect_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_logout_redirect]" placeholder="https://example.com/" type="text" value="%s" /><span class="dashicons dashicons-info" title="'.__('Eg: https://example.com/','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_login_logout_redirect'])
        );
    }    

    public function wpui_login_register_redirect_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_register_redirect]" placeholder="https://example.com/thank-you" type="text" value="%s" /><span class="dashicons dashicons-info" title="'.__('Eg: https://example.com/thank-you','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_login_register_redirect'])
        );
    }  
    
    public function wpui_login_disable_email_callback()
    {
        $options = get_option( 'wpui_login_option_name' );  
        
        $check = isset($options['wpui_login_disable_email']);
        
        echo '<input id="wpui_login_disable_email" name="wpui_login_option_name[wpui_login_disable_email]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_login_disable_email">'. __( 'Disable login by Email for users', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_login_disable_email'])) {
            esc_attr( $this->options['wpui_login_disable_email']);
        }
    }

    //Global
    public function wpui_global_custom_css_callback()
    {
        printf(
            '<textarea name="wpui_global_option_name[wpui_global_custom_css]">%s</textarea>',
            esc_html( $this->options['wpui_global_custom_css'])
        );
        
    }

    public function wpui_global_version_footer_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_version_footer']);
        
        echo '<input id="wpui_global_version_footer" name="wpui_global_option_name[wpui_global_version_footer]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_version_footer">'. __( 'Remove WordPress version in footer', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_version_footer'])) {
            esc_attr( $this->options['wpui_global_version_footer']);
        }
    } 

    public function wpui_global_custom_version_footer_callback()
    {
        printf(
            '<input name="wpui_global_option_name[wpui_global_custom_version_footer]" type="text" value="%s" />',
            esc_attr( $this->options['wpui_global_custom_version_footer'])
        );
    } 

    public function wpui_global_credits_footer_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_credits_footer']);
        
        echo '<input id="wpui_global_credits_footer" name="wpui_global_option_name[wpui_global_credits_footer]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_credits_footer">'. __( 'Remove WordPress credits in footer', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_credits_footer'])) {
            esc_attr( $this->options['wpui_global_credits_footer']);
        }
    } 

    public function wpui_global_custom_credits_footer_callback()
    {
        printf(
            '<input name="wpui_global_option_name[wpui_global_custom_credits_footer]" type="text" value="%s" />',
            esc_attr( $this->options['wpui_global_custom_credits_footer'])
        );
    } 

    public function wpui_global_custom_favicon_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_custom_favicon]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_global_custom_favicon'])
        
        );
        
    } 

    public function wpui_global_help_tab_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_help_tab']);
        
        echo '<input id="wpui_global_help_tab" name="wpui_global_option_name[wpui_global_help_tab]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_help_tab">'. __( 'Remove help tab', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_help_tab'])) {
            esc_attr( $this->options['wpui_global_help_tab']);
        }
    } 

    public function wpui_global_screen_options_tab_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_screen_options_tab']);
        
        echo '<input id="wpui_global_screen_options_tab" name="wpui_global_option_name[wpui_global_screen_options_tab]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_screen_options_tab">'. __( 'Remove screen options tab', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_screen_options_tab'])) {
            esc_attr( $this->options['wpui_global_screen_options_tab']);
        }
    } 

    public function wpui_global_update_notification_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_update_notification']);
        
        echo '<input id="wpui_global_update_notification" name="wpui_global_option_name[wpui_global_update_notification]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_update_notification">'. __( 'Remove WordPress update notifications', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_update_notification'])) {
            esc_attr( $this->options['wpui_global_update_notification']);
        }
    } 

    public function wpui_global_password_notification_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_password_notification']);
        
        echo '<input id="wpui_global_password_notification" name="wpui_global_option_name[wpui_global_password_notification]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_password_notification">'. __( 'Hide autogenerated password message', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_password_notification'])) {
            esc_attr( $this->options['wpui_global_password_notification']);
        }
    }

    public function wpui_global_edit_per_page_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_edit_per_page]" type="number" min="1" max="250" value="%s" /><span class="dashicons dashicons-info" title="'.__('Users who have defined their own settings will be overwritten','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_global_edit_per_page'])
        
        );
    }

    public function wpui_global_default_view_mode_callback()
    {
        $options = get_option( 'wpui_global_option_name' );    
        
        if (isset($options['wpui_global_default_view_mode'])) { 
            $check = $options['wpui_global_default_view_mode'];
        }
        else {
            $check = 'list';
        }

        echo '<input id="wpui_global_default_view_mode_none" name="wpui_global_option_name[wpui_global_default_view_mode]" type="radio"';
        if ('list' == $check) echo 'checked="yes"'; 
        echo ' value="list"/>';
        
        echo '<label for="wpui_global_default_view_mode_none">'. __( 'List view mode (default)', 'wp-admin-ui' ) .'</label><span class="dashicons dashicons-info" title="'.__('Users who have defined their own settings will NOT be overwritten','wp-admin-ui').'"></span>';
        
        echo '<br><br>';

        echo '<input id="wpui_global_default_view_mode_default" name="wpui_global_option_name[wpui_global_default_view_mode]" type="radio"';
        if ('excerpt' == $check) echo 'checked="yes"'; 
        echo ' value="excerpt"/>';
        
        echo '<label for="wpui_global_default_view_mode_default">'. __( 'Excerpt view mode', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_default_view_mode'])) {
            esc_attr( $this->options['wpui_global_default_view_mode']);
        }
    }

    public function wpui_global_disable_file_editor_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_disable_file_editor']);
        
        echo '<input id="wpui_global_disable_file_editor" name="wpui_global_option_name[wpui_global_disable_file_editor]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
	    echo ' value="1"/>';
        echo '<label for="wpui_global_disable_file_editor">'. __( 'Disable file editor', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_file_editor'])) {
            esc_attr( $this->options['wpui_global_disable_file_editor']);
        }
    }

    public function wpui_global_disable_file_mods_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_disable_file_mods']);

        echo '<input id="wpui_global_disable_file_mods" name="wpui_global_option_name[wpui_global_disable_file_mods]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';        
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_file_mods">'. __( 'Disable file modifications', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_file_mods'])) {
            esc_attr( $this->options['wpui_global_disable_file_mods']);
        }
    }

    public function wpui_global_block_admin_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_block_admin']);

        echo '<input id="wpui_global_block_admin" name="wpui_global_option_name[wpui_global_block_admin]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';        
        echo ' value="1"/>';
        echo '<label for="wpui_global_block_admin">'. __( 'Block WordPress backend for specific roles', 'wp-admin-ui' ) .'<span class="dashicons dashicons-info" title="'.__('Don\'t forget to assign this option to specific user roles in the Role manager page','wp-admin-ui').'"></span></label>';
    
        if (isset($this->options['wpui_global_block_admin'])) {
            esc_attr( $this->options['wpui_global_block_admin']);
        }
    }    
    
    public function wpui_global_disable_all_wp_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_all_wp_udpates']);
    
        echo '<input id="wpui_global_disable_all_wp_udpates" name="wpui_global_option_name[wpui_global_disable_all_wp_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_all_wp_udpates">'. __( 'Disable all WordPress updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_all_wp_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_all_wp_udpates']);
        }
    }
    
    public function wpui_global_disable_core_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_core_udpates']);
    
        echo '<input id="wpui_global_disable_core_udpates" name="wpui_global_option_name[wpui_global_disable_core_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_core_udpates">'. __( 'Disable WordPress core updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_core_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_core_udpates']);
        }
    }
    
    public function wpui_global_disable_core_dev_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_core_dev_udpates']);
    
        echo '<input id="wpui_global_disable_core_dev_udpates" name="wpui_global_option_name[wpui_global_disable_core_dev_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_core_dev_udpates">'. __( 'Disable WordPress core updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_core_dev_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_core_dev_udpates']);
        }
    }
    
    public function wpui_global_disable_core_minor_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_core_minor_udpates']);
    
        echo '<input id="wpui_global_disable_core_minor_udpates" name="wpui_global_option_name[wpui_global_disable_core_minor_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_core_minor_udpates">'. __( 'Disable WordPress core minor updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_core_minor_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_core_minor_udpates']);
        }
    }
    
    public function wpui_global_disable_core_major_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_core_major_udpates']);
    
        echo '<input id="wpui_global_disable_core_major_udpates" name="wpui_global_option_name[wpui_global_disable_core_major_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_core_major_udpates">'. __( 'Disable WordPress core major updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_core_major_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_core_major_udpates']);
        }
    }
    
    public function wpui_global_enable_core_vcs_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_enable_core_vcs_udpates']);
    
        echo '<input id="wpui_global_enable_core_vcs_udpates" name="wpui_global_option_name[wpui_global_enable_core_vcs_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_enable_core_vcs_udpates">'. __( 'Enable core updates on a VCS', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_enable_core_vcs_udpates'])) {
            esc_attr( $this->options['wpui_global_enable_core_vcs_udpates']);
        }
    }
    
    public function wpui_global_disable_plugin_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_plugin_udpates']);
    
        echo '<input id="wpui_global_disable_plugin_udpates" name="wpui_global_option_name[wpui_global_disable_plugin_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_plugin_udpates">'. __( 'Disable all plugins updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_plugin_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_plugin_udpates']);
        }
    }
    
    public function wpui_global_disable_theme_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_theme_udpates']);
    
        echo '<input id="wpui_global_disable_theme_udpates" name="wpui_global_option_name[wpui_global_disable_theme_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_theme_udpates">'. __( 'Disable all themes updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_theme_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_theme_udpates']);
        }
    }
    
    public function wpui_global_disable_translation_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_translation_udpates']);
    
        echo '<input id="wpui_global_disable_translation_udpates" name="wpui_global_option_name[wpui_global_disable_translation_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_translation_udpates">'. __( 'Disable all translations updates', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_translation_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_translation_udpates']);
        }
    }
    
    public function wpui_global_disable_email_udpates_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
    
        $check = isset($options['wpui_global_disable_email_udpates']);
    
        echo '<input id="wpui_global_disable_email_udpates" name="wpui_global_option_name[wpui_global_disable_email_udpates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_email_udpates">'. __( 'Disable emails notifications', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_email_udpates'])) {
            esc_attr( $this->options['wpui_global_disable_email_udpates']);
        }
    }    

    public function wpui_global_disable_emoji_callback()
    {       
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_disable_emoji']);
        
        echo '<input id="wpui_global_disable_emoji" name="wpui_global_option_name[wpui_global_disable_emoji]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_emoji">'. __( 'Disable Emoji support for old browsers (improve performances)', 'wp-admin-ui' ) .'</label><span class="dashicons dashicons-info" title="'.__('Emojis will still continue to work with modern browsers. This setting applies to every user/visitor in front AND backend.','wp-admin-ui').'"></span>';
    
        if (isset($this->options['wpui_global_disable_emoji'])) {
            esc_attr( $this->options['wpui_global_disable_emoji']);
        }
    }

    public function wpui_global_disable_json_rest_api_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_disable_json_rest_api']);

        echo '<input id="wpui_global_disable_json_rest_api" name="wpui_global_option_name[wpui_global_disable_json_rest_api]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';        
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_json_rest_api">'. __( 'Disable JSON REST API', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_json_rest_api'])) {
            esc_attr( $this->options['wpui_global_disable_json_rest_api']);
        }
    }     

    public function wpui_global_disable_xmlrpc_callback()
    {
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_disable_xmlrpc']);

        echo '<input id="wpui_global_disable_xmlrpc" name="wpui_global_option_name[wpui_global_disable_xmlrpc]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';        
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_xmlrpc">'. __( 'Disable XML RPC', 'wp-admin-ui' ) .'<span class="dashicons dashicons-info" title="'.__('Disable XML RPC is strongly recommended for security reasons!','wp-admin-ui').'"></span></label>';
    
        if (isset($this->options['wpui_global_disable_xmlrpc'])) {
            esc_attr( $this->options['wpui_global_disable_xmlrpc']);
        }
    }    

    public function wpui_global_disable_js_concatenation_callback()
    {       
        $options = get_option( 'wpui_global_option_name' );
        
        $check = isset($options['wpui_global_disable_js_concatenation']);
        
        echo '<input id="wpui_global_disable_js_concatenation" name="wpui_global_option_name[wpui_global_disable_js_concatenation]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_global_disable_js_concatenation">'. __( 'Disable JS/CSS concatenation', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_global_disable_js_concatenation'])) {
            esc_attr( $this->options['wpui_global_disable_js_concatenation']);
        }
    }

    public function wpui_global_open_sans_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_open_sans']);
        
        echo '<input id="wpui_global_open_sans" name="wpui_global_option_name[wpui_global_open_sans]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_open_sans">'. __( 'Disable Open Sans', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_global_open_sans'])) {
            esc_attr( $this->options['wpui_global_open_sans']);
        }
    }

    public function wpui_global_custom_avatar_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_custom_avatar]" placeholder="'.__('Enter your avatar url','wp-admin-ui').'" type="text" value="%s" />',
        esc_attr( $this->options['wpui_global_custom_avatar'])
        
        );
    }

    //Dashboard
    public function wpui_dashboard_welcome_panel_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_welcome_panel']);
        
        echo '<input id="wpui_dashboard_welcome_panel" name="wpui_dashboard_option_name[wpui_dashboard_welcome_panel]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_welcome_panel">'. __( 'Remove Welcome Panel', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_welcome_panel'])) {
            esc_attr( $this->options['wpui_dashboard_welcome_panel']);
        }
    }

    public function wpui_dashboard_single_column_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_single_column']);
        
        echo '<input id="wpui_dashboard_single_column" name="wpui_dashboard_option_name[wpui_dashboard_single_column]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_single_column">'. __( 'Display dashboard widgets in a single column', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_single_column'])) {
            esc_attr( $this->options['wpui_dashboard_single_column']);
        }
    }

    public function wpui_dashboard_list_widgets_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $wpui_dashboard_metaboxes = get_option( 'wpui_dashboard_list_all_widgets'); 

        $wpui_dashboard_contexts = array('normal','advanced','side');

        $wpui_dashboard_priorities = array('high','core','default','low');

        echo '<ul>';

        if ($wpui_dashboard_metaboxes !='') {
            foreach ($wpui_dashboard_metaboxes as $wpui_dashboard_contexts) {
                foreach ($wpui_dashboard_contexts as $wpui_dashboard_priorities) {
                    foreach ($wpui_dashboard_priorities as $wpui_dashboard_widget) {
                        foreach ($wpui_dashboard_widget as $wpui_dashboard_id) {
                            $check = isset($options['wpui_dashboard_metaboxe_all'][$wpui_dashboard_id['id']]);

                            echo '<li>';
                                echo '<input id="wpui_dashboard_metaboxe_all_['.$wpui_dashboard_id['id'].']" type="checkbox" name="wpui_dashboard_option_name[wpui_dashboard_metaboxe_all]['.$wpui_dashboard_id['id'].']"';
                                if ($wpui_dashboard_id['id'] == $check) echo 'checked="yes"'; 
                                echo ' value="'.$wpui_dashboard_id['id'].'"/>';
                                echo '<label for="wpui_dashboard_metaboxe_all_['.$wpui_dashboard_id['id'].']">'. $wpui_dashboard_id['title'] .'</label>';
                            echo '</li>';

                            if (isset($this->options['wpui_dashboard_metaboxe_all'][$wpui_dashboard_id['id']])) {
                                esc_attr( $this->options['wpui_dashboard_metaboxe_all'][$wpui_dashboard_id['id']]);
                            }
                        }
                    }
                }
            }
        }

        echo '</ul>';

        function wpui_clear_dashboard_cache() {
        ?>

        <form method="post">
            <?php 
                if (!get_option( 'wpui_dashboard_list_all_widgets')) {
                    '<p>'._e('You need to Refresh in order to initialize the dashboard widgets list.','wp-admin-ui').'</p>';
                }
            ?>
            <p><input type="hidden" name="wpui_action" value="refresh_metaboxes" /></p>
            <p>
                <?php wp_nonce_field( 'wpui_refresh_dashboard_metaboxes_nonce', 'wpui_refresh_dashboard_metaboxes_nonce' ); ?>

                <input name="wpui-refresh" id="wpui-refresh" class="button" value="Refresh" type="button">
                <span class="spinner"></span>
            </p>
        </form>
        <?php
    };
    ?>
    <?php echo wpui_clear_dashboard_cache(); ?>   
    <?php }

    public function wpui_dashboard_drag_and_drop_widgets_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_widgets_drag_and_drop']);
        
        echo '<input id="wpui_dashboard_drag_and_drop_widgets" name="wpui_dashboard_option_name[wpui_dashboard_widgets_drag_and_drop]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_drag_and_drop_widgets">'. __( 'Disable Drag and drop for dashboard widgets', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_widgets_drag_and_drop'])) {
            esc_attr( $this->options['wpui_dashboard_widgets_drag_and_drop']);
        }
    }

    public function wpui_dashboard_at_a_glance_cpt_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_at_a_glance_cpt']);
        
        echo '<input id="wpui_dashboard_at_a_glance_cpt" name="wpui_dashboard_option_name[wpui_dashboard_at_a_glance_cpt]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_at_a_glance_cpt">'. __( 'Display all custom post types in At a glance dashboard widget', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_at_a_glance_cpt'])) {
            esc_attr( $this->options['wpui_dashboard_at_a_glance_cpt']);
        }
    }

    public function wpui_dashboard_users_at_a_glance_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_users_at_a_glance']);
        
        echo '<input id="wpui_dashboard_users_at_a_glance" name="wpui_dashboard_option_name[wpui_dashboard_users_at_a_glance]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_users_at_a_glance">'. __( 'Display number of users in At a glance dashboard widget', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_users_at_a_glance'])) {
            esc_attr( $this->options['wpui_dashboard_users_at_a_glance']);
        }
    }

    public function wpui_dashboard_custom_widget_title_callback()
    {
        printf(
        '<input name="wpui_dashboard_option_name[wpui_dashboard_custom_widget_title]" placeholder="'.__('My widget title','wp-admin-ui').'" type="text" value="%s" />',
        esc_attr( $this->options['wpui_dashboard_custom_widget_title'])
        
        );
    }    

    public function wpui_dashboard_custom_widget_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' ); 

        $settings = array('textarea_name' => 'wpui_dashboard_option_name[wpui_dashboard_custom_widget]', 'editor_height' => '300', 'teeny' => true);

        echo wp_editor( html_entity_decode($options['wpui_dashboard_custom_widget']), 'wpui_dashboard_custom_widget', $settings );
    }

    //Admin Menu
    public function wpui_admin_menu_callback()
    {
        $menu = get_option('wpui_admin_menu_default_option_name');
        global $submenu, $pagenow;
        ?>
        <form method="post">
            <p>
                <input type="hidden" name="wpui_action" value="reset_menus" />
                <?php wp_nonce_field( 'wpui_menu_reset_order', 'wpui_reset_menus_nonce' ); ?>
                <input name="wpui-reset" id="wpui-reset" class="button" value="Reset" type="button">
                <span class="spinner"></span>
            </p>
        </form>

        <?php
        
        $options = get_option( 'wpui_admin_menu_option_name' );  
        $wpui_admin_menu_custom_list = get_option( 'wpui_admin_menu_list_option_name' );
        $wpui_admin_menu_slug_list = array();
        ob_start();
        $wpui_admin_menu_custom_list_order = $wpui_admin_menu_custom_list;
        $wpui_admin_menu_custom_list_default = $menu;

        if ($wpui_admin_menu_custom_list !='') {
            function wpui_admin_menu_order($wpui_admin_menu_custom_list_default, $wpui_admin_menu_custom_list_order) {
                $wpui_admin_menu_ordered = array();
                foreach($wpui_admin_menu_custom_list_order as $key) {
                    if(array_key_exists($key,$wpui_admin_menu_custom_list_default)) {
                        $wpui_admin_menu_ordered[$key] = $wpui_admin_menu_custom_list_default[$key];
                        unset($wpui_admin_menu_custom_list_default[$key]);
                    }
                }
                return $wpui_admin_menu_ordered + $wpui_admin_menu_custom_list_default;
            }
            $menu = wpui_admin_menu_order($wpui_admin_menu_custom_list_default, $wpui_admin_menu_custom_list_order);
        }
        echo '<br/><br/><hr>';
        echo '<div class="metabox-holder">';
            echo '<div id="side-sortables" class="accordion-container">';
                echo '<ul class="outer-border">';
                    foreach($menu as $menu_key => $menu_item):
                        if(!$menu_item[0]) { continue; }

                        $check = isset($options['wpui_admin_menu'][$menu_item[2]]);

                        echo '<li id="list_items_'.$menu_key.'" class="control-section accordion-section">';
                            echo '<h3 class="accordion-section-title hndle" tabindex="0">';
                                echo $menu_item[0];
                            echo '</h3>';
                            echo '<div class="accordion-section-content">';
                                echo '<div class="inside">';
                                    echo '<ul>';
                                        echo '<li>';
                                            echo '<input id="wpui_admin_menu['.$menu_key.']" type="checkbox" name="wpui_admin_menu_option_name[wpui_admin_menu]['.$menu_item[2].']"';
                                            if ($menu_item[2] == $check) echo 'checked="yes"'; 
                                            echo ' value="'.$menu_item[2].'"/>';
                                            echo '<label for="wpui_admin_menu['.$menu_key.']">';
                                            echo '<span class="';
                                            if ($menu_item[2] == $check) echo 'dashicons dashicons-hidden';
                                            echo '"></span>'. $menu_item[0] .'</label>';
                                            echo '<input placeholder="'.__("Enter your own menu name","wp-admin-ui").'" class="wpui-admin-menu-input" name="wpui_admin_menu_option_name[wpui_admin_menu]['.$menu_key.'][0]" type="text" value="'.$options['wpui_admin_menu'][$menu_key][0].'" />';
                                            if (isset($this->options['wpui_admin_menu'][$menu_key][0])) {
                                                esc_attr( $this->options['wpui_admin_menu'][$menu_key][0]);
                                            }
                                            
                                        echo '</li>';

                                        if (isset($this->options['wpui_admin_menu'][$menu_item[2]])) {
                                            esc_attr( $this->options['wpui_admin_menu'][$menu_item[2]]);
                                        }
                                        
                                        $wpui_admin_menu_slug_list_temp = array_push($wpui_admin_menu_slug_list, $menu_item[2]);
                                        update_option( 'wpui_admin_menu_slug', $wpui_admin_menu_slug_list );
                                        if( isset( $submenu[ $menu_item[2] ] ) ):
                                            foreach($submenu[ $menu_item[2] ] as $submenu_key => $submenu_item):

                                                $check = isset($options['wpui_admin_menu'][$menu_key][$menu_item[2]]['child'][$submenu_key]);
                                                if (isset($options['wpui_admin_menu'][$menu_key][$menu_item[2]][0][$submenu_key])) {
                                                    $check2 = $options['wpui_admin_menu'][$menu_key][$menu_item[2]][0][$submenu_key];
                                                } else {
                                                    $check2 ='';
                                                }
                                                echo '<li><input id="wpui_admin_menu['.$menu_key.']['.$submenu_key.']" type="checkbox"  name="wpui_admin_menu_option_name[wpui_admin_menu]['.$menu_key.']['.$menu_item[2].'][child]['.$submenu_key.']"';
                                                    if ($submenu_item[2] == $check) echo 'checked="yes"'; 
                                                    echo ' value="'.$submenu_item[2].'"/>';
                                                    echo '<label for="wpui_admin_menu['.$menu_key.']['.$submenu_key.']">';
                                                    echo '<span class="';
                                                    if ($submenu_item[2] == $check) echo 'dashicons dashicons-hidden';
                                                    echo '"></span>'.$submenu_item[0].'</label>';
                                                    echo '<input placeholder="'.__("Enter your own menu name","wp-admin-ui").'" class="wpui-admin-menu-input" name="wpui_admin_menu_option_name[wpui_admin_menu]['.$menu_key.']['.$menu_item[2].'][0]['.$submenu_key.']" type="text" value="'.$check2.'" />';
                                                    if (isset($this->options['wpui_admin_menu'][$menu_key][$menu_item[2]][0][$submenu_key])) {
                                                        esc_attr( $this->options['wpui_admin_menu'][$menu_key][$menu_item[2]][0][$submenu_key]);
                                                    }
                                                echo '</li>';   

                                                if (isset($this->options['wpui_admin_menu'][$menu_key][$menu_item[2]]['child'][$submenu_key])) {
                                                    esc_attr( $this->options['wpui_admin_menu'][$menu_key][$menu_item[2]]['child'][$submenu_key]);
                                                }
                                            endforeach;  
                                        endif;
                                    echo '</ul>';
                                echo '</div>';   
                            echo '</div>';
                        echo '</li>';
                    endforeach;
                echo '</ul>';
            echo '</div>';
        echo '</div>';

        echo ob_get_clean();
    }

    public function wpui_admin_menu_all_settings_callback()
    {
        $options = get_option( 'wpui_admin_menu_option_name' );  
        
        $check = isset($options['wpui_admin_menu_all_settings']);
        
        echo '<input id="wpui_admin_menu_all_settings" name="wpui_admin_menu_option_name[wpui_admin_menu_all_settings]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_menu_all_settings">'. __( 'Display all settings (required manage_options capability)', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_menu_all_settings'])) {
            esc_attr( $this->options['wpui_admin_menu_all_settings']);
        }
    } 

    //Admin bar
    public function wpui_admin_bar_wp_logo_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_wp_logo']);
        
        echo '<input id="wpui_admin_bar_wp_logo" name="wpui_admin_bar_option_name[wpui_admin_bar_wp_logo]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_wp_logo">'. __( 'Remove WordPress logo in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_wp_logo'])) {
            esc_attr( $this->options['wpui_admin_bar_wp_logo']);
        }
    } 

    public function wpui_admin_bar_custom_logo_callback()
    {
        printf(
        '<input name="wpui_admin_bar_option_name[wpui_admin_bar_custom_logo]" type="text" value="%s" /><span class="dashicons dashicons-info" title="'.__('Recommended size: 32x32 pixels','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_admin_bar_custom_logo'])
        
        );
    }

    public function wpui_admin_bar_site_name_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_site_name']);
        
        echo '<input id="wpui_admin_bar_site_name" name="wpui_admin_bar_option_name[wpui_admin_bar_site_name]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_site_name">'. __( 'Remove Site Name in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_site_name'])) {
            esc_attr( $this->options['wpui_admin_bar_site_name']);
        }
    }

    public function wpui_admin_bar_my_account_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_my_account']);
        
        echo '<input id="wpui_admin_bar_my_account" name="wpui_admin_bar_option_name[wpui_admin_bar_my_account]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_my_account">'. __( 'Remove My Account in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_my_account'])) {
            esc_attr( $this->options['wpui_admin_bar_my_account']);
        }
    } 
    
    public function wpui_admin_bar_menu_toggle_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_menu_toggle']);
        
        echo '<input id="wpui_admin_bar_menu_toggle" name="wpui_admin_bar_option_name[wpui_admin_bar_menu_toggle]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_menu_toggle">'. __( 'Remove Menu Toggle (hamburger icon in responsive mode)', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_menu_toggle'])) {
            esc_attr( $this->options['wpui_admin_bar_menu_toggle']);
        }
    } 

    public function wpui_admin_bar_edit_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_edit']);
        
        echo '<input id="wpui_admin_bar_edit" name="wpui_admin_bar_option_name[wpui_admin_bar_edit]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_edit">'. __( 'Remove Edit in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_edit'])) {
            esc_attr( $this->options['wpui_admin_bar_edit']);
        }
    } 
    
    public function wpui_admin_bar_preview_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_preview']);
        
        echo '<input id="wpui_admin_bar_preview" name="wpui_admin_bar_option_name[wpui_admin_bar_preview]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_preview">'. __( 'Remove Preview in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_preview'])) {
            esc_attr( $this->options['wpui_admin_bar_preview']);
        }
    }

    public function wpui_admin_bar_view_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_view']);
        
        echo '<input id="wpui_admin_bar_view" name="wpui_admin_bar_option_name[wpui_admin_bar_view]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_view">'. __( 'Remove View in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_view'])) {
            esc_attr( $this->options['wpui_admin_bar_view']);
        }
    } 
    
    public function wpui_admin_bar_comments_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_comments']);
        
        echo '<input id="wpui_admin_bar_comments" name="wpui_admin_bar_option_name[wpui_admin_bar_comments]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_comments">'. __( 'Remove Comments in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_comments'])) {
            esc_attr( $this->options['wpui_admin_bar_comments']);
        }
    } 
    
    public function wpui_admin_bar_new_content_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_new_content']);
        
        echo '<input id="wpui_admin_bar_new_content" name="wpui_admin_bar_option_name[wpui_admin_bar_new_content]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_new_content">'. __( 'Remove New Content in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_new_content'])) {
            esc_attr( $this->options['wpui_admin_bar_new_content']);
        }
    } 

    public function wpui_admin_bar_view_site_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_view_site']);
        
        echo '<input id="wpui_admin_bar_view_site" name="wpui_admin_bar_option_name[wpui_admin_bar_view_site]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_view_site">'. __( 'Remove View Site in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_view_site'])) {
            esc_attr( $this->options['wpui_admin_bar_view_site']);
        }
    }

    public function wpui_admin_bar_updates_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_updates']);
        
        echo '<input id="wpui_admin_bar_updates" name="wpui_admin_bar_option_name[wpui_admin_bar_updates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_updates">'. __( 'Remove Updates in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_updates'])) {
            esc_attr( $this->options['wpui_admin_bar_updates']);
        }
    }

    public function wpui_admin_bar_customize_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_customize']);
        
        echo '<input id="wpui_admin_bar_customize" name="wpui_admin_bar_option_name[wpui_admin_bar_customize]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_customize">'. __( 'Remove customize in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_customize'])) {
            esc_attr( $this->options['wpui_admin_bar_customize']);
        }
    }

    public function wpui_admin_bar_search_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_search']);
        
        echo '<input id="wpui_admin_bar_search" name="wpui_admin_bar_option_name[wpui_admin_bar_search]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_search">'. __( 'Remove search in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_search'])) {
            esc_attr( $this->options['wpui_admin_bar_search']);
        }
    }

    public function wpui_admin_bar_howdy_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_howdy']);
        
        echo '<input id="wpui_admin_bar_howdy" name="wpui_admin_bar_option_name[wpui_admin_bar_howdy]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_howdy">'. __( 'Remove Howdy in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_howdy'])) {
            esc_attr( $this->options['wpui_admin_bar_howdy']);
        }
    }

    public function wpui_admin_bar_wpui_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_wpui']);
        
        echo '<input id="wpui_admin_bar_wpui" name="wpui_admin_bar_option_name[wpui_admin_bar_wpui]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_wpui">'. __( 'Remove WP Admin UI in admin bar', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_wpui'])) {
            esc_attr( $this->options['wpui_admin_bar_wpui']);
        }
    }

    public function wpui_admin_bar_disable_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_disable']);
        
        echo '<input id="wpui_admin_bar_disable" name="wpui_admin_bar_option_name[wpui_admin_bar_disable]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_disable">'. __( 'Disable admin bar in front-end', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_disable'])) {
            esc_attr( $this->options['wpui_admin_bar_disable']);
        }
    }

    //Editor
    public function wpui_admin_editor_full_tinymce_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_full_tinymce']);
        
        echo '<input id="wpui_admin_editor_full_tinymce" name="wpui_editor_option_name[wpui_admin_editor_full_tinymce]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_full_tinymce">'. __( 'Enable full TinyMCE by default', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_full_tinymce'])) {
            esc_attr( $this->options['wpui_admin_editor_full_tinymce']);
        }
    }

    public function wpui_admin_editor_font_size_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_font_size']);
        
        echo '<input id="wpui_admin_editor_font_size" name="wpui_editor_option_name[wpui_admin_editor_font_size]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_font_size">'. __( 'Add Font Size select', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_font_size'])) {
            esc_attr( $this->options['wpui_admin_editor_font_size']);
        }
    }

    public function wpui_admin_editor_font_family_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_font_family']);
        
        echo '<input id="wpui_admin_editor_font_family" name="wpui_editor_option_name[wpui_admin_editor_font_family]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_font_family">'. __( 'Add Font Family select', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_font_family'])) {
            esc_attr( $this->options['wpui_admin_editor_font_family']);
        }
    }

    public function wpui_admin_editor_custom_fonts_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_custom_fonts']);
        
        echo '<input id="wpui_admin_editor_custom_fonts" name="wpui_editor_option_name[wpui_admin_editor_custom_fonts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_custom_fonts">'. __( 'Add Custom Fonts select', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_custom_fonts'])) {
            esc_attr( $this->options['wpui_admin_editor_custom_fonts']);
        }
    }

    public function wpui_admin_editor_formats_select_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_formats_select']);
        
        echo '<input id="wpui_admin_editor_formats_select" name="wpui_editor_option_name[wpui_admin_editor_formats_select]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_formats_select">'. __( 'Add Formats select (styles)', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_formats_select'])) {
            esc_attr( $this->options['wpui_admin_editor_formats_select']);
        }
    }

    public function wpui_admin_editor_get_shortlink_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_get_shortlink']);
        
        echo '<input id="wpui_admin_editor_get_shortlink" name="wpui_editor_option_name[wpui_admin_editor_get_shortlink]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_get_shortlink">'. __( 'Remove Get shortlink button', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_get_shortlink'])) {
            esc_attr( $this->options['wpui_admin_editor_get_shortlink']);
        }
    }

    public function wpui_admin_editor_btn_newdocument_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_newdocument']);
        
        echo '<input id="wpui_admin_editor_btn_newdocument" name="wpui_editor_option_name[wpui_admin_editor_btn_newdocument]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_newdocument">'. __( ' Add New Document button', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_newdocument'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_newdocument']);
        }
    }

        public function wpui_admin_editor_btn_cut_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_cut']);
        
        echo '<input id="wpui_admin_editor_btn_cut" name="wpui_editor_option_name[wpui_admin_editor_btn_cut]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_cut">'. __( ' Add Cut button', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_cut'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_cut']);
        }
    }

        public function wpui_admin_editor_btn_copy_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_copy']);
        
        echo '<input id="wpui_admin_editor_btn_copy" name="wpui_editor_option_name[wpui_admin_editor_btn_copy]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_copy">'. __( ' Add Copy button', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_copy'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_copy']);
        }
    }

    public function wpui_admin_editor_btn_paste_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_paste']);
        
        echo '<input id="wpui_admin_editor_btn_paste" name="wpui_editor_option_name[wpui_admin_editor_btn_paste]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_paste">'. __( ' Add Paste button', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_paste'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_paste']);
        }
    }

    public function wpui_admin_editor_btn_backcolor_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_backcolor']);
        
        echo '<input id="wpui_admin_editor_btn_backcolor" name="wpui_editor_option_name[wpui_admin_editor_btn_backcolor]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_backcolor">'. __( ' Add Backcolor button', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_backcolor'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_backcolor']);
        }
    }    

    public function wpui_admin_editor_media_insert_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_insert']);
        
        echo '<input id="wpui_admin_editor_media_insert" name="wpui_editor_option_name[wpui_admin_editor_media_insert]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_insert">'. __( 'Remove Insert Media in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_insert'])) {
            esc_attr( $this->options['wpui_admin_editor_media_insert']);
        }
    }

    public function wpui_admin_editor_media_upload_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_upload']);
        
        echo '<input id="wpui_admin_editor_media_upload" name="wpui_editor_option_name[wpui_admin_editor_media_upload]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_upload">'. __( 'Remove Upload Files in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_upload'])) {
            esc_attr( $this->options['wpui_admin_editor_media_upload']);
        }
    }

    public function wpui_admin_editor_media_library_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_library']);
        
        echo '<input id="wpui_admin_editor_media_library" name="wpui_editor_option_name[wpui_admin_editor_media_library]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_library">'. __( 'Remove Media Library in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_library'])) {
            esc_attr( $this->options['wpui_admin_editor_media_library']);
        }
    }

    public function wpui_admin_editor_media_gallery_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_gallery']);
        
        echo '<input id="wpui_admin_editor_media_gallery" name="wpui_editor_option_name[wpui_admin_editor_media_gallery]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_gallery">'. __( 'Remove Create Gallery in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_gallery'])) {
            esc_attr( $this->options['wpui_admin_editor_media_gallery']);
        }
    }    

    public function wpui_admin_editor_media_playlist_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_playlist']);
        
        echo '<input id="wpui_admin_editor_media_playlist" name="wpui_editor_option_name[wpui_admin_editor_media_playlist]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_playlist">'. __( 'Remove Create Playlist in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_playlist'])) {
            esc_attr( $this->options['wpui_admin_editor_media_playlist']);
        }
    }

    public function wpui_admin_editor_media_featured_img_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_featured_img']);
        
        echo '<input id="wpui_admin_editor_media_featured_img" name="wpui_editor_option_name[wpui_admin_editor_media_featured_img]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_featured_img">'. __( 'Remove Set Featured Image in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_featured_img'])) {
            esc_attr( $this->options['wpui_admin_editor_media_featured_img']);
        }
    }

    public function wpui_admin_editor_media_insert_url_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_insert_url']);
        
        echo '<input id="wpui_admin_editor_media_insert_url" name="wpui_editor_option_name[wpui_admin_editor_media_insert_url]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_insert_url">'. __( 'Remove Insert From URL in Media Modal', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_insert_url'])) {
            esc_attr( $this->options['wpui_admin_editor_media_insert_url']);
        }
    }

    public function wpui_admin_editor_quicktags_p_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );
    
        $check = isset($options['wpui_admin_editor_quicktags_p']);
    
        echo '<input id="wpui_admin_editor_quicktags_p" name="wpui_editor_option_name[wpui_admin_editor_quicktags_p]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_quicktags_p">'. __( 'Add Paragraph tag in text editor', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_admin_editor_quicktags_p'])) {
            esc_attr( $this->options['wpui_admin_editor_quicktags_p']);
        }
    }
    
    public function wpui_admin_editor_quicktags_hr_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );
    
        $check = isset($options['wpui_admin_editor_quicktags_hr']);
    
        echo '<input id="wpui_admin_editor_quicktags_hr" name="wpui_editor_option_name[wpui_admin_editor_quicktags_hr]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_quicktags_hr">'. __( 'Add HR tag in text editor', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_admin_editor_quicktags_hr'])) {
            esc_attr( $this->options['wpui_admin_editor_quicktags_hr']);
        }
    }
    
    public function wpui_admin_editor_quicktags_pre_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );
    
        $check = isset($options['wpui_admin_editor_quicktags_pre']);
    
        echo '<input id="wpui_admin_editor_quicktags_pre" name="wpui_editor_option_name[wpui_admin_editor_quicktags_pre]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_quicktags_pre">'. __( 'Add PRE tag in text editor', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_admin_editor_quicktags_pre'])) {
            esc_attr( $this->options['wpui_admin_editor_quicktags_pre']);
        }
    }    

    public function wpui_admin_editor_formatting_shortcuts_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );
    
        $check = isset($options['wpui_admin_editor_formatting_shortcuts']);
    
        echo '<input id="wpui_admin_editor_formatting_shortcuts" name="wpui_editor_option_name[wpui_admin_editor_formatting_shortcuts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"';
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_formatting_shortcuts">'. __( 'Disable WP formatting shortcuts in text editor', 'wp-admin-ui' ) .'</label>';
    
        if (isset($this->options['wpui_admin_editor_formatting_shortcuts'])) {
            esc_attr( $this->options['wpui_admin_editor_formatting_shortcuts']);
        }
    }

    public function wpui_admin_editor_img_def_align_callback()
    {      

        $options = get_option( 'wpui_editor_option_name' );    
        $selected = $options['wpui_admin_editor_img_def_align'];
        
        echo '<select id="wpui_admin_editor_img_def_align" name="wpui_editor_option_name[wpui_admin_editor_img_def_align]">';
            echo ' <option '; 
                if ('left' == $selected) echo 'selected="selected"'; 
                echo ' value="left">'. __("Left","wp-admin-ui") .'</option>';
            echo '<option '; 
                if ('center' == $selected) echo 'selected="selected"'; 
                echo ' value="center">'. __("Center","wp-admin-ui") .'</option>';
            echo '<option '; 
                if ('right' == $selected) echo 'selected="selected"'; 
                echo ' value="right">'. __("Right","wp-admin-ui") .'</option>';
            echo '<option '; 
                if ('none' == $selected) echo 'selected="selected"'; 
                echo ' value="none">'. __("None","wp-admin-ui") .'</option>';
        echo '</select>';

        if (isset($this->options['wpui_admin_editor_img_def_align'])) {
            esc_attr( $this->options['wpui_admin_editor_img_def_align']);
        }
    }

    public function wpui_admin_editor_img_def_link_callback()
    {      

        $options = get_option( 'wpui_editor_option_name' );    
        $selected = $options['wpui_admin_editor_img_def_link'];
        
        echo '<select id="wpui_admin_editor_img_def_link" name="wpui_editor_option_name[wpui_admin_editor_img_def_link]">';
            echo ' <option '; 
                if ('file' == $selected) echo 'selected="selected"'; 
                echo ' value="file">'. __("File","wp-admin-ui") .'</option>';
            echo '<option '; 
                if ('custom' == $selected) echo 'selected="selected"'; 
                echo ' value="custom">'. __("Custom","wp-admin-ui") .'</option>';
            echo '<option '; 
                if ('post' == $selected) echo 'selected="selected"'; 
                echo ' value="post">'. __("Post","wp-admin-ui") .'</option>';
            echo '<option '; 
                if ('none' == $selected) echo 'selected="selected"'; 
                echo ' value="none">'. __("None","wp-admin-ui") .'</option>';
        echo '</select>';

        if (isset($this->options['wpui_admin_editor_img_def_link'])) {
            esc_attr( $this->options['wpui_admin_editor_img_def_link']);
        }
    }

    public function wpui_admin_editor_img_def_size_callback()
    {      

        $options = get_option( 'wpui_editor_option_name' );    
        $selected = $options['wpui_admin_editor_img_def_size'];

        echo '<select id="wpui_admin_editor_img_def_size" name="wpui_editor_option_name[wpui_admin_editor_img_def_size]">';
            
            if (function_exists('get_intermediate_image_sizes')) {
                get_intermediate_image_sizes();
            }
            if (get_intermediate_image_sizes()) {
                foreach (get_intermediate_image_sizes() as $get_intermediate_image_sizes_key => $get_intermediate_image_sizes_value) {
                    echo ' <option '; 
                        if ($get_intermediate_image_sizes_value == $selected) echo 'selected="selected"'; 
                        echo ' value="'.$get_intermediate_image_sizes_value.'">'. $get_intermediate_image_sizes_value .'</option>';
                }
            }
            
        echo '</select>';

        if (isset($this->options['wpui_admin_editor_img_def_size'])) {
            esc_attr( $this->options['wpui_admin_editor_img_def_size']);
        }
    }

    //Profile
    public function wpui_profil_visual_editor_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_visual_editor']);
        
        echo '<input id="wpui_profil_visual_editor" name="wpui_profil_option_name[wpui_profil_visual_editor]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_visual_editor">'. __( 'Remove Disable the visual editor when writing', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_visual_editor'])) {
            esc_attr( $this->options['wpui_profil_visual_editor']);
        }
    }

    public function wpui_profil_admin_color_scheme_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_admin_color_scheme']);
        
        echo '<input id="wpui_profil_admin_color_scheme" name="wpui_profil_option_name[wpui_profil_admin_color_scheme]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_admin_color_scheme">'. __( 'Remove Admin Color Scheme', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_admin_color_scheme'])) {
            esc_attr( $this->options['wpui_profil_admin_color_scheme']);
        }
    }

    public function wpui_profil_default_color_scheme_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );    
        
        if (isset($options['wpui_profil_default_color_scheme'])) { 
            $check = $options['wpui_profil_default_color_scheme'];
        }
        else {
            $check = 'none';
        }

        echo '<p>';
            _e('<strong>WARNING:</strong> To apply color scheme properly, set in the role manager, what role should apply this setting, then define the default color scheme and save. If a user has already changed its color scheme, it will be erased by the selected one.','wp-admin-ui');
        echo '</p><br/>';

        echo '<input id="wpui_profil_default_color_scheme_none" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('none' == $check) echo 'checked="yes"'; 
        echo ' value="none"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_none">'. __( 'None', 'wp-admin-ui' ) .'</label>';
        
        echo '<br><br>';

        echo '<input id="wpui_profil_default_color_scheme_default" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('default' == $check) echo 'checked="yes"'; 
        echo ' value="default"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_default">'. __( 'Default', 'wp-admin-ui' ) .'</label>';
        
        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_light" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('light' == $check) echo 'checked="yes"'; 
        echo ' value="light"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_light">'. __( 'Light', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_blue" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('blue' == $check) echo 'checked="yes"'; 
        echo ' value="blue"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_blue">'. __( 'Blue', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_coffee" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('coffee' == $check) echo 'checked="yes"'; 
        echo ' value="coffee"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_coffee">'. __( 'Coffee', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_ectoplasm" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('ectoplasm' == $check) echo 'checked="yes"'; 
        echo ' value="ectoplasm"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_ectoplasm">'. __( 'Ectoplasm', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_midnight" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('midnight' == $check) echo 'checked="yes"'; 
        echo ' value="midnight"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_midnight">'. __( 'Midnight', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_ocean" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('ocean' == $check) echo 'checked="yes"'; 
        echo ' value="ocean"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_ocean">'. __( 'Ocean', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_sunrise" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('sunrise' == $check) echo 'checked="yes"'; 
        echo ' value="sunrise"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_sunrise">'. __( 'Sunrise', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_one" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-one' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-one"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_one">'. __( 'WPUI Algua', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_two" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-two' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-two"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_two">'. __( 'WPUI Dark', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_third" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-third' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-third"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_third">'. __( 'WPUI Teal', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_four" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-four' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-four"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_four">'. __( 'WPUI Ice', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_five" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-five' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-five"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_five">'. __( 'WPUI Army', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_six" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-six' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-six"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_six">'. __( 'WPUI Bayonne', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_seven" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-seven' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-seven"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_seven">'. __( 'WPUI Fashion', 'wp-admin-ui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_wpui_eight" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('wpui-eight' == $check) echo 'checked="yes"'; 
        echo ' value="wpui-eight"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_wpui_eight">'. __( 'WPUI Cafe', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_default_color_scheme'])) {
            esc_attr( $this->options['wpui_profil_default_color_scheme']);
        }

        function wpui_profil_default_color_scheme() {
            $wpui_profil_default_color_scheme_option = get_option("wpui_profil_option_name");
            if ( ! empty ( $wpui_profil_default_color_scheme_option ) ) {
                foreach ($wpui_profil_default_color_scheme_option as $key => $wpui_profil_default_color_scheme_value)
                    $options[$key] = $wpui_profil_default_color_scheme_value;
                 if (isset($wpui_profil_default_color_scheme_option['wpui_profil_default_color_scheme'])) { 
                    return $wpui_profil_default_color_scheme_option['wpui_profil_default_color_scheme'];
                 }
            }
        };
        if (wpui_profil_default_color_scheme() != '') {
            function wpui_set_default_color_scheme() {
                $wpui_roles = wpui_roles_list_role();
                foreach ($wpui_roles as $key => $value) {
                    if (array_key_exists( 'default_admin_color_scheme', wpui_get_roles_cap($key))) {
                        $wpui_users = get_users( array( 'role' => $key ) );
                        foreach ($wpui_users as $user) {
                            update_user_meta($user->ID, 'admin_color', wpui_profil_default_color_scheme());
                        }
                    }

                }
                
            }
            echo wpui_set_default_color_scheme();
        }
    }

    public function wpui_profil_keyword_shortcuts_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_keyword_shortcuts']);
        
        echo '<input id="wpui_profil_keyword_shortcuts" name="wpui_profil_option_name[wpui_profil_keyword_shortcuts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_keyword_shortcuts">'. __( 'Remove Enable Keyboard Shortcuts for comment moderation', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_keyword_shortcuts'])) {
            esc_attr( $this->options['wpui_profil_keyword_shortcuts']);
        }
    }

    public function wpui_profil_show_toolbar_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_show_toolbar']);
        
        echo '<input id="wpui_profil_show_toolbar" name="wpui_profil_option_name[wpui_profil_show_toolbar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_show_toolbar">'. __( 'Remove Show Toolbar when viewing site', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_show_toolbar'])) {
            esc_attr( $this->options['wpui_profil_show_toolbar']);
        }
    }    

    public function wpui_profil_facebook_field_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_facebook_field']);
        
        echo '<input id="wpui_profil_facebook_field" name="wpui_profil_option_name[wpui_profil_facebook_field]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_facebook_field">'. __( 'Add Facebook field in user profile', 'wp-admin-ui' ) .'</label>  <span class="dashicons dashicons-info" title="'.__('Use get_the_author_meta(\'wpui-facebook\'); to retreive this value in front-end','wp-admin-ui').'"></span>';
        
        if (isset($this->options['wpui_profil_facebook_field'])) {
            esc_attr( $this->options['wpui_profil_facebook_field']);
        }
    }

    public function wpui_profil_twitter_field_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_twitter_field']);
        
        echo '<input id="wpui_profil_twitter_field" name="wpui_profil_option_name[wpui_profil_twitter_field]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_twitter_field">'. __( 'Add Twitter field in user profile', 'wp-admin-ui' ) .'</label> <span class="dashicons dashicons-info" title="'.__('Use get_the_author_meta(\'wpui-twitter\'); to retreive this value in front-end','wp-admin-ui').'"></span>';
        
        if (isset($this->options['wpui_profil_twitter_field'])) {
            esc_attr( $this->options['wpui_profil_twitter_field']);
        }
    }

    public function wpui_profil_instagram_field_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_instagram_field']);
        
        echo '<input id="wpui_profil_instagram_field" name="wpui_profil_option_name[wpui_profil_instagram_field]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_instagram_field">'. __( 'Add Instagram field in user profile', 'wp-admin-ui' ) .'</label> <span class="dashicons dashicons-info" title="'.__('Use get_the_author_meta(\'wpui-instagram\'); to retreive this value in front-end','wp-admin-ui').'"></span>';
        
        if (isset($this->options['wpui_profil_instagram_field'])) {
            esc_attr( $this->options['wpui_profil_instagram_field']);
        }
    }    

    public function wpui_profil_linkedin_field_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_linkedin_field']);
        
        echo '<input id="wpui_profil_linkedin_field" name="wpui_profil_option_name[wpui_profil_linkedin_field]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_linkedin_field">'. __( 'Add LinkedIn field in user profile', 'wp-admin-ui' ) .'</label> <span class="dashicons dashicons-info" title="'.__('Use get_the_author_meta(\'wpui-linkedin\'); to retreive this value in front-end','wp-admin-ui').'"></span>';
        
        if (isset($this->options['wpui_profil_linkedin_field'])) {
            esc_attr( $this->options['wpui_profil_linkedin_field']);
        }
    }

    //Media Library
    public function wpui_library_jpeg_quality_callback()
    {
        printf(
        '<input name="wpui_library_option_name[wpui_library_jpeg_quality]" type="number" min="1" max="100" value="%s" /><span class="dashicons dashicons-info" title="'.__('Existing images must be regenerate to apply new image quality.','wp-admin-ui').'"></span>',
        esc_attr( $this->options['wpui_library_jpeg_quality'])
        
        );
    }

    public function wpui_library_clean_filename_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_clean_filename']);      
        
        echo '<input id="wpui_library_clean_filename" name="wpui_library_option_name[wpui_library_clean_filename]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        
        echo '<label for="wpui_library_clean_filename">'. __( 'Clean filenames when upload files to media library', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_clean_filename'])) {
            esc_attr( $this->options['wpui_library_clean_filename']);
        }
    }

    public function wpui_library_svg_mimes_type_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_svg']);      
        
        echo '<input id="wpui_library_svg" name="wpui_library_option_name[wpui_library_svg]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        
        echo '<label for="wpui_library_svg">'. __( 'Allow SVG file in media library (can present a security risk)', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_svg'])) {
            esc_attr( $this->options['wpui_library_svg']);
        }
    }    

    public function wpui_library_url_col_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_url_col']);      
        
        echo '<input id="wpui_library_url_col" name="wpui_library_option_name[wpui_library_url_col]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        
        echo '<label for="wpui_library_url_col">'. __( 'Add URL column in media library', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_url_col'])) {
            esc_attr( $this->options['wpui_library_url_col']);
        }
    }    

    public function wpui_library_dimensions_col_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_dimensions_col']);      
        
        echo '<input id="wpui_library_dimensions_col" name="wpui_library_option_name[wpui_library_dimensions_col]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        
        echo '<label for="wpui_library_dimensions_col">'. __( 'Add dimensions column in media library', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_dimensions_col'])) {
            esc_attr( $this->options['wpui_library_dimensions_col']);
        }
    }

    public function wpui_library_exif_col_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_exif_col']);      
        
        echo '<input id="wpui_library_exif_col" name="wpui_library_option_name[wpui_library_exif_col]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        
        echo '<label for="wpui_library_exif_col">'. __( 'Add EXIF metadata column in media library', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_exif_col'])) {
            esc_attr( $this->options['wpui_library_exif_col']);
        }
    }    

    public function wpui_library_id_col_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_id_col']);      
        
        echo '<input id="wpui_library_id_col" name="wpui_library_option_name[wpui_library_id_col]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        
        echo '<label for="wpui_library_id_col">'. __( 'Add ID column in media library', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_id_col'])) {
            esc_attr( $this->options['wpui_library_id_col']);
        }
    }

    public function wpui_library_filters_pdf_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        if (array_key_exists('application/pdf', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_pdf']);
        }      
        
        echo '<input id="wpui_library_filters_pdf" name="wpui_library_option_name[wpui_library_filters_pdf]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/pdf', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_pdf">'. __( 'Add PDF filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_pdf'])) {
            esc_attr( $this->options['wpui_library_filters_pdf']);
        }
    }

    public function wpui_library_filters_zip_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/zip', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_zip']);
        }
        
        $check = isset($options['wpui_library_filters_zip']);
        
        echo '<input id="wpui_library_filters_zip" name="wpui_library_option_name[wpui_library_filters_zip]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/zip', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_zip">'. __( 'Add ZIP filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_zip'])) {
            esc_attr( $this->options['wpui_library_filters_zip']);
        }
    }

    public function wpui_library_filters_rar_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        if (array_key_exists('application/rar', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_rar']);
        }

        $check = isset($options['wpui_library_filters_rar']);
        
        echo '<input id="wpui_library_filters_rar" name="wpui_library_option_name[wpui_library_filters_rar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/rar', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_rar">'. __( 'Add RAR filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_rar'])) {
            esc_attr( $this->options['wpui_library_filters_rar']);
        }
    }

    public function wpui_library_filters_7z_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/x-7z-compressed', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_7z']);
        }
        
        $check = isset($options['wpui_library_filters_7z']);
        
        echo '<input id="wpui_library_filters_7z" name="wpui_library_option_name[wpui_library_filters_7z]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/x-7z-compressed', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_7z">'. __( 'Add 7Z filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_7z'])) {
            esc_attr( $this->options['wpui_library_filters_7z']);
        }
    }

    public function wpui_library_filters_tar_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/x-tar', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_tar']);
        }
        
        $check = isset($options['wpui_library_filters_tar']);
        
        echo '<input id="wpui_library_filters_tar" name="wpui_library_option_name[wpui_library_filters_tar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/x-tar', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_tar">'. __( 'Add TAR filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_tar'])) {
            esc_attr( $this->options['wpui_library_filters_tar']);
        }
    }

    public function wpui_library_filters_swf_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/x-shockwave-flash', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_swf']);
        }
        
        $check = isset($options['wpui_library_filters_swf']);
        
        echo '<input id="wpui_library_filters_swf" name="wpui_library_option_name[wpui_library_filters_swf]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/x-shockwave-flash', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_swf">'. __( 'Add SWF filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_swf'])) {
            esc_attr( $this->options['wpui_library_filters_swf']);
        }
    }

    public function wpui_library_filters_doc_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/msword', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_doc']);
        }
        
        $check = isset($options['wpui_library_filters_doc']);
        
        echo '<input id="wpui_library_filters_doc" name="wpui_library_option_name[wpui_library_filters_doc]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/msword', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_doc">'. __( 'Add DOC filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_doc'])) {
            esc_attr( $this->options['wpui_library_filters_doc']);
        }
    }

    public function wpui_library_filters_docx_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/vnd.openxmlformats-officedocument.wordprocessingml.document', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_docx']);
        }
        
        $check = isset($options['wpui_library_filters_docx']);
        
        echo '<input id="wpui_library_filters_docx" name="wpui_library_option_name[wpui_library_filters_docx]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/vnd.openxmlformats-officedocument.wordprocessingml.document', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_docx">'. __( 'Add DOCX filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_docx'])) {
            esc_attr( $this->options['wpui_library_filters_docx']);
        }
    }

    public function wpui_library_filters_ppt_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/vnd.ms-powerpoint', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_ppt']);
        }
        
        $check = isset($options['wpui_library_filters_ppt']);
        
        echo '<input id="wpui_library_filters_ppt" name="wpui_library_option_name[wpui_library_filters_ppt]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/vnd.ms-powerpoint', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_ppt">'. __( 'Add PPT filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_ppt'])) {
            esc_attr( $this->options['wpui_library_filters_ppt']);
        }
    }

    public function wpui_library_filters_pptx_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  

        if (array_key_exists('application/vnd.openxmlformats-officedocument.presentationml.presentation', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_pptx']);
        }
        
        $check = isset($options['wpui_library_filters_pptx']);
        
        echo '<input id="wpui_library_filters_pptx" name="wpui_library_option_name[wpui_library_filters_pptx]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/vnd.openxmlformats-officedocument.presentationml.presentation', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_pptx">'. __( 'Add PPTX filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_pptx'])) {
            esc_attr( $this->options['wpui_library_filters_pptx']);
        }
    }

    public function wpui_library_filters_xls_callback()
    {
        $options = get_option( 'wpui_library_option_name' );

        if (array_key_exists('application/vnd.ms-excel', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_xls']);
        }  
        
        $check = isset($options['wpui_library_filters_xls']);
        
        echo '<input id="wpui_library_filters_xls" name="wpui_library_option_name[wpui_library_filters_xls]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/vnd.ms-excel', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_xls">'. __( 'Add XLS filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_xls'])) {
            esc_attr( $this->options['wpui_library_filters_xls']);
        }
    }

    public function wpui_library_filters_xlsx_callback()
    {
        $options = get_option( 'wpui_library_option_name' );

        if (array_key_exists('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', get_post_mime_types())) {
            $check = '1';
        } else {
            $check = isset($options['wpui_library_filters_xlsx']);
        }   
        
        $check = isset($options['wpui_library_filters_xlsx']);
        
        echo '<input id="wpui_library_filters_xlsx" name="wpui_library_option_name[wpui_library_filters_xlsx]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        if (array_key_exists('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', get_post_mime_types())) {
            echo ' value="0" disabled/><span class="dashicons dashicons-info" title="'.__('This option can not be changed because it\'s bypassed by a plugin, a mu-plugin or your theme.','wp-admin-ui').'"></span>';
        } else {
            echo ' value="1"/>';
        }
        echo '<label for="wpui_library_filters_xlsx">'. __( 'Add XLSX filter', 'wp-admin-ui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_xlsx'])) {
            esc_attr( $this->options['wpui_library_filters_xlsx']);
        }
    }
    
    //Roles
    public function wpui_roles_list_role_callback()
    {
        submit_button();
        global $pagenow;
        if (( $pagenow == 'admin.php' ) && ($_GET['page'] == 'wpui-roles')) {
            $options = get_option( 'wpui_roles_option_name' );  
            
            $wpui_all_settings = array(
                'Global' => array(
                    'custom_admin_css' => __('Custom admin CSS','wp-admin-ui'),
                    'remove_wp_version' => __('Remove WordPress version in footer','wp-admin-ui'),
                    'custom_wp_version' => __('Custom WordPress version in footer','wp-admin-ui'),
                    'remove_wp_credits' => __('Remove WordPress credits in footer','wp-admin-ui'),
                    'custom_wp_credits' => __('Custom WordPress credits in footer','wp-admin-ui'),
                    'custom_favicon' => __('Custom favicon in admin','wp-admin-ui'),
                    'remove_help_tab' => __('Remove help tab','wp-admin-ui'),
                    'remove_screen_tab' => __('Remove screen options tab','wp-admin-ui'),
                    'disable_wp_update_notifications' => __('Disable WordPress updates notifications','wp-admin-ui'),
                    'hide_pwd_msg' => __('Hide autogenerated password message','wp-admin-ui'),
                    'items_per_page_list' => __('Number of items per page in list view (default 20)','wp-admin-ui'),
                    'view_mode' => __('Define default view mode in list view (default list view mode)','wp-admin-ui'),
                    'disable_file_editor' => __('Disable file editor for themes and plugins','wp-admin-ui'),
                    'disable_plugin_theme_update_installation' => __('Disable Plugin and Theme Update, and Installation','wp-admin-ui'),
                    'block_admin' => __('Block WordPress admin for user roles','wp-admin-ui'),
                    'disable_all_updates' => __('Disable all updates','wp-admin-ui'),
                    'disable_core_updates' => __('Disable core updates','wp-admin-ui'),
                    'disable_core_dev_updates' => __('Disable core development updates','wp-admin-ui'),
                    'disable_minor_core_updates' => __('Disable minor core updates','wp-admin-ui'),
                    'disable_major_core_updates' => __('Disable major core updates','wp-admin-ui'),
                    'enable_updates_vcs' => __('Enable automatic updates on Versioning Control System (GIT/SVN)','wp-admin-ui'),
                    'disable_automatic_plugins_updates' => __('Disable automatic updates for all plugins','wp-admin-ui'),
                    'disable_automatic_themes_updates' => __('Disable automatic updates for all themes','wp-admin-ui'),
                    'disable_automatic_translations_updates' => __('Disable automatic updates for all translations','wp-admin-ui'),
                    'disable_updates_emails' => __('Disable update emails notifications','wp-admin-ui'),
                    'disable_js_concatenation' => __('Disable JS concatenation','wp-admin-ui'),
                    'disable_open_sans' => __('Disable Open Sans loading from Google','wp-admin-ui'),
                    'custom_avatar' => __('Enable custom avatar','wp-admin-ui'),
                    ), 
                'Dashboard' => array(
                    'remove_welcome_panel' => __('Remove Welcome Panel','wp-admin-ui'),
                    'display_single_column' => __('Display Dashboard in a single column','wp-admin-ui'),
                    'listing_widgets' => __('Remove dashboard widgets','wp-admin-ui'),
                    'remove_widgets_drag_and_drop' => __('Disable drag and drop for dashboard widgets','wp-admin-ui'),
                    'at_a_glance_cpt' => __('Display all custom post types in At a glance dashboard widget','wp-admin-ui'),
                    'at_a_glance_users' => __('Display number of users in At a glance dashboard widget','wp-admin-ui'),
                    'custom_widget' => __('Display a custom widget in Dashboard','wp-admin-ui'),
                    ),
                'Admin Menu' => array(
                    'menu_structure' => __('Menu Structure','wp-admin-ui'),
                    'menu_all_settings' => __('Display all settings in menu','wp-admin-ui'),
                    ),
                'Admin Bar' => array(
                    'remove_wp_logo' => __('Remove WordPress logo in admin bar','wp-admin-ui'),
                    'custom_wp_logo' => __('Custom logo in admin bar','wp-admin-ui'),
                    'remove_site_name' => __('Remove Site Name in admin bar','wp-admin-ui'),
                    'remove_my_account' => __('Remove My Account in admin bar','wp-admin-ui'),
                    'remove_menu_toggle' => __('Remove Menu Toggle in admin bar','wp-admin-ui'),
                    'remove_edit' => __('Remove Edit in admin bar','wp-admin-ui'),
                    'remove_view' => __('Remove View in admin bar','wp-admin-ui'),
                    'remove_preview' => __('Remove Preview in admin bar','wp-admin-ui'),
                    'remove_comments' => __('Remove Comments in admin bar','wp-admin-ui'),
                    'remove_new_content' => __('Remove New Content in admin bar','wp-admin-ui'),
                    'remove_view_site' => __('Remove View Site in admin bar','wp-admin-ui'),
                    'remove_updates' => __('Remove Updates in admin bar','wp-admin-ui'),
                    'remove_customize' => __('Remove customize in admin bar','wp-admin-ui'),
                    'remove_search' => __('Remove search in admin bar','wp-admin-ui'),
                    'remove_howdy' => __('Remove Howdy in admin bar','wp-admin-ui'),
                    'remove_wpui' => __('Remove WP Admin UI in admin bar','wp-admin-ui'),
                    'disable_admin_bar' => __('Disable admin bar in front-end','wp-admin-ui'),
                    ),
                'Editor' => array(
                    'enable_full_tinymce' => __('Enable Full TinyMCE by default','wp-admin-ui'),
                    'font_size_select' => __('Add Font Size select','wp-admin-ui'),
                    'font_family_select' => __('Add Font Family select','wp-admin-ui'),
                    'custom_fonts_select' => __('Add custom Fonts select','wp-admin-ui'),
                    'formats_select' => __('Add Formats select (styles)','wp-admin-ui'),
                    'remove_get_shortlink' => __('Remove Get Shortlink button','wp-admin-ui'),
                    'new_doc_btn' => __('Add New Document button','wp-admin-ui'),
                    'cut_btn' => __('Add Cut button','wp-admin-ui'),
                    'copy_btn' => __('Add Copy button','wp-admin-ui'),
                    'paste_btn' => __('Add Paste button','wp-admin-ui'),
                    'backcolor_btn' => __('Add Backcolor button','wp-admin-ui'),
                    'remove_insert_media_modal' => __('Remove Insert Media in Media Modal','wp-admin-ui'),
                    'remove_upload_media_modal' => __('Remove Upload Files in Media Modal','wp-admin-ui'),
                    'remove_library_media_modal' => __('Remove Media Library in Media Modal','wp-admin-ui'),
                    'remove_gallery_media_modal' => __('Remove Create Gallery in Media Modal','wp-admin-ui'),
                    'remove_playlist_media_modal' => __('Remove Create Playlist in Media Modal','wp-admin-ui'),
                    'remove_set_featured_media_modal' => __('Remove Set Featured Image in Media Modal','wp-admin-ui'),
                    'remove_insert_url_media_modal' => __('Remove Insert From URL in Media Modal','wp-admin-ui'),
                    'p_quicktags' => __('Add "p" quicktags in Text Editor','wp-admin-ui'),
                    'hr_quicktags' => __('Add "hr" quicktags in Text Editor','wp-admin-ui'),
                    'pre_quicktags' => __('Add "pre" quicktags in Text Editor','wp-admin-ui'),
                    'formatting_shortcuts' => __('Disable formatting_shortcuts in Text Editor','wp-admin-ui'),
                    'img_default_align' => __('Set a default image alignment in Text Editor','wp-admin-ui'),
                    'img_defaut_link_type' => __('Set a default link type on images in Text Editor','wp-admin-ui'),
                    'img_default_size' => __('Set a default image size in Text Editor','wp-admin-ui'),
                    ),
                'Metaboxes (PRO only)' => array(
                    'listing_metaboxes' => __('Remove metaboxes','wp-admin-ui'),
                    ),
                'Columns (PRO Only)' => array(
                    'listing_columns' => __('Remove columns','wp-admin-ui'),
                    'columns_show_id' => __('Show post ID column','wp-admin-ui'),
                    'columns_show_thumb' => __('Show Thumbnail column','wp-admin-ui'),
                    'columns_show_template' => __('Show Page Template column','wp-admin-ui'),
                    ),
                'Media Library' => array(
                    'jpg_quality' => __('Define JPG image quality (default 82%)','wp-admin-ui'),
                    'clean_filenames' => __('Clean filenames when uploading files to media library','wp-admin-ui'),
                    'svg_mimes_type' => __('Allow SVG file in media library','wp-admin-ui'),
                    'url_col' => __('Add URL column in media library','wp-admin-ui'),
                    'dimensions_col' => __('Add Dimensions column in media library','wp-admin-ui'),
                    'exif_col' => __('Add EXIF metadata column in media library','wp-admin-ui'),
                    'id_col' => __('Add ID column in media library','wp-admin-ui'),
                    'pdf_filter' => __('Add PDF filtering to media library','wp-admin-ui'),
                    'zip_filter' => __('Add ZIP filtering to media library','wp-admin-ui'),
                    'rar_filter' => __('Add RAR filtering to media library','wp-admin-ui'),
                    '7z_filter' => __('Add 7Z filtering to media library','wp-admin-ui'),
                    'tar_filter' => __('Add TAR filtering to media library','wp-admin-ui'),
                    'swf_filter' => __('Add SWF filtering to media library','wp-admin-ui'),
                    'doc_filter' => __('Add DOC filtering to media library','wp-admin-ui'),
                    'docx_filter' => __('Add DOCX filtering to media library','wp-admin-ui'),
                    'ppt_filter' => __('Add PPT filtering to media library','wp-admin-ui'),
                    'pptx_filter' => __('Add PPTX filtering to media library','wp-admin-ui'),
                    'xls_filter' => __('Add XLS filtering to media library','wp-admin-ui'),
                    'xlsx_filter' => __('Add XLSX filtering to media library','wp-admin-ui'),
                    ),
                'Profile' => array(
                    'remove_disable_visual_editor' => __('Remove Disable the visual editor when writing','wp-admin-ui'),
                    'remove_admin_color_scheme' => __('Remove Admin Color Scheme','wp-admin-ui'),
                    'default_admin_color_scheme' => __('Set a default admin color scheme','wp-admin-ui'),
                    'remove_enable_keyboard_shortcuts' => __('Remove Enable Keyboard Shortcuts for comment moderation','wp-admin-ui'),
                    'remove_show_toolbar' => __('Remove Show Toolbar when viewing site','wp-admin-ui'),
                    'add_facebook_field' => __('Add Facebook field in Profile','wp-admin-ui'),
                    'add_twitter_field' => __('Add Twitter field in Profile','wp-admin-ui'),
                    'add_instagram_field' => __('Add Instagram field in Profile','wp-admin-ui'),
                    'add_linkedin_field' => __('Add LinkedIn field in Profile','wp-admin-ui'),
                    ),
                'Plugins (PRO Only)' => array(
                    'remove_wp_seo_cols' => __('Remove WP SEO columns in list view','wp-admin-ui'),
                    'remove_wpseo_admin_bar' => __('Remove WP SEO in admin bar','wp-admin-ui'),
                    'move_wp_seo_cols' => __('Move WP SEO Metabox to low position','wp-admin-ui'),
                    'remove_wp_seo_notices' => __('Remove WP SEO admin notices','wp-admin-ui'),
                    'remove_aio_seo_notices' => __('Remove All In One SEO admin notices','wp-admin-ui'),
                    'remove_wpml_ad' => __('Remove WPML advert in publish metabox','wp-admin-ui'),
                    'remove_wpml_admin_bar' => __('Remove WPML in admin bar','wp-admin-ui'),
                    'remove_wpml_dashboard' => __('Remove WPML in dashboard widget','wp-admin-ui'),
                    'remove_woothemes_installer' => __('Remove Install the WooThemes Updater plugin','wp-admin-ui'),
                    'field_visibility_gravity_forms' => __('Enable field label visibility in Gravity Forms','wp-admin-ui'),
                    'jetpack_just_in_time' => __('Disable Just in Time messages from Jetpack','wp-admin-ui'),
                    ),
                'WooCommerce (PRO Only)' => array(
                    'remove_woo_downloadable_product' => __('Remove downloadable / virtual product checkboxe','wp-admin-ui'),
                    'remove_woo_product_data' => __('Remove product data type in select (simple product, grouped product...)','wp-admin-ui'),
                    'remove_woo_product_data_tabs' => __('Remove product data tabs (general, inventory, shipping...)','wp-admin-ui'),
                    ),
                'Themes (PRO Only)' => array(
                    'wpui-custom-themes' => __('Enable custom admin Themes','wp-admin-ui'),
                    ),
            );

            global $wp_roles;
     
            $wpui_roles = $wp_roles->get_names();
            
            echo '<table class="wpui-table" border="0" cellspacing="0" cellpadding="0">';
            
            foreach ($wpui_all_settings as $wpui_all_settings_key => $wpui_all_settings_value) {
                echo '<tr class="wpui-settings-section"><th>'.$wpui_all_settings_key.'</th>';
                foreach ($wpui_roles as $wpui_role_key => $wpui_role_value) {
                    echo '
                        <script>
                        function toggle_'.$wpui_role_key.'(source) {
                            checkboxes = jQuery("input[id^=wpui_roles_list_role_'.$wpui_role_key.'][name*='.$wpui_role_key.']");
                            
                            for(var i=0, n=checkboxes.length;i<n;i++) {
                                checkboxes[i].checked = source.checked;
                            }

                            checkboxes2 = jQuery("input[name=wpui_roles_list_role_'.$wpui_role_key.']");
                            for(var i=0, n=checkboxes2.length;i<n;i++) {
                                checkboxes2[i].checked = source.checked;
                            }
                        }
                        </script>
                    ';
                    echo '<td><label for="wpui_roles_list_role_'.$wpui_role_key.'">'.translate_user_role($wpui_role_value).'<br> <small>('.$wpui_role_value.')</small></label><br/>
                    <input type="checkbox" name="wpui_roles_list_role_'.$wpui_role_key.'" onClick="toggle_'.$wpui_role_key.'(this)" /><span class="screen-reader-text">'. __('Toggle All','wp-admin-ui').'</span></td>';
                }
                echo '</tr>';
                
                foreach ($wpui_all_settings_value as $_wpui_all_settings_key => $_wpui_all_settings_value) {
                    echo '<tr><th>'.$_wpui_all_settings_value.'</th>';
               
                    foreach ($wpui_roles as $wpui_role_key => $wpui_role_value) {
                        
                        $check = isset($options['wpui_roles_list_role'][$wpui_role_key][$_wpui_all_settings_key]);
                        echo '<td>';
                        echo '<input id="wpui_roles_list_role_'.$wpui_role_key.'_'.$_wpui_all_settings_key.'" name="wpui_roles_option_name[wpui_roles_list_role]['.$wpui_role_key.']['.$_wpui_all_settings_key.']" type="checkbox"';
                        if ('1' == $check) echo 'checked="yes"'; 
                        echo ' value="1"/>';
                        echo '<label for="wpui_roles_list_role_'.$wpui_role_key.'_'.$_wpui_all_settings_key.'"></label>';

                        if (isset($this->options['wpui_roles_list_role'][$wpui_role_key][$_wpui_all_settings_key])) {
                            esc_attr( $this->options['wpui_roles_list_role'][$wpui_role_key][$_wpui_all_settings_key]);
                        }
                        echo '</td>';
                    }
                    echo '</tr>';
                }
            }
            echo '</table>';
        }
    }
}
	
if( is_admin() )
    $my_settings_page = new wpui_options();
	
?>