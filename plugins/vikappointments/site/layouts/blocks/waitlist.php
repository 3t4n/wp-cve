<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$id_service  = isset($displayData['id_service'])  ? $displayData['id_service']  : 0;
$id_employee = isset($displayData['id_employee']) ? $displayData['id_employee'] : 0;
$title       = isset($displayData['title'])       ? $displayData['title']       : '';
$itemid      = isset($displayData['itemid'])      ? $displayData['itemid']      : null;

if (is_null($itemid))
{
	// item id not provided, get the current one (if set)
	$itemid = JFactory::getApplication()->input->getInt('Itemid');
}

$vik = VAPApplication::getInstance();

?>

<div class="vap-overlay" id="vapaddwaitlistoverlay" style="display: none;">
	<div class="vap-modal-box" style="width: 80%;max-width: 800px;height: 60%;margin-top:10px;">
		
		<div class="vap-modal-head">
			<div class="vap-modal-head-title">
				<h3><?php echo $title; ?></h3>
			</div>

			<div class="vap-modal-head-dismiss">
				<a href="javascript: void(0);" onClick="vapCloseWaitListOverlay('vapaddwaitlistoverlay');">×</a>
			</div>
		</div>

		<div class="vap-modal-body" style="height:90%;overflow:scroll;">
			
		</div>

	</div>
</div>

<?php
JText::script('VAPWAITLISTADDED0');
?>

<script>

	function vapOpenWaitListOverlay(ref, date, title) {
		if (title) {
			jQuery('.vap-modal-head-title h3').text(title);
		}
		
		jQuery('#' + ref).show();
		
		UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&tmpl=component&view=pushwl' . ($itemid ? '&Itemid=' . $itemid : ''), false); ?>',
			{
				date:        date, 
				id_service:  <?php echo $id_service; ?>, 
				id_employee: <?php echo $id_employee; ?>,
			},
			(resp) => {
				try {
					// try to decode JSON
					resp = JSON.parse(resp);
				} catch (err) {
					// nothing to decode
				}

				if (Array.isArray(resp)) {
					// in case of array, join the elements
					resp = resp.join("\n");
				}

				jQuery('.vap-modal-body').html(resp);
			},
			(err) => {
				alert(Joomla.JText._('VAPWAITLISTADDED0'));
			}
		);
	}

	function vapCloseWaitListOverlay(ref) {
		jQuery('#' + ref).hide();
		jQuery('.vap-modal-body').html('');
	}

	jQuery('.vap-modal-box').on('click', function(e) {
		// ignore outside clicks
		e.stopPropagation();
	});

	jQuery('.vap-overlay').on('click', function() {
		vapCloseWaitListOverlay('vapaddwaitlistoverlay');
	});

</script>
