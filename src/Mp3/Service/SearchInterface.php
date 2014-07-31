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
     * @param string $name
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function find($name);

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
     * @return void
     */
    public function memoryUsage();
}
