<?php
defined( 'ABSPATH' ) || exit;

$layout = $this->data->layout;
if ( 'full' === $layout ) {
	include __DIR__ . '/full.php';
}
if ( '2c' === $layout ) {
	include __DIR__ . '/2c.php';
}
if ( 'image_content' === $layout ) {
	include __DIR__ . '/left-image.php';
}
if ( 'content_image' === $layout ) {
	include __DIR__ . '/right-image.php';
}
