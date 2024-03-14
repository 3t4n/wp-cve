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

JHtml::fetch('bootstrap.tooltip', '.vapseroptname .opt-label-wrapper');

// the options are always grouped by category
$groups = isset($displayData['options']) ? $displayData['options'] : array();

$vik = VAPApplication::getInstance();

$currency = VAPFactory::getCurrency();

?>

<div class="vapseroptionscont <?php echo $vik->getThemeClass('background'); ?>" style="display: none;">

	<?php
	$count = count($groups);

	foreach ($groups as $i => $group)
	{
		?>
		<div class="vapseroptiongroup">
			<div class="vapseroptionsheader<?php echo $count > 1 ? ' toggle-link' : ''; ?>">
				<?php
				if ($count > 1)
				{
					?>
					<i class="fas fa-chevron-<?php echo $i == 0 ? 'down' : 'right'; ?>"></i>
					<?php
				}

				if ($group->name)
				{
					// use the category name
					echo $group->name;
				}
				else
				{
					// use default category name (available options)
					echo JText::translate('VAPSERAVAILOPTIONSTITLE');
				}
				?>
			</div>

			<div class="vapseroptionsdiv" style="<?php echo $i == 0 ? '' : 'display:none;'; ?>">
				
				<?php

				if ($group->description)
				{
					?>
					<div class="vapseroptiongroupdesc"><?php echo $group->description; ?></div>
					<?php
				}

				foreach ($group->options as $o)
				{ 
					$name     = $o->name;
					$price    = $o->price;
					$duration = $o->duration;

					if (count($o->variations))
					{
						// include price of the first available variation
						$price += $o->variations[0]->price;
						// include extra duration of the first available variation
						$duration += $o->variations[0]->duration;
					}

					if ($o->required)
					{
						// flag as required
						$name .= '*';
					}
					?>
					<div class="vapsersingoption" data-id="<?php echo $o->id; ?>">
						
						<div class="vapseroptrow">
							
							<?php
							if ($o->displaymode == 2 && $o->image)
							{
								// display option image before the name
								?>
								<span class="vapseroptimage left-side">
									<a href="javascript:void(0)" class="vapmodal" onClick="vapOpenModalImage('<?php echo VAPMEDIA_URI . $o->image; ?>', this);">
										<?php
										// render image tag
										echo JHtml::fetch('vaphtml.media.display', $o->image, [
											'loading' => 'lazy',
											'alt'     => $o->name,
											'small'   => true,
										]);
										?>
									</a>
								</span>
								<?php
							}
							?>

							<span class="vapseroptname">
								<span class="opt-label-wrapper" title="<?php echo $this->escape($o->description); ?>">
									<?php
									if ($o->displaymode == 1 && $o->image)
									{
										// open the image in a modal box after clicking the option name
										?>
										<a href="javascript:void(0)" class="vapmodal <?php echo ($o->required ? 'option-required' : ''); ?>" 
											<?php echo ($o->required ? 'id="vapreqopt' . $o->id . '"' : ''); ?>
											onClick="vapOpenModalImage('<?php echo VAPMEDIA_URI . $o->image; ?>', this);">
											<?php
											echo $name;

											// render image tag
											echo JHtml::fetch('vaphtml.media.display', $o->image, [
												'loading' => 'lazy',
												'alt'     => $o->name,
												'small'   => true,
												'style'   => 'display: none;',
											]);
											?>
										</a>
										<?php
									}
									else
									{
										// just use a label to toggle the checkbox
										?>
										<label 
											class="<?php echo ($o->required ? 'option-required' : ''); ?>"
											<?php echo ($o->required ? 'id="vapreqopt' . $o->id . '"' : ''); ?>
											for="vapoptchbox<?php echo $o->id; ?>"><?php echo $name; ?></label>
										<?php
									}
									?>
								</span>
							</span>

							<?php
							if ($o->variations)
							{
								?>
								<div class="vapseropt-variations">
									<select id="vapoptvar<?php echo $o->id; ?>" class="vap-optvar-sel" onChange="vapOptionVarValueChanged(<?php echo $o->id; ?>);">
										<?php
										foreach ($o->variations as $var)
										{
											$var_price = $o->price + $var->price;

											if ($var_price != 0)
											{
												// display option price
												$tot_price_label = $currency->format($var_price);
											}
											else
											{
												// do not display price
												$tot_price_label = '';
											}

											$var_duration = $o->duration + $var->duration;

											if ($var_duration > 0)
											{
												// display option duration
												$tot_duration_label = '+' . VikAppointments::formatMinutesToTime($var_duration);
											}
											else
											{
												// do not display duration
												$tot_duration_label = '';
											}
											?>
											<option value="<?php echo $var->id; ?>" data-price="<?php echo $this->escape($tot_price_label); ?>" data-duration="<?php echo $this->escape($tot_duration_label); ?>">
												<?php
												echo $var->name;

												if ($var->price != 0)
												{
													echo ' ' . $currency->format($var->price);
												}
												?>
											</option>
											<?php
										}
										?>
									</select>
								</div>
								<?php
							}
							?>

							<?php
							if ($o->duration > 0)
							{
								?>
								<span id="vapseroptduration<?php echo $o->id; ?>" class="vapseroptduration">
									<?php echo '+' . VikAppointments::formatMinutesToTime($duration); ?>
								</span>
								<?php
							}
							?>

							<span id="vapseroptprice<?php echo $o->id; ?>" class="vapseroptprice">
								<?php echo ($price != 0 ? $currency->format($price) : ''); ?>
							</span>

						</div>

						<div class="vapseroptact">
							<?php
							// The name represents the opposite of its purpose.
							// When single is TRUE, the option supports the quantity selection.
							if ($o->single)
							{
								?>
								<input type="number" value="1" size="4" min="1" max="<?php echo $o->maxqpeople ? 1 : $o->maxq; ?>"
									class="option-quantity<?php echo $o->maxqpeople ? ' people-variable' : ''; ?><?php echo $o->maxqpeople == 2 ? ' same-as-people' : ''; ?>"
									id="vapoptmaxq<?php echo $o->id; ?>" onChange="vapQuantityValueChanged(<?php echo $o->id; ?>);" style="max-width: 80px;" />
								<?php
							}
							else
							{
								?>
								<input type="hidden" value="1" class="option-quantity" id="vapoptmaxq<?php echo $o->id; ?>" />	
								<?php
							}
							?>
							<input type="checkbox" value="1" id="vapoptchbox<?php echo $o->id; ?>" class="option-checkbox <?php echo $o->required ? 'required' : ''; ?>" data-id="<?php echo $o->id; ?>" />
							<input type="hidden" value="<?php echo $o->id; ?>" id="vapoptid<?php echo $o->id; ?>" />
						</div>

					</div>
					<?php
				}
				?>

			</div>
		</div>
		<?php
	}
	?>

