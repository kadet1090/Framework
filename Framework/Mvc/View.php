<?php

namespace Framework\Mvc;

abstract class View
{
    /**
     * Global values for View.
     *
     * @var array
     */
    protected static $_globals = [];

    /**
     * @param string $template Template name, using '/' separator.
     */
    public abstract function __construct($template);

    public abstract function __set($var, $name);

    public abstract function __get($var);

    public static function set($name, $value)
    {
        self::$_globals[$name] = $value;
    }

    public static function get($name)
    {
        return isset(self::$_globals[$name]) ? self::$_globals[$name] : null;
    }

    public abstract function __toString();
}