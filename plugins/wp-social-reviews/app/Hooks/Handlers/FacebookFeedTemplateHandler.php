<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\App\Services\Platforms\Feeds\Facebook\FacebookFeed;
use WPSocialReviews\Framework\Foundation\App;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\Helper as GlobalHelper;
use WPSocialReviews\App\Services\GlobalSettings;

class FacebookFeedTemplateHandler
{

    /**
     *
     * Render parent opening div for the template item
     *
     * @param $template_meta
     *
     * @since 3.7.0
     *
     **/
    public function renderTemplateItemWrapper($template_meta = []){
        $app = App::getInstance();

        $desktop_column = Arr::get($template_meta, 'responsive_column_number.desktop');
        $tablet_column = Arr::get($template_meta, 'responsive_column_number.tablet');
        $mobile_column = Arr::get($template_meta, 'responsive_column_number.mobile');

        $classes = 'wpsr-mb-30 wpsr-col-' . esc_attr($desktop_column) . ' wpsr-col-sm-' . esc_attr($tablet_column) . ' wpsr-col-xs-' . esc_attr($mobile_column);
        $app->view->render('public.feeds-templates.facebook.elements.item-parent-wrapper', array(
            'classes' => $classes,
        ));
    }

    public function renderFeedAuthor($feed = [], $template_meta = [])
    {
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.facebook.elements.author', array(
            'feed'          => $feed,
            'account'       => Arr::get($feed, 'from'),
            'template_meta' => $template_meta,
        ));
    }

    public function renderFeedDescription($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'post_settings.display_description') === 'false') {
            return;
        }
        $app = App::getInstance();
        $allowed_tags = GlobalHelper::allowedHtmlTags();

        $app->view->render('public.feeds-templates.facebook.elements.description', array(
            'feed'          => $feed,
            'allowed_tags'  => $allowed_tags,
            'message'       => Arr::get($feed, 'message'),
	        'content_length'    => Arr::get($template_meta, 'post_settings.content_length' , 15),
        ));
    }

    public function renderFeedMedia($feed = [], $template_meta = [])
    {
        if (Arr::get($feed, 'status_type') === 'shared_story') {
            return;
        }
        $app = App::getInstance();

        $app->view->render('public.feeds-templates.facebook.elements.media', array(
            'feed'          => $feed,
            'template_meta' => $template_meta,
        ));
    }

    public function renderFeedSummaryCard($feed = [], $template_meta = [])
    {
        if (Arr::get($feed, 'status_type') !== 'shared_story') {
            return;
        }

        if(Arr::get($feed, 'attachments.data')){
            $app = App::getInstance();
            $app->view->render('public.feeds-templates.facebook.elements.summary-card', array(
                'feed'          => $feed,
                'message'       => Arr::get($feed, 'message'),
                'template_meta' => $template_meta,
            ));
        }
    }

    public function renderFeedDate($feed = [], $template_meta = [])
    {
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.facebook.elements.date', array(
            'feed'  => $feed,
            'template_meta' => $template_meta
        ));
    }

    public function getPaginatedFeedHtml($templateId = null, $page = null , $feed_id = null , $feed_type = '')
    {
        if ($feed_type === 'album_feed') {
            return apply_filters('wpsocialreviews/facebook_feed_album_paginated_feed_html', $templateId, $page, $feed_id, $feed_type);
        } else {
            $app = App::getInstance();
            $shortcodeHandler = new ShortcodeHandler();
            $template_meta = $shortcodeHandler->templateMeta($templateId, 'facebook_feed');
            $templateNumber = Arr::get($template_meta, 'feed_settings.template');
            $feed = (new FacebookFeed())->getTemplateMeta($template_meta, $templateId);
            $settings = $shortcodeHandler->formatFeedSettings($feed);
            $pagination_settings = $shortcodeHandler->formatPaginationSettings($feed);
            $sinceId = (($page - 1) * $pagination_settings['paginate']);
            $maxId = ($sinceId + $pagination_settings['paginate']) - 1;

            $template_body_data = array(
                'templateId' => $templateId,
                'feeds' => $settings['feeds'],
                'template_meta' => $settings['feed_settings'],
                'paginate' => $pagination_settings['paginate'],
                'sinceId' => $sinceId,
                'maxId' => $maxId,
                'translations' => GlobalSettings::getTranslations()
            );

            if ($templateNumber === 'template2') {
                return apply_filters('wpsocialreviews/add_facebook_feed_template', $template_body_data);
            } else {
                return (string)$app->view->make('public.feeds-templates.facebook.template1', $template_body_data);
            }
        }
    }

    public function renderLoadMoreButton ($template_meta = null, $templateId = null, $paginate = null, $layout_type = "", $total = null, $feed_type = "", $feed = null)
    {
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.facebook.elements.load-more', array(
            'template_meta' => $template_meta,
            'templateId' => $templateId,
            'paginate' => $paginate,
            'layout_type' => $layout_type,
            'feed_type' => $feed_type,
            'feed' => $feed,
            'total' => $total
        ));
    }

    public function handleAlbumPhoto($template_id = null, $feed_id = null)
    {
        do_action('wpsocialreviews/facebook_feed_handle_album_photo');
        die();
    }
}