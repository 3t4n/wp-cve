jQuery(document).ready(function ($) {
    //If no notices
    if (!$.trim($("#siteseo-notifications-center").html())) {
        $('#siteseo-notifications-center').remove();
    }
    const notices = [
        "notice-get-started",
        "notice-usm",
        "notice-wizard",
        "notice-insights-wizard",
        "notice-seo-consultant",
        "notice-amp-analytics",
        "notice-tagdiv",
        "notice-divide-comments",
        "notice-review",
        "notice-trailingslash",
        "notice-posts-number",
        "notice-rss-use-excerpt",
        "notice-ga-ids",
        "notice-search-console",
        "notice-google-business",
        "notice-ssl",
        "notice-title-tag",
        "notice-enfold",
        "notice-themes",
        "notice-page-builders",
        "notice-go-pro",
        "notice-noindex",
        "notice-tasks",
        "notice-insights",
        "notice-robots-txt",
        "notice-robots-txt-valid",
    ]
    notices.forEach(function (item) {
        $('#' + item).on('click', function () {
            $('#' + item).attr('data-notice', $('#' + item).attr('data-notice') == '1' ? '0' : '1');
            $.ajax({
                method: 'POST',
                url: siteseoAjaxHideNotices.siteseo_hide_notices,
                data: {
                    action: 'siteseo_hide_notices',
                    notice: item,
                    notice_value: $('#' + item).attr('data-notice'),
                    _ajax_nonce: siteseoAjaxHideNotices.siteseo_nonce,
                },
                success: function (data) {
                    $('#siteseo-notice-save').css('display', 'block');
                    $('#siteseo-notice-save .html').html('Notice successfully removed');
                    $('#' + item + '-alert').fadeOut();
                    $('#siteseo-notice-save').delay(3500).fadeOut();
                },
            });
        });
    });

    const features = [
        "titles",
        "xml-sitemap",
        "social",
        "google-analytics",
        "instant-indexing",
        "advanced",
        "local-business",
        "woocommerce",
        "edd",
        "dublin-core",
        "rich-snippets",
        "breadcrumbs",
        "inspect-url",
        "robots",
        "news",
        "404",
        "bot",
        "rewrite",
        "white-label",
        "ai"
    ]
    features.forEach(function (item) {
        $('#toggle-' + item).on('click', function () {
            $('#toggle-' + item).attr('data-toggle', $('#toggle-' + item).attr('data-toggle') == '1' ? '0' : '1');

            $(this).siblings('#titles-state-default').toggleClass('feature-state-off');
            $(this).siblings('#titles-state').toggleClass('feature-state-off');

            $.ajax({
                method: 'POST',
                url: siteseoAjaxToggleFeatures.siteseo_toggle_features,
                data: {
                    action: 'siteseo_toggle_features',
                    feature: 'toggle-' + item,
                    feature_value: $('#toggle-' + item).attr('data-toggle'),
                    _ajax_nonce: siteseoAjaxToggleFeatures.siteseo_nonce,
                },
                success: function () {
                    window.history.pushState("", "", window.location.href + "&settings-updated=true");
                    $('#siteseo-notice-save').show();
                    $('#siteseo-notice-save').delay(3500).fadeOut();
                    window.history.pushState("", "", window.location.href)
                },
            });
        });
    });
    $('#siteseo-activity-panel button').on('click', function () {
        
        // Toggle help and display menu
        if($(this).attr('id') == 'activity-panel-tab-expand'){
          $('.hide-panel').toggle(200);
          $(this).find('span').toggleClass('btn-rotate');
          return;
        }
        $(this).toggleClass('is-active');
        $('#siteseo-activity-panel-' + $(this).data('panel')).toggleClass('is-open');
    });
    
    $('#siteseo-activity-panel .siteseo-close-panel').on('click', function () {
      $('#siteseo-activity-panel-' + $(this).data('panel')).toggleClass('is-open');
    });

    $('#siteseo-content').on('click', function () {
        $('#siteseo-activity-panel').find('.is-open').toggleClass('is-open');
        $('#siteseo-activity-panel').find('.is-active').toggleClass('is-active');
    });
    $('body').on('click', '.siteseo-item-toggle-options', function (e) {
    	e.stopPropagation();
        $(this).next('.siteseo-card-popover').toggleClass('is-open');
    });

    $('#siteseo-news-items').on('click', function () {
        $.ajax({
            method: 'POST',
            url: siteseoAjaxNews.siteseo_news,
            data: {
                action: 'siteseo_news',
                news_max_items: $('#news_max_items').val(),
                _ajax_nonce: siteseoAjaxNews.siteseo_nonce,
            },
            success: function (data) {
                $('#siteseo-news-panel .siteseo-card-content').load(' #siteseo-news-panel .siteseo-card-content');
                $('#siteseo-news-panel .siteseo-card-popover').toggleClass('is-open');
            },
        });
    });
    $('#siteseo_news').on('click', function () {
        $('#siteseo-news-panel').toggleClass('is-active');
        $('#siteseo_news').attr('data-toggle', $('#siteseo_news').attr('data-toggle') == '1' ? '0' : '1');
        $.ajax({
            method: 'POST',
            url: siteseoAjaxDisplay.siteseo_display,
            data: {
                action: 'siteseo_display',
                news_center: $('#siteseo_news').attr('data-toggle'),
                _ajax_nonce: siteseoAjaxDisplay.siteseo_nonce,
            },
        });
    });
    $('#siteseo_tools').on('click', function () {
        $('#notice-insights-alert').toggleClass('is-active');
        $('#siteseo_tools').attr('data-toggle', $('#siteseo_tools').attr('data-toggle') == '1' ? '0' : '1');
        $.ajax({
            method: 'POST',
            url: siteseoAjaxDisplay.siteseo_display,
            data: {
                action: 'siteseo_display',
                tools_center: $('#siteseo_tools').attr('data-toggle'),
                _ajax_nonce: siteseoAjaxDisplay.siteseo_nonce,
            },
        });
    });
    $('#notifications_center').on('click', function () {
        $('#siteseo-notifications-center').toggleClass('is-active');
        $('#notifications_center').attr('data-toggle', $('#notifications_center').attr('data-toggle') == '1' ? '0' : '1');
        $.ajax({
            method: 'POST',
            url: siteseoAjaxDisplay.siteseo_display,
            data: {
                action: 'siteseo_display',
                notifications_center: $('#notifications_center').attr('data-toggle'),
                _ajax_nonce: siteseoAjaxDisplay.siteseo_nonce,
            },
        });
    });
	
    $('body').on('click','.siteseo-card-title .siteseo-drag-icon-container .dashicons-arrow-up-alt2',function(e){
      e.stopPropagation();
      var jEle = $(this).closest('.siteseo-card');
      var tEle = jEle.prevAll().filter('.siteseo-card');
      
      if(tEle.length > 0){
        var $wrapper = $('<div></div>'); // Create a temporary wrapper element
        jEle.appendTo($wrapper); // Append the container to the wrapper
        
        var containerHTML = $wrapper.prop('innerHTML'); // Get the HTML content of the wrapper
        $(containerHTML).insertBefore(tEle.get(0));
        siteseo_blur_focus_event();
      }
    });

    $('body').on('click','.siteseo-card-title .siteseo-drag-icon-container .dashicons-arrow-down-alt2',function(e){
      e.stopPropagation();
      var jEle = $(this).closest('.siteseo-card');
      var tEle = jEle.nextAll().filter('.siteseo-card');
		
      if(tEle.length > 0){
        var $wrapper = $('<div></div>'); // Create a temporary wrapper element
        jEle.appendTo($wrapper); // Append the container to the wrapper
		  
        var containerHTML = $wrapper.prop('innerHTML'); // Get the HTML content of the wrapper
        $(containerHTML).insertAfter(tEle.get(0));
        siteseo_blur_focus_event();
      }
    });

    $('body').on('click','.siteseo-card-title.ui-sortable-handle',function(e){
      e.stopPropagation();
      $(this).find('.dashicons-controls-play').click();
    });

    $('body').on('click','.siteseo-card-title .siteseo-drag-icon-container .dashicons-controls-play',function(e)	{
      e.stopPropagation();
      var jEle = $(this).closest('.siteseo-card').children('div:not(.siteseo-card-title)');
      jEle.slideToggle();
      $(this).toggleClass('rotate-element');
    });
    
    siteseo_blur_focus_event();
});

