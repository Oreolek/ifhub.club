<?php
/**
 * Статическая страница доната
 *
 * @package application.actions
 * @since 1.0
 */
class ActionDonate extends Action
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
    }

    /**
     * Вывод правил
     *
     */
    protected function EventIndex()
    {
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle('Поддержать IFHub');
        $this->SetTemplateAction('index');
    }
}
