<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Form;

use Zend\Form\Form;

/**
 * Class Search
 *
 * @package Mp3\Form
 */
class Search extends Form
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * Form Attributes
         */
        $this->setName('search');
        $this->setAttributes(array(
            'action' => '/mp3/search',
            'method' => 'post'
        ));

        /**
         * Name
         */
        $this->add(array(
            'type'       => 'text',
            'name'       => 'name',
            'options'    => array(
                'label' => 'name',
            ),
            'attributes' => array(
                'id' => 'name'
            )
        ));

        /**
         * Submit
         */
        $this->add(array(
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => array(
                'id'    => 'submit',
                'value' => 'Search'
            )
        ));
    }
}
