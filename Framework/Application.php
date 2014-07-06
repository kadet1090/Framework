<?php

namespace Framework;

use Framework\Http\HttpRouter;

class Application
{
    private $_config;

    /**
     * @var Application $app An application object
     */
    public static $app;

    /**
     * Class constructor.
     *
     * @param array $config Application configuration file in JSON format
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
        $this->_router = new HttpRouter($config);
        self::$app = $this;
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
        $controller->run($action, $request);
    }

    /**
     * Creates application's controller object specified by route
     *
     * @param string $name Controller name
     * @return mixed $name Application's controller object
     * @throws \Exception
     */
    public function controller($name)
    {
        if (!class_exists($name)) {
            throw new \Exception('Requested controller does not exist.');
        }

        return new $name();
    }

    /**
     * @param string $name Name of config to get
     * @return mixed
     * @throws \Exception
     */
    public function getConfig($name)
    {
        if (!array_key_exists($name, $this->_config)) {
            throw new \Exception('There is no configuration for ' . $name . '.');
        }

        return $this->_config[$name];
    }
}