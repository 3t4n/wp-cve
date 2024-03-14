document.addEventListener('DOMContentLoaded', function() {
    /* EJECUCIÓN DE FUNCIONES AL INICIO */


    /* CARGA DE FUNCIONES DE EVENTOS */
    addNewTopic();
    closeTopicModal();
    deleteTopic();
    editTopic();
    toggleTopicsStatus();
    deleteTopicModal();
    createTopicModal();
    updateTopicModal();
    changeTopicColor();

    /* FUNCIONES DE EVENTOS */
    function deleteTopicModal() {
        let button = document.getElementById('iwpAdminTopicDeleteSubmit');
        if (button) {
            button.addEventListener("click", function () {
                let topicId = document.getElementById('iwpAdminTopicDelete').value;
                deleteTopicAjax(topicId);
            });
        }
    }

    function createTopicModal() {
        let button = document.getElementById('iwpAdminTopicCreate');
        if (button) {
            button.addEventListener("click", function () {
                createTopicAjax();
            });
        }
    }
    function updateTopicModal() {
        let tableBody = document.getElementById('webPushTopicsTableBody');

        if (tableBody) {
            Array.from(tableBody.querySelectorAll('.iwp-admin-webPush-topics-item-name')).forEach(function (input) {
                input.addEventListener("change", function () {
                    updateTopicAjax(input);
                });
                input.addEventListener("keydown", function (e) {
                    if (e.which === 13) {
                        input.blur();
                        return false;
                    }
                });
            });
        }
    }

    function changeTopicColor() {
        let button = document.getElementById('iwpTopicsColor');
        if (button) {
            button.addEventListener("change", function () {
                changeTopicsColorAjax(button.value);
            });
        }
    }

    function toggleTopicsStatus() {
        let checkbox = document.getElementById('webPushTopicsStatus');
        let topicsContainer = document.getElementById('iwpTopicsContainer');

        if (checkbox) {
            checkbox.addEventListener("click", function (e) {
                sendTopicsStatusAjax(checkbox.checked);
                if (checkbox.checked) {
                    // Se muestran los topics
                    topicsContainer.classList.remove('iwp-hide');
                } else {
                    // Se ocultan los topics
                    topicsContainer.classList.add('iwp-hide');
                }
            });
        }
    }

    function deleteTopic() {
        let buttons = document.getElementById('webPushTopicsTableBody');
        let topicName = document.getElementById('webPushTopicsModalDeleteTopicName');
        let topicId = document.getElementById('iwpAdminTopicDelete');
        let parentTr, nameInput;

        if (buttons) {
            Array.from(buttons.querySelectorAll('.iwp-admin-webPush-topics-item-delete')).forEach(function (button) {
                button.addEventListener("click", function () {
                    parentTr = button.closest('.iwp-admin-webPush-topics-table-item');
                    if (parentTr) {
                        nameInput = parentTr.querySelector('.iwp-admin-webPush-topics-item-name');
                        if (nameInput) {
                            topicName.innerText = nameInput.value;
                            topicId.value = button.getAttribute('data-id');
                            let topicModal = document.getElementById('iwpAdminWebPushTopicsModalDelete');
                            if (topicModal) {
                                topicModal.classList.remove('iwp-hide');
                                window.scrollTo(0, 0);
                            }
                        }
                    }
                });
            });
        }
    }

    function editTopic() {
        let buttons = document.getElementById('webPushTopicsTableBody');
        let parentTr, nameInput;

        if (buttons) {
            Array.from(buttons.querySelectorAll('.iwp-admin-webPush-topics-item-edit')).forEach(function (button) {
                button.addEventListener("click", function () {
                    parentTr = button.closest('.iwp-admin-webPush-topics-table-item');
                    if (parentTr) {
                        nameInput = parentTr.querySelector('.iwp-admin-webPush-topics-item-name');
                        if (nameInput) {
                            nameInput.focus();
                        }
                    }
                });
            });
        }
    }

    function addNewTopic() {
        let button = document.getElementById('iwpAdminWebPushTopicCreate');
        if (button) {
            button.addEventListener("click", function () {
                let topicModal = document.getElementById('iwpAdminWebPushTopicsCreateModal');
                if (topicModal) {
                    topicModal.classList.remove('iwp-hide');
                    window.scrollTo(0,0);
                }
            });
        }
    }
    function closeTopicModal() {
        let createTimes = document.getElementById('iwpTimesTopicModal');
        let createCancel = document.getElementById('iwpAdminTopicCancel');
        let deleteTimes = document.getElementById('iwpTimesTopicDeleteModal');
        let deleteCancel = document.getElementById('iwpAdminTopicDeleteCancel');

        let topicCreateModal = document.getElementById('iwpAdminWebPushTopicsCreateModal');
        let topicDeleteModal = document.getElementById('iwpAdminWebPushTopicsModalDelete');

        let topicName = document.getElementById('webPushTopicsModalDeleteTopicName');
        let topicId = document.getElementById('iwpAdminTopicDelete');

        if (createTimes) {
            createTimes.addEventListener("click", function () {
                if (topicCreateModal) {
                    topicCreateModal.classList.add('iwp-hide');
                }
            });
        }
        if (createCancel) {
            createCancel.addEventListener("click", function () {
                if (topicCreateModal) {
                    topicCreateModal.classList.add('iwp-hide');
                }
            });
        }
        if (deleteTimes) {
            deleteTimes.addEventListener("click", function () {
                if (topicDeleteModal) {
                    topicDeleteModal.classList.add('iwp-hide');
                    topicName.innerText = '';
                    topicId.value = '';
                }
            });
        }
        if (deleteCancel) {
            deleteCancel.addEventListener("click", function () {
                if (topicDeleteModal) {
                    topicDeleteModal.classList.add('iwp-hide');
                    topicName.innerText = '';
                    topicId.value = '';
                }
            });
        }
    }

    /* FUNCIONES AJAX */
    function sendTopicsStatusAjax(status) {
        const data = new FormData();
        data.append('action', 'iwp_wp_toggle_topics');
        data.append('status', status);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => {
            const msg = {
                'Status code:': response.status,
                'Changed Topics status to:': status,
                'Request Response': response.statusText
            }
            printConsoleLogOnDevelopMode(msg);
        }).catch((err) => {
            printConsoleLogOnDevelopMode(err.message, true, true);
        });
    }

    function createTopicAjax() {
        let errorBox = document.getElementById('iwp-admin-topic-modal-error-box');
        let topicName = document.getElementById('topicName');
        let topicCode = document.getElementById('topicCode');
        const data = new FormData();
        data.append('action', 'iwp_wp_create_topic');
        data.append('name', topicName.value.trim());
        data.append('code', topicCode.value.trim());

        errorBox.classList.add('iwp-hide');
        showHideLoader(true);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    // Recarga la página para ver los cambios
                    window.location.reload();
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
                        errorBox.innerHTML = '';
                    }, 5000);
                }
                printConsoleLogOnDevelopMode(data);
            }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }

    function updateTopicAjax(element) {
        let topicId = element.getAttribute('data-id');
        let topicRow = element.closest('.iwp-admin-webPush-topics-table-item');
        topicRow.classList.remove('iwp-admin-error-box');

        const data = new FormData();
        data.append('action', 'iwp_wp_update_topic');
        data.append('topicId', topicId);
        data.append('name', element.value.trim());

        showHideLoader(true);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {

                let status = (data.status === 1) ? 'iwp-admin-success-box' : 'iwp-admin-error-box';
                let messageTimeOut = (data.status === 1) ? 500 : 2000;
                topicRow.classList.add(status);
                topicRow.classList.add('shake');

                setTimeout(function () {
                    topicRow.classList.remove('shake');
                    if (data.status === 1) {
                        topicRow.classList.remove(status);
                    }
                }, messageTimeOut);
                printConsoleLogOnDevelopMode(data);
            }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }

    function deleteTopicAjax(topicId) {
        let errorBox = document.getElementById('iwp-admin-topic-modal-error-box');
        errorBox.classList.add('iwp-hide');
        showHideLoader(true);

        const data = new FormData();
        data.append('action', 'iwp_wp_delete_topic');
        data.append('topicId', topicId);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    let tableBody = document.getElementById('webPushTopicsTableBody');
                    let topicRow = tableBody.querySelector('.iwp-admin-webPush-topics-item-delete[data-id="'+topicId+'"]');
                    if (topicRow) {
                        let row = topicRow.closest('.iwp-admin-webPush-topics-table-item');
                        row.parentNode.removeChild(row);
                    }
                    document.getElementById('iwpAdminTopicDelete').value = '';
                    document.getElementById('iwpAdminWebPushTopicsModalDelete').classList.add('iwp-hide');
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
                        errorBox.innerHTML = '';
                    }, 5000);
                }
                printConsoleLogOnDevelopMode(data);
        }).catch((err) => {
            printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }

    function changeTopicsColorAjax(color) {
        const data = new FormData();
        data.append('action', 'iwp_wp_toggle_topics_color');
        data.append('color', color);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => {
                const msg = {
                    'Status code:': response.status,
                    'Changed Topics color to:': color,
                    'Request Response': response.statusText
                }
                printConsoleLogOnDevelopMode(msg);
            }).catch((err) => {
            printConsoleLogOnDevelopMode(err.message, true, true);
        });
    }

    function sendTopicsData(data, errorBox) {
        errorBox.classList.add('iwp-hide');
        showHideLoader(true);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    // Recarga la página para ver los cambios
                    window.location.reload();
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
                        errorBox.innerHTML = '';
                    }, 5000);
                }
                printConsoleLogOnDevelopMode(data);
            }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }


    /* FUNCIONES SECUNDARIAS */
});