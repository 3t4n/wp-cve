<?php 

use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper;
use WPSocialReviews\Framework\Support\Arr;

$userName = Arr::get($feed, 'retweet_user.username', '');
?>

<div class="wpsr-retweeted">
    <a target="_blank" href="<?php echo esc_url('https://twitter.com/' . $userName . '/status/' . Arr::get($feed, 'id', '')); ?>">
        <?php echo Helper::getSvgIcons('retweeted'); ?>
    </a>
    
    <a target="_blank" href="<?php echo esc_url('https://twitter.com/' . $userName); ?>" class="wpsr-tweet-author-name">
        <span><?php echo esc_html($userName); ?><?php echo __(' Retweeted', 'wp-social-reviews'); ?></span>
    </a>
</div>