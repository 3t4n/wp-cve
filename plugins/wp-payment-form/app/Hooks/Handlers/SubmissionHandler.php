<?php

namespace WPPayForm\App\Hooks\Handlers;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\OrderItem;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\SubmissionActivity;
use WPPayForm\App\Models\Subscription;
use WPPayForm\App\Models\Transaction;
use WPPayForm\App\Modules\PaymentMethods\Stripe\Stripe;
use WPPayForm\App\Services\Browser;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Services\PlaceholderParser;
use WPPayForm\App\Services\ConfirmationHelper;
use WPPayForm\App\Services\Turnstile\Turnstile;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Form Submission Handler
 *
 * @since 1.0.0
 */

class SubmissionHandler
{
    private $customerName = '';
    private $customerEmail = '';
    private $selectedPaymentMethod = '';
    private $appliedCoupons = array();
    private $formID = null;
    private $validCoupons = null;

    public function handleSubmission()
    {
        if (!isset($_REQUEST['form_data'])) {
            return;
        }
   
        parse_str($_REQUEST['form_data'], $form_data);
        $form_localize = Arr::get($_REQUEST['form_localize'], 'conditional_logic');

        // Now Validate the form please
        $formId = absint($_REQUEST['form_id']);
        $this->formID = $formId;

        // Get Original Form Elements Now
        $totalPayableAmount = intval($_REQUEST['main_total']);

        do_action('wppayform/form_submission_activity_start', $formId);

        $form = Form::getForm($formId);

        if (!$form) {
            wp_send_json_error(array(
                'message' => __('Invalid request. Please try again', 'wp-payment-form'),
            ), 423);
        }

        $formattedElements = Form::getFormattedElements($formId);
        $this->validate($form_data, $formattedElements, $form, $form_localize);

        $paymentMethod = apply_filters('wppayform/choose_payment_method_for_submission', '', $formattedElements['payment_method_element'], $formId, $form_data);

        $this->selectedPaymentMethod = $paymentMethod;

        // Extract Payment Items Here
        $paymentItems = array();
        $subscriptionItems = array();

        foreach ($formattedElements['payment'] as $paymentId => $payment) {
            $quantity = $this->getItemQuantity($formattedElements['item_quantity'], $paymentId, $form_data);
            if ($quantity == 0) {
                continue;
            }
            if ($payment['type'] == 'recurring_payment_item') {
                $subscription = $this->getSubscriptionLine($payment, $paymentId, $quantity, $form_data, $formId);
                if (!empty($subscription['type']) && $subscription['type'] == 'single') {
                    // We converted this as one time payment
                    $paymentItems[] = $subscription;
                } else {
                    $subscriptionItems = array_merge($subscriptionItems, $subscription);
                }
            } elseif ($payment['type'] == 'coupon' && isset($form_data['__wpf_all_applied_coupons'])) {
                $this->appliedCoupons = json_decode($form_data['__wpf_all_applied_coupons']);
            } elseif ($payment['type'] == 'donation_item') {
                if (isset($form_data['donation_is_recurring']) && $form_data['donation_is_recurring'] == 'on') {
                    $subscription = $this->getSubDonationLine($payment, $paymentId, $quantity, $form_data, $formId);
                    $subscriptionItems = array_merge($subscriptionItems, $subscription);
                } else {
                    $lineItems = $this->getPaymentLine($payment, $paymentId, $quantity, $form_data);
                    if ($lineItems) {
                        $paymentItems = array_merge($paymentItems, $lineItems);
                    }
                };
            } else {
                $lineItems = $this->getPaymentLine($payment, $paymentId, $quantity, $form_data);

                if ($lineItems) {
                    $paymentItems = array_merge($paymentItems, $lineItems);
                }
            }
        }

        $subscriptionItems = apply_filters('wppayform/submitted_subscription_items', $subscriptionItems, $formattedElements, $form_data);

        $discountPercent = 0;
        if (!empty($this->appliedCoupons)) {
            $amountToPay = $totalPayableAmount;
            $couponModel = new \WPPayFormPro\Classes\Coupons\CouponModel();
            $coupons = $couponModel->getCouponsByCodes($this->appliedCoupons, true);
            $validCouponItems = $couponModel->getValidCoupons($coupons, $this->formID, $amountToPay);
            $this->validCoupons = (new \WPPayFormPro\Classes\Coupons\CouponController())->getTotalLine($validCouponItems, $amountToPay);
            $discountPercent = ($this->validCoupons['totalDiscounts'] * 100) / $amountToPay;
        }
        $paymentItems = apply_filters('wppayform/submitted_payment_items', $paymentItems, $formattedElements, $form_data, $discountPercent);
        /*
         * providing filter hook for payment method to push some payment data
         *  from $subscriptionItems
         * Some PaymentGateway like stripe may add signup fee as one time fee
         */
        if ($subscriptionItems) {
            $paymentItems = apply_filters('wppayform/submitted_payment_items_' . $paymentMethod, $paymentItems, $formattedElements, $form_data, $subscriptionItems);
        }

        // Extract Input Items Here
        $inputItems = array();

        foreach ($formattedElements['input'] as $inputName => $inputElement) {
            $value = Arr::get($form_data, $inputName);
            $inputItems[$inputName] = apply_filters('wppayform/submitted_value_' . $inputElement['type'], $this->sanitizeFormData($value, $inputElement['type']), $inputElement, $form_data);
        }

        // Calculate Payment Total Now
        $paymentTotal = 0;
        $taxTotal = 0;
        if ($paymentItems) {
            foreach ($paymentItems as $paymentItem) {
                $paymentTotal += $paymentItem['line_total'];
                if ($paymentItem['type'] == 'tax_line') {
                    $taxTotal += $paymentItem['line_total'];
                }
            }
        }

        if ($paymentTotal) {
            $this->paymentValidate($paymentTotal, $formId);
        }

        $currentUserId = get_current_user_id();
        if (!$this->customerName && $currentUserId) {
            $currentUser = get_user_by('ID', $currentUserId);
            $this->customerName = $currentUser->display_name;
        }

        if (!$this->customerEmail && $currentUserId) {
            $currentUser = get_user_by('ID', $currentUserId);
            $this->customerEmail = $currentUser->user_email;
        }

        if ($formattedElements['payment_method_element'] && !$paymentMethod) {
            wp_send_json_error(array(
                'message' => __('Validation failed, because selected payment method could not be found', 'wp-payment-form'),
            ), 423);
            exit;
        }

        if ($formattedElements['payment_method_element'] && $paymentMethod == 'stripe' && ($paymentTotal || $subscriptionItems)) {
            // do verification for stripe stripe_inline
            // We have to see if __stripe_payment_method_id has value or not
            $stripe = new Stripe();
            $methodStyle = $stripe->getStripePaymentMethodByElement($formattedElements['payment_method_element']);
            if ($methodStyle == 'stripe_inline') {
                if (empty($form_data['__stripe_payment_method_id'])) {
                    wp_send_json_error(array(
                        'message' => __('Validation failed, Please fill up card details', 'wp-payment-form'),
                    ), 423);
                    exit;
                }
            }
        }

        $currencySetting = Form::getCurrencySettings($formId);
        $currency = sanitize_text_field($currencySetting['currency']);

        if (isset($form_data['currency_switcher']) && $form_data['currency_switcher'] != '' && isset($form_data['donation_item'])) {
            $currency =  apply_filters('wppayform/currency_switch', $form_data['currency_switcher']);
        }

        $inputItems = apply_filters('wppayform/submission_data_formatted', $inputItems, $form_data, $formId);
        $submission = array(
            'form_id'             => (int) $formId,
            'user_id'             => $currentUserId,
            'customer_name'       => sanitize_text_field($this->customerName),
            'customer_email'      => sanitize_text_field($this->customerEmail),
            'form_data_raw'       => maybe_serialize($form_data),
            'form_data_formatted' => maybe_serialize(wp_unslash($inputItems)),
            'currency'            => $currency,
            'payment_method'      => sanitize_text_field($paymentMethod),
            'payment_status'      => 'pending',
            'submission_hash'     => sanitize_text_field($this->getHash()),
            'payment_total'       => $paymentTotal,
            'status'              => 'new',
            'created_at'          => current_time('mysql'),
            'updated_at'          => current_time('mysql'),
        );

        $browser = new Browser();
        $ipLoggingStatus = GeneralSettings::ipLoggingStatus(true);
        if ($ipLoggingStatus != 'no') {
            $submission['ip_address'] = $browser->getIp();
        }

        $submission['browser'] = sanitize_text_field($browser->getBrowser());
        $submission['device'] = sanitize_text_field($browser->getPlatform());

        $submission = apply_filters('wppayform/create_submission_data', $submission, $formId, $form_data);

        do_action('wppayform/wpf_before_submission_data_insert_' . $paymentMethod, $submission, $form_data, $paymentItems, $subscriptionItems);
        do_action('wppayform/wpf_before_submission_data_insert', $submission, $form_data, $paymentItems, $subscriptionItems);
        do_action('wppayform/wpf_honeypot_security', $form_data, $formId);

        // Insert Submission
        $submissionModel = new Submission();
        $submissionId = $submissionModel->createSubmission($submission)->id;

        do_action('wppayform/after_submission_data_insert', $submissionId, $formId, $form_data, $formattedElements);

        /*
         * Dear Payment method developers,
         * Please don't use this hook to process the payment
         * The order items is not processed yet!
         */
        do_action('wppayform/after_submission_data_insert_' . $paymentMethod, $submissionId, $formId, $formattedElements['payment_method_element']);

        $submission = $submissionModel->getSubmission($submissionId);

        //populating order items to submission only for 'after submission' email notification
        $submission->order_items = $paymentItems;

        // do_action('wppayform/after_form_submission_complete', $submission, $formId);

        if ($paymentItems || $subscriptionItems) {
            // Insert Payment Items
            $itemModel = new OrderItem();
            if ($paymentItems) {
                foreach ($paymentItems as $payItem) {
                    if (Arr::get($payItem, 'item_meta')) {
                        $payItem['item_meta'] = maybe_serialize($payItem['item_meta']);
                    }
                    $payItem['submission_id'] = $submissionId;
                    $payItem['form_id'] = $formId;
                    $itemModel->createOrder($payItem);
                }
            }

            // insert subscription items
            $subsTotal = 0;
            $subscription = new Subscription();
            foreach ($subscriptionItems as $subscriptionItem) {
                $quantity = isset($subscriptionItem['quantity']) ? $subscriptionItem['quantity'] : 1;
                $linePrice = $subscriptionItem['recurring_amount'] * $quantity;
                $subsTotal += intval($linePrice);

                $subscriptionItem['submission_id'] = $submissionId;
                $subscription->createSubscription($subscriptionItem);
            }

            $hasSubscriptions = (bool) $subscriptionItems;
            $transactionId = false;
            $totalPayable = $paymentTotal + $subsTotal;
            if (isset($this->validCoupons)) {
                foreach ($this->validCoupons['discounts'] as $item) {
                    $item['submission_id'] = intval($submissionId);
                    $item['form_id'] = $formId;
                    $itemModel->create($item);
                }
                //issue on bottom line- should minus discount based on percent
                $newTotal = ($paymentTotal - $taxTotal);
                $newDiscount = ($newTotal * $discountPercent) / 100;
                $paymentTotal = $newTotal - $newDiscount + $taxTotal;
            }
            do_action('wppayform/after_form_submission_complete', $submission, $formId);
            if ($paymentItems) {
                // Insert Transaction Item Now
                $transaction = array(
                    'form_id'        => $formId,
                    'user_id'        => $currentUserId,
                    'submission_id'  => $submissionId,
                    'charge_id'      => '',
                    'payment_method' => $paymentMethod,
                    'payment_total'  => $paymentTotal,
                    'currency'       => $currency,
                    'status'         => 'pending',
                    'created_at'     => current_time('mysql'),
                    'updated_at'     => current_time('mysql'),
                );

                $transaction = apply_filters('wppayform/submission_transaction_data', $transaction, $formId, $form_data);
                $transactionModel = new Transaction();
                $transactionId = $transactionModel->createTransaction($transaction)->id;
                do_action('wppayform/after_transaction_data_insert', $transactionId, $transaction);
                do_action('wppayform/maybe_add_coupon_meta', $submission, $submissionId, $form_data);
            }
            SubmissionActivity::createActivity(array(
                'form_id'       => $form->ID,
                'submission_id' => $submissionId,
                'type'          => 'activity',
                'created_by'    => 'Paymattic BOT',
                'content'       => 'After payment actions processed.',
            ));

            add_action('payment_handle_after_hundred_percent_discount', function ($transactionId, $submissionId) {
                $transactionModel = new Transaction();
                $transaction = $transactionModel->getTransaction($transactionId);

                if ($transactionId) {
                    $transactionModel->updateTransaction($transactionId, array(
                        'payment_mode' => '',
                        'status'       => 'paid',
                    ));
                }

                $submissionModel = new Submission();
                $submissionModel->updateSubmission($submissionId, array(
                    'payment_mode'   => '',
                    'payment_status' => 'paid',
                ));

                do_action('wppayform/after_payment_status_change', $submissionId, 'paid');

                SubmissionActivity::createActivity(array(
                    'form_id'       => $transaction->form_id,
                    'submission_id' => $transaction->submission_id,
                    'type'          => 'info',
                    'created_by'    => 'Payform Bot',
                    'content'       => __('Payment success with 100% discount and the status updated Paid', 'wp-payment-form'),
                ));
            }, 10, 2);

            if ($paymentMethod) {
                if (apply_filters('wppayform/validate_gateway_api_' . $paymentMethod, false, $form) === false && $paymentMethod != 'offline') {
                    wp_send_json_error(array(
                        'message' => "Validation failed, Credentials not setup yet for $paymentMethod !",
                    ), 423);
                }
                if (100 <= $discountPercent) {
                    do_action('payment_handle_after_hundred_percent_discount', $transactionId, $submissionId);
                } else {
                    do_action('wppayform/form_submission_make_payment_' . $paymentMethod, $transactionId, $submissionId, $form_data, $form, $hasSubscriptions, $totalPayable);
                }
            }
        } else {
            do_action('wppayform/after_form_submission_complete', $submission, $formId);
        }

        $this->sendSubmissionConfirmation($submission, $formId);
    }

