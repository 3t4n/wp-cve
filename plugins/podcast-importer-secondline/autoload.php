<?php
defined( 'ABSPATH' ) || exit;

spl_autoload_register(function ($class) {

  /* class prefix */
  $prefix = 'PodcastImporterSecondLine\\';

  /* base directory class files. */
  $base_dir = __DIR__ . '/app/';

  /* check class prefix. */
  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    return;
  }

  /* get the relative class name. */
  $relative_class = substr($class, $len);

  /* get class file. */
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  /* if the file exists, require it. */
  if (file_exists($file)) {
    require $file;
  }
});