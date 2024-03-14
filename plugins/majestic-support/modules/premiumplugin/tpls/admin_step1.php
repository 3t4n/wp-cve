<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(isset($_SESSION['ms_addon_install_data'])){
    unset($_SESSION['ms_addon_install_data']);
}
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
                            <form id="mjsupportfrom" action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=majesticsupport_premiumplugin&task=verifytransactionkey&action=mstask'),"verify-transaction-key")); ?>" method="post">
                                <div class="ms-addon-installer-right-section-wrap" >
                                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/addon-images/main-logo.png" />
                                    <div class="ms-addon-installer-right-heading" >
                                        <?php echo esc_html(__("Please Insert Your Activation Key",'majestic-support')); ?>
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
                                             <?php if($error_message != '' ){ ?>
                                                <div class="ms-addon-installer-right-key-field-message" > <?php echo wp_kses_post($error_message);?></div>
                                            <?php } ?>
                                            <input type="text" name="transactionkey" id="transactionkey" class="ms_key_field" value="<?php echo esc_attr($transactionkey);?>" placeholder="<?php echo esc_html(__('XXXX-XXXX-XXXXX-XXXXX','majestic-support')); ?>"/>
                
                                        
                                        <div class="ms-addon-installer-right-key-button" >
                                            <button type="submit" class="ms_btn" role="submit" onclick="jsShowLoading();"><?php echo esc_html(__("Proceed",'majestic-support')); ?></button>
                                        </div>
                                       
                                            </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$majesticsupport_js ="
    jQuery(document).ready(function(){
        jQuery('#mjsupportfrom').on('submit', function() {
            jsShowLoading();
        });
    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#mstran_loading').show();
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
