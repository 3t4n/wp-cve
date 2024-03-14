(function ($) {
    'use strict';

    $(document).ready(function() {
        $('[data-type-select="redirect_product"]').select2({
            width: '50%',
            ajax: {
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: function(params) {
                    return {
                        s: params.term,
                        type: 'product',
                        action: 'get_posts_by_string',
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.post_title,
                                id: item.ID
                            }
                        })
                    }
                }
            }
        });

        $('[data-type-select="redirect_product_category"]').select2({
            width: '50%',
            ajax: {
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: function(params) {
                    return {
                        s: params.term,
                        type: 'product_cat',
                        action: 'get_posts_by_string',
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.term_id
                            }
                        })
                    }
                }
            }
        });

        $('[data-type-select="redirect_category"]').select2({
            width: '50%',
            ajax: {
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: function(params) {
                    return {
                        s: params.term,
                        type: 'category',
                        action: 'get_posts_by_string',
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.term_id
                            }
                        })
                    }
                }
            }
        });

        $('[data-type-select="redirect_post"]').select2({
            width: '50%',
            ajax: {
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: function(params) {
                    return {
                        s: params.term,
                        type: 'post',
                        action: 'get_posts_by_string',
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.post_title,
                                id: item.ID
                            }
                        })
                    }
                }
            }
        });

        $('[data-type-select="redirect_page"]').select2({
            width: '50%',
            ajax: {
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: function(params) {
                    return {
                        s: params.term,
                        type: 'page',
                        action: 'get_posts_by_string',
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.post_title,
                                id: item.ID
                            }
                        })
                    }
                }
            }
        });

        $('[data-url]').on("invalid", function(event) {
            event.preventDefault();
            $(this).closest('.form-field').addClass('form-invalid');
        });
    });

    $(document).on('change', '[name="redirect_type"]', function () {
        var $form = $(this).closest('form');
        var $allTabs = $form.find('.redirect_data');
        var $currentTab = $form.find('[data-type="redirect_' + this.value + '"]');

        $allTabs.hide();
        $allTabs.find('input').attr('disabled', '');

        $currentTab.show();
        $currentTab.find('input').removeAttr('disabled');
    });

    $('[data-pagination-number]').on('input',function(e){
        var value = $(this).val();

        if (value < 1) {
            $(this).val(1);
        }

        if (value > 999) {
            $(this).val(999);
        }
    });

    $(document).on('click', '[data-link="delete"]', function () {
        return confirm($('[data-lang-name="confirm-delete"]').attr('data-lang-value'));
    });
})(jQuery);
