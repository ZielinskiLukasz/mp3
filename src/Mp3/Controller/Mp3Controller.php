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
        $service = $this->getServiceSearch()
            ->Search($this->params()->fromRoute());

        $viewModel = new ViewModel();
        $viewModel
            ->setTerminal(true)
            ->setTemplate('mp3/mp3/search')
            ->setVariables(array(
                'paginator'    => $service['paginator'],
                'path'         => $service['path'],
                'total_length' => $service['total_length'],
                'total_size'   => $service['total_size'],
                'dir'          => $this->params()->fromRoute('dir')
            ));

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
