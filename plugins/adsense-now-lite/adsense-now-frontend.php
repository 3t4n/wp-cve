<?php

class AdSenseNowFront {

  var $leadin, $leadout, $options, $defaultText, $verbose = false;
  static $ezMax = 3, $ezCount = 0;
  static $filterPass = 0, $info;

  function AdSenseNowFront() {
    $optionSet = EzGA::getMobileType();
    if ($optionSet == "Killed") {
      EzGA::$noAdsReason .= 'Mobile Type says Killed. ';
      EzGA::$noAds = true;
      $optionSet = "";
    }
    $this->options = EzGA::getOptions($optionSet);
    $this->defaultText = $this->options['defaultText'];
    self::$info = EzGA::info();
    $this->verbose = !empty($this->options['verbose']);
  }

  function mkAdBlock($slot) {
    $adBlock = '';
    self::$ezCount++;
    $adText = EzGA::handleDefaultText($this->options['ad_text']);
    if (!empty($adText)) {
      $show = EzGA::$metaOptions["show_$slot"];
      $adBlock = "<div class='adsense adsense-$slot' style='$show;margin:12px'>$adText</div>";
    }
    if ($this->verbose) {
      $info = self::$info;
      echo "\n$info\n <!--  ezCount = " . self::$ezCount . " - incremented at:\n";
      debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
      echo "Called AdSenseNowFront::mkAdBlock with slot = $slot\n";
      if (empty($adText)) {
        $adBlock = "\n$info\n<!-- Empty adText: Post[$slot] Count:" .
                self::$ezCount . " of " . self::$ezMax . "-->\n";
        echo "Returning Empty block\n";
      }
      else {
        $adBlock = "\n$info\n<!-- Post[$slot] Count:" .
                self::$ezCount . " of " . self::$ezMax . "-->\n" .
                $adBlock . "\n$info\n";
        echo "Ad Block = " . htmlspecialchars($adBlock);
      }
      echo "-->\n";
    }
    $adBlock = stripslashes($adBlock);
    return $adBlock;
  }

  function resetFilter() {
    if (self::$filterPass > 1 && is_singular()) {
      self::$ezMax = 3;
      self::$ezCount = 0;
      if ($this->verbose) {
        return " <!-- Filter Reset -->\n";
      }
    }
  }

  function filterContent($content) {
    ++self::$filterPass;
    $filterReset = $this->resetFilter();
    $plgName = EzGA::getPlgName();
    if ($this->verbose) {
      $content .= " <!-- $plgName: EzCount = " . self::$ezCount .
              " Filter Pass = " . self::$filterPass . "  -->\n";
      $content .= $filterReset;
    }
    $content = EzGA::preFilter($content);
    if (EzGA::$noAds) {
      return $content;
    }

    if (self::$ezCount >= self::$ezMax) {
      if ($this->verbose) {
        $content .= " <!-- $plgName: Unfiltered [count: " .
                self::$ezCount . " is not less than " . self::$ezMax . "] -->\n";
      }
      return $content;
    }

    $adMax = self::$ezMax;
    $adCount = 0;
    if (!is_singular()) {
      if (isset(EzGA::$options['excerptNumber'])) {
        $adMax = EzGA::$options['excerptNumber'];
      }
    }

    list($content, $return) = EzGA::filterShortCode($content);
    if ($return) {
      return $content;
    }

    $metaOptions = EzGA::getMetaOptions();
    $show_leadin = $metaOptions['show_leadin'];
    $leadin = '';
    if ($show_leadin != 'no') {
      if (self::$ezCount < self::$ezMax && $adCount++ < $adMax) {
        $leadin = $this->mkAdBlock("leadin");
      }
    }

    $show_midtext = $metaOptions['show_midtext'];
    $midtext = '';
    if ($show_midtext != 'no') {
      if (self::$ezCount < self::$ezMax && $adCount++ < $adMax) {
        $midtext = $this->mkAdBlock("midtext");
        if (!EzGA::$foundShortCode) {
          $split = EzGA::getSplit($content);
          $content = substr($content, 0, $split) . $midtext . substr($content, $split);
        }
      }
    }

    $show_leadout = $metaOptions['show_leadout'];
    $leadout = '';
    if ($show_leadout != 'no') {
      if (self::$ezCount < self::$ezMax && $adCount++ < $adMax) {
        $leadout = $this->mkAdBlock("leadout");
        if (!EzGA::$foundShortCode && strpos($show_leadout, "float") !== false) {
          $paras = EzGA::findParas($content);
          $split = array_pop($paras);
          if (!empty($split)) {
            $content1 = substr($content, 0, $split);
            $content2 = substr($content, $split);
          }
        }
      }
    }
    if (EzGA::$foundShortCode) {
      $content = EzGA::handleShortCode($content, $leadin, $midtext, $leadout);
    }
    else {
      if (empty($content1)) {
        $content = $leadin . $content . $leadout;
      }
      else {
        $content = $leadin . $content1 . $leadout . $content2;
      }
    }
    return $content;
  }

}

$adSenseNow = new AdSenseNowFront();
if (!empty($adSenseNow)) {
  add_filter('the_content', array($adSenseNow, 'filterContent'));
  if (EzGA::isPro()) {
    if (!empty(EzGA::$options['enableShortCode'])) {
      $shortCodes = array('ezadsense', 'adsense');
      foreach ($shortCodes as $sc) {
        add_shortcode($sc, array('EzGAPro', 'processShortcode'));
      }
    }
  }
}
