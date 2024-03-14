<?php
if( ! class_exists( 'WPLFLA_statistics' ) ) {
    class WPLFLA_statistics
    {
        public $dashboard_class = '';
        public function __construct()
        {
            add_action('add_meta_boxes_login-attempts_page_WPLFLASTATISTICS',array($this,'wptuts_add_my_meta_box'));

            add_action('admin_menu', array($this, 'WPLFLA_options_page'));
            add_action('admin_enqueue_scripts', array($this, 'my_enqueue'));
            $this->dashboard_class = new dashboard_widget_PRO();
        }

        public function WPLFLA_options_page()
        {
            add_menu_page(
                'WPLFLA',
                __('Login Attempts','codepressFailed_pro'),
                'manage_options',
                'WPLFLA',
                array($this,'WPLFLA_statistics')
            );
            add_submenu_page( 'WPLFLA', __('Statistics','codepressFailed_pro'), __('Statistics','codepressFailed_pro'),'manage_options', 'WPLFLA');

            add_submenu_page('WPLFLA', '', __('Options', 'codepressFailed_pro'), 'manage_options', 'WPLFLASettings', array('WPLFLA_admin_setting_PRO', "WPLFLA_options_page_html"));
        }

        function WPLFLA_statistics()
        {
            ?>
            <div class="wrap">

                <h2><?php esc_html_e('Failed login attempts','domain'); ?></h2>


                <div id="dashboard-widgets-wrap">

                    <div id="dashboard-widgets" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">

                        <div id="post-body-content">
                            <div id="normal-sortables" style="margin-bottom: 0px;" class="meta-box-sortables ui-sortable">
                                <?php $this->map();?>
                            </div>
                        </div>

                        <div id="postbox-container-1" class="postbox-container left_grid">
                            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                                <?php $this->Top_Failed_Logins_box();?>
                                <?php $this->Top_IPs_Blocked();?>
                            </div>
                        </div>

                        <div id="postbox-container-2" class="postbox-container">
                            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                                <?php $this->Top_Countries_Blocked();?>
                                <?php $this->Recently_Blocked_Attacks();?>
                            </div>
                        </div>

                    </div> <!-- #post-body -->

                </div> <!-- #poststuff -->

               

            </div><!-- .wrap -->
            <?php
        }
        public function map() {
            ?>

            <div id="Top_Failed_Logins" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle">Geo Statistics</h2>
                </div>
                <div class="inside">
                    <div id="poststuff">
                        <div id="post-body" class="map_int metabox-holder columns-2">
                            <div id="post-body-content" style="position: relative;">
                                <div id="svgMapGPD">
                                    <a target="_blank" href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/">
                                    <img class="failed_login_rep" src="<?php echo esc_url(WPLFLA_PLUGIN_URL.'/assets/images/map-pro.jpg');?>" style="width: 100%">
                                    </a>
                                </div>
                            </div>
                            <div id="postbox-container-1" class="postbox-container map_con" style="">
                               <div class="country_list" style="font-size:12px !important;">
                                   <a target="_blank" href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/">
                                   <img class="failed_login_rep" src="<?php echo esc_url(WPLFLA_PLUGIN_URL.'/assets/images/map-legend-pro.jpg');?>" style="width: 100%">
                                   </a>
								</div></div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>




                </div>
            </div>
            <?php
        }
        public function Top_Failed_Logins_box() {
            ?>
            <div id="Top_Failed_Logins" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle">Top 10 Failed Logins</h2>
                </div>
                <div class="inside">
                    <?php

                    $this->dashboard_class->Top_Failed_Logins();

                    ?>
                </div>
            </div>
            <?php
        }
        public function Top_IPs_Blocked() {
            ?>
            <div id="Top_IPs_Blocked" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle">Top 10 Blocked IPs</h2>
                </div>
                <div class="inside">
                    <?php
                    $this->dashboard_class->Top_IPs_Blocked();

                    ?>
                </div>
            </div>
            <?php
        }
        public function Top_Countries_Blocked() {
            ?>
            <div id="Top_Countries_Blocked" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle">Top 10 Blocked Countries</h2>
                </div>
                <div class="inside">
                    <?php
                    $this->dashboard_class->Top_Countries_Blocked();

                    ?>
                </div>
            </div>
            <?php
        }
        public function Recently_Blocked_Attacks() {
            ?>
            <div id="Recently_Blocked_Attacks" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle">Recently Blocked Attacks</h2>
                </div>
                <div class="inside">
                    <?php
                    $this->dashboard_class->Recently_Blocked_Attacks();

                    ?>
                </div>
            </div>
            <?php
        }
        function my_enqueue($hook)
        {
			
			if (strpos($hook, '_WPLFLA') !== false) {
			

            wp_enqueue_script('datatables_log', plugin_dir_url(__FILE__) . '../assets/js/datatables.min.js', array('jquery'));
            wp_enqueue_script('select2', plugin_dir_url(__FILE__) . '../assets/js/select2.min.js', array('jquery'));
            wp_localize_script('datatables_log', 'datatablesajax_log', array('url' => admin_url('admin-ajax.php')));
            wp_enqueue_script('datatables_log_responsive', plugin_dir_url(__FILE__) . '../assets/js/dataTables.responsive.min.js', array('jquery'));
            
            wp_enqueue_style('datatables', plugin_dir_url(__FILE__) . '../assets/css/datatables.min.css');
            wp_enqueue_style('responsive_dataTables', plugin_dir_url(__FILE__) . '../assets/css/responsive.dataTables.min.css');
            wp_enqueue_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
            wp_enqueue_style('failed_admin-pro-css', plugin_dir_url(__FILE__)  . '../assets/css/admin-css.css?re=1.1.1');
            wp_enqueue_style('select2-min-css', plugin_dir_url(__FILE__) . '../assets/css/select2.min.css');
            
			}

            //wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/myscript.js');
        }

    }
    new WPLFLA_statistics();
}