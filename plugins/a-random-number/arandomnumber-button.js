(function() {
    tinymce.PluginManager.add('arandomnumber_tc_button', function( editor, url ) {
        editor.addButton( 'arandomnumber_tc_button', {
            text: 'A Random Number',
            icon: false,
            onclick: function() {
			    editor.windowManager.open( {
			        title: 'Insert A Random Number',
			        body: [{
			            type: 'textbox',
			            name: 'min',
			            label: 'Minimum Number'
			        },
			        {
			            type: 'textbox',
			            name: 'max',
			            label: 'Maximum Number'
			        },
			        {
			        	type: 'container',
			        	html: 'If no values are used, will default to 1-100.<br/>Negative numbers allowed.'
			        }],
			        onsubmit: function( e ) {
			        	if(e.data.min && e.data.max){
			            	editor.insertContent( '[arandomnumber min=' + e.data.min + ' max=' + e.data.max + ']');
			        	}
			        	else{
			        		editor.insertContent( '[arandomnumber]');
			        	}
			        }
			    });
			}
        });
    });
})();