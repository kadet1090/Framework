<?php

namespace Framework;

use Framework\Http\HttpRouter;
use Framework\View\ViewFactory;

class Application
{
    /**
     * Application main config.
     * @var array
     */
    public $config;

    /**
     * Path to Framework.
     * @var string
     */
    public $frameworkPath;

    /**
     * Main factory for creating apps views.
     * @var View\ViewFactory
     */
    public $viewFactory;

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
        $errorHandler = new ErrorHandler();
        $errorHandler->register();

        $this->frameworkPath = __DIR__;
        $this->config        = $config;
        $this->viewFactory   = new ViewFactory('\\Framework\\Http\\HtmlView', $config); // todo: ViewClass from SAPI or config
        self::$app           = $this;
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

        $action     = 'action' . ucfirst($request->action);
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