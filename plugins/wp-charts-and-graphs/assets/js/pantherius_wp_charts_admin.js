jQuery( window ).load( function() {
	var dts = {}, datas = {}, titles_array = [], values_array = [], param = [], data = {}, key = 0, uniqueid = "";
	jQuery( "#wpbody-content .wrap" ).css( "visibility", "visible" );
	jQuery( "#screen_preloader" ).fadeOut( "slow", function() {
		jQuery( this ).remove();
	});
	
	jQuery("#pwpc-shortcode").focus( function() {
		jQuery( this ).select(); 
	});
	
	jQuery("#pwpc_types").on( "change", function() {
		if ( this.value == "bubblechart" ) {
			jQuery( "#pwpc_values" ).val( "3:5:6,7:2:4,5:11:14,12:3:8" );
		}
		else {
			jQuery( "#pwpc_values" ).val( "3,7,5,12" );			
		}
	});
	
	jQuery( "body" ).on( "click", "#generate_wpc_shortcode", function() {
		dts = {};
		param[ 'style' ] = jQuery( "#pwpc_types" ).val();
		if ( jQuery( "#pwpc_max" ).val().length > 0 ) {
			param[ 'max' ] = jQuery( "#pwpc_max" ).val();
		}
		else {
			param[ 'max' ] = "";
		}
		if ( jQuery( "#pwpc_min" ).val().length > 0 ) {
			param[ 'min' ] = jQuery( "#pwpc_min" ).val();
		}
		else {
			param[ 'min' ] = "0";
		}
		if ( jQuery( "#pwpc_legend" ).val() == 'true' ) {
			param[ 'legend' ] = "true";
		}
		else {
			param[ 'legend' ] = "false";
		}
		if ( jQuery( "#pwpc_bgcolor" ).val() != '' ) {
			param[ 'bgcolor' ] = jQuery( "#pwpc_bgcolor" ).val().replace(/\s/g, '');
		}
		else {
			param[ 'bgcolor' ] = "";
		}
		if ( param[ 'max' ] != "" ) {
			jQuery( "#pwpc-shortcode" ).val( '[wpcharts type="' + param[ 'style' ] + '" bgcolor="' + param[ 'bgcolor' ] + '" min="' + param[ 'min' ] + '" max="' + param[ 'max' ] + '" legend="' + param[ 'legend' ] + '" titles="' + jQuery( "#pwpc_titles" ).val() + '" values="' + jQuery( "#pwpc_values" ).val() + '"]' );			
		}
		else {
			jQuery( "#pwpc-shortcode" ).val( '[wpcharts type="' + param[ 'style' ] + '" bgcolor="' + param[ 'bgcolor' ] + '" min="' + param[ 'min' ] + '" legend="' + param[ 'legend' ] + '" titles="' + jQuery( "#pwpc_titles" ).val() + '" values="' + jQuery( "#pwpc_values" ).val() + '"]' );
		}
		datas[ 'style' ] = param;
		titles_array = jQuery( "#pwpc_titles" ).val().split( "," );
		values_array = jQuery( "#pwpc_values" ).val().split( "," );
			jQuery.each( titles_array, function( key, value ) {
				dts[ key ] = {
					answer: value,
					count: values_array[ key ]
				}
		})
		datas[ 'datas' ]= {
			0: dts
		}
		uniqueid = Math.floor( Math.random() * 26 ) + Date.now();
		jQuery( "#pwpc-chart-area" ).html( '<div id="pwp-charts-' + uniqueid + '" class="admin-chart"><canvas style="width: 500px; height: 100%;"></canvas></div>' );
		jQuery( "#pwp-charts-" + uniqueid ).pmsresults({ "style": datas.style, "datas": datas.datas });
		jQuery( [document.documentElement, document.body] ).animate({
			scrollTop: jQuery( "#pwp-charts-" + uniqueid + "" ).offset().top - 350
		}, 1000);
	});
});