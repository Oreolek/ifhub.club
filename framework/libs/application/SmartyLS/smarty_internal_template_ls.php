<?php

class Smarty_Internal_Template_LS extends \Smarty\Template
{
    /**
     * Переменные, переданные в подшаблон
     *
     * @var array
     */
    public $tpl_vars_local = [];

    /**
     * Признак рендеринга родительского шаблона при наследовании
     *
     * @var bool
     */
    public $bInheritRender = false;

    /**
     * @param string $template_name
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param int|null $caching
     * @param int|null $cache_lifetime
     * @param array $extra_vars
     * @param int|null $scope
     * @param string|null $currentDir
     */
    public function renderSubTemplate(
        $template_name,
        $cache_id,
        $compile_id,
        $caching,
        $cache_lifetime,
        array $extra_vars = [],
        ?int $scope = null,
        ?string $currentDir = null
    ) {
        $this->bInheritRender = $extra_vars === [] && $scope === null;
        try {
            parent::renderSubTemplate(
                $template_name,
                $cache_id,
                $compile_id,
                $caching,
                $cache_lifetime,
                $extra_vars,
                $scope,
                $currentDir
            );
        } finally {
            $this->bInheritRender = false;
        }
    }

    /**
     * Совместимость с API Smarty 3, который используют плагины LiveStreet
     *
     * @param string $template
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param int|null $caching
     * @param int|null $cache_lifetime
     * @param array $data
     * @param int $parent_scope
     * @return string
     */
    public function getSubTemplate(
        $template,
        $cache_id,
        $compile_id,
        $caching,
        $cache_lifetime,
        $data,
        $parent_scope
    ) {
        $oTemplate = $this->getSmarty()->doCreateTemplate(
            $template,
            $cache_id,
            $compile_id,
            $this,
            $caching,
            $cache_lifetime
        );

        $oTemplate->assign($this->getTemplateVars(null, false));
        foreach ((array)$data as $sName => $mValue) {
            $oTemplate->assign($sName, $mValue);
            $oTemplate->tpl_vars_local[$sName] = new \Smarty\Variable($mValue);
        }

        return $oTemplate->fetch();
    }
}
