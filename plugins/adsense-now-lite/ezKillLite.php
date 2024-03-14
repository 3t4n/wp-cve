<?php

/*
  Copyright (C) 2008 www.ads-ez.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!function_exists('is_plugin_active')) {
  include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if (!class_exists('EzKillLite')) {

  class EzKillLite {

    var $lite, $pro, $killer;
    var $liteName, $proName;
    static $adminNotice = '';
    static $killed = false;

    function __construct($lite, $pro = '', $killer = '') {
      $this->lite = $lite;
      $this->pro = $pro;
      $this->killer = $killer;
    }

    function init() {
      include_once ABSPATH . 'wp-admin/includes/plugin.php';
      deactivate_plugins($this->lite);
    }

    function admin_footer_kill() {
      printf('<script>document.getElementById("message").innerHTML="' . "<span style='font-weight:bold;font-size:1.1em;color:red'>{$this->killer}: " . __("Pro Plugin is activated. Lite version is deactivated.", "easy-common") . "</span>" . '";</script>');
    }

    function admin_footer_deny() {
      printf('<script>document.getElementById("message").innerHTML="' . "<span style='font-weight:bold;font-size:1.1em;color:red'>{$this->liteName}: " . __("Cannot be activated!", "easy-common") . "</span>" . '";</script>');
    }

    function kill() {
      $killed = false;
      $proActive = is_plugin_active($this->pro);
      $liteActive = is_plugin_active($this->lite);
      if ($proActive && $liteActive) {
        add_action('init', array($this, 'init'));
        $plgPath = ABSPATH . PLUGINDIR . "/$this->lite";
        $liteData = get_plugin_data($plgPath);
        $plgPath = ABSPATH . PLUGINDIR . "/$this->pro";
        $proData = get_plugin_data($plgPath);
        $this->liteName = $liteData['Name'];
        $this->proName = $proData['Name'];
        self::$adminNotice .= sprintf('<div class="updated"><p>');
        self::$adminNotice .= sprintf(__("%s cannot be active now. Deactivating it so that you can use the Pro version %s If you really want to use the %s version, please deactivate the %s version first.", "easy-common"), "<strong><em>{$this->liteName}</em></strong>", "<strong><em>{$this->proName}</em></strong>.<br />", "<strong><em>Lite</em></strong>", "<strong><em>Pro</em></strong>");
        self::$adminNotice .= sprintf("<br /><strong>" . __("Please reload this page to remove stale links.", 'easy-common') . " <input type='button' value='Reload Page' onClick='window.location.href=window.location.href.replace(\"activate=true&\",\"\")'></strong>");
        self::$adminNotice .= sprintf('</p></div>');
        add_action('admin_notices', array('EzKillLite', 'admin_notices'));
        add_action('admin_footer-plugins.php', array($this, 'admin_footer_kill'));
        $killed = true;
      }
      self::$killed = $killed;
      return $killed;
    }

    function deny() {
      if (self::$killed) {
        return;
      }
      $this->liteName = '';
      if (is_plugin_active($this->lite)) {
        $litePlg = ABSPATH . PLUGINDIR . "/" . $this->lite;
        $liteData = get_plugin_data($litePlg);
        $this->liteName = $liteData['Name'];
        if (!empty($_REQUEST['plugin'])) {
          add_action('init', array($this, 'init'));
          self::$adminNotice .= sprintf("<div class='error'>" . __("%s: Another plugin of the same family is active.<br />Please deactivate it before activating %s.", "easy-common") . "</div>", "<strong><em>{$this->liteName}</em></strong>", "<strong><em>{$this->liteName}</em></strong>");
          add_action('admin_notices', array('EzKillLite', 'admin_notices'));
          add_action('admin_footer-plugins.php', 'admin_footer_deny');
          return false;
        }
        else {
          return $this->liteName;
        }
      }
    }

    static function admin_notices() {
      echo self::$adminNotice;
    }

  }

}

if (!empty($liteList)) {
  foreach ($liteList as $lite) {
    $ezKillLite = new EzKillLite($lite, $pro, $ezKillingPlg);
    if ($ezKillLite->kill()) {
      break;
    }
  }
}