    private function sanitizeFormData($value, $type)
    {
        if (!$value) {
            return $value;
        }

        $fieldOptionsMap = [
            'customer_email' => 'sanitize_email',
            'customer_name'  => 'sanitize_text_field',
            'textarea'       => 'sanitize_textarea_field',
            'phone'          => 'sanitize_text_field',
            'password'       => 'sanitize_text_field',
            'text'           => 'sanitize_text_field',
            'select'         => 'sanitize_text_field',
            'radio'          => 'sanitize_text_field',
            'checkbox'       => 'sanitize_text_field',
            'date'           => 'sanitize_text_field',
            'hidden_input'   => 'sanitize_text_field',
        ];

        $fieldOptionsKeys = array_keys($fieldOptionsMap);
        if (in_array($type, $fieldOptionsKeys)) {
            if (is_array($value)) {
                return map_deep($value, 'sanitize_text_field');
            }
            return call_user_func($fieldOptionsMap[$type], $value);
        }

        if (is_array($value)) {
            return map_deep($value, 'wp_kses_post');
        }

        return wp_kses_post($value);
    }

    private function getSubDonationLine($payment, $paymentId, $quantity, $form_data, $formId)
    {
        if (!defined('WPPAYFORMHASPRO')) {
            return [];
        }
        $pricings = Arr::get($payment, 'options.pricing_details');

        if ($payment['type'] != 'donation_item' || $pricings['allow_recurring'] !== 'yes') {
            return array();
        }
        $label = Arr::get($payment, 'label');
        $amountTotal = Arr::get($form_data, $paymentId . '_custom');
        $subscription = array(
            'element_id'       => $paymentId,
            'item_name'        => $label,
            'form_id'          => $formId,
            'plan_name'        =>  $label,
            'billing_interval' => Arr::get($form_data, 'donation_recurring_interval', 'year'),
            'trial_days'       => 0,
            'recurring_amount' => wpPayFormConverToCents($amountTotal),
            'bill_times'       => Arr::get($pricings, 'bill_time_max'),
            'initial_amount'   => 0,
            'status'           => 'pending',
            'original_plan'    => maybe_serialize($pricings),
            'created_at'       => current_time('mysql'),
            'updated_at'       => current_time('mysql'),
        );

        if ($quantity > 1) {
            $subscription['quantity'] = $quantity;
        }
        $allSubscriptions = [$subscription];
        return $allSubscriptions;
    }

