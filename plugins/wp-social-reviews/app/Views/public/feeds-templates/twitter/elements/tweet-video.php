<?php

use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper as TwitterHelper;
use WPSocialReviews\Framework\Support\Arr;

$video_url = TwitterHelper::getHighQualityVideo($media);
?>

<div class="wpsr-tweet-media">
    <a href="<?php echo esc_url($video_url); ?>" class="wpsr-twitter-playmode" target="_blank"
       data-index="<?php echo esc_attr($index); ?>" data-playmode="<?php echo esc_attr('popup'); ?>"
       data-template-id="<?php echo esc_attr($templateId); ?>"
       data-video="<?php echo esc_url($video_url); ?>">
        <?php  if(Arr::get($media, 'type') === 'video' && Arr::get($advanced_settings, 'show_tweet_video') === 'true') {?>
            <img src="<?php echo esc_url(Arr::get($media, 'preview_image_url', '')); ?>" alt="No Img">
            <?php echo TwitterHelper::getSvgIcons('video_player'); ?>
        <?php } ?>

        <?php  if(Arr::get($media, 'type') === 'animated_gif' && Arr::get($advanced_settings, 'show_tweet_video') === 'true') {?>
            <video ass="wpsr-tweet-media-video-render" muted="muted" loop="loop" autoplay="autoplay" poster="<?php echo esc_url($preview_image); ?>" width="100%;">
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            </video>
        <?php } ?>
    </a>
</div>
