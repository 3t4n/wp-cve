<?php
defined('ABSPATH') or die('No script kiddies please!');

class ecf_fields_generator
{
    private static $instance = null;

    public static function get_instance($slug = '_slug', $text_domain = 'ecf')
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($slug = '_slug', $text_domain = 'ecf');
        }

        return self::$instance;
    }

    function __construct($slug = '_slug', $text_domain = 'ecf')
    {
        $this->fields =
            array(
                array(
                    'name' => __('Text', 'ecf'),
                    'type' => 'text',
                ),
                array(
                    'name' => __('Multi Line Text', 'ecf'),
                    'type' => 'multi_line_text',
                ),
                array(
                    'name' => __('Checkbox', 'ecf'),
                    'type' => 'checkbox',
                ),
                array(
                    'name' => __('Paragraph', 'ecf'),
                    'type' => 'paragraph',
                ),
                array(
                    'name' => __('ComboBox', 'ecf'),
                    'type' => 'select',
                ),
            );
        $this->render_custom_fields('_ecf', 'ecf');
    }

    public function render_custom_fields($slug = '_slug', $text_domain = '_ecf')
    {

        /**
         * I know this is dirty , but keep it here
         */
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable', false, array('jquery', 'jquery-ui-core'), false, false);

        ?>
        <style type="text/css">
            .wpm_custom_fields {
                padding: 25px;
            }

            .field_settings {
                display: none;
            }

            .m_fields {
                border: 1px solid #d2d2d2;
                box-shadow: 1px 2px 3px 1px #eee;
                margin: 10px auto;
                padding: 10px;
                position: relative;
                width: 95%;
                cursor: all-scroll;
                background: #f9f9f9;
            }

            .m_fields > p {
                display: inline;
            }

            .field_settings label {
                display: block;
                border-right: 1px solid #eee;
                padding-right: 5px;
            }

            .field_settings label strong {
                min-width: 110px;
                display: inline-block;
                /*background: #f5f5f5;*/
            }

            .field_settings input {
                font-size: 10pt;
            }

            .t_clear {
                display: block;
                height: 1px;
                width: 100%;
                clear: both;
            }

            .wpm_del_field {
                background: #ededed none repeat scroll 0 0;
                border-radius: 60px;
                cursor: pointer;
                margin: 5px;
                padding: 2px 9px;
                position: absolute;
                right: -10px;
                top: -10px;
                transition: all ease 0.3s;
                border: 1px solid #aaa;
            }

            .wpm_del_field:hover {
                background-color: red;
                color: #fff;
            }

            .combo_add, .combo_remove {
                background: #aaa;
                color: #444;
                padding: 4px;
                transition: all ease .3s;
            }

            .combo_add:hover, .combo_remove:hover {
                background: #fff;
            }

            .m_fields_start .field_settings_wrapper {
                display: none;
            }


            .m_fields:hover {
                background: #fff;
            }

            .wpm_field_main_name {
                width: 100%;
                padding: 5px;
                color: green;
                margin-right: 10px;
                font-weight: bold;
                display: block;
                font-size: 1.3em;
                display: inline-block;
            }

            .add_new_field_help {
                font-size: 1.5em;
            }

            .fields_placeholder {
                border: 2px dashed #ccc;
                padding: 5px;
                min-height: 150px;
            }

            .wpm_field_main_name .id {
                font-size: .9em;
                color: #aaa;
            }

            .field_type_paragraph .f_required,
            .field_type_paragraph .f_desc,
            .field_type_paragraph .f_show_admin,
            .field_type_paragraph .f_id
            {
                display: none;
            }

            .m_fields.field_type_text .field_checkbox, .m_fields.field_type_text .field_paragraph, .m_fields.field_type_text .field_select, .m_fields.field_type_checkbox .field_text, .m_fields.field_type_checkbox .field_paragraph, .m_fields.field_type_checkbox .field_select, .m_fields.field_type_paragraph .field_text, .m_fields.field_type_paragraph .field_checkbox, .m_fields.field_type_paragraph .field_select, .m_fields.field_type_select .field_text, .m_fields.field_type_select .field_checkbox, .m_fields.field_type_select .field_paragraph {
                display: none;
            }

            <?php
            $css_display_none = array();
            foreach ($this->fields  as $__field) {
                foreach ($this->fields  as $__field2) {
                    if($__field['type'] != $__field2['type'])
                        $css_display_none[]	 = '.m_fields.field_type_'.$__field['type'].' .field_'.$__field2['type'];
                }
            }
            $css_display_none = implode(',', $css_display_none);
            echo $css_display_none.'{display: none;}';
            ?>


        </style>

        <?php
        if (isset($_POST['wpm_fields'])) {
            update_option($slug, $_POST['wpm_fields']);
        }
        $fields = get_option($slug, array());
        if (isset($fields['last_saved'])) {
            unset($fields['last_saved']);
        }
        if ((!is_array($fields)) || !$fields)
            $fields = array();

        ?>

        <form method="post">
            <input type="hidden" name="wpm_fields[last_saved]" value="<?php echo time(); ?>">
            <div class="wpm_custom_fields">
                <button class="wpm_add_field button-secondary"><?php _e('+Add Field', 'ecf'); ?></button>
                <?php
                if (empty($fields)) {
                    ?>
                    <span class="add_new_field_help"> <img
                                src="<?php echo ecf_url . '/assets/click-here' . (is_rtl() ? '-rtl' : '') . '.png'; ?>"> <?php _e('Click to add new field', 'ecf'); ?> </span>
                    <?php
                }
                ?>
                <div class="t_clear"></div>
                <div class="wpm_note">
                    <p>
                        <?php
                        $notes = array(
                            // 'کلیه عناوین در "نام فیلد" قرار می گیرد',
                            // 'کلیه مقادیر در "مقدار پیشفرض" قرار می گیرد',
                        );
                        foreach ($notes as $note) {
                            echo '<span class="dashicons dashicons-yes"></span> کلیه مقادیر در "مقدار پیشفرض" قرار می گیرد.<br>';
                        }
                        ?>
                    </p>
                </div>
                <div class="t_clear"></div>
                <div class="fields_placeholder" data-fields-counter="<?php if (!empty($fields)) {
                    echo max(array_keys($fields['name'])) + 1;
                } else {
                    echo 1;
                } ?>">
                    <?php

                    if ((!empty($fields) || is_array($fields)) && isset($fields['name'])) {
                        foreach ($fields['name'] as $i => $name) {
                            echo $this->generate_field_html($i, $fields);
                        }

                    }

                    ?>
                </div> <!-- /.placeholder -->
                <div class="t_clear"></div>
            </div>
            <button class="button-primary"><?php _e('Save', 'ecf'); ?></button>
        </form>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                jQuery(".fields_placeholder").sortable({axis: "y"});
                $('.wpm_add_field').on('click', function (e) {
                    e.preventDefault();
                    var fields_counter = parseInt($('.fields_placeholder').attr('data-fields-counter'));
                    if (isNaN(fields_counter)) {
                        fields_counter = 0;
                    }
                    fields_counter = parseInt((fields_counter + 1));
                    $('.fields_placeholder').attr('data-fields-counter', fields_counter);
                    var new_element = $('<?php        $fields = get_option($slug, array());if (!empty($fields['name'])) {
                        $i_new = max(array_keys($fields['name'])) + 1;
                    } else {
                        $i_new = 1;
                    } echo str_replace(array("\n", "\r"), '', $this->generate_field_html($i_new, array(), true)); ?>');
                    new_element.appendTo(".fields_placeholder");
                    new_element.closest('.m_fields').find('.field_settings').slideDown();
                    // new_element.closest('.m_fields').find('.id').text(fields_counter+1);
                    $('.fields_placeholder').attr('data-fields-counter', fields_counter);
                    // alert(fields_counter);

                    $('html, body').animate({
                        scrollTop: new_element.offset().top
                    }, 900);


                });

                $('body').on('click', '.wpm_del_field', function () {
                    if (confirm('<?php _e('Are You Sure?', 'ecf'); ?>')) {
                        $(this).closest('.m_fields').slideUp().remove();
                    }
                });

                $('body').on('change', '.f_type_select', function () {
                    var this_parent = $(this).parents('.m_fields').eq(0);
                    this_parent[0].className = this_parent[0].className.replace(/\bfield_type_.*?\b/g, '');
                    this_parent.addClass('field_type_' + $(this).val());
                    this_parent.find('.field_settings_wrapper').slideUp();
                    this_parent.find('.field_settings_wrapper.field_' + $(this).val()).slideDown();
                });

                $('body').on('click', '.combo_remove', function () {
                    if (confirm('<?php _e('Are You Sure?', 'ecf'); ?>')) {
                        $(this).closest('.cobmobox_choices_wrapper').slideUp('slow').remove();
                    }
                });

                $('body').on('click', '.combo_add', function () {
                    var elem_index = parseInt($(this).attr('data-current-id'));
                    var element_to_add = $(this).closest('.field_settings_wrapper.field_select .f_choices');
                    var new_element = $('<?php $to_add = '<div class="cobmobox_choices_wrapper">								<strong>&nbsp;</strong>								<input value="" name="wpm_fields[combobox_choices][\'+elem_index+\'][]" type="text">								<span data-current-id="\'+elem_index+\'" class="combo_add">+</span>								<span class="combo_remove">-</span>							</div>'; echo str_replace(array("\n", "\r"), '', $to_add); ?>');
                    new_element.appendTo(element_to_add);
                });

                $('body').on('click', '.wpm_field_main_name', function () {
                    $(this).closest('.m_fields').find('.field_settings').slideToggle();
                });
                $('body').on('keyup', '.wpm_change_title_name', function () {
                    $(this).closest('.m_fields').find('.wpm_field_main_name').text($(this).val());
                });

            });
        </script>
        <?php


    }

    public function generate_field_html($i, $fields, $for_js = false)
    {
        ob_start();
        if ($for_js) {
            $fields['type'][$i] = "' + fields_counter + '";
            $fields['name'][$i] = "";
            $i = '';
            error_reporting(0);
        }

        ?>
        <div class="m_fields field_type_<?php echo $fields['type'][$i];
        if ($for_js) {
            echo ' m_fields_start ';
        } ?>">
            <span title="حذف" class="wpm_del_field">-</span>
            <span class="wpm_field_main_name"><span
                        class="id">#<?php echo $i; ?></span>  <?php if (isset($fields['name'][$i]) && !empty($fields['name'][$i])) {
                    echo $fields['name'][$i];
                } else {
                    _e('Untitled', 'ecf');
                } ?></span>
            <div class="field_settings">
                <div class="f_type">
                    <label><strong><?php _e('Field Type', 'ecf'); ?></strong></label>
                    <select class="f_type_select" name="wpm_fields[type][<?php echo $i; ?>]">
                        <option value="none"><?php _e('Select Field Type', 'ecf'); ?></option>
                        <?php
                        foreach ($this->fields as $field_types) {
                            ?>
                            <option value="<?php echo $field_types['type']; ?>" <?php selected($fields['type'][$i], $field_types['type'], true); ?>> <?php echo $field_types['name']; ?> </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <h3><?php _e('General', 'ecf'); ?></h3>
                <label class="f_name"><strong><?php _e('Field Name', 'ecf'); ?></strong> <input type="text"
                                                                                                value="<?php echo isset($fields['name'][$i]) ? $fields['name'][$i] : ''; ?>"
                                                                                                class="wpm_change_title_name"
                                                                                                name="wpm_fields[name][<?php echo $i; ?>]">
                </label>
                <label class="f_required"><strong><?php _e('Required?', 'ecf'); ?></strong>

                    <input
                            type="checkbox" <?php if (isset($fields['required'][$i])) {
                        echo 'checked=checked';
                    } ?> name="wpm_fields[required][<?php echo $i; ?>]" value="1">

                </label>
                <label class="f_show_admin"><strong><?php _e('Show in edd admin table?', 'ecf'); ?></strong>

                    <input
                            type="checkbox" <?php if (isset($fields['show_admin'][$i])) {
                        echo 'checked=checked';
                    } ?> name="wpm_fields[show_admin][<?php echo $i; ?>]" value="1">

                </label>
                <label class="f_desc"><strong><?php _e('Description', 'ecf'); ?></strong> <input type="text"
                                                                                                 value="<?php echo isset($fields['desc'][$i]) ? $fields['desc'][$i] : ''; ?>"
                                                                                                 name="wpm_fields[desc][<?php echo $i; ?>]">
                </label>
                <label class="f_id"><strong><?php _e('Custom ID', 'ecf'); ?></strong> <input type="text"
                                                                                             value="<?php echo isset($fields['f_id'][$i]) ? $fields['f_id'][$i] : ''; ?>"
                                                                                             name="wpm_fields[f_id][<?php echo $i; ?>]">
                    <span class="desc"><?php _e('to match with any older functions.php codes', 'ecf'); ?></span>
                </label>


                <!-- Text -->

                <div class="field_settings_wrapper field_text">
                    <hr>
                    <h3><?php _e('Textbox', 'ecf'); ?></h3>
                    <label class="f_value">
                        <strong><?php _e('Default Value', 'ecf'); ?></strong>
                        <input type="text"
                               value="<?php echo isset($fields['text_default'][$i]) ? $fields['text_default'][$i] : ''; ?>"
                               name="wpm_fields[text_default][<?php echo $i; ?>]">
                    </label>
                    <br>
                    <label class="f_placeholder">
                        <strong><?php _e('Placeholder', 'ecf'); ?></strong>
                        <input type="text"
                               value="<?php echo isset($fields['text_placeholder'][$i]) ? $fields['text_placeholder'][$i] : ''; ?>"
                               name="wpm_fields[text_placeholder][<?php echo $i; ?>]">
                    </label>
                </div>

                <!-- multi_line_text -->

                <div class="field_settings_wrapper field_multi_line_text">
                    <hr>
                    <h3><?php _e('Textbox', 'ecf'); ?></h3>
                    <label class="f_value">
                        <strong><?php _e('Default Value', 'ecf'); ?></strong>
                        <textarea name="wpm_fields[text_default][<?php echo $i; ?>]" cols="30"
                                  rows="10"><?php echo isset($fields['text_default'][$i]) ? $fields['text_default'][$i] : ''; ?></textarea>
                    </label>
                    <br>
                    <label class="f_placeholder">
                        <strong><?php _e('Placeholder', 'ecf'); ?></strong>
                        <textarea name="wpm_fields[text_placeholder][<?php echo $i; ?>]" cols="30"
                                  rows="10"><?php echo isset($fields['text_placeholder'][$i]) ? $fields['text_placeholder'][$i] : ''; ?></textarea>

                    </label>
                    <br>
                    <label class="f_readonly">
                        <strong><?php _e('ReadOnly', 'ecf'); ?></strong>
                        <input <?php if (isset($fields['readonly'][$i]) && $fields['readonly'][$i] == 1) {
                            echo ' checked=checked ';
                        } ?> name="wpm_fields[readonly][<?php echo $i; ?>]" value="1" type="checkbox">
                    </label>
                </div>

                <!-- Paragraph -->


                <div class="field_settings_wrapper field_paragraph">
                    <hr>
                    <h3><?php _e('Paragraph', 'ecf'); ?></h3>
                    <label class="f_value">
                        <strong><?php _e('Content', 'ecf'); ?></strong>
                        <textarea
                                name="wpm_fields[paragraph_content][<?php echo $i; ?>]"><?php echo isset($fields['paragraph_content'][$i]) ? $fields['paragraph_content'][$i] : ''; ?></textarea>
                    </label>
                </div>

                <!-- Checkbox -->


                <div class="field_settings_wrapper field_checkbox">
                    <hr>
                    <h3><?php _e('Checkbox', 'ecf'); ?></h3>

                </div>

                <!-- Combobox -->


                <div class="field_settings_wrapper field_select">
                    <hr>
                    <h3><?php _e('Combobox', 'ecf'); ?></h3>
                    <label class="f_choices">
                        <?php
                        if (isset($fields['combobox_choices'][$i]) && is_array($fields['combobox_choices'][$i])) {
                            foreach ($fields['combobox_choices'][$i] as $c_choice) {
                                ?>
                                <div class="cobmobox_choices_wrapper">
                                    <strong>&nbsp;</strong>
                                    <input type="text" value="<?php echo $c_choice; ?>"
                                           name="wpm_fields[combobox_choices][<?php echo $i; ?>][]">
                                    <span class="combo_add" data-current-id="<?php echo $i; ?>">+</span>
                                    <span class="combo_remove">-</span>
                                </div>
                                <?php
                            }

                        } else {
                            ?>
                            <div class="cobmobox_choices_wrapper">
                                <strong>&nbsp;</strong>
                                <input type="text" value="" name="wpm_fields[combobox_choices][<?php echo $i; ?>][]">
                                <span class="combo_add" data-current-id="<?php echo $i; ?>">+</span>
                                <!-- <span class="combo_remove">-</span> -->
                            </div>
                            <?php
                        }

                        ?>
                    </label>

                </div>


            </div>
        </div>
        <?php
        $return = ob_get_clean();
        return str_replace(array("\n", "\r"), '', $return);
    }

    public function generate_all_fields_html()
    {
        ob_start();
        foreach ($this->fields as $field) {
            $this->generate_field_html('', array());
        }
        $return = ob_get_clean();
        return str_replace(array("\n", "\r"), '', $return);
    }
}


