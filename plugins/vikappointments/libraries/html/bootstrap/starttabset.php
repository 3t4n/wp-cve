<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.bootstrap
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$selector = empty($displayData['selector']) ? '' : $displayData['selector'];

?>

<script>

	<?php
	echo
<<<JS
function {$selector}BootstrapTabSetChanged(id) {
	jQuery('#{$selector}Content .tab-pane').hide();
	jQuery('#{$selector}Content #' + id).show().trigger('show');
}
JS
	;
	?>

</script>

<ul class="nav nav-tabs postbox" id="<?php echo $selector; ?>Tabs"></ul>
<div class="tab-content" id="<?php echo $selector; ?>Content">
