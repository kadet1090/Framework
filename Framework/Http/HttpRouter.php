<?php
namespace Framework\Http;

use Framework\Exceptions\RoutingException;
use Framework\Interfaces\RouterInterface;

class HttpRouter implements RouterInterface
{
    /**
     * Stores routes and its regex.
     *
     * @var array[string]
     */
    private $_routes = [];

    /**
     * Url parameters separator
     * Can be regex value, remember to use non-capturing group!
     *
     * @var string
     */
    public $separator = '/';

    /**
     * File extension.
     * Can be regex value, remember to use non-capturing group!
     *
     * @var string
     */
    public $extension = '(:?\.php)?';

    public function __construct($config = null)
    {
        if ($config == null) {
            return;
        }

        if (isset($config['http']['separator'])) {
            $this->separator = $config['http']['separator'];
        }

        if (isset($config['http']['extension'])) {
            $this->separator = $config['http']['extension'];
        }

        if (isset($config['routes'])) {
            foreach ($config['routes'] as $name => $route) {
                if (is_array($route)) {
                    $this->addRoute($name, $route);
                }
            }
        }
    }

    /**
     * Turn request string into Request.
     *
     * @param string $string Request string to dispatch.
     *
     * @return HttpRequest Request data.
     */
    public function dispatch($string)
    {
        $input      = $_POST;
        $parameters = [];

        foreach ($this->_routes as $route) {
            preg_match($route['regex'], $string, $matches);

            $variables = isset($route['data']['variables']) ? $route['data']['variables'] : [];

            array_map(
                function ($var) {
                    if (!is_array($var)) {
                        return $var;
                    }
                    if (isset($var[$_SERVER['REQUEST_METHOD']])) {
                        return $var[$_SERVER['REQUEST_METHOD']];
                    }
                    if (isset($var['default'])) {
                        return $var['default'];
                    }
                    if (isset($var['get'])) {
                        return $var['get'];
                    }

                    throw new RoutingException('This route doesn\'t support this request method');
                },
                $variables
            );

            $parameters = array_merge(
                $parameters,
                array_filter_keys(
                    $matches,
                    function ($key) {
                        return !is_int($key);
                    }
                ),
                $variables
            );
        }

        $parameters = array_merge($parameters, explode('/', $string));

        return new HttpRequest($parameters, $input);
    }

    /**
     * Add new route
     *
     * @param string $name              Route name.
     * @param array  $route             Route data. <p>
     *                                  [
     *                                  'parameters' => [
     *                                  'first'  => 'string',
     *                                  'second' => 'int',
     *                                  'third'  => '0x[0-9A-Fa-f]+',
     *                                  ],
     *                                  'optional' => [
     *                                  'fourth' => 'string',
     *                                  [
     *                                  'fifth' => 'string',
     *                                  'sixth' => 'int'
     *                                  ],
     *                                  ],
     *                                  'parent' => 'parent route',
     *                                  ]
     *                                  </p>
     *
     * @throws \Framework\Exceptions\RoutingException
     * @throws \InvalidArgumentException
     * @return void
     */
    public function addRoute($name, array $route)
    {
        if (!isset($route['optional'])) {
            $route['optional'] = [];
        }
        if (!isset($route['parameters'])) {
            $route['parameters'] = [];
        }

        if (isset($route['parent'])) {
            if (!isset($this->_routes[$route['parent']])) {
                throw new RoutingException('Route depends on nonexistent route.');
            }

            if (isset($this->_routes[$route['parent']]['data']['route'], $route['route'])) {
                $route['route'] = array_merge(
                    $this->_routes[$route['parent']]['data']['route'],
                    $route['route']
                );
            }

            if (isset($this->_routes[$route['parent']]['data']['parameters'], $route['parameters'])) {
                $route['parameters'] = array_merge(
                    $this->_routes[$route['parent']]['data']['parameters'],
                    $route['parameters']
                );
            }

            if (isset($this->_routes[$route['parent']]['data']['optional'], $route['optional'])) {
                $route['optional'] = array_merge(
                    $this->_routes[$route['parent']]['data']['optional'],
                    $route['optional']
                );
            }
        }

        $this->_routes[$name] = [
            'data'  => $route,
            'regex' => $this->_compileRegex($route),
        ];
    }

    /**
     * Check if request matches specified route.
     *
     * @param string $route   Route to match.
     * @param string $request Request string.
     *
     * @return bool If true $request matches given route, else false.
     */
    public function match($route, $request)
    {
        if (!isset($this->_routes[$route])) {
            return false;
        }

        return (bool)preg_match($this->_routes[$route]['regex'], $request);
    }

    /**
     * Turns route into regex.
     *
     * @param array $route Route data.
     *
     * @return string Compiled regex.
     */
    private function _compileRegex(array $route)
    {
        $regex = '';

        if (!empty($route['route'])) {
            $regex .= '/' . implode('/', $route['route']);
        }

        if (isset($route['parameters'])) {
            foreach ($route['parameters'] as $name => $pattern) {
                $regex .= $this->_getRegexParameter($name, $pattern);
            }
        }
        $regex = substr($regex, 1);

        if (isset($route['optional'])) {
            foreach ($route['optional'] as $name => $pattern) {
                $regex .= '(?:$|';
                if (is_array($pattern)) {
                    foreach ($pattern as $subname => $subpattern) {
                        $regex .= $this->_getRegexParameter($subname, $subpattern);
                    }
                } else {
                    $regex .= $this->_getRegexParameter($name, $pattern);
                }
            }
            $regex .= str_repeat(')', count($route['optional']));
        }
        $regex .= $this->extension;

        return '#^' . $regex . '#si';
    }

    /**
     * Gets regex for specified parameter.
     *
     * @param string $name    Parameters' name
     * @param string $pattern Parameters' pattern/type (int is [0-9]+, string [a-zA-Z0-9\-\_\+]+ etc.)
     *
     * @return string
     */
    private function _getRegexParameter($name, $pattern)
    {
        $pattern = str_replace(['int', 'string'], ['[0-9]+', '[a-zA-Z0-9\-\_\+]+'], $pattern);

        return "{$this->separator}(?P<$name>$pattern)";
    }
}