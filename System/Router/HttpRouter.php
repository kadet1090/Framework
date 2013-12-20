<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kacper
 * Date: 14.08.13
 * Time: 22:39
 * To change this template use File | Settings | File Templates.
 */

namespace PipeCMS\System\Router;

use PipeCMS\System\Exceptions\RoutingException;

class HttpRouter implements RouterInterface
{
    /**
     * Stores routes and its regex.
     * @var array[string]
     */
    private $_routes = array();

    /**
     * Url parameters separator
     * Can be regex value, remember to use non-capturing group!
     * @var string
     */
    public $separator = '/';

    /**
     * File extension.
     * Can be regex value, remember to use non-capturing group!
     * @var string
     */
    public $extension = '(:?\.php)?';

    /**
     * Turn request string into Request.
     * @param string $string Request string to dispatch.
     * @return Request Request data.
     */
    public function dispatch($string)
    {

    }

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
     * @throws RoutingException
     * @return void
     */
    public function addRoute($name, array $route)
    {
        if (!isset($route['optional'])) $route['optional'] = array();
        if (!isset($route['parameters'])) $route['parameters'] = array();

        if (isset($route['parent'])) {
            if (!isset($this->_routes[$route['parent']]))
                throw new RoutingException('Route depends on nonexistent route.');

            $route['parameters'] = array_merge($this->_routes[$route['parent']]['data']['parameters'], $route['parameters']);
            $route['optional'] = array_merge($this->_routes[$route['parent']]['data']['optional'], $route['optional']);
        }

        $this->_routes[$name] = array(
            'data' => $route,
            'regex' => $this->_compileRegex($route)
        );
    }

    /**
     * Check if request matches specified route.
     * @param string $route Route to match.
     * @param string $request Request string.
     * @return bool If true $request matches given route, else false.
     */
    public function match($route, $request)
    {
        if (!isset($this->_routes[$route])) return false;
        return (bool)preg_match($this->_routes[$route]['regex'], $request);
    }

    /**
     * Turns route into regex.
     * @param array $route Route data.
     * @return string Compiled regex.
     */
    private function _compileRegex(array $route)
    {
        $regex = '';
        if (isset($route['parameters'])) {
            foreach ($route['parameters'] as $name => $pattern)
                $regex .= $this->_getRegexParameter($name, $pattern);
        }
        $regex = substr($regex, 1);

        if (isset($route['optional'])) {
            foreach ($route['optional'] as $name => $pattern) {
                $regex .= '(?:$|';
                if (is_array($pattern))
                    foreach ($pattern as $subname => $subpattern)
                        $regex .= $this->_getRegexParameter($subname, $subpattern);
                else
                    $regex .= $this->_getRegexParameter($name, $pattern);
            }
            $regex .= str_repeat(')', count($route['optional']));
        }
        $regex .= $this->extension;
        return '#^' . $regex . '#si';
    }

    /**
     * Gets regex for specified parameter.
     * @param string $name Parameters' name
     * @param string $pattern Parameters' pattern/type (int is [0-9]+, string [a-zA-Z0-9\-\_\+]+ etc.)
     * @return string
     */
    private function _getRegexParameter($name, $pattern)
    {
        $pattern = str_replace(array('int', 'string'), array('[0-9]+', '[a-zA-Z0-9\-\_\+]+'), $pattern);
        return "{$this->separator}(?P<$name>$pattern)";
    }
}