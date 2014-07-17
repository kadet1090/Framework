<?php

namespace Framework;

use Framework\Http\HttpRouter;

class Application
{
    public $config;

    /**
     * @var Application $app An application object
     */
    public static $app;

    /**
     * Class constructor.
     *
     * @param array $config Application configuration file
     */
    public function __construct(array $config)
    {
        $this->_router = new HttpRouter($config);

        $this->config = $config;
        self::$app    = $this;
    }

    /**
     * This method is responsible for starting up application
     */
    public function init()
    {
        $router = new HttpRouter($this->config['router']);

        if (isset($_GET['q'])) {
            $request = $router->dispatch($_GET['q']);
        } else {
            $request = $router->dispatch($this->config['defaultRequest']);
        }

        $action = 'action' . ucfirst($request->action);

        $controller = $this->controller($request->controller);
        $controller->run($action, $request);
    }

    /**
     * Creates application's controller object specified by route
     *
     * @param string $name Controller name
     *
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
}