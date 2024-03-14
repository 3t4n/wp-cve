jQuery(document).ready(function ($) {
    var seokeywords_column = $('#metaseo_seokeywords_details_column');
    var seokeywords_coulumn_item = $('td.metaseo_seokeywords_details_column');
    var data = [];
    seokeywords_column.append('<a href=#" class="dashicons dashicons-edit" title="' + wpms_post_bulk.title + '"></a>');
    seokeywords_column.wrapInner("<span/>")
    seokeywords_column.append('<span><a href="#" class="button button-primary button-small wpms-column-save-all">' + wpms_post_bulk.saveAll + '</a> <a href="#" class="button-link button-link-delete wpms-column-cancel-all">' + wpms_post_bulk.cancelButton + "</a></span>")

    seokeywords_column.find('.dashicons-edit').on('click', function () {
        seokeywords_coulumn_item.find('.wpms-column-display.keyword').hide();
        seokeywords_coulumn_item.find('.wpms-column-value').show().css('display', 'block');
        seokeywords_column.find('span:first').hide();
        seokeywords_column.find('span:last').show();
    });

    seokeywords_coulumn_item.find('.wpms-column-value').on('input', function (e) {
        seokeywords_coulumn_item.find('.wpms-column-edit').show();
        var inputTextValue = $(e.target).text().trim();
        $(this).siblings('.wpms-column-display.keyword').find('span').text(inputTextValue);
    }).on('keypress', function (e) {
        if (13 === e.which) {
            e.preventDefault();
            $(this).siblings('.wpms-column-edit').find('.wpms-column-save').trigger("click");
        }
    });

    seokeywords_column.find('.wpms-column-cancel-all').on('click', function () {
        cancel();
    });

    seokeywords_coulumn_item.find('.wpms-column-cancel').on('click', function () {
        cancel();
    });

    seokeywords_column.find('.wpms-column-save-all').on('click', function () {
        var listData = [];
        var listTR = seokeywords_coulumn_item.closest("tr");

        for (var i = 0; i < listTR.length; i++) {
            var id_post = parseInt($(listTR[i]).attr('id').replace("post-", ""));
            var keyword = $(listTR[i]).find('.metaseo_seokeywords_details_column .wpms-column-value').text().trim();
            var data = {'idpost': id_post, 'keyword': keyword};
            listData.push(data);
        }

        save(listData);
    });

    seokeywords_coulumn_item.find('.wpms-column-save').on('click', function () {
        var id_post = parseInt($(this).closest("tr").attr("id").replace("post-", ""));
        var keyword = $(this).parent(".wpms-column-edit").siblings("span.wpms-column-value").text().trim();
        var listData = [];
        var data = {'idpost': id_post, 'keyword': keyword};
        listData.push(data);

        save(listData);
    });

    function save(data) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: {
                'action': 'wpms',
                'task': 'update_seokeyword_bulk_edit',
                'listData': data,
                'wpms_nonce': wpms_post_bulk.nonce
            },
            success: function (res) {
                if (res.status && res.res_back) {
                    Object.entries(res.res_back).forEach(function (value) {
                        var post_id = value[0];
                        var score = value[1][0];
                        var keyword = value[1][1];
                        var className = getScoreClass(score);

                        $('.wp-list-table tr[id="post-'+post_id+'"]').find('.wpms-column-display.score').removeClass('wpms-no-score wpms-bad-score wpms-good-score wpms-great-score').addClass(className);
                        $('.wp-list-table tr[id="post-'+post_id+'"]').find('.wpms-column-display.score strong').text(score +' / 100');
                        $('.wp-list-table tr[id="post-'+post_id+'"]').find('.wpms-column-display.keyword span').text(keyword);

                        if (keyword !== '') {
                            $('.wp-list-table tr[id="post-'+post_id+'"]').find('.wpms-column-display.keyword').show();
                        } else {
                            $('.wp-list-table tr[id="post-'+post_id+'"]').find('.wpms-column-display.keyword').hide();
                        }
                    })
                }
                cancel();
            }
        });
    }

    function cancel() {
        var listTR = seokeywords_coulumn_item.closest("tr");
        for (var i = 0; i < listTR.length; i++) {
            var keyword = $(listTR[i]).find('.metaseo_seokeywords_details_column .wpms-column-value').text().trim();
            if (keyword !== '') {
                $(listTR[i]).find('.metaseo_seokeywords_details_column .wpms-column-display.keyword').show();
            } else {
                $(listTR[i]).find('.metaseo_seokeywords_details_column .wpms-column-display.keyword').hide();
            }
        }

        seokeywords_coulumn_item.find('.wpms-column-value').hide();
        seokeywords_coulumn_item.find('.wpms-column-edit').hide();
        seokeywords_column.find('span:first').show();
        seokeywords_column.find('span:last').hide();
    }

    function getScoreClass(score)
    {
        if (score === '') {
            return 'wpms-no-score';
        }

        if (score > 75) {
            return 'wpms-great-score';
        }

        return 'wpms-bad-score';
    }
});
