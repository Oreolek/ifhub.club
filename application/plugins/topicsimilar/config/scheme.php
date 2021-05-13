<?php
/*-------------------------------------------------------
*
*   Plugin name:    Topic Similar
*   Author:         Chiffa
*   Web:            http://goweb.pro
*
---------------------------------------------------------
*/


return [
    /*
     * Описание настроек плагина для интерфейса редактирования
     */
    '$config_scheme$' => [
        'position' => [
            /*
             * тип: integer, string, array, boolean, float
             */
            'type'        => 'string',
            /*
             * отображаемое имя параметра, ключ языкового файла
             */
            'name'        => 'config.position.name',
            /*
             * отображаемое описание параметра, ключ языкового файла
             */
            'description' => 'config.position.description',
            /*
             * валидатор (не обязательно)
             */
            'validator'   => [
                /*
                 * тип валидатора, существующие типы валидаторов движка:
                 * Boolean, Compare, Date, Email, Number, Regexp, Required, String, Tags, Type, Url, дополнительные: Array и Enum (специальные валидаторы, см. документацию)
                 */
                'type'   => 'Enum',
                /*
                 * параметры, которые будут переданы в валидатор
                 */
                'params' => [
                    'enum'       => [
                        'comments_before',
                        'comments_after',
                        'right',
                    ],
                    'allowEmpty' => false,
                ],
            ]
        ],
        'block.row' => [
            'type'        => 'integer',
            'name'        => 'config.block.row.name',
            'description' => 'config.block.row.description',
            'validator'   => array(
                'type'   => 'Number',
                'params' => array(
                    'min'         => 1,
                    'max'         => 100,
                    'integerOnly' => true,
                    'allowEmpty'  => false,
                ),
            ),
        ],
        'block.priority' => [
            'type'        => 'integer',
            'name'        => 'config.block.priority.name',
            'description' => 'config.block.priority.description',
            'validator'   => array(
                'type'   => 'Number',
                'params' => array(
                    'integerOnly' => true,
                    'allowEmpty'  => false,
                ),
            ),
        ],
    ],
    /**
     * Описание разделов для настроек
     * Каждый раздел группирует определенные параметры конфига
     */
    '$config_sections$' => [
        /**
         * Основные настройки
         */
        [
            /**
             * Название раздела
             */
            'name'         => 'config_sections.main.name',
            'description'  => 'config_sections.main.description',
            /**
             * Список параметров для отображения в разделе
             */
            'allowed_keys' => [
                'position'
            ],
        ],
        /**
         * Настройки Блока
         */
        [
            'name'         => 'config_sections.block.name',
            'description'  => 'config_sections.block.description',
            'allowed_keys' => [
                'block.row', 'block.priority',
            ],
        ],
    ]
];
