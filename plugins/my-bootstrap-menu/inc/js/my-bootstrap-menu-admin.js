/**
 * Created by Michael on 21-Dec-15.
 */

jQuery(document).ready(function($) {

    //Set Dropdown Icon to be displayed if checked
    $('#submenu_headings_are_links').click(function(){
        var headingsAreLinks = $('#submenu_headings_are_links').is(":checked");
        if(headingsAreLinks) {
            $('#display_caret').attr('checked', true);
        }
    });

    //Turn off Submenu headings are linked if unchecked
    $('#display_caret').click(function(){
        var displayDropdownIcon = $('#display_caret').is(":checked");
        if(!displayDropdownIcon) {
            $('#submenu_headings_are_links').attr('checked', false);
        }
    });

});