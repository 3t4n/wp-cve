<?php
    use WPSocialReviews\Framework\Support\Arr;
    if(empty(Arr::get($feed, 'user'))) return;
?>

<a target="_blank"
   href="<?php echo esc_url('https://twitter.com/' . Arr::get($feed, 'user.screen_name', '')); ?>"
   class="wpsr-tweet-author-name">
    <span>
        <?php echo esc_html(Arr::get($feed, 'user.name', '')); ?>
    </span>
</a>