(function( $ ) {
	'use strict';
	$(document).ready(function () {	

		$(document).find("#wpg-auto-scroll-button-color").wpColorPicker();

        $(document).find('.nav-tab-wrapper a.nav-tab').on('click', function (e) {
            if(! $(this).hasClass('no-js')){
                var elemenetID = $(this).attr('href');
                var active_tab = $(this).attr('data-tab');
                $(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
                    if ($(this).hasClass('nav-tab-active')) {
                        $(this).removeClass('nav-tab-active');
                    }
                });
                $(this).addClass('nav-tab-active');
                $(document).find('.wpg_auto_scroll_tab').each(function () {
                    $(this).css('display', 'none');
                });
                $(document).find("[name='wpg_auto_scroll_tab']").val(active_tab);
                $('.wpg-auto-scroll-tab-content' + elemenetID).css('display', 'block');
                e.preventDefault();
            }
        });

		$(document).find('#wpg-auto-scroll-settings-form [data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('change', '.wpg_toggle_checkbox, .wpg-switch-checkbox', function (e) {
            var state = $(this).prop('checked');
            var parent = $(this).parents('.wpg_toggle_parent');
            
            if($(this).hasClass('wpg_toggle_slide')){
                switch (state) {
                    case true:
                        parent.find('.wpg_toggle_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.wpg_toggle_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:
                        parent.find('.wpg_toggle_target').show(250);
                        break;
                    case false:
                        parent.find('.wpg_toggle_target').hide(250);
                        break;
                }
            }
        });

        $(document).on('keydown', function(e){
			if ( ( e.ctrlKey && e.which == 83 ) && !( e.which == 19 ) ){
				var saveButton = $(document).find('input#wpg_submit');
				if( saveButton ){
					e.preventDefault();
					saveButton.trigger('click');
				}
				return false;
			}
		});

	})

})( jQuery );
