jQuery(document).ready(function ($) {

    var get_hash = window.location.hash;
    var clean_hash = get_hash.split('$');

    if (typeof sessionStorage != 'undefined') {
        var siteseo_tab_session_storage = sessionStorage.getItem("siteseo_robots_tab");

        if (clean_hash[1] == '1') { //Robots Tab
            $('#tab_siteseo_robots-tab').addClass("nav-tab-active");
            $('#tab_siteseo_robots').addClass("active");
        } else if (clean_hash[1] == '2') { //htaccess Tab
            $('#tab_siteseo_htaccess-tab').addClass("nav-tab-active");
            $('#tab_siteseo_htaccess').addClass("active");
        } else if (clean_hash[1] == '3') { //White Label Tab
            $('#tab_siteseo_white_label-tab').addClass("nav-tab-active");
            $('#tab_siteseo_white_label').addClass("active");
        } else if (siteseo_tab_session_storage) {
            $('#siteseo-tabs').find('.nav-tab.nav-tab-active').removeClass("nav-tab-active");
            $('#siteseo-tabs').find('.siteseo-tab.active').removeClass("active");

            $('#' + siteseo_tab_session_storage.split('#tab=') + '-tab').addClass("nav-tab-active");
            $('#' + siteseo_tab_session_storage.split('#tab=')).addClass("active");
        } else {
            //Default TAB
            $('#tab_siteseo_robots-tab').addClass("nav-tab-active");
            $('#tab_siteseo_robots').addClass("active");
        }
    };
    $("#siteseo-tabs").find("a.nav-tab").click(function (e) {
        e.preventDefault();
        var hash = $(this).attr('href').split('#tab=')[1];

        $('#siteseo-tabs').find('.nav-tab.nav-tab-active').removeClass("nav-tab-active");
        $('#' + hash + '-tab').addClass("nav-tab-active");

        if (clean_hash[1] == 1) {
            sessionStorage.setItem("siteseo_robots_tab", 'tab_siteseo_robots');
        } else if (clean_hash[1] == 2) {
            sessionStorage.setItem("siteseo_robots_tab", 'tab_siteseo_htaccess');
        } else if (clean_hash[1] == 3) {
            sessionStorage.setItem("siteseo_white_label", 'tab_siteseo_white_label');
        } else {
            sessionStorage.setItem("siteseo_robots_tab", hash);
        }

        $('#siteseo-tabs').find('.siteseo-tab.active').removeClass("active");
        $('#' + hash).addClass("active");
    });
    //Robots
    $('#siteseo-tag-robots-1, #siteseo-tag-robots-2, #siteseo-tag-robots-3, #siteseo-tag-robots-4, #siteseo-tag-robots-5, #siteseo-tag-robots-6, #siteseo-tag-robots-7, #siteseo-tag-robots-8').click(function () {
        $(".siteseo_robots_file").val($(".siteseo_robots_file").val() + '\n' + $(this).attr('data-tag'));
    });
    //Flush permalinks
    $('#siteseo-flush-permalinks2').on('click', function () {
        $.ajax({
            method: 'GET',
            url: siteseoAjaxResetPermalinks.siteseo_ajax_permalinks,
            data: {
                action: 'siteseo_flush_permalinks',
                _ajax_nonce: siteseoAjaxResetPermalinks.siteseo_nonce,
            },
            success: function (data) {
                window.location.reload(true);
            },
        });
    });
    $('#siteseo-flush-permalinks2').on('click', function () {
        $(this).attr("disabled", "disabled");
        $('.spinner').css("visibility", "visible");
        $('.spinner').css("float", "none");
    });
});
