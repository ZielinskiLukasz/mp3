<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Service;

/**
 * Class FunctionTrait
 *
 * @package Mp3\Service
 */
trait FunctionTrait
{
    /**
     * Converts Timestamp from HH:MM:SS to Seconds
     *
     * @param string $length
     * @param string $hours
     * @param string $minutes
     * @param string $seconds
     *
     * @return string
     */
    protected function convertTime(
        $length,
        $hours = '0',
        $minutes = '00',
        $seconds = '00'
    ) {
        $length = preg_replace(
            "/^([\d]{1,2})\:([\d]{2})$/",
            "00:$1:$2",
            $length
        );

        sscanf(
            $length,
            "%d:%d:%d",
            $hours,
            $minutes,
            $seconds
        );

        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    /**
     * Get ID3 Details
     *
     * @param string $path
     *
     * @return array
     */
    protected function getId3($path)
    {
        $getID3 = new \getID3();

        $analyze = $getID3->analyze($path);

        \getid3_lib::CopyTagsToComments($analyze);

        return $analyze;
    }

    /**
     * Extensions
     *
     * @return array
     */
    protected function getExtensions()
    {
        return [
            '.flac',
            '.m4a',
            '.mp3',
            '.wav',
            '.wma'
        ];
    }

    /**
     * Clean Up Path
     * Removes extra characters from a filename
     * Some browsers error when there are too many dots, spaces, or comma's for example in a file
     *
     * @param string $path
     *
     * @return string
     */
    protected function cleanPath($path)
    {
        $lastDot = strrpos(
            $path,
            '.'
        );

        $replace = [
            '.',
            ','
        ];

        $string = str_replace(
                      $replace,
                      '',
                      substr(
                          $path,
                          0,
                          $lastDot
                      )
                  ) . substr(
                      $path,
                      $lastDot
                  );

        return $string;
    }
}
