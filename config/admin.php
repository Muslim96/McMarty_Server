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
    ],

    // slug роли => список прав ('*' = всё)
    'roles' => [
        'super_admin' => ['*'],
        'admin' => [
            'restaurants.manage', 'employers.view', 'employers.manage',
            'fines.view', 'fines.create', 'fines.pay', 'fines.manage',
            'fine-reasons.manage', 'users.manage',
        ],
        // видит и управляет только своим рестораном (users.restaurant_id)
        'manager' => ['employers.view', 'employers.manage', 'fines.view', 'fines.create'],
        // read-only по сотрудникам, фиксирует оплату штрафов
        'accountant' => ['employers.view', 'fines.view', 'fines.pay'],
    ],
];
