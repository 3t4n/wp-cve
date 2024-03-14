<?php

class WebshipperLangEn
{

  protected static $lang = [
    'search' => 'Search',
    'select' => 'Select',
    'address' => 'Address',
    'zip' => 'Zip',
    'city' => 'City',
    'see opening hours' => 'Show opening hours',
    'opening hours' => 'Opening hours',
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday',
    'saturday' => 'Saturday',
    'sunday' => 'Sunday',
    'day_0' => 'Monday',
    'day_1' => 'Tuesday',
    'day_2' => 'Wednesday',
    'day_3' => 'Thursday',
    'day_4' => 'Friday',
    'day_5' => 'Saturday',
    'day_6' => 'Sunday',
    'close' => 'Close',
    'pickup location' => 'Pickup location',
    'select pickup point' => 'Select pickup location',
    'selected pickup point' => 'Selected pickup location',
    'sorry! We could not find any pickup points. Try again!' => 'Sorry! We could not find any pickup points. Try again!',
    'we are looking for the nearest pickup points...' => 'We are looking for the nearest pickup points...',
    'use the search field above to find a pickup point near you.' => 'Use the search field above to find a pickup point near you.',
    'Close window' => 'Close',
    'you' => 'You',
    'closed' => 'Closed',
    'Unable to find droppoint. Please check your address' => 'Unable to find droppoint. Please check your address for any errors'
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
