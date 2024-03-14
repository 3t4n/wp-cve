<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Lucas_String_Replace_Settings {

    private static $_instance = null;
    public $parent = null;
    public $base = '';

    public $settings;



    public function __construct( $parent ){

        $this->parent = $parent;
        $this->base = $this->parent->_token . '_settings_';

        add_action( 'init', array( $this, 'init_settings' ), 11);
        add_action( 'admin_init' , array( $this, 'register_settings' ) );
        add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

    }



    private function settings_fields () {

        $settings['default'] = array(
			'title'					=> __('String Replace', 'lucas-string-replace'),
			'description'			=> '',
			'fields'				=> array(
				array(
                    'id' 			=> 'replacesets',
                    'label'			=> '',
                    'description'	=> '',
                    'type'			=> 'text',
                    'default'		=> serialize(array(array( array(''), '')))
                )
			)
        );

        $settings['settings'] = array(
			'title'					=> __('Settings', 'lucas-string-replace'),
			'description'			=> __('Change settings to adjust general plugin behavior', 'lucas-string-replace'),
			'fields'				=> array(
				array(
					'id' 			=> 'enable',
					'label'			=> __('Enable', 'lucas-string-replace'),
					'description'	=> __('If this option is active, Lucas String Replace will work on your site', 'lucas-string-replace'),
					'type'			=> 'checkbox',
					'default'		=> 'on'
                ),
                array(
					'id' 			=> 'enable_on_admin',
					'label'			=> __('Enable on Admin', 'lucas-string-replace'),
					'description'	=> __('If this option is active, Lucas String Replace will also work on admin pages', 'lucas-string-replace'),
					'type'			=> 'checkbox',
                    'default'		=> 'off'
                ),
                array(
					'id' 			=> 'delsettuninst',
					'label'			=> __('Delete settings on uninstall', 'lucas-string-replace'),
					'description'	=> __('If this option is activated, all settings will be deleted during deinstallation', 'lucas-string-replace'),
					'type'			=> 'checkbox',
                    'default'		=> 'off'
				)
			)
        );

        $settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

        return $settings;
    }



    public function init_settings () {
		$this->settings = $this->settings_fields();
    }



    public function add_menu_item () {
		add_options_page( 'Lucas String Replace', 'Lucas String Replace', 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'render_settings_page' ) );
    }



    public function register_settings () {

		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
                    register_setting( $this->parent->_token, $option_name, $validation );

                    // Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this, 'display_field' ), $this->parent->_token, $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
        }
        
    }



    public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}
    


    public function render_settings_page() {

        // Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '">' . "\n";
		$html .= '<h2>Lucas String Replace</h2>' . "\n";
		$html .= '<p>' . __('Change text, words, styles, scripts, anything: Lucas String Replace takes the final output of WordPress and replaces the defined strings with another string', 'lucas-string-replace') . '</p>';

        $tab = '';
        if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
            $tab .= $_GET['tab'];
        }

        // Show page tabs
        if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

            $html .= '<h2 class="nav-tab-wrapper">' . "\n";

            $c = 0;
            foreach ( $this->settings as $section => $data ) {

                // Set tab class
                $class = 'nav-tab';
                if ( ! isset( $_GET['tab'] ) ) {
                    if ( 0 == $c ) {
                        $class .= ' nav-tab-active';
                    }
                } else {
                    if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
                        $class .= ' nav-tab-active';
                    }
                }
                
                // Set tab link
                $tab_link = add_query_arg( array( 'tab' => $section ) );
                $tab_link = remove_query_arg('lsr_action', $tab_link);
                if ( isset( $_GET['settings-updated'] ) ) {
                    $tab_link = remove_query_arg( 'settings-updated', $tab_link );
                }

                // Output tab
                $html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

                ++$c;
            }

            $html .= '</h2>' . "\n";
        }

        if( empty($tab) || $tab == 'default' ){

            // Save?
            $saved = false;
            if( isset($_GET['lsr_action']) && $_GET['lsr_action'] == 'save' ) {
                $saved = $this->save_settings();
            }

            $replacesets = get_option( $this->parent->_token . '_settings_replacesets' );
            if( ! $replacesets ){
                $replacesets = $this->settings['default']['fields'][0]['default'];
            }

            $replacesets = lsr_stripslashes( unserialize($replacesets) );

            if( is_array($replacesets) ){

                // Inline CSS
                $html .= $this->get_inline_styles();

                // Begin form
                $html .= '<form method="post" action="' . add_query_arg('lsr_action', 'save') . '" enctype="multipart/form-data">' . "\n";
                $html .= '<input type="hidden" name="lsr_nonce" value="' . wp_create_nonce('lsr-save') . '" />' . "\n";

                $saved_message = "";
                if( $saved ){
                    $saved_message = '&nbsp;&nbsp;&nbsp;<span style="color: green; font-weight: bold;">' . __('Saved', 'lucas-string-replace') . '</span>';
                }

                // Add controls
                $html .= '<div id="lsr-controls"><button id="lsr-addnew" type="button" class="button" onclick="lsr_addReplaceSet()">'; 
                $html .= __('Add new', 'lucas-string-replace') . '</button> <button id="lsr-saveall" type="submit" class="button-primary" type="submit">';
                $html .= __('Save', 'lucas-string-replace') . '</button>' . $saved_message . '</div>' . "\n";

                $html .= '<div id="replacesets">' . "\n";

                $cnt = 0;
                foreach( $replacesets as $replaceset ){

                    if( (isset($replaceset[0]) && is_array($replaceset[0])) && isset($replaceset[1]) ){
                        $search = $replaceset[0];
                        $replace = $replaceset[1];
                    } else {
                        continue;
                    }

                    $html .= $this->display_replaceset($cnt, $search, $replace);

                    $cnt++;
                } // end foreach

                $html .= '</div>' . "\n";
                $html .= '</form>' . "\n";

                // Inline scripts
                $html .= $this->get_inline_scripts($cnt);

            } else {
                $html .= '<p>' . __('There was an error reading your settings. Please uninstall the plugin and install it again.', 'lucas-string-replace') . '</p>';
            }

        } else {

            $html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

            // Get settings fields
            ob_start();
            settings_fields( $this->parent->_token );
            do_settings_sections( $this->parent->_token );
            $html .= ob_get_clean();

            $saved_message = "";
            if( isset($_GET['settings-updated']) ){
                $saved_message = '&nbsp;&nbsp;&nbsp;<span style="color: green; font-weight: bold;">' . __('Saved', 'lucas-string-replace') . '</span>';
            }

            $html .= '<p class="submit">' . "\n";
            $html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
            $html .= '<input name="Submit" type="submit" class="button-primary" value="' . __('Save', 'lucas-string-replace') . '" />' . $saved_message . "\n";
            $html .= '</p>' . "\n";
            $html .= '</form>' . "\n";
        }

        $html .= '</div>' . "\n";
		echo $html;
    }



    public function save_settings() {
        
        // check nonce
        $nonceok = isset($_POST['lsr_nonce']) ? wp_verify_nonce($_POST['lsr_nonce'], 'lsr-save') : false;
        if( ! $nonceok ) return false;

        $replacesets = "";

        if( isset($_POST['replaceset']) ){
            $replacesets = $_POST['replaceset'];
        }

        if( ! is_array($replacesets) ){
            $replacesets = lsr_stripslashes( unserialize($this->settings['default']['fields'][0]['default']) );
        }

        $value = array(); // the value that is stored

        foreach( $replacesets as $replaceset ){

            $element = array(array(), ""); // the new element

            $search = (isset($replaceset[0]) && is_array($replaceset[0])) ? $replaceset[0] : array("");
            $replace = (isset($replaceset[1])) ? $replaceset[1] : "";

            foreach( $search as $s ){
                if( ! empty($s) ) array_push( $element[0], $s );
            }

            // may also be empty (e.g. to remove a string)
            //if( ! empty($replace) ) $element[1] = $replace;
            $element[1] = $replace;

            // add to $values for saving
            if( ! empty($element[0][0]) && ! empty($element[1]) ){
                array_push($value, $element);
            }
        }

        update_option( $this->parent->_token . '_settings_replacesets', serialize($value) );

        return true;
    }



    public function get_inline_styles() {
        return '<style>
            .replaceset {
                padding: 1%;
                margin-bottom: 1%;
                display: flex;
                border: 1px solid #e5e5e5;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                background-color: #fff;
            }
            #replacesets {
                padding-top: 1%;
            }
            #lsr-controls {
                padding-top: 1%;
            }
            .replaceset-search, .replaceset-replace {
                width: 40%;
                flex: 1;
            }
            .replaceset-controls {
                padding-left: 1%;
            }
            .replaceset-search {
                padding-right: 1%;
            }
            .replaceset-search textarea, .replaceset-replace textarea {
                width: 100%;
            }
            .replaceset-search > .addsearch {
                float: right;
            }
        </style>';
    }



    public function get_inline_scripts($cnt){
        return '<script type="text/javascript">
            var lsr_id_next = ' . $cnt . ';
            var lsr_replaceset_template = ' . json_encode( $this->display_replaceset('{index}', array(""), "") ) . ';

            function lsr_addReplaceSet() {
                var div = document.getElementById(\'replacesets\');
                var html = lsr_replaceset_template.split("{index}").join(lsr_id_next);
                var container = document.createElement("div");
                container.innerHTML = html;
                div.appendChild(container);
                lsr_id_next += 1;
            }

            function lsr_removeReplaceSet(target) {
                var element = document.getElementById(target);
                element.remove();
            }

            function lsr_appendSearchBox(id, target) {
                var div = document.getElementById(target);
                var btn = \'<textarea name="replaceset[\' + id + \'][0][]" type="text" placeholder="Search"></textarea>\';
                var container = document.createElement("div");
                container.innerHTML = btn;
                div.appendChild(container);
            }
        </script>';
    }



    public function display_replaceset( $cnt, $search, $replace ) {

        $html = '<div id="replaceset-' . $cnt . '" class="replaceset">' . "\n";
            $html .= '<div id="replaceset-' . $cnt . '-search" class="replaceset-search">' . "\n";
                $html .= '<div id="replaceset-' . $cnt . '-search-list">' . "\n";
                    foreach( $search as $s ){
                        $html .= '<textarea name="replaceset[' . $cnt .'][0][]" type="text" placeholder="Search">' . $s . '</textarea>' . "\n";
                    }
                $html .= '</div>' . "\n";
                $html .= '<button type="button" class="button button-small addsearch" onclick="lsr_appendSearchBox(' . $cnt .', \'replaceset-' . $cnt . '-search-list\')">' . __('Add', 'lucas-string-replace') . '</button>' . "\n";   
            $html .= '</div>' . "\n";
            $html .= '<div id="replaceset-' . $cnt . '-replace" class="replaceset-replace">' . "\n";
                $html .= '<textarea name="replaceset[' . $cnt .'][1]" type="text" placeholder="Replace">' . $replace . '</textarea>' . "\n";
            $html .= '</div>' . "\n";
            $html .= '<div id="replaceset-' . $cnt . '-controls" class="replaceset-controls">' . "\n";
                    $html .= '<button type="button" class="button button-small" onclick="lsr_removeReplaceSet(\'replaceset-' . $cnt . '\')">' . __('Remove', 'lucas-string-replace') . '</button>' . "\n";
            $html .= '</div>' . "\n";
        $html .= '</div>' . "\n";
        return $html;
    }



    public function display_field( $data = array(), $post = false, $echo = true ) {

        // Get field info
        if ( isset( $data['field'] ) ) {
            $field = $data['field'];
        } else {
            $field = $data;
        }

        // Check for prefix on option name
        $option_name = '';
        if ( isset( $data['prefix'] ) ) {
            $option_name = $data['prefix'];
        }

        // Get saved data
        $data = '';
        if ( $post ) {

            // Get saved field data
            $option_name .= $field['id'];
            $option = get_post_meta( $post->ID, $field['id'], true );

            // Get data to display in field
            if ( isset( $option ) ) {
                $data = $option;
            }

        } else {

            // Get saved option
            $option_name .= $field['id'];
            $option = get_option( $option_name );

            // Get data to display in field
            if ( isset( $option ) ) {
                $data = $option;
            }

        }

        // Show default data if no option saved and default is supplied
        if ( $data === false && isset( $field['default'] ) ) {
            $data = $field['default'];
        } elseif ( $data === false ) {
            $data = '';
        }

        $html = '';

        switch( $field['type'] ) {

            case 'text':
            case 'url':
            case 'email':
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
            break;

            case 'password':
            case 'number':
            case 'hidden':
                $min = '';
                if ( isset( $field['min'] ) ) {
                    $min = ' min="' . esc_attr( $field['min'] ) . '"';
                }

                $max = '';
                if ( isset( $field['max'] ) ) {
                    $max = ' max="' . esc_attr( $field['max'] ) . '"';
                }
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $min . '' . $max . '/>' . "\n";
            break;

            case 'text_secret':
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="" />' . "\n";
            break;

            case 'textarea':
                $html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
            break;

            case 'checkbox':
                $checked = '';
                if ( $data && 'on' == $data ) {
                    $checked = 'checked="checked"';
                }
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
            break;

            case 'checkbox_multi':
                foreach ( $field['options'] as $k => $v ) {
                    $checked = false;
                    if ( in_array( $k, (array) $data ) ) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="checkbox_multi"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
                }
            break;

            case 'radio':
                foreach ( $field['options'] as $k => $v ) {
                    $checked = false;
                    if ( $k == $data ) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
                }
            break;

            case 'select':
                $html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
                foreach ( $field['options'] as $k => $v ) {
                    $selected = false;
                    if ( $k == $data ) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
                }
                $html .= '</select> ';
            break;

            case 'select_multi':
                $html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
                foreach ( $field['options'] as $k => $v ) {
                    $selected = false;
                    if ( in_array( $k, (array) $data ) ) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
                }
                $html .= '</select> ';
            break;

            case 'image':
                $image_thumb = '';
                if ( $data ) {
                    $image_thumb = wp_get_attachment_thumb_url( $data );
                }
                $html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";
                $html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . __( 'Upload an image' , 'lucas-webp' ) . '" data-uploader_button_text="' . __( 'Use image' , 'lucas-webp' ) . '" class="image_upload_button button" value="'. __( 'Upload new image' , 'lucas-webp' ) . '" />' . "\n";
                $html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="'. __( 'Remove image' , 'lucas-webp' ) . '" />' . "\n";
                $html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";
            break;

            case 'color':
                ?><div class="color-picker" style="position:relative;">
                    <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
                    <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
                </div>
                <?php
            break;

        }

        switch( $field['type'] ) {

            case 'checkbox_multi':
            case 'radio':
            case 'select_multi':
                $html .= '<br/><span class="description">' . $field['description'] . '</span>';
            break;

            default:
                if ( ! $post ) {
                    $html .= '<label for="' . esc_attr( $field['id'] ) . '">' . "\n";
                }

                $html .= '<span class="description">' . $field['description'] . '</span>' . "\n";

                if ( ! $post ) {
                    $html .= '</label>' . "\n";
                }
            break;
        }

        if ( ! $echo ) {
            return $html;
        }

        echo $html;

    }



    public static function instance( $parent ) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self( $parent );
        }
        return self::$_instance;
	}

}