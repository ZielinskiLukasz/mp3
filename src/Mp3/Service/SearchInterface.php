<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 OTWebsoft Corporation
 * @license   http://otwebsoft.com/license License
 * @link      http://otwebsoft.com OTWebsoft
 */

namespace Mp3\Service;

use Zend\Paginator\Paginator;

/**
 * Interface SearchInterface
 *
 * @package Mp3\Service
 */
interface SearchInterface
{
    /**
     * Search
     *
     * @param string $string
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function find($string);

    /**
     * Import Search Results
     *
     * @throws \Exception
     */
    public function import();

    /**
     * Parse Help
     *
     * @param string $help
     *
     * @return string
     */
    public function help($help);

    /**
     * Determines PHP's Memory Usage Overflow
     *
     * Usage: Set memoryLimit in mp3.global.php to true in order to use this feature
     *
     * @return void
     */
    public function memoryUsage();
}
