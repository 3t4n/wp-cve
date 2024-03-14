<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\Turkish;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
use WPPayVendor\Doctrine\Inflector\Rules\Substitution;
use WPPayVendor\Doctrine\Inflector\Rules\Transformation;
use WPPayVendor\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /** @return Transformation[] */
    public static function getSingular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/l[ae]r$/i'), ''));
    }
    /** @return Transformation[] */
    public static function getPlural() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([eöiü][^aoıueöiü]{0,6})$/u'), '\\1ler'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([aoıu][^aoıueöiü]{0,6})$/u'), '\\1lar'));
    }
    /** @return Substitution[] */
    public static function getIrregular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('ben'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('biz')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('sen'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('siz')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('o'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('onlar')));
    }
}
