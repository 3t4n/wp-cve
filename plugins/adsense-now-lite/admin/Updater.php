<?php

if (class_exists("Updater")) {
  echo "Problem, class Updater exists! \nCannot safely continue.\n";
  exit;
}
else {

  class Updater {

    var $slug, $data, $localVersion, $remoteVersion;
    static $updateText;

    const CDN = "http://api.wp-plus.org/";

    function __construct($slug) {
      $this->slug = $slug;
      $this->localVersion = $this->remoteVersion = -1;
      if (!class_exists('ZipArchive')) {
        $msg = "This application cannot update itself because your PHP does not have ZIP support. Please update your application using the following steps.";
        $pluginLink = "<a href='" . admin_url('plugins.php') . "' class='popup'>Plugins Page</a>";
        $uploadLink = "<a href='" . admin_url('plugin-install.php?tab=upload') . "' class='popup'>Upload Page</a>";
        $workAround = "<ol><li>Deactivate and delete your on the $pluginLink. If you do not delete the plugin first, the next step will fail because WordPress will refuse to overwrite the existing plugin folder.</li><li>Install the plugin by uploading the zip file on the $uploadLink.</li><li>Activate the plugin and you are all set.</li></ol>";
        $msg .= $workAround;
        echo '<script>$(document).ready(function() {' .
        "showError(\"$msg\");
        });
        </script>";
      }
    }

    function getLocalVersion() {
      if ($this->localVersion < 0) {
        $readme_file = "../readme.txt";
        $readme_text = file_get_contents($readme_file);
        $lines = explode("\n", $readme_text);
        $needle = "Stable tag:";
        foreach ($lines as $line) {
          if (strpos($line, $needle) > -1) {
            $this->localVersion = trim(str_replace($needle, "", $line));
            break;
          }
        }
      }
      return $this->localVersion;
    }

    function getRemoteVersion() {
      if ($this->remoteVersion < 0) {
        if (empty($this->data)) {
          $slug = $this->slug;
          $this->data = unserialize(gzinflate(file_get_contents(self::CDN . "/plugins/$slug.dat")));
        }
        if (!empty($this->data)) {
          $this->remoteVersion = $this->data->version;
        }
      }
      return $this->remoteVersion;
    }

    function isOld() {
      $localVersion = (float) $this->getLocalVersion();
      $remoteVersion = (float) $this->getRemoteVersion();
      return $remoteVersion > $localVersion;
    }

    function getUpdateText() {
      $data = $this->data;
      if (empty(self::$updateText)) {
        $localVersion = $this->getLocalVersion();
        $remoteVersion = $this->getRemoteVersion();
        $name = $data->name;
        if ($this->isOld()) {
          $text = "You are using $name {$localVersion}. The current version is {$remoteVersion}. Click here to download the update.";
        }
        else {
          $text = "You are using $name {$localVersion}, which is the latest version available. Please visit this page periodically to check for updates.";
        }
        self::$updateText = $text;
      }
      return self::$updateText;
    }

    function getDiagnostics() {
      $diag = "PHP Version: " . phpversion() . "<br>\n";
      $diag .= $this->getUpdateText();
      global $wpdb;
      $diag .= "<br>\nWPDB Object: ";
      foreach ($wpdb as $k => $v) {
        if (!empty($v) && !is_array($v)) {
          $diag .= "<br>  $k : $v";
        }
      }
      return $diag;
    }

    static function zip($source, $destination) {

      $zip = new ZipArchive();
      if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
      }
      $source = str_replace('\\', '/', realpath($source));
      if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file) {
          $file = str_replace('\\', '/', $file);
          // Ignore "." and ".." folders
          if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) {
            continue;
          }
          $file = realpath($file);
          if (is_dir($file) === true) {
            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
          }
          else if (is_file($file) === true) {
            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
          }
        }
      }
      else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
      }
      return $zip->close();
    }

    function backup() { // TODO: Test this method and use it from admin.php
      $zipFile = tempnam(sys_get_temp_dir(), 'zip');
      $toZip = realpath('../..');
      $base = basename($toZip);
      if (!self::zip($toZip, $zipFile)) {
        echo "Failed to write files to zip\n";
      }
      else {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $base . '.zip');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($zipFile));
        @ob_clean(); // Don't let it throw any notices, which will mess up the zip file
        flush();
        readfile($zipFile);
        @unlink($zipFile);
      }
    }

  }

}