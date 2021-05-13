<?php
/*-------------------------------------------------------
*
*   Plugin name:    Topic Similar
*   Author:         Chiffa
*   Web:            http://goweb.pro
*
---------------------------------------------------------
*/

if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginTopicsimilar extends Plugin
{
    protected $aInherits = [
        'module' => [
            'ModuleTopic'
        ],
        'mapper' => [
            'ModuleTopic_MapperTopic'
        ],
    ];

    /**
     * Инициализация плагина
     */
    public function Init()
    {
        /**
         * Подключаем CSS
         */
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . 'assets/css/style.css');
        return true;
    }
}
