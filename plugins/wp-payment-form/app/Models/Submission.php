<?php

namespace WPPayForm\App\Models;

if (!defined('ABSPATH')) {
    exit;
}

use ExactLinks\Framework\Database\Orm\Builder;
use WPPayForm\App\Http\Controllers\FormController;
use WPPayForm\Framework\Foundation\App;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\SubscriptionTransaction;
use WPPayForm\App\Services\GeneralSettings;

/**
 * Manage Submission
 * @since 1.0.0
 */
class Submission extends Model
{
    protected $table = 'wpf_submissions';
    public $metaGroup = 'wpf_submissions';

    public function index($formId, $request)
    {
        $searchString = sanitize_text_field(Arr::get($request, 'search_string'));
        $page = absint(Arr::get($request, 'page_number'), 0);
        $perPage = absint(Arr::get($request, 'per_page'), 5000);
        $skip = ($page - 1) * $perPage;

        $wheres = array();
        $startDate = Arr::get($request, 'start_date');
        $endDate = Arr::get($request, 'end_date');

        if (isset($startDate)) {
            $startDate = sanitize_text_field($startDate);
        }

        if ((isset($endDate))) {
            $endDate = sanitize_text_field($endDate);
        }

        $paymentStatus = Arr::get($request, 'payment_status', false);

        $status = Arr::get($request, 'status', false);

        if ($paymentStatus) {
            $wheres['payment_status'] = sanitize_text_field($paymentStatus);
        }

        if ($status) {
            $wheres['status'] = sanitize_text_field($status);
        }

        if ('total' === $paymentStatus) {
            unset($wheres['payment_status']);
        }

        $submissions = $this->getAll($formId, $wheres, $perPage, $skip, 'DESC', $searchString, $startDate, $endDate);

        $currencySettings = GeneralSettings::getGlobalCurrencySettings($formId);

        $subscriptionTransaction = new SubscriptionTransaction();

        foreach ($submissions->items as $submission) {
            $submissionEntry = $this->getSubmission($submission['id'], array('transactions', 'order_items', 'tax_items', 'activities', 'refunds', 'discount'));
            $currencySettings['currency_sign'] = GeneralSettings::getCurrencySymbol($submission->currency);
            $hasSubscription = $subscriptionTransaction->hasSubscription($submission['id']);
            $submission->currencySettings = $currencySettings;
            $submission->submissionEntry = $submissionEntry;
            $submission->hasSubscription = $hasSubscription;
        }

        $submissionItems = apply_filters('wppayform/form_entries', $submissions->items, $formId);
        foreach ($submissionItems as $key => $submissionItem) {
            $submissionItems[$key] = apply_filters('wppayform/form_entry_recurring_info', $submissionItem);
        }
        $hasPaymentItem = true;
        if ($formId) {
            $hasPaymentItem = Form::hasPaymentFields($formId);
        }

        return array(
            'submissions' => $submissionItems->toArray(),
            'total' => (int) $submissions->total,
            'hasPaymentItem' => $hasPaymentItem,
        );
    }


    public function searchString($searchString, $query)
    {

        if (!$searchString) {
            return;
        }

        $query->where(function ($query) use ($searchString) {
            $fields = [
                'customer_name',
                'customer_email',
                'payment_method',
                'payment_total',
                'form_data_formatted',
                'created_at',
            ];

            foreach ($fields as $field) {
                $query->orWhere("wpf_submissions.$field", 'LIKE', "%{$searchString}%");
            }
        });

        return $query;
    }


    public function dateFilter($query, $startDate, $endDate)
    {
        if (isset($startDate) && isset($endDate)) {
            $endOfDay = date('Y-m-d', strtotime($endDate)) . ' 23:59:59';
            $query->whereBetween('created_at', [$startDate, $endOfDay]);
        }
        return $query;
    }

    public function paymentStatus($paymentStatus, $query)
    {

        if (!empty($paymentStatus)) {
            if ($paymentStatus == 'abandoned') {
                $query->where('payment_status', 'pending');
                $query = $this->makeQueryAbandoned($query, '<', true);
            } else {
                $query->where('payment_status', $paymentStatus);
            }
        }
        return $query;
    }

    public function createSubmission($submission)
    {
        return $this->create($submission);
    }

    public function getNewEntriesCount()
    {
        return $this->where('status', 'new')->count();
    }

