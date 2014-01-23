<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2013, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
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
            array(
                'unit'  => 'TB',
                'value' => pow(1024, 4)
            ),
            array(
                'unit'  => 'GB',
                'value' => pow(1024, 3)
            ),
            array(
                'unit'  => 'MB',
                'value' => pow(1024, 2)
            ),
            array(
                'unit'  => 'KB',
                'value' => 1024
            ),
            array(
                'unit'  => 'B',
                'value' => 1
            )
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem['value']) {
                $result = $bytes / $arItem['value'];

                if ($arItem['value'] != 'B' || $arItem['value'] != 'KB') {
                    $result = strval(round($result, 2)) . ' ' . $arItem['unit'];
                } else {
                    $result = str_replace('.', ',', strval(round($result, 2))) . ' ' . $arItem['unit'];
                }

                break;
            }
        }

        return $result;
    }
}
