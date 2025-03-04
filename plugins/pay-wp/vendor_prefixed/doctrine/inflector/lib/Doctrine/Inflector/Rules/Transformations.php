<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules;

use WPPayVendor\Doctrine\Inflector\WordInflector;
class Transformations implements \WPPayVendor\Doctrine\Inflector\WordInflector
{
    /** @var Transformation[] */
    private $transformations;
    public function __construct(\WPPayVendor\Doctrine\Inflector\Rules\Transformation ...$transformations)
    {
        $this->transformations = $transformations;
    }
    public function inflect(string $word) : string
    {
        foreach ($this->transformations as $transformation) {
            if ($transformation->getPattern()->matches($word)) {
                return $transformation->inflect($word);
            }
        }
        return $word;
    }
}
