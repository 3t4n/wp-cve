;(function( $ ) {
    tinymce.PluginManager.add('qcld_short_btn_chart', function( editor,url )
    {
        var shortcodeValues = [];

        editor.addButton('qcld_short_btn_chart', {
            //type: 'listbox',
			title : 'iChart Shortcode',
            //text: 'iChart',
            //icon: 'fa-meetup',
            image : url + '/ichart.png',
            onclick : function(e){
                e.preventDefault();
		
				$('#ichart-qcld-chart-field-modal').show();
            },
            values: shortcodeValues
        });
    });

    


 

}(jQuery));