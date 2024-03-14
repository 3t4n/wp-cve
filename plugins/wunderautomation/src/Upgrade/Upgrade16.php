<?php

namespace WunderAuto\Upgrade;

use WunderAuto\WunderAuto;

/**
 * Class Upgrade16
 */
class Upgrade16
{
    /**
     * @var WunderAuto
     */
    private $wunderAuto;

    /**
     * @param WunderAuto $wunderAuto
     */
    public function __construct($wunderAuto)
    {
        $this->wunderAuto = $wunderAuto;
    }

    /**
     * Upgrade
     *
     * @return void
     */
    public function upgrade()
    {
        $workflowPosts = $this->wunderAuto->getWorkflowPosts();
        $filterMapping = $this->filterMapping16();

        foreach ($workflowPosts as $workflowPost) {
            /** @var \stdClass $state */
            $state = get_post_meta($workflowPost->ID, 'workflow_settings', true);
            if ($state->version < 3) {
                $state->steps = [];
                $key          = 0;

                // 1. Handle trigger with object parameters
                if ($state->trigger->trigger === '|WunderAuto|Types|Triggers|Webhook|Webhook') {
                    if (isset($state->trigger->value->objects)) {
                        foreach ($state->trigger->value->objects as $object) {
                            $object->required = true;
                        }
                    }
                }

                // 2. Convert the filters (if any) to step 0
                if (isset($state->filters) && count($state->filters) > 0) {
                    $state->steps[] = (object)[
                        'type'    => 'filters',
                        'key'     => $key++,
                        'filters' => $state->filters,
                    ];

                    // Convert filter class name to separate filter and object keys 0 filterObjectKey
                    foreach ($state->steps[0]->filters as &$filterGroup) {
                        foreach ($filterGroup as &$filter) {
                            $filterClass       = str_replace('|', '\\', $filter->filter);
                            $filterInfo        = $this->infoFromFilterClass($filterClass, $filterMapping, $state);
                            $filter->object    = $filterInfo->object;
                            $filter->filter    = str_replace('\\', '|', $filterInfo->class);
                            $filter->filterKey = $filter->object . '::' . $filter->filter;
                            $this->replaceFilterMultiSelectValues($filter->value);
                        }
                    }
                }

                // 3. Convert each action to its own step
                foreach ((array)$state->actions as $action) {
                    $this->replaceParameters($action->value);

                    $state->steps[] = (object)[
                        'type'   => 'action',
                        'key'    => $key++,
                        'action' => $action,
                    ];
                }
            }

            unset($state->filters);
            unset($state->actions);
            $state->version = 3;
            update_post_meta($workflowPost->ID, 'workflow_settings', $state);
        }
    }

