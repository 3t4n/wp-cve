<?php

namespace Memsource\Dto;

use Memsource\Service\CustomFields\CustomFieldsService;

final class MetaKeyDto extends AbstractDto
{
    public const TYPE_DEFAULT = 'custom_field';
    public const TYPE_POST = 'post';
    public const TYPE_TERM = 'term';

    protected $id;
    protected $name;
    protected $value;
    protected $type;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        if ($this->type === self::TYPE_DEFAULT) {
            return self::TYPE_POST;
        }

        return $this->type;
    }

    public function getHash(): string
    {
        return CustomFieldsService::calculateHash($this->name, $this->getType());
    }
}
