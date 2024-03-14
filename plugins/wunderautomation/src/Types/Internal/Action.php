<?php

namespace WunderAuto\Types\Internal;

use PhpParser\Node\Stmt\Continue_;
use WunderAuto\Types\Actions\BaseAction;

/**
 * Class Filter
 */
class Action extends BaseInternalType
{
    /**
     * @var string
     */
    public $action;

    /**
     * @var \stdClass|string
     */
    public $value;

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        parent::__construct($state);

        $this->action = str_replace('|', '\\', $this->action);
        $this->sanitizeObjectProp($this, 'action', 'text');

        if (class_exists($this->action)) {
            $action = new $this->action();
            assert($action instanceof BaseAction);
            $action->sanitizeConfig($this);
        }
    }

    /**
     * Handle json
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $property => $value) {
            switch ($property) {
                case 'action':
                    $return[$property] = BaseInternalType::$wpPostMetaMode ?
                        str_replace('\\', '|', $value) :
                        $value;
                    break;
                case 'map':
                    break;
                default:
                    $return[$property] = $value;
            }
        }

        return $return;
    }
}
