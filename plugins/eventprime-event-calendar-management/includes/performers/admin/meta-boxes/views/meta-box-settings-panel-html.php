<?php
/**
 * Performer Settings panel html.
 */
defined('ABSPATH') || exit;
$em_type = get_post_meta($post->ID, 'em_type', true);
$em_role = get_post_meta($post->ID, 'em_role', true);
$em_display_front = get_post_meta($post->ID, 'em_display_front', true);
if ($em_display_front ==''){
    $em_display_front = 1;
}
?>


<div id="ep_performer_settings_data" class="panel ep_performer_options_panel">
    <div class="ep-box-wrap  ep-my-3">
        <div class="ep-box-row ep-meta-box-section">
            <div class="ep-box-col-12 ep-meta-box-data">
                <label class="ep-meta-box-title ep-form-check-label">
                    <?php esc_html_e('Performer Type', 'eventprime-event-calendar-management'); ?>
                </label>
                <div class="ep-form-check ep-mt-2 ep-performer-type">
                    <input type="radio" id="person" name="em_type" class="ep-form-check-input" value="person" <?php if (!empty($em_type) && $em_type == 'person') {
                        echo 'checked="checked"';
                    } ?> >
                    <label for="person" class="ep-form-check-label">
                       <?php esc_html_e('Person', 'eventprime-event-calendar-management'); ?>
                    </label>
                </div>

                <div class="ep-form-check ep-mt-2 ep-performer-type">
                    <input type="radio" id="group"  class="ep-form-check-input" name="em_type" value="group" <?php if (!empty($em_type) && $em_type == 'group') {
                       echo 'checked="checked"';} ?> >
                    <label for="group" class="ep-form-check-label">
                         <?php esc_html_e('Group', 'eventprime-event-calendar-management'); ?>
                    </label>
                </div>
                <div class="ep-error-message" id="ep_performer_type_error"></div>
            </div>
        </div>

        <div class="ep-box-row ep-meta-box-section ep-mt-2">
            <div class="ep-box-col-4 ep-meta-box-data">
                <label class="ep-meta-box-title ep-form-check-label">
                    <?php esc_html_e('Role', 'eventprime-event-calendar-management'); ?>
                </label>
                <input type="text" name="em_role" class="ep-form-control" id="em_role" value="<?php echo esc_attr($em_role); ?>" placeholder="<?php esc_html_e('Role', 'eventprime-event-calendar-management'); ?>" >

            </div>
        </div>

        <div class="ep-box-row ep-meta-box-section ep-mt-3 ep-mb-3">
            <div class="ep-box-col-4 ep-meta-box-data">
                <div class="ep-form-check">
                    <input type="checkbox" name="em_display_front" id="em_display_front" class="ep-form-check-input" value="<?php echo esc_attr($em_display_front); ?>" <?php
                        if ($em_display_front == 1) {
                          echo 'checked="checked"';
                          }
                         ?> >
                    <label class="ep-performer-display ep-form-check-label" for="em_display_front"><?php esc_html_e('Display On List Of Performers', 'eventprime-event-calendar-management'); ?></label>
                </div>
            </div>
        </div>

    </div> 
</div>