    private function validate($form_data, $formattedElements, $form, $form_localize)
    {
        $errors = array();
        $formId = $form->ID;
        $customerName = '';
        $customerEmail = '';
        // Validate Normal Inputs
        foreach ($formattedElements['input'] as $elementId => $element) {
            $error = false;
            $isRequired = Arr::get($form_localize[$element['type']], 'required');
            if (Arr::get($element, 'options.conditional_logic_option.conditional_logic') === 'no' || $isRequired === 'yes') {
                if (Arr::get($element, 'options.required') == 'yes' && empty($form_data[$elementId]) && !Arr::get($element, 'options.disable', false)) {
                    $error = $this->getErrorLabel($element, $formId);
                }
                $error = apply_filters('wppayform/validate_data_on_submission_' . $element['type'], $error, $elementId, $element, $form_data);
                if ($error) {
                    $errors[$elementId] = $error;
                }

                if ($element['type'] == 'customer_name' && !$customerName && isset($form_data[$elementId])) {
                    $customerName = $form_data[$elementId];
                } elseif ($element['type'] == 'customer_email' && !$customerEmail && isset($form_data[$elementId])) {
                    $customerEmail = $form_data[$elementId];
                }
            }
        }
        // Validate Payment Fields
        foreach ($formattedElements['payment'] as $elementId => $element) {
            $isRequired = Arr::get($form_localize[$element['type']], 'required');
            if (Arr::get($element, 'options.conditional_logic_option.conditional_logic') === 'no' || $isRequired === 'yes') {
                if (Arr::get($element, 'options.required') == 'yes' && !isset($form_data[$elementId]) && !Arr::get($element, 'options.disable', false)) {
                    $errors[$elementId] = $this->getErrorLabel($element, $formId);
                }
            }
        }
        // Validate Item Quantity Elements
        foreach ($formattedElements['item_quantity'] as $elementId => $element) {
            $error = '';
            if (isset($form_data[Arr::get($element, 'options.target_product')])) {
                if (Arr::get($element, 'options.required') == 'yes' && empty($form_data[$elementId]) && !Arr::get($element, 'options.disable', false)) {
                    $error = $this->getErrorLabel($element, $formId);
                }
            }

            $error = apply_filters('wppayform/validate_data_on_submission_' . $element['type'], $error, $elementId, $element, $form_data);
            if ($error) {
                $errors[$elementId] = $error;
            }
        }

        // Maybe validate recaptcha
        $formEvents = [];
        if (!$errors) {
            $recaptchaType = Form::recaptchaType($formId);
            if ($recaptchaType == 'v2_visible' || $recaptchaType == 'v3_invisible') {
                // let's validate recaptcha here
                $recaptchaSettings = GeneralSettings::getRecaptchaSettings();
                $ip_address = $this->getIp();
                $response = wp_remote_get(add_query_arg(array(
                    'secret'   => $recaptchaSettings['secret_key'],
                    'response' => isset($form_data['g-recaptcha-response']) ? $form_data['g-recaptcha-response'] : '',
                    'remoteip' => $ip_address,
                ), 'https://www.google.com/recaptcha/api/siteverify'));

                if (is_wp_error($response) || empty($response['body']) || !($json = json_decode($response['body'])) || !$json->success) {
                    $errors['g-recaptcha-response'] = __('reCAPTCHA validation failed. Please try again.', 'wp-payment-form');
                    $formEvents[] = 'refresh_recaptcha';
                }
            }
        }

        // Maybe handle turnstile
        if (!$errors) {
            $turnstile_status = Form::turnstileStatus($formId);
            if ($turnstile_status) {
                $turnstile_settings = GeneralSettings::getTurnstileSettings();
                $token = Arr::get($form_data, 'cf-turnstile-response');
                $isValid = Turnstile::validate($token, $turnstile_settings['secretKey']);
                if (!$isValid) {
                    $errors['cf-turnstile-response'] = __('Cloud flare turnstile validation failed. Please try again.', 'wp-payment-form');
                }
            }
        }

        $errors = apply_filters('wppayform/form_submission_validation_errors', $errors, $formId, $formattedElements);
        if ($errors) {
            wp_send_json_error(array(
                'message'     => __('Form Validation failed', 'wp-payment-form'),
                'errors'      => $errors,
                'form_events' => $formEvents,
            ), 423);
        }

        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;

        return;
    }

