<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kacper
 * Date: 14.08.13
 * Time: 22:48
 * To change this template use File | Settings | File Templates.
 */

namespace Framework\Data;

use Framework\Exceptions\IllegalOperationException;

abstract class Request implements \ArrayAccess
{
    /**
     * Gets some pretty good data from user input.
     *
     * @param string $name Index of user data to obtain.
     * @return mixed|null Some user data with index specified in $name, or null if this data wasn't specified by bad user.
     */
    abstract public function __get($name);

    /**
     * Checks if a parameter exists
     * @param mixed $offset The parameter to check
     * @return bool true on success or false on failure.
     *
     * @ignore This magic interface shouldn't be visible in documentation.
     */
    abstract public function offsetExists($offset);

    /**
     * Gets specified parameter
     * @param mixed $offset The parameter to get
     * @return mixed Can return all value types.
     *
     * @ignore This magic interface shouldn't be visible in documentation.
     */
    abstract public function offsetGet($offset);

    /**
     * Sets parameter, in theory but in practice you can't do it.
     * @param mixed $offset Name of parameter to perform value change,
     * @param string $value New value for parameter.
     *
     * But who cares about parameters?
     * @throws IllegalOperationException
     *
     * @ignore This magic interface shouldn't be visible in documentation.
     */
    final public function offsetSet($offset, $value)
    {
        throw new IllegalOperationException('Illegal operation: can\'t change request parameters');
    }

    /**
     * Unsets parameter, in theory but in practice you shouldn't and you even can't do that.
     * @param mixed $offset Name of parameter to unset.
     *
     * But who cares about parameters?
     * @throws IllegalOperationException
     *
     * @ignore This magic interface shouldn't be visible in documentation.
     */
    final public function offsetUnset($offset)
    {
        throw new IllegalOperationException('Illegal operation: can\'t unset request parameters');
    }
}