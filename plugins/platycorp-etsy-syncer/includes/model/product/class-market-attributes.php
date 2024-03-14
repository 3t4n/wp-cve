<?php

namespace platy\etsy;

class MarketAttributes
{

    public static function map_to_attribute_ids($array) {
        return \array_map(function($val){return $val['id'];}, $array);
    }
    
    const WHO_MADE_ARRAY = [
        array('name' => 'I did','id' => 'i_did'),
        array('name' => 'A member of my shop','id' => 'collective'),
        array('name' => 'Another company or person','id' => 'someone_else')
    ];
    
    const IS_SUPPLY_ARRAY = [
        array('name' => 'A finished product','id' => 'false'),
        array('name' => 'A supply or tool to make things','id' => 'true')
    ];
    
    const WHEN_MADE_ARRAY = [
        array('name' => 'Made to order','id' => 'made_to_order'),
        array('name' => '2020 - 2023','id' => '2020_2023'),
        array('name' => '2010 - 2019','id' => '2010_2019'),
        array('name' => '2004 - 2009','id' => '2004_2009'),
        array('name' => 'Before 2004','id' => 'before_2004'),
        array('name' => '2000 - 2003','id' => '2000_2003'),
        array('name' => '1990s','id' => '1990s'),
        array('name' => '1980s','id' => '1980s'),
        array('name' => '1970s','id' => '1970s'),
        array('name' => '1960s','id' => '1960s'),
        array('name' => '1950s','id' => '1950s'),
        array('name' => '1940s','id' => '1940s'),
        array('name' => '1930s','id' => '1930s'),
        array('name' => '1920s','id' => '1920s'),
        array('name' => '1910s','id' => '1910s'),
        array('name' => '1900 - 1909','id' => '1900s'),
        array('name' => '1800s','id' => '1800s'),
        array('name' => '1700s','id' => '1700s'),
        array('name' => 'Before 1700','id' => 'before_1700')
    ];

    const RECEPIENT_ARRAY = [
        ['name' => 'None', 'id' => ''],
        ['name' => 'Men', 'id' => 'men'],
        ['name' => 'Women', 'id' => 'women'],
        ['name' => 'Adults unisex', 'id' => 'unisex_adults'],
        ['name' => 'Teen boys', 'id' => 'teen_boys'],
        ['name' => 'Teen girls', 'id' => 'teen_girls'],
        ['name' => 'Teens', 'id' => 'teens'],
        ['name' => 'Baby boys', 'id' => 'baby_boys'],
        ['name' => 'Bayb girls', 'id' => 'baby_girls'],
        ['name' => 'Babies', 'id' => 'babies'],
        ['name' => 'Birds', 'id' => 'birds'],
        ['name' => 'Cats', 'id' => 'cats'],
        ['name' => 'Dogs', 'id' => 'dogs'],
        ['name' => 'Pets', 'id' => 'pets']
    ];

    const OCCASION_ARRAY = [
        ['name' => 'None', 'id' => ''],
        ['name' => 'Anniversary', 'id' => 'anniversary'],
        ['name' => 'Baptism', 'id' => 'baptism'],
        ['name' => 'Bar or bat mitzvah', 'id' => 'bar_or_bat_mitzvah'],
        ['name' => 'Birthday', 'id' => 'birthday'],
        ['name' => 'Canada day', 'id' => 'canada_day'],
        ['name' => 'Chinese new year', 'id' => 'chinese_new_year'],
        ['name' => 'Cinco de mayo', 'id' => 'cinco_de_mayo'],
        ['name' => 'Confirmation', 'id' => 'confirmation'],
        ['name' => 'Christmas', 'id' => 'christmas'],
        ['name' => 'Day of the dead', 'id' => 'day_of_the_dead'],
        ['name' => 'Easter', 'id' => 'easter'],
        ['name' => 'Eid', 'id' => 'eid'],
        ['name' => 'Engagement', 'id' => 'engagement'],
        ['name' => 'Fathers day', 'id' => 'fathers_day'],
        ['name' => 'Get well', 'id' => 'get_well'],
        ['name' => 'Graduation', 'id' => 'graduation'],
        ['name' => 'Halloween', 'id' => 'halloween'],
        ['name' => 'Hanukkah', 'id' => 'hanukkah'],
        ['name' => 'Housewarming', 'id' => 'housewarming'],
        ['name' => 'Kwanzaa', 'id' => 'kwanzaa'],
        ['name' => 'Prom', 'id' => 'prom'],
        ['name' => 'July 4th', 'id' => 'july_4th'],
        ['name' => 'Mothers_day', 'id' => 'mothers_day'],
        ['name' => 'New_baby', 'id' => 'new_baby'],
        ['name' => 'Quinceanera', 'id' => 'quinceanera'],
        ['name' => 'Retirement', 'id' => 'retirement'],
        ['name' => 'St patricks day', 'id' => 'st_patricks_day'],
        ['name' => 'Sweet_16', 'id' => 'sweet_16'],
        ['name' => 'Sympathy', 'id' => 'sympathy'],
        ['name' => 'Thanksgiving', 'id' => 'thanksgiving'],
        ['name' => 'Valentines', 'id' => 'valentines'],
        ['name' => 'Wedding', 'id' => 'wedding'],
    ];
}

