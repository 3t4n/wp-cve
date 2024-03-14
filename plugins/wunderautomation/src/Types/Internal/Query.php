<?php

namespace WunderAuto\Types\Internal;

/**
 * Class Query
 */
class Query extends BaseInternalType
{
    /**
     * @var string
     */
    public $objectType = 'post';

    /**
     * @var int
     */
    public $created = 30;

    /**
     * @var string
     */
    public $createdTimeUnit = 'days';

    /**
     * @var string
     */
    public $postType = 'post';

    /**
     * @var string
     */
    public $postStatus = 'publish';

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        parent::__construct($state);

        $this->created = (int)$this->created;
        $this->sanitizeObjectProp($this, 'objectType', 'key');
        $this->sanitizeObjectProp($this, 'createdTimeUnit', 'key');
        $this->sanitizeObjectProp($this, 'postType', 'key');
        $this->sanitizeObjectProp($this, 'postStatus', 'key');
    }
}
