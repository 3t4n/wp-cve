(function( $ ) {
	'use strict';

    function form_filter() {
        var form = $('#advanced-export-filters'),
            filters = form.find('.advanced-export-filters');
        filters.hide();
        form.find('input:radio').change(function() {
            filters.slideUp('fast');
            switch ( $(this).val() ) {
                case 'attachment': $('#attachment-filters').slideDown(); break;
                case 'posts': $('#post-filters').slideDown(); break;
                case 'pages': $('#page-filters').slideDown(); break;
            }
        });
    }

    /*form load by ajax*/
    function ajax_page_load() {
        var fd = new FormData(),
            _wpnonce = $(this).find('input[name=_wpnonce]'),
            _wp_http_referer = $(this).find('input[name=_wp_http_referer]');

        fd.append('action', 'advanced_export_ajax_form_load');
        fd.append('_wpnonce', _wpnonce.val());
        fd.append('_wp_http_referer', _wp_http_referer.val());

        $.ajax({
            type: 'POST',
            url: advanced_export_js_object.ajaxurl,
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function (data, settings) {
                jQuery('#advanced-export-ajax-form-data').html('');
            },
            success   : function (data) {
                jQuery('#advanced-export-ajax-form-data').html(data);
                form_filter();
            },
            error     : function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    }
    ajax_page_load();

})( jQuery );