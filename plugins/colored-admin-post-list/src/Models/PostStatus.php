<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Models;

class PostStatus
{
    private string $optionKey;

    private string $label;

    private string $name;

    private ?string $defaultColor = null;

    /**
     * @param string $label
     * @param string $name
     */
    public function __construct(string $label, string $name, string $defaultColor = null)
    {
        $this->optionKey = "capl-color-" . sanitize_key($name);
        $this->label = $label;
        $this->name = $name;
        $this->defaultColor = $defaultColor;
    }


    /**
     * @return string
     */
    public function getOptionKey(): string
    {
        return $this->optionKey;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDefaultColor(): ?string
    {
        return $this->defaultColor;
    }
}
