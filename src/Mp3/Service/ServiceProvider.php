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

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class ServiceProvider
 *
 * @package Mp3\Service
 */
class ServiceProvider implements ServiceManagerAwareInterface
{
    /**
     * Protected Variable
     *
     * @var ServiceManager $ServiceManager
     */
    protected $ServiceManager;

    /**
     * Get ServiceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->ServiceManager;
    }

    /**
     * Set ServiceManager
     *
     * @param ServiceManager $ServiceManager
     *
     * @return $this
     */
    public function setServiceManager(ServiceManager $ServiceManager)
    {
        $this->ServiceManager = $ServiceManager;

        return $this;
    }

    /**
     * Get Base Path
     *
     * @return string
     */
    public function getBasePath()
    {
        $config = $this->getServiceManager()
            ->get('config');

        if (php_sapi_name() == 'cli') {
            return $this->getConfig()['search_path'];
        } else {
            return $_SERVER['DOCUMENT_ROOT'] . $config['mp3']['base_dir'];
        }
    }

    /**
     * Get Config
     *
     * @return array
     */
    public function getConfig()
    {
        $config = $this->getServiceManager()
            ->get('config');

        return array(
            'base_dir'    => $config['mp3']['base_dir'],
            'format'      => $config['mp3']['format'],
            'search_file' => $config['mp3']['search_file'],
            'search_path' => $config['mp3']['search_path']
        );
    }
}
