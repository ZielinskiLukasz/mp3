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
use Mp3\InputFilter\Search as InputFilterSearch;
use Mp3\Service\Search as ServiceSearch;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class SearchController
 *
 * @package Mp3\Controller
 *
 * @method ConsoleRequest|HttpRequest getRequest()
 */
class SearchController extends AbstractActionController
{
    /**
     * @var InputFilterSearch $inputFilterSearch
     */
    private $inputFilterSearch;

    /**
     * @var FormSearch $formSearch
     */
    private $formSearch;

    /**
     * @var ServiceSearch $serviceSearch
     */
    private $serviceSearch;

    /**
     * Construct
     *
     * @param InputFilterSearch $inputFilterSearch
     * @param FormSearch        $formSearch
     * @param ServiceSearch     $serviceSearch
     */
    public function __construct(
        InputFilterSearch $inputFilterSearch,
        FormSearch $formSearch,
        ServiceSearch $serviceSearch
    ) {
        $this->inputFilterSearch = $inputFilterSearch;

        $this->formSearch = $formSearch;

        $this->serviceSearch = $serviceSearch;
    }

    /**
     * Search
     *
     * @return ViewModel
     */
    public function searchAction()
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

        $service = $this->serviceSearch
            ->find(
                $this->params()
                     ->fromRoute('name')
            );

        return (new ViewModel())
            ->setTemplate('mp3/mp3/search')
            ->setVariables(
                [
                    'form'          => $form,
                    'paginator'     => $service['paginator'],
                    'totalLength'   => $service['totalLength'],
                    'totalFileSize' => $service['totalFileSize'],
                    'search'        => $service['search'],
                    'flash'         => $this->params()
                                            ->fromRoute('flash')
                ]
            );
    }

    /**
     * Import Search Results
     *
     * @throws \RuntimeException
     */
    public function importAction()
    {
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $filter = $this->inputFilterSearch;

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
                $this->serviceSearch->import();
            }
        } else {
            if ($filter->getValue('help') != null) {
                $this->getEventManager()
                     ->trigger(
                         'Mp3Help',
                         null,
                         ['help' => 'import']
                     );

                exit;
            }
        }
    }
}
