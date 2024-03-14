'use-strict';

(function ($) {

	$(document).ready(function () {

		const editorContainer = document.createElement('div')
		editorContainer.id = "aglEditor"
		editorContainer.style.display = 'none'
		document.body.appendChild(editorContainer)

		// Function to show the tab based on URL hash or the first tab
		function showTabFromHash() {
			var hash = window.location.hash;
			var target;

			if (hash) {
				target = $('.nav-tab[href="' + hash + '"]');
			} else {
				target = $('.nav-tab:first');
			}

			$('.tab-content').hide();
			$(target.attr('href')).show();

			$('.nav-tab').removeClass('nav-tab-active');
			target.addClass('nav-tab-active');

			$('#aglEditor').toggle(hash === '#default-entrance');
		}

		// Show the tab on page load
		showTabFromHash();

		$('.nav-tab').click(function (e) {

			var target = $(this).attr('href');

			$('.tab-content').hide();
			$(target).show();

			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');

			$('#aglEditor').toggle(target === '#default-entrance');

			// Update the URL hash using the href
			history.pushState(null, null, target);

			return false;
		});

		// Handle back button functionality
		window.addEventListener('popstate', function () {
			showTabFromHash();
		});

		const preview = document.querySelector(".agl-preset")
		const container = document.querySelector('.agl-presets-container')

		const presets = agl.defaults.in.presets
		const names = agl.defaults.in.names

		// const names = [
		// 	{ name: 'fadeCSS', title: 'Fade' }
		// ]

		// const presets = {
		// 	fadeCSSLeft: { translate: { x: '50px' } },

		// }

		names.forEach(name => {
			createRow(name)
		});

		function createRow(name) {
			const rowTitle = document.createElement('h2')
			let rowTitleText = name.title
			rowTitleText += name.name.includes('CSS') ? ' CSS' : ' WebGL'
			rowTitle.innerText = rowTitleText
			container.appendChild(rowTitle)
			addItems(name.name)
		}

		function addItems(name) {

			let presetIndex = 0

			for (presetName in presets) {
				if (presetName.includes(name) && name.includes('CSS') == presetName.includes('CSS')) {
					var clonedPreview = preview.cloneNode(true);
					var img = clonedPreview.querySelector('img')
					var play = clonedPreview.querySelector('.agl-play')
					var title = clonedPreview.querySelector('.agl-preset-title')
					for (key2 in names) {
						var obj = names[key2]
						if (presetName.includes(obj.name)) {
							if (presetName.includes('CSS')) {
								title.innerText = obj.title.replace('CSS', '') + ' ' + presetName.replace(obj.name, '').replace('CSS', '') // CSS
							} else if (!obj.name.includes('CSS')) {
								title.innerText = obj.title + ' ' + presetName.replace(obj.name, '')  // WebGL
							}
						}
					}
					// var copy = clonedPreview.querySelector('.agl-copy')
					play.onclick = function () {
						const img = this.parentNode.parentNode.querySelector('img')
						img.plane.animatedIn = false
						// change of class will cause the animation to play
						img.classList.add('agl-in-delay-0')
						img.classList.remove('agl-in-delay-' + img.dataset.aglDelay)
						setTimeout(function () {
							img.classList.remove('agl-in-delay-0')
							img.classList.add('agl-in-delay-' + img.dataset.aglDelay)
						}, 1000)
					}

					img.className += ' agl agl-' + presetName + ' agl-repeat agl-in-delay-' + (presetIndex % 5 * 500)
					img.dataset.aglDelay = presetIndex % 5 * 500
					container.appendChild(clonedPreview)
					clonedPreview.querySelector('.agl-preset-class').innerText = 'agl agl-' + presetName
					presetIndex++;
				}
			}
		}


		preview.remove()

		const animationEditorTab = document.getElementById('default-entrance')
		const playDefault = animationEditorTab.querySelector('.play').addEventListener('click', function(e){
			e.preventDefault()
			animationEditorTab.querySelectorAll('.agl-editor').forEach(function(el){
				el.classList.remove('agl-editor')
			})
			this.classList.add('agl-editor')
			dispatchEvent(new Event('agl-animate'))
		})

		const wpAdminBar = document.getElementById('wpadminbar')

		//position editor after below admin bar
		function positionEditor() {
			editorContainer.style.top = wpAdminBar.offsetHeight + 'px'
		}

		positionEditor()

		window.addEventListener('resize', positionEditor)

		window.addEventListener('agl-editor-init', function (e) {
			const gui = e.detail.gui
			const guiOptions = e.detail.options
			guiOptions['Save'] = function () {
				saveAsDefault(guiOptions, saveAsController.domElement)
			}
			const saveAsController = gui.add(guiOptions, 'Save').name("Save")
		})

		window.addEventListener('agl-init', function () {
			animateGLInstance.options.container = editorContainer
			animateGLInstance.options.in.name = ''
			new AGL.Editor(animateGLInstance.options)
		})

		window.addEventListener('agl-option-change', function (e) {
			// const presetName = e.detail.in.preset
			// if(presetName)
			// 	document.getElementById('agl-entrance-preview-class').innerText = `agl agl-${presetName}`
		})


		function saveAsDefault(options, button) {
			button.style.opacity = .3
			button.style.pointerEvents = 'none'
			const obj = {
				in: {
					duration: options.in.duration,
					delay: options.in.delay,
					easing: options.in.easing,
					type: options.in.type,
					fade: options.in.fade,
					translate: options.in.translate,
					rotate: options.in.rotate,
					scale: options.in.scale,
					corners: options.in.corners,
					clipPath: options.in.clipPath
				}
			}
			const json = JSON.stringify(obj)

			$.ajax({

				type: "POST",
				url: agl_options[2],
				data: {
					json: json,
					security: agl_localize_script[0],
					action: "agl_json"
				},
	
				success: function (data, textStatus, jqXHR) {

					button.style.opacity = 1
					button.style.pointerEvents = ''
	
				},
	
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert("Status: " + textStatus);
					alert("Error: " + errorThrown);

					button.style.opacity = 1
					button.style.pointerEvents = ''
				}
			})
		}
	})


})(jQuery)






