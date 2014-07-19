<?php

namespace Framework;

class ErrorException extends \ErrorException
{
    protected static $fatalErrors = [
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_CORE_WARNING,
        E_COMPILE_ERROR,
        E_COMPILE_WARNING
    ];

    /**
     * Returns true if error is fatal error.
     *
     * @param array $error Error get from error_get_last()
     * @return bool
     */
    public static function isFatalError(array $error)
    {
        return in_array($error['name'], self::$fatalErrors);
    }
} 