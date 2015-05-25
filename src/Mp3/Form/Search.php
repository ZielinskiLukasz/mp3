<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
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

        $this->setAttribute(
            'method',
            'post'
        );

        /**
         * Name
         */
        $this->add(
            [
                'type'       => 'text',
                'name'       => 'name',
                'options'    => [
                    'label' => 'name',
                ],
                'attributes' => [
                    'id' => 'name'
                ]
            ]
        );

        /**
         * Submit
         */
        $this->add(
            [
                'type'       => 'submit',
                'name'       => 'submit',
                'attributes' => [
                    'id'    => 'submit',
                    'value' => 'Search'
                ]
            ]
        );
    }
}