</div>

<script>

	jQuery(function($) {
		$('.vap-optvar-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200,
		});

		$('#vapserpeopleselect').on('change', function() {
			let people = parseInt($(this).val());
			people = isNaN(people) || people <= 0 ? 1 : people;

			$('.option-quantity.people-variable').each(function() {
				$(this).attr('max', people);

				if ($(this).hasClass('same-as-people')) {
					// option equals to the number of participants
					$(this).attr('min', people).val(people);
				} else {
					// option depending on the number of participants
					let val = parseInt($(this).val());

					if (isNaN(val) || val > people) {
						// auto-adjust the currently selected value
						$(this).val(people);
					}
				}
			});
		}).trigger('change');

		<?php
		if ($count > 1)
		{
			?>
			$('.vapseroptionsheader.toggle-link').on('click', function() {
				const body = $(this).next();

				if (body.is(':visible')) {
					$(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
					body.slideUp('fast');
				} else {
					$(this).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
					body.slideDown('fast');
				}
			});
			<?php
		}
		?>
	});

	function vapGetSelectedOptions() {
		var options = [];

		jQuery('.vapsersingoption').each(function() {
			const checkbox = jQuery(this).find('.option-checkbox');

			// check whether the options was selected
			if (checkbox.is(':checked')) {
				let id_opt = parseInt(jQuery(this).data('id'));
				let id_var = parseInt(jQuery('#vapoptvar' + id_opt).val());

				options.push({
					id:        id_opt,
					quantity:  parseInt(jQuery(this).find('.option-quantity').val()),
					variation: id_var ? id_var : null,
				});
			}
			// nope, make sure it was not required
			else if (checkbox.hasClass('required')) {
				vapMarkRequiredOptions(true);
				throw "MissingRequiredOptionException";
			}
		});
		
		// all options seems valid
		vapMarkRequiredOptions(false);

		return options;
	}

	function vapOptionVarValueChanged(id) {
		jQuery('#vapseroptprice' + id).html(jQuery('#vapoptvar' + id + ' :selected').attr('data-price'));
		jQuery('#vapseroptduration' + id).html(jQuery('#vapoptvar' + id + ' :selected').attr('data-duration'));
		jQuery('#vapoptchbox' + id).prop('checked', true);
	}

	function vapQuantityValueChanged(id) {
		jQuery('#vapoptchbox' + id).prop('checked', true);
	}

	function vapMarkRequiredOptions(s) {
		jQuery('.option-checkbox.required').each(function() {
			var id    = jQuery(this).data('id');
			var label = jQuery('#vapreqopt' + id);

			if (s) {
				label.addClass('vapoptred');

				let wrapper = label.closest('.vapseroptionsdiv');
				if (!wrapper.is(':visible')) {
					wrapper.prev().trigger('click');
				}
			} else {
				label.removeClass('vapoptred');
			}
		});
	}

</script>
