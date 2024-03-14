<?php

namespace WPPayForm\App\Modules\Builder;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Http\Controllers\FormController;
use WPPayForm\App\Models\DemoForms;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Render
{

    protected $elementorPopUpHandler = false;

    public function render($formId, $show_title = false, $show_description = false)
    {
        $form = Form::getForm($formId);
        if (!$form) {
            return;
        }

        if ($show_title) {
            $form->show_title = $show_title;
        }
        if ($show_description) {
            $form->show_description = $show_description;
        }
        if (!$show_title || !$show_description) {
            $titleDescription = get_post_meta($formId, 'wppayform_show_title_description', true);
            $form->show_title = $titleDescription;
            $form->show_description = $titleDescription;
        }
        $form->scheduleing_settings = Form::getSchedulingSettings($formId);

        $elements = Form::getBuilderSettings($formId);

        $form->designSettings = Form::getDesignSettings($formId);
        $form->asteriskPosition = $form->designSettings['asteriskPlacement'];

        $form->recaptchaType = Form::recaptchaType($form->ID);

        if ($form->recaptchaType) {
            $recaptchaSettings = GeneralSettings::getRecaptchaSettings();
            $form->recaptcha_site_key = $recaptchaSettings['site_key'];
        }

        $form->turnstile_status = Form::turnstileStatus($form->ID);

        if ($form->turnstile_status) {
            $turnstileSettings = GeneralSettings::getTurnstileSettings();
            $form->turnstile_site_key = $turnstileSettings['siteKey'];
            $form->turnstile_secret_key = $turnstileSettings['secretKey'];
        }

        $this->registerScripts($form);
        $this->elementorPopupScripts();

        ob_start();

        if ($elements) {
            $isStepForm = $this->checkIsStepForm($elements);
            if (!empty($isStepForm)) {

                $class = '';
                if (count($isStepForm['editor_elements']['form_steps']) > 6) {
                    $class = 'justify-start';
                }
?> <div id="wpf_svg_wrap">
                    <div class="step-form <?php echo esc_attr($class); ?>">
                        <?php
                        foreach ($isStepForm['editor_elements']['form_steps'] as $key => $step) {
                        ?>
                            <div id="<?php echo intval($key) + 1; ?>" class="step-form-item">
                                <div class="step-form-item-header">
                                    <span id="<?php echo intval($key) + 1; ?>" class="number wpf-step-header-btn"><?php echo intval($key) + 1; ?></span>
                                </div>
                                <div class="step-form-item-content">
                                    <h2><?php echo esc_html($step['title']); ?></h2>
                                    <p><?php echo esc_textarea($step['description']); ?></p>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    $step_elements = $this->sortElemntsByActivePage($elements);
                    foreach ($isStepForm['editor_elements']['form_steps'] as $key => $elements) {
                        $className = "wpf_step_section_" . ($key + 1);
                    ?>
                        <section class="wpf_step_section" id="<?php echo esc_attr($className); ?>">
                            <?php
                            if (!empty($step_elements[$key])) {
                                foreach (Arr::get($step_elements, $key) as $element) {
                                    if ($element['type'] != 'step_form') {
                                        do_action('wppayform/render_component_' . $element['type'], $element, $form, $elements);
                                    }
                                }
                            }
                            // check if this is last page
                            if (sizeof($isStepForm['editor_elements']['form_steps']) == $key + 1) {
                                $this->renderFormFooter($form, empty($isStepForm));
                            }
                            if (sizeof($isStepForm['editor_elements']['form_steps']) == $key + 1) {
                            ?>
                                <button class="wpf_step_button" id="wpf_step_prev">&larr; <?php echo __('Previous', 'wp-payment-form') ?></button>
                                <button class="wpf_step_button" id="wpf_step_next"><?php echo __('Next', 'wp-payment-form') ?> &rarr;</button>
                                <div style="display: none" class="wpf_form_notices"></div> <?php
                                                                                        }
                                                                                            ?>

                        </section>
                    <?php
                    }
                    ?>
                </div>
        <?php
            } else {
                foreach ($elements as $element) {
                    do_action('wppayform/render_component_' . $element['type'], $element, $form, $elements);
                }
            }
            $form_body = ob_get_clean();
        } else {
            return "<p style='color:red;font-size: 16px;'>Notice: Please add some fields on ($form->post_title)</p>";
        }

        $instanceCssClass = Helper::getFormInstaceClass($form->ID);

        ob_start();
        $this->renderFormHeader($form, $instanceCssClass);
        $header_html = ob_get_clean();
        $formFooter = '';
        if (empty($isStepForm)) {
            ob_start();
            $this->renderFormFooter($form, empty($isStepForm));
            $formFooter = ob_get_clean();
        }
        $localize_form_instance = $this->getConditionals($this->getInstanceSettings($form, $instanceCssClass));
        $this->addAssets($form, $instanceCssClass);

        wp_localize_script('wppayform_public', 'wp_payform_' . $instanceCssClass,  $localize_form_instance, $form);

        $html = $header_html . $form_body . $formFooter;

        return apply_filters('wppayform/rendered_form_html', $html, $form);
    }

    private function checkIsStepForm($elements)
    {
        foreach ($elements as $element) {
            if ($element['type'] == 'step_form') {
                return $element;
            }
        }
        return [];
    }

    private function sortElemntsByActivePage($elements)
    {
        $sortedElements = [];
        foreach ($elements as $element) {
            $sortedElements[$element['active_page']][] = $element;
        }
        return $sortedElements;
    }

    public function getConditionals($form_data)
    {
        //saved conditionals
        $conditions = [];
        $elements = Form::getBuilderSettings($form_data['form_id']);
        foreach ($elements as $element) {
            $condition = Arr::get($element, 'field_options.conditional_logic_option');
            if ($condition != null) {
                $conditions[$element['id']] = $condition;
            }
        }
        $form_data['conditional_logic'] = $conditions;
        return $form_data;
    }


    public function getInstanceSettings($form, $instanceCssClass)
    {
        $currencySettings = Form::getCurrencyAndLocale($form->ID);
        $formController = new FormController();
        $cachingInterval = 24;
        $rates = [];

        if (isset($currencySettings['currency_rate_caching_interval'])) {
            $cachingInterval = Arr::get($currencySettings, 'currency_rate_caching_interval');
        }

        if (isset($currencySettings['currency_conversion_api_key'])) {
            $rates = $formController->getCurrencyRates($currencySettings['currency'], $currencySettings['currency_conversion_api_key'], $cachingInterval, $form->ID);
        }

        return $formIndivisuals = apply_filters('wppayform/checkout_vars', array(
            'form_id' => $form->ID,
            'checkout_description' => $form->post_title,
            'currency_settings' => $currencySettings,
            'rates' => $rates,
        ));
    }

    public function renderFormHeader($form, $instanceCssClass)
    {
        global $wp;
        $currentUrl = home_url(add_query_arg($_GET, $wp->request));
        $labelPlacement = $form->designSettings['labelPlacement'];
        $btnPosition = Arr::get($form->designSettings, 'submit_button_position');

        $extraCssClasses = array_keys(array_filter($form->designSettings['extra_styles'], function ($value) {
            return $value == 'yes';
        }));

        $css_classes = array(
            'wpf_form',
            $instanceCssClass,
            'wpf_strip_default_style',
            'wpf_form_id_' . $form->ID,
            'wpf_label_' . $labelPlacement,
            'wpf_asterisk_' . $form->asteriskPosition,
            'wpf_submit_button_pos_' . $btnPosition
        );

        $hasPaymentField = get_post_meta($form->ID, 'wpf_has_payment_field', true) == 'yes';
        if ($hasPaymentField) {
            $css_classes[] = 'wppayform_has_payment';
        }

        if ($form->recaptchaType) {
            $css_classes[] = 'wpf_has_recaptcha wpf_recaptcha_' . $form->recaptchaType;
        }

        if ($form->turnstile_status) {
            $css_classes[] = 'wpf_has_turnstile wpf_turnstile_' . $form->ID;
        }


        $css_classes = array_merge($css_classes, $extraCssClasses);

        if ($labelPlacement != 'top') {
            $css_classes[] = 'wpf_inline_labels';
        }

        $css_classes = apply_filters('wppayform/form_css_classes', $css_classes, $form);

        $formAttributes = array(
            'data-wpf_form_id' => $form->ID,
            'wpf_form_instance' => $instanceCssClass,
            'class' => implode(' ', $css_classes),
            'method' => 'POST',
            'action' => site_url(),
            'id' => "wpf_form_id_" . $form->ID
        );

        if ($form->recaptchaType) {
            $formAttributes['data-recaptcha_site_key'] = $form->recaptcha_site_key;
            if ($form->recaptchaType == 'v2_visible') {
                $formAttributes['data-recaptcha_version'] = 'v2';
            } else {
                $formAttributes['data-recaptcha_version'] = 'v3';
            }
        }

        if ($form->turnstile_status) {
            $formAttributes['data-turnstile_siteKey'] = $form->turnstile_site_key;
        }

        $formAttributes = apply_filters('wppayform/form_attributes', $formAttributes, $form);
        $formWrapperClasses = apply_filters('wppayform/form_wrapper_css_classes', array(
            'wpf_form_wrapper',
            'wpf_form_wrapper_' . $form->ID
        ), $form); ?>
        <div class="<?php echo esc_attr(implode(' ', $formWrapperClasses)); ?>">
            <?php if ($form->show_title == 'yes') : ?>
                <h3 class="wp_form_title"><?php echo esc_html($form->post_title); ?></h3>
            <?php endif; ?>
            <?php if ($form->show_description == 'yes') : ?>
                <div class="wpf_form_description">
                    <?php echo do_shortcode($form->post_content); ?>
                </div>
            <?php endif; ?>
            <?php do_action('wppayform/form_render_before', $form); ?>
            <form <?php $this->printAttributes($formAttributes); ?>>
                <?php do_action('wppayform/form_element_start', $form); ?>
                <input type="hidden" name="__wpf_form_id" value="<?php echo intval($form->ID); ?>" />
                <input type="hidden" name="__wpf_current_url" value="<?php echo esc_url($currentUrl); ?>">
                <input type="hidden" name="__wpf_current_page_id" value="<?php echo intval(get_the_ID()); ?>">
                <?php do_action('wppayform/form_render_start_form', $form); ?>
            <?php
        }

        public function renderFormFooter($form, $ste_form_is = false)
        {
            $submitButton = Form::getButtonSettings($form->ID);
            $processingText = $submitButton['processing_text'];
            if (!$processingText) {
                $processingText = __('Please Wait…', 'wp-payment-form');
            }
            $button_text = $submitButton['button_text'];
            if (!$button_text) {
                $button_text = __('Submit', 'wp-payment-form');
            }
            $buttonClasses = array(
                'wpf_submit_button',
                $submitButton['css_class'],
                $submitButton['button_style']
            );
            $buttonAttributes = apply_filters('wppayform/submit_button_attributes', array(
                'id' => 'stripe_form_submit_' . $form->ID,
                'disabled' => true,
                'class' => implode(' ', array_unique($buttonClasses))
            ), $form); ?>
                <?php do_action('wppayform/form_render_before_submit_button', $form); ?>

                <?php if ($form->recaptchaType) : ?>
                    <div class="wpf_form_group wpf_form_recaptcha">
                        <div id="wpf_recaptcha_<?php echo intval($form->ID); ?>"></div>
                    </div>
                <?php endif; ?>

                <?php if ($form->turnstile_status) : ?>
                    <div class="wpf_form_group wpf_form_turnstile">
                        <div data-sitekey="<?php echo esc_attr($form->turnstile_site_key); ?>" id="wpf-turnstile_<?php echo intval($form->ID); ?>" class='wpf-el-turnstile cf-turnstile' data-callback='turnstileCallback'>
                        </div>
                    </div>
                <?php endif ?>

                <div class="wpf_form_group wpf_form_submissions" style="margin-top: 20px;">
                    <button <?php $this->printAttributes($buttonAttributes); ?>>
                        <span class="wpf_txt_normal"><?php wpPayFormPrintInternal($this->parseText($button_text, $form->ID)); ?></span>
                        <span style="display: none;" class="wpf_txt_loading">
                            <?php wpPayFormPrintInternal($this->parseText($processingText, $form->ID)); ?>
                        </span>
                    </button>
                    <div class="wpf_loading_svg">
                        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
                            <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946 s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634 c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z" />
                            <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0 C22.32,8.481,24.301,9.057,26.013,10.047z">
                                <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite" />
                            </path>
                        </svg>
                    </div>
                </div>
                <?php do_action('wppayform/form_render_after_submit_button', $form); ?>
            </form>
            <?php
            if ($ste_form_is) {
            ?>
                <div style="display: none" class="wpf_form_notices"></div>
            <?php
            }
            ?>
            <?php do_action('wppayform/form_render_after', $form); ?>
            <?php do_action('wppayform/form_render_after_' . $form->ID, $form); ?>
        </div>

        <?php
            if ($form->recaptchaType) {
                if (!did_action('wpf_added_recaptcha_script')) {
                    if ($form->recaptchaType == 'v3_invisible') {
                        $key = $form->recaptcha_site_key;
                        $src = 'https://www.google.com/recaptcha/api.js?render=' . $key . '&onload=wpf_onload_recaptcha_callback';
                    } else {
                        $src = 'https://www.google.com/recaptcha/api.js?onload=wpf_onload_recaptcha_callback&render=explicit';
                    }

                    add_action('wp_footer', function () use ($src) {
        ?>
                    <script src="<?php echo esc_url($src); ?>" async defer></script>
                <?php
                    }, 11);
                    do_action('wpf_added_recaptcha_script');
                }
            }


            if ($form->turnstile_status) {
                if (!did_action('wpf_added_turnstile_script')) {
                    $key = $form->turnstile_site_key;
                    $src = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
                } else {
                    $src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
                }

                add_action('wp_footer', function () use ($src) {
                ?>
                <script src="<?php echo esc_url($src); ?>" async defer></script>
            <?php
                }, 12);
                do_action('wpf_added_turnstile_script');
            }
        }

        private function addAssets($form, $instanceCssClass)
        {
            $submitButton = Form::getButtonSettings($form->ID);
            $isQuickCheckoutForm  = DemoForms::checkFormCategory($form->post_name, 'Quick checkout');
            $processingText = $submitButton['processing_text'];
            $currencySymbols = GeneralSettings::getCurrencySymbols();
            wp_enqueue_script('wppayform_public', WPPAYFORM_URL . 'assets/js/payforms-publicv2.js', array('jquery'), WPPAYFORM_VERSION, true);
            if ($isQuickCheckoutForm) {
                wp_enqueue_style('wppayform_public', WPPAYFORM_URL . 'assets/css/quick_checkout.css', array(), WPPAYFORM_VERSION);
            } else {
                wp_enqueue_style('wppayform_public', WPPAYFORM_URL . 'assets/css/payforms-public.css', array(), WPPAYFORM_VERSION);
            }

            wp_localize_script('wppayform_public', 'wp_payform_general', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'date_i18n' => array(
                    'previousMonth' => __('Previous Month', 'wp-payment-form'),
                    'nextMonth' => __('Next Month', 'wp-payment-form'),
                    'months' => [
                        'shorthand' => [
                            __('Jan', 'wp-payment-form'),
                            __('Feb', 'wp-payment-form'),
                            __('Mar', 'wp-payment-form'),
                            __('Apr', 'wp-payment-form'),
                            __('May', 'wp-payment-form'),
                            __('Jun', 'wp-payment-form'),
                            __('Jul', 'wp-payment-form'),
                            __('Aug', 'wp-payment-form'),
                            __('Sep', 'wp-payment-form'),
                            __('Oct', 'wp-payment-form'),
                            __('Nov', 'wp-payment-form'),
                            __('Dec', 'wp-payment-form')
                        ],
                        'longhand' => [
                            __('January', 'wp-payment-form'),
                            __('February', 'wp-payment-form'),
                            __('March', 'wp-payment-form'),
                            __('April', 'wp-payment-form'),
                            __('May', 'wp-payment-form'),
                            __('June', 'wp-payment-form'),
                            __('July', 'wp-payment-form'),
                            __('August', 'wp-payment-form'),
                            __('September', 'wp-payment-form'),
                            __('October', 'wp-payment-form'),
                            __('November', 'wp-payment-form'),
                            __('December', 'wp-payment-form')
                        ]
                    ],
                    'weekdays' => [
                        'longhand' => array(
                            __('Sunday', 'wp-payment-form'),
                            __('Monday', 'wp-payment-form'),
                            __('Tuesday', 'wp-payment-form'),
                            __('Wednesday', 'wp-payment-form'),
                            __('Thursday', 'wp-payment-form'),
                            __('Friday', 'wp-payment-form'),
                            __('Saturday', 'wp-payment-form')
                        ),
                        'shorthand' => array(
                            __('Sun', 'wp-payment-form'),
                            __('Mon', 'wp-payment-form'),
                            __('Tue', 'wp-payment-form'),
                            __('Wed', 'wp-payment-form'),
                            __('Thu', 'wp-payment-form'),
                            __('Fri', 'wp-payment-form'),
                            __('Sat', 'wp-payment-form')
                        )
                    ],
                    'daysInMonth' => [
                        31,
                        28,
                        31,
                        30,
                        31,
                        30,
                        31,
                        31,
                        30,
                        31,
                        30,
                        31
                    ],
                    'rangeSeparator' => __(' to ', 'wp-payment-form'),
                    'weekAbbreviation' => __('Wk', 'wp-payment-form'),
                    'scrollTitle' => __('Scroll to increment', 'wp-payment-form'),
                    'toggleTitle' => __('Click to toggle', 'wp-payment-form'),
                    'amPM' => [
                        __('AM', 'wp-payment-form'),
                        __('PM', 'wp-payment-form')
                    ],
                    'yearAriaLabel' => __('Year', 'wp-payment-form')
                ),
                'i18n' => array(
                    'verify_recaptcha' => __('Please verify reCAPTCHA first', 'wp-payment-form'),
                    'verify_turnstile' => __('Please verify cloudflare turnstile first', 'wp-payment-form'),
                    'submission_error' => __('Something is wrong when submitting the form', 'wp-payment-form'),
                    'is_required' => __('is required', 'wp-payment-form'),
                    'validation_failed' => __('Validation failed, please fill-up required fields', 'wp-payment-form'),
                    'button_state' => $processingText,

                ),
                'currency_symbols' => $currencySymbols,
                'has_pro' => defined('WPPAYFORMHASPRO') && WPPAYFORMHASPRO
            ));
        }

        private function registerScripts($form)
        {
            do_action('wppayform/wppayform_adding_assets', $form);
            wp_register_script('flatpickr', WPPAYFORM_URL . 'assets/libs/flatpickr/flatpickr.min.js', array(), '4.5.7', true);
            wp_register_style('flatpickr', WPPAYFORM_URL . 'assets/libs/flatpickr/flatpickr.min.css', array(), '4.5.7', 'all');

            wp_register_script('dropzone', WPPAYFORM_URL . 'assets/libs/dropzone/dropzone.min.js', array('jquery'), '5.5.0', true);
            wp_register_script('wppayform_file_upload', WPPAYFORM_URL . 'assets/js/fileupload.js', array('jquery', 'wppayform_public', 'dropzone'), WPPAYFORM_VERSION, true);

            wp_register_style('intlTelInput', WPPAYFORM_URL . 'assets/libs/intl-tel-input/css/intlTelInput.min.css', array(), '16.0.0', 'all');
            wp_register_script('intlTelInput', WPPAYFORM_URL . 'assets/libs/intl-tel-input/js/intlTelInput.min.js', array('jquery'), '4.6.7', true);
            wp_register_script('intlTelInputUtils', WPPAYFORM_URL . 'assets/libs/intl-tel-input/js/utils.js', array('jquery'), '4.6.7', true);
        }

        private function elementorPopupScripts()
        {
            if (!defined('ELEMENTOR_PRO_VERSION') || $this->elementorPopUpHandler) {
                return '';
            }

            // Previously this handler was registered for every form
            // in a single page. So multiple event handlers were
            // registered. This flag ensures the handler is
            // registered just once, since one is enough!
            $this->elementorPopUpHandler = true;

            $actionName = 'wp_footer';
            if (is_admin()) {
                $actionName = 'admin_footer';
            }

            add_action($actionName, function () {
            ?>
            <script type="text/javascript">
                <?php if (defined('ELEMENTOR_PRO_VERSION')) : ?>
                    jQuery(document).on('elementor/popup/show', function(event, id, instance) {
                        var wpf = jQuery('#elementor-popup-modal-' + id).find('form.wpf_form');
                        if (wpf.length) {
                            jQuery.each(wpf, function(index, wppform) {
                                jQuery(document).trigger('wpf_reinit', [wppform]);
                            });
                        }
                    });
                <?php endif; ?>
            </script>
<?php
            }, 999);
            return '';
        }


        private function parseText($text, $formId)
        {
            return str_replace(
                array(
                    '{sub_total}',
                    '{tax_total}',
                    '{payment_total}'
                ),
                array(
                    '<span class="wpf_calc_sub_total"></span>',
                    '<span class="wpf_calc_tax_total"></span>',
                    '<span class="wpf_calc_payment_total"></span>',
                ),
                $text
            );
        }

        private function builtAttributes($attributes)
        {
            $atts = ' ';
            foreach ($attributes as $attributeKey => $attribute) {
                $atts .= $attributeKey . "='" . $attribute . "' ";
            }
            return $atts;
        }

        private function printAttributes($attributes)
        {
            echo ' ';
            foreach ($attributes as $attributeKey => $attribute) {
                if (is_array($attribute)) {
                    $attribute = json_encode($attribute);
                }
                echo esc_attr($attributeKey) . "='" . htmlspecialchars($attribute, ENT_QUOTES) . "' ";
            }
        }
    }
