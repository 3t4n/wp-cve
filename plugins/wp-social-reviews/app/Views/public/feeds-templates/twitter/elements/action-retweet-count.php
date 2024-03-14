<?php
    use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper;
    use WPSocialReviews\Framework\Support\Arr;
?>

<a target="_blank"
   href="<?php echo esc_url('https://twitter.com/intent/retweet?tweet_id=' . Arr::get($feed, 'id', '') . '&related=' . Arr::get($feed, 'user.username', '')); ?>"
   class="wpsr-tweet-retweet">
    <?php echo Helper::getSvgIcons('action_retweet'); ?>
    <span><?php echo esc_html($retweet_count); ?></span>
</a>