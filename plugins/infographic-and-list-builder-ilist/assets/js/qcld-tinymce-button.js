;(function( $ ) {
    tinymce.PluginManager.add('ilist_short_btn', function( editor,url )
    {
        var shortcodeValues = [];

        editor.addButton('ilist_short_btn', {
            //type: 'listbox',
			title : 'Add iList Shortcode',
            text: 'iList',
            icon: false,
            //image : url + '/16_pixel.png',
            onclick : function(e){
                $.post(
                    ajaxurl,
                    {
                        action : 'show_shortcodes'
                        
                    },
                    function(data){
                        $('#wpwrap').append(data);
                    }
                )
            },
            values: shortcodeValues
        });
    });

    

}(jQuery));