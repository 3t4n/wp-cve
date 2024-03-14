<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds\Instagram;

use WPSocialReviews\Framework\Support\Arr;

class MediaManager {
    protected $resized_image_ids = [];
    protected $image_settings = [];
    protected $imageSize = 'full';

    public function __construct($resizedImages, $imageSettings, $imageSize)
    {
        $this->resized_image_ids = $resizedImages;
        $this->image_settings = $imageSettings;
        $this->imageSize = $imageSize;
    }

    public function getMediaUri($post)
    {
        $media_id = Arr::get($post, 'id');
        if(Arr::get($this->image_settings, 'optimized_images') === 'true' || Arr::get($this->image_settings, 'has_gdpr') === "true") {
            if(in_array($media_id, $this->resized_image_ids)) {
                return $this->getLocaImageUri($post);
            }

            return $this->getPlaceholderUri();
        }

        return $this->getInstagramRemoteUri($post);
    }

    public function getLocaImageUri($post)
    {
        $user_name = Arr::get($post, 'username');
        $media_id = Arr::get($post, 'id');
        $upload     = wp_upload_dir();
        $upload_url = trailingslashit($upload['baseurl']) . trailingslashit(WPSOCIALREVIEWS_UPLOAD_DIR_NAME);

        $image_path = 'instagram/' .  $user_name . '/' . $media_id . '_' . $this->imageSize . '.jpg';
        $upload_dir = trailingslashit($upload['basedir']) . trailingslashit(WPSOCIALREVIEWS_UPLOAD_DIR_NAME);
        if(file_exists($upload_dir.$image_path)) {
            return $upload_url . $image_path;
        } else {
            return $this->getAnotherSizedImage($upload_dir, $upload_url, $user_name, $media_id);
        }
    }

    public function getImage($upload_dir, $upload_url, $user_name, $media_id, $size)
    {
        $image_path = 'instagram/' .  $user_name . '/' . $media_id . '_'. $size .'.jpg';
        if(file_exists($upload_dir.$image_path)) {
            return $upload_url . $image_path;
        }

        return false;
    }

    public function getAnotherSizedImage($upload_dir, $upload_url, $user_name, $media_id)
    {
        if($this->imageSize !== 'thumb') {
            $currentImage = $this->getImage($upload_dir, $upload_url, $user_name, $media_id, 'thumb');
            if($currentImage) {
                return $currentImage;
            }
        }

        if($this->imageSize !== 'low') {
            $currentImage = $this->getImage($upload_dir, $upload_url, $user_name, $media_id, 'low');
            if($currentImage) {
                return $currentImage;
            }
        }

        if($this->imageSize !== 'full') {
            $currentImage = $this->getImage($upload_dir, $upload_url, $user_name, $media_id, 'full');
            if($currentImage) {
                return $currentImage;
            }
        }
    }

    public function getMediaType($post)
    {
        //if gdpr or optimize images are on we have to return IMAGE as video will not be store locally.
        if(Arr::get($this->image_settings, 'optimized_images') === 'true' || Arr::get($this->image_settings, 'has_gdpr') === "true") {
            return 'IMAGE';
        }

        if(Arr::get($post, 'media_type', false) === 'CAROUSEL_ALBUM') {
            return Arr::get($post, 'children.data.0.media_type');
        }

        return Arr::get($post, 'media_type');
    }

    public function getThumbnailUrl($post)
    {
        if (Arr::get($post, 'media_type', false) === 'CAROUSEL_ALBUM') {
            return Arr::get($post, 'children.data.0.thumbnail_url');
        }

        return Arr::get($post, 'thumbnail_url');
    }

    public function getInstagramRemoteUri($post)
    {
        if(Arr::get($post, 'media_type', false) === 'CAROUSEL_ALBUM') {
            return Arr::get($post, 'children.data.0.media_url');
        }

        return Arr::get($post, 'media_url');
    }

    public function getPlaceholderUri()
    {
        return WPSOCIALREVIEWS_URL.'assets/images/ig-placeholder.png';
    }
}