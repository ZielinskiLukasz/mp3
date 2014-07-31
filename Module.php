<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class Module
 *
 * @package Mp3
 */
class Module implements
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface
{
    /**
     * Boostrap
     *
     * @param MvcEvent $mvcEvent
     */
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $eventManager = $mvcEvent->getApplication()
                                 ->getEventManager();

        /**
         * Disable Layout on Error
         */
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            function ($mvcEvent) {
                /**
                 * @var MvcEvent $mvcEvent
                 */
                $mvcEvent->getResult()
                         ->setTerminal(true);
            }
        );

        $sharedEvents = $eventManager
            ->getSharedManager();

        /**
         * Disable Layout in ViewModel
         */
        $sharedEvents->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            'dispatch',
            function ($mvcEvent) {
                /**
                 * @var MvcEvent $mvcEvent
                 */
                $result = $mvcEvent->getResult();

                if ($result instanceof ViewModel) {
                    $result->setTerminal(true);
                }
            }
        );

        $mvcEvent->getApplication()
                 ->getEventManager()
                 ->getSharedManager()
                 ->attach(
                     'Mp3\Controller\SearchController',
                     'Mp3Help',
                     function ($event) use
                     (
                         $mvcEvent
                     ) {
                         /**
                          * @var MvcEvent $event
                          */
                         echo $mvcEvent->getApplication()
                                       ->getServiceManager()
                                       ->get('Mp3\Service\Search')
                                       ->Help($event->getParam('help'));
                     }
                 );
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
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
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
