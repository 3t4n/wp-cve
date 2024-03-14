/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdmaq_admin_data, ajaxurl*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdmaq = window.wp.gdmaq || {};

    window.wp.gdmaq.admin = {
        storage: {
            url: ""
        },
        init: function() {
            if (gdmaq_admin_data.page === "tools") {
                wp.gdmaq.admin.tools.init();
            }

            if (gdmaq_admin_data.page === "settings") {
                wp.gdmaq.admin.settings.init();
            }

            if (gdmaq_admin_data.page === "log") {
                wp.gdmaq.admin.dialogs.log();
                wp.gdmaq.admin.log.init();
            }
        },
        dialogs: {
            classes: function(extra) {
                var cls = "wp-dialog d4p-dialog gdmaq-modal-dialog";

                if (extra !== "") {
                    cls+= " " + extra;
                }

                return cls;
            },
            defaults: function() {
                return {
                    width: 480,
                    height: "auto",
                    minHeight: 24,
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    closeOnEscape: false,
                    zIndex: 300000,
                    open: function() {
                        $(".gdmaq-button-focus").focus();
                    }
                };
            },
            icons: function(id) {
                $(id).next().find(".ui-dialog-buttonset button").each(function(){
                    var icon = $(this).data("icon");

                    if (icon !== "") {
                        $(this).find("span.ui-button-text").prepend(gdmaq_admin_data["button_icon_" + icon]);
                    }
                });
            },
            log: function() {
                var dlg_delete_single = $.extend({}, wp.gdmaq.admin.dialogs.defaults(), {
                    dialogClass: wp.gdmaq.admin.dialogs.classes("gdmaq-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdmaq-delete-delsingle-delete",
                            class: "gdmaq-dialog-button-delete",
                            text: gdmaq_admin_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdmaq.admin.storage.url;
                            }
                        },
                        {
                            id: "gdmaq-delete-delsingle-cancel",
                            class: "gdmaq-dialog-button-cancel gdmaq-button-focus",
                            text: gdmaq_admin_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdmaq-dialog-log-delete-single").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_log_entry = $.extend({}, wp.gdmaq.admin.dialogs.defaults(), {
                    width: 680,
                    height: 520,
                    buttons: [
                        {
                            id: "gdmaq-log-entry-ok",
                            class: "gdmaq-dialog-button-ok",
                            text: gdmaq_admin_data.dialog_button_ok,
                            data: { icon: "ok" },
                            click: function() {
                                $("#gdmaq-dialog-log-view-entry").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdmaq-dialog-log-delete-single").wpdialog(dlg_delete_single);
                $("#gdmaq-dialog-log-view-entry").wpdialog(dlg_log_entry);

                wp.gdmaq.admin.dialogs.icons("#gdmaq-dialog-log-delete-single");
                wp.gdmaq.admin.dialogs.icons("#gdmaq-dialog-log-view-entry");
            }
        },
        log: {
            init: function() {
                $(".gdmaq-action-delete-entry").click(function(e){
                    e.preventDefault();

                    wp.gdmaq.admin.storage.url = $(this).attr("href");

                    $("#gdmaq-dialog-log-delete-single").wpdialog("open");
                });

                $(".gdmaq-action-view-entry").click(function(e){
                    e.preventDefault();

                    var log = $(this).data("log"),
                        nonce = $(this).data("nonce");

                    $("#gdmaq-view-entry-inner").html(gdmaq_admin_data.dialog_content_pleasewait);
                    $("#gdmaq-dialog-log-view-entry").wpdialog("open");

                    $.ajax({
                        success: function(html) {
                            $("#gdmaq-view-entry-inner").html(html);
                        },
                        dataType: "html", data: {id: log},
                        type: "post", timeout: 15 * 60 * 1000,
                        url: ajaxurl + "?action=gdmaq_log_entry_preview&_ajax_nonce=" + nonce
                    });
                });
            }
        },
        settings: {
            init: function() {
                $(document).on("change", "#gdmaqvalue_engine_phpmailer_mode", function(){
                    var service = $(this).val();

                    $('[class^="d4p-group d4p-group-service_"], [class^="d4p-group-separator d4p-group-service_"]').hide();

                    if (service !== 'mail') {
                        $('[class^="d4p-group d4p-group-service_' + service + '"], [class^="d4p-group-separator d4p-group-service_' + service + '"]').show();
                    }
                });
            }
        },
        tools: {
            init: function() {
                if (gdmaq_admin_data.panel === "export") {
                    wp.gdmaq.admin.tools.export();
                }

                if (gdmaq_admin_data.panel === "test") {
                    wp.gdmaq.admin.tools.test();
                }

                if (gdmaq_admin_data.panel === "queue") {
                    wp.gdmaq.admin.tools.queue();
                }
            },
            export: function() {
                $("#gdmaq-tool-export").click(function(e){
                    e.preventDefault();

                    var url = $("#gdmaq-export-url").val();

                    if ($("#gdmaqtools-export-settings").is(":checked")) {
                        url+= "&export[]=settings";
                    }

                    if ($("#gdmaqtools-export-statistics").is(":checked")) {
                        url+= "&export[]=statistics";
                    }

                    window.location = url;
                });
            },
            test: function() {
                $("#gdmaq-tool-test").click(function(e){
                    e.preventDefault();

                    $("#d4p-tools-test-results").show()
                        .find(".d4p-group-inner")
                        .html(gdmaq_admin_data.dialog_content_pleasewait);

                    $("#gdmaq-tools-form").find("[name='action']").remove();

                    $("#gdmaq-tools-form").ajaxSubmit({
                        success: function(html) {
                            $("#d4p-tools-test-results .d4p-group-inner").html(html);
                        },
                        dataType: "html", type: "post", timeout: 5 * 60 * 1000,
                        url: ajaxurl + "?action=gdmaq_tools_emailtest"
                    });
                });
            },
            queue: function() {
                $("#gdmaq-tool-queue").click(function(e){
                    e.preventDefault();

                    $("#d4p-tools-test-results").show()
                        .find(".d4p-group-inner")
                        .html(gdmaq_admin_data.dialog_content_pleasewait);

                    $("#gdmaq-tools-form").find("[name='action']").remove();

                    $("#gdmaq-tools-form").ajaxSubmit({
                        success: function(html) {
                            $("#d4p-tools-test-results .d4p-group-inner").html(html);
                        },
                        dataType: "html", type: "post", timeout: 5 * 60 * 1000,
                        url: ajaxurl + "?action=gdmaq_tools_queuetest"
                    });
                });
            }
        }
    };

    wp.gdmaq.admin.init();
})(jQuery, window, document);
