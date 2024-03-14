(function($, document) {

    var wpsfsvi = {

        cache: function() {
            wpsfsvi.els = {};
            wpsfsvi.vars = {};

            wpsfsvi.els.tab_links = $('.wpsfsvi-nav__item-link');
            wpsfsvi.els.submit_button = $('.wpsfsvi-button-submit');
        },

        on_ready: function() {

            // on ready stuff here
            wpsfsvi.cache();
            wpsfsvi.trigger_dynamic_fields();
            wpsfsvi.setup_groups();
            wpsfsvi.tabs.watch();
            wpsfsvi.watch_submit();
            wpsfsvi.control_groups();
            wpsfsvi.importer.init();

            $(document.body).on('change', 'input, select, textarea', wpsfsvi.control_groups);
        },

        /**
         * Trigger dynamic fields
         */
        trigger_dynamic_fields: function() {

            wpsfsvi.setup_timepickers();
            wpsfsvi.setup_datepickers();

        },

        /**
         * Setup the main tabs for the settings page
         */
        tabs: {
            /**
             * Watch for tab clicks.
             */
            watch: function() {
                var tab_id = wpsfsvi.tabs.get_tab_id();

                if (tab_id) {
                    wpsfsvi.tabs.set_active_tab(tab_id);
                }

                wpsfsvi.els.tab_links.on('click', function(e) {
                    // Show tab
                    var tab_id = $(this).attr('href');

                    wpsfsvi.tabs.set_active_tab(tab_id);

                    e.preventDefault();
                });
            },

            /**
             * Is storage available.
             */
            has_storage: 'undefined' !== typeof(Storage),

            /**
             * Store tab ID.
             *
             * @param tab_id
             */
            set_tab_id: function(tab_id) {
                if (!wpsfsvi.tabs.has_storage) {
                    return;
                }

                localStorage.setItem(wpsfsvi.tabs.get_option_page() + '_wpsfsvi_tab_id', tab_id);
            },

            /**
             * Get tab ID.
             *
             * @returns {boolean}
             */
            get_tab_id: function() {
                if (!wpsfsvi.tabs.has_storage) {
                    return false;
                }

                return localStorage.getItem(wpsfsvi.tabs.get_option_page() + '_wpsfsvi_tab_id');
            },

            /**
             * Set active tab.
             *
             * @param tab_id
             */
            set_active_tab: function(tab_id) {
                var $tab = $(tab_id),
                    $tab_link = $('.wpsfsvi-nav__item-link[href="' + tab_id + '"]');

                if ($tab.length <= 0 || $tab_link.length <= 0) {
                    // Reset to first available tab.
                    $tab_link = $('.wpsfsvi-nav__item-link').first();
                    tab_id = $tab_link.attr('href');
                    $tab = $(tab_id);
                }

                // Set tab link active class
                wpsfsvi.els.tab_links.parent().removeClass('wpsfsvi-nav__item--active');
                $('a[href="' + tab_id + '"]').parent().addClass('wpsfsvi-nav__item--active');

                // Show tab
                $('.wpsfsvi-tab').removeClass('wpsfsvi-tab--active');
                $tab.addClass('wpsfsvi-tab--active');

                wpsfsvi.tabs.set_tab_id(tab_id);
            },

            /**
             * Get unique option page name.
             *
             * @returns {jQuery|string|undefined}
             */
            get_option_page: function() {
                return $('input[name="option_page"]').val();
            }
        },

        /**
         * Set up timepickers
         */
        setup_timepickers: function() {

            $('.timepicker').not('.hasTimepicker').each(function() {

                var timepicker_args = $(this).data('timepicker');

                // It throws an error if empty string is passed.
                if ('' === timepicker_args) {
                    timepicker_args = {};
                }

                $(this).timepicker(timepicker_args);

            });

        },

        /**
         * Set up timepickers
         */
        setup_datepickers: function() {
            $(document).on('focus', '.datepicker:not(.hasTimepicker)', function() {
                var datepicker_args = $(this).data('datepicker');

                $(this).datepicker(datepicker_args);
            });

            // Empty altField if datepicker field is emptied.
            $(document).on('change', '.datepicker', function() {
                var datepicker = $(this).data('datepicker');

                if (!$(this).val() && datepicker.settings && datepicker.settings.altField) {
                    $(datepicker.settings.altField).val('');
                }
            });
        },

        /**
         * Setup repeatable groups
         */
        setup_groups: function() {
            wpsfsvi.reindex_groups();

            // add row

            $(document).on('click', '.wpsfsvi-group__row-add', function() {

                var $group = $(this).closest('.wpsfsvi-group'),
                    $row = $(this).closest('.wpsfsvi-group__row'),
                    template_name = $(this).data('template'),
                    $template = $($('#' + template_name).html());

                $template.find('.wpsfsvi-group__row-id').val(wpsfsvi.generate_random_id());

                $row.after($template);

                wpsfsvi.reindex_group($group);

                wpsfsvi.trigger_dynamic_fields();

                return false;

            });

            // remove row

            $(document).on('click', '.wpsfsvi-group__row-remove', function() {

                var $group = jQuery(this).closest('.wpsfsvi-group'),
                    $row = jQuery(this).closest('.wpsfsvi-group__row');

                $row.remove();

                wpsfsvi.reindex_group($group);

                return false;

            });

        },

        /**
         * Generate random ID.
         *
         * @returns {string}
         */
        generate_random_id: function() {
            return (
                Number(String(Math.random()).slice(2)) +
                Date.now() +
                Math.round(performance.now())
            ).toString(36);
        },

        /**
         * Reindex all groups.
         */
        reindex_groups: function() {
            var $groups = jQuery('.wpsfsvi-group');

            if ($groups.length <= 0) {
                return;
            }

            $groups.each(function(index, group) {
                wpsfsvi.reindex_group(jQuery(group));
            });
        },

        /**
         * Reindex a group of repeatable rows
         *
         * @param arr $group
         */
        reindex_group: function($group) {
            var reindex_attributes = ['class', 'id', 'name', 'data-datepicker'];

            if (1 === $group.find(".wpsfsvi-group__row").length) {
                $group.find(".wpsfsvi-group__row-remove").hide();
            } else {
                $group.find(".wpsfsvi-group__row-remove").show();
            }

            $group.find(".wpsfsvi-group__row").each(function(index) {

                $(this).removeClass('alternate');

                if (index % 2 == 0) {
                    $(this).addClass('alternate');
                }

                $(this).find("input").each(function() {
                    var this_input = this,
                        name = jQuery(this).attr('name');

                    if (typeof name !== typeof undefined && name !== false) {
                        $(this_input).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                    }

                    $.each(this_input.attributes, function() {
                        if (this.name && this_input && $.inArray(this.name, reindex_attributes) > -1) {
                            $(this_input).attr(this.name, this.value.replace(/\_\d+\_/, '_' + index + '_'));
                        }
                    });
                });

                $(this).find('.wpsfsvi-group__row-index span').html(index);

            });
        },

        /**
         * Watch submit click.
         */
        watch_submit: function() {
            wpsfsvi.els.submit_button.on('click', function() {
                var $button = $(this),
                    $wrapper = $button.closest('.wpsfsvi-settings'),
                    $form = $wrapper.find('form').first();

                $form.submit();
            });
        },

        /**
         * Dynamic control groups.
         */
        control_groups: function() {
            // If show if, hide by default.
            $('.show-if').each(function(index) {
                var element = $(this);
                var parent_tag = element.parent().prop('nodeName').toLowerCase()
                var className = element.parent().prop('className');

                // Field.
                if ('td' === parent_tag || 'label' === parent_tag || className === 'wpsfsvi-color') {
                    element.closest('tr').hide();

                    wpsfsvi.maybe_show_element(element, function() {
                        element.closest('tr').show();
                    });
                }

                // Tab.
                if ('li' === parent_tag) {
                    element.closest('li').hide();

                    wpsfsvi.maybe_show_element(element, function() {
                        element.closest('li').show();
                    });
                }

                // Section.
                if ('div' === parent_tag && className != 'wpsfsvi-color') {
                    element.prev().hide();
                    element.next().hide();
                    if (element.next().hasClass('wpsfsvi-section-description')) {
                        element.next().next().hide();
                    }

                    wpsfsvi.maybe_show_element(element, function() {
                        element.prev().show();
                        element.next().show();
                        if (element.next().hasClass('wpsfsvi-section-description')) {
                            element.next().next().show();
                        }
                    });
                }
            });

            // If hide if, show by default.
            $('.hide-if').each(function(index) {
                var element = $(this);
                var parent_tag = element.parent().prop('nodeName').toLowerCase()

                // Field.
                if ('td' === parent_tag || 'label' === parent_tag) {
                    element.closest('tr').show();

                    wpsfsvi.maybe_hide_element(element, function() {
                        element.closest('tr').hide();
                    });
                }

                // Tab.
                if ('li' === parent_tag) {
                    element.closest('li').show();

                    wpsfsvi.maybe_hide_element(element, function() {
                        element.closest('li').hide();
                    });
                }

                // Section.
                if ('div' === parent_tag) {
                    element.prev().show();
                    element.next().show();
                    if (element.next().hasClass('wpsfsvi-section-description')) {
                        element.next().next().show();
                    }

                    wpsfsvi.maybe_hide_element(element, function() {
                        element.prev().hide();
                        element.next().hide();
                        if (element.next().hasClass('wpsfsvi-section-description')) {
                            element.next().next().hide();
                        }
                    });
                }
            });
        },

        /**
         * Maybe Show Element.
         * 
         * @param {object} element Element.
         * @param {function} callback Callback.
         */
        maybe_show_element: function(element, callback) {
            var classes = element.attr('class').split(/\s+/);
            var controllers = classes.filter(function(item) {
                return item.includes('show-if--');
            });

            Array.from(controllers).forEach(function(control_group) {
                var item = control_group.replace('show-if--', '');
                if (item.includes('&&')) {
                    var and_group = item.split('&&');
                    var show_item = true;
                    Array.from(and_group).forEach(function(and_item) {
                        if (!wpsfsvi.get_show_item_bool(show_item, and_item)) {
                            show_item = false;
                        }
                    });

                    if (show_item) {
                        callback();
                        return;
                    }
                } else {
                    var show_item = true;
                    show_item = wpsfsvi.get_show_item_bool(show_item, item);

                    if (show_item) {
                        callback();
                        return;
                    }
                }
            });
        },

        /**
         * Maybe Hide Element.
         * 
         * @param {object} element Element.
         * @param {function} callback Callback.
         */
        maybe_hide_element: function(element, callback) {
            var classes = element.attr('class').split(/\s+/);
            var controllers = classes.filter(function(item) {
                return item.includes('hide-if--');
            });

            Array.from(controllers).forEach(function(control_group) {
                var item = control_group.replace('hide-if--', '');
                if (item.includes('&&')) {
                    var and_group = item.split('&&');
                    var hide_item = true;
                    Array.from(and_group).forEach(function(and_item) {
                        if (!wpsfsvi.get_show_item_bool(hide_item, and_item)) {
                            hide_item = false;
                        }
                    });

                    if (hide_item) {
                        callback();
                        return;
                    }
                } else {
                    var hide_item = true;
                    hide_item = wpsfsvi.get_show_item_bool(hide_item, item);

                    if (hide_item) {
                        callback();
                        return;
                    }
                }
            });
        },

        /**
         * Get Show Item Bool.
         * 
         * @param {bool} show Boolean.
         * @param {object} item Element.
         * @returns {bool}
         */
        get_show_item_bool: function(show = true, item) {
            var split = item.split('===');
            var control = split[0];
            var values = split[1].split('||');
            var control_value = wpsfsvi.get_controller_value(control);

            if (!values.includes(control_value)) {
                show = !show;
            }

            return show;
        },

        /** 
         * Return the control value.
         */
        get_controller_value: function(id) {
            var original_control = $('#' + id);
            var control = $('#' + id);

            if ('checkbox' === control.attr('type') || 'radio' === control.attr('type')) {
                control = $('#' + id + ':checked');
            }

            var value = control.val();

            if ('checkbox' === original_control.attr('type') && typeof value === 'undefined') {
                value = '0';
            }

            if (typeof value === 'undefined') {
                value = '';
            }

            return value.toString();
        },

        /**
         * Import related functions.
         */
        importer: {
            init: function() {

                $('.wpsfsvi-import__button').click(function() {
                    $(this).parent().find('.wpsfsvi-import__file_field').trigger('click');
                });

                $(".wpsfsvi-import__file_field").change(function(e) {
                    $this = $(this);
                    $td = $this.closest('td');

                    var file_field = $this.get(0),
                        settings = "",
                        wpsfsvi_import_nonce = $td.find('.wpsfsvi_import_nonce').val();
                    wpsfsvi_import_option_group = $td.find('.wpsfsvi_import_option_group').val();


                    if ('undefined' === typeof file_field.files[0]) {
                        alert(wpsfsvi_vars.select_file);
                        return;
                    }

                    if (!confirm('Are you sure you want to overrid existing setting?')) {
                        return;
                    }

                    wpsfsvi.importer.read_file_text(file_field.files[0], function(content) {
                        try {
                            JSON.parse(content);
                            settings = content;
                        } catch {
                            settings = false;
                            alert(wpsfsvi_vars.invalid_file);
                        }

                        if (!settings) {
                            return;
                        }

                        $td.find('.spinner').addClass('is-active');
                        // Run an ajax call to save settings.
                        $.ajax({
                            url: 'admin-ajax.php',
                            type: 'POST',
                            data: {
                                action: 'wpsfsvi_import_settings',
                                settings: settings,
                                option_group: wpsfsvi_import_option_group,
                                _wpnonce: wpsfsvi_import_nonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                } else {
                                    alert(wpsfsvi_vars.something_went_wrong);
                                }

                                $td.find('.spinner').removeClass('is-active');
                            }
                        });
                    });
                });
            },

            /**
             * Read File text.
             *
             * @param string   File input. 
             * @param finction Callback function. 
             */
            read_file_text(file, callback) {
                const reader = new FileReader();
                reader.readAsText(file);
                reader.onload = () => {
                    callback(reader.result);
                };
            }
        }
    };

    $(document).ready(wpsfsvi.on_ready());

}(jQuery, document));