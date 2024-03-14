document.addEventListener('DOMContentLoaded', function() {
    loadIconPreview();
    previewIconChangeEvents();
    changeWhatsAppIconBalloon();
    showChatBubbleOnHover();
    changeIconOption();
    resetIconColor();

    function resetIconColor() {
        let color = document.getElementById('adminWhIconColor');
        color.addEventListener("reset-color-default", function () {
            changeIconPreview();
        });
    }

    function loadIconPreview() {
        changeIconPreview();
    }

    function changeIconOption() {
        Array.from(document.querySelectorAll('input[name="adminWhIconOption"]')).forEach(function(radioButton) {
            radioButton.addEventListener("change", function (el) {
                let element = el.target;
                if (element.checked) { // Better safe than sorry
                    let container = document.getElementById('iwpAdminWhatsappIconForm');
                    let defaultData = container.getAttribute('data-default');
                    defaultData = JSON.parse(atob(defaultData));
                    let customData = container.getAttribute('data-custom');
                    customData = JSON.parse(atob(customData));

                    let position       = defaultData.position;
                    let color          = defaultData.color;
                    let icon           = defaultData.icon;
                    let transparent    = defaultData.transparent;
                    let delay          = defaultData.delay;
                    let bubbleShow     = (defaultData.bubble === 'show');
                    let bubbleHover    = (defaultData.bubble === 'hover');
                    let bubbleText     = defaultData.bubbleText;
                    document.getElementById('iwpAdminWhatsappIconForm').classList.add('iwp-hide');

                    if (element.value === 'customized') {
                        position       = customData.position;
                        color          = customData.color;
                        icon           = customData.icon;
                        transparent    = customData.transparent;
                        delay          = customData.delay;
                        bubbleShow     = (customData.bubble === 'show');
                        bubbleHover    = (customData.bubble === 'hover');
                        bubbleText     = customData.bubbleText;
                        document.getElementById('iwpAdminWhatsappIconForm').classList.remove('iwp-hide');
                    }

                    document.querySelector('input[name="adminWhPositionValue"][value="'+position+'"]').checked = true;
                    document.getElementById('adminWhIconColor').value = color;
                    document.querySelector('input[name="adminWhIconImage"][value="'+icon+'"]').checked = true;
                    document.getElementById('adminWhIconTransparent').checked = transparent;
                    document.getElementById('adminWhChatIconSleep').value = delay;
                    document.getElementById('adminWhIconBalloonShow').checked = bubbleShow;
                    document.getElementById('adminWhIconBalloonHover').checked = bubbleHover;
                    document.getElementById('adminWhChatBalloonText').value = bubbleText;

                    changeIconPreview(false);
                }
            });
        });
    }

    function changeWhatsAppIconBalloon() {
        let bubbleShow = document.getElementById('adminWhIconBalloonShow');
        let bubbleHover = document.getElementById('adminWhIconBalloonHover');
        if (bubbleShow && bubbleHover) {
            bubbleShow.addEventListener("click", function () {
                bubbleHover.checked = false;
                bubbleShow.checker = !bubbleShow.checker;
            });
            bubbleHover.addEventListener("click", function () {
                bubbleShow.checked = false;
                bubbleHover.checker = !bubbleHover.checker;
            });
        }
    }

    function previewIconChangeEvents() {
        let positions = document.querySelectorAll('input[name="adminWhPositionValue"]');
        let color = document.getElementById('adminWhIconColor');
        let images = document.querySelectorAll('input[name="adminWhIconImage"]');
        let transparent = document.getElementById('adminWhIconTransparent');
        let bubbleShow = document.getElementById('adminWhIconBalloonShow');
        let bubbleHover = document.getElementById('adminWhIconBalloonHover');
        let balloonText = document.getElementById('adminWhChatBalloonText');
        let sleepIcon = document.getElementById('adminWhChatIconSleep');

        Array.from(positions).forEach(function (position) {
            position.addEventListener("click", function () {
                changeIconPreview();
            });
        });
        color.addEventListener("change", function () {
            changeIconPreview();
        });
        Array.from(images).forEach(function (image) {
            image.addEventListener("click", function () {
                if (image.value !== 'custom') {
                    changeIconPreview();
                } else {
                    let customImageId = document.getElementById('adminWhIconImageCustom').value;
                    openImageFromLibrary(customImageId);
                }
            });
        });
        transparent.addEventListener("click", function () {
            changeIconPreview();
        });
        bubbleShow.addEventListener("click", function () {
            changeIconPreview();
        });
        bubbleHover.addEventListener("click", function () {
            let previewBubbleTextContainer = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-message');
            previewBubbleTextContainer.classList.add('iwp-hide');
            // changeIconPreview();
        });
        balloonText.addEventListener("change", function () {
            changeIconPreview();
        });
        sleepIcon.addEventListener("input", function () {
            sleepIcon.value = sleepIcon.value.replace(/\D/, '');
        });
        sleepIcon.addEventListener("change", function () {
            let sleepError = document.getElementById('adminWhIconSleepError');
            let newValue = sleepIcon.value.replace(/\D/, '');
            sleepIcon.value = newValue;

            sleepError.classList.add('iwp-hide');
            const pattern = /^\d+$/;

            if (!pattern.test(newValue)) {
                sleepError.classList.remove('iwp-hide');
            }
        });
    }

    function showChatBubbleOnHover() {
        let whatsAppChatBalloonHover = document.getElementById('adminWhIconBalloonHover');
        let previewIconColor = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-icon');
        let previewBubbleTextContainer = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-message');
        if (whatsAppChatBalloonHover && previewIconColor && previewBubbleTextContainer) {
            previewIconColor.addEventListener("mouseover", function() {
                if (whatsAppChatBalloonHover.checked) {
                    previewBubbleTextContainer.classList.remove('iwp-hide');
                }
            });
            previewIconColor.addEventListener("mouseout", function() {
                if (whatsAppChatBalloonHover.checked) {
                    previewBubbleTextContainer.classList.add('iwp-hide');
                }
            });
        }
    }

    /**
     * El parámetro determina si hay que actualizar los datos 'custom'. Se actualizarán siempre, menos cuando
     *      cambiamos de 'option' para no perder los datos almacenados del 'custom'
     * @param updateData
     */
    function changeIconPreview(updateData = true) {
        let iconsContainer = document.getElementById('adminWhIconType');

        let position = document.querySelector('input[name="adminWhPositionValue"]:checked');
        let color = document.getElementById('adminWhIconColor');
        let icon = iconsContainer.querySelector('input[name="adminWhIconImage"]:checked');
        let iconBackground = icon.closest('.iwp-admin-whatsAppChat-icon').querySelector('.iwp-admin-whatsAppChat-icon-background');
        let iconBackgrounds = iconsContainer.querySelectorAll('.iwp-admin-whatsAppChat-icon-background');
        let transparent = document.getElementById('adminWhIconTransparent');
        let bubbleShow = document.getElementById('adminWhIconBalloonShow');
        let bubbleHover = document.getElementById('adminWhIconBalloonHover');
        let bubbleText = document.getElementById('adminWhChatBalloonText');
        let sleep = document.getElementById('adminWhChatIconSleep');

        let previewBody = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body');
        let previewIconColor = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-icon');
        let previewIcon = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-icon img');
        let previewBubbleTextContainer = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-message');
        let previewBubbleText = document.querySelector('.iwp-admin-whatsAppChat-icon-preview-body-message-box');

        if (updateData) {
            // Si cambiamos de "option", estos valores no se deben actualizar
            let container = document.getElementById('iwpAdminWhatsappIconForm');
            let customData = container.getAttribute('data-custom');
            customData = JSON.parse(atob(customData));
            customData.position = position.value;
            customData.color = color.value;
            customData.icon = icon.value;
            customData.transparent = transparent.checked;
            customData.delay = sleep.value;
            let bubble = bubbleShow.checked ? 'show' : 'none'; // Asignamos 'none' si no queremos activar algún bocadillo
            customData.bubble = bubbleHover.checked ? 'hover' : bubble;
            customData.bubbleText = bubbleText.value;
            container.setAttribute('data-custom', btoa(JSON.stringify(customData)));
        }

        if (position && previewBody) {
            previewBody.classList.remove('left-position');
            if (position.value === 'l') {
                previewBody.classList.add('left-position');
            }
        }

        if (color && transparent && previewIconColor && iconBackgrounds) {
            let backgroundColor = transparent.checked ? 'transparent' : color.value;
            previewIconColor.style.backgroundColor = backgroundColor;
            Array.from(iconBackgrounds).forEach(function (iconBackground) {
                if (!iconBackground.classList.contains('empty')) {
                    iconBackground.style.backgroundColor = backgroundColor;
                }
            });
        }

        if (iconBackground && previewIcon) {
            if (!iconBackground.classList.contains('empty')) {
                previewIcon.src = icon.getAttribute('data-img');
            }
        }

        if (bubbleText && previewBubbleText) {
            previewBubbleText.innerText = bubbleText.value;
        }

        if (previewBubbleTextContainer && bubbleShow) {
            previewBubbleTextContainer.classList.add('iwp-hide');
            if (bubbleShow.checked) {
                previewBubbleTextContainer.classList.remove('iwp-hide');
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
            changeCustomIcon(imageObj);
        });

        wpMedia.open();
    }

    function changeCustomIcon(imageObj) {
        let customIconContainer = document.getElementById('iwpAdminWhatsAppChatIconUpload');
        if (!customIconContainer) {
            return true;
        }

        let parentLabel = customIconContainer.closest('.iwp-admin-whatsAppChat-icon');
        if (!parentLabel) {
            return true;
        }

        let iconParentContainer = parentLabel.querySelector('.iwp-admin-whatsAppChat-icon-background');
        let customIconInput = document.getElementById('adminWhIconImageCustom');
        let customIconImage = customIconContainer.querySelector('img');
        let customIconDataImage = parentLabel.querySelector('input[name="adminWhIconImage"]');

        if (iconParentContainer && customIconInput && customIconImage && customIconDataImage) {
            customIconInput.value = imageObj.id;
            customIconContainer.querySelector('img').src = imageObj.url;
            customIconContainer.classList.remove('empty');
            customIconDataImage.setAttribute('data-img', imageObj.url);
            iconParentContainer.classList.remove('empty');
            changeIconPreview();
        }

    }

});