<?php

namespace Framework\Interfaces;

use Framework\Data\Request;

interface RouterInterface
{
    /**
     * Turns request string into array.
     * @param string $string Request string to dispatch.
     * @return Request Request data.
     */
    public function dispatch($string);

    /**
     * Adds new route
     * @param string $name Route name.
     * @param array $route Route data. <p>
     * array(
     *      'parameters' => array(
     *          'first'  => 'string',
     *          'second' => 'int',
     *          'third'  => '0x[0-9A-Fa-f]+',
     *      ),
     *      'optional' => array(
     *          'fourth' => 'string',
     *          array(
     *              'fifth' => 'string',
     *              'sixth' => 'int'
     *          ),
     *      ),
     *      'parent' => 'parent route',
     * )
     * </p>
     *
     * @return void
     */
    public function addRoute($name, array $route);

    /**
     * Checks if request matches specified route.
     * @param string $route Route to match.
     * @param string $request Request string.
     * @return bool True if $request matches given route, otherwise false.
     */
    public function match($route, $request);
}