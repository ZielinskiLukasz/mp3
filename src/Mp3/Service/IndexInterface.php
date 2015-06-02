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

/**
 * Interface IndexInterface
 *
 * @package Mp3\Service
 */
interface IndexInterface
{
    /**
     * Directory Listing
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function index(array $params);

    /**
     * Play All
     *
     * @param string $base64
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playAll($base64);

    /**
     * Play Single Song
     *
     * @param string $base64
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playSingle($base64);

    /**
     * Download Folder
     *
     * @param array $params
     *
     * @return void
     * @throws \Exception
     */
    public function downloadFolder(array $params);

    /**
     * Download Single
     *
     * @param string $base64
     *
     * @return void
     * @throws \Exception
     */
    public function downloadSingle($base64);
}