    /**
     * @return array<string, \stdClass>
     */
    private function filterMapping16()
    {
        return [
            '\\WunderAuto\\Types\\Filters\\Post\\Title'                      => (object)['object' => 'post'],
            '\\WunderAuto\\Types\\Filters\\Post\\Content'                    => (object)['object' => 'post'],
            '\\WunderAuto\\Types\\Filters\\Post\\Type'                       => (object)['object' => 'post'],
            '\\WunderAuto\\Types\\Filters\\Post\\Status'                     => (object)['object' => 'post'],
            '\\WunderAuto\\Types\\Filters\\Post\\Owner'                      => (object)['object' => 'post'],
            '\\WunderAuto\\Types\\Filters\\Post\\Tags'                       => (object)['object' => 'post'],
            '\\WunderAuto\\Types\\Filters\\Post\\Categories'                 => (object)['object' => 'post'],

            '\\WunderAuto\\Types\\Filters\\User\\Email'                      => (object)['object' => 'user'],
            '\\WunderAuto\\Types\\Filters\\User\\Role'                       => (object)['object' => 'user'],

            '\\WunderAuto\\Types\\Filters\\CurrentUser\\Email'               => (object)[
                'object' => 'currentuser',
                'class'  => '\\WunderAuto\\Types\\Filters\\User\\Email'
            ],
            '\\WunderAuto\\Types\\Filters\\CurrentUser\\Role'                => (object)[
                'object' => 'currentuser',
                'class'  => '\\WunderAuto\\Types\\Filters\\User\\Role'
            ],

            '\\WunderAuto\\Types\\Filters\\Comment\\Type'                    => (object)['object' => 'comment'],
            '\\WunderAuto\\Types\\Filters\\Comment\\Status'                  => (object)['object' => 'comment'],
            '\\WunderAuto\\Types\\Filters\\Comment\\AuthorName'              => (object)['object' => 'comment'],
            '\\WunderAuto\\Types\\Filters\\Comment\\AuthorEmail'             => (object)['object' => 'comment'],
            '\\WunderAuto\\Types\\Filters\\Comment\\Content'                 => (object)['object' => 'comment'],

            '\\WunderAuto\\Types\\Filters\\ConfirmationLink\\Name'           => (object)['object' => 'link'],
            '\\WunderAuto\\Types\\Filters\\ConfirmationLink\\Clicks'         => (object)['object' => 'link'],

            '\\WunderAuto\\Types\\Filters\\CustomField'                      => (object)[],
            '\\WunderAuto\\Types\\Filters\\Initiator'                        => (object)[],
            '\\WunderAuto\\Types\\Filters\\IP'                               => (object)[],
            '\\WunderAuto\\Types\\Filters\\RefererUrl'                       => (object)[],
            '\\WunderAuto\\Types\\Filters\\RefererPost'                      => (object)[],
            '\\WunderAuto\\Types\\Filters\\Option'                           => (object)[],

            '\\WunderAuto\\Types\\Filters\\Order\\Status'                    => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\Products'                  => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ProductTypes'              => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ProductTags'               => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ProductCategories'         => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\VirtualProduct'            => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\DownloadableProduct'       => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\Total'                     => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ShippingMethod'            => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ShippingZone'              => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\CreatedVia'                => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\IsGuest'                   => (object)['object' => 'order'],

            '\\WunderAuto\\Types\\Filters\\Order\\BillingEmail'              => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\BillingCountry'            => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\BillingState'              => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\BillingCity'               => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\BillingCompany'            => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\PaymentMethod'             => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ShippingCountry'           => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ShippingState'             => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ShippingCity'              => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Order\\ShippingCompany'           => (object)['object' => 'order'],

            '\\WunderAuto\\Types\\Filters\\Customer\\TotalOrderCount'        => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Customer\\CompletedOrderCount'    => (object)['object' => 'order'],
            '\\WunderAuto\\Types\\Filters\\Customer\\CompletedOrderTotal'    => (object)['object' => 'order'],

            '\\WunderAuto\\Types\\Filters\\Comment\\IsCustomerNote'          => (object)['object' => 'comment'],
            '\\WunderAuto\\Types\\Filters\\Comment\\IsOrderStatusNote'       => (object)['object' => 'comment'],
            '\\WunderAuto\\Types\\Filters\\Comment\\IsWooCommerceSystemNote' => (object)['object' => 'comment'],

            // CCF7
            '\\WunderAutoCCF7\\Filters\\Form'                                => (object)['object' => 'ccf7'],
            '\\WunderAutoCCF7\\Filters\\FormName'                            => (object)['object' => 'ccf7'],
            '\\WunderAutoCCF7\\Filters\\FormTitle'                           => (object)['object' => 'ccf7'],
            '\\WunderAutoCCF7\\Filters\\Field'                               => (object)['object' => 'ccf7'],

            // MailChimp
            '\\WunderAutoMailChimp\\Filters\\ListId'                         => (object)['object' => 'mc-webhook'],
            '\\WunderAutoMailChimp\\Filters\\Email'                          => (object)['object' => 'mc-webhook'],
            '\\WunderAutoMailChimp\\Filters\\Type'                           => (object)['object' => 'mc-webhook'],

            // MailPoet
            '\\WunderAutoMailPoet\\Filters\\Email'                           => (object)['object' => 'subscriber'],
            '\\WunderAutoMailPoet\\Filters\\Segment'                         => (object)['object' => 'list'],
            '\\WunderAutoMailPoet\\Filters\\Status'                          => (object)['object' => 'oldstatus'],
            '\\WunderAutoMailPoet\\Filters\\OldStatus'                       => (object)['object' => 'subscriber'],
            '\\WunderAutoMailPoet\\Filters\\SubscriberSegments'              => (object)['object' => 'subscriber'],
            '\\WunderAutoMailPoet\\Filters\\CustomField'                     => (object)['object' => 'subscriber'],

            // WPForms
            '\\WunderAutoWPForms\\Filters\\Form'                             => (object)['object' => 'wpform'],
            '\\WunderAutoWPForms\\Filters\\FieldId'                          => (object)['object' => 'wpform'],
            '\\WunderAutoWPForms\\Filters\\FieldLabel'                       => (object)['object' => 'wpform'],
        ];
    }

