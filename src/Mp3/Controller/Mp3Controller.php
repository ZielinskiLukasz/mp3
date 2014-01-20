<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, OTWebsoft Corporation
 * @license   http://otwebsoft.com/license
 * @link      http://otwebsoft.com OTWebsoft
 */

namespace Mp3\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class Mp3Controller
 *
 * @package Mp3\Controller
 */
class Mp3Controller extends AbstractActionController
{
    /**
     * Search
     *
     * @return ViewModel
     */
    public function searchAction()
    {
        $service = $this->getServiceSearch()->Search($this->params()->fromRoute());

        $viewModel = new ViewModel();
        $viewModel
            ->setTemplate('mp3/mp3/search')
            ->setVariables(array(
                'paginator'    => $service['paginator'],
                'path'         => $service['path'],
                'total_length' => $service['total_length'],
                'total_size'   => $service['total_size'],
                'dir'          => $this->params()->fromRoute('dir')
            ))
            ->setTerminal(true);

        return $viewModel;
    }

    /**
     * Play All
     */
    public function playallAction()
    {
        echo $this->getServiceSearch()
            ->PlayAll($this->params()->fromRoute('dir'));

        exit;
    }

    /**
     * Play Single
     */
    public function playsingleAction()
    {
        echo $this->getServiceSearch()
            ->PlaySingle($this->params()->fromRoute('dir'));

        exit;
    }

    /**
     * Download Single
     *
     * @return null|string|void
     */
    public function downloadsingleAction()
    {
        return $this->getServiceSearch()
            ->DownloadSingle($this->params()->fromRoute('dir'));
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
