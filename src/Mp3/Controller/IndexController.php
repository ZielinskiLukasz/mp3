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

use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 *
 * @package Mp3\Controller
 *
 * @method Request getRequest()
 */
class IndexController extends AbstractActionController
{
    /**
     * Index
     *
     * @return ViewModel
     */
    public function indexAction()
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
                        ->get('Mp3\Service\Index')
                        ->index(
                            $this->params()
                                 ->fromRoute()
                        );

        $viewModel = new ViewModel();
        $viewModel
            ->setTemplate('mp3/mp3/search')
            ->setVariables(
                array(
                    'form'        => $form,
                    'paginator'   => $service['paginator'],
                    'path'        => $service['path'],
                    'totalLength' => $service['total_length'],
                    'totalSize'   => $service['total_size'],
                    'search'      => $service['search'],
                    'dir'         => $this->params()
                                          ->fromRoute('dir')
                )
            );

        return $viewModel;
    }

    /**
     * Play All
     */
    public function playallAction()
    {
        $this->getServiceLocator()
             ->get('Mp3\Service\Index')
             ->playAll(
                 $this->params()
                      ->fromRoute('dir')
             );
    }

    /**
     * Play Single
     */
    public function playsingleAction()
    {
        $this->getServiceLocator()
             ->get('Mp3\Service\Index')
             ->playSingle(
                 $this->params()
                      ->fromRoute('dir')
             );
    }

    /**
     * Download Folder
     */
    public function downloadfolderAction()
    {
        $this->getServiceLocator()
             ->get('Mp3\Service\Index')
             ->downloadFolder(
                 array(
                     'dir'    => $this->params()
                                      ->fromRoute('dir'),
                     'format' => $this->params()
                                      ->fromRoute('format')
                 )
             );
    }

    /**
     * Download Single
     */
    public function downloadsingleAction()
    {
        $this->getServiceLocator()
             ->get('Mp3\Service\Index')
             ->downloadSingle(
                 $this->params()
                      ->fromRoute('dir')
             );
    }
}
