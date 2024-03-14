<?php

namespace FluentSupport\App\Services;

use FluentSupport\App\Models\Ticket;

class TicketQueryService
{
    private $args = [];

    private $model = null;

    public function __construct($args = [])
    {
        $this->args = wp_parse_args($args, [
            'with'               => [],
            'filter_type'        => 'simple',
            'filters_groups'     => [],
            'filters_groups_raw' => [],
            'sort_by'            => 'id',
            'sort_type'          => 'DESC',
            'limit'              => false,
            'offset'             => false,
            'simple_filters'     => [],
            'customer_id'        => '',
            'search'             => ''
        ]);
        $this->setupQuery();
    }

    /**
     *setupQuery method will prepare query to get the tickets based on the selected filter options
     */
    private function setupQuery()
    {
        //If filter conditions added in advanced filtering
        if ($this->args['filters_groups_raw']) {
            $this->formatAdvancedFilters();
        }

        $ticketsQuery = Ticket::with($this->args['with']);

        if ($this->args['filter_type'] == 'advanced') {
            $filtersGroups = $this->args['filters_groups'];
            foreach ($filtersGroups as $groupIndex => $group) {
                $method = 'orWhere';
                if ($groupIndex == 0) {
                    $method = 'where';
                }
                $ticketsQuery->{$method}(function ($q) use ($group) {
                    foreach ($group as $providerName => $items) {
                        //Call doSearchForAdvancedFilter/filterTicketByUser dynamically from pro
                        do_action_ref_array('fluent_support\tickets_filter_' . $providerName, [&$q, $items]);
                    }
                });
            }
        } else {

            if($simpleFilters = $this->args['simple_filters']) {
                $ticketsQuery->applyFilters($simpleFilters);
            }

            if($customerId = $this->args['customer_id']) {
               $ticketsQuery->where('customer_id', $customerId);
            }

            if ($search = $this->args['search']) {
                $ticketsQuery->searchBy($search);
            }
        }

        $ticketsQuery->orderBy($this->args['sort_by'], $this->args['sort_type']);

        $this->model = $ticketsQuery;
    }

    public function paginate()
    {
        return $this->model->paginate();
    }

    public function get()
    {
        $model = $this->model;

        if($limit = $this->args['limit']) {
            $model = $model->limit($limit);
        }
        if($offset = $this->args['offset']) {
            $model = $model->offset($offset);
        }

        return $model->get();
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     *formatAdvancedFilters method will format the requested variable for advanced filtering
     */
    private function formatAdvancedFilters()
    {
        $filters = $this->args['filters_groups_raw'];

        $groups = [];
        //$filterGroup mean all items under And, OR
        foreach ($filters as $filterGroup) {
            $group = [];
            //$filterItem is individual item under a group(And/OR)
            foreach ($filterGroup as $filterItem) {
                if (count($filterItem['source']) != 2 || empty($filterItem['source'][0]) || empty($filterItem['source'][1]) || empty($filterItem['operator'])) {
                    continue;
                }

                $provider = $filterItem['source'][0];

                if (!isset($group[$provider])) {
                    $group[$provider] = [];
                }

                $property = $filterItem['source'][1];

                $group[$provider][] = [
                    'property' => $property,
                    'operator' => $filterItem['operator'],
                    'value'    => $filterItem['value']
                ];
            }

            if ($group) {
                $groups[] = $group;
            }
        }

        $this->args['filters_groups'] = $groups;
    }
}
