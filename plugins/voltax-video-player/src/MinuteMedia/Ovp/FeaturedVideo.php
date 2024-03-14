<?php

namespace MinuteMedia\Ovp;

use \WP_Post;

class FeaturedVideo
{
    const NONCE_ID = 'mm_featured_video_nonce';
    const META_VIDEO_DATA = 'mm-featured-video-data';

    /**
     * FeaturedVideo constructor
     */
    public function __construct()
    {
        \add_action('add_meta_boxes', [$this, 'metaBox']);
        \add_action('save_post', [$this, 'saveFeaturedVideo'], 10, 2);
    }

    /**
     * Adds featured video meta box
     */
    public function metaBox()
    {
        if(Plugin::getEnableFeaturedVideo()) {
            \add_meta_box(
                'featured_video',
                'Featured Video',
                [$this, 'metaBoxContent'],
                'post',
                'side',
                'default'
            );
        }
    }

    /**
     * Creates meta box content
     * @param WP_Post $post
     */
    public function metaBoxContent($post)
    {
        $data = self::getVideo($post->ID);
        $thumbnailUrl = !empty($data['image']) ? $data['image'] : null;
        $title = !empty($data['title']) ? $data['title'] : '';
        $description = !empty($data['description']) ? $data['description'] : '';
        $playerId = !empty($data['playerId']) ? $data['playerId'] : '';
        $playlistId = !empty($data['playlistId']) ? $data['playlistId'] : '';
        $id = !empty($data['payload_id']) ? $data['payload_id'] : '';

        $featuredVideo = !empty($data);

        echo "<div 
            id='mm-featured-video-meta-box' 
            data-video-id='" . esc_attr(json_encode($id)) . "'
            data-thumbnail='" . esc_attr(json_encode($thumbnailUrl)) . "'
            data-title='" . esc_attr(json_encode($title)) . "'
            data-description='" . esc_attr(json_encode($description)) . "'
            data-player-id='" . esc_attr(json_encode($playerId)) . "'
            data-playlist-id='" . esc_attr(json_encode($playlistId)) . "'
            data-has-featured-video='" . esc_attr(json_encode($featuredVideo)) . "'
        ></div>";

        \wp_nonce_field(self::NONCE_ID, self::NONCE_ID);
    }


    /**
     * Save featured video meta
     * @param int $postId
     */
    public function saveFeaturedVideo($postId)
    {
        if (!isset($_POST['mm_featured_video_nonce'])) {
            return;
        }

        // Check nonce
        if (!\wp_verify_nonce($_POST[self::NONCE_ID], self::NONCE_ID)) {
            return;
        }

        // check capabilities
        if (!\current_user_can('edit_post', $postId)) {
            return;
        }

        if (empty($_POST['mm-featured-video-data'])) {
            \delete_post_meta($postId, self::META_VIDEO_DATA);
            return;
        }

        \update_post_meta($postId, self::META_VIDEO_DATA, sanitize_text_field($_POST['mm-featured-video-data']));
    }

    /**
     * Get video from post meta if exists
     * @param int $postId
     * @return array|null
     */
    public static function getVideo($postId)
    {
        $meta = \get_post_meta($postId, self::META_VIDEO_DATA, true);

        if (empty($meta)) {
            return null;
        }
        $meta = json_decode($meta, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return (array)$meta;
    }
}
