<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.plugins
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$value 	  = !empty($displayData['value'])    ? $displayData['value']    : '';
$name 	  = !empty($displayData['name'])     ? $displayData['name']     : uniqid();
$id 	  = !empty($displayData['id'])       ? $displayData['id']       : $name;
$class 	  = !empty($displayData['class'])    ? $displayData['class']    : '';
$format   = !empty($displayData['format'])   ? $displayData['format']   : 'Y-m-d';
$attr 	  = !empty($displayData['attr'])     ? $displayData['attr']     : '';
$showTime = !empty($displayData['showTime']) ? $displayData['showTime'] : false;

?>

<span class="wp-calendar-box">
	
	<input
		type="text"
		name="<?php echo $name; ?>"
		id="<?php echo $id; ?>"
		class="<?php echo $class; ?> wp-datepicker"
		value="<?php echo $value; ?>"
		data-value="<?php echo $value; ?>"
		autocomplete="off"
		<?php echo $attr; ?>
	/>

	<i class="dashicons dashicons-calendar-alt"></i>

</span>

<script>

	(function($) {
		'use strict';

		$('input[name="<?php echo $name; ?>"]').on('change', function() {
			<?php
			if ($showTime)
			{
				?>
				var curr = $(this).val();
				var prev = $(this).attr('data-value');

				if (!curr) {
					// do nothing in case of empty dates
					return;
				}

				// extract time from previous date set
				var time = prev.match(/ (\d{1,2}:\d{1,2})$/);

				if (time && time.length) {
					// extract time from matches
					time = time.pop();
				} else {
					var now = new Date();

					let h = now.getHours();
					let m = now.getMinutes();

					// use current time
					time = (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
				}

				// check if we have a time and the selected date doesn't
				if (!curr.match(/ (\d{1,2}:\d{1,2})$/)) {
					// extract date string to avoid using a wrong string
					var date = curr.match(/^\d{2,4}[.\-\/]\d{2,4}[.\-\/]\d{2,4}/);

					if (date) {
						let matches;

						if (matches = curr.match(/\s(\d{1,2})$/)) {
							// only the hour was provided, adjust it to a valid time
							time = (matches[1].length == 1 ? '0' : '') + matches[1] + ':00';
						}
						else if (matches = curr.match(/\s(\d{1,2})(\d{2,2})$/)) {
							// missing colon, which is not allowed by jQuery datepicker
							time = (matches[1].length == 1 ? '0' : '') + matches[1] + ':' + (matches[2].length == 1 ? '0' : '') + matches[2];
						}

						// append time to current date
						curr = date.pop() + ' ' + time;
					} else {
						// invalid date string
						curr = '';
					}
				}

				// update previous value with current one
				$(this).attr('data-value', curr);
				$(this).val(curr);
				<?php
			}
			?>
		});

		<?php
		if ($showTime)
		{
			?>
			$('input[name="<?php echo $name; ?>"]').on('keyup', function() {
				let curr = $(this).val();

				// auto-adjust the time in case only the hours have been specified
				if (curr.match(/\s(\d{2,2})$/)) {
					$(this).val(curr + ':00');
				}
			});
			<?php
		}
		?>
	})(jQuery);

</script>
