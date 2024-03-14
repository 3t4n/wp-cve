<?php

namespace WordPress\Plugin\GalleryManager;

abstract class ContentFilter
{
    public static function init(): void
    {
        add_Filter('the_content', [static::class, 'addGalleryImagesToContent'], 10);
        add_Filter('the_excerpt', [static::class, 'addGalleryImagesToExcerpt'], 10);
        add_Filter('the_content_feed', [static::class, 'addGalleryImagesToExcerpt']);
        add_Filter('the_excerpt_rss', [static::class, 'addGalleryImagesToExcerpt']);
    }

    public static function addGalleryImagesToExcerpt(string $excerpt): string
    {
        if (Post::isGallery() && !Post_Password_Required() && Options::get('enable_previews')) {
            if (!has_Excerpt() || (has_Excerpt() && Options::get('enable_previews_for_custom_excerpts'))) {
                $gallery = new Gallery(); # Creates a Gallery object from the currenct post
                $excerpt .= $gallery->renderPreview();
            }
        }

        return $excerpt;
    }

    public static function addGalleryImagesToContent(string $content): string
    {
        if (Post::isGallery() && !has_Shortcode($content, 'gallery') && !Post_Password_Required() && !doing_Filter('get_the_excerpt')) {
            $gallery = new Gallery(); # Creates a Gallery object from the currenct post
            $content .= $gallery->render();
        }

        return $content;
    }
}

ContentFilter::init();
