<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\App\Models\Review;
use WPSocialReviews\App\Services\Platforms\Feeds\Facebook\FacebookFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\Youtube\YoutubeFeed;
use WPSocialReviews\App\Services\Platforms\Reviews\Helper;
use WPSocialReviews\App\Services\Helper as GlobalHelper;
use WPSocialReviews\App\Services\Platforms\Feeds\Facebook\Helper as FacebookHelper;
use WPSocialReviews\App\Services\Platforms\Feeds\Config;
use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\TwitterFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\Instagram\InstagramFeed;
use WPSocialReviews\Framework\Foundation\App;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\GlobalSettings;

class ShortcodeHandler
{
    private $feedJson;
    private $popupSettings;
    private $additionalSettings;
    private $uniqueId = null;
    private $scripts = [];
    public $platform = '';
    public $imageSettings = false;

    public function addShortcode()
    {
        add_shortcode('wp_social_ninja', array($this, 'makeShortcode'));
        add_action('wp_enqueue_scripts', array($this, 'registerScripts'), 999);
        add_action('wp_social_ninja_add_layout_script', array($this, 'enqueueScripts'));
    }

    public function makeShortcode($args = [], $content = null, $tag = '')
    {
        $args = shortcode_atts(array(
            'id'       => '',
            'platform' => ''
        ), $args);

        if (!$args['id']) {
            return '';
        }
        if (empty($args['platform']) || empty($args['id'])) {
            return __('Please, set a template platform name or template id on your shortcode', 'wp-social-reviews');
        }

        $templateId = absint($args['id']);
        $platform = sanitize_text_field($args['platform']);

        if (!in_array($platform, GlobalHelper::shortcodeAllowedPlatforms())) {
            return __('Provided platform name is not valid.', 'wp-social-reviews');
        }

        $this->platform = $args['platform'];

        if (!did_action('wp_enqueue_scripts')) {
            $this->registerStyles();
        }

        $this->enqueueStyles([$this->platform]);

        $platformName = $args['platform'] === 'facebook_feed' ? 'Facebook' : $args['platform'];
        $methodName = str_replace('_', ucfirst($platformName), 'render_Template');

        $global_settings = get_option('wpsr_instagram_global_settings');
        $advanceSettings = (new GlobalSettings())->getGlobalSettings('advance_settings');

        $image_settings = [
            'optimized_images' => Arr::get($global_settings, 'global_settings.optimized_images', 'false'),
            'has_gdpr' => Arr::get($advanceSettings, 'has_gdpr', "false")
        ];

        $this->imageSettings = $image_settings;

        if ($platformName === 'tiktok') {
            return apply_filters('wpsocialreviews/render_tiktok_template', $templateId, $platform);
        } else {
            return $this->{$methodName}($templateId, $platform);
        }

    }

    public function templateMeta($templateId, $platform)
    {
        $encodedMeta = get_post_meta($templateId, '_wpsr_template_config', true);
        $template_meta = json_decode($encodedMeta, true);
        if (!$template_meta || empty($template_meta)) {
            return ['error_message' => __('No template is available for this shortcode!!', 'wp-social-reviews')];
        }

        $error_message = __('Please set a template platform name on your shortcode', 'wp-social-reviews');
        if ($platform === 'reviews' || $platform === 'testimonial') {
            $template_meta = Helper::formattedTemplateMeta($template_meta);
            if (empty($template_meta['platform'])) {
                return [
                    'error_message' => $error_message
                ];
            }
        } elseif ($platform === 'twitter') {
            $configs = Arr::get($template_meta, 'feed_settings', []);
            $template_meta = Config::formatTwitterConfig($configs, []);
        } elseif ($platform === 'youtube') {
            $configs = Arr::get($template_meta, 'feed_settings', []);
            $template_meta = Config::formatYoutubeConfig($configs, []);
        } elseif ($platform === 'instagram') {
            $configs = Arr::get($template_meta, 'feed_settings', []);
            $template_meta = Config::formatInstagramConfig($configs, []);
        } elseif ($platform === 'facebook_feed') {
            $configs = Arr::get($template_meta, 'feed_settings', []);
            $template_meta = Config::formatFacebookConfig($configs, []);
        } else {
            $configs = Arr::get($template_meta, 'feed_settings', []);
            $template_meta = apply_filters('wpsocialreviews/format_'. $platform .'_config', $configs, []);
        }

        if (($platform !== 'reviews' && $platform !== 'testimonial') && !Arr::get($template_meta, 'feed_settings.platform')) {
            return [
                'error_message' => $error_message
            ];
        }

        return $template_meta;
    }

    public function reviewsTemplatePath($template = '')
    {
        $templateMapping = [
            'grid1' => 'public.reviews-templates.template1',
            'grid2' => 'public.reviews-templates.template2',
            'grid3' => 'public.reviews-templates.template3',
            'grid4' => 'public.reviews-templates.template4',
            'grid5' => 'public.reviews-templates.template5',
        ];

        if (!isset($templateMapping[$template])) {
            return [
                'error_message' => __('You need to upgrade to pro to use this template!', 'wp-social-reviews')
            ];
        }

        return $templateMapping[$template];
    }

