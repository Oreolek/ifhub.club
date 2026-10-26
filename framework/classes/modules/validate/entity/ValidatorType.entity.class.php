<?php declare(strict_types=1);
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
 * CTypeValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Валидатор типа данных
 * Для типа дата/время используется внешний валидатор DateTimeParser
 *
 * @package framework.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorType extends ModuleValidate_EntityValidator
{
    /**
     * Допустимый тип данных.
     * Допустимые значения: 'string', 'integer', 'float', 'array', 'date', 'time' и 'datetime'.
     *
     * @var string
     */
    public $type = 'string';
    /**
     * Допустимый формат даты, актуально при type = date
     *
     * @var string
     */
    public $dateFormat = 'dd-MM-yyyy';
    /**
     * Допустимый формат времени, актуально при type = time
     *
     * @var string
     */
    public $timeFormat = 'hh:mm';
    /**
     * Допустимый формат даты со временем, актуально при type = datetime
     *
     * @var string
     */
    public $datetimeFormat = 'dd-MM-yyyy hh:mm';
    /**
     * Допускать или нет пустое значение
     *
     * @var bool
     */
    public $allowEmpty = true;

    /**
     * Запуск валидации
     *
     * @param mixed $sValue Данные для валидации
     *
     * @return bool|string
     */
    public function validate($sValue)
    {
        if ($this->allowEmpty && $this->isEmpty($sValue)) {
            return true;
        }

        if ($this->type === 'integer') {
            $bValid = preg_match('/^[-+]?[0-9]+$/', trim($sValue));
        } else {
            if ($this->type === 'float') {
                $bValid = preg_match('/^[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/', trim($sValue));
            } else {
                if ($this->type === 'date') {
                    $bValid = DateTimeParser::parse($sValue, $this->dateFormat) !== false;
                } else {
                    if ($this->type === 'time') {
                        $bValid = DateTimeParser::parse($sValue, $this->timeFormat) !== false;
                    } else {
                        if ($this->type === 'datetime') {
                            $bValid = DateTimeParser::parse($sValue, $this->datetimeFormat) !== false;
                        } else {
                            if ($this->type === 'array') {
                                $bValid = is_array($sValue);
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        if (!$bValid) {
            return $this->getMessage($this->Lang_Get('validate.type.error', null, false), 'msg',
                array('type' => $this->type));
        }
        return true;
    }
}