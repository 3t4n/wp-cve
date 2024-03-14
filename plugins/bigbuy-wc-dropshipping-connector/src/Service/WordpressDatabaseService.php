<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

class WordpressDatabaseService
{
    public const TABLE_TERMS = 'terms';
    public const TABLE_TERM_RELATIONSHIPS = 'term_relationships';
    public const TABLE_TERM_TAXONOMY = 'term_taxonomy';
    public const TABLE_POSTS = 'posts';
    public const TABLE_POST_META = 'postmeta';
    public const TABLE_TERM_META = 'termmeta';

    public static function getConnection(): \wpdb
    {
        global $wpdb;

        return $wpdb;
    }
}