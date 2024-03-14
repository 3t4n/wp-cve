<?php

$upload_dir = wp_upload_dir();

\define('IKANAWEB_EVT_VERSION', '2.0.3');
\define('IKANAWEB_EVT_NAME', 'Embed Video Thumbnail');
\define('IKANAWEB_EVT_SLUG', 'ikanaweb_evt');
\define('IKANAWEB_EVT_BASENAME', plugin_basename(__DIR__ . '/embed-video-thumbnail.php'));
\define('IKANAWEB_EVT_URL', plugins_url('', IKANAWEB_EVT_BASENAME));
\define('IKANAWEB_EVT_TEXT_DOMAIN', 'embed-video-thumbnail');
\define('IKANAWEB_EVT_IMAGE_PATH', $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'ikana-embed-video-thumbnail');
\define('IKANAWEB_EVT_REVIEW_URL', 'https://wordpress.org/support/plugin/embed-video-thumbnail/reviews/#new-post');
\define('IKANAWEB_EVT_SUPPORT_URL', 'https://wordpress.org/support/plugin/embed-video-thumbnail');
\define('IKANAWEB_EVT_PHP_VERSION', '5.6.0');