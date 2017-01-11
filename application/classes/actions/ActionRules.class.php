<?php
/**
 * Статическая страница правил
 *
 * @package application.actions
 * @since 1.0
 */
class ActionRules extends Action
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
        $this->Viewer_AddHtmlTitle('Правила Ифхаба');
        $this->SetTemplateAction('index');
    }
}
