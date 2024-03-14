<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class ChangeStatus
 */
class ChangeStatus extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Change status', 'wunderauto');
        $this->description = __('Change / update post status', 'wunderauto');
        $this->group       = 'WordPress';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'newStatus', 'key');
        $config->sanitizeObjectProp($config->value, 'type', 'key');
    }

    /**
     * Do the action
     *
     * @return void
     */
    public function doAction()
    {
        $target = $this->actionConfig->value;
        if (!($target instanceof \stdClass)) {
            return;
        }

        $status = $target->newStatus;
        $type   = $target->type;
        $object = $this->resolver->getObject($type);
        if (is_null($object)) {
            return;
        }

        switch ($type) {
            case 'comment':
                if (!($object instanceof \WP_Comment)) {
                    break;
                }
                $id = (int)$object->comment_ID;
                wp_set_comment_status($id, $status);
                break;
            case 'order':
                $manual = (bool)$this->getResolved('value.manualStatusChange', false);
                if (!($object instanceof \WC_Order)) {
                    break;
                }
                $object->update_status($status, '', $manual);
                break;
            default:
                $id = $this->resolver->getObjectId($object);
                if (!($object instanceof \WP_Post)) {
                    break;
                }

                wp_update_post([
                    'ID'          => $id,
                    'post_status' => $status,
                ]);
                break;
        }
    }
}
