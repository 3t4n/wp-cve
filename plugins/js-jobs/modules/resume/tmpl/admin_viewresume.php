<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jsjobs-repaptcha-scripti','https://maps.googleapis.com/maps/api/js?key='.jsjobs::$_configuration['google_map_api_key']);
?>
<script type="text/javascript">
    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
    jQuery(document).ready(function(){
        var print_link = document.getElementById('print-link');
        if (print_link) {
            var href = '<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'printresume', 'jsjobsid'=>jsjobs::$_data[0]['personal_section']->id, 'jsjobspageid'=>jsjobs::getPageid()))) ?>';
            print_link.addEventListener('click', function (event) {
                print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
                event.preventDefault();
            }, false);
        }
    });
    function showPopupAndSetValues() {
        jQuery("div#full_background").show();
        jQuery("div#popup-main-outer.coverletter").show();
        jQuery("div#popup-main.coverletter").slideDown('slow');
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
    }
    function closePopup() {
        jQuery("div#popup-main-outer").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            jQuery("div#popup-main").hide();
        }, 700);
    }

    function initialize(lat, lang, div) {
        var myLatlng = new google.maps.LatLng(lat, lang);
        var myOptions = {
            zoom: 8,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById(div), myOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: myLatlng
        });
    }
    jQuery(document).ready(function () {
        jQuery('div.resume-map div.row-title').click(function (e) {
            e.preventDefault();
            var img1 = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/show-map.png'; ?>';
            var img2 = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/hide-map.png'; ?>';
            var pdiv = jQuery(this).parent();
            var mdiv = jQuery(pdiv).find('div.row-value');
            if (jQuery(mdiv).css('display') == 'none') {
                jQuery(mdiv).show();
                jQuery(this).find('img').attr('src', img2);
            } else {
                jQuery(mdiv).hide();
                jQuery(this).find('img').attr('src', img1);
            }
        });
    });
    function sendMessageJobseeker() {
        jQuery("div#full_background").show();
        jQuery("div#popup-main-outer.sendmessage").show();
        jQuery("div#popup-main.sendmessage").slideDown('slow');
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
    }
    function sendMessage() {
        var subject = jQuery('input#subject').val();
        if (subject == '') {
            alert("<?php echo __("Please fill the subject", "js-jobs"); ?>");
            return false;
        }
        var message = tinyMCE.get('jobseekermessage').getContent();
        if (message == '') {
            alert("<?php echo __("Please fill the message", "js-jobs"); ?>");
            return false;
        }
        var resumeid = '<?php echo esc_attr(jsjobs::$_data[0]['personal_section']->id); ?>';
        var uid = '<?php echo esc_attr(jsjobs::$_data[0]['personal_section']->uid); ?>';
        jQuery.post(ajaxurl, {action: "jsjobs_ajax", jsjobsme: "message", task: "sendmessageresume", subject: subject, message: message, resumeid: resumeid, uid: uid,wpnoncecheck:common.wp_jm_nonce}, function (data) {
            if (data) {
                alert("<?php echo __("Message sent", "js-jobs"); ?>");
                closePopup();
            }else{
                alert("<?php echo __("Message not sent", "js-jobs"); ?>");
            }

        });
    }
</script>

<?php
    // css front end
    jsjobs::addStyleSheets();
    include_once  JSJOBS_PLUGIN_PATH. 'includes/css/style_color.php';
    wp_enqueue_style('jsjob-jobseeker-style', JSJOBS_PLUGIN_URL . 'includes/css/jobseekercp.css');
    wp_enqueue_style('jsjob-employer-style', JSJOBS_PLUGIN_URL . 'includes/css/employercp.css');
    wp_enqueue_style('jsjob-style', JSJOBS_PLUGIN_URL . 'includes/css/style.css');
    wp_enqueue_style('jsjob-style-tablet', JSJOBS_PLUGIN_URL . 'includes/css/style_tablet.css',array(),'','(min-width: 481px) and (max-width: 780px)');
    wp_enqueue_style('jsjob-style-mobile-landscape', JSJOBS_PLUGIN_URL . 'includes/css/style_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 650px)');
    wp_enqueue_style('jsjob-style-mobile', JSJOBS_PLUGIN_URL . 'includes/css/style_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jsjob-chosen-style', JSJOBS_PLUGIN_URL . 'includes/js/chosen/chosen.min.css');
    wp_enqueue_style('jsjob-normal-style', JSJOBS_PLUGIN_URL . 'includes/css/jsjobs_normlize.css');
    if (is_rtl()) {
        wp_register_style('jsjob-style-rtl', JSJOBS_PLUGIN_URL . 'includes/css/stylertl.css');
        wp_enqueue_style('jsjob-style-rtl');
    }
    $msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    if(! is_admin()){
        JSJOBSbreadcrumbs::getBreadcrumbs();
        include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');        
    }
if (jsjobs::$_error_flag == null) {
    $resumeviewlayout = JSJOBSincluder::getObjectClass('resumeviewlayout');
    ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
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

        <span class="js-admin-title">
            <span class="heading">
                <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_resume')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
                <span class="heading-text"><?php echo __('View Resume', 'js-jobs') ?></span> 
            </span>
        </span>
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
            $viewtags = jsjobs::$_data[0]['personal_section']->viewtags;
            $viewtags = $resumeviewlayout->makeanchorfortags($viewtags);
            echo wp_kses($viewtags, JSJOBS_ALLOWED_TAGS);
        }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
} ?>
    </div>
</div>
