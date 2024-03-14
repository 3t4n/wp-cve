"use strict";

(function($){
    $(document).ready(function () {
        /** Tabs */
        let activeIndex = $('.active-tab').index(),
            $contentList = $('.tabs-content section'),
            $tabsList = $('.tabs-list li');

        if ( location.hash.length > 0 ) {
            let currentIndex = $(`.tabs-list li[data-id="${location.hash.substr(1)}"]`).index();
            activeIndex = currentIndex > -1 ? currentIndex : activeIndex;

            $tabsList.removeClass('active-tab');
            $tabsList.eq(activeIndex).addClass('active-tab');
        }

        $contentList.eq(activeIndex).show();

        $('.tabs-list').on('click', 'li', function (e) {
            e.preventDefault();

            let $current = $(e.currentTarget),
                index = $current.index(),
                id = $current.data('id');

            $tabsList.removeClass('active-tab');
            $current.addClass('active-tab');
            $contentList.hide().eq(index).show();
            location.hash = id;
        });

        /** Radio */
        $("input[type='radio']:checked").parent().addClass('selected');
        $("input[type='radio']").on('change', function (e) {
            $(this).parents().siblings().removeClass('selected');
            $(this).parent().addClass('selected');
        });

        /** Autocomplete */
        $('.cew-autocomplete').each(function() {
            let $el = $(this);
            let target = $el.data('target');
            let terms = cew_get_terms(target);

            cew_render_terms(terms, target);

            $el.autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: cew.ajax_url,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'cew_autocomplete_search',
                            term: request.term
                        },
                        success: function (res) {
                            if (res?.success) {
                                response(res?.data);
                            } else {
                                response([{
                                    label: res?.message,
                                    value: 'false'
                                }])
                            }
                        }
                    });
                },
                minLength: 2,
                select: function (event, ui) {
                    terms = cew_get_terms(target);

                    if (terms.findIndex(el => el.value == ui.item.value) === -1) {
                        terms.push(ui.item);
                    }

                    cew_update_terms(terms, target);

                    this.value = '';
                    return false;
                }
            }).data('ui-autocomplete')._renderItem = function (ul, item) {
                if (item.value === 'false') {
                    return $('<li class="ui-state-disabled">' + item.label + '</li>').appendTo(ul);
                } else {
                    return $('<li>').append(item.label).appendTo(ul);
                }
            };
        });

        $(document).on('click', '.cew-autocomplete-terms span', function (e) {
            if (e.offsetX > $(this).width()) {
                let termValue = $(this).data('value');
                let target = $(this).data('target');
                let terms = cew_get_terms(target);

                if (termValue) {
                    terms = terms.filter(el => el.value != termValue)

                    cew_update_terms(terms, target);
                }
            }
        });

        function cew_get_terms(target) {
            let selector = $(`#${target}`)

            return JSON.parse(!selector.val() ? '[]' : selector.val());
        }

        function cew_update_terms(terms, target) {
            $(`#${target}`).val(JSON.stringify(terms));

            cew_render_terms(terms, target);
        }

        function cew_render_terms(terms, target) {
            $('.cew-autocomplete-terms').html('');

            terms.forEach(term => {
                $('.cew-autocomplete-terms').append(`<span data-value="${term.value}" data-target="${target}">${term.label}</span>`)
            });
        }

    });
})(jQuery);