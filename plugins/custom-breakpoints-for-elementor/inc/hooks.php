<?php

namespace MasterCustomBreakPoint\Inc;

use MasterCustomBreakPoint\JLTMA_Master_Custom_Breakpoint;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

class JLTMA_Master_Custom_Breakpoint_Hooks {

    public function __construct() {

        add_action( 'admin_menu', [$this, 'jltma_mcb_menu'], 55 );

        // Import Settings
        add_action( 'wp_ajax_jltma_cbp_import_elementor_settings', [$this, 'jltma_cbp_import_elementor_settings']);
        add_action( 'wp_ajax_nopriv_jltma_cbp_import_elementor_settings', [$this, 'jltma_cbp_import_elementor_settings']);

        // Export Setttings
        add_action( 'admin_post_jltma_mcb_export_settings', [$this, 'jltma_mcb_export_settings']);

        // Reset Form
        add_action( 'wp_ajax_jltma_mcb_reset_settings', [$this, 'jltma_mcb_reset_settings']);
        add_action( 'wp_ajax_nopriv_jltma_mcb_reset_settings', [$this, 'jltma_mcb_reset_settings']);

        // Save Breakpoint Settings
        add_action( 'wp_ajax_jltma_mcb_save_settings', [$this, 'jltma_mcb_save_settings']);
        add_action( 'wp_ajax_nopriv_jltma_mcb_save_settings', [ $this,'jltma_mcb_save_settings']);

        add_action( 'tgmpa_register', [$this,'jltma_mcb__register_required_plugins'] );
        add_action( 'admin_enqueue_scripts', [$this,'hooks_scripts'] );

    }

