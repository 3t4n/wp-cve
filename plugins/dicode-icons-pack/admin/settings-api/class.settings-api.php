<?php

if( ! defined( 'ABSPATH' ) ) exit(); 
if ( !class_exists( 'Dicode_Icons_Settings_API' ) ):
    class Dicode_Icons_Settings_API {

        /**
         * settings sections array
         *
         * @var array
         */
        protected $settings_sections = array();

        /**
         * Settings fields array
         *
         * @var array
         */
        protected $settings_fields = array();

        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        }

        /**
         * Enqueue scripts and styles
         */
        function admin_enqueue_scripts() {
            wp_enqueue_style( 'wp-color-picker' );

            wp_enqueue_media();
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'jquery' );
        }

        /**
         * Set settings sections
         *
         * @param array   $sections setting sections array
         */
        function set_sections( $sections ) {
            $this->settings_sections = $sections;

            return $this;
        }

        /**
         * Add a single section
         *
         * @param array   $section
         */
        function add_section( $section ) {
            $this->settings_sections[] = $section;

            return $this;
        }

        /**
         * Set settings fields
         *
         * @param array   $fields settings fields array
         */
        function set_fields( $fields ) {
            $this->settings_fields = $fields;

            return $this;
        }

        function add_field( $section, $field ) {
            $defaults = array(
                'name'  => '',
                'label' => '',
                'desc'  => '',
                'type'  => 'text'
            );

            $arg = wp_parse_args( $field, $defaults );
            $this->settings_fields[$section][] = $arg;

            return $this;
        }

        /**
         * Initialize and registers the settings sections and fileds to WordPress
         *
         * Usually this should be called at `admin_init` hook.
         *
         * This function gets the initiated settings sections and fields. Then
         * registers them to WordPress and ready for use.
         */
        function admin_init() {
            //register settings sections
            foreach ( $this->settings_sections as $section ) {
                if ( false == get_option( $section['id'] ) ) {
                    add_option( $section['id'] );
                }

                if ( isset($section['desc']) && !empty($section['desc']) ) {
                    $section['desc'] = '<div class="inside">' . esc_html($section['desc']) . '</div>';
                    $callback = function() use ( $section ) {
    		    echo str_replace( '"', '\"', esc_html($section['desc']) );
    		};
                } else if ( isset( $section['callback'] ) ) {
                    $callback = $section['callback'];
                } else {
                    $callback = null;
                }

                add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
            }

            //register settings fields
            foreach ( $this->settings_fields as $section => $field ) {
                foreach ( $field as $option ) {

                    $name = $option['name'];
                    $type = isset( $option['type'] ) ? $option['type'] : 'text';
                    $label = isset( $option['label'] ) ? $option['label'] : '';
                    $callback = isset( $option['callback'] ) ? $option['callback'] : array( $this, 'callback_' . $type );

                    $args = array(
                        'id'                => $name,
                        'class'             => isset( $option['class'] ) ? $option['class'] : $name,
                        'label_for'         => "{$section}[{$name}]",
                        'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
                        'name'              => $label,
                        'section'           => $section,
                        'size'              => isset( $option['size'] ) ? $option['size'] : null,
                        'options'           => isset( $option['options'] ) ? $option['options'] : '',
                        'std'               => isset( $option['default'] ) ? $option['default'] : '',
                        'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
                        'type'              => $type,
                        'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
                        'min'               => isset( $option['min'] ) ? $option['min'] : '',
                        'max'               => isset( $option['max'] ) ? $option['max'] : '',
                        'step'              => isset( $option['step'] ) ? $option['step'] : '',
						'url'				=> isset( $option['url'] ) ? $option['url'] : '',
						'count'				=> isset( $option['count'] ) ? $option['count'] : '',
                    );

                    add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
                }
            }

            // creates our settings in the options table
            foreach ( $this->settings_sections as $section ) {
                register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
            }
        }

        /**
         * Get field description for display
         *
         * @param array   $args settings field args
         */
        public function get_field_description( $args ) {
            if ( ! empty( $args['desc'] ) ) {
                $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
            } else {
                $desc = '';
            }

            return $desc;
        }


        /**
         * Displays a checkbox for a settings field
         *
         * @param array   $args settings field args
         */
        function callback_checkbox( $args ) {

            $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			echo '<span class="icons_count">'. esc_html($args['count']) .'</span>';
			echo '<a class="icons_source" href="'. esc_url($args['url']) .'" target="_blank">'. esc_html__('Source', 'dicode-icons-pack') .'</a>';
            echo '<fieldset class="dicode_checkbox_wrapper">';
				echo sprintf( '<label for="dicode-field-%1$s[%2$s]">', esc_attr($args['section']), esc_attr($args['id']) );
					echo sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', esc_attr($args['section']), esc_attr($args['id']) );
					echo sprintf( '<input type="checkbox" class="checkbox" id="dicode-field-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', esc_attr($args['section']), esc_attr($args['id']), checked( esc_attr($value), 'on', false ) ); // phpcs ok
					echo '<span class="slider round"></span>';
				echo '</label>';
			echo '</fieldset>';
			echo '<div>';
			echo '</div>';
        }

        /**
         * Sanitize callback for Settings API
         *
         * @return mixed
         */
        function sanitize_options( $options ) {

            if ( !$options ) {
                return $options;
            }

            foreach( $options as $option_slug => $option_value ) {
                $sanitize_callback = $this->get_sanitize_callback( $option_slug );

                // If callback is set, call it
                if ( $sanitize_callback ) {
                    $options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
                    continue;
                }
            }

            return $options;
        }

        /**
         * Get sanitization callback for given option slug
         *
         * @param string $slug option slug
         *
         * @return mixed string or bool false
         */
        function get_sanitize_callback( $slug = '' ) {
            if ( empty( $slug ) ) {
                return false;
            }

            // Iterate over registered fields and see if we can find proper callback
            foreach( $this->settings_fields as $section => $options ) {
                foreach ( $options as $option ) {
                    if ( $option['name'] != $slug ) {
                        continue;
                    }

                    // Return the callback name
                    return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
                }
            }

            return false;
        }

        /**
         * Get the value of a settings field
         *
         * @param string  $option  settings field name
         * @param string  $section the section name this field belongs to
         * @param string  $default default text if it's not found
         * @return string
         */
        function get_option( $option, $section, $default = '' ) {

            $options = get_option( $section );

            if ( isset( $options[$option] ) ) {
                return $options[$option];
            }

            return $default;
        }

        /**
         * Show navigations as tab
         *
         * Shows all the settings section labels as tab
         */
        function show_navigation() {
            echo '<h2 class="nav-tab-wrapper dicode-nav-tab-wrapper">';

				$count = count( $this->settings_sections );
				if ( $count === 1 ) {
					return;
				}

				foreach ( $this->settings_sections as $tab ) {
					echo '<a href="#'. esc_attr($tab['id']) .'" class="nav-tab" id="'. esc_attr($tab['id']) .'-tab">'. esc_html($tab['title']) .'</a>';
				}

            echo '</h2>';
        }

        /**
         * Show the section settings forms
         *
         * This function displays every sections in a different form
         */
        function show_forms() {
            ?>
            <div class="metabox-holder dicode-icons-admin-area">
                <?php foreach ( $this->settings_sections as $form ) { ?>
                    <div id="<?php echo $form['id']; ?>" class="group" style="display: none;">
                        <form method="post" action="options.php">
                            <?php
                            do_action( 'dicode_icons_form_top_' . esc_attr($form['id']), $form );
                            settings_fields( esc_attr($form['id']) );
                            do_settings_sections( esc_attr($form['id']) );
                            do_action( 'dicode_icons_form_bottom_' . esc_attr($form['id']), $form );
                            if ( isset( $this->settings_fields[ $form['id'] ] ) ):
                            ?>
                            <div style="padding-left: 10px">
                                <?php submit_button(); ?>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
            <?php
            $this->script();
        }

        /**
         * Tabbable JavaScript codes & Initiate Color Picker
         *
         * This code uses localstorage for displaying active tabs
         */
        function script() {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    //Initiate Color Picker
                    $('.wp-color-picker-field').wpColorPicker();

                    // Switches option sections
                    $('.group').hide();
                    var activetab = '';
                    if (typeof(localStorage) != 'undefined' ) {
                        activetab = localStorage.getItem("activetab");
                    }

                    //if url has section id as hash then set it as active or override the current local storage value
                    if(window.location.hash){
                        activetab = window.location.hash;
                        if (typeof(localStorage) != 'undefined' ) {
                            localStorage.setItem("activetab", activetab);
                        }
                    }

                    if (activetab != '' && $(activetab).length ) {
                        $(activetab).fadeIn();
                    } else {
                        $('.group:first').fadeIn();
                    }
                    $('.group .collapsed').each(function(){
                        $(this).find('input:checked').parent().parent().parent().nextAll().each(
                        function(){
                            if ($(this).hasClass('last')) {
                                $(this).removeClass('hidden');
                                return false;
                            }
                            $(this).filter('.hidden').removeClass('hidden');
                        });
                    });

                    if (activetab != '' && $(activetab + '-tab').length ) {
                        $(activetab + '-tab').addClass('nav-tab-active');
                    }
                    else {
                        $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                    }
                    $('.nav-tab-wrapper a').click(function(evt) {
                        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                        $(this).addClass('nav-tab-active').blur();
                        var clicked_group = $(this).attr('href');
                        if (typeof(localStorage) != 'undefined' ) {
                            localStorage.setItem("activetab", $(this).attr('href'));
                        }
                        $('.group').hide();
                        $(clicked_group).fadeIn();
                        evt.preventDefault();
                    });

                    $('.wpsa-browse').on('click', function (event) {
                        event.preventDefault();

                        var self = $(this);

                        // Create the media frame.
                        var file_frame = wp.media.frames.file_frame = wp.media({
                            title: self.data('uploader_title'),
                            button: {
                                text: self.data('uploader_button_text'),
                            },
                            multiple: false
                        });

                        file_frame.on('select', function () {
                            attachment = file_frame.state().get('selection').first().toJSON();
                            self.prev('.wpsa-url').val(attachment.url).change();
                        });

                        // Finally, open the modal
                        file_frame.open();
                    });
            });
            </script>
            <?php
            $this->_style_fix();
        }

        function _style_fix() {
            global $wp_version;

            if (version_compare($wp_version, '3.8', '<=')):
            ?>
            <style type="text/css">
                /** WordPress 3.8 Fix **/
                .form-table th { padding: 20px 10px; }
                #wpbody-content .metabox-holder { padding-top: 5px; }
            </style>
            <?php
            endif;
        }

    }

endif;
