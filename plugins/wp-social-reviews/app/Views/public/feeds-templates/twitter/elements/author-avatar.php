<?php
    use WPSocialReviews\Framework\Support\Arr;
    if(empty(Arr::get($feed, 'user'))) return;
?>

<div class="wpsr-tweet-author-avatar <?php echo esc_attr($has_retweet); ?>">
    <a class="wpsr-tweet-author-avatar-url" target="_blank" href="<?php echo esc_url('https://twitter.com/' . Arr::get($feed, 'user.username', '')); ?>">
        <img class="wpsr-tweet-author-avatar-img-render" src="<?php echo esc_url(Arr::get($feed, 'user.profile_image_url', '')); ?>"
             alt="<?php echo esc_attr(Arr::get($feed, 'user.name', '')); ?>">
    </a>
</div>