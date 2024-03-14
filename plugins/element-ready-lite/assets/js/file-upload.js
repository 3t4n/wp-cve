jQuery(window).on("elementor:init", function () {

var fileselectItemView = elementor.modules.controls.BaseData.extend({
	
    onReady: function () {
       
    	var file_input_id = this.$el.find( '.element-ready-selected-fle-url' ).attr('id');

    	this.$el.find( '.element-ready-select-file' ).click( function() {
		
	   	  	var _file_uploader = wp.media({
	            title: 'Upload File',
	            button: {
	                text: 'Get Link'
	            },
	            multiple: false,
				
	        })
	        .on('select', function() {
	            var attachment = _file_uploader.state().get('selection').first().toJSON();
	            jQuery( "#" + file_input_id ).val( attachment.url );
	            jQuery( "#" + file_input_id ).trigger( "input" );
	        })
	        .open();
	   	} );
	},
	
});

elementor.addControlView('file-select', fileselectItemView);
});