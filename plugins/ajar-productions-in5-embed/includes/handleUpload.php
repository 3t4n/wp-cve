<?php

if (!defined('ABSPATH')) {
  exit;
} // Exit if accessed directly

if ( ! defined( 'DOING_AJAX' ) ) {
	return false;
}
if ( ! isset( $_GET['in5'] ) || $_GET['in5'] <> 'upload' ) {
	return false;
}
if ( ! isset( $_FILES ) ) {
	return false;
}

$files             = $_FILES;
$files             = $_FILES['files'];
$allFiles['files'] = array();

$customPath = ($_POST['customPath']) ? $_POST['customPath'] : 'wp-content/uploads/in5-archives';

if ($customPath !== '') {
  $customPath = _normalise($customPath);
}

$name    = $files['name'][0];
$size    = $files['size'][0];
$tmpName = $files['tmp_name'][0];
$type    = $files['type'][0];
$date    = date( "F d, Y" );

if ( in5_validateSize( $size ) == false ) {
	$maxSize = ini_get( 'upload_max_filesize' );
	echo 'Maximum allowed file size is ' . $maxSize . '!';
	wp_die();
}

if ( in5_validateFile( $name, $type ) == false ) {
	echo "Invalid file! Please try uploading another file.";
	wp_die();
}

$folderName = in5_doArchiveUpload($name, $tmpName, $customPath);
if ($folderName == "") {
  echo "The archive " . $name . " does not contain an index.html!";
  wp_die();
}

$metaData = in5_extractMetaData($folderName, $size, $customPath);
in5_writeMetaData($metaData);

$allFiles['files'][] = $metaData;
$allFiles['directory'] = $customPath;

echo json_encode( $allFiles );
wp_die();


/**
 * Normalise a file path string so that it can be checked safely.
 *
 * Attempt to avoid invalid encoding bugs by transcoding the path. Then 
 * remove any unnecessary path components including '.', '..' and ''.
 *
 * @param $path string
 *     The path to normalise.
 * @param $encoding string
 *     The name of the path iconv() encoding.
 * @return string
 *    The path, normalised.
 */
function _normalise($path, $encoding = "UTF-8")
{

  // Attempt to avoid path encoding problems.
  $path = iconv($encoding, "$encoding//IGNORE//TRANSLIT", $path);
  // Process the components
  $parts = explode('/', $path);
  $safe = array();
  foreach ($parts as $idx => $part) {
    if (empty($part) || ('.' == $part)) {
      continue;
    } elseif ('..' == $part) {
      array_pop($safe);
      continue;
    } else {
      $safe[] = $part;
    }
  }

  // Return the "clean" path
  $path = implode(DIRECTORY_SEPARATOR, $safe);
  return $path;
}
