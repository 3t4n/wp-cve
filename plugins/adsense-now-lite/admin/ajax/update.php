<?php

ob_start();

require_once('../../EzGA.php');
require_once('../../lib/Ftp.php');

if (!EzGA::isLoggedIn()) {
  http_response_code(400);
  die("Please login before uploading files!");
}

if (!class_exists('ZipArchive')) {
  $error = "Seems like zip is not fully enabled in the PHP installation on your server. (<code>class ZipArchive</code> not found.) This updater cannot proceed without it.<br />You might be able to add zip support via your cPanel/WHM interface. Look for Module Installers, and try installing zip via PHP Pecl installer.";
  http_response_code(400);
  die($error);
}

if (isset($_REQUEST['backup'])) {
  require_once '../Updater.php';
  $updater = new Updater('google-adsense');
  $updater->backup();
  exit();
}

if (empty($_FILES)) {
  http_response_code(400);
  die("File upload error. No files reached the server!");
}

$ds = DIRECTORY_SEPARATOR;
$target = realpath("..$ds..") . $ds;
$ftp = new Ftp();
if (Ftp::isNeeded($target)) {
  if (!$ftp->isReady) {
    $error = "Cannot overwrite the plugin files! Here are your options to proceed."
            . "<ul><li>Enter or edit the FTP credentials below, if available. Contact your server admin for details.</li>"
            . "<li>Disable and delete the current version of the plugin, and use the WordPress interface <ol><li>Plugins &rarr; Add New</li><li>Click on <code>Upload Plugin</code></li><li>Choose the downloaded zip file</li><li>Click on Insall Now.</li></ol></li>"
            . "<li>Unpack the downloaded zip file and upload to your server, overwriting the existing files.</li>"
            . "<li>Make your installation updatable by using this Unix command or equivalent:<pre><code>chmod -R 777 $target</code></pre></li></ul>";
    http_response_code(400);
    die($error);
  }
}

$warning = '';
$dirCount = 0;
$zip = new ZipArchive;
$tmpName = $_FILES['file']['tmp_name'];
if ($zip->open($tmpName) !== TRUE) {
  $error = "Cannot open the uploaded zip file.";
  http_response_code(400);
  die($error);
}

// ensure that it is an a plugin archive
$slug = basename(dirname(dirname(getcwd())));
$slug = str_replace(array('-pro', '-lite', '-ultra'), "", $slug);

$toVerify = array("$slug-frontend.php", "$slug-admin.php", "$slug-options.php");

foreach ($toVerify as $d) {
  $idx = $zip->locateName($d, ZipArchive::FL_NODIR);
  if ($idx === false) {
    $idx = $zip->locateName($d);
  }
  if ($idx === false && $slug == 'ajax-adsense') {
    // check for ajax-adsense upgrade to easy-adsense-pro
    $d = str_replace($slug, 'easy-adsense', $d);
    $idx = $zip->locateName($d, ZipArchive::FL_NODIR);
    if ($idx === false) {
      $idx = $zip->locateName($d);
    }
  }
  if ($idx === false) {
    $error = "Cannot locate a critical file (<code>$d</code>) in the uploaded zip file.";
    http_response_code(400);
    die($error);
  }
}

$zipRoot = $zip->getNameIndex(0);
for ($i = 1; $i < $zip->numFiles; $i++) {
  $filename = $zip->getNameIndex($i);
  $sourceFile = "zip://$tmpName#$filename";
  $targetFile = str_replace($zipRoot, $target, $filename);
  if (is_dir($targetFile)) {
    ++$dirCount;
    continue;
  }
  $lastChar = substr($sourceFile, -1);
  if ($lastChar == $ds || $lastChar == '/') {
    if (!$ftp->mkdir($targetFile)) {
      $error = "Error creating the directory $targetFile";
      http_response_code(400);
      die($error);
    }
    continue;
  }
  $targetDir = dirname($targetFile);
  if (!is_dir($targetDir) && !@$ftp->mkdir($targetDir)) {
    $error = "Error creating the new folder $targetDir";
    http_response_code(400);
    die($error);
  }
  if (!$ftp->copy($sourceFile, $targetFile)) {
    $error = "Error copying $filename to $targetFile";
    http_response_code(400);
    die($error);
  }
}
if ($dirCount > 0) {
  $warning = "Ignoring $dirCount folders, which already exist on your server.<br />";
}
$zip->close();
$success = "Congratulations, you have successfully updated the plugin.";

ob_end_clean();
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(array('success' => $success, 'warning' => $warning));
exit();
