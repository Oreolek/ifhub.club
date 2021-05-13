<?php
/*-------------------------------------------------------
*
*   Plugin name:    Topic Similar
*   Author:         Chiffa
*   Web:            http://goweb.pro
*
---------------------------------------------------------
*/


/**
 * English language file for plugin
 */
return [
    'config' => [
        'position' => [
            'name' => 'Position',
            'description' => 'Before comments, After comments, Sidebar',
        ],
        'block' => [
            'row' => [
                'name' => 'Count topics per block',
                'description' => 'from 1 to 100',
            ],
            'priority' => [
                'name' => 'Block priority',
                'description' => '',
            ],
        ],
    ],
    'config_sections' => [
        'main' => [
            'name' => 'Main settings',
            'description' => '',
        ],
        'block' => [
            'name' => 'Block settings',
            'description' => '',
        ],
    ],
    'block' => [
        'title' => 'Similar topics',
    ]
];