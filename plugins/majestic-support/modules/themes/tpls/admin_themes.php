<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_enqueue_script('iris');
wp_enqueue_style('majesticsupport-main-css', MJTC_PLUGIN_URL . 'includes/css/style.css');
wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');
wp_enqueue_style('majesticsupport-color-css', MJTC_PLUGIN_URL . 'includes/css/color.css');
include_once MJTC_PLUGIN_PATH . 'includes/css/style.php';
MJTC_message::MJTC_getMessage();
?>
<style type="text/css">
    <?php $color1 = majesticsupport::$_colors['color1'];
    $color2 = majesticsupport::$_colors['color2'];
    $color3 = majesticsupport::$_colors['color3'];
    $color4 = majesticsupport::$_colors['color4'];
    $color5 = majesticsupport::$_colors['color5'];
    $color6 = majesticsupport::$_colors['color6'];
    $color7 = majesticsupport::$_colors['color7'];
    $color8 = majesticsupport::$_colors['color8'];
    $color9 = majesticsupport::$_colors['color9'];

    if (file_exists(MJTC_PLUGIN_PATH . 'includes/css/inc-css/ticket-myticket.css.php')) {
        require_once(MJTC_PLUGIN_PATH . 'includes/css/inc-css/ticket-myticket.css.php');
    }

    echo '
    div.mjtc-support-wrapper {border: 1px solid'.esc_attr($color5).';box-shadow: 0 8px 6px -6px #dedddd;}
    div.mjtc-support-wrapper:hover {border: 1px solid'.esc_attr($color2).';}
    div.mjtc-support-wrapper:hover div.mjtc-support-pic {border-right: 1px solid'.esc_attr($color2).';}
    div.mjtc-support-wrapper:hover div.mjtc-support-data1 {border-left: 0px solid'.esc_attr($color2).';}
    div.mjtc-support-wrapper:hover div.mjtc-support-bottom-line {background'.esc_attr($color2).';}
    div.mjtc-support-wrapper div.mjtc-support-pic {border-right: 1px solid'.esc_attr($color5).';}
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status {color: #FFFFFF;}
    div.mjtc-support-wrapper div.mjtc-support-data1 {border-left: 0px solid'.esc_attr($color5).';}
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-title {color: '.esc_attr($color4).';}
    div.msadmin-wrapper-main-overall-wrapper-for-all .mjtc-filter-button-wrp .mjtc-support-reset-btn {background: '.esc_attr($color2).';}
    div.msadmin-wrapper-main-overall-wrapper-for-all .mjtc-filter-button-wrp .mjtc-support-reset-btn:hover {background: '.esc_attr($color7).'; color: '.esc_attr($color2).'; border-color: '.esc_attr($color2).';}
    div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-search-btn:hover {background: '.esc_attr($color7).'; color: '.esc_attr($color1).'; border-color: '.esc_attr($color1).' !important;}
    a.mjtc-support-title-anchor:hover {color: '.esc_attr($color2).' !important;}
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-value {color: '.esc_attr($color4).';}
    div.mjtc-support-wrapper div.mjtc-support-bottom-line {background'.esc_attr($color2).';}
    div.mjtc-support-assigned-tome {border: 1px solid'.esc_attr($color5).'; background-color: '.esc_attr($color3).';}
    div.mjtc-support-sorting span.mjtc-support-sorting-link a {background: #373435;color: '.esc_attr($color7).';color: #fff;}
    div.mjtc-support-sorting span.mjtc-support-sorting-link a.selected,
    div.mjtc-support-sorting span.mjtc-support-sorting-link a:hover {background: '.esc_attr($color2).';}
    div#ms-header div#ms-header a {color: '.esc_attr($color7).';}';
    ?>
    div.mjtc-support-sorting {float: left;width: 100%;}
    /* My Tickets $ Staff My Tickets*/
    div.mjtc-support-wrapper {margin: 8px 0px;padding-left: 0px;padding-right: 0px;}
    div.mjtc-support-wrapper div.mjtc-support-pic {margin: 10px 0px;padding: 0px;padding: 0px 10px;text-align: center;position: relative;float: left;width: 16% !important;height: 96px !important;}
    div.mjtc-support-wrapper div.mjtc-support-pic img.mjtc-support-staff-img {width: auto;max-width: 96px;max-height: 96px; height: auto;position: absolute;top: 0;left: 0;right: 0; bottom: 0;margin: auto;}
    div.mjtc-support-wrapper div.mjtc-support-data {position: relative;padding: 23px 0px;width: 50%;}
    div.mjtc-support-wrapper .mjtc-support-wrapper-edit div.mjtc-support-data {position: relative;padding: 23px 0px;width: 68%;}
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status {position: absolute;top: 41%;right: 2%;padding: 10px 10px;  border-radius: 20px;line-height: 1;font-weight: bold;}
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status img.ticketstatusimage {position: absolute;top: 0px; }
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status img.ticketstatusimage.one {left: -25px;}
    div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status img.ticketstatusimage.two {left: -50px;}
    div.mjtc-support-wrapper div.mjtc-support-data1 {margin: 0px 0px;padding: 17px 15px !important; width: 33%;}
    div.mjtc-support-wrapper div.mjtc-support-bottom-line {position: absolute;display: inline-block;width: 90%;margin: 0 5%;height: 1px;left: 0px;bottom: 0px;}
    div.mjtc-support-wrapper div.mjtc-support-toparea {position: relative; padding: 0px;}
    div.mjtc-support-wrapper div.mjtc-support-bottom-data-part {padding: 0px;margin-bottom: 10px;}
    div.mjtc-support-wrapper div.mjtc-support-bottom-data-part a.button {float: right;margin-left: 10px;padding: 0px 20px;line-height: 30px;height: 32px;}
    div.mjtc-support-wrapper div.mjtc-support-bottom-data-part a.button img {height: 16px;margin-right: 5px; }
    div.mjtc-support-assigned-tome {float: left; width: 100%;padding: 11px 10px;}
    div.mjtc-support-assigned-tome input#assignedtome1 {margin-right: 5px;vertical-align: middle; }
    div.mjtc-support-assigned-tome label#forassignedtome {margin: 0px;display: inline-block;}
    label#forassigntome {margin: 0px;display: inline-block;}
    span.mjtc-support-wrapper-textcolor {display: inline-block;padding: 5px 10px; min-width: 85px;text-align: center;}
    /* Sorting Section */
    div.mjtc-support-sorting {padding-right: 0px;padding-left: 0px;margin-bottom: 15px;}
    div.mjtc-support-sorting span.mjtc-support-sorting-link {padding-right: 0px;padding-left: 0px;}
    div.mjtc-support-sorting span.mjtc-support-sorting-link a {text-decoration: none;display: block;padding: 15px;text-align: center;color: #fff !important;}
    div.mjtc-support-sorting span.mjtc-support-sorting-link a img {display: inline-block;vertical-align: text-top;width: 24px;}
</style>
<div class="msadmin-wrapper-main-overall-wrapper-for-all">
    <div id="msadmin-wrapper">
        <div id="msadmin-leftmenu">
            <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu');?>
        </div>
        <div id="msadmin-data">
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('themes');?>
            <div id="msadmin-data-wrp">
                <?php do_action('cm_theme_colors_message','majestic-support');?>
                <div id="theme_heading">
                    <div class="left_side">
                        <span class="job_sharing_text">
                            <?php echo esc_html(__('Color Chooser', 'majestic-support'));?>
                        </span>
                    </div>
                    <div class="right_side">
                        <a href="#" id="preset_theme">
                            <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/preset_theme.png" />
                            <span class="theme_presets_theme">
                                <?php echo esc_html(__('Preset Theme', 'majestic-support'));?>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="mjtc_theme_section mjtc_theme_section-wrapper-width">
                    <form action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=majesticsupport_themes&task=savetheme'),"save-theme"));?>" method="POST" name="adminForm" id="adminForm">
                        <span class="mjtc_theme_heading">
                            <?php echo esc_html(__('Color Chooser','majestic-support'));?>
                        </span>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 1','majestic-support'));?>
                            </span>
                            <input type="text" name="color1" id="color1" value="<?php echo esc_attr(majesticsupport::$_data[0]['color1']); ?>" style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color1']); ?>;" />
                            <span class="color_location">
                                <?php echo esc_html(__('Top Menu Heading Background', 'majestic-support')); ?>
                            </span>
                        </div>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 2', 'majestic-support'));?>
                            </span>
                            <input type="text" name="color2" id="color2" value="<?php echo esc_attr(majesticsupport::$_data[0]['color2']);?>" style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color2']); ?>;" />
                            <span class="color_location">
                                <?php echo esc_html(__('Top Header Line Color', 'majestic-support')); ?>,
                                <?php echo esc_html(__('Button Hover', 'majestic-support')); ?>,
                                <?php echo esc_html(__('Heading Text', 'majestic-support')); ?>
                            </span>
                        </div>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 3', 'majestic-support'));?>
                            </span>
                            <input type="text" name="color3" id="color3" value="<?php echo esc_attr(majesticsupport::$_data[0]['color3']);?>" style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color3']);?>;" />
                            <span class="color_location">
                                <?php echo esc_html(__('Content Background Color', 'majestic-support'));?>
                            </span>
                        </div>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 4', 'majestic-support'));?>
                            </span>
                            <input type="text" name="color4" id="color4" value="<?php echo esc_attr(majesticsupport::$_data[0]['color4']);?>" style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color4']);?>;" />
                            <span class="color_location">
                                    <?php echo esc_html(__('Content Text Color', 'majestic-support'));?>
                            </span>
                        </div>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 5','majestic-support'));?>
                            </span>
                            <input type="text" name="color5" id="color5" value="<?php echo esc_attr(majesticsupport::$_data[0]['color5']); ?>" style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color5']);?>;" />
                            <span class="color_location">
                                <?php echo esc_html(__('Border Color','majestic-support'));?>,
                                <?php echo esc_html(__('Lines','majestic-support')); ?>
                            </span>
                        </div>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 6', 'majestic-support'));?>
                            </span>
                            <input type="text" name="color6" id="color6" value="<?php echo esc_attr(majesticsupport::$_data[0]['color6']); ?>" style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color6']);?>;" />
                            <span class="color_location">
                                <?php echo esc_html(__('Button Color', 'majestic-support'));?>
                            </span>
                        </div>
                        <div class="color_portion">
                            <span class="color_title">
                                <?php echo esc_html(__('Color 7', 'majestic-support'));?>
                            </span>
                            <input type="text" name="color7" id="color7"
                                value="<?php echo esc_attr(majesticsupport::$_data[0]['color7']);?>"
                                style="background:<?php echo esc_attr(majesticsupport::$_data[0]['color7']);?>;" />
                            <span class="color_location">
                                <?php echo esc_html(__('Top Header Text Color', 'majestic-support')); ?>
                            </span>
                        </div>
                        <div class="color_submit_button">
                            <input type="hidden" name="form_request" value="majesticsupport" />
                            <input type="submit" value="<?php echo esc_html(__('Save Colors', 'majestic-support'));?>" />
                        </div>
                    </form>
                </div>
                <div class="mjtc_effect_preview mjtc_effect_preview-overall-wrapper">
                    <span class="mjtc_effect_preview_heading">
                        <?php echo esc_html(__('Color Effect Preview', 'majestic-support')); ?>
                    </span>
                    <main class="span12" role="main" id="content">
                        <div class="ms-main-up-wrapper">
                            <div class="ms-header-main-overall-wrapper">
                                <div id="ms-header-main-wrapper">
                                    <div id="ms-header" class="">
                                        <div id="ms-tabs-wrp" class="">
                                            <div class="ms-header-tab mjtc-support-homeclass">
                                                <a class="mjtc-cp-menu-link" href="#">
                                                    <?php echo esc_html(__('Dashboard', 'majestic-support'));?>
                                                </a>
                                            </div>
                                            <div class="ms-header-tab mjtc-support-openticketclass">
                                                <a id="#" class="mjtc-cp-menu-link" href="#">
                                                    <?php echo esc_html(__('Submit Ticket  ', 'majestic-support'));?>
                                                </a>
                                            </div>
                                            <div class="ms-header-tab mjtc-support-myticket">
                                                <a class="mjtc-cp-menu-link" href="#">
                                                    <?php echo esc_html(__('My Tickets', 'majestic-support'));?>
                                                </a>
                                            </div>
                                            <div class="ms-header-tab mjtc-support-loginlogoutclass" id="mjtc-support-loginlogoutclass-overall-wrapper">
                                                <a class="mjtc-cp-menu-link" href="#">
                                                    <?php echo esc_html(__('Log out', 'majestic-support'));?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mjtc-transparent-header mjtc-transparent-header-wraper-outerclass"></div>
                                </div>
                            </div>
                            <div class="mjtc-support-over-all-wrapper">
                                <div class="mjtc-support-top-sec-header">
                                    <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/tp-image.png">
                                    <div class="mjtc-support-top-sec-left-header">
                                        <div class="mjtc-support-main-heading">
                                            <?php echo esc_html(__('My Tickets','majestic-support'));?>
                                        </div>
                                        <div class="mjtc-support-breadcrumps">
                                            <a href="#">
                                                <img alt="<?php echo esc_html(__('image', 'majestic-support')) ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL) ?>includes/images/home-icon.png" />
                                            </a>
                                            <span><?php echo esc_html(__('My Tickets', 'majestic-support'));?></span>
                                        </div>
                                    </div>
                                    <div class=create-ticket-wrapper-button-header>
                                        <div class="mjtc-support-top-sec-right-header">
                                            <a id="#" href="#" class="mjtc-support-button-header mjtc-support-button-overall-wrapper">
                                                <?php echo esc_html(__('Submit Ticket','majestic-support'));?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- form -->
                            <div class="form-themes-overall-wrapper">
                                <div class="mjtc-support-cont-main-wrapper">
                                    <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
                                        <div class="mjtc-row mjtc-support-top-cirlce-count-wrp">
                                            <div class="mjtc-col-xs-12 mjtc-col-md-2 mjtc-myticket-link">
                                                <a class="mjtc-support-green mjtc-myticket-link active" href="#"
                                                    data-tab-number="1">
                                                    <div class="mjtc-support-cricle-wrp" data-per="60">
                                                        <div class="mjtc-mr-rp" data-progress="60">
                                                            <div class="circle">
                                                                <div class="mask full">
                                                                    <div class="fill mjtc-support-open">
                                                                    </div>
                                                                </div>
                                                                <div class="mask half">
                                                                    <div class="fill mjtc-support-open">
                                                                    </div>
                                                                    <div class="fill fix">
                                                                    </div>
                                                                </div>
                                                                <div class="shadow">
                                                                </div>
                                                            </div>
                                                            <div class="inset">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="mjtc-support-circle-count-text mjtc-support-green">
                                                        <?php echo esc_html(__('Open ( 3 )','majestic-support')); ?>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="mjtc-col-xs-12 mjtc-col-md-2 mjtc-myticket-link">
                                                <a class="mjtc-support-red mjtc-myticket-link" href="#" data-tab-number="2">
                                                    <div class="mjtc-support-cricle-wrp" data-per="40">
                                                        <div class="mjtc-mr-rp" data-progress="40">
                                                            <div class="circle">
                                                                <div class="mask full">
                                                                    <div class="fill mjtc-support-close"></div>
                                                                </div>
                                                                <div class="mask half">
                                                                    <div class="fill mjtc-support-close"></div>
                                                                    <div class="fill fix"></div>
                                                                </div>
                                                                <div class="shadow"></div>
                                                            </div>
                                                            <div class="inset">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="mjtc-support-circle-count-text mjtc-support-red">
                                                        <?php echo esc_html(__('Closed ( 2 )','majestic-support'));?>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="mjtc-col-xs-12 mjtc-col-md-2 mjtc-myticket-link">
                                                <a class="mjtc-support-brown mjtc-myticket-link" href="#" data-tab-number="3">
                                                    <div class="mjtc-support-cricle-wrp" data-per="20">
                                                        <div class="mjtc-mr-rp" data-progress="20">
                                                            <div class="circle">
                                                                <div class="mask full">
                                                                    <div class="fill mjtc-support-answer"></div>
                                                                </div>
                                                                <div class="mask half">
                                                                    <div class="fill mjtc-support-answer"></div>
                                                                    <div class="fill fix"></div>
                                                                </div>
                                                                <div class="shadow"></div>
                                                            </div>
                                                            <div class="inset">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="mjtc-support-circle-count-text mjtc-support-brown">
                                                        <?php echo esc_html(__('Answered ( 1 )','majestic-support')); ?>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="mjtc-col-xs-12 mjtc-col-md-2 mjtc-myticket-link">
                                                <a class="mjtc-support-blue mjtc-myticket-link" href="#" data-tab-number="4">
                                                    <div class="mjtc-support-cricle-wrp" data-per="">
                                                        <div class="mjtc-mr-rp" data-progress="100">
                                                            <div class="circle">
                                                                <div class="mask full">
                                                                    <div class="fill mjtc-support-allticket"></div>
                                                                </div>
                                                                <div class="mask half">
                                                                    <div class="fill mjtc-support-allticket"></div>
                                                                    <div class="fill fix"></div>
                                                                </div>
                                                                <div class="shadow"></div>
                                                            </div>
                                                            <div class="inset">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="mjtc-support-circle-count-text mjtc-support-blue">
                                                        <?php echo esc_html(__('All Tickets ( 5 )','majestic-support'));?>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mjtc-support-search-wrp">
                                            <div class="mjtc-form-wrapper-support">
                                                <div class="mjtc-support-form-wrp">
                                                    <div class=form-overall-wrapper>
                                                        <form class="mjtc-filter-form" name="majesticsupportform" id="majesticsupportform" method="POST" action="#">
                                                            <div class="mjtc-filter-wrapper">
                                                                <div class="mjtc-filter-form-fields-wrp"
                                                                    id="mjtc-filter-wrapper-toggle-search">
                                                                    <input type="text" name="ms-ticketsearchkeys"
                                                                        id="ms-ticketsearchkeys" value=""
                                                                        class="mjtc-support-input-field"
                                                                        placeholder="<?php echo __('Ticket ID', 'majestic-support') . ' ' . __('Or', 'majestic-support') . ' ' . __('Email Address', 'mamajestic-support') . ' ' . __('Or', 'majestic-support') . ' ' . __('Subject', 'mamajestic-support')?>">
                                                                </div>
                                                                <div class="buttons-overall-wrapper">
                                                                    <div class="mjtc-filter-button-wrp">
                                                                        <div>
                                                                            <a href="#" class="mjtc-search-filter-btn"
                                                                                id="mjtc-search-filter-toggle-btn">
                                                                                <?php echo esc_html(__('Show All','majestic-support'));?>
                                                                            </a>
                                                                            <input type="submit" name="#" id="#" value="<?php echo esc_html(__('Search','majestic-support'));?>"
                                                                                class="mjtc-support-filter-button mjtc-support-search-btn">
                                                                            <button class="mjtc-support-reset-btn">
                                                                                <?php echo esc_html(__('Reset','majestic-support'));?>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Top Circle Count Boxes -->
                                        <!-- Search Form -->
                                        <!-- Sorting Wrapper -->
                                        <div class="mjtc-support-sorting">
                                            <div class="mjtc-support-sorting-left">
                                                <div class="mjtc-support-sorting-heading">
                                                    <span>
                                                        <?php echo esc_html(__('Open Tickets','majestic-support'));?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mjtc-support-sorting-right">
                                                <div class="mjtc-support-sort">
                                                    <select class="mjtc-support-sorting-select">
                                                        <?php echo esc_html(__('Subject','majestic-support'));?>
                                                        <option value="subjectdesc">
                                                            <?php echo esc_html(__('Subject', 'majestic-support'));?>
                                                        </option>
                                                        <option value="prioritydesc">
                                                            <?php echo esc_html(__(' Priority', 'majestic-support'));?>
                                                        </option>
                                                        <option value="ticketiddesc">
                                                            <?php echo esc_html(__('Ticket ID', 'majestic-support'));?>
                                                        </option>
                                                        <option value="isanswereddesc">
                                                            <?php echo esc_html(__('Answered', 'majestic-support'));?>
                                                        </option>
                                                        <span class="status-button-wrapper-radius">
                                                            <option class="status-white" value="statusasc" selected="">
                                                                <?php echo esc_html(__('Status', 'majestic-support'));?>
                                                            </option>
                                                        </span>
                                                    </select>
                                                    <span class="sort-link-wrapper">
                                                        <a href="#" class="mjtc-admin-sort-btn" title="<?php echo __('sort', 'majestic-support') ?>">
                                                            <img alt="<?php echo __('sort', 'majestic-support') ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/sorting-2.png">
                                                        </a>
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="mjtc-support-wrapper">
                                            <div class="mjtc-support-wrapper-edit">
                                                <div class="mjtc-support-pic-edited">
                                                    <img alt="" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/user.png" class="avatar avatar-96 photo" height="96" width="96">
                                                </div>
                                                <div class="mjtc-col-xs-10 mjtc-col-md-6 mjtc-col-xs-10 mjtc-support-data mjtc-nullpadding">
                                                    <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses name">
                                                        <span class="mjtc-support-value" style="cursor:pointer;"
                                                            onclick="setFromNameFilter('user02@gmail.com');">
                                                            <?php echo esc_html(__('Kylee Arroyo','majestic-support'));?>
                                                        </span>
                                                    </div>
                                                    <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses">
                                                        <a class="mjtc-support-title-anchor" href="#">
                                                            <?php echo esc_html(__('Can i upgrade my plan?','majestic-support'));?>
                                                        </a>
                                                    </div>
                                                    <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses">
                                                        <span class="mjtc-support-title">
                                                            <?php echo esc_html(__('Department','majestic-support'));?>&nbsp;:&nbsp;
                                                        </span>
                                                        <span class="mjtc-support-value" style="cursor:pointer;"
                                                            onclick="setDepartmentFilter('2');">
                                                            <?php echo esc_html(__('Marketing','majestic-support'));?>
                                                        </span>
                                                    </div>
                                                    <span class="replid-ticket-overall-wrapper">
                                                        <div class="buttons-color-status-wrapper">
                                                            <span class="mjtc-support-status-clock-time">
                                                                <img class="ticketstatusimage one"
                                                                    src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/over-due.png"
                                                                    title="<?php echo esc_html(__('The ticket marks as overdue','majestic-support'));?>">
                                                            </span>
                                                            <span class="mjtc-support-status" style="color:#2168A2;">
                                                                <?php echo esc_html(__('Replied','majestic-support'));?>
                                                            </span>
                                                            <span class="mjtc-support-wrapper-textcolor prorty" style="background:#049fc1;">
                                                                <?php echo esc_html(__('Low','majestic-support'));?>
                                                            </span>
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="mjtc-col-xs-12 mjtc-col-md-4 mjtc-support-data1 mjtc-support-padding-left-xs">
                                                    <div class="mjtc-support-ticket-wrapper">
                                                        <div class="mjtc-support-data-row ticket-reply-wrapper">
                                                            <div class="mjtc-support-data-tit">
                                                                <?php echo esc_html(__('Ticket ID  :','majestic-support'));?>
                                                            </div>
                                                            <div class="mjtc-support-data-val">
                                                                <?php echo esc_html(__('XcyMfWYKp','majestic-support'));?>
                                                            </div>
                                                        </div>
                                                        <div class="mjtc-support-data-row">
                                                            <div class="mjtc-support-data-tit mjtc-last-reply-text">
                                                                <?php echo esc_html(__('Last Reply :','majestic-support'));?>
                                                            </div>
                                                            <div class="mjtc-support-data-val">
                                                                <?php echo esc_html(__('08-12-2022','majestic-support'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mjtc-support-wrapper">
                                            <div class="mjtc-support-wrapper-edit">
                                                <div class="mjtc-support-pic-edited"><img alt=""
                                                        src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/user.png"
                                                        class="avatar avatar-96 photo" height="96" width="96"></div>
                                                <div
                                                    class="mjtc-col-xs-10 mjtc-col-md-6 mjtc-col-xs-10 mjtc-support-data mjtc-nullpadding">
                                                    <div
                                                        class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses name">
                                                        <span class="mjtc-support-value" style="cursor:pointer;"
                                                            onclick="setFromNameFilter('fakehabutt650@gmail.com');">
                                                            <?php echo esc_html(__('Allison Carney','majestic-support'));?>
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses">
                                                        <a class="mjtc-support-title-anchor" href="#">
                                                            <?php echo esc_html(__('How can i get Subscription', 'majestic-support'));?>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses">
                                                        <span class="mjtc-support-title">
                                                            <?php echo esc_html(__(' Department   ', 'majestic-support'));?>&nbsp;:&nbsp;
                                                        </span>
                                                        <span class="mjtc-support-value" style="cursor:pointer;"
                                                            onclick="setDepartmentFilter('1');">
                                                            <?php echo esc_html(__('Support','majestic-support'));?>
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="buttons-color-status-wrapper button-color-overall-status-wraper-padding">
                                                        <span class=mjtc-support-wrapper-new-button-wrapper> <span
                                                                class="mjtc-support-status" style="color:#159667;">
                                                                <?php echo esc_html(__('New','majestic-support'));?>
                                                            </span></span>
                                                        <span class="mjtc-support-text-color-wrapper-normal"> <span
                                                                class="mjtc-support-wrapper-textcolor prorty"
                                                                style="background:#188f28;">
                                                                <?php echo esc_html(__('Normal','majestic-support'));?>
                                                            </span></span>
                                                    </div>
                                                </div>
                                                <div class="mjtc-col-xs-12 mjtc-col-md-4 mjtc-support-data1 mjtc-support-padding-left-xs">
                                                    <div class="mjtc-support-data-row">
                                                        <div class="mjtc-support-data-tit">
                                                            <?php echo esc_html(__(' Ticket ID :','majestic-support'));?>
                                                        </div>
                                                        <div class="mjtc-support-data-val">
                                                            <?php echo esc_html(__('Yx4cW3Mfk','majestic-support'));?>
                                                        </div>
                                                    </div>
                                                    <div class="mjtc-support-data-row">
                                                        <div class="mjtc-support-data-tit">
                                                            <?php echo esc_html(__('Created :','majestic-support'));?>
                                                        </div>
                                                        <div class="mjtc-support-data-val">
                                                            <?php echo esc_html(__('05-12-2022','majestic-support'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mjtc-support-wrapper">
                                            <div class="mjtc-support-wrapper-edit">
                                                <div class="mjtc-support-pic-edited">
                                                    <img alt="" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/user.png"
                                                        class="avatar avatar-96 photo" height="96" width="96">
                                                </div>
                                                <div class="mjtc-support-data mjtc-nullpadding">
                                                    <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses name">
                                                        <span class="mjtc-support-value" style="cursor:pointer;"
                                                            onclick="setFromNameFilter('fakehabutt650@gmail.com');">
                                                            <?php echo esc_html(__('Matthew Frazier','majestic-support'));?>
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses">
                                                        <a class="mjtc-support-title-anchor" href="#">
                                                            <?php echo esc_html(__('How long do i have support access','majestic-support'));?>
                                                        </a>
                                                    </div>
                                                    <div class="mjtc-col-xs-12 mjtc-col-md-12 mjtc-support-padding-xs mjtc-support-body-data-elipses">
                                                        <span class="mjtc-support-title">
                                                            <?php echo esc_html(__(' Department','majestic-support'));?>&nbsp;:&nbsp;
                                                        </span>
                                                        <span class="mjtc-support-value" style="cursor:pointer;" onclick="setDepartmentFilter('1');">
                                                            <?php echo esc_html(__('Support','majestic-support'));?>
                                                        </span>
                                                    </div>
                                                    <div class="buttons-color-status-wrapper button-color-overall-status-wraper-padding">
                                                        <span class=mjtc-support-wrapper-new-button-wrapper>
                                                            <span class="mjtc-support-status" style="color:#159667;">
                                                                <?php echo esc_html(__('New','majestic-support'));?>
                                                            </span>
                                                        </span>
                                                        <span class="mjtc-support-text-color-wrapper-normal">
                                                            <span class="mjtc-support-wrapper-textcolor prorty" style="background:#188f28;">
                                                                <?php echo esc_html(__('Normal','majestic-support'));?>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mjtc-col-xs-12 mjtc-col-md-4 mjtc-support-data1 mjtc-support-padding-left-xs">
                                                    <div class="mjtc-support-data-row">
                                                        <div class="mjtc-support-data-tit">
                                                            <?php echo esc_html(__(' Ticket ID :','majestic-support'));?>
                                                        </div>
                                                        <div class="mjtc-support-data-val">
                                                            <?php echo esc_html(__('GxNDv3MfC','majestic-support'));?>
                                                        </div>
                                                    </div>
                                                    <div class="mjtc-support-data-row">
                                                        <div class="mjtc-support-data-tit">
                                                            <?php echo esc_html(__('Created :','majestic-support'));?>
                                                        </div>
                                                        <div class="mjtc-support-data-val">
                                                            <?php echo esc_html(__('01-12-2022','majestic-support'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <div class="color_submit_button">
                    <a class="mjtc-color-submit-button" href="#" onclick="document.getElementById('adminForm').submit();" >
                        <?php echo esc_html(__('Save Colors','majestic-support')); ?>
                    </a>
                    <div class="mjtc-sugestion-alert-wrp">
                        <div class="mjtc-sugestion-alert">
                            <strong><?php echo esc_html(__('Note','majestic-support')).":";?></strong>
                            <?php echo esc_html(__('If the colors have been saved but the user-side colors are still the same, it is advised to clear the cache.','majestic-support'));?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $majesticsupport_js ="
            jQuery(document).ready(function () {
                makeColorPicker('". majesticsupport::$_data[0]['color1']."', '". majesticsupport::$_data[0]['color2']."', '". majesticsupport::$_data[0]['color3']."', '". majesticsupport::$_data[0]['color4']."', '". majesticsupport::$_data[0]['color5']."', '". majesticsupport::$_data[0]['color6']."', '". majesticsupport::$_data[0]['color7']."');
            });
            function makeColorPicker(color1, color2, color3, color4, color5, color6, color7) {
                jQuery('input#color1').iris({
                    color: color1,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color1').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('div#ms-header div.ms-header-tab.active a.mjtc-cp-menu-link').css('backgroundColor', '#' + hex);
                    }
                });
                jQuery('input#color2').iris({
                    color: color2,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color2').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('div.mjtc-support-wrapper').mouseover(function () {
                            jQuery('div.mjtc-support-wrapper').css('borderColor', jQuery('input#color2').val());
                            jQuery('div.mjtc-support-pic').css('borderColor', jQuery('input#color2').val());
                            jQuery('div.mjtc-support-data1').css('borderColor', jQuery('input#color2').val());
                            jQuery('div.mjtc-support-bottom-line').css('backgroundColor', jQuery('input#color2').val());
                        }).mouseout(function () {
                            jQuery('div.mjtc-support-wrapper').css('borderColor', jQuery('input#color5').val());
                            jQuery('div.mjtc-support-pic').css('borderColor', jQuery('input#color5').val());
                            jQuery('div.mjtc-support-data1').css('borderColor', jQuery('input#color5').val());
                            jQuery('div.mjtc-support-bottom-line').css('backgroundColor', jQuery('input#color5').val());
                        });
                        jQuery('div.mjtc-support-sorting span.mjtc-support-sorting-link a.selected').css('backgroundColor', jQuery('input#color2').val());
                        jQuery('div#ms-header-main-wrapper').css('backgroundColor', jQuery('input#color2').val());
                        jQuery('div.mjtc-support-wrapper div.mjtc-support-bottom-line').css('borderColor', jQuery('input#color2').val());
                        jQuery('div.mjtc-support-flat a.active').css('borderColor', jQuery('input#color2').val());
                        jQuery('div.mjtc-support-sorting span.mjtc-support-sorting-link a').mouseover(function () {
                            jQuery('div.mjtc-support-sorting span.mjtc-support-sorting-link a').css('backgroundColor', jQuery('input#color2').val());
                        }).mouseout(function () {
                            jQuery('div.mjtc-support-sorting span.mjtc-support-sorting-link a').css('backgroundColor', jQuery('input#color5').val());
                        });
                        jQuery('a.mjtc-support-title-anchor').mouseover(function () {
                            jQuery('a.mjtc-support-title-anchor').css('color', jQuery('input#color2').val());
                        }).mouseout(function () {
                            jQuery('a.mjtc-support-title-anchor').css('color', jQuery('input#color5').val());
                        });
                        jQuery('div.mjtc-support-flat a').mouseover(function () {
                            jQuery('div.mjtc-support-flat a').css('backgroundColor', jQuery('input#color2').val());
                        }).mouseout(function () {
                            jQuery('div.mjtc-support-flat a').css('backgroundColor', jQuery('input#color5').val());
                        });
                    }
                });
                jQuery('input#color3').iris({
                    color: color3,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color3').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('div#ms-header div#ms-header').css('color', '#' + hex);
                        jQuery('div.mjtc-support-assigned-tome').css('backgroundColor', '#' + hex);
                    }
                });
                jQuery('input#color4').iris({
                    color: color4,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color4').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('div.mjtc-support-breadcrumb-wrp .breadcrumb li a').css('color', '#' + hex);
                        jQuery('div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-title').css('color', '#' + hex);
                        jQuery('div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-value').css('color', '#' + hex);
                    }
                });
                jQuery('input#color5').iris({
                    color: color5,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color5').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('div.mjtc-support-wrapper').css('borderColor', '#' + hex);
                        jQuery('div.mjtc-support-wrapper div.mjtc-support-pic').css('borderColor', '#' + hex);
                        jQuery('div.mjtc-support-wrapper div.mjtc-support-data1').css('borderColor', '#' + hex);
                        jQuery('div.mjtc-support-assigned-tome').css('borderColor', '#' + hex);
                    }
                });
                jQuery('input#color6').iris({
                    color: color6,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color6').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('a.mjtc-myticket-link').css('backgroundColor', '#' + hex);
                    }
                });
                jQuery('input#color7').iris({
                    color: color7,
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#color7').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('a.mjtc-myticket-link,span.mjtc-support-sorting-link a').each(function () {
                            jQuery(this).css('color', '#' + hex)
                        });
                        jQuery('a.mjtc-support-header-links').mouseover(function () {
                            jQuery('a.mjtc-support-header-links').css('color', jQuery('input#color7').val());
                        }).mouseout(function () {
                            jQuery('a.mjtc-support-header-links').css('color', jQuery('input#color7').val());
                        });
                        jQuery('div#ms-header div.ms-header-tab a.mjtc-cp-menu-link').mouseover(function () {
                            jQuery('div#ms-header div.ms-header-tab a.mjtc-cp-menu-link').css('color', jQuery('input#color7').val());
                        }).mouseout(function () {
                            jQuery('div#ms-header div.ms-header-tab a.mjtc-cp-menu-link').css('color', jQuery('input#color7').val());
                        });
                        jQuery('input#color7').css('backgroundColor', '#' + hex).val('#' + hex);
                        jQuery('div#ms-header div.ms-header-tab.active a.mjtc-cp-menu-link').css('color', '#' + hex).val('#' + hex);
                        jQuery('div.mjtc-support-sorting span.mjtc-support-sorting-link a').css('color', '#' + hex).val('#' + hex);
                        jQuery('div#ms-header div#ms-header a').css('color', '#' + hex).val('#' + hex);
                    }
                });
            }

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>
        <div id="black_wrapper_jobapply" style="display:none;"></div>
        <div id="mjtc_jobapply_main_wrapper" style="display:none;padding:0px 5px;">
            <div id="mjtc_job_wrapper">
                <span class="mjtc_job_controlpanelheading">
                    <?php echo esc_html(__('Preset Theme', 'majestic-support')); ?>
                </span>
                <div class="mjtc_theme_wrapper">
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#291abc;"></div>
                            <div class="color 2" style="background:#2B2B2B;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Blue Jeans','majestic-support'));?>
                            </span>
                            <img class="preview" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/themes/preview1.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#CF020D;"></div>
                            <div class="color 2" style="background:#9F3233;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Red','majestic-support'));?>
                            </span>
                            <img class="preview"
                                src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/themes/preview2.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#00A37A;"></div>
                            <div class="color 2" style="background:#2B2B2B;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Mint','majestic-support'));?>
                            </span>
                            <img class="preview" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/themes/preview3.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#7F05AB;"></div>
                            <div class="color 2" style="background:#590478;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Lavender','majestic-support'));?>
                            </span>
                            <img class="preview" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/themes/preview4.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#CF520F;"></div>
                            <div class="color 2" style="background:#2B2B2B;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Orange','majestic-support'));?>
                            </span>
                            <img class="preview" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/themes/preview5.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#4C8C03;"></div>
                            <div class="color 2" style="background:#2B2B2B;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Grass','majestic-support'));?>
                            </span>
                            <img class="preview" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/themes/preview6.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                    <div class="theme_platte">
                        <div class="color_wrapper">
                            <div class="color 1" style="background:#4C4952;"></div>
                            <div class="color 2" style="background:#4C4952;"></div>
                            <div class="color 3" style="background:#F5F2F5;"></div>
                            <div class="color 4" style="background:#636363;"></div>
                            <div class="color 5" style="background:#D1D1D1;"></div>
                            <div class="color 6" style="background:#E7E7E7;"></div>
                            <div class="color 7" style="background:#FFFFFF;"></div>
                            <span class="theme_name">
                                <?php echo esc_html(__('Black','majestic-support'));?>
                            </span>
                            <img class="preview" src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/themes/preview7.png" />
                            <a href="#" class="preview"></a>
                            <a href="#" class="set_theme"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $majesticsupport_js ="
            jQuery(document).ready(function () {
                jQuery('a#preset_theme').click(function (e) {
                    e.preventDefault();
                    jQuery('div#mjtc_jobapply_main_wrapper').fadeIn();
                    jQuery('div#black_wrapper_jobapply').fadeIn();
                });
                jQuery('div#black_wrapper_jobapply').click(function () {
                    jQuery('div#mjtc_jobapply_main_wrapper').fadeOut();
                    jQuery('div#black_wrapper_jobapply').fadeOut();
                });
                jQuery('a.preview').each(function (index, element) {
                    jQuery(this).hover(function () {
                        if (index > 2)
                            jQuery(this).parent().find('img.preview').css('top', '-110px');
                        jQuery(jQuery(this).parent().find('img.preview')).show();
                    }, function () {
                        jQuery(jQuery(this).parent().find('img.preview')).hide();
                    });
                });
                jQuery('a.set_theme').each(function (index, element) {
                    jQuery(this).click(function (e) {
                        e.preventDefault();
                        var div = jQuery(this).parent();
                        var color1 = rgb2hex(jQuery(div.find('div.1')).css('backgroundColor'));
                        var color2 = rgb2hex(jQuery(div.find('div.2')).css('backgroundColor'));
                        var color3 = rgb2hex(jQuery(div.find('div.3')).css('backgroundColor'));
                        var color4 = rgb2hex(jQuery(div.find('div.4')).css('backgroundColor'));
                        var color5 = rgb2hex(jQuery(div.find('div.5')).css('backgroundColor'));
                        var color6 = rgb2hex(jQuery(div.find('div.6')).css('backgroundColor'));
                        var color7 = rgb2hex(jQuery(div.find('div.7')).css('backgroundColor'));
                        jQuery('input#color1').val(color1).css('backgroundColor', color1);
                        jQuery('input#color2').val(color2).css('backgroundColor', color2);
                        jQuery('input#color3').val(color3).css('backgroundColor', color3);
                        jQuery('input#color4').val(color4).css('backgroundColor', color4);
                        jQuery('input#color5').val(color5).css('backgroundColor', color5);
                        jQuery('input#color6').val(color6).css('backgroundColor', color6);
                        jQuery('input#color7').val(color7).css('backgroundColor', color7);
                        themeSelectionEffect();
                        jQuery('div#mjtc_jobapply_main_wrapper').fadeOut();
                        jQuery('div#black_wrapper_jobapply').fadeOut();
                    });
                });
            });
            function rgb2hex(rgb) {
                rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
                function hex(x) {
                    return ('0' + parseInt(x).toString(16)).slice(-2);
                }
                return '#' + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
            }
            function themeSelectionEffect() {
                jQuery('input.mjtc-support-search-btn').mouseover(function () {
                    jQuery('input.mjtc-support-search-btn').css('borderColor', jQuery('input#color1').val());
                    jQuery('input.mjtc-support-search-btn').css('color', jQuery('input#color1').val());
                    jQuery('input.mjtc-support-search-btn').css('backgroundColor', jQuery('input#color7').val());
                }).mouseout(function () {
                    jQuery('input.mjtc-support-search-btn').css('borderColor', jQuery('input#color1').val());
                    jQuery('input.mjtc-support-search-btn').css('color', jQuery('input#color7').val());
                    jQuery('input.mjtc-support-search-btn').css('backgroundColor', jQuery('input#color1').val());
                });
                jQuery('div.mjtc-support-wrapper').mouseover(function () {
                    jQuery(this).css('borderColor', jQuery('input#color2').val());
                }).mouseout(function () {
                    jQuery(this).css('borderColor', jQuery('input#color5').val());
                });
                jQuery('button.mjtc-support-reset-btn').mouseover(function () {
                    jQuery('button.mjtc-support-reset-btn').css('borderColor', jQuery('input#color2').val());
                    jQuery('button.mjtc-support-reset-btn').css('color', jQuery('input#color2').val());
                    jQuery('button.mjtc-support-reset-btn').css('backgroundColor', jQuery('input#color7').val());
                }).mouseout(function () {
                    jQuery('button.mjtc-support-reset-btn').css('borderColor', jQuery('input#color2').val());
                    jQuery('button.mjtc-support-reset-btn').css('color', jQuery('input#color7').val());
                    jQuery('button.mjtc-support-reset-btn').css('backgroundColor', jQuery('input#color2').val());
                });
                jQuery('div#ms-header, div.mjtc-cp-wrapper, mjtc-support-add-form-wrapper').css('backgroundColor', jQuery('input#color1').val());
                jQuery('div#ms-header div.ms-header-tab a').each(function () {
                    jQuery(this).css('color', jQuery('input#color7').val())
                });
                jQuery('.mjtc-support-top-sec-header').css('backgroundColor', jQuery('input#color1').val());                
                jQuery('.mjtc-support-cont-main-wrapper .mjtc-support-cont-wrapper, .mjtc-support-cont-wrapper1').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-support-top-cirlce-count-wrp').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-myticket-link a.mjtc-myticket-link').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper').css('backgroundColor', jQuery('input#color3').val());
                jQuery('div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp input.mjtc-support-input-field').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp input.mjtc-support-input-field').css('color', jQuery('input#color4').val());
                jQuery('div.mjtc-filter-button-wrp .mjtc-search-filter-btn').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-filter-button-wrp .mjtc-search-filter-btn').css('color', jQuery('input#color4').val());
                jQuery('input.mjtc-support-search-btn').css('borderColor', jQuery('input#color5').val());
                jQuery('input.mjtc-support-search-btn').css('backgroundColor', jQuery('input#color1').val());
                jQuery('input.mjtc-support-search-btn').css('color', jQuery('input#color7').val());
                jQuery('.mjtc-support-reset-btn').css('backgroundColor', jQuery('input#color2').val());
                jQuery('.mjtc-support-reset-btn').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-support-sorting').css('backgroundColor', jQuery('input#color2').val());
                jQuery('div.mjtc-support-sorting').css('color', jQuery('input#color2').val());
                jQuery('div.mjtc-support-sorting').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-support-wrapper').css('borderColor', jQuery('input#color5').val());
                jQuery('.name span.mjtc-support-value').css('color', jQuery('input#color4').val());
                jQuery('div.mjtc-support-data .mjtc-support-title-anchor').css('color', jQuery('input#color1').val());
                jQuery('div.mjtc-support-data span.mjtc-support-title').css('color', jQuery('input#color2').val());
                jQuery('div.mjtc-support-data span.mjtc-support-value').css('color', jQuery('input#color4').val());
                jQuery('div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row .mjtc-support-data-tit').css('color', jQuery('input#color2').val());
                jQuery('div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row .mjtc-support-data-val').css('color', jQuery('input#color4').val());
                jQuery('.mjtc-support-breadcrumps span').css('color', jQuery('input#color7').val());
                jQuery('.mjtc-support-ticket-detail-wrapper-color, .mjtc-support-cont-wrapper-color').css('color', '#fff');
                jQuery('div.mjtc-support-top-cirlce-count-wrp').css('backgroundColor', jQuery('input#color7').val());
                jQuery('div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status').css('borderColor', jQuery('input#color5').val());
                jQuery('select.mjtc-support-sorting-select').css('borderColor', jQuery('input#color5').val());
                jQuery('div.mjtc-support-search-wrp').css('borderColor', jQuery('input#color5').val());
                jQuery('select.mjtc-support-sorting-select').css('color', jQuery('input#color4').val());
                jQuery('.mjtc-support-button, .mjtc-support-top-sec-right-header mjtc-support-button-header.mjtc-support-button-overall-wrapper').css('color', jQuery('input#color7').val());
                jQuery('mjtc-support-search-btn').css('color', jQuery('input#color7').val());
                jQuery('.mjtc-support-wrapper-textcolor.prorty').css('color', jQuery('input#color7').val());
            }

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>
    </div>
</div>
