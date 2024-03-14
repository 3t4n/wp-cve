jQuery.noConflict();
var $parent_window;
jQuery(function ($) {
    $('.metabox-snippet-title .container-snippet .input').on('input', function () {
        $('.metabox-snippet-title .container-snippet .text').text($(this).val());
    }).trigger('input');

    var input = document.getElementById('metaseo_wpmseo_desc');
    input.addEventListener('input', resizeInput);
    resizeInput.call(input);

    function resizeInput() {
        this.style.width = this.value.length + "ch";
    }
});

function msClean(str) {
    if (str === '' || typeof (str) === 'undefined') {
        return '';
    }

    try {
        str = str.replace(/<\/?[^>]+>/gi, '');
        str = str.replace(/\[(.+?)](.+?\[\/\\1])?/g, '');
        str = jQuery('<div/>').html(str).text();
    } catch (e) {
    }

    return str;
}

function msReplaceVariables(str, callback) {
    if (typeof str === 'undefined') {
        return;
    }

    if (typeof ETBuilderBackendDynamic !== "undefined" && ETBuilderBackendDynamic.postTitle !== '') {
        str = str.replace(/%title%/g, ETBuilderBackendDynamic.postTitle.replace(/(<([^>]+)>)/ig, ''));
    } else if (jQuery(wpmsdivtitle).length) {
        str = str.replace(/%title%/g, jQuery(wpmsdivtitle).text().replace(/(<([^>]+)>)/ig, ''));
    }

    // These are added in the head for performance reasons.
    str = str.replace(/%id%/g, wpmseoMetaboxL10n.id);
    str = str.replace(/%date%/g, wpmseoMetaboxL10n.date);
    str = str.replace(/%sitedesc%/g, wpmseoMetaboxL10n.sitedesc);
    str = str.replace(/%sitename%/g, wpmseoMetaboxL10n.sitename);
    str = str.replace(/%sep%/g, wpmseoMetaboxL10n.sep);
    str = str.replace(/%page%/g, wpmseoMetaboxL10n.page);
    str = str.replace(/%currenttime%/g, wpmseoMetaboxL10n.currenttime);
    str = str.replace(/%currentdate%/g, wpmseoMetaboxL10n.currentdate);
    str = str.replace(/%currentday%/g, wpmseoMetaboxL10n.currentday);
    str = str.replace(/%currentmonth%/g, wpmseoMetaboxL10n.currentmonth);
    str = str.replace(/%currentyear%/g, wpmseoMetaboxL10n.currentyear);

    // excerpt
    var excerpt = '';
    if (jQuery('#excerpt').length) {
        excerpt = msClean(jQuery('#excerpt').val().replace(/(<([^>]+)>)/ig, ''));
        str = str.replace(/%excerpt_only%/g, excerpt);
    }
    if ('' === excerpt && jQuery('#content').length) {
        excerpt = jQuery('#content').val().replace(/(<([^>]+)>)/ig, '').substring(0, wpmseoMetaboxL10n.wpmseo_meta_desc_length - 1);
    }
    str = str.replace(/%excerpt%/g, excerpt);

    // parent page
    if (jQuery('#parent_id').length && jQuery('#parent_id option:selected').text() !== wpmseoMetaboxL10n.no_parent_text) {
        str = str.replace(/%parent_title%/g, jQuery('#parent_id option:selected').text());
    }

    // remove double separators
    var esc_sep = msEscapeFocusKw(wpmseoMetaboxL10n.sep);
    var pattern = new RegExp(esc_sep + ' ' + esc_sep, 'g');
    str = str.replace(pattern, wpmseoMetaboxL10n.sep);

    if (str.indexOf('%') !== -1 && str.match(/%[a-z0-9_-]+%/i) !== null) {
        var regex = /%[a-z0-9_-]+%/gi;
        var matches = str.match(regex);
        for (var i = 0; i < matches.length; i++) {
            if (typeof (replacedVars[ matches[ i ] ]) === 'undefined') {
                // str = str.replace(matches[ i ], replacedVars[ matches[ i ] ]);
            } else {
                var replaceableVar = matches[ i ];

                // create the cache already, so we don't do the request twice.
                replacedVars[ replaceableVar ] = '';
                msAjaxReplaceVariables(replaceableVar, callback);
            }
        }
    }
    callback(str);
}

