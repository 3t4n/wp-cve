<?php use WPSocialReviews\App\Services\Helper; ?>
<<?php echo esc_attr($tag); ?> <?php Helper::printInternalString(implode(' ', $attrs)); ?>>
    <span class="wpsr-reviewer-name"><?php echo esc_html($reviewer_name); ?></span>
</<?php echo esc_attr($tag); ?>>