    public function jltma_mcb_menu() {

        if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        $page_title = esc_html__('Master Custom Breakpoints', JLTMA_MCB_TD);
        $menu_title = esc_html__('Breakpoints', JLTMA_MCB_TD);
        $capability = 'manage_options';
        $menu_slug  = 'master-custom-breakpoints';
        $callback   = array( $this, 'jltma_mcb_content');

        if ( is_plugin_active( 'master-addons/master-addons.php' ) || is_plugin_active( 'master-addons-pro/master-addons.php' ) ) {
            return add_submenu_page( 'master-addons-settings', $page_title, $menu_title, $capability, $menu_slug, $callback );
        }
        
        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $callback, 'dashicons-desktop', 57 );

    }

    public function jltma_mcb_content() {

        if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
            echo esc_html__("Please Active Elementor Plugin", JLTMA_MCB_TD);
            return;
        }

        ?>

        <div class="jltma-wrap">
            
            <h2 style="margin-bottom: 15px;"><?php echo esc_html_e( JLTMA_Master_Custom_Breakpoint::$plugin_name, JLTMA_MCB_TD ); ?></h2>

            <em style="font-weight: 500;">
                <?php echo esc_html_e( 'You can Drag and Drop Breakpoint positions. Make sure click "Save Settings"', JLTMA_MCB_TD ); ?>
            </em>

            <br><br>

            <?php if ( isset($_POST['updated']) && $_POST["updated"] === 'true' ) $this->handle_form(); ?>

            <form method="POST" class="jlmta-cbp-input-form" id="jlmta-cbp-form">

                <div class="jltma-spinner"></div>

                <?php wp_nonce_field( 'breakpoints_update', 'breakpoints_form' ); ?>

                <div id="custom_breakpoints_page">

                    <div id="master_cbp_table" class="master_cbp_table">
        
                        <ul>
                            <li><?php echo esc_html__('Device Name', JLTMA_MCB_TD);?></li>
                            <li><?php echo esc_html__('Min-Width', JLTMA_MCB_TD);?></li>
                            <li><?php echo esc_html__('Max-Width', JLTMA_MCB_TD);?></li>
                            <li><?php echo esc_html__('Actions', JLTMA_MCB_TD);?></li>
                        </ul>
        
                        <ul :class="['breakpoint-row', breakpoint.isRecent ? 'breakpoint-is-recent' : '' ]" v-for="(breakpoint, index) in sorted_breakpoints" :key="index" :data-key="breakpoint.key">
        
                            <!-- Name -->
                            <li class="bp-name" :data-label="breakpoint.name">
                                <input type="text" :name="breakpoint.name" :value="breakpoint.name" disabled v-if="in_array( breakpoint.key, default_devices )">
                                <input type="text" :name="breakpoint.name" v-model="breakpoint.name" v-else>
                            </li>
        
                            <!-- Min Width -->
                            <li class="bp-min-width">
                                <input type="number" :value="breakpoint.min" disabled>
                            </li>
        
                            <!-- Max Width -->
                            <li class="bp-max-width" v-if="in_array( breakpoint.key, default_devices )">
                                <input type="number" disabled v-if="breakpoint.key == 'desktop'">
                                <input type="number" :value="breakpoint.max" disabled v-else>
                            </li>
                            <li class="bp-max-width" v-else>
                                <input class="jlma-mcb-model jlma-mcb-model--max" type="number" :value="breakpoint.max" @change="breakpoint_update($event, breakpoint)" @focus="input_focused(breakpoint)">
                            </li>
        
                            <!-- Actions -->
                            <li class="bp-actions">
                                <div class="button button-primary jltma-cbp-remove" @click="remove_breakpoint(breakpoint.key)" v-if="!in_array( breakpoint.key, default_devices )">x</div>
                            </li>
        
                        </ul>
        
                    </div>
        
                    <?php

                    if ( function_exists( 'ma_el_fs' ) ) {

                        if ( ! ma_el_fs()->can_use_premium_code() ) {
                            
                            $pro_message = sprintf( __( '<a href="%1$s">Upgrade to Pro</a> for Unlimited Options. <a href="%1$s">Upgrade Now</a>', JLTMA_MCB_TD ), ma_el_fs()->get_upgrade_url() );
                            printf( '<div id="jltma-mcb-message" v-if="show_pro_message">%1$s</div>', $pro_message );
                            echo esc_html__( '', JLTMA_MCB_TD );

                        }
        
                    } else {
        
                        $pro_message = sprintf( __( '<a href="%1$s">Upgrade to Pro</a> for Unlimited Options. <a href="%1$s">Upgrade Now</a>', JLTMA_MCB_TD ), esc_url_raw('https://master-addons.com/pricing/') );
                        printf( '<div id="jltma-mcb-message" v-if="show_pro_message">%1$s</div>', $pro_message );
                        echo esc_html__( '', JLTMA_MCB_TD );
        
                    } ?>
        
                    <div class="submit">
                        <div :class="['button button-primary jltma-cbp-add', disable_add_breakpoint ? 'jltma-disabled' : '']" @click="add_breakpoint"><?php echo esc_html__('Add Breakpoint', JLTMA_MCB_TD);?></div>
                        <input type="submit" name="submit" id="submit" class="button button-primary jltma-cbp-save" value="<?php echo esc_html__('Save Breakpoints', JLTMA_MCB_TD);?>">
                    </div>

                </div>

            </form>

        </div>

        <div class="jltma-wrap">

            <h2><?php echo esc_html__('Breakpoint Settings', JLTMA_MCB_TD);?></h2>

            <div class="jltma-mcb-settings master_cbp_table <?php if ( function_exists( 'ma_el_fs' ) ) { if ( ! ma_el_fs()->can_use_premium_code() ) { echo "jltma-mcb-disabled"; } } else{ echo "jltma-mcb-disabled"; }?>">

                <?php if ( function_exists( 'ma_el_fs' ) ) {
                    if ( ! ma_el_fs()->can_use_premium_code() ) {
                        echo '<span class="jltma-mcb-pro-badge eicon-pro-icon"></span>';
                    }
                } else {
                    echo '<span class="jltma-mcb-pro-badge eicon-pro-icon"></span>';
                } ?>

                <ul>
                    <li>
                        <strong>
                            <?php echo esc_html__('Reset Settings', JLTMA_MCB_TD);?>
                        </strong>
                    </li>
                    <li>
                        <strong>
                            <?php echo esc_html__('Export Settings', JLTMA_MCB_TD);?>
                        </strong>
                    </li>
                    <li>
                        <strong>
                            <?php echo esc_html__('Import Settings', JLTMA_MCB_TD);?>
                        </strong>
                    </li>
                </ul>

                <ul>
                    <li data-label='Reset Settings'>
                        <div style="margin: 20px 0px;">
                            <form id="elementor_settings_reset_form" method="post">
                                <?php wp_nonce_field( 'breakpoints_reset', 'reset_form' ); ?>
                                <button type="submit" class="button button-primary jltma-cbp-reset">
                                    <?php echo esc_html__('Reset Settings', JLTMA_MCB_TD);?>
                                </button>
                            </form>

                            <div id="reset_success" class='updated' style="display: none;">
                                <p><?php echo esc_html__('Reset Settings', JLTMA_MCB_TD);?></p>
                            </div>
                        </div>
                        <br>
                    </li>

                    <li data-label='Export Settings'>
                        <div style="margin: 20px 0px;">
                            <div class="button button-primary jltma-cbp-add" onclick="window.open('admin-post.php?action=jltma_mcb_export_settings');">
                                <?php echo esc_html__('Export Settings', JLTMA_MCB_TD);?>
                            </div>
                        </div>
                        <br>
                    </li>

                    <li>
                        <form id="elementor_settings_import_form" enctype="multipart/form-data" method="post">
                            <?php wp_nonce_field('jltma-mcb-import'); ?>
                            <input name="jltma_mcb" type="file" />
                            <input type="hidden" name="action" value="jltma_cbp_import_elementor_settings">
                            <br>
                            <button type="submit" class="button button-primary jltma-cbp-save">
                                <?php echo esc_html__('Import Settings', JLTMA_MCB_TD);?>
                            </button>
                        </form>

                        <div id="elementor_import_success" class='updated' style="display: none;">
                            <p><?php echo esc_html__('Settings Imported', JLTMA_MCB_TD);?></p>
                        </div>

                    </li>
                </ul>

            </div>

        </div>

        <?php

    }

    public function jltma_mcb_save_settings() {

        if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'breakpoints_update' ) ) {
            wp_send_json_error(  esc_html__( 'Security Error.', JLTMA_MCB_TD ) );
        }

        $form_fields = isset($_POST['form_fields']) ? $_POST['form_fields']: [];
        
        $custom_breakpoints = [];

        $free_limit = 2;

        foreach ( $form_fields as $key => $value ) {

            if ( $value['default_value'] < 1 ) continue;

            if ( ! wp_validate_boolean( function_exists( 'ma_el_fs' ) && ma_el_fs()->can_use_premium_code() ) ) {
                if ( count($custom_breakpoints) >= $free_limit ) continue;
            }

            $custom_breakpoints["breakpoint{$key}"] = [
                'label'         => sanitize_text_field( $value['label'] ),
                'default_value' => (int) $value['default_value'],
                'direction'     => sanitize_text_field( $value['direction'] )
            ];

        }

        update_option( 'jltma_mcb', $custom_breakpoints );

        die();

    }

    public function handle_form() {

        // Save Breakpoints
        if( ! isset( $_POST['breakpoints_form'] ) || ! wp_verify_nonce( $_POST['breakpoints_form'], 'breakpoints_update' ) ){ ?>
            <div class="error">
                <p>
                    <?php echo esc_html__('Sorry, your nonce was not correct. Please try again.', JLTMA_MCB_TD);?>
                </p>
            </div>
        <?php
            exit;
        } else {

            $data_updated = false;

            //CUSTOM BREAKPOINTS FILE SAVE
            $custom_breakpoints = [];

            if (is_array($_REQUEST["breakpoint_select1"]) || is_object($_REQUEST["breakpoint_select1"])){

                foreach($_REQUEST["breakpoint_select1"] as $key => $select1_value) {
                    $custom_breakpoints["breakpoint{$key}"] = [
                        'name'          => sanitize_text_field($_REQUEST["breakpoint_name"][$key]),
                        // 'select1'       => sanitize_title($select1_value),
                        'input1'        => $_REQUEST["breakpoint_input1"][$key],
                        // 'select2'       => sanitize_title($_REQUEST["breakpoint_select2"][$key]),
                        'input2'        => $_REQUEST["breakpoint_input2"][$key],
                        'orientation'   => sanitize_title($_REQUEST["orientation"][$key])
                    ];
                }

                if(!empty($custom_breakpoints))
                    // $data_updated = file_put_contents( JLTMA_MCB_PLUGIN_PATH . '/custom_breakpoints.json', json_encode($custom_breakpoints));
                    update_option( 'jltma_mcb', $custom_breakpoints );
                    $data_updated = get_option( 'jltma_mcb', $custom_breakpoints );                    

                //CUSTOM BREAKPOINTS SAVE END
                if($data_updated) {

                    echo "
                        <div class='updated'>
                            <p>Saved Breakpoints</p>
                        </div>
                        <script>
                            jQuery(document).ready(function() {
                               setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            });
                        </script>
                    ";

                } else {
                    echo "<div class='error'>
                            <p>Custom Breakpoints cannot be updated</p>
                        </div>";
                }
            }
        }

    }

    // Reset Settings
    public function jltma_mcb_reset_settings() {

        if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'breakpoints_reset' ) ) {
            wp_send_json_error(  esc_html__( 'Security Error.', JLTMA_MCB_TD ) );
        }

        delete_option( 'jltma_mcb' );
        echo json_encode('ok');
        die();

    }

    // Export & Download Settins Files
    public function jltma_mcb_export_settings() {

        if( ! current_user_can( 'manage_options' ) )
            return;

        $settings = get_option( 'jltma_mcb' );

        ignore_user_abort( true );

        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=master-custom-breakpoints-export-' . date( 'm-d-Y' ) . '.json' );
        header( "Expires: 0" );

        echo json_encode( $settings );
        exit;

    }

    // Import Files
    public function jltma_cbp_import_elementor_settings(){

        $extension = end( explode( '.', $_FILES['jltma_mcb']['name'] ) );

        if( $extension != 'json' ) {
            wp_die( __( 'Please upload a valid .json file' ) );
        }

        $import_file = $_FILES['jltma_mcb']['tmp_name'];

        if( empty( $import_file ) ) {
            wp_die( __( 'Please upload a file to import' ) );
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $settings = (array) json_decode( file_get_contents( $import_file ) );

        update_option( 'jltma_mcb', $settings );

        echo json_encode('ok');
        die();
    }

    public function jltma_mcb__register_required_plugins() {
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */

        $plugins = [];

        if ( function_exists( 'ma_el_fs' ) ) {
            if( ma_el_fs()->can_use_premium_code()) {
                
                $plugins = array(
                    array(
                            'name'               => esc_html__( 'Master Addons Pro', 'master-custom-breakpoint' ),
                            'slug'               => 'master-addons-pro',
                            'required'           => false,
                            'force_activation'   => false,
                        ),
                    array(
                            'name'               => esc_html__( 'Elementor', 'master-custom-breakpoint' ),
                            'slug'               => 'elementor',
                            'required'           => false,
                            'force_activation'   => false,
                        )
                    );

            }
        }else{

            $plugins = array(
                array(
                        'name'               => esc_html__( 'Master Addons', 'master-custom-breakpoint' ),
                        'slug'               => 'master-addons',
                        'required'           => false,
                        'force_activation'   => false,
                    ),
                array(
                        'name'               => esc_html__( 'Elementor', 'master-custom-breakpoint' ),
                        'slug'               => 'elementor',
                        'required'           => false,
                        'force_activation'   => false,
                    )
                );                
        }




        /*
         * Array of configuration settings. Amend each line as needed.
         *
         * TGMPA will start providing localized text strings soon. If you already have translations of our standard
         * strings available, please help us make TGMPA even better by giving us access to these translations or by
         * sending in a pull-request with .po file(s) with the translations.
         *
         * Only uncomment the strings in the config array if you want to customize the strings.
         */
        $config = array(
            'id'           => 'master-custom-breakpoint',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'plugins.php',            // Parent menu slug.
            'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.

            
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'master-custom-breakpoint' ),
                'menu_title'                      => __( 'Install Plugins', 'master-custom-breakpoint' ),
                'installing'                      => __( 'Installing Plugin: %s', 'master-custom-breakpoint' ),
                'updating'                        => __( 'Updating Plugin: %s', 'master-custom-breakpoint' ),
                'oops'                            => __( 'Something went wrong with the plugin API.', 'master-custom-breakpoint' ),
                'notice_can_install_required'     => _n_noop(
                    'This theme requires the following plugin: %1$s.',
                    'This theme requires the following plugins: %1$s.',
                    'master-custom-breakpoint'
                ),
                'notice_can_install_recommended'  => _n_noop(
                    'This theme recommends the following plugin: %1$s.',
                    'This theme recommends the following plugins: %1$s.',
                    'master-custom-breakpoint'
                ),
                'notice_ask_to_update'            => _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'master-custom-breakpoint'
                ),
                'notice_ask_to_update_maybe'      => _n_noop(
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'master-custom-breakpoint'
                ),
                'notice_can_activate_required'    => _n_noop(
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'master-custom-breakpoint'
                ),
                'notice_can_activate_recommended' => _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'master-custom-breakpoint'
                ),
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'master-custom-breakpoint'
                ),
                'update_link'                     => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'master-custom-breakpoint'
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'master-custom-breakpoint'
                ),
                'return'                          => __( 'Return to Required Plugins Installer', 'master-custom-breakpoint' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'master-custom-breakpoint' ),
                'activated_successfully'          => __( 'The following plugin was activated successfully:', 'master-custom-breakpoint' ),
                'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'master-custom-breakpoint' ),
                'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'master-custom-breakpoint' ),
                'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'master-custom-breakpoint' ),
                'dismiss'                         => __( 'Dismiss this notice', 'master-custom-breakpoint' ),
                'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'master-custom-breakpoint' ),
                'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'master-custom-breakpoint' ),

                'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
            ),
            
        );

        tgmpa( $plugins, $config );
    }

    public function hooks_scripts( $hook ) {

        if ( 'master-addons_page_master-custom-breakpoints' != $hook ) return;

        wp_register_script( 'jltma-vue-js', JLTMA_MCB_PLUGIN_URL . 'assets/js/vue.js', [], JLTMA_MCB_VERSION, true );

        wp_register_script( 'jltma-hooks-js', JLTMA_MCB_PLUGIN_URL . 'assets/js/hooks.js', ['jltma-vue-js', 'jquery'], JLTMA_MCB_VERSION, true );

        $breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $breakpoints = array_map( function( $breakpoint ) {
            return [
                'name' => $breakpoint->get_label(),
                'key' => $breakpoint->get_name(),
                'max' => $breakpoint->get_default_value()
            ];
        }, $breakpoints );

        $default_breakpoints = array_keys(Plugin::$instance->breakpoints->get_default_config());

        $data = [
            'breakpoints' => array_values( $breakpoints ),
            'default_devices' => $default_breakpoints,
            'devices' => [],
            'is_pro' => wp_validate_boolean( function_exists( 'ma_el_fs' ) && ma_el_fs()->can_use_premium_code() )
        ];

        wp_localize_script( 'jltma-hooks-js', 'jltma_custom_bp_data', $data );

        wp_enqueue_script( 'jltma-hooks-js' );

    }

}

new JLTMA_Master_Custom_Breakpoint_Hooks();