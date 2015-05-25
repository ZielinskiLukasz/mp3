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

use Mp3\Controller\SearchController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SearchControllerFactory
 *
 * @package Mp3\Controller\Factory
 */
class SearchControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SearchController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\ServiceManager\ServiceLocatorAwareTrait $serviceLocator
         */
        $sl = $serviceLocator->getServiceLocator();

        /**
         * @var \Mp3\InputFilter\Search $inputFilterSearch
         */
        $inputFilterSearch = $sl->get('InputFilterManager')
                                ->get('Mp3\InputFilter\Search');

        /**
         * @var \Mp3\Form\Search $formSearch
         */
        $formSearch = $sl->get('FormElementManager')
                         ->get('Mp3\Form\Search');

        /**
         * @var \Mp3\Service\Search $serviceSearch
         */
        $serviceSearch = $sl->get('Mp3\Service\Search');

        return new SearchController(
            $inputFilterSearch,
            $formSearch,
            $serviceSearch
        );
    }
}
