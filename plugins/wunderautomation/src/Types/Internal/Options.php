<?php

namespace WunderAuto\Types\Internal;

/**
 * Class Options
 */
class Options extends BaseInternalType
{
    /**
     * @var int
     */
    public $sortOrder = 5;

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        parent::__construct($state);

        $this->sortOrder = (int)$this->sortOrder;
    }
}
