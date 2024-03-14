/*jslint browser: true*/
/*global cmf_data, console, document, jQuery*/
var CM_Footnote = {};
/*
 * Inside this closure we use $ instead of jQuery in case jQuery is reinitialized again before document.ready()
 */
(function ($) {
    $(document).ready(function () {
        const behaviorSettings = cmf_data.behavior_settings;
        const isLicensed = cmf_data.isLicensed ;

        CM_Footnote.getFootnoteData = function (element_id) {
            var footnoteContentBlock = $('#' + element_id);
            var content = '';
            var title = footnoteContentBlock.data('footnote-title');
            var img = footnoteContentBlock.find(".cmf_footnote_img");

            if (img.length) {
                img = img[0].outerHTML;
            } else {
                img = null;
            }

            if (footnoteContentBlock) {
                var parent = footnoteContentBlock.closest(".cmfSimpleFootnoteDefinitionItem");
                if (parent.length) {
                    content = parent.find(".cmfSimpleFootnoteDefinitionItemContent").html();
                } else {
                    content = footnoteContentBlock.find(".cmf_footnote_description").html();
                }
            }

            return {content, title, img};
        };



        CM_Footnote.linkClickHandler = function (e) {
            e.preventDefault();
            var link = $(this);
            var href = link.attr('href');
            var ftid = href.substring(1, href.length);
            var parentSpan = $(link).parent().parent() ;
            var parentSpanId = $(parentSpan).attr('id') ;
            var defLinkId = parentSpanId.slice(0,parentSpanId.length-2) ; // cleared parent id
            var backLinkId = '#'+defLinkId ;  // def block id

            $('.cmf_has_footnote_custom').find('a').css('background-color', '');
            $('.cmf_has_footnote_custom').find('sup').css('background-color', '');

            var linkAddon = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
            var callBackId = defLinkId + '-' + linkAddon ;
            $(parentSpan).attr('id',callBackId) ;
            $(backLinkId).find('a').attr('href','#'+callBackId) ;


            $('.cmfSimpleFootnoteDefinitionItem').css('background-color', '');
            $('.cmf_footnote_row').css('background-color', '');
            var footnoteContentBlock = $('.' + ftid);
            if (!footnoteContentBlock.length) {
                footnoteContentBlock = $('#' + ftid);
            }
            footnoteContentBlock.css('background-color', '#eaf3ff');
            if (footnoteContentBlock.length) {
                $('html, body').animate({
                    scrollTop: footnoteContentBlock.offset().top - 50
                }, 500);
            }


        }


        //footnote type post and highlighting footnote content
        $(document).on("click", ".cmf_simple_footnote_link", CM_Footnote.linkClickHandler);

    });
}(jQuery));
