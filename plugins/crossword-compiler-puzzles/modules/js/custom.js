(function($) {
	var scale = 1;
	$(document).ready(function(){
                // No word search found			
                setTimeout(function(){
				
				if( $('#CrosswordCompilerPuz').width() > $('#CrosswordCompilerPuz').parent().width()){
					scale = $('#CrosswordCompilerPuz').parent().width() / $('#CrosswordCompilerPuz').width();
					var width = $('#CrosswordCompilerPuz').width() * scale;
					var height = $('#CrosswordCompilerPuz').height() * scale;
					$('#CrosswordCompilerPuz').css({
						'transform': 'scale('+scale+')',
						'-webkit-transform': 'scale('+scale+')',
						'-ms-transform': 'scale('+scale+')'
					});
					$( "#CrosswordCompilerPuz" ).wrap( "<div style='height:"+height+"px'></div>" );
				}
				
			}, 500);
	});
})( jQuery );
