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

<div class="config-fieldset">

	<div class="config-fieldset-body">
		<?php
		// display CSS code editor
		echo $vik->getCodeMirror('custom_css_code', $this->customizerModel->getCustomCSS(), ['syntax' => 'css']);
		?>
	</div>

</div>

<script>
	(function($) {
		'use strict';

		let editor;

		$(function() {
			onInstanceReady(() => {
				// wait until the editor is accessible
				return Joomla.editors.instances.custom_css_code;
			}).then((e) => {
				// register internal property
				editor = e;

				// check if we have a code mirror
				if (editor.element && editor.element.codemirror) {
					editor = editor.element.codemirror;
				}

				if (editor.on) {
					editor.on('keyup', VikTimer.debounce('customizer-preview-custom-css', applyCustomizerPreviewCSS, 1000));
				}

				$('li[data-id="vap_customizer_tab_additionalcss"]').on('click', function() {
					setTimeout(() => {
						if (editor.refresh) {
							editor.refresh();
						}
					}, 256);
				});
			});
		});
	})(jQuery);
</script>
