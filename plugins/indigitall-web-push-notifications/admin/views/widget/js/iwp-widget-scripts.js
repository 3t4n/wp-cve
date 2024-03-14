document.addEventListener('DOMContentLoaded', function() {
	toggleSendStatus();
	selectImage();
	editImage();
	deleteImage();
	toggleTopicList();

	function toggleSendStatus() {
		let check = document.getElementById('iwpWidgetSend');
		let form = document.getElementById('iwpWidgetForm');

		if (check && form) {
			check.addEventListener('click', function() {
				form.classList.add('iwp-hide');
				if (check.checked) {
					form.classList.remove('iwp-hide');
				}
			});
		}
	}

	function selectImage() {
		let button = document.getElementById('iwpWidgetImageSelect');
		if (button) {
			button.addEventListener('click', function() {
				openImageFromLibrary();
			});
		}
	}

	function editImage() {
		let button = document.getElementById('iwpWidgetImageEdit');
		if (button) {
			button.addEventListener('click', function() {
				openImageFromLibrary();
			});
		}
	}

	function deleteImage() {
		let button = document.getElementById('iwpWidgetImageDelete');
		if (button) {
			button.addEventListener('click', function() {
				let select = document.getElementById('iwpWidgetImageSelect');
				let buttons = document.getElementById('iwpWidgetImageButtons');
				let imageId = document.getElementById('iwpWidgetImageId');
				imageId.value = '';
				select.style.backgroundImage = '';
				select.classList.add('iwp-empty');
				buttons.classList.add('iwp-empty');
			});
		}
	}

	function toggleTopicList() {
		let topicRadios = document.querySelectorAll('input[name="iwpWidgetTopics"]');
		let topicList = document.getElementById('iwpWidgetTopicsList');

		if (topicRadios) {
			Array.from(topicRadios).forEach(function (topicRadio) {
				topicRadio.addEventListener("click", function () {
					const newValue = this.value;
					topicList.classList.remove('iwp-show');
					if (newValue === '1') {
						topicList.classList.add('iwp-show');
					}
				});
			});
		}
	}

	/***** FUNCIONES EXTRAS *****/

	/**
	 * Abre la biblioteca de WordPress para seleccionar una imagen
	 */
	function openImageFromLibrary() {
		let select = document.getElementById('iwpWidgetImageSelect');
		let buttons = document.getElementById('iwpWidgetImageButtons');
		let imageId = document.getElementById('iwpWidgetImageId');
		let selectedImageId = (imageId.value !== '') ? imageId.value : null;
		let acceptedImageTypes = ['image/jpeg', 'image/png'];

		document.getElementById('iwpWidgetImageMimes').classList.remove('iwp-error');

		let wpMedia = wp.media({
			title: 'Upload Image',
			multiple: false
		});

		if (selectedImageId !== null) {
			// Solamente preseleccionaremos una imagen, si tenemos su identificador. De lo contrario abriremos sin preselección
			wpMedia.on('open', function () {
				let selection = wpMedia.state().get('selection');
				let selected = selectedImageId; // la id de la imagen
				if (selected) {
					selection.add(wp.media.attachment(selected));
				}
			});
		}

		wpMedia.on('select', function() {
			let images = wpMedia.state().get('selection');
			if (images.length === 0) {
				// Si no hay ninguna imagen seleccionada, no hacemos nada
				return true;
			}
			// Cogemos la primera imagen del array aunque solamente debería haber uno
			let image = images.shift();
			let imageObj = image.toJSON();
			printConsoleLogOnDevelopMode(imageObj);

			if (acceptedImageTypes.includes(imageObj.mime)) {
				imageId.value = imageObj.id;
				select.style.backgroundImage = 'url("' + imageObj.url + '")';
				select.classList.remove('iwp-empty');
				buttons.classList.remove('iwp-empty');
			} else {
				document.getElementById('iwpWidgetImageMimes').classList.add('iwp-error');
			}
		});

		wpMedia.open();
	}
});