;(function( $ ) {
    tinymce.PluginManager.add('qcsld_shortcode_btn', function( editor,url )
    {
        var shortcodeValues = [];

        editor.addButton('qcsld_shortcode_btn', {
			title : 'Generate SLD Shortcode',
            //text: 'SLD',
            icon: 'icon qc_sld_btn',
            onclick : function(e){
                $.post(
                    ajaxurl,
                    {
                        action : 'show_qcsld_shortcodes'
                        
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
