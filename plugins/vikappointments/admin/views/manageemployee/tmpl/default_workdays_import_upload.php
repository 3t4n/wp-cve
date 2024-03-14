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

<div class="vap-media-droptarget" id="worktime-upload-box" style="position: relative;">
	<p class="icon">
		<i class="fas fa-upload" style="font-size: 48px;"></i>
	</p>

	<div class="lead">
		<a href="javascript: void(0);" id="worktime-upload-file"><?php echo JText::translate('VAPMANUALUPLOAD'); ?></a>&nbsp;<?php echo JText::translate('VAPFILEDRAGDROP'); ?>
	</div>

	<p class="maxsize">
		<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', JHtml::fetch('vikappointments.maxuploadsize')); ?>
	</p>

	<input type="file" id="worktime-legacy-upload" multiple style="display: none;"/>

	<div class="vap-upload-progress" style="position: absolute; bottom: 6px; right: 6px; display: flex; visibility: hidden;">
		<progress value="0" max="100">0%</progress>
	</div>
</div>

<div class="worktime-import-preview" style="display: none;">

</div>

<?php
JText::script('VAPMANAGEWD5');
JText::script('VAP_WORKTIME_IMPORT_DISABLED_WHY');
JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>
	(function($) {
		'use strict';

		let dragCounter = 0;
		let importData  = null;

		const fetchPreview = (file) => {
			const progressBox = $('#worktime-upload-box.vap-media-droptarget .vap-upload-progress');

			importData = null;

			doUpload(file, {layout: 'preview'}, progressBox).then((data) => {
				importData = data;

				let ul = $('<ul></ul>');

				for (let k in data) {
					if (!data.hasOwnProperty(k)) {
						continue;
					}

					let alreadyExists = $('#spday-fieldset-' + importData[k].ymd).length;

					let dateLi = $('<li class="wd-date"></li>');

					let checkboxDate = $('<input type="checkbox" value="1" checked />');
					checkboxDate.attr('id', 'wd_import_' + k);

					let labelDate = $('<label class="vap-disable-selection"></label>')
						.text(k)
						.attr('for', 'wd_import_' + k);

					if (alreadyExists) {
						checkboxDate.prop('disabled', true);

						labelDate.attr('title', Joomla.JText._('VAP_WORKTIME_IMPORT_DISABLED_WHY'));
						labelDate.addClass('hasTooltip');
					}

					dateLi.append(checkboxDate);
					dateLi.append(labelDate);

					if (data[k].times.length) {
						// open
						dateLi.append(
							$('<i class="fas fa-check-circle ok hasTooltip"></i>')
								.attr('title', Joomla.JText._('VAPMANAGEWD5'))
						);

						let dateLiUl = $('<ul></ul>');

						data[k].times.forEach((time, index) => {
							let timeLi = $('<li class="wd-time"></li>');

							let checkboxTime = $('<input type="checkbox" value="1" checked />');
							checkboxTime.attr('id', 'wd_import_' + k + '_' + index);

							if (alreadyExists) {
								checkboxTime.prop('disabled', true);
							}

							timeLi.append(checkboxTime);
							timeLi.append(
								$('<label class="vap-disable-selection"></label>')
									.text(time.fromTime + ' - ' + time.toTime)
									.attr('for', 'wd_import_' + k + '_' + index)
							);

							dateLiUl.append(timeLi);
						});

						dateLi.append(dateLiUl);
					} else {
						// closed
						dateLi.append(
							$('<i class="fas fa-dot-circle no hasTooltip"></i>')
								.attr('title', Joomla.JText._('VAPMANAGEEMPLOYEE22'))
						);
					}

					ul.append(dateLi);
				}

				ul.find('.hasTooltip').tooltip({
					container: 'body',
				});

				$('#worktime-upload-box').hide();
				$('.worktime-import-preview').html(ul).show();

				$('#wdimport-save').prop('disabled', false);
			});
		}

		const execImport = () => {
			if (!importData) {
				return;
			}

			for (let k in importData) {
				if (!importData.hasOwnProperty(k)) {
					continue;
				}

				if ($('#spday-fieldset-' + importData[k].ymd).length) {
					// fieldset already exists, avoid overwrite
					continue;
				}

				if (!$('input[id="wd_import_' + k + '"]').is(':checked')) {
					// working date ignored 
					continue;
				}

				let json = [];

				if (importData[k].times.length === 0) {
					importData[k].times.push({
						closed: 1,
						fromts: 0,
						endts:  0,
					});
				}

				importData[k].times.forEach((time, index) => {
					let checkbox = $('input[id="wd_import_' + k + '_' + index + '"]');

					if (!checkbox.length || checkbox.is(':checked')) {
						json.push({
							id:     0,
							closed: time.closed,
							from:   time.fromts,
							to:     time.endts,
							date:   importData[k].date,
							ymd:    importData[k].ymd,
						});
					}
				});

				if (json.length) {
					let fieldset = vapAddSpecialDayCard(json);
					vapRefreshWorkingDayCard(fieldset, json);
				}
			}

			vapCloseJModal('wdimport');
		}

		const doUpload = (file, request, progressBox) => {
			return new Promise((resolve, reject) => {
				const formData = new FormData();
				formData.append('file', file);

				for (let k in request) {
					if (request.hasOwnProperty(k)) {
						formData.append(k, request[k]);
					}
				}

				if (progressBox) {
					progressBox.css('visibility', 'visible');
				}

				UIAjax.upload(
					// end-point URL
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=employee.importworkdays'); ?>',
					// file post data
					formData,
					// success callback
					(data) => {
						resolve(data);
					},
					// failure callback
					(error) => {
						alert(error.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));

						if (progressBox) {
							progressBox.css('visibility', 'hidden');
						}

						reject(error);
					},
					// progress callback
					(progress) => {
						if (progressBox) {
							// update progress
							progressBox.find('progress').val(progress).text(progress + '%');
						}
					}
				);
			});
		}

		$(function() {
			// drag&drop actions on target div

			$('#worktime-upload-box.vap-media-droptarget').on('drag dragstart dragend dragover dragenter dragleave drop', (e) => {
				e.preventDefault();
				e.stopPropagation();
			});

			$('#worktime-upload-box.vap-media-droptarget').on('dragenter', function(e) {
				// increase the drag counter because we may
				// enter into a child element
				dragCounter++;

				$(this).addClass('drag-enter');
			});

			$('#worktime-upload-box.vap-media-droptarget').on('dragleave', function(e) {
				// decrease the drag counter to check if we 
				// left the main container
				dragCounter--;

				if (dragCounter <= 0) {
					$(this).removeClass('drag-enter');
				}
			});

			$('#worktime-upload-box.vap-media-droptarget').on('drop', function(e) {
				$(this).removeClass('drag-enter');
				
				fetchPreview(e.originalEvent.dataTransfer.files[0]);
			});

			$('#worktime-upload-box.vap-media-droptarget #worktime-upload-file').on('click', function() {
				// unset selected files before showing the dialog
				$('input#worktime-legacy-upload').val(null).trigger('click');
			});

			$('#worktime-upload-box.vap-media-droptarget input#worktime-legacy-upload').on('change', function() {
				fetchPreview($(this)[0].files[0]);
			});

			$('#wdimport-save').on('click', () => {
				execImport();
			});

			$('#jmodal-wdimport').on('hidden', function(event) {
				// make sure the target is the modal because Bootstrap may trigger the same event
				// also for a different element placed within the modal (such as a tooltip)
				if ($(event.target).is(this)) {
					$('#worktime-upload-box.vap-media-droptarget .vap-upload-progress').css('visibility', 'hidden');
					$('.worktime-import-preview').html('').hide();
					$('#worktime-upload-box').show();
					$('#wdimport-save').prop('disabled', true);
				}
			});

			$('#jmodal-wdimport').on('click', 'input[id^="wd_import_"]', function() {
				let ul = $(this).nextAll('ul');

				if (ul.length) {
					ul.find('input[id^="' + $(this).attr('id') + '"]').prop('disabled', $(this).is(':checked') ? false : true);
				}
			});
		});
	})(jQuery);
</script>
