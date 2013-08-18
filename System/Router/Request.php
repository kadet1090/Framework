<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kacper
 * Date: 14.08.13
 * Time: 22:48
 * To change this template use File | Settings | File Templates.
 */

namespace PipeCMS\System\Router;


class Request implements \ArrayAccess
{
    protected $_parameters;

    public function __construct(array $parameters)
    {
        $this->_parameters = $parameters;
    }

    public function __get($name)
    {
        if (!isset($this->_parameters[$name])) return null;
        return $this->_parameters[$name];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return is_int($offset) && isset($this->_parameters[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (!isset($this[$offset])) return null;

    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @throws \Exception
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception('Illegal operation: can\'t change request parameters');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @throws \Exception
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \Exception('Illegal operation: can\'t unset request parameters');
    }
}