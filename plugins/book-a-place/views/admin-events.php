<div class="wrap">

<?php screen_icon('options-general'); ?>
<h2><?php echo esc_html(get_admin_page_title()); ?></h2>

<div id='calendar'></div>

<script>

(function ($) {
    "use strict";
    $(function () {

        var calendarSelectedStart = null;
        var calendarSelectedEnd = null;
        var calendarSelectedAllDay = null;
        var calendarSelectedEvent = null;


        // fullCalendar event handlers

        var selectFullCalendarHandler = function (start, end, allDay) {

            calendarSelectedStart = start;
            calendarSelectedEnd = end;
            calendarSelectedAllDay = allDay;

            var eventDialog = $("#event-dialog-form");

            clearEventDialogFields();

            eventDialog.dialog({
                title: '<?php _e("Add an event", $this->plugin_slug); ?>',
                buttons: {
                    '<?php _e("Add", $this->plugin_slug); ?>': dialogAddButtonHandler,
                    '<?php _e("Cancel", $this->plugin_slug); ?>': dialogCancelButtonHandler
                }
            });

            updateSchemesList();
            eventDialog.dialog("open");

            //calendar.fullCalendar('unselect');
        };

        var eventDropFullCalendarHandler = function (event, dayDelta, minuteDelta, allDay, revertFunc) {
            console.log('event move');

            var data = {
                action: 'move_event',
                event_id: event.id,
                day_delta: dayDelta,
                minute_delta: minuteDelta,
                all_day: allDay ? 1 : 0
            };

            $.post(ajaxurl, data, function (response) {
                if (response.error) {
                    console.log('error');
                } else {
                    console.log('ok');
                }
            }, "json");
        };

        var eventResizeFullCalendarHandler = function (event, dayDelta, minuteDelta, revertFunc) {
            console.log('event resize');

            var data = {
                action: 'resize_event',
                event_id: event.id,
                day_delta: dayDelta,
                minute_delta: minuteDelta
            };

            $.post(ajaxurl, data, function (response) {
                if (response.error) {
                    console.log('error');
                } else {
                    console.log('ok');
                }
            }, "json");
        };

        var eventClickFullCalendarHandler = function (calEvent, jsEvent, view) {

            calendarSelectedEvent = calEvent;

            var eventDialog = $("#event-dialog-form"),
                data = {
                    action: 'get_event',
                    event_id: calEvent.id
                };

            eventDialog.dialog({
                title: '<?php _e("Edit an event", $this->plugin_slug); ?>',
                buttons: [
                    { text: "<?php _e("Delete", $this->plugin_slug); ?>", click: dialogDeleteButtonHandler, icons: { primary: "ui-icon-trash" } },
                    { text: "<?php _e("Edit", $this->plugin_slug); ?>", click: dialogEditButtonHandler },
                    { text: "<?php _e("Cancel", $this->plugin_slug); ?>", click: dialogCancelButtonHandler }
                ]
            });


            $.post(ajaxurl, data, function (response) {
                if (response.error) {
                    return false;
                } else {
                    updateSchemesList(response.event.scheme_id);
                    setEventDialogFields(response.event);
                    eventDialog.dialog("open");
                }
            }, "json");

        };


        // fullCalendar

        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            select: selectFullCalendarHandler,
            eventResize: eventResizeFullCalendarHandler,
            eventDrop: eventDropFullCalendarHandler,
            eventClick: eventClickFullCalendarHandler,
            editable: true,
            events: {
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_events_json',
                    timezone_offset: (new Date()).getTimezoneOffset() / 60 // hours after/before UTC
                }
            }
        });


        // event dialog handlers

        var dialogCancelButtonHandler = function () {
            $(this).dialog("close");
            clearEventDialogFields();
        };

        var dialogAddButtonHandler = function () {

            if (!validateEventDialogFields()) {
                return false;
            }

            var dialog = this,
                data = {
                    action: 'add_event',
                    name: $('#event-name').val(),
                    description: $('#event-description').val(),
                    url: $('#event-url').val(),
                    scheme_id: $('#event-scheme').val(),
                    start: calendarSelectedStart.getTime() / 1000, // seconds
                    end: calendarSelectedEnd.getTime() / 1000, // seconds
                    timezone_offset: calendarSelectedStart.getTimezoneOffset() / 60, // hours after/before UTC
                    all_day: calendarSelectedAllDay ? 1 : 0,
                    hours: $('#event-hours').val(),
                    background_color: $('#event-color-background').val(),
                    border_color: $('#event-color-border').val(),
                    text_color: $('#event-color-text').val()
                };

            $.post(ajaxurl, data, function (response) {

                if (response.error) {
                    console.log('error');
                } else {
                    calendar.fullCalendar('renderEvent',
                        {
                            title: response.event.name,
                            id: response.event.id,
                            //url: response.event.url,
                            start: calendarSelectedStart,
                            end: calendarSelectedEnd,
                            allDay: calendarSelectedAllDay,
                            backgroundColor: response.event.background_color,
                            borderColor: response.event.border_color,
                            textColor: response.event.text_color
                        },
                        true // make the event "stick"
                    );
                }

                $(dialog).dialog("close");
                clearEventDialogFields();

            }, "json");
        };

        var dialogEditButtonHandler = function () {

            if (!validateEventDialogFields()) {
                return false;
            }

            var dialog = this,
                data = {
                    action: 'edit_event',
                    event_id: calendarSelectedEvent.id,
                    name: $('#event-name').val(),
                    description: $('#event-description').val(),
                    url: $('#event-url').val(),
                    scheme_id: $('#event-scheme').val(),
                    hours: $('#event-hours').val(),
                    background_color: $('#event-color-background').val(),
                    border_color: $('#event-color-border').val(),
                    text_color: $('#event-color-text').val()
                };

            $.post(ajaxurl, data, function (response) {

                if (response.error) {
                    console.log('error');
                } else {
                    calendarSelectedEvent.title = response.event.name;
                    calendarSelectedEvent.backgroundColor = response.event.background_color;
                    calendarSelectedEvent.borderColor = response.event.border_color;
                    calendarSelectedEvent.textColor = response.event.text_color;
                    calendar.fullCalendar('updateEvent', calendarSelectedEvent);
                }

                $(dialog).dialog("close");
                clearEventDialogFields();

            }, "json");
        };

        var dialogDeleteButtonHandler = function () {

            if (!confirm('<?php _e("Are you sure you want to delete this event?", $this->plugin_slug); ?>')) {
                return false;
            }

            var dialog = this,
                data = {
                    action: 'delete_event',
                    event_id: calendarSelectedEvent.id
                };

            $.post(ajaxurl, data, function (response) {

                if (response.error) {
                    console.log('error');
                } else {
                    calendar.fullCalendar('removeEvents', calendarSelectedEvent.id);
                }

                $(dialog).dialog("close");
                clearEventDialogFields();

            }, "json");
        };


        // event dialog init
        $("#event-dialog-form").dialog({
            width: 710,
            height: 730,
            draggable: false,
            autoOpen: false,
            modal: true
        });


        // wpColorPicker init
        $('.event-color-field').wpColorPicker();
        // to close all wpColorPickers before open new that was clicked
        $('.wp-color-result').on('mouseup', function () {
            if (!$(this).hasClass('wp-picker-open')) {
                $('body').click();
            }
        });


        // functions

        function clearEventDialogFields() {
            $('#event-name')
                .val('')
                .removeClass('ui-state-error');
            $('#event-description')
                .val('')
                .removeClass('ui-state-error');
            $('#event-url')
                .val('')
                .removeClass('ui-state-error');
            $('#event-scheme')
                .val('0')
                .removeClass('ui-state-error');
            $('#event-hours')
                .val('')
                .removeClass('ui-state-error');
            $('.event-color-field').each(function () {
                var self = $(this);
                self.wpColorPicker('color', self.data('default-color'));
                //self.iris('hide');
            });
            $('body').click(); // to close all wpColorPickers
            $('#event-shortcode').find('code').html($('#event-shortcode').find('code').data('default-text'));

        }

        function updateSchemesList(scheme_id) {
            var data = {
                action: 'get_schemes_list',
                scheme_id: scheme_id
            };

            $.post(ajaxurl, data, function (response) {

                if (response.error) {
                    console.log('error');
                } else {
                    $('#event-scheme').html(response);
                    if (scheme_id) {
                        $('#event-scheme').val(scheme_id);
                    }
                }
            });
        }

        function setEventDialogFields(event) {
            $('#event-name').val(event.name);
            $('#event-description').val(event.description);
            $('#event-url').val(event.url);
            $('#event-scheme').val(event.scheme_id);
            $('#event-hours').val(event.hours);
            $('#event-color-background').wpColorPicker('color', event.background_color);
            $('#event-color-border').wpColorPicker('color', event.border_color);
            $('#event-color-text').wpColorPicker('color', event.text_color);
            $('#event-shortcode').find('code').html(event.shortcode);
        }

        function validateEventDialogFields() {

            var eventName = $('#event-name'),
                eventSchemeId = $('#event-scheme'),
                error = false;

            if (eventName.val() == '') {
                eventName.addClass('ui-state-error');
                error = true;
            } else {
                eventName.removeClass('ui-state-error');
            }
            if (eventSchemeId.val() == '0') {
                eventSchemeId.addClass('ui-state-error');
                error = true;
            } else {
                eventSchemeId.removeClass('ui-state-error');
            }

            if (error) {
                return false;
            } else {
                return true;
            }
        }


    });
}(jQuery));

