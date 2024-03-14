document.addEventListener('DOMContentLoaded', function() {
    /* EJECUCIÓN DE FUNCIONES AL INICIO */
    refreshPreview();

    /* CARGA DE FUNCIONES DE EVENTOS */
    changeTitule();
    changeBody();
    changeUrl();
    addImage();
    editImage();
    removeImage();

    createWelcomePush();
    updateWelcomePush();
    enableWelcomePush();
    disableWelcomePush();
    /* FUNCIONES DE EVENTOS */

    function changeTitule() {
        let input = document.getElementById('iwpAdminWebPushWelcomeTitle');
        if (input) {
            input.addEventListener("change", function () {
                refreshPreview();
            });
        }
    }

    function changeBody() {
        let input = document.getElementById('iwpAdminWebPushWelcomeBody');
        if (input) {
            input.addEventListener("change", function () {
                refreshPreview();
            });
        }
    }

    function changeUrl() {
        let input = document.getElementById('iwpAdminWebPushWelcomeUrl');
        if (input) {
            input.addEventListener("change", function () {
                refreshPreview();
            });
        }
    }

    function addImage() {
        let button = document.getElementById('iwpAdminWebPushWelcomeAddImage');
        if (button) {
            button.addEventListener("click", function () {
                openImageFromLibrary();
            });
        }
    }

    function editImage() {
        let button = document.getElementById('iwpAdminWebPushWelcomeEditImage');
        let imageId = document.getElementById('iwpAdminWebPushWelcomeImageId');

        if (button && imageId) {
            button.addEventListener("click", function () {
                let selectedImageId = imageId.value;
                if (selectedImageId !== '') {
                    // Solamente podremos editar si tenemos un identificador de imagen definido
                    openImageFromLibrary(selectedImageId);
                }
            });
        }
    }

    function removeImage() {
        let button = document.getElementById('iwpAdminWebPushWelcomeRemoveImage');
        if (button) {
            button.addEventListener("click", function () {
                refreshSelectedImage();
            });
        }
    }

    function createWelcomePush() {
        let button = document.getElementById('iwpAdminWebPushWelcomeCreate');
        if (button) {
            button.addEventListener("click", function () {
                sendWelcomePushAjax('iwp_wp_create_web_push');
            });
        }
    }

    function updateWelcomePush() {
        let button = document.getElementById('iwpAdminWebPushWelcomeUpdate');
        if (button) {
            button.addEventListener("click", function () {
                sendWelcomePushAjax('iwp_wp_update_web_push');
            });
        }
    }

    function enableWelcomePush() {
        let button = document.getElementById('iwpAdminWebPushWelcomeEnable');
        if (button) {
            button.addEventListener("click", function () {
                changeWelcomePushStatusAjax('iwp_wp_web_push_enable');
            });
        }
    }

    function disableWelcomePush() {
        let button = document.getElementById('iwpAdminWebPushWelcomeDisable');
        if (button) {
            button.addEventListener("click", function () {
                changeWelcomePushStatusAjax('iwp_wp_web_push_disable');
            });
        }
    }

    /* FUNCIONES AJAX */

    function changeWelcomePushStatusAjax(action) {
        showHideLoader(true);

        let errorBox = document.getElementById('iwp-admin-error-box');
        errorBox.classList.add('iwp-hide');
        let successAlert = document.getElementById('iwp-admin-success-box');
        successAlert.classList.add('iwp-hide');

        const data = new FormData();
        data.append('action',           action);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                processWebPushResponse(data);
                printConsoleLogOnDevelopMode(data);
            }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }

    function sendWelcomePushAjax(action) {
        showHideLoader(true);

        let errorBox = document.getElementById('iwp-admin-error-box');
        errorBox.classList.add('iwp-hide');
        let successAlert = document.getElementById('iwp-admin-success-box');
        successAlert.classList.add('iwp-hide');

        const data = new FormData();
        data.append('action',           action);
        data.append('isWelcomePush','1');
        data.append('title',            document.getElementById('iwpAdminWebPushWelcomeTitle').value);
        data.append('body',             document.getElementById('iwpAdminWebPushWelcomeBody').value);
        data.append('url',              document.getElementById('iwpAdminWebPushWelcomeUrl').value);
        data.append('imageId',          document.getElementById('iwpAdminWebPushWelcomeImageId').value);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                processWebPushResponse(data);
                printConsoleLogOnDevelopMode(data);
            }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }

    /* FUNCIONES SECUNDARIAS */

    function processWebPushResponse(data) {
        let errorBox = document.getElementById('iwp-admin-error-box');
        let successAlert = document.getElementById('iwp-admin-success-box');
        if (data.status === 1) {
            // Intentamos obtener las aplicaciones
            successAlert.innerText = data.message;
            successAlert.classList.remove('iwp-hide');
            setTimeout(function () {
                successAlert.classList.add('iwp-hide');
                successAlert.innerHTML = '';
            }, 10000);
        } else {
            if (data.hasOwnProperty('fields')) {
                let fields = data.fields;
                for (const key in fields) {
                    printConsoleLogOnDevelopMode(key);
                    printConsoleLogOnDevelopMode(fields[key]);
                    if (fields[key] === true) {
                        document.getElementById(key).closest('.iwp-admin-webPush-welcome-form-label').classList.add('iwp-admin-error-box');
                    }
                }
            }
            errorBox.innerHTML = data.message;
            errorBox.classList.remove('iwp-hide');
            setTimeout(function () {
                errorBox.classList.add('iwp-hide');
                errorBox.innerHTML = '';
            }, 5000);
        }
        if (data.hasOwnProperty('buttons')) {
            let oldButtons = document.getElementById('iwpAdminWebPushWelcomeFormButtons');
            Array.from(oldButtons.querySelectorAll('button')).forEach(function(button) {
                button.classList.add('iwp-hide');
            });

            let buttons = data.buttons;
            for (const key in buttons) {
                printConsoleLogOnDevelopMode(key);
                printConsoleLogOnDevelopMode(buttons[key]);
                document.getElementById(buttons[key]).classList.remove('iwp-hide');
            }
        }
    }

    /**
     * Abre la biblioteca de WordPress para seleccionar una imagen
     */
    function openImageFromLibrary(selectedImageId = null) {
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
            refreshSelectedImage(imageObj);
        });

        wpMedia.open();
    }

    /**
     * Actualiza la preview de la imagen seleccionada
     */
    function refreshSelectedImage(imageObj =  null) {
        let emptyContent = document.getElementById('iwpAdminWebPushWelcomeAddImage');
        let imageContent = document.getElementById('iwpAdminWebPushWelcomePreviewImage');

        if (!emptyContent || !imageContent) {
            return true;
        }

        let imagePreview = imageContent.querySelector('.iwp-admin-webPush-welcome-form-label-image-preview');
        let imageName = imageContent.querySelector('.iwp-admin-webPush-welcome-form-label-image-info-name');
        let imageSize = imageContent.querySelector('.iwp-admin-webPush-welcome-form-label-image-info-size');
        let imageId = document.getElementById('iwpAdminWebPushWelcomeImageId');
        let imageEditIcon = document.getElementById('iwpAdminWebPushWelcomeEditImage').querySelector('img');

        if (!imagePreview || !imageName || !imageSize || !imageId || !imageEditIcon) {
            return true;
        }

        if (imageObj !== null) {
            // Añadimos o actualizamos una imagen
            imageId.value = imageObj.id;
            imagePreview.style.backgroundImage = `url(${imageObj.url})`;
            imageName.innerText = imageObj.filename;
            imageName.setAttribute('title', imageObj.filename);
            imageSize.innerText = imageObj.filesizeHumanReadable;

            emptyContent.classList.add('iwp-hide');
            imageContent.classList.remove('iwp-hide');
        } else {
            // Eliminamos una imagen
            imageContent.classList.add('iwp-hide');
            emptyContent.classList.remove('iwp-hide');

            imageId.value = '-1';
            imagePreview.style.backgroundImage = '';
            imageName.innerText = '';
            imageName.removeAttribute('title');
            imageSize.innerText = '';
        }
        imageEditIcon.classList.remove('iwp-hide');
        refreshPreview();
    }

    /**
     * Actualiza toda la información en la preview
     */
    function refreshPreview() {
        let previewContent = document.getElementById('iwpAdminWebPushWelcomePreview');
        if (!previewContent) {
            return true;
        }

        let previewImage = previewContent.querySelector('.iwp-admin-webPush-welcome-preview-info-image');
        let previewTitle = previewContent.querySelector('.iwp-admin-webPush-welcome-preview-info-title');
        let previewBody = previewContent.querySelector('.iwp-admin-webPush-welcome-preview-info-body');
        let previewUrl = previewContent.querySelector('.iwp-admin-webPush-welcome-preview-info-url');

        let title = document.getElementById('iwpAdminWebPushWelcomeTitle');
        let body = document.getElementById('iwpAdminWebPushWelcomeBody');
        let imageUrl = document.getElementById('iwpAdminWebPushWelcomePreviewImage').querySelector('.iwp-admin-webPush-welcome-form-label-image-preview');
        let url = document.getElementById('iwpAdminWebPushWelcomeUrl');

        if (!previewImage || !previewTitle || !previewBody || !previewUrl || !title || !body || !imageUrl || !url) {
            return true;
        }

        previewImage.style.backgroundImage = imageUrl.style.backgroundImage;
        if (imageUrl.style.backgroundImage.trim() !== '') {
            previewImage.classList.add('hasIt');
        } else {
            previewImage.classList.remove('hasIt');
        }
        previewTitle.innerHTML = title.value;
        previewBody.innerHTML = body.value;
        previewUrl.setAttribute('href', url.value);
    }
});