var audioSelectItemView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
    	var file_input_id = this.$el.find('.smc-selected-audio-url').attr('id');

    	this.$el.find('.smc-ec-select-file').click(function() {
	   	  	var smc_ec_file_uploader = wp.media({
	            title: 'Upload Audio File',
	            button: {text: 'Get Audio'},
	            library: {type: 'audio'},
	            multiple: false
	        })
	        .on('select', function() {
	            var attachment = smc_ec_file_uploader.state().get('selection').first().toJSON();
	            jQuery("#" + file_input_id).val(attachment.url);
	            jQuery("#" + file_input_id).trigger("input");
	        })
	        .open();
	   	});
	},
});
elementor.addControlView('mpfe-audio-chooser', audioSelectItemView);