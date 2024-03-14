/***
    THIS IS SHARED CODE BASE

    it NEEDS TESTS!

    Implemented:
        mobile web
        chrome ext
        firefox ext
        safari ext
*/

// get user keys
var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = window.location.search.substring(1),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;
	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] === sParam) {
			return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		}
	}
	return false;
};
var giphy_key = getUrlParameter('giphy');

// console.log(gifm_tinymce_obj);

var GiphySearch = {
    MAX_TAGS                   : 5,
    PLATFORM_COPY_PREPEND_TEXT : "",
    MAX_GIF_WIDTH              : 145,
    SEARCH_PAGE_SIZE           : 100,
    INFINITE_SCROLL_PX_OFFSET  : 100,
    INFINITE_SCROLL_PAGE_SIZE  : 50,
    ANIMATE_SCROLL_PX_OFFSET   : 200,
    STILL_SCROLL_PAGES         : 1,
    SCROLLTIMER_DELAY          : 250,
    SCROLL_MOMENTUM_THRESHOLD  : 100,
    ALLOW_URL_UPDATES          : true, // enable history pushstate via add_history method
    API_KEY                    : giphy_key,
    //API_KEY                  : parent.tinyMCE.activeEditor.getParam('api_key'),
    location                   : {}, // for chrome ext

    // navigation vars
    curPage                    : "gifs",

    // scrolling vars
    curScrollPos               : 0,
    prevScrollPos              : 0,
    isRendering                : false,

    // event buffer timers
    scrollTimer                : 0,
    searchTimer                : 0,
    renderTimer                : 0,
    // infinite scroll vars
    curGifsNum                 : 0, // for offset to API server
    curResponse                : null,
    prevResponse               : null,

    // search vars
    curSearchTerm              : "",
    prevSearchTerm             : "",
    gat                        : false,

    init: function(data) {
        GiphySearch.bind_events();
    },
    bind_events:function() {
        //console.log("bind_events");
        jQuery(window).on('popstate', function(event) {
            GiphySearch.handleBrowserNavigation.call(this,event);
        });

        // set the container height for scrolling
        // container.height(jQuery(window).height());
        var $container = jQuery("#container");


        // watch scroll events for desktop
        $container.on("scroll", function(event) {
            GiphySearch.scroll.call(this,event);
        }).on("click", ".tag", function(event) {
            GiphySearch.handleTag.call(this,event);
        }).on("click", ".gif_drag_cover", function(event) {
       	    GiphySearch.handleGifDetailByCover.call(this,event);
        });

        
        jQuery('.embed-button').on("click", function(event){
            var gif_id = jQuery('img#gif-detail-gif').attr('data-id');
            setTimeout(function(){
                GiphyCMSExt.doTinyMCEEmbed();
            },100)
           
        });
        jQuery("#app").on("click", "#giphy_src", function(event) {
            // GiphySearch.handleTrendingHome.call(this,event);
			jQuery('.giphy_sec').show();
			jQuery('.tenor_sec').hide();
			jQuery(this).addClass('active');
			jQuery('#tenor_src').removeClass('active');
        });
		
		jQuery("#app").on("click", "#tenor_src", function(event) {
            jQuery('.tenor_sec').show();
			jQuery('.giphy_sec').hide();
			jQuery(this).addClass('active');
			jQuery('#giphy_src').removeClass('active');
        });

        // address 'home' via logo tag
        jQuery("#header").on("click", function(event) {

        });

        // search input handler
        jQuery("#searchbar-input").keypress(function(event) {
            if(event.keyCode == 13) {
                GiphySearch.handleSearch.call(this,event);
            }
        });

        // search button handler
        jQuery("#search-button").on("click", function(event) {
            GiphySearch.handleSearch.call(this,event);
        });
		
        // back button handler
        jQuery("#back-button").on("click", function(event) {
            GiphySearch.handleBack.call(this,event);
        });

    },

    is_ext:function() {
        return !!(window.chrome && chrome.contextMenus);
    },
    
    format:function(str,params) {
        return str.replace(/%\((\w+)\)s/g, function(m, key) {
            return params[key] || ""
        });
    },

    handleSearch: function(event) {
        var tag = jQuery("#searchbar-input").val();
        if(tag == "") return;

        GiphySearch.scrollTimer = 0;
        GiphySearch.searchTimer = 0;
        GiphySearch.curY = 0;
        GiphySearch.curOffset = 0;
        
        // make the new search
        GiphySearch.show_preloader();
        GiphySearch.search(tag, GiphySearch.SEARCH_PAGE_SIZE, true);

        GiphySearch.navigate("gifs");
    },
    handleTrendingHome:function(event) {
        GiphySearch.show_preloader();
        GiphySearch.resetSearch();
        GiphySearch.search("giphytrending", 100, true);
        GiphySearch.navigate("gifs");
    },
    handleTag: function(event) {
        var tag = jQuery(event.target).text();
        if(tag == '') return;
       
        GiphySearch.show_preloader();
        GiphySearch.resetViewport();
        GiphySearch.updateSearch( tag );

        // make the new search
        GiphySearch.search(tag, GiphySearch.SEARCH_PAGE_SIZE, true);
        GiphySearch.navigate("gifs");
    },
    handleGifDetailByCover:function(event) {
        var gif = jQuery(event.target).parent(".giphy_gif_li").find("img");
        GiphySearch._opendetail( gif );
    },

    handleGifDetail: function(event) {
        // get the fullsize gif src
        var gif = jQuery(event.target);
        GiphySearch._opendetail( gif );

    },
    _opendetail:function(gif) {
        //jQuery("html, body").animate({ scrollTop: 0 }, "fast");
        window.scrollTo(0, 0);
        jQuery("#container").css("overflow", "hidden");

        var gifEl = jQuery("#gif-detail-gif");
        // var loader = jQuery("#loader");
        var animatedLink = gif.attr("data-animated");
        var staticLink =  gif.attr("data-still");
        var resizeRatio = 480 / Math.floor(gif.attr("data-original-width"));
        var embedHeight = Math.floor(gif.attr("data-original-height") * resizeRatio);

        gifEl.attr("src", staticLink);
        gifEl.attr("data-id", gif.attr("data-id"));

        gifEl.attr("data-embed-width", "480");
        gifEl.attr("data-embed-height", embedHeight);

        gifEl.attr("data-shortlink", gif.attr("data-shortlink"));

        gifEl.attr("data-username", gif.attr("data-username"));
        gifEl.attr("data-profile-url", gif.attr("data-profile-url"));

        gifEl.attr("data-width", "500");
        gifEl.attr("data-height", Math.floor(gif.attr("height") * 2.5));

        GiphySearch.show_preloader();

        jQuery("<img />").attr("src", animatedLink).load(function(e){
            GiphySearch.hide_preloader();
            gifEl.attr("src", animatedLink);
        });

        var linkHTML = "<span class='gif-link-info'>" + GiphySearch.PLATFORM_COPY_PREPEND_TEXT+""+ gif.attr("data-shortlink")+"</span>";
        var tags = gif.attr("data-tags").split(',');
        var tagsHTML = "";
        jQuery(tags).each(function(idx, tag){
            if(tag !== ""){
                tagsHTML += "<span class='gif-detail-tag'>"+tag+"</span>"; //USE ACTUAL ENCODDed?
            }
        });


        jQuery("#gif-detail-link").html(linkHTML).attr({
            "data-shortlink":gif.attr("data-shortlink") // we should call this data the same name as the server does
        });

        jQuery("#gif-detail-tags").html(tagsHTML);

        jQuery(".gif-detail-tag").on("click", function(event) {
            GiphySearch.handleTag(event);
        });

        // GiphySearch.add_history( "Giphy", "/gifs/"+gif.attr("data-id") );
        GiphySearch.navigate("gif-detail");
    },

    handleBrowserNavigation: function(event){
        /*
         * UPDATE SO TO NOT MAKE NEW SEARCH CALLS WHen
         */
        var pathHash = window.location.pathname.split('/');
        if(pathHash[1] != "") {
            if(pathHash[1] == "gifs"){
                GiphySearch.navigate("gif-detail", pathHash[2]);
            }
            if(pathHash[1] == "search"){
                GiphySearch.search(pathHash[2], 100, true);
                GiphySearch.navigate("gifs");
            }
        } else {
            GiphySearch.search("giphytrending", 100, true);
            GiphySearch.navigate("gifs");
        }
    },

    handleBack: function(event) {
        jQuery("#container").css("overflow", "auto");

        // no back on the gifs page
        if(GiphySearch.curPage == "gifs") { return; }

        // back to the gif page
        if(GiphySearch.curPage == "categories" ||
            GiphySearch.curPage == "gif-detail") {
            // GiphySearch.add_history("Giphy", "/");

            GiphySearch.navigate("gifs");
        }
    },

    navigate: function(page, data) {
        //console.log("navigate(" + page + "," + data + ")");

        // set the current page
        GiphySearch.curPage = page;

        // hide everything
        jQuery("#gifs,#gif-detail,#share-menu,#categories,#category,#back-button").hide();
        // show the footer... it goes away on the gif-detail
        jQuery("#footer").show();

        // gifs
        if(page == "gifs") {
            jQuery("#gifs").show();
        }

        // gif detail
        if(page == "gif-detail") {
            jQuery("#gif-detail,#back-button,#share-menu").show();
            jQuery("#footer").hide();
        }

        // categories
        if(page == "categories") {
          //console.log("showing back button");
          jQuery("html, body").animate({ scrollTop: 0 }, "fast");
          jQuery("#categories,#back-button").show();
        }

        // category
        if(page == "category") {
            jQuery("#category").show();
        }
    },


    orientationchange: function(event) {
        //console.log("orientationchange()");
    },

    scroll: function(event) {
        ////console.log("scroll()");

        // only scroll on gifs page
        if(GiphySearch.curPage != "gifs") return;

        // set the current scroll pos
        GiphySearch.prevScrollPos = GiphySearch.curScrollPos;
        GiphySearch.curScrollPos = jQuery(event.target).scrollTop() + jQuery(window).height();

        // infinite scroll
        if(GiphySearch.curScrollPos + GiphySearch.INFINITE_SCROLL_PX_OFFSET > jQuery("#gifs").height()) {

            // start the infinite scroll after the last scroll event
            clearTimeout(GiphySearch.searchTimer);
            GiphySearch.searchTimer = setTimeout(function(event) {
                GiphySearch.search(GiphySearch.curSearchTerm, GiphySearch.INFINITE_SCROLL_PAGE_SIZE, false);
            }, 250);
        }

        // compenstate for a double scroll end event being triggered
        clearTimeout(GiphySearch.scrollTimer);
        GiphySearch.scrollTimer = setTimeout(function() {
            GiphySearch.scrollend(event);
        }, GiphySearch.SCROLLTIMER_DELAY);
    },

    scrollstart: function(event) {
        ////console.log("scrollstart()");
    },

    scrollend: function(event) {

        if(GiphySearch.renderTimer) { clearTimeout(GiphySearch.renderTimer); }
        GiphySearch.renderTimer = setTimeout(function() {
            GiphySearch.render();
        }, 250);
    },
    hide_preloader:function() {
        //console.log("hide preloader");
        jQuery(".loading_icon_box,.loading_icon").css("display","none");
    },
    show_preloader:function() {
        //console.log("show preloader");
        jQuery(".loading_icon_box,.loading_icon").css("display","block");
    },
    // THIS IS POORLY NAMED, it doesn't render, it displays..
    // renders (aka added to DOM happens WAY earlier)
    render: function() {


        if(GiphySearch.isRendering) return;
        GiphySearch.isRendering = true;


        // get all the gifs
        /**
            NOTE:
                lis ONLY has a length
                when there are ALREADY rendered items
                on the page

                this is related to using setTimeout
                when adding images to masonry / DOM


        */
        var lis = jQuery("#gifs li");
        // calculate the window boundaries
        var windowTop = jQuery(window).scrollTop();
        var windowBottom = windowTop + jQuery(window).height();
        var windowHeight = jQuery(window).height();

        // sliding window of animated, still, and off
        ////console.log("existing li : ", lis);
        ////console.log("rendering " + lis.length + " num lis");
        for(var i=0; i<lis.length; i++) {

            // get the gif

            var li = jQuery(lis.get(i));

            // try cooperative multitasking to let the graphics render have a moment
            // this seems super innefficient b/c we access the DOM a LOT
            (function($li, _pos) {
                setTimeout(function() {
                // need to calculate the window offsets and some emperical padding numbers
                var liTop = $li.offset().top;
                var liBottom = liTop + $li.height();
                var img = $li.find("img");
                var liHeightOffset = GiphySearch.ANIMATE_SCROLL_PX_OFFSET;
                var stillPagesOffset = GiphySearch.STILL_SCROLL_PAGES;

                ////console.log("GIF ON " , windowTop, liHeightOffset, liBottom, windowBottom);

                // turn on the gifs that are in view... we pad with an offset to get the edge gifs
                if((liTop >= windowTop - liHeightOffset) && (liBottom <= windowBottom + liHeightOffset)) {
                // if((liTop >= windowTop - liHeightOffset) && (liBottom <= windowBottom + liHeightOffset)) {
                    ////console.log("GIF ON " , windowTop, liHeightOffset, liBottom, windowBottom);
                    if (!jQuery(img).hasClass("seen")){
                        jQuery(img).addClass("seen")
                       
                    }

                    // buffer the animated gifs with a page above and below of stills...
                    // pad these a big with multiples of the window height
                    jQuery(img).attr("src", jQuery(img).attr("data-animated"));
                    // jQuery(img).attr("src", $img.attr("data-downsampled"));

                } else if((liTop >= windowTop - windowHeight*stillPagesOffset) &&
                          (liBottom <= windowBottom + windowHeight*stillPagesOffset)) {
                    ////console.log("GIF STILL");

                    // still these gifs
                    jQuery(img).attr("src", jQuery(img).attr("data-still"));

                } else {
                    ////console.log("GIF OFF");

                    // clear the rest of the gifs

                    if(GiphySearch.is_ext()) {
                        jQuery(img).attr("src", jQuery(img).attr("data-still") );
                    } else {
                        ////console.log("setting img src to clear");
                        jQuery(img).attr("src", "img/clear.gif");
                    }

                }

                if(lis.length-1 === _pos) {
                    GiphySearch.render_completed();
                    // //console.log(i, "current possition",  lis.length)
                }
            }, 0)})( jQuery(li), i  );

        }

        // reset rendering
        GiphySearch.isRendering = false;
        GiphySearch.hide_preloader();
        //console.log("rendering completed", "is rendering", GiphySearch.isRendering, lis.length);
    },
    gmail_template:function(params) {
        // we paste this 'template' into the dragdrop datatranser object
        return GiphySearch.format( '<a href="%(url)s"><img src="%(src)s" border="0" /></a><br />via <a href="%(url)s">giphy.com</a>', params );
    },
    render_completed:function() {
        //console.log("done rendering now!");

    },
    updateSearch:function(txt) {
        jQuery("#searchbar-input").val(txt);
    },
    resetViewport:function() {
        GiphySearch.scrollTimer = 0;
        GiphySearch.searchTimer = 0;
        GiphySearch.curY = 0;
        GiphySearch.curOffset = 0;
    },
    resetSearch: function() {
        ////console.log("resetSearch()");

        // reset the search box
        // jQuery("#searchbar-input").blur();
        jQuery("#searchbar-input").val("");
        // reset the scroll params
        GiphySearch.resetViewport();
    },
    process_search_response:function(response) {
        //console.log("fetched API data", response)
        // set the current search term
        // parse the gifs
        var gifs = response.data;
        // Begin logging a new event (GAT/Pingbacks)
        //console.log("response id is " + response.meta.response_id)
        
        mostRecentRespId = response.meta.response_id;
        var elem_array = [];



        var _frag = document.createDocumentFragment();
        //console.log("process search response ", _frag);
        //console.log("gifs length = " + gifs.length);

        for(var i=0; i<gifs.length; i++) {
            ////console.log("i = " + i);
            var gif = gifs[i];
            var tags = gif.tags || [];
            var gifTags = newT.frag();
            var _dataTags = [];
            // TODO: make this a function
            if(tags) {
                for(var j=0; j<tags.length && j<GiphySearch.MAX_TAGS; j++) {
                    if(tags[j].indexOf('giphy') == -1){
                        gifTags.appendChild(newT.span({
                            clss:"tag"
                        }, tags[j]));
                        _dataTags.push( tags[j] );
                    }
                }
            }

            var dataTags = _dataTags.join(",");
            var gif_height = Math.floor((gif.images.fixed_width.height * GiphySearch.MAX_GIF_WIDTH / gif.images.fixed_width.width));
            var hexes = ["#E646B6", "#6157FF", "#00E6CC", "#3191FF", "#FFF35C"];
            var hex = hexes[Math.floor(Math.random() * (hexes.length))];
            var username;
            var profile_url;
            // Check if the GIF has an associated user
            if (gif.user) {
                username = gif.username;
                profile_url = gif.user.profile_url;
            // If not, set those properties to null.
            } else {
                username = null;
                profile_url = null;
            }

            var _li = newT.li({
                        clss:"giphy_gif_li new_li",
                        draggable:true,
                    },
                    newT.img({
                        // draggable:true,
                        clss:"gif giphy_gif",
                        height:gif_height,
                        original_height:gif.images.fixed_width.height,
                        "data-id":gif.id,
                        "data-animated":gif.images.fixed_width.url,
                        "data-original-height":gif.images.original.height,
                        "data-original-width":gif.images.original.width,
                        "data-downsampled":gif.images.fixed_height_downsampled.url,
                        "data-still":gif.images.fixed_width_still.url,
                        "data-tags":dataTags,
                        "data-shortlink":gif.bitly_gif_url,
                        "data-username": username,
                        "data-profile-url": profile_url,
                        style:"background-color:" + hex
                    }),
                    newT.div({
                        clss:"tags"
                    }, newT.div({
                        clss:"tags_inner"
                        },gifTags)),
                    newT.div({
                        clss:"actions"
                    },newT.a({
                        href:"#"
                    })),
                    newT.div({
                        clss:"gif_drag_cover",
                        style:"height:" + gif_height + "px;"
                    })
                );

            ////console.log(_li);

            _frag.appendChild(_li);
            elem_array.push(_li);

            // increment the num gifs
            GiphySearch.curGifsNum++; // why? really seriously why?
        }

        //var gifs = document.getElementById("gifs");
        var gifsEl = jQuery('ul#gifs');
        gifsEl.append(_frag);
        //document.getElementById("gifs").appendChild(_frag);

        //console.log('calling masonry with ', elem_array.length, gifs);
        gifsEl.imagesLoaded(function() {
            gifsEl.masonry('appended', elem_array, true);
            var newLI = jQuery('.new_li');
            newLI.css('opacity','1.0');
            newLI.removeClass('new_li');
        });

    },

    search: function(q, limit, reset) {
        //console.log("search : " + q + " limit = " + limit + " reset = " + reset);
        // if we are searching, bail on scroll
        // are we a new search vs infinite scroll then reset the gif count
        if(reset) {
            GiphySearch.curGifsNum = 0;
            jQuery('#gifs').empty();
        }
        GiphySearch.show_preloader();

        // save the current and previous search terms
        GiphySearch.prevSearchTerm = GiphySearch.curSearchTerm;
        GiphySearch.curSearchTerm = q;
		
		if(q == "giphytrending"){
			// giphy Trending api url
			var url = "https://api.giphy.com/v1/gifs/trending?api_key=" + GiphySearch.API_KEY +
				"&limit=" + limit +
				"&offset=" + GiphySearch.curGifsNum;
		} else{
			// giphy search api url
			var url = "https://api.giphy.com/v1/gifs/search?api_key=" + GiphySearch.API_KEY +
				"&q=" + q +
				//"&type=min" +
				"&limit=" + limit +
				"&offset=" + GiphySearch.curGifsNum;
		}

        // make the ajax call
        var xhr = jQuery.ajax({
            dataType: "json",
            url: url
        });
        xhr.done(function(resp) {
            ////console.log("xhr done " + resp);
            // skip prev responses
            if(GiphySearch.curResponse == resp) { return; }
            GiphySearch.curSearchTerm = q;
            GiphySearch.curLimit = limit;
            // set the previous response to keep out old data
            GiphySearch.prevResponse = GiphySearch.curResponse;
            GiphySearch.curResponse = resp;

            // if this is reset then swap ou
            if(reset) {
                jQuery("#gifs").empty();
                jQuery("#gifs").masonry();
            }
            setTimeout(function() {
                GiphySearch.process_search_response(resp);
                GiphySearch.render();
            },0)

        })
        .fail(function(resp) {
          alert( "error communicating with giphy api! try again later." );
        });
        return xhr;
    }
}
