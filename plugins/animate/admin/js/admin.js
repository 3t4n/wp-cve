jQuery(function(){
	jQuery('#animatedialog-options-table').on("change", "#framework-style", function() {
		var style = jQuery(this).val();
		jQuery('#animatedialog-sandbox section').remove();
		jQuery('#animatedialog-sandbox').prepend('<section class="wow '+style+'"><img src="'+animate_plugin_data.url+'images/round200x200.png" width="200" height="200" alt=""></section>');
	});
	jQuery('#animatedialog-sandbox').on( "click", "button", function() {
		jQuery('#animatedialog-options-table #framework-style').change();
	});
});