    public function getAll($formId = false, $wheres = array(), $perPage = false, $skip = false, $orderBy = 'DESC', $searchString = false, $startDate = null, $endDate = null)
    {
        $resultQuery = $this->select(array('wpf_submissions.*', 'posts.post_title'))
            ->join('posts', 'posts.ID', '=', 'wpf_submissions.form_id')
            ->orderBy('wpf_submissions.id', $orderBy);

        if ($formId) {
            $resultQuery->where('wpf_submissions.form_id', $formId);
        }

        $queryType = Arr::get($wheres, 'payment_status');

        if (isset($wheres) && $queryType === 'abandoned') {
            $wheres['payment_status'] = 'pending';
            $resultQuery = self::makeQueryAbandoned($resultQuery, '<', true);
        }

        if (isset($wheres) && ($queryType === 'all-payments')) {
            unset($wheres['payment_status']);
            $resultQuery->where('wpf_submissions.payment_method', '!=', '');
        }

        if (isset($wheres) && $queryType === 'total') {
            unset($wheres['payment_status']);
        }

        foreach ($wheres as $whereKey => $where) {
            $resultQuery->where('wpf_submissions.' . $whereKey, '=', $where);
        }

        if ($searchString) {
            $resultQuery = $this->searchString($searchString, $resultQuery);
        }

        if ($startDate && $endDate) {
            $resultQuery = $this->dateFilter($resultQuery, $startDate, $endDate);
        }

        // if ($queryType !== null) {
        //     $resultQuery->where('payment_status', $queryType);
        // }

        $totalItems = $resultQuery->count();

        if ($perPage) {
            $resultQuery->limit($perPage);
        }
        if ($skip) {
            $resultQuery->offset($skip);
        }

        $results = $resultQuery->get();

        $formattedResults = array();

        foreach ($results as $result) {
            $result->form_data_raw = maybe_unserialize($result->form_data_raw);
            $result->form_data_formatted = maybe_unserialize($result->form_data_formatted);
            $result->payment_total += (new Subscription())->getSubscriptionPaymentTotal($result->form_id, $result->id);
            $formattedResults[] = $result;
        }

        return (object) array(
            'items' => $results,
            'total' => $totalItems,
        );
    }


    public function getDonationItem($form_id, $searchText = null, $orderByKey = null, $orderByVal = '', $skip = 0, $perPage = null)
    {
        $leaderboard_settings = get_option("wppayform_donation_leaderboard_settings", array(
            'enable_donation_for' => 'all',
            'template_id' => 3,
            'enable_donation_for_specific' => [],
            'orderby' => 'grand_total'
        ));
        
        $donation_for = Arr::get($leaderboard_settings, 'enable_donation_for', 'all');
        $specific_form = Arr::get($leaderboard_settings, 'enable_donation_for_specific', []);
        // dd($specific_form);
        $searchText = sanitize_text_field($searchText);
        $orderByKey = sanitize_text_field($orderByKey);
        $orderByVal = sanitize_text_field($orderByVal);

        $skip = absint($skip);
        $perPage = absint($perPage);
        $perPage = $perPage == 0 ? null : $perPage;
        $query = $this
            ->newQuery()
            ->where('payment_status', 'paid')
            ->where(function ($query) {
                return $query->whereHas('orderItems', function ($query) {
                    $query->where('parent_holder', 'like', 'donation_item%');
                })
                    ->orWhereHas('subscriptionItems', function ($query) {
                        $query->where('element_id', 'like', 'donation_item%');
                    });
            })
            ->withSum(
                [
                    'orderItems' => function ($query) {
                        $query->where('parent_holder', 'like', 'donation_item%');
                    }
                ],
                'line_total'
            )
            ->withSum(
                [
                    'subscriptionItems' => function ($query) {
                        $query->where('element_id', 'like', 'donation_item%');
                    }
                ],
                'payment_total'
            )
            ->when($donation_for == 'specific', function ($query) use ($specific_form) {
                $query->whereIn('form_id', $specific_form);
            })
            ->when($searchText, function ($query, $searchText) {
                $query->where('customer_name', 'like', '%' . $searchText . '%');
            })
            ->when($form_id, function ($query, $form_id) {
                $query->where('form_id', $form_id);
            })
            ->get()
            ->map(function ($item) {
                $item->grand_total = $item->order_items_sum_line_total + $item->subscription_items_sum_payment_total;
                $item->grand_total = $item->grand_total / 100;
                return $item;
            })
            ->groupBy('customer_email')
            ->map(function ($item) {
                return [
                    'customer_name' => $item->first()->customer_name,
                    "currency" => GeneralSettings::getCurrencySymbol($item->first()->currency),
                    'customer_email' => md5($item->first()->customer_email),
                    'grand_total' => $item->sum('grand_total'),
                    'created_at' => $item->first()->created_at,
                ];
            });
        $total = $query->count();

        $donationItems = $query
            ->skip(null)
            ->take($perPage)
            ->sortBy($orderByKey, SORT_REGULAR, $orderByVal);

        $topThreeDonors = $query->sortByDesc('grand_total')->take(3);

        return array(
            'topThreeDonars' => $topThreeDonors->toArray(),
            'donars' => $donationItems->toArray(),
            'has_more_data' => $total > $perPage ? true : false,
            'total' => $total
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'submission_id', 'id');
    }

