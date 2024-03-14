jQuery(document).ready(function() {
	jQuery( ".spEmailJSCloak" ).each( function(){
		var address = jQuery( this ).html().replace( / -dot- /g, "." ).replace( / -at- /g, "@" );
		jQuery( this ).html( '<a href="mailto:' + address + '">' + address + '</a>' );
	});
});

