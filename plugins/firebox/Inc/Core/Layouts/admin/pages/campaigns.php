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

$campaigns = $this->data->get('campaigns');
$table = new \FireBox\Core\FB\CampaignsList();

// Process bulk actions and show notices
$table->process_bulk_action();
do_action('fpframework/admin/notices');
?>
<h1 class="mb-3 text-default text-[32px] dark:text-white flex gap-1 items-center fp-admin-page-title"><?php esc_html_e(firebox()->_('FB_CAMPAIGNS')); ?></h1>

<form action="" method="get">
	<?php
	$table->views();
	$table->prepare_items();
	$table->search_box( __( 'Search campaigns' ), 'firebox-campaign' );
	$table->display();
	?>
	<input type="hidden" name="page" value="firebox-campaigns" />
</form>