    /**
     * Use heuristics to get a correct class and object name
     * for each existing filter row
     *
     * @param string                   $filterClass
     * @param array<string, \stdClass> $filterMapping
     * @param \stdClass                $state
     *
     * @return \stdClass
     */
    private function infoFromFilterClass($filterClass, $filterMapping, $state)
    {
        $ret           = (object)['object' => '', 'class' => $filterClass];
        $triggerParts  = explode('|', $state->trigger->trigger);
        $namespacePart = isset($triggerParts[4]) ? $triggerParts[4] : '';
        if ($namespacePart == 'Webhook') {
            $firstObject   = reset($state->trigger->value->objects);
            $namespacePart = ucwords($firstObject->type);
        }

        if ($filterClass === '\\WunderAuto\\Types\\Filters\\CustomField') {
            switch (strtolower($namespacePart)) {
                case 'post':
                    $ret->object = 'post';
                    $ret->class  = '\\WunderAuto\\Types\\Filters\\Post\\CustomField';
                    break;
                case 'user':
                    $ret->object = 'user';
                    $ret->class  = '\\WunderAuto\\Types\\Filters\\User\\CustomField';
                    break;
                case 'comment':
                    $ret->object = 'comment';
                    $ret->class  = '\\WunderAuto\\Types\\Filters\\Comment\\CustomField';
                    break;
                case 'order':
                    $ret->object = 'order';
                    $ret->class  = '\\WunderAuto\\Types\\Filters\\Order\\CustomField';
                    break;
            }

            return $ret;
        }

        if ($filterClass === '\\WunderAuto\\Types\\Filters\\AdvancedCustomFieldPost') {
            $ret->object = strtolower($namespacePart);
            $ret->class  = "\\WunderAuto\\Types\\Filters\\$namespacePart\\AdvancedCustomField";
            return $ret;
        }

        if ($filterClass === '\\WunderAuto\\Types\\Filters\\AdvancedCustomFieldUser') {
            $ret->object = 'user';
            $ret->class  = "\\WunderAuto\\Types\\Filters\\User\\AdvancedCustomField";
            return $ret;
        }

        if (isset($filterMapping[$filterClass])) {
            $ret->object = $filterMapping[$filterClass]->object;
            $ret->class  = isset($filterMapping[$filterClass]->class) ?
                $filterMapping[$filterClass]->class :
                $filterClass;
        }

        return $ret;
    }

    /**
     * @param \stdClass|array<int, mixed>|string|null $obj
     *
     * @return void
     */
    private function replaceFilterMultiSelectValues($obj)
    {
        if (is_object($obj) && isset($obj->code)) {
            $obj->value = $obj->code;
            unset($obj->code);
        }

        if (is_array($obj) || is_object($obj)) {
            $is_object = is_object($obj);
            foreach ((array)$obj as &$value) {
                $this->replaceFilterMultiSelectValues($value);
            }
            $obj = $is_object ? (object)$obj : $obj;
        }
    }

    /**
     * @param \stdClass|array<int, mixed>|string|null $obj
     *
     * @return void
     */
    private function replaceParameters(&$obj)
    {
        if (is_object($obj) || is_array($obj)) {
            foreach ($obj as &$value) { // @phpstan-ignore-line
                $this->replaceParameters($value);
            }
        }

        if (is_string($obj)) {
            $find    = [
                'site.name',
                'site.url',
                'remote.ip',
                'referer.url',
                'referer.postid',
                'email.customer_details',
                'email.meta'
            ];
            $replace = [
                'site_name',
                'site_url',
                'remote_ip',
                'referer_url',
                'referer_postid',
                'email_customer_details',
                'email_meta'
            ];
            $obj     = str_replace($find, $replace, $obj);
        }
    }
}
