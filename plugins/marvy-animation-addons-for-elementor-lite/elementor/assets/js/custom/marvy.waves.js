(function( $ ) {
    'use strict';
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];
    var VisualWavesAnimation = {
        initWaves: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', VisualWavesAnimation.initWavesWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', VisualWavesAnimation.initWavesWidget);
        },
        initWavesWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-' + sectionId;
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var wavesAnimationArgs = {};

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        wavesAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                wavesAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }

                    settings.switch = wavesAnimationArgs.marvy_enable_waves_animation;
                    // settings.color = wavesAnimationArgs.marvy_waves_animation_color;
                    settings.shininess = wavesAnimationArgs.marvy_waves_animation_shininess;
                    settings.waveHeight = wavesAnimationArgs.marvy_waves_animation_wave_height;
                    settings.waveSpeed = wavesAnimationArgs.marvy_waves_animation_wave_speed;
                    settings.waveZoom = wavesAnimationArgs.marvy_waves_animation_zoom;
                    if(Object.keys(wavesAnimationArgs).length !== 0){
                        // if(wavesAnimationArgs.__globals__.marvy_waves_animation_color){
                            if(wavesAnimationArgs.__globals__ && wavesAnimationArgs.__globals__.marvy_waves_animation_color && wavesAnimationArgs.__globals__.marvy_waves_animation_color !== ""){
                                var color_id = wavesAnimationArgs.__globals__.marvy_waves_animation_color.split('=')[1];
                                var color_arr = global_color.find(element => element._id  === color_id);
                                settings.color = color_arr.color; 
                            }
                            else{
                                settings.color = wavesAnimationArgs.marvy_waves_animation_color;
                            }
                        // }                                                       
                    } 
                });

            } else {
                settings.switch = $scope.data("marvy_enable_waves_animation");
                settings.color = $scope.data("marvy_waves_animation_color");
                settings.shininess = $scope.data("marvy_waves_animation_shininess");
                settings.waveHeight = $scope.data("marvy_waves_animation_wave_height");
                settings.waveSpeed = $scope.data("marvy_waves_animation_wave_speed");
                settings.waveZoom = $scope.data("marvy_waves_animation_zoom");
            }

            if (settings.switch) {
                wavesAnimation(target, settings, sectionId);
            }
        }
    };

    function wavesAnimation(target,settings,sectionId) {
        var checkElement = document.getElementsByClassName("marvy-waves-section-" + sectionId);
        if (checkElement.length >= 0) {

            var waves_div = document.createElement('div');
            waves_div.classList.add("marvy-waves-section-" + sectionId);

            document.querySelector(target).appendChild(waves_div);
            document.querySelector(target).classList.add("marvy-custom-waves-animation-section-" + sectionId);

            // Set Z-index for section container
            var wavesZindex = document.querySelector('.marvy-custom-waves-animation-section-'+sectionId+' .elementor-container , .marvy-custom-waves-animation-section-'+sectionId+'>*');
            wavesZindex.style.zIndex = '99';

            // Set min height
            var wavesMinHeight = document.querySelector(".elementor-element-"+sectionId);
            wavesMinHeight.closest('.elementor-top-section,.e-con-boxed,.e-con-full').style.minHeight = "200px";

            var waveAnimation = VANTA.WAVES({
                el: ".marvy-waves-section-" + sectionId,
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: settings.color,
                shininess: settings.shininess,
                waveHeight: settings.waveHeight,
                waveSpeed: settings.waveSpeed,
                zoom: settings.waveZoom
            });

            render(waveAnimation,sectionId);

        }
        return true;
    }

    function render(animation,sectionId) {
        document.querySelector(".elementor-element-"+sectionId).addEventListener('DOMAttrModified', function(e){
            animation.resize();
        }, false);
    }

    $( window ).on('elementor/frontend/init', VisualWavesAnimation.initWaves);
})( jQuery );
