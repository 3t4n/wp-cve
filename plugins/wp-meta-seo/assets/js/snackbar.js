'use strict';

/**
 * Snackbar main module
 */
/*
How to use snack module
    wpmsSnackbarModule.show(
        {
            id: 'id-here',
            content: 'content-alert',
            icon: '',
            auto_close: false,
            is_progress: true,
            ...
        }
    ); */
var wpmsSnackbarModule = void 0;
(function ($) {
    wpmsSnackbarModule = {
        snackbar_ids: [],
        $snackbar_wrapper: null, // Snackbar jQuery wrapper
        snackbar_defaults: {
            onClose: function onClose() {}, // Callback function when snackbar is closed
            is_undoable: false, // Show or not the undo button
            onUndo: function onUndo() {}, // Callback function when snackbar is undoed
            icon: '<span class="material-icons-outlined material-icons wpms-snack-icon">notification_important</span>',
            is_closable: true, // Can this snackbar be closed by user
            auto_close: true, // Do the snackbar close automatically
            auto_close_delay: 6000, // Time to wait before closing automatically
            is_progress: false, // Do we show the progress bar
            percentage: null // Percentage of the progress bar
        },

        /**
         * Initialize snackbar module
         */
        initModule: function initModule() {
            wpmsSnackbarModule.$snackbar_wrapper = $('<div class="wpms-snackbar-wrapper"></div>').appendTo('body');
        },

        /**
         * Display a new snackbar
         * @param options
         * @return HTMLElement the snackbar generated
         */
        show: function show(options) {
            if (options === undefined) {
                options = {};
            }

            // Set default values
            options = $.extend({}, wpmsSnackbarModule.snackbar_defaults, options);

            // If an id is set save it
            if (typeof options.id === "undefined") {
                options.id = options.content;
            }
            if (options.id !== undefined) {
                wpmsSnackbarModule.snackbar_ids[options.id] = options;
            }

            return wpmsSnackbarModule.renderSnack(options);
        },

        renderSnack: function renderSnack(notification_options) {
            var notification_class = 'wpms-snackbar-wrap';
            if (typeof notification_options !== "undefined" && typeof notification_options.error !== "undefined" && notification_options.error) {
                notification_class += ' wpms-snackbar-error';
            }
            var snack = '<div class="' + notification_class + '">';
            var snack_count = 0;
            Object.keys(wpmsSnackbarModule.snackbar_ids).map(function (snack_id, index) {
                snack_count++;
                var options = wpmsSnackbarModule.snackbar_ids[snack_id];
                // Generate undo html if needed
                var undo = '';
                if (options.is_undoable) {
                    undo = '<a href="#" class="wpms-snackbar-undo">' + wpms.l18n.wpms_undo + '</a>';
                }

                var id = '';
                if (options.id) {
                    id = 'data-id="' + options.id + '"';
                }

                snack += '<div ' + id + ' class="wpms-snackbar">\n                        ' + options.icon + '\n                        <div class="wpms-snackbar-content">' + options.content + '</div>\n                        ' + undo + '                        \n                    </div>';
            });

            snack += '<a class="wpms-snackbar-close" href="#"><i class="material-icons">close</i></a>';
            snack += '</div>';

            // Add element to the DOM
            $('.wpms-snackbar-wrap').remove();
            if (snack_count > 0) {
                var $snack = $(snack).prependTo(wpmsSnackbarModule.$snackbar_wrapper);

                // Initialize undo function
                $snack.find('.wpms-snackbar-undo').click(function (e) {
                    var snack_id = $(this).closest('.wpms-snackbar').data('id');
                    e.preventDefault();
                    wpmsSnackbarModule.snackbar_ids[snack_id].onUndo();
                    // Reset the close function as we've done an undo
                    wpmsSnackbarModule.snackbar_ids[snack_id].onClose = function () {};
                    // Finally close the snackbar
                    wpmsSnackbarModule.snackbar_ids[snack_id].close(snack_id);
                });

                Object.keys(wpmsSnackbarModule.snackbar_ids).map(function (snack_id, index) {
                    // Initialize autoclose feature
                    var options = wpmsSnackbarModule.snackbar_ids[snack_id];
                    if (options.auto_close) {
                        setTimeout(function () {
                            wpmsSnackbarModule.close(options.id);
                        }, options.auto_close_delay);
                    }
                });

                // Initialize close button
                $snack.find('.wpms-snackbar-close').click(function (e) {
                    $(this).closest('.wpms-snackbar-wrap').remove();
                    wpmsSnackbarModule.snackbar_ids = [];
                });
            }
        },

        /**
         * Remove a snackbar and call onClose callback if needed
         * @param snack_id snackbar element
         */
        close: function close(snack_id) {
            // Remove the id if exists
            if (snack_id !== undefined) {
                delete wpmsSnackbarModule.snackbar_ids[snack_id];
            }

            wpmsSnackbarModule.renderSnack();
        },

        /**
         * Retrieve an existing snackbar from its id
         * @param id
         * @return {null|object}
         */
        getFromId: function getFromId(id) {
            if (wpmsSnackbarModule.snackbar_ids[id] === undefined) {
                return null;
            }

            return id;
        },

        /**
         * Set the snackbar progress bar width
         * @param $snack jQuery element representing a snackbar
         * @param percentage int
         */
        setProgress: function setProgress($snack, percentage) {
            if ($snack === null) {
                return;
            }

            var $progress = $snack.find('.wpmsliner_progress > div');
            if (percentage !== undefined) {
                $progress.addClass('determinate').removeClass('indeterminate');
                $progress.css('width', percentage + '%');
            } else {
                $progress.addClass('indeterminate').removeClass('determinate');
            }
        }
    };

    // Let's initialize wpms features
    $(document).ready(function () {
        wpmsSnackbarModule.initModule();
    });
})(jQuery);
