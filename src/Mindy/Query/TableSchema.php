<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Mindy\Query;

use Mindy\Exception\InvalidParamException;
use Mindy\Helper\Traits\Accessors;
use Mindy\Helper\Traits\Configurator;

/**
 * TableSchema represents the metadata of a database table.
 *
 * @property array $columnNames List of column names. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TableSchema
{
    use Accessors, Configurator;

    /**
     * @var string name of the schema that this table belongs to.
     */
    public $schemaName;
    /**
     * @var string name of this table.
     */
    public $name;
    /**
     * @var string[] primary keys of this table.
     */
    public $primaryKey = [];
    /**
     * @var string sequence name for the primary key. Null if no sequence.
     */
    public $sequenceName;
    /**
     * @var array foreign keys of this table. Each array element is of the following structure:
     *
     * ~~~
     * [
     *     'ForeignTableName',
     *     'fk1' => 'pk1',  // pk1 is in foreign table
     *     'fk2' => 'pk2',  // if composite foreign key
     * ]
     * ~~~
     */
    public $foreignKeys = [];
    /**
     * @var ColumnSchema[] column metadata of this table. Each array element is a [[ColumnSchema]] object, indexed by column names.
     */
    public $columns = [];

    /**
     * Gets the named column metadata.
     * This is a convenient method for retrieving a named column even if it does not exist.
     * @param string $name column name
     * @return ColumnSchema metadata of the named column. Null if the named column does not exist.
     */
    public function getColumn($name)
    {
        return $this->hasColumn($name) ? $this->columns[$name] : null;
    }

    /**
     * @param string $name column name
     * @return bool
     */
    public function hasColumn($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * Returns the names of all columns in this table.
     * @return array list of column names
     */
    public function getColumnNames()
    {
        return array_keys($this->columns);
    }

    /**
     * Manually specifies the primary key for this table.
     * @param string|array $keys the primary key (can be composite)
     * @throws InvalidParamException if the specified key cannot be found in the table.
     */
    public function fixPrimaryKey($keys)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $this->primaryKey = $keys;
        foreach ($this->columns as $column) {
            $column->isPrimaryKey = false;
        }
        foreach ($keys as $key) {
            if (isset($this->columns[$key])) {
                $this->columns[$key]->isPrimaryKey = true;
            } else {
                throw new InvalidParamException("Primary key '$key' cannot be found in table '{$this->name}'.");
            }
        }
    }
}
