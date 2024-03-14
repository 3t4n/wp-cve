(function($) {

     // Classic background particles
     var Element_Ready_Section = {

        elementorSection: function($scope) {
            var $target = $scope,
                instance = null,
                editMode = Boolean(elementorFrontend.isEditMode());
            instance = new Element_Ready_Section_Plugin($target);
            // run main funcionality
            instance.init(instance);
        },

        loadScript: function(url, callback) {

            if (typeof url === 'string') {
                $.ajax({
                    url: url,
                    dataType: 'script',
                    success: callback,
                    async: true
                });
            }


            if (Array.isArray(url)) {
                url.forEach(element => $.ajax({
                    url: element,
                    dataType: 'script',
                    success: callback,
                    async: true
                }));
            }


        },

        particles_settings: function(new_settings) {
            // default
            var json_obj = {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
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
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 10,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 80,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 300,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 20
                    },
                    "move": {
                        "enable": true,
                        "speed": 12,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": false,
                            "mode": "repulse"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "bubble"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 800,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 800,
                            "size": 80,
                            "duration": 2,
                            "opacity": 0.8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 400,
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
            };

            json_obj.particles.number = new_settings.number;
            json_obj.particles.color = new_settings.color;
            json_obj.particles.shape = new_settings.shape;
            json_obj.particles.opacity = new_settings.opacity;
            json_obj.particles.line_linked = new_settings.line_linked;
            json_obj.particles.move = new_settings.move;
            json_obj.particles.interactivity = new_settings.interactivity;

            return json_obj;
        },

    };

    window.Element_Ready_Section_Plugin = function($target) {

        var self = this,
            sectionId = $target.data('id'),
            settings = false,
            editMode = Boolean(elementorFrontend.isEditMode()),
            $window = $(window),
            $body = $('body');
    
        /**
         * Init
         */
        self.init = function() {

            self.particles_background(sectionId);

            return false;
        };

        self.particles_section_option = function(sectionId) {

            let settings = {};

            let color = {
                "value": "#ffffff"
            };

            let particle_number = {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            };

            let shape = {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            };

            let opacity = {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            };

            let line_linked = {
                "enable": true,
                "distance": 300,
                "color": "#E44141",
                "opacity": 0.4,
                "width": 20
            };

            let move = {
                "enable": true,
                "speed": 12,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            };

            let interactivity = {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": false,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "bubble"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 800,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 800,
                        "size": 80,
                        "duration": 2,
                        "opacity": 0.8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 400,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            };

            particle_number.value = self.getSettings(sectionId, 'element_ready_particle_number') || 40;
            particle_number.density.enable = self.getSettings(sectionId, 'element_ready_particle_density') == 'true' ? true : false;
            particle_number.density.value_area = parseInt(self.getSettings(sectionId, 'element_ready_particle_value_area') || 400);
            settings.number = particle_number;

            // color
            color.value = self.getSettings(sectionId, 'element_ready_particle_color') || '#fff';
            settings.color = color;

            //shape
            shape.type = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_type') || ['circle'];
            shape.stroke.width = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_stroke_width') || 0;
            shape.stroke.color = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_stroke_color') || '#000000';
            shape.stroke.polygon = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_polygon_slides') || 5;

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_shape_image')) {

                shape.image.src = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_image').url;
                shape.image.width = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_image_width') || 100;
                shape.image.width = self.getSettings(sectionId, 'element_ready_global_section_particles_shape_image_height') || 100;
            }

            settings.shape = shape;

            // opacity
            if (self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_value')) {

                opacity.value = self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_value').size == '' ? 0.5 : self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_value').size;

            }
            opacity.random = self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_random') == 'true' ? true : false;
            opacity.anim.enable = self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_anim') == 'true' ? true : false;
            opacity.anim.speed = self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_anim_speed').size == '' ? 3 : self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_anim_speed').size;
            opacity.anim.opacity_min = self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_min_value').size == '' ? 3 : self.getSettings(sectionId, 'element_ready_global_section_particles_opacity_min_value').size;
            settings.opacity = opacity;

            // line_linked

            line_linked.enable = self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked') == 'true' ? true : false;
            line_linked.color = self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_color') == '' ? '#fff' : self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_color');
            line_linked.distance = self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_distance').size == '' ? 300 : self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_distance').size;
            line_linked.opacity = self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_opacity_value').size == '' ? 0.4 : self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_opacity_value').size;
            line_linked.width = self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_width').size == '' ? 3 : self.getSettings(sectionId, 'element_ready_global_section_particles_line_linked_width').size;
            settings.line_linked = line_linked;

            // move

            move.enable = self.getSettings(sectionId, 'element_ready_global_section_particles_move') == 'true' ? true : false;

            move.direction = self.getSettings(sectionId, 'element_ready_global_section_particles_move_direction') || 'none';
            move.random = self.getSettings(sectionId, 'element_ready_global_section_particles_move_random') == 'true' ? true : false;
            move.straight = self.getSettings(sectionId, 'element_ready_global_section_particles_move_straight') == 'true' ? true : false;
            move.bounce = self.getSettings(sectionId, 'element_ready_global_section_particles_move_bounce') == 'true' ? true : false;
            move.attract.enable = self.getSettings(sectionId, 'element_ready_global_section_particles_move_attract') == 'true' ? true : false;
            move.out_mode = self.getSettings(sectionId, 'element_ready_global_section_particles_move_mode') || 'out';
            move.attract.rotateX = self.getSettings(sectionId, 'element_ready_global_section_particles_move_attract_x') || '3000';
            move.attract.rotateY = self.getSettings(sectionId, 'element_ready_global_section_particles_move_attract_y') || '1500';

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_move_speed')) {
                move.speed = self.getSettings(sectionId, 'element_ready_global_section_particles_move_speed').size == 'undefined' ? 4 : self.getSettings(sectionId, 'element_ready_global_section_particles_move_speed').size;
            }

            settings.move = move;

            //interactivity
            interactivity.detect_on = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_detect_on') || 'canvas';
            interactivity.events.onhover.enable = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_onhover') == 'true' ? true : false;
            interactivity.events.onhover.mode = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_hover_mode');
            interactivity.events.onclick.enable = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_onclick') == 'true' ? true : false;
            interactivity.events.onclick.mode = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_click_mode');

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_distance')) {
                interactivity.modes.bubble.distance = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_distance').size == '' ? 100 : self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_distance').size;
            }

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_size')) {
                interactivity.modes.bubble.size = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_size').size == '' ? 4 : self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_size').size;
            }

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_duration')) {
                interactivity.modes.bubble.duration = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_duration').size == '' ? 0.4 : self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_bubble_duration').size;
            }
            // repulse mode
            if (self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_distance')) {
                interactivity.modes.repulse.distance = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_distance').size == '' ? 100 : self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_distance').size;
            }

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_size')) {
                interactivity.modes.repulse.size = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_size').size == '' ? 4 : self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_size').size;
            }

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_duration')) {
                interactivity.modes.repulse.duration = self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_duration').size == '' ? 0.4 : self.getSettings(sectionId, 'element_ready_global_section_particles_interactivity_repulse_duration').size;
            }


            if (self.getSettings(sectionId, 'element_ready_global_section_particles_mode_remove')) {
                interactivity.modes.remove.particles_nb = self.getSettings(sectionId, 'element_ready_global_section_particles_mode_remove').size == '' ? 4 : self.getSettings(sectionId, 'element_ready_global_section_particles_mode_remove').size;
            }

            if (self.getSettings(sectionId, 'element_ready_global_section_particles_mode_push')) {
                interactivity.modes.push.particles_nb = self.getSettings(sectionId, 'element_ready_global_section_particles_mode_push').size == '' ? 4 : self.getSettings(sectionId, 'element_ready_global_section_particles_mode_push').size;
            }

            settings.interactivity = interactivity;
            return settings;
        };


        self.particles_background = function(sectionId) {

            var section_p_background = false;

            var section_uid = 'element-ready-particles-background-' + sectionId;
            section_p_background = self.getSettings(sectionId, 'element_ready_background_effect_active');

            if (section_p_background == 'red') {

                $target.attr('id', section_uid);
                var json_obj = Element_Ready_Section.particles_settings(self.particles_section_option(sectionId));

                if (typeof someObject == 'undefined') Element_Ready_Section.loadScript(element_ready_script.particle, function() {

                    particlesJS(section_uid, json_obj);

                    /* ---- stats.js config ---- */
                    Element_Ready_Section.loadScript(element_ready_script.stats, function() {
                        var stats, update;
                        stats = new Stats;
                        stats.setMode(0);
                        stats.domElement.style.position = 'absolute';
                        stats.domElement.style.left = '0px';
                        stats.domElement.style.top = '0px';
                        document.body.appendChild(stats.domElement);
                        // count_particles = document.querySelector('.js-count-particles');
                        update = function() {
                            stats.begin();
                            stats.end();
                            //   if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
                            //    // count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
                            //   }
                            requestAnimationFrame(update);
                        };
                        requestAnimationFrame(update);
                    });



                });


            }


        };

        self.getSettings = function(sectionId, key) {
            var editorElements = null,
                sectionData = {};

            if (!editMode) {
                sectionId = 'section' + sectionId;

                if (!window.element_ready_section_data || !window.element_ready_section_data.hasOwnProperty(sectionId)) {
                    return false;
                }

                if (!window.element_ready_section_data[sectionId].hasOwnProperty(key)) {
                    return false;
                }

                return window.element_ready_section_data[sectionId][key];
            } else {

                if (!window.elementor.hasOwnProperty('elements')) {
                    return false;
                }
                editorElements = window.elementor.elements;

                if (!editorElements.models) {
                    return false;
                }
                $.each(editorElements.models, function(index, obj) {
                    if (sectionId == obj.id) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if (!sectionData.hasOwnProperty(key)) {
                    return false;
                }
            }

            return sectionData[key];
        };
    }

    $(window).on('elementor/frontend/init', function() {

        /* Sections */
        elementorFrontend.hooks.addAction('frontend/element_ready/section', Element_Ready_Section.elementorSection);
    
    });

})(jQuery);