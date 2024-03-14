<?php

class Form_Custom {

    public static $key = 'emma_form_custom';

    public static $settings;

    function __construct() {

        add_action( 'admin_init', array( &$this, 'register_settings' ) );

        self::$settings = $this->get_settings_options();

    }

    public static function get_settings_options() {

        // load the settings from the database
        $settings_options = (array) get_option( self::$key );

        // merge with defaults
        $settings_options = array_merge( self::get_settings_defaults(), $settings_options );

        return $settings_options;

    }

    public static function get_settings_defaults() {
        $defaults = array(
        	'form_layout_select' => 'vertical',
            'border_width' => '1',
            'border_color' => '000',
            'border_type' => 'solid',
            'txt_color' => '000',
            'bg_color' => 'FFF',
            'submit_btn_width' => '',
            'submit_txt_color' => 'FFF',
            'submit_bg_color' => '000',
            'submit_border_width' => '1',
            'submit_border_color' => '555',
            'submit_border_type' => 'solid',
            'submit_hover_txt_color' => '000',
            'submit_hover_border_width' => '1',
            'submit_hover_bg_color' => 'FFF',
            'submit_hover_border_color' => '555',
            'submit_hover_border_type' => 'solid'
        );
        return $defaults;
    }

    function register_settings() {

        // register_setting( $option_group, $option_name, $sanitize_callback );
        register_setting( self::$key, self::$key, array( &$this, 'sanitize_form_custom_settings') );

        // add_settings_section( $id, $title, $callback, $page );
		
		add_settings_section( 'section_form_layout_select', 'Form Layout', array( &$this, 'section_form_layout_select_desc' ), self::$key );
        add_settings_field( 'form_layout_select', 'Form Layout', array( &$this, 'field_form_layout_select' ), self::$key, 'section_form_layout_select' );
		
        add_settings_section( 'section_form_fields_custom', 'Form Fields customization', array( &$this, 'section_form_fields_custom_desc' ), self::$key );
        add_settings_field( 'border_width', 'Border Width', array( &$this, 'field_border_width' ), self::$key, 'section_form_fields_custom' );
        add_settings_field( 'border_color', 'Border Color', array( &$this, 'field_border_color' ), self::$key, 'section_form_fields_custom' );
        add_settings_field( 'border_type', 'Border Type', array( &$this, 'field_border_type' ), self::$key, 'section_form_fields_custom' );
        add_settings_field( 'txt_color', 'Text Color', array( &$this, 'field_txt_color' ), self::$key, 'section_form_fields_custom' );
        add_settings_field( 'bg_color', 'Background Color', array( &$this, 'field_bg_color' ), self::$key, 'section_form_fields_custom' );

        add_settings_section( 'section_submit_custom', 'Submit button customization', array( &$this, 'section_submit_desc' ), self::$key );
        add_settings_field( 'submit_btn_width', 'Submit Button Width', array( &$this, 'field_submit_btn_width' ), self::$key, 'section_submit_custom' );
        add_settings_field( 'submit_txt_color', 'Submit Button Text Color', array( &$this, 'field_submit_txt_color' ), self::$key, 'section_submit_custom' );
        add_settings_field( 'submit_bg_color', 'Submit Button Background Color', array( &$this, 'field_submit_bg_color' ), self::$key, 'section_submit_custom' );
        add_settings_field( 'submit_border_width', 'Submit Button Border Width', array( &$this, 'field_submit_border_width' ), self::$key, 'section_submit_custom' );
        add_settings_field( 'submit_border_color', 'Submit Button Border Color', array( &$this, 'field_submit_border_color' ), self::$key, 'section_submit_custom' );
        add_settings_field( 'submit_border_type', 'Submit Button Border Type', array( &$this, 'field_submit_border_type' ), self::$key, 'section_submit_custom' );

        add_settings_section( 'section_submit_hover_custom', 'Submit button hover state customization', array( &$this, 'section_submit_hover_desc' ), self::$key );
        add_settings_field( 'submit_hover_txt_color', 'Submit Button Hover Text Color', array( &$this, 'field_submit_hover_txt_color' ), self::$key, 'section_submit_hover_custom' );
        add_settings_field( 'submit_hover_bg_color', 'Submit Button Hover Background Color', array( &$this, 'field_submit_hover_bg_color' ), self::$key, 'section_submit_hover_custom' );
        add_settings_field( 'submit_hover_border_width', 'Submit Button Hover Border Width', array( &$this, 'field_submit_hover_border_width' ), self::$key, 'section_submit_hover_custom' );
        add_settings_field( 'submit_hover_border_color', 'Submit Button Hover Border Color', array( &$this, 'field_submit_hover_border_color' ), self::$key, 'section_submit_hover_custom' );
        add_settings_field( 'submit_hover_border_type', 'Submit Button Hover Border Type', array( &$this, 'field_submit_hover_border_type' ), self::$key, 'section_submit_hover_custom' );
    }

	function section_form_layout_select_desc() { }
	
