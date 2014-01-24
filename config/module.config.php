<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

return array(
    'router'          => array(
        'routes' => array(
            'mp3-index'           => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mp3/index[/:dir]',
                    'defaults' => array(
                        'controller' => 'Mp3\Controller\Mp3',
                        'action'     => 'index'
                    )
                )
            ),
            'mp3-search'          => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mp3/search[/name/:name]',
                    'defaults' => array(
                        'controller' => 'Mp3\Controller\Mp3',
                        'action'     => 'search'
                    )
                )
            ),
            'mp3-play-all'        => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mp3/play/all[/:dir]',
                    'defaults' => array(
                        'controller' => 'Mp3\Controller\Mp3',
                        'action'     => 'playall'
                    )
                )
            ),
            'mp3-play-single'     => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mp3/play/single[/:dir]',
                    'defaults' => array(
                        'controller' => 'Mp3\Controller\Mp3',
                        'action'     => 'playsingle'
                    )
                )
            ),
            'mp3-download-single' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mp3/download/single[/:dir]',
                    'defaults' => array(
                        'controller' => 'Mp3\Controller\Mp3',
                        'action'     => 'downloadsingle'
                    )
                )
            )
        )
    ),
    'console'         => array(
        'router' => array(
            'routes' => array(
                'mp3-import' => array(
                    'options' => array(
                        'route'    => 'mp3 import [--help] [--confirm=]',
                        'defaults' => array(
                            'controller' => 'Mp3\Controller\Mp3',
                            'action'     => 'import',
                            'confirm'    => 'yes'
                        )
                    )
                )
            )
        )
    ),
    'translator'      => array(
        'translation_file_patterns' => array(
            array(
                'type'        => 'gettext',
                'base_dir'    => __DIR__ . '/../language',
                'pattern'     => '%s.mo',
                'text_domain' => 'mp3'
            )
        )
    ),
    'controllers'     => array(
        'invokables' => array(
            'Mp3\Controller\Mp3' => 'Mp3\Controller\Mp3Controller'
        )
    ),
    'view_manager'    => array(
        'template_path_stack' => array(
            'mp3' => __DIR__ . '/../view'
        )
    ),
    'view_helpers'    => array(
        'invokables' => array(
            'convert'  => 'Mp3\View\Helper\Convert',
            'navigate' => 'Mp3\View\Helper\Navigate'
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'Mp3\Service\Calculate' => 'Mp3\Service\Calculate',
            'Mp3\Service\Search'    => 'Mp3\Service\Search'
        )
    ),
    'form_elements'   => array(
        'invokables' => array(
            'Mp3\Form\Search' => 'Mp3\Form\Search'
        )
    ),
    'input_filters'   => array(
        'invokables' => array(
            'Mp3\InputFilter\Search' => 'Mp3\InputFilter\Search'
        )
    )
);
