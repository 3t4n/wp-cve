/**
 * jquery.jcorgYoutubeUserChannel.js
 * Copyright (c) 2012 Jaspreet Chahal (http://jaspreetchahal.org/)
 * Licensed under the Free BSD License
 * @author Jaspret Chahal
 * @projectDescription    jQuery plugin to allow custom youtube channel embed
 * @documentation http://jaspreetchahal.org/jquery-plugin-youtube-channel-embed
 * @version 1.1
 * @requires jquery.js (tested with v 1.7.2)
 * NOT AFFILIATED WITH YOUTUBE
 * YOU MUST KEEP THIS COMMENT SECTION WHEN USING THIS PLUGIN AND A LINK BACK WILL BE APPRECIATED
 */
(function ($) {
    getYoutubePlaylistID = function () {
        if ($("#jcorgytce_channel_name").val() == "" || $("#jcorgytce_ytkey").val() == "") {
            alert("Channel name and Youtube API key is a required feild.");
        }
        else {
            $.getJSON("https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=" + $("#jcorgytce_channel_name").val() + "&key=" + $("#jcorgytce_ytkey").val(),
                function (data) {
                    var pl = data.items[0].contentDetails.relatedPlaylists.uploads;
                    if (data.items instanceof Array && pl != undefined && pl.length > 0) {
                        $("#jcorgytce_playlist").val(pl)
                    }
                    else {
                        alert("Cannot get playlist id for your channel. Please check if your youtube ");
                    }
                }, function (err) {

                })
                .error(function () {
                    alert("Cannot get playlist id for your channel. Please check if your youtube API key and channel name is valid. Strip leading and trailing whitespaces if there are any.");
                })
        }
        return false;
    };

    jQuery.fn.jcorgYoutubeUserChannelEmbed = function (settings) {
        settings = jQuery.extend({
            mode: 'thumbnails', // list || thumbnails
            videoWidth: '640',
            thumbnailWidth: '240',
            videoWidth: '640',
            showTitle: true,
            maxResults: 6,
            startIndex: 1,
            autoPlay: false,
            orderBy: 'published', // relevance | published | viewCount | rating
            filterKeyword: '', // just in case you want to filter videos by keyword in a channel being embedded
            channelUserName: 'jassiechahal',
            thumbQuality: 1, // 0,1,2 high, medium, low
            videos: '',
            onlyHD: false,
            ytkey: '',
            playlistid: '',
            allowFullScreen: true,
            format: 'embed', // embed | mobileH263 | mobileMP4
            useIncl: 'frame' // object || frame
        }, settings);

        var thumbQuality = [
            "high",
            "medium",
            "standard"
        ];
        var allowfullscreen = settings.allowfullscreen ? 'allowfullscreen' : '';
        var videos = settings.videos?"&videoId="+settings.videos:"";
        var url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails%2Csnippet'+videos+'&playlistId=' + settings.playlistid + '&key=' + settings.ytkey;
        var autoplay = settings.autoPlay ? '1' : 0;
        var youtubeParams = [
            "alt=json",
            "start-index=" + settings.startIndex,
            "maxResults=" + settings.maxResults,
            "orderBy=" + settings.orderBy
        ];
        if (settings.format == 'embed')
            youtubeParams.push("format=5");
        else if (settings.format == 'mobileH263')
            youtubeParams.push("format=1");
        else if (settings.format == 'mobileMP4')
            youtubeParams.push("format=3");
        if (settings.filterKeyword.length > 0)
            youtubeParams.push("q=" + settings.filterKeyword);
        // HD
        if (settings.onlyHD)
            youtubeParams.push("hd=true");
        // JSONP callback  

        url = url + "&" + youtubeParams.join('&');
        parentElement = jQuery(this);
        autoplay = false;
        return this.each(function () {
            jQuery.getJSON(url, function (data) {
                if (settings.mode == "list") {
                    var listObj = jQuery('<ul />', {'class': "jcorg-yt-list"}).appendTo(parentElement);
                    if (data.items != undefined) {
                        for (var i = 0; i < data.items.length; i++) {
                            var entry = data.items[i];
                            var vidID = (entry ? entry.snippet.resourceId.videoId : '');
                            var vidLink = (entry ? "https://www.youtube.com/embed/"+entry.snippet.resourceId.videoId : '');
                            var vidTitle = (entry ? entry.snippet.title : '');
                            var vidThumb = (entry ? eval("entry.snippet.thumbnails."+thumbQuality[settings.thumbQuality]).url : '');

                            if (settings.showTitle)
                                jQuery("<li/>", {'class': "jcorg-yt-list-title"}).html(vidTitle).appendTo(listObj);

                            if (settings.useIncl == 'frame') {
                                var allowfullscreen = (settings.allowFullScreen) ? 'allowfullscreen' : '';
                                ytObject = '<iframe width="' + settings.videoWidth + '" height="' + (parseInt(settings.videoWidth / 1.78)) + '" src="' + vidLink + '?feature=player_detailpage&origin=' + (window.location.origin) + '" autoplay="' + autoplay + '" frameborder="0" ' + allowfullscreen + '></iframe>';
                            }
                            else {
                                if (vidLink.substr(0, 31) == 'http://www.youtube.com/watch?v=') vidLink = 'http://www.youtube.com/v/' + vidLink.substr(31);
                                var allowfullscreen = (settings.allowFullScreen) ? 'true' : 'false';
                                var ytObject = '<object width="' + settings.videoWidth + '" height="' + (parseInt(settings.videoWidth / 1.78)) + '">' +
                                    '<param name="movie" value="' + vidLink + '?hl=en&fs=1&autoplay=' + autoplay + '"></param>' +
                                    '<param name="allowFullScreen" value="' + allowfullscreen + '"></param>' +
                                    '<param name="allowscriptaccess" value="always"></param>' +
                                    '<embed src="' + vidLink + '?hl=en&fs=1&autoplay=' + autoplay + '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="' + allowfullscreen + '" width="' + settings.videoWidth + '" height="' + (parseInt(settings.videoWidth / 1.78)) + '"></embed>' +
                                    '</object>';
                            }
                            jQuery("<li/>", {'class': "jcorg-yt-list-video"}).html(ytObject).appendTo(listObj);
                        }
                        ;
                    }
                }
                else if (settings.mode == "thumbnails") {
                    var listObj = jQuery('<div />', {'class': "jcorg-yt-default-play"}).appendTo(parentElement);
                    var listObj = jQuery('<ul />', {'class': "jcorg-yt-thumbnails clearfix"}).appendTo(parentElement);
                    var vidArray = [];
                    if (data.items != undefined) {
                        for (var i = 0; i < data.items.length; i++) {
                            var entry = data.items[i];
                            var vidID = (entry ? entry.snippet.resourceId.videoId : '');
                            var vidLink = (entry ? "https://www.youtube.com/watch?v="+entry.snippet.resourceId.videoId : '');
                            var vidTitle = (entry ? entry.snippet.title : '');
                            var vidThumb = (entry ? eval("entry.snippet.thumbnails."+thumbQuality[settings.thumbQuality]).url : '');
                            vid = '<a href="' + vidLink + '" rel="prettyPhoto[gallery]" title="' + vidTitle + '" class="jcorg-yt-thumbnail"><img src="' + vidThumb + '" alt="' + vidTitle + '" width="' + settings.thumbnailWidth + '" height="' + (parseInt(settings.thumbnailWidth / 1.34)) + '" /></a>';
                            if (settings.showTitle) {
                                vid = vid + '<div class="jcorg-yt-thumbnail-title" style="width:' + settings.thumbnailWidth + 'px !important">' + vidTitle + '</div>';
                            }
                            jQuery("<li/>").html(vid).appendTo(listObj);

                        }
                        jQuery("a[rel^='prettyPhoto']").prettyPhoto({
                            social_tools: false,
                            autoplay: settings.autoPlay,
                            default_width: settings.videoWidth,
                            default_height: (parseInt(settings.videoWidth / 1.78)),
                            show_title: false
                        });
                    }

                }
            });
        });
    }

})(jQuery);