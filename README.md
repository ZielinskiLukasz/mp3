MP3 Player
==========

**Version 1.1.2**

Review the latest changes from the [CHANGELOG.md](CHANGELOG.md).

[![Build Status](https://travis-ci.org/diemuzi/mp3.png?branch=master)](https://travis-ci.org/diemuzi/mp3)
[![Total Downloads](https://poser.pugx.org/diemuzi/mp3/downloads.png)](https://packagist.org/packages/diemuzi/mp3)
[![Latest Stable Version](https://poser.pugx.org/diemuzi/mp3/v/stable.png)](https://packagist.org/packages/diemuzi/mp3)
[![Dependency Status](https://www.versioneye.com/user/projects/52e2d329ec1375da4b00001a/badge.png)](https://www.versioneye.com/user/projects/52e2d329ec1375da4b00001a)

Introduction
------------

MP3 Player is a web-based music player which streams content to your local PC in several formats.

Supported formats are currently:

  * PLS (Winamp)
  * M3U (Windows Media Player)

Support
-------

* We can be found on the Freenode IRC Network in #otwebsoft
* More information is always available in the [Wiki](../../wiki)

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2)

* [PHP Gettext Extension](http://php.net/gettext)

* [PHP Phar Extension](http://php.net/phar) *Recommended, but not required*

Features
--------

* Searchable
* Translatable
* Play Full Albums or Single Songs
* Download Folders in .zip, .tar, .bz2, and .rar Formats (Requires PHP Phar Extension)
* Download Songs from your library
* More coming soon, see [TODO.md](TODO.md)

Installation
------------

1. Install the module via composer by running:

   ```sh
   php composer.phar require diemuzi/mp3:dev-master
   ```
   or download it directly from github and place it in your application's `module/` directory.
2. Add the `Mp3` module to the module section of your `config/application.config.php`.
3. Edit `config/autoload/mp3.global.php` and change the settings per your configuration.

Screenshots
-----------

Main Screen

![](docs/search.png)

Play Lists

![](docs/songs.png)
