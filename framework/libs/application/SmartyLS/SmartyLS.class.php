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

if (!class_exists('Smarty', false)) {
    class_alias('Smarty\\Smarty', 'Smarty');
}

require_once('smarty_internal_template_ls.php');

class SmartyLS extends \Smarty\Smarty
{
    /**
     * @var array
     */
    private $aTemplates = [];
    private $aLocalVars = [];

    public function __construct()
    {
        parent::__construct();
        /**
         * Smarty 5 не поддерживает короткие параметры function-плагинов LiveStreet.
         */
        $this->registerFilter('pre', [$this, 'CompileLegacyShortTags']);
        /**
         * Smarty 5 запрещает вызов нативных PHP-функций без регистрации.
         */
        foreach (
            [
                'trim', 'is_array', 'count', 'array_merge', 'in_array', 'strtotime', 'number_format',
                'mt_rand', 'str_replace', 'htmlspecialchars', 'array_key_exists', 'array_merge_recursive',
                'json_encode', 'is_bool', 'ucfirst', 'round', 'date', 'mb_substr', 'mb_strlen', 'strlen',
                'substr', 'explode', 'implode', 'strtolower', 'strtoupper', 'preg_match', 'preg_replace',
                'max', 'min', 'abs', 'intval', 'floatval', 'array_map', 'array_filter', 'array_values',
                'array_keys', 'array_slice', 'array_unique', 'array_reverse', 'array_pop', 'array_shift',
                'is_null', 'is_numeric', 'is_string', 'is_int', 'is_object', 'nl2br', 'strip_tags',
                'html_entity_decode', 'urlencode', 'rawurlencode', 'base64_encode', 'md5', 'sha1', 'time',
                'array_intersect', 'array_splice', 'end', 'memory_get_usage', 'memory_get_peak_usage',
            ] as $sFunction
        ) {
            if (function_exists($sFunction)) {
                $this->registerPlugin('modifier', $sFunction, $sFunction);
            }
        }
    }

    /**
     * @param string $sSource
     * @param \Smarty\Template|null $oTemplate
     * @return string
     */
    public function CompileLegacyShortTags($sSource, $oTemplate = null)
    {
        return preg_replace_callback(
            '/\{(component|lang|cfg|router)\s+((?:"(?:\\\\.|[^"\\\\])*"|\'(?:\\\\.|[^\'\\\\])*\'|\$[^\s}]+))/',
            function ($aMatch) {
                return '{' . $aMatch[1] . ' _default_short=' . $aMatch[2];
            },
            $sSource
        );
    }

    public function assign($tpl_var, $value = null, $nocache = false, $scope = null)
    {
        parent::assign($tpl_var, $value, $nocache, $scope);

        if ($scope === true || $scope === \Smarty\Data::SCOPE_LOCAL) {
            foreach (is_array($tpl_var) ? $tpl_var : [$tpl_var => $value] as $sName => $mValue) {
                $this->aLocalVars[$sName] = new \Smarty\Variable($mValue, $nocache);
            }
        }

        return $this;
    }

    public function fetch($template = null, $cache_id = null, $compile_id = null)
    {
        try {
            return parent::fetch($template, $cache_id, $compile_id);
        } finally {
            $this->aLocalVars = [];
        }
    }

    public function display($template = null, $cache_id = null, $compile_id = null)
    {
        try {
            parent::display($template, $cache_id, $compile_id);
        } finally {
            $this->aLocalVars = [];
        }
    }

