(function( $ ) {
    'use strict';
    var previousBanner = {};
    var styleBanner = document.createElement('style');
    var sheetBanner = document.head.appendChild(styleBanner).sheet;
    var innerImageUrl = marvyScript.pluginsUrl + 'assets/images/circle_inner.png';
    var outerImageUrl = marvyScript.pluginsUrl + 'assets/images/circle_outer.png';
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];
    
    var MarvyBannerAnimation = {
        initBanner: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', MarvyBannerAnimation.initBannerWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', MarvyBannerAnimation.initBannerWidget);
        },
        initBannerWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-'+ sectionId;
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var bannerAnimationArgs = {};

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        bannerAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                bannerAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }
                    settings.switch = bannerAnimationArgs.marvy_enable_fancy_rotate;
                    settings.circle = bannerAnimationArgs.marvy_enable_fancy_rotate_circle;
                    settings.particle = bannerAnimationArgs.marvy_enable_fancy_rotate_particle;
                    // settings.firstColor = bannerAnimationArgs.marvy_fancy_rotate_first_color;
                    // settings.secondColor = bannerAnimationArgs.marvy_fancy_rotate_second_color;
                    if(Object.keys(bannerAnimationArgs).length !== 0){
                        
                        if(bannerAnimationArgs.__globals__ && bannerAnimationArgs.__globals__.marvy_fancy_rotate_first_color && bannerAnimationArgs.__globals__.marvy_fancy_rotate_first_color !== ""){
                            var firstColor_id = bannerAnimationArgs.__globals__.marvy_fancy_rotate_first_color.split('=')[1];
                            var firstColor_arr = global_color.find(element => element._id  === firstColor_id);
                            settings.firstColor = firstColor_arr.color; 
                        }
                        else{
                            settings.firstColor = bannerAnimationArgs.marvy_fancy_rotate_first_color;  
                        }
                        
                        
                        if(bannerAnimationArgs.__globals__ && bannerAnimationArgs.__globals__.marvy_fancy_rotate_second_color && bannerAnimationArgs.__globals__.marvy_fancy_rotate_second_color !== ""){
                            var secondColor_id = bannerAnimationArgs.__globals__.marvy_fancy_rotate_second_color.split('=')[1];
                            var secondColor_arr = global_color.find(element => element._id  === secondColor_id);
                            settings.secondColor = secondColor_arr.color; 
                        }
                        else{
                            settings.secondColor = bannerAnimationArgs.marvy_fancy_rotate_second_color;                           
                        }
                    
                        
                    } 
                });
            } else {
                settings.switch = $scope.data('marvy_enable_fancy_rotate');
                settings.circle = $scope.data('marvy_enable_fancy_rotate_circle');
                settings.particle = $scope.data('marvy_enable_fancy_rotate_particle');
                settings.firstColor = $scope.data('marvy_fancy_rotate_first_color');
                settings.secondColor = $scope.data('marvy_fancy_rotate_second_color');
            }

            if (settings.switch) {
                var sectionKey = 'banner-'+sectionId;
                if (!previousBanner.hasOwnProperty(sectionKey)){
                    previousBanner[sectionKey] = settings;
                }

                var result = bannerAnimation(target, settings, sectionId, sectionKey);
                if (result){
                    previousBanner[sectionKey] = settings;
                }
            } else {
                previousBanner = {};
                if (sheetBanner.cssRules.length !== 0){
                    for (var j = sheetBanner.cssRules.length - 1; j >= 0; j--) {
                        if(sheetBanner.cssRules[j].selectorText.includes(sectionId) ) {
                            sheetBanner.deleteRule(j);
                        }
                    }
                }
            }
        }
    };

    $( window ).on('elementor/frontend/init', MarvyBannerAnimation.initBanner);

    function addRule(selector, css) {
        var propText = typeof css === "string" ? css : Object.keys(css).map(function (p) {
            return p + ":" + (p === "content" ? "'" + css[p] + "'" : css[p]);
        }).join(";");
        sheetBanner.insertRule(selector + "{" + propText + "}", sheetBanner.cssRules.length);
    }

    function bannerAnimation(target,settings,sectionId, sectionKey) {

        var checkElement = document.getElementsByClassName("marvy-banner-section-"+sectionId);

        if (checkElement.length >= 0 ) {
            var banner_div = document.createElement('div');

            banner_div.classList.add("marvy-banner-section-"+sectionId);
            document.querySelector(target).appendChild(banner_div);

            document.querySelector(target).classList.add('marvy-custom-banner-animation-section-'+sectionId);
            var bannerZindex = document.querySelector('.marvy-custom-banner-animation-section-'+sectionId+' .elementor-container, .marvy-custom-banner-animation-section-'+sectionId+'>*');
            bannerZindex.style.zIndex = '99';

            var bannerMinHeight = document.querySelector(".elementor-element-"+sectionId);
            bannerMinHeight.closest('.elementor-top-section,.e-con-boxed,.e-con-full').style.minHeight = "200px";

            var first_div_el = document.createElement('div');
            var second_div_el = document.createElement('div');

            if (settings.particle === 'yes') {
                first_div_el.classList.add("marvy-particles-first-"+sectionId);
                first_div_el.setAttribute("id","marvy-particleCanvas-First-"+sectionId);
                second_div_el.classList.add("marvy-particles-second-"+sectionId);
                second_div_el.setAttribute("id","marvy-particleCanvas-Second-"+sectionId);

                document.querySelector(".marvy-banner-section-"+sectionId).appendChild(first_div_el);
                document.querySelector(".marvy-banner-section-"+sectionId).appendChild(second_div_el);

                particlesJS("marvy-particleCanvas-First-"+sectionId,
                    {
                        "particles": {
                            "number": {
                                "value": 100,
                                "density": {
                                    "enable": true,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": settings.firstColor
                            },
                            "shape": {
                                "type": "circle",
                                "stroke": {
                                    "width": 0,
                                    "color": settings.firstColor
                                },
                                "polygon": {
                                    "nb_sides": 3
                                },
                                "image": {
                                    "src": "img/github.svg",
                                    "width": 100,
                                    "height": 100
                                }
                            },
                            "opacity": {
                                "value": 0.5,
                                "random": false,
                                "anim": {
                                    "enable": true,
                                    "speed": 1,
                                    "opacity_min": 0.1,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": 20,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 10,
                                    "size_min": 0.1,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": false,
                                "distance": 150,
                                "color": "#ffffff",
                                "opacity": 0.4,
                                "width": 1
                            },
                            "move": {
                                "enable": true,
                                "speed": 2,
                                "direction": "none",
                                "random": true,
                                "straight": false,
                                "out_mode": "bounce",
                                "bounce": false,
                                "attract": {
                                    "enable": false,
                                    "rotateX": 394.57382081613633,
                                    "rotateY": 157.82952832645452
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": true,
                                    "mode": "grab"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "push"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 200,
                                    "line_linked": {
                                        "opacity": 0.2
                                    }
                                },
                                "bubble": {
                                    "distance": 1500,
                                    "size": 40,
                                    "duration": 7.272727272727273,
                                    "opacity": 0.3676323676323676,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 50,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    });
                particlesJS("marvy-particleCanvas-Second-"+sectionId,
                    {
                        "particles": {
                            "number": {
                                "value": 100,
                                "density": {
                                    "enable": true,
                                    "value_area": 800
                                }
                            },
                            "color": {
                                "value": settings.secondColor
                            },
                            "shape": {
                                "type": "circle",
                                "stroke": {
                                    "width": 0,
                                    "color": settings.secondColor
                                },
                                "polygon": {
                                    "nb_sides": 3
                                },
                                "image": {
                                    "src": "img/github.svg",
                                    "width": 100,
                                    "height": 100
                                }
                            },
                            "opacity": {
                                "value": 0.5,
                                "random": true,
                                "anim": {
                                    "enable": false,
                                    "speed": 0.2,
                                    "opacity_min": 0,
                                    "sync": false
                                }
                            },
                            "size": {
                                "value": 15,
                                "random": true,
                                "anim": {
                                    "enable": true,
                                    "speed": 10,
                                    "size_min": 0.1,
                                    "sync": false
                                }
                            },
                            "line_linked": {
                                "enable": false,
                                "distance": 150,
                                "color": "#ffffff",
                                "opacity": 0.4,
                                "width": 1
                            },
                            "move": {
                                "enable": true,
                                "speed": 2,
                                "direction": "none",
                                "random": true,
                                "straight": false,
                                "out_mode": "bounce",
                                "bounce": false,
                                "attract": {
                                    "enable": true,
                                    "rotateX": 3945.7382081613637,
                                    "rotateY": 157.82952832645452
                                }
                            }
                        },
                        "interactivity": {
                            "detect_on": "canvas",
                            "events": {
                                "onhover": {
                                    "enable": false,
                                    "mode": "grab"
                                },
                                "onclick": {
                                    "enable": false,
                                    "mode": "push"
                                },
                                "resize": true
                            },
                            "modes": {
                                "grab": {
                                    "distance": 200,
                                    "line_linked": {
                                        "opacity": 0.2
                                    }
                                },
                                "bubble": {
                                    "distance": 1500,
                                    "size": 40,
                                    "duration": 7.272727272727273,
                                    "opacity": 0.3676323676323676,
                                    "speed": 3
                                },
                                "repulse": {
                                    "distance": 50,
                                    "duration": 0.4
                                },
                                "push": {
                                    "particles_nb": 4
                                },
                                "remove": {
                                    "particles_nb": 2
                                }
                            }
                        },
                        "retina_detect": true
                    });
            }
            var appendBanner = true;

            if (JSON.stringify(previousBanner[sectionKey]) !== JSON.stringify(settings)){
                appendBanner = false;
                for (var j = sheetBanner.cssRules.length - 1; j >= 0; j--) {
                    if(sheetBanner.cssRules[j].selectorText.includes(sectionId) ) {
                        sheetBanner.deleteRule(j);
                    }
                    if (j === 0){
                        appendBanner = true;
                        previousBanner[sectionKey] = settings;
                    }
                }
            }
            while (!appendBanner){}

            addRule(".marvy-banner-section-"+sectionId+"", {
                overflow: "hidden !important"
            });

            if (settings.circle === 'yes') {
                addRule(".marvy-banner-section-" + sectionId + ":before", {
                    "background-image": 'url(' + innerImageUrl + ')',
                    content: " "
                });

                addRule(".marvy-banner-section-" + sectionId + ":after", {
                    "background-image": 'url(' + outerImageUrl + ')',
                    content: " "
                });
            }

        }
        return true;
    }

})( jQuery );
