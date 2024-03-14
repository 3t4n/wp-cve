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

?>

<style>
	iframe#customizer-preview {
		min-height: 600px;
		width: 60%;
		margin-left: 15px;
		border: 1px solid #ddd;

		transition: all 400ms ease-in-out 0s;
		-moz-transition: all 400ms ease-in-out 0s;
		-webkit-transition: all 400ms ease-in-out 0s;
		-o-transition: all 400ms ease-in-out 0s;
	}
	iframe#customizer-preview.__hide {
		width: 0 !important;
		border: 0 !important;
		margin-left: 0 !important;
	}
	#vaptabview4 .config-panel-tabview {
		display: flex;
		flex-wrap: wrap;
	}
	#vaptabview4 .config-panel-tabview-inner {
		flex: 1;
	}

	@media screen and (max-width: 938px) {
		iframe#customizer-preview {
			width: 100%;
			margin: 15px 0 0 0;
		}
		iframe#customizer-preview.__hide {
			display: none;
		}
	}
</style>

<iframe id="customizer-preview" src="<?php echo $this->filters['preview_page']; ?>" class="hidden-phone<?php echo $this->filters['preview_status'] ? '' : ' __hide'; ?>"></iframe>

<script>
	(function($) {
		'use strict';

		window['getCustomizerPreview'] = () => {
			return onInstanceReady(() => {
				// load iframe
				const iframe = $('iframe#customizer-preview');

				if (!iframe) {
					// iframe not yet ready
					return false;
				}

				// load iframe contents
				const iframeContents = iframe.contents();

				if (!iframeContents) {
					// cannot access iframe
					return false;
				}

				// access the root of the iframe preview
				return iframeContents[0].querySelector(':root');
			});
		}

		window['applyCustomizerPreviewCSS'] = () => {
			getCustomizerPreview().then((preview) => {
				// delete existing custom CSS file
				$(preview).find('head link[href*="assets/css/vap-custom.css"]').remove();

				// get existing inline block
				let inlineBlock = $(preview).find('head style#vap-customizer-inline-css');

				if (!inlineBlock.length) {
					// create style block
					inlineBlock = $('<style id="vap-customizer-inline-css"></style>');
					// append block into the head
					$(preview).find('head').append(inlineBlock);
				}

				// update CSS code
				inlineBlock.html(Joomla.editors.instances.custom_css_code.getValue());
			});
		}

		window['refreshCustomizerEnvironmentVars'] = () => {
			getCustomizerPreview().then((preview) => {
				$('[name^="customizer["]').each(function() {
					// extract CSS var from name
					const match = $(this).attr('name').match(/^customizer\[(.*?)\]$/);
					const key   = match[1];

					// extract value from input
					let value = $(this).val();

					if (key.match(/-(?:color|background|border)$/)) {
						// sanitize HEX color
						value = '#' + value.replace(/^#/, '');
					}

					// overwrite CSS var
					preview.style.setProperty(key, value);
				});
			});
		}

		window['toggleCustomizerPreview'] = () => {
			const iframe = $('iframe#customizer-preview');

			let status = 1;

			if (iframe.hasClass('__hide')) {
				iframe.removeClass('__hide');
			} else {
				iframe.addClass('__hide');
				status = 0;
			}

			return status;
		}

		window['changeCustomizerPreviewPage'] = (url) => {
			$('iframe#customizer-preview').attr('src', url);
		}

		$(function() {
			$('[name^="customizer["]').on('change', function() {
				getCustomizerPreview().then((preview) => {
					// extract CSS var from name
					const match = $(this).attr('name').match(/^customizer\[(.*?)\]$/);
					const key   = match[1];

					// extract value from input
					let value = $(this).val();

					if (key.match(/-(?:color|background|border)$/)) {
						// sanitize HEX color
						value = '#' + value.replace(/^#/, '');
					}

					// overwrite CSS var
					preview.style.setProperty(key, value);
				});
			});

			// refresh custom contents every time the preview page changes
			$('iframe#customizer-preview').on('load', () => {
				// refresh custom CSS
				applyCustomizerPreviewCSS();
				// refresh environment vars
				refreshCustomizerEnvironmentVars();
			});
		});
	})(jQuery);
</script>
