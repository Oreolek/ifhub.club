<?php
/**
 * Страницы с архивами
 *
 * @package application.actions
 * @since 1.0
 */
class ActionArchive extends Action
{
    /**
     * Инициализация экшена
     *
     */
    public function Init()
    {
        /**
         * Устанавливаем дефолтный евент
         */
        $this->SetDefaultEvent('index');
    }

    /**
     * Регистрируем евенты
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEvent('wiki', 'EventIfwiki');
    }

    /**
     * Вывод списка архивов
     *
     */
    protected function EventIndex()
    {
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle('Архивы');
        $this->SetTemplateAction('index');
    }

    /**
     * Архивы вики
     */
    protected function EventIfwiki()
    {
      $this->Viewer_AddHtmlTitle('Архивы IFWiki');
      $files = array_slice(scandir('./wikidump'), 2);
      $this->SetTemplateAction('archive');
      $this->Viewer_Assign('files', $files);
    }
}
