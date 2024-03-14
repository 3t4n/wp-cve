<?php
/**
 * Full UTF-8 support for Wordpress
 * by {@link http://andowebsit.es/blog/noteslog.com Andrea Ercolino}
 */

require_once( WP_CONTENT_DIR . '/plugins/full-utf-8/FullUtf8_wpdb.php' ); 
$wpdb = new FullUtf8_wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
