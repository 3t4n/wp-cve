<?php

class EESW_Widget extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array(
            'classname' => 'EESW_Widget',
            'description' => 'Official Elastic Email Subscribe widget!',
        );
        parent::__construct('EESW_Widget', 'Subscribe Form by Elastic Email', $widget_ops);

    }

    /*  Widget Settings */
    // display widget
    function widget($args, $instance)
    {
        extract($args);       
        echo $before_widget;
            require(dirname(__DIR__) . '/template/widget/t-eesf_widget-body.php');
        echo $after_widget;
    }

    //default settings
    function form($instance)
    {
        wp_enqueue_script('ee-lib-jscolor');
        wp_enqueue_style('eewidget-admin-css');

        $defaults = array(
            'text_header' => 'Subscribe me!',
            'text_description' => 'Subscribe to our mailing list',
            'text_action' => 'Thank you for subscribing to our newsletter!',
            'text_terms' => 'I have read and agree to the terms & conditions',
            'url_terms' => '',
            'text_subscribe' => 'Subscribe!',
            'color_body' => '282842',
            'color_header-txt' => 'fff',
            'color_description-txt' => 'fff',
            'color_input-bg' => 'fff',
            'color_input-txt' => '1a1a1a',
            'color_input-label' => 'fff',
            'color_button-bg' => 'f9c053',
            'color_button-txt' => '32325c',
            'button-position' => 'center',
            'text_align' => 'center',
            'border_radius' => '4px',
            'widget_padding' => '40px 30px',
            'all_listname' => get_option('ee-list-checkbox'),
            'checked_lists_name_and_id' => '',
            'hide_name_checkbox' => '',
            'agree_and_terms_checkbox' => '',
            'activation_template' => '',
            'list_selection_checkbox' => ''
        );

        $checkboxArr = array();
        foreach (get_option('ee-list-checkbox') as $checkbox_default_arr => $checkbox_default_value) {
            $checkboxArr['ee-single-checkbox-'.$checkbox_default_arr] = 'off';
        }

        $defaults = array_merge($defaults, $checkboxArr);

        //widget forms
        $instance = wp_parse_args((array)$instance, $defaults);
        ?>

        <div style="margin-top:10px;margin-bottom:2px"><strong>YOUR LISTS:</strong></div>
        <div class="ee-widget-admin-col-12 ee-list-item-container">
        <?php
            foreach (get_option('ee-list-checkbox') as $checkbox_html_arr => $checkbox_html_value) {
                echo '<div class="ee-widget-admin-single-item">
                        <input
                            type="checkbox"
                            '.checked($instance['ee-single-checkbox-' . $checkbox_html_arr], 'on', false).' 
                            id="'.$this->get_field_id('ee-single-checkbox-' . $checkbox_html_arr).'" 
                            name="'.$this->get_field_name('ee-single-checkbox-' . $checkbox_html_arr).'" 
                        >
                        <label for="'. $this->get_field_id('ee-single-checkbox-' . $checkbox_html_arr) .'" >
                                '.$checkbox_html_value.'
                        </label>
                       </div>';
            }?>

        </div>

        <div class="ee-widget-admin-col-12 ee-widget-admin-single-element ee-widget-admin-checkbox">
            <input class="checkbox"
                   type="checkbox" <?php
                   checked($instance['list_selection_checkbox'], 'on'); ?>
                   id="<?php echo $this->get_field_id('list_selection_checkbox'); ?>"
                   name="<?php echo $this->get_field_name('list_selection_checkbox'); ?>"
            />
            <label for="<?php echo $this->get_field_id('list_selection_checkbox'); ?>">
                List selection by the subscriber
            </label>
        </div>
        
        <div class="ee-widget-admin-col-12 ee-widget-admin-single-header">INPUT:</div>
        <div>
            <div class="ee-widget-admin-col-12 ee-widget-admin-single-element ee-widget-admin-checkbox">
            <input class="checkbox"
                   type="checkbox" <?php
                   checked($instance['hide_name_checkbox'], 'on'); ?>
                   id="<?php echo $this->get_field_id('hide_name_checkbox'); ?>"
                   name="<?php echo $this->get_field_name('hide_name_checkbox'); ?>"
            />
            <label for="<?php echo $this->get_field_id('hide_name_checkbox'); ?>">
            Name input (checked to hide)
            </label>
            
            </div>
        </div>
        
        <div style="margin-top:10px;margin-bottom:2px"><strong>ACTIVATION TEMPLATE:</strong></div>
            <div class="ee-widget-admin-single-element">
                Template name:
                <input placeholder="Default empty"
                       class="widefat"
                       name="<?php echo $this->get_field_name('activation_template'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['activation_template']); ?>"
                />
            </div>
            
        <div>
            <div class="ee-widget-admin-col-12 ee-widget-admin-single-header">WIDGET TEXT:</div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Header:
                <input placeholder="Example: Subscribe me!"
                       class="widefat" name="<?php echo $this->get_field_name('text_header'); ?>"
                       type="text"
                       value="<?php echo $instance['text_header']; ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Button text:
                <input placeholder="Example: Subscribe!"
                       class="widefat" name="<?php echo $this->get_field_name('text_subscribe'); ?>"
                       type="text" value="<?php echo esc_attr($instance['text_subscribe']); ?>"
                />
            </div>

            <div class="ee-widget-admin-single-element">
                Description:
                <input placeholder="Example: Subscribe to our mailing list"
                       class="widefat" name="<?php echo $this->get_field_name('text_description'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['text_description']); ?>"
                />
            </div>

            <div class="ee-widget-admin-single-header">WIDGET STYLES:</div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Body color:
                <input class="widefat  jscolor"
                       name="<?php echo $this->get_field_name('color_body'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_body']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Header text color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_header-txt'); ?>"
                       type="text" value="<?php echo esc_attr($instance['color_header-txt']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Description text color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_description-txt'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_description-txt']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Button color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_button-bg'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_button-bg']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Button text color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_button-txt'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_button-txt']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Input color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_input-bg'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_input-bg']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Input text color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_input-txt'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_input-txt']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Input label color:
                <input class="widefat jscolor"
                       name="<?php echo $this->get_field_name('color_input-label'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['color_input-label']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Button position:
                <select class='widefat'
                        name="<?php echo $this->get_field_name('button-position'); ?>"
                        id="<?php echo $this->get_field_id('button-position'); ?>"
                        type="text">
                    <option value='left' <?php echo ($instance['button-position'] == 'left') ? 'selected' : ''; ?>>Left
                    </option>
                    <option value='center' <?php echo ($instance['button-position'] == 'center') ? 'selected' : ''; ?>>
                        Center
                    </option>
                    <option value='right' <?php echo ($instance['button-position'] == 'right') ? 'selected' : ''; ?>>
                        Right
                    </option>
                </select>
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Text align:
                <select class='widefat'
                        name="<?php echo $this->get_field_name('text_align'); ?>"
                        id="<?php echo $this->get_field_id('text_align'); ?>"
                        type="text">
                    <option value='left' <?php echo ($instance['text_align'] == 'left') ? 'selected' : ''; ?>>Left
                    </option>
                    <option value='center' <?php echo ($instance['text_align'] == 'center') ? 'selected' : ''; ?>>Center
                    </option>
                    <option value='right' <?php echo ($instance['text_align'] == 'right') ? 'selected' : ''; ?>>Right
                    </option>
                </select>
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Border radius:
                <input placeholder="4px"
                       class="widefat"
                       name="<?php echo $this->get_field_name('border_radius'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['border_radius']); ?>"
                />
            </div>

            <div class="ee-widget-admin-col-6 ee-widget-admin-single-element">
                Widget padding:
                <input placeholder="40px 30px"
                       class="widefat"
                       name="<?php echo $this->get_field_name('widget_padding'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['widget_padding']); ?>"
                />
            </div>

            <div class="ee-widget-admin-single-element">
                Information after adding to the list:
                <input placeholder="Thank you for subscribing to our newsletter!"
                       class="widefat"
                       name="<?php echo $this->get_field_name('text_action'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['text_action']); ?>"
                />
            </div>

            <div class="ee-widget-admin-single-header">AGREE AND TERMS:</div>

            <div>
                <div class="ee-widget-admin-col-12 ee-widget-admin-single-element ee-widget-admin-checkbox">
                <input class="checkbox"
                    type="checkbox" <?php
                    checked($instance['agree_and_terms_checkbox'], 'on'); ?>
                    id="<?php echo $this->get_field_id('agree_and_terms_checkbox'); ?>"
                    name="<?php echo $this->get_field_name('agree_and_terms_checkbox'); ?>"
                />
                <label for="<?php echo $this->get_field_id('agree_and_terms_checkbox'); ?>">
                Agree and terms checkbox
                </label>
                
                </div>
            </div>

            <div class="ee-widget-admin-single-element">
                Text:
                <input placeholder="Text"
                       class="widefat"
                       name="<?php echo $this->get_field_name('text_terms'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['text_terms']); ?>"
                />
            </div>

            <div class="ee-widget-admin-single-element">
                Terms url:
                <input placeholder="Terms URL"
                       class="widefat"
                       name="<?php echo $this->get_field_name('url_terms'); ?>"
                       type="text"
                       value="<?php echo esc_attr($instance['url_terms']); ?>"
                />
            </div>
            <p class="ee-widget-admin-single-bottom"></p>
        </div>
        <?php
    }

    //save widget settings
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['text_header'] = strip_tags($new_instance['text_header']);
        $instance['text_description'] = strip_tags($new_instance['text_description']);
        $instance['text_subscribe'] = strip_tags($new_instance['text_subscribe']);
        $instance['color_body'] = strip_tags($new_instance['color_body']);
        $instance['color_header-txt'] = strip_tags($new_instance['color_header-txt']);
        $instance['color_description-txt'] = strip_tags($new_instance['color_description-txt']);
        $instance['color_button-bg'] = strip_tags($new_instance['color_button-bg']);
        $instance['color_button-txt'] = strip_tags($new_instance['color_button-txt']);
        $instance['button-position'] = strip_tags($new_instance['button-position']);
        $instance['color_input-bg'] = strip_tags($new_instance['color_input-bg']);
        $instance['color_input-txt'] = strip_tags($new_instance['color_input-txt']);
        $instance['color_input-label'] = strip_tags($new_instance['color_input-label']);
        $instance['text_align'] = strip_tags($new_instance['text_align']);
        $instance['border_radius'] = strip_tags($new_instance['border_radius']);
        $instance['widget_padding'] = strip_tags($new_instance['widget_padding']);
        $instance['text_action'] = strip_tags($new_instance['text_action']);
        $instance['text_terms'] = strip_tags($new_instance['text_terms']);
        $instance['url_terms'] = strip_tags($new_instance['url_terms']);
        $instance['list_selection_checkbox'] = $new_instance['list_selection_checkbox'];
        $instance['hide_name_checkbox'] = $new_instance['hide_name_checkbox'];
        $instance['agree_and_terms_checkbox'] = $new_instance['agree_and_terms_checkbox'];
        $instance['activation_template'] = $new_instance['activation_template'];
        $chceched_list_arr = array();
        foreach (get_option('ee-list-checkbox') as $arr => $val) {
            $instance['ee-single-checkbox-'.$arr] = $new_instance['ee-single-checkbox-'.$arr];

            if($instance['ee-single-checkbox-' . $arr] === 'on') {
                $chceched_list_arr = $chceched_list_arr + array('ee-single-checkbox-' . $arr => $val);
            }
        }
        $new_instance['checked_lists_name_and_id'] = $chceched_list_arr;
        $instance['checked_lists_name_and_id'] = $new_instance['checked_lists_name_and_id'];

        return $instance;
    }
}