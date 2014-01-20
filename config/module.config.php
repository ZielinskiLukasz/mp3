<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, OTWebsoft Corporation
 * @license   http://otwebsoft.com/license
 * @link      http://otwebsoft.com OTWebsoft
 */

return array(
    'router'          => array(
        'routes' => array(
            'mp3-search'          => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mp3/search[/:dir]',
                    'defaults' => array(
                        'controller' => 'Mp3\Controller\Mp3',
                        'action'     => 'search'
                    )
                ),

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
    'controllers'     => array(
        'invokables' => array(
            'Mp3\Controller\Mp3' => 'Mp3\Controller\Mp3Controller'
        )
    ),
    'view_manager'    => array(
        'template_map' => include_once __DIR__ . '/template_map.php'
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
    )
);
