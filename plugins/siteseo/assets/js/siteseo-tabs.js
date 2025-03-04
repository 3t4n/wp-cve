jQuery(document).ready(function ($) {
    const features = [
        ["siteseo_titles", "siteseo_titles_home"],
        ["siteseo_xml_sitemap_tab", "siteseo_xml_sitemap_general"],
        ["siteseo_social_tab", "siteseo_social_knowledge"],
        ["siteseo_advanced_tab", "siteseo_advanced_image"],
        ["siteseo_google_analytics_enable", "siteseo_google_analytics_enable"],
        ["siteseo_tool_settings", "siteseo_tool_settings"],
        ["siteseo_instant_indexing_general", "siteseo_instant_indexing_general"],
        ["siteseo_insights_general", "siteseo_insights_general"]
    ];

    features.forEach(function (item) {
        var hash = $(location).attr("hash").split("#tab=")[1];

        if (typeof hash != "undefined") {
            $("#" + hash + "-tab").addClass("nav-tab-active");
            $("#" + hash).addClass("active");
        } else {
            if (
                typeof sessionStorage != "undefined" &&
                typeof sessionStorage != "null"
            ) {
                var siteseo_tab_session_storage =
                    sessionStorage.getItem("siteseo_save_tab");

                if (
                    siteseo_tab_session_storage &&
                    $("#" + siteseo_tab_session_storage + "-tab").length
                ) {
                    $("#siteseo-tabs")
                        .find(".nav-tab.nav-tab-active")
                        .removeClass("nav-tab-active");
                    $("#siteseo-tabs")
                        .find(".siteseo-tab.active")
                        .removeClass("active");

                    $("#" + siteseo_tab_session_storage + "-tab").addClass(
                        "nav-tab-active"
                    );
                    $("#" + siteseo_tab_session_storage).addClass("active");
                } else {
                    //Default TAB
                    $("#tab_" + item[1] + "-tab").addClass("nav-tab-active");
                    $("#tab_" + item[1]).addClass("active");
                }
            }

            $("#siteseo-tabs")
                .find("a.nav-tab")
                .click(function (e) {
                    e.preventDefault();
                    var hash = $(this).attr("href").split("#tab=")[1];

                    $("#siteseo-tabs")
                        .find(".nav-tab.nav-tab-active")
                        .removeClass("nav-tab-active");
                    $("#" + hash + "-tab").addClass("nav-tab-active");

                    sessionStorage.setItem("siteseo_save_tab", hash);

                    $("#siteseo-tabs")
                        .find(".siteseo-tab.active")
                        .removeClass("active");
                    $("#" + hash).addClass("active");
                });
        }
    });

    function siteseo_get_field_length(e) {
        if (e.val().length > 0) {
            meta = e.val() + " ";
        } else {
            meta = e.val();
        }
        return meta;
    }

    let alreadyBind = false;
	
    function siteseo_tab_change_hash(target = ''){
      // Update the URL with the hash after scrolling
      var currentURL = window.location.href.split('#')[0];
      if(target != 'undefined' && target != null && target != ''){
        var newURL = currentURL + target;
        history.pushState(null, '', newURL);
      }
    }

    // Home Binding
    $("#siteseo-tag-site-title").click(function () {
        $("#siteseo_titles_home_site_title").val(
            siteseo_get_field_length($("#siteseo_titles_home_site_title")) +
            $("#siteseo-tag-site-title").attr("data-tag")
        );
    });

    $("#siteseo-tag-site-desc").click(function () {
        $("#siteseo_titles_home_site_title").val(
            siteseo_get_field_length($("#siteseo_titles_home_site_title")) +
            $("#siteseo-tag-site-desc").attr("data-tag")
        );
    });
    $("#siteseo-tag-site-sep").click(function () {
        $("#siteseo_titles_home_site_title").val(
            siteseo_get_field_length($("#siteseo_titles_home_site_title")) +
            $("#siteseo-tag-site-sep").attr("data-tag")
        );
    });

    $("#siteseo-tag-meta-desc").click(function () {
        $("#siteseo_titles_home_site_desc").val(
            siteseo_get_field_length($("#siteseo_titles_home_site_desc")) +
            $("#siteseo-tag-meta-desc").attr("data-tag")
        );
    });

    //Author
    $("#siteseo-tag-post-author").click(function () {
        $("#siteseo_titles_archive_post_author").val(
            siteseo_get_field_length($("#siteseo_titles_archive_post_author")) +
            $("#siteseo-tag-post-author").attr("data-tag")
        );
    });
    $("#siteseo-tag-sep-author").click(function () {
        $("#siteseo_titles_archive_post_author").val(
            siteseo_get_field_length($("#siteseo_titles_archive_post_author")) +
            $("#siteseo-tag-sep-author").attr("data-tag")
        );
    });
    $("#siteseo-tag-site-title-author").click(function () {
        $("#siteseo_titles_archive_post_author").val(
            siteseo_get_field_length($("#siteseo_titles_archive_post_author")) +
            $("#siteseo-tag-site-title-author").attr("data-tag")
        );
    });

    //Date
    $("#siteseo-tag-archive-date").click(function () {
        $("#siteseo_titles_archives_date_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_date_title")) +
            $("#siteseo-tag-archive-date").attr("data-tag")
        );
    });
    $("#siteseo-tag-sep-date").click(function () {
        $("#siteseo_titles_archives_date_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_date_title")) +
            $("#siteseo-tag-sep-date").attr("data-tag")
        );
    });
    $("#siteseo-tag-site-title-date").click(function () {
        $("#siteseo_titles_archives_date_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_date_title")) +
            $("#siteseo-tag-site-title-date").attr("data-tag")
        );
    });

    //Search
    $("#siteseo-tag-search-keywords").click(function () {
        $("#siteseo_titles_archives_search_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_search_title")) +
            $("#siteseo-tag-search-keywords").attr("data-tag")
        );
    });
    $("#siteseo-tag-sep-search").click(function () {
        $("#siteseo_titles_archives_search_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_search_title")) +
            $("#siteseo-tag-sep-search").attr("data-tag")
        );
    });
    $("#siteseo-tag-site-title-search").click(function () {
        $("#siteseo_titles_archives_search_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_search_title")) +
            $("#siteseo-tag-site-title-search").attr("data-tag")
        );
    });

    //404
    $("#siteseo-tag-site-title-404").click(function () {
        $("#siteseo_titles_archives_404_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_404_title")) +
            $("#siteseo-tag-site-title-404").attr("data-tag")
        );
    });
    $("#siteseo-tag-sep-404").click(function () {
        $("#siteseo_titles_archives_404_title").val(
            siteseo_get_field_length($("#siteseo_titles_archives_404_title")) +
            $("#siteseo-tag-sep-404").attr("data-tag")
        );
    });

    //BuddyPress
    $("#siteseo-tag-post-title-bd-groups").click(function () {
        $("#siteseo_titles_bp_groups_title").val(
            siteseo_get_field_length($("#siteseo_titles_bp_groups_title")) +
            $("#siteseo-tag-post-title-bd-groups").attr("data-tag")
        );
    });
    $("#siteseo-tag-sep-bd-groups").click(function () {
        $("#siteseo_titles_bp_groups_title").val(
            siteseo_get_field_length($("#siteseo_titles_bp_groups_title")) +
            $("#siteseo-tag-sep-bd-groups").attr("data-tag")
        );
    });
    $("#siteseo-tag-site-title-bd-groups").click(function () {
        $("#siteseo_titles_bp_groups_title").val(
            siteseo_get_field_length($("#siteseo_titles_bp_groups_title")) +
            $("#siteseo-tag-site-title-bd-groups").attr("data-tag")
        );
    });
    
    var siteseoActiveTabChange = {};
    
    // Siteseo submenu scroll event
    $(window).on('scroll', function() {
      var scrollPos = $(this).scrollTop();
      
      // Loop through each tab content section
      $('.siteseo-sub-tabs a:visible').each(function(){
        var tabId = $(this).attr('href');
        var tabTop = $(tabId).offset().top - 200;

        if(scrollPos >= tabTop){
          
          clearTimeout(siteseoActiveTabChange);
          siteseoActiveTabChange = setTimeout(function(){
          	$('.siteseo-sub-tabs a:visible').removeClass('siteseo-active-sub-tabs');
            $('.siteseo-sub-tabs a[href="' + tabId + '"]').addClass('siteseo-active-sub-tabs');
          
            siteseo_tab_change_hash(tabId);
          }, 100);
          
        }
      });
    });
    
    // Siteseo submenu Click event
    $(".siteseo-sub-tabs a").click(function (event) {  
      event.preventDefault();
      var jEle = $(this);
      var target = this.hash;
      var target_ele = $(target);
      var offset =  target_ele.offset().top-100;
      var scrollDuration = 300;

      jEle.siblings('a').removeClass('siteseo-active-sub-tabs');
      jEle.addClass('siteseo-active-sub-tabs');

      $('html, body').stop().animate({
          'scrollTop': offset
        }, scrollDuration, 'swing', function(){
          siteseo_tab_change_hash(target);
      });
    });

    //All variables
    $(".siteseo-tag-dropdown").each(function (item) {
        const input_title = $(this).parent(".wrap-tags").prev("input");
        const input_desc = $(this).parent(".wrap-tags").prev("textarea");

        const _self = $(this);

        function handleClickLi(current) {
            if (_self.hasClass("tag-title")) {
                input_title.val(
                    siteseo_get_field_length(input_title) +
                    $(current).attr("data-value")
                );
                input_title.trigger("paste");
            }
            if (_self.hasClass("tag-description")) {
                input_desc.val(
                    siteseo_get_field_length(input_desc) +
                    $(current).attr("data-value")
                );
                input_desc.trigger("paste");
            }
        }

        $(this).on("click", function () {
            $(this).next(".siteseo-wrap-tag-variables-list").toggleClass("open");

            $(this)
                .next(".siteseo-wrap-tag-variables-list")
                .find("li")
                .on("click", function (e) {
                    handleClickLi(this);
                    e.stopImmediatePropagation();
                })
                .on("keyup", function (e) {
                    if (e.keyCode === 13) {
                        handleClickLi(this);
                        e.stopImmediatePropagation();
                    }
                });

            function closeItem(e) {
                if (
                    $(e.target).hasClass("dashicons") ||
                    $(e.target).hasClass("siteseo-tag-single-all")
                ) {
                    return;
                }

                alreadyBind = false;
                $(document).off("click", closeItem);
                $(".siteseo-wrap-tag-variables-list").removeClass("open");
            }

            if (!alreadyBind) {
                alreadyBind = true;
                $(document).on("click", closeItem);
            }
        });
    });

    //Instant Indexing: Display keywords counter
    if ($("#siteseo_instant_indexing_manual_batch").length) {
        newLines = $('#siteseo_instant_indexing_manual_batch').val().split("\n").length;
        $('#siteseo_instant_indexing_url_count').text(newLines);
        var lines = 50;
        var linesUsed = $('#siteseo_instant_indexing_url_count');

        if (newLines) {
            var progress = Math.round(newLines / 50 * 100);

            if (progress >= 100) {
                progress = 100;
            }

            $('#siteseo_instant_indexing_url_progress').attr('aria-valuenow', progress),
                $('#siteseo_instant_indexing_url_progress').text(progress + '%'),
                $('#siteseo_instant_indexing_url_progress').css('width', progress + '%')
        }

        $("#siteseo_instant_indexing_manual_batch").on('keyup paste change click focus mouseout', function (e) {


            newLines = $(this).val().split("\n").length;
            linesUsed.text(newLines);

            if (newLines > lines) {
                linesUsed.css('color', 'red');
            } else {
                linesUsed.css('color', '');
            }

            if (newLines) {
                var progress = Math.round(newLines / 50 * 100);
            }

            if (progress >= 100) {
                progress = 100;
            }
            $('#siteseo_instant_indexing_url_progress').attr('aria-valuenow', progress),
                $('#siteseo_instant_indexing_url_progress').text(progress + '%'),
                $('#siteseo_instant_indexing_url_progress').css('width', progress + '%')
        });
    }


    $('#siteseo_instant_indexing_google_action_include[URL_UPDATED]').is(':checked') ? true : false,
        //Instant Indexing: Batch URLs
        $('.siteseo-instant-indexing-batch').on('click', function () {
            $('#siteseo-tabs .spinner').css(
                "visibility",
                "visible"
            );
            $('#siteseo-tabs .spinner').css(
                "float",
                "none"
            );

            $.ajax({
                method: 'POST',
                url: siteseoAjaxInstantIndexingPost.siteseo_instant_indexing_post,
                data: {
                    action: 'siteseo_instant_indexing_post',
                    urls_to_submit: $('#siteseo_instant_indexing_manual_batch').val(),
                    indexnow_api: $('#siteseo_instant_indexing_bing_api_key').val(),
                    google_api: $('#siteseo_instant_indexing_google_api_key').val(),
                    update_action: $('#siteseo_instant_indexing_google_action_include_URL_UPDATED').is(':checked') ? 'URL_UPDATED' : false,
                    delete_action: $('#siteseo_instant_indexing_google_action_include_URL_DELETED').is(':checked') ? 'URL_DELETED' : false,
                    google: $('#siteseo_instant_indexing_engines_google').is(':checked') ? true : false,
                    bing: $('#siteseo_instant_indexing_engines_bing').is(':checked') ? true : false,
                    automatic_submission: $('#siteseo_instant_indexing_automate_submission').is(':checked') ? true : false,
                    _ajax_nonce: siteseoAjaxInstantIndexingPost.siteseo_nonce,
                },
                success: function (data) {
                    window.location.reload(true);
                },
            });
        });

    //Instant Indexing: refresh API Key
    $('.siteseo-instant-indexing-refresh-api-key').on('click', function () {
        $.ajax({
            method: 'POST',
            url: siteseoAjaxInstantIndexingApiKey.siteseo_instant_indexing_generate_api_key,
            data: {
                action: 'siteseo_instant_indexing_generate_api_key',
                _ajax_nonce: siteseoAjaxInstantIndexingApiKey.siteseo_nonce,
            },
            success: function (success) {
                if (success.success === true) {
                    window.location.reload(true);
                }
            },
        });
    });
});
