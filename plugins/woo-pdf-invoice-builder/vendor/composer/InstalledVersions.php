<?php











namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => 'f9462f1723d6b2617d07bc6bafe8fbdb41dd6765',
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => 'f9462f1723d6b2617d07bc6bafe8fbdb41dd6765',
    ),
    'dompdf/dompdf' => 
    array (
      'pretty_version' => 'v0.8.1',
      'version' => '0.8.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '9ea852c4bdc74fac5def165e6cd52353f7ca3b3f',
    ),
    'phenx/php-font-lib' => 
    array (
      'pretty_version' => '0.5.1',
      'version' => '0.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '760148820110a1ae0936e5cc35851e25a938bc97',
    ),
    'phenx/php-svg-lib' => 
    array (
      'pretty_version' => 'v0.3',
      'version' => '0.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a85f7fe9fe08d093a4a8583cdd306b553ff918aa',
    ),
    'printnode/printnode-php' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '960acabca3c0d976bbd92bf0d6490a1fe5df7496',
    ),
    'sabberworm/php-css-parser' => 
    array (
      'pretty_version' => '8.1.0',
      'version' => '8.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '850cbbcbe7fbb155387a151ea562897a67e242ef',
    ),
    'spipu/html2pdf' => 
    array (
      'pretty_version' => 'v5.0.1',
      'version' => '5.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bbfcde715c0458300af8bf7f56b040dcd666a0db',
    ),
    'tecnickcom/tcpdf' => 
    array (
      'pretty_version' => '6.2.13',
      'version' => '6.2.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '95c5938aafe4b20df1454dbddb3e5005c0b26f64',
    ),
    'wapmorgan/imagery' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '46301a475b73f597d923c972d44a4226454b1e46',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