	function field_form_layout_select() { ?>
        <label for="form_layout_select_vertical">Vertical</label>
        <input id="form_layout_select_vertical"
           type="radio"
           name="<?php echo self::$key; ?>[form_layout_select]"
           value="vertical" <?php checked( 'vertical', ( self::$settings['form_layout_select'] ) ); ?>
        />
        <label for="form_layout_select_horizontal">Horizontal</label>
        <input id="form_layout_select_horizontal"
           type="radio"
           name="<?php echo self::$key; ?>[form_layout_select]"
           value="horizontal" <?php checked( 'horizontal', ( self::$settings['form_layout_select'] ) ); ?>
        />
    <?php }
	
    function section_form_fields_custom_desc() {  }

    function field_border_width() { ?>
        <input id="emma_border_width"
           type="text"
           size="2"
           name="<?php echo self::$key; ?>[border_width]"
           value="<?php echo esc_attr( self::$settings['border_width'] ); ?>"
        /> px (enter 0 for no border.)
    <?php }

    function field_border_color() { ?>
        # <input id="emma_border_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[border_color]"
             value="<?php echo esc_attr( self::$settings['border_color'] ); ?>"
        />
    <?php }

    function field_border_type() {
        $border_types = array( 'none', 'dashed', 'dotted', 'double', 'groove', 'inset', 'outset', 'ridge', 'solid' );
        echo '<select id="emma_border_type" name="' . self::$key . '[border_type]">';
        foreach ( $border_types as $border_type ) {
            echo '<option value="' . $border_type . '"';
            if ( self::$settings['border_type'] == $border_type ) { echo "selected"; }
            echo '>'; echo $border_type . '</option>';
        }
        echo '</select>';
    }

    function field_txt_color() { ?>
        # <input id="emma_txt_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[txt_color]"
             value="<?php echo esc_attr( self::$settings['txt_color'] ); ?>"
        />
    <?php }
    function field_bg_color() { ?>
        # <input id="emma_bg_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[bg_color]"
             value="<?php echo esc_attr( self::$settings['bg_color'] ); ?>"
        />
    <?php }

    function section_submit_desc() {  }
	
	function field_submit_btn_width() { ?>
		<input  id="emma_submit_btn_width"
			type="text"
			size="6"
			name="<?php echo self::$key; ?>[submit_btn_width]"
			value="<?php echo esc_attr( self::$settings['submit_btn_width'] ); ?>"
		/> Use '<strong>px</strong>' or '<strong>%</strong>' after your width
	<?php }
	
    function field_submit_txt_color() { ?>
        # <input id="emma_submit_txt_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[submit_txt_color]"
             value="<?php echo esc_attr( self::$settings['submit_txt_color'] ); ?>"
        />
    <?php }

    function field_submit_bg_color() { ?>
        # <input id="emma_submit_bg_color" type="text"
             size="6"
             name="<?php echo self::$key; ?>[submit_bg_color]"
             value="<?php echo esc_attr( self::$settings['submit_bg_color'] ); ?>"
        />
    <?php }

    function field_submit_border_width() { ?>
        <input id="emma_submit_border_width"
           type="text"
           size="2"
           name="<?php echo self::$key; ?>[submit_border_width]"
           value="<?php echo esc_attr( self::$settings['submit_border_width'] ); ?>"
        /> px (enter 0 for no border.)
    <?php }

    function field_submit_border_color() { ?>
        # <input id="emma_submit_border_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[submit_border_color]"
             value="<?php echo esc_attr( self::$settings['submit_border_color'] ); ?>"
        />
    <?php }

    function field_submit_border_type() {
        $border_types = array( 'none', 'dashed', 'dotted', 'double', 'groove', 'inset', 'outset', 'ridge', 'solid' );
        echo '<select name="' . (string)self::$key . '[submit_border_type]">';
        foreach ( $border_types as $border_type ) {
            echo '<option value="' . $border_type . '"';
            if ( self::$settings['submit_border_type'] == $border_type ) { echo "selected"; }
            echo '>';
            echo $border_type . '</option>';
        }
        echo '</select>';
    }

    function section_submit_hover_desc() {  }

    function field_submit_hover_txt_color() { ?>
        # <input id="emma_submit_hover_text"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[submit_hover_txt_color]"
             value="<?php echo esc_attr( self::$settings['submit_hover_txt_color'] ); ?>"
        />
    <?php }

    function field_submit_hover_bg_color() { ?>
        # <input id="emma_submit_hover_bg_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[submit_hover_bg_color]"
             value="<?php echo esc_attr( self::$settings['submit_hover_bg_color'] ); ?>"
        />
    <?php }

    function field_submit_hover_border_width() { ?>
        <input id="emma_submit_hover_border_width"
           type="text"
           size="2"
           name="<?php echo self::$key; ?>[submit_hover_border_width]"
           value="<?php echo esc_attr( self::$settings['submit_hover_border_width'] ); ?>"
        /> px (enter 0 for no border.)
    <?php }

