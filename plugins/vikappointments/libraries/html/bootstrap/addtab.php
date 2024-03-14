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
$id       = empty($displayData['id'])       ? '' : $displayData['id'];
$active   = empty($displayData['active'])   ? '' : $displayData['active'];
$title    = empty($displayData['title'])    ? '' : $displayData['title'];

$li = '<li class="' . trim($active) . '"><a href="#' . $id . '" data-toggle="tab">' . addslashes($title) . '</a></li>';

?>

<script>
	<?php
	echo
<<<JS
jQuery('#{$selector}Tabs').append('{$li}').children().last().on('click', function() {
	{$selector}BootstrapTabSetChanged('{$id}');
});
JS
	;
	?>
</script>

<div id="<?php echo $id; ?>" class="tab-pane<?php echo $active; ?>" <?php echo ($active ? '' : 'style="display:none;"'); ?>>
