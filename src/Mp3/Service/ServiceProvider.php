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

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class ServiceProvider
 *
 * @package Mp3\Service
 */
abstract class ServiceProvider implements ServiceManagerAwareInterface
{
    /**
     * Protected Variable
     *
     * @var ServiceManager $serviceManager
     */
    protected $serviceManager;

    /**
     * Get ServiceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set ServiceManager
     *
     * @param ServiceManager $serviceManager
     *
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * Get Base Path
     *
     * @return string
     */
    public function getBasePath()
    {
        if (php_sapi_name() == 'cli') {
            return $this->getConfig()['searchPath'];
        } else {
            return $_SERVER['DOCUMENT_ROOT'] . $this->getConfig()['baseDir'];
        }
    }

    /**
     * Get Config
     *
     * @return array
     * @throws \Exception
     */
    public function getConfig()
    {
        $config = $this->getServiceManager()
                       ->get('config');

        if (!isset($config['mp3']['baseDir'])) {
            throw new \Exception(
                $this->getTranslator()
                     ->translate(
                         'baseDir is not currently set',
                         'mp3'
                     )
            );
        }

        if (!isset($config['mp3']['format'])) {
            throw new \Exception(
                $this->getTranslator()
                     ->translate(
                         'format is not currently set',
                         'mp3'
                     )
            );
        }

        if (!isset($config['mp3']['searchFile'])) {
            throw new \Exception(
                $this->getTranslator()
                     ->translate(
                         'searchFile is not currently set',
                         'mp3'
                     )
            );
        }

        if (!isset($config['mp3']['searchPath'])) {
            throw new \Exception(
                $this->getTranslator()
                     ->translate(
                         'searchPath is not currently set',
                         'mp3'
                     )
            );
        }

        return array(
            'baseDir'    => $config['mp3']['baseDir'],
            'format'     => $config['mp3']['format'],
            'searchFile' => $config['mp3']['searchFile'],
            'searchPath' => $config['mp3']['searchPath']
        );
    }

    /**
     * Get Translator
     *
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator()
    {
        return $this->getServiceManager()
                    ->get('MvcTranslator');
    }
}
