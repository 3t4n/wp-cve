(function ($) {
    'use strict';
    var previousBanner = {};
    var styleBanner = document.createElement('style');
    var sheetBanner = document.head.appendChild(styleBanner).sheet;
    var innerImageUrl = marvyScript.pluginsUrl + 'assets/images/circle_inner.png';
    var outerImageUrl = marvyScript.pluginsUrl + 'assets/images/circle_outer.png';
    var global_color = marvyScript.color.length != 0 ? [ ...marvyScript.color.custom_colors ,...marvyScript.color.system_colors ] : [];

    const randomNum = (min, max) => Math.random() * (max - min + 1) + min | 0;

    var MarvyFireworkAnimation = {
        initFirework: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/section', MarvyFireworkAnimation.initFireworkWidget);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', MarvyFireworkAnimation.initFireworkWidget);
        },
        initFireworkWidget: function ($scope) {
            var sectionId = $scope.data('id');
            var target = '.elementor-element-' + sectionId;
            var settings = {};
            if (window.isEditMode || window.elementorFrontend.isEditMode()) {
                var editorElements = null;
                var fireworkAnimationArgs = {};

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }

                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }

                $.each(editorElements.models, function (i, el) {
                    if (sectionId === el.id) {
                        fireworkAnimationArgs = el.attributes.settings.attributes;
                    } else if (el.id === $scope.closest('.elementor-top-section').data('id')) {
                        $.each(el.attributes.elements.models, function (i, col) {
                            $.each(col.attributes.elements.models, function (i, subSec) {
                                fireworkAnimationArgs = subSec.attributes.settings.attributes;
                            });
                        });
                    }
                    settings.switch = fireworkAnimationArgs.marvy_enable_firework_animation;
                    settings.circle_min_size = fireworkAnimationArgs.marvy_firework_animation_circle_min_size;
                    settings.circle_max_size = fireworkAnimationArgs.marvy_firework_animation_circle_max_size;
                    settings.color_type = fireworkAnimationArgs.marvy_firework_animation_color_type;
                    // settings.color_single = fireworkAnimationArgs.marvy_firework_animation_color_single;

                    // settings.background = fireworkAnimationArgs.marvy_firework_animation_background_color;

                    if(Object.keys(fireworkAnimationArgs).length !== 0){
                        
                        if(typeof fireworkAnimationArgs.marvy_firework_animation_color_multiples == "object"){
                            
                            settings.color_multiples = fireworkAnimationArgs.marvy_firework_animation_color_multiples.models?.map((val,index)=>{
                                if(val.attributes.__globals__ && val.attributes.__globals__.color && val.attributes.__globals__.color !== ""){
                                    var multiples_color_id = val.attributes.__globals__.color.split('=')[1];
                                    var multiples_color_arr = global_color.find(element => element._id  === multiples_color_id);
                                    var multiples_color = multiples_color_arr.color; 
                                    return multiples_color;
                                }
                                else{
                                    return val.attributes.color;
                                }
                                
                            })
                        }

                        if(fireworkAnimationArgs.__globals__ && fireworkAnimationArgs.__globals__.marvy_firework_animation_background_color && fireworkAnimationArgs.__globals__.marvy_firework_animation_background_color !== ""){
                            var background_id = fireworkAnimationArgs.__globals__.marvy_firework_animation_background_color.split('=')[1];
                            var background_arr = global_color.find(element => element._id  === background_id);
                            settings.background = background_arr.color; 
                        }
                        else{
                            settings.background = fireworkAnimationArgs.marvy_firework_animation_background_color;  
                        }

                        if(fireworkAnimationArgs.__globals__ && fireworkAnimationArgs.__globals__.marvy_firework_animation_color_single && fireworkAnimationArgs.__globals__.marvy_firework_animation_color_single !== ""){
                            var color_single_id = fireworkAnimationArgs.__globals__.marvy_firework_animation_color_single.split('=')[1];
                            var color_single_arr = global_color.find(element => element._id  === color_single_id);
                            settings.color_single = color_single_arr.color; 
                        }
                        else{
                            settings.color_single = fireworkAnimationArgs.marvy_firework_animation_color_single;  
                        }
                       
                        
                    } 
                    settings.circle_min_size_tablet = fireworkAnimationArgs.marvy_firework_animation_circle_min_size_tablet;
                    settings.circle_max_size_tablet = fireworkAnimationArgs.marvy_firework_animation_circle_max_size_tablet;

                    settings.circle_min_size_mobile = fireworkAnimationArgs.marvy_firework_animation_circle_min_size_mobile;
                    settings.circle_max_size_mobile = fireworkAnimationArgs.marvy_firework_animation_circle_max_size_mobile;                   
                });
            } else {
                settings.switch = $scope.data('marvy_enable_firework_animation');
                settings.circle_min_size = $scope.data('marvy_firework_animation_circle_min_size');
                settings.circle_max_size = $scope.data('marvy_firework_animation_circle_max_size');
                settings.color_type = $scope.data('marvy_firework_animation_color_type');
                settings.color_single = $scope.data('marvy_firework_animation_color_single');
                settings.color_multiples = $scope.data('marvy_firework_animation_color_multiples');
                settings.background = $scope.data('marvy_firework_animation_background_color');

                settings.circle_min_size_tablet = $scope.data('marvy_firework_animation_circle_min_size_tablet');
                settings.circle_max_size_tablet = $scope.data('marvy_firework_animation_circle_max_size_tablet');

                settings.circle_min_size_mobile = $scope.data('marvy_firework_animation_circle_min_size_mobile');
                settings.circle_max_size_mobile = $scope.data('marvy_firework_animation_circle_max_size_mobile');
            }

            if (settings.switch) {
                MarvyFireworkAnimation.loadFireworks(target, sectionId, settings);
            }
        },
        loadFireworks: function (target, sectionId, settings) {

            var checkElement = document.getElementsByClassName("marvy-firework-section-" + sectionId);
            if (checkElement.length >= 0) {

                if(checkElement.length >  0) {
                    document.querySelector(".marvy-firework-section-" + sectionId).remove();
                }
                    const firework_canvas = document.createElement('canvas');
                    firework_canvas.classList.add("marvy-firework-section-" + sectionId);

                // Canvas Setup


                document.querySelector(target).appendChild(firework_canvas);
                document.querySelector(target).classList.add("marvy-custom-firework-animation-section-" + sectionId);

                // Set Z-index for section container
                const fireworkZindex = document.querySelector('.marvy-custom-firework-animation-section-' + sectionId + ' .elementor-container , .marvy-custom-firework-animation-section-'+sectionId+'>*');
                fireworkZindex.style.zIndex = '99';

                // Set min height
                const fireworkMinHeight = document.querySelector(".elementor-element-" + sectionId);
                fireworkMinHeight.closest('.elementor-top-section,.e-con-boxed,.e-con-full').style.minHeight = "100px";
                // Canvas Setup End
                if((typeof settings.color_multiples) === 'string') {
                    settings.color_multiples = settings.color_multiples.split("--,--");
                }

                const targetStyle = getStyle(document.querySelector(".marvy-custom-firework-animation-section-" + sectionId));
                firework_canvas.width = parseInt(targetStyle['width']);
                firework_canvas.height = parseInt(targetStyle['height']);
                let mainCtx = firework_canvas.getContext('2d');

                class Birthday {
                    constructor(sectionId, settings) {

                        this.color_array = settings.color_multiples;
                        this.settings = settings;
                        this.resize();
                        // create a lovely place to store the firework
                        this.fireworks = [];
                        this.counter = 0;

                    }

                    resize() {
                        this.width = firework_canvas.width;
                        let center = this.width / 2 | 0;
                        this.spawnA = center - center / 4 | 0;
                        this.spawnB = center + center / 4 | 0;

                        this.height = firework_canvas.height;
                        this.spawnC = this.height * .1;
                        this.spawnD = this.height * .5;

                    }

                    onClick(evt) {
                        let rect = document.querySelector(".marvy-custom-firework-animation-section-" + this.settings.sectionId).getBoundingClientRect();
                        let x = evt.pageX - rect.left;
                        let y = (evt.pageY - rect.top - window.scrollY);
                        let count = randomNum(3, 6);
                        for (let i = 0; i < count; i++) {
                            this.fireworks.push(new Firework(
                                randomNum(this.spawnA, this.spawnB),             // Position
                                this.height,                                  // Position
                                x,                                            // Position
                                y,                                            // Position
                                randomNum(0, 360),                               // Color shade (No Changes)
                                this.getCircleSize(),                              // Circle Size (Min,Max)
                                this.settings.color_multiples[randomNum(0,this.settings.color_multiples.length-1)],
                                this.settings
                                )
                            );
                        }
                        this.counter = -1;

                    }

                    getCircleSize(){
                        let min = this.settings.circle_min_size_mobile;
                        let max = this.settings.circle_max_size_mobile;
                        if(window.innerWidth > 1023){
                            min = this.settings.circle_min_size;
                            max = this.settings.circle_max_size;
                        }else if(window.innerWidth <= 1023 && window.innerWidth > 767){
                            min = this.settings.circle_min_size_tablet;
                            max = this.settings.circle_max_size_tablet;
                        }
                        return randomNum(min,max);
                    }

                    update(delta) {
                        mainCtx.globalCompositeOperation = 'hard-light';
                        // mainCtx.clearRect(0,0,mainCtx.canvas.width,mainCtx.canvas.height);
                        let bgColor = fireworkHexToRgb(settings.background);
                        mainCtx.fillStyle = `rgba(`+bgColor.r+`,`+bgColor.g+`,`+bgColor.b+`,0.05)`;
                        mainCtx.fillRect(0, 0, mainCtx.canvas.width, mainCtx.canvas.height);

                        mainCtx.globalCompositeOperation = 'lighter';
                        for (let firework of this.fireworks) {
                            firework.update(delta);
                        }

                        // if enough time passed... create new new firework
                        this.counter += delta * 3; // each second
                        if (this.counter >= 1) {
                            this.fireworks.push(new Firework(
                                randomNum(this.spawnA, this.spawnB),           // Position
                                this.height,                                // Position
                                randomNum(0, this.width),                      // Position
                                randomNum(this.spawnC, this.spawnD),           // Position
                                randomNum(0, 360),                             // Color shade (No Changes)
                                this.getCircleSize(),                             // Circle Size (Min,Max)
                                this.settings.color_multiples[randomNum(0,this.settings.color_multiples.length-1)],
                                this.settings
                            ));
                            this.counter = 0;
                        }

                        // remove the dead fireworks
                        if (this.fireworks.length > 1000) {
                            this.fireworks = this.fireworks.filter(firework => !firework.dead);
                        }

                    }
                }

                class Firework {
                    constructor(x, y, targetX, targetY, shade, offsprings, color, setting) {
                        this.pi2 = Math.PI * 2;
                        this.dead = false;
                        this.offsprings = offsprings;
                        this.x = x;
                        this.y = y;
                        this.targetX = targetX;
                        this.targetY = targetY;
                        this.shade = shade;
                        this.setting = setting;
                        this.color = color;
                        this.history = [];
                    }

                    getColor(shade, i = 50) {
                        let color = 'hsl(';
                        if (this.setting.color_type === 'single') {
                            color = this.setting.color_single;
                        } else if (this.setting.color_type === 'multiple' && this.setting.color_multiples.length > 0) {
                            color = this.color;
                        } else {
                            color += shade + ',100%,' + i + '%)';
                        }
                        return color;
                    }

                    update(delta) {
                        if (this.dead) {
                            return;
                        }

                        let xDiff = this.targetX - this.x;
                        let yDiff = this.targetY - this.y;
                        if (Math.abs(xDiff) > 3 || Math.abs(yDiff) > 3) { // is still moving
                            this.x += xDiff * 2 * delta;
                            this.y += yDiff * 2 * delta;

                            this.history.push({
                                x: this.x,
                                y: this.y
                            });

                            if (this.history.length > 20) {
                                this.history.shift();
                            }

                        } else {
                            if (this.offsprings && !this.madeChilds) {
                                let babies = this.offsprings / 2;
                                for (let i = 0; i < babies; i++) {
                                    let targetX = this.x + this.offsprings * Math.cos(this.pi2 * i / babies) | 0;
                                    let targetY = this.y + this.offsprings * Math.sin(this.pi2 * i / babies) | 0;

                                    birthday.fireworks.push(new Firework(
                                        this.x,                               // Position
                                        this.y,                               // Position
                                        targetX,                              // Position
                                        targetY,                              // Position
                                        this.shade,                           // Color shade (No Changes)
                                        0,                                    // Circle Size (Min,Max)
                                        this.color,
                                        this.setting
                                        )
                                    );

                                }

                            }
                            this.madeChilds = true;
                            this.history.shift();
                        }

                        if (this.history.length === 0) {
                            this.dead = true;
                        } else if (this.offsprings) {
                            for (let i = 0; this.history.length > i; i++) {
                                let point = this.history[i];
                                mainCtx.beginPath();
                                mainCtx.fillStyle = this.getColor(this.shade, i);
                                mainCtx.arc(point.x, point.y, 1, 0, this.pi2, false);
                                mainCtx.fill();
                            }
                        } else {
                            mainCtx.beginPath();
                            mainCtx.fillStyle = this.getColor(this.shade);
                            mainCtx.arc(this.x, this.y, 1, 0, this.pi2, false);
                            mainCtx.fill();
                        }

                    }
                }


                // Start Animation

                const timestamp = () => new Date().getTime();
                let then = timestamp();
                settings.sectionId = sectionId;

                const birthday = new Birthday(sectionId, settings);
                birthday.fireworks = [];
                window.onresize = () => birthday.resize();
                fireworkZindex.onclick = evt => birthday.onClick(evt);
                fireworkZindex.ontouchstart = evt => birthday.onClick(evt);
                let showFireworks = true;
                let is_dev_tool_active = false;
                $(window).on("blur focus", function(e) {
                    var prevType = $(this).data("prevType");

                    if (prevType != e.type) {   //  reduce double fire issues
                        switch (e.type) {
                            case "blur":
                                showFireworks = false;
                                break;
                            case "focus":
                                showFireworks = true;
                                break;
                        }
                    }

                    $(this).data("prevType", e.type);
                });
                (function loop() {
                    requestAnimationFrame(loop);

                    let now = timestamp();
                    let delta = now - then;

                    then = now;
                    if(showFireworks || is_dev_tool_active) {
                        birthday.update(delta / 1000);
                    }
                })();

                window.addEventListener('resize', function (event) {
                    fireworkCanvasResize(sectionId);
                }, true);

                $(document).ready(function () {
                    fireworkCanvasResize(sectionId);
                });

                window.dispatchEvent(new Event('resize'));

                function fireworkCanvasResize(sectionId) {
                    let targetStyle = getStyle(document.querySelector(".marvy-custom-firework-animation-section-" + sectionId));
                    firework_canvas.width = parseInt(targetStyle['width']);
                    firework_canvas.height = parseInt(targetStyle['height']);
                    mainCtx = firework_canvas.getContext('2d');
                    birthday.resize();
                }
            }
        }
    };

    function getStyle(el) {
        return (typeof getComputedStyle !== 'undefined' ?
                getComputedStyle(el, null) :
                el.currentStyle
        );
    }

    function fireworkHexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }


    $(window).on('elementor/frontend/init', MarvyFireworkAnimation.initFirework);


})(jQuery);