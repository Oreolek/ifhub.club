<?php
/**
 * DbSimple_Mysql: MySQL database compatibility driver.
 *
 * The legacy mysql extension was removed in PHP 7.0. This driver now uses
 * mysqli under the hood via DbSimple_Mysqli.
 *
 * @author Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 * @author Konstantin Zhinko, http://forum.dklab.ru/users/KonstantinGinkoTit/
 */
require_once __DIR__.'/Mysqli.php';

/**
 * Database class for MySQL.
 */
class DbSimple_Mysql extends DbSimple_Mysqli
{
}

/**
 * MySQL BLOB implementation.
 */
class DbSimple_Mysql_Blob extends DbSimple_Mysqli_Blob
{
}
