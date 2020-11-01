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
    'reference' => '29e480a0b2d55d58ce4593609dc7c7fdc0367aa8',
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
      'reference' => '29e480a0b2d55d58ce4593609dc7c7fdc0367aa8',
    ),
    'phug/ast' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/compiler' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/dependency-injection' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/event' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/facade' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/formatter' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/invoker' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/lexer' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/parser' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/phug' => 
    array (
      'pretty_version' => '1.8.1',
      'version' => '1.8.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ee81108f9d7be420b8c57571679168ebd64f1762',
    ),
    'phug/reader' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/renderer' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'phug/util' => 
    array (
      'replaced' => 
      array (
        0 => '1.8.1',
      ),
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '39d483bdf39be819deabf04ec872eb0b2410b531',
    ),
    'symfony/polyfill-php72' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cede45fcdfabdd6043b3592e83678e42ec69e930',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e70aa8b064c5b72d3df2abd5ab1e90464ad009de',
    ),
    'symfony/var-dumper' => 
    array (
      'pretty_version' => 'v4.4.16',
      'version' => '4.4.16.0',
      'aliases' => 
      array (
      ),
      'reference' => '3718e18b68d955348ad860e505991802c09f5f73',
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
