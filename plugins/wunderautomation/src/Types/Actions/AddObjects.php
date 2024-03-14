<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class AddObjects
 */
class AddObjects extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Add objects', 'wunderauto');
        $this->description = __('Add objects and parameters to the workflow', 'wunderauto');
        $this->group       = 'Advanced';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectArray(
            $config->value,
            'objectRows',
            ['type' => 'key', 'name' => 'text', 'expression' => 'text']
        );
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $rows = (array)$this->get('value.objectRows');
        foreach ($rows as $row) {
            if (!isset($row->type) || !isset($row->name) || !isset($row->expression)) {
                continue;
            }

            $type = (string)$row->type;
            $name = (string)$row->name;
            $id   = $this->resolver->resolveField($row->expression);

            $this->resolver->addObjectById($type, $name, $id);
        }

        return true;
    }
}
