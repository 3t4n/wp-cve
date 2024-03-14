<?php

if (!defined('ABSPATH')) {
  exit;
} // Exit if accessed directly

function in5_get_meta_file()
{
  $in5Folder = wp_upload_dir();
  $metaFile  = $in5Folder['basedir'] . '/in5-archives/metadata.json';

  return $metaFile;
}

function in5_get_metadata()
{
  $metaFile = in5_get_meta_file();
  if (!file_exists($metaFile)) {
    return;
  }
  $getMetaFile = file_get_contents($metaFile);
  if ($getMetaFile == null) {
    return '{"files":[]}';
  } else {
    return $getMetaFile;
  }
}

function in5_extractArchive($zipFile, $folder)
{
  $zip = new ZipArchive;
  if ($zip->open($zipFile) === true) {
    $zip->extractTo($folder);
    $zip->close();

    return true;
  } else {
    return false;
  }
}

function in5_getIn5UploadsFolder($custom_path = '')
{
  $uploadsDir = wp_upload_dir();
  $in5Folder  = trailingslashit($uploadsDir['basedir']);
  $in5Folder = str_replace('wp-content/uploads', '', $in5Folder);
  $in5Folder  = trailingslashit($in5Folder);

  return $in5Folder;
}

function in5_getIndexFileUrl()
{
  $uploadsDir = wp_upload_dir();
  $in5Folder  = trailingslashit($uploadsDir['baseurl']);
  // $in5Folder  .= 'in5-archives/';

  $in5Folder = str_replace('wp-content/uploads', '', $in5Folder);
  $in5Folder  = trailingslashit($in5Folder);

  return $in5Folder;
}

function in5_convertSizeToBytes($size)
{
  $cleanSize = substr($size, 0, -1);
  $unit      = substr($size, strlen($cleanSize), strlen($size));
  switch (strtolower($unit)) {
    case 'g':
      $cleanSize *= 1024;
    case 'm':
      $cleanSize *= 1024;
    case 'k':
      $cleanSize *= 1024;
  }

  return $cleanSize;
}

// Convert bytes to a human size with the appropriate unit
function in5_convertBytesToSize($bytes)
{
  $unitLetter = 'BKMGTP';
  $factor     = floor((strlen($bytes) - 1) / 3);
  switch ($unitLetter[$factor]) {
    case 'B':
      $unit = " bytes";
      break;
    case 'K':
      $unit = " KB";
      break;
    case 'M':
      $unit = " MB";
      break;
    case 'G':
      $unit = " GB";
      break;
    case 'T':
      $unit = " TB";
      break;
    case 'P':
      $unit = " PB";
      break;
  }
  $sizeWithUnit = sprintf("%.0f", $bytes / pow(1024, $factor)) . @$unit;

  return $sizeWithUnit;
}

function in5_validateFile($fileName, $type)
{
  switch ($type) {
    case 'application/x-zip-compressed': /*windows*/
    case 'application/octet-stream':
    case 'application/zip':
    case 'application/x-compressed':
      if (strpos($fileName, '.zip') !== false || strpos($fileName, '.hpub') !== false || strpos($fileName, '.zhtml') !== false) {
        return true;
      } else {
        return false;
      }
    default:
      return false;
  }
}

function in5_validateSize($size)
{
  $maxSize   = ini_get('upload_max_filesize');
  $byteSized = in5_convertSizeToBytes($maxSize);
  if ($byteSized < $size) {
    return false;
  } else {
    return true;
  }
}

function in5_removeExtension($fileName)
{
  if (strpos($fileName, '.zip') !== false) {
    return substr($fileName, 0, -4);
  }
  if (strpos($fileName, '.hpub') !== false) {
    return substr($fileName, 0, -5);
  }
  if (strpos($fileName, '.zhtml') !== false) {
    return substr($fileName, 0, -6);
  }

  return;
}

function createFolderName($fileName, $customPath = '')
{
  $folderName    = in5_removeExtension($fileName);
  $folderName    = strtolower($folderName);
  $folderName    = str_replace(' ', '-', $folderName);
  $uploadsFolder = in5_getIn5UploadsFolder();

  if ($customPath !== '') {
    // return $uploadsFolder . $customPath . '/' . $folderName;
    return $uploadsFolder . $customPath . '/' . $folderName;
  } else {
    // print_r('No custom path');
    return $uploadsFolder . $folderName;
  }
}

function in5_ifFolderExists($fileName, $customPath = '')
{
  $folderPath = createFolderName($fileName, $customPath);
  $i          = 1;
  while (file_exists($folderPath)) {
    $folderPath .= "-" . $i;
    $i++;
  }

  return $folderPath;
}

// Delete the folder recursively
function in5_rrmdir($dir)
{
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir . "/" . $object) == "dir") {
          in5_rrmdir($dir . "/" . $object);
        } else {
          unlink($dir . "/" . $object);
        }
      }
    }
    reset($objects);
    rmdir($dir);
  }
}

