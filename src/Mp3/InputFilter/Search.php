<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2013, OTWebsoft Corporation
 * @license   http://otwebsoft.com/license
 * @link      http://otwebsoft.com OTWebsoft
 */

namespace Mp3\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class Search
 *
 * @package Mp3\InputFilter
 */
class Search extends InputFilter
{
    /**
     * Construct
     */
    public function __construct()
    {
        /**
         * Help
         */
        $this->add(array(
            'name'     => 'help',
            'required' => false,
            'filters'  => array(
                array(
                    'name' => 'stringtrim'
                ),
                array(
                    'name' => 'stringtolower'
                )
            )
        ));

        /**
         * Confirm
         */
        $this->add(array(
            'name'       => 'confirm',
            'required'   => false,
            'filters'    => array(
                array(
                    'name' => 'stringtrim'
                ),
                array(
                    'name' => 'stringtolower'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'alpha'
                ),
                array(
                    'name'    => 'regex',
                    'options' => array(
                        'pattern' => '/^(yes|no)$/'
                    )
                )
            )
        ));
    }
}
