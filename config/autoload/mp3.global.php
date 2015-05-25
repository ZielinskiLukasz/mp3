<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

return [
    'mp3' => [
        /**
         * Base Directory
         *
         * Must be Web Accessible and only contain the location without a full URL
         *
         * If your URL is http://example.com/music then only enter /music
         *
         * Example: /music
         */
        'baseDir'    => '/music',

        /**
         * Format
         *
         * Support types are; pls, m3u
         */
        'format'     => 'pls',

        /**
         * Full Path to search.txt
         *
         * If you want to enable the Search Feature
         * You will need a file to populate all the results from your library
         *
         * Warning: This may consume a lot of memory depending on how large your respository is
         *          You may need to increase the 'memory_limit' for PHP
         */
        'searchFile' => '/backup/domains/mp3-devel/public/search.txt',

        /**
         * Full Path to Search
         *
         * Define the base search path for your library
         */
        'searchPath' => '/backup/domains/mp3-devel/public/music'
    ]
];
