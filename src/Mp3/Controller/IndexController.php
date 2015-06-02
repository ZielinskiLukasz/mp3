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

use Mp3\Form\Search as FormSearch;
use Mp3\Service\Index as ServiceIndex;
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
     * @var FormSearch $formSearch
     */
    private $formSearch;

    /**
     * @var ServiceIndex $serviceIndex
     */
    private $serviceIndex;

    /**
     * Construct
     *
     * @param FormSearch   $formSearch
     * @param ServiceIndex $serviceIndex
     */
    public function __construct(
        FormSearch $formSearch,
        ServiceIndex $serviceIndex
    ) {
        $this->formSearch = $formSearch;

        $this->serviceIndex = $serviceIndex;
    }

    /**
     * Index
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $form = $this->formSearch;

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
                                [
                                    'name' => $this->params()
                                                   ->fromPost('name')
                                ]
                            );
            }
        }

        $service = $this->serviceIndex
            ->index(
                $this->params()
                     ->fromRoute()
            );

        return (new ViewModel())
            ->setTemplate('mp3/mp3/search')
            ->setVariables(
                [
                    'form'          => $form,
                    'paginator'     => $service['paginator'],
                    'path'          => $service['path'],
                    'totalLength'   => $service['totalLength'],
                    'totalFileSize' => $service['totalFileSize'],
                    'search'        => $service['search'],
                    'dir'           => $this->params()
                                            ->fromRoute('dir')
                ]
            );
    }

    /**
     * Play All
     */
    public function playallAction()
    {
        $this->serviceIndex
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
        $this->serviceIndex
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
        $this->serviceIndex
            ->downloadFolder(
                [
                    'dir'    => $this->params()
                                     ->fromRoute('dir'),
                    'format' => $this->params()
                                     ->fromRoute('format')
                ]
            );
    }

    /**
     * Download Single
     */
    public function downloadsingleAction()
    {
        $this->serviceIndex
            ->downloadSingle(
                $this->params()
                     ->fromRoute('dir')
            );
    }
}
