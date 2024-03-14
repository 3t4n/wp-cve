<?php

namespace WordPress\Plugin\GalleryManager;

use WP_Query;

abstract class WPQueryExtensions
{
    public static function init(): void
    {
        #add_Filter('query_vars', [static::class, 'registerQueryVars']);
        add_Action('pre_get_posts', [static::class, 'filterAttachmentQuery']);
        add_Filter('posts_where', [static::class, 'filterWhereStmt'], 10, 2);
        add_Filter('posts_join', [static::class, 'filterJoinStmt'], 100, 2);
        add_Filter('ajax_query_attachments_args', [static::class, 'restrictAttachments']);
    }

    /*
    public static function registerQueryVars($query_vars){
        $query_vars[] = 'restrict_gallery_images';
        return $query_vars;
    }
    */

    public static function filterAttachmentQuery(WP_Query $query): void
    {
        if (is_Admin() && $query->get('post_type') == 'attachment' && $query->get('orderby') == 'menu_order ASC, ID' && $query->get('order') == 'DESC')
            $query->set('order', 'ASC');
    }

    public static function filterJoinStmt(string $stmt, WP_Query $query): string
    {
        global $wpdb;

        if ($gallery_id = (int) $query->get('restrict_gallery_images')) {
            $post_type_name = PostType::post_type_name;
            $stmt .= " LEFT JOIN {$wpdb->posts} as galleries
                       ON ({$wpdb->posts}.post_type = 'attachment'
                       AND galleries.post_type = '{$post_type_name}'
                       AND {$wpdb->posts}.post_parent = galleries.ID) ";
        }

        return $stmt;
    }

    public static function filterWhereStmt(string $stmt, WP_Query $query): string
    {
        if ($gallery_id = (int) $query->get('restrict_gallery_images'))
            $stmt .= " AND (galleries.ID IS NULL OR galleries.ID = {$gallery_id}) ";

        return $stmt;
    }

    public static function restrictAttachments(array $query_args): array
    {
        setType($query_args, 'Array');
        $user_id = get_Current_User_ID();
        $gallery_id = empty($_POST['post_id']) ? false : IntVal($_POST['post_id']);

        if ($gallery_id && Post::isGallery($gallery_id)) {
            # Restrict attachments to their owner
            if ($user_id && !current_User_Can('edit_others_posts') && empty($query_args['author'])) {
                $query_args['author'] = $user_id;
            }

            $query_args['restrict_gallery_images'] = $gallery_id;
        }

        return $query_args;
    }
}

WPQueryExtensions::init();
