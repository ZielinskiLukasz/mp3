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
     */
    public function __invoke($path)
    {
        $navigate = array();

        $explode = explode('/', $path);

        $res = null;

        for ($i = 1; $i < count($explode); $i++) {
            $res .= '/' . $explode[$i];

            $navigate[] = array(
                'url'  => $res,
                'text' => $explode[$i]
            );
        }

        return $navigate;
    }
}
