<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2013, OTWebsoft Corporation
 * @license   http://otwebsoft.com/license
 * @link      http://otwebsoft.com OTWebsoft
 */

namespace Mp3\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class Convert
 *
 * @package Mp3\View\Helper
 */
class Convert extends AbstractHelper
{
    /**
     * Convert Filesize to Human Readable Format
     *
     * @param string $bytes
     *
     * @return string
     */
    public function __invoke($bytes)
    {
        $result = null;

        $bytes = floatval($bytes);

        $arBytes = array(
            0 => array(
                "UNIT"  => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT"  => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT"  => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT"  => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT"  => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                if ($arItem["VALUE"] != 'B' || $arItem["VALUE"] != 'KB') {
                    $result = strval(round($result, 2)) . " " . $arItem["UNIT"];
                } else {
                    $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                }

                break;
            }
        }

        return $result;
    }
}
