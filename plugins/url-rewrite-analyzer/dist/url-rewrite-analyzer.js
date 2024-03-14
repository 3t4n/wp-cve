jQuery(document).ready(function ($) {

    let $body = $('body');
    let adminClass = 'tools_page_url-rewrite-analyzer';
    let $spanRegexRepeater = $('span.regex-repeater');
    let $regexTester = $('#regex-tester');
    let $ClearButton = $('#_regex-search-bar .clear');
    let $ClearOldButton = $('#_regex-search-bar.clear');
    let $flushRewriteRules = $('#flush-rewrite-rules');
    let $scrollToTopWrapper = $('.urap-btt-wrapper');
    let $scrollToTopButton = $scrollToTopWrapper.find('button');
    let changeUIButton = $('#update-ui');
    let $radioButton = $('.modal-change-ui .ui-select');

    // Return if page invalid
    if (!$body.hasClass(adminClass)) { return; }

    // Highlight corresponding regex groups and their targets in the "Substitution" column
    $('span.regexgroup, span.regexgroup-target').hover(
        function () {
            let id = $(this)[0].id;
            if (id.substr(-7) == '-target') {
                id = id.substr(0, id.length - 7);
            }
            $('#' + id + ', #' + id + '-target').toggleClass('highlight');
        }
    );

    // Highlight the target of a repeater
    $spanRegexRepeater.on('hover', function () {
        let $this = $(this);
        let $parent = $this.parent();
        $parent.toggleClass('highlight');
    })

    let idxFirstMatchedRewriteRule = null;
    $regexTester.keyup(function () {
        let url = $(this).val();
        if (url == '') {
            // Empty box, show all rules
            $('.rewrite-rule-line').removeClass('rewrite-rule-matched rewrite-rule-matched-first rewrite-rule-unmatched');
            return;
        }

        let matchedRules = {};
        let result;
        let isFirst = true;
        for (let idx in Rewrite_Analyzer_Regexes) {
            if (result = Rewrite_Analyzer_Regexes[idx].exec(url)) {
                // If it is a match, show it
                matchedRules[idx] = result;
                let elRule = $('#rewrite-rule-' + idx).addClass('rewrite-rule-matched').removeClass('rewrite-rule-unmatched');

                // Fill in the corresponding query values
                for (let rIdx = 0; rIdx < result.length; rIdx++) {
                    $('#regex-' + idx + '-group-' + rIdx + '-target-value').html(result[rIdx] || '');
                }

                if (isFirst) {
                    // If it is the first match, highlight it
                    elRule.addClass('rewrite-rule-matched-first');
                    isFirst = false;
                    if (idxFirstMatchedRewriteRule != idx) {
                        // The previous first match is not longer the first match
                        $('#rewrite-rule-' + idxFirstMatchedRewriteRule).removeClass('rewrite-rule-matched-first');
                        idxFirstMatchedRewriteRule = idx;
                    }
                }
            } else {
                // If it is not a match, hide it
                $('#rewrite-rule-' + idx).removeClass('rewrite-rule-matched').addClass('rewrite-rule-unmatched');
            }
        }
    });

    // Clear the tester and show all rules
    $ClearButton.on('click', function () {
        $regexTester.val('');
        $('.rewrite-rule-line').removeClass('rewrite-rule-matched rewrite-rule-matched-first rewrite-rule-unmatched');
    });
    // Clear the tester and show all rules
    $ClearOldButton.on('click', function () {
        $regexTester.val('');
        $('.rewrite-rule-line').removeClass('rewrite-rule-matched rewrite-rule-matched-first rewrite-rule-unmatched');
    });

    // Compile all regexes
    for (let idx in Rewrite_Analyzer_Regexes) {
        let pattern = Rewrite_Analyzer_Regexes[idx];
        let regex = new RegExp('^' + pattern);
        Rewrite_Analyzer_Regexes[idx] = regex;
    }

    // Flush rewrite rules
    $flushRewriteRules.on('click', function () {
        let $this = $(this);
        $this.addClass('rotate');
        // Ajax Call
        $.post({
            url: admin.ajax_url,
            data: {
                action: "refresh_permalinks",
            },
            success: function (response) {
                let answer = response.data;
                if (answer) {
                    $this.find('span').html('Permalinks refreshed');
                    $this.removeClass('rotate');
                }
                setTimeout(() => {
                    $this.find('span').html('Refresh permalinks');
                    $this.removeClass('rotate');
                }, 1000);

            },
        });
    })

    // BackToTop button
    $(window).on('scroll', function () {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            $scrollToTopWrapper.addClass('open');
        } else {
            $scrollToTopWrapper.removeClass('open');
        }
    })
    $scrollToTopButton.on('click', function () {
        $(window).scrollTop(0);
    })

    // Change UI
    changeUIButton.on('click', function () {
        let $this = $(this);
        let $modal = $this.closest('.wrap').find('.modal-change-ui');
        let $head_wrapper = $modal.next();
        $modal.toggleClass('open');
        $head_wrapper.toggleClass('open');
    })

    let UIValue =  $('.modal-change-ui .ui-select.selected').next().val();
    $body.addClass(UIValue);
    
    // Radio Button
    $radioButton.click(function () {
        let val = $(this).next().val();
        $radioButton.not($(this)).removeClass('selected');
        $(this).addClass('selected');
        // Ajax Call
        $.post({
            url: admin.ajax_url,
            data: {
                action: "change_ui",
                style: val,
            },
            success: function (response) {
                window.location.reload();
            },
        });
    })

});
