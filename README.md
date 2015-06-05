MP3 Player
==========

**Version 1.3.0** (Last Updated on 06-05-2015)

Review the latest changes from the [CHANGELOG.md](CHANGELOG.md).

[![Build Status](https://travis-ci.org/diemuzi/mp3.png?branch=master)](https://travis-ci.org/diemuzi/mp3)
[![Total Downloads](https://poser.pugx.org/diemuzi/mp3/downloads.png)](https://packagist.org/packages/diemuzi/mp3)
[![Latest Stable Version](https://poser.pugx.org/diemuzi/mp3/v/stable.png)](https://packagist.org/packages/diemuzi/mp3)
[![Dependency Status](https://www.versioneye.com/user/projects/52e2d329ec1375da4b00001a/badge.png)](https://www.versioneye.com/user/projects/52e2d329ec1375da4b00001a)

Introduction
------------

MP3 Player is a web-based music player which streams content to your local PC in several formats.

Supported playlist formats are currently:

  * PLS (Shoutcast / Icecast / Winamp)
  * M3U (Common Steaming)

Supported File Extensions:

  * .flac
  * .m4a
  * .mp3
  * .wav
  * .wma

Support
-------

* We can be found on the Freenode IRC Network in #otwebsoft
* More information is always available in the [Wiki](../../wiki)

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2)

* [PHP Gettext Extension](http://php.net/gettext)

* [getID3](https://github.com/JamesHeinrich/getID3)

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
4. Copy the `config/autoload/mp3.global.php` to your autoload folder

Standalone Installation
-----------------------

If you're looking to run this software on it's own domain and don't plan on running any other modules you can use the MP3 Skeleton.

1. git clone https://github.com/diemuzi/mp3-skeleton.git
2. cd mp3-skeleton
3. php composer.phar self-update
4. php composer.phar install
5. chown -R nginx:nginx /backup/domains/mp3-skeleton
6. ln -s /backup/domains/mp3-skeleton/vendor/diemuzi/mp3/config/autoload/mp3.global.php /backup/domains/mp3-skeleton/config/autoload/mp3.global.php
7. Access http://example.com/mp3/index (for example)

This should get you started, just don't forget to change the paths to match your setup. If you run into any problems, let me know!

Screenshots
-----------

Main Screen

![](docs/search.png)

Play Lists

![](docs/songs.png)

Search and Download Options Enabled

![](docs/download.png)
