<?php

namespace Memsource\Dto;

final class ContentSettingsDto extends AbstractDto
{
    protected $id;
    protected $hash;
    protected $contentId;
    protected $contentType;
    protected $send;

    public function getId(): string
    {
        return $this->id;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getMetaKey(): string
    {
        return $this->contentId;
    }

    public function getMetaType(): string
    {
        return $this->contentType;
    }

    public function exportForTranslation(): bool
    {
        return $this->send !== '0';
    }
}
