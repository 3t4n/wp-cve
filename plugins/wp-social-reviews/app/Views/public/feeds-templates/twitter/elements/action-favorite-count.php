<?php
    use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper;
    use WPSocialReviews\Framework\Support\Arr;
?>

<a target="_blank"
   rel="noopener noreferrer"
   href="<?php echo esc_url('https://twitter.com/intent/like?tweet_id=' . Arr::get($feed, 'id', '') . '&related=' . Arr::get($feed, 'user.username', '')); ?>"
   class="wpsr-tweet-like">
    <?php echo Helper::getSvgIcons('action_favourite'); ?>
    <span><?php echo esc_html($favorite_count); ?></span>
</a>