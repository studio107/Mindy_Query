<?php

use Mindy\Query\Cubrid\QueryBuilder as CubridQueryBuilder;
use Mindy\Query\Mssql\QueryBuilder as MssqlQueryBuilder;
use Mindy\Query\Mysql\QueryBuilder as MysqlQueryBuilder;
use Mindy\Query\Pgsql\QueryBuilder as PgsqlQueryBuilder;
use Mindy\Query\QueryBuilder;
use Mindy\Query\Schema;
use Mindy\Query\Sqlite\QueryBuilder as SqliteQueryBuilder;

/**
 * @group db
 * @group mysql
 */
class QueryBuilderTest extends DatabaseTestCase
{
    /**
     * @throws \Exception
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        switch ($this->driverName) {
            case 'mysql':
                return new MysqlQueryBuilder($this->getConnection());
            case 'sqlite':
                return new SqliteQueryBuilder($this->getConnection());
            case 'mssql':
                return new MssqlQueryBuilder($this->getConnection());
            case 'pgsql':
                return new PgsqlQueryBuilder($this->getConnection());
            case 'cubrid':
                return new CubridQueryBuilder($this->getConnection());
        }
        throw new \Exception('Test is not implemented for ' . $this->driverName);
    }

    /**
     * this is not used as a dataprovider for testGetColumnType to speed up the test
     * when used as dataprovider every single line will cause a reconnect with the database which is not needed here
     */
    public function columnTypes()
    {
        return [
            [Schema::TYPE_PK, 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY'],
            [Schema::TYPE_PK . '(8)', 'int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY'],
            [Schema::TYPE_PK . ' CHECK (value > 5)', 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY CHECK (value > 5)'],
            [Schema::TYPE_PK . '(8) CHECK (value > 5)', 'int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY CHECK (value > 5)'],
            [Schema::TYPE_STRING, 'varchar(255)'],
            [Schema::TYPE_STRING . '(32)', 'varchar(32)'],
            [Schema::TYPE_STRING . ' CHECK (value LIKE "test%")', 'varchar(255) CHECK (value LIKE "test%")'],
            [Schema::TYPE_STRING . '(32) CHECK (value LIKE "test%")', 'varchar(32) CHECK (value LIKE "test%")'],
            [Schema::TYPE_STRING . ' NOT NULL', 'varchar(255) NOT NULL'],
            [Schema::TYPE_TEXT, 'text'],
            [Schema::TYPE_TEXT . '(255)', 'text'],
            [Schema::TYPE_TEXT . ' CHECK (value LIKE "test%")', 'text CHECK (value LIKE "test%")'],
            [Schema::TYPE_TEXT . '(255) CHECK (value LIKE "test%")', 'text CHECK (value LIKE "test%")'],
            [Schema::TYPE_TEXT . ' NOT NULL', 'text NOT NULL'],
            [Schema::TYPE_TEXT . '(255) NOT NULL', 'text NOT NULL'],
            [Schema::TYPE_SMALLINT, 'smallint(6)'],
            [Schema::TYPE_SMALLINT . '(8)', 'smallint(8)'],
            [Schema::TYPE_INTEGER, 'int(11)'],
            [Schema::TYPE_INTEGER . '(8)', 'int(8)'],
            [Schema::TYPE_INTEGER . ' CHECK (value > 5)', 'int(11) CHECK (value > 5)'],
            [Schema::TYPE_INTEGER . '(8) CHECK (value > 5)', 'int(8) CHECK (value > 5)'],
            [Schema::TYPE_INTEGER . ' NOT NULL', 'int(11) NOT NULL'],
            [Schema::TYPE_BIGINT, 'bigint(20)'],
            [Schema::TYPE_BIGINT . '(8)', 'bigint(8)'],
            [Schema::TYPE_BIGINT . ' CHECK (value > 5)', 'bigint(20) CHECK (value > 5)'],
            [Schema::TYPE_BIGINT . '(8) CHECK (value > 5)', 'bigint(8) CHECK (value > 5)'],
            [Schema::TYPE_BIGINT . ' NOT NULL', 'bigint(20) NOT NULL'],
            [Schema::TYPE_FLOAT, 'float'],
            [Schema::TYPE_FLOAT . '(16,5)', 'float'],
            [Schema::TYPE_FLOAT . ' CHECK (value > 5.6)', 'float CHECK (value > 5.6)'],
            [Schema::TYPE_FLOAT . '(16,5) CHECK (value > 5.6)', 'float CHECK (value > 5.6)'],
            [Schema::TYPE_FLOAT . ' NOT NULL', 'float NOT NULL'],
            [Schema::TYPE_DECIMAL, 'decimal(10,0)'],
            [Schema::TYPE_DECIMAL . '(12,4)', 'decimal(12,4)'],
            [Schema::TYPE_DECIMAL . ' CHECK (value > 5.6)', 'decimal(10,0) CHECK (value > 5.6)'],
            [Schema::TYPE_DECIMAL . '(12,4) CHECK (value > 5.6)', 'decimal(12,4) CHECK (value > 5.6)'],
            [Schema::TYPE_DECIMAL . ' NOT NULL', 'decimal(10,0) NOT NULL'],
            [Schema::TYPE_DATETIME, 'datetime'],
            [Schema::TYPE_DATETIME . " CHECK(value BETWEEN '2011-01-01' AND '2013-01-01')", "datetime CHECK(value BETWEEN '2011-01-01' AND '2013-01-01')"],
            [Schema::TYPE_DATETIME . ' NOT NULL', 'datetime NOT NULL'],
            [Schema::TYPE_TIMESTAMP, 'timestamp'],
            [Schema::TYPE_TIMESTAMP . " CHECK(value BETWEEN '2011-01-01' AND '2013-01-01')", "timestamp CHECK(value BETWEEN '2011-01-01' AND '2013-01-01')"],
            [Schema::TYPE_TIMESTAMP . ' NOT NULL', 'timestamp NOT NULL'],
            [Schema::TYPE_TIME, 'time'],
            [Schema::TYPE_TIME . " CHECK(value BETWEEN '12:00:00' AND '13:01:01')", "time CHECK(value BETWEEN '12:00:00' AND '13:01:01')"],
            [Schema::TYPE_TIME . ' NOT NULL', 'time NOT NULL'],
            [Schema::TYPE_DATE, 'date'],
            [Schema::TYPE_DATE . " CHECK(value BETWEEN '2011-01-01' AND '2013-01-01')", "date CHECK(value BETWEEN '2011-01-01' AND '2013-01-01')"],
            [Schema::TYPE_DATE . ' NOT NULL', 'date NOT NULL'],
            [Schema::TYPE_BINARY, 'blob'],
            [Schema::TYPE_BOOLEAN, 'tinyint(1)'],
            [Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1', 'tinyint(1) NOT NULL DEFAULT 1'],
            [Schema::TYPE_MONEY, 'decimal(19,4)'],
            [Schema::TYPE_MONEY . '(16,2)', 'decimal(16,2)'],
            [Schema::TYPE_MONEY . ' CHECK (value > 0.0)', 'decimal(19,4) CHECK (value > 0.0)'],
            [Schema::TYPE_MONEY . '(16,2) CHECK (value > 0.0)', 'decimal(16,2) CHECK (value > 0.0)'],
            [Schema::TYPE_MONEY . ' NOT NULL', 'decimal(19,4) NOT NULL'],
        ];
    }

    public function testGetColumnType()
    {
        $qb = $this->getQueryBuilder();
        foreach ($this->columnTypes() as $item) {
            list ($column, $expected) = $item;
            $this->assertEquals($expected, $qb->getColumnType($column));
        }
    }

    public function testAddDropPrimaryKey()
    {
        $tableName = 'tbl_constraints';
        $pkeyName = $tableName . "_pkey";

        // ADD
        $qb = $this->getQueryBuilder();
        $qb->db->createCommand()->addPrimaryKey($pkeyName, $tableName, ['id'])->execute();
        $tableSchema = $qb->db->getSchema()->getTableSchema($tableName);
        $this->assertEquals(1, count($tableSchema->primaryKey));

        //DROP
        $qb->db->createCommand()->dropPrimaryKey($pkeyName, $tableName)->execute();
        $qb = $this->getQueryBuilder(); // resets the schema
        $tableSchema = $qb->db->getSchema()->getTableSchema($tableName);
        $this->assertEquals(0, count($tableSchema->primaryKey));
    }
}