function siteseo_blur_focus_event(){
  jQuery('.siteseo-drag-icon-container').each(function(index, element){
	var jEle = jQuery(element).closest('.siteseo-card');
	var prev = jEle.prevAll().filter('.siteseo-card');
	var next = jEle.nextAll().filter('.siteseo-card');
	
	if(prev.length <= 0 ){
	  jQuery('.siteseo-drag-icon-container .dashicons-arrow-up-alt2').removeClass('siteseo-blur-icon');
	  jEle.find('.dashicons-arrow-up-alt2').addClass('siteseo-blur-icon');
	}
	
	if(next.length <= 0){
	  jQuery('.siteseo-drag-icon-container .dashicons-arrow-down-alt2').removeClass('siteseo-blur-icon');
	  jEle.find('.dashicons-arrow-down-alt2').addClass('siteseo-blur-icon');
	}
  });
}
    

//SEO Tools Tabs
jQuery(document).ready(function ($) {
    var get_hash = window.location.hash;
    var clean_hash = get_hash.split('$');

    if (typeof sessionStorage != 'undefined') {
        var siteseo_admin_tab_session_storage = sessionStorage.getItem("siteseo_admin_tab");

        if (clean_hash[1] == '1') { //Analytics Tab
            $('#tab_siteseo_analytics-tab').addClass("nav-tab-active");
            $('#tab_siteseo_analytics').addClass("active");
        } else if (clean_hash[1] == '2') { //Matomo Tab
            $('#tab_siteseo_matomo-tab').addClass("nav-tab-active");
            $('#tab_siteseo_matomo').addClass("active");
        } else if (clean_hash[1] == '3') { //Page Speed Tab
            $('#tab_siteseo_ps-tab').addClass("nav-tab-active");
            $('#tab_siteseo_ps_tools').addClass("active");
        } else if (siteseo_admin_tab_session_storage) {
            $('#siteseo-admin-tabs').find('.nav-tab.nav-tab-active').removeClass("nav-tab-active");
            $('#siteseo-admin-tabs').find('.siteseo-tab.active').removeClass("active");
            $('#' + siteseo_admin_tab_session_storage.split('#tab=') + '-tab').addClass("nav-tab-active");
            $('#' + siteseo_admin_tab_session_storage.split('#tab=')).addClass("active");
        } else {
            //Default TAB
            $('#siteseo-admin-tabs a.nav-tab').first().addClass("nav-tab-active");
            $('#siteseo-admin-tabs .wrap-siteseo-tab-content > div').first().addClass("active");
        }
    };
    $("#siteseo-admin-tabs").find("a.nav-tab").click(function (e) {
        e.preventDefault();
        var hash = $(this).attr('href').split('#tab=')[1];

        $('#siteseo-admin-tabs').find('.nav-tab.nav-tab-active').removeClass("nav-tab-active");
        $('#' + hash + '-tab').addClass("nav-tab-active");

        if (clean_hash[1] == 1) {
            sessionStorage.setItem("siteseo_admin_tab", 'tab_siteseo_analytics');
        } else if (clean_hash[1] == 2) {
            sessionStorage.setItem("siteseo_admin_tab", 'tab_siteseo_matomo');
        } else if (clean_hash[1] == 3) {
            sessionStorage.setItem("siteseo_admin_tab", 'tab_siteseo_ps_tools');
        } else {
            sessionStorage.setItem("siteseo_admin_tab", hash);
        }

        $('#siteseo-admin-tabs').find('.siteseo-tab.active').removeClass("active");
        $('#' + hash).addClass("active");
    });

    //Drag and drop for cards
    $(".siteseo-dashboard-columns .siteseo-dashboard-column:first-child").sortable({
        items: ".siteseo-card",
        placeholder: "siteseo-dashboard-card-highlight",
        cancel: ".siteseo-intro, .siteseo-card-popover",
        handle: ".siteseo-card-title",
        opacity: 0.9,
        forcePlaceholderSize: true,
        update: function (e) {
            const item = jQuery(e.target);

            var postData = item.sortable("toArray", {
                attribute: "id",
            });

            $.ajax({
                method: "POST",
                url: siteseoAjaxDndFeatures.siteseo_dnd_features,
                data: {
                    action: "siteseo_dnd_features",
                    order: postData,
                    _ajax_nonce: siteseoAjaxDndFeatures.siteseo_nonce,
                },
                success: function(response){
                    siteseo_blur_focus_event();
                }
            });
        },
    });
});
