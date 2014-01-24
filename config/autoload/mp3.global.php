<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

return array(
    'mp3' => array(
        /**
         * Base Directory
         * Must be Web Accessible
         *
         * Example: http://example.com/music
         */
        'base_dir'    => '/music',

        /**
         * Format
         * Support types are; pls, m3u
         */
        'format'      => 'pls',

        /**
         * Full Path to search.txt
         *
         * If you want to enable the Search Feature
         * You will need a file to populate all the results from your library
         */
        'search_file' => '/backup/domains/mp3-devel/public/search.txt',

        /**
         * Search Path
         *
         * Define the base search_path for your library
         */
        'search_path' => '/backup/domains/mp3-devel/public/music'
    )
);
