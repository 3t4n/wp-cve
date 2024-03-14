<?php

vc_map(array(
    'name'          => esc_html__('HQ Contact Form', 'rentit'),
    'base'          => 'hq_rentit_contact_form',
    "icon"          => HQ_MOTORS_VC_SHORTCODES_ICON, // Simply pass url to your icon here
    'description'   => esc_html__('Contact form', 'rentit'),
    'params'        => array(
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Widget title', 'rentit'),
            'param_name' => 'title'
        ),
        array(
            "type"        => "textarea",
            "holder"      => "div",
            "class"       => "",
            "heading"     => esc_html__("Enter description text", "rentit"),
            "param_name"  => "content",
            "value"       => 'This is Photoshop\'s version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. 
                                Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum',
            "description" => ''
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => esc_html__('Css', 'rentit'),
            'param_name' => 'css',
            'group'      => esc_html__('Design options', 'rentit'),
        ),
        array(
            'type'       => 'dropdown',
            'param_name' => 'show_subject',
            "heading"    => esc_html__("Show subject ?", "rentit"),
            'value'      => array(
                esc_html__('Show subject', "rentit") => '1',
                esc_html__('Hide subject', "rentit") => '0',
            ),
        ),
        array(
            'type'       => 'param_group',
            'holder'     => 'div',
            'heading'    => esc_html__('Other Contact Details', 'rentit'),
            'param_name' => 'items',
            'params'     => array(
                array(
                    'type'       => 'iconpicker',
                    'heading'    => esc_html__('Icon', 'rentit'),
                    'param_name' => 'icon',

                    'description' => esc_html__('Select icon from library.', 'rentit'),
                ),
                array(
                    'type'        => 'textfield',
                    'holder'      => 'div',
                    'heading'     => esc_html__('Text', 'rentit'),
                    'param_name'  => 'title',
                    'description' => esc_html__('Label  E.g. Adress: 1600 Pennsylvania Ave NW, Washington, D.C. ', 'rentit')
                ),
            ),
        ),
        /////////////
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Field Id Name', 'rentit'),
            'param_name' => 'name_id',
            'description' =>    'HQ Field Identification'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Field Id Email', 'rentit'),
            'param_name' => 'email_id',
            'description' =>    'HQ Field Identification'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Field Id Subject', 'rentit'),
            'param_name' => 'subject_id',
            'description' =>    'HQ Field Identification'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Field Id Message', 'rentit'),
            'param_name' => 'message_id',
            'description' =>    'HQ Field Identification'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Name Label', 'rentit'),
            'param_name' => 'name_label',
            'description' =>    'Name Field Label'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Email Label', 'rentit'),
            'param_name' => 'email_label',
            'description' =>    'Email Field Label'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Subject Label', 'rentit'),
            'param_name' => 'subject_label',
            'description' =>    'Subject Field Label'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Message Label', 'rentit'),
            'param_name' => 'message_label',
            'description' =>    'Message Field Label'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Name Placeholder', 'rentit'),
            'param_name' => 'name_placeholder',
            'description' =>    'Name Field Placeholder'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Email Placeholder', 'rentit'),
            'param_name' => 'email_placeholder',
            'description' =>    'Email Field Placeholder'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Subject Placeholder', 'rentit'),
            'param_name' => 'subject_placeholder',
            'description' =>    'Subject Field Placeholder'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Message Placeholder', 'rentit'),
            'param_name' => 'message_placeholder',
            'description' =>    'Message Field Placeholder'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Button Text', 'rentit'),
            'param_name' => 'button_text',
            'description' =>    'Submit Button Text'
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Action Link', 'rentit'),
            'param_name' => 'action',
            'description' =>    'HQ Link Form'
        ),
    ),
));

class WPBakeryShortCode_hq_rentit_contact_form extends WPBakeryShortCode
{
    /**
     * Load specific template
     * @package Rent It
     * @since Rent It 1.0
     */


    public function getFileName()
    {
        return 'rentit_vc_post_carousel_template';
    }

