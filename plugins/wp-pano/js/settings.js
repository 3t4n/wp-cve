jQuery(document).ready(function() {
	jQuery('.posttype_select').each(function() {
		var _this = jQuery(this);
		if ( _this.attr('val')!='' ) 
			_this.val(_this.attr('val'));
	});
	
	jQuery('.input-posttype').each(function() {
		var _this = jQuery(this);
		if(_this.attr('checked')) jQuery('.posttype_'+_this.attr('value')).prop( "disabled", false );
	});	
	
	jQuery('.input-posttype').click(function() {
		var _this = jQuery(this);
		if(_this.attr('checked')) 
			jQuery('.posttype_'+_this.attr('value')).prop( "disabled", false ); 
		else
			jQuery('.posttype_'+_this.attr('value')).prop( "disabled", true );
	});	
});