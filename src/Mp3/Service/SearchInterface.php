<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, OTWebsoft Corporation
 * @license   http://otwebsoft.com/license
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
     * @param array $params
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function Search(array $params);

    /**
     * Play All
     *
     * @param string $dir
     * @param null   $playlist
     *
     * @return null|string
     * @throws \Exception
     */
    public function PlayAll($dir, $playlist = null);

    /**
     * Play Single Song
     *
     * @param string $dir
     * @param null   $playlist
     *
     * @return null|string
     * @throws \Exception
     */
    public function PlaySingle($dir, $playlist = null);

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
     * Directory Array
     *
     * @param string $dir
     *
     * @return array
     * @throws \Exception
     */
    public function DirectoryArray($dir);
}
