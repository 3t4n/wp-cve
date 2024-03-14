(function( $ ) {
    'use strict';
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];
    var MarvyCloudAnimation = {
        initCloud: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', MarvyCloudAnimation.initCloudWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', MarvyCloudAnimation.initCloudWidget);
        },
        initCloudWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-'+ sectionId;
            var settings = {};

            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var CloudAnimationArgs = {};
                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;
                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        CloudAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                CloudAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }

                    settings.switch = CloudAnimationArgs.marvy_enable_cloud_animation;
                    // settings.background = CloudAnimationArgs.marvy_cloud_background_color;
                    // settings.skycolor = CloudAnimationArgs.marvy_cloud_sky_color;
                    // settings.cloudcolor = CloudAnimationArgs.marvy_cloud_cloud_color;
                    settings.cloudspeed = CloudAnimationArgs.marvy_cloud_cloud_speed;
                    // settings.lightcolor = CloudAnimationArgs.marvy_cloud_light_color;
                    if(Object.keys(CloudAnimationArgs).length !== 0){
                        // if(CloudAnimationArgs.__globals__.marvy_cloud_background_color){
                            if(CloudAnimationArgs.__globals__ && CloudAnimationArgs.__globals__.marvy_cloud_background_color && CloudAnimationArgs.__globals__.marvy_cloud_background_color !== ""){
                                var background_id = CloudAnimationArgs.__globals__.marvy_cloud_background_color.split('=')[1];
                                var background_arr = global_color.find(element => element._id  === background_id);
                                settings.background = background_arr.color; 
                            }
                            else{
                                settings.background = CloudAnimationArgs.marvy_cloud_background_color;
                            }
                        // }
                        
                        // if(CloudAnimationArgs.__globals__.marvy_cloud_cloud_color){
                            if(CloudAnimationArgs.__globals__ && CloudAnimationArgs.__globals__.marvy_cloud_cloud_color && CloudAnimationArgs.__globals__.marvy_cloud_cloud_color !== ""){
                                var cloudcolor_id = CloudAnimationArgs.__globals__.marvy_cloud_cloud_color.split('=')[1];
                                var cloudcolor_arr = global_color.find(element => element._id  === cloudcolor_id);
                                settings.cloudcolor = cloudcolor_arr.color; 
                            }
                            else{
                                settings.cloudcolor = CloudAnimationArgs.marvy_cloud_cloud_color;
                            }
                        // }
                        
                        // if(CloudAnimationArgs.__globals__.marvy_cloud_sky_color){
                            if(CloudAnimationArgs.__globals__ && CloudAnimationArgs.__globals__.marvy_cloud_sky_color && CloudAnimationArgs.__globals__.marvy_cloud_sky_color !== ""){
                                var skycolor_id = CloudAnimationArgs.__globals__.marvy_cloud_sky_color.split('=')[1];
                                var skycolor_arr = global_color.find(element => element._id  === skycolor_id);
                                settings.skycolor = skycolor_arr.color; 
                            }
                            else{
                                settings.skycolor = CloudAnimationArgs.marvy_cloud_sky_color;
                            }
                        // }
                        
                        // if(CloudAnimationArgs.__globals__.marvy_cloud_light_color){
                            if(CloudAnimationArgs.__globals__ && CloudAnimationArgs.__globals__.marvy_cloud_light_color && CloudAnimationArgs.__globals__.marvy_cloud_light_color !== ""){
                                var lightcolor_id = CloudAnimationArgs.__globals__.marvy_cloud_light_color.split('=')[1];
                                var lightcolor_arr = global_color.find(element => element._id  === lightcolor_id);
                                settings.lightcolor = lightcolor_arr.color; 
                            }
                            else{
                                settings.lightcolor = CloudAnimationArgs.marvy_cloud_light_color;
                            }
                        // }
                                                                    
                    }
                });

            }  else {
                settings.switch = $scope.data("marvy_enable_cloud_animation");
                settings.background = $scope.data("marvy_cloud_background_color");
                settings.skycolor = $scope.data("marvy_cloud_sky_color");
                settings.cloudcolor = $scope.data("marvy_cloud_cloud_color");
                settings.lightcolor = $scope.data("marvy_cloud_light_color");
                settings.cloudspeed = $scope.data("marvy_cloud_cloud_speed");
            }
            if (settings.switch) {
                CloudAnimation(target,settings,sectionId);
            }
        }
    };

    function CloudAnimation(target,settings,sectionId){

        var checkElement = document.getElementsByClassName("marvy-cloud-section-" + sectionId);
        if (checkElement.length >= 0) {
            var noise = marvyScript.pluginsUrl + 'assets/images/noise.png';

            var cloud_div = document.createElement('div');
            cloud_div.classList.add("marvy-cloud-section-" + sectionId);
            
            document.querySelector(target).appendChild(cloud_div);
            document.querySelector(target).classList.add("marvy-custom-cloud-animation-section-" + sectionId);


            let zIndex = document.querySelector('.marvy-custom-cloud-animation-section-'+sectionId+' .elementor-container , .marvy-custom-cloud-animation-section-'+sectionId+'>*');
            zIndex.style.zIndex = '99';

            const cloudMinHeight = document.querySelector(".elementor-element-" + sectionId);
            cloudMinHeight.closest('.elementor-top-section,.e-con-boxed,.e-con-full').style.minHeight = "400px";

            var CloudAnimation = VANTA.CLOUDS2({
                el: ".marvy-cloud-section-" + sectionId,
                minHeight: 200,
                minWidth: 200,
                scale: 1,
                backgroundColor: settings.background,
                skyColor: settings.skycolor,
                cloudColor: settings.cloudcolor,
                lightColor: settings.lightcolor,
                speed: settings.cloudspeed,
                texturePath: noise
            });
            
            render(CloudAnimation,sectionId);
        }
        return true;
    }

    function render(animation,sectionId) {
        document.querySelector(".elementor-element-"+sectionId).addEventListener('DOMAttrModified', function(e){
            animation.resize();
        }, false);
    }

    $( window ).on('elementor/frontend/init', MarvyCloudAnimation.initCloud);
})( jQuery );