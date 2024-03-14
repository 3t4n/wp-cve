<?php
/*
 *  SwiftSignature shortcode list
 */

function ssign_shortcodes_cb() {
    ?>
    <div class="wrap ss-shortcodes">
        <h2 class="swiftpage-title">Swift Signature Shortcodes</h2><hr>
        <div class="inner_content">
            <?php _e('<b>Note</b> : Each & Every field should have unique name to work properly.', 'swift-signature'); ?>
            <ol>
                <li><?php _e('<b>Add following shortcode to genrates Form.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swiftsign method=\'GET/POST\' action=\'form action\'].....[/swiftsign]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('method : form method GET or POST. Default is POST.', 'swift-signature'); ?></li>
                        <li><?php _e('action : gives action to form for submit. Default is https://swiftcloud.ai/is/drive/formHandlingProcess001', 'swift-signature'); ?></li>
                        <li><?php _e('swift_form_id : swift form id. Required otherwise form is not displaying.', 'swift-signature'); ?></li>
                        <br/>
                        <li><?php _e('<b>Note :</b> Following shortcodes goes between [swiftsign]. . . . .[swiftsign]', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Text field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_textbox name=\'field_name\' class=\'class_name\' value=\'field value\' placeholder=\'placeholder\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('value : field prefill value. default is blank.', 'swift-signature'); ?></li>
                        <li><?php _e('placeholder : add placeholder in field.', 'swift-signature'); ?></li>
                        <li><?php _e('required: field is required. Optional.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Email field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_email name=\'field_name\' class=\'class_name\' value=\'field value\' placeholder=\'placeholder\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('value : field prefill value. default is blank.', 'swift-signature'); ?></li>
                        <li><?php _e('placeholder : add placeholder in field.', 'swift-signature'); ?></li>
                        <li><?php _e('required: field is required. Optional.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates URL field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_url name=\'field_name\' class=\'class_name\' value=\'field value\' placeholder=\'placeholder\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('value : field prefill value. default is blank.', 'swift-signature'); ?></li>
                        <li><?php _e('placeholder : add placeholder in field.', 'swift-signature'); ?></li>
                        <li><?php _e('required: field is required. Optional.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Textarea field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_textarea name=\'field_name\' class=\'class_name\' value=\'field value\' rows=\'field rows\' placeholder=\'placeholder\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('value : field prefill value. default is blank.', 'swift-signature'); ?></li>
                        <li><?php _e('placeholder : add placeholder in field.', 'swift-signature'); ?></li>
                        <li><?php _e('rows : rows of textarea. default 5 rows.', 'swift-signature'); ?></li>
                        <li><?php _e('required: field is required. Optional.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Checkbox field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_checkbox name=\'field_name\' class=\'class_name\' options=\'option1,option2,.....,optionN\' checked=\'option_name\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('options : comma seprated list of options', 'swift-signature'); ?></li>
                        <li><?php _e('checked : pass option name which you want to pre-selected.', 'swift-signature'); ?></li>
                        <li><?php _e('required : field is required. Optional.', 'swift-signature'); ?></li>
                        <li><?php _e('<b>Note</b> : Options are set as field value and field ID.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Radio button field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_radio name=\'field_name\' class=\'class_name\'  options=\'option1,option2,.....,optionN\' checked=\'option_name\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('options : comma seprated list of options', 'swift-signature'); ?></li>
                        <li><?php _e('checked : pass option name which you want to pre-selected.', 'swift-signature'); ?></li>
                        <li><?php _e('required : field is required. Optional.', 'swift-signature'); ?></li>
                        <li><?php _e('<b>Note</b> : Options are set as field value and field ID.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Circleword.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_circleword name=\'field_name\' class=\'class_name\'  options=\'option1,option2,.....,optionN\' checked=\'option_name\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('options : comma seprated list of options', 'swift-signature'); ?></li>
                        <li><?php _e('checked : pass option name which you want to pre-selected.', 'swift-signature'); ?></li>
                        <li><?php _e('required : field is required. Optional.', 'swift-signature'); ?></li>
                        <li><?php _e('<b>Note</b> : Options are set as field value and field ID.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Dropdown field.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_dropdown name=\'field_name\' class=\'class_name\' option_values=\'value1,value2,....,valueN\' selected_option=\'value\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('option_values : dropdown\'s options; enter comma seprated values.', 'swift-signature'); ?></li>
                        <li><?php _e('selected_option : pass option which you want to pre-selected.', 'swift-signature'); ?></li>
                        <li><?php _e('required: field is required. Optional.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Submit button.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_button name=\'button name\' value=\'button value\' class=\'button class\' label=\'button label\']" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : button name.', 'swift-signature'); ?></li>
                        <li><?php _e('label : button label.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('value : button value.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to display current date.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_date_today]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates Date picker.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_date name=\'field_name\' class=\'class_name\' required]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                        <li><?php _e('name  : field name.', 'swift-signature'); ?></li>
                        <li><?php _e('class : add class for styling.', 'swift-signature'); ?></li>
                        <li><?php _e('required: field is required. Optional.', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to display current time.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_date_time_now]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates a signature-drawing tool.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swiftsignature size=\'small/medium/large\']" style="width: 60em"/>', 'swiftbooks-subscription'); ?></li>
                        <li><?php _e('size  : size of signature box; small / medium / large; default to small', 'swift-signature'); ?></li>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to genrates a smaller initial drawing tool.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_initials]" style="width: 60em"/>', 'swiftbooks-subscription'); ?>
                    </ul><hr/>
                </li>
                <li><?php _e('<b>Add following shortcode to display user\'s first name.</b>', 'swift-signature'); ?>
                    <ul>
                        <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swiftsign_capture_name]" style="width: 60em"/>', 'swiftbooks-subscription'); ?></li>
                        <li><?php _e('<b>Note :</b> This shortcode works after user submit swiftform.', 'swift-signature'); ?></li>
                    </ul>
                </li>
            </ol>
            <hr/>
        </div>
    </div>
    <?php
}