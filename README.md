# Xml2Array [![Latest Stable Version](https://poser.pugx.org/midnite81/xml2array/version)](https://packagist.org/packages/midnite81/xml2array) [![Total Downloads](https://poser.pugx.org/midnite81/xml2array/downloads)](https://packagist.org/packages/midnite81/xml2array) [![Latest Unstable Version](https://poser.pugx.org/midnite81/xml2array/v/unstable)](https://packagist.org/packages/midnite81/xml2array) [![License](https://poser.pugx.org/midnite81/xml2array/license.svg)](https://packagist.org/packages/midnite81/xml2array) [![Build](https://travis-ci.org/midnite81/xml2array.svg?branch=master)](https://travis-ci.org/midnite81/xml2array) [![Coverage Status](https://coveralls.io/repos/github/midnite81/xml2array/badge.svg?branch=master)](https://coveralls.io/github/midnite81/xml2array?branch=master)
_Package based on [gaarf/XML-string-to-PHP-array](https://github.com/gaarf/XML-string-to-PHP-array)_

# Installation

To install through composer include the package in your `composer.json`.

_If you are using php 8.1+ use "^2.0.0" for php7 use "^1.0.0"._

    "midnite81/xml2array": "^2.0.0"

Run `composer install` or `composer update` to download the dependencies, or 
you can run `composer require midnite81/xml2array`.

# Example usage:
 
```php
use Midnite81\Xml2Array\Xml2Array;

$xml = Xml2Array::create($someXmlString);
// or $xml = (new Xml2Array())->convert($someXmlString);
 
```

If the string is invalid then an `IncorrectFormatException` will be thrown, 
otherwise an `XmlResponse` class will be returned.

You can access the `XmlResponse` class like an array, as such:

`echo $xml['result'];`

Other methods include: 

| Method                  | Description                     |
|-------------------------|---------------------------------|
| `$xml->toArray();`      | Returns the array               |
| `$xml->toJson();`       | Returns as JSON                 | 
| `$xml->toCollection()`  | Returns as Laravel Collection*  |
| `$xml->serialize()`     | Returns the array serialized    |
| `$xml->serialise()`     | Alias of above                  |

* It will throw an exception if you try to run `$xml->toCollection()` but 
you do not have the `collect` helper available.