    public function subscriptionItems()
    {
        return $this->hasMany(Subscription::class, 'submission_id', 'id');
    }

    public function getDiscounts ($submissionId, $form_id, $result)
    {
        $discounts = (new OrderItem())->getDiscountItems($submissionId);

            $totalDiscount = 0;
            if (isset($discounts)) {
                foreach ($discounts as $discount) {
                    $totalDiscount += intval($discount->line_total);
                }
            }
            $totalWithoutTax = 0;
            $orderTotal = 0;
            if (!empty($result->order_items)) {
                foreach ($result->order_items as $items) {
                    $orderTotal += intval($items->line_total);
                }
            }

            $subsTotal = intval((new Subscription())->getSubscriptionPaymentTotal($form_id, $submissionId));
            $totalWithoutTax = $orderTotal + $subsTotal;
            $percentDiscount = 0;
            if ($totalWithoutTax) {
                $percentDiscount = intval(($totalDiscount * 100) / $totalWithoutTax, 2);
            }

            return  array(
                'applied' => $discounts,
                'total' => $totalDiscount,
                'percent' => $percentDiscount
            );
    }

    public function getSubmission($submissionId, $with = array())
    {
        $result = $this->select(array('wpf_submissions.*', 'posts.post_title'))
            ->join('posts', 'posts.ID', '=', 'wpf_submissions.form_id')
            ->where('wpf_submissions.id', $submissionId)
            ->first();
        $result->form_data_raw = maybe_unserialize($result->form_data_raw);
        $result->form_data_formatted = maybe_unserialize($result->form_data_formatted);
        if ($result->user_id) {
            $result->user_profile_url = get_edit_user_link($result->user_id);
        }

        if (in_array('transactions', $with)) {
            $result->transactions = (new Transaction())->getTransactions($submissionId);
        }

        if (in_array('order_items', $with)) {
            $result->order_items = (new OrderItem())->getSingleOrderItems($submissionId);
        }
        if (in_array('discount', $with)) {
            $result->discounts = $this->getDiscounts($submissionId, $result->form_id, $result);
        }

        if (in_array('tax_items', $with)) {
            $result->tax_items = (new OrderItem())->getTaxOrderItems($submissionId);
        }

        if (in_array('activities', $with)) {
            $result->activities = SubmissionActivity::getSubmissionActivity($submissionId);
        }

        if (in_array('subscriptions', $with)) {
            $subscriptionModel = new Subscription();
            $result->subscriptions = $subscriptionModel->getSubscriptions($result->id);
        }
        if (in_array('refunds', $with)) {
            $refundModel = new Refund();
            $result->refunds = $refundModel->getRefunds($result->id);
            $refundTotal = 0;
            if ($result->refunds) {
                foreach ($result->refunds as $refund) {
                    $refundTotal += $refund->payment_total;
                }
            }
            $result->refundTotal = $refundTotal;
        }

        return $result;
    }

    public function getSubmissionByHash($submissionHash, $with = array())
    {
        $submission = $this->where('submission_hash', $submissionHash)
            ->orderBy('id', 'DESC')
            ->first();

        if ($submission) {
            return $this->getSubmission($submission->id, array('transactions', 'order_items', 'tax_items', 'subscriptions', 'discount'));
        }
        return false;
    }

