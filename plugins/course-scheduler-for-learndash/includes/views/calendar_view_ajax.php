<?php
if(!empty($course_query->have_posts())) {
    ?>
    <div id="ld_cms_course_sub_list">
        <?php
        foreach( $course_query->posts as $course ) {
            $data = get_post_meta( $course->ID, 'course_schedule', true ); ?>
            <div class='fc-event' data-course-id="<?php echo $course->ID; ?>">
                <?php echo $course->post_title; ?>
            </div>
        <?php } ?>
    </div>
        <?php
} else {
    ?>
    <p><?php _e('No courses found for your search criteria', 'cs_ld_addon'); ?></p>
    <?php
}