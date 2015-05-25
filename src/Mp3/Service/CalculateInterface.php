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
 * Class Calculate
 *
 * @package Mp3\Service
 */
interface CalculateInterface
{
    /**
     * Get Meta Data
     *
     * @return array
     */
    public function get_metadata();

    /**
     * Is Layer
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_layer1(&$mp3);

    /**
     * Is Layer 2
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_layer2(&$mp3);

    /**
     * Is Layer 3
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_layer3(&$mp3);

    /**
     * Is MPEG 10
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_mpeg10(&$mp3);

    /**
     * Is MPEG 20
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_mpeg20(&$mp3);

    /**
     * Is MPEG 25
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_mpeg25(&$mp3);

    /**
     * Is MPEG 20 or 25
     *
     * @param $mp3
     *
     * @return bool
     */
    public static function is_mpeg20or25(&$mp3);

    /**
     * Bit Rate Lookup
     *
     * @param $mp3
     *
     * @return string
     */
    public static function bitratelookup(&$mp3);

    /**
     * Sample Lookup
     *
     * @param $mp3
     *
     * @return string
     */
    public static function samplelookup(&$mp3);

    /**
     * Get Frame Size
     *
     * @param $mp3
     *
     * @return float|string
     */
    public static function getframesize(&$mp3);

    /**
     * Get Duration
     *
     * @param $mp3
     * @param $startat
     *
     * @return string
     */
    public static function getduration(
        &$mp3,
        $startat
    );

    /**
     * Seconds to MM:SS
     *
     * @param $duration
     *
     * @return string
     */
    public static function seconds_to_mmss($duration);
}
