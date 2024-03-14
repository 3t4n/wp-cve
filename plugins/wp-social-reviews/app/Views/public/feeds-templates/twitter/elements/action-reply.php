<?php
    use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper;
    use WPSocialReviews\Framework\Support\Arr;
?>

<a target="_blank"
   href="<?php echo esc_url('https://twitter.com/intent/tweet?in_reply_to=' . Arr::get($feed, 'id', '') . '&related=' . Arr::get($feed, 'user.username', '')); ?>"
   class="wpsr-tweet-reply">
    <?php echo Helper::getSvgIcons('action_reply'); ?>
</a>