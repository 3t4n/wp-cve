(function ($) {

    // we create a copy of the WP inline edit post function
    var $wp_inline_edit = inlineEditPost.edit;

    // and then we overwrite the function with our own code
    inlineEditPost.edit = function (id) {

        // "call" the original WP edit function
        // we don't want to leave WordPress hanging
        $wp_inline_edit.apply(this, arguments);

        // get the post ID
        var $post_id = 0;
        if (typeof (id) == 'object') {
            $post_id = parseInt(this.getId(id));
        }

        if ($post_id > 0) {
            // define the edit row
            var $edit_row = $('#edit-' + $post_id);
            var $post_row = $('#post-' + $post_id);

            // get the data
            var $siteseo_title = $('.column-siteseo_title .hidden', $post_row).text();
            var $siteseo_desc = $('.column-siteseo_desc .hidden', $post_row).text();
            var $siteseo_tkw = $('.column-siteseo_tkw', $post_row).text();
            var $siteseo_canonical = $('.column-siteseo_canonical', $post_row).text();
            var $siteseo_noindex = $('.column-siteseo_noindex', $post_row).html();
            var $siteseo_nofollow = $('.column-siteseo_nofollow', $post_row).html();
            var $siteseo_redirections_enable = $('.column-siteseo_404_redirect_enable', $post_row).html();
            var $siteseo_redirections_regex_enable = $('.column-siteseo_404_redirect_regex_enable', $post_row).html();
            var $siteseo_redirections_type = $('.column-siteseo_404_redirect_type', $post_row).text();
            var $siteseo_redirections_value = $('.column-siteseo_404_redirect_value', $post_row).text();

            // populate the data
            $(':input[name="siteseo_title"]', $edit_row).val($siteseo_title);
            $(':input[name="siteseo_desc"]', $edit_row).val($siteseo_desc);
            $(':input[name="siteseo_tkw"]', $edit_row).val($siteseo_tkw);
            $(':input[name="siteseo_canonical"]', $edit_row).val($siteseo_canonical);

            if ($siteseo_noindex && $siteseo_noindex.includes('<span class="dashicons dashicons-hidden"></span>')) {
                $(':input[name="siteseo_noindex"]', $edit_row).attr('checked', 'checked');
            }

            if ($siteseo_nofollow && $siteseo_nofollow.includes('<span class="dashicons dashicons-yes"></span>')) {
                $(':input[name="siteseo_nofollow"]', $edit_row).attr('checked', 'checked');
            }

            if ($siteseo_redirections_enable && $siteseo_redirections_enable == '<span class="dashicons dashicons-yes-alt"></span>') {
                $(':input[name="siteseo_redirections_enabled"]', $edit_row).attr('checked', 'checked');
            }
            if ($siteseo_redirections_regex_enable && $siteseo_redirections_regex_enable == '<span class="dashicons dashicons-yes"></span>') {
                $(':input[name="siteseo_redirections_enabled_regex"]', $edit_row).attr('checked', 'checked');
            }
            if ($siteseo_redirections_type && $siteseo_redirections_type != '404') {
                $('select[name="siteseo_redirections_type"] option[value="' + $siteseo_redirections_type + '"]', $edit_row).attr('selected', 'selected');
            }
            $(':input[name="siteseo_redirections_value"]', $edit_row).val($siteseo_redirections_value);
        }
    };

})(jQuery);