function msAjaxReplaceVariables(replaceableVar, callback) {
    jQuery.post(ajaxurl, {
        action: 'wpmseo_replace_vars',
        string: replaceableVar,
        post_id: jQuery('#post_ID').val(),
        _wpnonce: wpmseoMetaboxL10n.wpmseo_replace_vars_nonce
    }, function (data) {
        if (data) {
            replacedVars[ replaceableVar ] = data;
        }

        msReplaceVariables(replaceableVar, callback);
    });
}

/*
 * Change meta title in meta box
 */
function msUpdateTitle(force) {
    var title = '';
    var titleElm = $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'title');
    if (!titleElm.length) {
        return;
    }
    var titleLengthError = $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'title-length-warning');
    var divHtml = jQuery('<div />');

    if (titleElm.val()) {
        title = titleElm.val().replace(/(<([^>]+)>)/ig, '');
    } else if (wpmseoMetaboxL10n.metatitle_tab === '1') {
        title = divHtml.html(title).text();
    }

    if (title === '') {
        var len = wpmseoMetaboxL10n.wpmseo_meta_title_length - $parent_window.find('#metaseo_snippet_title').val().length;
        metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'title-length');

        titleLengthError.hide();
        //return;
    }

    title = msClean(title);
    title = divHtml.text(title).html();

    if (force) {
        titleElm.val(title);
    }

    msReplaceVariables(title, function (title) {

        title = msSanitizeTitle(title);
        // do the placeholder
        var placeholder_title = divHtml.html(title).text();
        $parent_window.find('#metaseo_snippet_title').val(placeholder_title);
        var len = wpmseoMetaboxL10n.wpmseo_meta_title_length - $parent_window.find('#metaseo_snippet_title').val().length;
        metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'title-length');
    });
}

/*
 * Change meta keywords in meta box
 */
function msUpdateKeywords() {
    var keywordsElm = $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'keywords');
    if (keywordsElm.val() !== '') {
        var len = wpmseoMetaboxL10n.wpmseo_meta_keywords_length - keywordsElm.val().length;
        metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'keywords-length');
        $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'keywords-length').html(len);
    } else {
        $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'keywords-length').addClass('length-true').removeClass('length-wrong').html('<span class="good">' + wpmseoMetaboxL10n.wpmseo_meta_keywords_length + '</span>');
    }
}

/*
 * Clean title
 */
function msSanitizeTitle(title) {
    title = msClean(title);
    return title;
}

/*
 * Change meta description in meta box
 */
function msUpdateDesc() {
    var desc = (msClean($parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'desc').val())).trim();
    var divHtml = jQuery('<div />');
    var snippet = $parent_window.find('#wpmseosnippet');

    if (desc === '' && wpmseoMetaboxL10n.wpmseo_desc_template !== '') {
        desc = wpmseoMetaboxL10n.wpmseo_desc_template;
    }

    if (desc !== '') {
        msReplaceVariables(desc, function (desc) {
            desc = divHtml.text(desc).html();
            desc = msClean(desc);

            var len = wpmseoMetaboxL10n.wpmseo_meta_desc_length - desc.length;
            metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'desc-length');
            desc = msSanitizeDesc(desc);

            // Clear the autogen description.
            snippet.find('.desc span.autogen').html('');
            // Set our new one.
        });
    } else {
        var len = wpmseoMetaboxL10n.wpmseo_meta_desc_length;
        metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'desc-length');
    }
}

/*
 * Sanitize description
 */
function msSanitizeDesc(desc) {
    desc = msTrimDesc(desc);
    return desc;
}

function msTrimDesc(desc) {
    if (desc.length > wpmseoMetaboxL10n.wpmseo_meta_desc_length) {
        var space;
        if (desc.length > wpmseoMetaboxL10n.wpmseo_meta_desc_length) {
            space = desc.lastIndexOf(' ', (wpmseoMetaboxL10n.wpmseo_meta_desc_length - 3));
        } else {
            space = wpmseoMetaboxL10n.wpmseo_meta_desc_length;
        }
        desc = desc.substring(0, space).concat(' ...');
    }
    return desc;
}

