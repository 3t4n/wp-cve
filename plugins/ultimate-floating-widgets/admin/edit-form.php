<?php
/*
    Widget box edit form
*/

class UFW_Admin_Edit_Form{

    public static $opts;

    public static function init( $opts ){

        self::$opts = $opts;

    }

    public static function form( $values ){

        $sections = array(
            'trigger' => array(
                'name' => __( 'Trigger', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-external',
                'callback' => array( __CLASS__, 'trigger' )
            ),
            'close_behavior' => array(
                'name' => __( 'Close', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-exit',
                'callback' => array( __CLASS__, 'close_behavior' )
            ),
            'widget_box' => array(
                'name' => __( 'Widget box', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-feedback',
                'callback' => array( __CLASS__, 'widget_box' )
            ),
            'button' => array(
                'name' => __( 'Button', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-button',
                'callback' => array( __CLASS__, 'button' ),
                'tab_attrs' => 'data-conditioner data-condr-input="[name=ufw_trigger]" data-condr-value="button" data-condr-action="pattern?show:hide" data-condr-events="change"'
            ),
            'location_rules' => array(
                'name' => __( 'Location rules', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-location-alt',
                'callback' => array( __CLASS__, 'location_rules' )
            ),
            'visitor_conditions' => array(
                'name' => __( 'Visitor conditions', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-admin-users',
                'callback' => array( __CLASS__, 'visitor_conditions' ),
                'tab_attrs' => 'class="ufw_pro_link"'
            ),
            'advanced' => array(
                'name' => __( 'Advanced', 'ultimate-floating-widgets' ),
                'icon' => 'dashicons-admin-generic',
                'callback' => array( __CLASS__, 'advanced' )
            )
        );

        echo '<h2>' . esc_html__( 'Settings', 'ultimate-floating-widgets' ) . '</h2>';

        echo '<ul class="ufw_tabs">';
        foreach( $sections as $id => $prop ){
            $attrs = isset( $prop[ 'tab_attrs' ] ) ? ' ' . $prop[ 'tab_attrs' ] : '';
            echo '<li><a href="#' . esc_attr( $id ) . '"' . $attrs . '><span class="dashicons ' . esc_attr( $prop[ 'icon' ] ) . '"></span>' . esc_html( $prop[ 'name' ] ) . '</a></li>';
        }
        echo '</ul>';

        echo '<div class="ufw_sec_wrap">';
        foreach( $sections as $id => $prop ){
            echo '<section class="ufw_section" data-tab="' . esc_attr( $id ) . '">';
            if( array_key_exists( 'callback', $prop ) && is_callable( $prop[ 'callback' ] ) ){
                call_user_func( $prop[ 'callback' ], $values );
            }
            echo '</section>';
        }
        echo '</div>';

        echo '<div class="ufw_anim_prev"><h4>' . esc_html__( 'Animation preview', 'ultimate-floating-widgets' ) . '</h4><div class="ufw_anim_obj"></div></div>';

    }

    public static function main_settings( $values ){

        $opts = self::$opts;

        $main_table = $opts->table(array(
            
            array( 'Name of the widget box', $opts->field( 'text', array(
                'name' => 'ufw_name',
                'value' => $values['name'],
                'placeholder' => 'Enter a name for the widget box',
                'required' => 'required',
                'custom' => 'pattern="[a-zA-z0-9 \-]+" title="Only alphabets, numbers and hyphens are allowed."',
                'helper' => 'A name to identify the widget inside WordPress admin page.'
            ))),
            
            array( 'Status', $opts->field( 'select', array(
                'name' => 'ufw_status',
                'list' => array(
                    'enabled' => 'Enabled',
                    'disabled' => 'Disabled'
                ),
                'value' => $values['status']
            ))),
            
            array( 'Initial state on desktop', $opts->field( 'select', array(
                'name' => 'ufw_init_state',
                'list' => array(
                    'opened' => 'Opened',
                    'closed' => 'Closed'
                ),
                'value' => $values['init_state']
            ))),

            array( 'Initial state on mobile', $opts->field( 'select', array(
                'name' => 'ufw_init_state_m',
                'list' => array(
                    'opened' => 'Opened',
                    'closed' => 'Closed'
                ),
                'value' => $values['init_state_m']
            ))),
            
            array( 'Type of widget box', $opts->field( 'image_select', array(
                'name' => 'ufw_type',
                'list' => array(
                    'popup' => array( 'Popup bubble', 'type-popup.svg', '100px' ),
                    'flyout' => array( 'Flyout', 'type-flyout.svg', '100px' ),
                ),
                'value' => $values['type']
            ))),
            
            array( 'Position of widget box', $opts->field( 'select', array(
                'name' => 'ufw_pp_position',
                'list' => array(
                    'br' => 'Bottom right',
                    'bl' => 'Bottom left',
                    'tr' => 'Top right',
                    'tl' => 'Top left'
                ),
                'value' => $values['pp_position']
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="popup" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( 'Position of widget box', $opts->field( 'select', array(
                'name' => 'ufw_fo_position',
                'list' => array(
                    'left' => 'Left side of the page',
                    'right' => 'Right side of the page'
                ),
                'value' => $values['fo_position']
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="flyout" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
        ));
        
        $opts->section(array(
            'content' => $main_table
        ));

    }

    public static function trigger( $values ){

        $opts = self::$opts;

        $tr_settings = $opts->table(array(

            array( 'Trigger for widget box', $opts->field( 'image_select', array(
                'name' => 'ufw_trigger',
                'list' => array(
                    'button' => array( 'From button', 'trigger-button.svg', '80px' ),
                    'auto' => array( 'Automatic - On page scroll', 'trigger-scroll.svg', '80px' ),
                    'button_auto' => array( 'Both button and Automatic', 'trigger-button-scroll.svg', '80px' ),
                ),
                'value' => $values['trigger'],
                'helper' => 'The method on how the widget box can be opened by the user.'
            ))),
            
            array( 'Open widget box when page is scrolled', $opts->field( 'text', array(
                'name' => 'ufw_auto_trigger',
                'value' => $values['auto_trigger'],
                'class' => 'small-box',
                'unit' => '%',
                'tooltip' => 'Enter percentage to open/close widget box when page is scrolled. Example: 60%. Widget box will open automatically at 60% scroll.'
            )), 'data-conditioner data-condr-input="[name=ufw_trigger]" data-condr-value="auto" data-condr-action="pattern?show:hide" data-condr-events="change"'),
            
            array( 'Flyout button position', $opts->field( 'select', array(
                'name' => 'ufw_fo_btn_position',
                'list' => array(
                    'br' => 'Bottom right',
                    'bl' => 'Bottom left',
                    'tr' => 'Top right',
                    'tl' => 'Top left',
                ),
                'value' => $values['fo_btn_position']
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="flyout" data-condr-action="simple?show:hide" data-condr-events="change"'),

            array( '<h3>Animation</h3>', '<hr/>' ),

            array( 'Open animation', $opts->field( 'select', array(
                'name' => 'ufw_pp_anim_open',
                'list' => array(
                    'none' => 'None',
                    'bounceIn' => 'bounceIn',
                    'bounceInDown' => 'bounceInDown',
                    'bounceInLeft' => 'bounceInLeft',
                    'bounceInRight' => 'bounceInRight',
                    'bounceInUp' => 'bounceInUp',
                    'fadeIn' => 'fadeIn',
                    'fadeInDown' => 'fadeInDown',
                    'fadeInDownBig' => 'fadeInDownBig',
                    'fadeInLeft' => 'fadeInLeft',
                    'fadeInLeftBig' => 'fadeInLeftBig',
                    'fadeInRight' => 'fadeInRight',
                    'fadeInRightBig' => 'fadeInRightBig',
                    'fadeInUp' => 'fadeInUp',
                    'fadeInUpBig' => 'fadeInUpBig',
                    'rotateIn' => 'rotateIn',
                    'rotateInDownLeft' => 'rotateInDownLeft',
                    'rotateInDownRight' => 'rotateInDownRight',
                    'rotateInUpLeft' => 'rotateInUpLeft',
                    'rotateInUpRight' => 'rotateInUpRight',
                    'slideInUp' => 'slideInUp',
                    'slideInDown' => 'slideInDown',
                    'slideInLeft' => 'slideInLeft',
                    'slideInRight' => 'slideInRight',
                    'zoomIn' => 'zoomIn',
                    'zoomInDown' => 'zoomInDown',
                    'zoomInLeft' => 'zoomInLeft',
                    'zoomInRight' => 'zoomInRight',
                    'zoomInUp' => 'zoomInUp',
                ),
                'value' => $values['pp_anim_open'],
                'class' => 'ufw_do_anim_prev',
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="popup" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( 'Close animation', $opts->field( 'select', array(
                'name' => 'ufw_pp_anim_close',
                'list' => array(
                    'none' => 'None',
                    'bounceOut' => 'bounceOut',
                    'bounceOutDown' => 'bounceOutDown',
                    'bounceOutLeft' => 'bounceOutLeft',
                    'bounceOutRight' => 'bounceOutRight',
                    'bounceOutUp' => 'bounceOutUp',
                    'fadeOut' => 'fadeOut',
                    'fadeOutDown' => 'fadeOutDown',
                    'fadeOutDownBig' => 'fadeOutDownBig',
                    'fadeOutLeft' => 'fadeOutLeft',
                    'fadeOutLeftBig' => 'fadeOutLeftBig',
                    'fadeOutRight' => 'fadeOutRight',
                    'fadeOutRightBig' => 'fadeOutRightBig',
                    'fadeOutUp' => 'fadeOutUp',
                    'fadeOutUpBig' => 'fadeOutUpBig',
                    'rotateOut' => 'rotateOut',
                    'rotateOutDownLeft' => 'rotateOutDownLeft',
                    'rotateOutDownRight' => 'rotateOutDownRight',
                    'rotateOutUpLeft' => 'rotateOutUpLeft',
                    'rotateOutUpRight' => 'rotateOutUpRight',
                    'slideOutUp' => 'slideOutUp',
                    'slideOutDown' => 'slideOutDown',
                    'slideOutLeft' => 'slideOutLeft',
                    'slideOutRight' => 'slideOutRight',
                    'zoomOut' => 'zoomOut',
                    'zoomOutDown' => 'zoomOutDown',
                    'zoomOutLeft' => 'zoomOutLeft',
                    'zoomOutRight' => 'zoomOutRight',
                    'zoomOutUp' => 'zoomOutUp',
                ),
                'value' => $values['pp_anim_close'],
                'class' => 'ufw_do_anim_prev',
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="popup" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( 'Open animation', $opts->field( 'select', array(
                'name' => 'ufw_fo_anim_open',
                'list' => array(
                    'none' => 'None',
                    'fadeIn' => 'fadeIn',
                    'fadeInDown' => 'fadeInDown',
                    'fadeInDownBig' => 'fadeInDownBig',
                    'fadeInLeft' => 'fadeInLeft',
                    'fadeInLeftBig' => 'fadeInLeftBig',
                    'fadeInRight' => 'fadeInRight',
                    'fadeInRightBig' => 'fadeInRightBig',
                    'fadeInUp' => 'fadeInUp',
                    'fadeInUpBig' => 'fadeInUpBig',
                    'slideInUp' => 'slideInUp',
                    'slideInDown' => 'slideInDown',
                    'slideInLeft' => 'slideInLeft',
                    'slideInRight' => 'slideInRight',
                ),
                'value' => $values['fo_anim_open'],
                'class' => 'ufw_do_anim_prev',
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="flyout" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( 'Close animation', $opts->field( 'select', array(
                'name' => 'ufw_fo_anim_close',
                'list' => array(
                    'none' => 'None',
                    'fadeOut' => 'fadeOut',
                    'fadeOutDown' => 'fadeOutDown',
                    'fadeOutDownBig' => 'fadeOutDownBig',
                    'fadeOutLeft' => 'fadeOutLeft',
                    'fadeOutLeftBig' => 'fadeOutLeftBig',
                    'fadeOutRight' => 'fadeOutRight',
                    'fadeOutRightBig' => 'fadeOutRightBig',
                    'fadeOutUp' => 'fadeOutUp',
                    'fadeOutUpBig' => 'fadeOutUpBig',
                    'slideOutUp' => 'slideOutUp',
                    'slideOutDown' => 'slideOutDown',
                    'slideOutLeft' => 'slideOutLeft',
                    'slideOutRight' => 'slideOutRight',
                ),
                'value' => $values['fo_anim_close'],
                'class' => 'ufw_do_anim_prev',
            )), 'data-conditioner data-condr-input="[name=ufw_type]" data-condr-value="flyout" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( 'Animation duration', $opts->field( 'text', array(
                'name' => 'ufw_anim_duration',
                'value' => $values['anim_duration'],
                'class' => 'small-box',
                'tooltip' => 'Duration of the animation. Example: 1 seconds',
                'unit' => 'seconds'
            ))),
            
            array( '<h3>Save State <code>New</code></h3>', '<hr/>' ),

            array( 'Save state when widget box is opened or closed', $opts->field( 'select', array(
                'name' => 'ufw_save_state',
                'list' => array(
                    'no' => 'No',
                    'yes' => 'Yes',
                ),
                'value' => $values['save_state']
            ))),

            array( 'Save state for duration', $opts->field( 'text', array(
                'name' => 'ufw_save_state_duration',
                'value' => $values['save_state_duration'],
                'class' => 'small-box',
                'type' => 'number',
                'unit' => 'days',
                'tooltip' => 'Number of days the widget box should be kept open or closed.',
                'helper' => 'Enter 0 to save state only for current browser session.'
            )), 'data-conditioner data-condr-input="[name=ufw_save_state]" data-condr-value="yes" data-condr-action="simple?show:hide" data-condr-events="change"'),

        ));
        
        echo $opts->section(array(
            'content' => $tr_settings,
            'mini' => true
        ));

    }

    public static function close_behavior( $values ){

        $opts = self::$opts;

        $cb_settings = $opts->table(array(
            
            array( '<h3 style="margin-top:0">Close widget box</h3>', '<hr/>' ),

            array( 'When page is scrolled', $opts->field( 'text', array(
                'name' => 'ufw_auto_close',
                'value' => $values['auto_close'],
                'class' => 'small-box',
                'type' => 'number',
                'unit' => '%',
                'tooltip' => 'Enter percentage to close widget box when page is scrolled. Example: 90%. Widget box will close automatically at 90% scroll.',
                'helper' => 'Leave empty to close widget only on button click'
            ))),

            array( 'After specific time', $opts->field( 'text', array(
                'name' => 'ufw_auto_close_time',
                'value' => $values['auto_close_time'],
                'class' => 'small-box',
                'type' => 'number',
                'unit' => 'secs',
                'tooltip' => 'Enter time in seconds after which the widget box should close automatically. Example: 10 secs. Widget box will close automatically after 10 secs if not active.',
                'helper' => 'Leave empty to keep it open'
            ))),

            array( self::pro_link( 'close-outside', 'When clicked outside' ), $opts->field( 'select', array(
                'list' => array(
                    'no' => 'No',
                    'yes' => 'Yes',
                ),
                'value' => 'no',
                'class' => 'ufw_pro_feature',
                'helper' => 'With this feature users can close the widget box when clicked outside i.e on the page<br/>Note: This feature is available as part of the <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=close-outside&utm_campaign=ufw-pro#pro">PRO version</a>.',
                'tooltip' => 'With this feature users can close the widget box when clicked outside i.e on the page'
            ))),

            array( '<h3>Close button</h3>', '<hr/>' ),

            array( 'Dedicated close button on the widget box', $opts->field( 'select', array(
                'name' => 'ufw_wb_close_btn',
                'list' => array(
                    'no' => 'No',
                    'yes' => 'Yes',
                ),
                'value' => $values['wb_close_btn']
            ))),

            array( 'Dedicated close button icon', $opts->field( 'text', array(
                'name' => 'ufw_wb_close_icon',
                'value' => $values['wb_close_icon'],
                'helper' => '<strong>For icon font:</strong> Open <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">font awesome 5 page</a>, search for an icon and paste the icon code. Example: <code>fab fa-whatsapp</code>'
            )), 'data-conditioner data-condr-input="[name=ufw_wb_close_btn]" data-condr-value="yes" data-condr-action="simple?show:hide" data-condr-events="change"'),

        ));
        
        $opts->section(array(
            'content' => $cb_settings,
            'mini' => true
        ));

    }

    public static function widget_box( $values ){

        $opts = self::$opts;

        $wb_settings = $opts->table(array(
            
            array( 'Title of the widget box', $opts->field( 'text', array(
                'name' => 'ufw_title',
                'value' => $values['title'],
                'placeholder' => 'Title of the widget box',
                'helper' => 'Title of the widget box which will be shown to the user. Leave empty for not title.'
            ))),

            array( self::pro_link( 'multiple-columns', 'Number of columns' ), $opts->field( 'select', array(
                'list' => array(
                    '1' => '1',
                    '2:disabled' => '2',
                    '3:disabled' => '3',
                    '4:disabled' => '4',
                ),
                'class' => 'ufw_pro_feature',
                'value' => '1',
                'helper' => 'With this feature widgets can be added to multiple columns inside one widget box<br/>Note: This feature is available as part of the <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=multiple-columns&utm_campaign=ufw-pro#pro">PRO version</a>.',
                'tooltip' => 'With this feature widgets can be added to multiple columns inside one widget box'
            ))),

            array( 'Width', $opts->field( 'text', array(
                'name' => 'ufw_wb_width',
                'value' => $values['wb_width'],
                'class' => 'small-box',
                'tooltip' => 'Width of the widget box. Example: 250px (or) 20%'
            ))),
            
            array( 'Height', $opts->field( 'text', array(
                'name' => 'ufw_wb_height',
                'value' => $values['wb_height'],
                'class' => 'small-box',
                'tooltip' => 'Height of the widget box. Example: 400px. % is not supported.'
            ))),
            
            array( '<h3>Styling</h3>', '<hr/>' ),

            array( 'Background color', $opts->field( 'text', array(
                'name' => 'ufw_wb_bg_color',
                'value' => $values['wb_bg_color'],
                'class' => 'color_picker'
            ))),
            
            array( 'Text color', $opts->field( 'text', array(
                'name' => 'ufw_wb_text_color',
                'value' => $values['wb_text_color'],
                'class' => 'color_picker',
                'helper' => 'Set empty value to use default color as per theme.'
            ))),

            array( 'Border size', $opts->field( 'range', array(
                'name' => 'ufw_wb_bdr_size',
                'value' => $values['wb_bdr_size'],
                'min' => '0',
                'max' => '10',
                'step' => '1',
                'unit' => 'px'
            ))),
            
            array( 'Border color', $opts->field( 'text', array(
                'name' => 'ufw_wb_bdr_color',
                'value' => $values['wb_bdr_color'],
                'class' => 'color_picker'
            ))),
            
            array( 'Box roundness', $opts->field( 'range', array(
                'name' => 'ufw_wb_bdr_radius',
                'value' => $values['wb_bdr_radius'],
                'min' => '0',
                'max' => '64',
                'step' => '1',
                'unit' => 'px'
            ))),
            
        ));
        
        $opts->section(array(
            'content' => $wb_settings,
            'mini' => true
        ));

    }

    public static function button( $values ){

        $opts = self::$opts;

        $btn_settings = $opts->table(array(
            
            array( 'Type', $opts->field( 'image_select', array(
                'name' => 'ufw_btn_type',
                'list' => array(
                    'icon' => array( 'Only icon', 'button-size.svg', '32px' ),
                    'text' => array( 'Only text', 'button-text.svg', '70px' ),
                    'icon_text' => array( 'Both icon and text', 'button-icon-text.svg', '70px' )
                ),
                'value' => $values['btn_type']
            ))),
            
            array( 'Size', $opts->field( 'image_select', array(
                'name' => 'ufw_btn_size',
                'list' => array(
                    '32' => array( 'Quite small (32px)', 'button-size.svg', '32px' ),
                    '40' => array( 'Small (40px)', 'button-size.svg', '40px' ) ,
                    '48' => array( 'Standard (48px)', 'button-size.svg', '48px' ),
                    '56' => array( 'Big (56px)', 'button-size.svg', '56px' ),
                    '64' => array( 'Quite big (64px)', 'button-size.svg', '64px' )
                ),
                'value' => $values['btn_size'],
            ))),
            
            array( '<h3>Open button</h3>', '<hr/>' ),

            array( 'Open icon', $opts->field( 'text', array(
                'name' => 'ufw_btn_icon',
                'value' => $values['btn_icon'],
                'helper' => '<strong>For icon font:</strong> Open <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">font awesome 5 page</a>, search for an icon and paste the icon code. Example: <code>fab fa-whatsapp</code><br/>
                <strong>For custom image:</strong> Enter the URL of the icon. Example: <code>https://mywebsite.com/image/icon.png</code>'
            )), 'data-conditioner data-condr-input="[name=ufw_btn_type]" data-condr-value="icon" data-condr-action="pattern?show:hide" data-condr-events="change"'),
            
            array( 'Open text', $opts->field( 'text', array(
                'name' => 'ufw_btn_text',
                'value' => $values['btn_text'],
                'tooltip' => 'Text to show inside the button. Supports HTML and Shortcodes.'
            )), 'data-conditioner data-condr-input="[name=ufw_btn_type]" data-condr-value="text" data-condr-action="pattern?show:hide" data-condr-events="change"'),
            
            array( '<h3>Close button</h3>', '<hr/>' ),

            array( 'Close icon', $opts->field( 'text', array(
                'name' => 'ufw_btn_close_icon',
                'value' => $values['btn_close_icon'],
                'helper' => '<strong>For icon font:</strong> Open <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">font awesome 5 page</a>, search for an icon and paste the icon code. Example: <code>fab fa-whatsapp</code>'
            )), 'data-conditioner data-condr-input="[name=ufw_btn_type]" data-condr-value="icon" data-condr-action="pattern?show:hide" data-condr-events="change"'),
            
            array( 'Close text', $opts->field( 'text', array(
                'name' => 'ufw_btn_close_text',
                'value' => $values['btn_close_text'],
                'tooltip' => 'Text to show inside the button. Supports HTML and Shortcodes.'
            )), 'data-conditioner data-condr-input="[name=ufw_btn_type]" data-condr-value="text" data-condr-action="pattern?show:hide" data-condr-events="change"'),

            array( '<h3>Styling</h3>', '<hr/>' ),

            array( 'Background color', $opts->field( 'text', array(
                'name' => 'ufw_btn_bg_color',
                'value' => $values['btn_bg_color'],
                'class' => 'color_picker cp_gradient',
                'placeholder' => '#00ccff or linear-gradient(to right, #6bf3ff, #b5fa8a)',
                'helper' => 'Set value to "transparent" for a button without any background color.<br/>Supports gradients. You can use a <a href="https://cssgradient.io/" target="_blank">gradient generator tool</a> and copy the gradient in the format <code>linear-gradient(to right, #6bf3ff, #b5fa8a)</code>'
            ))),
            
            array( 'Text color', $opts->field( 'text', array(
                'name' => 'ufw_btn_text_color',
                'value' => $values['btn_text_color'],
                'class' => 'color_picker'
            ))),

            array( 'Border size', $opts->field( 'range', array(
                'name' => 'ufw_btn_bdr_size',
                'value' => $values['btn_bdr_size'],
                'min' => '0',
                'max' => '10',
                'step' => '1',
                'unit' => 'px'
            ))),
            
            array( 'Border color', $opts->field( 'text', array(
                'name' => 'ufw_btn_bdr_color',
                'value' => $values['btn_bdr_color'],
                'class' => 'color_picker'
            ))),
            
            array( 'Roundness', $opts->field( 'range', array(
                'name' => 'ufw_btn_radius',
                'value' => $values['btn_radius'],
                'min' => '0',
                'max' => '64',
                'step' => '1',
                'unit' => 'px'
            ))),
            
            array( '<h3>Display condition</h3>', '<hr/>' ),

            array( 'Reveal button when page is scrolled', $opts->field( 'text', array(
                'name' => 'ufw_btn_reveal',
                'value' => $values['btn_reveal'],
                'class' => 'small-box',
                'type' => 'number',
                'unit' => 'px',
                'tooltip' => 'Enter pixels to be scrolled in the page to reveal the button. Leave empty to show always. Example: 200px'
            ))),

            array( '<h3>Animation</h3>', '<hr/>' ),

            array( self::pro_link( 'on-show-anim', 'On show animation' ), $opts->field( 'select', array(
                'list' => array(
                    'none' => 'None',
                    'bounce' => 'Bounce',
                    'flash' => 'Flash',
                    'pulse' => 'Pulse',
                    'rubberBand' =>'Rubber band',
                    'shakeX' => 'Shake horizontal',
                    'shakeY' => 'Shake vertical',
                    'headShake' => 'Head shake',
                    'swing' => 'Swing',
                    'tada' => 'Tada',
                    'wobble' => 'Wobble',
                    'jello' => 'Jello',
                    'heartBeat' => 'Heart beat',
                    'bounceIn' => 'bounceIn',
                    'bounceInDown' => 'bounceInDown',
                    'bounceInLeft' => 'bounceInLeft',
                    'bounceInRight' => 'bounceInRight',
                    'bounceInUp' => 'bounceInUp',
                    'fadeIn' => 'fadeIn',
                    'fadeInDown' => 'fadeInDown',
                    'fadeInDownBig' => 'fadeInDownBig',
                    'fadeInLeft' => 'fadeInLeft',
                    'fadeInLeftBig' => 'fadeInLeftBig',
                    'fadeInRight' => 'fadeInRight',
                    'fadeInRightBig' => 'fadeInRightBig',
                    'fadeInUp' => 'fadeInUp',
                    'fadeInUpBig' => 'fadeInUpBig',
                    'rotateIn' => 'rotateIn',
                    'rotateInDownLeft' => 'rotateInDownLeft',
                    'rotateInDownRight' => 'rotateInDownRight',
                    'rotateInUpLeft' => 'rotateInUpLeft',
                    'rotateInUpRight' => 'rotateInUpRight',
                    'slideInUp' => 'slideInUp',
                    'slideInDown' => 'slideInDown',
                    'slideInLeft' => 'slideInLeft',
                    'slideInRight' => 'slideInRight',
                    'zoomIn' => 'zoomIn',
                    'zoomInDown' => 'zoomInDown',
                    'zoomInLeft' => 'zoomInLeft',
                    'zoomInRight' => 'zoomInRight',
                    'zoomInUp' => 'zoomInUp',
                ),
                'class' => 'ufw_do_anim_prev ufw_pro_feature',
                'helper' => 'This feature adds an animation to the button when it is loaded on the page to grab user attention.<br/>Note: This feature is available as a part of the <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=on-show-anim&utm_campaign=ufw-pro#pro">PRO version</a>',
                'tooltip' => 'This feature adds an animation to the button when it is loaded on the page to grab user attention.'
            ))),
            
            array( self::pro_link( 'idle-anim', 'Idle animation' ), $opts->field( 'select', array(
                'list' => array(
                    'none' => 'None',
                    'bounce' => 'Bounce',
                    'flash' => 'Flash',
                    'pulse' => 'Pulse',
                    'rubberBand' =>'Rubber band',
                    'shakeX' => 'Shake horizontal',
                    'shakeY' => 'Shake vertical',
                    'headShake' => 'Head shake',
                    'swing' => 'Swing',
                    'tada' => 'Tada',
                    'wobble' => 'Wobble',
                    'jello' => 'Jello',
                    'heartBeat' => 'Heart beat',
                ),
                'class' => 'ufw_do_anim_prev ufw_pro_feature',
                'helper' => 'With this feature, the button will constantly animate when it is idle on the page.<br/>Note: This feature is available as a part of the <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=idle-anim&utm_campaign=ufw-pro#pro">PRO version</a>',
                'tooltip' => 'With this feature, the button will constantly animate when it is idle on the page.'
            ))),

        ));
        
        $opts->section(array(
            'content' => $btn_settings,
            'mini' => true
        ));

    }

    public static function location_rules( $values ){

        echo '<p class="ufw_description">' . esc_html__( 'With below location rules settings you can select the pages where both the button and widget box must be displayed.', 'ultimate-floating-widgets' ) . '</p>';

        echo '<h4>' . esc_html__( 'Configuration level', 'ultimate-floating-widgets' ) . '</h4>';
        echo self::$opts->field( 'select', array(
            'name' => 'ufw_loc_rules_config',
            'value' => $values[ 'loc_rules_config' ],
            'list' => array(
                'basic' => __( 'Basic', 'ultimate-floating-widgets' ),
                'advanced' => __( 'Advanced (Pro)', 'ultimate-floating-widgets' )
            )
        ));

        echo '<div data-conditioner data-condr-input="[name=ufw_loc_rules_config]" data-condr-value="basic" data-condr-action="simple?show:hide" data-condr-events="change">';
        echo '<h4>' . esc_html__( 'Basic', 'ultimate-floating-widgets' ) . '</h4>';
        echo '<p class="ufw_description">' . esc_html__( 'The widget boxes are by default shown in all the pages. Select below options on where they have to be hidden.', 'ultimate-floating-widgets' ) . '</p>';
        echo self::$opts->field( 'checkboxes', array(
            'name' => 'ufw_loc_rules_basic[]',
            'value' => $values[ 'loc_rules_basic' ],
            'list' => array(
                'hide_all' => __( 'Hide in all pages', 'ultimate-floating-widgets' ),
                'hide_home' => __( 'Hide in home page', 'ultimate-floating-widgets' ),
                'hide_posts' => __( 'Hide in posts', 'ultimate-floating-widgets' ),
                'hide_pages' => __( 'Hide in pages', 'ultimate-floating-widgets' ),
                'hide_front_page' => __( 'Hide in front page', 'ultimate-floating-widgets' ),
            )
        ));
        echo '</div>';

        echo '<div data-conditioner data-condr-input="[name=ufw_loc_rules_config]" data-condr-value="advanced" data-condr-action="simple?show:hide" data-condr-events="change">';
        echo '<h4><a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=location-rules&utm_campaign=ufw-pro#pro" class="ufw_pro_link" target="_blank">' . __( 'Advanced', 'ultimate-floating-widgets' ) . '</a></h4>';
        echo '<p class="ufw_pro_desc">This is an advanced feature which is available as a part of the <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=location-rules&utm_campaign=ufw-pro#pro" target="_blank">PRO version</a>. <br/>With this feature you can create complex rules like in the screenshot below to insert the widget box in a specific granular way. Visit the homepage for more details.</p>';
        echo '<a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=location-rules&utm_campaign=ufw-pro#pro" target="_blank" class="ufw_pro_img_preview"><img src="' . UFW_ADMIN_URL . 'images/misc/location-rules.png" /></a>';
        echo '</div>';

        echo '<br/><hr />';

        echo '<h4>' . __( 'Show this on', 'ultimate-floating-widgets' ) . '</h4>';
        echo self::$opts->field( 'radio', array(
            'name' => 'ufw_loc_rules[devices]',
            'value' => $values[ 'loc_rules' ][ 'devices' ],
            'list' => array(
                'all' => __( 'On both desktop and mobile devices', 'ultimate-floating-widgets' ),
                'mobile_only' => __( 'On mobile devices alone', 'ultimate-floating-widgets' ),
                'desktop_only' => __( 'On desktops alone', 'ultimate-floating-widgets' )
            )
        ));

    }

    public static function visitor_conditions( $values ){
        echo '<p class="ufw_pro_desc">This is an advanced feature which is available as a part of the <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=visitor-conditions&utm_campaign=ufw-pro#pro" target="_blank">PRO version</a>. <br/>With this feature you can create complex rules like in the screenshot below to target visitors of specific criteria and show widget boxes only to them. Visit the homepage for more details.</p>';
        echo '<a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=visitor-conditions&utm_campaign=ufw-pro#pro" class="ufw_pro_img_preview" style="width: 100%" target="_blank"><img src="' . UFW_ADMIN_URL . 'images/misc/visitor-conditions.png" /></a>';
    }

    public static function advanced( $values ){

        $opts = self::$opts;
        $sidebar_tmpl = UFW_Helpers::sidebar_template();

        $misc_settings = $opts->table(array(
            
            array( 'Additional custom CSS', $opts->field( 'textarea', array(
                'name' => 'ufw_additional_css',
                'value' => $values['additional_css'],
                'placeholder' => '',
                'rows' => '6',
                'cols' => '50'
            ))),
            
            array( 'Tags before widget', $opts->field( 'text', array(
                'name' => 'ufw_before_widget',
                'value' => $values['before_widget'],
                'placeholder' => '',
                'helper' => sprintf( __( 'Current theme value <code>%s</code>. Leave default to use theme value.', 'ultimate-floating-widgets' ), esc_html( $sidebar_tmpl[ 'before_widget' ] ) )
            ))),
            
            array( 'Tags after widget', $opts->field( 'text', array(
                'name' => 'ufw_after_widget',
                'value' => $values['after_widget'],
                'placeholder' => '',
                'helper' => sprintf( __( 'Current theme value <code>%s</code>. Leave default to use theme value.', 'ultimate-floating-widgets' ), esc_html( $sidebar_tmpl[ 'after_widget' ] ) )
            ))),
            
            array( 'Tags before title', $opts->field( 'text', array(
                'name' => 'ufw_before_title',
                'value' => $values['before_title'],
                'placeholder' => '',
                'helper' => sprintf( __( 'Current theme value <code>%s</code>. Leave default to use theme value.', 'ultimate-floating-widgets' ), esc_html( $sidebar_tmpl[ 'before_title' ] ) )
            ))),
            
            array( 'Tags after title', $opts->field( 'text', array(
                'name' => 'ufw_after_title',
                'value' => $values['after_title'],
                'placeholder' => '',
                'helper' => sprintf( __( 'Current theme value <code>%s</code>. Leave default to take theme value.', 'ultimate-floating-widgets' ), esc_html( $sidebar_tmpl[ 'after_title' ] ) )
            ))),
            
        ));
        
        $opts->section(array(
            'content' => $misc_settings,
            'mini' => true
        ));

    }

    public static function pro_link( $id, $text ){

        return '<a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=admin&utm_medium=' . esc_attr( $id ) . '&utm_campaign=ufw-pro#pro" class="ufw_pro_link" target="_blank">' . esc_html( $text ) . '</a>';

    }

}

?>