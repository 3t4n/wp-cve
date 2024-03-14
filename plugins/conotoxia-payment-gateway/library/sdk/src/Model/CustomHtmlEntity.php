<?php

namespace CKPL\Pay\Model;

/**
 * Class HtmlEntity
 * @package CKPL\Pay\Model
 */
class CustomHtmlEntity
{
    /**
     * @var string
     */
    private $characterEntity;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var string
     */
    private $unicode;

    /**
     * HtmlEntity constructor.
     *
     * @param string $characterEntity
     * @param string $regex
     * @param string $unicode
     */
    public function __construct(string $characterEntity, string $regex, string $unicode)
    {
        $this->characterEntity = $characterEntity;
        $this->regex = $regex;
        $this->unicode = $unicode;
    }

    /**
     * @return string
     */
    public function getCharacterEntity(): string
    {
        return $this->characterEntity;
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @return string
     */
    public function getUnicode(): string
    {
        return $this->unicode;
    }
}
