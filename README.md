MP3 Player
=======
Version 1.0.0 Created by Sammie S. Taunton

[![Build Status](https://travis-ci.org/diemuzi/mp3.png?branch=master)](https://travis-ci.org/diemuzi/mp3)

Introduction
------------

MP3 Player is a web-based music player which streams content to your local PC in several formats.

Supported formats are currently:

  * PLS (Winamp)
  * M3U (Windows Media Player)

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Features / Goals
----------------

* Play music from your library
* Play Single Songs or Full Albums
* Download Songs from your library

Installation
------------

### Main Setup

#### By cloning project

1. Install the [mp3](https://github.com/diemuzi/mp3) ZF2 module
   by cloning it into `./vendor/`.
2. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "diemuzi/mp3": "dev-master"
    }
    ```

2. Now tell composer to download mp3 by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'Mp3',
        ),
        // ...
    );
    ```
