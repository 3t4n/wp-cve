<?php

namespace WunderAuto;

use WunderAuto\Types\Internal\Filter;
use WunderAuto\Types\Workflow;

/**
 * Class Logger
 */
class Logger
{
    /**
     * @var string
     */
    const EMERGENCY = 'emergency';

    /**
     * @var string
     */
    const ALERT = 'alert';

    /**
     * @var string
     */
    const CRITICAL = 'critical';

    /**
     * @var string
     */
    const ERROR = 'error';

    /**
     * @var string
     */
    const WARNING = 'warning';

    /**
     * @var string
     */
    const NOTICE = 'notice';

    /**
     * @var string
     */
    const INFO = 'info';

    /**
     * @var string
     */
    const DEBUG = 'debug';

    /**
     * @var string[]
     */
    private $levels = [
        self::DEBUG,
        self::INFO,
        self::NOTICE,
        self::WARNING,
        self::ERROR,
        self::CRITICAL,
        self::ALERT,
        self::EMERGENCY,
    ];

    /**
     * @var string
     */
    private $defaultLogLevel;

    /**
     * @var string
     */
    private $session;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = substr(strrev(dechex(intval(microtime(true) * 1000000))), 0, 8);
    }

    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        // Determine log level
        $generalSettings       = get_option('wunderauto-general');
        $this->defaultLogLevel = isset($generalSettings['loglevel']) ?
            $generalSettings['loglevel'] :
            self::INFO;
        if (!in_array($this->defaultLogLevel, $this->levels)) {
            $this->defaultLogLevel = self::INFO;
        }

        $loader->addAction(
            'wunderauto_trigger_fired',
            $this,
            'triggerFired',
            10,
            2
        );

        $loader->addAction(
            'wunderauto_workflow_started',
            $this,
            'workflowStarted',
            10,
            2
        );

        $loader->addAction(
            'wunderauto_workflow_no_filters',
            $this,
            'workflowNoFilters',
            10,
            1
        );

        $loader->addAction(
            'wunderauto_evaluated_filter',
            $this,
            'evaluatedFilter',
            10,
            3
        );

        $loader->addAction(
            'wunderauto_filter_missing',
            $this,
            'missingFilter',
            10,
            2
        );

        $loader->addAction(
            'wunderauto_evaluated_filter_group',
            $this,
            'evaluatedFilterGroup',
            10,
            3
        );

        $loader->addAction(
            'wunderauto_filter_evaluation_done',
            $this,
            'evaluatedAllFilters',
            10,
            2
        );

        $loader->addAction(
            'wunderauto_doing_action',
            $this,
            'doingAction',
            10,
            3
        );

        $loader->addAction(
            'wunderauto_action_done',
            $this,
            'actionDone',
            10,
            4
        );

        $loader->addAction(
            'wunderauto_all_actions_done',
            $this,
            'actionsDone',
            10,
            2
        );
    }

    /**
     * Hook for logging triggers that fire.
     *
     * @param string    $triggerClass
     * @param \stdClass $object
     *
     * @return void
     */
    public function triggerFired($triggerClass, $object)
    {
        $wunderAuto = wa_wa();

        $level = self::DEBUG;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $resolver = $wunderAuto->createResolver([]);
        $id       = $resolver->getObjectId($object->value);
        $class    = is_object($object->value) ? get_class($object->value) : '';

        $message = sprintf(
            'Trigger fired by %s object: %s(%d)',
            $triggerClass,
            $class,
            $id
        );

        $this->log($level, $message);
    }

    /**
     * Hook for logging triggers that fire.
     *
     * @param Workflow $workflow
     * @param Resolver $resolver
     *
     * @return void
     */
    public function workflowStarted($workflow, $resolver)
    {
        $level = self::INFO;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $type   = $resolver->getFirstObjectType();
        $object = $resolver->getFirstObjectByType(is_string($type) ? $type : '');
        $id     = 0;
        if (is_object($object)) {
            $id = $resolver->getObjectId($object);
        }
        $class = is_object($object) ? get_class($object) : '';

        $message = sprintf(
            'Workflow %s started object: %s(%d)',
            $workflow->getName(),
            $class,
            $id
        );
        $this->log($level, $message);
    }

    /**
     * @param Workflow $workflow
     *
     * @return void
     */
    public function workflowNoFilters($workflow)
    {
        $level = self::INFO;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s has no filters. Continuing',
            $workflow->getName()
        );
        $this->log($level, $message);
    }

    /**
     * @param Workflow $workflow
     * @param Filter   $filter
     * @param bool     $result
     *
     * @return void
     */
    public function evaluatedFilter($workflow, $filter, $result)
    {
        $level = self::DEBUG;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. Filter %s returned %s',
            $workflow->getName(),
            $filter->filter,
            $result ? 'true' : 'false'
        );
        $this->log($level, $message, ['filter' => $filter]);
    }

    /**
     * @param Workflow $workflow
     * @param Filter   $filter
     *
     * @return void
     */
    public function missingFilter($workflow, $filter)
    {
        $level = self::ERROR;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. Filter class %s not found. Filter processing aborted',
            $workflow->getName(),
            $filter->filter
        );
        $this->log($level, $message, ['filter' => $filter]);
    }

    /**
     * @param Workflow $workflow
     * @param int      $group
     * @param bool     $result
     *
     * @return void
     */
    public function evaluatedFilterGroup($workflow, $group, $result)
    {
        $level = self::DEBUG;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. Filter group %s returned %s',
            $workflow->getName(),
            $group,
            $result ? 'true' : 'false'
        );

        $this->log($level, $message);
    }

    /**
     * @param Workflow $workflow
     * @param bool     $result
     *
     * @return void
     */
    public function evaluatedAllFilters($workflow, $result)
    {
        $level = self::INFO;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. Filter processing returned %s',
            $workflow->getName(),
            $result ? 'true' : 'false'
        );

        $this->log($level, $message);
    }

    /**
     * @param Workflow $workflow
     * @param object   $action
     * @param int      $actionNr
     *
     * @return void
     */
    public function doingAction($workflow, $action, $actionNr)
    {
        $level = self::DEBUG;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. Doing action #%s',
            $workflow->getName(),
            $actionNr
        );

        $this->log($level, $message, ['action' => $action]);
    }

    /**
     * @param Workflow $workflow
     * @param object   $action
     * @param object   $ret
     * @param int      $actionNr
     *
     * @return void
     */
    public function actionDone($workflow, $action, $ret, $actionNr)
    {
        $level = self::INFO;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. Action #%s done.',
            $workflow->getName(),
            $actionNr
        );

        $this->log($level, $message, ['action' => $action, 'return' => $ret]);
    }

    /**
     * @param Workflow $workflow
     * @param int      $actionCount
     *
     * @return void
     */
    public function actionsDone($workflow, $actionCount)
    {
        $level = self::INFO;
        if (!$this->minLevelReached($level, $this->defaultLogLevel)) {
            return;
        }

        $message = sprintf(
            'Workflow %s. All actions done. Actions completed: %s',
            $workflow->getName(),
            $actionCount
        );

        $this->log($level, $message);
    }

    /**
     * @param string $level
     * @param string $minLevel
     *
     * @return bool
     */
    private function minLevelReached($level, $minLevel)
    {
        return array_search($level, $this->levels) >= array_search($minLevel, $this->levels);
    }

    /**
     * @param string                       $level
     * @param string                       $message
     * @param array<string, string|object> $context
     *
     * @return void
     */
    private function log($level, $message, $context = [])
    {
        $wpdb = wa_get_wpdb();

        $wpdb->insert(
            $wpdb->prefix . 'wa_log',
            [
                'time'    => date('Y-m-d H:i:s', time()),
                'session' => $this->session,
                'message' => $message,
                'level'   => $level,
                'context' => json_encode($context, 0, 3),
            ]
        );
    }
}
