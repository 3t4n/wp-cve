<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\TwitterFeed;
use WPSocialReviews\Framework\Foundation\App;
use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Helper as TwitterHelper;
use WPSocialReviews\App\Services\Helper as GlobalHelper;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\GlobalSettings;

class TwitterTemplateHandler
{
    /**
     *
     * Render Author Info HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetAuthorNameHtml($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['author_name'] === 'false') {
            return;
        }
        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.author-name', array(
            'feed' => $feed
        ));
    }

    /**
     *
     * Render Author Verified Icon HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetAuthorVerifiedIconHtml($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['author_name'] === 'false') {
            return;
        }

        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $app = App::getInstance();
        if (Arr::get($feed, 'user.verified')) {
            $app->view->render('public.feeds-templates.twitter.elements.verified-icon', array(
                'feed' => $feed
            ));
        }
    }

    /**
     *
     * Render Author Username HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetAuthorUsernameHtml($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['user_name'] === 'false') {
            return;
        }

        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.author-username', [
            'feed' => $feed
        ]);
    }

    /**
     *
     * Render Tweet time HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetTimeHtml($feed = [], $template_meta = [])
    {
        $translations =  GlobalSettings::getTranslations();

        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['date'] === 'false') {
            return;
        }

        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.tweet-time', array(
            'feed' => $feed,
            'translations' => $translations
        ));
    }

    /**
     *
     * Render Author Avatar HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetAuthorAvatarHtml($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta,
                'advance_settings') && $template_meta['advance_settings']['avatar_image'] === 'false') {
            return;
        }

        $has_retweet = isset($feed['retweeted_status']) ? 'wpsr-has-retweet' : '';
        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.author-avatar', array(
            'feed'        => $feed,
            'has_retweet' => $has_retweet,
        ));
    }

    /**
     *
     * Render Tweet Retweeted Status
     *
     * @param $feed
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetRetweetedStatusHtml($feed = [])
    {
        $retweeted_status = Arr::get($feed, 'type', '');
        $app = App::getInstance();
        if ($retweeted_status === 'retweeted') {
            $app->view->render('public.feeds-templates.twitter.elements.retweeted-status', array(
                'feed' => $feed
            ));
        }
    }

    /**
     *
     * Render Tweet Content HTML
     *
     * @param $feed
     * @param $template_meta
     * @param $templateId
     * @param $index
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetContentHtml($feed = [], $template_meta = [], $templateId = null, $index = null)
    {
//        $media_type = 'photo';//TwitterHelper::getMediaType($feed);
//        $has_external_media = TwitterHelper::hasExternalMedia($feed);
//        $external_platform = TwitterHelper::externalPlatformName($linkInfo);

        $medias = Arr::get($feed, 'media', []);
        $externalLinks = Arr::get($feed, 'entities.urls', []);

        $media_type = '';
        if(!empty($medias)) {
            foreach ($medias as $media) {
                $media_type = Arr::get($media, 'type');
                break;
            }
        }

        $externalPlatform = '';
        if(!empty($externalLinks)) {
            foreach($externalLinks as $linkInfo) {
                $externalPlatform = TwitterHelper::externalPlatformName($linkInfo);
            }
        }

        $classes = [];
        $classes[] = $media_type === 'video' ? 'wpsr-feed-video' : '';
        $classes[] = $media_type === 'photo' ? 'wpsr-feed-photo' : '';
        $classes[] = $media_type === 'animated_gif' && $template_meta['advance_settings']['show_tweet_gif'] === 'true' ? 'wpsr-feed-gif' : '';
        $classes[] = !empty($externalPlatform) ? 'wpsr-feed-iframe' : '';

        $twitter_card_data_attrs = '';
        if (Arr::get($template_meta, 'advance_settings') && (($template_meta['advance_settings']['show_twitter_card'] === 'true'))) {
            $card_attrs = TwitterHelper::generateCardAttrs($feed, $media_type);
            $classes[] = Arr::get($card_attrs, 'classes');
            $classes['classes'] = $template_meta['advance_settings']['show_card_for_third_party_url'] === 'true' ? 'wpsr-active-video-card' : '';
            $twitter_card_data_attrs = Arr::get($card_attrs, 'card_url');
        }

        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.tweet-content', array(
            'feed'                    => $feed,
            'template_meta'           => $template_meta,
            'twitter_card_data_attrs' => $twitter_card_data_attrs,
            'classes'                 => $classes,
            'media_type'              => $media_type,
            'has_external_media'      => false, //$has_external_media,
            'templateId'              => $templateId,
            'index'                   => $index
        ));
    }

    public function renderQuotedTweetStatusHtml($feed = [], $template_meta = [], $templateId = null, $index = null)
    {
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.quoted-tweet', [
            'feed'          => $feed,
            'template_meta' => $template_meta,
            'templateId'    => $templateId,
            'index'         => $index,
        ]);
    }

    /**
     *
     * Render Tweet Image HTML
     *
     * @param $feed
     * @param $template_meta
     * @param $templateId
     * @param $index
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetImageHtml($feed = [], $media = [], $template_meta = [], $templateId = null, $index = null)
    {
        $advanced_settings = Arr::get($template_meta, 'advance_settings', '');
        $tweet_action_target = Arr::get($advanced_settings, 'tweet_action_target', '');

        if (isset($advanced_settings['show_tweet_image']) && $advanced_settings['show_tweet_image'] === 'false') {
            return;
        }

        $media_url = Arr::get($media, 'url');
        if (!empty($media_url)) {
            $app = App::getInstance();
            $app->view->render('public.feeds-templates.twitter.elements.tweet-image', [
                'feed'                   => $feed,
                'tweet_action_target'    => $tweet_action_target,
                'media_url'              => $media_url,
                'templateId'             => $templateId,
                'index'                  => $index,
            ]);
        }
    }

    /**
     *
     * Render Tweet Video HTML
     *
     * @param $feed
     * @param $template_meta
     * @param $templateId
     * @param $index
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetVideoHtml($feed = [], $media  = [], $template_meta = [], $templateId = null, $index = null)
    {
        $advanced_settings = Arr::get($template_meta, 'advance_settings', '');
        $tweet_action_target = Arr::get($advanced_settings, 'tweet_action_target', '');
        $show_tweet_video = Arr::get($advanced_settings, 'show_tweet_video', '');
        $preview_image = Arr::get($media, 'preview_image_url', '');
        $media_type = Arr::get($media, 'type', '');

        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.tweet-video', [
            'media'                  => $media,
            'feed'                   => $feed,
            'advanced_settings'      => $advanced_settings,
            'tweet_action_target'    => $tweet_action_target,
            'show_tweet_video'       => $show_tweet_video,
            'preview_image'          => $preview_image,
            'media_type'             => $media_type,
            'templateId'             => $templateId,
            'index'                  => $index
        ]);
    }

    public function renderTweeterExternalLinkHtml($feed = [], $linkInfo = [], $template_meta = [], $templateId = [], $index = null)
    {
        $advanced_settings = Arr::get($template_meta, 'advance_settings', '');
        $tweet_action_target = Arr::get($advanced_settings, 'tweet_action_target', '');
        $show_tweet_video = Arr::get($advanced_settings, 'show_tweet_video', '');

        $external_platform = TwitterHelper::externalPlatformName($linkInfo);
        if (empty($external_platform)) { //|| Arr::get($template_meta, 'advance_settings.show_card_for_third_party_url') === 'false') {
            return;
        }

        $external_media_url = Arr::get($linkInfo, 'expanded_url', '');
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.tweet-external-link', [
            'feed'                   => $feed,
            'advanced_settings'      => $advanced_settings,
            'tweet_action_target'    => $tweet_action_target,
            'show_tweet_video'       => $show_tweet_video,
            'external_platform'      => $external_platform,
            'external_media_url'     => $external_media_url,
            'templateId'             => $templateId,
            'index'                  => $index
        ]);
    }

    /**
     *
     * Render Tweet Action Reply HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetActionReply($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['show_reply_action'] === 'false') {
            return;
        }

        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.action-reply', array(
            'feed' => $feed
        ));
    }

    /**
     *
     * Render Tweet Action Retweet Count HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetActionRetweetCount($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['show_retweet_action'] === 'false') {
            return;
        }

        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $retweet_count = GlobalHelper::shortNumberFormat(Arr::get($feed, 'statistics.retweet_count'));
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.action-retweet-count', [
            'feed'          => $feed,
            'retweet_count' => $retweet_count,
        ]);
    }

    /**
     *
     * Render Tweet Action Favorite Count HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.2.4
     *
     **/
    public function renderTweetActionFavoriteCount($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'advance_settings') && $template_meta['advance_settings']['show_like_action'] === 'false') {
            return;
        }

        $feed = Arr::get($feed, 'retweeted_status', $feed);
        $favorite_count = GlobalHelper::shortNumberFormat(Arr::get($feed, 'statistics.like_count'));
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.twitter.elements.action-favorite-count', [
            'feed'           => $feed,
            'favorite_count' => $favorite_count
        ]);
    }

    /**
     *
     * Retrieve twitter load more data
     *
     * @since 1.2.4
     *
     **/
    public function getPaginatedFeedHtml($templateId, $page)
    {
        $app = App::getInstance();
        $shortcodeHandler = new ShortcodeHandler();

        $template_meta = $shortcodeHandler->templateMeta($templateId, 'twitter');
        $feed = (new TwitterFeed())->getTemplateMeta($template_meta, $templateId);
        $settings = $shortcodeHandler->formatFeedSettings($feed, 'twitter');
        $pagination_settings = $shortcodeHandler->formatPaginationSettings($feed, 'twitter');
        $sinceId = (($page - 1) * $pagination_settings['paginate']);
        $maxId = ($sinceId + $pagination_settings['paginate']) - 1;

        $data = [
            'templateId'    => $templateId,
            'feeds'         => Arr::get($settings, 'dynamic.items', []),
            'template_meta' => $settings['feed_settings'],
            'paginate'      => $pagination_settings['paginate'],
            'total'         => $pagination_settings['total'],
            'sinceId'       => $sinceId,
            'maxId'         => $maxId,
        ];

        if ($settings['layout_type'] !== 'standard' && defined('WPSOCIALREVIEWS_PRO')) {
            return (string)apply_filters('wpsocialreviews/add_twitter_template', $data);
        }

        return (string)$app->view->make('public.feeds-templates.twitter.template1', $data);
    }
}