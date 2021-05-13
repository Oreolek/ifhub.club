{**
 * Блок "похожие топики по тегам"
 *
 * @param array  $items
 *}


{capture 'similar_content'}
    {if $aSimilarTopics}
        {$similarItems = []}

        {foreach $aSimilarTopics as $oSimilarTopic}
            {$oSimilarTopicUser = $oSimilarTopic->getUser()}

            {capture 'item_content'}
                <ul class="ls-topic-similar-info">
                    <li>
                        <a href="{$oSimilarTopicUser->getUserWebPath()}" class="ls-topic-similar-user">{$oSimilarTopicUser->getDisplayName()}</a>
                    </li>
                    <li>
                        <time datetime="{date_format date=$oSimilarTopic->getDatePublish() format='c'}" class="ls-topic-similar-date">
                            {date_format date=$oSimilarTopic->getDatePublish() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y"}
                        </time>
                    </li>
                    <li>
                        <a href="{$topic->getUrl()}#comments" class="ls-topic-similar-comments">
                            {component 'icon' icon='comments'}
                            {lang 'comments.comments_declension' count=$oSimilarTopic->getCountComment() plural=true}
                            {if $oSimilarTopic->getCountCommentNew()}<span>+{$oSimilarTopic->getCountCommentNew()}</span>{/if}
                        </a>
                    </li>
                </ul>
            {/capture}

            {$similarItems[] = [
                'element' => 'li',
                'mods'    => 'image-rounded',
                'title'   => $oSimilarTopic->getTitle(),
                'titleUrl'=> $oSimilarTopic->getUrl(),
                'content' => $smarty.capture.item_content,
                'image'   => [
                    'path' => $oSimilarTopicUser->getProfileAvatarPath(48),
                    'url'  => $oSimilarTopicUser->getUserWebPath()
                ]
            ]}
        {/foreach}

        {component 'item' template='group' items=$similarItems}
    {else}
        {component 'blankslate' text={lang 'common.empty'} mods='no-background'}
    {/if}
{/capture}


{component 'block'
    mods     = 'similar-topics'
    classes  = 'js-block-default'
    title    = {lang 'plugin.topicsimilar.block.title'}
    content  = $smarty.capture.similar_content}
