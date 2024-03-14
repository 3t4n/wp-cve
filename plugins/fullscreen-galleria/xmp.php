<?php
/******************************************************************************

Description: Read xmp metadata
Version: 0.1
Author: Petri Damstén
Author URI: https://petridamsten.com/
License: MIT

******************************************************************************/

class XMPMetadata {
  protected $xml = [];
  
  private function limit($value)
  {
    return substr($value, 0, 64);
  }
  
  function __construct($fname = null) 
  {
    if ($fname) {
      $this->read($fname);
    }
  }

  function read($fname) 
  {
    $this->xml = [];
    $buffer = '';
    if ($fp = fopen($fname, 'rb')) {
      $buffer = fread($fp, 1024*1024);
      $offset = 0;
      while (($endpos = strpos($buffer, '</x:xmpmeta>', $offset)) !== false ) {
        if (($startpos = strpos($buffer, '<x:xmpmeta', $offset)) !== false) {
            array_push($this->xml, substr($buffer, $startpos, $endpos - $startpos + 12));
        }
        $offset = $endpos + 12;
      }
      fclose($fp);
    }
  }

  function str_to_float($value)
  {
    $a = explode('/', $value);
    if (count($a) < 1) {
      return 0.0;
    }
    if (count($a) < 2) {
      return floatval($a[0]);
    }
    return floatval($a[0]) / floatval($a[1]);
  }

  function gps_to_degrees($value)
  {
    if (!empty($value)) {
      if (is_string($value)) {
        $value = explode(' ', $value);
      }
      if (count($value) > 0) {
        $d = $this->str_to_float($value[0]);
      }
      if (count($value) > 1) {
        $m = $this->str_to_float($value[1]);
      }
      if (count($value) > 2) {
        $s = $this->str_to_float($value[2]);
      }
      return $d + ($m / 60.0) + ($s / 3600.0);
    }
    return 0.0;
  }
  
  function calc_gps($gps, $ref)
  {
    $val = $this->gps_to_degrees($gps);
    if ($ref == 'S' || $ref == 'W') {
      $val *= -1;
    }
    return $val;
  }

  function longitude()
  {
    return $this->calc_gps($this->value('exif:GPSInfo.GPSLongitude'), 
                           $this->value('exif:GPSInfo.GPSLongitudeRef'));
  }

  function latitude()
  {
    return $this->calc_gps($this->value('exif:GPSInfo.GPSLatitude'), 
                           $this->value('exif:GPSInfo.GPSLatitudeRef'));
  }

  function value($key)
  {
    # could not get simplexml_load_string working with xmp metadata
    foreach ($this->xml as $xml) {
      if (($startpos = strpos($xml, $key.'="')) !== false) {
        $startpos += strlen($key) + 2;
        if (($endpos = strpos($xml, '"', $startpos)) !== false) {
          return substr($xml, $startpos, min($endpos - $startpos, 256));
        }
      }
      if (($startpos = strpos($xml, $key.'>')) !== false) {
        $startpos += strlen($key) + 1;
        $bag = strpos($xml, '<rdf:Bag>', $startpos);
        $xdef = strpos($xml, 'x-default', $startpos);
        $end = strpos($xml, '</'.$key.'>', $startpos);

        if ($bag !== false && $bag < $end) {
          $startpos = $bag + 9;
          if (($endpos = strpos($xml, '</rdf:Bag>', $startpos)) !== false) {
            $s = substr($xml, $startpos, $endpos - $startpos);
            $s = str_replace('<rdf:li>', '', $s);
            $s = str_replace('</rdf:li>', '|', $s);
            $s = trim($s, " \n\r\t\v\x00|");
            $s = preg_replace("/\s*\|\s*/m", '|', $s);
            $a = explode('|', $s);
            $a = array_map(array($this, 'limit'), $a);
            return $a;
          }
        } else if ($xdef !== false && $xdef < $end) {
          $startpos = $xdef + 11;
          if (($endpos = strpos($xml, '<', $startpos)) !== false) {
            return substr($xml, $startpos, min($endpos - $startpos, 256));
          } 
        } else if ($end !== false) {
          return substr($xml, $startpos, min($end - $startpos, 256));
        }
      }
    }
    return '';
  }

}

if (isset($argv) && isset($argv[0]) && realpath($argv[0]) === __FILE__) {
  $xml = new XMPMetadata($argv[1]);
  echo($xml->value('exif:Photo.BodySerialNumber'));
  echo($xml->value('dc:title'));
  var_dump($xml->value('dc:subject'));
}