    private function paymentValidate($paymentTotal, $formId)
    {
        $errors = apply_filters('wppayform/form_submission_payment_validation_errors', array(), $paymentTotal, $formId);
        if (!empty($errors)) {
            wp_send_json_error(array(
                'message'     => __('Form Validation failed', 'wp-payment-form'),
                'errors'      => $errors,
                'form_events' => [],
            ), 423);
        }
    }

    private function getErrorLabel($element, $formId)
    {
        $label = Arr::get($element, 'options.label');
        if (!$label) {
            $label = Arr::get($element, 'options.placeholder');
            if (!$label) {
                $label = $element['id'];
            }
        }
        $label = $label . __(' is required', 'wp-payment-form');
        return apply_filters('wppayform/error_label_text', $label, $element, $formId);
    }

    private function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        return sanitize_text_field($_SERVER['REMOTE_ADDR']);
    }

    private function getItemQuantity($quantityElements, $tragetItemId, $formData)
    {
        $state = Arr::get($quantityElements, 'item_quantity.options.disable');

        if (!$quantityElements || $state) {
            return 1;
        }

        foreach ($quantityElements as $key => $element) {
            if (Arr::get($element, 'options.target_product') == $tragetItemId) {
                if (isset($formData[$key])) {
                    return absint($formData[$key]);
                }
            }
        }
        return 1;
    }

    private function getSubscriptionLine($payment, $paymentId, $quantity, $formData, $formId)
    {
        if (!defined('WPPAYFORMHASPRO')) {
            return [];
        }

        if ($payment['type'] != 'recurring_payment_item') {
            return array();
        }
        if (!isset($formData[$paymentId])) {
            return array();
        }
        $label = Arr::get($payment, 'options.label');
        if (!$label) {
            $label = $paymentId;
        }

        $pricings = Arr::get($payment, 'options.recurring_payment_options.pricing_options');

        $paymentIndex = (int)$formData[$paymentId];
        
        if ( is_string($paymentIndex) ) {
           $paymentIndex = 0;
        }
       
        $plan = $pricings[$paymentIndex];

        if (!$plan) {
            return array();
        }

        if (Arr::get($plan, 'user_input') == 'yes') {
            $plan['subscription_amount'] = Arr::get($formData, $paymentId . '__' . $paymentIndex);
        }

        if ($plan['bill_times'] == 1) {
            // We can convert this as one time payment
            // This plan should not have trial
            if ($plan['has_trial_days'] != 'yes') {
                $signupFee = 0;
                if ($plan['has_signup_fee'] == 'yes') {
                    $signupFee = wpPayFormConverToCents($plan['signup_fee']);
                }
                $onetimeTotal = $signupFee + wpPayFormConverToCents($plan['subscription_amount']);
                return [
                    'type'          => 'single',
                    'parent_holder' => $paymentId,
                    'item_name'     => $label,
                    'quantity'      => $quantity,
                    'item_price'    => $onetimeTotal,
                    'line_total'    => $quantity * $onetimeTotal,
                    'created_at'    => current_time('mysql'),
                    'updated_at'    => current_time('mysql'),
                ];
            }
        }

        $subscription = array(
            'element_id'       => $paymentId,
            'item_name'        => $label,
            'form_id'          => $formId,
            'plan_name'        => $plan['name'],
            'billing_interval' => $plan['billing_interval'],
            'trial_days'       => 0,
            'recurring_amount' => wpPayFormConverToCents($plan['subscription_amount']),
            'bill_times'       => $plan['bill_times'],
            'initial_amount'   => 0,
            'status'           => 'pending',
            'original_plan'    => maybe_serialize($plan),
            'created_at'       => current_time('mysql'),
            'updated_at'       => current_time('mysql'),
        );

        if (Arr::get($plan, 'has_signup_fee') == 'yes' && Arr::get($plan, 'signup_fee')) {
            $subscription['initial_amount'] = wpPayFormConverToCents($plan['signup_fee']);
        }

        if (Arr::get($plan, 'has_trial_days') == 'yes' && Arr::get($plan, 'trial_days')) {
            $subscription['trial_days'] = $plan['trial_days'];
            $dateTime = current_datetime();
            $localtime = $dateTime->getTimestamp() + $dateTime->getOffset();
            $expirationDate = gmdate('Y-m-d H:i:s', $localtime + absint($plan['trial_days']) * 86400);
            $subscription['expiration_at'] = $expirationDate;
        }

        if ($quantity > 1) {
            $subscription['quantity'] = $quantity;
        }

        $allSubscriptions = [$subscription];

        return $allSubscriptions;
    }

    private function getPaymentLine($payment, $paymentId, $quantity, $formData)
    {
        if (!isset($formData[$paymentId])) {
            return array();
        }

        $label = Arr::get($payment, 'options.label');
        if (!$label) {
            $label = $paymentId;
        }
        $payItem = array(
            'type'          => 'single',
            'parent_holder' => $paymentId,
            'item_name'     => strip_tags($label),
            'quantity'      => $quantity,
            'created_at'    => current_time('mysql'),
            'updated_at'    => current_time('mysql'),
        );

        if ($payment['type'] == 'payment_item') {
            $priceDetailes = Arr::get($payment, 'options.pricing_details');
            $payType = Arr::get($priceDetailes, 'one_time_type');
            if ($payType == 'choose_single') {
                $pricings = $priceDetailes['multiple_pricing'];
                $price = $pricings[$formData[$paymentId]];
                $priceLabel = !empty($price['label']) ? $price['label'] : $payment['label'];
                $payItem['item_name'] = strip_tags($priceLabel);
                $payItem['item_price'] = wpPayFormConverToCents($price['value']);
                $payItem['line_total'] = $payItem['item_price'] * $quantity;
            } elseif ($payType == 'choose_multiple') {
                $selctedItems = $formData[$paymentId];
                $pricings = $priceDetailes['multiple_pricing'];
                $payItems = array();
                foreach ($selctedItems as $itemIndex => $selctedItem) {
                    $itemClone = $payItem;
                    $itemClone['item_name'] = strip_tags($pricings[$itemIndex]['label']);
                    $itemClone['item_price'] = wpPayFormConverToCents($pricings[$itemIndex]['value']);
                    $itemClone['line_total'] = $itemClone['item_price'] * $quantity;
                    $payItems[] = $itemClone;
                }
                return $payItems;
            } else {
                $payItem['item_price'] = wpPayFormConverToCents(Arr::get($priceDetailes, 'payment_amount'));
                $payItem['line_total'] = $payItem['item_price'] * $quantity;
            }
        } elseif ($payment['type'] == 'custom_payment_input') {
            $payItem['item_price'] = wpPayFormConverToCents(floatval($formData[$paymentId]));
            $payItem['line_total'] = $payItem['item_price'] * $quantity;
        } elseif ($payment['type'] == 'donation_item') {
            $payItem['item_price'] = wpPayFormConverToCents(floatval($formData[$paymentId . '_custom']));
            $payItem['line_total'] = $payItem['item_price'] * $quantity;
        } else {
            return array();
        }

        return array($payItem);
    }

    private function getHash()
    {
        $localtime = current_time('timestamp');

        $prefix = 'wpf_' . $localtime;
        $uid = uniqid($prefix);
        // now let's make a unique number from 1 to 999
        $uid .= mt_rand(1, 999);
        $uid = str_replace(array("'", '/', '?', '#', '\\'), '', $uid);
        return $uid;
    }

    public function sendSubmissionConfirmation($submission, $formId)
    {
        $confirmation = ConfirmationHelper::getFormConfirmation($formId, $submission);

        wp_send_json_success(array(
            'message'       => __('Form is successfully submitted', 'wp-payment-form'),
            'submission_id' => $submission->id,
            'confirmation'  => $confirmation,
        ), 200);
    }
    public function paymentHandelerAfterHundredDiscount($transactionId, $submissionId)
    {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->getTransaction($transactionId);

        if ($transactionId) {
            $transactionModel->updateTransaction($transactionId, array(
                'payment_mode' => '',
            ));
        }

        $submissionModel = new Submission();
        $submissionModel->updateSubmission($submissionId, array(
            'payment_mode' => '',
        ));

        SubmissionActivity::createActivity(array(
            'form_id'       => $transaction->form_id,
            'submission_id' => $transaction->submission_id,
            'type'          => 'info',
            'created_by'    => 'Payform Bot',
            'content'       => __('Offline Payment recorded and change the status to pending', 'wp-payment-form'),
        ));
    }
}
