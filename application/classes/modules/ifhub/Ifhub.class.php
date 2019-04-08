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
     * @param string $sTag Тег на котором сработал колбэк
     * @param array $aParams Список параметров тега
     * @return string
     */
    public function CallbackParserTagSpoiler($sTag, $aParams, $sText)
    {
        $sTitle = "Спойлер";
        if (isset($aParams['title'])) {
            $sTitle = $aParams['title'];
        }

        return '<details class="newspoiler">'.
          '<summary class="newspoiler-title">'.$sTitle.'</summary>'.
          $sText.'</details>';
    }
    /**
     * Обработка тега aside в тексте
     * <pre>
     * <aside>Текст врезки</aside>
     * </pre>
     *
     * @param string $sTag Тег на котором сработал колбэк
     * @param array $aParams Список параметров тега
     * @return string
     */
    public function CallbackParserTagAside($sTag, $aParams, $sText)
    {
        return '<div class="aside">'.$sText.'</div>';
    }
    /**
     * Обработка тега incut в тексте
     * <pre>
     * <incut>Текст врезки</incut>
     * </pre>
     *
     * @param string $sTag Тег на котором сработал колбэк
     * @param array $aParams Список параметров тега
     * @return string
     */
    public function CallbackParserTagIncut($sTag, $aParams, $sText)
    {
        return '<div class="incut">'.$sText.'</div>';
    }
}
