# Twigeval

[![Latest Stable Version](https://img.shields.io/packagist/v/khanhicetea/twigeval.svg)](https://packagist.org/packages/khanhicetea/twigeval)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg)](https://php.net/)
[![Build Status](https://api.travis-ci.org/khanhicetea/twigeval.svg?branch=master)](https://travis-ci.org/khanhicetea/twigeval)
[![GitHub license](https://img.shields.io/github/license/khanhicetea/twigeval.svg)](https://github.com/khanhicetea/twigeval/blob/master/LICENSE)

**SAFE** processing expression of variables from string without using `eval` (seems to be **evil**).

## Core

Using **twig** template engine to produce the result, so you can use any twig syntax and its filters.

## Usage

```bash
$ composer require khanhicetea/twigeval
```

```php
/*
$cacheDir could be :
    - false : no use cache (mean use eval function), be carefully !
    - null : use sys_get_temp_dir() to get system temp directory as cache dir
    - string : cache directory path
*/
$cacheDir = null;
$calculator = new KhanhIceTea\Twigeval\Calculator($cacheDir);
$math = $calculator->number('a / 4 + b * 3', ['a' => 16, 'b' => 3]); // => 13
$boolean1 = $calculator->isTrue('(a and b) or c', ['a' => false, 'b' => true, 'c' => false]); // => false
$boolean2 = $calculator->isFalse('(a and b) or c', ['a' => false, 'b' => true, 'c' => false]); // => true
$string = $calculator->calculate('{{ a|reverse }} world !', ['a' => 'hello']); // => olleh world !
```

## LICENSE

The MIT License (MIT)
Copyright (c) 2018 KhanhIceTea