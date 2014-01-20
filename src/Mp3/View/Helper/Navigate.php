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
     *
     * @TODO Bug when the final and previous directory names are the same
     */
    public function __invoke($path)
    {
        $array = array();

        $explode = explode('/', preg_replace('/(\/+)/', '/', $path));

        foreach (array_filter($explode) as $value) {
            $explode2 = explode($value, $path);

            $array[] = current($explode2) . prev($explode);
        }

        $navigate = array();

        foreach ($array as $dir) {
            $text1 = str_replace(current($array), '', $dir);
            $text2 = str_replace('/', '', $dir);

            $dir = preg_replace('/(\/+)/', '/', $dir);

            if ($text1 == '') {
                $navigate[] = array(
                    'url'  => rawurlencode($dir),
                    'text' => $text2
                );
            } else {
                $navigate[] = array(
                    'url'  => rawurlencode($dir),
                    'text' => str_replace('/', '', $text1)
                );
            }
        }

        $navigate[] = array(
            'url'  => null,
            'text' => end($explode)
        );

        return $navigate;
    }
}
