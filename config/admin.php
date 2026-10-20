<?php

return [
    'currency' => env('APP_CURRENCY', '₽'),

    'abilities' => [
        'restaurants.manage',
        'employers.view',
        'employers.manage',
        'fines.view',
        'fines.create',
        'fines.pay',
        'fines.manage',
        'fine-reasons.manage',
        'users.manage',
        'cities.manage'
    ],

    'roles' => [
        'super_admin' => ['*'],
        'admin' => [
            'restaurants.manage', 'employers.view', 'employers.manage',
            'fines.view', 'fines.create', 'fines.pay', 'fines.manage',
            'fine-reasons.manage', 'users.manage', 'cities.manage',
        ],
        'manager' => ['employers.view', 'employers.manage', 'fines.view', 'fines.create'],
        'accountant' => ['employers.view', 'fines.view', 'fines.pay'],
    ],
];
