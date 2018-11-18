<?php
/**
 * Created by PhpStorm.
 * User: David Leonardo V
 * Date: 13/11/2018
 * Time: 10:25 PM
 */
//composer require dasprid/container-interop-doctrine

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' => 'mysql://gps_web:GpsWeb2010.@107.170.208.14/gpsweb',
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'App\Entity' => 'my_entity',
                ],
            ],
            'my_entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../../src/App/Entity',
            ],
        ],
    ],
];