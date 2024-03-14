
jQuery(document).ready(function ($) {
    jQuery('.wdgk_select_product').select2({
        ajax: {
            type: 'POST',
            url: wdgkObj.ajaxurl,
            dataType: 'json',
            data: (params) => {
                return {
                    'search': params.term,
                    'action': 'wdgk_product_select_ajax',
                }
            },
            processResults: (data, params) => {
                console.log(data);
                const results = data.map(item => {
                    return {
                        id: item.id,
                        text: item.title,
                    };
                });
                return {
                    results: results,
                }
            },
        },
        minimumInputLength: 3
    });
});