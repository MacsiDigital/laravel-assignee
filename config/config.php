<?php

return [

    'models' => [

        'role' => Roles\Models\Role::class,

    ],

    'table_names' => [

        'roles' => 'roles',

        'model_has_roles' => 'model_has_roles',
    ],

    'column_names' => [

        'model_morph_key' => 'model_id',
    ],

    'middleware_key' => 'role',
];
