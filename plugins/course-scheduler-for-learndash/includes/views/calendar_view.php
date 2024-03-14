<?php
/**
 * Abort if this file is accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="wrap">
    <!--<div class="ld_cs_branding" style="padding: 10px">
        <div class="ld_cs_branding_logo" style="float: left; width: 30%">
            <a href="http://wooninjas.com" target="_blank"><img src="<?php /*echo CS_LD_ASSETS_URL . "wooninjas.png"; */?>" alt="WooNinjas Logo"></a>
        </div>
        <div class="ld_cs_branding_text" style="float: left; width: 70%">
            <?php /*_e('For any support or assistance please reach out us at <a href="http://wooninjas.com/contact" target="_blank">WooNinjas</a>. If you find our plugin useful, kindly take some time to leave us a review at <a href="https://wordpress.org/plugins/course-scheduler-for-learndash/" target="_blank">Course Scheduler for LearnDash</a>', 'cs_ld_addon'); */?>
        </div>
        <div style="clear: both;"></div>
    </div>-->
    <div class="ldcs-fc-container container container--wide">
        <div class="ldcs-fc-sidebar">
            <div id='external-events'>
                <h4><?php _e( "Select " . LearnDash_Custom_Label::get_label( 'courses' ), 'cs_ld_addon' ) ?></h4>
                <div class="main_tree">
                    <input type="text" id="ld_cms_search_courses" name="ld_cms_search_courses" value="" placeholder="<?php echo sprintf(__('Search %s'), LearnDash_Custom_Label::get_label('courses')); ?>" style="width: 95%;" />

                    <div id="ld_cms_course_list" style="display: none;"><?php echo _e('Loading...'); ?></div>
                    <div id="ld_cms_course_list_loader" class="spinner is-active" style="float: none; text-indent: -9999px;">Loading...</div>
                </div>
                <p><a href="#loadMore" id="ld_cms_load_more"><?php _e('Load more courses', 'cs_ld_addon'); ?></a></p>
            </div>
        </div>
        <div class="ldcs-fc-main">
            <div class="ldcs-fc-main-container" id="ldcs-content">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div style='clear:both'></div>
</div>