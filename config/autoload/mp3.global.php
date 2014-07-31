<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

return array(
    'mp3' => array(
        /**
         * Base Directory
         *
         * Must be Web Accessible and only contain the location without a full URL
         *
         * Example: http://example.com/music
         */
        'baseDir'    => '/music',

        /**
         * Format
         *
         * Support types are; pls, m3u
         */
        'format'      => 'pls',

        /**
         * Full Path to search.txt
         *
         * If you want to enable the Search Feature
         * You will need a file to populate all the results from your library
         */
        'searchFile' => '/backup/domains/mp3-devel/public/search.txt',

        /**
         * Full Path to Search
         *
         * Define the base searchPath for your library
         */
        'searchPath' => '/backup/domains/mp3-devel/public/music'
    )
);
