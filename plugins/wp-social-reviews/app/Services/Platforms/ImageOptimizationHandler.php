<?php

namespace WPSocialReviews\App\Services\Platforms;

use WPSocialReviews\App\Models\OptimizeImage;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\App\Services\Platforms\Feeds\Instagram\InstagramFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\Config;
use WPSocialReviews\Framework\Support\Arr;

class ImageOptimizationHandler
{
    public $doneResizing = [];
    public $availableRecords = null;

    public function registerHooks()
    {
        add_action('wp_ajax_wpsr_resize_images', array($this, 'savePhotos'));
        add_action('wp_ajax_nopriv_wpsr_resize_images', array($this, 'savePhotos'));
        add_action('wpsocialreviews/check_instagram_access_token_validity_weekly', array($this, 'checkValidity'));
        add_action('wpsocialreviews/reset_data', array($this, 'resetData'));
    }

    public function savePhotos()
    {
        $id = absint(Arr::get($_REQUEST, 'id', -1));

        if($id != -1) {
            $encodedMeta   = get_post_meta($id, '_wpsr_template_config', true);
            $decodedMeta   = json_decode($encodedMeta, true);
            $feed_settings = Arr::get($decodedMeta, 'feed_settings', []);
            $formattedMeta = Config::formatInstagramConfig($feed_settings, array());
            $feedConfigs = (new InstagramFeed())->getTemplateMeta($formattedMeta);

            $feeds = Arr::get($feedConfigs, 'dynamic.items', []);
            $resizedImages = Arr::get($feedConfigs, 'dynamic.resize_data', []);

            foreach ($feeds as $feed) {
                if (in_array(Arr::get($feed, 'id'), $resizedImages)) {
                    $this->doneResizing[] = Arr::get($feed, 'id');
                } else {
                    if(!$this->maxResizingPerUnitTimePeriod()) {
                        if ($this->isMaxRecordsReached()) {
                            $this->deleteLeastUsedImages();
                        }
                        $this->processSaveImage($feed);
                    }
                }
            }

            $header = Arr::get($feedConfigs, 'dynamic.header');
            $accountId = Arr::get($feedConfigs, 'feed_settings.header_settings.account_to_show');

            if (empty(Arr::get($header, 'user_avatar'))) {
                $accountId = null;
            }

            if (!empty($accountId)) {
                if ($this->localAvatarExists($accountId)) {
                    $accountId = null;
                }
            }

            if (!empty($accountId)) {
                $globalSettings = $this->getGlobalSettings();
                $userAvatar = Arr::get($header, 'user_avatar');

                $res = $this->maybeLocalAvatar($accountId, $userAvatar, $globalSettings);
                if (!$res) {
                    $accountId = null;
                }
            }

            $resizedImages = [
                'images_data'   => $this->doneResizing,
                'account_id'    => $accountId
            ];

            $resizedImagesJson = json_encode($resizedImages);
            echo $resizedImagesJson;
            die();
        }
    }

    public function processSaveImage($feed)
    {
        $userName = Arr::get($feed, 'username');
        if($userName) {
            $this->saveImage($feed);
        }
    }

    public function saveImage($feed)
    {
        $imageSizes = ['full'  => 640, 'low'   => 320, 'thumb' => 150];
        $mediaId = Arr::get($feed, 'id', '');
        $userName = Arr::get($feed, 'username', '');
        $isImageResized = false;
        $uploadDir = $this->getUploadDir() . '/' . $userName;

        $sizes = ['height' => 1, 'width'  => 1];
        foreach ($imageSizes as $suffix => $image_size) {
            $image_source_set    = $this->getMediaSource($feed);
            $fileName = Arr::get($image_source_set, $image_size, $this->getMediaUrl($feed));

            if (!empty($fileName) && !empty($mediaId)) {
                $imageFileName = $mediaId . '_'. $suffix . '.jpg';

                $headers = @get_headers($fileName, 1);
                if (isset($headers['Content-Type'])) {
                    if (!str_contains($headers['Content-Type'], 'image/')) {
                        error_log("Not a regular image");
                    } else {
                        if (!file_exists($uploadDir)) {
                            wp_mkdir_p($uploadDir);
                        }

                        $fullFileName = trailingslashit($uploadDir) . $imageFileName;
                        if (file_exists($fullFileName)) {
                            continue;
                        }

                        $imageEditor = wp_get_image_editor($fileName);
                        if (is_wp_error($imageEditor)) {
                            require_once ABSPATH . 'wp-admin/includes/file.php';

                            $timeoutInSeconds = 5;
                            $temp_file = download_url($fileName, $timeoutInSeconds);
                            $imageEditor = wp_get_image_editor($temp_file);
                        }

                        if (!is_wp_error($imageEditor)) {
                            $imageEditor->set_quality( 80 );
                            $sizes = $imageEditor->get_size();
                            $imageEditor->resize( $image_size, null );
                            $savedImage = $imageEditor->save($fullFileName);
                            if ($savedImage) {
                                $isImageResized = true;
                            }
                        } else {
                            $isImageResized |= $this->download($fileName, $fullFileName, $suffix);
                            $imgSize = @getimagesize($fileName);

                            if ($isImageResized && is_array($imgSize) && $imgSize[0] > 0 && $imgSize[1] > 0) {
                                $sizes = [
                                    'width' => $imgSize[0],
                                    'height' => $imgSize[1],
                                ];
                            }
                        }

                        if (!empty($temp_file)) {
                            @unlink( $temp_file );
                        }
                    }
                }
            }
        }

        $this->updateImageInDb($userName, $mediaId, $isImageResized, $sizes);
    }

