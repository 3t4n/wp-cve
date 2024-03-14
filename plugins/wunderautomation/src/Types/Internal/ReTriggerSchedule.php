<?php

namespace WunderAuto\Types\Internal;

/**
 * Class ReTriggerSchedule
 */
class ReTriggerSchedule extends BaseInternalType
{
    /**
     * @var string
     */
    public $frequency = 'day';

    /**
     * @var int
     */
    public $frequencyHour = 6;

    /**
     * @var int
     */
    public $frequencyMinute = 0;

    /**
     * @var int[]
     */
    public $weekDays = [];

    /**
     * @var int[]
     */
    public $monthDays = [];

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        parent::__construct($state);

        $this->frequencyHour   = (int)$this->frequencyHour;
        $this->frequencyMinute = (int)$this->frequencyMinute;

        $this->sanitizeObjectProp($this, 'frequency', 'key');
        $this->sanitizeObjectProp($this, 'frequency', 'key');
        $this->sanitizeValueArray($this, 'weekDays', 'int');
        $this->sanitizeValueArray($this, 'monthDays', 'int');
    }
}
