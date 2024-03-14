<?php
/**
 * Sight block template
 *
 * @var        $attributes - attributes
 * @var        $options    - options
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

switch ( $attributes['source'] ) {
	case 'projects':
		require SIGHT_PATH . 'render/handler/post-area-projects.php';
		break;
	case 'categories':
		require SIGHT_PATH . 'render/handler/post-area-categories.php';
		break;
	case 'post':
		require SIGHT_PATH . 'render/handler/post-area-post.php';
		break;
	case 'custom':
		require SIGHT_PATH . 'render/handler/post-area-custom.php';
		break;
	default:
		require SIGHT_PATH . 'render/handler/post-area-none.php';
		break;
}
