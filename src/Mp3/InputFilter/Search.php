<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
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
        $this->add(
            [
                'name'     => 'help',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'stringtrim'
                    ],
                    [
                        'name' => 'stringtolower'
                    ]
                ]
            ]
        );

        /**
         * Confirm
         */
        $this->add(
            [
                'name'       => 'confirm',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'stringtrim'
                    ],
                    [
                        'name' => 'stringtolower'
                    ]
                ],
                'validators' => [
                    [
                        'name' => 'alpha'
                    ],
                    [
                        'name'    => 'regex',
                        'options' => [
                            'pattern' => '/^(yes|no)$/'
                        ]
                    ]
                ]
            ]
        );
    }
}
