<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Service\Factory;

use Mp3\Service\Index;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IndexFactory
 *
 * @package Mp3\Service\Factory
 */
class IndexFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Index
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var array $config
         */
        $config = $serviceLocator->get('config');

        /**
         * @var \Zend\View\HelperPluginManager $serverUrl
         */
        $serverUrl = $serviceLocator->get('ViewHelperManager');

        /**
         * @var \Zend\Mvc\I18n\Translator $translator
         */
        $translator = $serviceLocator->get('translator');

        return new Index(
            $config,
            $serverUrl,
            $translator
        );
    }
}
