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
}
