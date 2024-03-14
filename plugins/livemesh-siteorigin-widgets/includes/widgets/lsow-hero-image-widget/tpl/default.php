<?php
/**
 * @var $settings
 */

$youtube_markup = '';
if ($settings['background']['bg_type'] == 'youtube') {
    $youtube_markup = ' data-property="{mute:true,autoPlay:true,opacity:1,loop:true, '
        . 'videoURL:\'' . esc_url($settings['background']['youtube_video']['youtube_url']) . '\','
        . 'quality:\'' . esc_attr($settings['background']['youtube_video']['quality']) . '\','
        . 'ratio:\'' . esc_attr($settings['background']['youtube_video']['ratio']) . '\'}"';
}

?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'] ?>

<div class="lsow-hero-header lsow-section-bg-<?php echo $settings['background']['bg_type']; ?>" <?php echo $youtube_markup; ?>>

    <?php if ($settings['background']['bg_type'] == 'youtube'): ?>

        <div id="lsow-vbg" data-vbg-mobile="false" data-vbg="<?php echo esc_url($settings['background']['youtube_video']['youtube_url']); ?>"></div>

    <?php endif; ?>

    <div class="lsow-background">

        <?php if ($settings['background']['bg_type'] == 'html5video'): ?>

            <div class="lsow-html5-video-bg">

                <video poster="<?php echo wp_get_attachment_url($settings['background']['bg_image']['image']); ?>" preload="auto"
                       autoplay loop muted>

                    <source src="<?php echo wp_get_attachment_url($settings['background']['html5_videos']['mp4_file']); ?>"
                            type="video/mp4">
                    <source src="<?php echo wp_get_attachment_url($settings['background']['html5_videos']['ogg_file']); ?>"
                            type="video/ogg">
                    <source src="<?php echo wp_get_attachment_url($settings['background']['html5_videos']['webm_file']); ?>"
                            type="video/webm">

                </video>

            </div><!-- .lsow-html5-video-bg -->

        <?php else: ?>

            <?php $attachment = wp_get_attachment_image_src(intval($settings['background']['bg_image']['image']), 'full'); ?>

            <?php if (!empty($attachment)): ?>

                <?php if ($settings['background']['bg_type'] == 'parallax'): ?>

                    <div class="lsow-parallax-bg"
                         style="background-image: url(<?php echo $attachment[0]; ?>);"></div>

                <?php elseif ($settings['background']['bg_type'] == 'cover' || $settings['background']['bg_type'] == 'youtube'): ?>

                    <div class="lsow-image-bg"
                         style="background-image: url(<?php echo $attachment[0]; ?>);"></div>

                <?php endif; ?>

            <?php endif; ?>

        <?php endif; ?>

    </div><!-- .lsow-background -->

    <?php

    $overlay = $settings['background']['overlay'];

    if (!empty($overlay['overlay_color'])) :

        $hex = $overlay['overlay_color'];
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        $overlay_opacity = $overlay['overlay_opacity'] / 100;

        $bg_color = empty($overlay['overlay_color']) ? "" : "background-color: rgba(" . "$r, $g, $b, $overlay_opacity);";
        ?>

        <div class="lsow-overlay" style="<?php echo($bg_color); ?>"></div>

    <?php

    endif;

    ?>

    <div class="lsow-header-content">

        <?php if ($settings['header_type'] == 'standard') : ?>

            <div class="lsow-standard-header">

                <?php echo empty($settings['standard_header']['subheading']) ? '' : '<div class="lsow-subheading">' . htmlspecialchars_decode($settings['standard_header']['subheading']) . '</div>'; ?>

                <?php echo empty($settings['standard_header']['heading']) ? '' : '<h3 class="lsow-heading">' . $settings['standard_header']['heading'] . '</h3>'; ?>

                <?php if (!empty($settings['standard_header']['button_url'])) : ?>

                    <a class="lsow-button lsow-trans"
                       href="<?php echo sow_esc_url($settings['standard_header']['button_url']); ?>"
                        <?php echo (empty($settings['standard_header']['new_window'])) ? '' : 'target="_blank"'; ?>><?php echo $settings['standard_header']['button_text']; ?></a>

                <?php endif; ?>

            </div>

        <?php elseif ($settings['header_type'] == 'custom'): ?>

            <div class="lsow-custom-header">

                <?php echo do_shortcode($settings['custom_header']['custom']); ?>

            </div>

        <?php endif; ?>


    </div>

    <?php if (!empty($settings['pointer_down_url'])): ?>

        <a href="<?php echo $settings['pointer_down_url']; ?>" class="icon-angle-down lsow-pointer-down"></a>

    <?php endif; ?>

</div>