</script>

</div>

<div id="event-dialog-form" title="<?php _e("Add an event", $this->plugin_slug); ?>">
    <p class="validateTips"><?php printf(__('Fields with %s are required.', $this->plugin_slug), '<span class="required">*</span>'); ?></p>

    <form>
        <fieldset class="event-info">
            <legend><?php _e("Event Info", $this->plugin_slug); ?></legend>
            <div class="row">
                <label for="event-name"><?php _e("Name", $this->plugin_slug); ?> <span class="required">*</span></label>
                <input type="text" name="event-name" id="event-name" class="text"/>
            </div>
            <div class="row">
                <label for="event-scheme"><?php _e("Scheme", $this->plugin_slug); ?> <span class="required">*</span></label>
                <select name="event-scheme" id="event-scheme">
                    <?php echo $this->get_schemes_list(); ?>
                </select>

                <p class="description"><?php _e("If you do not see the desired scheme in the list, perhaps it is already assigned to another event.", $this->plugin_slug); ?></p>
            </div>
            <div class="row">
                <label for="event-description"><?php _e("Description", $this->plugin_slug); ?></label>
                <textarea name="event-description" id="event-description" class="text"></textarea>
            </div>
            <div class="row">
                <label for="event-url"><?php _e("Url", $this->plugin_slug); ?></label>
                <input type="text" name="event-url" id="event-url" class="text"/>
            </div>
            <div class="row">
                <label for="event-hours"><?php _e("Hours", $this->plugin_slug); ?></label>
                <input type="text" name="event-hours" id="event-hours" class="text"/>

                <p class="description"><?php _e("Number of hours to close booking before event start.", $this->plugin_slug); ?></p>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php _e("Colors", $this->plugin_slug); ?></legend>
            <div class="row event-color">
                <label for="event-color-background"><?php _e("Background Color", $this->plugin_slug); ?></label>
                <input id="event-color-background" type="text" value="#3A87AD" class="event-color-field" data-default-color="#3A87AD"/>
            </div>
            <div class="row event-color">
                <label for="event-color-border"><?php _e("Border Color", $this->plugin_slug); ?></label>
                <input id="event-color-border" type="text" value="#3A87AD" class="event-color-field" data-default-color="#3A87AD"/>
            </div>
            <div class="row event-color">
                <label for="event-color-text"><?php _e("Text Color", $this->plugin_slug); ?></label>
                <input id="event-color-text" type="text" value="#FFFFFF" class="event-color-field" data-default-color="#FFFFFF"/>
            </div>
        </fieldset>
        <div class="clear"></div>
        <fieldset class="event-shortcode">
            <legend><?php _e("Shortcode", $this->plugin_slug); ?></legend>
            <div id="event-shortcode">
                <code data-default-text="<?php _e("Here will be shortcode for this event.", $this->plugin_slug); ?>"><?php _e("Here will be shortcode for this event.", $this->plugin_slug); ?></code>
            </div>
        </fieldset>
    </form>
</div>