<?php

namespace Framework;

use Framework\Http\HttpRouter;

class Application
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

        $this->_router = new HttpRouter($config);
    }

    /**
     * This method is responsible for starting up application
     */
    public function init()
    {
        $router = new HttpRouter($this->_config['router']);

        if (isset($_GET['q'])) {
            $request = $router->dispatch($_GET['q']);
        } else {
            $request = $router->dispatch($this->_config['defaultRequest']);
        }

        $action = 'action' . ucfirst($request->action);

        $controller = $this->controller($request->controller);
        $result     = $controller->run($action, $request);
    }

    public function controller($name)
    {
        if (!class_exists($name)) {
            throw new \Exception('Requested controller does not exist.');
        }

        return new $name();
    }
}