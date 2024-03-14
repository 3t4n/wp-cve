<?php

namespace WordPress\Plugin\GalleryManager;

abstract class AJAXRequests
{
    public static function init(): void
    {
        static::registerAJAXHook('get_gallery', 'getGallery');
    }

    public static function registerAJAXHook(string $action, string $method): void
    {
        add_Action("wp_ajax_{$action}", [static::class, $method]);
        add_Action("wp_ajax_nopriv_{$action}", [static::class, $method]);
    }

    public static function sendResponse($response): void
    {
        header('Content-Type: application/json');
        echo JSON_Encode($response);
        exit;
    }

    public static function getGallery()
    {
        $gallery_id = trim($_REQUEST['gallery_id']);
        $gallery = new Gallery($gallery_id);
        $arr_images = $gallery->getImages();
        $arr_images = Array_Values($arr_images);

        if (empty($arr_images)) return false;

        foreach ($arr_images as &$attachment_ptr) {
            $img_obj = (object) [
                'title' => isset($attachment_ptr->post_title) ? $attachment_ptr->post_title : false,
                'description' => isset($attachment_ptr->post_content) ? $attachment_ptr->post_content : false,
                'href' => $attachment_ptr->url,
                'thumbnail' => isset($attachment_ptr->thumbnail->url) ? $attachment_ptr->thumbnail->url : false
            ];

            # Overwrite image item at attachment_ptr with new image object
            $attachment_ptr = apply_Filters('gallery_manager_json_image', $img_obj, $attachment_ptr, $gallery);
        }

        # return the images
        static::sendResponse($arr_images);
    }
}

AJAXRequests::init();
