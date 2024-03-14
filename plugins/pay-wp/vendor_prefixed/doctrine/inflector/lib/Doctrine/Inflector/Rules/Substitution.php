<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules;

final class Substitution
{
    /** @var Word */
    private $from;
    /** @var Word */
    private $to;
    public function __construct(\WPPayVendor\Doctrine\Inflector\Rules\Word $from, \WPPayVendor\Doctrine\Inflector\Rules\Word $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    public function getFrom() : \WPPayVendor\Doctrine\Inflector\Rules\Word
    {
        return $this->from;
    }
    public function getTo() : \WPPayVendor\Doctrine\Inflector\Rules\Word
    {
        return $this->to;
    }
}
