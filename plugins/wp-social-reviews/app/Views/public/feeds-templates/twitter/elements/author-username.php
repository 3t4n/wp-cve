<?php
    use WPSocialReviews\Framework\Support\Arr;
    if(empty(Arr::get($feed, 'user'))) return;
?>

<a target="_blank"
   href="<?php echo esc_url('https://twitter.com/' . Arr::get($feed, 'user.username', '')); ?>"
   class="wpsr-tweet-user-name">
    @<?php echo esc_html(Arr::get($feed, 'user.username', '')); ?>
</a>