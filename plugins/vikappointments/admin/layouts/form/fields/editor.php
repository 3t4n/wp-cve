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

$name    = !empty($displayData['name'])   ? $displayData['name']    : 'name';
$value   = isset($displayData['value'])   ? $displayData['value']   : '';
$type    = isset($displayData['editor'])  ? $displayData['editor']  : null;
$width   = isset($displayData['width'])   ? $displayData['width']   : '100%';
$height  = isset($displayData['height'])  ? $displayData['height']  : 550;
$rows    = isset($displayData['rows'])    ? $displayData['rows']    : 30;
$cols    = isset($displayData['cols'])    ? $displayData['cols']    : 30;
$buttons = isset($displayData['buttons']) ? $displayData['buttons'] : true;

$use_tags_highlighter = !empty($displayData['use_tags_highlighter']);

// get system editor (or the specified one)
$editor = VAPApplication::getInstance()->getEditor($type);

// in case of tags highlighter, wraps all the strings between the
// curly brackets into a span, so that it is possible to apply some
// custom styles at runtime
if ($use_tags_highlighter)
{
	$value = preg_replace_callback("/{[a-zA-Z0-9_]+}/", function($match)
	{
		return '<span class="highlight-tag">' . $match[0] . '</span>';
	}, $value);
}

// display editor
echo $editor->display($name, $value, $width, $height, $rows, $cols, $buttons);

if ($use_tags_highlighter)
{
	?>
	<script>
		(function($) {
			'use strict';

			/**
			 * Callback used to inject some custom CSS styles into the
			 * head of the given TinyMCE editor.
			 *
			 * @param 	object  editor  The TinyMCE instance.
			 */
			const autoHighlightEditorTags = (editor) => {
				const styles = '.highlight-tag { background-color: #FBEEB8; }'; 

				// create style document
				const css = document.createElement('style'); 
				css.type = 'text/css'; 

				if (css.styleSheet) {
					// add support for IE
					css.styleSheet.cssText = styles; 
				} else {
					css.appendChild(document.createTextNode(styles)); 
				}

				// register style into the head of the TinyMCE iframe
				editor.contentDocument.head.appendChild(css);
			}

			$(function() {
				// make sure TinyMCE exists
				if (typeof tinymce !== 'undefined') {
					const editorName = '<?php echo addslashes($name); ?>';

					onInstanceReady(() => {
						// make sure the editor has been registered
						if (!Joomla.editors.instances.hasOwnProperty(editorName)) {
							return false;
						}

						// make sure the TinyMCE instance has been attached to the editor element
						if (!Joomla.editors.instances[editorName].instance) {
							return false;
						}

						// wait until the document of the editor is ready
						if (!Joomla.editors.instances[editorName].instance.contentDocument) {
							return false;
						}

						// return the TinyMCE instance
						return Joomla.editors.instances[editorName].instance;
					}).then((editor) => {
						// register custom styles
						autoHighlightEditorTags(editor);
					});
				}
			});
		})(jQuery);
	</script>
	<?php
}
