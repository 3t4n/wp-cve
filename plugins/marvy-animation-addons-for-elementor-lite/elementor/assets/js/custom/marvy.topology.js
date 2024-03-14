(function( $ ) {
    'use strict';
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];
    var VisualTopologyAnimation = {
        initTopology: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', VisualTopologyAnimation.initTopologyWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', VisualTopologyAnimation.initTopologyWidget);
        },
        initTopologyWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-' + sectionId;
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var topologyAnimationArgs = {};

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        topologyAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                topologyAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }

                    settings.switch = topologyAnimationArgs.marvy_enable_topology_animation;
                    // settings.color = topologyAnimationArgs.marvy_topology_animation_color;
                    // settings.bgColor = topologyAnimationArgs.marvy_topology_animation_background_color;
                    if(Object.keys(topologyAnimationArgs).length !== 0){
                        // if(topologyAnimationArgs.__globals__.marvy_topology_animation_color){
                            if(topologyAnimationArgs.__globals__ && topologyAnimationArgs.__globals__.marvy_topology_animation_color && topologyAnimationArgs.__globals__.marvy_topology_animation_color !== ""){
                                var color_id = topologyAnimationArgs.__globals__.marvy_topology_animation_color.split('=')[1];
                                var color_arr = global_color.find(element => element._id  === color_id);
                                settings.color = color_arr.color; 
                            }
                            else{
                                settings.color = topologyAnimationArgs.marvy_topology_animation_color;
                            }
                        // }
                        // if(topologyAnimationArgs.__globals__.marvy_topology_animation_background_color){
                            if(topologyAnimationArgs.__globals__ && topologyAnimationArgs.__globals__.marvy_topology_animation_background_color && topologyAnimationArgs.__globals__.marvy_topology_animation_background_color !== ""){
                                var bgColor_id = topologyAnimationArgs.__globals__.marvy_topology_animation_background_color.split('=')[1];
                                var bgColor_arr = global_color.find(element => element._id  === bgColor_id);
                                settings.bgColor = bgColor_arr.color; 
                            }
                            else{
                                settings.bgColor = topologyAnimationArgs.marvy_topology_animation_background_color;
                            }
                        // }
                                                                               
                    } 
                });

            } else {
                settings.switch = $scope.data("marvy_enable_topology_animation");
                settings.color = $scope.data("marvy_topology_animation_color");
                settings.bgColor = $scope.data("marvy_topology_animation_background_color");
            }

            if (settings.switch) {
                topologyAnimation(target, settings, sectionId);
            }
        }
    };

    function topologyAnimation(target,settings,sectionId) {
        var checkElement = document.getElementsByClassName("marvy-topology-section-" + sectionId);
        if (checkElement.length >= 0) {

            var topology_div = document.createElement('div');
            topology_div.classList.add("marvy-topology-section-" + sectionId);

            document.querySelector(target).appendChild(topology_div);
            document.querySelector(target).classList.add("marvy-custom-topology-animation-section-" + sectionId);

            // Set Z-index for section container
            var topologyZindex = document.querySelector('.marvy-custom-topology-animation-section-'+sectionId+' .elementor-container , .marvy-custom-topology-animation-section-'+sectionId+'>*');
            topologyZindex.style.zIndex = '99';

            // Set min height
            var topologyMinHeight = document.querySelector(".elementor-element-"+sectionId);
            topologyMinHeight.closest('.elementor-top-section,.e-con-boxed,.e-con-full').style.minHeight = "100px";

            var topoAnimation = VANTA.TOPOLOGY({
                el: ".marvy-topology-section-" + sectionId,
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 100.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: settings.color,
                backgroundColor:settings.bgColor
            });
            render(topoAnimation,sectionId);

        }
        return true;
    }

    function render(animation,sectionId) {
        document.querySelector(".elementor-element-"+sectionId).addEventListener('DOMAttrModified', function(e){
            animation.resize();
        }, false);
    }

    $( window ).on('elementor/frontend/init', VisualTopologyAnimation.initTopology);
})( jQuery );
