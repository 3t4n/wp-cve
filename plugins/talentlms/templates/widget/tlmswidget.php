<div class="tlms-widget-container">
    <?php foreach ($courses as $course) : ?>
        <div class="tlms-widget-item">
            <a href="<?php echo esc_url(get_site_url()); ?>/courses/?tlms-course=<?php echo esc_attr($course->id); ?>">
                <img src="<?php echo esc_attr($course->big_avatar); ?>" alt="<?php echo esc_attr($course->name); ?>"/>
                <span class="tlms-widget-caption"> <?php echo esc_html($course->name);
                    echo (!empty($course->course_code)) ? "(".esc_html($course->course_code).")" : ''; ?>
                </span>
            </a>
        </div>
    <?php endforeach; ?>
</div>
