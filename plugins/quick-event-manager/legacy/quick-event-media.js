var qem_allowed = {
    'frequency': {
        'Every': {
            'target': ['Day', 'Week', 'Month', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'for': ['Days', 'Weeks', 'Months', 'Years']
        },
        'First': {
            'target': ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'for': ['Months', 'Years']
        },
        'Second': {
            'target': ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'for': ['Months', 'Years']
        },
        'Third': {
            'target': ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'for': ['Months', 'Years']
        },
        'Fourth': {
            'target': ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'for': ['Months', 'Years']
        }
    },
    'target': {
        'Day': {
            'frequency': ['Every'],
            'for': ['Days', 'Weeks', 'Months', 'Years']
        },
        'Month': {
            'frequency': ['Every'],
            'for': ['Months', 'Years']
        },
        'Week': {
            'frequency': ['Every'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Monday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Tuesday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Wednesday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Thursday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Friday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Saturday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        },
        'Sunday': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'for': ['Weeks', 'Months', 'Years']
        }
    },
    'for': {
        'Days': {
            'frequency': ['Every'],
            'target': ['Day']
        },
        'Weeks': {
            'frequency': ['Every'],
            'target': ['Day', 'Week', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        },
        'Months': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'target': ['Day', 'Week', 'Month', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        },
        'Years': {
            'frequency': ['Every', 'First', 'Second', 'Third', 'Fourth'],
            'target': ['Day', 'Week', 'Month', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        }
    }
};

jQuery(document).ready(function () {

    datePickerOptions = {
        closeText: "Done",
        prevText: "Prev",
        nextText: "Next",
        currentText: "Today",
        monthNames: ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"],
        monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        weekHeader: "Wk",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "",
        dateFormat: 'dd M yy'
    };

    jQuery('#qemdate').datepicker(datePickerOptions);
    jQuery('#qemenddate').datepicker(datePickerOptions);
    jQuery('#qemcutoffdate').datepicker(datePickerOptions);
    jQuery('#qem_reg_start_date').datepicker(datePickerOptions);

    var custom_uploader, img;
    jQuery('.qem-color').wpColorPicker();
    jQuery('#upload_media_button').click(function (e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select Background Image', button: {text: 'Insert Image'}, multiple: false
        });
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('#upload_image').val(attachment.url);
        });
        custom_uploader.open();
    });
    jQuery('#upload_submit_button').click(function (e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select Submit Button Image', button: {text: 'Insert Image'}, multiple: false
        });
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('#submit_image').val(attachment.url);
        });
        custom_uploader.open();
    });
    jQuery('#upload_event_image').click(function (e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select Event Image', button: {text: 'Insert Image'}, multiple: false
        });
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('#event_image').val(attachment.url);
            jQuery('#event_image').change();
        });
        custom_uploader.open();
    });
    jQuery("#yourplaces").keyup(function () {
        var model = document.getElementById('yourplaces');
        var number = jQuery('#yourplaces').val()
        if (number == 1)
            jQuery("#morenames").hide();
        else {
            jQuery("#morenames").show();
        }
    });
    jQuery('#remove_event_image').click(function () {
        jQuery('#event_image').val('');
        jQuery('#event_image').change();
    });

    jQuery('#event_image').change(function () {

        /*
            Preload Image Before Displaying
        */
        img = jQuery('<img />');

        /*
            If load is complete, display the image
        */
        img.load(function () {
            if (this.src && this.complete) {
                jQuery('.qem-image').attr('src', this.src);
            }
        });

        /*
            If error occurs (usually due to invalid url) show failure image
        */
        img.error(function () {
            jQuery('.qem-image').attr('src', jQuery('.qem-image').attr('alt'));
        });

        /*
            Start the download of the image
        */
        if (jQuery('#event_image').val() != '') {
            img.attr('src', jQuery('#event_image').val());
        } else {
            jQuery('.qem-image').attr('src', jQuery('.qem-image').attr('rel'));
        }

    });

    jQuery('input[name=event_repeat]').change(function () {
        var rep = jQuery(this).closest('.inside').find('#repeat');
        if (jQuery(this).is(':checked')) rep.show();
        else rep.hide();
    });

    jQuery('input[name=event_repeat]').change();

    // Add in functionality for select box changes (Event_Repeat)
    jQuery('select[name=thenumber]').change(qem_handle_change);
    jQuery('select[name=theday]').change(qem_handle_change);
    jQuery('select[name=thewmy]').change(qem_handle_change);

    // Force a change event
    jQuery('select[name=thenumber]').change();

});

function qem_isAllowed(v1, a1, v2, a2) {
    if ((jQuery.inArray(v1, a2) !== -1) && (jQuery.inArray(v2, a1) !== -1)) return true;
    return false;
}

function qem_handle_change() {
    // Get the current select
    var current = ((this.name == 'thewmy') ? 'for' : ((this.name == 'theday') ? 'target' : 'frequency'));

    // Get all of the selects
    var selects = {
        'for': jQuery('select[name=thewmy]'),
        'target': jQuery('select[name=theday]'),
        'frequency': jQuery('select[name=thenumber]')
    };

    // Get the current values before swapping
    var val = {
        'for': selects.for.val() || false,
        'target': selects.target.val() || false,
        'frequency': selects.frequency.val() || false
    };
    // Determine if the values will be in the new values and if the current element's value is appropriate
    var sel = {'for': '', 'target': '', 'frequency': ''};

    var b;
    for (b in selects) { // Loop through the selects
        if (b == current) continue; // if select is current element skip it

        // Check if the currently selected value is OK with the other selections
        if (val[b] && qem_isAllowed(val[b], qem_allowed[b][val[b]][current], this.value, qem_allowed[current][this.value][b])) sel[b] = ' selected="selected"';

        // Empty the current select box
        selects[b].empty();
    }

    var i, x;
    // Loop through the changing selects
    for (i in qem_allowed[current][this.value]) {
        // Loop through the current select's new values
        for (x in qem_allowed[current][this.value][i]) {
            // Append the new value to the select object
            selects[i].append(jQuery("<option" + ((qem_allowed[current][this.value][i][x] == val[i]) ? sel[i] : '') + ">" + qem_allowed[current][this.value][i][x] + "</option>"));
        }
    }
}
