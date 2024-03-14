/**
 * Created by Michael on 18/06/2015.
 */
/**
 * Returns the image file url for any Image Select setting
 *
 * Displays the media uploader for selecting an image.
 * http://code.tutsplus.com/tutorials/getting-started-with-the-wordpress-media-uploader--cms-22011
 *
 */

jQuery(document).ready(function($) {
    'use strict';

    /**
     * Define Variables
     */
    //Current Icon Id
    var icon_value_id;
    var icon_overlay_selector;
    var icon_select_list_selector;

    //Overlay and select list ids
    var dashicon_overlay_selector = '#my_plugin_dashicon-select_overlay';
    var dashicon_select_list_selector = '#my_plugin_dashicon-select_list';

    var glyphicon_overlay_selector = '#my_plugin_glyphicon-select_overlay';
    var glyphicon_select_list_selector = '#my_plugin_glyphicon-select_list';

    //Button, value and display classes
    var icon_clear_selector = '.my_plugin_dashicon-select_clear, .my_plugin_glyphicon-select_clear';
    var icon_button_selector = '.my_plugin_dashicon-select_button.button, .my_plugin_glyphicon-select_button.button';
    var icon_selector = '.dashicons, .glyphicon';


    /**
     * jQuery Event handlers
     */

    /**
     * Trash icon to remove the value and image
     */
    $(icon_clear_selector).click(function(){
        var button = $(this);
        var icon_value_id = button.attr('id').replace('_clear', '');
        setIconValue(icon_value_id, '');
    });


    /**
     * On Button Select click
     */
    $(icon_button_selector).click(function() {

        //Get the unique settings id
        var button = $(this);
        icon_value_id = button.attr('id').replace('_button', '');

        //Get the overlay and selector
        if (button.hasClass('my_plugin_glyphicon-select_button')) {
            icon_overlay_selector = glyphicon_overlay_selector;
            icon_select_list_selector = glyphicon_select_list_selector;
        } else if (button.hasClass('my_plugin_dashicon-select_button')) {
            icon_overlay_selector = dashicon_overlay_selector;
            icon_select_list_selector = dashicon_select_list_selector;
        }

        // Display the Glyphicon/Dashicon selector
        show_icon_selector();

        /**
         * On click of an Icon in the selector - set the value, else just close it
         * Run this within the on button click function as overaly/select list variables have not yet been set.
         */
        $(icon_overlay_selector).click(function () {
            hide_icon_selector();
        }).children(icon_select_list_selector).children(icon_selector).not('.my_plugin_close_me').click(function () {
            var selected_icon = $(this).attr('class');
            setIconValue(icon_value_id, selected_icon);
        });

    });


    /**
     * Shows the Icon selector
     */
    function show_icon_selector() {
        var overlay = $(icon_overlay_selector);
        overlay.visibilityVisible();
    }

    /**
     * Hides the Icon selector
     */
    function hide_icon_selector() {
        var overlay = $(icon_overlay_selector);
        overlay.visibilityHidden();
    }


    /**
     * Function to Set Icon values
     */
    function setIconValue(icon_value_id, icon_value) {

        var icon_preview_id = icon_value_id + '_preview';

        //Set the dashicon value
        $("#" + icon_value_id).val(icon_value);

        //Set whether the dashicon is visible
        var display_class = (icon_value.length > 0) ? '' : 'my_plugin_hidden';

        //Removes the existing dashicon, removes the hidden class, adds the display class and the dashicon (if set)
        $('#' + icon_preview_id).removeClass(function (index, classes) {
            return (classes.match (/(^|\s)dashicon\S+/gmi) || []).join(' ');
        }).removeClass(function (index, classes) {
            return (classes.match (/(^|\s)glyphicon\S+/gmi) || []).join(' ');
        }).removeClass('my_plugin_hidden')
            .addClass(display_class + ' ' + icon_value)
            .prop('title', icon_value)
            .change(); //triggers change event to manage user leaving before saving etc.

        $('html, body').animate({
            scrollTop: $('#' + icon_preview_id).offset().top - 400
        }, 500);

    }

});


