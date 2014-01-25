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
        $eventManager = $e->getApplication()
            ->getEventManager();

        /**
         * Disable Layout on Error
         */
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function ($e) {
            /**
             * @var MvcEvent $e
             */
            $e->getResult()
                ->setTerminal(true);
        });

        $sharedEvents = $eventManager
            ->getSharedManager();

        /**
         * Disable Layout in ViewModel
         */
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch',
            function ($e) {
                /**
                 * @var MvcEvent $e
                 */
                $result = $e->getResult();

                if ($result instanceof \Zend\View\Model\ViewModel) {
                    $result->setTerminal(true);
                }
            });

        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Mp3\Controller\SearchController', 'Mp3Help', function ($event) use ($e) {
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
