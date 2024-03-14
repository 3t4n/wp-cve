(function ($) {
    'use strict';
    var previousRipple = {};
    var currentRipple = {};
    var styleRipple = document.createElement('style');
    var sheetRipple = document.head.appendChild(styleRipple).sheet;
    var i, j, hw_gap, left, right, top, bottom, eleft, eright, etop, ebottom;
    var parentHeight = 0, parentWidth = 0;
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];
    var VisualRipplesAnimation = {
        initRipples: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', VisualRipplesAnimation.initRipplesWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', VisualRipplesAnimation.initRipplesWidget);
        },
        initRipplesWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-' + sectionId;
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var rippleAnimationArgs = {};

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        rippleAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                rippleAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }
                   
                    settings.switch = rippleAnimationArgs.marvy_enable_ripples_animation;
                    // settings.color = rippleAnimationArgs.marvy_ripples_animation_circle_color;
                    settings.position = rippleAnimationArgs.marvy_ripples_animation_circle_position;
                    settings.size = rippleAnimationArgs.marvy_ripples_animation_circle_size;
                    if(Object.keys(rippleAnimationArgs).length !== 0){
                        // if(rippleAnimationArgs.__globals__.marvy_ripples_animation_circle_color){
                            if(rippleAnimationArgs.__globals__ && rippleAnimationArgs.__globals__.marvy_ripples_animation_circle_color && rippleAnimationArgs.__globals__.marvy_ripples_animation_circle_color !== ""){
                                var color_id = rippleAnimationArgs.__globals__.marvy_ripples_animation_circle_color.split('=')[1];
                                var color_arr = global_color.find(element => element._id  === color_id);
                                settings.color = color_arr.color; 
                            }
                            else{
                                settings.color = rippleAnimationArgs.marvy_ripples_animation_circle_color;
                            } 
                        // }                                                      
                    } 
                });

            } else {
                settings.switch = $scope.data("marvy_enable_ripples_animation");
                settings.color = $scope.data("marvy_ripples_animation_circle_color");
                settings.position = $scope.data("marvy_ripples_animation_circle_position");
                settings.size = $scope.data("marvy_ripples_animation_circle_size");
            }

            if (settings.switch) {
                var sectionKey = 'ripple-' + sectionId;
                currentRipple[sectionId] = settings;
                marvyRipplesRender(target, sectionId, sectionKey);
            } else {
                previousRipple = {};
                currentRipple = {};
                if (sheetRipple.cssRules.length !== 0) {
                    for (var j = sheetRipple.cssRules.length - 1; j >= 0; j--) {
                        if (sheetRipple.cssRules[j].selectorText.includes(sectionId)) {
                            sheetRipple.deleteRule(j);
                        }
                    }
                }
            }
        }
    };

    function addRule(selector, css, i) {
        var propText = typeof css === "string" ? css : Object.keys(css).map(function (p) {
            return p + ":" + (p === "content" ? "'" + css[p] + "'" : css[p]);
        }).join(";");
        sheetRipple.insertRule(selector + "{" + propText + "}", i);
    }

    function rippleAnimation(target, settings, sectionId, sectionKey) {
        if(settings === undefined) {
            return;
        }
        var checkElement = document.getElementsByClassName("marvy-ripples-section-" + sectionId);
        if (checkElement.length >= 0) {
            var previousElementLength = checkElement.length;
            for (let len = 0; len < previousElementLength; len++) {
                checkElement[len].remove();
            }
            var ripple_div = document.createElement('div');
            var delay_duration = 0.3;
            ripple_div.classList.add("marvy-ripples-section-" + sectionId);
            document.querySelector(target).appendChild(ripple_div);
            document.querySelector(target).classList.add("marvy-custom-ripples-animation-section-" + sectionId);

            // Set Z-index for section container
            var ripplesZindex = document.querySelector('.marvy-custom-ripples-animation-section-' + sectionId + ' .elementor-container , .marvy-custom-ripples-animation-section-' + sectionId + '>*');
            ripplesZindex.style.zIndex = '99';

            hw_gap = settings !== undefined && settings.size !== undefined && parseFloat(settings.size) >= 0 ? parseFloat(settings.size) : 300;
            var sectionStyle = getStyle(document.querySelector(".marvy-ripples-section-" + sectionId))
            parentHeight = parseFloat(sectionStyle['height']);
            parentWidth = parseFloat(sectionStyle['width']);

            left = right = top = bottom = eleft = eright = etop = ebottom = 'unset';
            if (settings.position === 'left') {
                left = 0;
                top = (parentHeight / 2);
                eleft = etop = 1;
            } else if (settings.position === 'top') {
                top = 0;
                left = (parentWidth / 2);
                eleft = etop = 1;
            } else if (settings.position === 'right') {
                right = 0;
                top = (parentHeight / 2);
                eright = etop = 1;
            } else if (settings.position === 'bottom') {
                bottom = 0;
                left = (parentWidth / 2);
                eleft = ebottom = 1;
            } else if (settings.position === 'topLeft') {
                top = left = 0;
                eleft = etop = 1;
            } else if (settings.position === 'topRight') {
                eright = etop = 1;
                top = right = 0;
            } else if (settings.position === 'bottomRight') {
                eright = ebottom = 1;
                right = bottom = 0;
            } else if (settings.position === 'bottomLeft') {
                eleft = ebottom = 1;
                left = bottom = 0;
            }

            i = 0;

            for (j = sheetRipple.cssRules.length - 1; j >= 0; j--) {
                if (sheetRipple.cssRules[j].selectorText.includes(sectionId)) {
                    sheetRipple.deleteRule(j);
                }
            }
            while (i <= 4) {
                var child_div_el = document.createElement('div');
                child_div_el.classList.add("marvy-ripples-circle-" + sectionId + '-' + i);
                document.querySelector(".marvy-ripples-section-" + sectionId).appendChild(child_div_el);

                addRule(".marvy-ripples-circle-" + sectionId + '-' + i, {
                    width: (hw_gap * (i + 1)) + 'px',
                    height: (hw_gap * (i + 1)) + 'px',
                    left: left - ((hw_gap / 2) * (i + 1) * eleft) + 'px',
                    top: top - ((hw_gap / 2) * (i + 1) * etop) + 'px',
                    bottom: bottom - ((hw_gap / 2) * (i + 1) * ebottom) + 'px',
                    right: right - ((hw_gap / 2) * (i + 1) * eright) + 'px',
                    "animation-delay": (delay_duration * (i + 1)) + 's',
                    background: settings.color,
                    "z-index": 1
                }, i);
                i++;
            }
        }
        return true;
    }

    function getStyle(el) {
        return (typeof getComputedStyle !== 'undefined' ?
                getComputedStyle(el, null) :
                el.currentStyle
        );
    }

    function marvyRipplesRender(target, sectionId, sectionKey) {
        var myEle = document.getElementsByClassName("marvy-custom-ripples-animation-section-" + sectionId);
        marvyRippleResizedEvent(target, sectionId, sectionKey);
        if(myEle.length!==0){
            document.querySelector(".elementor-element-" + sectionId).addEventListener('DOMAttrModified', marvyRippleResizedEvent.bind(event, target, sectionId, sectionKey));
        }
        window.addEventListener("resize", marvyRippleResizedEvent.bind(event, target, sectionId, sectionKey));
    }

    function marvyRippleResizedEvent(target, sectionId, sectionKey) {
        var sectionStyle = getStyle(document.querySelector(".elementor-element-" + sectionId));
        var currentHeight = parseFloat(sectionStyle['height']);
        var currentWidth = parseFloat(sectionStyle['width']);
        if (JSON.stringify(previousRipple[sectionKey]) !== JSON.stringify(currentRipple[sectionId]) || parentWidth !== currentWidth || parentHeight !== currentHeight) {
            previousRipple[sectionKey] = currentRipple[sectionId];
            if(currentRipple !== undefined && currentRipple[sectionId] !== undefined){
                rippleAnimation(target, currentRipple[sectionId], sectionId, sectionKey);
            }
        }
    }

    $(window).on('elementor/frontend/init', VisualRipplesAnimation.initRipples);
})(jQuery);

