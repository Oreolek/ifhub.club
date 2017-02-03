<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Обработка блока с топиками (прямой эфир)
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockActivityRecent extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        /**
         * Получаем топики
         */
        if ($oTopics = $this->Topic_GetTopicsLast(Config::Get('block.stream.row'))) {
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('topics', $oTopics, true);
            $sTextResult = $oViewer->Fetch("component@activity.recent-topics");
            $this->Viewer_Assign('content', $sTextResult, true);
        }

        $this->SetTemplate('component@activity.block.recent');
    }
}
