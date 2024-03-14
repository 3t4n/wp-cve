<?php
/*
Form class
Version 2.0
 */

if (!class_exists('Form_Class')) {
    class Form_Class
    {

        /*
        parameters
        1. name defauls is f
        2. id defauls is blank
        3. method default is post
        4. action default is blank
        5. encription default is blank
        6. echo ( true, false ) default is true
        7. extra default is blank
         */
        public static function form_open($name = "f", $id = "", $method = "post", $action = "", $enctype = "", $echo = true, $extra = "")
        {
            $field = '<form name="' . $name . '" method="' . $method . '" action="' . $action . '" ' . ($id == '' ? '' : 'id="' . $id . '"') . ' ' . ($enctype == '' ? '' : 'enctype="' . $enctype . '"') . ' ' . $extra . '>';
            if ($echo) {
                echo $field;
            } else {
                return $field;
            }
        }

        /*
        parameters
        1. echo ( true, false ) default is true
         */
        public static function form_close($echo = true)
        {
            $field = '</form>';
            if ($echo) {
                echo $field;
            } else {
                return $field;
            }
        }

        /*
        parameters
        1. type ( text, hidden, email, number, submit, reset ) default is text
        2. name default is blank
        3. id default is blank
        4. value default is blank
        5. class default is blank
        6. onclick default is blank
        7. onchange default is blank
        8. onkeyup default is blank
        9. size default is blank
        10. pattern default is blank
        11. required ( false, true ) default is false
        12. placeholder default is blank
        13. style default is blank
        14. echo ( true, false ) default is true
        15. title default is blank
        16. extra default is blank
         */
        public static function form_input($type = "text", $name = "", $id = "", $value = "", $class = "", $onclick = "", $onchange = "", $onkeyup = "", $size = "", $pattern = "", $required = false, $placeholder = "", $style = "", $echo = true, $title = "", $extra = "")
        {
            $field = '<input type="' . $type . '" name="' . $name . '" ' . ($id == '' ? '' : 'id="' . $id . '"') . ' ' . ($value == '' ? '' : 'value="' . stripslashes($value) . '"') . ' ' . ($class == '' ? '' : 'class="' . $class . '"') . ' ' . ($onclick == '' ? '' : 'onClick="' . $onclick . '"') . ' ' . ($onchange == '' ? '' : 'onChange="' . $onchange . '"') . ' ' . ($onkeyup == '' ? '' : 'onKeyUp="' . $onkeyup . '"') . ' ' . ($size == '' ? '' : 'size="' . $size . '"') . ' ' . ($pattern == '' ? '' : 'pattern="' . $pattern . '"') . ' ' . ($required == false ? '' : 'required="required"') . ' ' . ($placeholder == '' ? '' : 'placeholder="' . $placeholder . '"') . ' ' . ($style == '' ? '' : 'style="' . $style . '"') . ' ' . ($title == '' ? '' : 'title="' . $title . '"') . ' ' . $extra . ' />';

            if ($echo) {
                echo $field;
            } else {
                return $field;
            }

        }

        /*
        parameters
        1. name default is blank
        2. id default is blank
        3. value default is blank
        4. class default is blank
        5. onclick default is blank
        6. required ( false, true ) default is false
        7. checked ( false, true ) default is false
        8. disabled ( false, true ) default is false
        9. style default is blank
        10. echo ( true, false ) default is true
        11. extra default is blank
         */
        public static function form_checkbox($name = "", $id = "", $value = "", $class = "", $onclick = "", $required = false, $checked = false, $disabled = false, $style = "", $echo = true, $extra = "")
        {
            $field = '<input type="checkbox" name="' . $name . '" ' . ($id == '' ? '' : 'id="' . $id . '"') . ' ' . ($value == '' ? '' : 'value="' . $value . '"') . ' ' . ($class == '' ? '' : 'class="' . $class . '"') . ' ' . ($onclick == '' ? '' : 'onClick="' . $onclick . '"') . ' ' . ($required == false ? '' : 'required="required"') . ' ' . ($checked == false ? '' : 'checked="checked"') . ' ' . ($disabled == false ? '' : 'disabled="disabled"') . ' ' . ($style == '' ? '' : 'style="' . $style . '"') . ' ' . $extra . ' />';

            if ($echo) {
                echo $field;
            } else {
                return $field;
            }
        }

        /*
        parameters
        1. name default is blank
        2. id default is blank
        3. value default is blank
        4. class default is blank
        5. onclick default is blank
        6. required ( false, true ) default is false
        7. checked ( false, true ) default is false
        8. disabled ( false, true ) default is false
        9. style default is blank
        10. echo ( true, false ) default is true
        11. extra default is blank
         */
        public static function form_radio($name = "", $id = "", $value = "", $class = "", $onclick = "", $required = false, $checked = false, $disabled = false, $style = "", $echo = true, $extra = "")
        {
            $field = '<input type="radio" name="' . $name . '" ' . ($id == '' ? '' : 'id="' . $id . '"') . ' ' . ($value == '' ? '' : 'value="' . $value . '"') . ' ' . ($class == '' ? '' : 'class="' . $class . '"') . ' ' . ($onclick == '' ? '' : 'onClick="' . $onclick . '"') . ' ' . ($required == false ? '' : 'required="required"') . ' ' . ($checked == false ? '' : 'checked="checked"') . ' ' . ($disabled == false ? '' : 'disabled="disabled"') . ' ' . ($style == '' ? '' : 'style="' . $style . '"') . ' ' . $extra . ' />';

            if ($echo) {
                echo $field;
            } else {
                return $field;
            }
        }

        /*
        parameters
        1. name default is blank
        2. id default is blank
        3. value default is blank
        4. class default is blank
        5. onclick default is blank
        6. onchange default is blank
        7. onkeyup default is blank
        8. rows default is blank
        9. cols default is blank
        10. required ( false, true ) default is false
        11. placeholder default is blank
        12. style default is blank
        13. echo ( true, false ) default is true
        14. extra default is blank
         */
        public static function form_textarea($name = "", $id = "", $value = "", $class = "", $onclick = "", $onchange = "", $onkeyup = "", $rows = "", $cols = "", $required = false, $placeholder = "", $style = "", $echo = true, $extra = "")
        {
            $field = '<textarea name="' . $name . '" ' . ($id == '' ? '' : 'id="' . $id . '"') . ' ' . ($class == '' ? '' : 'class="' . $class . '"') . ' ' . ($onclick == '' ? '' : 'onClick="' . $onclick . '"') . ' ' . ($onchange == '' ? '' : 'onChange="' . $onchange . '"') . ' ' . ($onkeyup == '' ? '' : 'onKeyUp="' . $onkeyup . '"') . ' ' . ($rows == '' ? '' : 'rows="' . $rows . '"') . ' ' . ($cols == '' ? '' : 'cols="' . $cols . '"') . ' ' . ($required == false ? '' : 'required="required"') . ' ' . ($placeholder == '' ? '' : 'placeholder="' . $placeholder . '"') . ' ' . ($style == '' ? '' : 'style="' . $style . '"') . ' ' . $extra . '>' . stripslashes($value) . '</textarea>';

            if ($echo) {
                echo $field;
            } else {
                return $field;
            }
        }

        /*
        parameters
        1. name default is blank
        2. id default is blank
        3. option default is blank
        4. class default is blank
        5. onchange default is blank
        6. required ( false, true ) default is false
        7. style default is blank
        8. echo ( true, false ) default is true
        9. extra default is blank
         */
        public static function form_select($name = "", $id = "", $option = "", $class = "", $onchange = "", $required = false, $style = "", $echo = true, $extra = "")
        {
            $field = '<select name="' . $name . '" ' . ($id == '' ? '' : 'id="' . $id . '"') . ' ' . ($class == '' ? '' : 'class="' . $class . '"') . ' ' . ($onchange == '' ? '' : 'onChange="' . $onchange . '"') . ' ' . ($required == false ? '' : 'required="required"') . ' ' . ($style == '' ? '' : 'style="' . $style . '"') . ' ' . $extra . '>' . $option . '</select>';

            if ($echo) {
                echo $field;
            } else {
                return $field;
            }
        }
    }
}
