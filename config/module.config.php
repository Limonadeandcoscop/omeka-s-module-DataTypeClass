<?php
return [
    'data_types' => [
        'abstract_factories' => ['DataTypeClass\Service\DataTypeClassFactory'],
    ],
    'controllers' => [
        'invokables' => [
            'DataTypeClass\Controller\Index' => 'DataTypeClass\Controller\IndexController',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            OMEKA_PATH . '/modules/DataTypeClass/view',
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'data-type-class' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/data-type-class/sidebar-select',
                            'defaults' => [
                                '__NAMESPACE__' => 'DataTypeClass\Controller',
                                'controller' => 'Index',
                                'action' => 'sidebar-select',
                            ],
                        ],
                        'may_terminate' => true,

                    ],
                ],
            ],
        ],
    ],
];
