<?php
/**
 * Comment Moderation Role
 *
 * @package           WpbCommentModeration
 * @author            WPBeginner
 * @copyright         2021 WPBeginner
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Comment Moderation Role
 * Plugin URI:        https://wpbeginner.com
 * Description:       A comment moderation role for WordPress sites.
 * Version:           1.1.1
 * Requires at least: 5.1
 * Requires PHP:      5.6
 * Author:            WPBeginner
 * Author URI:        https://wpbeginner.com
 * Text Domain:       comment-moderation-role
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace WPB\CommentModerationRole;

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/meta-caps.php';
require_once __DIR__ . '/inc/roles-caps.php';
require_once __DIR__ . '/inc/admin-screen.php';

bootstrap();
