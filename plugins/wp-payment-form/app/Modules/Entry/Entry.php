<?php

namespace WPPayForm\App\Modules\Entry;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\OrderItem;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\Subscription;
use WPPayForm\App\Models\Transaction;
use WPPayForm\Framework\Foundation\App;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Entry Methods
 * @since 1.0.0
 */
class Entry
{
    protected $formId;
    protected $submissionId;
    protected $submission;
    protected $formattedInput;
    protected $rawInput;
    protected $formattedFields;
    protected $patsedItems;
    protected $instance;
    public $default = false;
    protected $app = null;

    public function __construct($submission)
    {
        $this->formId = $submission->form_id;
        $this->submissionId = $submission->id;
        $this->submission = $submission;
        $this->formattedInput = $submission->form_data_formatted;
        $this->rawInput = $submission->form_data_raw;
        $this->instance = $this;
        $this->app = App::getInstance();
    }

    public function getRawInput($key, $default = false)
    {
        if (isset($this->rawInput[$key])) {
            return $this->rawInput[$key];
        }
        return $default;
    }

    public function getInput($key, $default = false)
    {
        $value = $default;
        if (isset($this->formattedInput[$key])) {
            $value = $this->formattedInput[$key];
        } elseif (strpos($key, 'address_') !== false) {
            $value = $this->parseAddresses($key);
        }
        if (is_array($value)) {
            $value = $this->maybeNeedToConverHtml($value, $key);
        }
        return $value;
    }

    public function parseAddresses($key)
    {
        $key = substr($key, 8, strlen($key));
        $addresses = $this->rawInput['address_input'] ?? [];
        return isset($addresses[$key]) ? $addresses[$key] : '';
    }

    public function getItemQuantity($key)
    {
        return $this->getRawInput($key);
    }

    public function getPaymentItems($itemName)
    {
        $names = array();
        $itemNames = OrderItem::select(array('item_name'))
            ->where('submission_id', $this->submissionId)
            ->where('parent_holder', $itemName)
            ->get();
   
        // checking for subscription item
        if(count($itemNames) == 0){
            $itemNames = Subscription::select(array('item_name', 'plan_name'))
            ->where('submission_id', $this->submissionId)
            ->get();

            foreach ($itemNames as $item) {
                $subcr[] = $item['item_name'];
                $subcr[] = $item['plan_name'];
                $names[] = implode(' - ', $subcr);
            }

            return $names;
        }

        foreach ($itemNames as $itemName) {
            $names[] = $itemName->item_name;
        }
        return $names;
    }

    public function getInputFieldsHtmlTable()
    {
        return $this->app->view->make('elements.input_fields_html', array(
            'items' => $this->getParsedItems(),
            'showEmpty' => false
        ));
    }

    public function getInputFieldsWEmptyHtmlTable()
    {
        return $this->app->view->make('elements.input_fields_html', array(
            'items' => $this->getParsedItems(),
            'showEmpty' => true
        ));
    }

    public function getSubscriptionId()
    {
        // Just check if submission order items added or not
        $this->getSubscriptionItems();
        return $this->app->view->make('elements.subscription_id', array(
            'submission' => $this->submission,
            'load_table_css' => true
        ));
    }

    public function getOrderItemsHtml()
    {
        // Just check if submission order items added or not
        $this->getOrderItems();
        $this->getTaxItems();
        $this->getSubscriptionItems();
        foreach ($this->submission->subscriptions as $subscription) {
            // $submission->payment_total = ($subscription->payment_total - $subscription->initial_amount);
            $this->submission->payment_total =  ($subscription->payment_total - $subscription->initial_amount);

        }
        return $this->app->view->make('elements.order_items_table', array(
            'submission' => $this->submission
        ));
    }

    public function getTransactionId()
    {
        $this->getTransactionItems();

        return $this->app->view->make('elements.transaction_id', array(
            'submission' => $this->submission
        ));
    }

    public function getTransactionItems()
    {
        if (!$this->submission->transactions) {
            $transactions = new Transaction();
            $this->submission->transactions = $transactions->getTransactions($this->submissionId);
        }
        return $this->submission->transactions;
    }

    public function getSubscriptionsHtml()
    {
        // Just check if submission order items added or not
        $this->getSubscriptionItems();
        return $this->app->view->make('elements.subscriptions_info', array(
            'submission' => $this->submission,
            'load_table_css' => true
        ));
    }

    public function getOrderItems()
    {
        if (!$this->submission->order_items) {
            $orderItem = new OrderItem();
            $this->submission->order_items = $orderItem->getSingleOrderItems($this->submissionId);
        }
        return $this->submission->order_items;
    }

