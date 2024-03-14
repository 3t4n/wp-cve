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

$vik = VAPApplication::getInstance();

?>

<div class="inspector-form" id="inspector-position-form">

	<div class="inspector-fieldset">

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAP_WIDGET_POSITION'),
			'content' => JText::translate('VAP_WIDGET_POSITION_ADD_HELP'),
		));

		echo $vik->openControl(JText::translate('VAP_WIDGET_POSITION') . $help); ?>
			<input type="text" name="position_name" value="" class="field required" />
		<?php echo $vik->closeControl(); ?>

	</div>

</div>

<?php
JText::script('VAP_WIDGET_POSITION_EXISTS_ERR');
?>

<script>

	var positionValidator;

	(function($) {
		'use strict';

		$(function() {
			positionValidator = new VikFormValidator('#inspector-position-form');

			positionValidator.addCallback(function() {
				// get position input
				var input = $('#inspector-position-form input[name="position_name"]');

				// get position value
				var data = getPositionData();

				// make sure the position is not empty
				if (!data.position) {
					positionValidator.setInvalid(input);

					return false;
				}
				// make sure the position doesn't already exist
				else if ($('.widgets-position-row[data-position="' + data.position + '"]').length) {
					positionValidator.setInvalid(input);

					// inform the user that the position already exists
					alert(Joomla.JText._('VAP_WIDGET_POSITION_EXISTS_ERR'));

					return false;
				}

				// position is ok
				positionValidator.unsetInvalid(input);

				return true;
			});

		});
	})(jQuery);

	function clearPositionForm() {
		jQuery('#inspector-position-form input[name="position_name"]').val('');
	}

	function getPositionData() {
		var data = {};

		// get specified position
		data.position = jQuery('#inspector-position-form input[name="position_name"]').val();

		// strip any non supported character
		data.position = data.position.replace(/[^a-zA-Z0-9_-]/g, '');

		return data;
	}

</script>
