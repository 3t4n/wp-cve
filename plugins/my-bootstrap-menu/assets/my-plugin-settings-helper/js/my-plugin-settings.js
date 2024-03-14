/**
 * Created by Michael on 11/06/2015.
 *
 * http://diveintohtml5.info/storage.html
 */



jQuery(document).ready(function($) {

    var values_changed = ($("[name='reset_values']").length > 0);

    var previous_uniqueid  = $('.unique_id').val();

    /**
     * Capture on Window unload
     * @type {confirmExit}
     */
    window.onbeforeunload = confirmExit;

    /**
     * Handles the Nav-tabs - sets active tab and unhides/hides sections
     */
    $('.nav-tab-wrapper > .my_plugin_tab')
        .click(function(event){
            event.preventDefault();
            var tab_id = $(this).prop('id');

            //Set Tabs to inactive/active
            $('.my_plugin_tab').removeClass('nav-tab-active');
            $('.my_plugin_tab#' + tab_id).addClass('nav-tab-active');

            //Set Tab Content to hidden/visible
            $('.my_plugin_tab_content').addClass('my_plugin_hidden');
            $('.my_plugin_tab_content#' + tab_id + '_content').removeClass('my_plugin_hidden');
        });

    /**
     * Unique id change... reload the page with an additional parameter passed in..
     */
    $('.unique_id')
        .focus(function(){
            previous_uniqueid = this.value;
        }).change(function(event) {
        //Set the url parameter for the unique id and reload the page
        var unique_id = $(this).val();
        // This parsing should be done on input. i.e. sanitize_title()
        // unique_id = unique_id.replace(/[^a-zA-Z0-9]+/g, '_').toLowerCase();
        //Set the value back as either - page gets reloaded and the unique is set, or user stays and the value is the original.
        $(this).val(previous_uniqueid);
        //Change the url and reload the page if user selects ok...
        add_param_to_url('unique_id', unique_id);
    });

    /**
     * Set the values changed flag - to catch on exit/change of page
     * .not('.unique_id')
     */
    $('.my_plugin_input')
        .change(function(e){
            if(!$(':focus').hasClass('unique_id')) {
                values_changed = true;
            }
        });

    /**
     * Ignore confirm exit if submitting the page to be saved
     */
    $("input[name='submit']")
        .click(function() {
            values_changed = false;
        });

    /**
     * Flag changed values after resetting to default
     */
    $("input[name='reset']")
        .click(function() {
            values_changed = true;
        });


    /**
     * Confirm if user wishes to leave before settings saved
     * @returns {string}
     */
    function confirmExit() {
        //return (values_changed);
        if (values_changed === true) {
            return "Changed settings have not been saved.";
        }
    }

    /**
     * Adds the specified paramater key and value to the url and reloads the page
     * @param param_key
     * @param param_value
     */
    function add_param_to_url(param_key, param_value){
        var url = window.location.href;
        if (url.indexOf('?') > -1){
            url += '&' + param_key + '=' + param_value;
        }else{
            url += '?' + param_key + '=' + param_value;
        }
        window.location.href = url;
    }

    /**
     * Gets the current unique id, or null string if not available
     * @returns {*|jQuery|HTMLElement}
     */
    function getUniqueId(){
        var unique_id = $('.unique_id');
        if(unique_id.length){
            unique_id = unique_id.val();
        } else {
            unique_id = '';
        }
        return unique_id;
    }
    /**
     * Gets the cookie for a given named value
     * @param key
     * @returns {T}
     */
    function getKeyValue(key) {
        if(supports_html5_storage()) {
            return localStorage[getDatastoreKey(key)];
        } else {
            var value = "; " + document.cookie;
            var parts = value.split("; " + getDatastoreKey(key) + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
    }

    /**
     * Saves the key/value to a cookie or local storage if available...
     * @param key
     * @param value
     */
    function saveKeyValue(key, value){
        if(supports_html5_storage()) {
            localStorage[getDatastoreKey(key)] = value;
        } else {
            document.cookie = getDatastoreKey(key) + '=' + value;
        }
    }

    /**
     * Removes the key/cookie from the system
     * @param key
     */
    function deleteKeyValue(key){
        if(supports_html5_storage()) {
            localStorage.removeItem(getDatastoreKey(key));
        } else {
            document.cookie = getDatastoreKey(key) + "=''; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        }
    }

    /**
     * Gets the key for the datastore, appends _[key] if there is one
     * @param key
     * @returns {string}
     */
    function getDatastoreKey(key){
        if(key.length){
            return datastore_name + "_" + key;
        }
        return datastore_name;
    }


});

/**
 * Checks if the browser supports html5 browswer storage... will use cookies if not
 * @returns {boolean}
 */
function supports_html5_storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

var hidden_class = 'my_plugin_hidden';

/**
 * Extends the jQuery model with a visibility toggle
 * @returns {*}
 */
jQuery.fn.visibilityToggle = function() {
    return this.css('visibility', function(i, visibility) {
        return (visibility == 'visible') ? 'hidden' : 'visible';
    });
};
jQuery.fn.visibilityHidden = function() {
    return this.css('visibility', 'hidden').addClass(hidden_class);
};
jQuery.fn.visibilityVisible = function() {
    return this.css('visibility', 'visible').removeClass(hidden_class);
};

