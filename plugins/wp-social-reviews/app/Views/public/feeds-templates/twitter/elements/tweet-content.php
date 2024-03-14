<?php
    use WPSocialReviews\App\Services\Helper;
    use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper as TwitterHelper;
    use WPSocialReviews\Framework\Support\Arr;

    $medias = Arr::get($feed, 'media', []);
    $externalLinks = Arr::get($feed, 'entities.urls', []);
    $extraTweets = Arr::get($feed, 'extra_tweets', []);

    $totalMedia = count($medias);
    $mid = (int)($totalMedia / 2);
?>

<div class="wpsr-tweet-content" <?php Helper::printInternalString($twitter_card_data_attrs);?>>
    <!--    tweet text-->
    <?php if (isset($template_meta['advance_settings']) && $template_meta['advance_settings']['tweet_text'] === 'true') { ?>
        <p class="wpsr-tweet-text">
            <?php echo TwitterHelper::replaceTweetUrls($feed); ?>
        </p>
    <?php } ?>

    <!--    media-->
    <?php if(!empty($medias)) {?>
        <div  class="<?php echo esc_attr(implode(' ', $classes)); ?>">
            <?php if ($totalMedia > 1) { ?>
                <div class="wpsr-media-box-one wpsr-media-item-<?php echo esc_attr($mid); ?>">
                    <?php  for ($idx = 0; $idx <  $mid; $idx++) {
                        $media = Arr::get($medias, $idx, []);
                        $mediaType = Arr::get($media, 'type', '');
                        if ($mediaType === 'video' || $mediaType === 'animated_gif') {
                            do_action('wpsocialreviews/tweet_video', $feed, $media, $template_meta, $templateId, $index);
                        } else if($mediaType === 'photo') {?>
                            <?php do_action('wpsocialreviews/tweet_image', $feed, $media, $template_meta, $templateId, $index); ?>
                        <?php }
                    } ?>
                </div>
            <?php } ?>

            <div class="wpsr-media-box-two wpsr-media-item-<?php echo esc_attr($totalMedia - $mid); ?>">
                <?php for ($idx = $mid; $idx <  $totalMedia; $idx++) {
                    $media = Arr::get($medias, $idx, []);
                    $mediaType = Arr::get($media, 'type', '');
                    if ($mediaType === 'video' || $mediaType === 'animated_gif') {
                        do_action('wpsocialreviews/tweet_video', $feed, $media, $template_meta, $templateId, $index);
                    } else if($mediaType === 'photo') {?>
                        <?php do_action('wpsocialreviews/tweet_image', $feed, $media, $template_meta, $templateId, $index); ?>
                    <?php }
                } ?>
            </div>
        </div>
    <?php } ?>

    <!--   external links-->
    <?php if(!empty($externalLinks)) {?>
        <div  class="<?php echo esc_attr(implode(' ', $classes)); ?>">
            <?php foreach($externalLinks as $linkInfo) {
                do_action('wpsocialreviews/tweet_external_link', $feed, $linkInfo, $template_meta, $templateId, $index);
            }?>
        </div>
    <?php } ?>

    <!--    extra tweets(quoted maybe) -->
    <?php foreach ($extraTweets as $extraTweet) {
        do_action('wpsocialreviews/tweet_quoted_status', $extraTweet, $template_meta, $templateId, $index);
    }?>
</div>
