<?php

return [
    'router' => [
        'routes' => [
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
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];