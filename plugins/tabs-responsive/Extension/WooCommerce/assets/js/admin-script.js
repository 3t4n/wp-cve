jQuery(document).ready(function ($) {
    "use strict";


    $('.row-actions .edit a, .page-title-action, .column-title .row-title').on('click', function (e) {
        e.preventDefault();
        var id = 0;
        var modal = $('#responsive_woo_tabs_modal');
        var parent = $(this).parents('.column-title');

        modal.addClass('loading');
        modal.modal('show');
        if (parent.length > 0) {

            id = parent.find('.hidden').attr('id').split('_')[1];

            $.get(window.responsivewootabsultimate.restapi + 'woo_tabs_single_data', {id: id, _wpnonce: window.responsivewootabsultimate.nonce}, function (data) {
                ResponsiveWooTabsEditor(data);
                modal.removeClass('loading');
            });
        } else {

            var data = {
                title: '',
                priority: 20,
                activation: 'yes',
                condition: 'entire_site',
                singular_id: '',
                archive: 'products_cat',
                products_cat: '',
                products_tags: '',

            };
            ResponsiveWooTabsEditor(data);
            modal.removeClass('loading');
        }
        var url = modal.attr('data-editor-url') + 'post.php?post=' + id + '&action=edit';
        $('.open-data-btn-editor').attr('data-editor-url', url);
        $('#responsive-woo-tabs-id').val(id);




    });

    function ShortcodeCcontrolTabsSelect() {
        var responsive_woo_tabs_c = $('.responsive_woo_tabs_c').val();

        if (responsive_woo_tabs_c == 'singular') {
            $('.responsive_woo_tabs_singular_id-container').show();
            $('.responsive_woo_tabs_archive-container').hide();
            $('.responsive_woo_tabs_archive_author_id-container').hide();
            $('.responsive_woo_tabs_archive_cat_id-container').hide();
            $('.responsive_woo_tabs_archive_tags_id-container').hide();
        } else if (responsive_woo_tabs_c == 'archive') {
            $('.responsive_woo_tabs_singular_id-container').hide();
            $('.responsive_woo_tabs_archive-container').show();
            var condition = $('.responsive_woo_tabs_archive').val();
            if (condition == 'products_cat') {
                $('.responsive_woo_tabs_archive_author_id-container').hide();
                $('.responsive_woo_tabs_archive_cat_id-container').show();
                $('.responsive_woo_tabs_archive_tags_id-container').hide();
            } else if (condition == 'products_tags') {
                $('.responsive_woo_tabs_archive_author_id-container').hide();
                $('.responsive_woo_tabs_archive_cat_id-container').hide();
                $('.responsive_woo_tabs_archive_tags_id-container').show();
            }
        } else {
            $('.responsive_woo_tabs_singular_id-container').hide();
            $('.responsive_woo_tabs_archive-container').hide();
            $('.responsive_woo_tabs_archive_author_id-container').hide();
            $('.responsive_woo_tabs_archive_cat_id-container').hide();
            $('.responsive_woo_tabs_archive_tags_id-container').hide();

        }
    }
    ;

    $('.shortcode-control-type-text select').on('change', function () {
        ShortcodeCcontrolTabsSelect();
    });


    $('.open-data-btn-editor').on('click', function () {
        var link = $(this).attr('data-editor-url');
        window.location.href = link;
    });

    $('#responsive_woo_tabsinput-form').on('submit', function (e) {
        e.preventDefault();
        var modal = $('#addons_headerfooter_modal');
        modal.addClass('loading');
        var form_data = $(this).serialize();

        $.get(window.responsivewootabsultimate.restapi + 'tabsupdate/', form_data, function (output) {
            location.reload();
        });

    });

    $('.responsive_woo_tabs_singular_id').select2({
        ajax: {
            url: window.responsivewootabsultimate.restapi + 'woo_product_name',
            dataType: 'json',
            data: function (params) {
                var query = {
                    qu: params.term,
                    _wpnonce: window.responsivewootabsultimate.nonce
                }
                return query;
            }
        },
        width: '100%',
        cache: true,
        placeholder: "--",
    });

    $('.responsive_woo_tabs_archive_cat_id').select2({
        ajax: {
            url: window.responsivewootabsultimate.restapi + 'woo_cat_name',
            dataType: 'json',
            data: function (params) {
                var query = {
                    _wpnonce: window.responsivewootabsultimate.nonce
                }
                return query;
            }
        },
        width: '100%',
        cache: true,
        placeholder: "--",
    });

    $('.responsive_woo_tabs_archive_tags_id').select2({
        ajax: {
            url: window.responsivewootabsultimate.restapi + 'woo_tag_name',
            dataType: 'json',
            data: function (params) {
                var query = {
                    _wpnonce: window.responsivewootabsultimate.nonce
                }
                return query;
            }
        },
        width: '100%',
        cache: true,
        placeholder: "--",
    });

    function ResponsiveWooTabsEditor(data) {




        $('.responsive_woo_tabsinput-title').val(data.title);
        $('.responsive_woo_tabsinput-priority').val(data.priority);
        $('.responsive_woo_tabs_c').val(data.condition).change();

        $('.responsive_woo_tabs_archive').val(data.archive).change();
        var activation_input = $('.responsive_woo_tabsinput-activition');
        if (data.activation == 'yes') {
            activation_input.attr('checked', true);
        } else {
            activation_input.removeAttr('checked');
        }

        $('.responsive_woo_tabsinput-activition,  .responsive_woo_tabsinput-priority').trigger('change');


        ShortcodeCcontrolTabsSelect();


        if (data.singular_id !== null && data.singular_id.length > 0) {
            console.log(data.singular_id.length);
            var el = $('.responsive_woo_tabs_singular_id');
            $.ajax({
                url: window.responsivewootabsultimate.restapi + 'woo_product_name',
                dataType: 'json',
                data: {
                    ids: String(data.singular_id),
                    _wpnonce: window.responsivewootabsultimate.nonce
                }
            }).then(function (data) {

                if (data !== null && data.results.length > 0) {
                    el.html(' ');
                    $.each(data.results, function (i, v) {
                        var option = new Option(v.text, v.id, true, true);
                        el.append(option).trigger('change');
                    });
                    el.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                }
            });
        } else {
            $('.responsive_woo_tabs_singular_id').val(null).trigger('change');
        }



        if (data.products_cat !== null && data.products_cat.length > 0) {

            var el = $('.responsive_woo_tabs_archive_cat_id');
            $.ajax({
                url: window.responsivewootabsultimate.restapi + 'woo_cat_name',
                dataType: 'json',
                data: {
                    ids: String(data.products_cat),
                    _wpnonce: window.responsivewootabsultimate.nonce
                }
            }).then(function (data) {

                if (data !== null && data.results.length > 0) {
                    el.html(' ');
                    $.each(data.results, function (i, v) {
                        var option = new Option(v.text, v.id, true, true);
                        el.append(option).trigger('change');
                    });
                    el.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                }
            });
        }
        if (data.products_tags !== null && data.products_tags.length > 0) {
            var el = $('.responsive_woo_tabs_archive_tags_id');
            $.ajax({
                url: window.responsivewootabsultimate.restapi + 'woo_tag_name',
                dataType: 'json',
                data: {
                    ids: String(data.products_tags),
                    _wpnonce: window.responsivewootabsultimate.nonce
                }
            }).then(function (data) {

                if (data !== null && data.results.length > 0) {
                    el.html(' ');
                    $.each(data.results, function (i, v) {
                        var option = new Option(v.text, v.id, true, true);
                        el.append(option).trigger('change');
                    });
                    el.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                }
            });
        }
       
    }








});