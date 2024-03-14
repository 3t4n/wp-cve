<?php
if (!class_exists('AtfHtmlHelper')) {
    class AtfHtmlHelper
    {
        public static function assets($prefix = null, $url = null)
        {
            if (!$url) {
                $url = plugin_dir_url(__FILE__);
            }
            //Chosen
            wp_enqueue_script('chosen-script', $url . 'assets/chosen.jquery.min.js', array('jquery', 'wp-color-picker', 'jquery-ui-sortable'), '1.0', false);

            wp_enqueue_style('wp-color-picker');

            wp_enqueue_script('jquery-ui-datepicker');
//            wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

            wp_enqueue_media();

            //atf-fields
            wp_enqueue_style('atf-fields-css', $url . 'assets/fields.css?prefix=' . $prefix, array(), '1.0', 'all');
            wp_enqueue_script('atf-options-js', $url . 'assets/fields.js?prefix=' . $prefix, array(
                'jquery', 'wp-color-picker', 'jquery-ui-sortable', 'chosen-script'), '1.0', false);

            wp_localize_script('atf-options-js', 'atf_html_helper', array('url' => $url . 'assets/blank.png'));


        }

        public static function table($fields, $data = array())
        {
            ?>
            <table class="form-table atf-fields">
                <tbody>
                <?php
                foreach ($fields as $key => $field) {

                    $field = wp_parse_args($field, array(
                        'id' => $key,
                        'name' => $key,
                        'type' => 'text',
                        'default' => '',
                    ));

                    $field['value'] = (isset ($data[$key])) ? $data[$key] : $field['default'];


                    ?>
                    <tr>
                        <th scope="row">
                            <label for="<?php echo $field['id']; ?>"><?php echo $field['title'] ?></label>
                        </th>
                        <td>
                            <?php call_user_func(array(__CLASS__, $field['type']), $field); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>
            <?php
        }

        /**
         * @param array $args
         */
        public static function group_items($args = array())
        {
            $args = wp_parse_args($args, array(
                'vertical' => false,
            ))
            ?>

            <div class="atf-options-group <?php echo ($args['vertical']) ? 'vertical' : ''; ?>">
                <?php
                $i = 1;
                if (empty($args['value']) || !is_array($args['value'])) $args['value'] = array(array());
                foreach ($args['value'] as $row_key => $row_val) {

                    ?>
                    <div class="row">
                        <div class="header">
                            <div class="group-row-id"><?php echo $i ?></div>
                        </div>

                        <?php

                        AtfHtmlHelper::table($args['items'], $row_val, array(
                            'name_prefix' => $args['name'] . '[' . $row_key . ']',
                        ));

                        foreach ($args['items'] as $key => $item) {
                            $item['id'] = $key;
                            $item['desc'] = null;
                            $item['uniqid'] = uniqid($item['id']);



                            if (!isset($row_val[$item['id']])) {
                                $item['value'] = '';
                            } else {
                                $item['value'] = $row_val[$item['id']];
                            }
                            if (!isset($item['cell_style'])) $item['cell_style'] = '';


//                            echo '<td '
//                                . 'style="' . $item['cell_style'] . '"'
//                                . 'data-label="' . esc_attr($item['title']) . '" '
//                                . 'data-field-type="' . esc_attr($item['type']) . '" '
//                                . 'data-field-name-template="' . esc_attr($args['name'] . '[#][' . $item['id'] . ']') . '">';
//                            $item['id'] = $item['uniqid'];
//                            call_user_func(array(__CLASS__, $item['type']), $item);

                        }
                        ?>
                        <div class="group-row-controls">
                            <a class="button button-primary btn-control-group plus" href="#">+</a>
                            <a class=" btn-control-group minus" href="#"><?php _e('Delete'); ?></a>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <?php $i++;
                }

                ?>
            </div>


            <?php
        }

        /**
         * @param array $args
         */
        public static function group($args = array())
        {
            $args = wp_parse_args($args, array(
                'vertical' => false,
            ))
            ?>


            <table class="form-table atf-options-group <?php echo ($args['vertical']) ? 'vertical' : ''; ?>">
                <thead>
                <tr>
                    <th class="group-row-id">#</th>
                    <?php

                    foreach ($args['items'] as $key => $item) {
                        echo '<th>' . esc_html($item['title']) . '</th>';
                    }

                    ?>
                    <th class="group-row-controls"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                if (empty($args['value']) || !is_array($args['value'])) $args['value'] = array(array());
                foreach ($args['value'] as $row_key => $row_val) {
                    echo '<tr class="row">';
                    echo '<td class="group-row-id">' . $i . '</td>';
                    foreach ($args['items'] as $key => $item) {
                        $item['id'] = $key;
                        $item['desc'] = null;
                        $item['uniqid'] = uniqid($item['id']);
                        $item['name'] = $args['name'] . '[' . $row_key . '][' . $item['id'] . ']';


                        if (!isset($row_val[$item['id']])) {
                            $item['value'] = '';
                        } else {
                            $item['value'] = $row_val[$item['id']];
                        }
                        if (!isset($item['cell_style'])) $item['cell_style'] = '';


                        echo '<td '
                            . 'style="' . $item['cell_style'] . '"'
                            . 'data-label="' . esc_attr($item['title']) . '" '
                            . 'data-field-type="' . esc_attr($item['type']) . '" '
                            . 'data-field-name-template="' . esc_attr($args['name'] . '[#][' . $item['id'] . ']') . '">';
                        $item['id'] = $item['uniqid'];
                        call_user_func(array(__CLASS__, $item['type']), $item);
                        echo '</td>';


                    }
                    echo '<td class="group-row-controls">';
                    echo '<a class="btn-control-group plus" href="#" >+</a>';
                    echo '<a class="btn-control-group minus" href="#" >&times;</a>';
                    echo '</td>';
                    echo '</tr>';
                    $i++;
                }

                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="group-row-id">#</td>
                    <?php

                    foreach ($args['items'] as $key => $item) {

                        echo '<td>';
                        echo (empty($item['desc'])) ? '' : '<p  class="description">' . esc_html($item['desc']) . '</p>';
                        echo '</td>';
                    }

                    ?>
                    <th class="group-row-controls"></th>
                </tr>
                </tfoot>
            </table>


            <?php
        }

        /**
         * @param array $args
         */
        public static function text($args = array())
        {
            $args = wp_parse_args($args, array(
                'value' => '',
                'class' => 'regular-text',
                'add_class' => '',
                'after' => ''
            ));

            $result = '<input type="text" id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['name']) . '" value="' . esc_attr($args['value']) . '" class="' . esc_attr($args['class'] . $args['add_class']) . '" /> ' . $args['after'];

            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }

            echo $result;
        }

        /**
         * @param array $args
         */
        public static function datepicker($args = array())
        {
            $args = wp_parse_args($args, array(
                'value' => '',
                'class' => 'regular-text',
                'addClass' => '',
            ));

            $result = '<input type="text" id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['name']) . '" value="' . esc_attr($args['value']) . '" class="atf-datepicker ' . esc_attr($args['class'] . $args['addClass']) . '" />';
            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }

            echo $result;
        }


        /**
         * @param array $args
         */
        public static function number($args = array())
        {
            $args = wp_parse_args($args, array(
                'value' => '',
                'class' => 'regular-text',
                'addClass' => '',
                'step' => 1,
                'min' => 0,
            ));

            $result = '<input type="number" id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['name']) . '" value="' . esc_attr($args['value']) . '" class="' . esc_attr($args['class'] . $args['addClass']) . '" />';
            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }

            echo $result;
        }

        public static function upload($args = array())
        {
            $args = wp_parse_args($args, array(
                'multiple' => false,
                'accept' => '*'
            ));
            if (!isset($args['id'])) $args['id'] = uniqid('upload');
            if (!isset($args['label'])) $args['label'] = __('Choose a file', 'atf-fields');

            ?>
            <div class="upload-field">
                <ul class="file-list">
                </ul>
                <label for="<?php echo $args['id']; ?>" class="button button-default upload-label">
                    <?php _e('Upload CSV'); ?>
                    <input id="<?php echo $args['id']; ?>" type="file"
                           name="subscribers_base" <?php echo ($args['multiple']) ? 'multiple' : ''; ?>
                           accept="<?php echo $args['accept']; ?>"></label>

            </div>

            <?php

        }

        /**
         * @param array $args
         */
        public static function media($args = array())
        {

            $args = wp_parse_args($args, array(
                'value' => '',
                'class' => 'regular-text',
                'addClass' => '',
                'file' => false,
                'show_link' => false,
                'preview_size' => '150px',
            ));
            if (empty($args['value'])) {
                $screenshot = ' style="display:none;"';
                $remove = ' style="display:none;"';
                $upload = '';
            } else {
                $screenshot = '';
                $remove = '';
                $upload = ' style="display:none;"';
            }
            if ($args['file']) {
                $src = includes_url('images/media/document.png');
            } else {
                $src = $args['value'];
            }


            ?>
            <div class="uploader <?php echo ($args['file']) ? 'file' : ''; ?>">
                <div class="atf-preview" style="<?php echo 'width: ' . $args['preview_size'] . ';'; ?>">
                    <img class="atf-options-upload-screenshot" id="<?php echo esc_attr('screenshot-' . $args['id']); ?>"
                         src="<?php echo esc_url($src); ?>" <?php echo $screenshot; ?>/>
                </div>

                <input type="<?php echo ($args['show_link']) ? 'text' : 'hidden'; ?>"
                       id="<?php echo esc_attr($args['id']); ?>"
                       name="<?php echo esc_attr($args['name']); ?>"
                       value="<?php echo esc_url($args['value']); ?>"
                       class="<?php echo esc_attr($args['class'] . $args['addClass']); ?>"/>
                <a data-update="Select File"
                   data-choose="Choose a File"
                   href="javascript:void(0);"
                   class="atf-options-upload button-secondary" <?php echo $upload; ?>
                   rel-id="<?php echo esc_attr($args['id']); ?>"><?php echo __('Upload', 'atf'); ?></a>
                <a href="javascript:void(0);"
                   class="atf-options-upload-remove  button-secondary"<?php echo $remove; ?>
                   rel-id="<?php echo esc_attr($args['id']); ?>"><?php echo __('Remove Upload', 'atf'); ?></a>
            </div>

            <?php if (isset($args['desc'])) echo '<p class="description">' . $args['desc'] . '</p>';

        }

        /**
         * @param array $args
         */
        public static function color($args = array())
        {
            $args = wp_parse_args($args, array(
                'value' => '',
                'class' => 'color-picker-hex',
                'addClass' => '',
            ));

            $result = '<div class="customize-control-content"><input type="text" id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['name']) . '" value="' . $args['value'] . '" class="' . $args['class'] . $args['addClass'] . '" /></div>';
            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }

            echo $result;

        }

        public static function textarea($args = array())
        {
            $default = array(
                'value' => '',
                'class' => 'regular-text',
                'addClass' => '',
                'rows' => 10,
                'cols' => 50,
            );
            foreach ($default as $key => $value) {
                if (!isset($args[$key])) {
                    $args[$key] = $value;
                }
            }
            $result = '<textarea id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['name']) . '" rows="' . esc_attr($args['rows']) . '" cols="' . esc_attr($args['cols']) . '" class="' . esc_attr($args['class'] . $args['addClass']) . '" >' . esc_textarea($args['value']) . '</textarea>';
            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }
            echo $result;
        }

        public static function editor($args = array())
        {
            if (!isset($args['options'])) $args['options'] = array();
            $default = array(
                'value' => '',
                'class' => 'regular-text',
                'addClass' => '',
                'rows' => 10,
                'cols' => 50,
                'options' => array(
                    'wpautop' => true, // use wpautop?
                    'media_buttons' => false, // show insert/upload button(s)
                    'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                    'tabindex' => '',
                    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
                    'editor_class' => '', // add extra class(es) to the editor textarea
                    'teeny' => false, // output the minimal editor config used in Press This
                    'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
                    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                    'quicktags' => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                    'toolbar1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_fullscreen,wp_adv ',
                    'toolbar2' => 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ',
                    'toolbar3' => '',
                    'toolbar4' => '',
                ),
            );


            $args['options'] = wp_parse_args($args['options'], $default['options']);
            $args = wp_parse_args($args, $default);

            $args['options']['textarea_name'] = $args['name'];

            wp_editor(stripslashes($args['value']), $args['id'], $args['options']);
            if (isset($args['desc'])) {
                echo '<p class="description">' . $args['desc'] . '</p>';
            }

        }

        public static function tumbler($args = array())
        {
            if (empty($args['name'])) {
                $args['name'] = $args['id'];
            }

            $args = wp_parse_args($args, array(
                'true' => true,
                'false' => false,
            ));
            $result = '<input type="hidden" name="' . esc_attr($args['name']) . '" value="' . esc_attr($args['false']) . '" >';
            $result .= '<label class="tumbler-container">';
            $result .= '<input type="checkbox" class="on" name="' . esc_attr($args['name']) . '" value="' . esc_attr($args['true']) . '"  ' . checked($args['value'], $args['true'], false) . ' >';
            $result .= '<span class="on-off-box">';
            $result .= '<span class="tumbler"></span>';
            $result .= '<span class="text on">on</span>';
            $result .= '<span class="text off">off</span>';
            $result .= '</span>';
            $result .= '</label>';

            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }

            echo $result;
        }


        public static function select($args)
        {
            if (isset($args['taxonomy'])) {

                self::selectFromTaxonomy($args);
            } else {
                $result = '<select name="' . esc_attr($args['name']) . '">';

                if (!isset($args['values'])) {
                    $args['values'] = $args['options'];
                }

                foreach ($args['values'] as $value => $text) {
                    $result .= '<option value="' . esc_attr($value) . '" ' . selected($value, $args['value'], false) . ' > ' . $text . ' </option>';
                }

                $result .= '</select>';

                echo $result;
            }

        }

        public static function chosen($args)
        {
            $args = wp_parse_args($args, array(
                'multiple'
            ));
            $result = '<select ' .
                'multiple="multiple" ' .
                'name="' . esc_attr($args['name'] . '[]') . '"' .
                'class="chosen-select"' .
                'data-placeholder="Select Your Options">';

            if (!isset($args['values'])) $args['values'] = $args['options'];

            if (!is_array($args['value'])) $args['value'] = array($args['value']);

            foreach ($args['values'] as $value => $text) {
                $result .= '<option value="' . esc_attr($value) . '" ' . selected($value, (in_array($value, $args['value']) ? $value : ''), false) . ' > ' . $text . ' </option>';
            }

            $result .= '</select>';

            echo $result;
        }

        public static function taxonomy_select($args)
        {
            self::selectFromTaxonomy($args);
        }

        public static function selectFromTaxonomy($args)
        {
            if (taxonomy_exists($args['taxonomy'])) {
                $args['selected'] = $args['value'];
                wp_dropdown_categories($args);
            } else {
                var_dump(get_taxonomies());
                echo "Taxonomy not exist";
            }
            if (isset($args['desc'])) {
                echo '<p class="description">' . $args['desc'] . '</p>';
            }
        }

        //    public static function


        public static function checkboxTaxonomy($args)
        {

            if (taxonomy_exists($args['taxonomy'])) {
                if (!is_array($args['value'])) {
                    $args['value'] = array($args['value']);
                }

                $cats = get_terms(array(
                    'taxonomy' => $args['taxonomy'],
                    'hide_empty' => $args['hide_empty'],
                ));

                $result = '';


                foreach ($cats as $cat) {
                    $result .= ' <label><input type="checkbox"'
                        . ' name="' . esc_attr($args['name'] . '[]') . '"'
                        . ' value="' . esc_attr($cat->term_id) . '" ';
                    $result .= (in_array($cat->term_id, $args['value'])) ? 'checked="checked"' : '';
                    $result .= ' > ' . esc_html($cat->name) . '</label> ';

                }

                $result .= '';

                if (isset($args['desc'])) {
                    $result .= '<p class="description">' . $args['desc'] . '</p>';
                }

                echo $result;
            } else {
                var_dump(get_taxonomies());
                echo "Taxonomy not exist";
            }

        }

        public static function radio($args = array())
        {

            $args = wp_parse_args($args, $default = array(
                'vertical' => true,
                'value' => '',
                'class' => '',
                'addClass' => '',
                'attributes' => array(),
            ));

            $attributes = ' ';

            foreach ($args['attributes'] as $attr_key => $attr_val) {
                $attributes .= $attr_key . '="' . $attr_val . '" ';
            }

            $result = '';
            $result .= '<fieldset class="' . esc_attr($args['class'] . $args['addClass']) . '" >';
            foreach ($args['options'] as $value => $label) {
                $id = esc_attr($args['name'] . '__' . $value);
                $checked = '';
                if ($value == $args['value']) {
                    $checked = "checked";
                }

                $result .= '<input type="radio"'
                    . ' id="' . $id . '"'
                    . $attributes
                    . ' name="' . esc_attr($args['name']) . '" value="' . esc_attr($value) . '" ' . checked($args['value'], $value, false) . ' />';
                $result .= ' <label for="' . $id . '">' . $label . '</label> ';
                if ($args['vertical']) $result .= '<br />';
            }
            $result .= '</fieldset>';

            echo $result;
        }

        public static function checkbox($args)
        {
            $args = wp_parse_args($args, array(
                'vertical' => true,
                'value' => '',
                'class' => '',
                'addClass' => '',
            ));

            if (isset($args['taxonomy'])) {
                if (taxonomy_exists($args['taxonomy'])) {
                    $options = self::get_taxonomy_options($args);

                } else {
                    var_dump(get_taxonomies());
                    echo "Taxonomy not exist";
                }
            }

            if (isset($args['options']) && !isset($options)) {
                $options = $args['options'];
            } elseif (isset($args['options']) && isset($options)) {
                $options = $args['options'] + $options;
            } elseif (!isset($args['options']) && !isset($options)) {
                echo 'No options';
                return;
            }


            if (!is_array($args['value'])) {
                $args['value'] = array($args['value']);
            }

            $result = '';
            $result .= '<fieldset class="' . esc_attr($args['class'] . $args['addClass']) . '" >';
            foreach ($options as $val => $label) {
                $id = esc_attr($args['name'] . '__' . $val);
                $result .= '<input type="checkbox"'
                    . ' id="' . $id . '"'
                    . ' name="' . esc_attr($args['name'] . '[]') . '"'
                    . ' value="' . esc_attr($val) . '" ';
                $result .= (in_array($val, $args['value'])) ? 'checked="checked"' : '';
                $result .= ' > ';
                $result .= ' <label for="' . $id . '">' . esc_html($label) . '</label> ';
                if ($args['vertical']) $result .= '<br />';

            }
            $result .= '</fieldset>';

            if (isset($args['desc'])) {
                $result .= '<p class="description">' . $args['desc'] . '</p>';
            }

            echo $result;


        }

        public static function get_taxonomy_options($args = array())
        {
            if (!is_array($args) && !empty($args)) $args = array('taxonomy' => $args);

            $args = wp_parse_args($args, array(
                'taxonomy' => 'category',
                'hide_empty' => false,
            ));


            $terms = (array)get_terms(array(
                'taxonomy' => $args['taxonomy'],
                'hide_empty' => $args['hide_empty'],
            ));
            // Initate an empty array
            $term_options = array();
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    $term_options[$term->term_id] = $term->name;
                }
            }

            return $term_options;
        }

        public static function info($args = array())
        {
            echo 'info';
        }


        /**
         * @deprecated
         * @param array $args
         */
        public static function multiselect($args)
        {
            self::chosen($args);
        }

        /**
         * @deprecated
         * @param array $args
         */
        public static function textField($args = array())
        {
            self::text($args);
        }

        /**
         * @deprecated
         * @param array $args
         *
         */
        public static function radioButtons($args = array())
        {
            self::radio($args);
        }

        /**
         * @deprecated
         * @param array $args
         */
        public static function addMedia($args = array())
        {
            self::media($args);
        }

        /**
         * @deprecated
         * @param array $args
         */
        public static function colorPicker($args = array())
        {
            self::color($args);
        }

        /**
         * @deprecated
         * @param array $args
         */
        public static function wysiwyg($args = array())
        {
            self::editor($args);
        }

        /**
         * @deprecated
         * @param array $args
         */
        public static function onOffBox($args = array())
        {
            self::tumbler($args);
        }

    }

}

if (!function_exists('sanitize_atf_fields')) {
    function sanitize_atf_fields($value, $field)
    {

        if (!is_array($field)) {
            $field['type'] = $field;
        }
        switch ($field['type']) {
            case 'text':
                return sanitize_text_field($value);
                break;
            case 'media':
                return esc_url_raw($value);
                break;
            case 'editor':
                return wp_kses_post($value);
                break;
            case 'group':
                $group_data = array();
                foreach ($value as $row) {
                    $row_data = array();
                    foreach ($field['items'] as $key => $subfield) {
                        $row_data[$key] = sanitize_atf_fields($row[$key], $subfield);
                    }
                    $group_data[] = $row_data;
                    var_dump($row_data);
                }
                return $group_data;

                break;
            default:
                return sanitize_text_field($value);
                break;

        }

    }
}