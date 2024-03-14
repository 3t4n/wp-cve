jQuery(document).ready(function($){

    let _elm = $('#ezme-main .ezme-color-picker');
    
    let _adjust = function(color) {

	if(!color.toHsl || 'function' !== typeof color.toHsl) {
	    return;
	}

	let hsl = color.toHsl()
	$('#ezme-main input[name=primary_color_hsl]').val( hsl.h + ',' + hsl.s + ',' + hsl.l);
	
    };
    
    _elm.wpColorPicker({
	change: function(e, ui) {
	    _adjust(ui.color);
	}
    });

    // https://automattic.github.io/Iris/
    if('function' === typeof _elm.iris) {
	_adjust( _elm.iris('color', true) );
    }
    
});
