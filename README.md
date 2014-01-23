MP3 Player
==========

**Version 1.0.2**

Review the latest changes from the [CHANGELOG.md](CHANGELOG.md).

[![Build Status](https://travis-ci.org/diemuzi/mp3.png?branch=master)](https://travis-ci.org/diemuzi/mp3)

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

Features
--------

* Play music from your library
* Play Single Songs or Full Albums
* Download Songs from your library
* Translatable
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
