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
        $form = $this->getFormSearch();

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
                                'mp3-search', array(
                                    'name' => $this->params()
                                                   ->fromPost('name')
                                )
                            );
            }
        }

        $service = $this->getServiceIndex()
                        ->Index(
                            $this->params()
                                 ->fromRoute()
                        );

        $viewModel = new ViewModel();
        $viewModel
            ->setTemplate('mp3/mp3/search')
            ->setVariables(
                array(
                    'form'         => $form,
                    'paginator'    => $service['paginator'],
                    'path'         => $service['path'],
                    'total_length' => $service['total_length'],
                    'total_size'   => $service['total_size'],
                    'search'       => $service['search'],
                    'dir'          => $this->params()
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
        $this->getServiceIndex()
             ->PlayAll(
                 $this->params()
                      ->fromRoute('dir')
             );
    }

    /**
     * Play Single
     */
    public function playsingleAction()
    {
        $this->getServiceIndex()
             ->PlaySingle(
                 $this->params()
                      ->fromRoute('dir')
             );
    }

    /**
     * Download Folder
     */
    public function downloadfolderAction()
    {
        $this->getServiceIndex()
             ->DownloadFolder(
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
        $this->getServiceIndex()
             ->DownloadSingle(
                 $this->params()
                      ->fromRoute('dir')
             );
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
     * Service Index
     *
     * @return \Mp3\Service\Index
     */
    private function getServiceIndex()
    {
        return $this->getServiceLocator()
                    ->get('Mp3\Service\Index');
    }
}
