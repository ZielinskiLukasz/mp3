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
    public function Index(array $params);

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
     * Download Folder in ZIP Format
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function DownloadFolderZip($dir);
}
