<?php
if (!defined('ABSPATH')) die('Restricted Access');
$tran_opt = MJTC_includer::MJTC_getModel('majesticsupport')->getInstalledTranslationKey(); ?>
<div id="mjtc-spt-admin-wrapper">
    <div id="mjtc-spt-cparea">
        <div id="ms-main-wrapper" class="post-installation">
            <div class="mjtc-admin-title-installtion">
                <span class="ms_heading"><?php echo esc_html(__('Settings Complete','majestic-support')); ?></span>
                <div class="ms-config-topheading">
                        <?php
                            if($tran_opt && in_array('feedback', majesticsupport::$_active_addons)){
                                $step = '5';
                            }else if(!$tran_opt && !in_array('feedback', majesticsupport::$_active_addons)){
                                $step = '3';
                            }else{
                                $step = '4';
                            }
                            $steps = esc_html(__('Step 4 of ','majestic-support'));
                            $steps .= $step;
                        ?>
                        <span class="heading-post-ins ms-config-steps"><?php echo esc_html($steps); ?></span>
                    </div>
                <div class="close-button-bottom">
                    <a href="?page=majesticsupport" class="close-button">
                        <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/close-icon.png';?>" />
                    </a>
                </div>
            </div>
            <div class="post-installtion-content-wrapper">
                <div class="post-installtion-content-header">
                <div class="post-installtion-content-header_logo_img_section">
            
            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/logo.png';?>" />
            </div>
                    <ul class="update-header-img step-1">
                        <li class="header-parts first-part active">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepone")); ?>" title="link" class="tab_icon">
                            <span class="header-parts-number header-parts-number-active">1</span>
                                <span class="text"><?php echo esc_html(__('General Settings','majestic-support')); ?></span>
                            </a>
                        </li>
                        <li class="header-parts second-part active">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=steptwo")); ?>" title="link" class="tab_icon">
                            <span class="header-parts-number header-parts-number-active">2</span>
                                <span class="text"><?php echo esc_html(__('Ticket Settings','majestic-support')); ?></span>
                            </a>
                        </li>
                        <li class="header-parts third-part active">
                           <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepthree")); ?>" title="link" class="tab_icon">
                           <span class="header-parts-number header-parts-number-active">3</span>
                                <span class="text"><?php echo esc_html(__('Ticket Setting','majestic-support')); ?></span>
                            </a>
                        </li>
                        <li class="header-parts forth-part active">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=settingcomplete")); ?>" title="link" class="tab_icon">
                            <span class="header-parts-number">4</span>
                                <span class="text active"><?php echo esc_html(__('Complete','majestic-support')); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="post-installtion-content_wrapper_right">
                    <div class="post-installtion-content">
                        <form id="jslearnmanager-form-ins" method="post" action="#">
                            <div class="ms_img_wrp">
                                <img  src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/complete-icon.png';?>" alt="Seting Log" title="Setting Logo">
                            </div>
                            <div class="ms_text_below_img">
                                <?php echo esc_html(__('Setting you applied has been saved successfully.','majestic-support'));?>
                            </div>
                            <div class="pic-button-part">
                                <a class="next-step finish full-width" href="?page=majesticsupport">
                                    <?php echo esc_html(__('Finish','majestic-support')); ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
