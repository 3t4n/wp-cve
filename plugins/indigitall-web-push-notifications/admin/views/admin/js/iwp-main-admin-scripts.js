document.addEventListener('DOMContentLoaded', function() {
    showPluginMainWindow();
    goToLinkWithEvent();
    checkMandatoryLogin();

    function checkMandatoryLogin() {
        if (IS_LOGGED === '0') {
            Array.from(document.querySelectorAll('.iwp-admin-logged-content')).forEach(function (block) {
                block.classList.add('iwp-admin-no-logged-backdrop');
                block.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    showHideLoginModalBlock(document.getElementById('iwpAdminModalLogin'), true);
                    showHideLoginModalBlock(document.getElementById('iwpAdminLogin'), true);
                });
            });
        }
    }


    function showPluginMainWindow() {
        let mainWindow = document.querySelector('.iwp-admin-container');
        if (mainWindow) {
            mainWindow.classList.add('iwp-show');
        }
    }

    function goToLinkWithEvent() {
        let mainWindow = document.querySelector('.iwp-admin-container');
        if (mainWindow) {
            Array.from(mainWindow.querySelectorAll('a')).forEach(function(linkContainer) {
                if (linkContainer.hasAttribute('data-event')) {
                    linkContainer.addEventListener('click', async function (e) {
                        e.preventDefault();
                        const event = linkContainer.getAttribute('data-event');
                        await sendEvent(event);
                        window.open(
                            linkContainer.getAttribute('href'),
                            linkContainer.hasAttribute('target') ? linkContainer.getAttribute('target') : '_self',
                        );
                    });
                }
            });
        }
    }

});
    // Añadimos nuestro scrollbar al body
    document.body.classList.add('iwp-scrollbar');

    // Definimos las variables globales con su valor predeterminado
    let developerMode = 0;
    let DEBUG_ERROR_LEVEL_1 = 'error';
    let DEBUG_ERROR_LEVEL_2 = 'error';
    let DEBUG_ERROR_LEVEL_3 = 'error';
    let DEBUG_ERROR_LEVEL_4 = 'error';
    let MICRO_PLUGIN_SELECCIONA_SERVICIO = 'error';
    let iwpAjaxUrl = ajaxurl;
    let locale = 'en';
    let IS_LOGGED = false;
    printConsoleLogOnDevelopMode(ADMIN_PARAMS);
    if (typeof ADMIN_PARAMS !== "undefined") {
        // Si 'ADMIN_PARAMS' existe, intentamos cargar los valores correspondientes de las variables globales
        // Con cada variable es necesario comprobar que existe dentro de 'ADMIN_PARAMS'
        if (ADMIN_PARAMS.hasOwnProperty('developerMode')) {
            developerMode = (ADMIN_PARAMS.developerMode === '1');
        }

        if (ADMIN_PARAMS.hasOwnProperty('DEBUG_ERROR_LEVEL_1')) {
            DEBUG_ERROR_LEVEL_1 = ADMIN_PARAMS.DEBUG_ERROR_LEVEL_1;
        }
        if (ADMIN_PARAMS.hasOwnProperty('DEBUG_ERROR_LEVEL_2')) {
            DEBUG_ERROR_LEVEL_2 = ADMIN_PARAMS.DEBUG_ERROR_LEVEL_2;
        }
        if (ADMIN_PARAMS.hasOwnProperty('DEBUG_ERROR_LEVEL_3')) {
            DEBUG_ERROR_LEVEL_3 = ADMIN_PARAMS.DEBUG_ERROR_LEVEL_3;
        }
        if (ADMIN_PARAMS.hasOwnProperty('DEBUG_ERROR_LEVEL_4')) {
            DEBUG_ERROR_LEVEL_4 = ADMIN_PARAMS.DEBUG_ERROR_LEVEL_4;
        }
        if (ADMIN_PARAMS.hasOwnProperty('MICRO_PLUGIN_SELECCIONA_SERVICIO')) {
            MICRO_PLUGIN_SELECCIONA_SERVICIO = ADMIN_PARAMS.MICRO_PLUGIN_SELECCIONA_SERVICIO;
        }
        if (ADMIN_PARAMS.hasOwnProperty('locale')) {
            locale = ADMIN_PARAMS.locale;
            iwpAjaxUrl = `${iwpAjaxUrl}?lang=${locale}`;
        }
        if (ADMIN_PARAMS.hasOwnProperty('IS_LOGGED')) {
            IS_LOGGED = ADMIN_PARAMS.IS_LOGGED;
        }
    }

    /**
     * Si developerMode es true, pinta en consola cualquier cosa que le venga. No hace falta que sea un string
     * @param data
     * @param isError
     * @param force
     */
    function printConsoleLogOnDevelopMode(data = 'no-data', isError = false, force = false) {
        if (developerMode || force) {
            if (isError) {
                console.error("Error: ", data);
            } else {
                console.log(data);
            }
        }
    }

    /**
     * Llamada ajax para lanzar los eventos generados dentro del js
     * @param event
     * @param extraData
     */
    function sendEvent(event, extraData = []) {
        return new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('action', 'iwp_send_event');
            data.append('event', event);
            data.append('eventData', JSON.stringify(extraData));

            fetch(iwpAjaxUrl, {
                method: "POST",
                credentials: "same-origin",
                body: data
            }).then((response) => {
                const msg = {
                    'Status code:': response.status,
                    'Request EVENT': event,
                    'Event data': JSON.stringify(extraData),
                    'Request Response': response.statusText
                }
                printConsoleLogOnDevelopMode(msg);
            }).catch((err) => {
                // Error genérico
                printConsoleLogOnDevelopMode(err.message, true, true);
            }).finally(() => {
                resolve('resolved');
            });
        });
    }

    function showHideLoader(show = false) {
        const loader = document.getElementById('iwp-admin-loader-backdrop');
        if (loader) {
            if (show) {
                loader.classList.remove('iwp-hide');
            } else {
                loader.classList.add('iwp-hide');
            }
        }
    }

    function slideDown(element) {
        // element.style.height = element.scrollHeight + 'px';
        element.style.height = 'auto';
    }
    function slideUp(element) {
        element.style.height = '0px';
    }

    /**
     * Genera un código QR partiendo del link recibido y lo pinta en el tag con el ID recibido.
     * Si el link está vacío, no se va a pintar nada.
     * Si no se envía el ID, se pintará en el ID 'iwp-QR-code'
     * @param link
     * @param id
     */
    function generateQR(link = '', id = '') {
        if ((link.length > 0) && (id.length > 0)) {
            let qrTag = document.getElementById(id);
            if (qrTag) {
                qrTag.innerHTML = '';
                printConsoleLogOnDevelopMode('QR code generated');
                // new QRCode(qrTag, link);
                new QRCode(qrTag, {
                    text: link,
                    // width: 256,
                    // height: 256,
                    // useSVG: true,
                    // typeNumber: 4,
                    // colorDark : "#000000",
                    // colorLight : "#ffffff",
                    // correctLevel : QRCode.CorrectLevel.H
                });
                return;
            }
        }
        printConsoleLogOnDevelopMode('QR code NOT generated');
    }

    function testImagePromise(url) {
        // Define the promise
        return new Promise(function imgPromise(resolve, reject) {
            // Create the image
            const imgElement = new Image();
            // When image is loaded, resolve the promise
            imgElement.addEventListener('load', function imgOnLoad() {
                resolve(this);
            });
            // When there's an error during load, reject the promise
            imgElement.addEventListener('error', function imgOnError() {
                reject(this);
            })
            // Assign URL
            imgElement.src = url;
        });
    }
    function testImage(url) {
        testImagePromise(url).then(
            function fulfilled(img) {
                console.log('That image is found and loaded', img);
            },
            function rejected(img) {
                console.log('That image was not found', img);
            }
        );
    }

    /**
     * Procesa la 'response' del fetch
     * Si es correcto, transforma la respuesta json en un objeto y lo devuelve
     * Si no es correcto, lanza un error que se capturará en el catch
     */
    const isResponseOk = (response) => {
        if (!response.ok)
            throw new Error(response.status);
        return response.json()
    }

    const rgbToHex = (r, g, b) => '#' + [r, g, b].map(x => {
        const hex = x.toString(16)
        return hex.length === 1 ? '0' + hex : hex
    }).join('');

    const hexToRgb = (hex, alpha) => {
        if (hex === '') {
            return '';
        }
        const bigint = parseInt(hex.substring(1), 16);
        const r = (bigint >> 16) & 255;
        const g = (bigint >> 8) & 255;
        const b = bigint & 255;

        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    /**
     * Comprueba si un email tiene la forma correcta.
     * Si es correcto devuelve TRUE.
     * Si no es correcto, devuelve FALSE
     */
    const validateEmail = (email) => {
        let match = email.match(
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
        return match !== null;
    };
