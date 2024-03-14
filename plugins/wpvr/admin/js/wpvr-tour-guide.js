(function( $ ) {
    'use strict';

    $(document).ready(function(){
        var main_tour = new Shepherd.Tour();
       var guide_tranlation = window.wpvr_tour_guide_obj.Tour_Guide_Translation
        main_tour.options.defaults = {
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour shadow-md bg-purple-dark',
            showCancelLink: true,
            useModalOverlay: true,
            scrollTo: true,
            tetherOptions: {
                constraints: [
                    {
                        to: 'scrollParent',
                        attachment: 'together',
                        pin: false
                    }
                ]
            }
        };
        var next_button_text = guide_tranlation.next_button_text;
        var back_button_text = guide_tranlation.previous_button_text;

        //Start Sences guide tour

        main_tour.addStep('tour_title', {
            title: guide_tranlation.tour_title.title,
            text: guide_tranlation.tour_title.text,
            attachTo: '#post-body-content bottom',
            buttons: [
                {
                    classes: 'udp-tour-end',
                    text: guide_tranlation.end_tour,
                    action: main_tour.cancel
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next,
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        })
        main_tour.addStep('scene_section', {
            title:  guide_tranlation.scene_section.title,
            text: guide_tranlation.scene_section.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '.single-scene.rex-pano-tab.active .sceneid right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });

        main_tour.addStep('upload_image', {
            title: guide_tranlation.upload_image.title,
            text: guide_tranlation.upload_image.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '.single-scene.rex-pano-tab.active .scene-upload right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });

        $('.single-scene.rex-pano-tab.active .scene-upload').on('click',function(event){
            main_tour.hide()
            if($(this).parent().find('.wpvr_continue_guide').length == 0 && !main_tour.canceled ){
                $(this).parent().append('<span class="wpvr_continue_guide" >Continue to guide</span>');
            }
        })
        $(document).on('click', "span.wpvr_continue_guide", function() {
            $(this).remove();
            main_tour.show("preview_tour_button")
            $('body').addClass('shepherd-active')
        });




        main_tour.addStep('preview_tour_button', {
            title: guide_tranlation.preview_tour_button.title,
            text: guide_tranlation.preview_tour_button.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#panolenspreview left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('preview_tour_section', {
            title: guide_tranlation.preview_tour_section.title,
            text: guide_tranlation.preview_tour_section.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#wpvr_item_builder__box left',

            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('publish_tour', {
            title: guide_tranlation.publish_tour.title,
            text: guide_tranlation.publish_tour.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#publishing-action left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('sence_end', {
            title: guide_tranlation.sence_end.title,
            text: "Now your tour is ready to be published on your website.\n" +
                "\n" +
                "To learn how to publish it on your website,<a href='https://rextheme.com/docs/wp-vr-wpvr-shortcode-embed-virtual-tour/' target='_blank'> follow this detailed documentation.\n</a>" +
                "\n" +
                " To continue customizing this tour, click on Next.",
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '.rex-pano-tabs',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: "Start Customizing",
                    // action: main_tour.next
                    action: function() {
                        main_tour.next()
                        $(".rex-pano-nav-menu.main-nav ul li.hotspot span").trigger('click')
                    }
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });

        // End scenes Tour Guide

        // Start Hotspot
        main_tour.addStep('hotspot_start', {
            title: guide_tranlation.hotspot_start.title,
            text: guide_tranlation.hotspot_start.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#wpvr-main-nav .hotspot right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                        $(".rex-pano-nav-menu.main-nav ul li.scene span").trigger('click')
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('hotspot_id', {
            title: guide_tranlation.hotspot_id.title,
            text: guide_tranlation.hotspot_id.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-setting  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    // action: main_tour.next
                    action: function() {
                       var post_ID = $("#post_ID").val();
                        $(".pnlm-ui.pnlm-grab").trigger('click');
                        main_tour.next()

                    }
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('choose_previwer', {
            title: guide_tranlation.choose_previwer.title,
            text: guide_tranlation.choose_previwer.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#wpvr_item_builder__box left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('assigin_pitch_yaw', {
            title: guide_tranlation.assigin_pitch_yaw.title,
            text: guide_tranlation.assigin_pitch_yaw.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#panodata  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('pitch_yaw_set', {
            title: guide_tranlation.pitch_yaw_set.title,
            text: guide_tranlation.pitch_yaw_set.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-pitch  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('pitch_yaw_set_2', {
            title: guide_tranlation.pitch_yaw_set_2.title,
            text: guide_tranlation.pitch_yaw_set_2.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-yaw  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('on_click_content_info', {
            title: guide_tranlation.on_click_content_info.title,
            text: guide_tranlation.on_click_content_info.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-type.hotspot-setting:not(.hotspot-hover)  top',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('on_hover_info', {
            title: guide_tranlation.on_hover_info.title,
            text: guide_tranlation.on_hover_info.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#scene-1-hotspot-1 .hotspot-hover  top',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('preview_on_hotspot', {
            title: guide_tranlation.preview_on_hotspot.title,
            text: guide_tranlation.preview_on_hotspot.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#panolenspreview  right',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: next_button_text,
                    action: main_tour.next
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        main_tour.addStep('save_process_hotspot', {
            title: guide_tranlation.save_process_hotspot.title,
            text: guide_tranlation.save_process_hotspot.text,
            classes: 'shepherd-theme-arrows-plain-buttons shepherd-main-tour super-index',
            attachTo: '#publish  left',
            buttons: [
                {
                    classes: 'udp-tour-back',
                    text: back_button_text,
                    action: function() {
                        main_tour.back();
                    }
                },
                {
                    classes: 'button button-primary',
                    text: guide_tranlation.done_text,
                    action: main_tour.complete
                }
            ],
            when: {
                show: function() {
                    scroll_to_popup();
                }
            }
        });
        //End Hotspot
        /**
         * Scroll to Popup
         *
         * @param {Object} step
         */
        var scroll_to_popup = function(step) {
            main_tour.going_somewhere = false;
            if (!step) {
                step = main_tour.getCurrentStep();
            }
            var popup = $(step.el);
            var target = $(step.tether.target);
            $('body, html').animate({
                scrollTop: popup.offset().top - 50
            }, 500, function() {
                window.scrollTo(0, popup.offset().top - 50);
            });

        }
        main_tour.start();
        main_tour.on('cancel', cancel_tour);

        /**
         * Cancel tour
         */
        function cancel_tour() {
            // The tour is either finished or [x] was clicked
            main_tour.canceled = true;
           var get_param =  getParameterByName("wpvr-guide-tour");
           if(get_param == "1"){
               var newUrl = updateParam("wpvr-guide-tour",0);
               if (window.history != 'undefined' && window.history.pushState != 'undefined') {
                   window.history.pushState({ path: newUrl }, '', newUrl);
               }
           }
        };

        /**
         * Get URL parameter By name
         */
        function getParameterByName(name, url = window.location.href) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
        /**
         * Delete parameter By name
         */
        function updateParam (name,value, url = window.location.href){
            var url = new URL(url);
            var search_params = url.searchParams;
            search_params.delete(name);

            url.search = search_params.toString();

            var new_url = url.toString();

            return new_url;
        }

    })

})( jQuery );