<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$showcopyright = $this->data->get('showcopyright');
?>
<div class="fpframework-admin-container--content grow p-5">
	<?php
	do_action('fpframework/admin/notices');
	do_action('firebox/admin/content');
	fpframework()->renderer->admin->render('pages/new_footer', [
		'show_copyright' => $showcopyright,
		'plugin' => firebox()->_('FB_PLUGIN_NAME'),
		'plugin_version' => FBOX_VERSION . ' ' . ucfirst(FBOX_LICENSE_TYPE)
	]);
	?>
</div>