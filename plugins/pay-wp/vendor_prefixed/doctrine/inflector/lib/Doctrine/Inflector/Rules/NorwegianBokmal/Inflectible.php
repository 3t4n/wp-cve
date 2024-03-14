<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
use WPPayVendor\Doctrine\Inflector\Rules\Substitution;
use WPPayVendor\Doctrine\Inflector\Rules\Transformation;
use WPPayVendor\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /** @return Transformation[] */
    public static function getSingular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/re$/i'), 'r'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/er$/i'), ''));
    }
    /** @return Transformation[] */
    public static function getPlural() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/e$/i'), 'er'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/r$/i'), 're'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/$/'), 'er'));
    }
    /** @return Substitution[] */
    public static function getIrregular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('konto'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('konti')));
    }
}
