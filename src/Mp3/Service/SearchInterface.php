<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
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
     * Index
     *
     * @param array $params
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function Index(array $params);

    /**
     * Search
     *
     * @param string $name
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function Search($name);

    /**
     * Play All
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function PlayAll($dir);

    /**
     * Play Single Song
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function PlaySingle($dir);

    /**
     * Download Single
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function DownloadSingle($dir);

    /**
     * Import Search Results
     *
     * @throws \Exception
     */
    public function Import();

    /**
     * Parse Help
     *
     * @param string $help
     *
     * @return string
     */
    public function Help($help);
}
