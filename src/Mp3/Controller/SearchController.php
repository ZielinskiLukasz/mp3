<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Controller;

use Zend\Console\Prompt\Confirm;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class SearchController
 *
 * @package Mp3\Controller
 *
 * @method Request getRequest()
 */
class SearchController extends AbstractActionController
{
    /**
     * Search
     *
     * @return ViewModel
     */
    public function searchAction()
    {
        /**
         * @var \Mp3\Form\Search $form
         */
        $form = $this->getServiceLocator()
                     ->get('FormElementManager')
                     ->get('Mp3\Form\Search');

        if ($this->getRequest()
                 ->isPost()
        ) {
            $form->setData(
                $this->params()
                     ->fromPost()
            );

            if ($form->isValid()) {
                return $this->redirect()
                            ->toRoute(
                                'mp3-search',
                                array(
                                    'name' => $this->params()
                                                   ->fromPost('name')
                                )
                            );
            }
        }

        $service = $this->getServiceLocator()
                        ->get('Mp3\Service\Search')
                        ->find(
                            $this->params()
                                 ->fromRoute('name')
                        );

        $viewModel = new ViewModel();
        $viewModel
            ->setTemplate('mp3/mp3/search')
            ->setVariables(
                array(
                    'form'         => $form,
                    'paginator'    => $service['paginator'],
                    'total_length' => $service['total_length'],
                    'total_size'   => $service['total_size'],
                    'search'       => $service['search'],
                    'flash'        => $this->params()
                                           ->fromRoute('flash')
                )
            );

        return $viewModel;
    }

    /**
     * Import Search Results
     *
     * @throws \RuntimeException
     */
    public function importAction()
    {
        if (!$this->getRequest() instanceof Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        /**
         * @var \Mp3\InputFilter\Search $filter
         */
        $filter = $this->getServiceLocator()
                       ->get('InputFilterManager')
                       ->get('Mp3\InputFilter\Search');

        $filter->setData(
            $this->params()
                 ->fromRoute()
        );

        if ($filter->isValid() && $filter->getValue('help') == null) {
            if ($filter->getValue('confirm') == 'yes') {
                $confirm = new Confirm(
                    'Are you sure you want to Import Search Results? [y/n]',
                    'y',
                    'n'
                );

                $result = $confirm->show();
            } else {
                $result = true;
            }

            if ($result) {
                $this->getServiceLocator()
                     ->get('Mp3\Service\Search')
                     ->import();
            }
        } else {
            if ($filter->getValue('help') != null) {
                $this->getEventManager()
                     ->trigger(
                         'Mp3Help',
                         null,
                         array('help' => 'import')
                     );

                exit;
            }
        }
    }
}
