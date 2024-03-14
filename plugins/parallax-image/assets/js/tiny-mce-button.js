(function () {
	tinymce.PluginManager.add('dd_mce_button', function (editor, url) {
		editor.addButton('dd_mce_button', {
			icon: 'wp-format-image',
			tooltip: 'Insert Parallax Shortcode',
			onclick: function () {
				editor.windowManager.open({
					title: 'Insert a Parallax Image Shortcode',
					body: [{
							type: 'container',
							html: '<h3 class="duck-px">Required Elements</h4><hr class="duck-px"/>'
						}, {
							type: 'textbox',
							name: 'image',
							label: 'Parallax Image',
							value: ''
						}, {
							type: 'container',
							html: '<p class="help">Required Element. Include either a FULL URL, or just the image name, like &ldquo;my-image.jpg&rdquo;</p>'
						}, {
							type: 'textbox',
							name: 'height',
							label: 'Height of image in pixels',
							value: '500'
						}, {
							type: 'container',
							html: '<p class="help">Do not enter the &ldquo;px&rdquo; it is already understood</p><br /><hr class="duck-px" /><h3 class="duck-px" style="margin-top: 20px;">Optional Components</h3>'
						}, {
							type: 'container',
							html: '<p>&nbsp;</p>'
						}, {
							type: 'textbox',
							name: 'speed',
							label: 'Speed',
							value: '2'
						},

						{
							type: 'textbox',
							name: 'zindex',
							label: 'Z-Index',
							value: '0'
						}, {
							type: 'textbox',
							name: 'mobile',
							label: 'Enter the mobile image if desired.',
							value: ''
						}, {
							type: 'textbox',
							name: 'position',
							label: 'Position',
							value: 'left'
						}, {
							type: 'textbox',
							name: 'offset',
							label: 'Offset (true,false)',
							value: 'false'
						}, {
							type: 'textbox',
							name: 'textPos',
							label: 'Text Position (top,center,bottom)',
							value: 'top'
						}
					],

					onsubmit: function (e) {
						var theButton = '[dd-parallax img="' + e.data.image + '" height="' + e.data.height + '" speed="' + e.data.speed + '" z-index="' + e.data.zindex + '" position="' + e.data.position + '" offset="' + e.data.offset + '" text-pos="' + e.data.textPos +'"';
						if (e.data.mobile) {
							theButton += ' mobile="' + e.data.mobile + '"';
						}
						theButton += ']insert your content to be over the parallax image[/dd-parallax]';
						editor.insertContent(theButton);
					}
				});
			}

		});
	});
})();
