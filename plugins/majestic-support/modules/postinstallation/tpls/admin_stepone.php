<?php
if (!defined('ABSPATH')) die('Restricted Access');
$yesno = array(
    (object) array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('No', 'majestic-support')))
    );
$showhide = array(
    (object) array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))),
    (object) array('id' => '0', 'text' => esc_html(__('No', 'majestic-support')))
    );
$date_format = array(
    (object) array('id' => 'd-m-Y', 'text' => esc_html(__('DD-MM-YYYY' , 'majestic-support'))),
    (object) array('id' => 'm-d-Y', 'text' => esc_html(__('MM-DD-YYYY' , 'majestic-support'))),
    (object) array('id' => 'Y-m-d', 'text' => esc_html(__('YYYY-MM-DD' , 'majestic-support')))
    );
$tran_opt = MJTC_includer::MJTC_getModel('majesticsupport')->getInstalledTranslationKey();
?>
<div id="mjtc-spt-admin-wrapper">
    <div id="mjtc-spt-cparea">
        <div id="ms-main-wrapper" class="post-installation">
            <div class="post-installtion-content-wrapper">
                <div class="post-installtion-content-header">
                    <div class="post-installtion-content-header_logo_img_section">
            
                <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/logo.png';?>" />
                </div>
                    <ul class="update-header-img step-1">
                        <li class="header-parts first-part active">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepone")); ?>" title="link" class="tab_icon">
                                <span class="header-parts-number">1</span>
                                <span class="text active"><?php echo esc_html(__('General Settings','majestic-support')); ?></span>
                            </a>
                        </li>
                        <li class="header-parts second-part">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=steptwo")); ?>" title="link" class="tab_icon">
                            <span class="header-parts-number header-parts-number-active">2</span>
                                <span class="text"><?php echo esc_html(__('Ticket Settings','majestic-support')); ?></span>
                            </a>
                        </li>
                        <?php if(MJTC_includer::MJTC_getModel('majesticsupport')->getInstalledTranslationKey()){ ?>
                            <li class="header-parts third-part">
                               <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=translationoption")); ?>" title="link" class="tab_icon">
                               <span class="header-parts-number header-parts-number-active"></span>
                                    <span class="text"><?php echo esc_html(__('Translation','majestic-support')); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                            <li class="header-parts third-part">
                               <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepthree")); ?>" title="link" class="tab_icon">
                               <span class="header-parts-number header-parts-number-active">3</span>
                                    <span class="text"><?php echo esc_html(__('Feedback Settings','majestic-support')); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="header-parts forth-part">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=settingcomplete")); ?>" title="link" class="tab_icon">
                            <span class="header-parts-number header-parts-number-active">4</span>
                                <span class="text"><?php echo esc_html(__('Complete','majestic-support')); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="post-installtion-content_wrapper_right">
                <div class="mjtc-admin-title-installtion">
                <span class="ms_heading"><?php echo esc_html(__('General Settings','majestic-support')); ?></span>
                <div class="ms-config-topheading">
                        <?php
                            if($tran_opt && in_array('feedback', majesticsupport::$_active_addons)){
                                $step = '5';
                            }else if(!$tran_opt && !in_array('feedback', majesticsupport::$_active_addons)){
                                $step = '3';
                            }else{
                                $step = '4';
                            }
                            $steps = esc_html(__('Step 1 of ','majestic-support'));
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
                    <div class="post-installtion-content">
                        <form id="majesticsupport-form-ins" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_postinstallation&task=save&action=mstask"),"save")); ?>">
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Title','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('title', isset(majesticsupport::$_data[0]['title']) ? majesticsupport::$_data[0]['title'] : '', array('class' => 'inputbox ms-postsetting', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__("Enter the site title",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Data Directory','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('data_directory', isset(majesticsupport::$_data[0]['data_directory']) ? majesticsupport::$_data[0]['data_directory'] : '', array('class' => 'inputbox ms-postsetting', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__("You need to rename the existing data directory in the file system before changing the data directory name",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Date Format','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('date_format', $date_format , isset(majesticsupport::$_data[0]['date_format']) ? majesticsupport::$_data[0]['date_format'] : '' , esc_html(__('Select Type', 'majestic-support')) , array('class' => 'inputbox ms-postsetting mjtc-select ms-postsetting ')), MJTC_ALLOWED_TAGS)?>
                                </div>
                                <div class="desc"><?php echo esc_html(__('Date format for plugin','majestic-support'));?> </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Ticket auto close','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('ticket_auto_close', isset(majesticsupport::$_data[0]['ticket_auto_close']) ? majesticsupport::$_data[0]['ticket_auto_close'] : '', array('class' => 'inputbox ms-postsetting', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__("Ticket auto-close if user does not respond within given days",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Show Breadcrumbs','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('show_breadcrumbs', $showhide , isset(majesticsupport::$_data[0]['show_breadcrumbs']) ? majesticsupport::$_data[0]['show_breadcrumbs'] : '', '' , array('class' => 'inputbox ms-postsetting mjtc-select ms-postsetting ')), MJTC_ALLOWED_TAGS);?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__('Show navigation in breadcrumbs','majestic-support')); ?>&nbsp;
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('File maximum size','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('file_maximum_size', isset(majesticsupport::$_data[0]['file_maximum_size']) ? majesticsupport::$_data[0]['file_maximum_size'] : '', array('class' => 'inputbox ms-postsetting', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__("Upload file size in KB's",'majestic-support')); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('File Extension','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_textarea('file_extension', isset(majesticsupport::$_data[0]['file_extension']) ? majesticsupport::$_data[0]['file_extension'] : '', array('class' => 'inputbox mjtc-textarea', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__('Show navigation in breadcrumbs','majestic-support')); ?>&nbsp;
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Show count on my tickets','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_select('count_on_myticket', $yesno , isset(majesticsupport::$_data[0]['count_on_myticket']) ? majesticsupport::$_data[0]['count_on_myticket'] : '', esc_html(__('Select Type', 'majestic-support')) , array('class' => 'inputbox ms-postsetting mjtc-select ms-postsetting ')), MJTC_ALLOWED_TAGS);?>
                                </div>
                            </div>
                            <div class="pic-button-part">
                                <a class="next-step full-width" href="#" onclick="document.getElementById('majesticsupport-form-ins').submit();" >
                                    <?php echo esc_html(__('Next Setup','majestic-support')); ?>
                                     
                                </a>
                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'postinstallation_save'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('step', 1), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
