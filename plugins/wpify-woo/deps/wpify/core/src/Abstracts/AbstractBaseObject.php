<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WP;
use WP_Post;
use WP_Query;
use WP_Rewrite;
use wpdb;
use WpifyWooDeps\Wpify\Core\Traits\BaseObjectTrait;
/**
 * @property wpdb $wpdb
 * @property WP_Post $post
 * @property WP_Rewrite $wp_rewrite
 * @property WP $wp
 * @property WP_Query $wp_query
 * @property WP_Query $wp_the_query
 * @property string $pagenow
 * @property int $page
 */
abstract class AbstractBaseObject
{
    use BaseObjectTrait;
}
