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

require_once(__DIR__ . '/insert.block.php');

/**
 * Плагин для смарти: Smarty 5 удалил тег {insert}
 *
 * @param array $aParams
 * @param \Smarty\Template $oSmarty
 * @return string
 */
function smarty_function_insert($aParams, $oSmarty)
{
    return smarty_insert_block($aParams, $oSmarty);
}
