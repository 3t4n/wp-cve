<?php

namespace luckywp\tableOfContents\plugin\contentHandling;

class ContentHandlingDto
{

    /**
     * @var string
     */
    public $content;

    /**
     * @var bool
     */
    public $modify = false;

    /**
     * @var string
     */
    public $skipText = '';

    /**
     * @var array
     */
    public $skipLevels = [];

    /**
     * @var string
     */
    public $hashFormat = 'asheading';

    /**
     * @var bool
     */
    public $hashConvertToLowercase = false;

    /**
     * @var bool
     */
    public $hashReplaceUnderlinesToDashes = false;
}
