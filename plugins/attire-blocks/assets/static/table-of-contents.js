(function ($) {

    let scroll = true;
    let scroll_offset = 30;
    let scroll_delay = 800;
    let scroll_element = null;
    let attributes = block_attr;

    let parseToCSlug = function (slug) {

        // If not have the element then return false!
        if (!slug) {
            return slug;
        }

        let parsedSlug = slug.toString().toLowerCase()
            .replace(/\…+/g, '')                             // Remove multiple …
            .replace(/\u2013|\u2014/g, '')                    // Remove long dash
            .replace(/&(amp;)/g, '')                        // Remove &
            .replace(/[&]nbsp[;]/gi, '-')                    // Replace inseccable spaces
            .replace(/[^a-z0-9 -_]/gi, '')                    // Keep only alphnumeric, space, -, _
            .replace(/&(mdash;)/g, '')                        // Remove long dash
            .replace(/\s+/g, '-')                            // Replace spaces with -
            .replace(/[&\/\\#,^!+()$~%.\[\]'":*?;-_<>{}@‘’”“|]/g, '')  // Remove special chars
            .replace(/\-\-+/g, '-')                        // Replace multiple - with single -
            .replace(/^-+/, '')                            // Trim - from start of text
            .replace(/-+$/, '');                         	// Trim - from end of text

        return decodeURI(encodeURIComponent(parsedSlug));
    };

    let ATBSTableOfContents = {

        init: function () {

            $(document).on("click", ".atbs-toc__list a", ATBSTableOfContents._scroll);
            $(document).on("click", '.atbs-toc__title-wrap', ATBSTableOfContents._toggleCollapse);
            this._run(block_attr);

        },

        hyperLinks: function () {
            let hash = window.location.hash.substring(0);
            if ('' === hash || (/[^a-z0-9_-]$/).test(hash)) {
                return;
            }
            let hashId = encodeURI(hash.substring(0));
            let selectedAnchor = document.querySelector(hashId);
            if (null === selectedAnchor) {
                return;
            }
            let node = $(document).find('.wp-block-atbs-table-of-contents');
            scroll_offset = node.data('offset');
            let offset = $(decodeURIComponent(hash)).offset();
            scroll_delay = node.data('delay');
            if ("undefined" != typeof offset) {
                $("html, body").animate({
                    scrollTop: (offset.top - scroll_offset)
                }, scroll_delay)
            }
        },

        _toggleCollapse: function (e) {
            if ($(this).find('.atbs-toc__collapsible-wrap').length > 0) {

                let $root = $(this).closest('.wp-block-atbs-table-of-contents');

                if ($root.hasClass('atbs-toc__collapse')) {
                    $('.atbs-toc__collapsible-wrap i').removeClass().addClass(attributes.collapseIndicator[1]);
                    $root.removeClass('atbs-toc__collapse');

                } else {
                    $('.atbs-toc__collapsible-wrap i').removeClass().addClass(attributes.collapseIndicator[0]);
                    $root.addClass('atbs-toc__collapse');
                }
            }
        },

        /**
         * Smooth Scroll.
         */
        _scroll: function (e) {

            if (this.hash !== "") {

                let hash = this.hash;
                let node = $(this).closest('.wp-block-atbs-table-of-contents');

                scroll = node.data('scroll');
                scroll_offset = node.data('offset');
                scroll_delay = node.data('delay');

                if (scroll) {

                    let offset = $(decodeURIComponent(hash)).offset();

                    if ("undefined" != typeof offset) {

                        $("html, body").animate({
                            scrollTop: (offset.top - scroll_offset)
                        }, scroll_delay)
                    }
                }

            }
        },

        /**
         * Alter the_content.
         */
        _run: function (attr, id) {

            let $this_scope = $(id);

            if ($this_scope.find('.atbs-toc__collapsible-wrap').length > 0) {
                $this_scope.find('.atbs-toc__title-wrap').addClass('atbs-toc__is-collapsible');
            }

            let allowed_h_tags = [];
            let allowed_h_tags_str = '';
            if (undefined !== attr.mappingHeaders) {

                attr.mappingHeaders.forEach(function (h_tag, index) {
                    (h_tag === true ? allowed_h_tags.push('h' + (index + 1)) : null);
                });
                allowed_h_tags_str = allowed_h_tags.length ? allowed_h_tags.join(',') : '';
            }

            let all_header = (undefined !== allowed_h_tags_str && '' !== allowed_h_tags_str) ? $('body').find(allowed_h_tags_str) : $('body').find('h1, h2, h3, h4, h5, h6');

            if (0 !== all_header.length) {

                let toc_list_wrap = $('.atbs-toc__list-wrap');

                all_header.each(function (index, value) {
                    let header = $(this);
                    let header_text = parseToCSlug(header.text());

                    if (header_text.length < 1) {
                        let list_heading = toc_list_wrap.find('a:contains("' + header.text() + '")');

                        if (list_heading.length > 0) {
                            header_text = list_heading.attr('href').replace(/#/g, '');
                        }
                    }
                    header.before('<span id="' + header_text + '" class="atbs-toc__heading-anchor"></span>');
                });
            }

            ATBSTableOfContents.hyperLinks();
        },
    }

    $(document).ready(function () {
        ATBSTableOfContents.init();
    })
})(jQuery)
