/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function ($) {
    $(document).ready(function () {
    	
        $(document).foundation();

        $('[data-dependents]').on('change', function () {
            var $this = $(this),
                attrDependents = $this.attr('data-dependents'),
                dependents = attrDependents ? JSON.parse(attrDependents) : {},
                tag = this.nodeName.toLowerCase(),
                type = 'input' === tag ? $this.attr('type') : tag,
                value = undefined;

            switch (type) {
                case 'checkbox':
                    value = $this.prop('checked') ? 1 : 0;
                    break;
                case 'radio':
                case 'select':
                    value = $this.val();
                    break;
            }

            if (dependents[value]) {
                $.each(dependents[value], function (action, selectors) {
                    $.each(selectors, function (i, selector) {
                        if ($.isFunction($(selector)[action])) {
                            $(selector)[action]();
                        }
                    })
                });
            }
        });
        
        $('.twoj-gallery-option-new').click( function(evn){
        	evn.preventDefault();
        });

        //$('input[type="checkbox"][data-dependents]:checked').trigger('change');
        $('input[type="checkbox"][data-dependents]').trigger('change');
        $('input[type="radio"][data-dependents]:checked').trigger('change');
        $('select[data-dependents]').trigger('change');


        

		/*jQuery(".ui-dialog-titlebar-close").addClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close");
		
		jQuery('.twoj-gallery-option-premium').click( function(event ){
			event.preventDefault();
			twoJgalleryDialogOptions.dialog("open");
		});*/
    });
})(jQuery);
