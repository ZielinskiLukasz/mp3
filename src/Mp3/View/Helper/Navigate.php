<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2013, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class Navigate
 *
 * @package Mp3\View\Helper
 */
class Navigate extends AbstractHelper
{
    /**
     * Navigation
     *
     * @param string $path
     *
     * @return array
     */
    public function __invoke($path)
    {
        $navigate = [];

        $explode = explode(
            '/',
            $path
        );

        $res = null;

        for ($i = 1; $i < count($explode); $i++) {
            $res .= '/' . $explode[$i];

            $navigate[] = [
                'url'  => $res,
                'text' => $explode[$i]
            ];
        }

        return $navigate;
    }
}