function in5_doesIndexExist($finalFolderName)
{
  $finalFolderName = trailingslashit($finalFolderName);
  if (file_exists($finalFolderName . 'index.html')) {
    return true;
  }
  if ($handle = opendir($finalFolderName)) {
    $blacklist = array('__MACOSX', '.', '..');
    while (false !== ($file = readdir($handle))) {
      if (in_array($file, $blacklist)) {
        continue;
      }

      if (is_file($file)) {
        continue;
      }

      if (file_exists(trailingslashit($finalFolderName . $file) . 'index.html')) {
        return array('finalFolderName' => trailingslashit($finalFolderName . $file));
        break;
      } else {
        continue;
      }
    }
    closedir($handle);
  }

  return false;
}

function in5_doArchiveUpload($fileName, $tempFileLocation, $customPath = '')
{
  // So if custom path is filled we should use it?
  // TODO: Add custom path
  $finalFolderName = in5_ifFolderExists($fileName, $customPath);
  mkdir($finalFolderName);
  in5_extractArchive($tempFileLocation, $finalFolderName);
  $doesIndexExist = in5_doesIndexExist($finalFolderName);
  if (!is_array($doesIndexExist) && $doesIndexExist !== false) {
    return $finalFolderName;
  } elseif (is_array($doesIndexExist)) {
    if (isset($doesIndexExist['finalFolderName'])) {
      return $doesIndexExist['finalFolderName'];
    }
  } else {
    in5_rrmdir($finalFolderName);

    return false;
  }

  return '';
}

function in5_getNextEntryID()
{
  $files  = in5_get_metadata();
  $decode = json_decode($files);

  return count($decode->files) + 1;
}

function in5_findIndexFile($folder)
{
  if (file_exists(trailingslashit($folder) . 'index.html')) {
    return trailingslashit($folder) . 'index.html';
  } elseif (file_exists(trailingslashit($folder) . 'pages.html')) {
    return trailingslashit($folder) . 'pages.html';
  }

  return '';
}

function in5_getFileAttributes($archiveLocation)
{
  if (!file_exists($archiveLocation)) {
    return false;
  }
  $getJsLocation = $archiveLocation . '/assets/js/in5.config.js';
  $getJs         = file_get_contents($getJsLocation);
  preg_match('/var pageW = (.*?), pageH = (.*?);/i', $getJs, $matchesWH);
  preg_match('/var pageMode = (.*?);/i', $getJs, $matchesFlip);
  //preg_match('/var scaleMode = (.*?);/i', $getJs, $matchesScale);
  if (!isset($matchesWH) || empty($matchesWH)) {
  $attributes = array('width' => '100%', 'height' => '500'); 
  return $attributes;
  } else {
    $width      = (int) $matchesWH[1];
    $height     = (int) $matchesWH[2];
    if(isset($matchesFlip) && $matchesFlip[1] == "'flip'") {
      $height *= .5;
    }
    $attributes = array('width' => $width, 'height' => $height);

    return $attributes;
  }
}

/*deprecated*/
function in5_getFileAttributesFromCSS($archiveLocation){
  $getCssLocation = $archiveLocation . '/assets/css/pages.css';
    $getCss         = file_get_contents($getCssLocation);
    preg_match('/.page { width:(.*?)px; height:(.*?)px; /i', $getCss, $matches);
    return $matches;
}

function in5_extractMetaData($archiveLocation, $size)
{
  $index = file_get_contents(in5_findIndexFile($archiveLocation));
  preg_match('/<title>(.*)<\/title>/', $index, $matches);
  if (isset($matches[1])) {
    $name = $matches[1];
  } else {
    $name = "";
  }

  $attributes = in5_getFileAttributes($archiveLocation);
  $width      = $attributes['width'];
  $height     = $attributes['height'];

  $uploadsUrl    = in5_getIndexFileUrl();
  $uploadsDir    = in5_getIn5UploadsFolder();

  $filePath      = substr($archiveLocation, strlen($uploadsDir), strlen($archiveLocation));
  $directUrl     = trailingslashit($uploadsUrl . $filePath) . 'index.html';
  $indexContents = file_get_contents($directUrl);
  if (strpos($indexContents, '<ul class="thumbs">') && !strpos($indexContents, 'class="page"')) {
    $directUrl = trailingslashit($uploadsUrl . $filePath) . '0001.html';
  }
  $id   = in5_getNextEntryID();
  $date = date("F d, Y");

  $archiveData = array(
    'id'        => $id,
    'user_id'   => get_current_user_id(),
    'name'      => $name,
    'date'      => $date,
    'size'      => in5_convertBytesToSize($size),
    'directUrl' => $directUrl,
    'width'     => $width,
    'height'    => $height,
  );

  // print_r($archiveData);,,
  return $archiveData;
}

