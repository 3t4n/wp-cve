<?php
	if (! defined('ABSPATH'))
		exit;

/**
 * @var string $star_output
 * @var string $google_svg
 * @var number $slide_duration
 * @var array  $allowed_html
 * @var array  $review
 */
?>

<div class="swiper-slide" data-swiper-autoplay="<?php echo esc_attr($slide_duration); ?>">
    <div class="g-review">
        <div class="gr-inner-header">
            <img
                    class="gr-profile"
                    src="<?php echo esc_attr($review['profile_photo_url']); ?>"
                    width="50"
                    height="50"
                    alt=""
                    data-imgtype="image/png"
                    referrerpolicy="no-referrer"
            />
            <img
                    src="<?php echo esc_attr($google_svg); ?>"
                    alt=""
                    class="gr-google"
            />
            <p>
                <a href="<?php echo esc_attr($review['author_url']); ?>"
                   target="_blank">
                    <?php echo esc_html($review['name']); ?>
                </a>
                <br>
                <span class="gr-stars">
                    <?php echo wp_kses($star_output, $this->allowed_html); ?>
                </span>
            </p>
        </div>

        <div class="gr-inner-body">
            <p><?php echo esc_html($review['text']); ?></p>
        </div>
    </div>
</div>


