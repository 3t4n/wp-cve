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

$vik 	= VAPApplication::getInstance();
$config = VAPFactory::getConfig();

$calendar = $this->calendar->calendar;

?>

<!-- SERVICES -->

<?php echo $vik->openEmptyFieldset(); ?>
		
	<div class="cal-services-list">
		<?php
		foreach ($this->services as $i => $s)
		{
			$checked = empty($this->filters['services']) || in_array($s->id, $this->filters['services']) ? 'checked="checked"' : '';

			?>
			<div class="cal-service" style="<?php echo $i >= 10 ? 'display: none;' : ''; ?>">
				<span class="check">
					<input type="checkbox" name="services[]" id="service-checkbox<?php echo $s->id; ?>" 
						value="<?php echo $s->id; ?>" <?php echo $checked; ?> />
				</span>

				<span class="name">
					<label for="service-checkbox<?php echo $s->id; ?>"><?php echo $s->name; ?></span>
				</span>

				<span class="color-thumb clickable" data-id="<?php echo $s->id; ?>" data-hex="<?php echo $s->color; ?>" style="background-color: #<?php echo $s->color; ?>;">&nbsp;</span>
			</div>
			<?php
		}

		if (count($this->services) >= 10)
		{
			?>
			<div class="show-all-services">
				<button type="button" class="btn" onclick="jQuery('.cal-service').show();jQuery(this).hide();"><?php echo JText::translate('VAPSHOWALL'); ?></button>
			</div>
			<?php
		}
		?>
	</div>

<?php echo $vik->closeEmptyFieldset(); ?>

<!-- EVENTS -->

<?php echo $vik->openEmptyFieldset(); ?>
	
	<div class="cal-events-list">

		<?php
		if (!$calendar->has())
		{
			?>
			<p class="no-event"><?php echo JText::translate('JGLOBAL_NO_MATCHING_RESULTS'); ?></p>
			<?php
		}
		else
		{
			$max_events = 5;
			$i = 0;

			foreach ($calendar->getEventsList() as $i => $event)
			{
				$dt_str = JHtml::fetch('date', $event->checkin_ts, $config->get('dateformat'));
				$tm_str = JHtml::fetch('date', $event->checkin_ts, $config->get('timeformat'));
				?>
				<div class="event-row" style="<?php echo $i >= $max_events ? 'display: none;' : ''; ?>">

					<div class="event-id">
						<span><?php echo $event->id; ?></span>
					</div>

					<div class="event-checkin">
						<span><?php echo $dt_str; ?></span>
						<span>
							<span>
								<a href="javascript:void(0)" onclick="goToSlot(<?php echo $event->id; ?>);"><?php echo '@' . $tm_str; ?></a>
							</span>
							<span class="duration"><?php echo VikAppointments::formatMinutesToTime($event->duration); ?></span>
						</span>
					</div>

					<div class="event-details">
						<span class="service-name" style="color: #<?php echo $event->service_color; ?>;"><?php echo $event->service_name; ?></span>
						<span><?php echo $event->employee_name; ?></span>
					</div>

					<div class="event-customer">
						<span><?php echo $event->purchaser_nominative; ?></span>
					</div>

					<div class="event-guests">
						<?php
						if ($event->people > 1)
						{
							?>
							<span><?php echo $event->people; ?> <i class="fas fa-users"></i></span>
							<?php
						}
						?>
					</div>

				</div>
				<?php
			}

			if ($i >= $max_events)
			{
				?>
				<div class="show-all-events">
					<button type="button" class="btn" onclick="jQuery('.event-row').show();jQuery(this).hide();"><?php echo JText::translate('VAPSHOWALL'); ?></button>
				</div>
				<?php
			}
		}
		?>

	</div>

<?php echo $vik->closeEmptyFieldset(); ?>

<script>

	(function($) {
		'use strict';

		let POINTER_TIMER = null;

		window['goToSlot'] = (id) => {
			$('td div[data-id]').each(function() {
				var ids = $(this).data('id');

				if (!isNaN(ids)) {
					ids = [ids];
				} else {
					ids = ids.split(',');
				}

				for (var i = 0; i < ids.length; i++) {
					if (ids[i] == id) {
						animateSlot(this);
						return false;
					}
				}
			});
		}

		const animateSlot = (slot) => {
			// animate only in case the slot is not visible
			var px_to_scroll = isBoxOutOfMonitor($(slot), 50);
				
			if (px_to_scroll !== false) {
				let op = px_to_scroll > 0 ? '+=' : '-=';
				px_to_scroll = Math.abs(px_to_scroll) + $(slot).height() / 2 + 50;
				$('html,body').animate( {scrollTop: op + px_to_scroll}, {duration:'normal'} );
			}

			var pointer = $('.slot-pointer');
			var offset  = $(slot).offset();

			var top  = offset.top + $(slot).height() / 2 - pointer.height() / 2;
			var left = offset.left + $(slot).width() + 30;

			pointer.css('top', top + 'px');
			pointer.css('left', left + 'px');

			pointer.show();

			if (POINTER_TIMER) {
				clearTimeout(POINTER_TIMER);
			}
			
			POINTER_TIMER = setTimeout(function() {
				// pointer.hide();
			}, 2000);
		}

		// delay submit on service checkbox change by 2 seconds
		const debounceCheckboxChange = VikTimer.debounce('serviceCheckboxChange', () => {
			document.adminForm.submit();
		}, 2000);

		$(function() {
			var THUMB_ELEM  = null;
			var THUMB_COLOR = null;

			const changeThumbColor = (color, elem) => {
				$(elem).attr('data-hex', color);
				$(elem).css('background-color', '#' + color);

				var id_service = $(elem).data('id');

				$('td div[data-service="' + id_service + '"]').css('background-color', '#' + color);

				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=service.changecolorajax'); ?>',
					{
						id:    id_service,
						color: color,
					}
				);
			};

			$('.color-thumb.clickable').ColorPicker({
				onShow: function() {
					THUMB_COLOR = $(this).attr('data-hex');
					$(this).ColorPickerSetColor('#' + THUMB_COLOR.toUpperCase());

					THUMB_ELEM = this;
				},
				onChange: function (hsb, hex, rgb) {
					THUMB_COLOR = hex;
				},
				onHide: function() {
					if (THUMB_COLOR.toUpperCase() != $(THUMB_ELEM).attr('data-hex').toUpperCase()) {
						changeThumbColor(THUMB_COLOR, THUMB_ELEM);
					}
				}
			});

			/**
			 * Append slot pointer at the end of the body to prevent unexpected
			 * translations because of parent elements with relative position.
			 *
			 * @since 1.7
			 */
			$('body').append('<div class="slot-pointer" style="display: none;"></div>');

			// submit form when the status of a service checkbox changes
			$('#adminForm input[name="services[]"]').on('change', debounceCheckboxChange);
		});
	})(jQuery);

</script>
