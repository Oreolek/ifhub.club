<?php declare(strict_types=1);
/*
*   IFHub.club engine based on LiveStreet Engine
*   Copyright © 2026 Oreolek
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * Парсер строк даты/времени в UNIX-таймстамп по шаблону.
 * Замена Yii-библиотеки DateTimeParser (framework/libs/vendor/DateTime).
 *
 * Поддерживаются только токены, встречающиеся в форматах валидаторов движка:
 *   yyyy — год, 4 цифры; MM — месяц, 2 цифры; dd — день, 2 цифры;
 *   hh, HH — час 00-23; mm — минуты 00-59; ss — секунды 00-59.
 * Остальные символы шаблона должны совпадать в строке буквально.
 * Отсутствующие в шаблоне дата берется текущая (как в Yii-оригинале),
 * время считается 00:00:00.
 * shortcut: Yii-токены yy, M, d, h, H, m, s, a и defaults не поддерживаются;
 * если валидатору понадобится иной формат — вернуть полный переводчик шаблонов.
 */
class DateTimeParser
{
    /**
     * Распознаваемый токен шаблона => количество цифр
     *
     * @var array
     */
    private const TOKENS = [
        'yyyy' => 4,
        'MM'   => 2,
        'dd'   => 2,
        'hh'   => 2,
        'HH'   => 2,
        'mm'   => 2,
        'ss'   => 2,
    ];

    /**
     * Преобразует строку даты/времени в UNIX-таймстамп.
     *
     * @param string $value Строка даты
     * @param string $pattern Шаблон, которому следует строка
     * @return int|false Таймстамп или false, если разбор не удался
     */
    public static function parse($value, $pattern = 'yyyy-MM-dd')
    {
        $i = 0;
        $n = strlen($value);
        $year = $month = $day = $hour = $minute = $second = null;
        foreach (self::tokenize($pattern) as $token) {
            $iWidth = self::TOKENS[$token] ?? 0;
            if ($iWidth === 0) {
                // литерал
                if ($i >= $n || substr($value, $i, strlen($token)) !== $token) {
                    return false;
                }
                $i += strlen($token);
                continue;
            }
            $sPart = substr($value, $i, $iWidth);
            if (strlen($sPart) !== $iWidth || !ctype_digit($sPart)) {
                return false;
            }
            $i += $iWidth;
            switch ($token) {
                case 'yyyy':
                    $year = $sPart;
                    break;
                case 'MM':
                    $month = $sPart;
                    break;
                case 'dd':
                    $day = $sPart;
                    break;
                case 'hh':
                case 'HH':
                    $hour = $sPart;
                    break;
                case 'mm':
                    $minute = $sPart;
                    break;
                case 'ss':
                    $second = $sPart;
            }
        }
        if ($i < $n) {
            return false;
        }

        $year = (int)($year ?? date('Y'));
        $month = (int)($month ?? date('n'));
        $day = (int)($day ?? date('j'));
        $hour = (int)($hour ?? '0');
        $minute = (int)($minute ?? '0');
        $second = (int)($second ?? '0');

        // checkdate учитывает високосные годы
        if (!checkdate($month, $day, $year) || $hour > 23 || $minute > 59 || $second > 59) {
            return false;
        }
        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    /*
     * Разбивает шаблон на последовательности одинаковых символов
     */
    private static function tokenize($pattern)
    {
        if (!($n = strlen($pattern))) {
            return [];
        }
        $tokens = [];
        for ($c0 = $pattern[0], $start = 0, $i = 1; $i < $n; ++$i) {
            if (($c = $pattern[$i]) !== $c0) {
                $tokens[] = substr($pattern, $start, $i - $start);
                $c0 = $c;
                $start = $i;
            }
        }
        $tokens[] = substr($pattern, $start, $n - $start);
        return $tokens;
    }
}