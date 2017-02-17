<?php
/**
 * Модуль для функций Ифхаба
 * 
 * @license GPLv2
 * @package application.modules.ifhub
 * @author Alexander Yakovlev
 */
class ModuleIfhub extends Module
{
    /**
     * Инициализация
     *
     */
    public function Init()
    {
    }

    /**
     * Обработка тега spoiler в тексте
     * <pre>
     * <spoiler title="Заголовок">Текст спойлера</spoiler>
     * </pre>
     *
     * @param string $sTag Тег на ктором сработал колбэк
     * @param array $aParams Список параметров тега
     * @return string
     */
    public function CallbackParserTagSpoiler($sTag, $aParams, $sText)
    {
        $sTitle = "Спойлер";
        if (isset($aParams['title'])) {
            $sTitle = $aParams['title'];
        }

        return '<div class="spoiler">'.
          '<b class="spoiler-title">'.$sTitle.'</b>'.
          '<div class="spoiler-body">'.$sText.'</div>'.
          '</div>';
    }
}
