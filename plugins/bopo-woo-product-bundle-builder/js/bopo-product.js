jQuery(document).ready(function ($) {
    'use strict';
    let move = false;
    let bopobb_active_select = '';
    bopobb_sortable();
    bopobb_active_settings();

    $(document).on('click', '.bopobb_shortcode_show', function () {
        let $this = $(this),
            this_copied_text = $this.parent().find('.bopobb_copy_tooltip');
        /*Copy event*/
        $this.select();
        document.execCommand('copy');
        /*Show tooltip and auto hide it */
        this_copied_text.css('visibility', 'visible');
        setTimeout(function () {
            this_copied_text.css('visibility', 'hidden');
        }, 1000);
    });

    $(document).on('change', '#product-type', function () {
        if (typeof bopobb_get_type !== 'undefined') {
            bopobb_init_new_bopo(bopobb_get_type);
        } else {
            bopobb_active_settings();
        }
    });

    function bopobb_init_new_bopo(type = 'bopobb') {
        if (type == 'bopobb') {
            $('#product-type').val('bopobb');

            $('li.general_tab').addClass('show_if_bopobb');
            $('#_downloadable').closest('label').addClass('show_if_bopobb').removeClass('show_if_simple');
            $('#_virtual').closest('label').addClass('show_if_bopobb').removeClass('show_if_simple');

            $('.show_if_external').hide();
            $('.show_if_simple').show();
            $('.show_if_bopobb').show();

            $('.product_data_tabs li').removeClass('active');
            $('.product_data_tabs li.bopobb_tab').addClass('active');

            $('.panel-wrap .panel').hide();
            $('#bopobb-settings').show();

        }
    }

    function bopobb_active_settings() {
        if ($('#product-type').val() == 'bopobb') {
            $('li.general_tab').addClass('show_if_bopobb');
            $('#_downloadable').closest('label').addClass('show_if_bopobb').removeClass('show_if_simple');
            $('#_virtual').closest('label').addClass('show_if_bopobb').removeClass('show_if_simple');

            $('.show_if_external').hide();
            $('.show_if_simple').show();
            $('.show_if_bopobb').show();

            $('.product_data_tabs li').removeClass('active');
            $('.product_data_tabs li.bopobb_tab').addClass('active');

            $('.panel-wrap .panel').hide();
            $('#bopobb-settings').show();

        } else {
            $('li.general_tab').removeClass('show_if_bopobb');
            $('#_downloadable').closest('label').removeClass('show_if_bopobb').addClass('show_if_simple');
            $('#_virtual').closest('label').removeClass('show_if_bopobb').addClass('show_if_simple');

            if ($('#product-type').val() != 'grouped') {
                $('.general_tab').show();
            }

            if ($('#product-type').val() == 'simple') {
                $('#_downloadable').closest('label').show();
                $('#_virtual').closest('label').show();
            }
        }
    }

    function bopobb_sortable() {
        $('.bopobb-pbi-contain').sortable({
            items: '.bopobb-pbi',
            placeholder: 'bopobb-pbi-place-holder',
            cursor: 'move',
            connectWith: '.bopobb-pbi-contain',
            axis: 'y',
            handle: '.bopobb-pbi-anchor',
            start: function (event, ui) {
            },
            over: function (event, ui) {
            },
            receive: function (event, ui) {
                move = true;
            },
            stop: function (event, ui) {
                bopobb_ApplyChange();
            }
        }).disableSelection();
    }

    function bopobb_ApplyChange() {
        let index = 0;
        $('.bopobb-pbi-contain .bopobb-pbi .bopobb-pbi-index').each(function () {
            $(this).val(index);
            index = index + 1;
        });
        move = false;
    }

    function bopobb_ResetDefault() {
        let _index = bopobb_active_select.closest('.bopobb-pbi').querySelector('.bopobb-pbi-index').value;
        $('#bopobb_bpi_default_product_' + _index).val("").trigger("change");
    }

    $(".bopobb-pbi-category-search").select2({
        closeOnSelect: false,
        placeholder: "Please enter category title",
        ajax: {
            url: "admin-ajax.php?action=bopobb_search_cat",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    nonce: bopobbProductVars.bopobb_nonce,
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1
    }).on('select2:open', function (e) {
        bopobb_active_select = e.currentTarget;
    }).on("select2:selecting", function(e) {
        bopobb_active_select = e.currentTarget;
        bopobb_ResetDefault();
    }).on("select2:unselecting", function(e) {
        bopobb_active_select = e.currentTarget;
        bopobb_ResetDefault();
    });

    $(".bopobb-pbi-tag-search").select2({
        closeOnSelect: false,
        placeholder: "Please enter category title",
        ajax: {
            url: "admin-ajax.php?action=bopobb_search_tag",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    nonce: bopobbProductVars.bopobb_nonce,
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1
    }).on('select2:open', function (e) {
        bopobb_active_select = e.currentTarget;
    }).on("select2:selecting", function(e) {
        bopobb_active_select = e.currentTarget;
        bopobb_ResetDefault();
    }).on("select2:unselecting", function(e) {
        bopobb_active_select = e.currentTarget;
        bopobb_ResetDefault();
    });

    $(".bopobb-pbi-product-search").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your product title",
        ajax: {
            url: "admin-ajax.php?action=bopobb_search_product",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    nonce: bopobbProductVars.bopobb_nonce,
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1
    }).on('select2:open', function (e) {
        bopobb_active_select = e.currentTarget;
    }).on("select2:selecting", function(e) {
        bopobb_active_select = e.currentTarget;
        bopobb_ResetDefault();
    }).on("select2:unselecting", function(e) {
        bopobb_active_select = e.currentTarget;
        bopobb_ResetDefault();
    });

    $(".bopobb-pbi-default-search").select2({
        closeOnSelect: true,
        placeholder: "Please fill in your product title",
        ajax: {
            url: "admin-ajax.php?action=bopobb_default_product",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    nonce: bopobbProductVars.bopobb_nonce,
                    keyword: params.term,
                    cat: $(this).closest('tbody').find('.bopobb-pbi-category.bopobb-pbi-category-search').val(),
                    ex_cat: $(this).closest('tbody').find('.bopobb-pbi-category-exclude.bopobb-pbi-category-search').val(),
                    tag: $(this).closest('tbody').find('.bopobb-pbi-tag.bopobb-pbi-tag-search').val(),
                    ex_tag: $(this).closest('tbody').find('.bopobb-pbi-tag-exclude.bopobb-pbi-tag-search').val(),
                    prod: $(this).closest('tbody').find('.bopobb-pbi-title.bopobb-pbi-product-search').val(),
                    ex_prod: $(this).closest('tbody').find('.bopobb-pbi-input .bopobb-pbi-title-exclude').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 0,
        allowClear: true
    });

    $(document).on('click touch', '.bopobb-pbi', function (e) {
        e.stopPropagation();
        if ($(this).hasClass('wc-metabox closed')) {
            $(this).find('.bopobb-pbi-slide-arrow').addClass('dashicons-arrow-down-alt2').removeClass('dashicons-arrow-up-alt2');
        } else {
            $(this).find('.bopobb-pbi-slide-arrow').removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
        }
    });

});