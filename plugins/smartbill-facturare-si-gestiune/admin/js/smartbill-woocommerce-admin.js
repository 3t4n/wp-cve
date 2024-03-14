/**
* SmartBill Admin.
*
* This file adds different functionalities to the woocommerce admin interface.
*
* @link   /admin/js/smartbill-woocommerce-admin.js
* @author Intelligent IT SRL <vreauapi@smartbill.ro>.
* @since  1.0.0
*/

/*jshint esversion: 8 */
(function ($) {
    'use strict';

    // How many api calls need to be left (smartbill has a limit of 30) before starting to wait for each call.
    const maxCall = 4;

    // Array with all success messages.
    var successMessages = [];

    // Array with all error messages.
    var errorMessages = [];


    /**
    * Display messages after as Toasts.
    *
    * Get messages from browser storage and display them in one or multiple Toast/s.
    *
    * @since      1.0.0
    * 
    * @return {void}
    */
    function displayMessages() {
        var path = window.location.pathname;
        if ('undefined' !== typeof (Storage)) {
            if ('undefined' != localStorage.path) {
                if (path == localStorage.path) {
                    var storedMessages;
                    if ('' != localStorage.successMessages || 'undefined' != localStorage.successMessages) {
                        storedMessages = JSON.parse(localStorage.successMessages);
                        storedMessages.forEach(function (item, index, array) {
                            Toastify({
                                text: item, duration: 3000, newWindow: false, close: true, gravity: "top", position: 'center', backgroundColor: "#00A14B", stopOnFocus: true,
                            }).showToast();
                            if (index === array.length - 1) {
                                localStorage.removeItem("successMessages");
                            }
                        });

                    }
                    if ('' != localStorage.errorMessages || 'undefined' != localStorage.errorMessages) {
                        storedMessages = JSON.parse(localStorage.errorMessages);
                        storedMessages.forEach(function (item, index, array) {
                            Toastify({
                                text: item, duration: -1, newWindow: false, close: true, gravity: "top", position: 'center', backgroundColor: "#EF4136", stopOnFocus: true,
                            }).showToast();
                            if (index === array.length - 1) {
                                localStorage.removeItem("errorMessages");
                            }
                        });

                    }

                    localStorage.removeItem('path');
                } else {
                    localStorage.removeItem('path');
                    localStorage.removeItem("successMessages");
                    localStorage.removeItem("errorMessages");
                }
            }
        }
    }

    /**
    * Save all messages stored in successMessages and errorMessages arrays in browser storage for later use.
    *
    * @since      1.0.0
    *
    * @return {void}
    */
    function saveMessages() {
        if ('undefined' !== typeof (Storage)) {
            if ('undefined' != localStorage.successMessages) {
                localStorage.successMessages = JSON.stringify(successMessages);
            }
            if ('undefined' != localStorage.errorMessages) {
                localStorage.errorMessages = JSON.stringify(errorMessages);
            }
            if ('undefined' != localStorage.path) {
                localStorage.path = window.location.pathname;
            }
        }
    }

    $(document).ready(function () {

        //Display messages when page is ready.
        displayMessages();

        //Load select2 for smartbill setting field.
        $("#smrt-order-select").select2();

        /**
        * Manually sync stock.
        *
        * Send ajax call for retrieving stocks from smartbill.
        * 
        * @since      1.0.0
        * 
        * @return {void}
        */
        $("#smartbill-manually-sync-stock").click(function (e) {
            e.preventDefault();
            var warehouse = $("select[name=smartbill_plugin_options_settings\\[used_stock\\]]").find(":selected").val();
            var info_message = document.createElement("div");
            if ('' == warehouse || 'fara-gestiune' == warehouse) {
                Toastify({
                    text: "Este necesara selectarea gestiunii din care vor fi preluate stocurile.", duration: -1, newWindow: false, close: true, gravity: "top", position: 'center', backgroundColor: "#EF4136", stopOnFocus: true,
                }).showToast();
            } else {
                info_message.innerHTML = "Stocurile produselor din WooCommerce <strong>vor fi actualizate</strong> cu stocurile produselor din <strong>gestiunea " + warehouse + "</strong>."
                swal({
                    title: 'Atentie!',
                    content: info_message,
                    icon: 'warning',
                    buttons: ['Renunta', 'Actualizeaza stocuri']
                }).then(function (result) {
                    if (!result) {
                        return false;
                    };
                    info_message = document.createElement("div");
                    info_message.innerHTML = "Va rugam asteptati finalizarea preluarii...";

                    swal({
                        title: "Se incarca!",
                        content: info_message,
                        icon: 'info',
                        buttons: [false, false]
                    });
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        data: {
                            security: smartbill.security,
                            body: warehouse,
                            action: 'smartbill_woocommerce_manually_sync_stock'
                        },
                        success: function (response) {
                            try {
                                response = JSON.parse(response);
                                info_message = document.createElement("div")
                                info_message.innerHTML = response.data;
                                if (undefined != response.data) {
                                    swal.close();
                                    swal({
                                        title: "",
                                        content: info_message,
                                        icon: response.icon,
                                        buttons: ['Am inteles', 'Descarca istoric']
                                    }).then(function (result) {
                                        if (!result) {
                                            return false;
                                        };

                                        swal({
                                            title: "Descarcarea documentului va incepe curand…",
                                            content: document.createElement("div"),
                                            icon: 'info',
                                            buttons: [false, false]
                                        });
                                        $.ajax({
                                            url: ajaxurl,
                                            type: 'post',
                                            data: {
                                                security: smartbill.security,
                                                action: 'smartbill_woocommerce_download_manual_stock_history'
                                            },
                                            success: function (response) {
                                                response = JSON.parse(response);

                                                try {
                                                    if (undefined != response.data) {
                                                        swal.close();
                                                        location.href = response.data;
                                                    } else {
                                                        throw response.error;
                                                    }
                                                } catch (error) {
                                                    errorMessages.push(error);
                                                    saveMessages();
                                                    location.reload();
                                                }
                                            },
                                            error: function (request, status, error) {
                                                errorMessages.push(error);
                                                saveMessages();
                                                location.reload();
                                            }
                                        });
                                    });

                                } else {
                                    throw response.error;
                                }

                            } catch (error) {
                                errorMessages.push(error);
                                saveMessages();
                                location.reload();
                            }
                        },
                        error: function (request, status, error) {
                            errorMessages.push(error);
                            saveMessages();
                            location.reload();
                        }
                    });

                });
            }
        });

        /**
        * Get stock history zip file.
        *
        * Send ajax call for retrieving stock history from backend.
        * 
        * @since      1.0.0
        * 
        * @return {void}
        */
        $("#smartbill-download-sync-stock-history").click(function (e) {
            e.preventDefault();

            swal({
                title: "Descarcarea documentului va incepe curand…",
                content: document.createElement("div"),
                icon: 'warning',
                buttons: [false, false]
            });
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    security: smartbill.security,
                    action: 'smartbill_woocommerce_download_stock_history'
                },
                success: function (response) {
                    response = JSON.parse(response);

                    try {
                        if (undefined != response.data) {
                            swal.close();
                            location.href = response.data;
                        } else {
                            throw response.error;
                        }
                    } catch (error) {
                        errorMessages.push(error);
                        saveMessages();
                        location.reload();
                    }
                },
                error: function (request, status, error) {
                    errorMessages.push(error);
                    saveMessages();
                    location.reload();
                }
            });
        });

        /**
        * Get smartbill settings file.
        *
        * Send ajax call for creating and retrieving smartbill settings file.
        * 
        * @since      1.0.0
        *
        * @return {void}
        */
        $('#export-settings').click(function (e) {
            e.preventDefault();
            smartbill_export.setariEmitere.moneda = $('select[name="smartbill_plugin_options_settings[billing_currency]"]').find(":selected").text();
            var d = new Date();
            var datestring = d.getFullYear() + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2) + " " + ("0" + d.getHours()).slice(-2) + "_" + ("0" + d.getMinutes()).slice(-2) + "_" + ("0" + d.getSeconds()).slice(-2);
            smartbill_export.versiuni.data_export = datestring;
            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(smartbill_export));
            var downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", "smartbill_modul_setari.json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        });

        /**
        * Call get series, get taxes and get measuring units.
        *
        * Send ajax call for updating smartbill data.
        * 
        * @since      1.0.0
        *
        * @return {void}
        */
        $('#get-smartbill-setting').click(function (e) {
            e.preventDefault();
            swal({
                title: "Se preiau informatiile din SmartBill…",
                content: document.createElement("div"),
                icon: 'warning',
                buttons: [false, false]
            });
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    security: smartbill.security,
                    action: 'smartbill_woocommerce_sync_settings'
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if ("" != response.message) {
                        successMessages.push(response.message);
                    }
                    if ("" != response.error) {
                        successMessages.push(response.error);
                    }
                    saveMessages();

                    location.reload();

                },
                error: function (request, status, error) {
                    errorMessages.push(error);
                    saveMessages();

                    location.reload();
                }
            });
        });

        /**
        * Create invoice on order save.
        * 
        * @since      1.0.0
        * 
        * @return {Array | false}.
        */
        $('button[name="save"].save_order.button-primary').click(function (e) {
            if (!e.detail || 1 == e.detail) {

                //Check if smartbill setting is enabled.
                if (0 !== smartbill.woocommerce_status.length) {

                    //Backwards compatibility. Ensure that smartbill setting is array.
                    if (!Array.isArray(smartbill.woocommerce_status)) {
                        smartbill.woocommerce_status = [smartbill.woocommerce_status];
                    }

                    //Check if current order status maches smartbill settings.
                    if (smartbill.woocommerce_status.includes($('#order_status').val())) {
                        var attention_text;

                        //Check if order has invoice.
                        if (0 === $('#smartbill-woocommerce-view-document-button').length) {
                            e.preventDefault();
                            attention_text = document.createElement("div");
                            attention_text.innerHTML = "Comanda #" + smartbill.post_id + " va fi emisa in SmartBill.";
                            swal({
                                title: "Modulul SmartBill a detectat modificarea statusului comenzii",
                                content: attention_text,
                                icon: 'warning',
                                buttons: ['Mai tarziu', 'Emite document']
                            }).then(async function (result) {
                                if (!result) {
                                    $(e.currentTarget.form).submit();
                                    return false;
                                }
                                var payload = {
                                    data: {
                                        order_id: smartbill.post_id
                                    },
                                    security: smartbill.security,
                                    action: 'smartbill_woocommerce_issue_document'
                                };

                                //Create invoice.
                                await call_issue_doc(payload, false, true).then(function () {
                                    $(e.currentTarget.form).submit();
                                });
                            });
                        } else {
                            e.preventDefault();
                            attention_text = document.createElement("div");
                            saveMessages();
                            attention_text.innerHTML = "Te informam ca nu se va emite automat o alta comanda in SmartBill.";
                            swal({
                                title: "Comanda a fost deja facturata in SmartBill. ",
                                content: attention_text,
                                icon: 'warning',
                                buttons: [false, 'Continua']
                            }).then(function (result) {
                                $(e.currentTarget.form).submit();
                            });
                        }

                    }
                }
            } else {
                return false;
            }
        });


        var box = $('#smartbill_woocommerce_meta_box');
        if (box.length){
            box.prependTo(box.parent());
        }

        //Add send email option if smartbill setting is enabled.
        if ('1' == smartbill.send_mail_with_document) {
            $('<option>').val('smartbill_send_documents_to_client').text('Trimite factura clientului').appendTo("select[name='action'], select[name='action2']");
        }

        //Add bulk create invoice option.
        $('<option>').val('smartbill_generate_documents').text('Emite documente in SmartBill').appendTo("select[name='action'], select[name='action2']");

        //doaction is duplicated and emits 2 invoices. In order to prevent that disable doaction2.
        $('#doaction2').click(function (e) {
            if (!e.detail || 1 == e.detail) {
                $('#doaction2').removeAttr('onclick');
                $('#doaction2').css('pointer-events', 'none');
            }
        });

        //Create invoice as bulk.
        $('#doaction').click(function (e) {
            if (!e.detail || 1 == e.detail) {
                var sel = $(this).siblings('select');
                var pending_orders = [];
                var orders_with_invoices = [];
                var inputs = $('input[name="id[]"]:checked');
                if( 0 === inputs.length ){
                    inputs = $('input[name="post[]"]:checked');
                }

                var attention_text;

                //Get all selected orders that have an invoice and all selected orders that don't.
                inputs.each(function (k, elem) {
                    var has_document = $(elem).parent().siblings('.smartbill_woocommerce_invoice').find('a');
                    if (has_document.length) {
                        orders_with_invoices.push(elem);
                    }
                    else {
                        pending_orders.push(elem);
                    }
                });

                //Sort selected order.
                pending_orders.sort((a, b) => (a.value > b.value) ? 1 : -1);
                orders_with_invoices.sort((a, b) => (a.value > b.value) ? 1 : -1);

                if ('smartbill_generate_documents' == sel.val()) {
                    e.preventDefault();

                    //Check if there are orders that don't have invoices.
                    if (pending_orders.length) {

                        //Display warning if there are orders with invoices.
                        if (orders_with_invoices.length) {
                            attention_text = document.createElement("div");
                            attention_text.innerHTML = "Exista comenzi care au fost deja facturate. <br/> Doar comenzile nefacturate vor fi emise in SmartBill.";
                            if (orders_with_invoices.length == 1) {
                                attention_text.innerHTML = "Exista o comanda care a fost deja facturata. <br/> Doar comenzile nefacturate vor fi emise in SmartBill.";
                            }
                            if (pending_orders.length == 1) {
                                attention_text.innerHTML = "Exista comenzi care au fost deja facturate. <br/> Doar comanda nefacturata va fi emisa in SmartBill.";
                            }
                            if (orders_with_invoices.length == 1 && pending_orders.length == 1) {
                                attention_text.innerHTML = "Exista o comanda care a fost deja facturata. <br/> Doar comanda nefacturata va fi emisa in SmartBill.";
                            }

                            swal({
                                title: "Atentie!",
                                content: attention_text,
                                icon: 'warning',
                                buttons: ['Renunta', 'Emite in SmartBill']
                            }).then(function (result) {
                                if (!result) {
                                    return false;
                                }
                                var info_text = document.createElement("div");
                                info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                                swal({
                                    title: 'Se incarca!',
                                    content: info_text,
                                    icon: 'info',
                                    buttons: [false, false]
                                });

                                //Create invoice for all selected orders (that don't already have a invoice).
                                const start = async () => {
                                    await asyncForEach(pending_orders, async (item, index) => {
                                        var order_id = parseInt($(item).val());
                                        var is_first = true;
                                        if (index > 0) {
                                            is_first = false;
                                        }
                                        var payload = {
                                            data: {
                                                order_id: order_id,
                                                is_first: is_first
                                            },
                                            security: smartbill.security,
                                            action: 'smartbill_woocommerce_issue_document'
                                        };
                                        var result = await call_issue_doc(payload, true, false);
                                        result = JSON.parse(result);
                                        if (result.hasOwnProperty('headers')) {

                                            //Check if smartbill 30 calls limit is close.
                                            if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {

                                                //Calculate time untill next call is available.
                                                var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                                var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                                var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;

                                                //Wait for next call.
                                                await waitFor(time_to_wait);
                                            }
                                        }
                                    });
                                };
                                start().then(function () {
                                    swal.close();
                                    window.location.reload();
                                });
                            });

                        }
                        else {
                            e.preventDefault();
                            var info_text = document.createElement("div");
                            info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                            swal({
                                title: 'Se incarca!',
                                content: info_text,
                                icon: 'info',
                                buttons: [false, false]
                            });
                            const start = async () => {
                                await asyncForEach(pending_orders, async (item, index) => {
                                    var order_id = parseInt($(item).val());
                                    var is_first = true;
                                    if (index > 0) {
                                        is_first = false;
                                    }
                                    var payload = {
                                        data: {
                                            order_id: order_id,
                                            is_first: is_first
                                        },
                                        security: smartbill.security,
                                        action: 'smartbill_woocommerce_issue_document'
                                    };
                                    var result = await call_issue_doc(payload, true, false);
                                    result = JSON.parse(result);
                                    if (result.hasOwnProperty('headers')) {
                                        if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {
                                            var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                            var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                            var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;
                                            await waitFor(time_to_wait);
                                        }
                                    }
                                });
                            };
                            start().then(function () {
                                swal.close();
                                window.location.reload();
                            });
                        }
                    }
                    else if (orders_with_invoices.length) {
                        attention_text = document.createElement("div");
                        attention_text.innerHTML = "Comenzile selectate sunt deja facturate in SmartBill.";
                        if (orders_with_invoices.length == 1) {
                            attention_text.innerHTML = "Comanda selectata este deja facturata in SmartBill.";
                        }
                        swal({
                            title: "Atentie!",
                            content: attention_text,
                            icon: 'warning',
                            buttons: ['Renunta', 'Reemite in SmartBill']
                        }).then(function (result) {
                            if (!result) {
                                return false;
                            }
                            var info_text = document.createElement("div");
                            info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                            swal({
                                title: 'Se incarca!',
                                content: info_text,
                                icon: 'info',
                                buttons: [false, false]
                            });

                            //Recreate invoices for the selected order.
                            const start = async () => {
                                await asyncForEach(orders_with_invoices, async (item, index) => {
                                    var order_id = parseInt($(item).val());
                                    var is_first = true;
                                    if (index > 0) {
                                        is_first = false;
                                    }
                                    var payload = {
                                        data: {
                                            order_id: order_id,
                                            is_first: is_first
                                        },
                                        security: smartbill.security,
                                        action: 'smartbill_woocommerce_issue_document'
                                    };
                                    var result = await call_issue_doc(payload, true, false);
                                    result = JSON.parse(result);
                                    if (result.hasOwnProperty('headers')) {
                                        if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {
                                            var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                            var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                            var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;
                                            await waitFor(time_to_wait);
                                        }
                                    }
                                });
                            };
                            start().then(function () {
                                swal.close();
                                window.location.reload();
                            });
                        });
                    }
                } else if (sel.val() == 'smartbill_send_documents_to_client') {

                    //Check if smartbill setting is enabled.
                    if ('1' == smartbill.send_mail_with_document) {
                        e.preventDefault();

                        //Check if there are orders with invoices.
                        if (orders_with_invoices.length) {

                            //If there are orders without invoices display custom message.
                            if (pending_orders.length) {
                                attention_text = document.createElement("div");
                                var button_message = 'Trimite documente clientilor';
                                attention_text.innerHTML = "Exista comenzi care nu au fost facturate. <br/> Doar comenzile facturate vor fi trimise prin e-mail clientilor.";

                                if (1 == orders_with_invoices.length) {
                                    attention_text.innerHTML = "O comanda nu a fost facturata. <br/> Doar comenzile facturate vor fi trimise prin e-mail clientilor.";
                                    button_message = 'Trimite documentul clientului';
                                }
                                swal({ title: "Atentie!", content: attention_text, icon: 'warning', buttons: ['Renunta', button_message] }).then(function (result) {
                                    if (!result) { return false; }
                                    var info_text = document.createElement("div");
                                    info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                                    swal({
                                        title: 'Se incarca!',
                                        content: info_text,
                                        icon: 'info',
                                        buttons: [false, false]
                                    });

                                    //Send invoice to customer for each selected order that has an invoice.
                                    const start = async () => {
                                        await asyncForEach(orders_with_invoices, async (item, index) => {
                                            var order_id = parseInt($(item).val());
                                            var is_first = true;
                                            if (index > 0) {
                                                is_first = false;
                                            }
                                            var payload = {
                                                data: {
                                                    order_id: order_id,
                                                    is_first: is_first
                                                },
                                                security: smartbill.security,
                                                action: 'smartbill_woocommerce_issue_document'
                                            };
                                            var result = await call_mail_doc(payload, true, false);
                                            result = JSON.parse(result);
                                            if (result.hasOwnProperty('headers')) {
                                                if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {
                                                    var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                                    var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                                    var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;
                                                    await waitFor(time_to_wait);
                                                }
                                            }
                                        });
                                    };
                                    start().then(function () {
                                        swal.close();
                                        window.location.reload();
                                    });
                                });
                            } else {
                                e.preventDefault();
                                var info_text = document.createElement("div");
                                info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                                swal({
                                    title: 'Se incarca!',
                                    content: info_text,
                                    icon: 'info',
                                    buttons: [false, false]
                                });
                                const start = async () => {
                                    await asyncForEach(orders_with_invoices, async (item, index) => {
                                        var order_id = parseInt($(item).val());
                                        var is_first = true;
                                        if (index > 0) {
                                            is_first = false;
                                        }
                                        var payload = {
                                            data: {
                                                order_id: order_id,
                                                is_first: is_first
                                            },
                                            security: smartbill.security,
                                            action: 'smartbill_woocommerce_issue_document'
                                        };
                                        var result = await call_mail_doc(payload, true, false);
                                        result = JSON.parse(result);
                                        if (result.hasOwnProperty('headers')) {
                                            if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {
                                                var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                                var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                                var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;
                                                await waitFor(time_to_wait);
                                            }
                                        }
                                    });
                                };
                                start().then(function () {
                                    swal.close();
                                    window.location.reload();
                                });
                            }
                        } else if (pending_orders.length) {
                            attention_text = document.createElement("div");
                            attention_text.innerHTML = "Comenzile selectate nu sunt facturate in SmartBill.";
                            if (pending_orders.length == 1) {
                                attention_text.innerHTML = "Comanda selectata nu este facturata in SmartBill.";
                            }

                            swal({ title: "Atentie!", content: attention_text, icon: 'warning', buttons: ['Renunta', 'Emite in SmartBill'] }).then(function (result) {
                                if (!result) { return false; }
                                var info_text = document.createElement("div");
                                info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                                swal({
                                    title: 'Se incarca!',
                                    content: info_text,
                                    icon: 'info',
                                    buttons: [false, false]
                                });

                                const start = async () => {
                                    await asyncForEach(pending_orders, async (item, index) => {
                                        var order_id = parseInt($(item).val());
                                        var is_first = true;
                                        if (index > 0) {
                                            is_first = false;
                                        }
                                        var payload = {
                                            data: {
                                                order_id: order_id,
                                                is_first: is_first
                                            },
                                            security: smartbill.security,
                                            action: 'smartbill_woocommerce_issue_document'
                                        };
                                        var result = await call_issue_doc(payload, true, false);
                                        result = JSON.parse(result);
                                        if (result.hasOwnProperty('headers')) {
                                            if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {
                                                var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                                var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                                var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;
                                                await waitFor(time_to_wait);
                                            }
                                        }
                                    });
                                };
                                start().then(function () {
                                    swal.close();
                                    window.location.reload();
                                });

                            });
                        }
                    }
                }
                else if (sel.val().indexOf('mark_') !== -1) {

                    //Check if smartbill setting is enabled.
                    if (0 !== smartbill.woocommerce_status.length) {

                        //Backwards compatibility. Ensure that smartbill setting is array.
                        if (!Array.isArray(smartbill.woocommerce_status)) {
                            smartbill.woocommerce_status = [smartbill.woocommerce_status];
                        }

                        //Check is selected order status is included in smartbill settings.
                        if (smartbill.woocommerce_status.includes(sel.val().replace('mark_', 'wc-'))) {
                            if (pending_orders.length) {
                                e.preventDefault();
                                attention_text = document.createElement("div");
                                var titleSwal = "Modulul SmartBill a detectat modificarea statusului comenzilor.";
                                var message = [];
                                var textButton = 'Emite documente';
                                pending_orders.forEach(function (item, index) {
                                    var order_id = parseInt($(item).val());
                                    message.push('#' + order_id);
                                });
                                pending_orders.sort((a, b) => (a.value > b.value) ? 1 : -1);

                                attention_text.innerHTML = "Comenzile " + message.join(', ') + " vor fi emise in SmartBill.";
                                if (pending_orders.length == 1) {
                                    attention_text.innerHTML = "Comanda " + message.join(', ') + " va fi emisa in SmartBill.";
                                    titleSwal = "Modulul SmartBill a detectat modificarea statusului unei comenzi.";
                                    textButton = 'Emite document';
                                }
                                swal({
                                    title: titleSwal,
                                    content: attention_text,
                                    icon: 'warning',
                                    buttons: ['Mai tarziu', textButton]
                                }).then(async function (result) {
                                    if (!result) {
                                        $(e.currentTarget.form).submit();
                                    } else {
                                        var info_text = document.createElement("div");
                                        info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                                        swal({
                                            title: 'Se incarca!',
                                            content: info_text,
                                            icon: 'info',
                                            buttons: [false, false]
                                        });
                                        const start = async () => {
                                            await asyncForEach(pending_orders, async (item, index) => {
                                                var order_id = parseInt($(item).val());
                                                var is_first = true;
                                                if (index > 0) {
                                                    is_first = false;
                                                }
                                                var payload = {
                                                    data: {
                                                        order_id: order_id,
                                                        is_first: is_first
                                                    },
                                                    security: smartbill.security,
                                                    action: 'smartbill_woocommerce_issue_document'
                                                };
                                                var result = await call_issue_doc(payload, true, false);
                                                result = JSON.parse(result);
                                                if (result.hasOwnProperty('headers')) {
                                                    if (parseInt(result.headers['x-ratelimit-remaining']) <= maxCall) {
                                                        var server_date = Math.floor(new Date(parseInt(result.headers['x-ratelimit-reset']) * 1000).getTime() / 1000.0);
                                                        var curentr_date = Math.floor(new Date().getTime() / 1000.0);
                                                        var time_to_wait = Math.abs(curentr_date - server_date) * (maxCall + 1 - parseInt(result.headers['x-ratelimit-remaining'])) * 1000;
                                                        await waitFor(time_to_wait);
                                                    }
                                                }
                                            });
                                        };
                                        start().then(function () {
                                            swal.close();
                                            $(e.currentTarget.form).submit();
                                        });
                                    }
                                });

                            }
                        }
                    }

                }

            } else {
                return false;
            }
        });


        if ('undefined' !== typeof hide_toast) {

            //Check if warning message has been displayed.
            if (hide_toast[0]) {

                //Check if measuring unit has been selected.
                if ($('#smartbill_settings_display_um').find('option:selected').val() == 'no_value') {
                    Toastify({
                        text: 'Este necesara selectarea unei unitati de masura. <br/> Configureaza setarea in zona <strong><a style="color:white;" href="' + window.location.href + '#smartbill_settings_display_um">Setari produse</a></strong> si salveaza din nou.', duration: -1, newWindow: false, close: true, gravity: "top", position: 'center', backgroundColor: "#EF4136", stopOnFocus: true,
                    }).showToast();
                }
            } else {
                Toastify({
                    text: 'Verifica toate setarile modulului pentru a te asigura ca facturarea comenzilor in SmartBill se va face corect.', duration: -1, newWindow: false, close: true, gravity: "top", position: 'center', backgroundColor: "#2271b1", stopOnFocus: true,
                }).showToast();
            }
        }

        //Display custom warning on click on series select field.
        $("#smartbill_plugin_options_settings_document_series").click(function () {

            //If there are no children/series, they need to be added in smartbill cloud.
            if (0 == $(this).children().size()) {
                $(this).closest("td").find("div").removeClass("hide-element");
            }
        });

        //Display custom warning on click.
        $("#smartbill_settings_display_stock").click(function () {

            //If there are no children/werehouses, they need to be added in smartbill cloud.
            if (1 == $(this).children().size()) {
                $(this).closest("td").find("div").removeClass("hide-element");
            }
        });

        //Display custom warning on click.
        $("#smartbill_settings_display_um").click(function () {

            //If there are no children/measurin units, they need to be added in smartbill cloud.
            if ($(this).children().size() == 0 || $(this).children().size() == 1) {
                $(this).closest("td").find("div").removeClass("hide-element");
            }
        });

        const INVOICE_TYPE = 0;
        const ETIMATE_TYPE = 1;

        /**
         * Change document series based on document type.
         * @param  {boolean} type
         * 
         * @return {void}
         */
        function changeSmartBillDocumentType(type) {
            $('#smartbill_plugin_options_settings_document_series').find('option').each(function () {
                if ($(this).val() != -1) {
                    $(this).remove();
                }
            });
            var html = '';
            var value;
            var selectedSeries;
            var selected = '';

            if (INVOICE_TYPE == type) {
                selectedSeries = smartbill.selected_invoice_series;
                for (var key in smartbill.invoice_series) {
                    value = smartbill.invoice_series[key];
                    selected = '';
                    if (key == selectedSeries) {
                        selected = ' selected="selected" ';
                    }
                    html = '<option value="' + key + '" ' + selected + '>' + value + "</option>";
                    $('#smartbill_plugin_options_settings_document_series').append(html);
                }
                $('#smartbill_plugin_options_settings_document_series').attr('name', 'smartbill_plugin_options_settings[invoice_series]');
            } else if (ETIMATE_TYPE == type) {
                selectedSeries = smartbill.selected_estimate_series;
                for (var key in smartbill.estimate_series) {
                    value = smartbill.estimate_series[key];
                    selected = '';
                    if (key == selectedSeries) {
                        selected = ' selected="selected" ';
                    }
                    html = '<option value="' + key + '" ' + selected + '>' + value + "</option>";
                    $('#smartbill_plugin_options_settings_document_series').append(html);
                }

                $('#smartbill_plugin_options_settings_document_series').attr('name', 'smartbill_plugin_options_settings[estimate_series]');
            }
        }

        //Copy link to clipboard and display message on click.
        $("#url_container_stocks strong").click(function (e) {
            navigator.clipboard.writeText(this.innerText);
            Toastify({
                text: "URL-ul a fost copiat cu succes!", duration: 3000, newWindow: false, close: true, gravity: "top", position: 'center', backgroundColor: "#00A14B", stopOnFocus: true,
            }).showToast();
        });

        $('.smartbill_sync_stock').click(function (event) {

            //If smartbill stock syncronization is enabled to yes display details.
            if (1 == $(this).val()) {
                $('.token-details').show();
                $('.smartbill-show-sync-stock-both-ways').show();
            }
            else {
                $('.token-details').hide();
                $('.smartbill-show-sync-stock-both-ways').hide();
            }
        });

        //If smartbill stock syncronization is enabled to yes display details.
        if (1 == $('.smartbill_sync_stock').val()) {
            $('.token-details').show();
            $('.smartbill-show-sync-stock-both-ways').show();
        }

        $('#custom-checkout').click(function (event) {

            //If smartbill custom checkout is enabled, display settings table.
            if (1 == $(this).val()) {
                $('#custom-checkout-options-table').closest('tr').show();
            } else {
                $('#custom-checkout-options-table').closest('tr').hide();
            }
        });

        //If smartbill custom checkout is enabled, display settings table.
        if (1 == $('#custom-checkout').val()) {
            $('#custom-checkout-options-table').closest('tr').show();
        } else {
            $('#custom-checkout-options-table').closest('tr').hide();
        }

        $('.show_delivery_days').click(function (event) {

            //If smartbill delivery day is enabled, display input field.
            if ($(this).val() == 1) {
                $('.smartbill-show-if-delivery-days-on').show();
            }
            else {
                $('.smartbill-show-if-delivery-days-on').hide();
            }
        });

        //If smartbill delivery day is enabled, display input field.
        if (1 == $('.show_delivery_days').val()) {
            $('.smartbill-show-if-delivery-days-on').show();
        }

        $('.show_shipping_cost').click(function (event) {

            //If smartbill shipping is enabled, display input field.
            if ($(this).val() == 1) {
                $('.smartbill-show-if-shipping-cost-is-included').show();
            }
            else {
                $('.smartbill-show-if-shipping-cost-is-included').hide();
            }
        });

        //If smartbill shipping is disabled, hide input field.
        if ($('.show_shipping_cost').val() == 0) {
            $('.smartbill-show-if-shipping-cost-is-included').hide();
        }

        $('.smartbill-public-invoice').click(function (event) {

            //If smartbill public invoice is enabled, display input field.
            if (1 == $(this).val()) {
                $('.public-invoice-name').show();
            }
            else {
                $('.public-invoice-name').hide();
            }
        });

        //If smartbill public invoice is disabled, hide input field.
        if (0 == $('.smartbill-public-invoice').val()) {
            $('.public-invoice-name').hide();
        }

        $('.smartbill-discount-name').click(function (event) {

            //If smartbill product discount is enabled, display input field.
            if ($(this).val() == 1) {
                $('.discount_text').show();
            }
            else {
                $('.discount_text').hide();
            }
        });

        //If smartbill product discount is disabled, hide input field.
        if (0 == $('.smartbill-discount-name').val()) {
            $('.discount_text').hide();
        }

        $('.issue_with_due_date').click(function (event) {

            //If smartbill invoice due date is enabled, dispaly input field.
            if (1 == $(this).val()) {
                $('.smartbill-show-if-due-days-on').show();
            }
            else {
                $('.smartbill-show-if-due-days-on').hide();
            }
        });
        if (1 == $('.issue_with_due_date').val()) {
            $('.smartbill-show-if-due-days-on').show();
        }

        $('.smartbill-delegate-data').click(function (event) {
            //If smartbill delegate info is enabled, dispaly input fields.
            if ('1' == $(this).val()) {
                $('.smartbill-show-if-add-delegate-data-on').show();
            } else {
                $('.smartbill-show-if-add-delegate-data-on').hide();
            }
        });

        //If smartbill delegate info is disabled, hide input fields.
        if ('0' == $('.smartbill-delegate-data').val()) {
            $('.smartbill-show-if-add-delegate-data-on').hide();
        }

        /**
         * Generate smartbill document from order info page.
         * 
         * @param  {Array} e
         * 
         * @return {Response | false}
         */
        async function smartbill_issue_document_from_link(e) {
            e.preventDefault();
            if (!e.detail || 1 == e.detail) {
                var attention_text;
                try {

                    var payload = {
                        data: {
                            order_id: smartbill.post_id
                        },
                        security: smartbill.security,
                        action: 'smartbill_woocommerce_issue_document'
                    };
                    if ('order' in $(e.currentTarget).data()) {
                        payload.data.order_id = $(e.currentTarget).data()['order'];
                    }

                    if ($(this).hasClass('reissue')) {
                        attention_text = document.createElement("div");
                        attention_text.innerHTML = "Comanda a fost deja facturata in SmartBill.<br/>Inainte de reemitere va recomandam sa anulati sau sa stergeti documentul emis anterior.";
                        swal({
                            title: "Atentie!",
                            content: attention_text,
                            icon: 'warning',
                            buttons: ['Renunta', 'Reemite in SmartBill']
                        }).then(async function (result) {
                            if (!result) {
                                return false;
                            }

                            await call_issue_doc(payload, false, true).then(function () {
                                window.location.reload();
                            });
                        });
                    } else {
                        await call_issue_doc(payload, false, true).then(function () {
                            window.location.reload();
                        });
                    }
                } catch (e) {
                    swal.close();
                    attention_text = document.createElement("div");
                    attention_text.innerHTML = e.responseText;
                    swal({
                        title: "Atentie!",
                        content: attention_text,
                        icon: 'error',
                        buttons: [false, 'Ok']
                    }).then(function (result) {
                        if (!result) { return false; } else { return false; }
                    });

                }
            } else {
                return false;
            }
        }

        /**
        * Do calls asyncronously for an array list.
        * 
        * @param  {Array} array
        * @param  {callback} callback
        * 
        * @return {Response}
        */
        async function asyncForEach(array, callback) {
            for (let index = 0; index < array.length; index++) {
                await callback(array[index], index, array);
            }
        }

        //Wait for timeout to end.
        const waitFor = (ms) => new Promise(r => setTimeout(r, ms));

        $('a#smartbill-woocommerce-invoice-button').click(smartbill_issue_document_from_link);
        $('a.smartbill-woocommerce-invoice-button').click(smartbill_issue_document_from_link);

        /**
        * Send email to client through smartbill.
        * 
        * @param  {Array} e
        * 
        * @return {Response | false}
        */
        async function smartbill_send_document_mail(e) {
            e.preventDefault();
            if (!e.detail || 1 == e.detail) {
                var payload = {
                    data: {
                        order_id: smartbill.post_id
                    },
                    security: smartbill.security,
                    action: 'smartbill_woocommerce_send_document_mail'
                };
                if ('order' in $(e.currentTarget).data()) {
                    payload.data.order_id = $(e.currentTarget).data()['order'];
                }

                await call_mail_doc(payload, false, true).then(function () {
                    window.location.reload();
                });
            } else {
                return false;
            }
        }

        $('a#smartbill-woocommerce-send-document-email-button').click(smartbill_send_document_mail);
        $('a.smartbill-woocommerce-send-document-email-button').click(smartbill_send_document_mail);


        /**
        * Ajax call for sending mail with smartbill document.
        * 
        * @param  {Array} payload
        * @param  {boolean} payload //Used to modify succes message.
        * @param  {boolean} show_toast //Used to display/hide warning.
        * 
        * @return {Response}
        */
        async function call_mail_doc(payload, catch_all, show_toast) {
            let result;
            var info_text = document.createElement("div");
            info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
            if (true == show_toast) {
                swal({
                    title: 'Se incarca!',
                    content: info_text,
                    icon: 'info',
                    buttons: [false, false]
                });
            }
            result = await $.post(ajaxurl, payload, function (response) {
                response = JSON.parse(response);
                if (response && response.status) {
                    if ('undefined' != typeof catch_all && catch_all) {
                        if (successMessages != 0) {
                            successMessages = ['Documentele au fost trimise cu succes catre clienti.'];
                        }
                        else {
                            successMessages.push(response.message);
                        }
                        saveMessages();
                    }
                    else {
                        successMessages.push(response.message);
                        saveMessages();
                    }
                }
                else {
                    var span = document.createElement('span');
                    span.innerHTML = decodeHTMLEntities(response.error);
                    errorMessages.push("Eroare! " + span.innerText.replace("Smart Bill", "SmartBill").replace("SmartBill Cloud", "SmartBill"));
                }
                if (true == show_toast) {
                    swal.close();
                }
                saveMessages();
                return successMessages;

            });
            return result;
        }

        /**
        * Ajax call for creating smartbill document.
        * 
        * @param  {Array} payload
        * @param  {boolean} payload //Used to modify succes message.
        * @param  {boolean} show_toast //Used to display/hide warning.
        * 
        * @return {Response}
        */
        async function call_issue_doc(payload, catch_all, show_toast) {
            let result;
            $('#smartbill-woocommerce-invoice-button').css('background', '#f3f5f6 url("/wp-admin/images/spinner.gif") no-repeat left center');
            $('#smartbill-woocommerce-invoice-button').css('padding-left', '25px');

            if (true == show_toast) {
                var info_text = document.createElement("div");
                info_text.innerHTML = "Va rugam asteptati raspunsul serverului...";
                swal({
                    title: 'Se incarca!',
                    content: info_text,
                    icon: 'info',
                    buttons: [false, false]
                });
            }
            result = await $.post(ajaxurl, payload, function (response) {
                response = JSON.parse(response);
                $('html,body').animate({ scrollTop: 0 }, 'slow');
                if (response && response.status) {

                    //Display loader.
                    $('#smartbill-woocommerce-invoice-button').css('background', 'none');
                    $('#smartbill-woocommerce-invoice-button').css('background-color', '#f3f5f6');
                    $('#smartbill-woocommerce-invoice-button').css('padding-left', '10px');
                    $('#smartbill-woocommerce-invoice-button').css('border-color', '#007cba');
                    $('#smartbill-woocommerce-invoice-button').css('box-shadow', '0 0 0 1px #007cba');
                    $('#smartbill-woocommerce-invoice-button').css('outline', '2px solid transparent');
                    $('#smartbill-woocommerce-invoice-button').css('outline-offset', '0');

                    if (true == show_toast) {
                        swal.close();
                    }

                    if ('undefine' != typeof catch_all && catch_all) {

                        if (successMessages.length != 0) {
                            successMessages = ['Documentele au fost emise cu succes in SmartBill.'];
                        }
                        else {
                            successMessages.push(response.message);
                        }
                        saveMessages();

                    } else {
                        successMessages.push(response.message);
                        saveMessages();
                    }
                } else {
                    $('#smartbill-woocommerce-invoice-button').css('background-image', 'none');
                    $('#smartbill-woocommerce-invoice-button').css('padding-left', '10px');
                    var span = document.createElement('span');
                    span.innerHTML = decodeHTMLEntities(response.message);

                    if (true == show_toast) {
                        swal.close();
                    }

                    //Additional changes to the succes/error messages.
                    var smrt_mes = span.innerText.replace("Smart Bill", "SmartBill").replace("SmartBill Cloud", "SmartBill");
                    if ("Verifica setarile modulului SmartBill" == smrt_mes) {
                        errorMessages.push("Factura pentru comanda #" + payload.data.order_id + " nu a fost emisa in SmartBill. <br/>" + span.innerText.replace("Smart Bill", "SmartBill").replace("SmartBill Cloud", "SmartBill") + ".");
                    } else if ("" == smrt_mes) {
                        errorMessages.push("A aparut o eroare la contactarea serverului SmartBill. Acceseaza cloud.smartbill.ro, anuleaza factura pentru comanda #" + payload.data.order_id + " si incearca din nou facturarea comenzii din magazinul online.");
                    } else {
                        errorMessages.push("Factura pentru comanda #" + payload.data.order_id + " nu a fost emisa in SmartBill. <br/> Eroare " + span.innerText.replace("Smart Bill", "SmartBill").replace("SmartBill Cloud", "SmartBill") + ".");
                    }
                    saveMessages();
                }
                return successMessages;
            });
            return result;
        }




        // Settings page - show/hide elements.
        var selected_value = "1";

        // Show/hide company info.
        if ($('select.smartbill_display_company_info').val() == selected_value) {
            $('.smartbill-show-if-company-info').show();
        }
        else {
            $('.smartbill-show-if-company-info').hide();
        }
        // Show/hide company info on change select field.
        $('select.smartbill_display_company_info').on('change', function (e) {
            if (this.value === selected_value) {
                $('.smartbill-show-if-company-info').show();
            }
            else {
                $('.smartbill-show-if-company-info').hide();
            }
        });

        // If measuring unit is not selected add red background to indicate wrong configuration.
        if ($("#smartbill_settings_display_um").find("option:selected").length) {
            if ("no_value" == $("#smartbill_settings_display_um").find("option:selected")[0].value) {
                $("#smartbill_settings_display_um").closest("tr").find(".settings-font-size").first().addClass("smartbill-error-highlight");
            }
        }

        // Show/hide send document to client settings.
        if ($('select.smartbill_display_send_mail_with_document').val() == selected_value) {
            $('.smartbill-show-if-send-mail').show();
        }
        else {
            $('.smartbill-show-if-send-mail').hide();
        }

        // Show/hide send document to client settings on change select field.
        $('select.smartbill_display_send_mail_with_document').on('change', function (e) {
            if (this.value === selected_value) {
                $('.smartbill-show-if-send-mail').show();
            }
            else {
                $('.smartbill-show-if-send-mail').hide();
            }
        });

        // Show/hide settings for invoice.
        if ($('select#smartbill_plugin_options_settings_document_type').val() != selected_value) {
            $('.smartbill-show-if-type-invoice').show();
            $('label[for="smartbill-display-payment-url"]').text('Afiseaza buton "Plateste cu cardul" pe factura');
        }
        else {
            $('.smartbill-show-if-type-invoice').hide();
            //$('label[for="smartbill-display-payment-url"]').parent().parent().hide();
            $('label[for="smartbill-display-payment-url"]').text('Afiseaza buton "Plateste cu cardul" pe proforma');
        }

        // Show/hide settings for invoice when document type select field changes.
        $('select#smartbill_plugin_options_settings_document_type').on('change', function (e) {
            if (this.value !== selected_value) {
                $('.smartbill-show-if-type-invoice').show();
                $('label[for="smartbill-display-payment-url"]').text('Afiseaza buton "Plateste cu cardul" pe factura');
            }
            else {
                $('.smartbill-show-if-type-invoice').hide();
                //$('label[for="smartbill-display-payment-url"]').parent().parent().hide();
                $('label[for="smartbill-display-payment-url"]').text('Afiseaza buton "Plateste cu cardul" pe proforma');
            }
            // Change document series based on document type.
            changeSmartBillDocumentType(this.value);
        });

        // Show/hide sync stock settings.
        if ($('select.smartbill_display_save_stock_history').val() != selected_value) {
            $('#smartbill-download-sync-stock-history').hide();
        }
        else {
            $('#smartbill-download-sync-stock-history').show();
        }
        $('select.smartbill_display_save_stock_history').on('change', function (e) {
            if (this.value !== selected_value) {
                $('#smartbill-download-sync-stock-history').hide();
            }
            else {
                $('#smartbill-download-sync-stock-history').show();
            }
        });

        //Move admin billing address elements. 
        $('.smartbill_billing_nr_reg_com_field').css({ "float": "right", "clear": "right" });
        $("._billing_company_field").after($(".smartbill_billing_cif_field").first());
        $(".smartbill_billing_cif_field").first().after($(".smartbill_billing_nr_reg_com_field").first());
        $('div.edit_address').first().prepend($(".smartbill_billing_type_field").first());

        //Move admin shipping address elements. 
        $('.smartbill_shipping_nr_reg_com_field').css({ "float": "right", "clear": "right" });
        $("._shipping_company_field").after($(".smartbill_shipping_cif_field").first());
        $(".smartbill_shipping_cif_field").first().after($(".smartbill_shipping_nr_reg_com_field").first());
        $('div.edit_address').last().prev().prepend($(".smartbill_shipping_type_field").first());

    });

    /**
     * Function that returns text with decoded html entities.
     * 
     * @param  {string} text
     * 
     * @return {string}
     */
    function decodeHTMLEntities(text) {
        var entities = [
            ['amp', '&'],
            ['apos', '\''],
            ['#x27', '\''],
            ['#x2F', '/'],
            ['#39', '\''],
            ['#47', '/'],
            ['lt', '<'],
            ['gt', '>'],
            ['nbsp', ' '],
            ['quot', '"']
        ];
        for (var i = 0, max = entities.length; i < max; ++i) {
            text = text.replace(new RegExp('&' + entities[i][0] + ';', 'g'), entities[i][1]);
        }

        text = text.replace(new RegExp('</b>', 'g'), '</strong>');
        text = text.replace(new RegExp('<b>', 'g'), '<strong>');

        return text;
    }
})(jQuery);
