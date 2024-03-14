<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\English;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
use WPPayVendor\Doctrine\Inflector\Rules\Substitution;
use WPPayVendor\Doctrine\Inflector\Rules\Transformation;
use WPPayVendor\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /** @return Transformation[] */
    public static function getSingular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(s)tatuses$'), 'WPPayVendor\\1\\2tatus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(s)tatus$'), 'WPPayVendor\\1\\2tatus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(c)ampus$'), 'WPPayVendor\\1\\2ampus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('^(.*)(menu)s$'), 'WPPayVendor\\1\\2'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(quiz)zes$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(matr)ices$'), '\\1ix'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(vert|ind)ices$'), '\\1ex'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('^(ox)en'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(alias)(es)*$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(buffal|her|potat|tomat|volcan)oes$'), '\\1o'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$'), '\\1us'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([ftw]ax)es'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(analys|ax|cris|test|thes)es$'), '\\1is'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(shoe|slave)s$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(o)es$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('ouses$'), 'ouse'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([^a])uses$'), '\\1us'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([m|l])ice$'), '\\1ouse'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(x|ch|ss|sh)es$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(m)ovies$'), 'WPPayVendor\\1\\2ovie'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(s)eries$'), 'WPPayVendor\\1\\2eries'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([^aeiouy]|qu)ies$'), '\\1y'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([lr])ves$'), '\\1f'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(tive)s$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(hive)s$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(drive)s$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(dive)s$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(olive)s$'), '\\1'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([^fo])ves$'), '\\1fe'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(^analy)ses$'), '\\1sis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$'), 'WPPayVendor\\1\\2sis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(tax)a$'), '\\1on'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(c)riteria$'), '\\1riterion'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([ti])a(?<!regatta)$'), '\\1um'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(p)eople$'), 'WPPayVendor\\1\\2erson'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(m)en$'), '\\1an'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(c)hildren$'), 'WPPayVendor\\1\\2hild'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(f)eet$'), '\\1oot'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(n)ews$'), 'WPPayVendor\\1\\2ews'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('eaus$'), 'eau'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('^tights$'), 'tights'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('^shorts$'), 'shorts'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('s$'), ''));
    }
    /** @return Transformation[] */
    public static function getPlural() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(s)tatus$'), 'WPPayVendor\\1\\2tatuses'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(quiz)$'), '\\1zes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('^(ox)$'), 'WPPayVendor\\1\\2en'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([m|l])ouse$'), '\\1ice'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(matr|vert|ind)(ix|ex)$'), '\\1ices'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(x|ch|ss|sh)$'), '\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([^aeiouy]|qu)y$'), '\\1ies'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(hive|gulf)$'), '\\1s'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(?:([^f])fe|([lr])f)$'), 'WPPayVendor\\1\\2ves'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sis$'), 'ses'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('([ti])um$'), '\\1a'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(tax)on$'), '\\1a'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(c)riterion$'), '\\1riteria'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(p)erson$'), '\\1eople'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(m)an$'), '\\1en'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(c)hild$'), '\\1hildren'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(f)oot$'), '\\1eet'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(buffal|her|potat|tomat|volcan)o$'), 'WPPayVendor\\1\\2oes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$'), '\\1i'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('us$'), 'uses'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(alias)$'), '\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('(analys|ax|cris|test|thes)is$'), '\\1es'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('s$'), 's'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('^$'), ''));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Transformation(new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('$'), 's'));
    }
    /** @return Substitution[] */
    public static function getIrregular() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('atlas'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('atlases')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('axis'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('axes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('axe'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('axes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('beef'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('beefs')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('blouse'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('blouses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('brother'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('brothers')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cafe'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cafes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cave'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('caves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('chateau'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('chateaux')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('niveau'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('niveaux')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('child'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('children')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('canvas'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('canvases')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cookie'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cookies')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('brownie'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('brownies')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('corpus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('corpuses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cow'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('cows')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('criterion'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('criteria')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('curriculum'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('curricula')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('demo'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('demos')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('domino'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('dominoes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('echo'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('echoes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('epoch'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('epochs')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('foot'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('feet')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('fungus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('fungi')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('ganglion'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('ganglions')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('gas'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('gases')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('genie'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('genies')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('genus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('genera')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('goose'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('geese')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('graffito'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('graffiti')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('hippopotamus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('hippopotami')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('hoof'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('hoofs')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('human'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('humans')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('iris'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('irises')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('larva'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('larvae')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('leaf'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('leaves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('lens'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('lenses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('loaf'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('loaves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('man'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('men')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('medium'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('media')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('memorandum'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('memoranda')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('money'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('monies')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('mongoose'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('mongooses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('motto'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('mottoes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('move'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('moves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('mythos'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('mythoi')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('niche'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('niches')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('nucleus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('nuclei')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('numen'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('numina')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('occiput'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('occiputs')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('octopus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('octopuses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('opus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('opuses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('ox'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('oxen')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('passerby'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('passersby')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('penis'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('penises')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('person'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('people')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('plateau'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('plateaux')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('runner-up'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('runners-up')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('safe'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('safes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('sex'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('sexes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('sieve'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('sieves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('soliloquy'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('soliloquies')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('son-in-law'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('sons-in-law')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('syllabus'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('syllabi')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('testis'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('testes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('thief'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('thieves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('tooth'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('teeth')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('tornado'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('tornadoes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('trilby'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('trilbys')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('turf'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('turfs')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('valve'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('valves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('volcano'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('volcanoes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('abuse'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('abuses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('avalanche'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('avalanches')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('cache'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('caches')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('criterion'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('criteria')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('curve'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('curves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('emphasis'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('emphases')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('foe'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('foes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('grave'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('graves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('hoax'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('hoaxes')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('medium'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('media')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('neurosis'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('neuroses')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('save'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('saves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('wave'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('waves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('oasis'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('oases')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('valve'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('valves')));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Substitution(new \WPPayVendor\Doctrine\Inflector\Rules\Word('zombie'), new \WPPayVendor\Doctrine\Inflector\Rules\Word('zombies')));
    }
}
