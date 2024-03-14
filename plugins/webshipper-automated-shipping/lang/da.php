<?php

class WebshipperLangDa
{
  protected static $lang = [
    'search' => 'Søg',
    'select' => 'Vælg',
    'address' => 'Adresse',
    'zip' => 'Postnr.',
    'city' => 'By',
    'see opening hours' => 'Vis åbningstider',
    'opening hours' => 'Åbningstider',
    'monday' => 'Mandag',
    'tuesday' => 'Tirsdag',
    'wednesday' => 'Onsdag',
    'thursday' => 'Torsdag',
    'friday' => 'Fredag',
    'saturday' => 'Lørdag',
    'sunday' => 'Søndag',
    'day_0' => 'Mandag',
    'day_1' => 'Tirsdag',
    'day_2' => 'Onsdag',
    'day_3' => 'Torsdag',
    'day_4' => 'Fredag',
    'day_5' => 'Lørdag',
    'day_6' => 'Søndag',
    'close' => 'Luk',
    'pickup location' => 'Afhentningssted',
    'select pickup point' => 'Vælg afhentningssted',
    'selected pickup point' => 'Valgt afhentningssted',
    'sorry! We couldnt find any pickup points. Try again!' => 'Vi kunne ikke finde nogle afhentningssteder, prøv igen',
    'we are looking for the nearest pickup points...' => 'Vi leder efter det nærmeste afhentningssted',
    'use the search field above to find a pickup point near you.' => 'Brug søgefunktionen ovenover for at finde det tætteste afhentningssted',
    'Close window' => 'Luk',
    'you' => 'Dig',
    'closed' => 'Lukket',
    'Unable to find droppoint. Please check your address' => 'Ingen pakkeshop kunne findes. Tjek venligst om din adresse er indtastet korrekt'
  ];

  public static function translate($str)
  {
    if (isset(self::$lang[$str])) {
      return self::$lang[$str];
    }

    return $str;
  }

  public static function js()
  {
    return json_encode(self::$lang);
  }
}
