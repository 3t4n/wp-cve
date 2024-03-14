<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Post
{
    public static function isGallery(int $post_id = -1): bool
    {
        if ($post_id < 1) $post_id = get_the_id();
        $post_type = get_Post_Type($post_id);
        return $post_type === PostType::post_type_name;
    }

    public static function isGalleryImage(int $post_id = -1): bool
    {
        if ($post_id < 1) $post_id = get_the_id();
        $attachment = get_Post($post_id);
        return static::isGallery((int) $attachment->post_parent);
    }
}
