<?php

namespace filr;

if ( !class_exists( 'FILR_Settings' ) ) {
    class FILR_Settings
    {
        /**
         * Sections array.
         *
         * @var   array
         * @since 1.0.0
         */
        private  $sections_array = array() ;
        /**
         * Fields array.
         *
         * @var   array
         * @since 1.0.0
         */
        private  $fields_array = array() ;
        /**
         * Constructor.
         *
         * @since  1.0.0
         */
        public function __construct()
        {
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        }
        
        /**
         * Admin Scripts.
         *
         * @since 1.0.0
         */
        public function admin_scripts()
        {
            // jQuery is needed.
            wp_enqueue_script( 'jquery' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
        }
        
        /**
         * Set Sections.
         *
         * @param array $sections
         *
         * @return FILR_Settings
         * @since 1.0.0
         */
        public function set_sections( array $sections ) : FILR_Settings
        {
            // Assign to the sections array.
            $this->sections_array = $sections;
            return $this;
        }
        
        /**
         * Add a single section.
         *
         * @param array $section
         *
         * @return FILR_Settings
         * @since 1.0.0
         */
        public function add_section( array $section ) : FILR_Settings
        {
            // Assign the section to sections array.
            $this->sections_array[] = $section;
            return $this;
        }
        
        /**
         * Set Fields.
         *
         * @since 1.0.0
         */
        public function set_fields( $fields )
        {
            // Bail if not array.
            if ( !is_array( $fields ) ) {
                return false;
            }
            // Assign the fields.
            $this->fields_array = $fields;
            return $this;
        }
        
        /**
         * Add a single field.
         *
         * @since 1.0.0
         */
        public function add_field( $section, $field_array ) : FILR_Settings
        {
            // Set the defaults
            $defaults = array(
                'id'   => '',
                'name' => '',
                'desc' => '',
                'type' => 'text',
            );
            // Combine the defaults with user's arguements.
            $arg = wp_parse_args( $field_array, $defaults );
            // Each field is an array named against its section.
            $this->fields_array[$section][] = $arg;
            return $this;
        }
        
        /**
         * Initialize API.
         *
         * Initializes and registers the settings sections and fields.
         * Usually this should be called at `admin_init` hook.
         *
         * @since  1.0.0
         */
        public function admin_init()
        {
            /**
             * Register the sections.
             *
             * Sections array is like this:
             *
             * $sections_array = array (
             *   $section_array,
             *   $section_array,
             *   $section_array,
             * );
             *
             * Section array is like this:
             *
             * $section_array = array (
             *   'id'    => 'section_id',
             *   'title' => 'Section Title'
             * );
             *
             * @since 1.0.0
             */
            foreach ( $this->sections_array as $section ) {
                if ( !get_option( $section['id'] ) ) {
                    // Add a new field as section ID.
                    add_option( $section['id'] );
                }
                // Deals with sections description.
                
                if ( isset( $section['desc'] ) && !empty($section['desc']) ) {
                    // Create the callback for description.
                    $callback = function () use( $section ) {
                        echo  esc_html( str_replace( '"', '\\"', $section['desc'] ) ) ;
                    };
                } elseif ( isset( $section['callback'] ) ) {
                    $callback = esc_html( $section['callback'] );
                } else {
                    $callback = null;
                }
                
                /**
                 * Add a new section to a settings page.
                 *
                 * @param string $id
                 * @param string $title
                 * @param callable $callback
                 * @param string $page | Page is same as section ID.
                 *
                 * @since 1.0.0
                 */
                add_settings_section(
                    $section['id'],
                    $section['title'],
                    $callback,
                    $section['id']
                );
            }
            // foreach ended.
            /**
             * Register settings fields.
             *
             * Fields array is like this:
             *
             * $fields_array = array (
             *   $section => $field_array,
             *   $section => $field_array,
             *   $section => $field_array,
             * );
             *
             *
             * Field array is like this:
             *
             * $field_array = array (
             *   'id'   => 'id',
             *   'name' => 'Name',
             *   'type' => 'text',
             * );
             *
             * @since 1.0.0
             */
            foreach ( $this->fields_array as $section => $field_array ) {
                foreach ( $field_array as $field ) {
                    // ID.
                    $id = $field['id'] ?? false;
                    // Type.
                    $type = $field['type'] ?? 'text';
                    // Name.
                    $name = $field['name'] ?? 'No Name Added';
                    // Label for.
                    $label_for = "{$section}[{$field['id']}]";
                    // Description.
                    $description = $field['desc'] ?? '';
                    // Premium.
                    $premium = $field['premium'] ?? '';
                    // Min and max.
                    $min = $field['min'] ?? '';
                    $max = $field['max'] ?? '';
                    // Class.
                    $class = $field['class'] ?? '';
                    // Size.
                    $size = $field['size'] ?? null;
                    // Options.
                    $options = $field['options'] ?? '';
                    // Standard default value.
                    $default = $field['default'] ?? '';
                    // Standard default placeholder.
                    $placeholder = $field['placeholder'] ?? '';
                    // Sanitize Callback.
                    $sanitize_callback = $field['sanitize_callback'] ?? '';
                    $args = array(
                        'id'                => $id,
                        'type'              => $type,
                        'name'              => $name,
                        'label_for'         => $label_for,
                        'desc'              => $description,
                        'premium'           => $premium,
                        'min'               => $min,
                        'max'               => $max,
                        'class'             => $class,
                        'section'           => $section,
                        'size'              => $size,
                        'options'           => $options,
                        'std'               => $default,
                        'placeholder'       => $placeholder,
                        'sanitize_callback' => $sanitize_callback,
                    );
                    /**
                     * Add a new field to a section of a settings page.
                     *
                     * @param string $id
                     * @param string $title
                     * @param callable $callback
                     * @param string $page
                     * @param string $section = 'default'
                     * @param array $args = array()
                     *
                     * @since 1.0.0
                     */
                    // @param string 	$id
                    $field_id = $section . '[' . $field['id'] . ']';
                    add_settings_field(
                        $field_id,
                        $name,
                        array( $this, 'callback_' . $type ),
                        $section,
                        $section,
                        $args
                    );
                }
                // foreach ended.
            }
            // foreach ended.
            // Creates our settings in the fields table.
            foreach ( $this->sections_array as $section ) {
                /**
                 * Registers a setting and its sanitization callback.
                 *
                 * @param string $field_group | A settings group name.
                 * @param string $field_name | The name of an option to sanitize and save.
                 * @param callable $sanitize_callback = ''
                 *
                 * @since 1.0.0
                 */
                register_setting( $section['id'], $section['id'], array( $this, 'sanitize_fields' ) );
            }
            // foreach ended.
        }
        
        // admin_init() ended.
        /**
         * Sanitize callback for Settings API fields.
         *
         * @since 1.0.0
         */
        public function sanitize_fields( $fields )
        {
            foreach ( $fields as $field_slug => $field_value ) {
                $sanitize_callback = $this->get_sanitize_callback( $field_slug );
                // If callback is set, call it.
                
                if ( $sanitize_callback ) {
                    $fields[$field_slug] = call_user_func( $sanitize_callback, $field_value );
                    continue;
                }
            
            }
            return $fields;
        }
        
        /**
         * Get sanitization callback for given option slug
         *
         * @param string $slug option slug.
         *
         * @return bool
         * @since  1.0.0
         */
        public function get_sanitize_callback( string $slug = '' )
        {
            if ( empty($slug) ) {
                return false;
            }
            // Iterate over registered fields and see if we can find proper callback.
            foreach ( $this->fields_array as $section => $field_array ) {
                foreach ( $field_array as $field ) {
                    if ( $field['name'] != $slug ) {
                        continue;
                    }
                    // Return the callback name.
                    return ( isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false );
                }
            }
            return false;
        }
        
        /**
         * Get field description for display
         *
         * @param array $args settings field args.
         */
        public function get_field_description( array $args ) : string
        {
            
            if ( !empty($args['desc']) ) {
                $desc = sprintf( '<p class="description">%s</p>', wp_kses_post( $args['desc'] ) );
            } else {
                $desc = '';
            }
            
            return $desc;
        }
        
        /**
         * Displays a documentation field for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_documentation( array $args )
        {
            if ( !empty($args['desc']) ) {
                echo  sprintf( '<p class="description">%s</p>', wp_kses_post( $args['desc'] ) ) ;
            }
        }
        
        /**
         * Displays a text field for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_text( array $args )
        {
            $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $size = $args['size'] ?? 'regular';
            $type = $args['type'] ?? 'text';
            ?>
                <label for="<?php 
            echo  esc_attr( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]"></label>
            <input type="<?php 
            echo  esc_attr( $type ) ;
            ?>" class="<?php 
            echo  esc_html( $size ) ;
            ?>-text <?php 
            echo  esc_html( $args['class'] ) ;
            ?> <?php 
            echo  esc_html( $args['premium'] ) ;
            ?>"
                   id="<?php 
            echo  esc_attr( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]" name="<?php 
            echo  esc_attr( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]"
                   value="<?php 
            echo  esc_html( $value ) ;
            ?>" placeholder="<?php 
            esc_html( $args['placeholder'] );
            ?>"/>
            <?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
            <?php 
        }
        
        /**
         * Displays a url field for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_url( array $args )
        {
            $this->callback_text( $args );
        }
        
        /**
         * Displays a number field for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_number( array $args )
        {
            $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $size = $args['size'] ?? 'regular';
            $type = $args['type'] ?? 'number';
            $min = ( isset( $args['min'] ) ? 'min="' . esc_html( $args['min'] ) . '"' : '' );
            $max = ( isset( $args['max'] ) ? 'max="' . esc_html( $args['max'] ) . '"' : '' );
            ?>
            <label for="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"></label>
            <input type="<?php 
            echo  esc_html( $type ) ;
            ?>" class="<?php 
            echo  esc_html( $size ) ;
            ?>-text <?php 
            echo  esc_html( $args['class'] ) ;
            ?>  <?php 
            echo  esc_html( $args['premium'] ) ;
            ?>"
                   id="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]" name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"
                   <?php 
            echo  esc_html( $min ) ;
            ?> <?php 
            echo  esc_html( $max ) ;
            ?> value="<?php 
            echo  esc_html( $value ) ;
            ?>" placeholder="<?php 
            echo  esc_html( $args['placeholder'] ) ;
            ?>"/>
                   <?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
		<?php 
        }
        
        /**
         * Displays a checkbox for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_checkbox( array $args )
        {
            $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $checked = checked( $value, 'on', false );
            ?>
            <fieldset>
                <label for="wposa-<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]">
                    <input type="hidden" name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]" value="off" />
                    <input type="checkbox" class="checkbox" id="wposa-<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]" name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_attr( $args['id'] ) ;
            ?>]" value="on" <?php 
            echo  esc_attr( $checked ) ;
            ?> />
                </label>
            </fieldset>
            <?php 
        }
        
        /**
         * Displays a multicheckbox a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_radio( array $args )
        {
            $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
            ?>
            <fieldset>
                <?php 
            foreach ( $args['options'] as $key => $label ) {
                ?>
                    <?php 
                $checked = checked( $value, $key, false );
                ?>
                    <label for="wposa-<?php 
                echo  esc_html( $args['section'] ) ;
                ?>[<?php 
                echo  esc_html( $args['id'] ) ;
                ?>][<?php 
                echo  esc_html( $key ) ;
                ?>]">
                        <input type="radio" class="radio" id="wposa-<?php 
                echo  esc_html( $args['section'] ) ;
                ?>[<?php 
                echo  esc_html( $args['id'] ) ;
                ?>][<?php 
                echo  esc_html( $key ) ;
                ?>]"
                               name="<?php 
                echo  esc_html( $args['section'] ) ;
                ?>[<?php 
                echo  esc_html( $args['id'] ) ;
                ?>]"
                               value="<?php 
                echo  esc_html( $key ) ;
                ?>" <?php 
                echo  esc_attr( $checked ) ;
                ?> />
                               <?php 
                echo  esc_html( $label ) ;
                ?>
                    </label>
                    <br>
                <?php 
            }
            ?>
            </fieldset>
			<?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
            <?php 
        }
        
        /**
         * Displays a select box for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_select( array $args )
        {
            $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $size = $args['size'] ?? 'regular';
            ?>
            <select class="<?php 
            echo  esc_attr( $size ) ;
            ?> <?php 
            echo  esc_html( $args['premium'] ) ;
            ?>"
                    name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"
                    id="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]">
                    <?php 
            foreach ( $args['options'] as $key => $label ) {
                ?>
                        <?php 
                $selected = selected( $value, $key, false );
                ?>
                        <option value="<?php 
                echo  esc_attr( $key ) ;
                ?>" <?php 
                echo  esc_html( $selected ) ;
                ?>><?php 
                echo  esc_html( $label ) ;
                ?></option>
                    <?php 
            }
            ?>
            </select>
			<?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
            <?php 
        }
        
        /**
         * Displays a textarea for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_textarea( array $args )
        {
            $value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $size = $args['size'] ?? 'regular';
            ?>
            <textarea rows="5" cols="55" class="<?php 
            echo  esc_html( $size ) ;
            ?>-text" id="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"
                      name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"><?php 
            echo  esc_html( $value ) ;
            ?></textarea>
			<?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
            <?php 
        }
        
        /**
         * Displays a textarea for a settings field
         *
         * @param array $args settings field args.
         *
         * @return void
         */
        public function callback_html( array $args )
        {
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
        }
        
        /**
         * Displays a rich text textarea for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_wysiwyg( array $args )
        {
            $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
            $size = $args['size'] ?? '500px';
            $editor_settings = array(
                'teeny'         => true,
                'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
                'textarea_rows' => 10,
            );
            if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
                $editor_settings = array_merge( $editor_settings, $args['options'] );
            }
            ?>
            <div style="max-width: <?php 
            echo  esc_html( $size ) ;
            ?>">
                <?php 
            wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );
            ?>
            </div>
            <?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
            <?php 
        }
        
        /**
         * Displays a toggle for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_toggle( array $args )
        {
            $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $checked = checked( $value, 'on', false );
            ?>
            <fieldset>
                <label class="switch" for="wposa-<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]">
                    <input type="hidden" name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]" value="off" />
                    <input type="checkbox" class="toggle-checkbox <?php 
            echo  esc_html( $args['premium'] ) ;
            ?>" id="wposa-<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"
                           name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]" value="on" <?php 
            echo  esc_attr( $checked ) ;
            ?> />
                    <span class="slider round"></span>
                </label>
            </fieldset>
            <?php 
        }
        
        /**
         * Displays a color picker field for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_color( array $args )
        {
            $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
            $size = $args['size'] ?? 'regular';
            ?>
             <label for="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"></label>
            <input type="text" class="<?php 
            echo  esc_html( $size ) ;
            ?>-text color-picker" data-alpha="true" id="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]"
                   name="<?php 
            echo  esc_html( $args['section'] ) ;
            ?>[<?php 
            echo  esc_html( $args['id'] ) ;
            ?>]" value="<?php 
            echo  esc_html( $value ) ;
            ?>" data-default-color="<?php 
            echo  esc_html( $args['std'] ) ;
            ?>"
                   placeholder="<?php 
            echo  esc_html( $args['placeholder'] ) ;
            ?>" />
			<?php 
            echo  wp_kses_post( $this->get_field_description( $args ) ) ;
            ?>
			<?php 
        }
        
        /**
         * Displays a separator field for a settings field
         *
         * @param array $args settings field args.
         */
        public function callback_separator( array $args )
        {
            ?>
            <div class="filr-settings-separator"></div>
            <?php 
        }
        
        /**
         * Get the value of a settings field
         *
         * @param string $option settings field name.
         * @param string $section the section name this field belongs to.
         * @param string $default default text if it's not found.
         *
         * @return string
         */
        public function get_option( string $option, string $section, string $default = '' ) : string
        {
            $options = get_option( $section );
            if ( isset( $options[$option] ) ) {
                return $options[$option];
            }
            return $default;
        }
        
        /**
         * Add submenu page to the Settings main menu.
         *
         * @return void
         */
        public function admin_menu()
        {
            add_submenu_page(
                'edit.php?post_type=filr',
                esc_html__( 'Settings', 'filr' ),
                esc_html__( 'Settings', 'filr' ),
                'manage_options',
                'filr_settings',
                array( $this, 'plugin_page' )
            );
        }
        
        /**
         * Add admin page content.
         *
         * @return void
         */
        public function plugin_page()
        {
            ?>
			<div class="filr-admin-header">
				<div class="logo"><img src="<?php 
            echo  FILR_URL . '/assets/filr-logo.svg' ;
            ?>" alt="filr-logo" /></div>
				<div class="info-links">
				<?php 
            ?>
					<a href="https://patrickposner.dev/plugins/filr/" target="_blank">Go Pro</a>
					<a href="https://patrickposner.dev/docs/filr/" target="_blank">Documentation</a>
					<a href="https://wordpress.org/plugins/filr-protection" target="_blank">Support</a>
				<?php 
            ?>
				</div>
			</div>
			<div class="wrap filr-admin">
				<?php 
            $this->show_navigation();
            ?>
				<?php 
            $this->show_forms();
            ?>
			</div>
			<?php 
        }
        
        /**
         * Show navigations as tab
         *
         * Shows all the settings section labels as tab
         */
        public function show_navigation()
        {
            ?>
            <h2 class="nav-tab-wrapper">
            <?php 
            foreach ( $this->sections_array as $tab ) {
                ?>
                <a href="#<?php 
                echo  esc_html( $tab['id'] ) ;
                ?>" class="nav-tab" id="<?php 
                echo  esc_html( $tab['id'] ) ;
                ?>-tab"><?php 
                echo  esc_html( $tab['title'] ) ;
                ?></a>
            <?php 
            }
            ?>
            </h2>
            <?php 
        }
        
        /**
         * Show the section settings forms
         *
         * This function displays every sections in a different form
         */
        public function show_forms()
        {
            ?>
			<div class="metabox-holder">
				<?php 
            foreach ( $this->sections_array as $form ) {
                ?>
					<!-- style="display: none;" -->
					<div id="<?php 
                echo  esc_attr( $form['id'] ) ;
                ?>" class="group">
						<form method="post" action="options.php">
							<?php 
                do_action( 'wsa_form_top_' . $form['id'], $form );
                settings_fields( $form['id'] );
                do_settings_sections( $form['id'] );
                do_action( 'wsa_form_bottom_' . $form['id'], $form );
                ?>
							<div style="padding-left: 10px">
								<?php 
                submit_button();
                ?>
							</div>
						</form>
					</div>
				<?php 
            }
            ?>
			</div>
			<?php 
            $this->script();
        }
        
        /**
         * Tabbable JavaScript codes & Initiate Color Picker
         *
         * This code uses localstorage for displaying active tabs
         */
        public function script()
        {
            ?>
			<script>
				jQuery(document).ready(function ($) {

					//Initiate Color Picker.
					$('.color-picker').wpColorPicker();

					// Code Mirror.
					$(".code-textarea").each(function() {
							wp.codeEditor.initialize($(this), filr_settings);
						});

					// Switches option sections
					$('.group').hide();
                    let activetab = '';
					if ('undefined' != typeof localStorage) {
						activetab = localStorage.getItem('activetab');
					}
					if ('' != activetab && $(activetab).length) {
						$(activetab).fadeIn();
					} else {
						$('.group:first').fadeIn();
					}
					$('.group .collapsed').each(function () {
						$(this)
							.find('input:checked')
							.parent()
							.parent()
							.parent()
							.nextAll()
							.each(function () {
								if ($(this).hasClass('last')) {
									$(this).removeClass('hidden');
									return false;
								}
								$(this)
									.filter('.hidden')
									.removeClass('hidden');
							});
					});

					if ('' != activetab && $(activetab + '-tab').length) {
						$(activetab + '-tab').addClass('nav-tab-active');
					} else {
						$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
					}
					$('.nav-tab-wrapper a').click(function (evt) {
						$('.nav-tab-wrapper a').removeClass('nav-tab-active');
						$(this)
							.addClass('nav-tab-active')
							.blur();
                        let clicked_group = $(this).attr('href');
						if ('undefined' != typeof localStorage) {
							localStorage.setItem('activetab', $(this).attr('href'));
						}
						$('.group').hide();
						$(clicked_group).fadeIn();
						evt.preventDefault();
					});

					$('input.filr-url')
						.on('change keyup paste input', function () {
							let self = $(this);
							self
								.next()
								.parent()
								.children('.filr-image-preview')
								.children('img')
								.attr('src', self.val());
						})
						.change();
				});

			</script>

			<style>
				/** WordPress 3.8 Fix **/
				.form-table th {
					padding: 20px 10px;
				}

				#wpbody-content .metabox-holder {
					padding-top: 5px;
				}

				.filr-image-preview img {
					height: auto;
					max-width: 70px;
				}

				.filr-settings-separator {
					background: #ccc;
					border: 0;
					color: #ccc;
					height: 1px;
					position: absolute;
					left: 0;
					width: 99%;
				}

				.group .form-table input.color-picker {
					max-width: 100px;
				}
			</style>
			<?php 
        }
    
    }
}