function in5_writeMetaData($metaData)
{
  $currentMeta          = in5_get_metadata();
  $currentMeta          = json_decode($currentMeta);
  $currentMeta->files[] = $metaData;
  $currentMeta          = json_encode($currentMeta);
  $handle               = fopen(in5_get_meta_file(), 'w');
  fwrite($handle, $currentMeta);
  fclose($handle);
}

function in5_display_file_list()
{
  $meta = in5_get_metadata();
  if ($meta == false) {
    return false;
  }
  $metaArray = json_decode($meta);
  $metaArray = (array) $metaArray;
  if (count($metaArray['files']) < 1) {
    return false;
  }
  for ($i = 0; $i < count($metaArray['files']); $i++) {
    $metaStuff = $metaArray['files'][$i];
    ?>
    <li data-in5-file-id="<?php echo $metaStuff->id; ?>">
      <img src="<?php echo IN5_PLUGIN_URL . 'assets/img/archive.png'; ?>">
      <div class="in5-file-title">
        <span><?php echo str_replace("\'", "'", $metaStuff->name); ?></span>
      </div>
    </li>
  <?php
  }
}

/*works even with custom paths*/
function in5_getPathFromUrl($directUrl)
{
  $home_path = get_home_path();
  $home_dir = basename( rtrim($home_path,'/') );
  $parts = explode($home_dir, $directUrl);
  $final = dirname( dirname($home_path) . '/' . $home_dir . $parts[1] );
  return $final;
}

function in5_delete_file()
{
  check_ajax_referer('in5-security-string', 'security');

  $fileID = sanitize_text_field($_POST['id']);
  if (!is_numeric($fileID)) {
    return false;
  }
  $directUrl = sanitize_text_field($_POST['directUrl']);
  if ($directUrl == '') {
    return false;
  }

  // Delete archive
  $archiveLocation = in5_getPathFromUrl($directUrl);

  in5_rrmdir($archiveLocation2);

  // Delete from meta
  $meta = in5_get_metadata();
  if ($meta == false) {
    return false;
  }
  $metaArray = json_decode($meta);
  for ($i = 0; $i < count($metaArray->files); $i++) {
    if ($metaArray->files[$i]->id == $fileID) {
      unset($metaArray->files[$i]);
    }
  }
  $metaArray->files = array_values($metaArray->files);
  $newMeta          = json_encode($metaArray);
  $handle           = fopen(in5_get_meta_file(), 'w');
  fwrite($handle, $newMeta);
  fclose($handle);

  echo "success";

  die();
}

add_action('wp_ajax_in5_delete_permanently', 'in5_delete_file');

function in5_change_filename()
{
  check_ajax_referer('in5-security-string', 'security');

  $fileID   = sanitize_text_field($_POST['id']);
  $fileName = sanitize_file_name($_POST['fileName']);

  if (!is_numeric($fileID)) {
    return false;
  }

  $meta       = in5_get_metadata();
  $decode     = json_decode($meta);
  $filesArray = (array) $decode;
  $files      = $filesArray['files'];

  for ($i = 0; $i < count($files); $i++) {
    if ($files[$i]->id == $fileID) {
      $files[$i]->name = $fileName;
      break;
    }
  }

  $newMeta = json_encode($filesArray);
  $handle  = fopen(in5_get_meta_file(), 'w');
  fwrite($handle, $newMeta);
  fclose($handle);

  echo "success";

  die();
}

add_action('wp_ajax_in5_change_filename', 'in5_change_filename');

function in5_save_attributes()
{
  check_ajax_referer('in5-security-string', 'security');

  $fileID = sanitize_text_field($_POST['id']);
  $width  = sanitize_text_field($_POST['width']);
  if (!in5_validateContainsNumber($width)) {
    return false;
  }
  $height = sanitize_text_field($_POST['height']);
  if (!in5_validateContainsNumber($height)) {
    return false;
  }

  if (!is_numeric($fileID)) {
    return false;
  }
  $noPercentageWidth  = str_replace('%', '', $width);
  $noPercentageHeight = str_replace('%', '', $height);
  if (!is_numeric($noPercentageWidth) || !is_numeric($noPercentageHeight) ) {
    return false;
  }

  $meta       = in5_get_metadata();
  $decode     = json_decode($meta);
  $filesArray = (array) $decode;
  $files      = $filesArray['files'];

  for ($i = 0; $i < count($files); $i++) {
    if ($files[$i]->id == $fileID) {
      $files[$i]->width  = $width;
      $files[$i]->height = $height;
      break;
    }
  }

  $newMeta = json_encode($filesArray);
  $handle  = fopen(in5_get_meta_file(), 'w');
  fwrite($handle, $newMeta);
  fclose($handle);

  echo "success";

  die();
}

add_action('wp_ajax_in5_save_attributes', 'in5_save_attributes');

function in5_validateContainsNumber($n)
{
  return preg_match('/[0-9]/', $n);
}

?>