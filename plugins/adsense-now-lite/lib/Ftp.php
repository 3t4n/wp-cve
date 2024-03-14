<?php

if (!class_exists('Ftp')) {

  class Ftp {

    private $_server, $_user, $_password, $_dir = '', $_showRootDir = false;
    private $_connId = false, $_loggedIn = false;
    var $status = '', $isReady = false;

    function __construct() {
      $options = EzGA::getAllOptions();
      if (empty($options['ftp_server'])) {
        $this->status = "FTP Server not verified.";
        return;
      }
      else {
        $this->_server = $options['ftp_server'];
      }
      if (false && function_exists('ftp_ssl_connect')) {
        $this->_connId = ftp_ssl_connect($this->_server);
      }
      else {
        $this->_connId = ftp_connect($this->_server);
      }
      if (!$this->_connId) {
        $this->status = "Cannot reach FTP server.";
        return;
      }
      if (empty($options['ftp_user'])) {
        $this->status = "FTP User ID not verified.";
        return;
      }
      else {
        $this->_user = $options['ftp_user'];
      }
      if (empty($options['ftp_password'])) {
        $this->status = "FTP Password not verified.";
        return;
      }
      else {
        $this->_password = $options['ftp_password'];
      }
      $this->_loggedIn = @ftp_login($this->_connId, $this->_user, $this->_password);
      if (!$this->_loggedIn) {
        $this->status = "FTP login fails";
        return;
      }
      if (empty($options['ftp_rootdir'])) {
        if (!$this->guessDir()) {
          $this->status = "FTP Root Directory needed.";
          $this->_showRootDir = true;
          return;
        }
      }
      else {
        $this->_dir = $options['ftp_rootdir'];
        ftp_chdir($this->_connId, $this->_dir);
      }
      $this->isReady = true;
    }

    function guessDir() {
      $dir0 = __DIR__;
      $ds = DIRECTORY_SEPARATOR;
      $parts = explode($ds, $dir0);
      foreach ($parts as $p) {
        if (empty($p)) {
          continue;
        }
        $this->_dir .= $ds . $p;
        $dir = str_replace($this->_dir, '', $dir0);
        $exists = @ftp_chdir($this->_connId, $dir);
        if ($exists) {
          return true;
        }
      }
      return false;
    }

    static function isNeeded($path) {
      $target = realpath($path) . DIRECTORY_SEPARATOR . EzGA::randString();
      if (@file_put_contents($target, "This is a test file") !== false) {
        if (@unlink($target)) {
          return false;
        }
      }
      return true;
    }

    function printForm() {
      $table = '';
      if (self::isNeeded($this->_dir)) {
        if ($this->_loggedIn) {
          $display = "style='display:none'";
          $table = "<div><h4 style='display:inline'>FTP is in Use</h4>&nbsp; <a id='showFtp' class='btn-sm btn-primary' title='See FTP Details' data-trigger='hover' data-placement='top' data-toggle='popover' data-content='<p>FTP is needed to update your application. Your credentials and serer details are already stored, and are being used. If you would like to inspect or modify them, please click here.</p>'><i class='glyphicon glyphicon-zoom-in icon-white'></i> See FTP Details</a></div>";
        }
        else {
          $display = '';
        }
        if (empty($this->status)) {
          $btn = "<a href='' class='btn-sm btn-success ftp' title='Your FTP credentials look fine and are saved in your database. If you modify them below, click on this button to check again.' data-toggle='tooltip'><i class='glyphicon glyphicon-thumbs-up'></i> All Okay</a>";
        }
        else {
          $btn = "<a href='' class='btn-sm btn-warning ftp' title='Your FTP credentials do not look right. Please re-enter them and click on this button to check again.' data-toggle='tooltip'><i class='glyphicon glyphicon-thumbs-down'></i> Check Again</a>";
        }
        $options = array();
        $options['ftp_server'] = array('name' => __('FTP Server', 'easy-common'),
            'value' => 'localhost',
            'validator' => 'notNull',
            'help' => __('Enter your FPT server name.', 'easy-common'),
            'dataTpl' => 'none',
            'dataMode' => '');
        if (defined('FTP_HOST')) {
          $options['ftp_server']['value'] = FTP_HOST;
        }
        $options['ftp_user'] = array('name' => __('FTP User Name', 'easy-common'),
            'value' => '',
            'validator' => 'notNull',
            'help' => __('Enter your FPT user name.', 'easy-common'),
            'dataTpl' => 'none',
            'dataMode' => '');
        if (defined('FTP_USER')) {
          $options['ftp_user']['value'] = FTP_USER;
        }
        $options['ftp_password'] = array('name' => __('FTP Password', 'easy-common'),
            'value' => '',
            'validator' => 'notNull',
            'help' => __('Enter your FPT password.', 'easy-common'),
            'dataTpl' => 'none',
            'dataMode' => '');
        if (defined('FTP_PASS')) {
          $options['ftp_password']['value'] = FTP_PASS;
          if (!$this->isReady) {
            $this->status .= " (Click on Check Again to verify.)";
          }
        }
        if ($this->_showRootDir) {
          $options['ftp_rootdir'] = array('name' => __('FTP Root Directory', 'easy-common'),
              'value' => '',
              'help' => __('When you logon to your server using FTP, it puts you in a folder. In most cases, we can discover this folder automatically. So you can start by leaving this option empty, but may have to come back and enter the value if prompted later.', 'easy-common'),
              'dataTpl' => 'none',
              'dataMode' => '');
        }
        $table .= "<div id='ftp' $display><h4>FTP Details</h4><p>Looks like FTP is needed to update your application. Please provide or verify the FTP details below. Note that FTP details will be stored in your database in plain text.</p><p class='red'><strong>$this->status</strong>&nbsp;&nbsp;$btn</p><table class='table table-striped table-bordered responsive'><tbody>";
        foreach ($options as $pk => $option) {
          $dbVal = EzGA::getGenOption($pk);
          if (empty($dbVal)) {
            EzGA::putGenOption($pk, $option['value']);
          }
          $table .= EzGA::renderOption($pk, $option);
        }
        $table .= "</tbody></table></div>";
        $table .= "<script>var xeditHandler = 'ajax/options.php'; var xparams ={}; $('#showFtp').click(function() { $(this).parent().hide();$('#ftp').fadeIn();});</script>";
      }
      return $table;
    }

    function copy($source, $target) { // ftp equivalent of php copy
      if (!@copy($source, $target)) {
        if (!$this->isReady) {
          return false;
        }
        $target = str_replace($this->_dir, "", $target);
        return ftp_put($this->_connId, $target, $source, FTP_BINARY);
      }
      else {
        return true;
      }
    }

    function rename($source, $target) {
      if (!@rename($source, $target)) {
        if (!$this->isReady) {
          return false;
        }
        $target = str_replace($this->_dir, "", $target);
        return ftp_rename($this->_connId, $target, $source);
      }
      else {
        return true;
      }
    }

    function mkdir($pathname) {
      if (!@mkdir($pathname)) {
        if (!$this->isReady) {
          return false;
        }
        $pathname = str_replace($this->_dir, "", $pathname);
        return ftp_mkdir($this->_connId, $pathname);
      }
      else {
        return true;
      }
    }

    function unlink($filename) {
      if (!@unlink($filename)) {
        if (!$this->isReady) {
          return false;
        }
        $filename = str_replace($this->_dir, "", $filename);
        return ftp_delete($this->_connId, $filename);
      }
      else {
        return true;
      }
    }

  }

}