<?php

if (function_exists('d')) {
  return;
}

$fakeKint = $_SERVER['SERVER_NAME'] === '192.168.1.11' ||
        $_SERVER['SERVER_NAME'] === 'localhost';

$kintSearchDepth = 3;
$kintLocation = '../kint';
for ($i = 0; $i < $kintSearchDepth; ++$i) {
  if (@file_exists("$kintLocation/Kint.class.php")) {
    $kintFile = "$kintLocation/Kint.class.php";
    break;
  }
  else {
    $kintLocation = "../$kintLocation";
  }
}
if (!empty($kintFile)) {
  include $kintFile;
}
else if (!empty($killKint)) {

  function d() {
    echo "<pre>Kint Function <b>d()</b> called:\n";
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    echo "</pre>";
  }

  function dd() {
    echo "<pre>Kint Function <b>dd()</b> called:\n";
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    die("Quitting because of <b>dd()</b></pre>");
  }

}
else if ($fakeKint) {

  function varName($v) {
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $vLine = file($trace[1]['file']);
    $fLine = $vLine[$trace[1]['line'] - 1];
    preg_match("#\\$(\w+)#", $fLine, $match);
    return $match[0];
  }

  function d($arg) {
    echo "<pre>";
    echo "Printing <b>" . varName($arg) . "</b>\n";
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    echo htmlspecialchars(print_r($arg, true));
    echo "</pre>";
  }

  function dd($arg) {
    d($arg);
    die("Quitting because of <b>dd()</b></pre>");
  }

}
else {

  function d() {

  }

  function dd() {

  }

}