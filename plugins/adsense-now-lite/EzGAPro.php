<?php

if (!class_exists('EzGAPro')) {

  class EzGAPro {

    static $foundShortCode = false;
    static $options = array();

    static function isKilled() {
      return false;
    }

    static function killDivs($str) {
      return $str;
    }

    static function cacheContent() {

    }

    static function isSafe() {
      return true;
    }

    static function isBanned() {
      return false;
    }

    static function processShortCode($atts) {

    }

    static function filterShortCode($content) {
      return array($content, false);
    }

    static function handleShortCode($content) {
      return $content;
    }

    static function getMobileType() {
      return;
    }

    static function getTaxonomyList() {
      return array();
    }

    static function verifyOptionSets() {

    }

  }

}