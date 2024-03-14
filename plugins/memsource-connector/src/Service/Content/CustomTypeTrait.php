<?php

namespace Memsource\Service\Content;

trait CustomTypeTrait
{
    /** @var string */
    protected $label;

    /** @var string */
    protected $type;

    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
