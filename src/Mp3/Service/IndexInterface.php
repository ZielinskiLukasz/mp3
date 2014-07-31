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
 * Interface IndexInterface
 *
 * @package Mp3\Service
 */
interface IndexInterface
{
    /**
     * Index
     *
     * @param array $params
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function index(array $params);

    /**
     * Play All
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playAll($dir);

    /**
     * Play Single Song
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playSingle($dir);

    /**
     * Download Single
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function downloadSingle($dir);

    /**
     * Download Folder
     *
     * @param array $params
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function downloadFolder(array $params);
}