    /**
     * @param string $resource_name
     * @return bool
     */
    public function templateExists($resource_name)
    {
        try {
            return parent::templateExists($this->ResolveTemplateResource($resource_name));
        } catch (\Smarty\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $resource_name
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param \Smarty\Data|null $parent
     * @param int|null $caching
     * @param int|null $cache_lifetime
     * @param bool $isConfig
     * @param array $data
     * @return \Smarty\Template
     */
    public function doCreateTemplate(
        $resource_name,
        $cache_id = null,
        $compile_id = null,
        $parent = null,
        $caching = null,
        $cache_lifetime = null,
        bool $isConfig = false,
        array $data = []
    ): \Smarty\Template {
        /**
         * Нормализуем каталоги шаблонов
         */
        $this->getTemplateDir();

        $resource_name = $this->ResolveTemplateResource($resource_name);
        $sTemplateId = $this->GetTemplateId($resource_name, $cache_id, $compile_id, $caching);

        if (!isset($this->aTemplates[$sTemplateId])) {
            $oTemplate = new Smarty_Internal_Template_LS(
                $resource_name,
                $this,
                $parent ?: $this,
                $cache_id,
                $compile_id,
                $caching,
                $isConfig
            );
            $oTemplate->templateId = $sTemplateId;
            $this->aTemplates[$sTemplateId] = $oTemplate;
        }

        $oTemplate = clone $this->aTemplates[$sTemplateId];
        $oTemplate->setParent($parent ?: $this);

        $aLocalVars = ($parent === null || $parent === $this) ? $this->aLocalVars : [];
        if ($parent instanceof Smarty_Internal_Template_LS && $parent->bInheritRender) {
            $aLocalVars = $parent->tpl_vars_local;
        }
        foreach ($data as $sName => $mValue) {
            $oTemplate->assign($sName, $mValue);
            $aLocalVars[$sName] = new \Smarty\Variable($mValue);
        }
        $oTemplate->tpl_vars_local = $aLocalVars;

        if ($cache_lifetime) {
            $oTemplate->setCacheLifetime($cache_lifetime);
        }

        $oTemplate->tplFunctions = array_merge($parent->tplFunctions ?? [], $oTemplate->tplFunctions ?? []);

        if (!$this->debugging && $this->debugging_ctrl === 'URL') {
            $oTemplate->getSmarty()->getDebug()->debugUrl($oTemplate->getSmarty());
        }

        return $oTemplate;
    }

    /**
     * @param string $sResource
     * @return string
     */
    protected function ResolveTemplateResource($sResource)
    {
        $bSkipDelegate = false;

        if (preg_match('#^Inherit@(.+)#i', $sResource, $aMatch)) {
            /**
             * Получаем шаблон по цепочке наследования
             */
            $sTpl = trim($aMatch[1]);
            $sParentTemplate = Engine::getInstance()->Plugin_GetParentInherit($sTpl);
            if ($sTpl == $sParentTemplate) {
                /**
                 * Сбрасываем цепочку наследования к начальному состоянию
                 */
                Engine::getInstance()->Plugin_ResetInheritPosition($sParentTemplate);
                /**
                 * В параметре может быть путь до шаблона с ".tpl" в конце, а таже может быть путь до шаблона компонента вида "component.name.template"
                 */
                if (!preg_match("#^\.tpl$#i", $sTpl)) {
                    $aPath = explode('.', $sTpl);
                    if (count($aPath) == 3 and preg_match('#^([\w\_]+\:)?component$#i', $aPath[0], $aMatch2)) {
                        $sPrefix = '';
                        if (isset($aMatch2[1])) {
                            $sPrefix = $aMatch2[1];
                        }
                        $sParentTemplate = Engine::getInstance()->Component_GetTemplatePath($sPrefix . $aPath[1],
                            $aPath[2], false);
                    }
                }
            }
            $sResource = $sParentTemplate;
            $bSkipDelegate = true;
        }

        /*
         * Ловим использование extends на файлы компонентов
         * Синтаксис: {extends 'Component@compname.template'}
         */
        if (preg_match('#^Component@(.+)#i', $sResource, $aMatch)) {
            $aPath = explode('.', $aMatch[1], 2);
            $sResource = Engine::getInstance()->Component_GetTemplatePath($aPath[0], isset($aPath[1]) ? $aPath[1] : null, true);
            $bSkipDelegate = true;
        }

        if (!$bSkipDelegate) {
            $sResource = Engine::getInstance()->Plugin_GetDelegate('template', $sResource);
        }

        return $sResource;
    }

    /**
     * @param string $sResource
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param int|null $caching
     * @return string
     */
    protected function GetTemplateId($sResource, $cache_id, $compile_id, $caching)
    {
        $cache_id = $cache_id ?? $this->cache_id;
        $compile_id = $compile_id ?? $this->compile_id;
        $caching = (int)($caching ?? $this->caching);

        if (strpos($sResource, ':') === false) {
            $sResource = "{$this->default_resource_type}:{$sResource}";
        }

        $sTemplateId = $sResource . '#' . $cache_id . '#' . $compile_id . '#' . $caching;
        if (strlen($sTemplateId) > 150) {
            $sTemplateId = sha1($sTemplateId);
        }

        return $sTemplateId;
    }
}
