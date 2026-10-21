{$component = 'ls-tab'}
{component_define_params params=[ 'name', 'isActive', 'mods', 'classes', 'attributes' ]}

<li class="{$component} {cmods name=$component mods=$mods} {$classes} {if $isActive}active{/if}" {cattr list=$attributes}
    data-tab
    data-lstab-options='{
        "target": "{$uid|default:''}",
        "urls": {
            "load": "{$url|default:''}"
        }
    }'>

    {if $url|default:''}
        <a href="{$url}" class="ls-tab-inner">{$text|default:''}</a>
    {else}
        <span class="ls-tab-inner">{$text|default:''}</span>
    {/if}
</li>