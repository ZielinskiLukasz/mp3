<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 *
 * @package Mp3
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface, ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{
    /**
     * Boostrap
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Mp3\Controller\Mp3Controller', 'Mp3', function ($event) use ($e) {
                /**
                 * @var MvcEvent $event
                 */
                echo $e->getApplication()
                    ->getServiceManager()
                    ->get('Mp3\Service\Search')
                    ->Help($event->getParam('help'));
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include_once __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'MP3 Player Console';
    }

    /**
     * {@inheritdoc}
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            array(
                'Import Search',
                'mp3 import',
                '--help'
            )
        );
    }
}
