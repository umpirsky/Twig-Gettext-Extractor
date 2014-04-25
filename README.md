Twig Gettext Extractor [![Build Status](https://secure.travis-ci.org/umpirsky/Twig-Gettext-Extractor.png?branch=master)](http://travis-ci.org/umpirsky/Twig-Gettext-Extractor)
======================

The Twig Gettext Extractor is [Poedit](http://www.poedit.net/download.php)
friendly tool which extracts translations from twig templates.

## Installation

The recommended way to install Twig Gettext Extractor is through
[composer](http://getcomposer.org).

```json
{
    "require": {
        "umpirsky/twig-gettext-extractor": "1.1.*"
    }
}
```

## Setup

By default, Poedit does not have the ability to parse Twig templates.
This can be resolved by adding an additional parser (Edit > Preferences > Parsers)
with the following options:

- Language: `Twig`
- List of extensions: `*.twig`
- Invocation:
    - Parser command: `<project>/vendor/bin/twig-gettext-extractor --sort-output --force-po -o %o %C %K -L PHP --files %F`
    - An item in keyword list: `-k%k`
    - An item in input file list: `%f`
    - Source code charset: `--from-code=%c`

<img src="http://i.imgur.com/f9px2.png" />

Now you can update your catalog and Poedit will synchronize it with your twig
templates.

## Custom extensions

Twig-Gettext-Extractor registers some default twig extensions. However, if you are using custom extensions, you need to register them first before you can extract the data. In order to achieve that, copy the binfile into some custom place. A common practice would be: `cp vendor/bin/twig-gettext-extractor bin/twig-gettext-extractor`

Now you may add your custom extensions [here](https://github.com/umpirsky/Twig-Gettext-Extractor/blob/master/twig-gettext-extractor#L41):

```php
$twig->addFunction(new \Twig_SimpleFunction('myCustomExtension', true));
$twig->addFunction(new \Twig_SimpleFunction('myCustomExtension2', true));
```

## Tests

To run the test suite, you need [composer](http://getcomposer.org) and
[PHPUnit](https://github.com/sebastianbergmann/phpunit).

    $ composer install --dev
    $ phpunit

## License

Twig Gettext Extractor is licensed under the MIT license.
