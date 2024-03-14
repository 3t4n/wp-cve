(function($) {
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    $('.md-size-chart-btn').click(function (t) {
        t.preventDefault();
        var c = $(this).attr('chart-data-id');
        $('.scfw-size-chart-modal[chart-data-id="' + c + '"]').show();
        $('.scfw-size-chart-modal[chart-data-id="' + c + '"]').removeClass('md-size-chart-hide');
        $('.scfw-size-chart-modal[chart-data-id="' + c + '"]').addClass('md-size-chart-show');
        $('body').addClass('scfw-size-chart-active');

        if ( $('.scfw_size-chart-details-tab').length !== 0 ) {
        	setTimeout(function() {
	        	// Set tab wdith and position on tab change
				var actTabPosition = $('.scfw_size-chart-details-tab span.active-tab').position();
				var actTabWidth = $('.scfw_size-chart-details-tab span.active-tab').outerWidth();
			    $('.scfw_size-chart-details-tab .scfw_tab_underline').css({'left':+ actTabPosition.left, 'width':actTabWidth});
	        }, 400);
        }
    });

    $('div#md-size-chart-modal .remodal-close').click(function (t) {
        t.preventDefault();
        $(this).parents('.scfw-size-chart-modal').removeClass('md-size-chart-show');
        $(this).parents('.scfw-size-chart-modal').addClass('md-size-chart-hide');
        $('body').removeClass('scfw-size-chart-active');
    });

    $('div.md-size-chart-overlay').click(function (t) {
        t.preventDefault();
        $(this).parents('.scfw-size-chart-modal').removeClass('md-size-chart-show');
        $(this).parents('.scfw-size-chart-modal').addClass('md-size-chart-hide');
        $('body').removeClass('scfw-size-chart-active');
    });

    $('.md-size-chart-modal').each(function () {
        var c = $(this).attr('chart-data-id');
        $('.md-size-chart-modal[chart-data-id="' + c + '"]').slice(1).remove();
    });

    $('.scfw_size-chart-details-tab span').click(function() {
		var tab_id = $(this).attr('data-tab');
		$('.scfw_size-chart-details-tab span').removeClass('active-tab');
		$('.scfw_size-chart-details-tab + .chart-container .scfw-tab-content').removeClass('active-tab');

		$(this).addClass('active-tab');
		$('.scfw_size-chart-details-tab + .chart-container #' + tab_id).addClass('active-tab');

		// Set tab wdith and position on tab change
		var tabPosition = $(this).position();
		var tabWidth = $(this).outerWidth();
		$('.scfw_size-chart-details-tab span').css('border-color','transparent');
	    $('.scfw_size-chart-details-tab .scfw_tab_underline').css({'visibility': 'visible', 'left':+ tabPosition.left, 'width':tabWidth});
	});

	$('.scfw-chart-table td, .scfw-chart-table th').mouseenter(function() {
		var columnIndex = $(this).prevAll().length + 1;
		var rowIndex = $(this).closest('tr').prevAll().length + 1;
	    
		if (columnIndex > 1) {
	        $(this).closest('table').find('tr:lt(' + rowIndex + ') > td:nth-child(' + columnIndex + '), tr:lt(' + rowIndex + ') > th:nth-child(' + columnIndex + ')').addClass('col-highlight');
	    }
	}).mouseleave(function() {
	    $(this).closest('table').find('tr > .col-highlight').removeClass('col-highlight');
	});
})(jQuery);
