<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\Portuguese;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
use WPPayVendor\Doctrine\Inflector\Rules\Substitution;
use WPPayVendor\Doctrine\Inflector\Rules\Transformation;
use WPPayVendor\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /** @return Transformation[] */
    public static function getSingular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(g|)ases$/i'), '\\1ás'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(japon|escoc|ingl|dinamarqu|fregu|portugu)eses$/i'), '\\1ês'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(ae|ao|oe)s$/'), 'ao'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(ãe|ão|õe)s$/'), 'ão'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(.*[^s]s)es$/i'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/sses$/i'), 'sse'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ns$/i'), 'm'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(r|t|f|v)is$/i'), '\\1il'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/uis$/i'), 'ul'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ois$/i'), 'ol'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/eis$/i'), 'ei'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/éis$/i'), 'el'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([^p])ais$/i'), '\\1al'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(r|z)es$/i'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(á|gá)s$/i'), '\\1s'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([^ê])s$/i'), '\\1'));
    }
    /** @return Transformation[] */
    public static function getPlural() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(alem|c|p)ao$/i'), '\\1aes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(irm|m)ao$/i'), '\\1aos'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ao$/i'), 'oes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(alem|c|p)ão$/i'), '\\1ães'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(irm|m)ão$/i'), '\\1ãos'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ão$/i'), 'ões'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(|g)ás$/i'), '\\1ases'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/^(japon|escoc|ingl|dinamarqu|fregu|portugu)ês$/i'), '\\1eses'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/m$/i'), 'ns'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/([^aeou])il$/i'), '\\1is'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ul$/i'), 'uis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/ol$/i'), 'ois'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/el$/i'), 'eis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/al$/i'), 'ais'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(z|r)$/i'), '\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/(s)$/i'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('/$/'), 's'));
    }
    /** @return Substitution[] */
    public static function getIrregular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('abdomen'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('abdomens')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('alemão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('alemães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('artesã'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('artesãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('álcool'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('álcoois')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('árvore'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('árvores')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('bencão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('bencãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('campus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('campi')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cadáver'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cadáveres')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('capelão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('capelães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('capitão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('capitães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('chão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('chãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('charlatão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('charlatães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cidadão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cidadãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('consul'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('consules')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cristão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cristãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('difícil'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('difíceis')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('email'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('emails')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('escrivão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('escrivães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('fóssil'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('fósseis')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('gás'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('gases')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('germens'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('germen')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('grão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('grãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('hífen'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('hífens')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('irmão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('irmãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('liquens'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('liquen')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('mal'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('males')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('mão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('mãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('orfão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('orfãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('país'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('países')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('pai'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('pais')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('pão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('pães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('projétil'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('projéteis')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('réptil'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('répteis')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('sacristão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('sacristães')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('sotão'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('sotãos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('tabelião'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('tabeliães')));
    }
}
