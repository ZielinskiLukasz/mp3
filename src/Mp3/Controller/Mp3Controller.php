<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Controller;

use Zend\Console\Prompt\Confirm;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class Mp3Controller
 *
 * @package Mp3\Controller
 *
 * @method \Zend\Http\Request getRequest()
 */
class Mp3Controller extends AbstractActionController
{
    /**
     * Index
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $form = $this->getFormSearch();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                return $this->redirect()->toRoute('mp3-search', array(
                    'name' => $this->getRequest()->getPost('name')
                ));
            }
        }

        $service = $this->getServiceSearch()
            ->Index($this->params()->fromRoute());

        $viewModel = new ViewModel();
        $viewModel
            ->setTerminal(true)
            ->setTemplate('mp3/mp3/search')
            ->setVariables(array(
                'form'         => $form,
                'paginator'    => $service['paginator'],
                'path'         => $service['path'],
                'total_length' => $service['total_length'],
                'total_size'   => $service['total_size'],
                'dir'          => $this->params()->fromRoute('dir')
            ));

        return $viewModel;
    }

    /**
     * Search
     *
     * @return ViewModel
     */
    public function searchAction()
    {
        $form = $this->getFormSearch();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                return $this->redirect()->toRoute('mp3-search', array(
                    'name' => $this->getRequest()->getPost('name')
                ));
            }
        }

        $service = $this->getServiceSearch()
            ->Search($this->params()->fromRoute('name'));

        $viewModel = new ViewModel();
        $viewModel
            ->setTerminal(true)
            ->setTemplate('mp3/mp3/search')
            ->setVariables(array(
                'form'         => $form,
                'paginator'    => $service['paginator'],
                'path'         => $service['path'],
                'total_length' => $service['total_length'],
                'total_size'   => $service['total_size'],
                'dir'          => null
            ));

        return $viewModel;
    }

    /**
     * Play All
     */
    public function playallAction()
    {
        $this->getServiceSearch()
            ->PlayAll($this->params()->fromRoute('dir'));
    }

    /**
     * Play Single
     */
    public function playsingleAction()
    {
        $this->getServiceSearch()
            ->PlaySingle($this->params()->fromRoute('dir'));
    }

    /**
     * Download Single
     */
    public function downloadsingleAction()
    {
        $this->getServiceSearch()
            ->DownloadSingle($this->params()->fromRoute('dir'));
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

        $filter = $this->getFilterSearch();
        $filter->setData($this->params()->fromRoute());

        if ($filter->isValid()) {
            if ($filter->getValue('confirm') == 'yes') {
                $confirm = new Confirm('Are you sure you want to Import Search Results? [y/n]', 'y', 'n');

                $result = $confirm->show();
            } else {
                $result = true;
            }

            if ($result) {
                $this->getServiceSearch()
                    ->Import();
            }
        } else {
            if ($filter->getValue('help') != null) {
                $this->getEventManager()
                    ->trigger('Mp3Help', null, array('help' => 'import'));

                exit;
            }
        }
    }

    /**
     * Form Search
     *
     * @return \Mp3\Form\Search
     */
    private function getFormSearch()
    {
        return $this->getServiceLocator()
            ->get('FormElementManager')
            ->get('Mp3\Form\Search');
    }

    /**
     * Filter Search
     *
     * @return \Mp3\InputFilter\Search
     */
    private function getFilterSearch()
    {
        return $this->getServiceLocator()
            ->get('InputFilterManager')
            ->get('Mp3\InputFilter\Search');
    }

    /**
     * Service Search
     *
     * @return \Mp3\Service\Search
     */
    private function getServiceSearch()
    {
        return $this->getServiceLocator()
            ->get('Mp3\Service\Search');
    }
}
