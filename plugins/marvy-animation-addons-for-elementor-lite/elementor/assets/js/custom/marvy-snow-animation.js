(function ($) {
    'use strict';
    var previousColorSnow = {};
    var styleColorSnow = document.createElement('style');
    styleColorSnow.setAttribute('data-description', 'snow-css');
    var sheetColorSnow = document.head.appendChild(styleColorSnow).sheet;
    var styleKeyframeSnow = document.createElement('style')
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];
    var MarvyColorSnowAnimation = {
        initColorSnow: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', MarvyColorSnowAnimation.initColorSnowWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', MarvyColorSnowAnimation.initColorSnowWidget);
        },
        initColorSnowWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-' + sectionId;
            document.querySelector(target).classList.remove('marvy-snow-animtion-section');
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {

                var colorSnowEditorElements = null;
                var colorSnowAnimationArgs = {};
                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }
                colorSnowEditorElements = window.elementor.elements;
                if (!colorSnowEditorElements.models) {
                    return false;
                }

                $.each(colorSnowEditorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        colorSnowAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                colorSnowAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }

                    settings.switch = colorSnowAnimationArgs.marvy_enable_snow_animation;
                    settings.count = colorSnowAnimationArgs.marvy_snow_animation_count;
                    settings.size = colorSnowAnimationArgs.marvy_snow_animation_size;
                    // settings.color = colorSnowAnimationArgs.marvy_snow_animation_color;
                    // settings.shadow_color = colorSnowAnimationArgs.marvy_snow_animation_shadow_color;
                    settings.shadow_size = colorSnowAnimationArgs.marvy_snow_animation_shadow_size;
                    if(Object.keys(colorSnowAnimationArgs).length !== 0){
                        // if(colorSnowAnimationArgs.__globals__.marvy_snow_animation_shadow_color){
                            if(colorSnowAnimationArgs.__globals__ && colorSnowAnimationArgs.__globals__.marvy_snow_animation_shadow_color && colorSnowAnimationArgs.__globals__.marvy_snow_animation_shadow_color !== ""){
                                var shadow_color_id = colorSnowAnimationArgs.__globals__.marvy_snow_animation_shadow_color.split('=')[1];
                                var shadow_color_arr = global_color.find(element => element._id  === shadow_color_id);
                                settings.shadow_color = shadow_color_arr.color; 
                            }
                            else{
                                settings.shadow_color = colorSnowAnimationArgs.marvy_snow_animation_shadow_color;
                            }
                        // }
                        
                        // if(colorSnowAnimationArgs.__globals__.marvy_snow_animation_color){
                            if(colorSnowAnimationArgs.__globals__ && colorSnowAnimationArgs.__globals__.marvy_snow_animation_color && colorSnowAnimationArgs.__globals__.marvy_snow_animation_color !== ""){
                                var color_id = colorSnowAnimationArgs.__globals__.marvy_snow_animation_color.split('=')[1];
                                var color_arr = global_color.find(element => element._id  === color_id);
                                settings.color = color_arr.color; 
                            }
                            else{
                                settings.color = colorSnowAnimationArgs.marvy_snow_animation_color;
                            }
                        // }
                                                                           
                    } 
                });
            } else {
                settings.switch = $scope.data('marvy_enable_snow_animation');
                settings.count = $scope.data('marvy_snow_animation_count');
                settings.size = $scope.data('marvy_snow_animation_size');
                settings.color = $scope.data('marvy_snow_animation_color');
                settings.shadow_color = $scope.data('marvy_snow_animation_shadow_color');
                settings.shadow_size = $scope.data('marvy_snow_animation_shadow_size');
            }
            if (settings.switch) {
                var sectionKey = 'colorSnow-' + sectionId;
                if (!previousColorSnow.hasOwnProperty(sectionKey)) {
                    previousColorSnow[sectionKey] = settings;
                }
                var result = colorSnowAnimation(target, settings, sectionId, sectionKey);
                if (result) {
                    previousColorSnow[sectionKey] = settings;
                }
            } else {
                previousColorSnow = {};
                if (sheetColorSnow.cssRules.length !== 0){
                    for (var j = sheetColorSnow.cssRules.length - 1; j >= 0; j--) {
                        if(sheetColorSnow.cssRules[j].selectorText.includes(sectionId) ){
                            sheetColorSnow.deleteRule(j);
                        }
                    }
                }
            }
        }
    };

    $(window).on('elementor/frontend/init', MarvyColorSnowAnimation.initColorSnow);

    function addRule(selector, css, i) {
        var propText = typeof css === "string" ? css : Object.keys(css).map(function (p) {
            return p + ":" + (p === "content" ? "'" + css[p] + "'" : css[p]);
        }).join(";");
        sheetColorSnow.insertRule(selector + "{" + propText + "}", i);
    }

    function randomIntFromInterval(max, min = 1) { // min and max included
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    function colorSnowAnimation(target, settings, sectionId, sectionKey) {
        var checkElement = document.getElementsByClassName("marvy-snow-" + sectionId);
        document.querySelector(target).classList.add("marvy-snow-animtion-section");
        if (checkElement.length <= 0) {
            document.querySelector(target).classList.add('marvy-custom-snow-section-' + sectionId);
            var snowChild = document.querySelector(target + " .marvy-snow-" + sectionId);
            var childCount = (document.querySelector(target) ? document.querySelector(target).childElementCount : 0) - (snowChild ? snowChild.length : 0);
            childCount = childCount >= 0 ? childCount : 0;

            var zIndex = document.querySelector('.marvy-custom-snow-section-' + sectionId + ' .elementor-container , .marvy-custom-snow-section-' + sectionId + '>*');
            zIndex.style.zIndex = '99';
            zIndex.style.width = '100%';

            var appendColeSnowRule = true;
            if (JSON.stringify(previousColorSnow[sectionKey]) !== JSON.stringify(settings)) {
                appendColeSnowRule = false;
                for (let j = sheetColorSnow.cssRules.length - 1; j >= 0; j--) {
                    if (sheetColorSnow.cssRules[j].selectorText.includes(sectionId)) {
                        sheetColorSnow.deleteRule(j);
                    }
                    if (j === 0) {
                        appendColeSnowRule = true;
                        previousColorSnow[sectionKey] = settings;
                    }
                }
            }
            var i = 1;
            while (i <= settings.count) {
                var child_color_snow_div_el = document.createElement('div');
                child_color_snow_div_el.classList.add("marvy-snow-" + sectionId);
                document.querySelector(target).appendChild(child_color_snow_div_el);

                let random_x = randomIntFromInterval(1000000, 0) * 0.0001;
                let random_offset = randomIntFromInterval(100000, -100000) * 0.0001;
                let random_x_end = random_x + random_offset;
                let random_x_end_yoyo = random_x + (random_offset / 2);
                let random_yoyo_time = randomIntFromInterval(80000, 30000) * 0.00001;
                let random_yoyo_y = random_yoyo_time * 100;
                let random_scale = randomIntFromInterval(10000, 0) * 0.0001;
                let fall_duration = randomIntFromInterval(30, 10);
                let fall_delay = randomIntFromInterval(30, 0);
                styleKeyframeSnow.append("@keyframes fall-" + sectionId + "-" + i + " {" + (randomIntFromInterval(30000, 80000) / 1000).toString() + "% { transform: translate(" + random_x_end + "vw, " + random_yoyo_y + "vh) scale(" + random_scale + "); } to { transform : translate(" + random_x_end_yoyo + "vw, 100vh) scale(" + random_scale + "); }}");
                if (appendColeSnowRule) {
                    +addRule(".marvy-snow-" + sectionId + ":nth-child(" + (i + childCount) + ")", {
                        opacity: randomIntFromInterval(10000, 1) * 0.0001,
                        transform: "translate(" + random_x + "vw, -10px) scale(" + random_scale + ")",
                        animation: "fall-" + sectionId + "-" + i + " " + fall_duration + "s " + fall_delay + "s linear infinite"
                    });
                }
                i++;
            }
            document.head.append(styleKeyframeSnow);
            if (appendColeSnowRule) {
                addRule(".marvy-snow-" + sectionId, {
                    position: "absolute",
                    top: '-'+settings.size+'px',
                    height: settings.size + 'px',
                    width: settings.size + 'px',
                    "background-color": settings.color,
                    'border-radius': '50%',
                    "box-shadow": '0 0 '+settings.shadow_size+'px ' + settings.shadow_color
                });
            }
        }
    }
})(jQuery);