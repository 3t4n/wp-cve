<?php
if (!defined('ABSPATH')) die('Restricted Access');
$ticketidsequence = array(
    (object) array('id' => '0', 'text' => esc_html(__('Random', 'majestic-support'))),
    (object) array('id' => '1', 'text' => esc_html(__('Sequential', 'majestic-support')))
    );
$type = array(
    (object) array('id' => '0', 'text' => esc_html(__('Days', 'majestic-support'))),
    (object) array('id' => '1', 'text' => esc_html(__('Hours', 'majestic-support')))
    );

?>
<div id="mjtc-spt-admin-wrapper">
    <div id="mjtc-spt-cparea">
        <div id="ms-main-wrapper" class="post-installation">
            <div class="mjtc-admin-title-installtion">
                <span class="ms_heading"><?php echo esc_html(__('Feedback Settings','majestic-support')); ?></span>
                <div class="ms-config-topheading">
                        <!-- <?php
                            if($tran_opt && in_array('feedback', majesticsupport::$_active_addons)){
                                $step = '5';
                            }else if(!$tran_opt && !in_array('feedback', majesticsupport::$_active_addons)){
                                $step = '3';
                            }else{
                                $step = '4';
                            }
                            $steps = esc_html(__('Step 3 of ','majestic-support'));
                            $steps .= $step;
                        ?> -->
                        <span class="heading-post-ins ms-config-steps"><?php echo esc_html($steps); ?></span>
                    </div>
                
                <div class="close-button-bottom">
                    <a href="#" class="close-button">
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
                        <?php if(MJTC_includer::MJTC_getModel('majesticsupport')->getInstalledTranslationKey()){ ?>
                            <li class="header-parts third-part active">
                               <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=translationoption")); ?>" title="link" class="tab_icon">
                               <span class="header-parts-number header-parts-number-active">3</span>
                                    <span class="text"><?php echo esc_html(__('Translation','majestic-support')); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="header-parts third-part active">
                           <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepthree")); ?>" title="link" class="tab_icon">
                           <span class="header-parts-number">3</span>
                                <span class="text active"><?php echo esc_html(__('Feedback Settings','majestic-support')); ?></span>
                            </a>
                        </li>
                        <li class="header-parts forth-part">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=settingcomplete")); ?>" title="link" class="tab_icon">
                            <span class="header-parts-number header-parts-number-active">4</span>
                                <span class="text"><?php echo esc_html(__('Complete','majestic-support')); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="post-installtion-content_wrapper_right">
                    <div class="post-installtion-content">
                        <form id="majesticsupport-form-ins" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_postinstallation&task=save&action=mstask"),"save")); ?>">
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Feedback Email Delay Type','majestic-support')); ?><?php echo esc_html(__(':', 'majestic-support'));?>
                                </div>
                                <div class="field">
                                     <?php echo wp_kses(MJTC_formfield::MJTC_select('feedback_email_delay_type', $type , isset(majesticsupport::$_data[0]['feedback_email_delay_type']) ? majesticsupport::$_data[0]['feedback_email_delay_type'] : '', esc_html(__('Select Type', 'majestic-support')) , array('class' => 'inputbox ms-postsetting mjtc-select ms-postsetting ')), MJTC_ALLOWED_TAGS);?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__('Set Email Delay Time','majestic-support')); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Feedback Email Delay','majestic-support')); ?><?php echo esc_html(__(' :', 'majestic-support'));?>
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('feedback_email_delay', isset(majesticsupport::$_data[0]['feedback_email_delay']) ? majesticsupport::$_data[0]['feedback_email_delay'] : '', array('class' => 'inputbox ms-postsetting mjtc-select ms-postsetting', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__('Set Email Delay','majestic-support')); ?>
                                </div>
                            </div>
                             <div class="pic-button-part">
                             <a class="back" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_postinstallation&mjslay=steptwo')); ?>">
                                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/back-arrow.png';?>">
                                    <?php echo esc_html(__('Back','majestic-support')); ?>
                                </a>
                                <a class="next-step" href="#" onclick="document.getElementById('majesticsupport-form-ins').submit();" >
                                    <?php echo esc_html(__('Next','majestic-support')); ?>
                                     <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/next-arrow.png';?>">
                                </a>
                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'postinstallation_save'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('step', 3), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
