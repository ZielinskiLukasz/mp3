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

use Mp3\Service\Search;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SearchFactory
 *
 * @package Mp3\Service\Factory
 */
class SearchFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Search
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

        return new Search(
            $config,
            $serverUrl,
            $translator
        );
    }
}
