<?php

namespace WunderAuto\Types\Triggers;

use WunderAuto\Types\BaseWorkflowEntity;
use WunderAuto\Types\Internal\WorkflowState;
use WunderAuto\Types\Workflow;

/**
 * Class BaseTrigger
 */
class BaseTrigger extends BaseWorkflowEntity
{
    /**
     * @var array<int, \stdClass>
     */
    public $providedObjects = [];

    /**
     * @var object|null
     */
    public $defaultValue = null;

    /**
     * @var bool
     */
    public $supportsDelayedWorkflow = true;

    /**
     * @var bool
     */
    public $supportsOnlyOnce = true;

    /**
     * @var bool
     */
    protected $registered = false;

    /**
     * @var array<int, mixed>
     */
    protected $triggeredIds = [];

    /**
     * @var Workflow|null
     */
    protected $workflow = null;

    /**
     * BaseTrigger
     */
    public function __construct()
    {
        $this->defaultValue = (object)[];
    }

    /**
     * Overridden by child classes
     *
     * @return void
     */
    public function registerHooks()
    {
        return;
    }

    /**
     * Setter for workflow object
     *
     * @param Workflow $workflow
     *
     * @return void
     */
    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * @return array<int, \stdClass>
     */
    public function getProvidedObjectTypes()
    {
        return $this->providedObjects;
    }

    /**
     * @param int           $postId
     * @param Workflowstate $workflowSettings
     *
     * @return void
     */
    public function saveWunderAutomationWorkflow($postId, $workflowSettings)
    {
        return;
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $wunderAuto = wa_wa();

        do_action('wunderauto/trigger/initialize', $this);
        $tags = $wunderAuto->getWordPressFilterTags('wunderauto/trigger/initialize', $this);
        foreach ($tags as $tag) {
            do_action($tag, $this);
        }
    }

    /**
     * @param array<string, mixed> $objects
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function doTrigger($objects, $args = [])
    {
        $wunderAuto = wa_wa();

        $resolverObjects = $this->getResolverObjects($objects);

        $className = '\\' . get_class($this);
        $scheduler = $wunderAuto->getScheduler();
        $scheduler->doTrigger($className, $resolverObjects, $args);
    }

    /**
     * Check if a workflow should run based on the arguments supplied by this
     * trigger at runtime.
     *
     * @param Workflow             $workflow
     * @param array<string, mixed> $triggerArgs
     *
     * @return bool
     */
    public function runThisWorkflow($workflow, $triggerArgs)
    {
        return true;
    }

    /**
     * @param array<string, object> $objects
     *
     * @return array<string, mixed>
     */
    protected function getResolverObjects($objects)
    {
        $resolverObjects = [];
        foreach ($objects as $id => $object) {
            foreach ($this->providedObjects as $providedObject) {
                if ($id === $providedObject->id) {
                    $resolverObjects[$id] = (object)[
                        'id'       => $id,
                        'value'    => $object,
                        'type'     => $providedObject->type,
                        'transfer' => $providedObject->transfer,
                    ];
                }
            }
        }

        return $resolverObjects;
    }

    /**
     * @param string $id
     * @param string $type
     * @param string $description
     * @param bool   $transfer
     *
     * @return void
     */
    public function addProvidedObject($id, $type, $description, $transfer = true)
    {
        $this->providedObjects[] = (object)[
            'id'          => $id,
            'type'        => $type,
            'description' => $description,
            'transfer'    => $transfer,
        ];
    }
}
