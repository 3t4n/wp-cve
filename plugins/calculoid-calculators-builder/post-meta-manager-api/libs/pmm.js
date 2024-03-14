/*****************************************************************************/
// CUSTOM JQUERY PLUGIN - LIMIT TEXTBOX TO ONLY NUMBERS
/*****************************************************************************/
(function ($)
{
	$.fn.onlyNumbers = function(options){
		var options = $.extend({}, $.fn.onlyNumbers.defaults, options);
		
		return this.each(function(){
			var $element = $(this);
			
			if( ( $element.is('input') && $element.attr("type") == "text" ) || $element.is("textarea") ){
				$element.keydown(function(e){
					var key = e.charCode || e.keyCode || e.which || 0;

					if (key == 109)
					{
						return !($element.val().indexOf("-") != -1);
					}
					
					if (key == 190)
					{
						if (!options.allowDot) return false;
						return !($element.val().indexOf(".") != -1);
					}

					return (
						key == 8 ||
						key == 9 ||
						key == 46 ||
						key == 36 ||
						( key >= 37 && key <= 40 ) ||
						( key >= 48 && key <= 57 ) ||
						( key >= 96 && key <= 105 )
					);
				});
			}
		});
	};		
	
	$.fn.onlyNumbers.defaults = {
		allowDot: false
	};
	
})(jQuery);