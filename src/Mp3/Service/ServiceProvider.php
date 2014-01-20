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
