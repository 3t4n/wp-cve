function clearInput(fieldName) {
    fieldName = fieldName.replace(/[^a-zA-Z0-9\-_ ]/g, "");
    tmp = fieldName.replace(/(\s|&nbsp;|&\#160;)+/gi, "_");
    //fieldName = tmp.toLowerCase();
    return fieldName;
}
function clearOptions(fieldName) {
    fieldName = fieldName.replace(/[^a-zA-Z0-9,\-_ ]/g, "");
    tmp = fieldName.replace(/(\s|&nbsp;|&\#160;)+/gi, " ");
    //fieldName = tmp.toLowerCase();
    return fieldName;
}

(function() {

    tinymce.PluginManager.add('ssing_mce_button', function(editor, url) {
        /*  CONDITIONAL LOGIC
         * function test_conditional_logic() {
         jQuery("#mceu_107").hide();
         }*/
        editor.addButton('ssing_mce_button', {
            image: url + '/eSignature.png',
            title: 'SwiftSignature Shortcode Generator', //Tooltip
            type: 'menubutton',
            menu: []
        });
    });
})();