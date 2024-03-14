jQuery(document).ready(function ($) {
    // it is a copy of the inline edit function
    var wp_inline_edit_function = inlineEditPost.edit;

    // we overwrite the it with our own
    inlineEditPost.edit = function (post_id) {

        // let's merge arguments of the original function
        wp_inline_edit_function.apply(this, arguments);

        // get the post ID from the argument
        var id = 0;
        if (typeof (post_id) == 'object') { // if it is object, get the ID number
            id = parseInt(this.getId(post_id));
        }

        //if post id exists
        if (id > 0) {

            // add rows to variables
            var specific_post_edit_row = $('#edit-' + id),
                specific_post_row = $('#post-' + id),
                patreon_level = $('.column-patreon_level', specific_post_row).text(),
                series = $('.column-comic_series', specific_post_row).text();


            // set the series
            specific_post_edit_row.find('select[name="parent_id"] option').each(function () {
                if ($(this).text() == series) {
                    $(this).attr('selected', 'selected');
                };
            });

               // set the patreon level
               specific_post_edit_row.find('select[name="patreon_level"] option').each(function () {
                if ($(this).text() == patreon_level) {
                    $(this).attr('selected', 'selected');
                };
            });

        }
    }

    $('body').on('click', 'input[name="bulk_edit"]', function () {

        // let's add the WordPress default spinner just before the button
        $(this).after('<span class="spinner is-active"></span>');


        // define: prices, featured products and the bulk edit table row
        var bulk_edit_row = $('tr#bulk-edit'),
            post_ids = new Array()
        series = bulk_edit_row.find('select[name="parent_id"]').val(),
        patreon_level= bulk_edit_row.find('select[name="patreon_level"]').val();


        // now we have to obtain the post IDs selected for bulk edit
        bulk_edit_row.find('#bulk-titles').children().each(function () {
            post_ids.push($(this).attr('id').replace(/^(ttle)/i, ''));
        });

         // save the data with AJAX
        $.ajax({
            url: ajaxurl, // WordPress has already defined the AJAX url for us (at least in admin area)
            type: 'POST',
            data: {
                action: 'toocheke_companion_save_bulk', // wp_ajax action hook
                post_ids: post_ids, // array of post IDs
                series: series, // new series
                patreon_level : patreon_level, //patreon level
                nonce: $('#toocheke_companion_nonce').val() // I take the nonce from hidden #toocheke_companion_nonce field
            }
        });
    });

});
