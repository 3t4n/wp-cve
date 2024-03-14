<?php

namespace WunderAuto\Types\Internal;

/**
 * Class Schedule
 */
class Schedule extends BaseInternalType
{
    /**
     * @var string
     */
    public $when = 'direct';

    /**
     * @var int
     */
    public $delayFor = 12;

    /**
     * @var string
     */
    public $delayTimeUnit = 'hours';

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        parent::__construct($state);

        $this->sanitizeObjectProp($this, 'when', 'key');
        $this->sanitizeObjectProp($this, 'delayFor', 'int');
        $this->sanitizeObjectProp($this, 'delayTimeUnit', 'key');
    }
}
