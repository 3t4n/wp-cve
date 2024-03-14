<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Query;

abstract class PostRelations
{
    public static function getTermRelatedItems(array $arguments = [])
    {
        global $wpdb, $post;

        $arguments = is_Array($arguments) ? $arguments : [];

        # Load default arguments
        $arguments = (object) Array_Merge([
            'post_id' => empty($post->ID) ? null : $post->ID,
            'number' => 10,
            'taxonomy' => 'encyclopedia-tag',
            'min_relation_threshold' => (int) Options::get('min_relation_threshold')
        ], $arguments);

        # apply filter
        $arguments = apply_Filters('encyclopedia_term_related_items_arguments', $arguments);

        # if there is no term set we leave
        if (empty($arguments->post_id))
            return false;

        # if the taxonomy does not exists we leave
        if (!Taxonomy_Exists($arguments->taxonomy))
            return false;

        # Get the Tags
        $arr_terms = WP_Get_Post_Terms($arguments->post_id, $arguments->taxonomy);
        if (empty($arr_terms))
            return false;

        # Get term IDs
        $arr_term_ids = Array_Map(function ($taxonomy) {
            return $taxonomy->term_taxonomy_id;
        }, $arr_terms);
        $str_term_id_list = implode(',', $arr_term_ids);

        # The Query to get the related posts
        $stmt = "
            SELECT
                post.id,
                COUNT(relation.object_id) AS common_term_count

            FROM
                {$wpdb->term_relationships} AS relation,
                {$wpdb->posts} AS post

            WHERE
                relation.object_id = post.id AND
                relation.term_taxonomy_id IN({$str_term_id_list}) AND
                post.id != {$arguments->post_id} AND
                post.post_status = 'publish'

            GROUP BY
                relation.object_id

            HAVING
                common_term_count >= {$arguments->min_relation_threshold}

            ORDER BY
                common_term_count DESC,
                post.post_title ASC,
                post.post_date_gmt DESC";

        # Get the related post ids
        $related_post_ids = $wpdb->get_Col($stmt);

        # If there are no related posts we leave
        if (empty($related_post_ids))
            return false;

        # Generate Query args
        $query_args = [
            'post_type' => PostType::post_type_name,
            'post__in' => $related_post_ids,
            'orderby' => 'post__in',
            'posts_per_page' => $arguments->number,
            'ignore_sticky_posts' => true
        ];

        # Put it in a WP_Query
        $query = new WP_Query($query_args);

        # apply a filter to the query object
        do_Action('encyclopedia_term_related_items_query_object', $query, $arguments);

        # return
        return $query->have_Posts() ? $query : false;
    }
}