    public function getTaxItems()
    {
        if (!$this->submission->tax_items) {
            $orderItem = new OrderItem();
            $this->submission->tax_items = $orderItem->getTaxOrderItems($this->submissionId);
        }
        return $this->submission->tax_items;
    }

    public function getTaxTotal()
    {
        $taxItems = $this->getTaxItems();
        if (!$taxItems) {
            return 0;
        }
        $total = 0;
        foreach ($taxItems as $item) {
            $total += $item->line_total;
        }
        return $total;
    }

    public function getSubscriptionItems()
    {
        if (!$this->submission->subscriptions) {
            $subscriptionModel = new Subscription();
            $this->submission->subscriptions = $subscriptionModel->getSubscriptions($this->submissionId);
        }
        return $this->submission->subscriptions;
    }

    public function getOrderItemsAsText($separator = "\n")
    {
        $orderItems = $this->getOrderItems();
        $text = '';
        foreach ($orderItems as $index => $orderItem) {
            $text .= $orderItem->item_name . ' (' . $orderItem->quantity . ') - ' . number_format($orderItem->line_total / 100, 2);
            if ($index != (count($orderItems) - 1)) {
                $text .= $separator;
            }
        }
        return $text;
    }

    public function getSubscriptionsAsText($separator = "\n")
    {
        // Just check if submission order items added or not
        $subscriptionItems = $this->getSubscriptionItems();
        $text = '';
        foreach ($subscriptionItems as $index => $subscriptionItem) {
            $text .= $subscriptionItem->item_name . ' - ' . $subscriptionItem->plan_name . ' ( ' . number_format($subscriptionItem->payment_total / 100, 2) . ' ) - ' . $subscriptionItem->status;
            if ($index != (count($subscriptionItems) - 1)) {
                $text .= $separator;
            }
        }
        return $text;
    }

    public function __get($name)
    {
        if ($name == 'all_input_field_html') {
            return $this->getInputFieldsHtmlTable();
        }

        if ($name == 'all_input_field_html_with_empty') {
            return $this->getInputFieldsWEmptyHtmlTable();
        }

        if ($name == 'product_items_table_html') {
            return $this->getOrderItemsHtml();
        }
        if ($name == 'transaction_id') {
            return $this->getTransactionId();
        }

        if ($name == 'subscription_details_table_html') {
            return $this->getSubscriptionsHtml();
        }

        if ($name == 'subscription_id') {
            return $this->getSubscriptionId();
        }

        if ($name == 'payment_total_in_cents') {
            return $this->submission->payment_total;
        } elseif ($name == 'payment_total_in_decimal') {
            return number_format($this->submission->payment_total / 100, 2);
        }

        if ($name == 'payment_receipt') {
            $receiptHandler = new \WPPayForm\App\Modules\Builder\PaymentReceipt();
            return $receiptHandler->render($this->submissionId);
        }

        if ($name == 'payment_total') {
            $subscriptionItems = $this->getSubscriptionItems();
            if(count($subscriptionItems)) {
                foreach ($subscriptionItems as $subscription) {
                    $this->submission->payment_total +=  $subscription->recurring_amount;
                }
                return $this->paymentTotal();
            }
            return $this->paymentTotal();
        }

        if ($this->submission->{$name}) {
            if ($name == 'payment_total') {
                return $this->paymentTotal();
            }
            if ($name == 'created_at') {
                $dateString = $this->submission->created_at;
                return is_string($dateString) ? $dateString : $dateString->format('Y/m/d H:i:s');
            }
            return $this->submission->{$name};
        }

        return $this->default;
    }

    public function paymentTotal()
    {
        return wpPayFormFormattedMoney($this->submission->payment_total, Form::getCurrencyAndLocale($this->form_id));
    }

    public function getSubmission()
    {
        return $this->submission;
    }

    public function maybeNeedToConverHtml($value, $key)
    {
        $formattedInputs = $this->getFormattedInputs();
        $element = Arr::get($formattedInputs, 'input.' . $key);
        if ($element) {
            $value = apply_filters('wppayform/maybe_conver_html_' . $element['type'], $value, $this->submission, $element);
        }
        return $value;
    }

    public function getFormattedInputs()
    {
        if (!$this->formattedFields) {
            $this->formattedFields = Form::getFormattedElements($this->formId);
        }
        return $this->formattedFields;
    }

    public function getParsedItems()
    {
        if ($this->patsedItems) {
            return $this->patsedItems;
        }

        $submissionModel = new Submission();
        $parsedItems = $submissionModel->getParsedSubmission($this->submission);
        $this->patsedItems = $parsedItems;
        return $this->patsedItems;
    }
}
