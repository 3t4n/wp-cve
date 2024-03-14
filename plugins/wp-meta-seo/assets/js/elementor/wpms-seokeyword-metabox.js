var elmContent = '';
var elmTitle = '';
(function (wpData, $) {
    const {select, subscribe} = wpData;

    class wpmsSpecificKeywords {
        constructor() {
            this.title = null;
            this.content = null;
            this.tagsElement = $('#metaseo_wpmseo_specific_keywords');
            this.metaTitleElement = $('#metaseo_wpmseo_title');
            this.metaDescElement = $('#metaseo_wpmseo_desc');
            this.listTags = [];
            this.resultAnalytics = null;
        }

        init = () => {
            // Get editor title
            if (typeof elmTitle !== 'undefined') {
                this.title = elmTitle;
            }
            // Get editor content
            if (typeof elmContent !== 'undefined') {
                this.content = elmContent;
            }

            this.bindEvents();
            this.tagsElement.on('itemAdded itemRemoved', this.bindEvents);
            this.metaTitleElement.on('keyup', this.bindEvents);
            this.metaDescElement.on('keyup', this.bindEvents);
        }

        bindEvents = () => {
            this.listTags = this.tagsElement.tagsinput('items');
            if (typeof this.listTags !== 'undefined' && typeof this.listTags.itemsArray !== 'undefined' && this.listTags.itemsArray !== '') {
                this.listTags = this.listTags.itemsArray;
            }

            this.analytics();
        }

        analytics = () => {
            this.resultAnalytics = {
                keyInTitle: this.collectKeywordsInTitle(),
                keyInContent: this.collectKeywordsInContent(),
                keyInContentHeading: this.collectKeywordsInHeading(),
                keyInMetaTitle: this.collectKeywordsInMetaTitle(),
                keyInMetaDescription: this.collectKeywordsInMetaDesc()
            }

            if (this.resultAnalytics !== null) {
                let discovered = false;
                Object.entries(this.resultAnalytics).forEach(entry => {
                    const [key, value] = entry;
                    if (value) discovered = true;
                    this.changeAnalyticsInfo(key, value);
                });

                this.editAnalyticsInfo(discovered);
            }
        }

        editAnalyticsInfo = (discovered) => {
            const seo_keywords = $("input#metaseo_wpmseo_specific_keywords").val();
            if (seo_keywords === '') {
                $('div.metaseo_analysis[data-title="seokeyword"]').hide();
                $('.seokeyword-information').hide();
                $('input.wpms_analysis_hidden[name="wpms[seokeyword]"]').val(0);
                this.reDrawInactive(7);
                return false;
            } else {
                $('div.metaseo_analysis[data-title="seokeyword"]').show();
                $('.seokeyword-information').show();
            }

            if (!discovered) {
                $('div.metaseo_analysis[data-title="seokeyword"]').find('i').removeClass('icons-mboxdone').addClass('icons-mboxwarning').html('error_outline');
                $('input.wpms_analysis_hidden[name="wpms[seokeyword]"]').val(0);
            } else {
                $('div.metaseo_analysis[data-title="seokeyword"]').find('i').removeClass('icons-mboxwarning').addClass('icons-mboxdone').html('done');
                $('input.wpms_analysis_hidden[name="wpms[seokeyword]"]').val(1);
            }

            this.reDrawInactive(8);
        }

        reDrawInactive = (totalItems) => {
            const analyticItems = $('.panel-left .wpms_analysis_hidden');
            let mcheck = 0;

            for (let i = 0; i < analyticItems.length; i++) {
                if ($(analyticItems[i]).val() == 1) {
                    mcheck++;
                }
            }

            const circliful = Math.ceil((mcheck * 100) / totalItems);

            $('#wpmetaseo_seo_keywords_result').val(circliful);
            $('.metaseo-progress-bar').circleProgress('value', circliful / 100).on('circle-animation-progress', function (event, progress) {
                $(this).find('strong').html(circliful + '<i>%</i>');
            });
        }

        // change material icon
        changeAnalyticsInfo = (key, value) => {
            if (value) {
                $('div.metaseo_analysis[data-title="' + key.toLowerCase() + '"]').find('i').removeClass('icons-mboxwarning').addClass('icons-mboxdone').html('done');
                $('input.wpms_analysis_hidden[name="wpms[' + key.toLowerCase() + ']"]').val(1);
            } else {
                $('div.metaseo_analysis[data-title="' + key.toLowerCase() + '"]').find('i').removeClass('icons-mboxdone').addClass('icons-mboxwarning').html('error_outline');
                $('input.wpms_analysis_hidden[name="wpms[' + key.toLowerCase() + ']"]').val(0);
            }
        }

        collectKeywordsInTitle = () => {
            let title = {text: this.title,};
            let isContain = false;
            //alert(this.listTags);
            if (this.listTags.length && title.text.length) {
                this.listTags.forEach(function (item) {
                    if (title.text.toLowerCase().includes(item.toLowerCase().trim())) {
                        isContain = true;
                        return isContain;
                    }
                });
            }

            return isContain;
        }

        collectKeywordsInContent = () => {
            let content = {text: this.content};
            let isContain = false;
            if (this.listTags.length && content.text.length) {
                this.listTags.forEach(function (item, index) {
                    if (content.text.toLowerCase().includes(item.toLowerCase().trim())) {
                        isContain = true;
                        return isContain;
                    }
                });
            }

            return isContain;
        }

        collectKeywordsInHeading = () => {
            let content = {text: this.content};
            let isContain = false;
            if (this.listTags.length && content.text.length) {
                this.listTags.forEach(function (item, index) {
                    const regex = RegExp("<h[2-6][^>]*>.*" + item.toLowerCase().trim() + ".*</h[2-6]>", "gi");
                    if (content.text.toLowerCase().match(regex) != null) {
                        isContain = true;
                        return isContain;
                    }
                });
            }

            return isContain;
        }

        collectKeywordsInMetaTitle = () => {
            let metaTitle = {text: this.replaceVariables(this.metaTitleElement.val())};
            let isContain = false;
            if (this.listTags.length && metaTitle.text.length) {
                this.listTags.forEach(function (item, index) {
                    if (metaTitle.text.toLowerCase().includes(item.toLowerCase().trim())) {
                        isContain = true;
                        return isContain;
                    }
                });
            }

            return isContain;
        }

        collectKeywordsInMetaDesc = () => {
            let metaDesc = {text: this.replaceVariables(this.metaDescElement.val())};
            let isContain = false;
            if (this.listTags.length && metaDesc.text.length) {
                this.listTags.forEach(function (item, index) {
                    if (metaDesc.text.toLowerCase().includes(item.toLowerCase().trim())) {
                        isContain = true;
                        return isContain;
                    }
                });
            }

            return isContain;
        }

        replaceVariables = (str) => {
            if (typeof str === 'undefined') {
                return;
            }

            if (this.title) {
                str = str.replace(/%title%/g, this.title.replace(/(<([^>]+)>)/ig, ''));
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
            str = str.replace(/%pagetotal%/g, wpmseoMetaboxL10n.pagetotal);
            str = str.replace(/%pagenumber%/g, wpmseoMetaboxL10n.pagenumber);
            str = str.replace(/%currentyear%/g, wpmseoMetaboxL10n.currentyear);

            // excerpt
            let excerpt = '';
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
            const esc_sep = wpmseoMetaboxL10n.sep.replace(/[\-\[\]\/\{}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&');
            const pattern = new RegExp(esc_sep + ' ' + esc_sep, 'g');
            str = str.replace(pattern, wpmseoMetaboxL10n.sep);

            return str;
        }
    }

    $(document).ready(function () {
        $(document).on('click', '#wpms-onelementor-tab', (e) => {
            if (typeof document.title !== 'undefined') {
                elmTitle = document.title;
            }
            elmTitle = elmTitle.replace('Elementor |', '').trim();

            // get content html from elementor frontend
            elmContent = elementor.$previewElementorEl.html();

            $(document).ajaxComplete(function (event, request, settings) {
                if (typeof settings.data !== "undefined" && settings.data.includes('action=wpms&task=reload_analysis')) {
                    new wpmsSpecificKeywords().init();
                }
            });
        });
    });
})(wp.data, jQuery);
