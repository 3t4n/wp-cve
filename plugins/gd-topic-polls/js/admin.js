/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global d4plib_admin_data, ajaxurl*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdpol_admin = window.wp.gdpol_admin || {};

    window.wp.gdpol_admin = {
        init: function() {
            if (d4plib_admin_data.page.panel === "polls") {
                wp.gdpol_admin.dialogs.polls();
                wp.gdpol_admin.polls.init();
            }

            if (d4plib_admin_data.page.panel === "votes") {
                wp.gdpol_admin.dialogs.votes();
                wp.gdpol_admin.votes.init();
            }
        },
        dialogs: {
            polls: function() {
                var dialog_delete = $.extend({}, wp.dev4press.dialogs.default_dialog(), {
                    dialogClass: wp.dev4press.dialogs.classes("", true),
                    buttons: [
                        $.extend({}, wp.dev4press.dialogs.default_button("delete", false), {
                            click: function() {
                                window.location.href = wp.dev4press.dialogs.storage.url;
                            }
                        }),
                        $.extend({}, wp.dev4press.dialogs.default_button("cancel", false), {
                            click: function() {
                                $("#gdpol-dialog-polls-delete").wpdialog("close");
                            }
                        })
                    ]
                }), dialog_disable = $.extend({}, wp.dev4press.dialogs.default_dialog(), {
                    dialogClass: wp.dev4press.dialogs.classes("", true),
                    buttons: [
                        $.extend({}, wp.dev4press.dialogs.default_button("disable", false), {
                            click: function() {
                                window.location.href = wp.dev4press.dialogs.storage.url;
                            }
                        }),
                        $.extend({}, wp.dev4press.dialogs.default_button("cancel", false), {
                            click: function() {
                                $("#gdpol-dialog-polls-disable").wpdialog("close");
                            }
                        })
                    ]
                }), dialog_empty = $.extend({}, wp.dev4press.dialogs.default_dialog(), {
                    dialogClass: wp.dev4press.dialogs.classes("", true),
                    buttons: [
                        $.extend({}, wp.dev4press.dialogs.default_button("empty", false), {
                            click: function() {
                                window.location.href = wp.dev4press.dialogs.storage.url;
                            }
                        }),
                        $.extend({}, wp.dev4press.dialogs.default_button("cancel", false), {
                            click: function() {
                                $("#gdpol-dialog-polls-empty").wpdialog("close");
                            }
                        })
                    ]
                });

                $("#gdpol-dialog-polls-delete").wpdialog(dialog_delete);
                $("#gdpol-dialog-polls-disable").wpdialog(dialog_disable);
                $("#gdpol-dialog-polls-empty").wpdialog(dialog_empty);

                wp.dev4press.dialogs.icons("#gdpol-dialog-polls-delete");
                wp.dev4press.dialogs.icons("#gdpol-dialog-polls-disable");
                wp.dev4press.dialogs.icons("#gdpol-dialog-polls-empty");
            },
            votes: function() {
                var dialog_delete = $.extend({}, wp.dev4press.dialogs.default_dialog(), {
                    dialogClass: wp.dev4press.dialogs.classes("", true),
                    buttons: [
                        $.extend({}, wp.dev4press.dialogs.default_button("delete", false), {
                            click: function() {
                                window.location.href = wp.dev4press.dialogs.storage.url;
                            }
                        }),
                        $.extend({}, wp.dev4press.dialogs.default_button("cancel", false), {
                            click: function() {
                                $("#gdpol-dialog-votes-delete").wpdialog("close");
                            }
                        })
                    ]
                });

                $("#gdpol-dialog-votes-delete").wpdialog(dialog_delete);

                wp.dev4press.dialogs.icons("#gdpol-dialog-votes-delete");
            }
        },
        polls: {
            init: function() {
                $(".gdpol-button-disable-poll").click(function(e) {
                    e.preventDefault();

                    wp.dev4press.dialogs.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-polls-disable").wpdialog("open");
                });

                $(".gdpol-button-delete-poll").click(function(e) {
                    e.preventDefault();

                    wp.dev4press.dialogs.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-polls-delete").wpdialog("open");
                });

                $(".gdpol-button-empty-poll").click(function(e) {
                    e.preventDefault();

                    wp.dev4press.dialogs.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-polls-empty").wpdialog("open");
                });
            }
        },
        votes: {
            init: function() {
                $(".gdpol-button-delete-vote").click(function(e) {
                    e.preventDefault();

                    wp.dev4press.dialogs.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-votes-delete").wpdialog("open");
                });
            }
        }
    };

    wp.gdpol_admin.init();
})(jQuery, window, document);
