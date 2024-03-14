<?php
    use WPSocialReviews\Framework\Support\Arr;
    if(empty(Arr::get($feed, 'user'))) return;
?>

<a target="_blank"
   href="<?php echo esc_url('https://twitter.com/' . Arr::get($feed, 'user.username') . '/status/' . Arr::get($feed, 'id', '')); ?>"
   class="wpsr-tweet-time">
    <?php
    $created_at = strtotime(Arr::get($feed, 'created_at', ''));
    /* translators: %s: Human-readable time difference. */
    echo sprintf(__('%s ago'), human_time_diff($created_at));
    ?>
</a>