    public function resizeImage($imageUrl, $originalImage, $old_size, $image_size)
    {
        try {
            // Get the image resource.
            $image = imagecreatefromjpeg($originalImage);

            // Get the width and height of the original image.
            $originalWidth  = getimagesize($imageUrl)[0];
            $originalHeight = getimagesize($imageUrl)[1];

            // Set the new width and height of the resized image.
            $newWidth = 640; $newHeight = 640;
            switch ($image_size) {
                case 'low':
                    $newWidth = 320;
                    $newHeight = 320;
                    break;
                case 'thumb':
                    $newWidth = 150;
                    $newHeight = 150;
                    break;
            }

            // Create a new image object of the same type as the original image.
            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            // Copy the original image to the new image, resizing it as needed.
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            $tmpImage = $originalImage;
            $tmpImage = str_replace($old_size, $image_size, $tmpImage);

            // Save the resized image to a file.
            imagejpeg($newImage, $tmpImage);

            // Free the memory used by the images.
            imagedestroy($image);
            imagedestroy($newImage);
            return true;
        } catch (\Exception $exception) {
            //$exception->getMessage();
        }

        return false;
    }

    public function download($url = '', $filepath = '', $image_size = '')
    {
        $curl = curl_init($url);

        if (!$curl) {
            //error_log('wpsn was unable to initialize curl. Please check if the curl extension is enabled.');
            return false;
        }

        $file = @fopen($filepath, 'wb');

        if (!$file) {
            //error_log('wpsn was unable to create the file: ' . $filepath);
            return false;
        }

        try {
            curl_setopt($curl, CURLOPT_FILE, $file);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_ENCODING, '');
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            if (!empty($_SERVER['HTTP_USER_AGENT'])) {
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            }

            $success = curl_exec($curl);

            if (!$success) {
                //error_log('wpsn failed to get the media data from Instagram: ' . curl_error($curl));
                return false;
            }
            
            if($image_size == 'full') {
                $this->resizeImage($url, $filepath, 'full', 'low');
                $this->resizeImage($url, $filepath, 'full', 'thumb');
            }

            else if($image_size == 'low') {
                $this->resizeImage($url, $filepath, 'low', 'full');
                $this->resizeImage($url, $filepath, 'low', 'thumb');
            }

            else if($image_size == 'thumb') {
                $this->resizeImage($url, $filepath, 'thumb', 'low');
                $this->resizeImage($url, $filepath, 'thumb', 'full');
            }

            return true;
        } finally {
            curl_close($curl);
            fclose($file);
        }
    }

    public function updateImageInDb($userName, $mediaId, $isImageResized, $sizes)
    {
        $dateFormat = date('Y-m-d H:i:s');
        $data = [
            'user_name'         => $userName,
            'last_requested'    => $dateFormat,
            'created_at'        => $dateFormat,
            'updated_at'        => $dateFormat,
            'platform'          => 'instagram',
            'media_id'          => $mediaId,
        ];

        $data['images_resized'] = 0;
        if ($isImageResized) {
            $data['images_resized'] = 1;
            $aspectRatio = round($sizes['width'] / $sizes['height'], 2);
            $data['aspect_ratio'] = $aspectRatio;
        }

        $saved = (new OptimizeImage())->updateData($mediaId, $userName, $data);

        if($saved) {
            $this->doneResizing[] = $mediaId;
        }
    }

    public function getMediaSource($post)
    {
        $media_urls   = [];
        $accountType = Arr::get($post, 'images') ? 'personal' : 'business';
        if ($accountType === 'personal') {
            $media_urls['150'] = Arr::get($post, 'images.thumbnail.url');
            $media_urls['320'] = Arr::get($post, 'images.low_resolution.url');
            $media_urls['640'] = Arr::get($post, 'images.standard_resolution.url');
        } else {
            $full_size    = $this->getMediaUrl($post);
            $media_urls['150'] = $full_size;
            $media_urls['320'] = $full_size;
            $media_urls['640'] = $full_size;
        }

        return $media_urls;
    }

    public function getMediaUrl($post)
    {
        if(Arr::get($post, 'media_name') == 'VIDEO' && !empty(Arr::get($post, 'thumbnail_url'))) {
            return Arr::get($post, 'thumbnail_url');
        }

        if(Arr::get($post, 'media_type') == 'IMAGE' && !empty(Arr::get($post, 'default_media'))) {
            return Arr::get($post, 'default_media');
        }
    }

    public function checkValidity($account)
    {
       $error_status = Arr::get($account, 'status');
       $has_app_permission_error = Arr::get($account, 'has_app_permission_error', false);
       if($error_status === 'error' && $has_app_permission_error){
           (new PlatformData('instagram'))->handleAppPermissionError();
       }
    }

    public function cleanData($account)
    {
        $userName   = Arr::get($account, 'username');
        $userId   = Arr::get($account, 'user_id');

        $cacheHandler = new CacheHandler('instagram');
        if(!empty($userName)) {
            (new OptimizeImage())->deleteMediaByUserName($userName);
            $uploadDir = $this->getUploadDir() . '/' . $userName;
            $this->deleteDirectory($uploadDir);
            $cacheHandler->clearCacheByAccount($userId);
        }
    }

    public function resetData($platform)
    {
        $connectedIds      = get_option('wpsr_instagram_verification_configs', []);
        $connectedAccounts  = Arr::get($connectedIds, 'connected_accounts', []);

        foreach($connectedAccounts as $account) {
            $userName   = Arr::get($account, 'username');

            if (!empty($userName)) {
                (new OptimizeImage())->deleteMediaByUserName($userName);
                $uploadDir = $this->getUploadDir() . '/' . $userName;
                $this->deleteDirectory($uploadDir);
            }
        }
    }

    public function deleteDirectory($dir)
    {
        $deleted = false;
        if(is_dir($dir)) {
            if(!str_ends_with($dir, '/')) {
                $dir .= '/';
            }

            $files = glob($dir . '*', GLOB_MARK);
            foreach($files as $file) {
                if(is_dir($file)) {
                    $this->deleteDirectory($file);
                } else {
                    if(file_exists($file)) {
                        unlink($file);
                    }
                }
            }

            if(is_dir($dir) && !file_exists($dir)) {
                $deleted = rmdir($dir);
            }
        }

        return $deleted;
    }

    public function getResizeNeededImageLists($feeds)
    {
        $ids = array_column($feeds , 'id');
        $userNames = array_column($feeds , 'username');
        $resized_images = (new OptimizeImage())->getMediaIds($ids, $userNames);

        return array_unique($resized_images);
    }

    public function getUploadDir()
    {
        $errorManager = new PlatformErrorManager();
        $upload     = wp_upload_dir();
        $uploadDir = trailingslashit($upload['basedir']) . trailingslashit(WPSOCIALREVIEWS_UPLOAD_DIR_NAME) . 'instagram';
        if (!file_exists($uploadDir)) {
            $created = wp_mkdir_p($uploadDir);
            if($created){
                $errorManager->removeErrors('upload_dir');
            } else {
                $error = __( 'There was an error creating the folder for storing resized instagram images.', 'wp-social-reviews' );
                $errorManager->addError('upload_dir', $error);
            }
        } else {
            $errorManager->removeErrors('upload_dir');
        }

        return $uploadDir;
    }

    public function getUploadUrl()
    {
        $upload     = wp_upload_dir();
        return trailingslashit($upload['baseurl']) . trailingslashit(WPSOCIALREVIEWS_UPLOAD_DIR_NAME) . 'instagram';
    }

    public function getGlobalSettings()
    {
        $globalSettings = get_option('wpsr_instagram_global_settings');
        return Arr::get($globalSettings, 'global_settings', []);
    }

    public function formattedData($header)
    {
        $avatar = Arr::get($header, 'user_avatar');
        $accountId = Arr::get($header, 'account_id');

        if(!empty($accountId) && !$this->localAvatarExists($accountId)) {
            return $header;
        }

        $globalSettings = $this->getGlobalSettings();
        if(!empty($avatar) && !empty($accountId)) {
            $header['local_avatar'] = $this->maybeLocalAvatar($accountId, $avatar, $globalSettings);
        }

        return $header;
    }

    public function maybeLocalAvatar($userId, $profilePicture, $globalSettings)
    {
        if ($this->localAvatarExists($userId)) {
            return $this->getLocalAvatarUrl($userId);
        }

        if ($this->shouldCreateLocalAvatar($userId, $globalSettings)) {
            $created = $this->createLocalAvatar($userId, $profilePicture);
            $this->updateLocalAvatarStatus($userId, $created);

            if ($created) {
                return $this->getLocalAvatarUrl($userId);
            }
        }

        return false;
    }

    public function localAvatarExists($userId)
    {
        $avatars = get_option('wpsr_instagram_local_avatars', array());
        return !empty(Arr::get($avatars, $userId));
    }

    public function getLocalAvatarUrl($userId)
    {
        return $this->getUploadUrl() . '/' . $userId . '.jpg';
    }

    public function shouldCreateLocalAvatar($userId, $globalSettings)
    {
        if (Arr::get($globalSettings, 'optimized_images') === 'true') {
            $avatars = get_option('wpsr_instagram_local_avatars', array());
            return empty(Arr::get($avatars, $userId));
        }

        return false;
    }

    public function updateLocalAvatarStatus($userId, $status)
    {
        $avatars = get_option('wpsr_instagram_local_avatars', array());
        if(!empty($userId)) {
            $avatars[$userId] = $status;
        }

        update_option('wpsr_instagram_local_avatars', $avatars);
    }

    public function createLocalAvatar($userName, $fileName)
    {
        if (empty($fileName)) {
            return false;
        }

        $imageEditor   = wp_get_image_editor($fileName);
        if(is_wp_error($imageEditor)) {
            if (!function_exists('download_url' )) {
                include_once ABSPATH . 'wp-admin/includes/file.php';
            }

            $timeoutInSeconds = 5;
            $temp_file = download_url($fileName, $timeoutInSeconds);
            $imageEditor = wp_get_image_editor($temp_file);
            if (!empty($temp_file)) {
                @unlink($temp_file);
            }
        }

        $fullFileName = $this->getUploadDir() . '/' . $userName . '.jpg';
        if (!is_wp_error($imageEditor)) {
            $imageEditor->set_quality(80);
            $imageEditor->resize(150, null);
            $saved_image = $imageEditor->save($fullFileName);
            if ($saved_image) {
                return true;
            }
        }

        return $this->download($fileName, $fullFileName);
    }

    public function deleteLeastUsedImages()
    {
        $limit = ($this->availableRecords  && $this->availableRecords > 1) ? $this->availableRecords : 1;
        $oldPosts = (new OptimizeImage())->getOldPosts($limit);

        $upload_dir = $this->getUploadDir();
        $imageSizes = ['thumb', 'low', 'full'];
        foreach ($oldPosts as $post) {
            $userName = Arr::get($post, 'user_name');
            foreach ($imageSizes as $size) {
                $file_name = $upload_dir .  '/'. $userName . '/' . Arr::get($post, 'media_id') . '_' . $size . '.jpg';
                if (is_file($file_name)) {
                    unlink($file_name);
                }
            }

            $mediaId = Arr::get($post, 'media_id');
            if(!empty($mediaId)) {
                (new OptimizeImage())->deleteMedia($mediaId, $userName);
            }
        }
    }

    public function isMaxRecordsReached()
    {
        $totalRecords = OptimizeImage::count();
        if ($totalRecords > WPSOCIALREVIEWS_INSTAGRAM_MAX_RECORDS ) {
            $this->availableRecords = (int) $totalRecords - WPSOCIALREVIEWS_INSTAGRAM_MAX_RECORDS;
        }

        return ($totalRecords >= WPSOCIALREVIEWS_INSTAGRAM_MAX_RECORDS );
    }

    public function updateLastRequestedTime($ids)
    {
        if (count($ids) === 0) {
            return;
        }

        if($this->shouldUpdateLastRequestedTime()) {
            (new OptimizeImage())->updateLastRequestedTime($ids);
        }
    }

    public function maxResizingPerUnitTimePeriod()
    {
        $fifteenMinutesAgo = date('Y-m-d H:i:s', time() - 15 * 60);
        $totalRecords = OptimizeImage::where('created_at', '>', $fifteenMinutesAgo)->count();

        return ($totalRecords > 100);
    }

    public function shouldUpdateLastRequestedTime()
    {
        return (rand(1, 20) === 20);
    }
}