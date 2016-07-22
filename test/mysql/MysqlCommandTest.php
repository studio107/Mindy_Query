<?php

namespace Mindy\Query\Tests;

use Mindy\QueryBuilder\Database\Mysql\Adapter;

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 05/02/15 17:06
 */
class MysqlCommandTest extends CommandTest
{
    public $driverName = 'mysql';

    public function getAdapter()
    {
        return new Adapter();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return require(__DIR__ . '/config.php');
    }
}
