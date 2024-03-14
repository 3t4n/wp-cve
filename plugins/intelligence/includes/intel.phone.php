<?php
/**
 * @file
 * Functions to support extended Google Analytics data.
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

/**
 * formats phone number to E.164 standard
 */
function intel_format_phonenumber_to_e164($number) {
  // remove non numeric
  $number = preg_replace("/[^0-9+]/", "", $number);
  if (substr($number, 0, 1) != '+') {
    $number = '+' . $number;
  }
  return $number;
}