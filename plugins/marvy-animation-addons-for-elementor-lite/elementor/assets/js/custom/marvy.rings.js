(function( $ ) {
    'use strict';

    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];

    var VisualRingsAnimation = {
        initRings: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', VisualRingsAnimation.initRingsWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', VisualRingsAnimation.initRingsWidget);
        },
        initRingsWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-' + sectionId;
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var ringsAnimationArgs = {};

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        ringsAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                ringsAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }
                  
                    settings.switch = ringsAnimationArgs.marvy_enable_rings_animation;
                    // settings.ringsRandomColor = ringsAnimationArgs.marvy_rings_animation_rings_random_color;
                    // settings.bgColor = ringsAnimationArgs.marvy_rings_animation_background_color;
                    settings.bgOpacity = ringsAnimationArgs.marvy_rings_animation_background_opacity;

                    if(Object.keys(ringsAnimationArgs).length !== 0){
                        // if(ringsAnimationArgs.__globals__.marvy_rings_animation_rings_random_color){
                            if(ringsAnimationArgs.__globals__ && ringsAnimationArgs.__globals__.marvy_rings_animation_rings_random_color && ringsAnimationArgs.__globals__.marvy_rings_animation_rings_random_color !== ""){
                                var ringsRandomColor_id = ringsAnimationArgs.__globals__.marvy_rings_animation_rings_random_color.split('=')[1];
                                var ringsRandomColor_arr = global_color.find(element => element._id  === ringsRandomColor_id);
                                settings.ringsRandomColor = ringsRandomColor_arr.color; 
                            }
                            else{
                                settings.ringsRandomColor = ringsAnimationArgs.marvy_rings_animation_rings_random_color;
                            }  
                        // }
                        // if(ringsAnimationArgs.__globals__.marvy_rings_animation_background_color){
                            if(ringsAnimationArgs.__globals__ && ringsAnimationArgs.__globals__.marvy_rings_animation_background_color && ringsAnimationArgs.__globals__.marvy_rings_animation_background_color !== ""){
                                var bgColor_id = ringsAnimationArgs.__globals__.marvy_rings_animation_background_color.split('=')[1];
                                var bgColor_arr = global_color.find(element => element._id  === bgColor_id);
                                settings.bgColor = bgColor_arr.color; 
                            }
                            else{
                                settings.bgColor = ringsAnimationArgs.marvy_rings_animation_background_color;
                            }
                        // }
                                                                             
                    } 
                });

            } else {
                settings.switch = $scope.data("marvy_enable_rings_animation");
                settings.ringsRandomColor = $scope.data("marvy_rings_animation_rings_random_color");
                settings.bgColor = $scope.data("marvy_rings_animation_background_color");
                settings.bgOpacity = $scope.data("marvy_rings_animation_background_opacity");
            }

            if (settings.switch) {
                ringsAnimation(target, settings, sectionId);
            }
        }
    };

    function ringsAnimation(target,settings,sectionId) {
        var checkElement = document.getElementsByClassName("marvy-rings-section-" + sectionId);
        if (checkElement.length >= 0) {

            var rings_div = document.createElement('div');
            rings_div.classList.add("marvy-rings-section-" + sectionId);

            document.querySelector(target).appendChild(rings_div);
            document.querySelector(target).classList.add("marvy-custom-rings-animation-section-" + sectionId);

            // Set Z-index for section container
            var ringsZindex = document.querySelector('.marvy-custom-rings-animation-section-'+sectionId+' .elementor-container, .marvy-custom-rings-animation-section-'+sectionId+ '>*');
            ringsZindex.style.zIndex = '99';

            // Set min height
            var ringsMinHeight = document.querySelector(".elementor-element-"+sectionId);
            ringsMinHeight.style.minHeight = "200px";

            var ringAnimation = VANTA.RINGS({
                el: ".marvy-rings-section-" + sectionId,
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: settings.ringsRandomColor,
                backgroundColor: settings.bgColor,
                backgroundAlpha: settings.bgOpacity
            });

            render(ringAnimation,sectionId);

        }
        return true;
    }

    function render(animation,sectionId) {
        document.querySelector(".elementor-element-"+sectionId).addEventListener('DOMAttrModified', function(e){
            animation.resize();
        }, false);
    }

    $( window ).on('elementor/frontend/init', VisualRingsAnimation.initRings);
})( jQuery );
