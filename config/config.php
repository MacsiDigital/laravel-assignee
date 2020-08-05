<?php

return [

    'models' => [

        'role' => Assignee\Models\Role::class,

    ],

    'table_names' => [

        'roles' => 'roles',

        'model_has_roles' => 'model_has_roles',
    ],

    'column_names' => [

        'model_morph_key' => 'model_id',
    ]
];
