(function($) {
    var options = {
        isTbOpened: false,
        tabs : {
            searchResults: {
                name: 'rumble-results',
                xhr: null,
                loading: false,
                page: 1,
                lastPage: false,
                pageLimit: 10,
                bgColor: false,
		searchTime: 0 
            },
            editorPicks: {
                name: 'rumble-editor-picks',
                xhr: null,
                loading: false,
                page: 1,
                lastPage: false,
                pageLimit: 10,
                bgColor: false,
		searchTime: 0 
            },
            newestVideos: {
                name: 'rumble-newest-videos',
                xhr: null,
                loading: false,
                page: 1,
                lastPage: false,
                pageLimit: 10,
                bgColor: false,
		searchTime: 0 
            },
	    yourVideos: {
                name: 'rumble-your-videos',
                xhr: null,
                loading: false,
                page: 1,
                lastPage: false,
                pageLimit: 10,
                bgColor: false,
		searchTime: 0 
	    }
        },
        dim: {
            paddingTop: 2,
            paddingRight: 15,
            paddingBottom: 15,
            paddingLeft: 15,
            titleHeight: 30
        },
        message: {
            invalidResponseFormat: 'Something went wrong, please try later.',
            invalidAccessToken: 'Your publisher ID is invalid. Go to rumble plugin settings and enter correct publisher ID, save it, then try again.',
            loadingResults: 'Results are loading...',
            noResults: 'No results found'
        },
	search:{
		time: new Date().getTime(),
		text: ''
	}
    }, currentForm = 0;

    function attachToRumbleForm(t){
        var o=$(t).closest('#TB_window,#rumble-popup');
	if(o.length>0) currentForm = o;
    }

    /**
     * Returns Html error element.
     *
     * @param {Object} response Rumble search response.
     * @returns {Object} Html element containing the error message.
     */
    function getErrorMessageElement(response) {
        var error = $('<p />');
        error.attr({ class: 'rumble-result-error' });

        if (response === null) {
            error.html(options.message.invalidResponseFormat);
        } else if (response.error.msg === 'Invalid access token format') {
            error.html(options.message.invalidAccessToken);
        } else {
            error.html(options.message.invalidResponseFormat);
        }

        return error;
    }

    /**
     * Returns Html loading message element.
     *
     * @returns {Object} Html element displaying message about loading results.
     */
    function getLoadingVideosElement() {
        var loading = $('<p />');
        loading.attr({ class: 'rumble-result-loading' });
        loading.html(options.message.loadingResults);

        return loading;
    }

    /**
     * Returns Html no results found element.
     *
     * @returns {Object} Html element displaying message when no results are found.
     */
    function getNoResultsFoundElement() {
        var noResults = $('<p />');
        noResults.attr({ class: 'rumble-result-not-found'});
        noResults.html(options.message.noResults);

        return noResults;
    }

    /**
     * Resizes the ThickBox content.
     *
     * @returns {void}
     */
    function resize() {
        var width = $("#TB_window").width();
        var height = $("#TB_window").height();

        var footerHeight = $('.rumble-footer').height();

        $('#TB_ajaxContent').css({width: (width - options.dim.paddingLeft - options.dim.paddingRight) + 'px'});
        $('#TB_ajaxContent').css({height: (height - options.dim.paddingTop - options.dim.paddingBottom - options.dim.titleHeight - footerHeight) + 'px'});
    }

    /**
     * Attaches the scroll handler on ThickBox content. It's used to load more content when user scrolls.
     *
     * @returns {void}
     */
    function attachScrollHandler() {
        $('#TB_ajaxContent').on('scroll', function() {
            if ($('.rumble-tabs a.active').prop('id') === 'tab-' + options.tabs.searchResults.name
                    && options.tabs.searchResults.loading === false
                    && options.tabs.searchResults.lastPage === false
                    && $(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 500) {
                load(true, options.tabs.searchResults);
            }

            if ($('.rumble-tabs a.active').prop('id') === 'tab-' + options.tabs.newestVideos.name
                    && options.tabs.newestVideos.loading === false
                    && options.tabs.newestVideos.lastPage === false
                    && $(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 500) {
                load(true, options.tabs.newestVideos);
            }

            if ($(this).scrollTop() > 150) {
                $('.rumble-scroll-to-top').css({opacity: 0.8, 'z-index': 0});
            } else {
                $('.rumble-scroll-to-top').css({opacity: 0, 'z-index': -1});
            }
        });
    }

    /**
     * Performs a search against rumble's servers.
     *
     * @returns {void}
     */
    function search() {
        if (options.tabs.searchResults.xhr !== null) {
            options.tabs.searchResults.xhr.abort();
            options.tabs.searchResults.xhr = null;
        }

	options.search.time = new Date().getTime()
	options.search.text = currentForm.find('#rumble-search').val(),

	options.tabs.searchResults.page = 1;
	if(options.search.text==''){
	        selectTab('tab-' + options.tabs.editorPicks.name);
	}else{
	        options.tabs.searchResults.bgColor = false;
	        selectTab('tab-' + options.tabs.searchResults.name);
	}
    }

    /**
     * Loads videos from rumble's servers.
     *
     * @param {Boolean} moreVideos Determines if this is initial search or loading of additional videos.
     * @param {Object} tab Tab for which video need to be retrieved.
     * @returns {void}
     */
    function load(moreVideos, tab) {
        var results = currentForm.find('#' + tab.name);
        tab.loading = true;

        if (moreVideos === false) {
            results.html('');
        }

        results.append(getLoadingVideosElement());

	tab.searchTime = options.search.time;

        tab.xhr = $.ajax({
            type: 'post',
            url: ajaxurl,
            data:  {
                action: 'get_videos',
                search: options.search.text,
                tab: tab.name,
                page: tab.page++
            },
            dataType: 'json'
        }).done(function(response){
            tab.loading = false;

            results.find('.rumble-result-error').remove();

            if (response === null || response.hasOwnProperty('error')) {
                var error = getErrorMessageElement(response);

                results.prepend(error);
                $("#TB_ajaxContent").animate({ scrollTop: 0 }, "slow");
                return;
            }

            tab.lastPage = response.paginate.current === response.paginate.pages || response.paginate.current === tab.pageLimit;

            // If results are not found on first page
            if (tab.page === 2 && (response.results === 0 || response.results.length === 0)) {
                results.prepend(getNoResultsFoundElement());
            }

            for (var i = 0; i < response.results.length; i++) {
                // If item already rendered, don't render it again.
                if (results.find('div[data-iframe^="' + response.results[i].video.iframe + '"]').length > 0) {
                    continue;
                }

                var result = $('<div />');
                result.attr({ class: 'rumble-result' });

                if (tab.bgColor === true) {
                    result.addClass('rumble-result-bg');
                }

                tab.bgColor = !tab.bgColor;

                var resultThumb = $('<div />');
                resultThumb.attr({ class: 'rumble-result-img-wrapper' });
                result.append(resultThumb);

                var resultThumbImg = $('<img />');

                if (response.results[i].thumbnails.length > 0) {
                    resultThumbImg.attr({ src: response.results[i].thumbnails[0].url });
                } else {
                    resultThumbImg.attr({ src: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAYAAADDPmHLAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAipJREFUeJzt3U1uE0EUhdEHa0BeCY7JkpA8yMoxsADCwLSwkLFd/Vfvtc+RMsugkvspcqJqJwIAAAAAAAAAAAAAAAAAAAAAACbZ9T7ACBXPnNJbRHyPiEPvgzR4jYgfEXHsfZDq3iLi/c9HlQheI+Jn/D23CEa6HL9KBP+OL4KRro2fPYL/jS+CRrfGzxrBvfFF8KBdnMe9943MFMGj479HxLeI+NTnmHUcok4Ew6v9R856iojPfY5ZT4UIjL+wzBEYfyUZIzD+yjJFYPxOMkTwJYzfVc8IjJ9EjwiMn8yaERg/qTUiMH5yS0Zg/CKWiOAQxi9lzgiMX9QcERi/uCkRGH8jxkRg/I1pjcD4G9QSgfE3aq4IjF/Y1AiMvwFjIzD+hrRG8DTjf+x9gBV96H0A+mj5Pf8pfwps2djxRbABU8cXQWFzjS+Cglovc5waPne/4tfBCGNu8uxDBJsw5RqXCIqb4w6fCIqa8wKnCIpZ4vauCIpY8kHNfbRdKhHBytZ4SlcESa35iLYIkunxfL4Ikuj55gwi6KzlrdiW+rt9awQvC5zhKWUYf/ASIlhVpvEHIlhJxvEHIlhY5vEHIlhIhfEHIpjZLuo9q9cSwSm8WfRdx6gz/uCR5w5+RcTXXges5lYE2cYf3IrA+CNciyDr+INrERh/gssIso8/uIzA+DM4xvk/bVQYf3CIc7DGn0nFV84VzwwAAAAAAAAAAAAAAAAAAAAAQB6/AZCTL2fnnyD7AAAAAElFTkSuQmCC" });
                }

                resultThumb.append(resultThumbImg);

                var resultContent = $('<div />');
                resultContent.attr({ class: 'rumble-result-content' });
                result.append(resultContent);

                var resultTitle = $('<div />');
                resultTitle.attr({ class: 'rumble-result-title' });
                resultTitle.html(response.results[i].title);
                resultContent.append(resultTitle);

                var resultDesc = $('<div />');
                resultDesc.attr({
                    class: 'rumble-result-desc',
                    title: response.results[i].description
                });
                resultDesc.html(response.results[i].description);
                resultContent.append(resultDesc);

                var resultActions = $('<div />');
                resultActions.attr({ class: 'rumble-result-actions' });
                resultContent.append(resultActions);

                var showAction = $('<button />');
                showAction.html('Preview video');
                showAction.attr({
                    class: 'button'
                });
                showAction.click(function() {
                    var embed = $(this).closest('.rumble-result').find('.rumble-result-embed');
                    if (embed.is(':visible') === false) {
                        var iframe = $('<iframe />');
                        iframe.attr({
                            width: 736,
                            height: 414,
                            src: embed.data('iframe'),
                            frameborder: 0,
                            allowfullscreen: ''
                        });
                        embed.html(iframe);
                        $(this).html('Close video');
                    } else {
                        embed.html('');
                        $(this).html('Preview video');
                    }
                    embed.toggle();
                });
                resultActions.append(showAction);

                var insertAction = $('<button />');
                insertAction.attr({
                    class: 'rumble-result-action-insert button',
                    'data-iframe': response.results[i].video.iframe
                });
                insertAction.html('Insert video');
                resultActions.append(insertAction);

                var resultEmbedWrapper = $('<div />');
                resultEmbedWrapper.attr({
                    class: 'rumble-result-embed',
                    'data-iframe': response.results[i].video.iframe
                });
                result.append(resultEmbedWrapper);

                results.append(result);
            }
        }).fail(function() {
            tab.loading = false;

            // Check if user aborted
            if (tab.xhr.getAllResponseHeaders().length === 0) {
                var results = $('#' + tab.name);

                results.find('.rumble-result-error').remove();

                var error = getErrorMessageElement(null);

                results.prepend(error);
            }

            $("#TB_ajaxContent").animate({ scrollTop: 0 }, "fast");
        }).always(function() {
            results.find('.rumble-result-loading').remove();
            tab.xhr = null;
        });
    }

    function findCurrentRumbleForm(){
	var o=$('#TB_window .rumble-tabs');
	if(o.length<1) o = $('#rumble-popup .rumble-tabs');
	if(o.length>0){
		attachToRumbleForm(o[0]);
	}
    }

    function initTabs() {
        $('.rumble-tabs').on('click', 'a', function() {
	    attachToRumbleForm(this);
            selectTab($(this).prop('id'));
        });
	findCurrentRumbleForm();
    }

    function selectTab(tabId) {
        if(!currentForm)return;
	currentForm.find('#rumble-search').val(options.search.text);
        $('.rumble-tabs a').removeClass('active');
        currentForm.find('#' + tabId).addClass('active');

        var contentId = tabId.substring(4, tabId.length), currentTab=0;

        $('.rumble-tabs-content > div').hide();
        currentForm.find('#' + contentId).show();

	for(var i in options.tabs){
		if(options.tabs[i].name==contentId){
			currentTab = i;
		}
	}

	if(options.tabs[currentTab].searchTime != options.search.time){
		options.tabs[currentTab].page=1;
		load(false, options.tabs[currentTab]);
	}
    }

    function onTbOpen() {
	findCurrentRumbleForm();
        $('#rumble-search').focus();
    }

    function onTbClose() {
        selectTab('tab-' + options.tabs.editorPicks.name);
    }

    $().ready(function () {
        initTabs();
        selectTab('tab-' + options.tabs.editorPicks.name);

        // Attach a handler to insert the rumble shortcode for embedded video.
        $('.rumble-tabs-content').on('click', '.rumble-result-action-insert', function() {
            var embedCode = "[rumble]" + $(this).data('iframe') + "[/rumble]";

            window.parent.send_to_editor(embedCode);
            tb_remove();
        });

        // Attach handler to submit a search request by pressing the 'enter' key.
        $('#rumble-search').keypress(function(e) {
            if (parseInt(e.keyCode) === $.ui.keyCode.ENTER) {
	        attachToRumbleForm(this);
                search();
            }
        });

        // Attach handler to submit a search request by clicking on search button.
        $('#rumble-submit').click(function() {
	    attachToRumbleForm(this);
            search();
        });
        $('#rumble-clear').click(function() {
	    attachToRumbleForm(this);
            currentForm.find('#rumble-search').val('');
            search();
        });

        $('.rumble-scroll-to-top').click(function()
        {
	    attachToRumbleForm(this);
            $('#TB_ajaxContent').animate({ scrollTop: 0 }, "slow");
        });

        // Every 200ms content will be resized and scroll handlers reattached.
        setInterval(function() {
            resize();

            // It's detached when tb is reopened.
            attachScrollHandler();
        }, 200);

        // Every 50ms check if tb state changed and fire event if it is.
        setInterval(function() {
            if ($('#TB_window').is(':visible') === true && options.isTbOpened === false) {
                options.isTbOpened = true;
                onTbOpen();
            } else if ($('#TB_window').is(':visible') === false && options.isTbOpened === true) {
                options.isTbOpened = false;
                onTbClose();
            }
        }, 50);
    });
})(jQuery);
