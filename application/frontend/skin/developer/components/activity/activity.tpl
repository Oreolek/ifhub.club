{**
 * Список событий активности
 *
 * @param array   $events
 * @param integer $targetId
 * @param integer $count
 *}

{$component = 'activity'}

{$events = $smarty.local.events}

<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
	{if $events}
		{* Список *}
		<ul class="activity-event-list js-activity-event-list">
			{include './event-list.tpl' events=$events}
		</ul>

		{* Кнопка подгрузки *}
		{if $smarty.local.count > Config::Get('module.stream.count_default')}
			{$last = end($events)}

			{include 'components/more/more.tpl'
					 iCount      = $smarty.local.count
					 sClasses    = "js-activity-more"
					 sAttributes = "data-proxy-last_id=\"{$last->getId()}\" data-param-target_id=\"{$smarty.local.targetId}\""}
		{/if}
	{else}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.common.empty sMods='empty'}
	{/if}
</div>