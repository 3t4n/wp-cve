<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('step1'); ?>
        <div id="msadmin-data-wrp">
            <div id="majesticsupport-content">
                <div id="black_wrapper_translation"></div>
                <div id="mstran_loading">
                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
                </div>
                <div id="ms-lower-wrapper">
                    <div class="ms-addon-installer-wrapper" >
                        <div class="ms-addon-installer-right-section-wrap ms-addon-installer-right-section-wrap_step3">
                           <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/addon-images/main-logo.png" />
                                   <div class="ms-addon-installer-right-heading ms-addon-installer-right-heading_step3">
                                        <?php echo esc_html(__("Addons Installed Successfully",'majestic-support')); ?>
                                    </div>
                                    <div class="ms-addon-installer-right-key-section" >
                                        <?php
                                        $error_message = '';
                                        $transactionkey = '';
                                        if(isset($_COOKIE['ms_addon_return_data'])){
                                            $ms_addon_return_data = json_decode(MJTC_majesticsupportphplib::MJTC_safe_decoding(majesticsupport::MJTC_sanitizeData($_COOKIE['ms_addon_return_data'])),true);// MJTC_sanitizeData() function uses wordpress santize functions
                                            $ms_error_msg = $ms_addon_return_data;
                                            if(isset($ms_addon_return_data['status']) && $ms_addon_return_data['status'] == 0){
                                                $error_message = $ms_addon_return_data['message'];
                                                $transactionkey = $ms_addon_return_data['transactionkey'];
                                            }
                                            unset($ms_addon_return_data);
                                        }
                                        ?>
                                        <div class="ms-addon-installer-right-key-field" >
                                            <div class="ms-addon-installer-right-button ms-addon-installer-right-button_step3" >
                                            <a href="?page=majesticsupport" class="ms_btn ms-addon-installer-right-button_step3_dashboard_btn"><?php echo esc_html(__("Open Dashboard",'majestic-support')); ?></a>
                                            <a href="<?php echo esc_url(admin_url('plugins.php')) ?>" class="ms_btn ms-addon-installer-right-button_step3_plugins_btn"><?php echo esc_html(__("Open Plugins Page",'majestic-support')); ?></a>
                                        </div>
                                        <?php if($error_message != '' ){ ?>
                                                <div class="ms-addon-installer-right-key-field-message" > <?php echo wp_kses_post($error_message);?></div>
                                            <?php } ?>
                                            </div>
                                    </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