    protected function content($atts, $content = null)
    {
        ob_start();
        $atts    = shortcode_atts(
            array(
                'show_subject' => true,
                'css'          => '',
                'items'        => '',
                'icon'         => '',
                'name_id'      =>   '',
                'email_id'      =>  '',
                'subject_id'    =>  '',
                'message_id'    =>  '',
                'name_label'      =>   '',
                'email_label'      =>  '',
                'subject_label'    =>  '',
                'message_label'    =>  '',
                'name_placeholder'      =>   '',
                'email_placeholder'      =>  '',
                'subject_placeholder'    =>  '',
                'message_placeholder'    =>  '',
                'button_text'           =>  '',
                'action'                =>  ''
            ),
            $atts
        );
        $items_v = array();
        $atts    = vc_map_get_attributes($this->getShortcode(), $atts);
        if (function_exists('vc_param_group_parse_atts')) {
            $items_v = vc_param_group_parse_atts($atts['items']);
        }
        extract($atts);

        $css       = ( isset($atts['css']) ) ? $atts['css'] : '';
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);


        ?>

        <div class="row <?php echo esc_attr($css_class); ?>">
            <div class="col-md-6">
                <!-- Contact form -->
                <!-- Contact form -->
                <form name="contact-form" method="post" action="<?php echo $action; ?>" class="contact-form">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="outer required">
                                <div class="form-group af-inner has-icon">
                                    <label class="sr-only" for="name"><?php echo $name_label ?></label>
                                    <input
                                        type="text" name="<?php echo $name_id; ?>" id="name"
                                        placeholder="<?php echo $name_placeholder; ?>" value="" size="30"
                                        data-toggle="tooltip"
                                        title="<?php echo $name_placeholder; ?>"
                                        class="form-control placeholder"/>
                                    <span class="form-control-icon"><i class="fa fa-user"></i></span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="outer required">
                                <div class="form-group af-inner has-icon">
                                    <label class="sr-only" for="email"><?php $email_label; ?></label>
                                    <input
                                        type="text" name="<?php echo $email_id?>" id="email"
                                        placeholder="<?php echo $email_placeholder; ?>" value="" size="30"
                                        data-toggle="tooltip"
                                        title="<?php echo $email_placeholder; ?>"
                                        class="form-control placeholder"/>
                                    <span class="form-control-icon"><i class="fa fa-envelope"></i></span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php if ($atts['show_subject'] == true) : ?>
                        <div class="outer required">
                            <div class="form-group af-inner has-icon">
                                <label class="sr-only" for="subject"><?php $subject_label; ?></label>
                                <input
                                    type="text" name="<?php echo $subject_id; ?>" id="subject"
                                    placeholder="<?php echo $subject_placeholder; ?>" value="" size="30"
                                    data-toggle="tooltip"
                                    title="<?php echo $subject_placeholder; ?>"
                                    class="form-control placeholder"/>
                                <span class="form-control-icon"><i class="fa fa-bars"></i></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group af-inner has-icon">
                        <label class="sr-only" for="input-message"><?php echo $message_label; ?></label>
                        <textarea
                            name="<?php echo $message_id; ?>" id="input-message"
                            placeholder="<?php echo $message_placeholder; ?>" rows="4" cols="50"
                            data-toggle="tooltip"
                            title="<?php echo $message_placeholder; ?>"
                            class="form-control placeholder"></textarea>
                        <span class="form-control-icon"><i class="fa fa-bars"></i></span>
                    </div>

                    <div class="outer required">

                        <div class="form-group af-inner">
                            <input type="submit" name="submit"
                                   class="form-button form-button-submit btn btn-block btn-theme ripple-effect btn-theme-dark"
                                   id="submit_btn" value="<?php echo $button_text; ?>" style="max-width: 600px !important;"/>
                        </div>
                    </div>

                </form>

            </div>
            <div class="col-md-6">
                <p><?php echo wp_kses_post($content); ?></p>

                <ul class="media-list contact-list">
                    <?php
                    if ($items_v) {
                        foreach ($items_v as $item) { ?>
                            <li class="media">
                                <div class="media-left"><i class="<?php
                                if (isset($item['icon'])) {
                                    echo esc_attr($item['icon']);
                                } ?>"></i></div>
                                <div class="media-body"><?php
                                if (isset($item['title'])) {
                                    echo wp_kses_post($item['title']);
                                } ?></div>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}






