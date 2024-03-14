<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\Spanish;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
use WPPayVendor\Doctrine\Inflector\Rules\Substitution;
use WPPayVendor\Doctrine\Inflector\Rules\Transformation;
use WPPayVendor\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /** @return Transformation[] */
    public static function getSingular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ereses$/'), 'erés'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/iones$/'), 'ión'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ces$/'), 'z'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/es$/'), ''));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/s$/'), ''));
    }
    /** @return Transformation[] */
    public static function getPlural() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ú([sn])$/i'), 'WPPayVendor\\u\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ó([sn])$/i'), 'WPPayVendor\\o\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/í([sn])$/i'), 'WPPayVendor\\i\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/é([sn])$/i'), 'WPPayVendor\\e\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/á([sn])$/i'), 'WPPayVendor\\a\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/z$/i'), 'ces'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([aeiou]s)$/i'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([^aeéiou])$/i'), '\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/$/'), 's'));
    }
    /** @return Substitution[] */
    public static function getIrregular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('el'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('los')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('papá'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('papás')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('mamá'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('mamás')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('sofá'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('sofás')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('mes'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('meses')));
    }
}
