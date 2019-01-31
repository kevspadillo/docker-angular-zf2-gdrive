<?php

return [
    'router' => [
        'routes' => [
            'Index' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Upload\Controller\Index',
                    ],
                ],
            ],
            'upload' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/api/upload[/:id]',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Upload\Controller\Upload',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Upload\Controller\Upload' => 'Upload\Controller\UploadController',
            'Upload\Controller\Index'  => 'Upload\Controller\IndexController',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];