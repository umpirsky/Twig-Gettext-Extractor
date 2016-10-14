<h3 align="center">
    <a href="https://github.com/umpirsky">
        <img src="https://farm2.staticflickr.com/1709/25098526884_ae4d50465f_o_d.png" />
    </a>
</h3>
<p align="center">
  <a href="https://github.com/umpirsky/Symfony-Upgrade-Fixer">symfony upgrade fixer</a> &bull;
  <b>twig gettext extractor</b> &bull;
  <a href="https://github.com/umpirsky/wisdom">wisdom</a> &bull;
  <a href="https://github.com/umpirsky/centipede">centipede</a> &bull;
  <a href="https://github.com/umpirsky/PermissionsHandler">permissions handler</a> &bull;
  <a href="https://github.com/umpirsky/Extraload">extraload</a> &bull;
  <a href="https://github.com/umpirsky/Gravatar">gravatar</a> &bull;
  <a href="https://github.com/umpirsky/locurro">locurro</a> &bull;
  <a href="https://github.com/umpirsky/country-list">country list</a> &bull;
  <a href="https://github.com/umpirsky/Transliterator">transliterator</a>
</p>

Twig Gettext Extractor [![Build Status](https://secure.travis-ci.org/umpirsky/Twig-Gettext-Extractor.svg?branch=master)](http://travis-ci.org/umpirsky/Twig-Gettext-Extractor)
======================

The Twig Gettext Extractor is [Poedit](http://www.poedit.net/download.php)
friendly tool which extracts translations from twig templates.

## Installation

### Manual

#### Local

Download the ``twig-gettext-extractor.phar`` file and store it somewhere on your computer.

#### Global

You can run these commands to easily access ``twig-gettext-extractor`` from anywhere on
your system:

```bash
$ sudo wget https://github.com/umpirsky/Twig-Gettext-Extractor/releases/download/1.2.0/twig-gettext-extractor.phar -O /usr/local/bin/twig-gettext-extractor
$ sudo chmod a+x /usr/local/bin/twig-gettext-extractor
```
Then, just run ``twig-gettext-extractor``.

### Composer

#### Local

```bash
$ composer require umpirsky/twig-gettext-extractor
```

#### Global

```bash
$ composer global require umpirsky/twig-gettext-extractor
```

Make sure you have ``~/.composer/vendor/bin`` in your ``PATH`` and
you're good to go:

```bash
$ export PATH="$PATH:$HOME/.composer/vendor/bin"
```
Don't forget to add this line in your `.bashrc` file if you want to keep this change after reboot.

## Setup

By default, Poedit does not have the ability to parse Twig templates.
This can be resolved by adding an additional parser (Edit > Preferences > Parsers)
with the following options:

- Language: `Twig`
- List of extensions: `*.twig`
- Invocation:
    - Parser command: `<project>/vendor/bin/twig-gettext-extractor --sort-output --force-po -o %o %C %K -L PHP --files %F` (replace `<project>` with absolute path to your project)
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
