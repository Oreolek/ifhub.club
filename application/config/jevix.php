<?php

return array(
	'default' => array(
		// Разрешённые теги
		'cfgAllowTags'		  => array(
			// вызов метода с параметрами
			array(
				array(
					'p',
					'ls',
					'cut',
					'a',
					'img',
					'i',
					'b',
					'u',
					's',
					'small',
					'video',
					'em',
					'strong',
					'nobr',
					'li',
					'ol',
					'ul',
					'sup',
					'abbr',
					'sub',
					'acronym',
					'h4',
					'h5',
					'h6',
					'br',
					'hr',
					'pre',
					'code',
					'codeline',
					'object',
					'param',
					'embed',
					'blockquote',
					'iframe',
					'table',
					'tbody',
					'thead',
					'th',
					'tr',
					'td',
					'gallery',
					'spoiler',
					'abbr',
					'audio',
					'source',
					'aside',
					'incut',
				),
			),
		),
		// Короктие теги типа
		'cfgSetTagShort'		=> array(
			array(
				array(
				  'br',
				  'img',
				  'hr',
				  'cut',
				  'ls',
				  'gallery',
				  'source'
				)
			),
		),
		// Преформатированные теги
		'cfgSetTagPreformatted'	 => array(
			array(
				array('pre', 'code', 'codeline', 'video')
			),
		),
		// Разрешённые параметры тегов
		'cfgAllowTagParams'	 => array(
		  // вызов метода
		  array(
			'img',
			array(
			  'src',
			  'alt'	=> '#text',
			  'title',
			  'align'  => array('right', 'left', 'center', 'middle'),
			  'width'  => '#int',
			  'height' => '#int',
			  'hspace' => '#int',
			  'vspace' => '#int',
			  'class'  => array('image-center')
			)
		  ),
		  [
				'iframe',
				[
					'width'  => '#int',
					'height' => '#int',
					'src'	=> [
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
							'instead-games.ru',
							'cdn.rawgit.com',
							'itch.io',
							'html-classic.itch.zone',
							'html.itch.zone',
							'itch.zone',
							'ifhub.club',
						]
					  ],
					'frameborder' => '#int',
					'msallowfullscreen' => ['true', 'false'],
					'mozallowfullscreen' => ['true', 'false'],
					'allowtransparency' => ['true', 'false'],
					'allowfullscreen' => ['true', 'false'],
					'webkitallowfullscreen' => ['true', 'false']
				]
			],
			[
				'cut',
				array('name')
			],
			array(
				'audio',
				array('controls' => '#text', 'src' => '#text')
			),
			array(
				'source',
				array('src' => '#text', 'type' => ['audio/ogg', 'audio/mpeg'])
			),
			[
				'object',
				array(
					'width'  => '#int',
					'height' => '#int',
					'data'   => array('#domain' => array('youtube.com', 'rutube.ru', 'vimeo.com')),
					'type'   => '#text'
				)
			],
			array(
				'param',
				array('name' => '#text', 'value' => '#text')
			),
			array(
				'embed',
				array(
					'src'			   => array('#domain' => array('youtube.com', 'rutube.ru', 'vimeo.com')),
					'type'			  => '#text',
					'allowscriptaccess' => '#text',
					'allowfullscreen'   => '#text',
					'width'			 => '#int',
					'height'			=> '#int',
					'flashvars'		 => '#text',
					'wmode'			 => '#text'
				)
			),
			array(
				'acronym',
				array('title')
			),
			array(
				'abbr',
				array('title')
			),
			[
			  'ol',
			  [
				'start' => '#int',
			  ],
			],
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
        array(
          'title',
          'id' => '#text',
          'href',
          'rel' => '#text',
          'class' => array('js-lbx'),
          'name' => '#text',
          'target' => array('_blank')
        ),
			),
			array(
				'spoiler',
				array('title' => '#text')
			),
			array(
				'th',
				array(
					'colspan' => '#int',
					'rowspan' => '#int',
					'align'   => array('right', 'left', 'center', 'justify'),
					'height'  => '#int',
					'width'   => '#int'
				)
			),
			array(
				'td',
				array(
					'colspan' => '#int',
					'rowspan' => '#int',
					'align'   => array('right', 'left', 'center', 'justify'),
					'height'  => '#int',
					'width'   => '#int'
				)
			),
			array(
				'table',
				array(
					'border'	  => '#int',
					'cellpadding' => '#int',
					'cellspacing' => '#int',
					'align'	   => array('right', 'left', 'center'),
					'height'	  => '#int',
					'width'	   => '#int'
				)
			),
			array(
				'li',
        array('id' => '#text')
      ),
		),
		// Теги с обязательными параметрами
		'cfgSetTagParamDefault'	 => array(
			array(
				'embed',
				'wmode',
				'opaque',
				true,
				'a',
			),
		),
		// допустимые комбинации значений у параметров
		'cfgSetTagParamCombination' => array(
			array(
				'param',
				'name',
				array(
					'allowScriptAccess' => array(
						'value' => array('sameDomain'),
					),
					'movie'			 => array(
						'value' => array('#domain' => array('youtube.com', 'rutube.ru', 'vimeo.com')),
					),
					'align'			 => array(
						'value' => array('bottom', 'middle', 'top', 'left', 'right'),
					),
					'base'			  => array(
						'value' => true,
					),
					'bgcolor'		   => array(
						'value' => true,
					),
					'border'			=> array(
						'value' => true,
					),
					'devicefont'		=> array(
						'value' => true,
					),
					'flashVars'		 => array(
						'value' => true,
					),
					'hspace'			=> array(
						'value' => true,
					),
					'quality'		   => array(
						'value' => array('low', 'medium', 'high', 'autolow', 'autohigh', 'best'),
					),
					'salign'			=> array(
						'value' => array('L', 'T', 'R', 'B', 'TL', 'TR', 'BL', 'BR'),
					),
					'scale'			 => array(
						'value' => array('scale', 'showall', 'noborder', 'exactfit'),
					),
					'tabindex'		  => array(
						'value' => true,
					),
					'title'			 => array(
						'value' => true,
					),
					'type'			  => array(
						'value' => true,
					),
					'vspace'			=> array(
						'value' => true,
					),
					'wmode'			 => array(
						'value' => array('window', 'opaque', 'transparent'),
					),
				),
				true, // Удалять тег, если нет основного значения параметра в списке комбинаций
			),
		),
		// Теги, после которых необходимо пропускать одну пробельную строку
		'cfgSetTagBlockType'		=> array(
			array(
				array('h4', 'h5', 'h6', 'ol', 'ul', 'blockquote', 'pre', 'table', 'iframe', 'code')
			)
		),
		'cfgSetTagCallbackFull' => array(
			array(
				'video',
				array('_this_', 'Text_CallbackParserTag'),
			),
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
			array(
				'code',
				array('_this_', 'Text_CallbackParserTag'),
			),
			array(
				'codeline',
				array('_this_', 'Text_CallbackParserTag'),
			),
		)
	),
	// настройки для обработки текста в результатах поиска
	'search'  => array(
		// Разрешённые теги
		'cfgAllowTags'	  => array(
			// вызов метода с параметрами
			array(
				array('span'),
			),
		),
		// Разрешённые параметры тегов
		'cfgAllowTagParams' => array(
			array(
				'span',
				array('class' => '#text')
			),
		),
	),
);
