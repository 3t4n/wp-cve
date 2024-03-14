jQuery(document).ready(function ($) {
    // initiate the table.
    var table = $('.filr').DataTable({
        'paging': filr_shortcode.pagination,
        'searching': filr_shortcode.search,
        "order": [filr_shortcode.default_sort, filr_shortcode.default_sort_type],
        "pageLength": filr_shortcode.default_number_rows,
        "autoWidth": false,
        responsive: true,
        'language': {
            'decimal': '',
            'emptyTable': filr_shortcode.translations.no_files,
            'info': filr_shortcode.translations.count_files,
            'infoEmpty': filr_shortcode.translations.no_files,
            'infoFiltered': filr_shortcode.translations.filtered_files,
            'lengthMenu': filr_shortcode.translations.available_files,
            'loadingRecords': filr_shortcode.translations.loading_files,
            'searchPlaceholder': filr_shortcode.translations.search_files,
            'search': '',
            'zeroRecords': filr_shortcode.translations.no_files_found,
            'paginate': {
                'first': filr_shortcode.translations.first_page_files,
                'last': filr_shortcode.translations.last_page_files,
                'next': filr_shortcode.translations.next_page_files,
                'previous': filr_shortcode.translations.previous_page_files
            },
        },
    });

    $('.filr-button').on('click', function (e) {
        var download_id = $(this).attr('data-download');
        var expire = $(this).attr('data-expire');
        var current = $(this);

        if ('∞' !== expire) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: filr_shortcode.ajax_url,
                data: {'action': 'decrease_downloads', 'post_id': download_id, 'nonce': filr_shortcode.nonce},
                success: function (response) {
                    // find parent tr and remaining td.
                    var current_tr = $(current).parent().parent();

                    if (0 === response.remaining) {
                        $(current_tr).remove();
                    } else {
                        $(current_tr).find('.remaining').text(response.remaining);
                    }
                }
            });
        }
    });

    // Folder management.

    // Todo: Do that for each folder.
    var sub_table = $('.filr-folder-table').DataTable({
        'paging': filr_shortcode.pagination,
        'searching': filr_shortcode.search,
        "order": [filr_shortcode.default_sort, filr_shortcode.default_sort_type],
        "pageLength": filr_shortcode.default_number_rows,
        "autoWidth": false,
        responsive: true,
        'language': {
            'decimal': '',
            'emptyTable': filr_shortcode.translations.no_files,
            'info': filr_shortcode.translations.count_files,
            'infoEmpty': filr_shortcode.translations.no_files,
            'infoFiltered': filr_shortcode.translations.filtered_files,
            'lengthMenu': filr_shortcode.translations.available_files,
            'loadingRecords': filr_shortcode.translations.loading_files,
            'searchPlaceholder': filr_shortcode.translations.search_files,
            'search': '',
            'zeroRecords': filr_shortcode.translations.no_files_found,
            'paginate': {
                'first': filr_shortcode.translations.first_page_files,
                'last': filr_shortcode.translations.last_page_files,
                'next': filr_shortcode.translations.next_page_files,
                'previous': filr_shortcode.translations.previous_page_files
            },
        },
    });

    // Position folders based on visiblity.
    $( ".dataTables_wrapper" ).each(function() {
        if ($(this).css('visibility') == 'hidden') {
            $( this ).css( "position", "absolute" );
        } else {
            $(this).css('position', 'relative');
        }
    });


    // Opening folders.
    $(document).on('click', '.filr-folder-button', function () {
        var folder_id = $(this).attr('id');
        var folder_wrapper = $('#filr-' + folder_id + '_wrapper');

        $(this).toggleClass('filr-folder-button-close');

        $(this).text(filr_shortcode.translations.close_folder_text);

        if ($(folder_wrapper).css('visibility') == 'hidden') {
            $(folder_wrapper).css('visibility', 'visible');
            $(folder_wrapper).css('position', 'relative');
        } else {
            $(this).text(filr_shortcode.translations.open_folder_text);
            $(folder_wrapper).css('visibility', 'hidden');
            $(folder_wrapper).css('position', 'absolute');
        }
    });


    // File preview.
    if ($('.filr-preview').length > 0 && 'on' === filr_shortcode.use_preview) {
        $('.filr-preview').each(function(){
            // Check if it's an image.
            var image_url = $(this).attr('href');

            if( isImage(image_url) ) {
                $(this).on('click', function (e) {
                    e.preventDefault();
                });
                console.log(image_url)
                $('.filr-preview').anarchytip();
            }
        });
    }

    function isImage(url) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url);
    }

});
