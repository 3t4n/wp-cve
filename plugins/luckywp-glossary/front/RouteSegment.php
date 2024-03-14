<?php

namespace luckywp\glossary\front;

use luckywp\glossary\core\base\BaseObject;

class RouteSegment extends BaseObject
{
    const ARCHIVE = 1;
    const TERM = 2;
    const STRING = 3;

    /**
     * @var string Строка
     */
    public $var;

    /**
     * @var int Тип
     */
    public $type;

    /**
     * @var string
     */
    public $prefix = '';

    /**
     * @var string
     */
    public $suffix = '';

    /**
     * @param string $segment
     */
    public function __construct($segment)
    {
        parent::__construct();

        // Строка
        $this->var = trim($segment);

        // Тип сегмента
        if ((bool)preg_match('~^([^/%]*)%(.+)%([^/%]*)$~', $this->var, $match)) {
            switch ($match[2]) {
                case 'archive':
                    $this->type = self::ARCHIVE;
                    break;
                case 'term':
                    $this->type = self::TERM;
                    break;
                default:
                    $this->type = self::STRING;
            }
            if ($this->type !== self::STRING) {
                $this->prefix = $match[1];
                $this->suffix = $match[3];
            }
        } else {
            $this->type = self::STRING;
        }
    }
}