    function field_submit_hover_border_color() { ?>
        # <input id="emma_submit_hover_boder_color"
             type="text"
             size="6"
             name="<?php echo self::$key; ?>[submit_hover_border_color]"
             value="<?php echo esc_attr( self::$settings['submit_hover_border_color'] ); ?>"
        />
    <?php }

    function field_submit_hover_border_type() {
        $border_types = array( 'none', 'dashed', 'dotted', 'double', 'groove', 'inset', 'outset', 'ridge', 'solid' );
        echo '<select name="' . (string)self::$key . '[submit_hover_border_type]">';
        foreach ( $border_types as $border_type ) {
            echo '<option value="' . $border_type . '"';
            if ( self::$settings['submit_hover_border_type'] == $border_type ) { echo "selected"; }
            echo '>';
            echo $border_type . '</option>';
        }
        echo '</select>';
    }

    function sanitize_form_custom_settings( $input ) {

        $valid_input = array();

        // check which button was clicked, submit or reset,
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'])  ? true : false );

        if ( $submit ) {

            // check all hexadecimal values
            // not checking for a true hex value, not capturing '#'
            // border_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['border_color']) ) {
                $valid_input['border_color'] = $input['border_color'];
            } else {
                add_settings_error(
                    'border_color',
                    'emma_error',
                    'The form fields border color is an invalid hexadecimal value',
                    'error'
                );
            }
            // txt_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['txt_color']) ) {
                $valid_input['txt_color'] = $input['txt_color'];
            } else {
                add_settings_error(
                    'txt_color',
                    'emma_error',
                    'The form fields text color is an invalid hexadecimal value',
                    'error'
                );
            }
            // bg_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['bg_color']) ) {
                $valid_input['bg_color'] = $input['bg_color'];
            } else {
                add_settings_error(
                    'bg_color',
                    'emma_error',
                    'The form fields background color is an invalid hexadecimal value',
                    'error'
                );
            }
            // submit_txt_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['submit_txt_color']) ) {
                $valid_input['submit_txt_color'] = $input['submit_txt_color'];
            } else {
                add_settings_error(
                    'submit_txt_color',
                    'emma_error',
                    'The submit button text color is an invalid hexadecimal value',
                    'error'
                );
            }
            // submit_bg_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['submit_bg_color']) ) {
                $valid_input['submit_bg_color'] = $input['submit_bg_color'];
            } else {
                add_settings_error(
                    'submit_bg_color',
                    'emma_error',
                    'The submit button background color is an invalid hexadecimal value',
                    'error'
                );
            }
            // submit_border_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['submit_border_color']) ) {
                $valid_input['submit_border_color'] = $input['submit_border_color'];
            } else {
                add_settings_error(
                    'submit_border_color',
                    'emma_error',
                    'The submit border color is an invalid hexadecimal value',
                    'error'
                );
            }
            // submit_hover_txt_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['submit_hover_txt_color']) ) {
                $valid_input['submit_hover_txt_color'] = $input['submit_hover_txt_color'];
            } else {
                add_settings_error(
                    'submit_hover_txt_color',
                    'emma_error',
                    'The submit hover text color is an invalid hexadecimal value',
                    'error'
                );
            }
            // submit_hover_bg_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['submit_hover_bg_color']) ) {
                $valid_input['submit_hover_bg_color'] = $input['submit_hover_bg_color'];
            } else {
                add_settings_error(
                    'submit_hover_bg_color',
                    'emma_error',
                    'The submit hover background color is an invalid hexadecimal value',
                    'error'
                );
            }
            // submit_hover_border_color
            if ( preg_match('/[a-fA-F0-9]{3,6}/', $input['submit_hover_border_color']) ) {
                $valid_input['submit_hover_border_color'] = $input['submit_hover_border_color'];
            } else {
                add_settings_error(
                    'submit_hover_border_color',
                    'emma_error',
                    'The submit hover border color is an invalid hexadecimal value',
                    'error'
                );
            }
			
			$valid_input['form_layout_select'] = $input['form_layout_select'];
			$valid_input['submit_btn_width'] = $input['submit_btn_width'];
			
            // validate pixel values,
            $valid_input['border_width'] = (is_numeric($input['border_width']) ? $input['border_width'] : $valid_input['border_width']);
            $valid_input['submit_border_width'] = (is_numeric($input['submit_border_width']) ? $input['submit_border_width'] : $valid_input['submit_border_width']);
            $valid_input['submit_hover_border_width'] = (is_numeric($input['submit_hover_border_width']) ? $input['submit_hover_border_width'] : $valid_input['submit_hover_border_width']);

            // validate select elements, border types
            $valid_input['border_type'] = $input['border_type'];
            $valid_input['submit_border_type'] = $input['submit_border_type'];
            $valid_input['submit_hover_border_type'] = $input['submit_hover_border_type'];

        } elseif ( $reset ) {

            // get defaults
            $default_input = $this->get_settings_defaults();
            // assign to valid input
            $valid_input = $default_input;

        }

        return $valid_input;

    }

}
