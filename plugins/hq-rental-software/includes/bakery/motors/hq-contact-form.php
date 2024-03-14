<?php

/*
 * Caag Contact Form
 * Author: Miguel Faggioni
 */

vc_map(
    array(
        'name'                    => __('HQ Contact Form ', 'js_composer'),
        'base'                    => 'hq_contact_form',
        'content_element'         => true,
        'show_settings_on_create' => true,
        'description'             => __('HQ Contact Form Integration', 'js_composer'),
        'icon'                    =>    HQ_MOTORS_VC_SHORTCODES_ICON,
        'params' => array(
            array(
                'type'        => 'textfield',
                'heading'     => __('Name Field Label', 'js_composer'),
                'param_name'  => 'name_label',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Name Field Placeholder', 'js_composer'),
                'param_name'  => 'name_placeholder',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Name Field ID - HQ Integration', 'js_composer'),
                'param_name'  => 'name_hq_id',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Last Name Field Label', 'js_composer'),
                'param_name'  => 'last_name_label',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Last Name Field Placeholder', 'js_composer'),
                'param_name'  => 'last_name_placeholder',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Last Name Field ID - HQ Integration', 'js_composer'),
                'param_name'  => 'last_name_hq_id',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Email Field Label', 'js_composer'),
                'param_name'  => 'email_label',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Email Field Placeholder', 'js_composer'),
                'param_name'  => 'email_placeholder',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Email Field ID - HQ Integration', 'js_composer'),
                'param_name'  => 'email_hq_id',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Phone Field Label', 'js_composer'),
                'param_name'  => 'phone_label',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Phone Field Placeholder', 'js_composer'),
                'param_name'  => 'phone_placeholder',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Phone Field ID - HQ Integration', 'js_composer'),
                'param_name'  => 'phone_hq_id',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Message Field Label', 'js_composer'),
                'param_name'  => 'message_label',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Message Field Placeholder', 'js_composer'),
                'param_name'  => 'message_placeholder',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Message Field ID - HQ Integration', 'js_composer'),
                'param_name'  => 'message_hq_id',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Button Text', 'js_composer'),
                'param_name'  => 'button_text',
                'value'       => ''
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __('Form Action Url', 'js_composer'),
                'param_name'  => 'form_action',
                'value'       => ''
            )
        )
    )
);

class WPBakeryShortCode_hq_contact_form extends WPBakeryShortCode
{
    protected function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'name_label'            =>  '',
            'name_placeholder'      =>  '',
            'last_name_label'       =>  '',
            'last_name_placeholder' =>  '',
            'email_label'           =>  '',
            'email_placeholder'     =>  '',
            'phone_label'           =>  '',
            'phone_placeholder'     =>  '',
            'message_label'         =>  '',
            'message_placeholder'   =>  '',
            'button_text'           =>  '',
            'name_hq_id'            =>  '',
            'last_name_hq_id'       =>  '',
            'phone_hq_id'           =>  '',
            'email_hq_id'           =>  '',
            'message_hq_id'         =>  '',
            'form_action'           =>  ''
        ), $atts));
        ?>
            <form action="<?php echo $form_action; ?>" method="post" class="wpcf7-form">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="contact-us-label"><?php echo $message_label; ?></div>
                                    <p><span class="wpcf7-form-control-wrap message">
                                            <textarea name="<?php echo $message_hq_id; ?>" cols="40" rows="10"
                                                      class="wpcf7-form-control wpcf7-textarea" aria-invalid="false"
                                                      placeholder="<?php echo $message_placeholder; ?>">

                                            </textarea>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="contact-us-label"><?php echo $name_label; ?>*</div>
                                <p><span class="wpcf7-form-control-wrap name">
                                        <input type="text" name="<?php echo $name_hq_id; ?>" value="" size="40"
                                               class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                                               aria-invalid="false" placeholder="<?php echo $name_placeholder; ?>" required>
                                    </span>
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="contact-us-label"><?php echo $last_name_label; ?>*</div>
                                <p><span class="wpcf7-form-control-wrap name">
                                        <input type="text" name="<?php echo $last_name_hq_id; ?>" value="" size="40"
                                               class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                                               aria-invalid="false" placeholder="<?php echo $last_name_placeholder; ?>" required></span>
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="contact-us-label"><?php echo $email_label; ?>*</div>
                                <p><span class="wpcf7-form-control-wrap email">
                                        <input type="email" name="<?php echo $email_hq_id; ?>" value="" size="40"
                                               class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email"
                                               aria-required="true" aria-invalid="false"
                                               placeholder="<?php echo $email_placeholder; ?>" required></span>
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="contact-us-label"><?php echo $phone_label; ?></div>
                                <p><span class="wpcf7-form-control-wrap email">
                                        <input type="text" name="<?php echo $phone_hq_id; ?>" value="" size="40"
                                               class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email"
                                               aria-required="true" aria-invalid="false" placeholder="<?php echo $phone_placeholder; ?>"></span>
                                </p>
                            </div>
                            <div class="contact-us-submit">
                                <input type="submit" value="<?php echo $button_text; ?>" class="wpcf7-form-control wpcf7-submit contact-us-submit">
                            </div>
                        </div>
                    </div>
            </form>
        <?php
    }
}
