jQuery(document).ready(function ($) {
    $("#ecsa-search").on("keyup", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            goToFirstSelectableLink();
            $(".typeahead").typeahead("close");
        }
    });

    $(".ecsa-search-icon").on("click", function (e) {
        e.preventDefault();
        goToFirstSelectableLink();
        $(".typeahead").typeahead("close");
    });

    $(".ecsa-search-field").each(function () {
        var thisEle = $(this),
            disablePast = thisEle.data("disable-past"),
            noUpResult = thisEle.data("no-up-result"),
            noPastResult = thisEle.data("no-past-result"),
            showEvents = thisEle.data("show-events"),
            upcomingHeading = thisEle.data("up-ev-heading"),
            pastHeading = thisEle.data("past-ev-heading"),
            styleContentType = thisEle.data("style-full");

        var upcoming_source = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace("name"),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function (obj) {
                return obj.name;
            },
            prefetch: {
                url: ecsaSearch.prefetchUpcomingUrl,
                cache: false
            }
        });

       
        upcoming_source.initialize();
        function futureEventSource(q, sync) {
            var events = upcoming_source.index.datums;
            if (typeof events === 'undefined') {
                return;
            }
            if (q === '') {
                sync(upcoming_source.all());
            } else {
                upcoming_source.search(q, sync);
            }
        }

        var past_source = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function (obj) {
                return obj.name;
            },
            prefetch: {
                url: ecsaSearch.prefetchPastUrl,
                cache: false
            }
        });

        
        var past_promise = past_source.initialize();

      
        function PastEventSource(q, sync) {
            var event = past_source.index.datums;
            if (disablePast === true || typeof event === 'undefined') {
                return;
            }
            if (q === '') {
                sync(past_source.all());
            } else {
                past_source.search(q, sync);
            }
        }
        past_promise.done(function () {
            $('.ecsa-search-box-skelton').hide();
            $('.ecsa-search-load').show();
        });
        var pastEventsEmpty = ['<div class="empty-message">', noPastResult, "</div>"].join("\n");
        if (disablePast === true) {
            pastEventsEmpty = '';
        }

        var templateId = styleContentType === 'basic' ? "ecsa-search_temp_short" : "ecsa-search_temp_full";

        thisEle.find(".typeahead").typeahead({
            minLength: 0,
            highlight: true
        }, {
            name: "matched-links",
            displayKey: "name",
            limit: showEvents,
            source: futureEventSource,
            async: true,
            templates: {
                header: '<h3 class="ecsa-heading">' + upcomingHeading + "</h3>",
                empty: ['<div class="empty-message">', noUpResult, "</div>"].join("\n"),
                suggestion: Handlebars.compile(document.getElementById(templateId).innerHTML)
            }
        }, {
            name: "matched-links",
            displayKey: "name",
            limit: showEvents,
            source: PastEventSource,
            async: true,
            templates: {
                header: '<h3 class="ecsa-heading">' + pastHeading + "</h3>",
                empty: pastEventsEmpty,
                suggestion: Handlebars.compile(document.getElementById(templateId).innerHTML)
            }
        });
    });
   
    function goToFirstSelectableLink() {
        var selectables = $(".typeahead").siblings(".tt-menu").find(".tt-selectable a");
        if (selectables.length > 0) {
            window.location = $(selectables[0]).attr("href");
        }
    }
});
