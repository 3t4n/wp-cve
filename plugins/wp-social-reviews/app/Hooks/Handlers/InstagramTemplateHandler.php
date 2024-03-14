<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\Framework\Foundation\App;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\app\Services\Platforms\ImageOptimizationHandler;

class InstagramTemplateHandler
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
        $template = Arr::get($template_meta, 'template') === 'template2' ? 'wpsr-mb-30 ' : '';

        $classes = esc_attr($template) . 'wpsr-col-' . esc_attr($desktop_column) . ' wpsr-col-sm-' . esc_attr($tablet_column) . ' wpsr-col-xs-' . esc_attr($mobile_column);
        $app->view->render('public.feeds-templates.instagram.elements.item-parent-wrapper', array(
            'classes' => $classes,
        ));
    }

    /**
     *
     * Render Instagram Post Media HTML
     *
     * @param $feed
     *
     * @since 1.3.0
     *
     **/
    public function renderPostMedia($feed = [], $template_meta = [], $index = null)
    {
        $media_type = Arr::get($feed, 'media_type', '');
        $thumbnail_url = ($media_type === 'VIDEO') ? Arr::get($feed, 'thumbnail_url', '') : '';
        $media_url = Arr::get($feed, 'media_url', '');

        $app = App::getInstance();
        $app->view->render('public.feeds-templates.instagram.elements.media', [
            'feed'          => $feed,
            'template_meta' => $template_meta,
            'media_type'    => $media_type,
            'media_url'     => $media_url,
            'thumbnail_url' => $thumbnail_url,
            'media_name'    => Arr::get($feed, 'media_name', ''),
            'placeholder_img_class' => (!str_contains($media_url, 'placeholder') ? 'wpsr-show' : 'wpsr-hide'),
            'index'         => $index
        ]);
    }

    /**
     *
     * Render Instagram Post Media HTML
     *
     * @param $feed
     * @param $template_meta
     *
     * @since 1.3.0
     *
     **/
    public function renderPostCaption($feed = [], $template_meta = [])
    {
        if (Arr::get($template_meta, 'post_settings.display_caption') === 'false') {
            return false;
        }

        $trim_words_count = isset($template_meta['post_settings']['trim_caption_words']) && $template_meta['post_settings']['trim_caption_words'] > 0 ? $template_meta['post_settings']['trim_caption_words'] : 0;
        if (isset($feed['caption']) && $trim_words_count) {
            $caption = apply_filters('wpsocialreviews/instagram_trim_caption_words', $feed['caption'],
                $trim_words_count);
        } else {
            $caption = isset($feed['caption']) ? $feed['caption'] : '';
        }

        $app = App::getInstance();
        $app->view->render('public.feeds-templates.instagram.elements.caption', array(
            'feed'          => $feed,
            'template_meta' => $template_meta,
            'caption'       => $caption
        ));
    }

    public function renderIcon()
    {
        $app = App::getInstance();
        $app->view->render('public.feeds-templates.instagram.elements.icon');
    }

    public function renderUserAvatar($header = [], $header_settings = [])
    {
        $userName = Arr::get($header, 'username');
        if (Arr::get($header_settings, 'display_avatar') !== 'true' || empty($userName)) {
            return;
        }

        $profileUrl = 'https://www.instagram.com/' . $userName;
        $avatarUrl = $this->getUserAvatar($header, $header_settings);

        $app = App::getInstance();
        $app->view->render('public.feeds-templates.instagram.elements.avatar', [
            'profile_url' => $profileUrl,
            'avatar_url'  => $avatarUrl,
            'account_id'  => Arr::get($header, 'account_id')
        ]);
    }

    public function getUserAvatar($header, $header_settings)
    {
        $customAvatar = Arr::get($header_settings, 'custom_profile_photo');
        if (!empty($customAvatar)) {
            return $customAvatar;
        }

        $userAvatar = Arr::get($header, 'user_avatar');
        $globalSettings = get_option('wpsr_instagram_global_settings');
        if (Arr::get($globalSettings, 'global_settings.optimized_images') === 'false' && !empty($userAvatar)) {
            return $userAvatar;
        }

        $localAvatar = Arr::get($header, 'local_avatar');
        if(!empty($localAvatar) && Arr::get($globalSettings, 'global_settings.optimized_images') === 'true') {
            return $localAvatar;
        }

        if (empty($localAvatar) && Arr::get($globalSettings, 'global_settings.optimized_images') === 'true') {
            //download file
            $avatar = Arr::get($header, 'user_avatar');
            $accountId = Arr::get($header, 'account_id');

            if(!empty($avatar)) {
                $imageOptimizationObj =  new ImageOptimizationHandler();
                $created = $imageOptimizationObj->createLocalAvatar($accountId, $avatar);
                $imageOptimizationObj->updateLocalAvatarStatus($accountId, $created);

                if ($created) {
                    return $imageOptimizationObj->getLocalAvatarUrl($accountId);
                }
            }
        }

        return WPSOCIALREVIEWS_URL . 'assets/images/template/review-template/placeholder-image.png';
    }
}