<?php
/*-------------------------------------------------------
*
*   Plugin name:    Topic Similar
*   Author:         Chiffa
*   Web:            http://goweb.pro
*
---------------------------------------------------------
*/


class PluginTopicsimilar_ModuleTopic extends PluginTopicsimilar_Inherit_ModuleTopic
{

    /**
     * Получает список топиков по массиву тегов
     *
     * @param array $aTags          Массив тегов
     * @param array $aExcludeTopic  Список ID топиков для исключения
     * @param int   $iLimit         Количество
     * @param array $aIncludeType   Типы топиков
     * @return array
     */
    public function GetTopicsSimilarByTags($aTags = [], $aExcludeTopic = [], $iLimit = 10, $aIncludeType = [])
    {
      $sCacheKey = 'topics_similar_by_' . serialize($aTags) . serialize($aExcludeTopic) . '_types_' . (!$aIncludeType) ? 'all' : serialize($aIncludeType);
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
          $data = $this->oMapperTopic->GetTopicsIdByTagsArray($aTags, $aExcludeTopic, $iLimit, $aIncludeType);
          $data = $this->GetTopicsAdditionalData($data, [ 'user' => [] ]);
          // Убираем будущие публикации из предложений
          foreach ($data as $index => $oTopic) {
            $date = (DateTime::createFromFormat('Y-m-d H:i:s', $oTopic->getTopicDatePublish()))->format('U');
            if ($date > time()) {
              unset($data[$index]);
              continue;
            }
          }
          $this->Cache_Set($data, $sCacheKey, array('topic_update', 'topic_new'), 60 * 60 * 24 * 3);
        }
        return $data;
    }

}
