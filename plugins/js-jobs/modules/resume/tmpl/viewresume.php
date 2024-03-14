<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
    $printResume = JSJOBSrequest::getVar('jsjobslt');
    if ($printResume == 'printresume')
        wp_head();
    $msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    if(! is_admin()){
        JSJOBSbreadcrumbs::getBreadcrumbs();
        include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');        
    }
if (jsjobs::$_error_flag == null) {
    $resumeviewlayout = JSJOBSincluder::getObjectClass('resumeviewlayout');
    ?>
    <div id="jsjobs-wrapper">
        <div id="full_background" style="display:none;"></div>
        <?php if (isset(jsjobs::$_data['coverletter']) && !empty(jsjobs::$_data['coverletter'])) { ?>
            <div id="popup-main-outer" class="coverletter" style="display:none;">
                <div id="popup-main" class="coverletter" style="display:none;">
                    <span class="popup-top">
                        <span id="popup_title"><?php echo esc_html(jsjobs::$_data[0]['personal_section']->first_name) . '&nbsp' . esc_html(jsjobs::$_data[0]['personal_section']->middle_name) . '&nbsp' . esc_html(jsjobs::$_data[0]['personal_section']->last_name); ?></span>
                        <img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png" />
                    </span>
                    <div class="js-field-wrapper js-row no-margin" id="popup-bottom-part">
                        <span id="popup_coverletter_title"><?php echo esc_html(jsjobs::$_data['coverletter']->ctitle); ?></span>
                        <span id="popup_coverletter_desc"><?php echo wp_kses(jsjobs::$_data['coverletter']->cdescription, JSJOBS_ALLOWED_TAGS); ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div id="popup-main-outer" class="sendmessage" style="display:none;">
            <div id="popup-main" class="sendmessage" style="display:none;">
                <span class="popup-top">
                    <span id="popup_title"><?php echo esc_html(jsjobs::$_data[0]['personal_section']->first_name) . '&nbsp' . esc_html(jsjobs::$_data[0]['personal_section']->middle_name) . '&nbsp' . esc_html(jsjobs::$_data[0]['personal_section']->last_name); ?></span>
                    <img id="popup_cross" alt="popup cross" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/popup-close.png" />
                </span>
                <div class="js-field-wrapper js-row no-margin" id="popup-bottom-part">
                    <span id="popup_coverletter_title">
                        <?php echo wp_kses(JSJOBSformfield::text('subject', '', array('class' => 'inputbox', 'placeholder' => __('Message Subject', 'js-jobs'))), JSJOBS_ALLOWED_TAGS); ?>
                    </span>
                    <span id="popup_coverletter_desc">
                        <?php wp_editor('', 'jobseekermessage'); ?>
                    </span>
                </div>
                <div class="js-field-wrapper js-row no-margin center" id="popup-bottom-part">
                    <input type="button" class="jsjobs-button" value="<?php echo __('Send Message', 'js-jobs'); ?>" onclick="sendMessage();"/>
                </div>
            </div>
        </div>

        <div class="page_heading"><?php echo __('View Resume', 'js-jobs'); ?></div>
        <?php
        if (isset(jsjobs::$_data['socialprofile']) && jsjobs::$_data['socialprofile'] == true) { // social profile
            $profileid = jsjobs::$_data['socialprofileid'];
            JSJOBSincluder::getObjectClass('socialmedia')->showprofilebyprofileid($profileid);
        } else {
            $html = '<div id="resume-wrapper">';
            $isowner = (JSJOBSincluder::getObjectClass('user')->uid() == jsjobs::$_data[0]['personal_section']->uid) ? 1 : 0;
            $html .= $resumeviewlayout->getPersonalTopSection($isowner, 1);
            $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/personal-info.png" />' . __('Personal information', 'js-jobs') . '</div>';
            $html .= $resumeviewlayout->getPersonalSection(0, 1);
            $show_section_that_have_value = JSJOBSincluder::getJSModel('configuration')->getConfigValue('show_only_section_that_have_value');
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['address_section'][0])){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][2]['section_address']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/word.png" />' . __('Addresses', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getAddressesSection(0, 0, 1);
            }
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['institute_section'][0])){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][3]['section_education']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/education.png" />' . __('Education', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getEducationSection(0, 0, 1);
            }
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['employer_section'][0])){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][4]['section_employer']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/employer.png" />' . __('Employer', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getEmployerSection(0, 0, 1);
            }
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['personal_section']->skills)){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][5]['section_skills']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/skills.png" />' . __('Skills', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getSkillSection(0, 0, 1);
            }
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['personal_section']->resume)){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][6]['section_resume']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/resume.png" />' . __('Resume', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getResumeSection(0, 0, 1);
            }
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['reference_section'][0])){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][7]['section_reference']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/referances.png" />' . __('References', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getReferenceSection(0, 0, 1);
            }
            $showflag = 1;
            if ($show_section_that_have_value == 1 && empty(jsjobs::$_data[0]['language_section'][0])){
                $showflag = 0;
            }
            if (isset(jsjobs::$_data[2][8]['section_language']) && $showflag == 1) {
                $html .= '<div class="resume-section-title"><img class="heading-img" src="' . JSJOBS_PLUGIN_URL . 'includes/images/language.png" />' . __('Languages', 'js-jobs') . '</div>';
                $html .= $resumeviewlayout->getLanguageSection(0, 0, 1);
            }
            $html .= '</div>';
            echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
        }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
</div>
