// Select button group
// https://www.jqueryscript.net/form/Bootstrap-Plugin-To-Convert-Select-Boxes-Into-Button-Groups-select-togglebutton-js.html

(function($) {
    // Define the togglebutton plugin.
    $.fn.togglebutton = function(opts) {
        // Apply the users options if exists.
        var settings = $.extend( {}, $.fn.togglebutton.defaults, opts);

        // For each select element.
        this.each(function() {
            var self = $(this);
            var multiple = this.multiple;

            // Retrieve all options.
            var options = self.children('option');
            // Create an array of buttons with the value of select options.
            var buttons = options.map(function(index, opt) {
                var button = $("<button type='button' class='btn btn-default'></button>")
                .prop('value', opt.value)
                .text(opt.text);

                // Add an `active` class if the option has been selected.
                if (opt.selected)
                    button.addClass("active");

                // Return the button.
                return button[0];
            });

            // For each button, implement the click button removing and adding
            // `active` class to simulate the toggle effect. And also change the
            // select selected option.
            buttons.each(function(index, btn) {
                $(btn).click(function() {
                    // Retrieve all buttons siblings of the clicked one with an
                    // `active` class !
                    var activeBtn = $(btn).siblings(".active");
                    var total = [];

                    // Remove all selected property on options.
                    self.children("option:selected").prop("selected", false);

                    // Check if the clicked button has the class `active`.
                    // Add or remove it according to the check.
                    if ($(btn).hasClass("active"))  {
                        $(btn).removeClass("active");
                    }
                    else {
                        $(btn).addClass("active");
                        options.val(btn.value).prop("selected", true);
                        total.push(btn.value);
                    }
                   
                    // If the select allow multiple values, remove all active
                    // class to the other buttons (to keep only the last clicked
                    // button).
                    if (!multiple) {
                        activeBtn.removeClass("active");
                    }

                    // Push all active buttons value in an array.
                    activeBtn.each(function(index, btn) {
                        total.push(btn.value);
                    });

                    // Change selected options of the select.
                    self.val(total).change();
                });
            });

            // Group all the buttons in a `div` element.
            var btnGroup = $("<div class='ui-segment'>").append(buttons);
            // Include the buttons group after the select element.
            self.after(btnGroup);
            // Hide the display element.
            self.hide();
        });
    };

    // Set the defaults options of the plugin.
    $.fn.togglebutton.defaults = {
    };

}(jQuery));


(function( $ ) {

    //Initiate Color Picker
    $('.wp-color-picker-field').wpColorPicker();

    // Switches option sections
    $('.group').hide();
    var activetab = '';
    if (typeof(localStorage) != 'undefined' ) {
        activetab = localStorage.getItem("activetab");
    }
    //if url has section id as hash then set it as active or override the current local storage value
    if(window.location.hash){
        activetab = window.location.hash;
        if (typeof(localStorage) != 'undefined' ) {
            localStorage.setItem("activetab", activetab);
        }
    }
    if (activetab != '' && $(activetab).length ) {
        $(activetab).fadeIn();
    } else {
        $('.group:first').fadeIn();
    }
    $('.group .collapsed').each(function(){
        $(this).find('input:checked').parent().parent().parent().nextAll().each(
        function(){
            if ($(this).hasClass('last')) {
                $(this).removeClass('hidden');
                return false;
            }
            $(this).filter('.hidden').removeClass('hidden');
        });
    });
    if (activetab != '' && $(activetab + '-tab').length ) {
        $(activetab + '-tab').addClass('nav-tab-active');
    }
    else {
        $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
    }
    $('.nav-tab-wrapper a').click(function(evt) {
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active').blur();
        var clicked_group = $(this).attr('href');


        if (typeof(localStorage) != 'undefined' ) {
            localStorage.setItem("activetab", $(this).attr('href'));
        }
        $('.group').hide();
        $(clicked_group).fadeIn();
        evt.preventDefault();
    });

    $('.wpsa-browse').on('click', function (event) {
        event.preventDefault();

        var self = $(this);

        // Create the media frame.
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: self.data('uploader_title'),
            button: {
                text: self.data('uploader_button_text'),
            },
            multiple: false
        });

        file_frame.on('select', function () {
            attachment = file_frame.state().get('selection').first().toJSON();
            self.prev('.wpsa-url').val(attachment.url).change();
        });

        // Finally, open the modal
        file_frame.open();
    });

    $('.wpb-select-buttons').togglebutton();

})( jQuery );