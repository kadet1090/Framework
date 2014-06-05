<?php

namespace Framework;

class Bootstrap
{
    private $_config;

    /**
     * Class constructor.
     *
     * @param array
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * This method is responsible for starting up application
     */
    public function init()
    {
        $router = new \Framework\Http\HttpRouter($this->_config['router']);

        if (isset($_GET['q'])) {
            $request = $router->dispatch($_GET['q']);
        } else {
            $request = $router->dispatch($this->_config['defaultRequest']);
        }

        $controller = ucfirst($request->controller);
        $action = 'action' . ucfirst($request->action);

        $controller = $this->callController($controller);
        $result = $this->callControllerAction($controller, $action);
    }

    public function callController($controller)
    {
        $controller = '\Application\Controller\\' . $controller;

        if (!is_callable($controller)) {
            //throw new \Exception('Requested controller is not callable.');
        } else if (!class_exists($controller)) {
            throw new \Exception('Requested controller does not exist.');
        }
        $c = new $controller();
    }

    // when we finally write Controller class 
    // it's instance will be required here as ...(Controller $controller)
    public function callControllerAction($controller, $action)
    {
        return $controller->{$action}();
    }

}