    public function renderTestimonialTemplate($templateId, $platform)
    {
        return $this->renderReviewsTemplate($templateId, $platform);
    }

    public function renderReviewsTemplate($templateId, $platform)
    {
        $app = App::getInstance();
        $template_meta = $this->templateMeta($templateId, $platform);

        if (!empty($template_meta['error_message'])) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $template_meta['error_message']);
        }

        $platforms = Arr::get($template_meta, 'platform', []);
        $badge_settings = Arr::get($template_meta, 'badge_settings', []);
        $selectedBusinesses = Arr::get($template_meta, 'selectedBusinesses', []);
        $business_info = Helper::getSelectedBusinessInfoByPlatforms($platforms, $selectedBusinesses);
        $validTemplatePlatforms = Helper::validPlatforms($platforms);
        $templateType = Arr::get($template_meta, 'templateType');


        if(in_array('facebook', $validTemplatePlatforms)) {
            $accountsLists = get_option('wpsr_reviews_facebook_settings', []);
            $connected_ids = array_keys($accountsLists);

            $account_ids = $connected_ids;
            if(!empty($account_ids)) {
                $account_ids = array_intersect($selectedBusinesses, $connected_ids);
            }

            do_action('wpsocialreviews/before_display_facebook', $account_ids);
        }

        $translations = GlobalSettings::getTranslations();

        $reviews = array();
        $totalReviews = 0;
        if (!empty($validTemplatePlatforms)) {
            $data = Review::paginatedReviews($validTemplatePlatforms, $template_meta);
            $reviews = $data['reviews'];

            if(defined('WC_PLUGIN_FILE')) {
                $reviews = Helper::trimProductTitle($reviews);
            }

            $totalReviews = $data['total_reviews'];
        }

        $template = Arr::get($template_meta, 'template', '');
        if (empty($template) || empty($reviews)) {
            $error_message = __('Sorry! We could not get any reviews', 'wp-social-reviews');
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message);
        }

        $html = '';
        if ($templateType === 'badge') {
            if (empty($business_info)) {
                return $html;
            }
            $html .= apply_filters('wpsocialreviews/add_reviews_badge_template', $templateId, $templateType,
                $business_info, $badge_settings);
            if (Arr::get($badge_settings, 'display_mode') !== 'popup') {
                return $html;
            }
        }

        if ($templateType === 'notification') {
            $html .= apply_filters('wpsocialreviews/add_reviews_notification_template', $templateId, $template_meta,
                $reviews);
            $this->enqueueNotificationScripts();
        }

        $hookType = $templateType;
        if ($hookType == 'slider') {
            $hookType = 'carousel';
        }

        do_action('wp_social_review_loading_layout_' . $hookType, $templateId, $template_meta);

        //if (Arr::get($template_meta, 'pagination_type') == 'load_more') {
        $this->enqueueScripts();
        //}

        do_action('wpsocialreviews/load_template_assets', $templateId);


        $html .= $app->view->make('public.reviews-templates.header', array(
            'template_meta' => $template_meta,
            'templateType'  => $templateType,
            'reviews'       => $reviews,
            'business_info' => $business_info,
            'templateId'    => $templateId,
            'translations'  => $translations
        ));


        $templates = ['grid1', 'grid2', 'grid3', 'grid4', 'grid5'];
        if (!in_array($template, $templates) && defined('WPSOCIALREVIEWS_PRO')) {
            $html .= apply_filters('wpsocialreviews/add_reviews_template', $template, $reviews, $template_meta);
        } else {
            $templatePath = $this->reviewsTemplatePath($template);
            if (!empty($templatePath['error_message'])) {
                return $templatePath['error_message'] . '<br/>';
            }
            $html .= $app->view->make($templatePath, array(
                'reviews' => $reviews,
                'template_meta' => $template_meta,
            ));
        }

        $html .= $app->view->make('public.reviews-templates.footer', array(
            'templateId'    => $templateId,
            'totalReviews'  => $totalReviews,
            'template_meta' => $template_meta,
            'templateType'  => $templateType,
            'reviews'       => $reviews,
            'business_info' => $business_info,
            'translations'  => $translations
        ));

        return $html;
    }

    public function renderTwitterTemplate($templateId, $platform)
    {
        if(defined('WPSOCIALREVIEWS_PRO_VERSION') && version_compare(WPSOCIALREVIEWS_PRO_VERSION, '3.10.0', '<=')){
            return 'You are using old version of WP Social Ninja Pro. Please update it to the latest version from your plugins list to ensure your feed functions properly.';
        }

        if (defined('LSCWP_V')) {
            do_action('litespeed_tag_add', 'wpsn_purge_twitter_feed');
        }

        $app = App::getInstance();
        $template_meta = $this->templateMeta($templateId, $platform);

        if (!empty($template_meta['error_message'])) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $template_meta['error_message']);
        }

        $feed = (new TwitterFeed())->getTemplateMeta($template_meta, $templateId);
        $settings = $this->formatFeedSettings($feed, $platform);

        $error_message = Arr::get($feed, 'error_message');
        if ($error_message) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message);
        }

        //pagination settings
        $pagination_settings = $this->formatPaginationSettings($feed, $platform);

        if (Arr::get($settings['feed_settings'], 'advance_settings.tweet_action_target') === 'popup') {
            $this->makePopupModal(Arr::get($settings, 'dynamic.items', []), Arr::get($settings, 'dynamic.header', []), $settings['feed_settings'], $templateId, $platform);
            do_action('wp_social_review_loading_layout_carousel', $templateId, $settings);
        }

        if (Arr::get($settings['feed_settings'], 'pagination_settings.pagination_type') != 'none') {
            $this->enqueueScripts();
        }

        $layout = Arr::get($settings, 'feed_settings.layout_type');
        if ($layout) {
            do_action('wp_social_review_loading_layout_' . $layout, $templateId, $settings);
        }

        if (Arr::get($settings, 'feed_settings.advance_settings.tweet_action_target') == 'popup') {
            $this->enqueuePopupScripts();
        }

        $template_body_data = [
            'templateId'    => $templateId,
            'feeds'         => Arr::get($settings, 'dynamic.items', []),
            'template_meta' => $settings['feed_settings'],
            'paginate'      => $pagination_settings['paginate'],
            'total'         => $pagination_settings['total'],
            'sinceId'       => $pagination_settings['sinceId'],
            'maxId'         => $pagination_settings['maxId'],
        ];

        $translations = GlobalSettings::getTranslations();

        do_action('wpsocialreviews/load_template_assets', $templateId);

        $html = '';
        $html .= $app->view->make('public.feeds-templates.twitter.header', [
            'templateId'      => $templateId,
            'header'          => Arr::get($settings, 'dynamic.header', []),
            'feed_settings'   => $settings['feed_settings'],
            'column_gaps'     => $settings['column_gaps'],
            'layout_type'     => $settings['layout_type'],
            'pagination_type' => $pagination_settings['pagination_type'],
            'translations'    => $translations
        ]);

        if ($settings['layout_type'] !== 'standard' && defined('WPSOCIALREVIEWS_PRO')) {
            $html .= apply_filters('wpsocialreviews/add_twitter_template', $template_body_data);
        } else {
            $html .= $app->view->make('public.feeds-templates.twitter.template1', $template_body_data);
        }

        $html .= $app->view->make('public.feeds-templates.twitter.footer', [
            'templateId'      => $templateId,
            'header'          => $settings['header'],
            'feed_settings'   => $settings['feed_settings'],
            'column_gaps'     => $settings['column_gaps'],
            'layout_type'     => $settings['layout_type'],
            'paginate'        => $pagination_settings['paginate'],
            'total'           => $pagination_settings['total'],
            'pagination_type' => $pagination_settings['pagination_type']
        ]);

        return $html;
    }

    public function renderYoutubeTemplate($templateId, $platform)
    {
        $app = App::getInstance();
        $template_meta = $this->templateMeta($templateId, $platform);

        if (!empty($template_meta['error_message'])) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $template_meta['error_message']);
        }

        $feed = (new YoutubeFeed())->getTemplateMeta($template_meta, $templateId);
        $feed_info = Arr::get($feed, 'feed_info', []);
        $settings = $this->formatFeedSettings($feed);


        $error_message = Arr::get($settings['dynamic'], 'error_message');
        if (Arr::get($error_message, 'error_message')) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message['error_message']);
        } elseif ($error_message) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message);
        }

        //pagination settings
        $pagination_settings = $this->formatPaginationSettings($feed);

        $template = Arr::get($settings['feed_settings'], 'template', '');
        // render template 1, template 2, template 3 from template 1
        $template = !defined('WPSOCIALREVIEWS_PRO') && ($template === 'template2' || $template === 'template3') ? 'template1' : $template;

        $layout = Arr::get($settings, 'feed_settings.layout_type');
        if ($layout) {
            do_action('wp_social_review_loading_layout_' . $layout, $templateId, $settings);
        }

        $this->enqueuePopupScripts();

        //if (Arr::get($settings['feed_settings'], 'pagination_settings.pagination_type') != 'none') {
            $this->enqueueScripts();
       // }

        $translations = GlobalSettings::getTranslations();

        do_action('wpsocialreviews/load_template_assets', $templateId);


        $html = '';
        $html .= $app->view->make('public.feeds-templates.youtube.header', array(
            'template'        => $template,
            'templateId'      => $templateId,
            'header'          => $settings['header'],
            'feeds'           => $settings['feeds'],
            'feed_settings'   => $settings['feed_settings'],
            'paginate'        => $pagination_settings['paginate'],
            'pagination_type' => $pagination_settings['pagination_type'],
            'total'           => $pagination_settings['total'],
            'translations'    => $translations
        ));
        $html .= $app->view->make('public.feeds-templates.youtube.template1', array(
            'templateId'    => $templateId,
            'feeds'         => $settings['feeds'],
            'feed_info'     => $feed_info,
            'template_meta' => $settings['feed_settings'],
            'total'         => $pagination_settings['total'],
            'sinceId'       => $pagination_settings['sinceId'],
            'maxId'         => $pagination_settings['maxId'],
        ));
        $html .= $app->view->make('public.feeds-templates.youtube.footer', array(
            'templateId'      => $templateId,
            'header'          => $settings['header'],
            'feed_settings'   => $settings['feed_settings'],
            'column_gaps'     => $settings['column_gaps'],
            'layout_type'     => $settings['layout_type'],
            'paginate'        => $pagination_settings['paginate'],
            'pagination_type' => $pagination_settings['pagination_type'],
            'total'           => $pagination_settings['total'],
        ));

        return $html;
    }

    public function renderFacebookTemplate($templateId, $platform)
    {
        if (defined('LSCWP_V')) {
            do_action('litespeed_tag_add', 'wpsn_purge_facebook_feed');
        }

        $app = App::getInstance();
        $template_meta = $this->templateMeta($templateId, $platform);

        $account_ids = Arr::get($template_meta, 'feed_settings.source_settings.selected_accounts');

        do_action('wpsocialreviews/before_display_facebook_feed', $account_ids);

        if (!empty($template_meta['error_message'])) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $template_meta['error_message']);
        }

        $feed = (new FacebookFeed())->getTemplateMeta($template_meta, $templateId);
        $settings = $this->formatFeedSettings($feed);

	    if (Arr::get($feed , 'feed_settings.source_settings.feed_type') === 'album_feed') {
		    $this->enqueueAlbumScripts();
	    }

        $error_message = Arr::get($settings['dynamic'], 'error_message');
        if (Arr::get($error_message, 'error_message')) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message['error_message']);
        } elseif ($error_message) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message);
        }

        if (sizeof(Arr::get($settings, 'feeds')) === 0) {
            $posts_not_found = __('Posts are not available!', 'wp-social-reviews');
            return apply_filters('wpsocialreviews/display_frontend_error_message', $posts_not_found);
        }

        $template = Arr::get($settings, 'feed_settings.template', '');
        $layout = Arr::get($settings, 'feed_settings.layout_type');
        do_action('wp_social_review_loading_layout_' . $layout, $templateId, $settings);

        //pagination settings
        $pagination_settings = $this->formatPaginationSettings($feed);
        $translations = GlobalSettings::getTranslations();

        if (Arr::get($settings['feed_settings'], 'post_settings.display_mode') === 'popup') {
            $feeds = $settings['feeds'];
            $hasMulti = false;
            $display_wp_date_format = Arr::get($settings, 'feed_settings.post_settings.display_wp_date_format', 'false');

            foreach ($feeds as $index => $feed) {
                $comments_text = Arr::get($translations, 'comments') ?: __('Comments', 'wp-social-reviews');
                $comment_total_count = Arr::get($feed, 'comments.summary.total_count');
                $feeds[$index]['comments_count'] = $comment_total_count > 0 ? GlobalHelper::shortNumberFormat($comment_total_count) .' '. $comments_text : '';
                $feeds[$index]['react_count'] = FacebookHelper::getTotalFeedReactions($feed);

                $feed_type = Arr::get($settings, 'feed_settings.source_settings.feed_type');
                if($feed_type === 'event_feed'){
                    $feeds[$index]['start_time'] = get_date_from_gmt(Arr::get($feed,'start_time'), 'M j, Y \a\t g:i A');
                    $feeds[$index]['end_time'] = get_date_from_gmt(Arr::get($feed,'end_time'), 'M j, Y \a\t g:i A');
                }

                $subAttachments = Arr::get($feed, 'attachments.data.0.subattachments.data');
                if (!$hasMulti && $subAttachments) {
                    $hasMulti = true;
                }
            }
            $this->makePopupModal($feeds, $settings['header'], $settings['feed_settings'], $templateId, $platform);
            $this->enqueuePopupScripts();
            if ($hasMulti) {
                do_action('wp_social_review_loading_layout_carousel', $templateId, $settings);
            }
        }

        // if(Arr::get($settings['feed_settings'], 'pagination_settings.pagination_type') != 'none') {
        $this->enqueueScripts();
        //}
        do_action('wpsocialreviews/load_template_assets', $templateId);

        $html = '';
        $template_body_data = [
            'templateId'    => $templateId,
            'feeds'         => $settings['feeds'],
            'template_meta' => $settings['feed_settings'],
            'paginate'      => $pagination_settings['paginate'],
            'sinceId'       => $pagination_settings['sinceId'],
            'maxId'         => $pagination_settings['maxId'],
            'pagination_settings' => $pagination_settings,
            'translations'  => $translations
        ];

        $html .= $app->view->make('public.feeds-templates.facebook.header', array(
            'templateId'    => $templateId,
            'template'      => $template,
            'header'        => $settings['header'],
            'feed_settings' => $settings['feed_settings'],
            'layout_type'   => $settings['layout_type'],
            'column_gaps'   => $settings['column_gaps'],
            'translations'  => $translations
        ));

        if (defined('WPSOCIALREVIEWS_PRO') && $template !== 'template1') {
            $html .= apply_filters('wpsocialreviews/add_facebook_feed_template', $template_body_data);
        } else {
            $html .= $app->view->make('public.feeds-templates.facebook.template1', $template_body_data);
        }

        $html .= $app->view->make('public.feeds-templates.facebook.footer', array(
            'templateId'      => $templateId,
            'feeds'           => $settings['feeds'],
            'feed_settings'   => $settings['feed_settings'],
            'layout_type'     => $settings['layout_type'],
            'column_gaps'     => $settings['column_gaps'],
            'paginate'        => $pagination_settings['paginate'],
            'pagination_type' => $pagination_settings['pagination_type'],
            'header'          => $settings['header'],
            'total'           => $pagination_settings['total'],
        ));

        return $html;
    }

    public function renderInstagramTemplate($templateId, $platform)
    {
        $html = '';
        if (defined('LSCWP_V')) {
            do_action('litespeed_tag_add', 'wpsn_purge_instagram');
        }

        $app = App::getInstance();
        $template_meta = $this->templateMeta($templateId, $platform);

        $account_ids = Arr::get($template_meta, 'feed_settings.source_settings.account_ids');

        do_action('wpsocialreviews/before_display_instagram_feed', $account_ids);

        if (!empty($template_meta['error_message'])) {
            return apply_filters('wpsocialreviews/display_frontend_error_message', $template_meta['error_message']);
        }

        $feed = (new InstagramFeed())->getTemplateMeta($template_meta, $templateId);
        $settings = $this->formatFeedSettings($feed);

        //template mapping
        $templateMapping = [
            'template1' => 'public.feeds-templates.instagram.template1',
            'template2' => 'public.feeds-templates.instagram.template2',
        ];
        $template = Arr::get($settings['feed_settings'], 'template', '');
        if (!isset($templateMapping[$template])) {
            $error_message = __('No templates found!! Please save and try again', 'wp-social-reviews');
            return apply_filters('wpsocialreviews/display_frontend_error_message', $error_message);
        }
        $file = $templateMapping[$template];

        $layout = Arr::get($settings, 'feed_settings.layout_type');
        do_action('wp_social_review_loading_layout_' . $layout, $templateId, $settings);

        //pagination settings
        $pagination_settings = $this->formatPaginationSettings($feed);
        $translations = GlobalSettings::getTranslations();
        $feeds = Arr::get($settings, 'dynamic.items', []);

        $hasLatestPost = Arr::get($settings, 'dynamic.has_latest_post', false);
        if (Arr::get($settings['feed_settings'], 'post_settings.display_mode') === 'popup') {
            $hasMulti = false;
            foreach ($feeds as $index => $feed) {
                /* translators: %s: Human-readable time difference. */
                $feeds[$index]['time_ago'] = sprintf(__('%s ago'), human_time_diff(strtotime($feed['timestamp'])));
                if (isset($feed['comments'])) {
                    foreach ($feed['comments'] as $commentIndex => $comment) {
                        $feeds[$index]['comments'][$commentIndex]['time_ago'] = sprintf(__('%s ago'), human_time_diff(strtotime($comment['timestamp'])));
                    }
                }

                if (!$hasMulti && isset($feed['children'])) {
                    $hasMulti = true;
                }
            }
            $this->makePopupModal($feeds, $settings['header'], $settings['feed_settings'], $templateId, $platform);
            $this->enqueuePopupScripts();
            if ($hasMulti) {
                do_action('wp_social_review_loading_layout_carousel', $templateId, $settings);
            }
        }

        //enable when gdpr is on
        $resizedImages = Arr::get($settings, 'dynamic.resize_data', []);
        if(Arr::get($this->imageSettings, 'optimized_images', 'false') === 'true' && (count($resizedImages) < count($feeds) || $hasLatestPost)) {
            wp_enqueue_script('wpsr-image-resizer');
        }

        if (Arr::get($this->imageSettings, 'optimized_images', 'false') === 'true' || Arr::get($settings['feed_settings'], 'pagination_settings.pagination_type') != 'none' || Arr::get($settings['feed_settings'], 'post_settings.display_mode') === 'popup') {
            $this->enqueueScripts();
        }

        do_action('wpsocialreviews/load_template_assets', $templateId);

        $settings = apply_filters('wpsocialreviews/get_shoppable_feeds', $settings);

        if(defined('WPSOCIALREVIEWS_PRO') && class_exists('\WPSocialReviewsPro\App\Services\Platforms\Feeds\Shoppable')){
            $settings = (new \WPSocialReviewsPro\App\Services\Platforms\Feeds\Shoppable())->makeShoppableFeeds($settings, 'instagram');
        }

        $error_message = Arr::get($settings['dynamic'], 'error_message');
        $hashtags = Arr::get($settings, 'feed_settings.source_settings.hash_tags');

        if (Arr::get($error_message, 'error_message')) {
            $html .= apply_filters('wpsocialreviews/display_frontend_error_message', $error_message['error_message'], $account_ids, $hashtags);
        } else {
            $html .= apply_filters('wpsocialreviews/display_frontend_error_message', $error_message, $account_ids, $hashtags);
        }

        $html .= $app->view->make('public.feeds-templates.instagram.header', array(
            'templateId' => $templateId,
            'template' => $template,
            'header' => $settings['header'],
            'feed_settings' => $settings['feed_settings'],
            'layout_type' => $settings['layout_type'],
            'column_gaps' => $settings['column_gaps'],
            'translations' => $translations
        ));

        if(!empty($feeds)) {
            $html .= $app->view->make($file, array(
                'templateId'    => $templateId,
                'feeds'         => $feeds,
                'template_meta' => $settings['feed_settings'],
                'sinceId'       => $pagination_settings['sinceId'],
                'maxId'         => $pagination_settings['maxId'],
            ));
        }

        $html .= $app->view->make('public.feeds-templates.instagram.footer',
            array(
                'templateId' => $templateId,
                'feeds' => $feeds,
                'feed_settings' => $settings['feed_settings'],
                'layout_type' => $settings['layout_type'],
                'column_gaps' => $settings['column_gaps'],
                'paginate' => $pagination_settings['paginate'],
                'pagination_type' => $pagination_settings['pagination_type'],
                'total' => $pagination_settings['total'],
        ));

        return $html;
    }

    public function formatFeedSettings($feed = [], $platform = '')
    {
        $feed_settings = Arr::get($feed, 'feed_settings', []);
        $filterSettings = Arr::get($feed_settings, 'filters', []);
        $dynamic = Arr::get($feed, 'dynamic', $feed);
        $feeds = Arr::get($dynamic, 'items', []);

        if ($platform === 'twitter') {
            $header = Arr::get($feed, 'header', []);
            $layout_type = Arr::get($feed_settings, 'layout_type', 'standard');
        } else {
            $header = Arr::get($dynamic, 'header', []);
            $layout_type = Arr::get($feed_settings, 'layout_type', 'grid');
        }

        $column_gaps = Arr::get($feed_settings, 'column_gaps', 'default');

        return [
            'feeds'           => $feeds,
            'header'          => $header,
            'feed_settings'   => $feed_settings,
            'filter_settings' => $filterSettings,
            'layout_type'     => $layout_type,
            'column_gaps'     => $layout_type !== 'carousel' ? $column_gaps : null,
            'dynamic'         => $dynamic,
        ];
    }

    public function formatPaginationSettings($feed = [], $platform = '')
    {
        $settings = $this->formatFeedSettings($feed);
        $sinceId = 0;
        $paginate = intval(Arr::get($settings['feed_settings'], 'pagination_settings.paginate', 6));
        $maxId = ($sinceId + $paginate) - 1;
        $totalFeed = is_array($settings['feeds']) ? count($settings['feeds']) : 0;

        $numOfFeeds = Arr::get($settings, 'filter_settings.total_posts_number');
        $totalFilterFeed = wp_is_mobile() ? Arr::get($numOfFeeds, 'mobile') : Arr::get($numOfFeeds, 'desktop');
        $total = (int)($totalFilterFeed && $totalFeed < $totalFilterFeed) ? $totalFeed : $totalFilterFeed;

        $pagination_type = Arr::get($settings['feed_settings'], 'pagination_settings.pagination_type', 'none');

        if ($settings['layout_type'] === 'carousel' || $pagination_type === 'none') {
            $maxId = $totalFeed;
        }

        return [
            'sinceId'         => $sinceId,
            'maxId'           => $maxId,
            'paginate'        => $paginate,
            'total'           => $total,
            'pagination_type' => $pagination_type,
        ];
    }

    public function loadPopupScripts()
    {
        foreach ($this->scripts as $script) {
            $platform = Arr::get($script, 'platform_name');
            $platform = $platform === 'facebook_feed' ? 'FacebookFeed' : $platform;
            $prefix = 'WPSR_';
            $frontEndJson = str_replace('_', $prefix . ucfirst($platform), '_FrontEndJson');
            $popupSettings = str_replace('_', $prefix . ucfirst($platform), '_PopupSettings');
            $additionalSettings = str_replace('_', $prefix . ucfirst($platform), '_AdditionalSettings');
            ?>
            <script type="text/javascript" id="wpsr-popup-script">
                if (!window.<?php echo esc_attr($frontEndJson); ?>) {
                    window.<?php echo esc_attr($frontEndJson); ?> = {};
                }
                if (!window.<?php echo esc_attr($popupSettings); ?>) {
                    window.<?php echo esc_attr($popupSettings); ?> = {};
                }
                if (!window.<?php echo esc_attr($additionalSettings); ?>) {
                    window.<?php echo esc_attr($additionalSettings); ?> = {};
                }

                window.<?php echo esc_attr($frontEndJson); ?>["<?php echo esc_attr($script['uniqueId']); ?>"] = <?php echo GlobalHelper::printInternalString($script['feedJson']); ?>;
                window.<?php echo esc_attr($popupSettings); ?>["<?php echo esc_attr($script['uniqueId']); ?>"] = <?php echo GlobalHelper::printInternalString($script['popupSettings']); ?>;
                window.<?php echo esc_attr($additionalSettings); ?>["<?php echo esc_attr($script['uniqueId']); ?>"] = <?php echo GlobalHelper::printInternalString($script['additionalSettings']); ?>;
            </script>
            <?php
        }
    }

    public function makePopupModal($feeds = [], $header = [], $feed_settings = [], $templateId = null, $platform = '')
    {
        $popupSettings = Arr::get($feed_settings, 'popup_settings', []);
        $headerSettings = Arr::get($feed_settings, 'header_settings', []);

        //set all data, settings for popup
        $additionalSettings = array(
            'header_settings' => $headerSettings
        );

        if ($platform === 'instagram') {
            $additionalSettings['assets_url'] = WPSOCIALREVIEWS_URL . 'assets';
            $additionalSettings['user_avatar'] = Arr::get($header, 'user_avatar');
            $additionalSettings['feed_type'] = Arr::get($feed_settings, 'source_settings.feed_type');
            $additionalSettings['hash_tags'] = Arr::get($feed_settings, 'source_settings.hash_tags');
        }

        if ($platform === 'facebook_feed' || $platform === 'tiktok') {
            $additionalSettings['assets_url'] = WPSOCIALREVIEWS_URL . 'assets';
            $additionalSettings['feed_type'] = Arr::get($feed_settings, 'source_settings.feed_type');
        }

        $this->feedJson = json_encode($feeds);
        $this->popupSettings = json_encode($popupSettings);
        $this->additionalSettings = json_encode($additionalSettings);
        $this->uniqueId = $templateId;
        $this->scripts[] = $this->setPopupModalData($platform);
        add_action('wp_footer', array($this, 'loadPopupScripts'), 99);
    }

    public function setPopupModalData($platform)
    {
        return [
            'platform_name'      => $platform,
            'uniqueId'           => intval($this->uniqueId),
            'feedJson'           => $this->feedJson,
            'popupSettings'      => $this->popupSettings,
            'additionalSettings' => $this->additionalSettings,
        ];
    }

    /**
     *  Enqueue All Front-End Assets
     *
     * @param
     */
    public function registerScripts()
    {
        wp_register_script('wpsr-image-resizer', WPSOCIALREVIEWS_URL . 'assets/js/image_resizer.js',
            array('jquery'), WPSOCIALREVIEWS_VERSION, true);

        wp_register_script('wp-social-review', WPSOCIALREVIEWS_URL . 'assets/js/wp-social-review.js',
            array('jquery'), WPSOCIALREVIEWS_VERSION, true);

        wp_register_script('social-ninja-modal', WPSOCIALREVIEWS_URL . 'assets/js/social-ninja-modal.js',
            array('jquery'), WPSOCIALREVIEWS_VERSION, true);

        wp_register_script('wpsn-notification', WPSOCIALREVIEWS_URL . 'assets/js/wpsn-notification.js',
            array('jquery'), WPSOCIALREVIEWS_VERSION, true);

	    wp_register_script('wp-social-reviews_album_js', WPSOCIALREVIEWS_URL . 'assets/js/wpsr-fb-album.js',
		    array('jquery'), WPSOCIALREVIEWS_VERSION, true);


        $this->registerStyles();

        global $post;
        if ((is_a($post, 'WP_Post') && $shortcodeIds = get_post_meta($post->ID, '_wpsn_ids', true))) {
            $this->enqueueStyles($shortcodeIds);
        }
    }

    public function registerStyles()
    {
        wp_register_style(
            'wp_social_ninja_reviews',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_reviews.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );

        wp_register_style(
            'wp_social_ninja_testimonial',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_testimonial.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );

        wp_register_style(
            'wp_social_ninja_ig',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_ig.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );

        wp_register_style(
            'wp_social_ninja_fb',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_fb.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );

        wp_register_style(
            'wp_social_ninja_tw',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_tw.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );

        wp_register_style(
            'wp_social_ninja_yt',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_yt.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );

        wp_register_style(
            'wp_social_ninja_tt',
            WPSOCIALREVIEWS_URL . 'assets/css/wp_social_ninja_tt.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );
    }

    public function enqueueStyles($platformNames = [])
    {
        if (!$platformNames) {
            return false;
        }
        $maps = [
            'twitter'       => 'tw',
            'youtube'       => 'yt',
            'instagram'     => 'ig',
            'facebook_feed' => 'fb',
            'tiktok'        => 'tt',
            'reviews'       => 'reviews',
            'testimonial'       => 'testimonial'
        ];

        $styles = [];
        foreach ($platformNames as $platform) {
            if (isset($maps[$platform])) {
                $styles[$maps[$platform]] = $maps[$platform];
            } else {
                $styles['reviews'] = 'reviews';
            }
        }

        if (!$styles) {
            return false;
        }

        $styles = array_keys($styles);

        foreach ($styles as $style) {
            wp_enqueue_style('wp_social_ninja_' . $style);
        }
    }

    public function loadLocalizeScripts()
    {
        static $jsLoaded;

        if ($jsLoaded) {
            return;
        }

        $upload     = wp_upload_dir();
        $upload_url = trailingslashit($upload['baseurl']) . WPSOCIALREVIEWS_UPLOAD_DIR_NAME;
        $translations = GlobalSettings::getTranslations();

        $params = apply_filters('wpsocialreviews/frontend_vars', array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'wpsr_nonce' => wp_create_nonce('wpsr-ajax-nonce'),
            'has_pro'    => defined('WPSOCIALREVIEWS_PRO'),
            'is_custom_feed_for_tiktok_activated'   => defined('CUSTOM_FEED_FOR_TIKTOK'),
            'read_more'  => Arr::get($translations, 'read_more') ?: __('Read more', 'wp-social-reviews'),
            'read_less'  => Arr::get($translations, 'read_less') ?: __('Read less', 'wp-social-reviews'),
            'view_on_fb' => Arr::get($translations, 'view_on_fb') ?: __('View on Facebook', 'wp-social-reviews'),
            'people_responded' => Arr::get($translations, 'people_responded') ?: __( 'People Responded', 'wp-social-reviews' ),
            'online_event' => Arr::get($translations, 'online_event') ?: __( 'Online Event', 'wp-social-reviews' ),
            'view_on_ig' => Arr::get($translations, 'view_on_ig') ?: __( 'View on Instagram', 'wp-social-reviews' ),
            'view_on_tiktok' => Arr::get($translations, 'view_on_tiktok') ?: __( 'View on TikTok', 'wp-social-reviews' ),
            'likes'      => Arr::get($translations, 'likes') ?: __( 'likes', 'wp-social-reviews' ),
            'interested' => Arr::get($translations, 'interested') ?: __( 'interested', 'wp-social-reviews' ),
            'going'      => Arr::get($translations, 'going') ?: __( 'going', 'wp-social-reviews' ),
            'went'       => Arr::get($translations, 'went') ?: __( 'went', 'wp-social-reviews' ),
            'plugin_url' => WPSOCIALREVIEWS_URL,
            'image_settings'   => $this->imageSettings,
            'upload_url' => $upload_url,
            'a11y'       => [
                    'prevSlideMessage' => __('Previous slide', 'wp-social-reviews'),
                    'nextSlideMessage' => __('Next slide', 'wp-social-reviews'),
                    'firstSlideMessage' => __('This is the first slide', 'wp-social-reviews'),
                    'lastSlideMessage' => __('This is the last slide', 'wp-social-reviews'),
                    'paginationBulletMessage' => sprintf(__('Go to slide %s', 'wp-social-reviews'), '{{index}}'),
            ]
        ));
        ?>
        <script type="text/javascript" id="wpsr-localize-script">
            window.wpsr_ajax_params = <?php echo json_encode($params); ?>;
        </script>
        <?php
        $jsLoaded = true;
    }

    public function enqueueScripts()
    {
        static $jsLoaded;

        if ($jsLoaded) {
            return;
        }

        wp_enqueue_script('wp-social-review');
        add_action('wp_footer', array($this, 'loadLocalizeScripts'), 99);
        $jsLoaded = true;
    }

    public function enqueueNotificationScripts()
    {
        wp_enqueue_script('wpsn-notification');
    }

    public function localizePopupScripts()
    {
        static $jsLoaded;

        if ($jsLoaded) {
            return;
        }
        $params = array(
            'ajax_url' => admin_url('admin-ajax.php'),
        );
        ?>
        <script type="text/javascript" id="wpsr-localize-popup-script">
            window.wpsr_popup_params = <?php echo json_encode($params); ?>;
        </script>
        <?php
        $jsLoaded = true;
    }

    public function enqueuePopupScripts()
    {
        wp_enqueue_script('social-ninja-modal');
        add_action('wp_footer', array($this, 'localizePopupScripts'), 99);
    }

    public function enqueueAlbumScripts()
    {
        wp_enqueue_script('wp-social-reviews_album_js');
    }

    public function handleLoadMoreAjax()
    {
        $templateId = absint(Arr::get($_REQUEST, 'template_id'));
        $platform = sanitize_text_field(Arr::get($_REQUEST, 'platform'));
        $page = absint(Arr::get($_REQUEST, 'page', 2));
        $feed_type = sanitize_text_field(Arr::get($_REQUEST, 'feed_type' , ''));
        $feed_id = sanitize_text_field(Arr::get($_REQUEST, 'feed_id' , null));

        if ($platform == 'youtube') {
            $content = (new YoutubeTemplateHandler())->getPaginatedFeedHtml($templateId, $page);
        } else if ($platform == 'twitter') {
            $content = (new TwitterTemplateHandler())->getPaginatedFeedHtml($templateId, $page);
        } else if ($platform == 'facebook_feed') {
            $content = (new FacebookFeedTemplateHandler())->getPaginatedFeedHtml($templateId, $page, $feed_id, $feed_type);
        } else if ($platform === 'tiktok') {
            $content = apply_filters('wpsocialreviews/get_paginated_feed_html', $templateId, $page);
        }else if ($platform == 'reviews') {
            $content = (new ReviewsTemplateHandler())->getPaginatedFeedHtml($templateId, $page);
        } else {
            $content = apply_filters('wpsr_feed_items_by_page_' . $platform, '', $templateId, $page);
        }

        wp_send_json([
            'content' => $content
        ]);
        die();
    }
}