    public function getTotalCount($formId = false, $paymentStatus = false, $searchString = false, $startDate = null, $endDate = null)
    {
        if ($formId) {
            $query = $this->where('form_id', $formId);
        }

        if (!empty($paymentStatus)) {
            $query = $this->paymentStatus($paymentStatus, $query);
        }

        if ($searchString) {
            $query = $this->searchString($searchString, $query);
        }

        if ($startDate && $endDate) {
            $query = $this->dateFilter($query, $startDate, $endDate);
        }

        if ($paymentStatus !== null && $paymentStatus !== '') {
            $query->where('payment_status', $paymentStatus);
        }

        return $query->count();
    }

    public function makeQueryAbandoned($query, $condition = '<', $payOnly = true)
    {
        $hour = get_option('wppayform_abandoned_time', 3);

        $beforeHour = intval($hour) * 3600;
        $now = current_time('mysql');
        $formatted_date = date('Y-m-d H:i:s', strtotime($now) - $beforeHour);

        $query->where('wpf_submissions.created_at', $condition, $formatted_date);
        if ($payOnly) {
            $query->where('wpf_submissions.payment_method', '!=', '');
        }
        return $query;
    }


    public function paymentTotal($formId, $paymentStatus = false, $searchString = false, $startDate = null, $endDate = null)
    {
        $paymentTotal = 0;
        //check for donation item
        $hasDonationItem = false;
        $builderSettings = Form::getBuilderSettings($formId);

        foreach ($builderSettings as $key => $value) {
            if ('donation_item' === $value['type']) {
                $hasDonationItem = true;
            }
        }

        if ($hasDonationItem) {
            return $this->donationTotal($formId, $paymentStatus, $searchString, $startDate, $endDate);
        }

        $DB = App::make('db');
        // $query = $this->select($DB->raw('SUM(payment_total) as payment_total'));
        $query = $this->select('*');


        if ($formId) {
            $query = $query->where('form_id', $formId);
        }

        if (!empty($paymentStatus)) {
            $query = $this->paymentStatus($paymentStatus, $query);
        }

        if ($paymentStatus !== null) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($searchString) {
            $query = $this->searchString($searchString, $query);
        }

        if ($startDate && $endDate) {
            $query = $this->dateFilter($query, $startDate, $endDate);
        }

        $results = $query->get();
       
        $paymentTotal = $results->map(function ($result) {
            $result = apply_filters('wppayform/form_entry_recurring_info', $result);
            $submissionId = $result->id;
            $discountItems = (new OrderItem())->getDiscountItems($submissionId);
            $discount_line_total = $discountItems->sum('line_total');
            $submissionTotal = $result->subscription_payment_total ? $result->subscription_payment_total :  $result->payment_total - $discount_line_total;
            return $submissionTotal;
        })->sum();

        return $paymentTotal;
    }



