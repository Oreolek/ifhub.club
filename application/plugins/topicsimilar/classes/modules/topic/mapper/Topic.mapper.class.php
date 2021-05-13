<?php

/*-------------------------------------------------------
*
*   Plugin name:    Topic Similar
*   Author:         Chiffa
*   Web:            http://goweb.pro
*
---------------------------------------------------------
*/


class PluginTopicsimilar_ModuleTopic_MapperTopic extends PluginTopicsimilar_Inherit_ModuleTopic_MapperTopic
{

    /**
     * Получает список топиков по массиву тегов
     *
     * @param array $aTags Массив тегов
     * @param array $aExcludeTopic Список ID топиков для исключения
     * @param string $sType Тип топика
     * @param int $iLimit Количество
     * @return array
     */
    public function GetTopicsIdByTagsArray($aTags = array(), $aExcludeTopic = array(), $iLimit = 10, $aIncludeType = array())
    {
        $aResult = array();

        if ($aTags) {
            $sql = "SELECT
						tt.topic_id,
						COUNT(*) AS tags_count
					FROM
						" . Config::Get('db.table.topic_tag') . " AS tt,
						" . Config::Get('db.table.topic') . " AS t
					WHERE t.topic_publish = 1
					AND tt.topic_id = t.topic_id
					AND tt.topic_tag_text IN (?a)
					{ AND tt.topic_id NOT IN (?a) }
					{ AND t.topic_type NOT IN (?a) }
					GROUP BY tt.topic_id
					ORDER BY tags_count DESC
					LIMIT 0, ?d
			";
            if ($aRows = $this->oDb->select(
                $sql, $aTags,
                (is_array($aExcludeTopic) && count($aExcludeTopic)) ? $aExcludeTopic : DBSIMPLE_SKIP,
                (is_array($aIncludeType) && count($aIncludeType)) ? $aIncludeType : DBSIMPLE_SKIP,
                $iLimit
            )
            ) {
                foreach ($aRows as $aRow) {
                    $aResult[] = $aRow['topic_id'];
                }
            }
        }
        return $aResult;
    }

}
