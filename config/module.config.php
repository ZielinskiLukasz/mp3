<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

return [
    'router'          => [
        'routes' => [
            'mp3-index'           => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/mp3/index[:dir]',
                    'defaults' => [
                        'controller' => 'Mp3\Controller\Index',
                        'action'     => 'index'
                    ]
                ]
            ],
            'mp3-play-all'        => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/mp3/play/all[:dir]',
                    'defaults' => [
                        'controller' => 'Mp3\Controller\Index',
                        'action'     => 'playall'
                    ]
                ]
            ],
            'mp3-play-single'     => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/mp3/play/single[:dir]',
                    'defaults' => [
                        'controller' => 'Mp3\Controller\Index',
                        'action'     => 'playsingle'
                    ]
                ]
            ],
            'mp3-download-folder' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/mp3/download/folder[:dir][/format/:format]',
                    'defaults' => [
                        'controller' => 'Mp3\Controller\Index',
                        'action'     => 'downloadfolder'
                    ]
                ]
            ],
            'mp3-download-single' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/mp3/download/single[:dir]',
                    'defaults' => [
                        'controller' => 'Mp3\Controller\Index',
                        'action'     => 'downloadsingle'
                    ]
                ]
            ],
            'mp3-search'          => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/mp3/search[/name/][:name][/flash/:flash]',
                    'defaults' => [
                        'controller' => 'Mp3\Controller\Search',
                        'action'     => 'search',
                        'name'       => null
                    ]
                ]
            ]
        ]
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'mp3-import' => [
                    'options' => [
                        'route'    => 'mp3 import [--help] [--confirm=]',
                        'defaults' => [
                            'controller' => 'Mp3\Controller\Search',
                            'action'     => 'import',
                            'confirm'    => 'yes'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'translator'      => [
        'locale'                    => 'en_US',
        'translation_file_patterns' => [
            [
                'type'        => 'gettext',
                'base_dir'    => dirname(__DIR__) . '/language',
                'pattern'     => '%s.mo',
                'text_domain' => 'mp3'
            ]
        ]
    ],
    'controllers'     => [
        'invokables' => [
            'Mp3\Controller\Index'  => 'Mp3\Controller\IndexController',
            'Mp3\Controller\Search' => 'Mp3\Controller\SearchController'
        ]
    ],
    'view_manager'    => [
        'template_map'        => include_once dirname(__DIR__) . '/template_map.php',
        'template_path_stack' => [
            'mp3' => dirname(__DIR__) . '/view'
        ]
    ],
    'view_helpers'    => [
        'invokables' => [
            'convert'  => 'Mp3\View\Helper\Convert',
            'navigate' => 'Mp3\View\Helper\Navigate'
        ]
    ],
    'service_manager' => [
        'invokables' => [
            'Mp3\Service\Calculate' => 'Mp3\Service\Calculate',
            'Mp3\Service\Index'     => 'Mp3\Service\Index',
            'Mp3\Service\Search'    => 'Mp3\Service\Search'
        ]
    ],
    'form_elements'   => [
        'invokables' => [
            'Mp3\Form\Search' => 'Mp3\Form\Search'
        ]
    ],
    'input_filters'   => [
        'invokables' => [
            'Mp3\InputFilter\Search' => 'Mp3\InputFilter\Search'
        ]
    ]
];
