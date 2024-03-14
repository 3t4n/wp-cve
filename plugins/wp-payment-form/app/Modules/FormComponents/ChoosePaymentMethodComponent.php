<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\App\Services\ProRoutes;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class ChoosePaymentMethodComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('choose_payment_method', 4);
        add_filter('wppayform/choose_payment_method_for_submission', array($this, 'choosePaymentMethod'), 10, 4);
    }

    public function choosePaymentMethod($paymentMethod, $elements, $formId, $form_data)
    {
        if ($paymentMethod) {
            // Already someone choose that it's their payment method
            return $paymentMethod;
        }
        $methodElement = false;
        foreach ($elements as $element) {
            if ((isset($element['type']) && $element['type'] == 'choose_payment_method')) {
                $methodElement = $element;
            }
        }
        if (!$methodElement) {
            return $paymentMethod;
        }
        $selectedPaymentMethod = Arr::get($form_data, '__wpf_selected_payment_method');
        $methods = Arr::get($methodElement, 'options.method_settings.payment_settings', array());
        foreach ($methods as $payMethod => $method) {
            if ($method['enabled'] == 'yes') {
                if ($payMethod == $selectedPaymentMethod) {
                    return $payMethod;
                }
            }
        }

        return $paymentMethod;
    }

    public function component()
    {
        $available_methods = apply_filters('wppayform/available_payment_methods', array());

        if (!$available_methods || count($available_methods) < 2) {
            return;
        }

        return array(
            'type' => 'choose_payment_method',
            'editor_title' => 'Choose Payment Method',
            'group' => 'payment_method_element',
            'conditional_hide' => true,
            'postion_group' => 'payment_method',
            'single_only' => true,
            'is_pro' => 'no',
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'default_payment_method' => array(
                    'type' => 'text',
                    'group' => 'general',
                ),
                'method_settings' => array(
                    'label' => 'Payment Methods',
                    'type' => 'choose_payment_method',
                    'available_methods' => $available_methods,
                    'group' => 'general'
                ),
                'enable_image' => array(
                    'label' => 'Enable image',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'admin_label' => array(
                    'label' => 'Admin Label',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
            ),
            'field_options' => array(
                'label' => __('Select Payment Method', 'wp-payment-form'),
                'enable_image' => 'yes',
                'method_settings' => array(
                    'prefered_method' => '',
                    'payment_settings' => array(
                        'stripe' => array(
                            'enabled' => 'yes',
                            'checkKey' => 'Credit/Debit Card (Stripe)',
                            'logo' => WPPAYFORM_URL . 'assets/images/gateways/stripe.svg',
                            'label' => 'Pay with Card (Stripe)',
                        )
                    )
                )
            )
        );
    }

    public function render($element, $form, $elements)
    {
        $fieldOption = Arr::get($element, 'field_options');
        $enabled_image = Arr::get($element, 'field_options.enable_image');
        $controlAttributes = array(
            'id' => 'wpf_' . $this->elementName,
            'data-element_type' => $this->elementName,
            'class' => $this->elementControlClass($element)
        );
        $methods = Arr::get($fieldOption, 'method_settings.payment_settings', array());
        $available_methods = apply_filters('wppayform_payment_method_settings', ProRoutes::getMethods());
        $validMethods = array();
        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOption, 'default_value'), $element, $form);
        $lastPaymentMethod = '';
        foreach ($methods as $methodName => $method) {
            if (isset($method['enabled']) && $method['enabled'] == 'yes') {
                $logo = Arr::get($available_methods, $methodName . '.svg');
                $method['logo'] = $logo;
                $lastPaymentMethod = $methodName;
                $validMethods[$methodName] = $method;
            }
        }
        echo '<input type="hidden" name="__wpf_valid_payment_methods_count" value="' . count($validMethods) . '"/>';

        if ($validMethods && count($validMethods) > 1) :
?>
            <div <?php $this->printAttributes($controlAttributes); ?>>
                <?php $this->buildLabel($fieldOption, $form); ?>
                <div class="wpf_multi_form_controls wpf_input_content">
                    <?php if ($enabled_image === 'yes') {
                    ?>
                    <div class="wpf-radio-with-Icon">
                        <ul style="list-style-type: none;">
                            <?php
                            foreach ($validMethods as $methodName => $method) :
                                $optionId = $element['id'] . '_' . $methodName . '_' . $form->ID;
                                $attributes = array(
                                    'class' => 'form-check-input',
                                    'type' => 'radio',
                                    'name' => '__wpf_selected_payment_method',
                                    'id' => $optionId,
                                    'value' => $methodName,
                                    'required' => true
                                );
                                $file_path = Arr::get($method, 'logo');
                                if ($methodName == $defaultValue) {
                                    $attributes['checked'] = 'true';
                                } ?>

                                <li><input <?php $this->printAttributes($attributes); ?> />
                                    <label for="<?php echo esc_attr($optionId); ?>"><img src="<?php echo esc_url($file_path); ?>" alt="<?php echo esc_html($methodName)?>"/></label>
                                </li>
                            <?php endforeach;
                            ?>
                        </ul>
                    </div>
                        <?php
                            } else { ?>

                        <?php foreach ($validMethods as $methodName => $method) : ?>
                            <?php
                                    $optionId = $element['id'] . '_' . $methodName . '_' . $form->ID;
                                    $attributes = array(
                                        'class' => 'form-check-input',
                                        'type' => 'radio',
                                        'name' => '__wpf_selected_payment_method',
                                        'id' => $optionId,
                                        'value' => $methodName,
                                        'required' => true
                                    );
                                    if ($methodName == $defaultValue) {
                                        $attributes['checked'] = 'true';
                                    } ?>
                            <div class="form-check">
                                <input <?php $this->printAttributes($attributes); ?>>
                                <label class="form-check-label" for="<?php echo esc_attr($optionId); ?>">
                                    <?php echo wp_kses_post($method['label']); ?>
                                </label>
                            </div>
                    <?php endforeach;
                            } ?>
                </div>
            </div>
        <?php else : ?>
            <input data-wpf_payment_method="<?php echo esc_attr($lastPaymentMethod); ?>" type="hidden" name="__wpf_selected_payment_method" value="<?php echo esc_attr($lastPaymentMethod); ?>" />
        <?php endif; ?>
        <div class="wpf_all_payment_methods_wrapper">
            <?php foreach ($validMethods as $methodName => $method) : ?>
                <div data-payment_method="<?php echo esc_attr($methodName); ?>" class="wpf_payment_method_element wpf_payment_method_element_<?php echo esc_attr($methodName); ?>">
                    <?php do_action('wppayform/payment_method_choose_element_render_' . $methodName, $method, $form, $elements); ?>
                </div>
            <?php endforeach; ?>
        </div>
<?php
    }
}
