<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules;

class Ruleset
{
    /** @var Transformations */
    private $regular;
    /** @var Patterns */
    private $uninflected;
    /** @var Substitutions */
    private $irregular;
    public function __construct(\WPPayVendor\Doctrine\Inflector\Rules\Transformations $regular, \WPPayVendor\Doctrine\Inflector\Rules\Patterns $uninflected, \WPPayVendor\Doctrine\Inflector\Rules\Substitutions $irregular)
    {
        $this->regular = $regular;
        $this->uninflected = $uninflected;
        $this->irregular = $irregular;
    }
    public function getRegular() : \WPPayVendor\Doctrine\Inflector\Rules\Transformations
    {
        return $this->regular;
    }
    public function getUninflected() : \WPPayVendor\Doctrine\Inflector\Rules\Patterns
    {
        return $this->uninflected;
    }
    public function getIrregular() : \WPPayVendor\Doctrine\Inflector\Rules\Substitutions
    {
        return $this->irregular;
    }
}
