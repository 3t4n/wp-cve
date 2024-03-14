jQuery(function($) {

    var lockBtn = false;

    var ImportReviews = (function(){

        var $this = null;

        function request(action, args, callback) {

            args = args !== '' && typeof args === 'object' ? window.ADS.serialize(args) : args;

            $.ajaxQueue({
                url: ajaxurl,
                data: {action: 'dm_action_reviews', dm_action: action, args: args},
                type: 'POST',
                dataType: 'json',
                success: callback
            });
        }

        function renderEvents(){

            var v   = $('#prod_type').val(),
               // cat = $('#product_cat').closest('.multi-select-full'),
                cat = $('#product_cat').closest('.form-group '),
                min = $('#apply_min');

            if( v === 'categories' ){
                cat.show();
                $('#product_cat').on('change', function() {
                    let selected = 0;
                    $('#product_cat option:selected').each(function(){
                        selected++;
                    });
                    if (selected > 1) {
                        console.log(selected);
                        cat.find('.filter-option-inner-inner').html('Selected ' + selected + ' categories');
                    }
                });
            }
            else{
                $("#product_cat").val('default').selectpicker("refresh");
                cat.hide();
            }

            min.toggle( $( '#apply_empty' ).is( ':checked' ) );

            if( parseInt( min.val() ) < 1 ) {
                min.val(1);
            }
        }

        function send(action, args, callback) {

            $.ajaxQueue({
                url: ajaxurl,
                data: {action: 'dm_action_reviews', dm_action: action, args: args},
                type: 'POST',
                dataType: 'json',
                success: callback
            });
        }

        function renderReviewForm ( response ) {

            var tmpl = $('#ali-review').html(),
                target = $('#dm_review-form'),
                total_item = $('#total_item');
            if (response) {

                if (response.hasOwnProperty('error')) {
                    window.ADS.notify(response.error, 'danger');
                } else {
                    target.html(window.ADS.objTotmpl(tmpl, response));
                    setTimeout(window.ADS.switchery(target), 300);

                    var total = parseInt( total_item.val() );
                    var current = parseInt( total_item.val() );

                    if( current > 0 && total > 0 ) {
                        window.ADS.progress( $( '#activity-list' ), total, current );
                    }

                    $.event.trigger( {
                        type : "request:done",
                        obj  : '#dm_review-form'
                    } );
                }
            }
        }

        function parseUrl(url) {

            var chipsUrl = url.split('?'),
                hostName = chipsUrl[0],
                paramsUrl = chipsUrl[1],
                chipsParamsUrl = paramsUrl.split('&'),
                urlArray = {};

            $.each(chipsParamsUrl, function(i, value) {
                var tempChips = value.split('=');
                urlArray[tempChips[0]] = tempChips[1];
            });

            return {
                'hostName' : hostName,
                'urlArray' : urlArray
            };
        }

        function getStar(width) {
            var star;
            width = parseInt( width.replace( /[^0-9]/g, '' ) );

            star = 0;
            if (width > 0) {
                star = parseInt( 5 * width / 100 );
            }

            return star;
        }

        function buildUrl(hostName, urlArray) {
            var url = hostName + '?';
            var urlParams = [];

            $.each(urlArray, function(index, value) {
                if (typeof value === 'undefined') {
                    value = '';
                }
                urlParams.push(index + '=' + value);
            });

            url += urlParams.join('&');
            return url;
        }

        function changeUrl(url, params) {

            if (typeof params === 'undefined') {
                return false;
            }

            var result = parseUrl(url);

            $.each(params, function(key, value) {
                result.urlArray[key] = value;
            });

            return buildUrl(result.hostName, result.urlArray);
        }

        function updateActivity( response ) {

            var post_id = response.post_id,
                item    = $('#dm_activities-list').find('[data-post_id="'+post_id+'"] .count-reviews');

            var count = parseInt( item.text() ) + response.count;

            item.text(count);

            if( count < response.count_review && response.page < 30 )
                addReview( response.page, post_id, response.feedbackUrl );
            else
                importReviews(false);
        }

        function sendReview( params, response ) {

            var $obj    = response,
                post_id = params.post_id,
                args    = params,
                review  = {
                    'flag'     : '',
                    'author'   : '',
                    'star'     : '',
                    'feedback' : '',
                    'date'     : ''
                },
                $feedbackList = $obj.find( '.feedback-list-wrap .feedback-item' ),
                feedList = [];

            var importedReview = $('#dm_activities-list').find('[data-post_id="'+post_id+'"] .count-reviews').text();
                importedReview = parseInt(importedReview);

            if ( $feedbackList.length !== 0 ) {

                $feedbackList.each( function ( i, e ) {

                    var images = [];

                    review = {};

                    review.feedback = $(this).find('.buyer-feedback').text();
                    if($(this).find('.buyer-feedback .r-time-new').length){
                        review.feedback = $(this).find('.buyer-feedback span:not(.r-time-new)').text();
                     }
                    review.feedback = review.feedback.replace('seller', 'store');
                    review.flag     = $(this).find('.css_flag').text();
                    review.author   = $(this).find('.user-name').text();
                    review.star     = getStar($(this).find('.star-view span').attr('style'));

                    $(this).find('.pic-view-item').each(function(index, value) {
                        images.push($(value).data('src'));
                    });

                    let dateBox = $(this).find('.r-time');
                    if($(this).find('.r-time-new').length){
                        dateBox = $(this).find('.r-time-new');
                    }
                    review.date = dateBox.text();
                    review.images = images;
                    feedList.push(review);
                });

                if ( importedReview <= args.countReviews) {

                    var data = {
                        post_id        : post_id,
                        feed_list      : ADS.b64EncodeUnicode(JSON.stringify(feedList)),
                        star_min       : args.star_min,
                        withPictures   : args.withPictures,
                        uploadImages   : args.uploadImages,
                        apply_empty    : args.apply_empty,
                        apply_min      : args.apply_min,
                        ignoreImages   : args.ignoreImages,
                        importedReview : importedReview,
                        page           : args.page++,
                        feedbackUrl    : args.feedbackUrl,
                        count_review   : $('#count_review').val(),
                        approved       : args.approved
                    };

                    send( 'upload_review', data, updateActivity );
                } else {
                    importReviews(false);
                }
            }
            else {
                importReviews(false);
            }
        }

        function getNextProduct( response ) {

            var $progress = $('#activity-list');

            if (response.hasOwnProperty('error')) {

                window.ADS.notify(response.error, 'danger');

                window.ADS.btnUnLock(lockBtn);
                lockBtn = false;
            } else if (response.hasOwnProperty('message')) {

                window.ADS.notify(response.message, 'success');
                window.ADS.progress($progress, 10, 10);

                window.ADS.btnUnLock(lockBtn);
                lockBtn = false;
            } else {

                var tmpl = $('#item-review-template').html(),
                    $el = $('#dm_activities-list');

                if (!$el.find('.table-container').length)
                    $el.html($('<div/>', {class: 'table-container'}));

                $el = $el.find('.table-container');

                $('#total_item').val( response.total );

                window.ADS.progress($progress, response.total, response.current); //рисуем в прогресс бар +1

                var c = $el.find('.review-item');

                if (c.length >= 15) {
                    c.last().remove();
                }

                if( response.row !== false )
                    $el.prepend(window.ADS.objTotmpl(tmpl, response.row)); //пишем в activity list товар + вешаем на него спинер и что обрабатывается

                $('#current_item').val(response.current);

                if(!response.row.feedbackUrl){
                    importReviews();
                }else{
                    addReview(1, response.row.post_id, response.row.feedbackUrl);
                }
            }
        }

        function addReview( page, post_id, feedbackUrl ) {

            var args  = {
                rate              : $('#min_star').val(),
                countReviews      : $('#count_review').val(),
                onlyFromMyCountry : $('#onlyFromMyCountry').is(':checked'),
                translate         : $('#switchTranslate').is(':checked') ? '+Y+' : '+N+',
                ignoreImages      : $('#ignoreImages').is(':checked'),
                withPictures      : $('#withImage').is(':checked'),
                uploadImages      : $('#uploadImage').is(':checked'),
                apply_empty       : $('#apply_empty').is(':checked'),
                apply_min         : $('#apply_min').val(),
                approved          : $('#approved').is(':checked')
            };

            var url = changeUrl(
                feedbackUrl,
                {
                    'translate'         : args.translate,
                    'page'              : page,
                    'withPictures'      : args.withPictures,
                    'onlyFromMyCountry' : args.onlyFromMyCountry
                }
            );

            var params = {
                post_id      : post_id,
                page         : page,
                feedbackUrl  : feedbackUrl,
                countReviews : args.countReviews,
                withPictures : args.withPictures,
                uploadImages : args.uploadImages,
                apply_empty  : args.apply_empty,
                apply_min    : args.apply_min,
                ignoreImages : args.ignoreImages,
                star_min     : args.rate,
                approved     : args.approved
            };
            setTimeout(function() {
                addTask( url, params );
            }, 2000);
        }

        function addTask( url, params ) {

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'html',
                success: function ( data ) {

                    var div = $( '<div></div>' );

                    sendReview( params, $( div ).append( data ) );
                },
                fail : function (jqXHR, textStatus) {
                    console.log(textStatus);
                }
            });
        }

        function importReviews( first ) {

            var action = first ? 'first_review' : 'next_review';

            request( action, $('#dm_review-form'), getNextProduct );
        }

        function checkIgnoreImages() {

            if( $(document).find('#ignoreImages').is(':checked') ) {

                var im   = $('#withImage'),
                    i    = im.parents('.checkbox-switchery'),
                    up   = $('#uploadImage'),
                    u    = up.parents('.checkbox-switchery');

                if( im.prop('checked') ) {
                    im.click();
                }
                if( up.prop('checked') ) {
                    up.click();
                }

                i.hide();
                u.hide();
            }
        }

        return {
            init: function(){

                $this = this;

                request( 'review_form', '', renderReviewForm );

                $(document).on('click', '#js-reviewImport', function(e){

                    e.preventDefault();

                    $('#js-reviewNext').remove();

                    lockBtn = $(this);
                    window.ADS.btnLock( lockBtn );

                    $('#current_item').val(0);
                    $('#dm_activities-list').html('');

                    window.ADS.progress( $( '#activity-list' ), 0, 0 );

                    importReviews(true);
                });

                $(document).on('click', '#js-reviewNext', function(e){

                    e.preventDefault();

                    $(this).remove();
                    lockBtn = $('#js-reviewImport');
                    $('#dm_activities-list').html('');
                    window.ADS.progress( $( '#activity-list' ), 0, 0 );

                    window.ADS.btnLock( lockBtn );

                    $('#current_item').val(0);

                    importReviews(false);
                });

                $(document).on('request:done', function(e) {
                    if( e.obj === '#dm_review-form') {
                        checkIgnoreImages();
                    }
                });

                $(document).on('click', '#ignoreImages', function(){

                    var $obj = $('#dm_review-form');
                    var im   = $obj.find('#withImage'),
                        i    = im.parents('.checkbox-switchery'),
                        up   = $obj.find('#uploadImage'),
                        u    = up.parents('.checkbox-switchery');

                    if( ! $(this).is(':checked') ) {
                        i.show();
                        u.show();
                    } else {

                        if( im.prop('checked') ) {
                            im.click();
                        }
                        if( up.prop('checked') ) {
                            up.click();
                        }

                        i.hide();
                        u.hide();
                    }
                });

                $(document).on( 'request:done', function () {
                    renderEvents();
                } );

                $(document).on('change', '#apply_empty, #prod_type', function(){
                    renderEvents();
                });
            }
        };
    })();

    ImportReviews.init();
});