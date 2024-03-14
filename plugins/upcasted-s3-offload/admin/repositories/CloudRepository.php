<?php

/**
 * Class CloudRepository
 */
class CloudRepository
{
    /**
     * @var null|CloudRepository
     */
    private static $instance = null;

    /**
     * @return CloudRepository
     */
    public static function getInstance(): CloudRepository
    {
        if (self::$instance == null) {
            self::$instance = new CloudRepository();
        }

        return self::$instance;
    }

    /**
     * @param string $metaCompare
     * @param int $postPerPage
     * @return WP_Query
     */
    public static function getAttachments(string $metaCompare, int $postPerPage = -1): WP_Query
    {
        $mimeTypes = get_option(UPCASTED_S3_OFFLOAD_SETTINGS)[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES];
        $params = [
            'post_type' => ['attachment'],
            'post_status' => 'inherit',
            'fields' => 'ids',
            'post_mime_type' => is_array($mimeTypes) ? $mimeTypes : get_allowed_mime_types(),
            'posts_per_page' => $postPerPage
        ];
        if ($metaCompare == 'LIKE') {
            $params['meta_key'] = '_wp_attachment_metadata';
            $params['meta_value'] = 'bucket';
            $params['meta_compare'] = $metaCompare;
        }
        if ($metaCompare == 'NOT LIKE') {
            $params['meta_query'] = array(
                'relation' => 'OR',
                array(
                  'key' => '_wp_attachment_metadata',
                  'value' => 'bucket',
                  'compare' => 'NOT EXISTS',
                ),
                array(
                  'key' => '_wp_attachment_metadata',
                  'value' => 'bucket',
                  'compare' => 'NOT LIKE',
                )
            );
        }
        return new WP_Query($params);
    }
}