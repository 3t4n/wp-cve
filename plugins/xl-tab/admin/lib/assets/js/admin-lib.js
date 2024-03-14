(function ($) {

   'use strict';

    var XLTAB;

    XLTAB = {

        init: function () {

            window.elementor.on(
                'document:loaded',
                window._.bind(XLTAB.onPreviewLoaded, XLTAB)
            );
        },

        onPreviewLoaded: function () {

            var main_wrap = $('#elementor-preview-iframe').contents();
            var wrapper_html = "<div style='display:none;' class='xltab-lib-wrap'>"
                                    +"<div class='lib-inner'>"
                                        +"<div class='header'>"
                                            +"<div class='lhead'>"
                                                +"<h2 class='lib-logo'>Library</h2>"
                                            +"</div>"
                                            +"<div class='rhead'>"
                                                +"<i class='eicon-sync'></i>"
                                                +"<i class='lib-close eicon-close'></i>"
                                            +"</div>"                                            
                                        +"</div>"
                                        +"<div class='lib-inner'>"
                                            +"<div class='lib-content'>"
                                            +"</div>"
                                            +"<div class='xl-loader'>"
                                                +"<div class='loader'>"
                                                +"</div>"
                                            +"</div>"
                                        +"</div>"
                                    +"</div>"
                                    +"<div class='xl-settings'></div>"
                                +"</div>";

            main_wrap.find('.elementor-add-template-button').after("<div class='elementor-add-section-area-button xltab-add-section-btn' style='background:black;margin-left:10px;'><i class='eicon-accordion'></i></div>");

            $('#elementor-editor-wrapper').append(wrapper_html);

            main_wrap.find('.xltab-add-section-btn').click(function(){

                $('#elementor-editor-wrapper').find('.xltab-lib-wrap').show();
                var ajax_data = {
                    page : '1',
                    category:'',
                };
                process_data(ajax_data);

            });

            $(document).on('click', '.xltab-lib-wrap .insert-tmpl', function(e) {
                var tmpl_id = $(this).data('id');
                $('.xl-loader').show();
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                      action: 'xl_tab_import_template',
                      id: tmpl_id,
                    },
                    success: function(data, textStatus, XMLHttpRequest) {
                        var xl_data = JSON.parse(data); 
                        elementor.getPreviewView().addChildModel(xl_data, {silent: 0});
                        $('.xl-loader').hide();
                        $('#elementor-editor-wrapper').find('.xltab-lib-wrap').hide();
                    },
                  });
            });

            $(document).on('click', '.xltab-lib-wrap .rhead .eicon-sync', function(e) {
                $('.xl-loader').show();
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                      action: 'xl_tab_reload_template',
                    },
                    success: function(data, textStatus, XMLHttpRequest) {
                        $('.xl-loader').hide();
                        var ajax_data = {
                            page : '1',
                            category:'',
                        };
                        process_data(ajax_data);                        
                    },
                  });
            });

            $(document).on('click', '.xltab-lib-wrap .lib-img-wrap', function(e) {
                var live_link = $(this).data('preview');
                var win = window.open( live_link, '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                } else {
                    //Browser has blocked it
                    alert('Please allow popups for this website');
                } 
            });

            $(document).on('click', '.xltab-lib-wrap .page-link', function(e) {
                $('.xl-loader').show();
                var page_no = $(this).data('page-number');
                var category = $('#elementor-editor-wrapper').find('.xl-settings').attr('data-catsettings');
                $('#elementor-editor-wrapper').find('.xl-settings').attr('data-pagesettings', page_no);
                var ajax_data = {
                    page: page_no,
                    category: category,
                };
                process_data(ajax_data);
            });

            $(document).on('click', '.xltab-lib-wrap .filter-wrap a', function(e) {
                var category = $(this).data('cat');
                $('#elementor-editor-wrapper').find('.xl-settings').attr('data-catsettings', category);
                $('.xl-loader').show();
                var ajax_data = {
                    page : '1',
                    category:category,
                };
                process_data(ajax_data);
            });

            function process_data($data){

                  $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                      action: 'xltab_process_ajax',
                      category: $data['category'],
                      page:$data['page'],
                    },

                    beforeSend: function() {

                        $(".lib-inner").animate({ scrollTop: 0 }, "slow");
                    },

                    success: function(data, textStatus, XMLHttpRequest) {
                        $('.xl-loader').hide();
                        $('.lib-content').html(data);

                    $('.item-wrap').masonry({
                        itemSelector: '.item',
                        isAnimated: false,
                        transitionDuration: 0
                    });

                    $('.item-wrap').masonry('reloadItems');
                    $('.item-wrap').masonry('layout');

                    $('.item-wrap').imagesLoaded( function() {
                      $('.item-wrap').masonry('layout');
                    });

                    },

                  });

            }

            $('#elementor-editor-wrapper').find('.lib-close').click(function(){
                $('#elementor-editor-wrapper').find('.xltab-lib-wrap').hide();
                $('.lib-content').show();
                $('.back-to-home').hide();
            });

        },

    };

    $(window).on('elementor:init', XLTAB.init);

})(jQuery);