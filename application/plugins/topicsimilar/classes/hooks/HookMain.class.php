<?php

/*-------------------------------------------------------
*
*   Plugin name:    Topic Similar
*   Author:         Chiffa
*   Web:            http://goweb.pro
*
---------------------------------------------------------
*/


class PluginTopicsimilar_HookMain extends Hook
{

    /**
     * Регистрация необходимых хуков
     */
    public function RegisterHook()
    {
        $this->AddHook('topic_show', 'TopicShow');
        $this->AddHook('template_comments_begin', 'TplCommentsBefore');
        $this->AddHook('template_comments_end', 'TplCommentsAfter');
    }

    public function TopicShow($aVars = [])
    {
        if (isset($aVars['oTopic'])) {
            $aGroupsExists = ['right', 'comments_before', 'comments_after'];
            if (!in_array(Config::Get('plugin.topicsimilar.position'), $aGroupsExists)) {
                return false;
            }
            $sBlockGroup = Config::Get('plugin.topicsimilar.position');

            /**
             * Добавляем блок
             */
            $this->Viewer_AddBlock($sBlockGroup, 'similar',
                array('plugin' => Plugin::GetPluginCode($this), 'topic' => $aVars['oTopic']),
                (int)Config::Get('plugin.topicsimilar.block.priority'));
        }
        return true;
    }

    public function TplCommentsBefore()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.comments.before.tpl');
    }

    public function TplCommentsAfter()
    {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.comments.after.tpl');
    }
}
