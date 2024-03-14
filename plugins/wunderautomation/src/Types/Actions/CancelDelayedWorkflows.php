<?php

namespace WunderAuto\Types\Actions;

/**
 * Class CancelDelayedWorkflow
 */
class CancelDelayedWorkflows extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Cancel delayed workflows', 'wunderauto');
        $this->description = __(
            'Cancel delayed workflows that would do work on an object provided in this workflow',
            'wunderauto'
        );
        $this->group       = 'Advanced';
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $wpdb = wa_get_wpdb();

        $workflowId = $this->get('value.workflowId');
        $scope      = $this->get('value.scope');
        $objectType = $this->get('value.objectType');

        if (!(int)$workflowId > 0) {
            return false;
        }

        if (!in_array($scope, ['all', 'hasTheSame'])) {
            return false;
        }

        if ($scope === 'all') {
            /** @var string $sql */
            $sql = $wpdb->prepare(
                "delete from {$wpdb->prefix}wa_queue WHERE workflow_id=%s",
                $workflowId
            );
            $wpdb->query($sql);
            return true;
        }

        if ($scope === 'hasTheSame') {
            $compType   = $this->get('value.objectType');
            $compObject = $this->resolver->getObject($compType);
            $compId     = $this->resolver->getObjectId($compObject); // @phpstan-ignore-line
            $delete     = [];

            /** @var string $sql */
            $sql = $wpdb->prepare(
                "select id, args from {$wpdb->prefix}wa_queue WHERE workflow_id=%s",
                $workflowId
            );

            /** @var array<int, \stdClass> $rows */
            $rows = $wpdb->get_results($sql, 'OBJECT_K');

            foreach ($rows as $row) {
                $objects = json_decode($row->args);
                foreach ($objects as $object) {
                    if ($object->type == $compType && $object->id == $compId) {
                        $delete[] = $row->id;
                    }
                }
            }
            if (count($delete) > 0) {
                $sql = "delete from {$wpdb->prefix}wa_queue WHERE id in(" . join(',', $delete) . ")";
                $wpdb->query($sql);
            }
            return true;
        }

        return false;
    }
}
