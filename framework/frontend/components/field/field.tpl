{**
 * Базовый шаблон поля формы
 *
 * @param string  $label     Текст лэйбла
 * @param string  $note      Подсказка (отображается под полем)
 *
 * @param object  $form
 * @param string  $rules     Правила валидации
 * @param object  $entity
 * @param object  $entityScenario
 *
 * @param boolean $escape   Экранировать параметр value или нет
 * @param string  $name      Имя поля (параметр name)
 * @param object  $placeholder
 * @param object  $isDisabled
 * @param object  $inputData
 * @param object  $inputClasses
 * @param object  $inputAttributes
 *}

{* Название компонента *}
{$component = 'ls-field'}

{component_define_params params=[ 'form', 'placeholder', 'isDisabled', 'entity', 'entityScenario', 'entityField', 'escape', 'data', 'label', 'name',
    'rules', 'value', 'id', 'inputClasses', 'inputAttributes', 'inputData', 'mods', 'classes', 'attributes', 'note' ]}

{* Уникальный ID *}
{$uid = $id|default:($component|cat:mt_rand())}

{* Дефолтные значения *}
{$rules = $rules|default:[]}
{$escape = $escape|default:true}
{$form = $form|default:$_aRequest}
{$getValueFromForm = true}

{if $isDisabled}
    {$mods = "$mods disabled"}
{/if}

{* Правила валидации *}
{if $entity}
    {field_make_rule entity=$entity field=$entityField|default:$name scenario=$entityScenario assign=rules}
{/if}

{block 'field_options'}
    {* Получение значения атрибута value *}
    {if ! isset($value) && $getValueFromForm && $name && $form}
        {$value = {field_get_value form=$form name=$name}|default:$value}
    {/if}

    {* Escape *}
    {$value = $value|default:''}
    {$value = ($escape) ? htmlspecialchars($value) : $value}
{/block}

{block 'field'}
    <div class="{$component} {cmods name=$component mods=$mods} ls-clearfix {$classes} {block 'field_classes'}{/block}"
        {cdata name=$component list=$data}
        {cattr list=$attributes}>

        {* Лэйбл *}
        {if $label}
            <label for="{$uid}" class="{$component}-label">{$label}</label>
        {/if}

        {* Блок с инпутом *}
        <div class="{$component}-holder">
            {block 'field_input'}{/block}
        </div>

        {* Подсказка *}
        {if $note}
            <small class="{$component}-note js-{$component}-note">{$note}</small>
        {/if}
    </div>
{/block}
