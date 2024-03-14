(function () {
    window.init_wpfront_notifiction_bar_options = function (data, settings, is_pro) {
        var $ = jQuery;
        var app = Vue.createApp({
            data() {
                return data;
            },
            mounted() {
                var $div = $('div.wrap.notification-bar-add-edit');
                $(function () {
                    $div.find('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                    postboxes.add_postbox_toggles('<?php echo $this->controller->get_menu_slug(); ?>');
                });

                this.minutestohours();
                if (is_pro) {
                    this.tinymce();
                }
            },
            methods: {
                tinymceinit() {
                    if (typeof tinymce !== 'undefined') {
                        tinymce.init({
                            body_id: "wpfront-notification-bar-editor",
                            body_class: "wpfront-notification-bar-editor wpfront-message",
                            selector: 'textarea#notification-bar-message-text',
                            height: '400',
                            plugins: 'code, lists, link, fullscreen, image, media, quickbars, hr',
                            quickbars_selection_toolbar: 'bold italic underline | formatselect | blockquote quicklink',
                            menubar: false,
                            media_live_embeds: true,
                            content_css: settings.content_css,
                            content_style: "body{ text-align: center; padding: 0; margin: 0; } p{ margin: 0; }",
                            toolbar1: 'undo redo | styleselect | fontselect fontsizeselect forecolor backcolor | numlist bullist | bold italic strikethrough blockquote underline lineheight hr |  code',
                            toolbar2: 'alignleft aligncenter alignright | link unlink | outdent indent | uploadmedia image media',
                            fontsize_formats: settings.fontsize_formats,
                            font_formats: settings.font_formats,
                            setup: function (editor) {
                                var frame;
                                editor.ui.registry.addButton('uploadmedia', {
                                    icon: 'upload',
                                    tooltip: settings.tinymce_tooltip,
                                    onAction: function () {
                                        if (frame) {
                                            frame.open();
                                            return;
                                        }
        
                                        frame = wp.media({
                                            title: settings.tinymce_title,
                                            button: {
                                                text: settings.tinymce_button_text,
                                            },
                                            multiple: false
                                        });
        
        
                                        frame.on('select', function () {
        
                                            var attachment = frame.state().get('selection').first().toJSON();
                                            var media_file;
        
                                            if (attachment.type == 'image') {
                                                media_file = '<img width="' + attachment.width + '" height="' + attachment.height + '"' + 'src="' + attachment.url + '">';
                                            } else if (attachment.type == 'video') {
                                                media_file = '<video width="320" height="240"  controls>' + '<source src="' + attachment.url + '" type="video/mp4">' + '</video>';
                                            } else {
                                                media_file = '<a href="' + attachment.url + '">' + attachment.name + '</a>';
                                            }
                                            editor.execCommand('mceInsertContent', false, media_file);
                                        });
        
                                        frame.open();
                                    }
                                });
                            }
        
                        });
                    } else {
                        var self = this;
                        setTimeout(function(){ self.tinymceinit() }, 100);
                    }
                },
                tinymce(){
                    if (data.editor == "visual") {
                        this.tinymceinit();
                    } else {
                        if (typeof tinymce !== 'undefined') {
                            tinymce.remove("textarea#notification-bar-message-text");
                        }
                    }
                },
                mediaLibrary() {
                    var mediaLibrary = null;
                    if (mediaLibrary === null) {
                        mediaLibrary = wp.media.frames.file_frame = wp.media({
                            title: settings.choose_image,
                            multiple: false,
                            button: {
                                text: settings.select_image
                            }
                        }).on('select', function () {
                            var obj = mediaLibrary.state().get('selection').first().toJSON();

                            $('#reopen-button-image-url').val(obj.url);
                        });
                    }

                    mediaLibrary.open();
                    return false;
                },

                minutestohours() {
                    var duration = parseInt($('input[name="wpfront-notification-bar-options[schedule_duration]"]').val());
                    if (duration > 60) {
                        var hours = (duration / 60);
                        var comp_hours = Math.floor(hours);
                        var minutes = (hours - comp_hours) * 60;
                        var comp_minutes = Math.round(minutes);
                        var hours_minutes;
                        if (comp_minutes > 0) {
                            hours_minutes = settings.x_hours_minutes.replace("%1$d", comp_hours).replace("%2$d", comp_minutes);
                        } else {
                            hours_minutes = settings.x_hours.replace("%1$d", comp_hours);
                        }
                        $('#schedule_duration').replaceWith('<span id="schedule_duration" class="description">= ' + hours_minutes + '</span>');
                    } else {
                        $('#schedule_duration').replaceWith('<span id="schedule_duration" class="description"></span>');
                    }
                },        

                schedules() {
                    var cronminutes = [];
                    var cronhours = [];
                    var cronmday = [];
                    var cronmon = [];
                    var cronwday = [];
                    var schedule_date = $('input[name="wpfront-notification-bar-options[schedule_start_date]"]').val();
                    var schedule_time = $('input[name="wpfront-notification-bar-options[schedule_start_time]"]').val();
                    var schedule_end_date = $('input[name="wpfront-notification-bar-options[schedule_end_date]"]').val();
                    var schedule_end_time = $('input[name="wpfront-notification-bar-options[schedule_end_time]"]').val();
                    var schedule_duration = $('input[name="wpfront-notification-bar-options[schedule_duration]"]').val();

                    if ('day' == $('input[name="wpfront-notification-bar-options[schedule]"]:checked').val()) {
                        $("input:checkbox[name='wpfront-day-minutes[]']:checked").each(function () {
                            cronminutes.push($(this).val());
                        });
                        $("input:checkbox[name='wpfront-day-hour[]']:checked").each(function () {
                            cronhours.push($(this).val());
                        });
                        cronmday.push('*');
                        cronmon.push('*');
                        cronwday.push('*');
                    }

                    if ('week' == $('input[name="wpfront-notification-bar-options[schedule]"]:checked').val()) {
                        $("input:checkbox[name='wpfront-day-minutes[]']:checked").each(function () {
                            cronminutes.push($(this).val());
                        });
                        $("input:checkbox[name='wpfront-day-hour[]']:checked").each(function () {
                            cronhours.push($(this).val());
                        });
                        cronmday.push('*');
                        cronmon.push('*');
                        $("input:checkbox[name='wpfront-week-days[]']:checked").each(function () {
                            cronwday.push($(this).val());
                        });
                    }

                    if ('mon' == $('input[name="wpfront-notification-bar-options[schedule]"]:checked').val()) {
                        $("input:checkbox[name='wpfront-day-minutes[]']:checked").each(function () {
                            cronminutes.push($(this).val());
                        });
                        $("input:checkbox[name='wpfront-day-hour[]']:checked").each(function () {
                            cronhours.push($(this).val());
                        });
                        $("input:checkbox[name='wpfront-month-days[]']:checked").each(function () {
                            cronmday.push($(this).val());
                        });
                        cronmon.push('*');
                        cronwday.push('*');
                    }

                    var data = {
                        action: 'notification_bar_cron_text',
                        cronminutes: cronminutes,
                        cronhours: cronhours,
                        cronmday: cronmday,
                        cronmon: cronmon,
                        cronwday: cronwday,
                        schedulestartdate: schedule_date,
                        schedulestarttime: schedule_time,
                        scheduleduration: schedule_duration,
                        scheduleenddate: schedule_end_date,
                        scheduleendtime: schedule_end_time
                    };

                    var loading = $("a.thickbox.more_schedules").attr("data-action");

                    $("#div_more_schedules_inside").text(loading);
                    $.post(ajaxurl, data, function (response) {
                        $('#notification-bar-schedule').replaceWith(response.data[0]);
                        $("#div_more_schedules_inside").replaceWith(response.data[1]);
                    });

                }
            },
        });

        app.component('HelpIcon', {
            props: ['helpText'],
            template: '<el-tooltip :content="helpText" raw-content="true" placement="right"><i class="tooltip fa fa-question-circle-o help-icon"></i></el-tooltip>'
        });

        app.component('ColorPicker', {
            props: ['modelValue', 'name'],
            template: $('#color-picker').html(),
        });

        app.component('PostsFilterSelection', {
            props: ['modelValue', 'name'],
            template: $('#posts-filter-selection').html(),
            computed: {
                selectedPosts: {
                    get() {
                        return this.modelValue.split(',');
                    },
                    set(values) {
                        this.$emit('update:modelValue', values.filter(e => e).join().trim());
                    }
                }
            }
        });

        app.component('DisplayRolesSettings', {
            props: ['modelValue', 'name'],
            template: $('#display-roles-settings').html(),
            computed: {
                selectedRoles: {
                    get() {
                        return this.modelValue;
                    },
                    set(values) {
                        this.$emit('update:modelValue', values);
                    }
                }
            }
        });

        app.component('DatePicker', {
            props: ['modelValue', 'name'],
            data: function () {
                return { data: this.modelValue }
            },
            template: $('#date-picker').html(),
        });

        app.component('TimePicker', {
            props: ['modelValue', 'name'],
            data: function () {
                return { data: this.modelValue }
            },
            template: $('#time-picker').html(),
        });

        app.component('ScheduleSelectionSettings', {
            props: ['modelValue'],
            template: $('#schedule-selection-settings').html(),
            data() {
                data.scheduleType = this.scheduledata();
                return data;
            },
            methods: {
                schedules() {
                    this.$parent.schedules();
                },
                scheduledata() {
                    $time_units = data.schedule.split(' ');
                    $minutes = $time_units[0].split(',');
                    $hours = $time_units[1].split(',');
                    $month_days = $time_units[2].split(',');
                    $month = $time_units[3].split(',');
                    $week_days = $time_units[4].split(',');

                    if (!isNaN($month_days[0]) || ($month_days[0] == "L")) {
                        $schedule_type = "mon";
                    }

                    if (!isNaN($week_days[0])) {
                        $schedule_type = "week";
                    }

                    if ($month_days[0] + $week_days[0] == '**') {
                        $schedule_type = 'day';
                    }

                    return $schedule_type;
                }
            },
            computed: {
                selectedMonthDays: {
                    get() {
                        $time_units = this.modelValue.split(' ');
                        $month_days = $time_units[2].split(',');
                        return $month_days;
                    },
                    set(values) {
                        this.$emit('input', values);
                    }
                },

                selectedWeekDays: {
                    get() {
                        $time_units = this.modelValue.split(' ');
                        $week_days = $time_units[4].split(',');
                        return $week_days;
                    },
                    set(values) {
                        this.$emit('input', values);
                    }
                },

                selectedHours: {
                    get() {
                        $time_units = this.modelValue.split(' ');
                        $hours = $time_units[1].split(',');
                        return $hours;
                    },
                    set(values) {
                        this.$emit('input', values);
                    }
                },

                selectedMinutes: {
                    get() {
                        $time_units = this.modelValue.split(' ');
                        $minutes = $time_units[0].split(',');
                        return $minutes;
                    },
                    set(values) {
                        this.$emit('input', values);
                    }
                }
            }
        });

        app.use(ElementPlus);
        app.mount('#notification-bar-add-edit');

    };
})();