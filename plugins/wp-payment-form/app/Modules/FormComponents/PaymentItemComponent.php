<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\App\Models\Form;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class PaymentItemComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('payment_item', 1);
        add_filter('wppayform/validate_component_on_save_payment_item', array($this, 'validateOnSave'), 1, 3);
    }

    public function component()
    {
        return array(
            'type' => 'payment_item',
            'editor_title' => 'Payment Item',
            'group' => 'payment',
            'is_pro' => 'no',
            'postion_group' => 'payment',
            'isNumberic' => 'yes',
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'enable_image' => array(
                    'label' => 'Enable Image',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'payment_options' => array(
                    'type' => 'payment_options',
                    'group' => 'general',
                    'label' => 'Configure Payment Item',
                    'selection_type' => 'Payment Type',
                    'selection_type_options' => array(
                        'one_time' => 'One Time Payment',
                        'one_time_custom' => 'One Time Custom Amount'
                    ),
                    'one_time_field_options' => array(
                        'single' => 'Single Item',
                        'choose_single' => 'Choose One From Multiple Item',
                        'choose_multiple' => 'Choose Multiple Items'
                    )
                ),
                'conditional_render' => array(
                    'type' => 'conditional_render',
                    'group' => 'advanced',
                    'label' => 'Conditional render',
                    'selection_type' => 'Conditional logic',
                    'conditional_logic' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    ),
                    'conditional_type' => array(
                        'any' => 'Any',
                        'all' => 'All'
                    ),
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
                )
            ),
            'is_system_field' => true,
            'is_payment_field' => true,
            'field_options' => array(
                'disable' => false,
                'label' => 'Payment Item',
                'required' => 'no',
                'enable_image' => 'no',
                'conditional_logic_option' => array(
                    'conditional_logic' => 'no',
                    'conditional_type'  => 'any',
                    'options' => array(
                        array(
                            'target_field' => '',
                            'condition' => '',
                            'value' => ''
                        )
                    ),
                ),
                'pricing_details' => array(
                    'one_time_type' => 'single',
                    'payment_amount' => '10.00',
                    'show_onetime_labels' => 'yes',
                    'image_url' => array(
                        array(
                            'label' => '',
                            'value' => ''
                        )
                    ),
                    'multiple_pricing' => array(
                        array(
                            'label' => '',
                            'value' => ''
                        )
                    ),
                    'prices_display_type' => 'radio',
                )
            )
        );
    }

    public function validateOnSave($error, $element, $formId)
    {
        $pricingDetails = Arr::get($element, 'field_options.pricing_details', array());
        $paymentType = Arr::get($pricingDetails, 'one_time_type');
        if ($paymentType == 'single') {
            if (!Arr::get($pricingDetails, 'payment_amount')) {
                $error = __('Payment amount is required for item:', 'wp-payment-form') . ' ' . Arr::get($element, 'field_options.label');
            }
        } elseif ($paymentType == 'choose_multiple' || $paymentType == 'choose_single') {
            if (!count(Arr::get($pricingDetails, 'multiple_pricing', array()))) {
                $error = __('Pricing Details is required for item:', 'wp-payment-form') . ' ' . Arr::get($element, 'field_options.label');
            }
        }
        return $error;
    }

    public function render($element, $form, $elements)
    {
        $disable = Arr::get($element, 'field_options.disable', false);
        $has_pro = defined('WPPAYFORMHASPRO') && WPPAYFORMHASPRO;
        $pricingDetails = Arr::get($element, 'field_options.pricing_details', array());
        if (!$pricingDetails || $disable) {
            return;
        }

        $element['field_options']['default_value'] = apply_filters('wppayform/input_default_value', Arr::get($element['field_options'], 'default_value'), $element, $form);

        $paymentType = Arr::get($pricingDetails, 'one_time_type');
        if ($paymentType == 'single') {
            $this->renderSingleAmount($element, $form, $has_pro, Arr::get($pricingDetails, 'payment_amount'));
            return;
        } elseif ($paymentType == 'choose_single') {
            $displayType = Arr::get($pricingDetails, 'prices_display_type', 'radio');
            $this->renderSingleChoice(
                $displayType,
                $element,
                $form,
                $has_pro,
                Arr::get($pricingDetails, 'multiple_pricing', array())
            );
        } elseif ($paymentType == 'choose_multiple') {
            $this->chooseMultipleChoice(
                $element,
                $form,
                $has_pro,
                Arr::get($pricingDetails, 'multiple_pricing', array())
            );
        }
    }

    public function renderSingleAmount($element, $form, $has_pro, $amount = false)
    {
        $enableImage = Arr::get($element, 'field_options.enable_image') == 'yes';
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        $showTitle = Arr::get($element, 'field_options.pricing_details.show_onetime_labels') == 'yes';
        $imageUrl = Arr::get($element, 'field_options.pricing_details.image_url');
        $wpf_has_condition = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'wpf_has_condition' : '';
        $displayValue = $has_pro === true ? $hiddenAttr : '';
?>
        <div single-paymen-item="<?php echo esc_attr($element['id']); ?>" style="display : <?php echo esc_attr($displayValue); ?>" class="<?php echo esc_attr($wpf_has_condition); ?>">
            <?php
            if ($enableImage && is_array($imageUrl)) {
                foreach ($imageUrl as $item) {
            ?>
                    <div class='imageContainer'>
                        <div class="wpf_tabular_product_photo">
                            <?php wpPayFormPrintInternal($this->renderImage($item['photo'])); ?>
                        </div>
                    </div>
                <?php
                };
            };
            $customname = Arr::get($element, 'field_options.label');
            if ($showTitle) {
                $title = Arr::get($element, 'field_options.label');
                $currenySettings = Form::getCurrencyAndLocale($form->ID);
                $controlAttributes = array(
                    'data-element_type' => $this->elementName,
                    'required_id' => $element['id'],
                    'class' => $this->elementControlClass($element)
                ); ?>
                <div <?php $this->printAttributes($controlAttributes); ?>>
                    <div class="wpf_input_label wpf_single_amount_label">
                        <?php echo wp_kses_post($title); ?>: <span name=<?php echo esc_attr($element['id']); ?> class="wpf_single_amount"><?php echo wpPayFormFormattedMoney(wpPayFormConverToCents($amount), $currenySettings); ?></span>
                    </div>
                </div>
            <?php
            }
            echo '<input customname= ' . esc_attr($customname) . ' name=' . esc_attr($element['id']) . ' type="hidden" class="wpf_payment_item" data-price="' . wpPayFormConverToCents($amount) . '" value="' . esc_attr($amount) . '" />';
            ?>
        </div>
    <?php
    }


    private function renderImage($image, $lightboxed = false)
    {
        if (!$image) {
            return '';
        }

        $thumb = Arr::get($image, 'image_thumb');
        $imageFull = Arr::get($image, 'image_full');
        $altText = Arr::get($image, 'alt_text');

        if (!$thumb) {
            return '';
        }

        if ($lightboxed) {
            return '<a class="wpf_lightbox" href="' . esc_url($imageFull) . '"><img src="' . esc_url($thumb) . '" alt="' . esc_attr($altText) . '" /></a>';
        }
        return '<img src="' . esc_url($thumb) . '" alt="' . esc_attr($altText) . '" style="border-radius: 5px; width: 80px; margin-bottom:10px;"/>';
    }

    public function renderSingleChoice($type, $element, $form, $has_pro, $prices = array())
    {
        if (!$type || !$prices) {
            return;
        }
        $fieldOptions = Arr::get($element, 'field_options', false);
        $conditional_logic = $fieldOptions['conditional_logic_option']['conditional_logic'];
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        $enableImage = Arr::get($element, 'field_options.enable_image') == 'yes';
        $displayValue = $has_pro === true ? $hiddenAttr : '';
        $currenySettings = Form::getCurrencyAndLocale($form->ID);
        $elementId = 'wpf_' . $element['id'];
        $controlAttributes = array(
            'data-element_type' => $this->elementName,
            'data-required_element' => $type,
            'data-required' => Arr::get($fieldOptions, 'required'),
            'data-target_element' => $element['id'],
            'required_id' => $element['id'],
            'class' => $this->elementControlClass($element)
        );
        $defaultValue = Arr::get($fieldOptions, 'default_value'); ?>
        <div style="display : <?php echo esc_attr($displayValue); ?>" <?php $this->printAttributes($controlAttributes); ?>>
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $elementId)); ?>
            <?php if ($type == 'select') : ?>
                <?php
                $placeholder = '--Select--';
                $inputId = 'wpf_input_' . $form->ID . '_' . $this->elementName;
                $inputAttributes = array(
                    'data-required' => Arr::get($fieldOptions, 'required'),
                    'data-type' => 'select',
                    'name' => $element['id'],
                    'class' => $this->elementInputClass($element) . ' wpf_payment_item',
                    'id' => $inputId,
                ); ?>
                <div class="wpf_multi_form_controls wpf_input_content wpf_multi_form_controls_select">
                    <select <?php $this->printAttributes($inputAttributes); ?>>
                        <?php if ($placeholder) : ?>
                            <option data-type="placeholder" value=""><?php echo esc_attr($placeholder); ?></option>
                        <?php endif; ?>
                        <?php foreach ($prices as $index => $price) : ?>
                            <?php
                            $optionAttributes = array(
                                'value' => $index,
                                'data-price' => wpPayFormConverToCents($price['value']),
                                'customname' =>  $price['label']
                            );
                            if ($defaultValue == $price['label']) {
                                $optionAttributes['selected'] = 'true';
                            } ?>
                            <option <?php $this->printAttributes($optionAttributes); ?>><?php echo esc_attr($price['label']); ?>
                                (<?php echo esc_html(wpPayFormFormattedMoney(wpPayFormConverToCents($price['value']), $currenySettings)); ?>
                                )
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else : ?>
                <div class="wpf_multi_form_controls wpf_input_content wpf_multi_form_controls_radio">
                    <?php foreach ($prices as $index => $price) : ?>
                        <?php
                        $image_len = strlen(Arr::get($price, 'photo.image_full'));
                        $optionId = $element['id'] . '_' . $index . '_' . $form->ID;
                        $attributes = array(
                            'class' => 'form-check-input wpf_payment_item',
                            'type' => 'radio',
                            'data-price' => wpPayFormConverToCents($price['value']),
                            'name' => $element['id'],
                            'id' => $optionId,
                            'value' => $index,
                            'customname' => $price['label']
                        );

                        if ($price['label'] == $defaultValue) {
                            $attributes['checked'] = 'true';
                        }
                        if ($enableImage && $image_len > 0) : ?>
                            <div class="wpf_tabular_product_photo" style='margin-top:10px;'>
                                <?php wpPayFormPrintInternal($this->renderImage($price['photo'])); ?>
                            </div>
                            <!-- </div> -->
                        <?php endif; ?>
                        <div class="form-check">
                            <input <?php $this->printAttributes($attributes); ?>>
                            <label class="form-check-label" for="<?php echo esc_attr($optionId); ?>">
                                <span class="wpf_price_option_name" itemprop="description"><?php echo esc_html($price['label']); ?></span>
                                <span class="wpf_price_option_sep">&nbsp;–&nbsp;</span>
                                <span class="wpf_price_option_price"><?php echo wpPayFormFormattedMoney(wpPayFormConverToCents($price['value']), $currenySettings); ?></span>
                                <meta itemprop="price" content="<?php echo esc_attr($price['value']); ?>">
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }

    public function chooseMultipleChoice($element, $form, $has_pro, $prices = array())
    {
        $fieldOptions = Arr::get($element, 'field_options', false);
        $enableImage = Arr::get($fieldOptions, 'enable_image', false);

        if (!$fieldOptions) {
            return;
        }
        $currenySettings = Form::getCurrencyAndLocale($form->ID);
        $controlClass = $this->elementControlClass($element);
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        $inputId = 'wpf_input_' . $form->ID . '_' . $this->elementName;
        $defaultValue = Arr::get($fieldOptions, 'default_value');
        $displayValue = $has_pro === true ? $hiddenAttr : '';
        $defaultValues = $defaultValue? explode(',', $defaultValue) : [];
        $controlAttributes = array(
            'data-element_type' => $this->elementName,
            'class' => $controlClass,
            'data-checkbox_required' => Arr::get($fieldOptions, 'required'),
            'data-element_type' => 'checkbox',
            'required_id' => $element['id'],
            'data-target_element' => $element['id']
        ); ?>
        <div style="display : <?php echo esc_attr($displayValue); ?>" <?php $this->printAttributes($controlAttributes); ?>>
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>

            <?php
            $itemParentAtrributes = array(
                'class' => 'wpf_multi_form_controls wpf_input_content',
                'data-item_required' => Arr::get($fieldOptions, 'required'),
                'data-item_selector' => 'checkbox',
                'data-has_multiple_input' => 'yes'
            ); ?>

            <div <?php $this->printAttributes($itemParentAtrributes); ?>>
                <?php foreach ($prices as $index => $option) : ?>
                    <?php
                    $image_len = Arr::get($option, 'photo.image_full') ? strlen(Arr::get($option, 'photo.image_full')) : 0;
                    $optionId = $element['id'] . '_' . $index . '_' . $form->ID;
                    $attributes = array(
                        'class' => 'form-check-input wpf_payment_item',
                        'type' => 'checkbox',
                        'data-price' => wpPayFormConverToCents($option['value']),
                        'name' => $element['id'] . '[' . $index . ']',
                        'id' => $optionId,
                        'condition_id' => $element['id'],
                        'data-group_id' => $element['id'],
                        'value' => $option['label'],
                        'customname' => $option['label'],
                        'groupname' => $fieldOptions['label']
                    );
                    if (in_array($option['value'], $defaultValues)) {
                        $attributes['checked'] = 'true';
                    }
                    if ($enableImage == 'yes' && $image_len > 0) : ?>
                        <div>
                            <?php wpPayFormPrintInternal($this->renderImage($option['photo'])); ?>
                        </div>
                        <!-- </div> -->
                    <?php endif; ?>
                    <div class="form-check" style="margin-bottom: 20px;">
                        <input <?php $this->printAttributes($attributes); ?>>
                        <label class="form-check-label" for="<?php echo esc_attr($optionId); ?>">
                            <span class="wpf_price_option_name" itemprop="description"><?php echo wp_kses_post($option['label']); ?></span>
                            <span class="wpf_price_option_sep">&nbsp;–&nbsp;</span>
                            <span class="wpf_price_option_price"><?php echo wpPayFormFormattedMoney(wpPayFormConverToCents($option['value']), $currenySettings); ?></span>
                            <meta itemprop="price" content="<?php echo esc_attr($option['value']); ?>" />
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php

    }
}
