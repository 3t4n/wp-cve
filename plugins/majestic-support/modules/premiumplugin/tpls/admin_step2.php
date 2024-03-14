<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$allPlugins = get_plugins(); // associative array of all installed plugins

$addon_array = array();
foreach ($allPlugins as $key => $value) {
    $addon_index = MJTC_majesticsupportphplib::MJTC_explode('/', $key);
    $addon_array[] = $addon_index[0];
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
                         <div class="ms-addon-installer-wrapper-addon-card">
                            <div class="ms-addon-installer-wrapper no_bg ms-addon-installer-wrapper-overall-wrapper" >
                            <form id="mjsupportfrom" action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=majesticsupport_premiumplugin&task=downloadandinstalladdons&action=mstask'),"download-and-install-addons")); ?>" method="post">
                                <div class="ms-addon-installer-right-section-wrap step2 ms-addon-installer-right-section-wrap-step2-no_bg">
                                    <div class="ms-addon-installer-right-heading" style = "display:none;">
                                        <?php echo esc_html(__("Majestic Support Addon Installer",'majestic-support')); ?>
                                    </div>
                                    <div class="ms-addon-installer-right-addon-wrapper" >
                                        <?php
                                        if(isset($_COOKIE['ms_addon_install_data'])){
                                            $ms_addon_install_data = MJTC_majesticsupportphplib::MJTC_safe_decoding(majesticsupport::MJTC_sanitizeData($_COOKIE['ms_addon_install_data']));// MJTC_sanitizeData() function uses wordpress santize functions
                                            $ms_addon_install_data = json_decode( $ms_addon_install_data , true);
                                        }else{
                                            $ms_addon_install_data = json_decode(get_option('ms_addon_install_data'), true);
                                        }
                                        $error_message = '';
                                        if($ms_addon_install_data){
                                            $result = $ms_addon_install_data;
                                            if(isset($result['status']) && $result['status'] == 1){?>
                                                <div class="ms-addon-installer-right-addon-section" >
                                                    <div class="ms-addon-installer-right-addon-section-select_all_div">
                                                <label for="ms-addon-installer-right-addon-checkall-checkbox"><input type="checkbox" class="ms-addon-installer-right-addon-checkall-checkbox" id="ms-addon-installer-right-addon-checkall-checkbox"><?php echo esc_html(__("Select All Addons",'majestic-support')); ?></label>
                                                </div>
                                                    <?php
                                                    if(!empty($result['data'])){
                                                        $addon_availble_count = 0;
                                                        foreach ($result['data'] as $key => $value) {
                                                            if(!in_array($key, $addon_array)){
                                                                $addon_availble_count++;
                                                                $addon_slug_array = MJTC_majesticsupportphplib::MJTC_explode('-', $key);
                                                                $addon_image_name = $addon_slug_array[count($addon_slug_array) - 1];
                                                                $addon_slug = MJTC_majesticsupportphplib::MJTC_str_replace('-', '', $key);

                                                                $addon_img_path = '';
                                                                $addon_img_path = MJTC_PLUGIN_URL.'includes/images/addon-images/addons/';
                                                                if($value['status'] == 1){ ?>
                                                                    <div class="ms-addon-installer-right-addon-single" >
                                                                        <img class="ms-addon-installer-right-addon-image" data-addon-name="<?php echo esc_attr($key); ?>" src="<?php echo esc_url($addon_img_path.$addon_image_name.'.png');?>" />
                                                                        <div class="ms-addon-installer-right-addon-name">
                                                                        <input type="checkbox" class="ms-addon-installer-right-addon-single-checkbox" id="addon-<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="1">
                                                                            <?php echo esc_html($value['title']);?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        if($addon_availble_count == 0){ // all allowed addon are already installed
                                                            $error_message = esc_html(__('All allowed add ons are already installed','majestic-support')).'.';
                                                        }
                                                    }else{ // no addon returend
                                                        $error_message = esc_html(__('You are not allowed to install any add on','majestic-support')).'.';
                                                    }
                                                    if($error_message != ''){
                                                        $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");

                                                        $data = '<div class="ms-addon-go-back-messsage-wrap">';
                                                        $data .= '<h1>';
                                                        $data .= wp_kses_post($error_message);
                                                        $data .= '</h1>';

                                                        $data .= '<a class="ms-addon-go-back-link" href="'.esc_url($url).'">';
                                                        $data .= esc_html(__('Back','majestic-support'));
                                                        $data .= '</a>';
                                                        $data .= '</div>';
                                                        echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                                    }
                                                     ?>
                                                      <div class="ms-addon-installer-right-addon-section-select_all_div">
                                                <label for="ms-addon-installer-right-addon-checkall-checkbox"><input type="checkbox" class="ms-addon-installer-right-addon-checkall-checkbox" id="ms-addon-installer-right-addon-checkall-checkbox"><?php echo esc_html(__("Select All Addons",'majestic-support')); ?></label>
                                                </div>
                                                </div>
                                                <?php if($error_message == ''){ ?>
                                                    <div class="ms-addon-installer-right-addon-bottom" >
                                                        <div class="hr"></div>
                                                    </div>
                                                    <div class="ms-addon-installer-right-button" >
                                            <button type="submit" class="ms_btn" role="submit" onclick="jsShowLoading();"><?php echo esc_html(__("Proceed",'majestic-support')); ?></button>
                                        </div>
                                                <?php
                                                }
                                            }
                                        }else{
                                            $error_message = esc_html(__('Something went wrong','majestic-support')).'!';
                                            $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1"); ?>
                                            <div class="ms-addon-installer-wrapper" >
                                                <div class="ms-addon-installer-right-section-wrap ms-addon-installer-right-section-wrap_something_wrong">
                                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/addon-images/main-logo.png" />
                                                    <div class="ms-addon-installer-right-heading ms-addon-installer-right-heading_something_wrong">
                                                        <?php echo wp_kses_post($error_message); ?>
                                                    </div>
                                                    <div class="ms-addon-installer-right-key-section" >
                                                        <div class="ms-addon-installer-right-key-field" >
                                                            <div class="ms-addon-installer-right-key-button2 ms-addon-go-back-messsage-wrap" >
                                                                <a class="ms-addon-go-back-link" href="<?php echo esc_url($url); ?>" >
                                                                    <?php echo esc_html(__('Back','majestic-support')); ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        if($error_message == ''){ ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <input type="hidden" name="token" value="<?php echo esc_attr(isset($result['token']) ? $result['token'] : ''); ?>"/>
                                </form>
                                </div>
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

        jQuery('.ms-addon-installer-right-addon-image').on('click', function() {
            var addon_name = jQuery(this).attr('data-addon-name')
            var prop_checked = jQuery('#addon-'+addon_name).prop('checked');
            if(prop_checked){
                jQuery('#addon-'+addon_name).prop('checked', false);
            }else{
                jQuery('#addon-'+addon_name).prop('checked', true);
            }
        });
        // to handle select all check box.
        jQuery('.ms-addon-installer-right-addon-checkall-checkbox').change(function() {
           jQuery('.ms-addon-installer-right-addon-single-checkbox').prop('checked', this.checked);
       });


    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#mstran_loading').show();
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<?php
if(isset($_SESSION['ms_addon_install_data'])){// to avoid to show data on refresh
    unset($_SESSION['ms_addon_install_data']);
}
delete_option('ms_addon_install_data');

?>
