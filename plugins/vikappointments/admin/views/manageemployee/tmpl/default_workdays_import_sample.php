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

// load list of supported types
VAPLoader::import('libraries.worktime.import.manager');
$types = VAPWorktimeImportManager::getSupportedTypes();

$vik = VAPApplication::getInstance();

if (!$types)
{
	// no supported types...
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return;
}

$lookup = array_map(function($t)
{
	return $t->getSample();
}, $types);

?>

<div class="vap-worktime-import-wrapper">

	<div class="import-types">
		<div class="btn-group">
			<?php
			foreach ($types as $k => $t)
			{
				?>
				<button type="button" class="btn" data-type="<?php echo $this->escape($k); ?>">
					<?php echo strtoupper($k); ?>
				</button>
				<?php
			}
			?>
		</div>
	</div>

	<div class="import-editor" id="wd-import-editor" style="display: none;">
		<?php echo $vik->getEditor('codemirror')->display('worktime_editor_sample', '', '100%', 550, 30, 30, false); ?>
	</div>

	<?php echo $vik->alert(JText::translate('VAP_WORKTIME_IMPORT_SAMPLE_TIP'), 'info', $dismiss = false, ['id' => 'wd-import-tip']); ?>

</div>

<script>

	(function($) {
		'use strict';

		// create lookup
		const typesLookup = <?php echo json_encode($lookup); ?>;

		$(function() {
			$('.vap-worktime-import-wrapper .import-types button[data-type]').on('click', function() {
				let type = $(this).data('type');

				if (!typesLookup.hasOwnProperty(type)) {
					return false;
				}

				$('#wd-import-tip').hide();
				$('#wd-import-editor').show();

				$(this).siblings().removeClass('active');
				$(this).addClass('active');

				let editor = Joomla.editors.instances.worktime_editor_sample;

				// update value
				editor.setValue(typesLookup[type]);

				// check if we have code mirror
				if (editor.element && editor.element.codemirror) {
					editor = editor.element.codemirror;
				}

				if (editor.setOption) {
					// update language mode
					editor.setOption('mode', type === 'json' ? 'javascript' : type);
				}

				if (editor.refresh) {
					editor.refresh();
				}
			});
		});
	})(jQuery);

</script>
