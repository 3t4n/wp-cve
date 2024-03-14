<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\English;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
final class Uninflected
{
    /** @return Pattern[] */
    public static function getSingular() : iterable
    {
        yield from self::getDefault();
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('.*ss'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('clothes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('data'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('fascia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('fuchsia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('galleria'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('mafia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('militia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('pants'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('petunia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sepia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('trivia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('utopia'));
    }
    /** @return Pattern[] */
    public static function getPlural() : iterable
    {
        yield from self::getDefault();
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('people'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('trivia'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('\\w+ware$'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('media'));
    }
    /** @return Pattern[] */
    private static function getDefault() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('\\w+media'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('advice'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('aircraft'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('amoyese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('art'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('audio'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('baggage'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('bison'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('borghese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('bream'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('breeches'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('britches'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('buffalo'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('butter'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('cantus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('carp'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('cattle'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('chassis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('clippers'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('clothing'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('coal'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('cod'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('coitus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('compensation'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('congoese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('contretemps'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('coreopsis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('corps'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('cotton'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('data'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('debris'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('deer'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('diabetes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('djinn'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('education'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('eland'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('elk'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('emoji'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('equipment'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('evidence'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('faroese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('feedback'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('fish'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('flounder'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('flour'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('foochowese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('food'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('furniture'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('gallows'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('genevese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('genoese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('gilbertese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('gold'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('headquarters'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('herpes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('hijinks'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('homework'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('hottentotese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('impatience'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('information'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('innings'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('jackanapes'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('jeans'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('jedi'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('kin'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('kiplingese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('knowledge'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('kongoese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('leather'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('love'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('lucchese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('luggage'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('mackerel'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('Maltese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('management'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('metadata'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('mews'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('money'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('moose'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('mumps'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('music'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('nankingese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('news'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('nexus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('niasese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('nutrition'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('offspring'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('oil'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('patience'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('pekingese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('piedmontese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('pincers'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('pistoiese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('plankton'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('pliers'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('pokemon'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('police'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('polish'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('portuguese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('proceedings'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('progress'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('rabies'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('rain'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('research'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('rhinoceros'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('rice'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('salmon'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sand'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sarawakese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('scissors'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sea[- ]bass'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('series'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('shavese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('shears'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sheep'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('siemens'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('silk'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sms'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('soap'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('social media'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('spam'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('species'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('staff'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('sugar'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('swine'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('talent'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('toothpaste'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('traffic'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('travel'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('trousers'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('trout'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('tuna'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('us'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('vermontese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('vinegar'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('weather'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('wenchowese'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('wheat'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('whiting'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('wildebeest'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('wood'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('wool'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('yengeese'));
    }
}
