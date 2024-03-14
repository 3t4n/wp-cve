<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_contact_form_7') ) {
    class acf_contact_form_7 extends acf_field
    {
        function __construct($settings)
        {
            $this->name = 'CONTACT_FORM_7';
            $this->label = __('Contact Form 7', 'acf-contact-form-7');
            $this->category = 'content';
            $this->settings = $settings;
            parent::__construct();
        }

        function render_field($field)
        {
            $cf = WPCF7_ContactForm::find();
            ?>
            <select name="<?= esc_attr($field['name']) ?>" value="<?= esc_attr($field['value']) ?>">
                <option disabled<?php if (empty($field['value'])) { ?> selected<?php } ?>><?= __('Select form', 'acf-contact-form-7'); ?></option>
                <?php
                foreach ($cf as $form) {
                    $value = '[contact-form-7 id="'.$form->id().'" title="'.$form->title().']';?>
                    <option value='<?= $value; ?>'<?php if ($field['value']==$value) { ?> selected<?php } ?>><?= $form->title(); ?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    new acf_contact_form_7( $this->settings );
}
?>