<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kacper
 * Date: 14.08.13
 * Time: 22:39
 * To change this template use File | Settings | File Templates.
 */

namespace PipeCMS\System\Router;

interface RouterInterface
{
    /**
     * Turn request string into array.
     * @param string $string Request string to dispatch.
     * @return Request Request data.
     */
    public function dispatch($string);

    /**
     * Add new route
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
     * Check if request matches specified route.
     * @param string $route Route to match.
     * @param string $request Request string.
     * @return bool If true $request matches given route, else false.
     */
    public function match($route, $request);
}