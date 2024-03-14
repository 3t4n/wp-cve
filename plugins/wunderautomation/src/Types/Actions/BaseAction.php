<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Resolver;
use WunderAuto\Types\BaseWorkflowEntity;
use WunderAuto\Types\Internal\Action;

/**
 * Class BaseAction
 */
class BaseAction extends BaseWorkflowEntity
{
    /**
     * An action can add objects to the workflow context (newuser, newpost etc.)
     *
     * @var array<int, \stdClass>
     */
    public $emittedObjects = [];

    /**
     * Link to a specific page/section in the documentation
     *
     * @var string|null
     */
    public $docLink = null;

    /**
     * Link text for the above
     *
     * @var string
     */
    public $docLinkText = "Documentation [extermal link]";

    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var Action
     */
    protected $actionConfig;

    /**
     * Perform the action
     *
     * @return mixed
     */
    public function doAction()
    {
        return null;
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function setActionConfig($config)
    {
        $this->actionConfig = $config;
    }

    /**
     * @param Resolver $resolver
     *
     * @return void
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $path
     * @param mixed  $default
     * @param string $dataType
     *
     * @return mixed
     */
    public function getResolved($path, $default = null, $dataType = 'string')
    {
        return $this->resolver->resolveField($this->get($path, $default), $dataType);
    }

    /**
     * @param string      $path
     * @param string|null $default
     *
     * @return mixed
     */
    public function get($path, $default = null)
    {
        $parts = explode('.', $path);
        $tmp   = $this->actionConfig;
        foreach ($parts as $part) {
            if (!isset($tmp->$part)) {
                return $default;
            }
            $tmp = $tmp->$part;
        }
        return $tmp;
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
    }

    /**
     * @param string $id
     * @param string $type
     * @param string $description
     * @param bool   $transfer
     *
     * @return void
     */
    protected function addProvidedObject($id, $type, $description, $transfer = true)
    {
        $this->emittedObjects[] = (object)[
            'id'          => $id,
            'type'        => $type,
            'description' => $description,
            'transfer'    => $transfer,
            'source'      => 'action',
            'sourceNr'    => null,
        ];
    }
}
