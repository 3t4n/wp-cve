<?php
if( ! class_exists( 'BIR_admin_setting' ) ) {
    class BIR_admin_setting{

        public function __construct() {
            add_action( 'admin_menu', array($this,'broken_image_domain_options_page') );
            add_action( 'admin_init', array($this,'broken_image_domain_settings_init') );
            add_action( 'admin_enqueue_scripts', array($this,'wpdocs_selectively_enqueue_admin_script' ));
            add_action('update_option_broken_img_options',array($this,'onChangeSettingValue'),100,100);
        }
        public function broken_image_domain_settings_init() {
            register_setting( 'broken_img', 'broken_img_options');
            add_settings_section(
                'broken_img_section_developers',
                __( '', 'broken-image-domain' ),
                array(),
                'broken_image_domain'
            );
            add_settings_field(
                'broken_img_status',
                __( 'Plugin Status:', 'broken-image-domain' ),
                array($this,'broken_image_domain_field_type_checkbox'),
                'broken_image_domain',
                'broken_img_section_developers',
                array(
                    'label_for' => 'broken_img_status',
                    'class' => 'broken_img_row',
                    'broken_img_custom_data' => 'custom',
                    'textOn' =>'ON',
                    'textOff' =>'OFF',
                )
            );
            add_settings_field(
                'broken_img_upload',
                __( 'image replace:', 'broken-image-domain' ),
                array($this,'broken_image_domain_field_type_upload'),
                'broken_image_domain',
                'broken_img_section_developers',
                array(
                    'label_for' => 'broken_img_upload',
                    'class' => 'broken_img_upload_row',
                    'broken_img_custom_data' => ''
                )
            );




        }
        public function broken_image_domain_field_type_checkbox( $args ) {
            $options = get_option( 'broken_img_options' );

            ?>
            <span class="on_off off"><?php esc_html_e( $args['textOff'], 'broken-image-domain' ); ?></span>
            <label class="switch">
                <input type="checkbox" value="1" id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['broken_img_custom_data'] ); ?>" name="broken_img_options[<?php echo esc_attr( $args['label_for'] ); ?>]"  <?php echo isset( $options[ $args['label_for']] ) ? ( checked( $options[ $args['label_for'] ], '1', false ) ) : ( '' ); ?>>
                <span class="slider round"></span>
            </label>
            <span class="on_off on"><?php esc_html_e( $args['textOn'], 'broken-image-domain' ); ?></span>
            <p class="description">
                <?php esc_html_e( 'Using this option, you can enable or disable the plugin functionality', 'broken-image-domain' ); ?>
            </p>

            <?php
        }

        public function broken_image_domain_field_type_upload( $args ) {
            $options = get_option( 'broken_img_options' );

            $image_id = isset($options[ $args['label_for']] )?absint($options[ $args['label_for']] ):'';
            if( wp_get_attachment_image_src($image_id) &&  $image_id != '') {
                $image = wp_get_attachment_image_src($image_id);
                ?><a href="#" class="broken_image_upload_link"><img src="<?php echo esc_url($image[0]);?>" style="max-width: 100px;"/></a>
                <a href="#" class="broken_image_remove_link">Remove image</a>
                <input type="hidden" class="broken_image_hidden" name="broken_img_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php esc_attr_e($image_id);?>">
                <?php
            }else{
                ?>
                <a href="#" class="broken_image_upload_link"><span>Upload image</span></a>
                <a href="#" class="broken_image_remove_link" style="display:none">Remove image</a>
                <input type="hidden" class="broken_image_hidden" name="broken_img_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="" >
                <?php
            }
            ?>
            <style>
                a.broken_image_upload_link>span {
                    text-decoration: none;
                    background: #2271b1;
                    border-radius: 5px;
                    color: #fff;
                    padding: 7px 20px;
                }

                a.broken_image_remove_link {
                    text-decoration: none;
                    background: #bd0808;
                    border-radius: 5px;
                    color: #fff;
                    padding: 7px 20px;
                }
            </style>
            <?php
        }



        public function broken_image_domain_options_page() {
            add_menu_page(
                __('broken image','broken-image-domain'),
                __('broken image','broken-image-domain'),
                'administrator',
                'broken_image_domain',
                array($this,'broken_image_domain_options_page_html'),
                'dashicons-unlock',
                6
            );


        }


        public function broken_image_domain_options_page_html() {

            if ( ! current_user_can( 'administrator' ) ) {
                return;
            }

            if ( isset( $_GET['settings-updated'] ) ) {
                add_settings_error( 'broken_image_domain_messages', 'broken_image_domain_message', __( 'Settings Saved', 'broken-image-domain' ), 'updated' );
            }
            settings_errors( 'broken_image_domain_messages' );

            ?>
                <style>
                    #wpbody-content .metabox-holder {
                        padding-top: 0px !important;
                    }
                    h2.h2_broken_tabs_header {
                        margin: 45px 16px 16px 7px;
                    }

                    a.broken_tabs_header {
                       
                        padding: 14px;
                        border: 1px solid #f0f0f1;
                        text-decoration: none;
                        text-transform: capitalize;
                    }
                </style>
				<h1>404 Image Redirection (Replace Broken Images)</h1><br />
            <h2 class="h2_broken_tabs_header"><a href="<?php echo esc_url(admin_url('admin.php?page=broken_image_domain'));?>"  class="broken_tabs_header" style="background-color:#ffffff;"><?php _e( 'General Options', 'broken-image-domain' );?> </a><a class="broken_tabs_header"  href="<?php echo esc_url(admin_url('admin.php?page=broken_image_change'));?>"> <?php _e( 'Custom Redirection', 'broken-image-domain' );?></a></h2>
            <?php
            if(isset($_GET['error']) && $_GET['error'] == 2){
                ?>
                <div class="updated notice">
                    <p><?php _e( 'Great! your settings are now verified & saved successfully', 'broken-image-domain' );?></p>
                </div>
                <?php
            }?>
            <div id="">
                <div id="dashboard-widgets" class="metabox-holder">
                    <div id="" class="">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div id="dashboard_quick_press" class="postbox" style="border:none;">
                                <h2 class="hndle ui-sortable-handle">
									<span>
										
									</span>
                                </h2>
                                <div class="inside">

                                    <form action="options.php" method="post">

                                        <div class="input-text-wrap broken_img" id="title-wrap">
                                            <?php
                                            settings_fields( 'broken_img' );
                                            do_settings_sections( 'broken_image_domain' );
                                            ?>
                                        </div>
                                        <p class="submit">
                                            <?php
                                            submit_button( 'Save Settings' );
                                            ?>
                                            <br class="clear">
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- The Modal -->



            <?php
        }
        function wpdocs_selectively_enqueue_admin_script( $hook ) {
            if (strpos($hook, 'broken_image_domain') !== false) {
                if (!did_action('wp_enqueue_media')) {
                    wp_enqueue_media();
                }
                wp_enqueue_script( 'broken_image_upload_img_js', broken_image_PLUGIN_URL . '/assets/js/custom.js', array('jquery'), '1.0' );
            }

        }

        function onChangeSettingValue($option,$value){

            $setCode = new BIR_setCode();
            if(!isset($value['broken_img_status'])||empty($value['broken_img_status'])||!isset($value['broken_img_upload'])||empty($value['broken_img_upload'])){

                return $setCode->clear_htaccess(broken_image_val_update_htaccess);
            }
            return $setCode->modify_htaccess_def_image($value['broken_img_upload']);
        }

    }

    new BIR_admin_setting();
}
if ( ! function_exists( 'BIR_hkdc_admin_styles' ) ) {
    function BIR_hkdc_admin_styles($page)
    {
        if (isset($_GET['page']) && $_GET['page'] == 'broken_image_domain') {
            wp_enqueue_style('broken-admin-css', broken_image_PLUGIN_URL . '/assets/css/admin-css.css?ver=5');
        }
    }

    add_action('admin_print_styles', 'BIR_hkdc_admin_styles');
}


