<?php

return array(
    'default' => array(
        // Разрешённые теги
        'cfgAllowTags'          => array(
            // вызов метода с параметрами
            array(
                array(
                  'ls',
                  'gallery',
                  'spoiler',
                  'abbr',
                  'audio',
                  'source',
                  'aside',
                  'incut'
                ),
            ),
        ),
        // Короктие теги типа
        'cfgSetTagShort'        => array(
            array(
                array(
                  'ls',
                  'gallery',
                  'source'
                )
            ),
        ),
        // Разрешённые параметры тегов
        'cfgAllowTagParams'     => array(
            [
                'iframe',
                [
                    'width'  => '#int',
                    'height' => '#int',
                    'src'    => [
                        '#domain' => [
                            'vk.com',
                            'youtube.com',
                            'rutube.ru',
                            'vimeo.com',
                            'video.yandex.ru',
                            'b.gamejolt.net',
                            'philome.la',
                            'oreolek.ru',
                            'instead-hub.github.io',
                            'cdn.rawgit.com',
                            'itch.io',
                        ]
                    ],
                    'msallowfullscreen' => ['true', 'false'],
                    'mozallowfullscreen' => ['true', 'false'],
                    'allowtransparency' => ['true', 'false'],
                    'allowfullscreen' => ['true', 'false'],
                    'webkitallowfullscreen' => ['true', 'false']
                ]
            ],
            array(
                'abbr',
                array('title' => '#text')
            ),
            array(
                'audio',
                array('controls' => '#text', 'src' => '#text')
            ),
            array(
                'source',
                array('src' => '#text', 'type' => ['audio/ogg', 'audio/mpeg'])
            ),
            array(
                'ls',
                array('user' => '#text')
            ),
            array(
                'gallery',
                array('items' => '#text', 'nav' => array('thumbs'), 'caption' => array('0', '1'))
            ),
            array(
                'a',
                array('data-rel' => '#text', 'class' => array('js-lbx'))
            ),
            array(
                'spoiler',
                array('title' => '#text')
            ),
        ),
        'cfgSetTagCallbackFull' => array(
            array(
                'ls',
                array('_this_', 'Tools_CallbackParserTagLs'),
            ),
            array(
                'gallery',
                array('_this_', 'Media_CallbackParserTagGallery'),
            ),
            array(
                'spoiler',
                array('_this_', 'Ifhub_CallbackParserTagSpoiler'),
            ),
            array(
                'aside',
                array('_this_', 'Ifhub_CallbackParserTagAside'),
            ),
            array(
                'incut',
                array('_this_', 'Ifhub_CallbackParserTagIncut'),
            ),
        )
    ),
);
