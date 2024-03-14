<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 12/06/2015
 * Time: 15:59
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {
    /**
     * Class My_Plugin_Input_Forms
     * A static class to manage the basic html building of input forms, settings and sections
     */
    class My_Plugin_Input_Forms
    {
        /**
         *
         * Builds the section header, for each section
         * @param My_Plugin_Section_Node $section_node
         * @return string
         * @internal param $args
         */
        public static function build_section(My_Plugin_Section_Node $section_node)
        {
            $html_string = "";
            // echo section intro text here
            if (isset($section_node->description)) {
                $html_string .= "<p class='my_plugin_section_description'>" . $section_node->description . '</p>';
            }
            //Add a horizontal rule under the header / description
            $html_string .= '<hr>';

            return $html_string;
        }

        /**
         * Builds each input form for the settings nodes (fields)
         * @param $option_settings_db_name
         * @param My_Plugin_Settings_Node $settings_node
         * @return string
         * @internal param $value
         * @internal param $key
         * @internal param $args
         */
        public static function build_input_form($option_settings_db_name,
                                                My_Plugin_Settings_Node $settings_node)
        {
            //need to prepend the name with $this->get_option_settings_db_name()[field_id]

            //Get Default value if actual value is not set (null)
            $settings_value = (!isset($settings_node->value)) ? $settings_node->default_value : $settings_node->value;

            //Create the html input form string
            $build_function = 'build_input_' . $settings_node->input_type;
            $html_input_form = self::$build_function($option_settings_db_name, $settings_value, $settings_node);

            // Return the html input form here
            return $html_input_form;
        }

        private static function build_input_text($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            return self::build_input_text_field($option_settings_db_name, $settings_value, $settings_node, 'text');
        }

        private static function build_input_number($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            return self::build_input_text_field($option_settings_db_name, $settings_value, $settings_node, 'number');
        }

        private static function build_input_url($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            return self::build_input_text_field($option_settings_db_name, $settings_value, $settings_node, 'url');
        }

        private static function build_input_email($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            return self::build_input_text_field($option_settings_db_name, $settings_value, $settings_node, 'email');
        }

        private static function build_input_text_field($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node, $input_type)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            return "<div class='my_plugin_input'>
                    <input id='{$id}'
                        name='{$option_settings_db_name}[{$id}]'
                        size='40'
                        type='{$input_type}'
                        step='1'
                        class='{$class}  my_plugin_input-{$input_type}'
                        value='{$settings_value}' />
                    <label for='{$id}' class='my_plugin_label' id='{$id}_label'>{$description}</label>
                </div>";
        }

        private static function build_input_multiline_text($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            return "<div class='my_plugin_input'>
                    <textarea 
                        id='{$id}'
                        name='{$option_settings_db_name}[{$id}]'
                        class='{$class}  my_plugin_input-multiline_text'
                        rows='5'
                        cols='50'
                        >{$settings_value}</textarea>
                    <label class='my_plugin_label' id='{$id}_label'>{$description}</label>
                </div>";
        }

        private static function build_input_radio($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            $html_string = '';
            foreach ($select_options as $description_key => $select_value) {
                $checked = ($settings_value == $select_value) ? 'checked' : '';
                $description_key = (is_string($description_key)) ? $description_key : $select_value;
                $html_string .= "<div class='my_plugin_input'>
                                <input id='{$id}'
                                    type='radio'
                                    name='{$option_settings_db_name}[{$id}]'
                                    class='{$class}  my_plugin_input-radio'
                                    value='{$select_value}'
                                    {$checked}>
                                    $description_key}
                                <br>";
            }
            $html_string .= "<label class='my_plugin_label' id='{$id}_label'>{$description}</label>
                        </div>";
            return $html_string;
        }

        private static function build_input_unique_settings_id($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $settings_node->class .= ' unique_id';
            $slugify = true;
            return self::build_input_select($option_settings_db_name, $settings_value, $settings_node, $slugify);
        }

        private static function build_input_select($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node, $slugify=false)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            $html_string = "<div class='my_plugin_input'>
                            <select id='{$id}'
                                name='{$option_settings_db_name}[{$id}]'
                                class='{$class} my_plugin_input-select'>";

            foreach ($select_options as $description_key => $select_value) {
                if($slugify){
                    $select_value = sanitize_title($select_value);
                } else {
                    $select_value = sanitize_text_field($select_value);
                }
                $selected = ($settings_value == $select_value) ? "selected='selected'" : '';
                $description_key = (is_string($description_key)) ? $description_key : $select_value;

                $html_string .= "<option value='{$select_value}'
                                    {$selected}>{$description_key}
                            </option>";
            }
            $html_string .= "    </select>
                            <!-- the label text -->
                            <label for='{$id}' class='my_plugin_label' id='{$id}_label'>{$description}</label>
                        </div>";

            return $html_string;
        }

        private static function build_input_checkbox($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            $checked = ($settings_value == true) ? 'checked' : '';
            $html_string = "<div class='my_plugin_input'>
                                <input id='{$id}'
                                    type='checkbox'
                                    name='{$option_settings_db_name}[{$id}]'
                                    class='{$class} my_plugin_input-checkbox'
                                    value='1'
                                    {$checked}>
                                    <!-- the label text -->
                                    <label for='{$id}' class='my_plugin_label' id='{$id}_label'>{$description}</label>
                                </input>
                        </div>";
            return $html_string;
        }

        private static function build_input_colour_select($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;
            $default_value = $settings_node->default_value;

            return "<div class='my_plugin_input'>
                    <input id='{$id}'
                        name='{$option_settings_db_name}[{$id}]'
                        size='40'
                        type='text'
                        step='1'
                        class='{$class} my_plugin_colour-picker'
                        data-default-color='{$default_value}'
                        value='{$settings_value}' />
                    <!-- the label text -->
                    <label for='{$id}' class='my_plugin_label' id='{$id}_label'>{$description}</label>
                </div>";
        }

        private static function build_input_image_select($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            $display_class = (isset($settings_value) && $settings_value != '') ? '' : 'my_plugin_hidden';

            $image_preview_url = (isset($settings_value) && $settings_value != '') ? 'url(' . $settings_value . ')' : '';

            //http://www.w3schools.com/cssref/css3_pr_background-size.asp
            return "<div class='my_plugin_input'>

                    <!-- the text box input, hidden as media is selected from gallery -->
                    <input id='{$id}'
                            name='{$option_settings_db_name}[{$id}]'
                            class='my_plugin_image-select_url my_plugin_hidden {$class}'
                            type='text'
                            value='{$settings_value}'/>

                    <!-- the button to open the media browser and choose/upload an image (jQuery) -->
                    <input id='{$id}_button'
                            class='my_plugin_image-select_button button'
                            type='text'
                            value='Select / Upload Image' />

                    <!-- the garbage icon - click to clear the url and image (jQuery) -->
                    <div class='my_plugin_image-select_clear clear_button dashicons dashicons-trash'
                            id='{$id}_clear' />
                    </div>

                    <!-- the label text -->
                    <label for='{$id}' class='my_plugin_label' id='{$id}_label'>{$description}</label>

                    <!-- a preview of the image, can be resized, hidden on load - unless there is a url available. also displayed/hidden in jQuery on load/delete -->
                    <div id='{$id}_preview'
                            class='my_plugin_image-select_preview {$display_class}'
                            style='background-image:{$image_preview_url}'/>
                    </div>
            ";
        }

        private static function build_input_about_page($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            return "<div class='my_plugin_about-page'
                        id='{$id}'>
                    <h4>{$title}</h4>
                    <p>{$description}</p>
                </div>
                ";
        }

        private static function build_input_dashicon_select($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            $display_class = (isset($settings_value) && $settings_value != '') ? '' : 'my_plugin_hidden';

            $dashicon_preview_value = (isset($settings_value) && $settings_value != '') ? $settings_value : '';

            $dashicon_selector = My_Plugin_Icons::get_dashicon_list();

            return "<div class='my_plugin_input'>

                    <!-- the text box input, hidden as media is selected from popup -->
                    <input id='{$id}'
                            name='{$option_settings_db_name}[{$id}]'
                            class='my_plugin_dashicon-select_value my_plugin_hidden {$class}'
                            type='text'
                            value='{$settings_value}'/>

                    <!-- the button to open the popup browser and choose/upload a Dashicon (jQuery) -->
                    <input id='{$id}_button'
                            class='my_plugin_dashicon-select_button button'
                            type='text'
                            value='Select WP Dashicon'
                            size='25'/>

                    <!-- the garbage icon - click to clear the dashicon value and image (jQuery) -->
                    <div class='my_plugin_dashicon-select_clear my_plugin_clear_button dashicons dashicons-trash'
                         id='{$id}_clear' />
                    </div>

                    <!-- the label text -->
                    <label class='my_plugin_label' id='{$id}_label'>{$description}</label>

                    <!-- a preview of the image, can be resized, hidden on load - unless there is a url available. also displayed/hidden in jQuery on load/delete -->
                    <div id='{$id}_preview'
                         class='my_plugin_dashicon-select_preview {$display_class} {$dashicon_preview_value}'
                         title='{$dashicon_preview_value}'/>
                    </div>
            ";
        }

        private static function build_input_glyphicon_select($option_settings_db_name, $settings_value, My_Plugin_Settings_Node $settings_node)
        {
            $id = $settings_node->id;
            $title = $settings_node->title;
            $description = $settings_node->description;
            $select_options = $settings_node->select_options;
            $class = $settings_node->class;

            $display_class = (isset($settings_value) && $settings_value != '') ? '' : 'my_plugin_hidden';

            $glyphicon_preview_value = (isset($settings_value) && $settings_value != '') ? $settings_value : '';

            $glyphicon_selector = My_Plugin_Icons::get_glyphicons_list();

            return "<div class='my_plugin_input'>

                    <!-- the text box input, hidden as media is selected from popup -->
                    <input id='{$id}'
                            name='{$option_settings_db_name}[{$id}]'
                            class='my_plugin_glyphicon-select_value my_plugin_hidden {$class}'
                            type='text'
                            value='{$settings_value}'/>

                    <!-- the button to open the popup browser and choose/upload a Dashicon (jQuery) -->
                    <input id='{$id}_button'
                            class='my_plugin_glyphicon-select_button button'
                            type='text'
                            value='Select Bootstrap Glyphicon'
                            size='25'/>

                    <!-- the garbage icon - click to clear the dashicon value and image (jQuery) -->
                    <div class='my_plugin_glyphicon-select_clear my_plugin_clear_button dashicons dashicons-trash'
                         id='{$id}_clear' />
                    </div>

                    <!-- the label text -->
                    <label class='my_plugin_label' id='{$id}_label'>{$description}</label>

                    <!-- a preview of the image, can be resized, hidden on load - unless there is a url available. also displayed/hidden in jQuery on load/delete -->
                    <div id='{$id}_preview'
                         class='my_plugin_glyphicon-select_preview {$display_class} {$glyphicon_preview_value}'
                         title='{$glyphicon_preview_value}'/>
                    </div>
            ";
        }

    }
}