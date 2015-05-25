<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Controller\Factory;

use Mp3\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IndexControllerFactory
 *
 * @package Mp3\Controller\Factory
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\ServiceManager\ServiceLocatorAwareTrait $serviceLocator
         */
        $sl = $serviceLocator->getServiceLocator();

        /**
         * @var \Mp3\Form\Search $formSearch
         */
        $formSearch = $sl->get('FormElementManager')
                         ->get('Mp3\Form\Search');

        /**
         * @var \Mp3\Service\Index $serviceIndex
         */
        $serviceIndex = $sl->get('Mp3\Service\Index');

        return new IndexController(
            $formSearch,
            $serviceIndex
        );
    }
}
