<?php

include __DIR__ . '/app/helpers/helpers.php';
// use Mnet\Helpers\Arr;

function mnet_config($key, $default = null)
{
  $config = json_decode(file_get_contents(__DIR__ . '/plugin.config.json'), true);
  if (isset($config[$key])) {
    return $config[$key];
  }
  return $default;
}

function getCurrentVersion()
{
  return mnet_config('version');
}

function mnet_normalize_chunks($filepath)
{
  $paths = preg_split('#/#', $filepath);

  $path = implode('/', array_slice($paths, 0, -1));

  $filename = $paths[count($paths) - 1];

  $normalizeJson = json_decode(file_get_contents(__DIR__ . "/dist/normalizeChunks.json"), true);
  return "{$path}/" . \Arr::get($normalizeJson, $filename, $filename);
}
