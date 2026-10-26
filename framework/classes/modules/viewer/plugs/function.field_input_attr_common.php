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
 * Общие атрибуты поля формы.
 * В Smarty 5 функции из родительского шаблона недоступны при компиляции дочернего.
 *
 * @param array $aParams
 * @param \Smarty\Template $oSmarty
 * @return string
 */
function smarty_function_field_input_attr_common($aParams, $oSmarty)
{
    $aVars = $oSmarty->getTemplateVars();
    $bUseValue = array_key_exists('useValue', $aParams) ? $aParams['useValue'] : true;

    $sResult = ' id="' . ($aVars['uid'] ?? '') . '" class="' . ($aVars['component'] ?? '') . '-input ' . ($aVars['inputClasses'] ?? '') . '"';
    if ($bUseValue) {
        $sResult .= ' value="' . ($aVars['value'] ?? '') . '"';
    }
    if (!empty($aVars['name'])) {
        $sResult .= ' name="' . $aVars['name'] . '"';
    }
    if (!empty($aVars['placeholder'])) {
        $sResult .= ' placeholder="' . $aVars['placeholder'] . '"';
    }
    if (!empty($aVars['isDisabled'])) {
        $sResult .= ' disabled';
    }

    $aRules = $aVars['rules'] ?? [];
    foreach ($aRules as $sRuleName => $mRuleValue) {
        if (is_bool($mRuleValue) && !$mRuleValue) {
            continue;
        }
        if ($sRuleName === 'remote') {
            $sResult .= ' data-parsley-remote-validator="' . ($aRules['remote-validator'] ?? 'livestreet') . '"';
            $sResult .= ' data-parsley-trigger="focusout"';
            $aJson = ['type' => 'post', 'data' => ['security_ls_key' => $aVars['LIVESTREET_SECURITY_KEY'] ?? null]];
            if (array_key_exists('remote-options', $aRules)) {
                $aJson = array_merge_recursive($aJson, $aRules['remote-options']);
            }
            $sResult .= " data-parsley-remote-options='" . json_encode($aJson) . "'";
        }
        if ($sRuleName === 'remote-options') {
            continue;
        }
        $sResult .= ' data-parsley-' . $sRuleName . '="' . $mRuleValue . '"';
    }

    if (function_exists('smarty_function_cattr')) {
        $sResult .= smarty_function_cattr(['list' => $aVars['inputAttributes'] ?? null]);
    }
    if (function_exists('smarty_function_cdata')) {
        $sResult .= smarty_function_cdata([
            'name' => $aVars['component'] ?? '',
            'list' => $aVars['inputData'] ?? null,
        ]);
    }

    return $sResult;
}
