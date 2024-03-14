<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/admin/partials
 */

/**
 * @var array $error
 */

/**
 * @var array $message
 */
?><?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit; ?>

<?php $boxClass = empty($error) ? 'success' : 'error'; ?>

<?php if( !empty($error) || !empty($message)):?>
	<div class="notice notice-<?php print $boxClass; ?>">
		<ul>
			<li><?php print implode('</li><li>', !empty($error) ? $error : empty($message) ?: $message); ?></li>
		</ul>
	</div>
<?php endif; ?>