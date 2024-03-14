function load_dashboard( page = '', keyword = '') {
    let container = jQuery('#report-content');
    if ( keyword === '' ) {
        keyword = lasso_lite_helper.get_url_parameter('link-search-input');
    }

    if ( page === '' ) {
        page = lasso_lite_helper.get_page_from_current_url();
    }

    jQuery.ajax({
        url: lassoLiteOptionsData.ajax_url,
        type: 'post',
        data: {
            action: 'lasso_lite_dashboard_get_list',
            nonce: lassoLiteOptionsData.optionsNonce,
            page: page,
            keyword: keyword
        },
        beforeSend: function () {
            container.html(lasso_lite_helper.get_loading_image());
        }
    })
    .done(function (res) {
        if ( res.success === true ) {
            let data = res.data;
            let json_data = data.output;

            lasso_lite_helper.inject_to_template(jQuery("#report-content"), 'dashboard-list', json_data);
            lasso_lite_helper.generate_paging( jQuery('.dashboard-pagination'), data.page, data.total, function (page_number) {
                load_dashboard(page_number);
            }, data.limit_on_page);

            if ( data.total === 0 || json_data.length == 0 ) {
                container.html(lasso_lite_helper.default_empty_data);
            }
        } else {
            container.html(lasso_lite_helper.default_empty_data);
        }
    })
    .fail(function (xhr, status, error) {
        container.html(lasso_lite_helper.default_empty_data);
    });
}

jQuery(document).ready(function () {
    load_dashboard();
    jQuery("#links-filter").submit(function (e) {
        e.preventDefault();
        let keyword = jQuery("#link-search-input").val().trim();
        lasso_lite_helper.update_url_parameter('link-search-input', keyword);
        load_dashboard('', keyword );
    });
});