/*
 * Update Url
 */
function msUpdateURL() {
    var url;
    if ($parent_window.find('#editable-post-name-full').length) {
        var name = $parent_window.find('#editable-post-name-full').text();
        url = wpmseoMetaboxL10n.wpmseo_permalink_template.replace('%postname%', name).replace('http://', '');
    }

    $parent_window.find('#wpmseosnippet').find('.url').html(url);
}

function msUpdateSnippet() {
    if (typeof wpmseoMetaboxL10n.show_keywords !== "undefined" && parseInt(wpmseoMetaboxL10n.show_keywords) === 1) {
        msUpdateKeywords();
    }
    msUpdateURL();
    msUpdateTitle();
    msUpdateDesc();
}

function msEscapeFocusKw(str) {
    return str.replace(/[\-\[\]\/\{}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&');
}

function metaseo_status_length(len, id, number) {
    console.log(len, id);
    var num = 46;
    var check = 0;
    var mclass = '';
    if (id === '#metaseo_wpmseo_title-length') {
        num = 50;
        check = wpmseoMetaboxL10n.wpmseo_meta_title_length - len;
        mclass = 'word-74B6FC';
    } else if (id === '#metaseo_wpmseo_desc-length') {
        num = 120;
        check = wpmseoMetaboxL10n.wpmseo_meta_desc_length - len;
        mclass = 'word-74B6FC';
    } else if (id === '#metaseo_wpmseo_keywords-length') {
        num = 120;
        check = len;
    }

    if (len < 0) {
        $parent_window.find(id).addClass('length-wrong').removeClass('length-true length-warn ' + mclass);
        len = '<span class="wrong">' + len + '</span>';
    }  else if (check >= 0 && check <= num) {
        $parent_window.find(id).addClass('length-warn ' + mclass).removeClass('length-true length-wrong');
        len = '<span class="length-warn '+mclass+'">' + len + '</span>';
    } else {
        $parent_window.find(id).addClass('length-true').removeClass('length-wrong length-warn ' + mclass);
        len = '<span class="good">' + len + '</span>';
    }
    console.log($parent_window, $parent_window.find(id).length);
    $parent_window.find(id).html(len);
}

(function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

var replacedVars = [];  // jshint ignore:line
var wpmsdivtitle = '';
jQuery(document).ready(function ($) {
    function wpms_load_js() {
        // title
        if (typeof wp.blocks !== "undefined" && wp.data.select('core/editor') !== null) {
            wpmsdivtitle = '.editor-post-title__input';
        } else {
            wpmsdivtitle = '#title';
        }

        $parent_window.find('.wpmseo-heading').hide();

        $.fn.focusTextToEnd = function(){
            this.focus();
            var $thisVal = this.val();
            this.val('').val($thisVal);
            return this;
        }

        $parent_window.find('.snippet-preview').on('click', function(e){
            e.preventDefault();
        });

        $parent_window.find('#metaseo_snippet_title').on('focus', function () {
            $(this).hide();
            $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'title').removeAttr('type').focusTextToEnd();
        });

        $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'title').on('focusout', function () {
            $(this).attr('type', 'hidden');
            $parent_window.find('#metaseo_snippet_title').show();
        })

        $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'title').keyup(function () {
            msUpdateTitle();
        });

        $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'keywords').keyup(function () {
            msUpdateKeywords();
        });

        $parent_window.find('body').on('keyup', wpmsdivtitle, function(event) {
            if ($parent_window.find('#metaseo_wpmseo_title').data('firstcreatepost')) {
                if (typeof wp.blocks !== "undefined" && wp.data.select('core/editor') !== null) {
                    $parent_window.find('#metaseo_wpmseo_title').val($parent_window.find(wpmsdivtitle).text().replace(/(<([^>]+)>)/ig, ''));
                } else {
                    $parent_window.find('#metaseo_wpmseo_title').val($parent_window.find(wpmsdivtitle).val().replace(/(<([^>]+)>)/ig, ''));
                }
            }
            msUpdateTitle();
            msUpdateDesc();
        });

        $parent_window.find('#parent_id').change(function () {
            msUpdateTitle();
            msUpdateDesc();
        });

        // DON'T 'optimize' this to use descElm! descElm might not be defined and will cause js errors (Soliloquy issue)
        $parent_window.find('#' + wpmseoMetaboxL10n.field_prefix + 'desc').keyup(function () {
            msUpdateDesc();
        });

        $parent_window.find('.gsc_keywords_time_filter select').change(function () {
            wpms_filter_search_keywords();
        });

        // Call ajax to search keywords
        function wpms_filter_search_keywords() {
            var time = $parent_window.find('select[name="gsc_keywords_time_filter_select"] option:selected').val();
            var keycsl = '';
            if (typeof $parent_window.find('input.wpms-search-key-csl').val() !== 'undefined') {
                keycsl = $parent_window.find('input.wpms-search-key-csl').val().trim().toLocaleString();
            }
            var searchType = $parent_window.find('select[name="filter_type_name"] option:selected').val();
            var postId = $('#post_ID').val();
            var showitems = [];
            $parent_window.find('input[name="filter-row-name"]:checked').each(function () {
                showitems.push(this.value);
            });

            var sorteditem = $parent_window.find("#gsc_keywords_table label.sorted");
            var dataSort = '';
            if (sorteditem.length > 0) {
                dataSort = JSON.stringify([sorteditem.attr('data-sort'), sorteditem.attr('data-order')]);
            }

            $parent_window.find('table#gsc_keywords_table tbody#the-list').html('<tr><td colspan="6" class="td-page-loader"><img class="page-loader-loadmore" src="'+wpmseoMetaboxL10n.image_loader+'"></td></tr>');

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wpms_filter_search_keywords',
                    time : time,
                    postId: postId,
                    showitems : showitems,
                    keycsl : keycsl,
                    searchType : searchType,
                    dataSort : dataSort,
                    wpms_nonce: wpms_localize.wpms_nonce
                },
                success: function (res) {
                    $parent_window.find('.wpms_load_more_keyword td.td-page-loader').remove();
                    if(res.status){
                        $parent_window.find('.wpms_load_more_keyword').data('page',2);
                        $parent_window.find('.wpms_list_gsc_keywords tr').remove();
                        $parent_window.find('.wpms_list_gsc_keywords').append(res.html);
                    } else {
                        $parent_window.find('.wpms_list_gsc_keywords').html('<tr><td colspan="6">'+wpmseoMetaboxL10n.keyword_filter_return+'</td></tr>');
                    }
                }
            });
        }

        $parent_window.find('#wpms-csl-search-submit').on('click', function (e) {
            var keycsl = $parent_window.find('.wpms-search-key-csl').val().trim().toLocaleString();

            if (keycsl === '') {
                $parent_window.find('#wpms-search-input-csl').focus();
            }

            wpms_filter_search_keywords();
        });

        $parent_window.find('select[name="filter_type_name"]').change(function(){
            var select = $(this).val();
            var placeholder = 'Add keywords like "Apple pie recipe..."';
            if (select === 'page') {
                placeholder = 'Paste URL to fetch the Google Search keywords';
            }

            $parent_window.find(".icons-key-csl-search").removeClass('obtain');
            $parent_window.find("#wpms-search-input-csl").val('').attr('placeholder', placeholder).focus();
        });

        $parent_window.find('#wpms-search-input-csl').on('keyup', function() {
            var keycsl = $(this).val();

            if (keycsl === '') {
                $parent_window.find(".icons-key-csl-search").removeClass('obtain');
            } else {
                $parent_window.find("#wpms-search-input-csl").removeClass('obtain');
                $parent_window.find(".icons-key-csl-search").addClass('obtain');
            }
        });

        // CHeck is google console connected
        if (typeof wpmseoMetaboxL10n.keyword_console_connected !== "undefined" && parseInt(wpmseoMetaboxL10n.keyword_console_connected) === 1) {
            addLinkToSearchBox('page-load');
        }

        $parent_window.find('.icons-key-csl-search').on('click', function() {
            addLinkToSearchBox();
            $parent_window.find("#wpms-search-input-csl").focus();
            $parent_window.find('select[name="filter_type_name"]').val('page');
            return false;
        });

        function addLinkToSearchBox(action)
        {
            var link = $parent_window.find('#wp-admin-bar-view .ab-item');
            var prelink = $parent_window.find('#wp-admin-bar-preview .ab-item');
            var newlink = $parent_window.find('.is-link');

            if (link.length || prelink.length) {
                if (link.length) {
                    $parent_window.find("#wpms-search-input-csl").val(link.attr('href'));
                }

                if (prelink.length) {
                    $parent_window.find("#wpms-search-input-csl").val(prelink.attr('href'));
                }
            } else {
                if (newlink.length) {
                    $parent_window.find("#wpms-search-input-csl").val(newlink.attr('href'));
                }
            }

            $parent_window.find(".icons-key-csl-search").addClass('obtain');

            if (action) {
                wpms_filter_search_keywords();
            }
        }

        // Show filter row
        $parent_window.find('.filter-row').on('click', function () {
            $parent_window.find('#filter-row-list').toggle('fade');
        });
        // Show filter row outside
        $(document).mouseup(function (e) {
            var container = $parent_window.find("#filter-row-list");
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide('fade');
            }
        });

        // Set filter row
        $parent_window.find('.filter-row-checkbox').on('click', function () {
            var showitems = [];
            $parent_window.find('input[name="filter-row-name"]:checked').each(function () {
                showitems.push(this.value);
            });
            $parent_window.find('#gsc_keywords_table tr >th:nth-child(4),#gsc_keywords_table tr >th:nth-child(5),#gsc_keywords_table tr >td:nth-child(4), #gsc_keywords_table tr >td:nth-child(5)').hide();


            if (showitems.length > 0) {
                $.each(showitems, function (i, showitem) {
                    $parent_window.find('#gsc_keywords_table tr >th:nth-child('+showitem+'), #gsc_keywords_table tr >td:nth-child('+showitem+')').show();
                });
            }
        });

        function isGutenbergActive() {
            return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
        }

        // CHeck is google console connected
        if (typeof wpmseoMetaboxL10n.keyword_console_connected !== "undefined" && parseInt(wpmseoMetaboxL10n.keyword_console_connected) === 1) {
            // Save post edit gutenberg
            if (isGutenbergActive()) {
                wp.data.subscribe(function () {
                    var isSavingPost = wp.data.select('core/editor').isSavingPost();
                    var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();

                    if (isSavingPost && !isAutosavingPost) {
                        wpms_filter_search_keywords();
                    }
                });
            }
        }

        // Enter action
        $(document).on("keypress","#wpms-search-input-csl", function (e) {
            if(e.which === 13){
                e.preventDefault();
                wpms_filter_search_keywords();
            }
        });

        // Sort table
        $parent_window.find('#gsc_keywords_table label.gsc-sort-by').on('click', function() {
            $parent_window.find('#gsc_keywords_table label.gsc-sort-by').not($(this)).removeClass('sorted down up');

            if ($(this).hasClass('down')) {
                $(this).removeClass('down').addClass('sorted up').attr('data-order', 'ascending');
            } else {
                $(this).removeClass('up').addClass('sorted down').attr('data-order', 'descending');
            }

            var page = $parent_window.find('#gsc_keywords_table tfoot').data('page');
            if (typeof page === 'undefined') {
                page = 2;
            }
            wpms_filter_search_keywords_more(parseInt(page) - 1, 1);
        });


        $parent_window.find('#gsc_keywords_table tfoot').on('click', function () {
            var page = $(this).data('page');
            wpms_filter_search_keywords_more(page, 2);
        });

        function wpms_filter_search_keywords_more(page, type) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            var $this = $parent_window.find('#gsc_keywords_table tfoot');
            var time = $parent_window.find('select[name="gsc_keywords_time_filter_select"] option:selected').val();
            var keycsl = $parent_window.find('input.wpms-search-key-csl').val().trim().toLocaleString();
            var searchType = $parent_window.find('select[name="filter_type_name"] option:selected').val();
            var postId = $('#post_ID').val();
            var showitems = [];
            $parent_window.find('input[name="filter-row-name"]:checked').each(function () {
                showitems.push(this.value);
            });

            var sorteditem = $parent_window.find("#gsc_keywords_table label.sorted");
            var dataSort = '';
            if (sorteditem.length > 0) {
                dataSort = JSON.stringify([sorteditem.attr('data-sort'), sorteditem.attr('data-order')]);
            }
            var loadder = '<tr><td colspan="6" class="td-page-loader"><img class="page-loader-loadmore" src="'+wpmseoMetaboxL10n.image_loader+'"></td></tr>';
            if (type === 1) {
                // Sort loader
                $parent_window.find('table#gsc_keywords_table tbody#the-list').html(loadder);
            } else {
                $parent_window.find('table#gsc_keywords_table tbody#the-list').append(loadder);
            }
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wpms_filter_search_keywords',
                    time : time,
                    postId: postId,
                    page:page,
                    showitems : showitems,
                    keycsl : keycsl,
                    searchType : searchType,
                    dataSort : dataSort,
                    wpms_nonce: wpms_localize.wpms_nonce
                },
                success: function (res) {
                    if(res.status){
                        $this.data('page',res.page);
                        $parent_window.find('.wpms_list_gsc_keywords tr').remove();
                        $parent_window.find('.wpms_list_gsc_keywords').append(res.html);
                    } else {
                        $parent_window.find('.wpms_list_gsc_keywords').html('<tr><td colspan="6">'+wpmseoMetaboxL10n.keyword_filter_return+'</td></tr>');
                    }
                }
            });
        }

        // Set time out because of tinymce is initialized later then this is done
        setTimeout(
            function () {
                msUpdateSnippet();

                // Adding events to content and excerpt
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content') !== null) {
                    tinyMCE.get('content').on('blur', msUpdateDesc);
                }

                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('excerpt') !== null) {
                    tinyMCE.get('excerpt').on('blur', msUpdateDesc);
                }
            },
            500
        );

        tippy('.metaseo_help, .metaseo_tool', {
            animation: 'scale',
            duration: 0,
            arrow: false,
            placement: 'top',
            theme: 'metaseo-tippy tippy-rounded',
            onShow(instance) {
                instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
                instance.setContent(instance.reference.dataset.tippy);
            }
        });

        $parent_window.find('#metaseo_wpmseo_title, #metaseo_wpmseo_desc, #metaseo_snippet_title').on('mouseover', function () {
            $(this).addClass('wpms-mouseover-frame ');
        }).on('mouseout', function () {
            $(this).removeClass('wpms-mouseover-frame ');
        });
    }

    if ($(window.document).find('#seo_et_ignore_iframe').length > 0) {
        var load_only = true;
        if ($('#wpseo-settings-bar-root').length > 0) {
            $parent_window = $(window.parent.document);
            $parent_window.find('#wpseo-settings-bar-root').on('click', function () {
                if (load_only) {
                    load_only = false;
                    wpms_load_js();
                }
            });
        }
    }
});

(function ($) {
    $(document).on("click",".editor-post-permalink-editor__save", function () {
        var url;
        if ($('.editor-post-permalink-editor__edit').length) {
            var slug = $('.editor-post-permalink-editor__edit').val();
            url = wpmseoMetaboxL10n.wpmseo_permalink_template.replace('%postname%', slug).replace('http://', '');
        }

        $('#wpmseosnippet').find('.url').html(url);
    });

    $(document).on("keypress",".editor-post-permalink-editor__edit", function (e) {
        if(e.which === 13){
            var slug = $(this).val();
            url = wpmseoMetaboxL10n.wpmseo_permalink_template.replace('%postname%', slug).replace('http://', '');
            $('#wpmseosnippet').find('.url').html(url);
        }
    });

    // Custom bootstrap tagsinput when paste value
    $(document).on('paste', '.wpms-bootstrap-tagsinput > input', function(e) {
        let pasteData = e.originalEvent.clipboardData.getData('text');
        $(this).attr('size', pasteData.length);
    })
        .on('focusout', '.wpms-bootstrap-tagsinput > input', function (e) {
            $(this).attr('size', 1);
        });
})(jQuery);