    public function donationTotal($formId, $paymentStatus = false, $searchString = false, $startDate = null, $endDate = null)
    {
        $paymentTotal = 0;
        $DB = App::make('db');
        // $query = $this->select(['currency', 'payment_total']);
        $query = $this->select(['id', 'currency', 'payment_total']);

        if ($formId) {
            $query = $query->where('form_id', $formId);
        }

        // if ($paymentStatus == 'abandoned') {
        //     $query->where('payment_status', 'pending');
        //     $query = $this->makeQueryAbandoned($query, '<', true);
        // } else {
        //     $query->where('payment_status', $paymentStatus);
        // }

        if (!empty($paymentStatus)) {
            $query = $this->paymentStatus($paymentStatus, $query);
        }

        if ($searchString) {
            $query = $this->searchString($searchString, $query);
        }

        if ($startDate && $endDate) {
            $query = $this->dateFilter($query, $startDate, $endDate);
        }

        if ($paymentStatus !== null) {
            $query->where('payment_status', $paymentStatus);
        }


        $result = $query->get()->toArray();

        $currencySettings = Form::getCurrencySettings($formId);
        $baseCurrency = $currencySettings['currency'];
        $apiKey = '';
        $rates = [];

        $hasDifferentCurrencies = 0;
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $currency = $result[$key]['currency'];
                if ($currency != $baseCurrency) {
                    $hasDifferentCurrencies = 1;
                }
            }
        }

        if (isset($currencySettings['currency_conversion_api_key']) && $currencySettings['currency_conversion_api_key'] != '') {
            $apiKey = $currencySettings['currency_conversion_api_key'];
            $cachingInterval = 24;
            if (isset($currencySettings['currency_rate_caching_interval'])) {
                $cachingInterval = Arr::get($currencySettings, 'currency_rate_caching_interval');
            }
        }
        if ($apiKey != '' && count($result) > 0 && $hasDifferentCurrencies) {
            $formController = new FormController();
            $rates = $formController->getCurrencyRates($baseCurrency, $apiKey, $cachingInterval, $formId);
            if (count($rates) > 0) {
                foreach ($result as $key => $value) {
                    $currency = $result[$key]['currency'];
                    if ($currency && $currency != $baseCurrency) {
                        $rawamount = number_format(floatval(floatval($result[$key]['payment_total'] / 100) / $rates[$currency]['value']), 2);
                        $amountFormatted = str_replace(',', '', $rawamount);
                        // Now, you can safely multiply the formatted number by 100
                        $amountInCents = $amountFormatted * 100;
                        $paymentTotal += $amountInCents;
                    } else {
                        $paymentTotal += $result[$key]['payment_total'] ? $result[$key]['payment_total'] : 0;
                    }
                }
            } else {
                $paymentTotal = 0;
            }
        }
        if ($paymentTotal == 0) {
            // Do the usual calculation
            // $query = $this->select($DB->raw('SUM(payment_total) as payment_total'));
            $query = $this->select('id', 'payment_total');

            if ($formId) {
                $query = $query->where('form_id', $formId);
            }

            if (!empty($paymentStatus)) {
                $query = $this->paymentStatus($paymentStatus, $query);
            }

            if ($searchString) {
                $query = $this->searchString($searchString, $query);
            }

            if ($startDate && $endDate) {
                $query = $this->dateFilter($query, $startDate, $endDate);
            }

            if ($paymentStatus !== null) {
                $query->where('payment_status', $paymentStatus);
            }

            //     $result = $query->first();

            //     if ($result && $result->payment_total) {
            //         $paymentTotal = $result->payment_total;
            //     }
            // }
            // if (!$paymentStatus || $paymentStatus == 'paid') {
            //     $paymentTotal += (new Subscription())->getDonationSubscriptionTotal($formId, $baseCurrency, $rates);
            // }

            $results = $query->get();

            foreach ($results as $payment) {

                $paymentTotal += $payment->payment_total;
            }
        }

        foreach ($result as $value) {
            $submissionId = $value['id'];
            if (!$paymentStatus || $paymentStatus == 'paid') {
                $paymentTotal += (new Subscription())->getSubscriptionPaymentTotal($formId, $submissionId, $searchString, $startDate, $endDate);
            }
        }

        return $paymentTotal;
    }


    public function updateSubmission($submissionId, $data)
    {
        $data['updated_at'] = current_time('mysql');
        return $this->where('id', $submissionId)->update($data);
    }

    public function getParsedSubmission($submission)
    {
        $elements = get_post_meta($submission->form_id, 'wppayform_paymentform_builder_settings', true);
        if (!$elements) {
            return array();
        }

        $parsedSubmission = array();
        $inputValues = $submission->form_data_formatted;

        foreach ($elements as $element) {
            if ($element['group'] == 'input') {
                $elementId = Arr::get($element, 'id');
                $elementValue = apply_filters(
                    'wppayform/rendering_entry_value_' . $element['type'],
                    Arr::get($inputValues, $elementId),
                    $submission,
                    $element
                );

                if (is_array($elementValue)) {
					foreach ($elementValue as $key => $value) {
						if (!is_string($value)) {
							$elementValue[$key] = '';
						}
					}
                    $elementValue = implode(', ', $elementValue);
                }
                $parsedSubmission[$elementId] = array(
                    'label' => $this->getLabel($element),
                    'value' => $elementValue,
                    'type' => $element['type']
                );
            }
        }

        return apply_filters('wppayform/parsed_entry', $parsedSubmission, $submission);
    }

    public function getUnParsedSubmission($submission)
    {
        $elements = get_post_meta($submission->form_id, 'wppayform_paymentform_builder_settings', true);
        if (!$elements) {
            return array();
        }
        $parsedSubmission = array();

        $inputValues = $submission->form_data_formatted;

        foreach ($elements as $element) {
            if ($element['group'] == 'input') {
                $elementId = Arr::get($element, 'id');
                $elementValue = Arr::get($inputValues, $elementId);

                if (is_array($elementValue)) {
                    $elementValue = implode(', ', $elementValue);
                }
                $parsedSubmission[$elementId] = array(
                    'label' => $this->getLabel($element),
                    'value' => $elementValue,
                    'type' => $element['type']
                );
            }
        }

        return apply_filters('wppayform/unparsed_entry', $parsedSubmission, $submission);
    }

    private function getLabel($element)
    {
        $elementId = Arr::get($element, 'id');
        if (!$label = Arr::get($element, 'field_options.admin_label')) {
            $label = Arr::get($element, 'field_options.label');
        }
        if (!$label) {
            $label = $elementId;
        }
        return $label;
    }

    public function deleteSubmission($submissionId)
    {
        foreach ($submissionId as $value) {
            Submission::where('id', intval($value))
                ->delete();

            OrderItem::where('submission_id', intval($value))
                ->delete();

            Refund::where('submission_id', intval($value))
                ->where('transaction_type', 'one_time')
                ->delete();

            SubmissionActivity::where('submission_id', intval($value))
                ->delete();

            Meta::where('option_id', intval($value))->delete();
        }
    }

    public function getEntryCountByPaymentStatus($formId, $paymentStatuses = array(), $period = 'total')
    {
        $query = $this->where('form_id', $formId);
        $DB = App::make('db');
        if ($paymentStatuses && count($paymentStatuses)) {
            $query->whereIn('payment_status', $paymentStatuses);
        }

        if ($period && $period != 'total') {
            $col = 'created_at';
            if ($period == 'day') {
                $year = "YEAR(`{$col}`) = YEAR(NOW())";
                $month = "MONTH(`{$col}`) = MONTH(NOW())";
                $day = "DAY(`{$col}`) = DAY(NOW())";
                $query->where($DB->raw("{$year} AND {$month} AND {$day}"));
            } elseif ($period == 'week') {
                $query->where(
                    $DB->raw("YEARWEEK(`{$col}`, 1) = YEARWEEK(CURDATE(), 1)")
                );
            } elseif ($period == 'month') {
                $year = "YEAR(`{$col}`) = YEAR(NOW())";
                $month = "MONTH(`{$col}`) = MONTH(NOW())";
                $query->where($DB->raw("{$year} AND {$month}"));
            } elseif ($period == 'year') {
                $query->where($DB->raw("YEAR(`{$col}`) = YEAR(NOW())"));
            }
        }
        return $query->count();
    }

    public function changeEntryStatus($formId, $entryId, $newStatus)
    {
        $this->where('form_id', $formId)
            ->where('id', $entryId)
            ->update(['status' => $newStatus]);
        return $newStatus;
    }

    public function updateMeta($submissionId, $metaKey, $metaValue)
    {
        $exist = Meta::where('meta_group', 'wpf_submissions')
            ->where('option_id', $submissionId)
            ->where('meta_key', $metaKey)
            ->first();

        if ($exist) {
            Meta::where('id', $exist->id)
                ->update([
                    'meta_value' => maybe_serialize($metaValue),
                    'updated_at' => current_time('mysql')
                ]);
        } else {
            Meta::create([
                'meta_key' => $metaKey,
                'option_id' => $submissionId,
                'meta_group' => $this->table,
                'meta_value' => maybe_serialize($metaValue),
                'updated_at' => current_time('mysql'),
                'created_at' => current_time('mysql'),
            ]);
        }
    }

    public function getMeta($submissionId, $metaKey, $default = '')
    {
        $exist = Meta::where('meta_group', $this->metaGroup)
            ->where('option_id', $submissionId)
            ->where('meta_key', $metaKey)
            ->first();

        if ($exist) {
            $value = maybe_unserialize($exist->meta_value);
            if ($value) {
                return $value;
            }
        }

        return $default;
    }

    public static function getByCustomerEmail()
    {
        $DB = App::make('db');
        $customers = Submission::select(
            'currency',
            'customer_email',
            'customer_name',
            $DB->raw("SUM(payment_total) as total_paid"),
            $DB->raw("COUNT(*) as submissions")
        )
            ->whereIn('payment_status', ['paid'])
            ->where('payment_total', '>', 0)
            ->groupBy(['customer_email', 'currency'])
            ->orderBy('submissions', 'desc')
            ->limit(19)
            ->get();

        return $customers;
    }
}
